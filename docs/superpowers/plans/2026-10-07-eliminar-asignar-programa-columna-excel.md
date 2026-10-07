# Eliminar módulo Asignar Programa + columna PROGRAMA DE ESTUDIO en plantilla Excel — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Eliminar por completo el módulo administrativo "Asignar Programa de Estudio" y añadir la columna `PROGRAMA DE ESTUDIO` a la plantilla Excel de importación (obligatoria para ESTUDIANTE/EGRESADO, ignorada en otros roles).

**Architecture:** Se borra el módulo ASP (vista + JS + rutas + controller). El importador `UsersImport` pasa a leer la columna índice 6, resolver `study_program_id` por nombre case-insensitive contra `study_programs` y reportar advertencias nuevas (`warnings`), devueltas por `UserController::import()` en el JSON de éxito. La plantilla `public/assets/Importacion de usuarios.xlsx` se regenera con PhpSpreadsheet.

**Tech Stack:** Laravel 10/11 (PHP 8.2), Maatwebsite/Excel + PhpSpreadsheet, Blade + JS vanilla en `dashboard.blade.php`, PHPUnit (`php artisan test`).

## Global Constraints

- **No se hacen commits** salvo petición explícita del usuario (regla de la sesión).
- No tocar nada fuera del alcance: registro/login, reportes, mantenedores, CRUD de programas y oferta laboral quedan intactos.
- Columna nueva = **índice 6** (columna G), después de ROL (índice 5).
- Roles con programa obligatorio: `rol_id` 3 (ESTUDIANTE) y 5 (EGRESADO). Otros roles: columna ignorada.
- Programa vacío (roles 3/5) → **error de fila** (no se importa). Programa con nombre no registrado → **se importa + advertencia**.
- Coincidencia de nombre: `trim` + `mb_strtolower` (case-insensitive).
- Ruta de la plantilla: `public/assets/Importacion de usuarios.xlsx` (link de descarga `/assets/Importacion de usuarios.xlsx`).
- Comandos se ejecutan desde `C:\xampp\htdocs\bolsalaboralv2` (PHP 8.2.12 CLI disponible).

---

### Task 1: Eliminar el módulo Asignar Programa de Estudio

**Files:**
- Modify: `resources/views/admin/dashboard.blade.php` (líneas ~135, 2653-2766, 3774-3811, 3820, 3903-3906, 3937, 7940-8265)
- Modify: `routes/web.php:88-89`
- Modify: `app/Http/Controllers/AdminController.php` (imports línea 6 y 8; `$studyPrograms` línea 208-209, 225, 241, 265; métodos líneas 350-412)

**Interfaces:**
- Consumes: nada.
- Produces: rutas `admin.users.search-persons` y `admin.study-programs.assign` eliminadas; vista sin panel `panel-assign-study-program` ni funciones `asp*`. La Task 2 no depende de esto.

- [ ] **Step 1: Quitar la entrada del menú lateral**

En `resources/views/admin/dashboard.blade.php` eliminar la línea exacta:

```blade
                        ['key' => 'assign-study-program', 'icon' => 'school', 'label' => 'Asignar Programa'],
```

- [ ] **Step 2: Eliminar el panel HTML completo**

En `dashboard.blade.php` eliminar desde la línea 2653 hasta la 2766 inclusive. Anclagos:
- Inicio (único): `            {{-- ================= PANEL: ASIGNAR PROGRAMA DE ESTUDIO ================= --}}`
- Fin (único): las 4 últimas líneas del bloque:

```blade
                <script>
                    window.aspStudyPrograms = @json($studyPrograms->values());
                </script>
            </div>
```

Nota: justo después debe quedar una línea en blanco seguida de `        </div>` y `    </main>` (no eliminar esas).

- [ ] **Step 3: Eliminar el modal de confirmación masiva**

En `dashboard.blade.php` eliminar desde (anclago de inicio único):

```blade
    <!-- ================= MODAL: CONFIRMACIÓN ASIGNACIÓN EN MASA ================= -->
```

