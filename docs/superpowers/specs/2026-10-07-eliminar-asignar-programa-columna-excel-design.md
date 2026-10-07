# Spec: Eliminar módulo "Asignar Programa" + columna PROGRAMA DE ESTUDIO en plantilla Excel

Fecha: 2026-10-07
Estado: Aprobado por el usuario (Enfoque A)

## Objetivo

1. Eliminar por completo el módulo administrativo "Asignar Programa de Estudio".
2. Hacer práctica la asignación de programa de estudio al importar usuarios: nueva columna `PROGRAMA DE ESTUDIO` en la plantilla Excel, válida solo para ESTUDIANTE y EGRESADO.

## Alcance

Solo lo pedido: no se toca el registro de usuarios, login, reportes, mantenedores ni el CRUD de programas.

## 1. Eliminación del módulo Asignar Programa

### `resources/views/admin/dashboard.blade.php`
- Entrada del menú lateral: `['key' => 'assign-study-program', ...]` (línea ~135).
- Panel HTML `panel-assign-study-program` completo (líneas ~2653-2766), incluido el `<script>` de `window.aspStudyPrograms`.
- Modal `asp-bulk-confirm-modal` (líneas ~3774-3811).
- `ADMIN_VALID_TABS`: quitar `'assign-study-program'` (línea ~3820).
- `switchTab()`: quitar rama `else if (tabId === 'assign-study-program')` (líneas ~3903-3906).
- `tabTitles`: quitar `'assign-study-program'` (línea ~3937).
- Bloque JS completo del módulo (líneas ~7940-8263): variables `asp*`, `aspLoadUsers`, `aspUpdateStats`, `aspToggleSelectAll`, `aspToggleUserSelect`, `aspRenderTable`, `aspChangePage`, `aspAssign`, `aspEscapeHtml`, `toggleAspBulkModal`, `aspBulkAssign`, `executeAspBulkAssign`.

### `routes/web.php`
- Eliminar (líneas ~88-89):
  - `GET /admin/users/search-persons` → `AdminController@searchPersonUsers`
  - `POST /admin/study-programs/assign` → `AdminController@assignStudyProgram`

### `app/Http/Controllers/AdminController.php`
- Eliminar métodos `searchPersonUsers()` y `assignStudyProgram()`.
- Eliminar `$studyPrograms = StudyProgram::orderBy('name')->get();` de `dashboard()` y su presencia en ambos `compact(...)` (solo lo usaba el panel eliminado); idem en el bloque de fallback.
- Quitar imports `Person` y `StudyProgram` solo si quedan sin uso tras la eliminación (verificar con búsqueda).

No se modifica la vista `partials/sidebar` (solo consume la config).

## 2. Columna PROGRAMA DE ESTUDIO (plantilla + import)

### Plantilla `public/assets/Importacion de usuarios.xlsx`
Regenerar manteniendo el formato actual (dimension A1:G1000, anchos de columna, estilos de encabezado y celdas texto):
- G1: `PROGRAMA DE ESTUDIO` con el mismo estilo que A1:F1.
- Filas de ejemplo actuales (2-6) conservan sus datos; se completa G solo en filas ESTUDIANTE (fila 2) y EGRESADO (fila 6) con nombres de programas de ejemplo (ej. `Administración`, `Ingeniería de Sistemas`).
- Filas DOCENTE/ADMINISTRADOR/EMPRESA dejan G vacía.
- Filas 7-1000: columna G vacía con el mismo formato texto que el resto.

### `app/Imports/UsersImport.php`
- Nueva propiedad pública `public $warnings = [];`.
- Precargar catálogo una vez: `study_programs` → mapa normalizado `mb_strtolower(trim(name)) => id`.
- Leer columna índice 6: `$programRaw = trim((string)($row[6] ?? ''));`.
- Reglas (solo `rol_id` 3 ESTUDIANTE y 5 EGRESADO):
  - Vacío → `errors[] = "Fila N: El programa de estudio es obligatorio para estudiantes y egresados."` y se descarta la fila (no se importa).
  - No vacío → lookup case-insensitive con trim; si existe, se resuelve `study_program_id`; si no existe → **la fila sí se importa** y se agrega `warnings[] = "Fila N: el programa \"X\" no existe en Mantenedores; el usuario se importó sin programa."`.
- Resto de roles (1, 2, 4): la columna se ignora, `study_program_id = null`.
- El `study_program_id` resuelto se incluye en `$peopleToInsert` (bulk insert de `person`) dentro de la transacción existente.

Nota: `person.study_program_id` es `nullable` con FK a `study_programs` (`2026_07_15_000002_add_study_program_id_to_person_table.php`), por lo que no se requiere migración.

### `app/Http\Controllers\UserController::import()`
- Devolver en el JSON de éxito: `'warnings' => $import->warnings`.
- Incluir el resumen de advertencias en `message` cuando existan (ej. `Importación completada. 5 usuarios registrados. 2 advertencias: Fila 3: ... | Fila 7: ...`), limitando a las primeras 5 advertencias + `y N más`.
- Errores de fila se mantienen igual que hoy (se ignoran si hubo importación parcial, comportamiento existente sin cambios).

## Errores y validaciones

- Archivo sin columna G (plantilla vieja de 6 columnas): filas ESTUDIANTE/EGRESADO → error por programa obligatorio; otros roles → se importan normalmente.
- Filas vacías → se omiten como hoy.
- Sin cambios en validación de email, documento, RUC/DNI ni contraseñas.

## Verificación

1. `php artisan route:list | findstr search-persons` y `| findstr study-programs` → sin resultados.
2. Búsqueda en el proyecto de `assign-study-program`, `asp-`, `aspLoadUsers` → sin resultados.
3. Importar la plantilla regenerada → estudiantes/egresados quedan con `person.study_program_id` asignado.
4. Importar archivo sin columna G → error claro solo en filas de estudiantes/egresados.
5. Importar con nombre de programa inexistente → usuario importado + advertencia visible en el toast.
6. `php artisan test` (suite existente) para regresión.
