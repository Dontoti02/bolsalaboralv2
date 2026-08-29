# Task 2: Add backend queries in AdminController

**Files:**
- Modify: `app/Http/Controllers/AdminController.php:19-181`

**Interfaces:**
- Produces: `$monthlyStats`, `$roleDistribution`, `$topCompanies` (passed to view via compact)

**Steps:**

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
