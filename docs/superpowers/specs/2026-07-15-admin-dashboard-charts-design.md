# Admin Dashboard — Gráficos Espectaculares

**Fecha:** 2026-07-15
**Estado:** Aprobado

## Resumen

Agregar 3 gráficos interactivos al panel de administración usando Chart.js para visualizar tendencias temporales, distribución de usuarios por rol y ranking de empresas.

## Layout

```
┌─────────────────────────────────────────────────────────┐
│  [4 Tarjetas KPI existentes]                            │
├──────────────────────────────┬──────────────────────────┤
│  GRÁFICO 1: Tendencia       │  GRÁFICO 2: Distribución │
│  de Registros (Línea/Área)  │  por Roles (Dona)        │
│  2 columnas                 │  1 columna               │
├──────────────────────────────┴──────────────────────────┤
│  GRÁFICO 3: Top Empresas con Más Ofertas (Barras)      │
│  2 columnas (centrado)                                  │
├─────────────────────────────────────────────────────────┤
│  [Tabla Empresas Recientes] [Historial Actividad]       │
└─────────────────────────────────────────────────────────┘
```

## Gráfico 1 — Tendencia de Registros (Línea/Área)

- **Tipo:** Line chart con área degradada
- **Series:** Usuarios nuevos, Ofertas creadas, Postulaciones recibidas
- **Eje X:** Últimos 6 meses
- **Colores:** Primary (#002741), Secondary (#006b60), Accent (#ff9f43)
- **Tooltip:** Hover con valores exactos

## Gráfico 2 — Distribución por Roles (Dona)

- **Tipo:** Doughnut chart
- **Datos:** Conteo por rol (Admin, Docente, Estudiante, Empresa)
- **Centro:** Total de usuarios
- **Leyenda:** Debajo con iconos
- **Hover:** Resalta segmento y muestra %

## Gráfico 3 — Top Empresas (Barras Horizontales)

- **Tipo:** Horizontal bar chart
- **Datos:** Top 5 empresas por ofertas publicadas
- **Barras:** Gradiente primary→secondary, border-radius 8px
- **Labels:** Nombre izquierda, cantidad derecha

## Cambios en Controller

### `AdminController.php` — Nuevas queries

```php
// 1. Estadísticas mensuales (6 meses)
$months = collect();
for ($i = 5; $i >= 0; $i--) {
    $date = now()->subMonths($i);
    $months->push([
        'label' => $date->locale('es')->isoFormat('MMM'),
        'users' => User::whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)->count(),
        'offers' => JobOpportunityOffer::whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)->count(),
        'applications' => JobOpportunityApplication::whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)->count(),
    ]);
}
$monthlyStats = $months;

// 2. Distribución por roles
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

// 3. Top empresas
$topCompanies = JobOpportunityOffer::with('company')
    ->select('company_id', DB::raw('count(*) as total'))
    ->groupBy('company_id')
    ->orderByDesc('total')
    ->take(5)
    ->get()
    ->filter(fn($item) => $item->company)
    ->values();
```

### Variables a pasar a la vista

```php
compact(
    // ... existentes ...
    'monthlyStats',
    'roleDistribution',
    'topCompanies'
)
```

## Dependencias

```bash
npm install chart.js
```

## Implementación Blade

- Chart.js se carga vía Vite (`resources/js/app.js`)
- Datos se pasan de PHP a JS con `@json($variable)`
- Inicialización al final del template
- `maintainAspectRatio: false` para responsive

## Archivos a modificar

1. `app/Http/Controllers/AdminController.php` — Agregar queries
2. `resources/views/admin/dashboard.blade.php` — Agregar HTML de gráficos + JS
3. `package.json` — Agregar dependencia chart.js

## Criterios de aceptación

- [ ] 3 gráficos visibles en el dashboard de admin
- [ ] Datos reales desde la base de datos
- [ ] Colores consistentes con el theme Material Design 3
- [ ] Responsive en móvil y desktop
- [ ] Tooltips interactivos al hover
- [ ] Sin errores en consola del navegador
