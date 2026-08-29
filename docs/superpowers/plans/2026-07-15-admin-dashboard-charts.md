# Admin Dashboard Charts Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add 3 interactive Chart.js charts to the admin dashboard showing registration trends, role distribution, and top companies.

**Architecture:** Install Chart.js via npm, add backend queries in AdminController, render charts in the dashboard blade using `@json()` to pass data from PHP to JS.

**Tech Stack:** Laravel 12, Chart.js 4.x, Tailwind CSS 4, Vite

## Global Constraints

- PHP ^8.2
- Laravel ^12.0
- Tailwind CSS ^4.0.0
- Vite ^7.0.7
- Material Design 3 color tokens: primary #002741, secondary #006b60, accent #ff9f43
- Charts must be responsive (maintainAspectRatio: false)
- No CDN — all assets via Vite bundle

---

### Task 1: Install Chart.js dependency

**Files:**
- Modify: `package.json`

- [ ] **Step 1: Install chart.js via npm**

Run: `npm install chart.js`

Expected: `package.json` now includes `"chart.js"` in dependencies.

- [ ] **Step 2: Verify installation**

Run: `npm ls chart.js`
Expected: `chart.js@4.x.x`

- [ ] **Step 3: Commit**

```bash
git add package.json package-lock.json
git commit -m "feat: add chart.js dependency for admin dashboard charts"
```

---

### Task 2: Add backend queries in AdminController

**Files:**
- Modify: `app/Http/Controllers/AdminController.php:19-181`

**Interfaces:**
- Produces: `$monthlyStats`, `$roleDistribution`, `$topCompanies` (passed to view via compact)

- [ ] **Step 1: Add monthly statistics query**

In `AdminController.php`, inside the `dashboard()` method, after line 32 (`$totalApplications = ...`), add:

```php
// Monthly stats for charts (last 6 months)
$monthlyStats = collect();
for ($i = 5; $i >= 0; $i--) {
    $date = now()->subMonths($i);
    $monthlyStats->push([
        'label' => $date->locale('es')->isoFormat('MMM'),
        'users' => User::whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)->count(),
        'offers' => JobOpportunityOffer::whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)->count(),
        'applications' => JobOpportunityApplication::whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)->count(),
    ]);
}
```

- [ ] **Step 2: Add role distribution query**

After the monthly stats block, add:

```php
// Role distribution for doughnut chart
$roleDistribution = User::select('rol_id', DB::raw('count(*) as total'))
    ->groupBy('rol_id')
    ->get()
    ->map(function ($item) {
        $labels = [1 => 'Administradores', 2 => 'Docentes', 3 => 'Estudiantes', 4 => 'Empresas'];
        $colors = [1 => '#002741', 2 => '#006b60', 3 => '#ff9f43', 4 => '#18A999'];
        return [
            'label' => $labels[$item->rol_id] ?? 'Otro',
            'total' => $item->total,
            'color' => $colors[$item->rol_id] ?? '#94a3b8',
        ];
    });
```

- [ ] **Step 3: Add top companies query**

After the role distribution block, add:

```php
// Top 5 companies by offer count
$topCompanies = JobOpportunityOffer::with('company')
    ->select('company_id', DB::raw('count(*) as total'))
    ->groupBy('company_id')
    ->orderByDesc('total')
    ->take(5)
    ->get()
    ->filter(fn($item) => $item->company)
    ->values()
    ->map(function ($item) {
        return [
            'name' => $item->company->name,
            'total' => $item->total,
        ];
    });
```

- [ ] **Step 4: Add new variables to compact()**

In the `return view('admin.dashboard', compact(...))` call (line 166), add the three new variables:

```php
return view('admin.dashboard', compact(
    'totalUsers',
    'pendingCompanies',
    'activeOffers',
    'totalApplications',
    'recentCompanies',
    'recentActivity',
    'config',
    'users',
    'userGrowth',
    'appGrowth',
    'currentSearch',
    'currentRolId',
    'currentStatus',
    'studyPrograms',
    'monthlyStats',
    'roleDistribution',
    'topCompanies'
));
```

Also update the fallback compact() in the catch block (line 200) with empty defaults:

