<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Institucional de Bolsa Laboral</title>
    <style>
        @page {
            margin: 10mm 8mm 12mm 8mm;
            size: a4 portrait;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 8px;
            color: #1e293b;
            line-height: 1.3;
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
            padding-top: 3px;
            font-size: 7.5px;
            color: #64748b;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .pagenum:before {
            content: counter(page);
        }

        /* Header block */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }

        .main-title {
            font-size: 13px;
            font-weight: bold;
            color: #0f3d5e;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin: 0 0 2px 0;
        }

        .sub-title {
            font-size: 9px;
            color: #00796b;
            font-weight: bold;
            margin: 0;
        }

        .accent-bar {
            height: 3px;
            background-color: #0f3d5e;
            margin-bottom: 8px;
            border-radius: 1px;
        }

        /* Filter summary box */
        .filters-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 5px 8px;
            margin-bottom: 8px;
            font-size: 7.5px;
        }

        .filters-table {
            width: 100%;
            border-collapse: collapse;
        }

        .filters-table td {
            padding: 1px 3px;
            vertical-align: middle;
        }

        /* KPI summary cards */
        .kpi-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .kpi-cell {
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            padding: 5px 3px;
            text-align: center;
        }

        .kpi-num {
            font-size: 13px;
            font-weight: bold;
            line-height: 1.1;
        }

        .kpi-lbl {
            font-size: 7px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            margin-top: 2px;
        }

        /* Main data table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5px;
        }

        .data-table thead {
            display: table-header-group;
        }

        .data-table tfoot {
            display: table-footer-group;
        }

        .data-table tr {
            page-break-inside: avoid;
        }

        .data-table th {
            background-color: #0f3d5e;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 7.5px;
            letter-spacing: 0.2px;
            padding: 5px 3px;
            border: 1px solid #0a293f;
            text-align: center;
        }

        .data-table td {
            padding: 4px 4px;
            border: 1px solid #cbd5e1;
            vertical-align: top;
            line-height: 1.25;
        }

        .data-table tr.even {
            background-color: #f8fafc;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 1px 4px;
            font-size: 6.5px;
            font-weight: bold;
            border-radius: 3px;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            white-space: nowrap;
        }

        .badge-student {
            background-color: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        .badge-graduate {
            background-color: #faf5ff;
            color: #7e22ce;
            border: 1px solid #e9d5ff;
        }

        .badge-accepted {
            background-color: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }

        .badge-review {
            background-color: #fffbeb;
            color: #b45309;
            border: 1px solid #fde68a;
        }

        .badge-rejected {
            background-color: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .badge-postulated {
            background-color: #f0f9ff;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }

        .badge-none {
            background-color: #f1f5f9;
            color: #64748b;
            border: 1px solid #cbd5e1;
        }

        /* Helper classes */
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-muted { color: #64748b; }
        .text-primary { color: #0f3d5e; }
        .nowrap { white-space: nowrap; }

        .email-text {
            font-size: 7px;
            color: #0369a1;
            word-break: break-all;
            word-wrap: break-word;
        }

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
        <table class="footer-table">
            <tr>
                <td style="width: 75%; text-align: left;">
                    <strong>{{ $institutionName }}</strong> &bull; Sistema de Bolsa Laboral Institucional &bull; Reporte Oficial de Postulantes
                </td>
                <td style="width: 25%; text-align: right;">
                    Página <span class="pagenum"></span>
                </td>
            </tr>
        </table>
    </footer>

    <!-- Header -->
    <table class="header-table">
        <tr>
            @if(!empty($logoBase64))
                <td style="width: 15%; vertical-align: middle;">
                    <img src="{{ $logoBase64 }}" style="max-height: 42px; max-width: 95px;" alt="Logo Institucional">
                </td>
            @endif
            <td style="vertical-align: middle; padding-left: {{ !empty($logoBase64) ? '8px' : '0' }};">
                <h1 class="main-title">Reporte Institucional de Bolsa Laboral</h1>
                <p class="sub-title">{{ $institutionName }} &bull; Postulantes y Trazabilidad ({{ strtoupper($typeLabel) }})</p>
            </td>
            <td style="width: 32%; vertical-align: middle; text-align: right; font-size: 7.5px; color: #475569;">
                <div><strong>Emisión:</strong> {{ $date }}</div>
                <div><strong>Total Registros:</strong> {{ $totalEvaluated }}</div>
                <div><strong>Generado por:</strong> Administración</div>
            </td>
        </tr>
    </table>
    
    <div class="accent-bar"></div>

    <!-- Filters summary -->
    <div class="filters-box">
        <table class="filters-table">
            <tr>
                <td style="width: 32%;"><strong>Población:</strong> {{ $typeLabel }}</td>
                <td style="width: 36%;"><strong>Programa:</strong> {{ $programName }}</td>
                <td style="width: 32%; text-align: right;"><strong>Estado:</strong> {{ $statusLabel }}</td>
            </tr>
            @if(!empty($search))
            <tr>
                <td colspan="3" style="padding-top: 3px; color: #0369a1;">
                    <strong>Búsqueda activa:</strong> "{{ $search }}"
                </td>
            </tr>
            @endif
        </table>
    </div>

    <!-- Summary KPI Cards (4 cards in portrait) -->
    <table class="kpi-table">
        <tr>
            <td class="kpi-cell" style="width: 25%;">
                <div class="kpi-num text-primary">{{ $totalEvaluated }}</div>
                <div class="kpi-lbl">Total {{ $typeLabel }}</div>
            </td>
            <td class="kpi-cell" style="width: 25%;">
                <div class="kpi-num" style="color: #0284c7;">{{ $totalWithApps }}</div>
                <div class="kpi-lbl">Con Postulaciones</div>
            </td>
            <td class="kpi-cell" style="width: 25%;">
                <div class="kpi-num" style="color: #64748b;">{{ max($totalEvaluated - $totalWithApps, 0) }}</div>
                <div class="kpi-lbl">Sin Postulaciones</div>
            </td>
            <td class="kpi-cell" style="width: 25%;">
                <div class="kpi-num" style="color: #059669;">{{ $totalAccepted }}</div>
                <div class="kpi-lbl">Postulaciones Aceptadas</div>
            </td>
        </tr>
    </table>

    <!-- Main Data Table (Portrait Optimized) -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;">N°</th>
                <th style="width: 27%;">Postulante / Identificación</th>
                <th style="width: 18%;">Programa de Estudio</th>
                <th style="width: 21%;">Contacto</th>
                <th style="width: 30%;">Postulaciones & Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $index => $u)
                @php
                    $person = $u['person'] ?? [];
                    $apps = $u['applications'] ?? [];
                    $appsCount = count($apps);
                    $isEven = ($index % 2 === 1);
                    $isGrad = ($u['rol_id'] ?? null) == 5;
                @endphp
                <tr class="{{ $isEven ? 'even' : '' }}">
                    <!-- N° -->
                    <td class="text-center font-bold" style="color: #64748b;">{{ $index + 1 }}</td>
                    
                    <!-- Postulante / Identificación -->
                    <td>
                        <div style="font-weight: bold; font-size: 8px; color: #0f172a; margin-bottom: 2px;">
                            {{ $person['names'] ?? '-' }}
                        </div>
                        <div>
                            <span class="badge {{ $isGrad ? 'badge-graduate' : 'badge-student' }}">
                                {{ $isGrad ? 'Egresado' : 'Estudiante' }}
                            </span>
                            <span style="font-size: 7px; color: #475569; margin-left: 3px;">
                                DNI: <strong>{{ $person['document_number'] ?? '-' }}</strong>
                            </span>
                        </div>
                    </td>

                    <!-- Programa de Estudio -->
                    <td>
                        <div style="font-size: 7.5px; color: #1e293b;">
                            {{ $person['study_program'] ?? 'Sin asignar' }}
                        </div>
                    </td>

                    <!-- Contacto -->
                    <td>
                        <div class="email-text">{{ $u['email'] ?? '-' }}</div>
                        <div style="font-size: 7px; color: #64748b; margin-top: 1px;">
                            Tel: <strong>{{ !empty($person['phone']) ? $person['phone'] : 'Sin teléf.' }}</strong>
                        </div>
                    </td>

                    <!-- Postulaciones & Estado -->
                    <td>
                        @if($appsCount > 0)
                            <div style="font-size: 7px;">
                                @foreach($apps as $appIdx => $app)
                                    @php
                                        $st = $app['status'] ?? 'postulated';
                                        $badgeClass = match($st) {
                                            'accepted' => 'badge-accepted',
                                            'under_review' => 'badge-review',
                                            'rejected' => 'badge-rejected',
                                            default => 'badge-postulated',
                                        };
                                    @endphp
                                    <div class="app-item">
                                        <div style="display: table; width: 100%;">
                                            <div style="display: table-cell; vertical-align: top;">
                                                <strong style="color: #0f172a;">{{ $appIdx + 1 }}. {{ $app['offer_title'] }}</strong>
                                                <div class="text-muted" style="font-size: 6.5px;">{{ $app['company_name'] }} ({{ $app['created_at'] }})</div>
                                            </div>
                                            <div style="display: table-cell; vertical-align: top; text-align: right; width: 60px;">
                                                <span class="badge {{ $badgeClass }}">{{ $app['status_label'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <span class="badge badge-none">Sin postulaciones (Inactivo)</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 25px; color: #64748b; font-size: 8.5px;">
                        <em>No se encontraron registros de estudiantes o egresados que coincidan con los criterios aplicados.</em>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
