### Task 4: VerificaciÃ³n final

**Files:**
- Ninguno nuevo; verificaciÃ³n transversal.

- [ ] **Step 1: Sin referencias residuales al mÃ³dulo eliminado**

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

- [ ] **Step 4: Prueba manual de importaciÃ³n (usuario)**

1. Ir a `/admin/dashboard?tab=users` â†’ el menÃº ya no muestra "Asignar Programa".
2. Descargar la plantilla (`Importacion de usuarios.xlsx`), verificar 7 columnas.
3. Llenar una fila ESTUDIANTE con programa inexistente â†’ importar â†’ toast con advertencia y usuario creado.
4. Llenar una fila ESTUDIANTE sin programa â†’ importar â†’ error de fila.
5. Revisar en Mantenedores/base que `person.study_program_id` quedÃ³ asignado para el caso vÃ¡lido.

---

## Self-Review (hecho al escribir el plan)

- **Spec coverage:** Task 1 = secciÃ³n 1 del spec; Task 2 = secciÃ³n 2 (importador + UserController); Task 3 = plantilla; Task 4 = verificaciÃ³n. Cobertura completa.
- **Placeholders:** sin TBD/TODO; todos los pasos incluyen cÃ³digo o comandos exactos.
- **Consistencia de tipos:** `UsersImport::$warnings` (array pÃºblico) coincide con `$import->warnings` en UserController y `response->json('warnings')` en los tests; Ã­ndice 6/G coherente entre spec, script y tests.
