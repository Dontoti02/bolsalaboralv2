# Task 1 Report — Eliminar el módulo Asignar Programa de Estudio

**Status:** DONE
**Scope:** Solo remoción del módulo admin (vista + JS + rutas + controller). No se tocaron `app/Imports/UsersImport.php`, `UserController::import` ni plantillas Excel.
**Commits:** ninguno (todas los cambios quedan en el working tree, según instrucción).

## Cambios realizados

### `resources/views/admin/dashboard.blade.php` (8999 → 8515 líneas; git numstat: 2 inserciones / 487 borrados)
- **Step 1:** eliminada la entrada de menú `['key' => 'assign-study-program', 'icon' => 'school', 'label' => 'Asignar Programa'],`.
- **Step 2:** eliminado el panel completo (`{{-- PANEL: ASIGNAR PROGRAMA DE ESTUDIO --}}` … cierre del `div#panel-assign-study-program`, incluido `window.aspStudyPrograms = @json(...)`). Quedó: `</div>` + línea en blanco + `        </div>` + `    </main>`.
- **Step 3:** eliminado el modal `<!-- MODAL: CONFIRMACIÓN ASIGNACIÓN EN MASA -->` completo hasta su `    </div>` final. Se conservó `<!-- Toast system → ui-alerts.js -->`.
- **Step 4:** `ADMIN_VALID_TABS` sin `'assign-study-program'`.
- **Step 5:** rama `else if (tabId === 'assign-study-program')` eliminada; la rama `'support'` cierra con su propio `}` (línea 3748). Nota: el snippet de 4 líneas del brief dejaba la rama `support` sin cerrar; se aplicó la variante sintácticamente correcta descrita por el propio brief ("dejando que la rama anterior de 'support' cierre con su `}`").
- **Step 6:** `tabTitles` termina en `'support': 'Soporte'`.
- **Step 7:** eliminado el bloque JS MÓDULO: ASIGNAR PROGRAMA DE ESTUDIO (ASP) (líneas 7939–8264 del archivo pre-borrado: separador inicial, 5 variables `asp*` y las 14 funciones `asp*`/`toggleAspBulkModal`/`executeAspBulkAssign`). Tras el borrado quedan juntos exactamente:
  ```js
  // =====================================================================

  // ==========================================
  // MODULE: REPORTES DE BOLSA LABORAL
  // ==========================================
  ```

Los tres bloques grandes se borraron con un script PHP temporal (`tmp_sdd_task1_delete.php`, ya eliminado) que **validaba cada ancla antes de borrar** (comentario de inicio, línea final `</div>`/separador y la línea siguiente que debe conservarse) — todas las anclas coincidieron. Se usó splice por rango para no depender de números de línea aproximados.

### `routes/web.php` (−4 líneas)
- Eliminadas las rutas `admin.users.search-persons` (GET) y `admin.study-programs.assign` (POST).
- Extra: eliminado el comentario huérfano `// Study Program Routes` (ya no apuntaba a nada). No afecta funcionalidad.

### `app/Http/Controllers/AdminController.php` (−72 líneas, 0 inserciones)
- Eliminados imports `App\Models\Person` y `App\Models\StudyProgram`.
- Eliminados `$studyPrograms = StudyProgram::orderBy('name')->get();` (+ su comentario) y `$studyPrograms = collect();` del `catch`.
- Eliminado `'studyPrograms',` de los dos `compact(...)`.
- Eliminados los métodos completos con docblocks `searchPersonUsers()` y `assignStudyProgram()`; el archivo cierra con el `}` de clase.

## Verificación (Step 10) — salida real