hasta (anclago de fin único):

```blade
                <button id="btn-confirm-asp-bulk" type="button" onclick="executeAspBulkAssign()"
                    class="px-6 py-2.5 bg-primary text-on-primary font-label-md text-label-md rounded-xl hover:opacity-95 shadow-sm transition-all font-semibold flex items-center gap-1">
                    <span class="material-symbols-outlined text-[18px]">done</span>
                    Confirmar
                </button>
            </div>
        </div>
    </div>
```

No eliminar la línea `    <!-- Toast system → ui-alerts.js (renderizado dinámicamente) -->` que sigue.

- [ ] **Step 4: Quitar la pestaña de `ADMIN_VALID_TABS`**

Línea ~3820, cambiar:

```js
        const ADMIN_VALID_TABS = ['dashboard', 'users', 'settings', 'offers', 'companies-manage', 'companies-register', 'applications', 'reports', 'maintainers', 'support', 'assign-study-program'];
```

por:

```js
        const ADMIN_VALID_TABS = ['dashboard', 'users', 'settings', 'offers', 'companies-manage', 'companies-register', 'applications', 'reports', 'maintainers', 'support'];
```

- [ ] **Step 5: Quitar la rama de `switchTab()`**

Eliminar:

```js
            } else if (tabId === 'assign-study-program') {
                targetPanelId = 'panel-assign-study-program';
                aspLoadUsers();
            }
```

(dejando que la rama anterior de `'support'` cierre con su `}`).

- [ ] **Step 6: Quitar el título de la pestaña**

En `tabTitles` (~3937) cambiar:

```js
                'support': 'Soporte',
                'assign-study-program': 'Asignar Programa de Estudio'
```

por:

```js
                'support': 'Soporte'
```

- [ ] **Step 7: Eliminar el bloque JS del módulo ASP**

En `dashboard.blade.php` eliminar desde la línea 7940 hasta la 8265 inclusive. Anclagos:
- Inicio (único):

```js
        // =====================================================================
        // MÓDULO: ASIGNAR PROGRAMA DE ESTUDIO (ASP)
        // =====================================================================
```

- Fin: termina justo antes de la línea 8266, que es un separador `        // =====...` seguido de `        // ==========================================` y `        // MODULE: REPORTES DE BOLSA LABORAL`. Verificar que tras el borrado aparezcan juntos:

```js
        // =====================================================================

        // ==========================================
        // MODULE: REPORTES DE BOLSA LABORAL
        // ==========================================
```

El bloque eliminado contiene: variables `aspAllUsers`, `aspFilteredUsers`, `aspCurrentPage`, `aspPageSize`, `aspSelectedPersonIds` y las funciones `aspLoadUsers`, `aspUpdateStats`, `aspToggleSelectAll`, `aspToggleUserSelect`, `aspRenderTable`, `aspChangePage`, `aspAssign`, `aspEscapeHtml`, `toggleAspBulkModal`, `aspBulkAssign`, `executeAspBulkAssign`.

- [ ] **Step 8: Eliminar las rutas**

En `routes/web.php` eliminar las líneas 88-89:

```php
        Route::get('/admin/users/search-persons', [App\Http\Controllers\AdminController::class, 'searchPersonUsers'])->name('admin.users.search-persons');
        Route::post('/admin/study-programs/assign', [App\Http\Controllers\AdminController::class, 'assignStudyProgram'])->name('admin.study-programs.assign');
```

- [ ] **Step 9: Limpiar `AdminController.php`**

1. Eliminar los imports sin uso (líneas 6 y 8):

```php
use App\Models\Person;
use App\Models\StudyProgram;
```

2. En `dashboard()`: eliminar el comentario y la línea:

```php
            // Cargar programas de estudio para el select de asignación
            $studyPrograms = StudyProgram::orderBy('name')->get();
```