```php
$monthlyStats = collect();
$roleDistribution = collect();
$topCompanies = collect();
```

- [ ] **Step 5: Commit**

```bash
git add app/Http/Controllers/AdminController.php
git commit -m "feat: add chart data queries to AdminController"
```

---

### Task 3: Add chart HTML containers in dashboard blade

**Files:**
- Modify: `resources/views/admin/dashboard.blade.php` (after line 326, before the Details Layout grid)

**Interfaces:**
- Consumes: `$monthlyStats`, `$roleDistribution`, `$topCompanies` from controller

- [ ] **Step 1: Add charts section HTML**

In `resources/views/admin/dashboard.blade.php`, after the closing `</div>` of the Bento Grid (line 326), before the Details Layout grid (line 329), insert:

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

- [ ] **Step 2: Commit**

```bash
git add resources/views/admin/dashboard.blade.php
git commit -m "feat: add chart containers to admin dashboard view"
```

---

### Task 4: Add Chart.js initialization script

**Files:**
- Modify: `resources/views/admin/dashboard.blade.php` (before closing `</body>` or at end of file)

**Interfaces:**
- Consumes: `@json($monthlyStats)`, `@json($roleDistribution)`, `@json($topCompanies)`

- [ ] **Step 1: Add the chart initialization script**

At the end of `resources/views/admin/dashboard.blade.php`, before the closing `</body>` tag, add:

```html
@push('scripts')
<script type="module">
import Chart from 'chart.js/auto';

// Data from controller
const monthlyStats = @json($monthlyStats);
const roleDistribution = @json($roleDistribution);
const topCompanies = @json($topCompanies);

// --- Chart 1: Registration Trends (Line/Area) ---
const trendCtx = document.getElementById('trendChart').getContext('2d');

// Create gradients
const gradientUsers = trendCtx.createLinearGradient(0, 0, 0, 300);
gradientUsers.addColorStop(0, 'rgba(0, 39, 65, 0.3)');
gradientUsers.addColorStop(1, 'rgba(0, 39, 65, 0.0)');

const gradientOffers = trendCtx.createLinearGradient(0, 0, 0, 300);
gradientOffers.addColorStop(0, 'rgba(0, 107, 96, 0.3)');
gradientOffers.addColorStop(1, 'rgba(0, 107, 96, 0.0)');

const gradientApps = trendCtx.createLinearGradient(0, 0, 0, 300);
gradientApps.addColorStop(0, 'rgba(255, 159, 67, 0.3)');
gradientApps.addColorStop(1, 'rgba(255, 159, 67, 0.0)');

new Chart(trendCtx, {
    type: 'line',
    data: {
        labels: monthlyStats.map(m => m.label),
        datasets: [
            {
                label: 'Usuarios',
                data: monthlyStats.map(m => m.users),
                borderColor: '#002741',
                backgroundColor: gradientUsers,
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#002741',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
            },
            {
                label: 'Ofertas',
                data: monthlyStats.map(m => m.offers),
                borderColor: '#006b60',
                backgroundColor: gradientOffers,
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#006b60',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
            },
            {
                label: 'Postulaciones',
                data: monthlyStats.map(m => m.applications),
                borderColor: '#ff9f43',
                backgroundColor: gradientApps,
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#ff9f43',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
            },
        ],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            intersect: false,
            mode: 'index',
        },
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    usePointStyle: true,
                    pointStyle: 'circle',
                    padding: 20,
                    font: { family: 'Inter', size: 12 },
                },
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleFont: { family: 'Inter', size: 13 },
                bodyFont: { family: 'Inter', size: 12 },
                padding: 12,
                cornerRadius: 8,
                displayColors: true,
            },
        },
        scales: {
            x: {
                grid: { display: false },
                ticks: { font: { family: 'Inter', size: 11 } },
            },
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(0, 0, 0, 0.05)' },
                ticks: {
                    font: { family: 'Inter', size: 11 },
                    stepSize: 1,
                },
            },
        },
    },
});

// --- Chart 2: Role Distribution (Doughnut) ---
const roleCtx = document.getElementById('roleChart').getContext('2d');

new Chart(roleCtx, {
    type: 'doughnut',
    data: {
        labels: roleDistribution.map(r => r.label),
        datasets: [{
            data: roleDistribution.map(r => r.total),
            backgroundColor: roleDistribution.map(r => r.color),
            borderColor: '#ffffff',
            borderWidth: 3,
            hoverBorderWidth: 0,
            hoverOffset: 8,
        }],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '65%',
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleFont: { family: 'Inter', size: 13 },
                bodyFont: { family: 'Inter', size: 12 },
                padding: 12,
                cornerRadius: 8,
                callbacks: {
                    label: function(context) {
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const pct = total > 0 ? ((context.raw / total) * 100).toFixed(1) : 0;
                        return ` ${context.label}: ${context.raw} (${pct}%)`;
                    },
                },
            },
        },
    },
});

// Render custom legend
const legendContainer = document.getElementById('roleLegend');
const totalUsers = roleDistribution.reduce((sum, r) => sum + r.total, 0);
roleDistribution.forEach(r => {
    const pct = totalUsers > 0 ? ((r.total / totalUsers) * 100).toFixed(1) : 0;
    legendContainer.innerHTML += `
        <div class="flex items-center gap-1.5 text-xs">
            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: ${r.color}"></span>
            <span class="text-on-surface-variant font-medium">${r.label}</span>
            <span class="text-on-surface font-semibold">${pct}%</span>
        </div>
    `;
});

// --- Chart 3: Top Companies (Horizontal Bar) ---
const companiesCtx = document.getElementById('companiesChart').getContext('2d');

const barGradient = companiesCtx.createLinearGradient(0, 0, 500, 0);
barGradient.addColorStop(0, '#002741');
barGradient.addColorStop(1, '#006b60');

new Chart(companiesCtx, {
    type: 'bar',
    data: {
        labels: topCompanies.map(c => c.name),
        datasets: [{
            label: 'Ofertas',
            data: topCompanies.map(c => c.total),
            backgroundColor: barGradient,
            borderRadius: 8,
            borderSkipped: false,
            barThickness: 28,
        }],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        indexAxis: 'y',
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleFont: { family: 'Inter', size: 13 },
                bodyFont: { family: 'Inter', size: 12 },
                padding: 12,
                cornerRadius: 8,
                callbacks: {
                    label: function(context) {
                        return ` ${context.raw} ofertas publicadas`;
                    },
                },
            },
        },
        scales: {
            x: {
                beginAtZero: true,
                grid: { color: 'rgba(0, 0, 0, 0.05)' },
                ticks: {
                    font: { family: 'Inter', size: 11 },
                    stepSize: 1,
                },
            },
            y: {
                grid: { display: false },
                ticks: {
                    font: { family: 'Inter', size: 12, weight: '500' },
                    color: '#1a1a1a',
                },
            },
        },
    },
});
</script>
@endpush
```

- [ ] **Step 2: Verify @push is rendered**

Make sure the blade layout (`resources/views/layouts/app.blade.php` or equivalent) includes `@stack('scripts')` before `</body>`. If not, add it.

- [ ] **Step 3: Commit**

```bash
git add resources/views/admin/dashboard.blade.php
git commit -m "feat: add Chart.js initialization for dashboard charts"
```

---

### Task 5: Build and verify

**Files:** None (verification only)

- [ ] **Step 1: Run Vite build**

Run: `npm run build`
Expected: Build completes without errors.

- [ ] **Step 2: Start dev server and test**

Run: `php artisan serve`
Open: `http://localhost:8000/admin/dashboard`
Verify:
- 3 charts render correctly
- Data matches database
- Tooltips work on hover
- Responsive on mobile viewport
- No console errors

- [ ] **Step 3: Final commit if any fixes needed**

```bash
git add -A
git commit -m "fix: polish dashboard charts"
```

---

## Summary

| Task | Description | Files |
|------|-------------|-------|
| 1 | Install chart.js | package.json |
| 2 | Backend queries | AdminController.php |
| 3 | Chart HTML containers | dashboard.blade.php |
| 4 | Chart.js initialization script | dashboard.blade.php |
| 5 | Build & verify | — |
