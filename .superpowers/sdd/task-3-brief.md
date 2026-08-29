# Task 3: Add chart HTML containers in dashboard blade

**Files:**
- Modify: `resources/views/admin/dashboard.blade.php` (after line 326, before the Details Layout grid at line 329)

**Interfaces:**
- Consumes: `$monthlyStats`, `$roleDistribution`, `$topCompanies` from controller

**Steps:**

- [ ] **Step 1: Add charts section HTML**

In `resources/views/admin/dashboard.blade.php`, after the closing `</div>` of the Bento Grid (the 4 metric cards that end around line 326), BEFORE the Details Layout grid (line 329), insert:

```html
<!-- Analytics Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-lg">
    <!-- Chart 1: Registration Trends (spans 2 cols) -->
    <div class="lg:col-span-2 bg-surface rounded-xl border border-outline-variant shadow-sm overflow-hidden">
        <div class="p-lg border-b border-outline-variant flex justify-between items-center">
            <h3 class="text-headline-sm font-headline-sm text-on-background">Tendencia de Registros</h3>
            <span class="text-label-sm text-on-surface-variant">Últimos 6 meses</span>
        </div>
        <div class="p-lg" style="height: 320px;">
            <canvas id="trendChart"></canvas>
        </div>
    </div>

    <!-- Chart 2: Role Distribution (spans 1 col) -->
    <div class="bg-surface rounded-xl border border-outline-variant shadow-sm overflow-hidden">
        <div class="p-lg border-b border-outline-variant">
            <h3 class="text-headline-sm font-headline-sm text-on-background">Distribución por Roles</h3>
        </div>
        <div class="p-lg flex flex-col items-center" style="height: 320px;">
            <div class="flex-1 w-full flex items-center justify-center">
                <canvas id="roleChart"></canvas>
            </div>
            <div id="roleLegend" class="flex flex-wrap justify-center gap-3 mt-4"></div>
        </div>
    </div>
</div>

<!-- Chart 3: Top Companies -->
<div class="bg-surface rounded-xl border border-outline-variant shadow-sm overflow-hidden">
    <div class="p-lg border-b border-outline-variant flex justify-between items-center">
        <h3 class="text-headline-sm font-headline-sm text-on-background">Top Empresas con Más Ofertas</h3>
        <span class="text-label-sm text-on-surface-variant">Top 5</span>
    </div>
    <div class="p-lg" style="height: 280px;">
        <canvas id="companiesChart"></canvas>
    </div>
</div>
```

**IMPORTANT:** The insertion point is AFTER the 4 metric cards grid ends and BEFORE the existing Details Layout grid (which has the companies table and activity history). Look for the comment `<!-- Details Layout -->` or the grid that has `lg:grid-cols-3` containing the companies table.

- [ ] **Step 2: Commit**

```bash
git add resources/views/admin/dashboard.blade.php
git commit -m "feat: add chart containers to admin dashboard view"
```