3. Eliminar `'studyPrograms',` de los dos `compact(...)` (líneas ~225 y ~265).
4. En el bloque `catch`: eliminar `$studyPrograms = collect();`.
5. Eliminar ambos métodos completos con sus docblocks (líneas 350-412): `searchPersonUsers()` y `assignStudyProgram()`. Debe quedar el cierre de clase `}` como última línea del archivo.

- [ ] **Step 10: Verificar que no quedan referencias**

Run:
```powershell
php artisan route:list | findstr /I "search-persons study-programs/assign"
```
Expected: sin salida.

```powershell
rg -n "assign-study-program|aspLoadUsers|aspBulkAssign|asp-render|asp-table-body|search-persons|searchPersonUsers|assignStudyProgram" resources routes app
```
Expected: sin resultados en `resources`, `routes`, `app`.

```powershell
php artisan route:clear; php artisan view:clear
```
Expected: `Route cache cleared successfully.` / `Compiled views cleared successfully.`

---

### Task 2: Importador con columna PROGRAMA DE ESTUDIO + warnings (TDD)

**Files:**
- Create: `tests/Feature/UsersImportTest.php`
- Modify: `app/Imports/UsersImport.php`
- Modify: `app/Http/Controllers/UserController.php:552-603` (método `import`)

**Interfaces:**
- Consumes: tabla `person.study_program_id` (nullable, FK a `study_programs`), tabla `study_programs` (`name`, `is_active`), ruta `POST /admin/users/import` con middleware `role:1`.
- Produces: `UsersImport::$warnings` (array de strings), JSON de éxito con clave `warnings` y `message` resumiendo advertencias. Ninguna otra tarea lo consume.

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
        return ['NOMBRES', 'TIPO DE DOCUMENTO', 'DOCUMENTO', 'TELÉFONO', 'EMAIL', 'ROL', 'PROGRAMA DE ESTUDIO'];
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
            ['name' => 'Ingeniería de Sistemas'],
            ['is_active' => true]
        );
        $email = 'import.student.' . uniqid() . '@talentum.edu.pe';

        $response = $this->import([
            $this->header(),
            ['Estudiante Import Test', 'DNI', '76543210', '987654321', $email, 'ESTUDIANTE', '  INGENIERÍA de SISTEMAS '],
        ]);

        $response->assertStatus(200)->assertJsonPath('success', true);

        $user = User::where('email', $email)->first();
        $this->assertNotNull($user, 'El usuario debió importarse.');
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
        $this->assertNull(User::where('email', $email)->first(), 'La fila no debió importarse.');
    }

    public function test_import_warns_and_creates_user_when_program_does_not_exist()
    {
        $email = 'import.unknownprog.' . uniqid() . '@talentum.edu.pe';

        $response = $this->import([
            $this->header(),
            ['Egresado Programa Raro', 'DNI', '76543212', '987654322', $email, 'EGRESADO', 'Carrera Inexistente XYZ'],
        ]);

        $response->assertStatus(200)->assertJsonPath('success', true);
        $this->assertNotEmpty($response->json('warnings'), 'Debió generar advertencia.');
        $this->assertStringContainsString(
            'Carrera Inexistente XYZ',
            implode(' ', $response->json('warnings'))
        );

        $user = User::where('email', $email)->first();
        $this->assertNotNull($user, 'El usuario debió importarse a pesar de la advertencia.');
        $this->assertNull($user->person->study_program_id);
    }

    public function test_import_company_ignores_program_column()
    {
        $email = 'import.company.' . uniqid() . '@ejemplo.com';

        $response = $this->import([
            $this->header(),
            ['Empresa Import SAC', 'RUC', '20551234567', '955111222', $email, 'EMPRESA', 'Administración'],
        ]);

        $response->assertStatus(200)->assertJsonPath('success', true);
        $this->assertEmpty($response->json('warnings'), 'A empresas no debe advertírsele por programa.');

        $user = User::where('email', $email)->first();
        $this->assertNotNull($user);
        $this->assertNotNull($user->company_id);
    }
}
```

- [ ] **Step 2: Ejecutar el test y verificar que falla**

Run: `php artisan test --filter=UsersImportTest`
Expected: FALLA. `test_import_assigns_study_program_to_student_case_insensitive` (programa no asignado), `test_import_rejects_student_without_program` (recibe 200 en vez de 422), `test_import_warns_and_creates_user_when_program_does_not_exist` (`warnings` vacío). El test de EMPRESA puede pasar ya.

- [ ] **Step 3: Implementar en `app/Imports/UsersImport.php`**

1. Añadir import (junto a los existentes):

```php
use App\Models\StudyProgram;
```

2. Añadir propiedades junto a las existentes (después de `protected $hashedPasswordCache = [];`):

```php
    public $warnings = [];
    protected $studyProgramsByNormalizedName;
