<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Institucional de Bolsa Laboral</title>
    <style>
        @page {
            margin: 12mm 10mm 15mm 10mm;
            size: a4 landscape;
        }

        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9px;
            color: #1e293b;
            line-height: 1.35;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }

        /* Running footer */
        footer {
            position: fixed;
            bottom: -8mm;
            left: 0;
            right: 0;
            height: 6mm;
            border-top: 1px solid #cbd5e1;
            padding-top: 2px;
            font-size: 8px;
            color: #64748b;
        }

        .footer-content {
            width: 100%;
        }

        .footer-left {
            float: left;
            width: 70%;
        }

        .footer-right {
            float: right;
            width: 30%;
            text-align: right;
        }

        .pagenum:before {
            content: counter(page);
        }

        /* Header block */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            border-bottom: 2px solid #002741;
            padding-bottom: 6px;
        }

        .header-title-box {
            vertical-align: middle;
        }

        .main-title {
            font-size: 14px;
            font-weight: bold;
            color: #002741;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 2px 0;
        }

        .sub-title {
            font-size: 10px;
            color: #006b60;
            font-weight: bold;
            margin: 0;
        }

        .header-meta-box {
            vertical-align: middle;
            text-align: right;
            font-size: 8.5px;
            color: #475569;
        }

        .header-meta-box strong {
            color: #0f172a;
        }

        /* Filter chips row */
        .filters-bar {
            background-color: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 5px 8px;
            margin-bottom: 8px;
            font-size: 8.5px;
        }

        .filters-table {
            width: 100%;
            border-collapse: collapse;
        }

        .filters-table td {
            padding: 1px 4px;
            vertical-align: middle;
        }

        /* KPI summary cards */
        .kpi-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px 0;
            margin-bottom: 8px;
        }

        .kpi-card {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 5px 8px;
            text-align: center;
        }

        .kpi-value {
            font-size: 13px;
            font-weight: bold;
            color: #002741;
            margin-bottom: 1px;
        }

        .kpi-label {
            font-size: 7.5px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* Main table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5px;
        }

        .data-table th {
            background-color: #002741;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8px;
            letter-spacing: 0.3px;
            padding: 5px 4px;
            border: 1px solid #001f35;
            text-align: center;
        }

        .data-table td {
            padding: 4px;
            border: 1px solid #cbd5e1;
            vertical-align: top;
        }

        .data-table tr.even {
            background-color: #f8fafc;
        }

        .data-table tr:hover {
            background-color: #f1f5f9;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 1px 5px;
            font-size: 7.5px;
            font-weight: bold;
            border-radius: 3px;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            white-space: nowrap;
        }

        .badge-student {
            background-color: #dbeafe;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }

        .badge-graduate {
            background-color: #f3e8ff;
            color: #6b21a8;
            border: 1px solid #e9d5ff;
        }

        .badge-accepted {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .badge-review {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .badge-rejected {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .badge-postulated {
            background-color: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }

        .badge-none {
            background-color: #f1f5f9;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }

        /* Helper classes */
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-muted { color: #64748b; }
        .text-primary { color: #002741; }
        .nowrap { white-space: nowrap; }

        .app-item {
            margin-bottom: 3px;
            padding-bottom: 2px;
            border-bottom: 1px dashed #e2e8f0;
        }
        .app-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
    </style>
</head>
<body>

    <!-- Running Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-left">
                <strong>{{ $institutionName }}</strong> — Sistema de Bolsa Laboral Institucional | Reporte Confidencial y Oficial
            </div>
            <div class="footer-right">
                Página <span class="pagenum"></span>
            </div>
        </div>
    </footer>

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td class="header-title-box">
                <h1 class="main-title">Reporte Institucional de Bolsa Laboral</h1>
                <p class="sub-title">{{ $institutionName }} — Convocatorias y Postulaciones ({{ $typeLabel }})</p>
            </td>
            <td class="header-meta-box">
                <div><strong>Emisión:</strong> {{ $date }}</div>
                <div><strong>Registros encontrados:</strong> {{ $totalEvaluated }}</div>
            </td>
        </tr>
    </table>

    <!-- Filters summary -->
    <div class="filters-bar">
        <table class="filters-table">
            <tr>
                <td style="width: 25%;"><strong>Población evaluada:</strong> {{ $typeLabel }}</td>
                <td style="width: 30%;"><strong>Programa de Estudio:</strong> {{ $programName }}</td>
                <td style="width: 25%;"><strong>Estado de postulación:</strong> {{ $statusLabel }}</td>
                <td style="width: 20%; text-align: right;">
                    @if(!empty($search))
                        <strong>Búsqueda:</strong> "{{ $search }}"
                    @else
                        <span class="text-muted">Sin término de búsqueda</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- Summary KPI Cards -->
    <table class="kpi-table">
        <tr>
            <td class="kpi-card" style="width: 25%;">
                <div class="kpi-value">{{ $totalEvaluated }}</div>
                <div class="kpi-label">Total {{ $typeLabel }}</div>
            </td>
            <td class="kpi-card" style="width: 25%;">
                <div class="kpi-value" style="color: #0284c7;">{{ $totalWithApps }}</div>
                <div class="kpi-label">Con Postulaciones</div>
            </td>
            <td class="kpi-card" style="width: 25%;">
                <div class="kpi-value" style="color: #64748b;">{{ max($totalEvaluated - $totalWithApps, 0) }}</div>
                <div class="kpi-label">Sin Postulaciones</div>
            </td>
            <td class="kpi-card" style="width: 25%;">
                <div class="kpi-value" style="color: #16a34a;">{{ $totalAccepted }}</div>
                <div class="kpi-label">Postulaciones Aceptadas</div>
            </td>
        </tr>
    </table>

    <!-- Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 3%;">N°</th>
                <th style="width: 9%;">Tipo</th>
                <th style="width: 8%;">DNI / Doc</th>
                <th style="width: 18%;">Nombres y Apellidos</th>
                <th style="width: 17%;">Contacto (Email / Teléfono)</th>
                <th style="width: 15%;">Programa de Estudio</th>
                <th style="width: 22%;">Postulaciones Realizadas</th>
                <th style="width: 8%;">Estado(s)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $index => $u)
                @php
                    $person = $u['person'] ?? [];
                    $apps = $u['applications'] ?? [];
                    $appsCount = count($apps);
                    $isEven = ($index % 2 === 1);
                @endphp
                <tr class="{{ $isEven ? 'even' : '' }}">
                    <td class="text-center font-bold">{{ $index + 1 }}</td>
                    <td class="text-center">
                        <span class="badge {{ ($u['rol_id'] ?? null) == 5 ? 'badge-graduate' : 'badge-student' }}">
                            {{ $u['role_name'] ?? (($u['rol_id'] ?? null) == 5 ? 'Egresado' : 'Estudiante') }}
                        </span>
                    </td>
                    <td class="text-center nowrap font-bold">{{ $person['document_number'] ?? '-' }}</td>
                    <td>
                        <strong>{{ $person['names'] ?? '-' }}</strong>
                    </td>
                    <td>
                        <div>{{ $u['email'] ?? '-' }}</div>
                        @if(!empty($person['phone']))
                            <div class="text-muted" style="font-size: 8px;">Telf: {{ $person['phone'] }}</div>
                        @endif
                    </td>
                    <td>
                        {{ $person['study_program'] ?? 'Sin asignar' }}
                    </td>
                    <td>
                        @if($appsCount > 0)
                            <div style="font-size: 8px;">
                                @foreach($apps as $appIdx => $app)
                                    <div class="app-item">
                                        <strong>{{ $appIdx + 1 }}. {{ $app['offer_title'] }}</strong>
                                        <div class="text-muted">{{ $app['company_name'] }} ({{ $app['created_at'] }})</div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <span class="text-muted" style="font-style: italic;">Sin postulaciones registradas</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($appsCount > 0)
                            @foreach($apps as $app)
                                @php
                                    $st = $app['status'] ?? 'postulated';
                                    $badgeClass = match($st) {
                                        'accepted' => 'badge-accepted',
                                        'under_review' => 'badge-review',
                                        'rejected' => 'badge-rejected',
                                        default => 'badge-postulated',
                                    };
                                @endphp
                                <div style="margin-bottom: 2px;">
                                    <span class="badge {{ $badgeClass }}">{{ $app['status_label'] }}</span>
                                </div>
                            @endforeach
                        @else
                            <span class="badge badge-none">Inactivo</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px; color: #64748b;">
                        <em>No se encontraron registros de estudiantes o egresados que coincidan con los filtros aplicados.</em>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
