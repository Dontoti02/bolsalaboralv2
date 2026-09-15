<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Company;
use App\Models\Person;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class UsersImport implements ToCollection, WithChunkReading
{
    public $errors = [];
    public $importedUserIds = [];
    protected $existingEmails;
    protected $existingRucs;
    protected $isFirstChunk = true;
    protected $hashedPasswordCache = [];

    public function collection(Collection $rows)
    {
        // Set dynamic limits for large operations
        set_time_limit(600);
        ini_set('memory_limit', '512M');

        $rolesMap = [
            'ADMINISTRADOR' => 1,
            'DOCENTE' => 2,
            'ESTUDIANTE' => 3,
            'EMPRESA' => 4,
            'EGRESADO' => 5,
        ];

        // Eager load existing emails once across all chunks
        if ($this->existingEmails === null) {
            $this->existingEmails = User::pluck('email')->flip()->toArray();
        }

        // Eager load existing RUCs once across all chunks (ruc => id)
        if ($this->existingRucs === null) {
            $this->existingRucs = Company::pluck('id', 'ruc')->toArray();
        }

        $companiesToInsert = [];
        $peopleToInsert = [];
        $usersToPrepare = [];
        
        $chunkNewRucs = [];
        $chunkPersonEmails = [];

        $isHeader = $this->isFirstChunk;
        $this->isFirstChunk = false;

        foreach ($rows as $index => $row) {
            if ($isHeader) {
                $isHeader = false;
                continue;
            }

            // Access columns by numeric indexes
            $names = trim((string)($row[0] ?? ''));
            $docType = strtoupper(trim((string)($row[1] ?? 'DNI')));
            $docNumber = trim((string)($row[2] ?? ''));
            $phone = trim((string)($row[3] ?? ''));
            $email = strtolower(trim((string)($row[4] ?? '')));
            $roleStr = strtoupper(trim((string)($row[5] ?? 'ESTUDIANTE')));

            if (empty($names) && empty($email)) {
                continue;
            }

            $lineNum = $index + 1;

            if (empty($names) || empty($email)) {
                $this->errors[] = "Fila $lineNum: Nombres y Email son campos obligatorios.";
                continue;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->errors[] = "Fila $lineNum: El formato del correo $email no es válido.";
                continue;
            }

            if (isset($this->existingEmails[$email])) {
                $this->errors[] = "Fila $lineNum: El correo $email ya está registrado en el sistema.";
                continue;
            }

            $roleId = $rolesMap[$roleStr] ?? 3;

            if (!in_array($docType, ['DNI', 'RUC', 'CE'])) {
                $docType = ($roleId == 4) ? 'RUC' : 'DNI';
            }

            // Defensive padding for numeric document numbers stripped of leading zeros by Excel
            if ($docType === 'DNI' && is_numeric($docNumber) && strlen($docNumber) < 8) {
                $docNumber = str_pad($docNumber, 8, '0', STR_PAD_LEFT);
            }
            if ($roleId == 4 && is_numeric($docNumber) && strlen($docNumber) < 11) {
                $docNumber = str_pad($docNumber, 11, '0', STR_PAD_LEFT);
            }

            if (in_array($roleId, [2, 3, 5], true) && ($docType !== 'DNI' || !preg_match('/^\d{8}$/', $docNumber))) {
                $this->errors[] = "Fila $lineNum: Docentes, estudiantes y egresados deben tener un DNI válido de 8 dígitos.";
                continue;
            }

            if ($roleId == 4 && ($docType !== 'RUC' || !preg_match('/^\d{11}$/', $docNumber))) {
                $this->errors[] = "Fila $lineNum: Las empresas deben tener un RUC válido de 11 dígitos.";
                continue;
            }

            if (empty($docNumber)) {
                $this->errors[] = "Fila $lineNum: El número de documento es obligatorio.";
                continue;
            }

            // Mark as taken in-memory
            $this->existingEmails[$email] = true;

            $phoneFormatted = substr($phone, 0, 9);
            
            $passKey = $docNumber;
            if (!isset($this->hashedPasswordCache[$passKey])) {
                $this->hashedPasswordCache[$passKey] = Hash::make($passKey);
            }
            $hashedPassword = $this->hashedPasswordCache[$passKey];

            if ($roleId == 4) {
                $ruc = $docNumber;
                // Only insert company if it doesn't already exist and is not queued in this chunk
                if (!isset($this->existingRucs[$ruc]) && !isset($chunkNewRucs[$ruc])) {
                    $companiesToInsert[] = [
                        'name' => $names,
                        'ruc' => $ruc,
                        'email' => $email,
                        'phone' => $phoneFormatted,
                        'mailbox' => $email,
                        'is_verified' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $chunkNewRucs[$ruc] = true;
                }
            } else {
                $dni = $docNumber;
                $peopleToInsert[] = [
                    'document_type' => $docType,
                    'document_number' => $dni,
                    'names' => $names,
                    'phone' => $phoneFormatted,
                    'email' => $email,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $chunkPersonEmails[] = $email;
            }

            $usersToPrepare[] = [
                'email' => $email,
                'password' => $hashedPassword,
                'rol_id' => $roleId,
                'is_active' => true,
                'attempts' => 0,
                'role_id_flag' => $roleId,
                'doc_number' => $docNumber,
                'names' => $names,
                'phone' => $phoneFormatted,
            ];
        }

        if (empty($usersToPrepare)) {
            return;
        }

        DB::transaction(function () use ($companiesToInsert, $peopleToInsert, $usersToPrepare, $chunkPersonEmails) {
            // 1. Process companies
            $companyIdMapByEmail = [];
            if (!empty($companiesToInsert)) {
                DB::table('job_opportunity_company')->insert($companiesToInsert);
                
                $companyRucs = array_column($companiesToInsert, 'ruc');
                $newCompanyMap = DB::table('job_opportunity_company')
                    ->whereIn('ruc', $companyRucs)
                    ->pluck('id', 'ruc')
                    ->toArray();
                
                // Merge without array_merge to preserve keys
                foreach ($newCompanyMap as $r => $cid) {
                    $this->existingRucs[(string)$r] = $cid;
                }
            }

            // Map companies by email for users in this chunk
            foreach ($usersToPrepare as $up) {
                if ($up['role_id_flag'] == 4) {
                    $doc = $up['doc_number'];
                    if (isset($this->existingRucs[$doc])) {
                        $companyId = $this->existingRucs[$doc];
                        $companyIdMapByEmail[$up['email']] = $companyId;
                        
                        // Update company name/phone if already existing
                        DB::table('job_opportunity_company')
                            ->where('id', $companyId)
                            ->update([
                                'name' => $up['names'],
                                'phone' => $up['phone'],
                                'mailbox' => $up['email'],
                                'updated_at' => now(),
                            ]);
                    }
                }
            }

            // 2. Process people (roles 1, 2, 3)
            $personIdMapByEmail = [];
            if (!empty($chunkPersonEmails)) {
                $existingPersonMap = DB::table('person')
                    ->whereIn('email', $chunkPersonEmails)
                    ->pluck('id', 'email')
                    ->toArray();

                $toInsert = [];
                foreach ($peopleToInsert as $p) {
                    $pEmail = $p['email'];
                    if (isset($existingPersonMap[$pEmail])) {
                        $pId = $existingPersonMap[$pEmail];
                        DB::table('person')->where('id', $pId)->update([
                            'document_type' => $p['document_type'],
                            'document_number' => $p['document_number'],
                            'names' => $p['names'],
                            'phone' => $p['phone'],
                            'updated_at' => now(),
                        ]);
                        $personIdMapByEmail[$pEmail] = $pId;
                    } else {
                        $toInsert[] = $p;
                    }
                }

                if (!empty($toInsert)) {
                    DB::table('person')->insert($toInsert);

                    $insertedEmails = array_column($toInsert, 'email');
                    $newPersonMap = DB::table('person')
                        ->whereIn('email', $insertedEmails)
                        ->pluck('id', 'email')
                        ->toArray();

                    foreach ($newPersonMap as $em => $pid) {
                        $personIdMapByEmail[$em] = $pid;
                    }
                }
            }

            // 3. Finalize user records
            $usersToInsert = [];
            foreach ($usersToPrepare as $up) {
                $email = $up['email'];
                $roleId = $up['role_id_flag'];
                
                $usersToInsert[] = [
                    'email' => $email,
                    'password' => $up['password'],
                    'rol_id' => $up['rol_id'],
                    'is_active' => $up['is_active'],
                    'attempts' => $up['attempts'],
                    'company_id' => ($roleId == 4) ? ($companyIdMapByEmail[$email] ?? null) : null,
                    'person_id' => ($roleId == 4) ? null : ($personIdMapByEmail[$email] ?? null),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // 4. Bulk insert users
            $userIdMap = [];
            if (!empty($usersToInsert)) {
                DB::table('user')->insert($usersToInsert);
                
                $userEmails = array_column($usersToInsert, 'email');
                $userIdMap = DB::table('user')
                    ->whereIn('email', $userEmails)
                    ->pluck('id', 'email')
                    ->toArray();
                
                // Merge new IDs into class array
                $this->importedUserIds = array_merge($this->importedUserIds, array_values($userIdMap));
            }

            // 5. Bulk insert rol_user
            $rolUserToInsert = [];
            foreach ($usersToInsert as $ui) {
                $email = $ui['email'];
                $userId = $userIdMap[$email] ?? null;
                if ($userId) {
                    $rolUserToInsert[] = [
                        'rol_id' => $ui['rol_id'],
                        'user_id' => $userId
                    ];
                }
            }
            if (!empty($rolUserToInsert)) {
                DB::table('rol_user')->insert($rolUserToInsert);
            }
        });
    }

    public function chunkSize(): int
    {
        return 250;
    }
}