```

3. Dentro de `collection()`, justo después de precargar `$this->existingRucs` (bloque `if ($this->existingRucs === null) {...}`), añadir:

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

4. Leer la columna nueva, justo después de `$roleStr = ...` (línea ~69):

```php
            $programRaw = trim((string) ($row[6] ?? ''));
```

5. Tras el bloque de validación de `docNumber` vacío (línea ~116-119) y **antes** de `// Mark as taken in-memory`, insertar:

```php
            $studyProgramId = null;
            if (in_array($roleId, [3, 5], true)) {
                if ($programRaw === '') {
                    $this->errors[] = "Fila $lineNum: El programa de estudio es obligatorio para estudiantes y egresados.";
                    continue;
                }

                $studyProgramId = $this->studyProgramsByNormalizedName[mb_strtolower($programRaw)] ?? null;
                if ($studyProgramId === null) {
                    $this->warnings[] = "Fila $lineNum: el programa \"{$programRaw}\" no existe en Mantenedores; el usuario se importó sin programa.";
                }
            }
```

6. Añadir el campo al array de personas (dentro del `else` de `if ($roleId == 4)`, en `$peopleToInsert[] = [...]`):

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

Reemplazar el bloque final del `try` (desde `$errors = $import->errors;` hasta el `return response()->json` de éxito) por:

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

            $message = 'Importación completada. ' . count($importedUsers) . ' usuarios registrados.';
            if (!empty($warnings)) {
                $shown = array_slice($warnings, 0, 5);
                $extra = count($warnings) > 5 ? ' y ' . (count($warnings) - 5) . ' más' : '';
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

### Task 3: Regenerar la plantilla Excel con la columna PROGRAMA DE ESTUDIO

**Files:**
- Create: `scratch/update_import_template.php`
- Modify: `public/assets/Importacion de usuarios.xlsx` (generado por el script)

**Interfaces:**
- Consumes: plantilla actual (columnas A-F con encabezados `NOMBRES`, `TIPO DE DOCUMENTO`, `DOCUMENTO`, `TELÉFONO`, `EMAIL`, `ROL`; filas 2-6 con ejemplos: 2=ESTUDIANTE, 3=DOCENTE, 4=ADMINISTRADOR, 5=EMPRESA, 6=EGRESADO).
- Produces: plantilla con columna G `PROGRAMA DE ESTUDIO`; leída por `UsersImport` en índice 6 (Task 2).

- [ ] **Step 1: Crear el script de regeneración**

Crear `scratch/update_import_template.php`:

```php
<?php

require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

$path = __DIR__ . '/../public/assets/Importacion de usuarios.xlsx';

if (!file_exists($path)) {
    fwrite(STDERR, "No existe la plantilla: {$path}\n");
    exit(1);
}

$spreadsheet = IOFactory::load($path);
$sheet = $spreadsheet->getActiveSheet();

// Encabezado con el mismo estilo que F1
$sheet->setCellValue('G1', 'PROGRAMA DE ESTUDIO');
$sheet->getStyle('G1')->applyFromArray($sheet->getStyle('F1')->getArrayCopy());

// Ancho de columna
$sheet->getColumnDimension('G')->setWidth(30)->setCustomWidth(true);

// Ejemplos: fila 2 = ESTUDIANTE, fila 6 = EGRESADO (otros roles dejan G vacío)
$sheet->setCellValue('G2', 'Administración');
$sheet->setCellValue('G6', 'Ingeniería de Sistemas');
$sheet->getStyle('G2')->applyFromArray($sheet->getStyle('F2')->getArrayCopy());
$sheet->getStyle('G6')->applyFromArray($sheet->getStyle('F6')->getArrayCopy());

// Texto plano en toda la columna para que Excel no transforme valores
$sheet->getStyle('G2:G1000')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

$spreadsheet->setActiveSheetIndex(0);
IOFactory::createWriter($spreadsheet, 'Xlsx')->save($path);

echo "Plantilla actualizada: {$path}\n";
```

- [ ] **Step 2: Ejecutar el script**

Run: `php scratch/update_import_template.php`
Expected: `Plantilla actualizada: ...Importacion de usuarios.xlsx`

- [ ] **Step 3: Verificar los encabezados resultantes**

Run:
```powershell
php -r "require 'vendor/autoload.php'; $s = PhpOffice\PhpSpreadsheet\IOFactory::load('public/assets/Importacion de usuarios.xlsx')->getActiveSheet(); echo implode(' | ', array_filter($s->rangeToArray('A1:H1')[0], fn($v) => $v !== null)), PHP_EOL; echo 'G2=' . $s->getCell('G2')->getValue() . ' G6=' . $s->getCell('G6')->getValue() . PHP_EOL;"
```
Expected:
```
NOMBRES | TIPO DE DOCUMENTO | DOCUMENTO | TELÉFONO | EMAIL | ROL | PROGRAMA DE ESTUDIO
G2=Administración G6=Ingeniería de Sistemas
```

- [ ] **Step 4: Verificar que la descarga pública sirve el archivo nuevo**

Run:
```powershell
(Get-Item "public\assets\Importacion de usuarios.xlsx").LastWriteTime
```
Expected: fecha/hora actual.

---

### Task 4: Verificación final

**Files:**
- Ninguno nuevo; verificación transversal.

- [ ] **Step 1: Sin referencias residuales al módulo eliminado**

Run:
```powershell
rg -n "assign-study-program|aspLoadUsers|aspBulkAssign|aspToggleSelectAll|aspRenderTable|asp-assign|panel-assign-study-program|searchPersonUsers|assignStudyProgram|search-persons|study-programs/assign" resources routes app
```
Expected: sin resultados.

- [ ] **Step 2: Rutas y vistas OK**

Run:
```powershell
php artisan route:list | findstr /I "search-persons study-programs"
php artisan view:clear
```
Expected: sin resultados en la primera; vista cache limpiada en la segunda.

- [ ] **Step 3: Suite de tests**

Run: `php artisan test`
Expected: sin errores nuevos respecto al estado base.

- [ ] **Step 4: Prueba manual de importación (usuario)**

1. Ir a `/admin/dashboard?tab=users` → el menú ya no muestra "Asignar Programa".
2. Descargar la plantilla (`Importacion de usuarios.xlsx`), verificar 7 columnas.
3. Llenar una fila ESTUDIANTE con programa inexistente → importar → toast con advertencia y usuario creado.
4. Llenar una fila ESTUDIANTE sin programa → importar → error de fila.
5. Revisar en Mantenedores/base que `person.study_program_id` quedó asignado para el caso válido.

---

## Self-Review (hecho al escribir el plan)

- **Spec coverage:** Task 1 = sección 1 del spec; Task 2 = sección 2 (importador + UserController); Task 3 = plantilla; Task 4 = verificación. Cobertura completa.
- **Placeholders:** sin TBD/TODO; todos los pasos incluyen código o comandos exactos.
- **Consistencia de tipos:** `UsersImport::$warnings` (array público) coincide con `$import->warnings` en UserController y `response->json('warnings')` en los tests; índice 6/G coherente entre spec, script y tests.
