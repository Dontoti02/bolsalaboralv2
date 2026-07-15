<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel de Empresa</title>
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;family=Manrope:wght@600;700;800&amp;display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f7f9fc; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>
</head>
<body class="bg-background text-on-background min-h-screen flex">

    @php
        $sidebarConfig = [
            'logo' => $config['logo'] ?? '/assets/logo.png',
            'brand' => 'Bolsa Laboral',
            'subtitle' => 'Portal de Empresas',
            'active' => '',
            'home_tab' => 'dashboard',
            'show_publish' => true,
            'publish_tab' => 'offers',
            'publish_label' => 'Publicar Oferta',
            'show_help' => true,
            'help_label' => 'Centro de Ayuda',
            'items' => [
                [
                    'label' => '',
                    'items' => [
                        ['key' => 'dashboard', 'icon' => 'dashboard', 'label' => 'Dashboard'],
                        ['key' => 'offers', 'icon' => 'work', 'label' => 'Gestionar Ofertas'],
                        ['key' => 'applicants', 'icon' => 'group', 'label' => 'Postulantes'],
                        ['key' => 'profile', 'icon' => 'domain', 'label' => 'Perfil de Empresa'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('partials.sidebar', ['sidebarConfig' => $sidebarConfig, 'config' => $config ?? []])

    

<!-- Main Content Wrapper -->
<div class="flex-1 flex flex-col ml-0 md:ml-0 w-full min-h-screen">
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
                @include('partials.notifications-dropdown', ['id' => 'company-notifications-button', 'menuId' => 'company-notifications-menu'])
                <button class="text-on-surface-variant hover:bg-surface-container-high rounded-full p-2 transition-all">
                    <span class="material-symbols-outlined">help</span>
                </button>
                <div class="w-px h-6 bg-outline-variant mx-sm"></div>
                
                <div class="flex items-center gap-3 ml-sm">
                    <div class="text-right hidden sm:block">
                        <p id="header-company-name" class="text-label-md font-semibold text-on-surface leading-none">{{ $company->name }}</p>
                        <span class="text-[11px] text-on-surface-variant">Portal Corporativo</span>
                    </div>
                    <div class="w-10 h-10 rounded-full overflow-hidden border border-outline-variant bg-primary-container text-on-primary flex items-center justify-center font-bold relative shrink-0">
                        @if(!empty($company->logo))
                            <img id="header-logo" src="{{ $company->logo }}" alt="Logo" class="w-full h-full object-cover">
                        @else
                            <div id="header-initials" class="w-full h-full flex items-center justify-center bg-primary-container text-on-primary font-bold">
                                {{ strtoupper(substr($company->name ?? 'C', 0, 1)) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Main Dashboard Canvas -->
    <main class="flex-1 p-lg md:p-2xl w-full max-w-container-max mx-auto">
        
        <!-- ================= PANEL 1: DASHBOARD OVERVIEW ================= -->
        <div id="panel-dashboard" class="tab-panel space-y-xl">
            <!-- Page Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-md">
                <div>
                    <h1 class="font-headline-lg text-headline-lg text-on-surface mb-xs">Resumen de Empresa</h1>
                    <p class="font-body-md text-body-md text-on-surface-variant">Monitorea el rendimiento de tus ofertas y postulantes.</p>
                </div>
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
            </div>

            @if(!$company->is_verified)
            <!-- Unverified Warning Banner -->
            <div id="verification-warning-banner" class="flex flex-col sm:flex-row sm:items-center justify-between gap-md bg-red-50 border-2 border-red-200 text-red-900 p-lg rounded-2xl shadow-sm">
                <div class="flex items-start gap-md">
                    <span class="material-symbols-outlined text-red-600 text-3xl shrink-0">gavel</span>
                    <div>
                        <p class="font-bold text-body-sm leading-none text-red-950">¡Cuenta no verificada!</p>
                        <p class="text-body-sm mt-1">Tu empresa aún no ha sido verificada por el administrador. No podrás publicar ofertas de empleo hasta que sea aprobada.</p>
                    </div>
                </div>
            </div>
            @endif

            @if(empty($company->phone) || empty($company->address))
            <!-- Warning Banner -->
            <div id="profile-warning-banner" class="flex flex-col sm:flex-row sm:items-center justify-between gap-md bg-yellow-50 border-2 border-yellow-200 text-yellow-900 p-lg rounded-2xl shadow-sm">
                <div class="flex items-start gap-md">
                    <span class="material-symbols-outlined text-yellow-600 text-3xl shrink-0">warning</span>
                    <div>
                        <p class="font-bold text-body-sm leading-none text-yellow-950">¡Perfil Incompleto!</p>
                        <p class="text-body-sm mt-1">Completa la información detallada de tu empresa (teléfono, dirección, logo, etc.) para que los postulantes conozcan más sobre tu marca empleadora.</p>
                    </div>
                </div>
                <button onclick="switchTab('profile')" class="px-5 py-2.5 bg-yellow-600 text-white font-label-md text-label-md rounded-xl hover:bg-yellow-700 transition-colors shadow-sm whitespace-nowrap font-semibold">
                    Completar Perfil
                </button>
            </div>
            @endif
            
            <!-- Metrics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md">
                <!-- Metric 1 -->
                <div class="bg-surface-container-lowest rounded-xl p-lg border border-outline-variant shadow-sm flex flex-col">
                    <div class="flex items-center justify-between mb-md">
                        <div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined">publish</span>
                        </div>
                    </div>
                    <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs">Ofertas Publicadas</p>
                    <h3 class="font-display-lg text-display-lg text-on-surface">{{ $offersCount }}</h3>
                </div>
                <!-- Metric 2 -->
                <div class="bg-surface-container-lowest rounded-xl p-lg border border-outline-variant shadow-sm flex flex-col">
                    <div class="flex items-center justify-between mb-md">
                        <div class="w-10 h-10 rounded-full bg-secondary-container flex items-center justify-center text-secondary">
                            <span class="material-symbols-outlined">check_circle</span>
                        </div>
                    </div>
                    <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs">Ofertas Activas</p>
                    <h3 class="font-display-lg text-display-lg text-on-surface">{{ $activeOffersCount }}</h3>
                </div>
                <!-- Metric 3 -->
                <div class="bg-surface-container-lowest rounded-xl p-lg border border-outline-variant shadow-sm flex flex-col">
                    <div class="flex items-center justify-between mb-md">
                        <div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container">
                            <span class="material-symbols-outlined">groups</span>
                        </div>
                    </div>
                    <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs">Postulantes Recibidos</p>
                    <h3 class="font-display-lg text-display-lg text-on-surface">{{ $applicantsCount }}</h3>
                </div>
                <!-- Metric 4 -->
                <div class="bg-surface-container-lowest rounded-xl p-lg border border-outline-variant shadow-sm flex flex-col">
                    <div class="flex items-center justify-between mb-md">
                        <div class="w-10 h-10 rounded-full bg-error-container flex items-center justify-center text-on-error-container">
                            <span class="material-symbols-outlined">pending_actions</span>
                        </div>
                    </div>
                    <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs">Pendientes</p>
                    <h3 class="font-display-lg text-display-lg text-on-surface">{{ $pendingApplicantsCount }}</h3>
                </div>
            </div>
            
            <!-- Tables Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-lg">
                <!-- Últimas Ofertas Table -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden flex flex-col h-full">
                    <div class="px-lg py-md border-b border-outline-variant flex justify-between items-center bg-surface-bright">
                        <h3 class="font-headline-sm text-headline-sm text-on-surface">Últimas Ofertas</h3>
                        <button onclick="switchTab('offers')" class="text-primary font-label-sm text-label-sm hover:underline">Ver todas</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-outline-variant bg-surface-container-lowest">
                                    <th class="px-lg py-sm font-label-sm text-label-sm text-on-surface-variant font-semibold">Título</th>
                                    <th class="px-lg py-sm font-label-sm text-label-sm text-on-surface-variant font-semibold">Fecha</th>
                                    <th class="px-lg py-sm font-label-sm text-label-sm text-on-surface-variant font-semibold text-center">Postulantes</th>
                                    <th class="px-lg py-sm font-label-sm text-label-sm text-on-surface-variant font-semibold text-right">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOffers as $offer)
                                <tr class="border-b border-surface-container-high hover:bg-surface-container-lowest transition-colors group">
                                    <td class="px-lg py-md font-body-sm text-body-sm text-on-surface font-medium">{{ $offer->title }}</td>
                                    <td class="px-lg py-md font-body-sm text-body-sm text-on-surface-variant">{{ $offer->publication_date ? $offer->publication_date->format('d M, Y') : '-' }}</td>
                                    <td class="px-lg py-md font-body-sm text-body-sm text-on-surface text-center">{{ $offer->applicants_count ?? 0 }}</td>
                                    <td class="px-lg py-md text-right">
                                        @php
                                            $stateName = $offer->state->name ?? 'Desconocido';
                                            $stateKey = $offer->state->key ?? '';
                                            $stateClass = 'bg-surface-container text-on-surface-variant';
                                            if ($stateKey === 'active') $stateClass = 'bg-secondary-container text-on-secondary-container';
                                            elseif ($stateKey === 'draft') $stateClass = 'bg-tertiary-fixed text-on-tertiary-fixed-variant';
                                            elseif ($stateKey === 'finished') $stateClass = 'bg-surface-container text-on-surface-variant';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-label-sm text-label-sm {{ $stateClass }}">{{ $stateName }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-lg py-md text-center text-on-surface-variant">No hay ofertas publicadas aún.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Últimos Postulantes Table -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden flex flex-col h-full">
                    <div class="px-lg py-md border-b border-outline-variant flex justify-between items-center bg-surface-bright">
                        <h3 class="font-headline-sm text-headline-sm text-on-surface">Últimos Postulantes</h3>
                        <button onclick="switchTab('applicants')" class="text-primary font-label-sm text-label-sm hover:underline">Ver todos</button>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-outline-variant bg-surface-container-lowest">
                                    <th class="px-lg py-sm font-label-sm text-label-sm text-on-surface-variant font-semibold">Nombre</th>
                                    <th class="px-lg py-sm font-label-sm text-label-sm text-on-surface-variant font-semibold">Puesto Aplicado</th>
                                    <th class="px-lg py-sm font-label-sm text-label-sm text-on-surface-variant font-semibold">Fecha</th>
                                    <th class="px-lg py-sm font-label-sm text-label-sm text-on-surface-variant font-semibold text-right">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentApplicants as $app)
                                <tr class="border-b border-surface-container-high hover:bg-surface-container-lowest transition-colors group">
                                    <td class="px-lg py-md">
                                        <div class="flex items-center gap-sm">
                                            <div class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-primary font-label-sm text-label-sm font-bold">{{ strtoupper(substr($app->fullname ?? 'C', 0, 1)) }}{{ isset($app->fullname) && strlen($app->fullname) > 1 ? strtoupper(substr(str_replace(' ', '', $app->fullname), 1, 1)) : 'A' }}</div>
                                            <span class="font-body-sm text-body-sm text-on-surface font-medium">{{ $app->fullname ?? 'Candidato' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-lg py-md font-body-sm text-body-sm text-on-surface-variant">{{ $app->offer_title ?? 'Puesto' }}</td>
                                    <td class="px-lg py-md font-body-sm text-body-sm text-on-surface-variant">{{ $app->created_at ? \Carbon\Carbon::parse($app->created_at)->diffForHumans() : '-' }}</td>
                                    <td class="px-lg py-md text-right">
                                        <button type="button" data-name="{{ addslashes($app->fullname ?? 'Candidato') }}"
                                            data-career="{{ addslashes($app->person_career ?? $app->program_study ?? '') }}"
                                            data-msg="{{ addslashes($app->message ?? '') }}"
                                            data-cv="{{ $app->cv ?? '' }}"
                                            data-avatar="{{ $app->user_avatar ?? '' }}"
                                            data-skills="{{ htmlspecialchars(json_encode($app->person_skills ?? []), ENT_QUOTES, 'UTF-8') }}"
                                            data-about="{{ addslashes($app->person_about_me ?? '') }}"
                                            onclick="openApplicantModal(this)"
                                            style="color:#002741; font-weight:600; font-size:13px;" onmouseover="this.style.color='#006b60'" onmouseout="this.style.color='#002741'">Ver Perfil</button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-lg py-md text-center text-on-surface-variant">No hay postulantes aún.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- ================= PANEL 2: GESTIONAR OFERTAS ================= -->
        <div id="panel-offers" class="tab-panel space-y-lg hidden">

            <!-- Offers List View -->
            <div id="company-offers-list-view" class="space-y-lg">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-headline-lg font-headline-lg text-primary mb-1">Mis Ofertas Laborales</h1>
                        <p class="text-body-md text-on-surface-variant">Gestiona y publica las convocatorias de tu empresa.</p>
                    </div>
                </div>

                <!-- Search and Action Bar -->
                <div class="flex flex-col sm:flex-row gap-md items-center justify-between">
                    <div class="flex-1 w-full max-w-xl relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[20px]">search</span>
                        <input id="company-search-offers-input" oninput="loadCompanyOffers()"
                            class="w-full pl-10 pr-4 py-2.5 bg-surface border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm"
                            placeholder="Buscar convocatorias..." type="text">
                    </div>
                    <div class="flex items-center gap-md w-full sm:w-auto">
                        <select id="company-filter-sort" onchange="loadCompanyOffers()"
                            class="flex-1 sm:flex-initial px-4 py-2.5 bg-surface border border-outline-variant rounded-xl outline-none text-body-sm font-medium focus:border-primary transition-all">
                            <option value="recent">Recientes</option>
                            <option value="title_asc">Título A-Z</option>
                            <option value="title_desc">Título Z-A</option>
                            <option value="salary_desc">Mayor Salario</option>
                            <option value="salary_asc">Menor Salario</option>
                        </select>
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
                    </div>
                </div>

                <!-- Offers Table -->
                <div class="bg-surface rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-surface-container-low border-b border-outline-variant text-[11px] font-bold uppercase tracking-wider text-on-surface-variant/80">
                                    <th class="px-4 py-3.5 w-12">Acción</th>
                                    <th class="px-4 py-3.5 w-24">Estado</th>
                                    <th class="px-4 py-3.5">Título</th>
                                    <th class="px-4 py-3.5">Salario</th>
                                    <th class="px-4 py-3.5">Categoría</th>
                                    <th class="px-4 py-3.5">Jornada</th>
                                    <th class="px-4 py-3.5">Modalidad</th>
                                    <th class="px-4 py-3.5">Contrato</th>
                                </tr>
                            </thead>
                            <tbody id="company-offers-table-body"
                                class="divide-y divide-outline-variant/60 font-body-sm text-body-sm text-on-surface">
                                <tr>
                                    <td colspan="8" class="text-center py-xl text-on-surface-variant">Cargando ofertas...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Offers Create/Edit Form View -->
            <div id="company-offers-form-view"
                class="bg-surface rounded-2xl border border-outline-variant shadow-sm overflow-hidden p-lg space-y-lg hidden">
                <div class="flex items-center gap-sm">
                    <button onclick="hideCreateOfferForm()"
                        class="p-2 border border-outline-variant hover:bg-surface-container-high rounded-xl text-on-surface-variant flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                    </button>
                    <h2 id="company-form-offer-title" class="text-headline-md font-headline-md text-primary">Crear Oferta Laboral</h2>
                </div>

                <form id="company-offer-form" onsubmit="handleCompanyOfferSubmit(event)" class="space-y-lg">
                    <input type="hidden" id="company-form-offer-id" value="">

                    <!-- Row 1: Title and Date -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-lg">
                        <div class="md:col-span-3 space-y-xs">
                            <label class="font-label-sm text-label-sm text-on-surface-variant block" for="co-offer-title">Título</label>
                            <input class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm"
                                id="co-offer-title" placeholder="Aa" type="text" required />
                        </div>
                        <div class="space-y-xs">
                            <label class="font-label-sm text-label-sm text-on-surface-variant block" for="co-offer-pub-date">Fecha de publicación</label>
                            <input class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm"
                                id="co-offer-pub-date" type="date" required />
                        </div>
                    </div>

                    <!-- Row 2: Description -->
                    <div class="space-y-xs">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block">Descripción</label>
                        <div class="border border-outline-variant rounded-xl bg-background overflow-hidden flex flex-col">
                            <div class="bg-surface-container-low px-md py-sm border-b border-outline-variant/60 flex flex-wrap gap-md items-center text-on-surface-variant/80">
                                <span class="font-semibold text-xs border-r border-outline-variant/80 pr-md">Normal</span>
                                <button type="button" onclick="formatTextarea('co-offer-description','bold')" class="font-bold hover:text-primary">B</button>
                                <button type="button" onclick="formatTextarea('co-offer-description','italic')" class="italic hover:text-primary">I</button>
                                <span class="text-outline-variant">|</span>
                                <button type="button" onclick="formatTextarea('co-offer-description','bullet')" class="material-symbols-outlined text-[18px] hover:text-primary">format_list_bulleted</button>
                                <button type="button" onclick="formatTextarea('co-offer-description','numeric')" class="material-symbols-outlined text-[18px] hover:text-primary">format_list_numbered</button>
                            </div>
                            <textarea id="co-offer-description" rows="5"
                                class="w-full px-4 py-2.5 bg-transparent border-none outline-none font-body-sm text-body-sm text-on-surface focus:ring-0"
                                placeholder="Descripción..." required></textarea>
                        </div>
                    </div>

                    <!-- Row 3: Requirements & Benefits -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-lg">
                        <div class="space-y-xs">
                            <label class="font-label-sm text-label-sm text-on-surface-variant block">Requisitos</label>
                            <div class="border border-outline-variant rounded-xl bg-background overflow-hidden flex flex-col">
                                <div class="bg-surface-container-low px-md py-sm border-b border-outline-variant/60 flex flex-wrap gap-md items-center text-on-surface-variant/80">
                                    <span class="font-semibold text-xs border-r border-outline-variant/80 pr-md">Normal</span>
                                    <button type="button" onclick="formatTextarea('co-offer-requirements','bold')" class="font-bold hover:text-primary">B</button>
                                    <button type="button" onclick="formatTextarea('co-offer-requirements','italic')" class="italic hover:text-primary">I</button>
                                    <span class="text-outline-variant">|</span>
                                    <button type="button" onclick="formatTextarea('co-offer-requirements','bullet')" class="material-symbols-outlined text-[18px] hover:text-primary">format_list_bulleted</button>
                                    <button type="button" onclick="formatTextarea('co-offer-requirements','numeric')" class="material-symbols-outlined text-[18px] hover:text-primary">format_list_numbered</button>
                                </div>
                                <textarea id="co-offer-requirements" rows="4"
                                    class="w-full px-4 py-2.5 bg-transparent border-none outline-none font-body-sm text-body-sm text-on-surface focus:ring-0"
                                    placeholder="Requisitos..." required></textarea>
                            </div>
                        </div>
                        <div class="space-y-xs">
                            <label class="font-label-sm text-label-sm text-on-surface-variant block">Beneficios</label>
                            <div class="border border-outline-variant rounded-xl bg-background overflow-hidden flex flex-col">
                                <div class="bg-surface-container-low px-md py-sm border-b border-outline-variant/60 flex flex-wrap gap-md items-center text-on-surface-variant/80">
                                    <span class="font-semibold text-xs border-r border-outline-variant/80 pr-md">Normal</span>
                                    <button type="button" onclick="formatTextarea('co-offer-benefits','bold')" class="font-bold hover:text-primary">B</button>
                                    <button type="button" onclick="formatTextarea('co-offer-benefits','italic')" class="italic hover:text-primary">I</button>
                                    <span class="text-outline-variant">|</span>
                                    <button type="button" onclick="formatTextarea('co-offer-benefits','bullet')" class="material-symbols-outlined text-[18px] hover:text-primary">format_list_bulleted</button>
                                    <button type="button" onclick="formatTextarea('co-offer-benefits','numeric')" class="material-symbols-outlined text-[18px] hover:text-primary">format_list_numbered</button>
                                </div>
                                <textarea id="co-offer-benefits" rows="4"
                                    class="w-full px-4 py-2.5 bg-transparent border-none outline-none font-body-sm text-body-sm text-on-surface focus:ring-0"
                                    placeholder="Beneficios..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Row 4: Selects Grid (3 Columns) -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-lg">
                        <!-- Column 1 -->
                        <div class="space-y-md">
                            <!-- Tipo de contrato -->
                            <div class="space-y-xs">
                                <label class="font-label-sm text-label-sm text-on-surface-variant block" for="co-offer-contract-type">Tipo de contrato</label>
                                <div class="flex gap-2">
                                    <select id="co-offer-contract-type"
                                        class="flex-1 px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm" required>
                                        <option value="">Seleccionar...</option>
                                    </select>
                                    <button type="button" onclick="openAddLookupModal('contract_type', 'Tipo de contrato')"
                                        class="px-3 border border-outline-variant hover:bg-surface-container-high rounded-xl text-primary flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[18px]">add</span>
                                    </button>
                                </div>
                            </div>
                            <!-- Modalidad de trabajo -->
                            <div class="space-y-xs">
                                <label class="font-label-sm text-label-sm text-on-surface-variant block" for="co-offer-location">Modalidad de trabajo</label>
                                <div class="flex gap-2">
                                    <select id="co-offer-location"
                                        class="flex-1 px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm" required>
                                        <option value="">Seleccionar...</option>
                                    </select>
                                    <button type="button" onclick="openAddLookupModal('location', 'Modalidad de trabajo')"
                                        class="px-3 border border-outline-variant hover:bg-surface-container-high rounded-xl text-primary flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[18px]">add</span>
                                    </button>
                                </div>
                            </div>
                            <!-- Dirección -->
                            <div class="space-y-xs">
                                <label class="font-label-sm text-label-sm text-on-surface-variant block" for="co-offer-address">Dirección</label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">pin_drop</span>
                                    <input class="w-full pl-10 pr-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm"
                                        id="co-offer-address" placeholder="Ej. Av. De la Cultura 123" type="text" required />
                                </div>
                            </div>
                        </div>
                        <!-- Column 2 -->
                        <div class="space-y-md">
                            <!-- Jornada laboral -->
                            <div class="space-y-xs">
                                <label class="font-label-sm text-label-sm text-on-surface-variant block" for="co-offer-work-schedule">Jornada laboral</label>
                                <div class="flex gap-2">
                                    <select id="co-offer-work-schedule"
                                        class="flex-1 px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm" required>
                                        <option value="">Seleccionar...</option>
                                    </select>
                                    <button type="button" onclick="openAddLookupModal('work_schedule', 'Jornada laboral')"
                                        class="px-3 border border-outline-variant hover:bg-surface-container-high rounded-xl text-primary flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[18px]">add</span>
                                    </button>
                                </div>
                            </div>
                            <!-- Departamento -->
                            <div class="space-y-xs">
                                <label class="font-label-sm text-label-sm text-on-surface-variant block" for="co-offer-department">Departamento</label>
                                <select id="co-offer-department" onchange="handleCompanyDepartmentChange()"
                                    class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm" required>
                                    <option value="">Seleccionar...</option>
                                </select>
                            </div>
                            <!-- Categoría -->
                            <div class="space-y-xs">
                                <label class="font-label-sm text-label-sm text-on-surface-variant block" for="co-offer-category">Categoría</label>
                                <div class="flex gap-2">
                                    <select id="co-offer-category"
                                        class="flex-1 px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm" required>
                                        <option value="">Seleccionar...</option>
                                    </select>
                                    <button type="button" onclick="openAddLookupModal('category', 'Categoría')"
                                        class="px-3 border border-outline-variant hover:bg-surface-container-high rounded-xl text-primary flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[18px]">add</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Column 3 -->
                        <div class="space-y-md">
                            <div class="grid grid-cols-2 gap-sm">
                                <div class="space-y-xs">
                                    <label class="font-label-sm text-label-sm text-on-surface-variant block" for="co-offer-salary">Salario</label>
                                    <div class="relative">
                                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">payments</span>
                                        <input class="w-full pl-10 pr-3 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm"
                                            id="co-offer-salary" type="number" min="0" value="0" required />
                                    </div>
                                </div>
                                <div class="space-y-xs">
                                    <label class="font-label-sm text-label-sm text-on-surface-variant block" for="co-offer-currency">Moneda</label>
                                    <select id="co-offer-currency"
                                        class="w-full px-3 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm" required>
                                        <option value="SOLES">SOLES</option>
                                        <option value="DOLARES">DOLARES</option>
                                    </select>
                                </div>
                            </div>
                            <div class="space-y-xs">
                                <label class="font-label-sm text-label-sm text-on-surface-variant block" for="co-offer-province">Provincia</label>
                                <select id="co-offer-province"
                                    class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm" required>
                                    <option value="">Seleccionar departamento primero...</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Actions -->
                    <div class="flex justify-end gap-md pt-lg border-t border-outline-variant">
                        <button type="button" onclick="hideCreateOfferForm()"
                            class="px-6 py-2.5 border border-primary text-primary font-label-md text-label-md rounded-xl hover:bg-primary-fixed transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" id="co-btn-save-offer"
                            class="px-6 py-2.5 bg-primary text-on-primary font-label-md text-label-md rounded-xl hover:opacity-95 shadow-sm transition-all font-semibold">
                            Crear Oferta Laboral
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ================= PANEL 3: POSTULANTES ================= -->
        <div id="panel-applicants" class="tab-panel space-y-xl hidden">
            <h2 class="text-headline-sm font-headline-sm text-on-surface">Candidatos Postulados</h2>

            {{-- Nota informativa: correos de postulación pueden ir a spam --}}
            <div class="flex items-start gap-3 bg-amber-50 border border-amber-300 rounded-xl px-4 py-3 shadow-sm" role="alert">
                <span class="material-symbols-outlined text-amber-500 mt-0.5 flex-shrink-0" style="font-size:20px;">mark_email_unread</span>
                <div>
                    <p class="font-semibold text-amber-800 text-sm mb-0.5">¿No ves los correos de postulación?</p>
                    <p class="text-amber-700 text-sm leading-relaxed">
                        Los mensajes de correo con el CV de los postulantes pueden llegar a la carpeta de
                        <strong>Spam</strong> o <strong>Correo no deseado</strong> de su bandeja de entrada.
                        Si no los encuentra en su bandeja principal, revise esa carpeta y marque el remitente
                        como <em>«No es spam»</em> para recibir futuros correos sin inconvenientes.
                    </p>
                </div>
            </div>

            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-outline-variant bg-surface-container-low">
                                <th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant font-semibold">Postulante</th>
                                <th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant font-semibold">Puesto Aplicado</th>
                                <th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant font-semibold">Fecha</th>
                                <th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant font-semibold">Contacto</th>
                                <th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant font-semibold text-right">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($applicants as $app)
                            <tr class="border-b border-surface-container-high hover:bg-surface-container-lowest transition-colors">
                                <td class="px-lg py-md">
                                    <div class="flex items-center gap-sm">
                                        <div class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-primary font-bold text-label-sm">{{ strtoupper(substr($app->fullname ?? 'C', 0, 1)) }}</div>
                                        <div>
                                            <span class="font-body-sm text-body-sm text-on-surface font-semibold block">{{ $app->fullname ?? 'Candidato' }}</span>
                                            <span class="text-[11px] text-on-surface-variant">{{ $app->program_study }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-lg py-md font-body-sm text-body-sm text-on-surface-variant">{{ $app->offer_title }}</td>
                                <td class="px-lg py-md font-body-sm text-body-sm text-on-surface-variant">{{ \Carbon\Carbon::parse($app->created_at)->format('d M Y') }}</td>
                                <td class="px-lg py-md font-body-sm text-body-sm text-on-surface-variant">
                                    <span class="font-semibold text-xs py-0.5 px-2 rounded @if($app->status == 'accepted') bg-green-100 text-green-800 @elseif($app->status == 'rejected') bg-red-100 text-red-800 @else bg-yellow-100 text-yellow-800 @endif">{{ ['postulated'=>'POSTULADO','accepted'=>'ACEPTADO','rejected'=>'RECHAZADO','selected'=>'SELECCIONADO','finished'=>'FINALIZADO'][$app->status] ?? strtoupper($app->status) }}</span>
                                </td>
                                <td class="px-lg py-md text-right flex justify-end gap-3 items-center">
                                    <button type="button" data-name="{{ addslashes($app->fullname ?? 'Candidato') }}"
                                            data-career="{{ addslashes($app->person_career ?? $app->program_study ?? '') }}"
                                            data-msg="{{ addslashes($app->message ?? '') }}"
                                            data-cv="{{ $app->cv ?? '' }}"
                                            data-avatar="{{ $app->user_avatar ?? '' }}"
                                            data-skills="{{ htmlspecialchars(json_encode($app->person_skills ?? []), ENT_QUOTES, 'UTF-8') }}"
                                            data-about="{{ addslashes($app->person_about_me ?? '') }}"
                                            onclick="openApplicantModal(this)"
                                            style="color:#002741; font-weight:600; font-size:13px;" onmouseover="this.style.color='#006b60'" onmouseout="this.style.color='#002741'">Ver Perfil</button>
                                    @if($app->cv)
                                    <a href="{{ $app->cv }}" target="_blank" style="color:#002741; font-weight:600; font-size:13px; display:flex; align-items:center; gap:4px;" onmouseover="this.style.color='#006b60'" onmouseout="this.style.color='#002741'"><span class="material-symbols-outlined" style="font-size:14px;">picture_as_pdf</span> CV</a>
                                    @endif
                                    @if($app->status == 'postulated')
                                    <button onclick="updateStatus({{ $app->id }}, 'accepted')" class="px-3 py-1.5 bg-green-100 text-green-700 hover:bg-green-200 font-label-sm text-label-sm font-semibold rounded-lg transition-colors flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">check</span>
                                        Aprobar
                                    </button>
                                    <button onclick="updateStatus({{ $app->id }}, 'rejected')" class="px-3 py-1.5 bg-red-100 text-red-700 hover:bg-red-200 font-label-sm text-label-sm font-semibold rounded-lg transition-colors flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">close</span>
                                        Rechazar
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-lg py-md text-center text-on-surface-variant">No hay postulantes registrados todavía.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ================= PANEL 4: PERFIL DE EMPRESA ================= -->
        <div id="panel-profile" class="tab-panel space-y-xl hidden">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-headline-sm font-headline-sm text-on-surface mb-xs">Perfil de la Empresa</h2>
                    <p class="font-body-md text-body-md text-on-surface-variant">Gestiona la información corporativa visible para los postulantes.</p>
                </div>
            </div>

            <!-- Profile form -->
            <form id="company-profile-form" onsubmit="handleProfileSubmit(event)" class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg space-y-lg shadow-sm" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-lg">
                    <!-- Nombre de la empresa -->
                    <div class="space-y-xs">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block" for="prof-name">Nombre de la Empresa *</label>
                        <input class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm" id="prof-name" name="name" value="{{ $company->name }}" type="text" required />
                    </div>

                    <!-- RUC (lectura únicamente) -->
                    <div class="space-y-xs">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block" for="prof-ruc">RUC *</label>
                        <input class="w-full px-4 py-2.5 bg-surface-container border border-outline-variant rounded-xl outline-none transition-all font-body-sm text-body-sm text-on-surface-variant cursor-not-allowed" id="prof-ruc" value="{{ $company->ruc }}" type="text" readonly />
                    </div>

                    <!-- Email de la empresa -->
                    <div class="space-y-xs">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block" for="prof-email">Email *</label>
                        <input class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm" id="prof-email" name="email" value="{{ $company->email }}" type="email" required />
                    </div>

                    <!-- Teléfono -->
                    <div class="space-y-xs">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block" for="prof-phone">Teléfono *</label>
                        <input class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm" id="prof-phone" name="phone" value="{{ $company->phone }}" placeholder="Ej: (01) 1234-5678" type="text" required />
                    </div>

                    <!-- Dirección -->
                    <div class="space-y-xs">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block" for="prof-address">Dirección *</label>
                        <input class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm" id="prof-address" name="address" value="{{ $company->address }}" placeholder="Ej: Av. Siempre Viva 123" type="text" required />
                    </div>

                    <!-- Buzón de Correo -->
                    <div class="space-y-xs">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block" for="prof-mailbox">Buzón de Correo (Recepción de postulaciones)</label>
                        <input class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm" id="prof-mailbox" name="mailbox" value="{{ $company->mailbox }}" placeholder="Correo donde se recibirán las postulaciones" type="email" />
                    </div>

                    <!-- Página Web -->
                    <div class="space-y-xs md:col-span-2">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block" for="prof-website">Página Web</label>
                        <input class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm" id="prof-website" name="website" value="{{ $company->website }}" placeholder="Página web de la empresa" type="url" />
                    </div>

                    <!-- Acerca de la empresa -->
                    <div class="space-y-xs md:col-span-2">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block" for="prof-description">Acerca de la empresa</label>
                        <textarea class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm" id="prof-description" name="description" rows="4" placeholder="Descripción de la empresa">{{ $company->description }}</textarea>
                    </div>

                    <!-- Logotipo -->
                    <div class="space-y-sm md:col-span-2 border-t border-outline-variant pt-lg">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block">Logotipo de la Empresa</label>
                        <div class="flex items-center gap-lg">
                            <div class="w-24 h-24 rounded-2xl overflow-hidden border border-outline-variant bg-surface flex items-center justify-center shrink-0">
                                <img id="logo-preview-image" src="{{ $company->logo ?? 'https://via.placeholder.com/150?text=Sin+Logo' }}" alt="Logo Preview" class="w-full h-full object-contain">
                            </div>
                            <div class="space-y-xs">
                                <input type="file" id="logo-file-input" name="logo" class="hidden" accept="image/*" onchange="previewLogoImage(this)">
                                <button type="button" onclick="document.getElementById('logo-file-input').click()" class="px-4 py-2 bg-secondary text-on-secondary rounded-lg font-label-sm text-label-sm hover:opacity-90 flex items-center gap-1 shadow-sm transition-all font-semibold">
                                    <span class="material-symbols-outlined text-sm">cloud_upload</span>
                                    Subir Imagen
                                </button>
                                <p class="text-[11px] text-on-surface-variant">Formatos permitidos: JPG, PNG, GIF. Máximo 2MB.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form actions -->
                <div class="flex justify-end gap-md pt-lg border-t border-outline-variant">
                    <button type="submit" id="btn-save-profile" class="px-6 py-2.5 bg-primary text-on-primary font-label-md text-label-md rounded-xl hover:opacity-95 shadow-sm transition-all font-semibold">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
        
    </main>
</div>

<!-- Toast Notification Container -->
<div id="toast" class="fixed bottom-5 right-5 bg-primary text-on-primary px-lg py-md rounded-xl shadow-lg transform translate-y-20 opacity-0 transition-all duration-300 z-50 flex items-center gap-sm">
    <span class="material-symbols-outlined" id="toast-icon">check_circle</span>
    <span id="toast-message" class="font-label-md text-label-md"></span>
</div>

<!-- ================= MODAL: AGREGAR METADATOS inline (+) ================= -->
<div id="add-lookup-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/40 p-4">
    <div
        class="w-full max-w-md bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-xl overflow-hidden transform scale-95 transition-transform duration-300">
        <div
            class="flex justify-between items-center px-lg py-md border-b border-outline-variant bg-surface-container-low">
            <h2 id="lookup-modal-title" class="text-headline-md font-headline-md text-on-surface">Agregar opción</h2>
            <button onclick="toggleLookupModal()"
                class="text-on-surface-variant hover:bg-surface-container-high p-1 rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="add-lookup-form" onsubmit="handleAddLookupSubmit(event)" class="p-lg space-y-md">
            <input type="hidden" id="lookup-type" value="">
            <div class="space-y-xs">
                <label class="font-label-sm text-label-sm text-on-surface-variant block" for="lookup-name">Nombre de la opción</label>
                <input
                    class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm text-on-surface"
                    id="lookup-name" placeholder="Ej. Tiempo parcial" type="text" required />
            </div>
            <div class="flex justify-end gap-md pt-lg border-t border-outline-variant">
                <button type="button" onclick="toggleLookupModal()"
                    class="px-6 py-2.5 border border-primary text-primary font-label-md text-label-md rounded-xl hover:bg-primary-fixed transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-6 py-2.5 bg-primary text-on-primary font-label-md text-label-md rounded-xl hover:opacity-95 shadow-sm transition-all font-semibold">
                    Agregar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Detalles del Postulante -->
<div id="applicant-detail-modal" style="position:fixed;inset:0;background:rgba(0,0,0,0.5);backdrop-filter:blur(4px);z-index:50;display:none;align-items:center;justify-content:center;opacity:0;transition:opacity 0.3s;">
    <div style="background:#fff;border-radius:16px;box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);max-width:480px;width:100%;margin:0 16px;display:flex;flex-direction:column;overflow:hidden;transform:scale(0.95);transition:transform 0.3s;">
        <!-- Header con gradiente -->
        <div style="position:relative;background:linear-gradient(135deg,#002741,#004d45);padding:24px;color:#fff;">
            <button onclick="closeApplicantModal()" style="position:absolute;top:16px;right:16px;color:rgba(255,255,255,0.8);background:rgba(255,255,255,0.2);border:none;width:32px;height:32px;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;">
                <span class="material-symbols-outlined">close</span>
            </button>
            
            <div style="display:flex;align-items:center;gap:16px;">
                <div id="applicant-modal-avatar" style="width:80px;height:80px;border-radius:16px;background:rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;color:#fff;font-size:28px;font-weight:bold;border:2px solid rgba(255,255,255,0.3);overflow:hidden;flex-shrink:0;">
                    <img id="applicant-modal-avatar-img" src="" alt="" style="width:100%;height:100%;object-fit:cover;display:none;">
                    <span id="applicant-modal-avatar-initials">-</span>
                </div>
                <div style="flex:1;">
                    <h3 id="applicant-modal-name" style="font-size:20px;font-weight:bold;margin:0 0 4px 0;">-</h3>
                    <p id="applicant-modal-career" style="font-size:14px;color:rgba(255,255,255,0.8);margin:0;display:flex;align-items:center;gap:6px;">
                        <span class="material-symbols-outlined" style="font-size:16px;">school</span>
                        <span>-</span>
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div style="padding:24px;max-height:50vh;overflow-y:auto;">
            <!-- Sobre mí -->
            <div id="applicant-modal-about-section" style="margin-bottom:16px;display:none;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                    <span class="material-symbols-outlined" style="font-size:18px;color:#6b7280;">person</span>
                    <span style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:#6b7280;">Sobre mí</span>
                </div>
                <div style="background:#f9fafb;border-radius:12px;padding:16px;border:1px solid #e5e7eb;">
                    <p id="applicant-modal-about" style="font-size:14px;color:#374151;white-space:pre-wrap;line-height:1.6;margin:0;">
                        -
                    </p>
                </div>
            </div>

            <!-- Habilidades -->
            <div id="applicant-modal-skills-section" style="margin-bottom:16px;display:none;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                    <span class="material-symbols-outlined" style="font-size:18px;color:#6b7280;">star</span>
                    <span style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:#6b7280;">Habilidades</span>
                </div>
                <div id="applicant-modal-skills" style="display:flex;flex-wrap:wrap;gap:6px;">
                </div>
            </div>

            <!-- Mensaje de presentación -->
            <div style="margin-bottom:16px;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                    <span class="material-symbols-outlined" style="font-size:18px;color:#6b7280;">chat</span>
                    <span style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:#6b7280;">Mensaje de presentación</span>
                </div>
                <div style="background:#f9fafb;border-radius:12px;padding:16px;border:1px solid #e5e7eb;">
                    <p id="applicant-modal-message" style="font-size:14px;color:#374151;white-space:pre-wrap;line-height:1.6;margin:0;">
                        -
                    </p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div style="padding:0 24px 24px;display:flex;gap:12px;">
            <button onclick="closeApplicantModal()" style="flex:1;padding:12px;border:2px solid #e5e7eb;background:#fff;color:#4b5563;font-weight:600;font-size:14px;border-radius:12px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;transition:background 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='#fff'">
                <span class="material-symbols-outlined" style="font-size:18px;">close</span>
                Cerrar
            </button>
            <a id="applicant-modal-cv-link" href="#" target="_blank" style="flex:1;padding:12px;background:linear-gradient(135deg,#002741,#004d45);color:#fff;font-weight:600;font-size:14px;border-radius:12px;text-decoration:none;display:flex;align-items:center;justify-content:center;gap:8px;box-shadow:0 4px 12px rgba(0,39,65,0.3);transition:opacity 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                <span class="material-symbols-outlined" style="font-size:18px;">picture_as_pdf</span>
                Ver CV PDF
            </a>
        </div>
    </div>
</div>

<!-- Modal: Feedback/Comentario para actualizar estado -->
<div id="feedback-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 flex flex-col overflow-hidden transform scale-95 transition-transform duration-300">
        <!-- Header con color -->
        <div id="feedback-modal-header" class="px-6 py-5 text-white">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div id="feedback-modal-icon" class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[24px]">check_circle</span>
                    </div>
                    <div>
                        <h3 id="feedback-modal-title" class="text-lg font-bold">Aprobar Postulante</h3>
                        <p id="feedback-modal-subtitle" class="text-sm text-white/80">Acción para el candidato</p>
                    </div>
                </div>
                <button onclick="closeFeedbackModal()" class="text-white/80 hover:text-white hover:bg-white/20 p-2 rounded-full transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
        </div>
        
        <!-- Content -->
        <div class="p-6 space-y-4">
            <p id="feedback-modal-description" class="text-gray-600 text-sm leading-relaxed">
                Agrega un comentario opcional para el candidato.
            </p>
            
            <div class="space-y-2">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider block" for="feedback-textarea">Comentario (opcional)</label>
                <textarea 
                    id="feedback-textarea" 
                    rows="4"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition-all text-sm text-gray-800 resize-none placeholder-gray-400"
                    placeholder="Escribe un mensaje para el candidato..."></textarea>
            </div>
        </div>

        <!-- Actions -->
        <div class="px-6 pb-6 flex gap-3">
            <button onclick="closeFeedbackModal()" class="flex-1 py-3 border-2 border-gray-200 text-gray-600 font-semibold text-sm rounded-xl hover:bg-gray-50 transition-colors">
                Cancelar
            </button>
            <button id="feedback-submit-btn" onclick="submitFeedback()" class="flex-1 py-3 text-white font-semibold text-sm rounded-xl transition-all flex items-center justify-center gap-2 shadow-lg">
                <span class="material-symbols-outlined text-[18px]">send</span>
                Confirmar
            </button>
        </div>
    </div>
</div>

<!-- Modal: Confirmación genérica (cambiar estado / eliminar oferta) -->
<div id="confirm-modal" style="position:fixed;inset:0;background:rgba(0,0,0,0.5);backdrop-filter:blur(4px);z-index:60;display:none;align-items:center;justify-content:center;opacity:0;transition:opacity 0.3s;">
    <div style="background:#fff;border-radius:16px;box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);max-width:420px;width:100%;margin:0 16px;display:flex;flex-direction:column;overflow:hidden;transform:scale(0.95);transition:transform 0.3s;">
        <!-- Header -->
        <div id="confirm-modal-header" style="padding:24px 24px 16px;text-align:center;">
            <div id="confirm-modal-icon-wrap" style="width:56px;height:56px;border-radius:50%;margin:0 auto 12px;display:flex;align-items:center;justify-content:center;">
                <span id="confirm-modal-icon" class="material-symbols-outlined" style="font-size:28px;color:#fff;">help</span>
            </div>
            <h3 id="confirm-modal-title" style="font-size:18px;font-weight:700;margin:0 0 6px 0;color:#1f2937;">Confirmar acción</h3>
            <p id="confirm-modal-message" style="font-size:14px;color:#6b7280;margin:0;line-height:1.5;">¿Estás seguro de realizar esta acción?</p>
        </div>

        <!-- Actions -->
        <div style="padding:0 24px 24px;display:flex;gap:12px;">
            <button id="confirm-modal-cancel-btn" onclick="closeConfirmModal()" style="flex:1;padding:12px;border:2px solid #e5e7eb;background:#fff;color:#4b5563;font-weight:600;font-size:14px;border-radius:12px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;transition:background 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='#fff'">
                <span class="material-symbols-outlined" style="font-size:18px;">close</span>
                Cancelar
            </button>
            <button id="confirm-modal-ok-btn" onclick="executeConfirmAction()" style="flex:1;padding:12px;color:#fff;font-weight:600;font-size:14px;border-radius:12px;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;box-shadow:0 4px 12px rgba(0,0,0,0.15);transition:opacity 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                <span id="confirm-modal-ok-icon" class="material-symbols-outlined" style="font-size:18px;">check</span>
                <span id="confirm-modal-ok-label">Confirmar</span>
            </button>
        </div>
    </div>
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
            if (currentTabId === tabId) {
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

    function previewLogoImage(input) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logo-preview-image').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        const toastMsg = document.getElementById('toast-message');
        const toastIcon = document.getElementById('toast-icon');
        
        toastMsg.textContent = message;
        
        if (type === 'error') {
            toast.className = "fixed bottom-5 right-5 bg-red-600 text-on-primary px-lg py-md rounded-xl shadow-lg transform transition-all duration-300 z-50 flex items-center gap-sm";
            toastIcon.textContent = 'error';
        } else {
            toast.className = "fixed bottom-5 right-5 bg-primary text-on-primary px-lg py-md rounded-xl shadow-lg transform transition-all duration-300 z-50 flex items-center gap-sm";
            toastIcon.textContent = 'check_circle';
        }
        
        toast.classList.remove('translate-y-20', 'opacity-0');
        
        setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0');
        }, 3000);
    }

    function handleProfileSubmit(event) {
        event.preventDefault();
        
        const form = document.getElementById('company-profile-form');
        const btn = document.getElementById('btn-save-profile');
        const formData = new FormData(form);
        const token = form.querySelector('input[name="_token"]').value;
        
        btn.setAttribute('disabled', 'true');
        btn.innerHTML = `<span class="material-symbols-outlined animate-spin text-[16px] leading-none align-middle mr-1">autorenew</span> Guardando...`;
        
        fetch('/company/profile', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            btn.removeAttribute('disabled');
            btn.innerHTML = 'Guardar Cambios';
            
            if (data.success) {
                showToast(data.message);
                
                // Update header company name
                const headerName = document.getElementById('header-company-name');
                if (headerName) {
                    headerName.textContent = data.company.name;
                }
                
                // Update header logo/initials
                const headerLogoContainer = document.querySelector('.w-10.h-10.rounded-full');
                if (headerLogoContainer && data.company.logo) {
                    headerLogoContainer.innerHTML = `<img id="header-logo" src="${data.company.logo}" alt="Logo" class="w-full h-full object-cover">`;
                } else if (headerLogoContainer) {
                    headerLogoContainer.innerHTML = `
                        <div id="header-initials" class="w-full h-full flex items-center justify-center bg-primary-container text-on-primary font-bold">
                            ${data.company.name.charAt(0).toUpperCase()}
                        </div>
                    `;
                }
                
                // Remove warning banner if it exists
                const warningBanner = document.getElementById('profile-warning-banner');
                if (warningBanner) {
                    warningBanner.remove();
                }
            } else {
                showToast(data.message || 'Error al guardar los cambios.', 'error');
            }
        })
        .catch(err => {
            btn.removeAttribute('disabled');
            btn.innerHTML = 'Guardar Cambios';
            showToast('Error de red al intentar guardar.', 'error');
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Add click events to tab buttons
        const tabBtns = document.querySelectorAll('.tab-btn');
        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                switchTab(tabId);
                if (tabId === 'offers') loadCompanyOffers();
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

        // Initialize to show 'dashboard' panel first or the tab specified in URL
        const urlParams = new URLSearchParams(window.location.search);
        const tabToOpen = urlParams.get('tab') || 'dashboard';
        const validTabs = ['dashboard', 'offers', 'applicants', 'profile'];
        switchTab(validTabs.includes(tabToOpen) ? tabToOpen : 'dashboard');
    });

    /* ============================================================
       COMPANY OFFER MANAGEMENT – mirrors admin panel
    ============================================================ */
    let companyOfferMeta = null;
    const CSRF = '{{ csrf_token() }}';

    async function loadCompanyOfferMeta() {
        if (!companyOfferMeta) {
            const res = await fetch('/company/offers/meta', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await res.json();
            if (data.success) {
                companyOfferMeta = data;
            }
        }
        if (companyOfferMeta) {
            populateCompanySelect('co-offer-contract-type', companyOfferMeta.contract_types);
            populateCompanySelect('co-offer-location', companyOfferMeta.locations);
            populateCompanySelect('co-offer-work-schedule', companyOfferMeta.work_schedules);
            populateCompanySelect('co-offer-category', companyOfferMeta.categories);
            populateCompanyDepartments();
        }
        return companyOfferMeta;
    }

    function populateCompanySelect(id, items, selectedId = null) {
        const sel = document.getElementById(id);
        if (!sel) return;
        const cur = selectedId || sel.value;
        sel.innerHTML = '<option value="">Seleccionar...</option>';
        (items || []).forEach(i => {
            const opt = document.createElement('option');
            opt.value = i.id;
            opt.textContent = i.name;
            if (String(i.id) === String(cur)) opt.selected = true;
            sel.appendChild(opt);
        });
    }

    const PERU_DEPARTMENTS = {
        "Amazonas": ["Chachapoyas", "Bagua", "Bongará", "Condorcanqui", "Luya", "Rodríguez de Mendoza", "Utcubamba"],
        "Áncash": ["Huaraz", "Aija", "Antonio Raymondi", "Asunción", "Bolognesi", "Carhuaz", "Carlos Fermín Fitzcarrald", "Casma", "Corongo", "Doméstico", "Huari", "Huarmey", "Huaylas", "Mariscal Luzuriaga", "Ocros", "Pallasca", "Pomabamba", "Recuay", "Santa", "Sihuas", "Yungay"],
        "Apurímac": ["Abancay", "Andahuaylas", "Antabamba", "Cotabambas", "Grau", "Chincheros", "Aymaraes"],
        "Arequipa": ["Arequipa", "Camaná", "Caravelí", "Castilla", "Caylloma", "Condesuyos", "Islay", "La Unión"],
        "Ayacucho": ["Cangallo", "Huamanga", "Huanca Sancos", "Huanta", "La Mar", "Lucanas", "Parinacochas", "Paucar del Sara Sara", "Sucre", "Victor Fajardo", "Vilcas Huaman"],
        "Cajamarca": ["Cajamarca", "Cajabamba", "Celendín", "Chota", "Contumazá", "Cutervo", "Hualgayoc", "Jaén", "San Ignacio", "San Marcos", "San Miguel", "San Pablo", "Santa Cruz"],
        "Callao": ["Callao"],
        "Cusco": ["Cusco", "Acomayo", "Anta", "Calca", "Canas", "Canchis", "Chumbivilcas", "Espinar", "La Convención", "Paruro", "Quispicanchi", "Urubamba"],
        "Huancavelica": ["Huancavelica", "Acobamba", "Angaraes", "Castrovirreyna", "Churcampa", "Huaytará", "Tayacaja"],
        "Huánuco": ["Huánuco", "Ambo", "Dos de Mayo", "Huacaybamba", "Huamalíes", "Leoncio Prado", "Marañón", "Pachitea", "Puerto Inca", "Lauricocha", "Yarowilca"],
        "Ica": ["Ica", "Chincha", "Nasca", "Palpa", "Pisco"],
        "Junín": ["Huancayo", "Chanchamayo", "Chupaca", "Concepción", "Jauja", "Junín", "Satipo", "Tarma", "Yauli"],
        "La Libertad": ["Trujillo", "Ascope", "Bolívar", "Chepén", "Gran Chimú", "Julcán", "Otuzco", "Pacasmayo", "Pataz", "Sánchez Carrión", "Santiago de Chuco", "Virú"],
        "Lambayeque": ["Chiclayo", "Ferreñafe", "Lambayeque"],
        "Lima": ["Lima", "Barranca", "Cajatambo", "Canta", "Cañete", "Huaral", "Huarochirí", "Huaura", "Oyón", "Yauyos"],
        "Loreto": ["Iquitos", "Alto Amazonas", "Loreto", "Mariscal Ramón Castilla", "Requena", "Ucayali", "Datem del Marañón", "Putumayo"],
        "Madre de Dios": ["Tambopata", "Manu", "Tahuamanu"],
        "Moquegua": ["Mariscal Nieto", "General Sánchez Cerro", "Ilo"],
        "Pasco": ["Pasco", "Daniel Alcides Carrión", "Oxapampa"],
        "Piura": ["Piura", "Ayabaca", "Huancabamba", "Morropón", "Paita", "Sechura", "Sullana", "Talara"],
        "Puno": ["Puno", "Azángaro", "Carabaya", "Chucuito", "El Collao", "Huancané", "Lampa", "Melgar", "Moho", "San Antonio de Putina", "San Román", "Sandia", "Yunguyo"],
        "San Martín": ["Moyobamba", "Bellavista", "El Dorado", "Huallaga", "Lamas", "Mariscal Cáceres", "Picota", "Rioja", "San Martín", "Tocache"],
        "Tacna": ["Tacna", "Candarave", "Jorge Basadre", "Tarata"],
        "Tumbes": ["Tumbes", "Contralmirante Villar", "Zarumilla"],
        "Ucayali": ["Coronel Portillo", "Atalaya", "Padre Abad", "Purús"]
    };

    function populateCompanyDepartments() {
        const sel = document.getElementById('co-offer-department');
        if (!sel) return;
        const cur = sel.value;
        sel.innerHTML = '<option value="">Seleccionar...</option>';
        Object.keys(PERU_DEPARTMENTS).sort().forEach(d => {
            const opt = document.createElement('option');
            opt.value = d;
            opt.textContent = d;
            if (d === cur) opt.selected = true;
            sel.appendChild(opt);
        });
    }

    function handleCompanyDepartmentChange() {
        const dept = document.getElementById('co-offer-department').value;
        const provSel = document.getElementById('co-offer-province');
        provSel.innerHTML = '<option value="">Seleccionar provincia...</option>';
        if (dept && PERU_DEPARTMENTS[dept]) {
            PERU_DEPARTMENTS[dept].sort().forEach(p => {
                const opt = document.createElement('option');
                opt.value = p;
                opt.textContent = p;
                provSel.appendChild(opt);
            });
        }
    }

    function loadCompanyOffers() {
        const tbody = document.getElementById('company-offers-table-body');
        tbody.innerHTML = '<tr><td colspan="8" class="text-center py-xl text-on-surface-variant">Cargando ofertas...</td></tr>';
        const search  = (document.getElementById('company-search-offers-input')?.value || '').trim();
        const sortBy  = document.getElementById('company-filter-sort')?.value || 'recent';
        const params  = new URLSearchParams({ search, sort_by: sortBy });

        fetch(`/company/offers?${params}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(data => {
                if (!data.success) { tbody.innerHTML = '<tr><td colspan="8" class="text-center py-xl text-red-600">Error al cargar ofertas.</td></tr>'; return; }
                const offers = data.offers;
                if (!offers.length) { tbody.innerHTML = '<tr><td colspan="8" class="text-center py-xl text-on-surface-variant">No hay ofertas publicadas aún.</td></tr>'; return; }

                const stateLabels = { 1:'Borrador', 2:'Activa', 3:'Finalizada', 4:'Pausada', 5:'Archivada' };
                const stateColors = { 1:'bg-surface-container text-on-surface-variant', 2:'bg-secondary-container text-on-secondary-container', 3:'bg-surface-container text-on-surface-variant', 4:'bg-tertiary-fixed text-on-tertiary-fixed-variant', 5:'bg-surface-container text-on-surface-variant' };

                tbody.innerHTML = offers.map(o => {
                    const stateId   = o.state_id || 1;
                    const stateLbl  = o.state?.name || stateLabels[stateId] || 'Borrador';
                    const stateClr  = stateColors[stateId] || stateColors[1];
                    const toggleLbl = stateId === 2 ? 'Finalizar' : 'Activar';
                    return `
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1">
                                <button onclick="editCompanyOffer(${o.id})" title="Editar" class="p-1.5 rounded-lg hover:bg-surface-container-high text-primary"><span class="material-symbols-outlined text-[18px]">edit</span></button>
                                <button onclick="deleteCompanyOffer(${o.id})" title="Eliminar" class="p-1.5 rounded-lg hover:bg-error-container text-error"><span class="material-symbols-outlined text-[18px]">delete</span></button>
                                <button onclick="toggleCompanyOfferState(${o.id})" title="${toggleLbl}" class="p-1.5 rounded-lg hover:bg-surface-container-high text-on-surface-variant"><span class="material-symbols-outlined text-[18px]">${stateId === 2 ? 'pause' : 'play_arrow'}</span></button>
                                <button onclick="window.open('/?offer=${o.id}', '_blank')" title="Ver publicación de oferta" class="p-1.5 rounded-lg hover:bg-surface-container-high text-secondary"><span class="material-symbols-outlined text-[18px]">visibility</span></button>
                            </div>
                        </td>
                        <td class="px-4 py-3"><span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold ${stateClr}">${stateLbl}</span></td>
                        <td class="px-4 py-3 font-semibold">${o.title}</td>
                        <td class="px-4 py-3">${o.salary_currency === 'DOLARES' ? '$' : 'S/'} ${Number(o.salary).toLocaleString()}</td>
                        <td class="px-4 py-3">${o.category?.name || '-'}</td>
                        <td class="px-4 py-3">${o.work_schedule?.name || '-'}</td>
                        <td class="px-4 py-3">${o.location?.name || '-'}</td>
                        <td class="px-4 py-3">${o.contract_type?.name || '-'}</td>
                    </tr>`;
                }).join('');
            })
            .catch(() => { tbody.innerHTML = '<tr><td colspan="8" class="text-center py-xl text-red-600">Error de red al cargar ofertas.</td></tr>'; });
    }

    function showCreateOfferForm() {
        document.getElementById('company-form-offer-id').value = '';
        document.getElementById('company-offer-form').reset();
        document.getElementById('co-offer-pub-date').value = new Date().toISOString().split('T')[0];
        document.getElementById('company-form-offer-title').textContent = 'Crear Oferta Laboral';
        document.getElementById('co-btn-save-offer').textContent = 'Crear Oferta Laboral';
        document.getElementById('company-offers-list-view').classList.add('hidden');
        document.getElementById('company-offers-form-view').classList.remove('hidden');
        loadCompanyOfferMeta();
    }

    function hideCreateOfferForm() {
        document.getElementById('company-offers-form-view').classList.add('hidden');
        document.getElementById('company-offers-list-view').classList.remove('hidden');
        loadCompanyOffers();
    }

    function handleCompanyOfferSubmit(event) {
        event.preventDefault();
        const id  = document.getElementById('company-form-offer-id').value;
        const btn = document.getElementById('co-btn-save-offer');
        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[16px] leading-none align-middle mr-1">autorenew</span> Guardando...';

        const payload = {
            title:            document.getElementById('co-offer-title').value,
            description:      document.getElementById('co-offer-description').value,
            requirements:     document.getElementById('co-offer-requirements').value,
            benefits:         document.getElementById('co-offer-benefits').value,
            publication_date: document.getElementById('co-offer-pub-date').value,
            salary:           document.getElementById('co-offer-salary').value,
            salary_currency:  document.getElementById('co-offer-currency').value,
            address:          document.getElementById('co-offer-address').value,
            department:       document.getElementById('co-offer-department').value,
            province:         document.getElementById('co-offer-province').value,
            location_id:      document.getElementById('co-offer-location').value,
            category_id:      document.getElementById('co-offer-category').value,
            work_schedule_id: document.getElementById('co-offer-work-schedule').value,
            contract_type_id: document.getElementById('co-offer-contract-type').value,
        };

        const url    = id ? `/company/offers/${id}` : '/company/offers';
        const method = id ? 'PUT' : 'POST';

        fetch(url, {
            method,
            body:    JSON.stringify(payload),
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            btn.disabled = false;
            btn.textContent = id ? 'Guardar Cambios' : 'Crear Oferta Laboral';
            if (data.success) {
                showToast(data.message);
                hideCreateOfferForm();
            } else {
                showToast(data.message || 'Error al guardar la oferta.', 'error');
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.textContent = id ? 'Guardar Cambios' : 'Crear Oferta Laboral';
            showToast('Error de red al intentar guardar la oferta.', 'error');
        });
    }

    function editCompanyOffer(id) {
        fetch(`/company/offers/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(async data => {
                if (!data.success) { showToast('No se pudieron cargar los datos de la oferta.', 'error'); return; }
                await loadCompanyOfferMeta();
                const o = data.offer;
                document.getElementById('company-form-offer-id').value = o.id;
                document.getElementById('co-offer-title').value         = o.title || '';
                document.getElementById('co-offer-description').value   = o.description || '';
                document.getElementById('co-offer-requirements').value  = o.requirements || '';
                document.getElementById('co-offer-benefits').value      = o.benefits || '';
                document.getElementById('co-offer-pub-date').value      = o.publication_date || '';
                document.getElementById('co-offer-salary').value        = o.salary || 0;
                document.getElementById('co-offer-currency').value      = o.salary_currency || 'SOLES';
                document.getElementById('co-offer-address').value       = o.address || '';
                populateCompanySelect('co-offer-contract-type', companyOfferMeta.contract_types, o.contract_type_id);
                populateCompanySelect('co-offer-location',      companyOfferMeta.locations, o.location_id);
                populateCompanySelect('co-offer-work-schedule', companyOfferMeta.work_schedules, o.work_schedule_id);
                populateCompanySelect('co-offer-category',      companyOfferMeta.categories, o.category_id);
                populateCompanyDepartments();
                document.getElementById('co-offer-department').value = o.department || '';
                handleCompanyDepartmentChange();
                document.getElementById('co-offer-province').value = o.province || '';
                document.getElementById('company-form-offer-title').textContent = 'Editar Oferta Laboral';
                document.getElementById('co-btn-save-offer').textContent = 'Guardar Cambios';
                document.getElementById('company-offers-list-view').classList.add('hidden');
                document.getElementById('company-offers-form-view').classList.remove('hidden');
            })
            .catch(() => showToast('Error de red al cargar detalles de oferta.', 'error'));
    }

    /* ===== Modal de Confirmación Genérico ===== */
    let confirmModalCallback = null;

    function openConfirmModal({ title, message, icon, iconBg, okLabel, okBg, okColor, callback }) {
        document.getElementById('confirm-modal-title').textContent = title || 'Confirmar acción';
        document.getElementById('confirm-modal-message').textContent = message || '¿Estás seguro?';
        
        const iconWrap = document.getElementById('confirm-modal-icon-wrap');
        iconWrap.style.background = iconBg || 'linear-gradient(135deg,#002741,#004d45)';
        
        const iconEl = document.getElementById('confirm-modal-icon');
        iconEl.textContent = icon || 'help';
        
        const okBtn = document.getElementById('confirm-modal-ok-btn');
        okBtn.style.background = okBg || 'linear-gradient(135deg,#002741,#004d45)';
        okBtn.style.color = okColor || '#fff';
        
        document.getElementById('confirm-modal-ok-label').textContent = okLabel || 'Confirmar';
        document.getElementById('confirm-modal-ok-icon').textContent = icon || 'check';
        
        confirmModalCallback = callback || null;

        const modal = document.getElementById('confirm-modal');
        const modalContent = modal.querySelector('div');
        modal.style.display = 'flex';
        setTimeout(() => {
            modal.style.opacity = '1';
            modalContent.style.transform = 'scale(1)';
        }, 10);
    }

    function closeConfirmModal() {
        const modal = document.getElementById('confirm-modal');
        const modalContent = modal.querySelector('div');
        modal.style.opacity = '0';
        modalContent.style.transform = 'scale(0.95)';
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
        confirmModalCallback = null;
    }

    function executeConfirmAction() {
        if (typeof confirmModalCallback === 'function') {
            confirmModalCallback();
        }
        closeConfirmModal();
    }

    function toggleCompanyOfferState(id) {
        openConfirmModal({
            title: 'Cambiar estado de oferta',
            message: '¿Estás seguro de que deseas cambiar el estado de esta oferta laboral?',
            icon: 'swap_horiz',
            iconBg: 'linear-gradient(135deg,#002741,#004d45)',
            okLabel: 'Sí, cambiar',
            okBg: 'linear-gradient(135deg,#002741,#004d45)',
            callback: function() {
                fetch(`/company/offers/${id}/toggle-state`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) { showToast('Estado de la oferta actualizado.'); loadCompanyOffers(); }
                    else showToast(data.message || 'Error al cambiar estado.', 'error');
                })
                .catch(() => showToast('Error de red.', 'error'));
            }
        });
    }

    function deleteCompanyOffer(id) {
        openConfirmModal({
            title: 'Eliminar oferta laboral',
            message: 'Esta acción es permanente. ¿Estás seguro de que deseas eliminar esta oferta laboral?',
            icon: 'delete',
            iconBg: 'linear-gradient(135deg,#dc2626,#991b1b)',
            okLabel: 'Sí, eliminar',
            okBg: 'linear-gradient(135deg,#dc2626,#991b1b)',
            callback: function() {
                fetch(`/company/offers/${id}`, {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) { showToast('Oferta laboral eliminada.'); loadCompanyOffers(); }
                    else showToast(data.message || 'Error al eliminar oferta.', 'error');
                })
                .catch(() => showToast('Error de red al eliminar oferta.', 'error'));
            }
        });
    }

    let pendingFeedbackAppId = null;
    let pendingFeedbackStatus = null;

    function updateStatus(appId, status) {
        pendingFeedbackAppId = appId;
        pendingFeedbackStatus = status;
        
        const modal = document.getElementById('feedback-modal');
        const modalContainer = modal.querySelector('.bg-white');
        const header = document.getElementById('feedback-modal-header');
        const title = document.getElementById('feedback-modal-title');
        const subtitle = document.getElementById('feedback-modal-subtitle');
        const description = document.getElementById('feedback-modal-description');
        const icon = document.getElementById('feedback-modal-icon');
        const textarea = document.getElementById('feedback-textarea');
        const submitBtn = document.getElementById('feedback-submit-btn');
        
        // Reset textarea
        textarea.value = '';
        
        // Configure based on status
        if (status === 'accepted') {
            header.style.background = 'linear-gradient(135deg, #16a34a, #15803d)';
            title.textContent = 'Aprobar Postulante';
            subtitle.textContent = 'Candidato seleccionado';
            description.textContent = '¿Deseas aprobar a este candidato? Puedes agregar un mensaje de felicitación.';
            icon.innerHTML = '<span class="material-symbols-outlined text-[24px] text-white">check_circle</span>';
            submitBtn.style.background = 'linear-gradient(135deg, #16a34a, #15803d)';
            submitBtn.innerHTML = '<span class="material-symbols-outlined text-[18px]">check</span> Aprobar';
        } else {
            header.style.background = 'linear-gradient(135deg, #dc2626, #b91c1c)';
            title.textContent = 'Rechazar Postulante';
            subtitle.textContent = 'Candidato no seleccionado';
            description.textContent = '¿Deseas rechazar a este candidato? Puedes agregar un comentario constructivo.';
            icon.innerHTML = '<span class="material-symbols-outlined text-[24px] text-white">cancel</span>';
            submitBtn.style.background = 'linear-gradient(135deg, #dc2626, #b91c1c)';
            submitBtn.innerHTML = '<span class="material-symbols-outlined text-[18px]">close</span> Rechazar';
        }
        
        // Show modal
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContainer.classList.remove('scale-95');
            modal.classList.remove('opacity-0');
            textarea.focus();
        }, 10);
    }

    function closeFeedbackModal() {
        const modal = document.getElementById('feedback-modal');
        const modalContainer = modal.querySelector('.bg-white');
        
        modalContainer.classList.add('scale-95');
        modal.classList.add('opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            pendingFeedbackAppId = null;
            pendingFeedbackStatus = null;
        }, 300);
    }

    function submitFeedback() {
        if (!pendingFeedbackAppId || !pendingFeedbackStatus) return;
        
        const feedback = document.getElementById('feedback-textarea').value;
        const submitBtn = document.getElementById('feedback-submit-btn');
        
        // Disable button and show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="material-symbols-outlined text-[18px] animate-spin">progress_activity</span> Procesando...';
        
        fetch('/company/applications/' + pendingFeedbackAppId + '/status', {
            method: 'POST',
            body: JSON.stringify({ status: pendingFeedbackStatus, feedback }),
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            submitBtn.disabled = false;
            if (data.success) {
                showToast('¡Postulante actualizado!');
                closeFeedbackModal();
                // Reload page to refresh the applicants list
                setTimeout(() => location.reload(), 500);
            } else {
                showToast(data.message || 'Error.', 'error');
                submitBtn.innerHTML = '<span class="material-symbols-outlined text-[18px]">send</span> Confirmar';
            }
        })
        .catch(() => {
            submitBtn.disabled = false;
            showToast('Error de red.', 'error');
            submitBtn.innerHTML = '<span class="material-symbols-outlined text-[18px]">send</span> Confirmar';
        });
    }

    function formatTextarea(id, format) {
        const ta = document.getElementById(id);
        if (!ta) return;
        const start = ta.selectionStart, end = ta.selectionEnd, sel = ta.value.substring(start, end);
        let insert = sel;
        if (format === 'bold')    insert = `**${sel}**`;
        if (format === 'italic')  insert = `_${sel}_`;
        if (format === 'bullet')  insert = `\n• ${sel}`;
        if (format === 'numeric') insert = `\n1. ${sel}`;
        ta.setRangeText(insert, start, end, 'end');
        ta.focus();
    }

    // Toggle Metadata Add Inline Modal
    let activeLookupSelectId = '';
    function openAddLookupModal(type, label) {
        activeLookupSelectId = {
            'contract_type': 'co-offer-contract-type',
            'location': 'co-offer-location',
            'work_schedule': 'co-offer-work-schedule',
            'category': 'co-offer-category'
        }[type];

        document.getElementById('lookup-type').value = type;
        document.getElementById('lookup-modal-title').textContent = `Agregar ${label}`;
        document.getElementById('lookup-name').value = '';

        toggleLookupModal();
    }

    function toggleLookupModal() {
        const modal = document.getElementById('add-lookup-modal');
        const modalContainer = modal.querySelector('.max-w-md');

        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            setTimeout(() => modalContainer.classList.remove('scale-95'), 10);
            document.getElementById('lookup-name').focus();
        } else {
            modalContainer.classList.add('scale-95');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }
    }

    function handleAddLookupSubmit(event) {
        event.preventDefault();

        const type = document.getElementById('lookup-type').value;
        const name = document.getElementById('lookup-name').value;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('/company/offers/meta/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ type, name: name.trim() })
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast('Opción agregada exitosamente.');

                    // Append to selection field and select it
                    const select = document.getElementById(activeLookupSelectId);
                    const opt = document.createElement('option');
                    opt.value = data.item.id;
                    opt.textContent = data.item.name;
                    opt.selected = true;
                    select.appendChild(opt);

                    // Update the cached meta object
                    if (companyOfferMeta) {
                        if (type === 'contract_type') companyOfferMeta.contract_types.push(data.item);
                        else if (type === 'location') companyOfferMeta.locations.push(data.item);
                        else if (type === 'work_schedule') companyOfferMeta.work_schedules.push(data.item);
                        else if (type === 'category') companyOfferMeta.categories.push(data.item);
                    }

                    toggleLookupModal();
                } else {
                    showToast(data.message || 'Error al agregar opción.', 'error');
                }
            })
            .catch(() => showToast('Error de red al agregar opción.', 'error'));
    }

    function openApplicantModal(btn) {
        // Read data from data-* attributes to avoid inline JS injection issues
        const name     = btn.dataset.name    || 'Candidato';
        const career   = btn.dataset.career  || '';
        const msg      = btn.dataset.msg     || '';
        const cvUrl    = btn.dataset.cv      || '';
        const avatarUrl= btn.dataset.avatar  || '';
        const aboutMe  = btn.dataset.about   || '';
        let skillsJson = btn.dataset.skills  || '[]';

        const modal = document.getElementById('applicant-detail-modal');
        const modalContent = modal.querySelector('div');

        // Set values
        document.getElementById('applicant-modal-name').textContent = name;
        document.getElementById('applicant-modal-career').querySelector('span:last-child').textContent = career || 'No especificado';
        document.getElementById('applicant-modal-message').textContent = msg ? msg.trim() : 'Sin mensaje de presentación.';

        // Avatar - foto real o iniciales
        const avatarImg = document.getElementById('applicant-modal-avatar-img');
        const avatarInitials = document.getElementById('applicant-modal-avatar-initials');
        if (avatarUrl && avatarUrl !== '') {
            avatarImg.src = avatarUrl;
            avatarImg.style.display = 'block';
            avatarInitials.style.display = 'none';
        } else {
            avatarImg.style.display = 'none';
            avatarInitials.style.display = 'flex';
            const initials = name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
            avatarInitials.textContent = initials || 'C';
        }

        // Sobre mí
        const aboutSection = document.getElementById('applicant-modal-about-section');
        const aboutText = document.getElementById('applicant-modal-about');
        if (aboutMe && aboutMe.trim() !== '') {
            aboutText.textContent = aboutMe.trim();
            aboutSection.style.display = 'block';
        } else {
            aboutSection.style.display = 'none';
        }

        // Habilidades
        const skillsSection = document.getElementById('applicant-modal-skills-section');
        const skillsContainer = document.getElementById('applicant-modal-skills');
        skillsContainer.innerHTML = '';
        let skills = [];
        try {
            skills = JSON.parse(skillsJson);
        } catch(e) {
            skills = [];
        }
        if (Array.isArray(skills) && skills.length > 0) {
            skills.forEach(function(skill) {
                const tag = document.createElement('span');
                tag.textContent = skill;
                tag.style.cssText = 'background:#e0f2f1;color:#004d45;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:500;';
                skillsContainer.appendChild(tag);
            });
            skillsSection.style.display = 'block';
        } else {
            skillsSection.style.display = 'none';
        }

        // CV Link
        const cvLink = document.getElementById('applicant-modal-cv-link');
        if (cvUrl && cvUrl !== '') {
            cvLink.href = cvUrl;
            cvLink.style.display = 'flex';
        } else {
            cvLink.style.display = 'none';
        }

        // Open modal
        modal.style.display = 'flex';
        setTimeout(() => {
            modal.style.opacity = '1';
            modalContent.style.transform = 'scale(1)';
        }, 10);
    }

    function closeApplicantModal() {
        const modal = document.getElementById('applicant-detail-modal');
        const modalContent = modal.querySelector('div');
        
        modal.style.opacity = '0';
        modalContent.style.transform = 'scale(0.95)';
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const lookupModal = document.getElementById('add-lookup-modal');
                if (lookupModal && !lookupModal.classList.contains('hidden')) {
                    toggleLookupModal();
                }
                const appModal = document.getElementById('applicant-detail-modal');
                if (appModal && appModal.style.display !== 'none') {
                    closeApplicantModal();
                }
                const feedbackModal = document.getElementById('feedback-modal');
                if (feedbackModal && !feedbackModal.classList.contains('hidden')) {
                    closeFeedbackModal();
                }
                const confirmModal = document.getElementById('confirm-modal');
                if (confirmModal && confirmModal.style.display !== 'none') {
                    closeConfirmModal();
                }
            }
        });

        // Cerrar modal de confirmación al hacer click afuera
        document.getElementById('confirm-modal').addEventListener('click', function(e) {
            if (e.target === this) closeConfirmModal();
        });
    });
</script>

</body>
</html>
