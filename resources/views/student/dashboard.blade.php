<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Portal del Estudiante</title>
    <link rel="icon" href="{{ $config['favicon'] ?? '/assets/favicon.png' }}" />
    <style>
        :root {
            --primary-color: {{ $config['primary_color'] ?? '#002741' }};
            --primary-container-color: {{ $config['primary_container_color'] ?? ($config['primary_color'] ?? '#0f3d5e') }};
            --secondary-color: {{ $config['secondary_color'] ?? '#006b60' }};
            --secondary-container-color: {{ $config['secondary_container_color'] ?? '#7df7e4' }};
            --accent-color: {{ $config['accent_color'] ?? '#ff9f43' }};
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&amp;family=Manrope:wght@600;700;800&amp;display=swap" rel="stylesheet">

    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
        }
    </style>
</head>
<body class="bg-background text-on-background font-body-md min-h-screen flex">

    @php
        $sidebarConfig = [
            'logo' => $config['logo'] ?? '/assets/logo.png',
            'brand' => 'Bolsa Laboral',
            'subtitle' => 'Portal Estudiantil',
            'active' => '',
            'home_tab' => 'jobs',
            'show_publish' => false,
            'show_help' => true,
            'help_label' => 'Centro de Ayuda',
            'items' => [
                [
                    'label' => '',
                    'items' => [
                        ['key' => 'jobs', 'icon' => 'work', 'label' => 'Ofertas Laborales'],
                        ['key' => 'applications', 'icon' => 'person_check', 'label' => 'Mis Postulaciones'],
                        ['key' => 'cvs', 'icon' => 'description', 'label' => 'Mis CVs'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('partials.sidebar', ['sidebarConfig' => $sidebarConfig, 'config' => $config ?? []])

    

<!-- Main Content Wrapper -->
<div class="flex-1 md:ml-0 flex flex-col min-h-screen">
    <!-- TopNavBar -->
    <header class="sticky top-0 right-0 w-full bg-surface-bright border-b border-outline-variant z-20 h-[72px]">
        <div class="flex justify-between items-center px-lg py-md h-full">
            <!-- Search and Mobile Toggle -->
            <div class="flex items-center gap-4 flex-1 max-w-md">
                <!-- Mobile Menu Trigger -->
                <button id="open-sidebar-btn" class="md:hidden text-on-surface-variant hover:bg-surface-container-high p-2 rounded-full">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <div class="relative w-full hidden md:block">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
                    <input class="w-full pl-10 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-body-sm font-body-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" placeholder="Buscar..." type="text">
                </div>
            </div>
            <!-- Trailing Actions & Profile -->
            <div class="flex items-center gap-md">
                <button class="text-on-surface-variant hover:bg-surface-container-high rounded-full p-2 transition-all">
                    <span class="material-symbols-outlined">notifications</span>
                </button>
                <button class="text-on-surface-variant hover:bg-surface-container-high rounded-full p-2 transition-all">
                    <span class="material-symbols-outlined">help</span>
                </button>
                <div class="w-px h-6 bg-outline-variant mx-sm"></div>
                
                <div class="flex items-center gap-3 ml-sm">
                    <div class="text-right hidden sm:block">
                        <p class="text-label-md font-semibold text-on-surface leading-none">{{ Auth::user()->person->names ?? 'Estudiante' }}</p>
                        <span class="text-[11px] text-on-surface-variant">Estudiante</span>
                    </div>
                    <div class="w-10 h-10 rounded-full overflow-hidden border border-outline-variant bg-primary-container text-on-primary flex items-center justify-center font-bold">
                        {{ substr(Auth::user()->person->names ?? 'E', 0, 1) }}
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Main Dashboard Canvas -->
    <main class="flex-1 p-lg md:p-2xl space-y-xl max-w-container-max mx-auto w-full">
        
        <!-- ================= PANEL 1: OFERTAS LABORALES ================= -->
        <div id="panel-jobs" class="tab-panel space-y-xl">
            <!-- Welcome Section -->
            <div class="bg-surface-container-lowest rounded-xl p-xl shadow-sm border border-outline-variant relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
                <div class="relative z-10">
                    <h1 class="font-headline-lg text-headline-lg text-on-surface mb-2 font-black">¡Hola, {{ explode(' ', Auth::user()->person->names ?? 'Estudiante')[0] }}! Bienvenido a tu panel de carrera.</h1>
                    <p class="text-on-surface-variant font-body-lg">Aquí tienes un resumen de tu actividad reciente y tus oportunidades.</p>
                </div>
                <div class="mt-lg flex flex-wrap gap-4">
                    <button onclick="switchTab('jobs')" class="bg-primary text-on-primary font-semibold text-label-md px-6 py-2 rounded-lg hover:opacity-90 transition-opacity shadow-sm">Buscar empleos</button>
                    <button onclick="switchTab('cvs')" class="border border-outline-variant bg-surface text-on-surface-variant font-semibold text-label-md px-6 py-2 rounded-lg hover:bg-surface-container-low transition-colors">Subir CV</button>
                    <button onclick="switchTab('applications')" class="border border-outline-variant bg-surface text-on-surface-variant font-semibold text-label-md px-6 py-2 rounded-lg hover:bg-surface-container-low transition-colors">Mis postulaciones</button>
                </div>
            </div>

            <!-- Metrics Bento Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md">
                <div class="bg-surface-container-lowest rounded-xl p-lg border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-label-md font-label-md text-on-surface-variant">Postulaciones realizadas</h3>
                        <div class="p-2 bg-primary-fixed rounded-lg text-on-primary-fixed">
                            <span class="material-symbols-outlined text-[20px]">send</span>
                        </div>
                    </div>
                    <p class="text-display-lg font-display-lg text-on-surface">{{ $totalApplications }}</p>
                </div>
                <div class="bg-surface-container-lowest rounded-xl p-lg border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-label-md font-label-md text-on-surface-variant">Pendientes</h3>
                        <div class="p-2 bg-tertiary-fixed rounded-lg text-on-tertiary-fixed">
                            <span class="material-symbols-outlined text-[20px]">pending_actions</span>
                        </div>
                    </div>
                    <p class="text-display-lg font-display-lg text-on-surface">{{ $pendingApplications }}</p>
                </div>
                <div class="bg-surface-container-lowest rounded-xl p-lg border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-label-md font-label-md text-on-surface-variant">Aprobadas</h3>
                        <div class="p-2 bg-secondary-fixed rounded-lg text-on-secondary-fixed">
                            <span class="material-symbols-outlined text-[20px]">check_circle</span>
                        </div>
                    </div>
                    <p class="text-display-lg font-display-lg text-on-surface">{{ $acceptedApplications }}</p>
                </div>
                <div class="bg-surface-container-lowest rounded-xl p-lg border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-label-md font-label-md text-on-surface-variant">CVs cargados</h3>
                        <div class="p-2 bg-surface-variant rounded-lg text-on-surface-variant">
                            <span class="material-symbols-outlined text-[20px]">description</span>
                        </div>
                    </div>
                    <p class="text-display-lg font-display-lg text-on-surface">{{ $cvsCount }}</p>
                </div>
            </div>

            <!-- Main Content Area: Recommended Jobs & Recent Applications -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-lg">
                <!-- Recommended Jobs (2/3 width on large screens) -->
                <div class="lg:col-span-2 space-y-md">
                    <div class="flex justify-between items-center">
                        <h2 class="text-headline-sm font-headline-sm text-on-surface">Empleos Recomendados</h2>
                        <a class="text-label-sm font-label-sm text-primary-container hover:underline" href="#">Ver todos</a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                        @forelse($activeOffers as $offer)
                        <div onclick="openJobDetailPage({{ $offer->id }})" class="bg-surface-container-lowest rounded-xl p-lg border border-outline-variant shadow-sm hover:border-primary transition-colors group cursor-pointer">
                            <div class="flex justify-between items-start mb-4">
                                <div class="w-12 h-12 bg-surface-container rounded-lg flex items-center justify-center border border-outline-variant overflow-hidden shrink-0">
                                    @if(!empty($offer->company->logo))
                                        <img src="{{ $offer->company->logo }}" class="w-full h-full object-contain" alt="Logo">
                                    @else
                                        <span class="material-symbols-outlined text-on-surface-variant">corporate_fare</span>
                                    @endif
                                </div>
                                <span class="bg-secondary-fixed/20 text-on-secondary-container px-2 py-1 rounded text-label-sm font-label-sm font-semibold">{{ $offer->category->name ?? 'General' }}</span>
                            </div>
                            <h3 class="text-headline-sm font-headline-sm mb-1 group-hover:text-primary-container transition-colors">{{ $offer->title }}</h3>
                            <p class="text-body-sm font-body-sm text-on-surface-variant mb-4">{{ $offer->company_name }} • {{ $offer->location_name }}</p>
                            <div class="flex gap-2 flex-wrap">
                                <span class="px-2 py-1 bg-surface-container-low text-on-surface-variant rounded-md text-label-sm">{{ $offer->workSchedule->name ?? 'Jornada completa' }}</span>
                                <span class="px-2 py-1 bg-surface-container-low text-on-surface-variant rounded-md text-label-sm">{{ $offer->contractType->name ?? 'Contrato' }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-2 text-center py-xl text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl text-outline mb-2 block">work_off</span>
                            <p class="font-semibold">No hay ofertas activas disponibles</p>
                            <p class="text-body-sm mt-1">Las nuevas ofertas aparecerán aquí.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                
                <!-- Recent Applications Table (1/3 width on large screens) -->
                <div class="lg:col-span-1 space-y-md">
                    <div class="flex justify-between items-center">
                        <h2 class="text-headline-sm font-headline-sm text-on-surface">Postulaciones Recientes</h2>
                    </div>
                    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
                        <table class="w-full text-left border-collapse">
                            <tbody>
                                @forelse($recentApplications->take(3) as $app)
                                @php
                                    $statusText = 'Pendiente';
                                    $statusClass = 'bg-tertiary-fixed text-on-tertiary-fixed-variant';
                                    if ($app->status === 'accepted') {
                                        $statusText = 'Aprobada';
                                        $statusClass = 'bg-secondary-fixed/50 text-on-secondary-container';
                                    } elseif ($app->status === 'rejected') {
                                        $statusText = 'Rechazada';
                                        $statusClass = 'bg-error-container text-on-error-container';
                                    }
                                @endphp
                                <tr class="border-b border-surface-container-high hover:bg-surface-container-low transition-colors">
                                    <td class="p-md">
                                        <p class="font-label-md text-on-surface">{{ $app->offer->title ?? 'Puesto' }}</p>
                                        <p class="text-body-sm text-on-surface-variant">{{ $app->offer->company->name ?? 'Empresa' }}</p>
                                    </td>
                                    <td class="p-md text-right">
                                        <span class="{{ $statusClass }} px-2 py-1 rounded text-label-sm font-label-sm font-semibold">{{ $statusText }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="p-md text-center text-on-surface-variant">No ha realizado ninguna postulación.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="p-sm bg-surface-container-lowest border-t border-outline-variant text-center">
                            <button onclick="switchTab('applications')" class="text-label-sm font-label-sm text-on-surface-variant hover:text-primary-container">Ver todas</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= PANEL 2: MIS POSTULACIONES ================= -->
        <div id="panel-applications" class="tab-panel space-y-xl hidden">
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
                <div class="p-lg border-b border-outline-variant">
                    <h2 class="text-headline-sm font-headline-sm text-on-surface">Historial de Postulaciones</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-low text-on-surface-variant border-b border-outline-variant">
                                <th class="p-4 text-label-sm font-label-sm font-semibold">Puesto</th>
                                <th class="p-4 text-label-sm font-label-sm font-semibold">Empresa</th>
                                <th class="p-4 text-label-sm font-label-sm font-semibold">Fecha de Postulación</th>
                                <th class="p-4 text-label-sm font-label-sm font-semibold">Estado</th>
                                <th class="p-4 text-label-sm font-label-sm font-semibold text-right">Detalles</th>
                            </tr>
                        </thead>
                        <tbody class="text-body-md font-body-md">
                            @forelse($recentApplications as $app)
                            @php
                                $statusText = 'Pendiente';
                                $statusClass = 'bg-tertiary-fixed text-on-tertiary-fixed-variant';
                                if ($app->status === 'accepted') {
                                    $statusText = 'Aprobada';
                                    $statusClass = 'bg-secondary-fixed/50 text-on-secondary-container';
                                } elseif ($app->status === 'rejected') {
                                    $statusText = 'Rechazada';
                                    $statusClass = 'bg-error-container text-on-error-container';
                                }
                            @endphp
                            <tr class="border-b border-surface-container-high hover:bg-surface-container-low transition-colors">
                                <td class="p-4 font-semibold text-on-surface">{{ $app->offer->title ?? 'Puesto' }}</td>
                                <td class="p-4 text-on-surface-variant">{{ $app->offer->company->name ?? 'Empresa' }}</td>
                                <td class="p-4 text-on-surface-variant">{{ $app->created_at ? $app->created_at->format('d M Y') : '-' }}</td>
                                <td class="p-4">
                                    <span class="{{ $statusClass }} px-2.5 py-1 rounded-full text-label-sm font-label-sm font-semibold">{{ $statusText }}</span>
                                </td>
                                <td class="p-4 text-right">
                                    <button onclick="alert('Feedback del empleador: {{ $app->feedback ?: 'Ninguno' }}')" class="text-primary hover:underline text-label-sm font-label-sm font-semibold">Ver seguimiento</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-on-surface-variant">No ha realizado ninguna postulación aún.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ================= PANEL 3: MIS CVS ================= -->
        <div id="panel-cvs" class="tab-panel space-y-xl hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-lg">
                <!-- Upload Panel -->
                <div onclick="document.getElementById('cv-file-input').click()" class="lg:col-span-1 bg-surface-container-lowest border-2 border-dashed border-outline-variant rounded-2xl p-lg flex flex-col items-center justify-center text-center hover:border-primary transition-all cursor-pointer group">
                    <div class="w-12 h-12 rounded-full bg-primary-fixed text-primary flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-2xl">cloud_upload</span>
                    </div>
                    <h3 class="font-headline-sm text-[16px] font-bold text-on-surface mb-2">Sube un nuevo Currículum</h3>
                    <p class="text-body-sm text-on-surface-variant mb-4">Soporta formato PDF (Máx. 5MB).</p>
                    <button class="px-4 py-2 bg-primary text-on-primary rounded-xl text-label-md font-label-md font-semibold hover:bg-primary/95 transition-all">Seleccionar Archivo</button>
                    <form id="cv-upload-form" class="hidden">
                        @csrf
                        <input type="file" id="cv-file-input" name="cv" accept=".pdf" onchange="uploadCv(this)">
                    </form>
                </div>
                
                <!-- Document List -->
                <div class="lg:col-span-2 bg-surface-container-lowest rounded-2xl border border-outline-variant p-lg shadow-sm">
                    <h3 class="font-headline-sm text-headline-sm text-on-surface mb-4">Mis Currículums Subidos</h3>
                    <div id="cv-list-container" class="space-y-md">
                        @forelse($cvs as $cv)
                        <div id="cv-item-{{ $cv->id }}" class="flex items-center justify-between p-md bg-surface-container-low rounded-xl border border-outline-variant">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-red-100 text-red-700 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-2xl">picture_as_pdf</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-on-surface text-body-sm leading-tight">{{ $cv->filename }}</h4>
                                    <p class="text-[11px] text-on-surface-variant mt-0.5">Subido el {{ $cv->uploaded_at }} • Versión {{ $cv->version }} @if($loop->first) • <span class="text-green-600 font-semibold">Principal</span> @endif</p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('student.cv.download', $cv->id) }}" class="p-2 text-on-surface-variant hover:bg-surface-container-high rounded-full transition-colors flex items-center justify-center">
                                    <span class="material-symbols-outlined text-xl">download</span>
                                </a>
                                <button onclick="deleteCv({{ $cv->id }})" class="p-2 text-red-600 hover:bg-red-50 rounded-full transition-colors flex items-center justify-center">
                                    <span class="material-symbols-outlined text-xl">delete</span>
                                </button>
                            </div>
                        </div>
                        @empty
                        <p id="no-cvs-placeholder" class="text-center py-8 text-on-surface-variant">No ha subido ningún currículum todavía.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= PANEL 4: DETALLE DE EMPLEO (PAGE VIEW) ================= -->
        <div id="panel-job-detail" class="tab-panel space-y-xl hidden">
            <!-- Back Button & Breadcrumbs -->
            <div class="flex items-center gap-2">
                <button onclick="switchTab('jobs')" class="flex items-center gap-1.5 px-3 py-1.5 bg-surface-container-low border border-outline-variant hover:bg-surface-container-high rounded-xl text-primary font-semibold text-body-sm transition-all">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    Volver al listado
                </button>
            </div>

            <!-- Job Page Container -->
            <div class="bg-surface-container-lowest rounded-3xl border border-outline-variant flex flex-col lg:flex-row overflow-hidden shadow-sm">
                <!-- Left Column: Details & Tabs (2/3 width) -->
                <div class="w-full lg:w-2/3 p-6 md:p-8 flex flex-col space-y-6">
                    <!-- Header Info -->
                    <div class="flex items-start gap-4">
                        <div id="detail-company-logo" class="w-16 h-16 bg-surface-container rounded-xl flex items-center justify-center border border-outline-variant shrink-0">
                            <span class="material-symbols-outlined text-4xl text-on-surface-variant">corporate_fare</span>
                        </div>
                        <div class="space-y-1">
                            <h2 id="detail-title" class="font-headline-md text-headline-md text-on-surface leading-tight text-[22px] md:text-[26px]">Título de la Oferta</h2>
                            <p id="detail-company" class="text-body-md text-on-surface-variant font-medium">Nombre de la Empresa</p>
                            <div class="flex flex-wrap gap-2 text-body-xs text-outline font-semibold mt-1">
                                <span id="detail-location-badge" class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">location_on</span>
                                    <span id="detail-location">Lima, Perú</span>
                                </span>
                                <span id="detail-salary-badge" class="flex items-center gap-1 hidden">
                                    <span class="material-symbols-outlined text-[14px]">payments</span>
                                    <span id="detail-salary">S/. 2,000</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Headers -->
                    <div class="flex border-b border-outline-variant">
                        <button id="tab-btn-detail" onclick="switchJobTab('detail')" class="px-4 py-2 border-b-2 border-primary text-primary font-bold text-label-md transition-all">
                            Detalle del empleo
                        </button>
                        <button id="tab-btn-related" onclick="switchJobTab('related')" class="px-4 py-2 border-b-2 border-transparent text-on-surface-variant font-medium text-label-md hover:text-on-surface transition-all">
                            Empleos relacionados
                        </button>
                    </div>

                    <!-- Tab Content -->
                    <div class="space-y-4">
                        <!-- Tab: Detalle -->
                        <div id="job-tab-detail" class="space-y-6">
                            <div>
                                <h4 class="font-bold text-body-md text-on-surface mb-2">Descripción del puesto</h4>
                                <div id="detail-desc" class="text-body-sm text-on-surface-variant whitespace-pre-line leading-relaxed">
                                    Descripción completa del puesto...
                                </div>
                            </div>

                            <div id="detail-req-section">
                                <h4 class="font-bold text-body-md text-on-surface mb-2">Requisitos</h4>
                                <div id="detail-req" class="text-body-sm text-on-surface-variant whitespace-pre-line leading-relaxed">
                                    Requisitos requeridos...
                                </div>
                            </div>

                            <div id="detail-benefits-section">
                                <h4 class="font-bold text-body-md text-on-surface mb-2">Beneficios</h4>
                                <div id="detail-benefits" class="text-body-sm text-on-surface-variant whitespace-pre-line leading-relaxed">
                                    Beneficios ofrecidos...
                                </div>
                            </div>

                            <!-- Quick Metadata Grid -->
                            <div class="grid grid-cols-2 gap-4 bg-surface-container-low p-4 rounded-2xl border border-outline-variant/60">
                                <div>
                                    <span class="text-body-xs text-on-surface-variant block">Jornada</span>
                                    <span id="detail-schedule" class="font-semibold text-body-sm text-on-surface">Jornada completa</span>
                                </div>
                                <div>
                                    <span class="text-body-xs text-on-surface-variant block">Tipo de Contrato</span>
                                    <span id="detail-contract" class="font-semibold text-body-sm text-on-surface">Contrato indefinido</span>
                                </div>
                                <div>
                                    <span class="text-body-xs text-on-surface-variant block">Categoría</span>
                                    <span id="detail-category" class="font-semibold text-body-sm text-on-surface">General</span>
                                </div>
                                <div>
                                    <span class="text-body-xs text-on-surface-variant block">Fecha límite</span>
                                    <span id="detail-deadline" class="font-semibold text-body-sm text-on-surface">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- Tab: Relacionados -->
                        <div id="job-tab-related" class="hidden space-y-4 max-h-[500px] overflow-y-auto pr-2">
                            <div id="related-jobs-list" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <!-- Related jobs cards will be injected here -->
                            </div>
                            <p id="no-related-placeholder" class="text-center py-8 text-on-surface-variant hidden">
                                No se encontraron empleos relacionados.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Actions (1/3 width) -->
                <div class="w-full lg:w-1/3 bg-surface-container-low p-6 md:p-8 border-t lg:border-t-0 lg:border-l border-outline-variant/60 flex flex-col justify-between space-y-6">
                    <div class="space-y-6">
                        <h3 class="font-bold text-headline-sm text-on-surface">Acciones</h3>
                        
                        <!-- Main Action Area -->
                        <div id="action-initial-view" class="space-y-3">
                            <button id="btn-apply-trigger" onclick="showApplyForm()" class="w-full py-3 bg-primary text-on-primary rounded-xl font-semibold flex items-center justify-center gap-2 hover:bg-primary/95 transition-all shadow-md">
                                <span class="material-symbols-outlined text-[20px]">send</span>
                                Postular ahora
                            </button>
                            <button onclick="shareJob()" class="w-full py-3 bg-surface-container border border-outline-variant text-primary rounded-xl font-semibold flex items-center justify-center gap-2 hover:bg-surface-container-high transition-all">
                                <span class="material-symbols-outlined text-[20px]">share</span>
                                Compartir empleo
                            </button>
                        </div>

                        <!-- Applied Status Badge -->
                        <div id="applied-status-badge" class="hidden p-4 bg-green-50 border border-green-200 rounded-2xl flex items-start gap-3">
                            <span class="material-symbols-outlined text-green-600">check_circle</span>
                            <div>
                                <span class="font-bold text-green-800 text-body-sm block">¡Ya postulaste!</span>
                                <span id="applied-status-text" class="text-green-700 text-body-xs">Tu postulación fue recibida.</span>
                            </div>
                        </div>

                        <!-- Apply Form (shown when btn-apply-trigger clicked) -->
                        <div id="action-apply-form" class="hidden space-y-4">
                            <h4 class="font-bold text-body-sm text-on-surface">Enviar Postulación</h4>
                            <form id="detail-apply-form" class="space-y-4">
                                <input type="hidden" id="detail-offer-id">
                                <div>
                                    <label for="detail-cv-select" class="block font-semibold text-body-xs text-on-surface mb-1">Seleccionar Currículum:</label>
                                    <select id="detail-cv-select" class="w-full p-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg text-body-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                                        @foreach($cvs as $cv)
                                        <option value="{{ $cv->id }}">{{ $cv->filename }} (Versión {{ $cv->version }})</option>
                                        @endforeach
                                    </select>
                                    @if($cvs->isEmpty())
                                    <p class="text-xs text-red-600 mt-1">¡Debe subir un currículum primero en la pestaña "Mis CVs"!</p>
                                    @endif
                                </div>
                                <div>
                                    <label for="detail-msg" class="block font-semibold text-body-xs text-on-surface mb-1">Mensaje (Opcional):</label>
                                    <textarea id="detail-msg" rows="4" class="w-full p-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg text-body-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none" placeholder="Escriba un mensaje para el reclutador..."></textarea>
                                </div>
                                <div class="flex gap-2 pt-2">
                                    <button type="button" onclick="cancelApplyForm()" class="flex-1 py-2.5 border border-outline-variant text-on-surface-variant rounded-lg text-label-md font-semibold hover:bg-surface-container-high transition-all">
                                        Volver
                                    </button>
                                    <button type="button" onclick="submitDetailedApplication()" class="flex-1 py-2.5 bg-primary text-on-primary rounded-lg text-label-md font-semibold hover:bg-primary/95 transition-all shadow-sm">
                                        Confirmar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Tip/Safety advice inside sidebar -->
                    <div class="text-[11px] text-on-surface-variant leading-tight border-t border-outline-variant/60 pt-4 mt-auto">
                        <p class="font-semibold mb-1">Consejo de seguridad:</p>
                        No compartas información personal sensible como contraseñas o datos financieros durante tu postulación.
                    </div>
                </div>
            </div>
        </div>
        
    </main>
</div>

<script>
    function switchTab(tabId) {
        // Find all tabs and panels
        const tabBtns = document.querySelectorAll('.tab-btn');
        const panels = document.querySelectorAll('.tab-panel');
        
        // Hide all panels
        panels.forEach(p => p.classList.add('hidden'));
        
        // Show target panel
        const targetPanel = document.getElementById('panel-' + tabId);
        if (targetPanel) {
            targetPanel.classList.remove('hidden');
        }
        
        // Reset button states
        tabBtns.forEach(btn => {
            const currentTabId = btn.getAttribute('data-tab');
            if (currentTabId === tabId || (tabId === 'job-detail' && currentTabId === 'jobs')) {
                // Set active style
                btn.className = "tab-btn w-full flex items-center gap-3 px-4 py-3 scale-95 transition-all text-left bg-surface-container-high text-on-surface font-semibold border-l-4 border-primary rounded-r-lg rounded-l-none shadow-sm";
            } else {
                // Set inactive style
                btn.className = "tab-btn w-full flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:bg-surface-container-high rounded-lg scale-95 transition-all text-left";
            }
        });
        
        // On mobile, close sidebar automatically
        if (window.innerWidth < 768) {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Add click events to tab buttons
        const tabBtns = document.querySelectorAll('.tab-btn');
        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                switchTab(tabId);
            });
        });
        
        // Mobile Sidebar Toggle
        const openBtn = document.getElementById('open-sidebar-btn');
        const closeBtn = document.getElementById('close-sidebar-btn');
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        
        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }
        
        if (openBtn) openBtn.addEventListener('click', toggleSidebar);
        if (closeBtn) closeBtn.addEventListener('click', toggleSidebar);
        if (backdrop) backdrop.addEventListener('click', toggleSidebar);
        
        // Initialize to show 'jobs' panel first or the tab specified in URL
        const urlParams = new URLSearchParams(window.location.search);
        const tabToOpen = urlParams.get('tab') || 'jobs';
        const validTabs = ['jobs', 'applications', 'cvs'];
        switchTab(validTabs.includes(tabToOpen) ? tabToOpen : 'jobs');
    });

    let toastTimeout = null;
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        const toastMsg = document.getElementById('toast-message');
        const toastIcon = document.getElementById('toast-icon');
        if (!toast || !toastMsg) return;

        toastMsg.textContent = message;

        // Custom styling based on type
        if (type === 'error') {
            toastIcon.textContent = 'error';
            toastIcon.className = 'material-symbols-outlined text-[20px] text-error';
        } else if (type === 'info') {
            toastIcon.textContent = 'info';
            toastIcon.className = 'material-symbols-outlined text-[20px] text-primary';
        } else {
            toastIcon.textContent = 'check_circle';
            toastIcon.className = 'material-symbols-outlined text-[20px] text-emerald-600';
        }

        toast.classList.remove('translate-y-20', 'opacity-0');

        if (toastTimeout) clearTimeout(toastTimeout);
        toastTimeout = setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0');
        }, 3000);
    }

    function showApplyStatusModal(type, title, message, showButton = false, buttonCallback = null) {
        const modal = document.getElementById('apply-status-modal');
        const modalContent = modal.querySelector('div');
        const spinner = document.getElementById('apply-modal-spinner');
        const successIcon = document.getElementById('apply-modal-success-icon');
        const errorIcon = document.getElementById('apply-modal-error-icon');
        const titleEl = document.getElementById('apply-modal-title');
        const msgEl = document.getElementById('apply-modal-message');
        const closeBtn = document.getElementById('apply-modal-close-btn');

        // Reset display
        spinner.classList.add('hidden');
        successIcon.classList.add('hidden');
        errorIcon.classList.add('hidden');
        closeBtn.classList.add('hidden');

        // Set type
        if (type === 'loading') {
            spinner.classList.remove('hidden');
        } else if (type === 'success') {
            successIcon.classList.remove('hidden');
        } else if (type === 'error') {
            errorIcon.classList.remove('hidden');
        }

        // Set text
        titleEl.textContent = title;
        msgEl.textContent = message;

        // Configure close button
        if (showButton) {
            closeBtn.classList.remove('hidden');
            closeBtn.onclick = function() {
                hideApplyStatusModal();
                if (buttonCallback) buttonCallback();
            };
        }

        // Show modal
        modal.classList.remove('hidden');
        // Force reflow
        modal.offsetHeight;
        modal.classList.remove('opacity-0');
        modalContent.classList.remove('scale-95');
        modalContent.classList.add('scale-100');
    }

    function hideApplyStatusModal() {
        const modal = document.getElementById('apply-status-modal');
        const modalContent = modal.querySelector('div');
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function uploadCv(input) {
        if (!input.files || input.files.length === 0) return;
        
        const formData = new FormData();
        formData.append('cv', input.files[0]);
        formData.append('_token', '{{ csrf_token() }}');
        
        showToast('Subiendo currículum...');
        
        fetch('{{ route("student.cv.upload") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast('¡Currículum subido con éxito!');
                location.reload();
            } else {
                showToast(data.message || 'Error al subir currículum.');
            }
        })
        .catch(err => {
            showToast('Error en la comunicación con el servidor.');
        });
    }
    
    function deleteCv(id) {
        if (!confirm('¿Está seguro de que desea eliminar este currículum?')) return;
        
        fetch('/student/cv/delete/' + id, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast('Currículum eliminado.');
                const el = document.getElementById('cv-item-' + id);
                if (el) el.remove();
                location.reload();
            } else {
                showToast(data.message || 'Error al eliminar.');
            }
        })
        .catch(err => {
            showToast('Error en el servidor.');
        });
    }

    // Expose offers list and recent applications to JS
    const activeOffersList = @json($activeOffers);
    const recentApplicationsList = @json($recentApplications);

    // Create a Set of applied offer IDs for fast checking
    const appliedOfferIds = new Set(recentApplicationsList.map(app => app.offer_id));

    // Active job category/id in the page view
    let currentSelectedOffer = null;

    function openJobDetailPage(id) {
        const offer = activeOffersList.find(o => o.id === id);
        if (!offer) return;

        currentSelectedOffer = offer;

        // Fill details
        document.getElementById('detail-offer-id').value = offer.id;
        document.getElementById('detail-title').textContent = offer.title;
        document.getElementById('detail-company').textContent = offer.company_name;
        document.getElementById('detail-location').textContent = offer.location_name;

        // Salary info
        const salaryBadge = document.getElementById('detail-salary-badge');
        if (offer.salary && parseFloat(offer.salary) > 0) {
            const currency = offer.salary_currency || 'S/.';
            document.getElementById('detail-salary').textContent = `${currency} ${parseFloat(offer.salary).toLocaleString('es-PE', { minimumFractionDigits: 2 })}`;
            salaryBadge.classList.remove('hidden');
        } else {
            salaryBadge.classList.add('hidden');
        }

        // Set description
        document.getElementById('detail-desc').textContent = offer.description || 'Sin descripción disponible.';

        // Requirements
        const reqSection = document.getElementById('detail-req-section');
        if (offer.requirements) {
            document.getElementById('detail-req').textContent = offer.requirements;
            reqSection.classList.remove('hidden');
        } else {
            reqSection.classList.add('hidden');
        }

        // Benefits
        const benefitsSection = document.getElementById('detail-benefits-section');
        if (offer.benefits) {
            document.getElementById('detail-benefits').textContent = offer.benefits;
            benefitsSection.classList.remove('hidden');
        } else {
            benefitsSection.classList.add('hidden');
        }

        // Metadata
        const scheduleName = offer.work_schedule?.name || offer.workSchedule?.name || 'No especificada';
        const contractTypeName = offer.contract_type?.name || offer.contractType?.name || 'No especificado';
        const categoryName = offer.category?.name || 'General';
        const deadlineStr = offer.deadline ? new Date(offer.deadline).toLocaleDateString('es-PE', { day: 'numeric', month: 'short', year: 'numeric' }) : 'Sin límite';

        document.getElementById('detail-schedule').textContent = scheduleName;
        document.getElementById('detail-contract').textContent = contractTypeName;
        document.getElementById('detail-category').textContent = categoryName;
        document.getElementById('detail-deadline').textContent = deadlineStr;

        // Set company logo
        const logoContainer = document.getElementById('detail-company-logo');
        if (offer.company && offer.company.logo) {
            logoContainer.innerHTML = `<img src="${offer.company.logo}" class="w-full h-full object-contain rounded-xl" alt="logo" onerror="this.outerHTML='<span class=\'material-symbols-outlined text-4xl text-on-surface-variant\'>corporate_fare</span>'">`;
        } else {
            logoContainer.innerHTML = `<span class="material-symbols-outlined text-4xl text-on-surface-variant">corporate_fare</span>`;
        }

        // Reset view states
        switchJobTab('detail');
        cancelApplyForm();

        // Check if already applied
        const alreadyApplied = appliedOfferIds.has(offer.id);
        const initialView = document.getElementById('action-initial-view');
        const statusBadge = document.getElementById('applied-status-badge');

        if (alreadyApplied) {
            // Find application status
            const app = recentApplicationsList.find(a => a.offer_id === offer.id);
            let statusText = 'Tu postulación fue recibida.';
            if (app && app.status === 'accepted') {
                statusText = 'Tu postulación ha sido aprobada por la empresa.';
            } else if (app && app.status === 'rejected') {
                statusText = 'Tu postulación fue descartada.';
            } else if (app && app.status === 'under_review') {
                statusText = 'Tu postulación está en revisión.';
            }
            document.getElementById('applied-status-text').textContent = statusText;
            initialView.classList.add('hidden');
            statusBadge.classList.remove('hidden');
        } else {
            initialView.classList.remove('hidden');
            statusBadge.classList.add('hidden');
        }

        // Go to detailed page
        switchTab('job-detail');
    }

    function switchJobTab(tabName) {
        const tabBtnDetail = document.getElementById('tab-btn-detail');
        const tabBtnRelated = document.getElementById('tab-btn-related');
        const tabContentDetail = document.getElementById('job-tab-detail');
        const tabContentRelated = document.getElementById('job-tab-related');

        if (tabName === 'detail') {
            tabBtnDetail.className = "px-4 py-2 border-b-2 border-primary text-primary font-bold text-label-md transition-all";
            tabBtnRelated.className = "px-4 py-2 border-b-2 border-transparent text-on-surface-variant font-medium text-label-md hover:text-on-surface transition-all";
            tabContentDetail.classList.remove('hidden');
            tabContentRelated.classList.add('hidden');
        } else {
            tabBtnRelated.className = "px-4 py-2 border-b-2 border-primary text-primary font-bold text-label-md transition-all";
            tabBtnDetail.className = "px-4 py-2 border-b-2 border-transparent text-on-surface-variant font-medium text-label-md hover:text-on-surface transition-all";
            tabContentDetail.classList.add('hidden');
            tabContentRelated.classList.remove('hidden');

            // Populate Related Jobs
            renderRelatedJobs();
        }
    }

    function renderRelatedJobs() {
        const container = document.getElementById('related-jobs-list');
        const placeholder = document.getElementById('no-related-placeholder');
        
        if (!currentSelectedOffer) return;

        // Filter related jobs: same category OR same company, excluding current offer
        const related = activeOffersList.filter(o => 
            o.id !== currentSelectedOffer.id && 
            (o.category_id === currentSelectedOffer.category_id || o.company_id === currentSelectedOffer.company_id)
        );

        if (related.length === 0) {
            container.innerHTML = '';
            placeholder.classList.remove('hidden');
            return;
        }

        placeholder.classList.add('hidden');
        let html = '';
        related.forEach(o => {
            const schedule = o.work_schedule?.name || o.workSchedule?.name || 'Jornada completa';
            html += `
                <div onclick="openJobDetailPage(${o.id})" class="p-4 bg-surface-container rounded-2xl border border-outline-variant hover:border-primary transition-all cursor-pointer group flex items-start gap-3">
                    <div class="w-10 h-10 bg-surface-container-lowest rounded-lg border border-outline-variant/60 flex items-center justify-center text-primary shrink-0">
                        <span class="material-symbols-outlined text-[20px]">corporate_fare</span>
                    </div>
                    <div class="space-y-1 min-w-0 flex-1">
                        <h5 class="font-bold text-body-sm text-on-surface group-hover:text-primary transition-colors truncate">${o.title}</h5>
                        <p class="text-body-xs text-on-surface-variant truncate">${o.company_name} • ${o.location_name}</p>
                        <span class="inline-block px-2 py-0.5 bg-surface-container-lowest text-on-surface-variant rounded text-[10px]">${schedule}</span>
                    </div>
                </div>
            `;
        });
        container.innerHTML = html;
    }

    function showApplyForm() {
        document.getElementById('action-initial-view').classList.add('hidden');
        document.getElementById('action-apply-form').classList.remove('hidden');
    }

    // Volver / Cancelar
    function cancelApplyForm() {
        document.getElementById('action-apply-form').classList.add('hidden');
        // If not applied, show initial view
        if (currentSelectedOffer && !appliedOfferIds.has(currentSelectedOffer.id)) {
            document.getElementById('action-initial-view').classList.remove('hidden');
        }
    }

    function shareJob() {
        if (!currentSelectedOffer) return;
        
        // Build share link (using query parameter offer_id)
        const shareUrl = `${window.location.origin}${window.location.pathname}?offer_id=${currentSelectedOffer.id}`;
        
        navigator.clipboard.writeText(shareUrl).then(() => {
            showToast('¡Enlace de la oferta copiado al portapapeles!');
        }).catch(err => {
            showToast('Error al copiar el enlace.');
        });
    }

    function submitDetailedApplication() {
        if (!currentSelectedOffer) return;
        
        const cvSelect = document.getElementById('detail-cv-select');
        if (!cvSelect || !cvSelect.value) {
            showToast('Debe seleccionar un currículum. Si no tiene uno, súbalo en la sección Mis CVs.', 'error');
            return;
        }
        
        const payload = {
            cv_id: cvSelect.value,
            message: document.getElementById('detail-msg').value,
            _token: '{{ csrf_token() }}'
        };
        
        showApplyStatusModal('loading', 'Enviando postulación...', 'Por favor, espera un momento mientras procesamos tu solicitud.');
        
        fetch('/student/apply/' + currentSelectedOffer.id, {
            method: 'POST',
            body: JSON.stringify(payload),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showApplyStatusModal('success', '¡Postulación Exitosa!', 'Tu postulación ha sido registrada y enviada a la empresa de manera exitosa.', true, function() {
                    switchTab('jobs');
                    location.reload();
                });
            } else {
                showApplyStatusModal('error', 'Error al postular', data.message || 'Ocurrió un problema al enviar tu postulación. Intenta nuevamente.', true);
            }
        })
        .catch(err => {
            showApplyStatusModal('error', 'Error de red', 'No pudimos conectarnos con el servidor. Verifica tu conexión e intenta de nuevo.', true);
        });
    }

    // Auto-open offer from URL query parameter
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const offerIdParam = urlParams.get('offer_id');
        if (offerIdParam) {
            const id = parseInt(offerIdParam);
            if (!isNaN(id)) {
                // Wait slightly for DOM to settle
                setTimeout(() => {
                    openJobDetailPage(id);
                }, 300);
            }
        }
    });
