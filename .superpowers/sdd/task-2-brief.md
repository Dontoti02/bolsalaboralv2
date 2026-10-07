### Task 2: Importador con columna PROGRAMA DE ESTUDIO + warnings (TDD)

**Files:**
- Create: `tests/Feature/UsersImportTest.php`
- Modify: `app/Imports/UsersImport.php`
- Modify: `app/Http/Controllers/UserController.php:552-603` (mÃ©todo `import`)

**Interfaces:**
- Consumes: tabla `person.study_program_id` (nullable, FK a `study_programs`), tabla `study_programs` (`name`, `is_active`), ruta `POST /admin/users/import` con middleware `role:1`.
- Produces: `UsersImport::$warnings` (array de strings), JSON de Ã©xito con clave `warnings` y `message` resumiendo advertencias. Ninguna otra tarea lo consume.

- [ ] **Step 1: Escribir el test fallido**

Crear `tests/Feature/UsersImportTest.php`:

```php
<?php

namespace Tests\Feature;

use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class UsersImportTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('user') || !Schema::hasTable('study_programs')) {
            $this->markTestSkipped('Las tablas "user" o "study_programs" no existen en este entorno.');
        }
    }

    private function makeXlsx(array $rows): string
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        foreach ($rows as $r => $row) {
            foreach ($row as $c => $value) {
                $sheet->setCellValueByColumnAndRow($c + 1, $r + 1, $value);
            }
        }

        $path = tempnam(sys_get_temp_dir(), 'users_import_') . '.xlsx';
        (new Xlsx($spreadsheet))->save($path);
        $spreadsheet->disconnectActiveSheet();

        return $path;
    }

    private function header(): array
    {
        return ['NOMBRES', 'TIPO DE DOCUMENTO', 'DOCUMENTO', 'TELÃ‰FONO', 'EMAIL', 'ROL', 'PROGRAMA DE ESTUDIO'];
    }

    private function admin(): User
    {
        $admin = User::where('rol_id', 1)->first();

        return $admin ?: User::factory()->create(['rol_id' => 1]);
    }

    private function import(array $rows)
    {
        $path = $this->makeXlsx($rows);

        return $this->actingAs($this->admin())->post('/admin/users/import', [
            'file' => new UploadedFile(
                $path,
                'usuarios.xlsx',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                null,
                true
            ),
        ]);
    }

    public function test_import_assigns_study_program_to_student_case_insensitive()
    {
        $program = StudyProgram::firstOrCreate(
            ['name' => 'IngenierÃ­a de Sistemas'],
            ['is_active' => true]
        );
        $email = 'import.student.' . uniqid() . '@talentum.edu.pe';

        $response = $this->import([
            $this->header(),
            ['Estudiante Import Test', 'DNI', '76543210', '987654321', $email, 'ESTUDIANTE', '  INGENIERÃA de SISTEMAS '],
        ]);

        $response->assertStatus(200)->assertJsonPath('success', true);

        $user = User::where('email', $email)->first();
        $this->assertNotNull($user, 'El usuario debiÃ³ importarse.');
        $this->assertSame($program->id, $user->person->study_program_id);
    }

    public function test_import_rejects_student_without_program()
    {
        $email = 'import.noprog.' . uniqid() . '@talentum.edu.pe';

        $response = $this->import([
            $this->header(),
            ['Estudiante Sin Programa', 'DNI', '76543211', '987654321', $email, 'ESTUDIANTE', ''],
        ]);

        $response->assertStatus(422);
        $this->assertStringContainsString('obligatorio para estudiantes y egresados', $response->json('message'));
        $this->assertNull(User::where('email', $email)->first(), 'La fila no debiÃ³ importarse.');
    }

    public function test_import_warns_and_creates_user_when_program_does_not_exist()
    {
        $email = 'import.unknownprog.' . uniqid() . '@talentum.edu.pe';

        $response = $this->import([
            $this->header(),
            ['Egresado Programa Raro', 'DNI', '76543212', '987654322', $email, 'EGRESADO', 'Carrera Inexistente XYZ'],
        ]);

        $response->assertStatus(200)->assertJsonPath('success', true);
        $this->assertNotEmpty($response->json('warnings'), 'DebiÃ³ generar advertencia.');
        $this->assertStringContainsString(
            'Carrera Inexistente XYZ',
            implode(' ', $response->json('warnings'))
        );

        $user = User::where('email', $email)->first();
        $this->assertNotNull($user, 'El usuario debiÃ³ importarse a pesar de la advertencia.');
        $this->assertNull($user->person->study_program_id);
    }

    public function test_import_company_ignores_program_column()
    {
        $email = 'import.company.' . uniqid() . '@ejemplo.com';

        $response = $this->import([
            $this->header(),
            ['Empresa Import SAC', 'RUC', '20551234567', '955111222', $email, 'EMPRESA', 'AdministraciÃ³n'],
        ]);

        $response->assertStatus(200)->assertJsonPath('success', true);
        $this->assertEmpty($response->json('warnings'), 'A empresas no debe advertÃ­rsele por programa.');

        $user = User::where('email', $email)->first();
        $this->assertNotNull($user);
        $this->assertNotNull($user->company_id);
    }
}
```

