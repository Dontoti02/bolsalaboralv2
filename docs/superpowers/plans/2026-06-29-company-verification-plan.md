# Company Verification and User Constraints Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Restrict "Empresa" role registration under the admin user manager, and require admin verification before a company can publish job offers.

**Architecture:** Block role 4 creation/updating in `UserController`, set registration status default to unverified in `AuthController`, and enforce `is_verified` checks in `CompanyDashboardController` (Approach B). Update views accordingly.

**Tech Stack:** Laravel (PHP), Tailwind/Custom CSS, Blade templates.

---

### Task 1: Restrict Empresa Role in Admin User Management

**Files:**
- Modify: `resources/views/admin/dashboard.blade.php`
- Modify: `app/Http/Controllers/UserController.php`

- [ ] **Step 1: Remove "EMPRESA" option from user creation dropdown**
  Modify [dashboard.blade.php](file:///c:/xampp/htdocs/bolsalaboralv2/resources/views/admin/dashboard.blade.php) around line 2532:
  ```html
  <!-- Remove: <option value="4">EMPRESA</option> -->
  ```

- [ ] **Step 2: Update role validation in UserController@store**
  Modify [UserController.php](file:///c:/xampp/htdocs/bolsalaboralv2/app/Http/Controllers/UserController.php) around line 32:
  ```php
  'role_id' => 'required|integer|in:1,2,3',
  ```

- [ ] **Step 3: Update role validation in UserController@update**
  Modify [UserController.php](file:///c:/xampp/htdocs/bolsalaboralv2/app/Http/Controllers/UserController.php) around line 148:
  ```php
  'role_id' => 'required|integer|in:1,2,3',
  ```

---

### Task 2: Set Registered Companies to Unverified by Default

**Files:**
- Modify: `app/Http/Controllers/AuthController.php`

- [ ] **Step 1: Change default is_verified to false on registration**
  Modify [AuthController.php](file:///c:/xampp/htdocs/bolsalaboralv2/app/Http/Controllers/AuthController.php) around line 177:
  ```php
  'is_verified' => false,
  ```

---

### Task 3: Enforce Verification to Publish/Modify Job Offers

**Files:**
- Modify: `app/Http/Controllers/CompanyDashboardController.php`

- [ ] **Step 1: Add verification check in storeOffer**
  Modify [CompanyDashboardController.php](file:///c:/xampp/htdocs/bolsalaboralv2/app/Http/Controllers/CompanyDashboardController.php) around line 301 (at the beginning of `storeOffer` after fetching company):
  ```php
  if (!$company->is_verified) {
      return response()->json(['success' => false, 'message' => 'Tu empresa aún no ha sido verificada por el administrador. No tienes permisos para realizar esta acción.'], 403);
  }
  ```

- [ ] **Step 2: Add verification check in updateOffer**
  Modify [CompanyDashboardController.php](file:///c:/xampp/htdocs/bolsalaboralv2/app/Http/Controllers/CompanyDashboardController.php) around line 392 (at the beginning of `updateOffer` after fetching company):
  ```php
  if (!$company->is_verified) {
      return response()->json(['success' => false, 'message' => 'Tu empresa aún no ha sido verificada por el administrador. No tienes permisos para realizar esta acción.'], 403);
  }
  ```

- [ ] **Step 3: Add verification check in toggleOfferState**
  Modify [CompanyDashboardController.php](file:///c:/xampp/htdocs/bolsalaboralv2/app/Http/Controllers/CompanyDashboardController.php) around line 455 (at the beginning of `toggleOfferState` after fetching company):
  ```php
  if (!$company->is_verified) {
      return response()->json(['success' => false, 'message' => 'Tu empresa aún no ha sido verificada por el administrador. No tienes permisos para realizar esta acción.'], 403);
  }
  ```

---

### Task 4: Warn and Disable Creation Buttons on Company Dashboard View

**Files:**
- Modify: `resources/views/company/dashboard.blade.php`

- [ ] **Step 1: Add warning banner for unverified company**
  Modify [dashboard.blade.php](file:///c:/xampp/htdocs/bolsalaboralv2/resources/views/company/dashboard.blade.php) around line 119, right before the profile completeness banner:
  ```html
  @if(!$company->is_verified)
  <!-- Unverified Warning Banner -->
  <div id="verification-warning-banner" class="flex flex-col sm:flex-row sm:items-center justify-between gap-md bg-red-50 border-2 border-red-200 text-red-900 p-lg rounded-2xl shadow-sm mb-lg">
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

- [ ] **Step 2: Disable Overview "Crear Nueva Oferta" button if unverified**
  Modify [dashboard.blade.php](file:///c:/xampp/htdocs/bolsalaboralv2/resources/views/company/dashboard.blade.php) around line 113:
  ```html
  @if($company->is_verified)
  <button onclick="switchTab('offers'); showCreateOfferForm()" class="bg-primary text-on-primary rounded-lg px-lg py-3 font-label-md text-label-md flex items-center justify-center gap-sm shadow-sm hover:opacity-90 transition-opacity">
      <span class="material-symbols-outlined text-[20px]">add</span>
      Crear Nueva Oferta
  </button>
  @else
  <button disabled class="bg-outline-variant text-on-surface-variant/50 cursor-not-allowed rounded-lg px-lg py-3 font-label-md text-label-md flex items-center justify-center gap-sm shadow-sm">
      <span class="material-symbols-outlined text-[20px]">lock</span>
      Crear Nueva Oferta (Requiere Verificación)
  </button>
  @endif
  ```

- [ ] **Step 3: Disable Offer Panel "Crear Oferta" button if unverified**
  Modify [dashboard.blade.php](file:///c:/xampp/htdocs/bolsalaboralv2/resources/views/company/dashboard.blade.php) around line 296:
  ```html
  @if($company->is_verified)
  <button onclick="showCreateOfferForm()"
      class="flex-1 sm:flex-initial px-6 py-2.5 bg-primary text-on-primary font-label-md text-label-md rounded-xl hover:opacity-95 shadow-sm transition-all font-semibold flex items-center justify-center gap-2">
      <span class="material-symbols-outlined text-[18px]">add</span>
      Crear Oferta
  </button>
  @else
  <button disabled
      class="flex-1 sm:flex-initial px-6 py-2.5 bg-outline-variant text-on-surface-variant/50 cursor-not-allowed font-label-md text-label-md rounded-xl shadow-sm transition-all font-semibold flex items-center justify-center gap-2">
      <span class="material-symbols-outlined text-[18px]">lock</span>
      Crear Oferta (Requiere Verificación)
  </button>
  @endif
  ```