```
PS> php artisan route:list | findstr /I "search-persons study-programs/assign"
(sin salida; $LASTEXITCODE = 1 de findstr → sin coincidencias)

PS> php artisan route:clear; php artisan view:clear
INFO  Route cache cleared successfully.
INFO  Compiled views cleared successfully.

PS> php artisan view:cache
INFO  Blade templates cached successfully.
PS> php artisan view:clear
INFO  Compiled views cleared successfully.

PS> php -l app/Http/Controllers/AdminController.php
No syntax errors detected in app/Http/Controllers/AdminController.php
PS> php -l routes/web.php
No syntax errors detected in routes/web.php

PS> php artisan route:list | Measure-Object -Line
Lines Words Characters Property
----- ----- ---------- --------
   86
EXIT=0
```

Grep de residuales (equivalente al `rg` del brief; `rg` no disponible, se usó la Grep tool) sobre `resources`, `routes`, `app` con patrón
`assign-study-program|aspLoadUsers|aspBulkAssign|asp-render|asp-table-body|search-persons|searchPersonUsers|assignStudyProgram|toggleAspBulkModal|executeAspBulkAssign|aspUpdateStats|aspToggleSelectAll|aspChangePage|aspEscapeHtml|aspAllUsers|aspSelectedPersonIds|asp-programs-count|window.aspStudyPrograms`:

```
resources: 0 matches en *.php/*.blade.php/*.js (ver Notas abajo)
routes:    No files found
app:       No files found (searchPersonUsers|assignStudyProgram|search-persons|study-programs/assign)
```

Búsqueda adicional `admin.study-programs.assign|admin.users.search-persons|study-programs/assign|users/search-persons` en todo el proyecto (`*.php,*.blade.php,*.js,*.json,*.vue`): **sin resultados**.

## Auto-revisión
- Menú lateral: sin entrada `assign-study-program` ✓
- Panel `panel-assign-study-program` y `@foreach($studyPrograms ...)` de la vista: eliminados ✓
- Modal `asp-bulk-confirm-modal`: eliminado ✓
- Funciones/IDs JS `asp*`: 0 en `resources/views/admin/dashboard.blade.php` ✓
- `ADMIN_VALID_TABS`, rama de `switchTab`, `tabTitles`: sin la pestaña ✓
- Rutas y métodos del controller: eliminados ✓; imports sin uso: eliminados ✓
- `php artisan view:cache` compila las 2 vistas sin error (sintaxis Blade válida) ✓
- Diferencia neta del módulo: 2 únicas líneas insertadas en todo el proyecto de código = los 2 reemplazos intencionales (Step 4 y Step 6); todo lo demás es borrado puro.
- No se tocaron `app/Imports/UsersImport.php`, `UserController::import` ni plantillas Excel ✓
- Archivos temporales: el script PHP de borrado fue eliminado ✓
- Sin commits; `git status` muestra modificados únicamente los 3 archivos de código (más los briefs/progress que ya venían modificados por el orquestador).

## Notas / preocupaciones
1. **Baja:** `resources/views/admin/_rest.txt` es un archivo `.txt` suelto dentro de `resources/views/admin/` (parece un volcado/backups antiguo). No contiene referencias ASP (`assign-study-program`, `asp*`, `search-persons` → 0 matches), pero no forma parte del plan y no fue tocado.
2. **Baja:** `login.blade.php` usa `$studyPrograms`, pero proviene de `AuthController::login` (página de login) — módulo distinto, correcto dejarlo.
3. **Info:** `storage/framework/views/*` (vistas compiladas) aún contenían el código viejo hasta `php artisan view:clear`; ya fue limpiado.
4. **Info:** la eliminación de la rama de `switchTab` se hizo con 3 líneas borradas en lugar de las 4 del snippet para conservar el `}` de cierre de la rama `'support'` (el snippet del brief, aplicado literalmente, habría dejado un JS sin cerrar). Comportamiento final idéntico al previsto por el brief.

## Comandos sugeridos para probar
```powershell
php artisan route:list | findstr /I "search-persons study-programs/assign"   # sin salida
php artisan serve
# En el navegador: /admin → el menú ya no muestra "Asignar Programa";
# pestañas users/reports/support funcionan; consola JS sin errores de asp*.
```
