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
                        ['key' => 'profile', 'icon' => 'person', 'label' => 'Mi Perfil'],
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
                @include('partials.notifications-dropdown', ['id' => 'student-notifications-button', 'menuId' => 'student-notifications-menu'])
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

        <!-- ================= PANEL 5: MI PERFIL ================= -->
        <div id="panel-profile" class="tab-panel space-y-xl hidden">
            <form id="student-profile-form" onsubmit="saveStudentProfile(event)" class="grid grid-cols-1 lg:grid-cols-3 gap-lg">
                <!-- Columna Izquierda: Información Personal Básica -->
                <div class="lg:col-span-1 bg-surface-container-lowest rounded-2xl border border-outline-variant p-lg shadow-sm space-y-md">
                    <h3 class="font-headline-sm text-headline-sm text-on-surface mb-2 font-bold flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">person</span>
                        Datos Personales
                    </h3>
                    <p class="text-body-xs text-on-surface-variant">Completa tu información básica de identificación y contacto.</p>
                    <hr class="border-outline-variant my-2" />
                    
                    <div class="space-y-sm">
                        <div>
                            <label for="profile-names" class="block font-semibold text-body-xs text-on-surface mb-1">Nombre Completo:</label>
                            <input type="text" id="profile-names" name="names" value="{{ Auth::user()->person->names ?? '' }}" required
                                   class="w-full p-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg text-body-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label for="profile-doc-type" class="block font-semibold text-body-xs text-on-surface mb-1">Tipo Doc.:</label>
                                <select id="profile-doc-type" name="document_type"
                                        class="w-full p-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg text-body-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                                    <option value="DNI" {{ (Auth::user()->person->document_type ?? 'DNI') === 'DNI' ? 'selected' : '' }}>DNI</option>
                                    <option value="CE" {{ (Auth::user()->person->document_type ?? '') === 'CE' ? 'selected' : '' }}>C.E.</option>
                                    <option value="PASAPORTE" {{ (Auth::user()->person->document_type ?? '') === 'PASAPORTE' ? 'selected' : '' }}>Pasaporte</option>
                                </select>
                            </div>
                            <div>
                                <label for="profile-doc-number" class="block font-semibold text-body-xs text-on-surface mb-1">Nº Doc.:</label>
                                <input type="text" id="profile-doc-number" name="document_number" value="{{ Auth::user()->person->document_number ?? '' }}" required
                                       class="w-full p-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg text-body-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                            </div>
                        </div>
                        <div>
                            <label for="profile-phone" class="block font-semibold text-body-xs text-on-surface mb-1">Teléfono:</label>
                            <input type="text" id="profile-phone" name="phone" value="{{ Auth::user()->person->phone ?? '' }}" maxlength="9"
                                   class="w-full p-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg text-body-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label for="profile-sex" class="block font-semibold text-body-xs text-on-surface mb-1">Sexo:</label>
                                <select id="profile-sex" name="sex"
                                        class="w-full p-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg text-body-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                                    <option value="">Seleccionar</option>
                                    <option value="M" {{ (Auth::user()->person->sex ?? '') === 'M' ? 'selected' : '' }}>Masculino</option>
                                    <option value="F" {{ (Auth::user()->person->sex ?? '') === 'F' ? 'selected' : '' }}>Femenino</option>
                                    <option value="O" {{ (Auth::user()->person->sex ?? '') === 'O' ? 'selected' : '' }}>Otro</option>
                                </select>
                            </div>
                            <div>
                                <label for="profile-native-lang" class="block font-semibold text-body-xs text-on-surface mb-1">Idioma Nativo:</label>
                                <input type="text" id="profile-native-lang" name="native_language" value="{{ Auth::user()->person->native_language ?? '' }}" placeholder="Ej. Español"
                                       class="w-full p-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg text-body-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                            </div>
                        </div>
                        <div>
                            <label for="profile-birth-date" class="block font-semibold text-body-xs text-on-surface mb-1">Fecha Nacimiento:</label>
                            <input type="date" id="profile-birth-date" name="birth_date" value="{{ Auth::user()->person->birth_date ?? '' }}"
                                   class="w-full p-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg text-body-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Perfil Profesional -->
                <div class="lg:col-span-2 space-y-lg">
                    <!-- Tarjeta: Sobre mí, Habilidades y Aficiones -->
                    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant p-lg shadow-sm space-y-md">
                        <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">psychology</span>
                            Perfil Profesional
                        </h3>
                        
                        <div>
                            <label for="profile-about-me" class="block font-semibold text-body-xs text-on-surface mb-1">Sobre mí (Breve extracto):</label>
                            <textarea id="profile-about-me" name="about_me" rows="4" maxlength="1000" placeholder="Escribe un breve resumen de tu perfil profesional..."
                                      class="w-full p-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg text-body-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">{{ Auth::user()->person->about_me ?? '' }}</textarea>
                        </div>

                        <!-- Tag Inputs: Skills & Hobbies -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                            <div>
                                <label class="block font-semibold text-body-xs text-on-surface mb-1">Habilidades (Ej: Java, PHP, Liderazgo):</label>
                                <div class="border border-outline-variant rounded-lg p-2 bg-surface-container-lowest flex flex-wrap gap-2 items-center min-h-[48px] focus-within:border-primary transition-all">
                                    <div id="skills-tags-container" class="flex flex-wrap gap-1.5">
                                        <!-- Javascript will render tags here -->
                                    </div>
                                    <input type="text" id="skills-tag-input" placeholder="Escribe y pulsa Enter"
                                           class="flex-1 min-w-[120px] bg-transparent outline-none border-none text-body-sm p-1">
                                </div>
                            </div>
                            <div>
                                <label class="block font-semibold text-body-xs text-on-surface mb-1">Aficiones / Pasatiempos (Ej: Lectura, Deporte):</label>
                                <div class="border border-outline-variant rounded-lg p-2 bg-surface-container-lowest flex flex-wrap gap-2 items-center min-h-[48px] focus-within:border-primary transition-all">
                                    <div id="hobbies-tags-container" class="flex flex-wrap gap-1.5">
                                        <!-- Javascript will render tags here -->
                                    </div>
                                    <input type="text" id="hobbies-tag-input" placeholder="Escribe y pulsa Enter"
                                           class="flex-1 min-w-[120px] bg-transparent outline-none border-none text-body-sm p-1">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta: Educación -->
                    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant p-lg shadow-sm space-y-md">
                        <div class="flex items-center justify-between">
                            <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">school</span>
                                Educación
                            </h3>
                            <button type="button" onclick="addEducationRow()" class="flex items-center gap-1 text-primary text-label-md font-semibold hover:opacity-85 transition-opacity bg-primary/10 px-3 py-1.5 rounded-lg">
                                <span class="material-symbols-outlined text-[18px]">add</span>
                                Añadir
                            </button>
                        </div>
                        <div id="education-repeater-container" class="space-y-md">
                            <!-- Dynamic education rows here -->
                        </div>
                    </div>

                    <!-- Tarjeta: Experiencia Laboral -->
                    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant p-lg shadow-sm space-y-md">
                        <div class="flex items-center justify-between">
                            <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">work_history</span>
                                Experiencia Laboral
                            </h3>
                            <button type="button" onclick="addExperienceRow()" class="flex items-center gap-1 text-primary text-label-md font-semibold hover:opacity-85 transition-opacity bg-primary/10 px-3 py-1.5 rounded-lg">
                                <span class="material-symbols-outlined text-[18px]">add</span>
                                Añadir
                            </button>
                        </div>
                        <div id="experience-repeater-container" class="space-y-md">
                            <!-- Dynamic experience rows here -->
                        </div>
                    </div>

                    <!-- Botón de guardar todo -->
                    <div class="flex justify-end pt-2">
                        <button type="submit" class="bg-primary text-on-primary font-semibold text-label-md px-8 py-3 rounded-xl hover:bg-primary/95 transition-all shadow-md flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">save</span>
                            Guardar Perfil Profesional
                        </button>
                    </div>
                </div>
            </form>
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
        const validTabs = ['jobs', 'applications', 'cvs', 'profile'];
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
        showCustomConfirm({
            title: 'Eliminar Currículum',
            message: '¿Está seguro de que desea eliminar este currículum?'
        }).then(confirmed => {
            if (!confirmed) return;
            
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

    // Global arrays to store tags
    let currentSkills = {!! json_encode(Auth::user()->person->skills ?? []) !!};
    let currentHobbies = {!! json_encode(Auth::user()->person->hobbies ?? []) !!};
    let educationData = {!! json_encode(Auth::user()->person->education ?? []) !!};
    let experienceData = {!! json_encode(Auth::user()->person->experience ?? []) !!};

    function initProfileUI() {
        renderTags('skills');
        renderTags('hobbies');
        
        // Render Education rows
        const eduContainer = document.getElementById('education-repeater-container');
        eduContainer.innerHTML = '';
        if (educationData.length === 0) {
            addEducationRow(); // Add at least one empty row
        } else {
            educationData.forEach((item, index) => addEducationRow(item));
        }

        // Render Experience rows
        const expContainer = document.getElementById('experience-repeater-container');
        expContainer.innerHTML = '';
        if (experienceData.length === 0) {
            addExperienceRow(); // Add at least one empty row
        } else {
            experienceData.forEach((item, index) => addExperienceRow(item));
        }
    }

    function setupTagInput(type) {
        const input = document.getElementById(type + '-tag-input');
        if (!input) return;
        
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ',') {
                e.preventDefault();
                const val = this.value.trim().replace(/,/g, '');
                if (val) {
                    const arr = (type === 'skills') ? currentSkills : currentHobbies;
                    if (!arr.includes(val)) {
                        arr.push(val);
                        renderTags(type);
                    }
                }
                this.value = '';
            }
        });
    }

    function renderTags(type) {
        const container = document.getElementById(type + '-tags-container');
        const arr = (type === 'skills') ? currentSkills : currentHobbies;
        if (!container) return;
        
        container.innerHTML = arr.map((tag, idx) => `
            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-primary/10 text-primary rounded-full text-body-xs font-semibold">
                ${tag}
                <button type="button" onclick="removeTag('${type}', ${idx})" class="hover:bg-primary/20 rounded-full w-4 h-4 flex items-center justify-center text-[12px] font-bold">×</button>
            </span>
        `).join('');
    }

    function removeTag(type, index) {
        const arr = (type === 'skills') ? currentSkills : currentHobbies;
        arr.splice(index, 1);
        renderTags(type);
    }

    // Repeater for Education
    function addEducationRow(data = {}) {
        const container = document.getElementById('education-repeater-container');
        const rowId = 'edu-' + Date.now() + '-' + Math.floor(Math.random() * 1000);
        
        const rowHTML = `
            <div id="${rowId}" class="education-row p-md bg-surface-container-low rounded-xl border border-outline-variant space-y-sm relative">
                <button type="button" onclick="removeRepeaterRow('${rowId}')" class="absolute top-2 right-2 p-1.5 text-error hover:bg-error/10 rounded-full transition-colors flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">delete</span>
                </button>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 pr-6">
                    <div>
                        <label class="block font-semibold text-body-xs text-on-surface mb-0.5">Institución:</label>
                        <input type="text" placeholder="Ej: Universidad Nacional" value="${data.institution ?? ''}" required
                               class="edu-institution w-full p-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-body-sm focus:border-primary outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-body-xs text-on-surface mb-0.5">Grado / Título:</label>
                        <input type="text" placeholder="Ej: Bachiller en Sistemas" value="${data.degree ?? ''}" required
                               class="edu-degree w-full p-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-body-sm focus:border-primary outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 max-w-sm">
                    <div>
                        <label class="block font-semibold text-body-xs text-on-surface mb-0.5">Año Inicio:</label>
                        <input type="text" placeholder="Ej: 2021" value="${data.year_start ?? ''}" required
                               class="edu-start w-full p-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-body-sm focus:border-primary outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-body-xs text-on-surface mb-0.5">Año Fin (o 'Actual'):</label>
                        <input type="text" placeholder="Ej: 2025" value="${data.year_end ?? ''}" required
                               class="edu-end w-full p-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-body-sm focus:border-primary outline-none">
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', rowHTML);
    }

    // Repeater for Experience
    function addExperienceRow(data = {}) {
        const container = document.getElementById('experience-repeater-container');
        const rowId = 'exp-' + Date.now() + '-' + Math.floor(Math.random() * 1000);
        
        const rowHTML = `
            <div id="${rowId}" class="experience-row p-md bg-surface-container-low rounded-xl border border-outline-variant space-y-sm relative">
                <button type="button" onclick="removeRepeaterRow('${rowId}')" class="absolute top-2 right-2 p-1.5 text-error hover:bg-error/10 rounded-full transition-colors flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">delete</span>
                </button>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 pr-6">
                    <div>
                        <label class="block font-semibold text-body-xs text-on-surface mb-0.5">Empresa:</label>
                        <input type="text" placeholder="Ej: Tech Solutions" value="${data.company ?? ''}" required
                               class="exp-company w-full p-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-body-sm focus:border-primary outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-body-xs text-on-surface mb-0.5">Cargo / Puesto:</label>
                        <input type="text" placeholder="Ej: Desarrollador Jr." value="${data.role ?? ''}" required
                               class="exp-role w-full p-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-body-sm focus:border-primary outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 max-w-sm">
                    <div>
                        <label class="block font-semibold text-body-xs text-on-surface mb-0.5">Año Inicio:</label>
                        <input type="text" placeholder="Ej: 2023" value="${data.year_start ?? ''}" required
                               class="exp-start w-full p-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-body-sm focus:border-primary outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-body-xs text-on-surface mb-0.5">Año Fin (o 'Actual'):</label>
                        <input type="text" placeholder="Ej: Actual" value="${data.year_end ?? ''}" required
                               class="exp-end w-full p-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-body-sm focus:border-primary outline-none">
                    </div>
                </div>
                <div>
                    <label class="block font-semibold text-body-xs text-on-surface mb-0.5">Descripción de labores (Opcional):</label>
                    <textarea rows="2" placeholder="Describe brevemente tus funciones..."
                              class="exp-desc w-full p-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-body-sm focus:border-primary outline-none">${data.description ?? ''}</textarea>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', rowHTML);
    }

    function removeRepeaterRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    function saveStudentProfile(event) {
        event.preventDefault();
        
        // Collate Education
        const eduRows = document.querySelectorAll('.education-row');
        const education = [];
        eduRows.forEach(row => {
            const inst = row.querySelector('.edu-institution').value.trim();
            const deg = row.querySelector('.edu-degree').value.trim();
            const start = row.querySelector('.edu-start').value.trim();
            const end = row.querySelector('.edu-end').value.trim();
            if (inst || deg) {
                education.push({
                    institution: inst,
                    degree: deg,
                    year_start: start,
                    year_end: end
                });
            }
        });

        // Collate Experience
        const expRows = document.querySelectorAll('.experience-row');
        const experience = [];
        expRows.forEach(row => {
            const comp = row.querySelector('.exp-company').value.trim();
            const role = row.querySelector('.exp-role').value.trim();
            const start = row.querySelector('.exp-start').value.trim();
            const end = row.querySelector('.exp-end').value.trim();
            const desc = row.querySelector('.exp-desc').value.trim();
            if (comp || role) {
                experience.push({
                    company: comp,
                    role: role,
                    year_start: start,
                    year_end: end,
                    description: desc
                });
            }
        });

        const payload = {
            names: document.getElementById('profile-names').value.trim(),
            document_type: document.getElementById('profile-doc-type').value,
            document_number: document.getElementById('profile-doc-number').value.trim(),
            phone: document.getElementById('profile-phone').value.trim(),
            sex: document.getElementById('profile-sex').value,
            native_language: document.getElementById('profile-native-lang').value.trim(),
            birth_date: document.getElementById('profile-birth-date').value,
            about_me: document.getElementById('profile-about-me').value.trim(),
            skills: currentSkills,
            hobbies: currentHobbies,
            education: education,
            experience: experience,
            _token: '{{ csrf_token() }}'
        };

        showApplyStatusModal('loading', 'Guardando cambios...', 'Guardando los cambios de tu perfil profesional, por favor espera.');

        fetch('/student/profile', {
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
                showApplyStatusModal('success', '¡Perfil Guardado!', 'Los cambios en tu perfil profesional se han guardado con éxito.', true, function() {
                    location.reload();
                });
            } else {
                showApplyStatusModal('error', 'Error al guardar', data.message || 'Ocurrió un problema al guardar los cambios.', true);
            }
        })
        .catch(err => {
            showApplyStatusModal('error', 'Error de red', 'No pudimos conectarnos con el servidor. Verifica tu conexión e intenta de nuevo.', true);
        });
    }

    // Auto-open offer from URL query parameter
    document.addEventListener('DOMContentLoaded', function() {
        initProfileUI();
        setupTagInput('skills');
        setupTagInput('hobbies');

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

<!-- Custom Confirm Modal -->
<div id="custom-confirm-modal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm transition-all duration-300">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl max-w-md w-full overflow-hidden transform scale-95 transition-all duration-200 ease-out" id="custom-confirm-box">
        <div class="p-6 text-center space-y-4">
            <div class="w-16 h-16 rounded-full bg-red-50 text-red-600 flex items-center justify-center mx-auto">
                <span class="material-symbols-outlined text-[36px]">warning</span>
            </div>
            <div class="space-y-2">
                <h3 class="font-headline-sm text-[18px] text-slate-800 font-bold" id="custom-confirm-title">¿Confirmar acción?</h3>
                <p class="text-sm text-slate-500 leading-relaxed" id="custom-confirm-message">Esta acción no se puede deshacer.</p>
            </div>
        </div>
        <div class="flex border-t border-slate-100">
            <button id="custom-confirm-btn-cancel" class="flex-1 px-4 py-4 text-sm font-semibold text-slate-500 hover:bg-slate-50 transition-colors outline-none cursor-pointer">
                Cancelar
            </button>
            <div class="w-[1px] bg-slate-100"></div>
            <button id="custom-confirm-btn-ok" class="flex-1 px-4 py-4 text-sm font-bold text-red-600 hover:bg-red-50/50 transition-colors outline-none cursor-pointer">
                Confirmar
            </button>
        </div>
    </div>
</div>

<script>
    // Custom Confirm Modal Helper
    function showCustomConfirm(options = {}) {
        return new Promise((resolve) => {
            const modal = document.getElementById('custom-confirm-modal');
            const box = document.getElementById('custom-confirm-box');
            const titleEl = document.getElementById('custom-confirm-title');
            const msgEl = document.getElementById('custom-confirm-message');
            const btnCancel = document.getElementById('custom-confirm-btn-cancel');
            const btnOk = document.getElementById('custom-confirm-btn-ok');

            if (!modal || !box) {
                resolve(confirm(options.message || '¿Confirmar acción?'));
                return;
            }

            titleEl.textContent = options.title || '¿Confirmar acción?';
            msgEl.textContent = options.message || 'Esta acción no se puede deshacer.';
            btnOk.textContent = options.btnOkText || 'Confirmar';
            btnCancel.textContent = options.btnCancelText || 'Cancelar';

            modal.classList.remove('hidden');
            setTimeout(() => box.classList.remove('scale-95'), 10);

            function cleanup(result) {
                box.classList.add('scale-95');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 200);
                
                btnCancel.replaceWith(btnCancel.cloneNode(true));
                btnOk.replaceWith(btnOk.cloneNode(true));
                
                resolve(result);
            }

            document.getElementById('custom-confirm-btn-cancel').addEventListener('click', () => cleanup(false));
            document.getElementById('custom-confirm-btn-ok').addEventListener('click', () => cleanup(true));
        });
    }
</script>
</body>
</html>
