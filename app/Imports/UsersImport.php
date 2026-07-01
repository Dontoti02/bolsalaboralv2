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
    protected $existingPeopleDnis;
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
        ];

        // Eager load existing emails once across all chunks
        if ($this->existingEmails === null) {
            $this->existingEmails = User::pluck('email')->flip()->toArray();
        }

        // Eager load existing RUCs once across all chunks
        if ($this->existingRucs === null) {
            $this->existingRucs = Company::pluck('id', 'ruc')->toArray();
        }

        // Eager load existing DNI/document numbers once across all chunks
        if ($this->existingPeopleDnis === null) {
            $this->existingPeopleDnis = Person::pluck('id', 'document_number')->toArray();
        }

        $companiesToInsert = [];
        $peopleToInsert = [];
        $usersToPrepare = [];
        
        $chunkNewRucs = [];
        $chunkNewDnis = [];

        $isHeader = $this->isFirstChunk;
        $this->isFirstChunk = false;

        foreach ($rows as $index => $row) {
            if ($isHeader) {
                $isHeader = false;
                continue;
            }

            // Access columns by numeric indexes
            $names = trim($row[0] ?? '');
            $docType = strtoupper(trim($row[1] ?? 'DNI'));
            $docNumber = trim($row[2] ?? '');
            $phone = trim($row[3] ?? '');
            $email = trim($row[4] ?? '');
            $roleStr = strtoupper(trim($row[5] ?? 'ESTUDIANTE'));

            if (empty($names) && empty($email)) {
                continue;
            }

            $lineNum = $index + 1;

            if (empty($names) || empty($email)) {
                $this->errors[] = "Fila $lineNum: Nombres y Email son campos obligatorios.";
                continue;
            }

            if (isset($this->existingEmails[$email])) {
                $this->errors[] = "Fila $lineNum: El correo $email ya está registrado en el sistema.";
                continue;
            }

            // Mark as taken in-memory
            $this->existingEmails[$email] = true;

            $roleId = $rolesMap[$roleStr] ?? 3;

            if (!in_array($docType, ['DNI', 'RUC', 'CE'])) {
                $docType = ($roleId == 4) ? 'RUC' : 'DNI';
            }

            if (in_array($roleId, [2, 3], true) && ($docType !== 'DNI' || !preg_match('/^\d{8}$/', $docNumber))) {
                $this->errors[] = "Fila $lineNum: Docentes y estudiantes deben tener un DNI válido de 8 dígitos.";
                continue;
            }

            if ($roleId == 4 && ($docType !== 'RUC' || !preg_match('/^\d{11}$/', $docNumber))) {
                $this->errors[] = "Fila $lineNum: Las empresas deben tener un RUC válido de 11 dígitos.";
                continue;
            }

            $phoneFormatted = substr($phone, 0, 9);
            
            $passKey = $docNumber ?: '00000000';
            if (!isset($this->hashedPasswordCache[$passKey])) {
                $this->hashedPasswordCache[$passKey] = Hash::make($passKey);
            }
            $hashedPassword = $this->hashedPasswordCache[$passKey];

            if ($roleId == 4) {
                $ruc = $docNumber ?: '00000000000';
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
                $dni = $docNumber ?: '00000000';
                // Only insert person if they don't already exist and are not queued in this chunk
                if (!isset($this->existingPeopleDnis[$dni]) && !isset($chunkNewDnis[$dni])) {
                    $peopleToInsert[] = [
                        'document_type' => $docType,
                        'document_number' => $dni,
                        'names' => $names,
                        'phone' => $phoneFormatted,
                        'email' => $email,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $chunkNewDnis[$dni] = true;
                }
            }

            $usersToPrepare[] = [
                'email' => $email,
                'password' => $hashedPassword,
                'rol_id' => $roleId,
                'is_active' => true,
                'attempts' => 0,
                'role_id_flag' => $roleId,
                'doc_number' => ($roleId == 4) ? ($docNumber ?: '00000000000') : ($docNumber ?: '00000000'),
            ];
        }

        if (empty($usersToPrepare)) {
            return;
        }

        DB::transaction(function () use ($companiesToInsert, $peopleToInsert, $usersToPrepare) {
            // 1. Bulk insert companies
            if (!empty($companiesToInsert)) {
                DB::table('job_opportunity_company')->insert($companiesToInsert);
                
                $companyRucs = array_column($companiesToInsert, 'ruc');
                $newCompanyMap = DB::table('job_opportunity_company')
                    ->whereIn('ruc', $companyRucs)
                    ->pluck('id', 'ruc')
                    ->toArray();
                
                $this->existingRucs = array_merge($this->existingRucs, $newCompanyMap);
            }

            // 2. Bulk insert people
            if (!empty($peopleToInsert)) {
                DB::table('person')->insert($peopleToInsert);
                
                $personDnis = array_column($peopleToInsert, 'document_number');
                $newPersonMap = DB::table('person')
                    ->whereIn('document_number', $personDnis)
                    ->pluck('id', 'document_number')
                    ->toArray();
                
                $this->existingPeopleDnis = array_merge($this->existingPeopleDnis, $newPersonMap);
            }

            // 3. Finalize user records
            $usersToInsert = [];
            foreach ($usersToPrepare as $up) {
                $email = $up['email'];
                $roleId = $up['role_id_flag'];
                $doc = $up['doc_number'];
                
                $usersToInsert[] = [
                    'email' => $email,
                    'password' => $up['password'],
                    'rol_id' => $up['rol_id'],
                    'is_active' => $up['is_active'],
                    'attempts' => $up['attempts'],
                    'company_id' => ($roleId == 4) ? ($this->existingRucs[$doc] ?? null) : null,
                    'person_id' => ($roleId == 4) ? null : ($this->existingPeopleDnis[$doc] ?? null),
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