</script>

<!-- Modal de Estado de Postulación (Cargando / Éxito) -->
<div id="apply-status-modal" class="fixed inset-0 bg-on-surface/30 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-surface-bright rounded-2xl border border-outline-variant p-xl shadow-lg max-w-sm w-full mx-4 flex flex-col items-center text-center space-y-lg transform scale-95 transition-transform duration-300">
        <!-- Spinner Container / Icon -->
        <div id="apply-modal-icon-container" class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center text-primary">
            <!-- Spinner -->
            <svg id="apply-modal-spinner" class="animate-spin h-8 w-8 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <!-- Success Icon -->
            <span id="apply-modal-success-icon" class="material-symbols-outlined text-[40px] text-emerald-600 hidden">check_circle</span>
            <!-- Error Icon -->
            <span id="apply-modal-error-icon" class="material-symbols-outlined text-[40px] text-error hidden">error</span>
        </div>
        
        <!-- Status Text -->
        <div class="space-y-xs">
            <h3 id="apply-modal-title" class="text-headline-sm font-bold text-on-surface">Enviando postulación...</h3>
            <p id="apply-modal-message" class="text-body-sm text-on-surface-variant leading-relaxed">Por favor, espera un momento mientras procesamos tu solicitud.</p>
        </div>

        <!-- Action Button -->
        <button id="apply-modal-close-btn" class="w-full py-2.5 bg-primary text-on-primary font-semibold text-label-md rounded-xl hover:opacity-90 transition-opacity hidden">
            Aceptar
        </button>
    </div>
</div>

<!-- Toast Container -->
<div id="toast" class="fixed bottom-5 right-5 bg-surface-container-high border border-outline-variant/60 text-on-surface px-lg py-md rounded-xl shadow-lg transform translate-y-20 opacity-0 transition-all duration-300 z-50 flex items-center gap-sm max-w-sm pointer-events-none">
    <span id="toast-icon" class="material-symbols-outlined text-[20px] text-primary">info</span>
    <span id="toast-message" class="text-body-sm font-semibold">Mensaje</span>
</div>
</body>
</html>
