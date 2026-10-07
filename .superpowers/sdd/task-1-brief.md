### Task 1: Eliminar el mÃ³dulo Asignar Programa de Estudio

**Files:**
- Modify: `resources/views/admin/dashboard.blade.php` (lÃ­neas ~135, 2653-2766, 3774-3811, 3820, 3903-3906, 3937, 7940-8265)
- Modify: `routes/web.php:88-89`
- Modify: `app/Http/Controllers/AdminController.php` (imports lÃ­nea 6 y 8; `$studyPrograms` lÃ­nea 208-209, 225, 241, 265; mÃ©todos lÃ­neas 350-412)

**Interfaces:**
- Consumes: nada.
- Produces: rutas `admin.users.search-persons` y `admin.study-programs.assign` eliminadas; vista sin panel `panel-assign-study-program` ni funciones `asp*`. La Task 2 no depende de esto.

- [ ] **Step 1: Quitar la entrada del menÃº lateral**

En `resources/views/admin/dashboard.blade.php` eliminar la lÃ­nea exacta:

```blade
                        ['key' => 'assign-study-program', 'icon' => 'school', 'label' => 'Asignar Programa'],
```

- [ ] **Step 2: Eliminar el panel HTML completo**

En `dashboard.blade.php` eliminar desde la lÃ­nea 2653 hasta la 2766 inclusive. Anclagos:
- Inicio (Ãºnico): `            {{-- ================= PANEL: ASIGNAR PROGRAMA DE ESTUDIO ================= --}}`
- Fin (Ãºnico): las 4 Ãºltimas lÃ­neas del bloque:

```blade
                <script>
                    window.aspStudyPrograms = @json($studyPrograms->values());
                </script>
            </div>
```

Nota: justo despuÃ©s debe quedar una lÃ­nea en blanco seguida de `        </div>` y `    </main>` (no eliminar esas).

- [ ] **Step 3: Eliminar el modal de confirmaciÃ³n masiva**

En `dashboard.blade.php` eliminar desde (anclago de inicio Ãºnico):

```blade
    <!-- ================= MODAL: CONFIRMACIÃ“N ASIGNACIÃ“N EN MASA ================= -->
```

hasta (anclago de fin Ãºnico):

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

No eliminar la lÃ­nea `    <!-- Toast system â†’ ui-alerts.js (renderizado dinÃ¡micamente) -->` que sigue.

- [ ] **Step 4: Quitar la pestaÃ±a de `ADMIN_VALID_TABS`**

LÃ­nea ~3820, cambiar:

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

- [ ] **Step 6: Quitar el tÃ­tulo de la pestaÃ±a**

En `tabTitles` (~3937) cambiar:

```js
                'support': 'Soporte',
                'assign-study-program': 'Asignar Programa de Estudio'
```

por:

```js
                'support': 'Soporte'
```

- [ ] **Step 7: Eliminar el bloque JS del mÃ³dulo ASP**

En `dashboard.blade.php` eliminar desde la lÃ­nea 7940 hasta la 8265 inclusive. Anclagos:
- Inicio (Ãºnico):

```js
        // =====================================================================
        // MÃ“DULO: ASIGNAR PROGRAMA DE ESTUDIO (ASP)
        // =====================================================================
```

- Fin: termina justo antes de la lÃ­nea 8266, que es un separador `        // =====...` seguido de `        // ==========================================` y `        // MODULE: REPORTES DE BOLSA LABORAL`. Verificar que tras el borrado aparezcan juntos:

```js
        // =====================================================================

        // ==========================================
        // MODULE: REPORTES DE BOLSA LABORAL
        // ==========================================
```

El bloque eliminado contiene: variables `aspAllUsers`, `aspFilteredUsers`, `aspCurrentPage`, `aspPageSize`, `aspSelectedPersonIds` y las funciones `aspLoadUsers`, `aspUpdateStats`, `aspToggleSelectAll`, `aspToggleUserSelect`, `aspRenderTable`, `aspChangePage`, `aspAssign`, `aspEscapeHtml`, `toggleAspBulkModal`, `aspBulkAssign`, `executeAspBulkAssign`.

- [ ] **Step 8: Eliminar las rutas**

En `routes/web.php` eliminar las lÃ­neas 88-89:

```php
        Route::get('/admin/users/search-persons', [App\Http\Controllers\AdminController::class, 'searchPersonUsers'])->name('admin.users.search-persons');
        Route::post('/admin/study-programs/assign', [App\Http\Controllers\AdminController::class, 'assignStudyProgram'])->name('admin.study-programs.assign');
```

- [ ] **Step 9: Limpiar `AdminController.php`**

1. Eliminar los imports sin uso (lÃ­neas 6 y 8):

```php
use App\Models\Person;
use App\Models\StudyProgram;
```

2. En `dashboard()`: eliminar el comentario y la lÃ­nea:

```php
            // Cargar programas de estudio para el select de asignaciÃ³n
            $studyPrograms = StudyProgram::orderBy('name')->get();
```

3. Eliminar `'studyPrograms',` de los dos `compact(...)` (lÃ­neas ~225 y ~265).
4. En el bloque `catch`: eliminar `$studyPrograms = collect();`.
5. Eliminar ambos mÃ©todos completos con sus docblocks (lÃ­neas 350-412): `searchPersonUsers()` y `assignStudyProgram()`. Debe quedar el cierre de clase `}` como Ãºltima lÃ­nea del archivo.

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

