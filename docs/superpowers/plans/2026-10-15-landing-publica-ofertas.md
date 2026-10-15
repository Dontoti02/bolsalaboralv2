# Landing Pública de Ofertas Laborales - Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Transformar la ruta principal `/` en una landing pública moderna, intuitiva y rápida para consultar ofertas laborales activas, reflejando el logo y colores institucionales configurados en Admin, y solicitando iniciar sesión al intentar postular.

**Architecture:** Mover las rutas `/` y `/buscar-ofertas` fuera del middleware `auth` en Laravel. Actualizar `LandingController` y `AuthController` para soportar retorno contextual (`redirect_offer`) al postular. Integrar en `landing.blade.php` una sección Hero moderna con estadísticas y buscador rápido, más un modal reactivo para postulantes no autenticados.

**Tech Stack:** Laravel 12, Blade, Vanilla CSS/JS con variables CSS dinámicas, MySQL, PHPUnit.

## Global Constraints
- Respetar los colores y logos dinámicos de `system_configuration` (`--pri`, `--sec`, `--secc`, `--acc`).
- No alterar rutas protegidas de administración, empresas o postulaciones efectivas de estudiantes.
- Cero dependencias CSS/JS adicionales (Vanilla JS y CSS puro de alto rendimiento).
- Probar con PHPUnit antes de finalizar.

---

### Task 1: Public Routes & Controller Updates for Guests

**Files:**
- Modify: `routes/web.php:24-30`
- Modify: `app/Http/Controllers/LandingController.php:20-40`
- Modify: `app/Http/Controllers/AuthController.php:108-118`
- Create: `tests/Feature/PublicLandingTest.php`

**Interfaces:**
- Consumes: `JobOpportunityOffer`, `Company`, `system_configuration`
- Produces: Rutas públicas `GET /` (`name('landing')`) y `GET /buscar-ofertas` (`name('landing.search')`) accesibles sin sesión.

- [ ] **Step 1: Write the failing test for public landing access**

```php
// tests/Feature/PublicLandingTest.php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class PublicLandingTest extends TestCase
{
    public function test_guest_can_access_landing_page_without_login_redirect(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSeeText('Bolsa');
    }

    public function test_guest_can_search_offers_via_ajax(): void
    {
        $response = $this->getJson('/buscar-ofertas');
        $response->assertStatus(200);
        $response->assertJsonStructure(['offers', 'total']);
    }
}
```

- [ ] **Step 2: Run test to verify it fails (redirects to /login)**

Run: `php artisan test --filter=PublicLandingTest`
Expected: FAIL with status 302 (redirect to login) instead of 200.

- [ ] **Step 3: Update `routes/web.php` and controllers**

Mover `Route::get('/', ...)` y `Route::get('/buscar-ofertas', ...)` antes del `Route::middleware(['auth'])`.
En `AuthController.php`, si viene `redirect_offer` en request, redirigir a `/?offer={$offerId}&apply=1`.

- [ ] **Step 4: Run test to verify it passes**

Run: `php artisan test --filter=PublicLandingTest`
Expected: PASS (2 tests, 2 passed).

- [ ] **Step 5: Commit changes**

```bash
git add routes/web.php app/Http/Controllers/LandingController.php app/Http/Controllers/AuthController.php tests/Feature/PublicLandingTest.php
git commit -m "feat: make landing and search offers routes public for guests"
```

---

### Task 2: Hero Section, Branding & Dynamic Colors in `landing.blade.php`

**Files:**
- Modify: `resources/views/landing.blade.php`

**Interfaces:**
- Consumes: `$config` (`primary_color`, `secondary_color`, `accent_color`, `logo`, `application_name`), `$totalActiveOffers`, `$totalCompanies`, `$categories`
- Produces: Hero interactivo, contadores de confianza y estilos visuales basados en la configuración de Admin.

- [ ] **Step 1: Implement Hero section and CSS improvements in `landing.blade.php`**
  - Agregar banner Hero con gradiente armónico usando `var(--pri)` y `var(--sec)`.
  - Título principal atractivo: "Encuentra tu próximo empleo ideal".
  - Buscador Hero central con autocompletado y botón de acción.
  - Indicadores estadísticos: Ofertas activas y Empresas verificadas.
  - Píldoras de categorías destacadas con filtro inmediato.

- [ ] **Step 2: Ensure responsive design on mobile and desktop**
  - Probar que el buscador en móviles sea accesible y fluido.
  - Verificar que el logotipo configurado en Admin se muestre con las proporciones adecuadas.

- [ ] **Step 3: Run existing and new tests to ensure no regressions**

Run: `php artisan test --filter=PublicLandingTest`
Expected: PASS.

- [ ] **Step 4: Commit changes**

```bash
git add resources/views/landing.blade.php
git commit -m "feat: add modern hero section and dynamic admin branding to public landing"
```

---

### Task 3: Modal Informativo "Inicia Sesión para Postular" y Flujo de Retorno

**Files:**
- Modify: `resources/views/landing.blade.php`

**Interfaces:**
- Consumes: Modal state, `$authUser`
- Produces: Modal `#modal-guest-postular` y lógica JS para abrirlo si un visitante hace clic en "Postularme" o "Guardar".

- [ ] **Step 1: Add Guest Postulation Modal markup and styles in `landing.blade.php`**
  - Componente modal con diseño moderno (backdrop blur, bordes redondeados, sombra suave).
  - Título: "Inicia sesión para postular".
  - Mensaje dinámico con el título del puesto y la empresa.
  - Botón "Iniciar Sesión" (apuntando a `/login?redirect_offer={id}&apply=1`).
  - Botón "Registrarme como Egresado" (`/registro-egresado`).
  - Botón de cierre accesible.

- [ ] **Step 2: Add JS handlers in `landing.blade.php`**
  - Si el usuario no está logueado (`@if(!$authUser)`), la función `handlePostularClick(offerId)` abre `#modal-guest-postular`.
  - Si el visitante llega logueado con `?apply=1&offer={id}`, auto-ejecutar `openPostularModal()`.

- [ ] **Step 3: Test guest modal triggers and auth redirection**
  - Simular clic en Postular sin sesión y verificar apertura de modal y enlaces correctos.

- [ ] **Step 4: Commit changes**

```bash
git add resources/views/landing.blade.php
git commit -m "feat: add guest postulation modal with seamless auth redirect"
```

---

### Task 4: Complete Verification & Final Testing

**Files:**
- Run: `tests/Feature/PublicLandingTest.php`

- [ ] **Step 1: Execute test suite**

Run: `php artisan test`
Expected: Todos los tests pasan sin errores.

- [ ] **Step 2: Verify routes list**

Run: `php artisan route:list --name=landing`
Expected: `landing` y `landing.search` no tienen middleware `auth`.

- [ ] **Step 3: Final Commit and status check**

```bash
git status
```
