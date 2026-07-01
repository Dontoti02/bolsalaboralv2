# Diseño de Verificación de Empresas y Restricciones de Creación de Usuarios

Este documento detalla el diseño para implementar las restricciones en la creación de usuarios tipo Empresa y la verificación obligatoria para publicar ofertas de empleo.

## Cambios Propuestos

### 1. Restricción del Rol Empresa en el Gestor de Usuarios (Administración)

#### Modificación en la Vista de Administración
* **Archivo:** [dashboard.blade.php](file:///c:/xampp/htdocs/bolsalaboralv2/resources/views/admin/dashboard.blade.php)
* **Acción:** Remover la opción del rol Empresa del formulario de creación y edición de usuarios.
* **Detalle:** Comentar o eliminar la línea `<option value="4">EMPRESA</option>`.

#### Modificación en la Validación del Controlador
* **Archivo:** [UserController.php](file:///c:/xampp/htdocs/bolsalaboralv2/app/Http/Controllers/UserController.php)
* **Acción:** Actualizar las reglas de validación en `store` y `update` para que solo permitan los roles `1` (Administrador), `2` (Docente) y `3` (Estudiante).
* **Detalle:** Cambiar `'role_id' => 'required|integer|in:1,2,3,4'` por `'role_id' => 'required|integer|in:1,2,3'`.

### 2. Registro de Empresas como "No Verificadas" por Defecto

#### Modificación en el Flujo de Registro
* **Archivo:** [AuthController.php](file:///c:/xampp/htdocs/bolsalaboralv2/app/Http/Controllers/AuthController.php)
* **Acción:** Al registrarse una nueva empresa, marcarla inicialmente como no verificada.
* **Detalle:** Cambiar `'is_verified' => true` por `'is_verified' => false` en el método `registerCompany`.

### 3. Restricción de Publicación de Ofertas para Empresas no Verificadas

#### Modificación en el Controlador de la Empresa
* **Archivo:** [CompanyDashboardController.php](file:///c:/xampp/htdocs/bolsalaboralv2/app/Http/Controllers/CompanyDashboardController.php)
* **Acción:** En los métodos que alteran el estado o crean ofertas, validar si la empresa está verificada. Si no lo está, retornar un error `403`.
* **Métodos afectados:**
  - `storeOffer`: Validar `is_verified`.
  - `updateOffer`: Validar `is_verified`.
  - `toggleOfferState`: Validar `is_verified`.
* **Código a insertar:**
  ```php
  if (!$company->is_verified) {
      return response()->json([
          'success' => false,
          'message' => 'Tu empresa aún no ha sido verificada por el administrador. No tienes permisos para realizar esta acción.'
      ], 403);
  }
  ```

#### Modificación en la Vista del Dashboard de la Empresa
* **Archivo:** [dashboard.blade.php](file:///c:/xampp/htdocs/bolsalaboralv2/resources/views/company/dashboard.blade.php)
* **Acción:** 
  1. Mostrar un banner rojo de advertencia si la empresa no está verificada.
  2. Condicionar los botones de "Crear Nueva Oferta" para que estén deshabilitados.
* **Detalles:**
  - Agregar banner:
    ```html
    @if(!$company->is_verified)
    <div id="verification-warning-banner" class="flex flex-col sm:flex-row sm:items-center justify-between gap-md bg-red-50 border-2 border-red-200 text-red-900 p-lg rounded-2xl shadow-sm mb-4">
        <div class="flex items-start gap-md">
            <span class="material-symbols-outlined text-red-600 text-3xl shrink-0">gavel</span>
            <div>
                <p class="font-bold text-body-sm leading-none text-red-950">¡Cuenta no verificada!</p>
                <p class="text-body-sm mt-1">Tu empresa aún no ha sido verificada por el administrador. No podrás publicar ofertas de empleo hasta que sea aprobada.</p>
            </div>
        </div>
    </div>
    @endif
    ```
  - Condicionar botones de creación:
    ```html
    @if($company->is_verified)
    <button onclick="switchTab('offers'); showCreateOfferForm()" class="bg-primary ...">
        ...
    </button>
    @else
    <button disabled class="bg-outline-variant text-on-surface-variant/50 cursor-not-allowed rounded-lg px-lg py-3 font-label-md text-label-md flex items-center justify-center gap-sm shadow-sm">
        <span class="material-symbols-outlined text-[20px]">lock</span>
        Crear Nueva Oferta (Requiere Verificación)
    </button>
    @endif
    ```

## Plan de Verificación
* **Manual:**
  - Registrar una nueva empresa e intentar crear una oferta desde el dashboard de la empresa. Validar que aparezca el banner y que los botones de creación estén deshabilitados.
  - Como administrador, verificar la empresa registrada.
  - Regresar al dashboard de la empresa y corroborar que ahora sí se permita crear ofertas de trabajo.
  - Intentar crear un usuario "Empresa" desde el panel general de usuarios de administración y confirmar que la opción ya no está disponible.