- [ ] **Step 2: Ejecutar el test y verificar que falla**

Run: `php artisan test --filter=UsersImportTest`
Expected: FALLA. `test_import_assigns_study_program_to_student_case_insensitive` (programa no asignado), `test_import_rejects_student_without_program` (recibe 200 en vez de 422), `test_import_warns_and_creates_user_when_program_does_not_exist` (`warnings` vacÃ­o). El test de EMPRESA puede pasar ya.

- [ ] **Step 3: Implementar en `app/Imports/UsersImport.php`**

1. AÃ±adir import (junto a los existentes):

```php
use App\Models\StudyProgram;
```

2. AÃ±adir propiedades junto a las existentes (despuÃ©s de `protected $hashedPasswordCache = [];`):

```php
    public $warnings = [];
    protected $studyProgramsByNormalizedName;
```

3. Dentro de `collection()`, justo despuÃ©s de precargar `$this->existingRucs` (bloque `if ($this->existingRucs === null) {...}`), aÃ±adir:

```php
        // Eager load study programs (normalized name => id) once across all chunks
        if ($this->studyProgramsByNormalizedName === null) {
            $this->studyProgramsByNormalizedName = StudyProgram::pluck('id', 'name')
                ->mapWithKeys(function ($id, $name) {
                    return [mb_strtolower(trim((string) $name)) => $id];
                })
                ->toArray();
        }
```

4. Leer la columna nueva, justo despuÃ©s de `$roleStr = ...` (lÃ­nea ~69):

```php
            $programRaw = trim((string) ($row[6] ?? ''));
```

5. Tras el bloque de validaciÃ³n de `docNumber` vacÃ­o (lÃ­nea ~116-119) y **antes** de `// Mark as taken in-memory`, insertar:

```php
            $studyProgramId = null;
            if (in_array($roleId, [3, 5], true)) {
                if ($programRaw === '') {
                    $this->errors[] = "Fila $lineNum: El programa de estudio es obligatorio para estudiantes y egresados.";
                    continue;
                }

                $studyProgramId = $this->studyProgramsByNormalizedName[mb_strtolower($programRaw)] ?? null;
                if ($studyProgramId === null) {
                    $this->warnings[] = "Fila $lineNum: el programa \"{$programRaw}\" no existe en Mantenedores; el usuario se importÃ³ sin programa.";
                }
            }
```

6. AÃ±adir el campo al array de personas (dentro del `else` de `if ($roleId == 4)`, en `$peopleToInsert[] = [...]`):

```php
                $peopleToInsert[] = [
                    'document_type' => $docType,
                    'document_number' => $dni,
                    'names' => $names,
                    'phone' => $phoneFormatted,
                    'email' => $email,
                    'study_program_id' => $studyProgramId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
```

- [ ] **Step 4: Implementar en `UserController::import()`**

Reemplazar el bloque final del `try` (desde `$errors = $import->errors;` hasta el `return response()->json` de Ã©xito) por:

```php
            $errors = $import->errors;
            $warnings = $import->warnings;
            $importedUserIds = $import->importedUserIds;

            if (count($errors) > 0 && count($importedUserIds) == 0) {
                return response()->json([
                    'success' => false,
                    'message' => implode("\n", $errors)
                ], 422);
            }

            $importedUsers = [];
            if (!empty($importedUserIds)) {
                $importedUsers = User::with(['person', 'company'])
                    ->whereIn('id', $importedUserIds)
                    ->get();
            }

            $message = 'ImportaciÃ³n completada. ' . count($importedUsers) . ' usuarios registrados.';
            if (!empty($warnings)) {
                $shown = array_slice($warnings, 0, 5);
                $extra = count($warnings) > 5 ? ' y ' . (count($warnings) - 5) . ' mÃ¡s' : '';
                $message .= ' ' . count($warnings) . ' advertencia(s): ' . implode(' | ', $shown) . $extra;
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'users' => $importedUsers,
                'errors' => $errors,
                'warnings' => $warnings
            ]);
```

- [ ] **Step 5: Ejecutar el test y verificar que pasa**

Run: `php artisan test --filter=UsersImportTest`
Expected: `4 passed` (o SKIP si el entorno no tiene las tablas; en ese caso verificar con la BD real de XAMPP, ver Step 6).

- [ ] **Step 6: Verificar contra la BD real (si los tests se saltan)**

Run:
```powershell
php artisan tinker --execute="echo App\Models\StudyProgram::count() . ' programas; ' . App\Models\User::count() . ' usuarios';"
```
Expected: conteos > 0 (BD de XAMPP activa). Si los tests se saltaron por esquema, repetir Step 5 apuntando `DB_DATABASE` a esa BD o validar manualmente con la plantilla en la Task 3/4.

- [ ] **Step 7: Correr la suite completa**

Run: `php artisan test`
Expected: sin errores nuevos (los tests previos existentes siguen pasando o en su estado actual).

---

