<!DOCTYPE html>
<html class="light" lang="es">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel de Administración</title>
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
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&amp;family=Manrope:wght@600;700&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <style>
        .material-symbols-outlined {
            font-family: 'Material Symbols Outlined';
            font-weight: normal;
            font-style: normal;
            font-size: 24px;
            line-height: 1;
            letter-spacing: normal;
            text-transform: none;
            display: inline-block;
            white-space: nowrap;
            word-wrap: normal;
            direction: ltr;
            -webkit-font-feature-settings: 'liga';
            -webkit-font-smoothing: antialiased;
        }

        /* Custom Switch Toggle Style */
        .switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #c2c7ce;
            transition: .3s;
            border-radius: 24px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .3s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #18A999;
        }

        input:checked+.slider:before {
            transform: translateX(20px);
        }
    </style>

</head>

<body class="bg-background text-on-background font-body-md min-h-screen flex {{ ($config['interface_density'] ?? 'comfortable') === 'compact' ? 'admin-density-compact' : '' }} {{ ($config['sidebar_style'] ?? 'expanded') === 'compact' ? 'admin-sidebar-compact' : '' }}">
    @php
        $adminUser = auth()->user();
        $adminPerson = $adminUser?->person;
        $adminName = $adminPerson?->names ?: 'Administrador';
        $adminEmail = $adminUser?->email ?: '';
        $adminNameParts = preg_split('/\s+/', trim($adminName));
        $adminInitials = collect($adminNameParts)->filter()->take(2)->map(fn($part) => mb_strtoupper(mb_substr($part, 0, 1)))->implode('');
        $adminAvatarUrl = $adminUser?->avatar ? \Illuminate\Support\Facades\Storage::url($adminUser->avatar) : null;

        $sidebarConfig = [
            'logo' => $config['logo'] ?? '/assets/logo.png',
            'brand' => 'Bolsa Laboral',
            'subtitle' => 'Panel de Administración',
            'active' => '',
            'home_tab' => 'dashboard',
            'show_publish' => true,
            'publish_tab' => 'offers',
            'publish_label' => 'Publicar Oferta',
            'show_help' => true,
            'help_label' => 'Soporte',
            'help_tab' => 'support',
            'items' => [
                [
                    'label' => 'Configuración',
                    'items' => [
                        ['key' => 'dashboard', 'icon' => 'dashboard', 'label' => 'Dashboard'],
                        ['key' => 'users', 'icon' => 'group', 'label' => 'Usuarios'],
                    ],
                ],
                [
                    'label' => 'Preferencias',
                    'items' => [
                        ['key' => 'settings', 'icon' => 'tune', 'label' => 'Ajustes'],
                    ],
                ],
                [
                    'label' => 'Bolsa Laboral',
                    'items' => [
                        ['key' => 'offers', 'icon' => 'list_alt', 'label' => 'Ofertas'],
                        ['key' => 'companies-manage', 'icon' => 'corporate_fare', 'label' => 'Empresas · Gestionar'],
                        ['key' => 'applications', 'icon' => 'person_search', 'label' => 'Postulaciones'],
                    ],
                ],
                [
                    'label' => '',
                    'items' => [
                        ['key' => 'maintainers', 'icon' => 'settings', 'label' => 'Mantenedores'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('partials.sidebar', ['sidebarConfig' => $sidebarConfig, 'config' => $config ?? []])

    <!-- Main Content Canvas -->
    <main class="flex-1 flex flex-col min-w-0">
        <!-- TopNavBar -->
        <header
            class="bg-surface border-b border-outline-variant sticky top-0 z-30 h-16 px-gutter flex justify-between items-center">
            <div class="flex items-center gap-4">
                <!-- Mobile Menu Trigger -->
                <button id="open-sidebar-btn"
                    class="md:hidden text-on-surface-variant hover:bg-surface-variant p-2 rounded-full">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <h2 id="header-page-title" class="text-headline-sm font-headline-sm font-bold text-primary">Panel de
                    Control</h2>
            </div>
            <div class="hidden">
                <div class="hidden">
                    <button onclick="switchTab('dashboard')"
                        class="text-label-md font-label-md text-on-surface-variant hover:text-primary transition-colors opacity-80 active:opacity-100">Resumen</button>
                    <button onclick="switchTab('maintainers')"
                        class="text-label-md font-label-md text-on-surface-variant hover:text-primary transition-colors opacity-80 active:opacity-100">Reportes</button>
                    <button onclick="switchTab('settings')"
                        class="text-label-md font-label-md text-on-surface-variant hover:text-primary transition-colors opacity-80 active:opacity-100">Analíticas</button>
                </div>
                <div class="hidden">
                    <button
                        class="text-on-surface-variant hover:bg-surface-variant p-2 rounded-full transition-colors relative">
                        <span class="material-symbols-outlined">notifications</span>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-error rounded-full"></span>
                    </button>
                    <button class="text-on-surface-variant hover:bg-surface-variant p-2 rounded-full transition-colors">
                        <span class="material-symbols-outlined">settings</span>
                    </button>
                    <div
                        class="w-8 h-8 rounded-full bg-primary-fixed-dim border border-outline-variant overflow-hidden ml-2 cursor-pointer">
                        <img alt="Admin User Profile" class="w-full h-full object-cover"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBTVr7jKLFatPQVPlP4q9clP3sh-Z5lJeEcAEsimRpXpr1pDyA9q-G6Hs_pD0LwE0sSHJMAb3JZ7Hfk8ulCPVvNfekaxqTBVD__GCq3Kju1aY8Ijx64r3pNLQB6KztHKqdxBKZaaI66fMocCn5NLc-1P6Ay1dx1fYdWE9b5Bbp7c_6WOi51gtRGTRcK273pfB3kDIZ1w7niqGen3WiN0h6ndNP8-b11_rxPQ0a8yhq113DSc_wAU3VD_q8wnbUC1g8DAs0kUn4xXgYH" />
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-sm">
                @include('partials.notifications-dropdown', ['id' => 'notifications-button', 'menuId' => 'notifications-menu'])
                
                <button type="button" onclick="switchTab('settings')"
                    class="w-10 h-10 flex items-center justify-center text-on-surface-variant hover:text-primary hover:bg-surface-container-high rounded-xl transition-colors"
                    aria-label="Ajustes" title="Ajustes del sistema"><span
                        class="material-symbols-outlined">settings</span></button>
                <div class="relative ml-1">
                    <button id="admin-account-button" type="button" onclick="toggleAdminHeaderMenu('account')"
                        class="flex items-center gap-3 p-1.5 pr-2.5 rounded-xl hover:bg-surface-container-high transition-colors text-left">
                        <span id="admin-header-avatar"
                            class="w-9 h-9 rounded-xl bg-primary text-on-primary border border-outline-variant/50 overflow-hidden flex items-center justify-center font-bold text-label-md shrink-0">@if ($adminAvatarUrl)<img
                                src="{{ $adminAvatarUrl }}" alt="Foto de {{ $adminName }}"
                            class="w-full h-full object-cover">@else<span>{{ $adminInitials ?: 'AD' }}</span>@endif</span>
                        <span class="hidden sm:block min-w-0 max-w-40"><span id="admin-header-name"
                                class="block text-label-md font-semibold text-on-surface truncate">{{ $adminName }}</span><span
                                class="block text-[11px] text-on-surface-variant">Administrador</span></span>
                        <span
                            class="material-symbols-outlined text-[18px] text-on-surface-variant hidden sm:block">expand_more</span>
                    </button>
                    <div id="admin-account-menu"
                        class="admin-header-menu hidden absolute right-0 top-14 w-72 bg-surface-container-lowest border border-outline-variant rounded-2xl shadow-xl overflow-hidden z-50">
                        <div class="p-4 bg-gradient-to-br from-primary to-primary/80 text-on-primary">
                            <div class="flex items-center gap-3"><span id="admin-menu-avatar"
                                    class="w-12 h-12 rounded-xl bg-white/15 border border-white/25 overflow-hidden flex items-center justify-center font-bold text-lg shrink-0">@if ($adminAvatarUrl)<img
                                        src="{{ $adminAvatarUrl }}" alt="Foto de perfil"
                                    class="w-full h-full object-cover">@else<span>{{ $adminInitials ?: 'AD' }}</span>@endif</span>
                                <div class="min-w-0">
                                    <p id="admin-menu-name" class="font-semibold truncate">{{ $adminName }}</p>
                                    <p id="admin-menu-email" class="text-[12px] text-white/75 truncate">
                                        {{ $adminEmail }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="p-2">
                            <button type="button" onclick="openAdminProfileModal()"
                                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-surface-container-high text-on-surface transition-colors text-left"><span
                                    class="material-symbols-outlined text-primary">account_circle</span><span><span
                                        class="block text-label-md font-semibold">Mi perfil</span><span
                                        class="block text-[11px] text-on-surface-variant">Datos, foto y
                                        seguridad</span></span></button>
                            <button type="button" onclick="switchTab('settings'); closeAdminHeaderMenus();"
                                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-surface-container-high text-on-surface transition-colors text-left"><span
                                    class="material-symbols-outlined text-primary">tune</span><span
                                    class="text-label-md font-semibold">Preferencias del sistema</span></button>
                        </div>
                        <div class="p-2 border-t border-outline-variant"><button type="button"
                                onclick="document.getElementById('logout-form').submit()"
                                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-red-50 text-red-600 transition-colors text-left"><span
                                    class="material-symbols-outlined">logout</span><span
                                    class="text-label-md font-semibold">Cerrar sesi&oacute;n</span></button></div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto p-4 md:p-lg">

            <!-- ================= PANEL 1: DASHBOARD OVERVIEW ================= -->
            <div id="panel-dashboard" class="tab-panel space-y-lg">
                <!-- Welcome Section -->
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div>
                        <h1 class="text-display-lg font-display-lg text-primary mb-2 hidden md:block">Panel de
                            Administración Global</h1>
                        <h1 class="text-headline-lg-mobile font-headline-lg-mobile text-primary mb-2 md:hidden">Panel de
                            Administración Global</h1>
                        <p class="text-body-md font-body-md text-on-surface-variant">Gestione empresas, usuarios y
                            ofertas laborales desde un solo lugar.</p>
                    </div>
                </div>

                <!-- Bento Grid: Metric Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md">
                    <!-- Card 1: Total Usuarios -->
                    <div
                        class="bg-surface rounded-xl p-lg border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-2 bg-primary-fixed rounded-lg text-primary">
                                <span class="material-symbols-outlined">group</span>
                            </div>
                            @if($userGrowth > 0)
                                <span class="text-label-sm font-label-sm text-secondary flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">trending_up</span>
                                    +{{ $userGrowth }}%
                                </span>
                            @endif
                        </div>
                        <p class="text-label-md font-label-md text-on-surface-variant mb-1">Total Usuarios</p>
                        <h3 class="text-headline-lg font-headline-lg text-on-background">
                            {{ number_format($totalUsers) }}
                        </h3>
                    </div>
                    <!-- Card 2: Empresas Pendientes (Warning) -->
                    <div
                        class="bg-surface rounded-xl p-lg border-2 border-tertiary-fixed-dim shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-16 h-16 bg-tertiary-fixed-dim/10 rounded-bl-full"></div>
                        <div class="flex justify-between items-start mb-4 relative z-10">
                            <div class="p-2 bg-tertiary-fixed rounded-lg text-on-tertiary-fixed">
                                <span class="material-symbols-outlined">domain_add</span>
                            </div>
                            @if($pendingCompanies > 0)
                                <span
                                    class="px-2 py-1 bg-error-container text-on-error-container text-label-sm font-label-sm rounded-full">Atención
                                    Requerida</span>
                            @endif
                        </div>
                        <p class="text-label-md font-label-md text-on-surface-variant mb-1 relative z-10">Empresas
                            Pendientes</p>
                        <h3 class="text-headline-lg font-headline-lg text-on-background relative z-10">
                            {{ $pendingCompanies }}
                        </h3>
                    </div>
                    <!-- Card 3: Ofertas Activas -->
                    <div class="bg-surface rounded-xl p-lg border border-outline-variant shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-2 bg-secondary-fixed rounded-lg text-on-secondary-fixed">
                                <span class="material-symbols-outlined">work_outline</span>
                            </div>
                        </div>
                        <p class="text-label-md font-label-md text-on-surface-variant mb-1">Ofertas Activas</p>
                        <h3 class="text-headline-lg font-headline-lg text-on-background">{{ $activeOffers }}</h3>
                    </div>
                    <!-- Card 4: Postulaciones Totales -->
                    <div class="bg-surface rounded-xl p-lg border border-outline-variant shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-2 bg-primary-container rounded-lg text-on-primary-container">
                                <span class="material-symbols-outlined">contact_page</span>
                            </div>
                            @if($appGrowth > 0)
                                <span class="text-label-sm font-label-sm text-secondary flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">trending_up</span>
                                    +{{ $appGrowth }}%
                                </span>
                            @endif
                        </div>
                        <p class="text-label-md font-label-md text-on-surface-variant mb-1">Postulaciones Totales</p>
                        <h3 class="text-headline-lg font-headline-lg text-on-background">
                            {{ number_format($totalApplications) }}
                        </h3>
                    </div>
                </div>

                <!-- Details Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-lg">
                    <!-- Main Data View (Spans 2 columns) -->
                    <div
                        class="lg:col-span-2 bg-surface rounded-xl border border-outline-variant shadow-sm overflow-hidden flex flex-col">
                        <div class="p-lg border-b border-outline-variant flex justify-between items-center">
                            <h3 class="text-headline-sm font-headline-sm text-on-background">Registros de Empresas
                                Recientes</h3>
                            <button type="button" onclick="switchTab('companies-manage')"
                                class="text-primary text-label-md font-label-md hover:underline">Ver Todas</button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-surface-container-low text-on-surface-variant">
                                        <th
                                            class="p-4 text-label-sm font-label-sm font-semibold border-b border-outline-variant">
                                            Nombre de Empresa</th>
                                        <th
                                            class="p-4 text-label-sm font-label-sm font-semibold border-b border-outline-variant">
                                            RUC</th>
                                        <th
                                            class="p-4 text-label-sm font-label-sm font-semibold border-b border-outline-variant">
                                            Fecha</th>
                                        <th
                                            class="p-4 text-label-sm font-label-sm font-semibold border-b border-outline-variant">
                                            Estado</th>
                                        <th
                                            class="p-4 text-label-sm font-label-sm font-semibold border-b border-outline-variant text-right">
                                            Acción</th>
                                    </tr>
                                </thead>
                                <tbody class="text-body-md font-body-md">
                                    @forelse($recentCompanies as $company)
                                        <tr
                                            class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors">
                                            <td class="p-4 flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 rounded bg-primary-fixed flex items-center justify-center text-primary font-bold text-label-md">
                                                    {{ $company->initial }}
                                                </div>
                                                <span class="font-medium text-on-background">{{ $company->name }}</span>
                                            </td>
                                            <td class="p-4 text-on-surface-variant">{{ $company->ruc }}</td>
                                            <td class="p-4 text-on-surface-variant">{{ $company->formatted_date }}</td>
                                            <td class="p-4">
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-label-sm font-label-sm {{ $company->status_class }}">
                                                    {{ $company->status_label }}
                                                </span>
                                            </td>
                                            <td class="p-4 text-right">
                                                <button type="button" onclick="switchTab('companies-manage')"
                                                    title="Gestionar empresa"
                                                    class="text-primary hover:bg-primary-fixed p-1 rounded transition-colors">
                                                    <span class="material-symbols-outlined text-[20px]">more_vert</span>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="p-4 text-center text-on-surface-variant">No hay empresas
                                                registradas</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Side Panel (Spans 1 column) -->
                    <div class="bg-surface rounded-xl border border-outline-variant shadow-sm flex flex-col h-full">
                        <div class="p-lg border-b border-outline-variant">
                            <h3 class="text-headline-sm font-headline-sm text-on-background">Historial de Actividad</h3>
                        </div>
                        <div class="p-lg flex-1 overflow-y-auto space-y-6">
                            @forelse($recentActivity as $activity)
                                <div class="flex gap-4">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-8 h-8 rounded-full {{ $activity['icon_bg'] }} flex items-center justify-center {{ $activity['icon_color'] }} shrink-0">
                                            <span
                                                class="material-symbols-outlined text-[16px]">{{ $activity['icon'] }}</span>
                                        </div>
                                        @if(!$loop->last)
                                            <div class="w-px h-full bg-outline-variant mt-2"></div>
                                        @else
                                            <div class="w-px h-full bg-transparent mt-2"></div>
                                        @endif
                                    </div>
                                    <div class="{{ $loop->last ? '' : 'pb-6' }}">
                                        <p class="text-body-sm font-body-sm text-on-background">{!! $activity['text'] !!}
                                        </p>
                                        <p class="text-label-sm font-label-sm text-on-surface-variant mt-1">
                                            {{ $activity['time'] }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-xl text-on-surface-variant">
                                    <span class="material-symbols-outlined text-4xl text-outline mb-2 block">timeline</span>
                                    <p class="font-semibold">Sin actividad reciente</p>
                                    <p class="text-body-sm text-on-surface-variant mt-1">Las novedades aparecerán aquí.
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= PANEL 2: GESTION DE USUARIOS ================= -->
            <div id="panel-users" class="tab-panel space-y-lg hidden">
                <!-- Header section with Action Buttons -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-headline-lg font-headline-lg text-primary mb-1">Gestión de Usuarios</h1>
                        <p class="text-body-md text-on-surface-variant">Agrega, edita, habilita/deshabilita e importa
                            usuarios del sistema.</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <button onclick="toggleImportModal()"
                            class="px-4 py-2.5 bg-secondary text-on-secondary rounded-xl text-label-md font-label-md hover:opacity-90 flex items-center gap-2 shadow-sm transition-all">
                            <span class="material-symbols-outlined text-[20px]">publish</span>
                            Importar desde Excel
                        </button>
                        <button onclick="openCreateUserModal()"
                            class="px-4 py-2.5 bg-student-accent text-on-primary rounded-xl text-label-md font-label-md hover:opacity-90 flex items-center gap-2 shadow-sm transition-all">
                            <span class="material-symbols-outlined text-[20px]">person_add</span>
                            Agregar Usuario
                        </button>
                    </div>
                </div>

                <!-- Users Data Table -->
                <div
                    class="bg-surface rounded-xl border border-outline-variant shadow-sm overflow-hidden flex flex-col">
                    <div class="p-md border-b border-outline-variant bg-surface-container-low">
                        <div class="flex flex-col gap-3">
                            <!-- Top bar: Title + Controles -->
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                                <h3 class="font-headline-sm text-[16px] text-on-surface font-bold shrink-0">Listado
                                    General de Usuarios</h3>

                                <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">

                                    <!-- Custom Select de Rol -->
                                    @php
                                        $roleOptions = [
                                            '' => ['label' => 'Todos los roles', 'icon' => 'group', 'color' => 'text-on-surface-variant'],
                                            '1' => ['label' => 'Administradores', 'icon' => 'admin_panel_settings', 'color' => 'text-blue-600'],
                                            '2' => ['label' => 'Docentes', 'icon' => 'school', 'color' => 'text-amber-600'],
                                            '3' => ['label' => 'Estudiantes', 'icon' => 'grade', 'color' => 'text-student-accent'],
                                            '4' => ['label' => 'Empresas', 'icon' => 'corporate_fare', 'color' => 'text-emerald-600'],
                                        ];
                                        $selectedRole = $roleOptions[$currentRolId] ?? $roleOptions[''];
                                    @endphp
                                    <div class="relative" id="role-select-wrapper">
                                        <button type="button" id="role-select-trigger" onclick="toggleRoleDropdown()"
                                            class="flex items-center gap-2 px-3.5 py-2 bg-surface border border-outline-variant rounded-xl hover:bg-surface-container-high focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all text-body-sm font-semibold min-w-[180px] shadow-sm group"
                                            aria-haspopup="listbox" aria-expanded="false">
                                            <span
                                                class="material-symbols-outlined text-[18px] {{ $selectedRole['color'] }}">{{ $selectedRole['icon'] }}</span>
                                            <span class="flex-1 text-left truncate text-on-surface"
                                                id="role-select-label">{{ $selectedRole['label'] }}</span>
                                            <span
                                                class="material-symbols-outlined text-[18px] text-outline-variant transition-transform duration-200 group-[.open]:rotate-180"
                                                id="role-select-arrow">expand_more</span>
                                        </button>

                                        <!-- Dropdown Options -->
                                        <div id="role-select-dropdown"
                                            class="absolute top-full left-0 mt-1.5 z-30 w-56 bg-surface-container-lowest border border-outline-variant/80 rounded-2xl shadow-xl overflow-hidden hidden"
                                            style="animation: none;" role="listbox">
                                            <div class="p-1.5 flex flex-col gap-0.5">
                                                @foreach ($roleOptions as $roleVal => $roleOpt)
                                                    <button type="button" onclick="selectRole('{{ $roleVal }}')"
                                                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-body-sm font-medium hover:bg-surface-container-high transition-colors text-left w-full group/opt {{ $currentRolId === strval($roleVal) ? 'bg-primary/5 text-primary font-semibold' : 'text-on-surface' }}"
                                                        role="option"
                                                        aria-selected="{{ $currentRolId === strval($roleVal) ? 'true' : 'false' }}">
                                                        <span
                                                            class="material-symbols-outlined text-[18px] {{ $currentRolId === strval($roleVal) ? 'text-primary' : $roleOpt['color'] }}">{{ $roleOpt['icon'] }}</span>
                                                        <span class="flex-1">{{ $roleOpt['label'] }}</span>
                                                        @if ($currentRolId === strval($roleVal))
                                                            <span
                                                                class="material-symbols-outlined text-[16px] text-primary">check</span>
                                                        @endif
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Búsqueda simple -->
                                    <div class="relative flex-1 sm:flex-none">
                                        <span
                                            class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px] pointer-events-none">search</span>
                                        <input id="search-users-input"
                                            class="w-full sm:w-52 pl-9 pr-8 py-2 bg-surface border border-outline-variant rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-body-sm transition-all shadow-sm"
                                            placeholder="Buscar..." type="text" value="{{ $currentSearch }}"
                                            onkeydown="if(event.key === 'Enter') filterUsers()">
                                        @if($currentSearch)
                                            <button onclick="clearSearch()"
                                                class="absolute right-2.5 top-1/2 -translate-y-1/2 text-outline hover:text-on-surface transition-colors flex items-center justify-center p-0.5 rounded-full hover:bg-surface-container-high"
                                                title="Limpiar">
                                                <span class="material-symbols-outlined text-[16px]">close</span>
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Botón Buscar -->
                                    <button onclick="filterUsers()"
                                        class="px-4 py-2 bg-primary text-on-primary rounded-xl text-label-sm font-semibold hover:opacity-90 transition-opacity shrink-0 flex items-center gap-1.5 shadow-sm shadow-primary/10">
                                        <span class="material-symbols-outlined text-[18px]">search</span>
                                        <span class="hidden sm:inline">Buscar</span>
                                    </button>

                                    <!-- Botón Búsqueda Avanzada -->
                                    <button type="button" id="advanced-search-toggle" onclick="toggleAdvancedSearch()"
                                        class="px-3 py-2 border rounded-xl text-label-sm font-semibold transition-all shrink-0 flex items-center gap-1.5 {{ ($currentStatus) ? 'bg-primary/5 border-primary text-primary' : 'border-outline-variant text-on-surface-variant hover:bg-surface-container-high' }}"
                                        title="Busqueda avanzada">
                                        <span class="material-symbols-outlined text-[18px]">tune</span>
                                        @if($currentStatus)
                                            <span class="w-2 h-2 rounded-full bg-primary shrink-0"></span>
                                        @endif
                                    </button>
                                </div>
                            </div>

                            <!-- Panel de Búsqueda Avanzada (colapsable) -->
                            <div id="advanced-search-panel"
                                class="{{ ($currentStatus) ? '' : 'hidden' }} overflow-hidden transition-all duration-300"
                                aria-hidden="{{ ($currentStatus) ? 'false' : 'true' }}">
                                <div
                                    class="bg-surface border border-outline-variant/60 rounded-2xl p-4 flex flex-col sm:flex-row flex-wrap gap-4 mt-1">
                                    <div class="flex-1 min-w-[180px] space-y-1.5">
                                        <label
                                            class="text-label-sm font-semibold text-on-surface-variant flex items-center gap-1.5">
                                            <span class="material-symbols-outlined text-[16px]">toggle_on</span>
                                            Estado del usuario
                                        </label>
                                        <div class="flex gap-2 flex-wrap">
                                            <button type="button" onclick="setStatus('')"
                                                class="px-3.5 py-1.5 rounded-full border text-xs font-semibold transition-all {{ ($currentStatus === '') ? 'bg-on-surface text-surface border-on-surface' : 'bg-surface border-outline-variant text-on-surface-variant hover:bg-surface-container-high' }}">
                                                Todos
                                            </button>
                                            <button type="button" onclick="setStatus('active')"
                                                class="px-3.5 py-1.5 rounded-full border text-xs font-semibold transition-all flex items-center gap-1.5 {{ ($currentStatus === 'active') ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-surface border-outline-variant text-on-surface-variant hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-300' }}">
                                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                                Activos
                                            </button>
                                            <button type="button" onclick="setStatus('inactive')"
                                                class="px-3.5 py-1.5 rounded-full border text-xs font-semibold transition-all flex items-center gap-1.5 {{ ($currentStatus === 'inactive') ? 'bg-red-600 text-white border-red-600' : 'bg-surface border-outline-variant text-on-surface-variant hover:bg-red-50 hover:text-red-700 hover:border-red-300' }}">
                                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                                Inactivos
                                            </button>
                                        </div>
                                    </div>

                                    <div class="flex items-end gap-2 ml-auto">
                                        @if($currentStatus || $currentRolId || $currentSearch)
                                            <button onclick="clearAllFilters()"
                                                class="px-3.5 py-2 border border-outline-variant text-on-surface-variant hover:bg-surface-container-high rounded-xl text-label-sm font-semibold transition-colors flex items-center gap-1.5">
                                                <span class="material-symbols-outlined text-[16px]">filter_alt_off</span>
                                                Limpiar todos
                                            </button>
                                        @endif
                                        <button onclick="filterUsers()"
                                            class="px-4 py-2 bg-primary text-on-primary rounded-xl text-label-sm font-semibold hover:opacity-90 transition-opacity flex items-center gap-1.5">
                                            <span class="material-symbols-outlined text-[16px]">search</span>
                                            Aplicar
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Tags de filtros activos -->
                            @if($currentRolId || $currentSearch || $currentStatus)
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <span class="text-label-sm text-on-surface-variant font-medium">Filtros activos:</span>
                                    @if($currentRolId)
                                        <span
                                            class="inline-flex items-center gap-1 pl-2 pr-1 py-0.5 rounded-full bg-primary/8 border border-primary/20 text-primary text-[11px] font-semibold">
                                            {{ $roleOptions[$currentRolId]['label'] ?? 'Rol' }}
                                            <button onclick="filterByRole('')"
                                                class="hover:bg-primary/10 rounded-full p-0.5 transition-colors">
                                                <span class="material-symbols-outlined text-[13px]">close</span>
                                            </button>
                                        </span>
                                    @endif
                                    @if($currentSearch)
                                        <span
                                            class="inline-flex items-center gap-1 pl-2 pr-1 py-0.5 rounded-full bg-secondary/8 border border-secondary/20 text-secondary text-[11px] font-semibold">
                                            "{{ $currentSearch }}"
                                            <button onclick="clearSearch()"
                                                class="hover:bg-secondary/10 rounded-full p-0.5 transition-colors">
                                                <span class="material-symbols-outlined text-[13px]">close</span>
                                            </button>
                                        </span>
                                    @endif
                                    @if($currentStatus)
                                        <span
                                            class="inline-flex items-center gap-1 pl-2 pr-1 py-0.5 rounded-full text-[11px] font-semibold {{ $currentStatus === 'active' ? 'bg-emerald-50 border border-emerald-200 text-emerald-700' : 'bg-red-50 border border-red-200 text-red-700' }}">
                                            {{ $currentStatus === 'active' ? 'Activos' : 'Inactivos' }}
                                            <button onclick="setStatus('')"
                                                class="hover:bg-black/5 rounded-full p-0.5 transition-colors">
                                                <span class="material-symbols-outlined text-[13px]">close</span>
                                            </button>
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- Barra de acciones masivas flotante (Glassmorphism) -->
                    <div id="bulk-actions-bar"
                        class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 px-6 py-4 bg-surface-container-lowest/90 backdrop-blur-md border border-outline-variant/60 rounded-2xl shadow-2xl hidden flex items-center gap-4 transition-all duration-300 transform translate-y-10 opacity-0 scale-95 max-w-md w-[calc(100%-2rem)]">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-error animate-pulse shrink-0"></span>
                            <span class="text-label-md font-bold text-on-surface shrink-0" id="selected-count-label">0
                                seleccionados</span>
                        </div>
                        <div class="h-5 w-px bg-outline-variant/60 shrink-0"></div>
                        <div class="flex items-center gap-2 w-full justify-end">
                            <button onclick="clearUserSelection()"
                                class="px-3.5 py-2 border border-outline-variant hover:bg-surface-container rounded-xl text-label-sm font-semibold transition-colors">
                                Cancelar
                            </button>
                            <button onclick="deleteSelectedUsers()"
                                class="px-4 py-2 bg-error text-on-error rounded-xl text-label-sm font-semibold hover:opacity-90 shadow-md shadow-error/10 transition-all flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                Eliminar
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr
                                    class="bg-surface-container-low text-on-surface-variant border-b border-outline-variant">
                                    <th class="p-4 w-10">
                                        <input type="checkbox" id="select-all-users"
                                            onchange="toggleSelectAllUsers(this)"
                                            class="w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary/20 bg-surface-container-lowest">
                                    </th>
                                    <th class="p-4 text-label-sm font-label-sm font-semibold">Usuario</th>
                                    <th class="p-4 text-label-sm font-label-sm font-semibold">Documento de Identidad
                                    </th>
                                    <th class="p-4 text-label-sm font-label-sm font-semibold">Rol</th>
                                    <th class="p-4 text-label-sm font-label-sm font-semibold">Estado</th>
                                    <th class="p-4 text-label-sm font-label-sm font-semibold text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-body-md font-body-md" id="users-table-body">
                                @forelse ($users as $user)
                                    @php
                                        $displayName = $user->person->names ?? ($user->company->name ?? 'Usuario');
                                        $docType = $user->person->document_type ?? 'RUC';
                                        $docNum = $user->person->document_number ?? ($user->company->ruc ?? '-');

                                        // Badge role classes
                                        $roleBadge = 'bg-surface-container text-on-surface-variant';
                                        $roleName = 'ESTUDIANTE';
                                        if ($user->rol_id == 1) {
                                            $roleBadge = 'bg-primary-fixed text-primary';
                                            $roleName = 'ADMINISTRADOR';
                                        } elseif ($user->rol_id == 2) {
                                            $roleBadge = 'bg-tertiary-fixed text-on-tertiary-fixed-variant';
                                            $roleName = 'DOCENTE';
                                        } elseif ($user->rol_id == 3) {
                                            $roleBadge = 'bg-student-accent-light text-student-accent';
                                            $roleName = 'ESTUDIANTE';
                                        } elseif ($user->rol_id == 4) {
                                            $roleBadge = 'bg-secondary-fixed/50 text-on-secondary-container';
                                            $roleName = 'EMPRESA';
                                        }
                                    @endphp
                                    <tr id="user-row-{{ $user->id }}"
                                        class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors {{ $user->id == auth()->id() ? 'bg-primary-fixed/10' : '' }}">
                                        <td class="p-4 w-10">
                                            @if ($user->id == auth()->id())
                                                <span class="w-4 h-4 flex items-center justify-center"
                                                    title="Este es tu usuario activo"></span>
                                            @else
                                                <input type="checkbox"
                                                    class="user-checkbox w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary/20 bg-surface-container-lowest"
                                                    value="{{ $user->id }}" onchange="updateSelectedCount()">
                                            @endif
                                        </td>
                                        <td class="p-4 flex items-center gap-3">
                                            <div
                                                class="w-9 h-9 rounded-full bg-surface-container flex items-center justify-center text-primary font-bold text-label-md">
                                                {{ strtoupper(substr($displayName, 0, 1)) }}
                                            </div>
                                            <div>
                                                <span
                                                    class="font-medium text-on-background block leading-tight">{{ $displayName }}</span>
                                                <span
                                                    class="text-body-sm text-on-surface-variant text-[13px]">{{ $user->email }}</span>
                                            </div>
                                            @if ($user->id == auth()->id())
                                                <span
                                                    class="ml-1 px-2 py-0.5 rounded-full bg-primary/10 text-primary text-[11px] font-bold tracking-wide shrink-0">Tú</span>
                                            @endif
                                        </td>
                                        <td class="p-4 text-on-surface-variant">
                                            <span class="font-semibold text-body-sm">{{ $docType }}:</span> {{ $docNum }}
                                        </td>
                                        <td class="p-4">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-label-sm font-label-sm font-semibold {{ $roleBadge }}">
                                                {{ $roleName }}
                                            </span>
                                        </td>
                                        <td class="p-4">
                                            <!-- Switch enable/disable -->
                                            <label class="switch">
                                                <input type="checkbox" {{ $user->is_active ? 'checked' : '' }}
                                                    onchange="toggleUserStatus({{ $user->id }}, '{{ addslashes($displayName) }}', this)">
                                                <span class="slider"></span>
                                            </label>
                                        </td>
                                        <td class="p-4 text-right space-x-1">
                                            <button
                                                onclick="editUser({{ $user->id }}, '{{ addslashes($displayName) }}', '{{ $user->email }}', '{{ $user->person->phone ?? ($user->company->phone ?? '') }}', {{ $user->rol_id }}, '{{ $docType }}', '{{ $docNum }}')"
                                                class="p-1.5 text-on-surface-variant hover:bg-surface-container rounded-lg transition-colors"
                                                title="Editar Usuario">
                                                <span class="material-symbols-outlined text-[20px]">edit</span>
                                            </button>
                                            <button
                                                onclick="openChangePasswordModal({{ $user->id }}, '{{ addslashes($displayName) }}')"
                                                class="p-1.5 text-on-surface-variant hover:bg-surface-container rounded-lg transition-colors"
                                                title="Cambiar Contraseña">
                                                <span class="material-symbols-outlined text-[20px]">lock_reset</span>
                                            </button>
                                            @if ($user->id != auth()->id())
                                                <button
                                                    onclick="deleteUserRow({{ $user->id }}, '{{ addslashes($displayName) }}')"
                                                    class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                    title="Eliminar Usuario">
                                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                                </button>
                                            @else
                                                <span class="p-1.5 text-outline-variant inline-flex"
                                                    title="No puedes eliminar tu propio usuario">
                                                    <span class="material-symbols-outlined text-[20px]">shield_person</span>
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-10 text-center">
                                            <div class="flex flex-col items-center justify-center space-y-4">
                                                <div
                                                    class="w-16 h-16 rounded-2xl bg-surface-container flex items-center justify-center text-outline shadow-inner">
                                                    <span class="material-symbols-outlined text-4xl">group_off</span>
                                                </div>
                                                <div class="space-y-1">
                                                    <p class="font-bold text-on-surface text-lg">No se encontraron usuarios
                                                    </p>
                                                    <p class="text-body-sm text-on-surface-variant max-w-sm mx-auto">No hay
                                                        registros que coincidan con los filtros de rol o búsqueda aplicados
                                                        actualmente.</p>
                                                </div>
                                                @if($currentSearch || $currentRolId)
                                                    <button onclick="clearAllFilters()"
                                                        class="mt-2 px-4 py-2 border border-outline-variant bg-surface hover:bg-surface-container transition-colors rounded-xl text-label-sm font-semibold flex items-center gap-1.5 mx-auto">
                                                        <span
                                                            class="material-symbols-outlined text-[18px]">filter_alt_off</span>
                                                        Limpiar Filtros
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- Paginación Premium -->
                    @if ($users instanceof \Illuminate\Pagination\LengthAwarePaginator && $users->hasPages())
                        @php
                            $currentPage = $users->currentPage();
                            $lastPage = $users->lastPage();
                            $sidePages = 2; // Cantidad de páginas a mostrar a los lados de la actual
                        @endphp
                        <div
                            class="px-md py-3.5 border-t border-outline-variant bg-surface-container-low flex flex-col sm:flex-row items-center justify-between gap-3">
                            <span class="text-body-sm text-on-surface-variant font-medium">
                                Mostrando <span class="text-on-surface font-semibold">{{ $users->firstItem() }}</span> -
                                <span class="text-on-surface font-semibold">{{ $users->lastItem() }}</span> de <span
                                    class="text-on-surface font-semibold">{{ $users->total() }}</span> usuarios
                            </span>
                            <nav class="flex items-center gap-1.5 select-none" aria-label="Navegación de páginas">
                                {{-- Botón Anterior --}}
                                @if ($users->onFirstPage())
                                    <span
                                        class="w-9 h-9 rounded-xl text-outline-variant/60 cursor-not-allowed flex items-center justify-center border border-outline-variant/30"
                                        aria-disabled="true">
                                        <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                                    </span>
                                @else
                                    <a href="{{ $users->previousPageUrl() }}"
                                        class="w-9 h-9 rounded-xl text-on-surface-variant hover:bg-surface-container-high hover:text-primary transition-colors flex items-center justify-center border border-outline-variant/60 shadow-sm"
                                        aria-label="Página anterior">
                                        <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                                    </a>
                                @endif

                                {{-- Páginas --}}
                                @if ($lastPage <= 7)
                                    {{-- Si hay 7 páginas o menos, mostrarlas todas --}}
                                    @foreach (range(1, $lastPage) as $page)
                                        @if ($page == $currentPage)
                                            <span
                                                class="w-9 h-9 rounded-xl bg-primary text-on-primary flex items-center justify-center text-label-sm font-semibold shadow-md shadow-primary/20 ring-2 ring-primary/10">{{ $page }}</span>
                                        @else
                                            <a href="{{ $users->url($page) }}"
                                                class="w-9 h-9 rounded-xl text-on-surface-variant hover:bg-surface-container-high hover:text-primary border border-outline-variant/60 hover:border-primary/30 flex items-center justify-center text-label-sm font-semibold transition-colors shadow-sm bg-surface">{{ $page }}</a>
                                        @endif
                                    @endforeach
                                @else
                                    {{-- Página 1 --}}
                                    @if (1 == $currentPage)
                                        <span
                                            class="w-9 h-9 rounded-xl bg-primary text-on-primary flex items-center justify-center text-label-sm font-semibold shadow-md shadow-primary/20 ring-2 ring-primary/10">1</span>
                                    @else
                                        <a href="{{ $users->url(1) }}"
                                            class="w-9 h-9 rounded-xl text-on-surface-variant hover:bg-surface-container-high hover:text-primary border border-outline-variant/60 hover:border-primary/30 flex items-center justify-center text-label-sm font-semibold transition-colors shadow-sm bg-surface">1</a>
                                    @endif

                                    {{-- Elipsis inicial --}}
                                    @if ($currentPage - $sidePages > 2)
                                        <span class="w-9 h-9 flex items-center justify-center text-outline text-label-sm">...</span>
                                    @endif

                                    {{-- Rango medio --}}
                                    @php
                                        $startPage = max(2, $currentPage - $sidePages);
                                        $endPage = min($lastPage - 1, $currentPage + $sidePages);
                                    @endphp
                                    @foreach (range($startPage, $endPage) as $page)
                                        @if ($page == $currentPage)
                                            <span
                                                class="w-9 h-9 rounded-xl bg-primary text-on-primary flex items-center justify-center text-label-sm font-semibold shadow-md shadow-primary/20 ring-2 ring-primary/10">{{ $page }}</span>
                                        @else
                                            <a href="{{ $users->url($page) }}"
                                                class="w-9 h-9 rounded-xl text-on-surface-variant hover:bg-surface-container-high hover:text-primary border border-outline-variant/60 hover:border-primary/30 flex items-center justify-center text-label-sm font-semibold transition-colors shadow-sm bg-surface">{{ $page }}</a>
                                        @endif
                                    @endforeach

                                    {{-- Elipsis final --}}
                                    @if ($currentPage + $sidePages < $lastPage - 1)
                                        <span class="w-9 h-9 flex items-center justify-center text-outline text-label-sm">...</span>
                                    @endif

                                    {{-- Última página --}}
                                    @if ($lastPage == $currentPage)
                                        <span
                                            class="w-9 h-9 rounded-xl bg-primary text-on-primary flex items-center justify-center text-label-sm font-semibold shadow-md shadow-primary/20 ring-2 ring-primary/10">{{ $lastPage }}</span>
                                    @else
                                        <a href="{{ $users->url($lastPage) }}"
                                            class="w-9 h-9 rounded-xl text-on-surface-variant hover:bg-surface-container-high hover:text-primary border border-outline-variant/60 hover:border-primary/30 flex items-center justify-center text-label-sm font-semibold transition-colors shadow-sm bg-surface">{{ $lastPage }}</a>
                                    @endif
                                @endif

                                {{-- Botón Siguiente --}}
                                @if ($users->hasMorePages())
                                    <a href="{{ $users->nextPageUrl() }}"
                                        class="w-9 h-9 rounded-xl text-on-surface-variant hover:bg-surface-container-high hover:text-primary transition-colors flex items-center justify-center border border-outline-variant/60 shadow-sm"
                                        aria-label="Siguiente página">
                                        <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                                    </a>
                                @else
                                    <span
                                        class="w-9 h-9 rounded-xl text-outline-variant/60 cursor-not-allowed flex items-center justify-center border border-outline-variant/30"
                                        aria-disabled="true">
                                        <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                                    </span>
                                @endif
                            </nav>
                        </div>
                    @endif
                </div>
            </div>

            <!-- ================= PANEL: CONFIGURACIÓN (AJUSTES) ================= -->
            <div id="panel-settings" class="tab-panel space-y-lg hidden">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-headline-lg font-headline-lg text-primary mb-1">Ajustes del Sistema</h1>
                        <p class="text-body-md text-on-surface-variant">Personaliza la identidad visual y restricciones
                            generales del portal.</p>
                    </div>
                </div>

                <!-- Form Settings -->
                <form id="settings-form" onsubmit="handleSettingsSubmit(event)"
                    class="bg-surface rounded-xl border border-outline-variant shadow-sm overflow-hidden flex flex-col p-lg space-y-lg"
                    enctype="multipart/form-data">
                    @csrf
                    <!-- Sección 1: Información General -->
                    <div class="space-y-lg">
                        <h3
                            class="font-headline-sm text-headline-sm text-on-surface border-b border-outline-variant pb-2">
                            Información General</h3>

                        <!-- Nombre de la institución -->
                        <div class="space-y-xs">
                            <label class="font-label-sm text-label-sm text-on-surface-variant block"
                                for="set-app-name">Nombre de la institución</label>
                            <input
                                class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm"
                                id="set-app-name" name="application_name"
                                value="{{ $config['application_name'] ?? 'Bolsa Laboral' }}" type="text" required />
                        </div>

                        <!-- Extensiones permitidas -->
                        @php
                            $defaultExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'rar', 'jpg', 'jpeg', 'png', 'gif', 'mp3', 'mp4', 'avi', 'mkv'];
                            $allowedExtsJson = json_decode($config['extensions_allowed_to_upload'] ?? '[]', true);

                            $allowedExtsMap = [];
                            if (empty($allowedExtsJson)) {
                                $allowedExtsMap = array_fill_keys($defaultExtensions, true);
                            } else {
                                foreach ($allowedExtsJson as $item) {
                                    $ext = strtolower($item['extension']);
                                    $permitted = isset($item['permitted']) ? (bool) $item['permitted'] : true;
                                    $allowedExtsMap[$ext] = $permitted;
                                }
                            }
                        @endphp
                        <div class="space-y-xs">
                            <div class="flex items-center justify-between">
                                <label class="font-label-sm text-label-sm text-on-surface-variant block">Extensiones
                                    permitidas</label>
                                <div class="flex items-center gap-sm">
                                    <button type="button" onclick="selectAllExtensions(event)"
                                        class="text-xs text-primary hover:underline font-semibold">Seleccionar
                                        todas</button>
                                    <span class="text-xs text-on-surface-variant">|</span>
                                    <button type="button" onclick="deselectAllExtensions(event)"
                                        class="text-xs text-primary hover:underline font-semibold">Deseleccionar
                                        todas</button>
                                </div>
                            </div>
                            <div id="extensions-checkbox-container"
                                class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-md p-md border border-outline-variant rounded-xl bg-background">
                                @foreach ($defaultExtensions as $ext)
                                    @php
                                        $isPermitted = isset($allowedExtsMap[$ext]) ? $allowedExtsMap[$ext] : true;
                                    @endphp
                                    <label
                                        class="flex items-center gap-2 px-3 py-2 border border-outline-variant rounded-lg bg-surface hover:bg-surface-container-high cursor-pointer transition-colors select-none">
                                        <input type="checkbox" name="extensions[]" value="{{ $ext }}" {{ $isPermitted ? 'checked' : '' }}
                                            class="w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary/20 bg-surface-container-lowest">
                                        <span class="text-body-sm font-semibold uppercase text-on-surface">{{ $ext }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Tamaño máximo -->
                        <div class="space-y-xs">
                            <label class="font-label-sm text-label-sm text-on-surface-variant block"
                                for="set-max-size">Tamaño máximo de archivos a subir (MB)</label>
                            <input
                                class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm"
                                id="set-max-size" name="maximum_file_size_to_upload"
                                value="{{ $config['maximum_file_size_to_upload'] ?? '2' }}" type="number" min="1"
                                required />
                        </div>

                        <!-- Identidad visual -->
                        @php
                            $currentPrimary = $config['primary_color'] ?? '#ff9f43';
                            $currentSecondary = $config['secondary_color'] ?? '#006b60';
                            $currentAccent = $config['accent_color'] ?? '#ff9f43';
                            $themeMode = $config['theme_mode'] ?? 'light';
                            $interfaceDensity = $config['interface_density'] ?? 'comfortable';
                            $sidebarStyle = $config['sidebar_style'] ?? 'expanded';
                            $themePresets = [
                                ['name' => 'Institucional', 'primary' => '#ff9f43', 'secondary' => '#006b60', 'accent' => '#f97316'],
                                ['name' => 'Academico', 'primary' => '#2563eb', 'secondary' => '#0f766e', 'accent' => '#f59e0b'],
                                ['name' => 'Bosque', 'primary' => '#15803d', 'secondary' => '#0f766e', 'accent' => '#84cc16'],
                                ['name' => 'Pacifico', 'primary' => '#0284c7', 'secondary' => '#0d9488', 'accent' => '#fb923c'],
                                ['name' => 'Grafito', 'primary' => '#374151', 'secondary' => '#64748b', 'accent' => '#f97316'],
                                ['name' => 'Rojo', 'primary' => '#dc2626', 'secondary' => '#475569', 'accent' => '#f59e0b'],
                            ];
                        @endphp
                        <div class="grid grid-cols-1 xl:grid-cols-[1.2fr_0.8fr] gap-lg">
                            <div class="space-y-md rounded-2xl border border-outline-variant bg-surface-container-lowest p-md">
                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-sm">
                                    <div>
                                        <h4 class="font-headline-sm text-headline-sm text-on-surface">Identidad visual</h4>
                                        <p class="text-body-sm text-on-surface-variant mt-1">Ajusta los colores base que usa el panel y las vistas del sistema.</p>
                                    </div>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-primary-container px-3 py-1 text-xs font-semibold text-primary">
                                        <span class="material-symbols-outlined text-[16px]">palette</span>
                                        Vista en vivo
                                    </span>
                                </div>

                                <input type="hidden" id="set-primary-color" name="primary_color" value="{{ $currentPrimary }}">
                                <input type="hidden" id="set-secondary-color" name="secondary_color" value="{{ $currentSecondary }}">
                                <input type="hidden" id="set-accent-color" name="accent_color" value="{{ $currentAccent }}">

                                <div class="space-y-xs">
                                    <label class="font-label-sm text-label-sm text-on-surface-variant block">Temas rapidos</label>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-sm" id="theme-preset-palette">
                                        @foreach ($themePresets as $preset)
                                            @php
                                                $presetSelected = strtolower($currentPrimary) === strtolower($preset['primary'])
                                                    && strtolower($currentSecondary) === strtolower($preset['secondary'])
                                                    && strtolower($currentAccent) === strtolower($preset['accent']);
                                            @endphp
                                            <button type="button"
                                                onclick="selectThemePreset('{{ $preset['primary'] }}', '{{ $preset['secondary'] }}', '{{ $preset['accent'] }}', this)"
                                                class="theme-preset-btn flex items-center justify-between gap-3 rounded-xl border {{ $presetSelected ? 'border-primary bg-primary-container' : 'border-outline-variant bg-surface' }} px-3 py-2 text-left hover:border-primary transition-colors">
                                                <span class="text-body-sm font-semibold text-on-surface">{{ $preset['name'] }}</span>
                                                <span class="flex -space-x-1">
                                                    <span class="w-5 h-5 rounded-full border border-white shadow-sm" style="background-color: {{ $preset['primary'] }}"></span>
                                                    <span class="w-5 h-5 rounded-full border border-white shadow-sm" style="background-color: {{ $preset['secondary'] }}"></span>
                                                    <span class="w-5 h-5 rounded-full border border-white shadow-sm" style="background-color: {{ $preset['accent'] }}"></span>
                                                </span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-md">
                                    <label class="rounded-xl border border-outline-variant bg-surface p-sm space-y-2">
                                        <span class="font-label-sm text-label-sm text-on-surface-variant">Principal</span>
                                        <div class="flex items-center gap-sm">
                                            <input type="color" id="set-primary-color-picker" value="{{ $currentPrimary }}"
                                                oninput="syncCustomColor(this, 'primary')"
                                                class="w-12 h-10 rounded-lg border border-outline-variant bg-transparent cursor-pointer">
                                            <span id="set-primary-color-value" class="font-mono text-body-sm text-on-surface">{{ strtoupper($currentPrimary) }}</span>
                                        </div>
                                    </label>
                                    <label class="rounded-xl border border-outline-variant bg-surface p-sm space-y-2">
                                        <span class="font-label-sm text-label-sm text-on-surface-variant">Secundario</span>
                                        <div class="flex items-center gap-sm">
                                            <input type="color" id="set-secondary-color-picker" value="{{ $currentSecondary }}"
                                                oninput="syncCustomColor(this, 'secondary')"
                                                class="w-12 h-10 rounded-lg border border-outline-variant bg-transparent cursor-pointer">
                                            <span id="set-secondary-color-value" class="font-mono text-body-sm text-on-surface">{{ strtoupper($currentSecondary) }}</span>
                                        </div>
                                    </label>
                                    <label class="rounded-xl border border-outline-variant bg-surface p-sm space-y-2">
                                        <span class="font-label-sm text-label-sm text-on-surface-variant">Acento</span>
                                        <div class="flex items-center gap-sm">
                                            <input type="color" id="set-accent-color-picker" value="{{ $currentAccent }}"
                                                oninput="syncCustomColor(this, 'accent')"
                                                class="w-12 h-10 rounded-lg border border-outline-variant bg-transparent cursor-pointer">
                                            <span id="set-accent-color-value" class="font-mono text-body-sm text-on-surface">{{ strtoupper($currentAccent) }}</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div id="settings-theme-preview" class="rounded-2xl border border-outline-variant bg-surface-container-lowest p-md space-y-md"
                                style="--preview-primary: {{ $currentPrimary }}; --preview-secondary: {{ $currentSecondary }}; --preview-accent: {{ $currentAccent }};">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-headline-sm text-headline-sm text-on-surface">Previsualizacion</h4>
                                        <p class="text-body-sm text-on-surface-variant mt-1">Asi se combinan los colores.</p>
                                    </div>
                                    <span class="w-10 h-10 rounded-xl flex items-center justify-center text-white shadow-sm" style="background-color: var(--preview-primary);">
                                        <span class="material-symbols-outlined text-[20px]">school</span>
                                    </span>
                                </div>
                                <div class="rounded-xl border border-outline-variant bg-background p-md space-y-sm">
                                    <div class="h-2 w-24 rounded-full" style="background-color: var(--preview-primary);"></div>
                                    <div class="h-2 w-40 rounded-full bg-outline-variant"></div>
                                    <div class="flex items-center gap-sm pt-2">
                                        <span class="px-3 py-1 rounded-full text-white text-xs font-semibold" style="background-color: var(--preview-secondary);">Activo</span>
                                        <span class="px-3 py-1 rounded-full text-white text-xs font-semibold" style="background-color: var(--preview-accent);">Nuevo</span>
                                    </div>
                                    <button type="button" class="w-full rounded-xl py-2 text-label-md font-semibold text-white shadow-sm" style="background-color: var(--preview-primary);">
                                        Boton principal
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-md rounded-2xl border border-outline-variant bg-surface-container-lowest p-md">
                            <label class="space-y-xs">
                                <span class="font-label-sm text-label-sm text-on-surface-variant block">Modo visual</span>
                                <select name="theme_mode"
                                    class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm">
                                    <option value="light" {{ $themeMode === 'light' ? 'selected' : '' }}>Claro</option>
                                    <option value="system" {{ $themeMode === 'system' ? 'selected' : '' }}>Segun el sistema</option>
                                </select>
                            </label>
                            <label class="space-y-xs">
                                <span class="font-label-sm text-label-sm text-on-surface-variant block">Densidad</span>
                                <select name="interface_density"
                                    class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm">
                                    <option value="comfortable" {{ $interfaceDensity === 'comfortable' ? 'selected' : '' }}>Comoda</option>
                                    <option value="compact" {{ $interfaceDensity === 'compact' ? 'selected' : '' }}>Compacta</option>
                                </select>
                            </label>
                            <label class="space-y-xs">
                                <span class="font-label-sm text-label-sm text-on-surface-variant block">Sidebar</span>
                                <select name="sidebar_style"
                                    class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm">
                                    <option value="expanded" {{ $sidebarStyle === 'expanded' ? 'selected' : '' }}>Expandido</option>
                                    <option value="compact" {{ $sidebarStyle === 'compact' ? 'selected' : '' }}>Compacto</option>
                                </select>
                            </label>
                        </div>

                        <div class="flex justify-end pt-md">
                            <button type="submit"
                                class="px-6 py-2.5 bg-primary text-on-primary font-label-md text-label-md rounded-xl hover:opacity-95 shadow-sm transition-all font-semibold">
                                Actualizar
                            </button>
                        </div>
                    </div>

                    <!-- Sección 2: Iconos e Imágenes -->
                    <div class="space-y-lg pt-lg border-t border-outline-variant">
                        <div>
                            <h3 class="font-headline-sm text-headline-sm text-on-surface">Iconos e Imágenes</h3>
                            <p class="text-body-sm text-on-surface-variant mt-1">Sube los archivos en los formatos y medidas recomendadas para mejor calidad visual.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-lg">
                            <!-- Logo Upload Card -->
                            <div class="flex flex-col items-center p-md border border-outline-variant rounded-2xl bg-surface-container-low text-center space-y-sm">
                                <div class="w-full flex items-center justify-between">
                                    <span class="font-bold text-body-sm text-on-surface">Logo</span>
                                    @if(!empty($config['logo']))
                                    <button type="button" onclick="deleteSettingsImage('logo', 'preview-logo')"
                                        title="Eliminar logo actual"
                                        class="p-1 rounded-lg hover:bg-error-container text-error transition-colors flex items-center gap-1 text-[11px] font-semibold">
                                        <span class="material-symbols-outlined text-[16px]">delete</span> Eliminar
                                    </button>
                                    @endif
                                </div>
                                <div class="w-32 h-32 rounded-xl border border-outline-variant bg-white p-2 flex items-center justify-center overflow-hidden">
                                    <img id="preview-logo" src="{{ $config['logo'] ?? '/assets/logo.png' }}"
                                        class="max-w-full max-h-full object-contain" alt="Logo actual"
                                        onerror="this.src='/assets/logo.png'">
                                </div>
                                <div class="space-y-1 w-full">
                                    <p class="text-[11px] text-on-surface-variant font-medium">📐 Recomendado: <strong>200 × 60 px</strong></p>
                                    <p class="text-[10px] text-on-surface-variant">PNG/SVG transparente · máx. 500 KB</p>
                                </div>
                                <label class="w-full px-4 py-2 border border-primary text-primary rounded-lg text-label-sm font-label-sm hover:bg-primary-fixed cursor-pointer transition-all flex items-center justify-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">upload</span>
                                    Seleccionar Logo
                                    <input type="file" name="logo" class="hidden" accept="image/*"
                                        onchange="previewImage(this, 'preview-logo')">
                                </label>
                            </div>

                            <!-- Favicon Upload Card -->
                            <div class="flex flex-col items-center p-md border border-outline-variant rounded-2xl bg-surface-container-low text-center space-y-sm">
                                <div class="w-full flex items-center justify-between">
                                    <span class="font-bold text-body-sm text-on-surface">Favicon</span>
                                    @if(!empty($config['favicon']))
                                    <button type="button" onclick="deleteSettingsImage('favicon', 'preview-favicon')"
                                        title="Eliminar favicon actual"
                                        class="p-1 rounded-lg hover:bg-error-container text-error transition-colors flex items-center gap-1 text-[11px] font-semibold">
                                        <span class="material-symbols-outlined text-[16px]">delete</span> Eliminar
                                    </button>
                                    @endif
                                </div>
                                <div class="w-32 h-32 rounded-xl border border-outline-variant bg-white p-2 flex items-center justify-center overflow-hidden">
                                    <img id="preview-favicon" src="{{ $config['favicon'] ?? '/assets/favicon.png' }}"
                                        class="max-w-full max-h-full object-contain" alt="Favicon actual"
                                        onerror="this.src='/assets/favicon.png'">
                                </div>
                                <div class="space-y-1 w-full">
                                    <p class="text-[11px] text-on-surface-variant font-medium">📐 Recomendado: <strong>32 × 32 px</strong></p>
                                    <p class="text-[10px] text-on-surface-variant">ICO/PNG cuadrado · máx. 100 KB</p>
                                </div>
                                <label class="w-full px-4 py-2 border border-primary text-primary rounded-lg text-label-sm font-label-sm hover:bg-primary-fixed cursor-pointer transition-all flex items-center justify-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">upload</span>
                                    Seleccionar Favicon
                                    <input type="file" name="favicon" class="hidden" accept="image/*"
                                        onchange="previewImage(this, 'preview-favicon')">
                                </label>
                            </div>

                            <!-- Banner Upload Card -->
                            <div class="flex flex-col items-center p-md border border-outline-variant rounded-2xl bg-surface-container-low text-center space-y-sm">
                                <div class="w-full flex items-center justify-between">
                                    <span class="font-bold text-body-sm text-on-surface">Banner de Login</span>
                                    @if(!empty($config['banner']))
                                    <button type="button" onclick="deleteSettingsImage('banner', 'preview-banner')"
                                        title="Eliminar banner actual"
                                        class="p-1 rounded-lg hover:bg-error-container text-error transition-colors flex items-center gap-1 text-[11px] font-semibold">
                                        <span class="material-symbols-outlined text-[16px]">delete</span> Eliminar
                                    </button>
                                    @endif
                                </div>
                                <div class="w-32 h-32 rounded-xl border border-outline-variant bg-white p-2 flex items-center justify-center overflow-hidden">
                                    <img id="preview-banner" src="{{ $config['banner'] ?? '/assets/banner.png' }}"
                                        class="max-w-full max-h-full object-cover" alt="Banner actual"
                                        onerror="this.src='/assets/banner.png'">
                                </div>
                                <div class="space-y-1 w-full">
                                    <p class="text-[11px] text-on-surface-variant font-medium">📐 Recomendado: <strong>1920 × 1080 px</strong></p>
                                    <p class="text-[10px] text-on-surface-variant">JPG/PNG horizontal · máx. 2 MB</p>
                                </div>
                                <label class="w-full px-4 py-2 border border-primary text-primary rounded-lg text-label-sm font-label-sm hover:bg-primary-fixed cursor-pointer transition-all flex items-center justify-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">upload</span>
                                    Seleccionar Banner
                                    <input type="file" name="banner" class="hidden" accept="image/*"
                                        onchange="previewImage(this, 'preview-banner')">
                                </label>
                            </div>
                        </div>

                        <!-- Image actions submission -->
                        <div class="flex justify-end pt-md">
                            <button type="submit" form="settings-form"
                                class="px-6 py-2.5 bg-secondary text-on-secondary font-label-md text-label-md rounded-xl hover:opacity-95 shadow-sm transition-all font-semibold flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">save</span>
                                Guardar Imágenes
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- ================= PANEL: OFERTAS LABORALES ================= -->
            <div id="panel-offers" class="tab-panel space-y-lg hidden">
                <!-- Offers List View -->
                <div id="offers-list-view" class="space-y-lg">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        <div>
                            <h1 class="text-headline-lg font-headline-lg text-primary mb-1">Ofertas Laborales</h1>
                            <p class="text-body-md text-on-surface-variant">Gestiona y publica las convocatorias de
                                empleo del portal.</p>
                        </div>
                    </div>

                    <!-- Search and Action Bar -->
                    <div class="flex flex-col sm:flex-row gap-md items-center justify-between">
                        <div class="flex-1 w-full max-w-xl relative">
                            <span
                                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[20px]">search</span>
                            <input id="search-offers-input" oninput="loadOffers()"
                                class="w-full pl-10 pr-4 py-2.5 bg-surface border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm"
                                placeholder="Buscar convocatorias..." type="text">
                        </div>
                        <div class="flex items-center gap-md w-full sm:w-auto">
                            <button onclick="clearOffersFilters()"
                                class="flex-1 sm:flex-initial px-6 py-2.5 bg-surface border border-outline-variant text-on-surface-variant font-label-md text-label-md rounded-xl hover:bg-surface-container-high shadow-sm transition-all font-semibold">
                                Limpiar Filtros
                            </button>
                            <button onclick="showCreateOfferForm()"
                                class="flex-1 sm:flex-initial px-6 py-2.5 bg-primary text-on-primary font-label-md text-label-md rounded-xl hover:opacity-95 shadow-sm transition-all font-semibold flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">add</span>
                                Crear Oferta
                            </button>
                        </div>
                    </div>

                    <!-- Filters Row -->
                    <div
                        class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-sm bg-surface p-sm border border-outline-variant rounded-2xl shadow-sm">
                        <!-- Sort -->
                        <div class="space-y-1">
                            <label
                                class="text-[10px] font-bold uppercase tracking-wider text-on-surface-variant/70 px-1">Ordenar</label>
                            <select id="filter-sort" onchange="loadOffers()"
                                class="w-full bg-background border border-outline-variant/60 rounded-xl px-2 py-1.5 outline-none text-body-sm font-medium focus:border-primary transition-all">
                                <option value="recent">Recientes</option>
                                <option value="title_asc">Título A-Z</option>
                                <option value="title_desc">Título Z-A</option>
                                <option value="salary_desc">Mayor Salario</option>
                                <option value="salary_asc">Menor Salario</option>
                            </select>
                        </div>
                        <!-- Date -->
                        <div class="space-y-1">
                            <label
                                class="text-[10px] font-bold uppercase tracking-wider text-on-surface-variant/70 px-1">Fecha</label>
                            <select id="filter-date" onchange="loadOffers()"
                                class="w-full bg-background border border-outline-variant/60 rounded-xl px-2 py-1.5 outline-none text-body-sm font-medium focus:border-primary transition-all">
                                <option value="">Cualquier fecha</option>
                                <option value="today">Hoy</option>
                                <option value="last_3_days">Últimos 3 días</option>
                                <option value="last_week">Última semana</option>
                                <option value="last_month">Último mes</option>
                            </select>
                        </div>
                        <!-- Category -->
                        <div class="space-y-1">
                            <label
                                class="text-[10px] font-bold uppercase tracking-wider text-on-surface-variant/70 px-1">Categoría</label>
                            <select id="filter-category" onchange="loadOffers()"
                                class="w-full bg-background border border-outline-variant/60 rounded-xl px-2 py-1.5 outline-none text-body-sm font-medium focus:border-primary transition-all">
                                <option value="">Cualquier categoría</option>
                            </select>
                        </div>
                        <!-- Salary -->
                        <div class="space-y-1">
                            <label
                                class="text-[10px] font-bold uppercase tracking-wider text-on-surface-variant/70 px-1">Salario</label>
                            <select id="filter-salary" onchange="loadOffers()"
                                class="w-full bg-background border border-outline-variant/60 rounded-xl px-2 py-1.5 outline-none text-body-sm font-medium focus:border-primary transition-all">
                                <option value="">Cualquier salario</option>
                                <option value="under_1000">Menos de 1000</option>
                                <option value="1000_to_2000">1000 a 2000</option>
                                <option value="2000_to_4000">2000 a 4000</option>
                                <option value="above_4000">Más de 4000</option>
                            </select>
                        </div>
                        <!-- Work schedule (Jornada) -->
                        <div class="space-y-1">
                            <label
                                class="text-[10px] font-bold uppercase tracking-wider text-on-surface-variant/70 px-1">Jornada</label>
                            <select id="filter-work-schedule" onchange="loadOffers()"
                                class="w-full bg-background border border-outline-variant/60 rounded-xl px-2 py-1.5 outline-none text-body-sm font-medium focus:border-primary transition-all">
                                <option value="">Cualquier jornada</option>
                            </select>
                        </div>
                        <!-- Work modality / Location -->
                        <div class="space-y-1">
                            <label
                                class="text-[10px] font-bold uppercase tracking-wider text-on-surface-variant/70 px-1">Lugar
                                de trabajo</label>
                            <select id="filter-location" onchange="loadOffers()"
                                class="w-full bg-background border border-outline-variant/60 rounded-xl px-2 py-1.5 outline-none text-body-sm font-medium focus:border-primary transition-all">
                                <option value="">Cualquier lugar</option>
                            </select>
                        </div>
                        <!-- Contract -->
                        <div class="space-y-1">
                            <label
                                class="text-[10px] font-bold uppercase tracking-wider text-on-surface-variant/70 px-1">Contrato</label>
                            <select id="filter-contract-type" onchange="loadOffers()"
                                class="w-full bg-background border border-outline-variant/60 rounded-xl px-2 py-1.5 outline-none text-body-sm font-medium focus:border-primary transition-all">
                                <option value="">Cualquier contrato</option>
                            </select>
                        </div>
                        <!-- Company -->
                        <div class="space-y-1">
                            <label
                                class="text-[10px] font-bold uppercase tracking-wider text-on-surface-variant/70 px-1">Empresa</label>
                            <select id="filter-company" onchange="loadOffers()"
                                class="w-full bg-background border border-outline-variant/60 rounded-xl px-2 py-1.5 outline-none text-body-sm font-medium focus:border-primary transition-all">
                                <option value="">Cualquier empresa</option>
                            </select>
                        </div>
                    </div>

                    <!-- Bulk Actions (BARRA DE ELIMINACIÓN MASIVA DE OFERTAS) -->
                    <div id="offers-bulk-actions" class="hidden flex flex-col sm:flex-row items-center justify-between bg-red-50 border border-red-200 p-4 rounded-xl shadow-sm gap-md">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-red-600 text-3xl">gavel</span>
                            <div>
                                <p class="text-body-sm font-bold text-red-950" id="offers-selected-count">0 ofertas seleccionadas</p>
                                <p class="text-[12px] text-red-800">Esta acción eliminará de forma permanente (borrado lógico) las convocatorias seleccionadas.</p>
                            </div>
                        </div>
                        <button onclick="bulkDeleteOffers()" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-label-md text-label-md rounded-xl transition-all shadow-sm flex items-center gap-1 font-semibold">
                            <span class="material-symbols-outlined text-[18px]">delete</span>
                            Eliminar seleccionadas
                        </button>
                    </div>

                    <!-- Offers Table -->
                    <div class="bg-surface rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr
                                        class="bg-surface-container-low border-b border-outline-variant text-[11px] font-bold uppercase tracking-wider text-on-surface-variant/80">
                                        <th class="px-4 py-3.5 w-10">
                                            <input type="checkbox" id="select-all-offers" onchange="toggleSelectAllOffers(this)" class="rounded border-outline-variant focus:ring-primary text-primary cursor-pointer">
                                        </th>
                                        <th class="px-4 py-3.5 w-24">Estado</th>
                                        <th class="px-4 py-3.5">Título</th>
                                        <th class="px-4 py-3.5">Salario</th>
                                        <th class="px-4 py-3.5">Categoría</th>
                                        <th class="px-4 py-3.5">Jornada de trabajo</th>
                                        <th class="px-4 py-3.5">Modalidad de trabajo</th>
                                        <th class="px-4 py-3.5">Tipo de contrato</th>
                                        <th class="px-4 py-3.5 text-right w-12">Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="offers-table-body"
                                    class="divide-y divide-outline-variant/60 font-body-sm text-body-sm text-on-surface">
                                    <tr>
                                        <td colspan="9" class="text-center py-xl text-on-surface-variant">Cargando
                                            ofertas...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Offers Create/Edit Form View -->
                <div id="offers-form-view"
                    class="bg-surface rounded-2xl border border-outline-variant shadow-sm overflow-hidden p-lg space-y-lg hidden">
                    <div class="flex items-center gap-sm">
                        <button onclick="hideCreateOfferForm()"
                            class="p-2 border border-outline-variant hover:bg-surface-container-high rounded-xl text-on-surface-variant flex items-center justify-center">
                            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                        </button>
                        <h2 id="form-offer-title" class="text-headline-md font-headline-md text-primary">Crear Oferta
                            Laboral</h2>
                    </div>

                    <form id="offer-form" onsubmit="handleOfferFormSubmit(event)" class="space-y-lg">
                        <input type="hidden" id="form-offer-id" value="">

                        <!-- Row 1: Title and Date -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-lg">
                            <div class="md:col-span-3 space-y-xs">
                                <label class="font-label-sm text-label-sm text-on-surface-variant block"
                                    for="offer-title">Título</label>
                                <input
                                    class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm"
                                    id="offer-title" placeholder="Aa" type="text" required />
                            </div>
                            <div class="space-y-xs">
                                <label class="font-label-sm text-label-sm text-on-surface-variant block"
                                    for="offer-pub-date">Fecha de publicación</label>
                                <input
                                    class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm"
                                    id="offer-pub-date" type="date" required />
                            </div>
                        </div>

                        <!-- Row 2: Description -->
                        <div class="space-y-xs">
                            <label
                                class="font-label-sm text-label-sm text-on-surface-variant block">Descripción</label>
                            <div
                                class="border border-outline-variant rounded-xl bg-background overflow-hidden flex flex-col">
                                <!-- Formatting toolbar -->
                                <div
                                    class="bg-surface-container-low px-md py-sm border-b border-outline-variant/60 flex flex-wrap gap-md items-center text-on-surface-variant/80">
                                    <span
                                        class="font-semibold text-xs border-r border-outline-variant/80 pr-md">Normal</span>
                                    <button type="button" onclick="formatTextarea('offer-description', 'bold')"
                                        class="font-bold hover:text-primary">B</button>
                                    <button type="button" onclick="formatTextarea('offer-description', 'italic')"
                                        class="italic hover:text-primary">I</button>
                                    <span class="text-outline-variant">|</span>
                                    <button type="button" onclick="formatTextarea('offer-description', 'bullet')"
                                        class="material-symbols-outlined text-[18px] hover:text-primary">format_list_bulleted</button>
                                    <button type="button" onclick="formatTextarea('offer-description', 'numeric')"
                                        class="material-symbols-outlined text-[18px] hover:text-primary">format_list_numbered</button>
                                    <span class="text-outline-variant">|</span>
                                    <button type="button" onclick="formatTextarea('offer-description', 'link')"
                                        class="material-symbols-outlined text-[18px] hover:text-primary">link</button>
                                    <button type="button" onclick="formatTextarea('offer-description', 'image')"
                                        class="material-symbols-outlined text-[18px] hover:text-primary">image</button>
                                </div>
                                <textarea id="offer-description" rows="5"
                                    class="w-full px-4 py-2.5 bg-transparent border-none outline-none font-body-sm text-body-sm text-on-surface focus:ring-0"
                                    placeholder="Descripción..." required></textarea>
                            </div>
                        </div>

                        <!-- Row 3: Requirements & Benefits -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-lg">
                            <!-- Requirements -->
                            <div class="space-y-xs">
                                <label
                                    class="font-label-sm text-label-sm text-on-surface-variant block">Requisitos</label>
                                <div
                                    class="border border-outline-variant rounded-xl bg-background overflow-hidden flex flex-col">
                                    <div
                                        class="bg-surface-container-low px-md py-sm border-b border-outline-variant/60 flex flex-wrap gap-md items-center text-on-surface-variant/80">
                                        <span
                                            class="font-semibold text-xs border-r border-outline-variant/80 pr-md">Normal</span>
                                        <button type="button" onclick="formatTextarea('offer-requirements', 'bold')"
                                            class="font-bold hover:text-primary">B</button>
                                        <button type="button" onclick="formatTextarea('offer-requirements', 'italic')"
                                            class="italic hover:text-primary">I</button>
                                        <span class="text-outline-variant">|</span>
                                        <button type="button" onclick="formatTextarea('offer-requirements', 'bullet')"
                                            class="material-symbols-outlined text-[18px] hover:text-primary">format_list_bulleted</button>
                                        <button type="button" onclick="formatTextarea('offer-requirements', 'numeric')"
                                            class="material-symbols-outlined text-[18px] hover:text-primary">format_list_numbered</button>
                                        <span class="text-outline-variant">|</span>
                                        <button type="button" onclick="formatTextarea('offer-requirements', 'link')"
                                            class="material-symbols-outlined text-[18px] hover:text-primary">link</button>
                                        <button type="button" onclick="formatTextarea('offer-requirements', 'image')"
                                            class="material-symbols-outlined text-[18px] hover:text-primary">image</button>
                                    </div>
                                    <textarea id="offer-requirements" rows="4"
                                        class="w-full px-4 py-2.5 bg-transparent border-none outline-none font-body-sm text-body-sm text-on-surface focus:ring-0"
                                        placeholder="Requisitos..." required></textarea>
                                </div>
                            </div>

                            <!-- Benefits -->
                            <div class="space-y-xs">
                                <label
                                    class="font-label-sm text-label-sm text-on-surface-variant block">Beneficios</label>
                                <div
                                    class="border border-outline-variant rounded-xl bg-background overflow-hidden flex flex-col">
                                    <div
                                        class="bg-surface-container-low px-md py-sm border-b border-outline-variant/60 flex flex-wrap gap-md items-center text-on-surface-variant/80">
                                        <span
                                            class="font-semibold text-xs border-r border-outline-variant/80 pr-md">Normal</span>
                                        <button type="button" onclick="formatTextarea('offer-benefits', 'bold')"
                                            class="font-bold hover:text-primary">B</button>
                                        <button type="button" onclick="formatTextarea('offer-benefits', 'italic')"
                                            class="italic hover:text-primary">I</button>
                                        <span class="text-outline-variant">|</span>
                                        <button type="button" onclick="formatTextarea('offer-benefits', 'bullet')"
                                            class="material-symbols-outlined text-[18px] hover:text-primary">format_list_bulleted</button>
                                        <button type="button" onclick="formatTextarea('offer-benefits', 'numeric')"
                                            class="material-symbols-outlined text-[18px] hover:text-primary">format_list_numbered</button>
                                        <span class="text-outline-variant">|</span>
                                        <button type="button" onclick="formatTextarea('offer-benefits', 'link')"
                                            class="material-symbols-outlined text-[18px] hover:text-primary">link</button>
                                        <button type="button" onclick="formatTextarea('offer-benefits', 'image')"
                                            class="material-symbols-outlined text-[18px] hover:text-primary">image</button>
                                    </div>
                                    <textarea id="offer-benefits" rows="4"
                                        class="w-full px-4 py-2.5 bg-transparent border-none outline-none font-body-sm text-body-sm text-on-surface focus:ring-0"
                                        placeholder="Beneficios..."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Row 4: Grid of Selects (3 Columns) -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-lg">
                            <!-- Column 1 -->
                            <div class="space-y-md">
                                <!-- Tipo de contrato -->
                                <div class="space-y-xs">
                                    <label class="font-label-sm text-label-sm text-on-surface-variant block"
                                        for="offer-contract-type">Tipo de contrato</label>
                                    <div class="flex gap-2">
                                        <select id="offer-contract-type"
                                            class="flex-1 px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm"
                                            required>
                                            <option value="">Seleccionar...</option>
                                        </select>
                                        <button type="button"
                                            onclick="openAddLookupModal('contract_type', 'Tipo de contrato')"
                                            class="px-3 border border-outline-variant hover:bg-surface-container-high rounded-xl text-primary flex items-center justify-center">
                                            <span class="material-symbols-outlined text-[18px]">add</span>
                                        </button>
                                    </div>
                                </div>
                                <!-- Modalidad de trabajo -->
                                <div class="space-y-xs">
                                    <label class="font-label-sm text-label-sm text-on-surface-variant block"
                                        for="offer-location">Modalidad de trabajo</label>
                                    <div class="flex gap-2">
                                        <select id="offer-location"
                                            class="flex-1 px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm"
                                            required>
                                            <option value="">Seleccionar...</option>
                                        </select>
                                        <button type="button"
                                            onclick="openAddLookupModal('location', 'Modalidad de trabajo')"
                                            class="px-3 border border-outline-variant hover:bg-surface-container-high rounded-xl text-primary flex items-center justify-center">
                                            <span class="material-symbols-outlined text-[18px]">add</span>
                                        </button>
                                    </div>
                                </div>
                                <!-- Dirección -->
                                <div class="space-y-xs">
                                    <label class="font-label-sm text-label-sm text-on-surface-variant block"
                                        for="offer-address">Dirección</label>
                                    <div class="relative">
                                        <span
                                            class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">pin_drop</span>
                                        <input
                                            class="w-full pl-10 pr-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm"
                                            id="offer-address" placeholder="Ej. Av. De la Cultura 123" type="text"
                                            required />
                                    </div>
                                </div>
                            </div>

                            <!-- Column 2 -->
                            <div class="space-y-md">
                                <!-- Jornada laboral -->
                                <div class="space-y-xs">
                                    <label class="font-label-sm text-label-sm text-on-surface-variant block"
                                        for="offer-work-schedule">Jornada laboral</label>
                                    <div class="flex gap-2">
                                        <select id="offer-work-schedule"
                                            class="flex-1 px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm"
                                            required>
                                            <option value="">Seleccionar...</option>
                                        </select>
                                        <button type="button"
                                            onclick="openAddLookupModal('work_schedule', 'Jornada laboral')"
                                            class="px-3 border border-outline-variant hover:bg-surface-container-high rounded-xl text-primary flex items-center justify-center">
                                            <span class="material-symbols-outlined text-[18px]">add</span>
                                        </button>
                                    </div>
                                </div>
                                <!-- Departamento -->
                                <div class="space-y-xs">
                                    <label class="font-label-sm text-label-sm text-on-surface-variant block"
                                        for="offer-department">Departamento</label>
                                    <select id="offer-department" onchange="handleDepartmentChange()"
                                        class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm"
                                        required>
                                        <option value="">Seleccionar...</option>
                                    </select>
                                </div>
                                <!-- Categoría -->
                                <div class="space-y-xs">
                                    <label class="font-label-sm text-label-sm text-on-surface-variant block"
                                        for="offer-category">Categoría</label>
                                    <div class="flex gap-2">
                                        <select id="offer-category"
                                            class="flex-1 px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm"
                                            required>
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
                                <!-- Salario y Moneda -->
                                <div class="grid grid-cols-2 gap-sm">
                                    <div class="space-y-xs">
                                        <label class="font-label-sm text-label-sm text-on-surface-variant block"
                                            for="offer-salary">Salario</label>
                                        <div class="relative">
                                            <span
                                                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">payments</span>
                                            <input
                                                class="w-full pl-10 pr-3 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm"
                                                id="offer-salary" type="number" min="0" value="0" required />
                                        </div>
                                    </div>
                                    <div class="space-y-xs">
                                        <label class="font-label-sm text-label-sm text-on-surface-variant block"
                                            for="offer-currency">Moneda</label>
                                        <select id="offer-currency"
                                            class="w-full px-3 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm"
                                            required>
                                            <option value="SOLES">SOLES</option>
                                            <option value="DOLARES">DOLARES</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Provincia -->
                                <div class="space-y-xs">
                                    <label class="font-label-sm text-label-sm text-on-surface-variant block"
                                        for="offer-province">Provincia</label>
                                    <select id="offer-province"
                                        class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm"
                                        required>
                                        <option value="">Seleccionar departamento primero...</option>
                                    </select>
                                </div>
                                <!-- Empresa -->
                                <div class="space-y-xs">
                                    <label class="font-label-sm text-label-sm text-on-surface-variant block"
                                        for="offer-company">Empresa</label>
                                    <select id="offer-company"
                                        class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm"
                                        required>
                                        <option value="">Seleccionar...</option>
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
                            <button type="submit" id="btn-save-offer"
                                class="px-6 py-2.5 bg-primary text-on-primary font-label-md text-label-md rounded-xl hover:opacity-95 shadow-sm transition-all font-semibold">
                                Crear Oferta Laboral
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ================= PANEL: GESTION DE EMPRESAS ================= -->
            <div id="panel-companies-manage" class="tab-panel space-y-lg hidden">
                <!-- Header section with Action Buttons -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-headline-lg font-headline-lg text-primary mb-1">Empresas Registradas</h1>
                        <p class="text-body-md text-on-surface-variant">Gestione y verifique las empresas registradas en el sistema.</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <button id="btn-bulk-delete-companies" onclick="bulkDeleteCompanies()"
                            class="px-4 py-2.5 bg-error text-on-error rounded-xl text-label-md font-label-md hover:opacity-90 flex items-center gap-2 shadow-sm transition-all hidden">
                            <span class="material-symbols-outlined text-[20px]">delete_sweep</span>
                            Eliminar seleccionadas
                        </button>
                    </div>
                </div>

                <!-- Search & Filters -->
                <div
                    class="bg-surface rounded-xl border border-outline-variant shadow-sm p-4 flex flex-col sm:flex-row justify-between items-center gap-4 bg-surface-container-low">
                    <h3 class="font-headline-sm text-[16px] text-on-surface font-bold">Listado General de Empresas</h3>
                    <div class="flex items-center gap-sm w-full sm:w-auto">
                        <div class="relative w-full sm:w-80">
                            <span
                                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
                            <input id="search-companies-input" oninput="filterCompanies()"
                                class="w-full pl-9 pr-4 py-1.5 bg-surface-container-lowest border border-outline-variant rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-body-sm"
                                placeholder="Buscar empresa por nombre o RUC..." type="text">
                        </div>
                        <div class="flex border border-outline-variant rounded-lg overflow-hidden shrink-0">
                            <button type="button" id="btn-view-list" onclick="setCompaniesViewMode('list')" class="px-3 py-1.5 bg-primary text-on-primary flex items-center justify-center transition-colors" title="Vista de Lista">
                                <span class="material-symbols-outlined text-[18px]">format_list_bulleted</span>
                            </button>
                            <button type="button" id="btn-view-grid" onclick="setCompaniesViewMode('grid')" class="px-3 py-1.5 bg-surface-container-lowest hover:bg-surface-container-high text-on-surface-variant flex items-center justify-center transition-colors border-l border-outline-variant" title="Vista de Cuadrícula">
                                <span class="material-symbols-outlined text-[18px]">grid_view</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Companies List/Grid Container -->
                <div id="companies-list-view" class="bg-surface rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-surface-container-low border-b border-outline-variant text-[11px] font-bold uppercase tracking-wider text-on-surface-variant/80">
                                    <th class="px-4 py-3.5 w-10 text-center">
                                        <input type="checkbox" id="check-all-companies" onchange="toggleAllCompanies(this.checked)" class="w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary/30 cursor-pointer">
                                    </th>
                                    <th class="px-4 py-3.5 w-12">Logo</th>
                                    <th class="px-4 py-3.5">Empresa</th>
                                    <th class="px-4 py-3.5">RUC</th>
                                    <th class="px-4 py-3.5">Email</th>
                                    <th class="px-4 py-3.5">Teléfono</th>
                                    <th class="px-4 py-3.5 text-center">Ofertas</th>
                                    <th class="px-4 py-3.5 text-center">Estado</th>
                                    <th class="px-4 py-3.5 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="companies-cards-container" class="divide-y divide-outline-variant/60 font-body-sm text-body-sm text-on-surface">
                                <tr><td colspan="9" class="text-center py-xl text-on-surface-variant">Cargando empresas...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="companies-grid-view" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-lg hidden">
                    <!-- Cards will be populated dynamically -->
                </div>
                    <!-- Pagination -->
                    <div id="companies-pagination" class="px-4 py-3 border-t border-outline-variant/60 flex flex-col sm:flex-row items-center justify-between gap-2 text-body-sm text-on-surface-variant hidden">
                        <span id="companies-pagination-info"></span>
                        <div class="flex items-center gap-2">
                            <button id="companies-prev-page" onclick="changeCompaniesPage(currentCompaniesPage - 1)" disabled
                                class="px-3 py-1.5 rounded-lg border border-outline-variant hover:bg-surface-container-high text-on-surface-variant hover:text-on-surface transition-colors disabled:opacity-40 disabled:cursor-not-allowed text-[13px] font-medium flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">chevron_left</span>
                                Anterior
                            </button>
                            <span id="companies-page-indicator" class="px-3 py-1 text-[13px] font-semibold text-on-surface"></span>
                            <button id="companies-next-page" onclick="changeCompaniesPage(currentCompaniesPage + 1)" disabled
                                class="px-3 py-1.5 rounded-lg border border-outline-variant hover:bg-surface-container-high text-on-surface-variant hover:text-on-surface transition-colors disabled:opacity-40 disabled:cursor-not-allowed text-[13px] font-medium flex items-center gap-1">
                                Siguiente
                                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                            </button>
                        </div>
                    </div>
                </div>

            <!-- ================= PANEL: REGISTRO DE EMPRESA ================= -->
            <div id="panel-companies-register" class="tab-panel space-y-lg hidden">
                <div class="flex items-center gap-sm">
                    <button onclick="switchTab('companies-manage')"
                        class="p-2 border border-outline-variant hover:bg-surface-container-high rounded-xl text-on-surface-variant flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                    </button>
                    <h2 id="companies-form-title" class="text-headline-md font-headline-md text-primary">Registrar
                        Empresa</h2>
                </div>

                <form id="companies-form" onsubmit="handleCompanyFormSubmit(event)"
                    class="bg-surface rounded-xl border border-outline-variant shadow-sm p-lg space-y-lg"
                    enctype="multipart/form-data">
                    <input type="hidden" id="form-company-id" value="">

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-lg">
                        <!-- Columna Izquierda: Datos Básicos y Contacto -->
                        <div class="space-y-md">
                            <!-- Vincular Cuenta de Usuario de Empresa -->
                            <div class="space-y-xs" id="container-comp-user-id">
                                <label class="font-label-sm text-label-sm text-on-surface-variant block"
                                    for="comp-user-id">Vincular Cuenta de Usuario de Empresa Existente
                                    (Opcional)</label>
                                <select id="comp-user-id" name="user_id" onchange="handleSelectCompanyUser(this)"
                                    class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm text-on-surface">
                                    <option value="">-- Crear nueva cuenta de usuario --</option>
                                    @foreach ($users->where('rol_id', 4) as $companyUser)
                                        @php
                                            $companyUserName = $companyUser->company->name ?? 'Usuario Empresa';
                                        @endphp
                                        <option value="{{ $companyUser->id }}" data-email="{{ $companyUser->email }}"
                                            data-name="{{ $companyUserName }}"
                                            data-ruc="{{ $companyUser->company->ruc ?? '' }}"
                                            data-phone="{{ $companyUser->company->phone ?? '' }}">
                                            {{ $companyUser->email }} ({{ $companyUserName }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Nombre de la Empresa -->
                            <div class="space-y-xs">
                                <label class="font-label-sm text-label-sm text-on-surface-variant block"
                                    for="comp-name">Nombre de la Empresa</label>
                                <input
                                    class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm"
                                    id="comp-name" name="name" placeholder="Ej. TechCorp Solutions S.A.C." type="text"
                                    required />
                            </div>

                            <!-- RUC y Email -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                                <div class="space-y-xs">
                                    <label class="font-label-sm text-label-sm text-on-surface-variant block"
                                        for="comp-ruc">RUC</label>
                                    <input
                                        class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm"
                                        id="comp-ruc" name="ruc" placeholder="Ej. 20123456789" type="text"
                                        maxlength="11" required />
                                </div>
                                <div class="space-y-xs">
                                    <label class="font-label-sm text-label-sm text-on-surface-variant block"
                                        for="comp-email">Email corporativo / Usuario</label>
                                    <input
                                        class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm"
                                        id="comp-email" name="email" placeholder="Ej. contacto@techcorp.com"
                                        type="email" required />
                                </div>
                            </div>

                            <!-- Teléfono y Dirección -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                                <div class="space-y-xs">
                                    <label class="font-label-sm text-label-sm text-on-surface-variant block"
                                        for="comp-phone">Teléfono</label>
                                    <input
                                        class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm"
                                        id="comp-phone" name="phone" placeholder="Ej. 987654321" type="text" required />
                                </div>
                                <div class="space-y-xs">
                                    <label class="font-label-sm text-label-sm text-on-surface-variant block"
                                        for="comp-address">Dirección</label>
                                    <input
                                        class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm"
                                        id="comp-address" name="address" placeholder="Ej. Av. Larco 456, Miraflores"
                                        type="text" required />
                                </div>
                            </div>

                            <!-- Buzón de Correo y Página Web -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                                <div class="space-y-xs">
                                    <label class="font-label-sm text-label-sm text-on-surface-variant block"
                                        for="comp-mailbox">Buzón de Postulaciones (Opcional)</label>
                                    <input
                                        class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm"
                                        id="comp-mailbox" name="mailbox" placeholder="Ej. postulaciones@techcorp.com"
                                        type="email" />
                                </div>
                                <div class="space-y-xs">
                                    <label class="font-label-sm text-label-sm text-on-surface-variant block"
                                        for="comp-website">Página Web (Opcional)</label>
                                    <input
                                        class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm"
                                        id="comp-website" name="website" placeholder="Ej. www.techcorp.com"
                                        type="text" />
                                </div>
                            </div>
                        </div>

                        <!-- Columna Derecha: Descripción y Logo -->
                        <div class="space-y-md flex flex-col">
                            <!-- Descripción -->
                            <div class="space-y-xs flex-1 flex flex-col">
                                <label class="font-label-sm text-label-sm text-on-surface-variant block"
                                    for="comp-description">Acerca de la Empresa</label>
                                <textarea id="comp-description" name="description" rows="4"
                                    class="w-full flex-1 px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm text-on-surface"
                                    placeholder="Cuéntanos un poco sobre la empresa..."></textarea>
                            </div>

                            <!-- Logotipo Carga / Vista Previa -->
                            <div class="space-y-xs">
                                <label class="font-label-sm text-label-sm text-on-surface-variant block">Logotipo
                                    Corporativo</label>
                                <div
                                    class="flex items-center gap-md p-md border border-outline-variant rounded-xl bg-background">
                                    <div
                                        class="w-20 h-20 rounded-xl border border-outline-variant overflow-hidden shrink-0 bg-surface-container flex items-center justify-center">
                                        <img id="comp-logo-preview" src="/assets/placeholder-logo.png"
                                            class="w-full h-full object-cover"
                                            onerror="this.src='https://placehold.co/100x100?text=Logo'">
                                    </div>
                                    <div class="space-y-2">
                                        <input type="file" id="comp-logo-file" name="logo" class="hidden"
                                            accept="image/*" onchange="previewImage(this, 'comp-logo-preview')">
                                        <button type="button"
                                            onclick="document.getElementById('comp-logo-file').click()"
                                            class="px-4 py-2 bg-secondary text-on-secondary rounded-lg font-label-sm text-label-sm hover:opacity-90 flex items-center gap-1 shadow-sm transition-all font-semibold">
                                            <span class="material-symbols-outlined text-[18px]">upload</span>
                                            Subir Logo
                                        </button>
                                        <p class="text-[11px] text-on-surface-variant">Formatos: PNG, JPG, JPEG (máx.
                                            2MB)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Actions -->
                    <div class="flex justify-end gap-md pt-lg border-t border-outline-variant">
                        <button type="button" onclick="switchTab('companies-manage')"
                            class="px-6 py-2.5 border border-primary text-primary font-label-md text-label-md rounded-xl hover:bg-primary-fixed transition-colors font-semibold">
                            Cancelar
                        </button>
                        <button type="submit" id="btn-save-company"
                            class="px-6 py-2.5 bg-primary text-on-primary font-label-md text-label-md rounded-xl hover:opacity-95 shadow-sm transition-all font-semibold">
                            Registrar Empresa
                        </button>
                    </div>
                </form>
            </div>

            <!-- ================= PANEL: POSTULACIONES ================= -->
            <div id="panel-applications" class="tab-panel space-y-lg hidden">
                <!-- Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-headline-lg font-headline-lg text-primary mb-1">Postulaciones Recibidas</h1>
                        <p class="text-body-md text-on-surface-variant">Monitoree, gestione y califique las
                            postulaciones de candidatos en el sistema.</p>
                    </div>
                </div>

                <!-- Filters -->
                <div
                    class="bg-surface rounded-xl border border-outline-variant shadow-sm p-lg flex flex-col md:flex-row justify-between items-center gap-md bg-surface-container-low">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-md w-full">
                        <!-- Search Input -->
                        <div class="relative">
                            <span
                                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
                            <input id="search-apps-input" oninput="filterApplications()"
                                class="w-full pl-9 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-body-sm"
                                placeholder="Buscar por candidato u oferta..." type="text">
                        </div>
                        <!-- Status Filter -->
                        <div>
                            <select id="filter-apps-status" onchange="filterApplications()"
                                class="w-full px-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-body-sm text-on-surface">
                                <option value="">Todos los estados</option>
                                <option value="postulated">Postulado</option>
                                <option value="under_review">En revisión</option>
                                <option value="accepted">Aceptado</option>
                                <option value="rejected">Rechazado</option>
                            </select>
                        </div>
                        <!-- Company Filter -->
                        <div>
                            <select id="filter-apps-company" onchange="filterApplications()"
                                class="w-full px-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-body-sm text-on-surface">
                                <option value="">Todas las empresas</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Bulk Actions (BARRA DE ELIMINACIÓN MASIVA) -->
                <div id="apps-bulk-actions" class="hidden flex flex-col sm:flex-row items-center justify-between bg-red-50 border border-red-200 p-4 rounded-xl shadow-sm gap-md">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-red-600 text-3xl">gavel</span>
                        <div>
                            <p class="text-body-sm font-bold text-red-950" id="apps-selected-count">0 postulaciones seleccionadas</p>
                            <p class="text-[12px] text-red-800">Esta acción eliminará de forma permanente los registros seleccionados del sistema.</p>
                        </div>
                    </div>
                    <button onclick="bulkDeleteApplications()" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-label-md text-label-md rounded-xl transition-all shadow-sm flex items-center gap-1 font-semibold">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                        Eliminar seleccionados
                    </button>
                </div>

                <!-- Applications Table -->
                <div
                    class="bg-surface rounded-xl border border-outline-variant shadow-sm overflow-hidden flex flex-col">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr
                                    class="bg-surface-container-low text-on-surface-variant border-b border-outline-variant">
                                    <th class="p-4 w-10">
                                        <input type="checkbox" id="select-all-apps" onchange="toggleSelectAllApps(this)" class="rounded border-outline-variant focus:ring-primary text-primary cursor-pointer">
                                    </th>
                                    <th class="p-4 text-label-sm font-label-sm font-semibold">Candidato</th>
                                    <th class="p-4 text-label-sm font-label-sm font-semibold">Programa de Estudio</th>
                                    <th class="p-4 text-label-sm font-label-sm font-semibold">Oferta / Empresa</th>
                                    <th class="p-4 text-label-sm font-label-sm font-semibold">Fecha</th>
                                    <th class="p-4 text-label-sm font-label-sm font-semibold">Estado</th>
                                    <th class="p-4 text-label-sm font-label-sm font-semibold text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-body-md font-body-md" id="applications-table-body">
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-on-surface-variant">Cargando
                                        postulaciones...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ================= PANEL: MANTENEDORES ================= -->
            <div id="panel-maintainers" class="tab-panel space-y-lg hidden">
                <div>
                    <h1 class="text-headline-lg font-headline-lg text-primary mb-1">Mantenedores</h1>
                    <p class="text-body-md text-on-surface-variant">Administre las opciones utilizadas al registrar y
                        filtrar ofertas laborales.</p>
                </div>

                <div class="bg-surface rounded-xl border border-outline-variant shadow-sm overflow-hidden">
                    <div class="border-b border-outline-variant overflow-x-auto">
                        <div class="flex min-w-max px-md" role="tablist" aria-label="Tipos de mantenedor">
                            <button type="button" data-maintainer-type="work_schedule"
                                onclick="selectMaintainerType('work_schedule')"
                                class="maintainer-tab px-4 py-4 text-label-md font-label-md border-b-2 border-primary text-primary transition-colors">Jornada
                                laboral</button>
                            <button type="button" data-maintainer-type="category"
                                onclick="selectMaintainerType('category')"
                                class="maintainer-tab px-4 py-4 text-label-md font-label-md border-b-2 border-transparent text-on-surface-variant hover:text-primary transition-colors">Categor&iacute;as</button>
                            <button type="button" data-maintainer-type="location"
                                onclick="selectMaintainerType('location')"
                                class="maintainer-tab px-4 py-4 text-label-md font-label-md border-b-2 border-transparent text-on-surface-variant hover:text-primary transition-colors">Ubicaciones</button>
                            <button type="button" data-maintainer-type="contract_type"
                                onclick="selectMaintainerType('contract_type')"
                                class="maintainer-tab px-4 py-4 text-label-md font-label-md border-b-2 border-transparent text-on-surface-variant hover:text-primary transition-colors">Tipos
                                de contrato</button>
                        </div>
                    </div>

                    <div class="p-md md:p-lg">
                        <div class="flex justify-end mb-md">
                            <button type="button" onclick="openMaintainerModal()"
                                class="px-4 py-2.5 bg-secondary text-on-secondary rounded-xl text-label-md font-label-md hover:opacity-90 flex items-center gap-2 shadow-sm transition-all">
                                <span class="material-symbols-outlined text-[20px]">add</span>
                                <span id="maintainer-create-label">Crear jornada laboral</span>
                            </button>
                        </div>

                        <div class="overflow-x-auto border-y border-outline-variant">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-surface-container-low text-on-surface-variant">
                                        <th
                                            class="px-4 py-3.5 text-label-sm font-label-sm font-semibold uppercase tracking-wider">
                                            Nombre</th>
                                        <th
                                            class="px-4 py-3.5 text-label-sm font-label-sm font-semibold uppercase tracking-wider text-right w-40">
                                            Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="maintainers-table-body" class="text-body-md font-body-md">
                                    <tr>
                                        <td colspan="2" class="p-8 text-center text-on-surface-variant">Cargando
                                            opciones...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center justify-end gap-4 pt-md">
                            <label class="flex items-center gap-2 text-body-sm text-on-surface-variant">
                                Elementos por p&aacute;gina:
                                <select id="maintainer-page-size" onchange="changeMaintainerPageSize()"
                                    class="px-3 py-2 bg-background border border-outline-variant rounded-lg text-body-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                    <option value="5">5</option>
                                    <option value="10" selected>10</option>
                                    <option value="20">20</option>
                                </select>
                            </label>
                            <span id="maintainer-page-summary"
                                class="text-body-sm text-on-surface-variant min-w-[90px] text-center">0 de 0</span>
                            <div class="flex items-center gap-1">
                                <button id="maintainer-first-page" type="button" onclick="goToMaintainerPage(1)"
                                    class="p-2 rounded-full text-on-surface-variant hover:bg-surface-container-high disabled:opacity-30 disabled:pointer-events-none"
                                    title="Primera p&aacute;gina"><span
                                        class="material-symbols-outlined text-[20px]">first_page</span></button>
                                <button id="maintainer-prev-page" type="button"
                                    onclick="goToMaintainerPage(maintainerCurrentPage - 1)"
                                    class="p-2 rounded-full text-on-surface-variant hover:bg-surface-container-high disabled:opacity-30 disabled:pointer-events-none"
                                    title="P&aacute;gina anterior"><span
                                        class="material-symbols-outlined text-[20px]">chevron_left</span></button>
                                <button id="maintainer-next-page" type="button"
                                    onclick="goToMaintainerPage(maintainerCurrentPage + 1)"
                                    class="p-2 rounded-full text-on-surface-variant hover:bg-surface-container-high disabled:opacity-30 disabled:pointer-events-none"
                                    title="P&aacute;gina siguiente"><span
                                        class="material-symbols-outlined text-[20px]">chevron_right</span></button>
                                <button id="maintainer-last-page" type="button"
                                    onclick="goToMaintainerPage(maintainerTotalPages)"
                                    class="p-2 rounded-full text-on-surface-variant hover:bg-surface-container-high disabled:opacity-30 disabled:pointer-events-none"
                                    title="&Uacute;ltima p&aacute;gina"><span
                                        class="material-symbols-outlined text-[20px]">last_page</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ================= PANEL 3+: PLACEHOLDERS ================= -->
            <div id="panel-placeholder" class="tab-panel space-y-lg hidden">
                <div
                    class="bg-surface rounded-xl border border-outline-variant p-2xl text-center space-y-md max-w-xl mx-auto shadow-sm">
                    <span class="material-symbols-outlined text-outline text-6xl">construction</span>
                    <h2 class="text-headline-md font-headline-md text-primary">Sección en Desarrollo</h2>
                    <p class="text-body-md text-on-surface-variant">Próximamente se integrarán las funcionalidades
                        completas correspondientes para este módulo del sistema.</p>
                    <button onclick="switchTab('dashboard')"
                        class="px-6 py-2 bg-primary text-on-primary rounded-xl text-label-md font-label-md hover:opacity-90 transition-opacity">
                        Volver al Inicio
                    </button>
                </div>
            </div>

            <!-- ================= PANEL: SOPORTE ================= -->
            <div id="panel-support" class="tab-panel space-y-lg hidden">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-headline-lg font-headline-lg text-primary mb-1">Soporte</h1>
                        <p class="text-body-md text-on-surface-variant">Accesos de ayuda para el panel administrativo.</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-lg">
                    <div class="bg-surface rounded-xl border border-outline-variant p-lg shadow-sm space-y-sm">
                        <span class="material-symbols-outlined text-primary text-4xl">help</span>
                        <h2 class="text-headline-sm font-headline-sm text-on-surface">Centro de ayuda</h2>
                        <p class="text-body-md text-on-surface-variant">Revise los módulos disponibles y use los accesos rápidos para volver a la sección que necesita.</p>
                        <div class="flex flex-wrap gap-sm pt-sm">
                            <button type="button" onclick="switchTab('users')"
                                class="px-4 py-2 rounded-lg bg-primary text-on-primary text-label-md font-label-md hover:opacity-90">Usuarios</button>
                            <button type="button" onclick="switchTab('offers')"
                                class="px-4 py-2 rounded-lg border border-outline-variant text-on-surface text-label-md font-label-md hover:bg-surface-container-high">Ofertas</button>
                            <button type="button" onclick="switchTab('companies-manage')"
                                class="px-4 py-2 rounded-lg border border-outline-variant text-on-surface text-label-md font-label-md hover:bg-surface-container-high">Empresas</button>
                        </div>
                    </div>
                    <div class="bg-surface rounded-xl border border-outline-variant p-lg shadow-sm space-y-sm">
                        <span class="material-symbols-outlined text-secondary text-4xl">admin_panel_settings</span>
                        <h2 class="text-headline-sm font-headline-sm text-on-surface">Configuración del sistema</h2>
                        <p class="text-body-md text-on-surface-variant">Si algo no se muestra correctamente, revise la configuración visual o cierre sesión para iniciar nuevamente.</p>
                        <div class="flex flex-wrap gap-sm pt-sm">
                            <button type="button" onclick="switchTab('settings')"
                                class="px-4 py-2 rounded-lg bg-secondary text-on-secondary text-label-md font-label-md hover:opacity-90">Ajustes</button>
                            <button type="button" onclick="document.getElementById('logout-form').submit()"
                                class="px-4 py-2 rounded-lg border border-outline-variant text-on-surface text-label-md font-label-md hover:bg-surface-container-high">Cerrar sesión</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- ================= MODAL: DETALLES DE POSTULACIÓN ================= -->
    <div id="view-app-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/40 p-4">
        <div
            class="w-full max-w-lg bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-xl overflow-hidden transform scale-95 transition-transform duration-300">
            <!-- Header -->
            <div
                class="flex justify-between items-center px-lg py-md border-b border-outline-variant bg-surface-container-low">
                <h2 class="text-headline-md font-headline-md text-on-surface">Detalle de Postulación</h2>
                <button onclick="toggleAppModal()"
                    class="text-on-surface-variant hover:bg-surface-container-high p-1 rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <!-- Content -->
            <form id="app-status-form" onsubmit="handleAppStatusSubmit(event)" class="p-lg space-y-md">
                <input type="hidden" id="detail-app-id" value="">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-md">
                    <div>
                        <span class="text-label-sm font-label-sm text-on-surface-variant block">Candidato</span>
                        <span id="detail-app-name" class="text-body-md font-bold text-on-surface">María Espinoza</span>
                    </div>
                    <div>
                        <span class="text-label-sm font-label-sm text-on-surface-variant block">Programa de
                            Estudio</span>
                        <span id="detail-app-program" class="text-body-md text-on-surface">Computación e
                            Informática</span>
                    </div>
                    <div>
                        <span class="text-label-sm font-label-sm text-on-surface-variant block">Puesto / Oferta</span>
                        <span id="detail-app-offer" class="text-body-md text-on-surface">Científico de Datos</span>
                    </div>
                    <div>
                        <span class="text-label-sm font-label-sm text-on-surface-variant block">Empresa</span>
                        <span id="detail-app-company" class="text-body-md text-on-surface">AI Solutions</span>
                    </div>
                </div>

                <hr class="border-outline-variant" />

                <!-- Curriculum Vitae (CV) -->
                <div class="space-y-xs">
                    <span class="text-label-sm font-label-sm text-on-surface-variant block">Currículum Vitae
                        (CV)</span>
                    <div
                        class="flex items-center justify-between p-sm bg-surface-container-low rounded-xl border border-outline-variant">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-red-600 text-3xl">picture_as_pdf</span>
                            <span id="detail-app-cv-name"
                                class="text-body-sm font-medium text-on-surface max-w-[240px] truncate">cv_candidato.pdf</span>
                        </div>
                        <a id="detail-app-cv-link" href="#" target="_blank"
                            class="px-3 py-1.5 bg-secondary text-on-secondary hover:opacity-90 rounded-lg text-label-sm font-label-sm font-semibold flex items-center gap-1 shadow-sm transition-all">
                            <span class="material-symbols-outlined text-[16px]">download</span>
                            Descargar
                        </a>
                    </div>
                </div>

                <!-- Message from Candidate -->
                <div class="space-y-xs">
                    <span class="text-label-sm font-label-sm text-on-surface-variant block">Mensaje /
                        Presentación</span>
                    <div id="detail-app-message"
                        class="p-sm bg-surface-container-low rounded-xl border border-outline-variant text-body-sm text-on-surface-variant whitespace-pre-wrap max-h-32 overflow-y-auto">
                        No se adjuntó mensaje de presentación.
                    </div>
                </div>

                <hr class="border-outline-variant" />

                <!-- Change Status & Feedback -->
                <div class="space-y-md bg-primary-container/10 p-md rounded-xl border border-primary/10">
                    <div class="space-y-xs">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block"
                            for="detail-app-status">Estado del Proceso</label>
                        <select id="detail-app-status"
                            class="w-full px-4 py-2 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm"
                            required>
                            <option value="postulated">Postulado</option>
                            <option value="under_review">En revisión</option>
                            <option value="accepted">Aceptado</option>
                            <option value="rejected">Rechazado</option>
                        </select>
                    </div>

                    <div class="space-y-xs">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block"
                            for="detail-app-feedback">Retroalimentación / Notas (Opcional)</label>
                        <textarea id="detail-app-feedback" rows="3"
                            class="w-full px-4 py-2 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm"
                            placeholder="Escribe aquí los comentarios o detalles del cambio de estado..."></textarea>
                        <p id="detail-app-feedback-date" class="text-[10px] text-on-surface-variant italic hidden"></p>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="flex justify-end gap-md pt-lg border-t border-outline-variant">
                    <button type="button" onclick="toggleAppModal()"
                        class="px-6 py-2.5 border border-primary text-primary font-label-md text-label-md rounded-xl hover:bg-primary-fixed transition-colors font-semibold">
                        Cerrar
                    </button>
                    <button type="submit" id="btn-save-app-status"
                        class="px-6 py-2.5 bg-primary text-on-primary font-label-md text-label-md rounded-xl hover:opacity-95 shadow-sm transition-all font-semibold">
                        Guardar Estado
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODAL: MANTENEDOR ================= -->
    <div id="maintainer-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/40 p-4">
        <div
            class="w-full max-w-md bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-xl overflow-hidden transform scale-95 transition-transform duration-300">
            <div
                class="flex justify-between items-center px-lg py-md border-b border-outline-variant bg-surface-container-low">
                <h2 id="maintainer-modal-title" class="text-headline-md font-headline-md text-on-surface">Crear
                    opci&oacute;n</h2>
                <button type="button" onclick="toggleMaintainerModal()"
                    class="text-on-surface-variant hover:bg-surface-container-high p-1 rounded-full flex items-center justify-center"><span
                        class="material-symbols-outlined">close</span></button>
            </div>
            <form id="maintainer-form" onsubmit="handleMaintainerSubmit(event)" class="p-lg space-y-md">
                <input type="hidden" id="maintainer-item-id" value="">
                <div class="space-y-xs">
                    <label class="font-label-sm text-label-sm text-on-surface-variant block"
                        for="maintainer-name">Nombre</label>
                    <input id="maintainer-name"
                        class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm"
                        type="text" maxlength="255" required>
                </div>
                <div class="flex justify-end gap-md pt-lg border-t border-outline-variant">
                    <button type="button" onclick="toggleMaintainerModal()"
                        class="px-6 py-2.5 border border-primary text-primary font-label-md text-label-md rounded-xl hover:bg-primary-fixed transition-colors">Cancelar</button>
                    <button id="maintainer-save-button" type="submit"
                        class="px-6 py-2.5 bg-primary text-on-primary font-label-md text-label-md rounded-xl hover:opacity-95 shadow-sm transition-all font-semibold">Crear</button>
                </div>
            </form>
        </div>
    </div>
    <!-- ================= MODAL: AGREGAR METADATOS inline (+) ================= -->
    <div id="add-lookup-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/40 p-4">
        <div
            class="w-full max-w-md bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-xl overflow-hidden transform scale-95 transition-transform duration-300">
            <div
                class="flex justify-between items-center px-lg py-md border-b border-outline-variant bg-surface-container-low">
                <h2 id="lookup-modal-title" class="text-headline-md font-headline-md text-on-surface">Agregar opción
                </h2>
                <button onclick="toggleLookupModal()"
                    class="text-on-surface-variant hover:bg-surface-container-high p-1 rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form id="add-lookup-form" onsubmit="handleAddLookupSubmit(event)" class="p-lg space-y-md">
                <input type="hidden" id="lookup-type" value="">
                <div class="space-y-xs">
                    <label class="font-label-sm text-label-sm text-on-surface-variant block" for="lookup-name">Nombre de
                        la opción</label>
                    <input
                        class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-body-sm"
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

    <!-- ================= MODAL: PREVISUALIZAR OFERTA ================= -->
    <div id="preview-offer-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/40 p-4">
        <div
            class="w-full max-w-4xl bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-xl overflow-hidden transform scale-95 transition-transform duration-300 flex flex-col max-h-[90vh]">
            <!-- Header -->
            <div
                class="flex justify-between items-center px-lg py-md border-b border-outline-variant bg-surface-container-low flex-shrink-0">
                <h2 class="text-headline-md font-headline-md text-on-surface">Previsualización de Oferta</h2>
                <button onclick="togglePreviewOfferModal()"
                    class="text-on-surface-variant hover:bg-surface-container-high p-1 rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <!-- Content Area -->
            <div id="preview-offer-content"
                class="p-lg space-y-lg overflow-y-auto flex-1 font-body-sm text-body-sm text-on-surface-variant">
                <div class="text-center py-xl">Cargando...</div>
            </div>
        </div>
    </div>

    <!-- ================= MODAL: POSTULANTES DE OFERTA ================= -->
    <div id="offer-applicants-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/40 p-4">
        <div
            class="w-full max-w-4xl bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-xl overflow-hidden transform scale-95 transition-transform duration-300 flex flex-col max-h-[90vh]">
            <!-- Header -->
            <div
                class="flex justify-between items-center px-lg py-md border-b border-outline-variant bg-surface-container-low flex-shrink-0">
                <div>
                    <h2 class="text-headline-md font-headline-md text-on-surface">Candidatos Postulados</h2>
                    <p id="offer-applicants-title" class="text-body-sm text-on-surface-variant font-medium mt-0.5"></p>
                </div>
                <button onclick="toggleOfferApplicantsModal()"
                    class="text-on-surface-variant hover:bg-surface-container-high p-1 rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <!-- Content Area -->
            <div class="p-lg overflow-y-auto flex-1 font-body-sm text-body-sm text-on-surface-variant">
                <div class="overflow-x-auto border border-outline-variant rounded-xl">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-low border-b border-outline-variant text-[11px] font-bold uppercase tracking-wider text-on-surface-variant/80">
                                <th class="p-4">Candidato</th>
                                <th class="p-4">Programa de Estudio</th>
                                <th class="p-4">Contacto</th>
                                <th class="p-4">Fecha</th>
                                <th class="p-4 text-center">Estado</th>
                                <th class="p-4 text-center">Currículum</th>
                            </tr>
                        </thead>
                        <tbody id="offer-applicants-list-container" class="divide-y divide-outline-variant/60 font-body-sm text-body-sm text-on-surface">
                            <tr><td colspan="6" class="text-center py-xl text-on-surface-variant">Cargando postulantes...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Global Floating Actions Dropdown (3 puntos) -->
    <div id="offer-actions-dropdown"
        class="fixed hidden bg-surface-container-lowest border border-outline-variant rounded-xl shadow-xl z-50 py-2 w-48 transition-all duration-150">
        <div
            class="px-4 py-1 text-[10px] font-bold uppercase tracking-wider text-on-surface-variant/70 border-b border-outline-variant/50">
            Acciones</div>
        <button onclick="handleOfferAction('preview')"
            class="w-full text-left px-4 py-2 hover:bg-surface-container-high text-body-sm text-on-surface flex items-center gap-sm">
            <span class="material-symbols-outlined text-[18px]">visibility</span>
            Previsualizar
        </button>
        <button onclick="handleOfferAction('applicants')"
            class="w-full text-left px-4 py-2 hover:bg-surface-container-high text-body-sm text-on-surface flex items-center gap-sm">
            <span class="material-symbols-outlined text-[18px]">group</span>
            Postulantes
        </button>
        <button onclick="handleOfferAction('share')"
            class="w-full text-left px-4 py-2 hover:bg-surface-container-high text-body-sm text-on-surface flex items-center gap-sm">
            <span class="material-symbols-outlined text-[18px]">share</span>
            Compartir
        </button>
        <button onclick="handleOfferAction('edit')"
            class="w-full text-left px-4 py-2 hover:bg-surface-container-high text-body-sm text-on-surface flex items-center gap-sm">
            <span class="material-symbols-outlined text-[18px]">edit</span>
            Editar
        </button>
        <button onclick="handleOfferAction('delete')"
            class="w-full text-left px-4 py-2 hover:bg-surface-container-high text-body-sm text-red-600 flex items-center gap-sm border-b border-outline-variant/50">
            <span class="material-symbols-outlined text-[18px]">delete</span>
            Eliminar
        </button>
        <div
            class="px-4 py-1 text-[10px] font-bold uppercase tracking-wider text-on-surface-variant/70 border-b border-outline-variant/50 mt-1">
            Estados</div>
        <button onclick="handleOfferAction('state_draft')"
            class="w-full text-left px-4 py-2 hover:bg-surface-container-high text-body-sm text-on-surface flex items-center gap-sm">
            <span class="material-symbols-outlined text-[18px]">draft</span>
            Cambiar a Borrador
        </button>
        <button onclick="handleOfferAction('state_active')"
            class="w-full text-left px-4 py-2 hover:bg-surface-container-high text-body-sm text-on-surface flex items-center gap-sm">
            <span class="material-symbols-outlined text-[18px]">check_circle</span>
            Publicar / Activar
        </button>
        <button onclick="handleOfferAction('state_finished')"
            class="w-full text-left px-4 py-2 hover:bg-surface-container-high text-body-sm text-on-surface flex items-center gap-sm">
            <span class="material-symbols-outlined text-[18px]">task_alt</span>
            Finalizar Oferta
        </button>
    </div>

    <!-- Global Floating Company Actions Dropdown (3 puntos) -->
    <div id="company-actions-dropdown"
        class="fixed hidden bg-surface-container-lowest border border-outline-variant rounded-xl shadow-xl z-50 py-2 w-52 transition-all duration-150">
        <div
            class="px-4 py-1 text-[10px] font-bold uppercase tracking-wider text-on-surface-variant/70 border-b border-outline-variant/50">
            Acciones de Empresa</div>
        <button onclick="handleCompanyAction('toggle-verify')"
            class="w-full text-left px-4 py-2 hover:bg-surface-container-high text-body-sm text-on-surface flex items-center gap-sm">
            <span class="material-symbols-outlined text-[18px]" id="comp-verify-menu-icon">verified_user</span>
            <span id="comp-verify-menu-text">Verificar / Desverificar</span>
        </button>
        <button onclick="handleCompanyAction('edit')"
            class="w-full text-left px-4 py-2 hover:bg-surface-container-high text-body-sm text-on-surface flex items-center gap-sm">
            <span class="material-symbols-outlined text-[18px]">edit</span>
            Editar Empresa
        </button>
        <button onclick="handleCompanyAction('delete')"
            class="w-full text-left px-4 py-2 hover:bg-surface-container-high text-body-sm text-red-600 flex items-center gap-sm border-t border-outline-variant/50">
            <span class="material-symbols-outlined text-[18px]">delete</span>
            Eliminar Empresa
        </button>
    </div>

    <!-- ================= MODAL: CREACIÓN DE USUARIO ================= -->
    <div id="create-user-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/40 p-4">
        <div
            class="w-full max-w-4xl bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-xl overflow-hidden transform scale-95 transition-transform duration-300">
            <!-- Header -->
            <div
                class="flex justify-between items-center px-lg py-md border-b border-outline-variant bg-surface-container-low">
                <h2 class="text-headline-md font-headline-md text-on-surface">Creación de usuario</h2>
                <button onclick="toggleCreateModal()"
                    class="text-on-surface-variant hover:bg-surface-container-high p-1 rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <!-- Form -->
            <form id="create-user-form" onsubmit="handleCreateUserSubmit(event)" class="p-lg space-y-lg">
                <input type="hidden" id="form-user-id" value="">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-lg">
                    <!-- Nombres -->
                    <div class="space-y-xs">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block"
                            for="form-names">Nombres</label>
                        <input
                            class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-student-accent/20 focus:border-student-accent outline-none transition-all font-body-sm text-body-sm"
                            id="form-names" placeholder="Ej. Juan Pérez" type="text" required />
                    </div>

                    <!-- Email -->
                    <div class="space-y-xs">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block"
                            for="form-email">Email</label>
                        <input
                            class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-student-accent/20 focus:border-student-accent outline-none transition-all font-body-sm text-body-sm"
                            id="form-email" placeholder="Ej. juan.perez@bolsalaboral.edu.pe" type="email" required />
                    </div>

                    <!-- Teléfono -->
                    <div class="space-y-xs">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block"
                            for="form-phone">Teléfono</label>
                        <input
                            class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-student-accent/20 focus:border-student-accent outline-none transition-all font-body-sm text-body-sm"
                            id="form-phone" placeholder="Ej. 987654321" type="text" />
                    </div>

                    <!-- Rol -->
                    <div class="space-y-xs">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block"
                            for="form-role">Rol</label>
                        <select
                            class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-student-accent/20 focus:border-student-accent outline-none transition-all font-body-sm text-body-sm"
                            id="form-role" required>
                            <option value="1">ADMINISTRADOR</option>
                            <option value="2">DOCENTE</option>
                            <option value="3">ESTUDIANTE</option>
                        </select>
                    </div>

                    <!-- Tipo de documento -->
                    <div class="space-y-xs">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block"
                            for="form-doc-type">Tipo de documento</label>
                        <select
                            class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-student-accent/20 focus:border-student-accent outline-none transition-all font-body-sm text-body-sm"
                            id="form-doc-type" required>
                            <option value="DNI">DNI</option>
                            <option value="RUC">RUC</option>
                            <option value="CE">CE</option>
                        </select>
                    </div>

                    <!-- Documento de identidad -->
                    <div class="space-y-xs">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block"
                            for="form-doc-number">Documento de identidad</label>
                        <input
                            class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-student-accent/20 focus:border-student-accent outline-none transition-all font-body-sm text-body-sm"
                            id="form-doc-number" placeholder="Ej. 48293041" type="text" required />
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-md pt-lg border-t border-outline-variant">
                    <button type="button" onclick="toggleCreateModal()"
                        class="px-6 py-2.5 border border-student-accent text-student-accent font-label-md text-label-md rounded-xl hover:bg-student-accent-light transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 bg-student-accent text-on-primary font-label-md text-label-md rounded-xl hover:opacity-95 shadow-sm transition-all font-semibold">
                        Agregar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODAL: IMPORTACIÓN DE USUARIOS ================= -->
    <div id="import-users-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/40 p-4">
        <div
            class="w-full max-w-2xl bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-xl overflow-hidden transform scale-95 transition-transform duration-300">
            <!-- Header -->
            <div
                class="flex justify-between items-center px-lg py-md border-b border-outline-variant bg-surface-container-low">
                <h2 class="text-headline-md font-headline-md text-on-surface">Importación de usuarios</h2>
                <button onclick="toggleImportModal()"
                    class="text-on-surface-variant hover:bg-surface-container-high p-1 rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <!-- Content -->
            <div class="p-lg space-y-lg">
                <div class="flex items-start gap-md bg-primary-fixed/30 p-md rounded-xl text-on-primary-fixed-variant">
                    <span class="material-symbols-outlined text-primary text-3xl">info</span>
                    <div class="space-y-1">
                        <p class="font-bold text-body-sm leading-none text-primary">Instrucciones</p>
                        <p class="text-body-sm">Descarga la plantilla oficial, completa la información de los usuarios
                            respetando las columnas e importa el archivo Excel.</p>
                    </div>
                </div>

                <!-- Download Template link -->
                <div class="flex justify-between items-center p-md border border-outline-variant rounded-xl bg-surface">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-green-700 text-3xl">description</span>
                        <div>
                            <p class="font-semibold text-body-sm leading-tight text-on-surface">Plantilla de
                                Importación</p>
                            <p class="text-[11px] text-on-surface-variant">Importacion de usuarios.xlsx</p>
                        </div>
                    </div>
                    <a href="/assets/Importacion de usuarios.xlsx" download
                        class="px-4 py-2 bg-secondary text-on-secondary rounded-lg font-label-sm text-label-sm hover:opacity-90 flex items-center gap-1 shadow-sm transition-all font-semibold">
                        <span class="material-symbols-outlined text-sm">download</span>
                        Descargar Plantilla
                    </a>
                </div>

                <!-- File dropzone -->
                <div id="dropzone" onclick="triggerFileInput()"
                    class="border-2 border-dashed border-outline-variant hover:border-primary rounded-2xl p-xl flex flex-col items-center justify-center text-center cursor-pointer bg-background group transition-colors">
                    <input type="file" id="excel-file-input" class="hidden" accept=".xlsx, .xls"
                        onchange="handleExcelFileSelect(this)">
                    <div
                        class="w-12 h-12 rounded-full bg-primary-fixed text-primary flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
                        <span class="material-symbols-outlined text-2xl">cloud_upload</span>
                    </div>
                    <h3 id="dropzone-text" class="font-bold text-body-sm text-on-surface mb-2">Selecciona o arrastra tu
                        archivo Excel</h3>
                    <p class="text-body-sm text-on-surface-variant">Solo archivos .xlsx o .xls</p>
                </div>

                <!-- Progress Screen (Loading progress bar in percentages) -->
                <div id="import-progress-container"
                    class="hidden space-y-md p-md bg-surface-container-low rounded-xl border border-outline-variant">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <span id="progress-spinner"
                                class="material-symbols-outlined animate-spin text-primary">autorenew</span>
                            <span id="progress-status" class="font-semibold text-body-sm text-on-surface">Procesando
                                archivo...</span>
                        </div>
                        <span id="progress-percent" class="font-bold text-headline-sm text-primary">0%</span>
                    </div>
                    <div
                        class="w-full bg-surface-container-highest rounded-full h-3 overflow-hidden border border-outline-variant">
                        <div id="progress-bar" class="bg-primary h-full w-0 transition-all duration-100 ease-out"></div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-md pt-lg border-t border-outline-variant">
                    <button type="button" id="import-cancel-btn" onclick="toggleImportModal()"
                        class="px-6 py-2.5 border border-primary text-primary font-label-md text-label-md rounded-xl hover:bg-primary-fixed transition-colors">
                        Cancelar
                    </button>
                    <button id="start-import-btn" onclick="startExcelImport()"
                        class="px-6 py-2.5 bg-primary text-on-primary font-label-md text-label-md rounded-xl hover:opacity-95 shadow-sm transition-all flex items-center gap-2 font-semibold"
                        disabled>
                        <span class="material-symbols-outlined text-[18px]">publish</span>
                        Importar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= MODAL: CAMBIO DE CONTRASEÑA ================= -->
    <!-- ================= MODAL: MI PERFIL ADMIN ================= -->
    <div id="admin-profile-modal"
        class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm p-3 md:p-6 overflow-y-auto">
        <div class="min-h-full flex items-center justify-center">
            <div id="admin-profile-dialog"
                class="w-full max-w-5xl bg-surface-container-lowest rounded-3xl border border-outline-variant shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300">
                <div
                    class="relative bg-gradient-to-r from-primary via-primary to-secondary text-on-primary px-6 md:px-8 pt-7 pb-24 overflow-hidden">
                    <div class="absolute -right-16 -top-20 w-64 h-64 rounded-full bg-white/10"></div>
                    <div class="absolute right-36 -bottom-24 w-48 h-48 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between gap-4">
                        <div>
                            <div
                                class="flex items-center gap-2 text-white/75 text-label-sm uppercase tracking-wider mb-2">
                                <span class="material-symbols-outlined text-[18px]">admin_panel_settings</span>Cuenta
                                administrativa
                            </div>
                            <h2 class="text-headline-lg font-headline-lg">Mi perfil</h2>
                            <p class="text-white/75 text-body-sm mt-1">Mantenga su identidad y acceso siempre
                                actualizados.</p>
                        </div>
                        <button type="button" onclick="toggleAdminProfileModal()"
                            class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors"
                            aria-label="Cerrar"><span class="material-symbols-outlined">close</span></button>
                    </div>
                </div>

                <div class="px-5 md:px-8 -mt-16 relative">
                    <div
                        class="bg-surface rounded-2xl border border-outline-variant shadow-lg p-4 md:p-5 flex flex-col md:flex-row md:items-center gap-5">
                        <div class="relative self-start md:self-auto">
                            <div id="admin-profile-avatar-preview"
                                class="w-28 h-28 rounded-2xl bg-primary text-on-primary border-4 border-surface shadow-md overflow-hidden flex items-center justify-center text-3xl font-bold">
                                @if ($adminAvatarUrl)<img src="{{ $adminAvatarUrl }}" alt="Foto de perfil"
                                class="w-full h-full object-cover">@else<span>{{ $adminInitials ?: 'AD' }}</span>@endif
                            </div>
                            <button type="button"
                                onclick="document.getElementById('admin-profile-avatar-input').click()"
                                class="absolute -right-2 -bottom-2 w-10 h-10 rounded-xl bg-secondary text-on-secondary shadow-lg flex items-center justify-center hover:scale-105 transition-transform"
                                title="Cambiar foto"><span
                                    class="material-symbols-outlined text-[20px]">photo_camera</span></button>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <h3 id="admin-profile-summary-name"
                                    class="text-headline-md font-headline-md text-on-surface">{{ $adminName }}</h3><span
                                    class="px-2.5 py-1 rounded-full bg-secondary/10 text-secondary text-[11px] font-bold uppercase tracking-wider">Administrador</span>
                            </div>
                            <p id="admin-profile-summary-email" class="text-body-sm text-on-surface-variant truncate">
                                {{ $adminEmail }}
                            </p>
                            <div class="flex flex-wrap gap-3 mt-3 text-[12px] text-on-surface-variant"><span
                                    class="flex items-center gap-1"><span
                                        class="material-symbols-outlined text-[17px] text-secondary">verified_user</span>Cuenta
                                    activa</span><span class="flex items-center gap-1"><span
                                        class="material-symbols-outlined text-[17px] text-secondary">shield_lock</span>Acceso
                                    protegido</span></div>
                        </div>
                        <div class="text-left md:text-right">
                            <p class="text-[11px] uppercase tracking-wider text-on-surface-variant font-semibold">
                                Miembro desde</p>
                            <p class="text-label-md font-semibold text-on-surface">
                                {{ $adminUser?->created_at ? $adminUser->created_at->format('d/m/Y') : 'Cuenta institucional' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="px-5 md:px-8 pt-6">
                    <div class="flex gap-2 border-b border-outline-variant overflow-x-auto">
                        <button id="admin-profile-tab-data" type="button" onclick="selectAdminProfileSection('data')"
                            class="admin-profile-tab flex items-center gap-2 px-4 py-3 border-b-2 border-primary text-primary font-semibold whitespace-nowrap"><span
                                class="material-symbols-outlined text-[20px]">badge</span>Informaci&oacute;n
                            personal</button>
                        <button id="admin-profile-tab-security" type="button"
                            onclick="selectAdminProfileSection('security')"
                            class="admin-profile-tab flex items-center gap-2 px-4 py-3 border-b-2 border-transparent text-on-surface-variant hover:text-primary font-semibold whitespace-nowrap"><span
                                class="material-symbols-outlined text-[20px]">lock</span>Seguridad</button>
                    </div>
                </div>

                <div class="p-5 md:p-8 pt-6 max-h-[58vh] overflow-y-auto">
                    <form id="admin-profile-form" onsubmit="handleAdminProfileSubmit(event)"
                        class="admin-profile-section space-y-6" enctype="multipart/form-data">
                        <input id="admin-profile-avatar-input" name="avatar" type="file"
                            accept="image/jpeg,image/png,image/webp" class="hidden" onchange="previewAdminAvatar(this)">
                        <div class="flex items-start gap-3 p-4 rounded-2xl bg-primary/5 border border-primary/10">
                            <span
                                class="w-10 h-10 rounded-xl bg-primary text-on-primary flex items-center justify-center shrink-0"><span
                                    class="material-symbols-outlined text-[21px]">person_edit</span></span>
                            <div>
                                <p class="font-semibold text-on-surface">Datos de identificaci&oacute;n</p>
                                <p class="text-body-sm text-on-surface-variant">Estos datos se mostrar&aacute;n en su
                                    cuenta administrativa.</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-2 space-y-1.5"><label for="admin-profile-names"
                                    class="text-label-sm font-semibold text-on-surface-variant">Nombres y
                                    apellidos</label><input id="admin-profile-names" name="names"
                                    value="{{ $adminPerson?->names }}"
                                    class="w-full px-4 py-3 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"
                                    required></div>
                            <div class="space-y-1.5"><label for="admin-profile-email"
                                    class="text-label-sm font-semibold text-on-surface-variant">Correo
                                    electr&oacute;nico</label><input id="admin-profile-email" name="email" type="email"
                                    value="{{ $adminEmail }}"
                                    class="w-full px-4 py-3 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"
                                    required></div>
                            <div class="space-y-1.5"><label for="admin-profile-phone"
                                    class="text-label-sm font-semibold text-on-surface-variant">Tel&eacute;fono</label><input
                                    id="admin-profile-phone" name="phone" value="{{ $adminPerson?->phone }}"
                                    maxlength="9"
                                    class="w-full px-4 py-3 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"
                                    placeholder="Ej. 999999999"></div>
                            <div class="space-y-1.5"><label for="admin-profile-document-type"
                                    class="text-label-sm font-semibold text-on-surface-variant">Tipo de
                                    documento</label><select id="admin-profile-document-type" name="document_type"
                                    class="w-full px-4 py-3 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"
                                    required>
                                    <option value="DNI" @selected(($adminPerson?->document_type ?? 'DNI') === 'DNI')>DNI
                                    </option>
                                    <option value="CE" @selected($adminPerson?->document_type === 'CE')>Carn&eacute; de
                                        extranjer&iacute;a</option>
                                    <option value="PASAPORTE" @selected($adminPerson?->document_type === 'PASAPORTE')>
                                        Pasaporte</option>
                                </select></div>
                            <div class="space-y-1.5"><label for="admin-profile-document-number"
                                    class="text-label-sm font-semibold text-on-surface-variant">N&uacute;mero de
                                    documento</label><input id="admin-profile-document-number" name="document_number"
                                    value="{{ $adminPerson?->document_number }}" maxlength="20"
                                    class="w-full px-4 py-3 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"
                                    required></div>
                            <div class="space-y-1.5"><label for="admin-profile-sex"
                                    class="text-label-sm font-semibold text-on-surface-variant">Sexo</label><select
                                    id="admin-profile-sex" name="sex"
                                    class="w-full px-4 py-3 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                    <option value="">Prefiero no indicar</option>
                                    <option value="M" @selected($adminPerson?->sex === 'M')>Masculino</option>
                                    <option value="F" @selected($adminPerson?->sex === 'F')>Femenino</option>
                                    <option value="O" @selected($adminPerson?->sex === 'O')>Otro</option>
                                </select></div>
                            <div class="space-y-1.5"><label for="admin-profile-birth-date"
                                    class="text-label-sm font-semibold text-on-surface-variant">Fecha de
                                    nacimiento</label><input id="admin-profile-birth-date" name="birth_date" type="date"
                                    value="{{ $adminPerson?->birth_date }}"
                                    class="w-full px-4 py-3 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                            </div>
                            <div class="space-y-1.5"><label for="admin-profile-language"
                                    class="text-label-sm font-semibold text-on-surface-variant">Idioma
                                    principal</label><input id="admin-profile-language" name="native_language"
                                    value="{{ $adminPerson?->native_language }}" maxlength="100"
                                    class="w-full px-4 py-3 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"
                                    placeholder="Ej. Espa&ntilde;ol"></div>
                        </div>
                        <div
                            class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-5 border-t border-outline-variant">
                            <button type="button" onclick="toggleAdminProfileModal()"
                                class="px-5 py-2.5 border border-outline-variant rounded-xl text-on-surface-variant hover:bg-surface-container-high font-semibold">Cerrar</button><button
                                id="admin-profile-save-button" type="submit"
                                class="px-5 py-2.5 bg-primary text-on-primary rounded-xl hover:opacity-90 shadow-sm font-semibold flex items-center justify-center gap-2"><span
                                    class="material-symbols-outlined text-[19px]">save</span>Guardar cambios</button>
                        </div>
                    </form>

                    <form id="admin-security-form" onsubmit="handleAdminPasswordSubmit(event)"
                        class="admin-profile-section hidden space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-[1fr_280px] gap-6">
                            <div class="space-y-5">
                                <div
                                    class="flex items-start gap-3 p-4 rounded-2xl bg-secondary/5 border border-secondary/15">
                                    <span
                                        class="w-10 h-10 rounded-xl bg-secondary text-on-secondary flex items-center justify-center shrink-0"><span
                                            class="material-symbols-outlined text-[21px]">key</span></span>
                                    <div>
                                        <p class="font-semibold text-on-surface">Cambiar contrase&ntilde;a</p>
                                        <p class="text-body-sm text-on-surface-variant">Confirme su contrase&ntilde;a
                                            actual antes de establecer una nueva.</p>
                                    </div>
                                </div>
                                <div class="space-y-1.5"><label for="admin-current-password"
                                        class="text-label-sm font-semibold text-on-surface-variant">Contrase&ntilde;a
                                        actual</label>
                                    <div class="relative"><input id="admin-current-password" name="current_password"
                                            type="password"
                                            class="w-full px-4 py-3 pr-12 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"
                                            required><button type="button"
                                            onclick="togglePasswordVisibility('admin-current-password', this)"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant"><span
                                                class="material-symbols-outlined text-[20px]">visibility</span></button>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="space-y-1.5"><label for="admin-new-password"
                                            class="text-label-sm font-semibold text-on-surface-variant">Nueva
                                            contrase&ntilde;a</label>
                                        <div class="relative"><input id="admin-new-password" name="password"
                                                type="password" minlength="8"
                                                class="w-full px-4 py-3 pr-12 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"
                                                oninput="updateAdminPasswordStrength(this.value)" required><button
                                                type="button"
                                                onclick="togglePasswordVisibility('admin-new-password', this)"
                                                class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant"><span
                                                    class="material-symbols-outlined text-[20px]">visibility</span></button>
                                        </div>
                                    </div>
                                    <div class="space-y-1.5"><label for="admin-confirm-password"
                                            class="text-label-sm font-semibold text-on-surface-variant">Confirmar
                                            contrase&ntilde;a</label>
                                        <div class="relative"><input id="admin-confirm-password"
                                                name="password_confirmation" type="password" minlength="8"
                                                class="w-full px-4 py-3 pr-12 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"
                                                required><button type="button"
                                                onclick="togglePasswordVisibility('admin-confirm-password', this)"
                                                class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant"><span
                                                    class="material-symbols-outlined text-[20px]">visibility</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-[11px] text-on-surface-variant mb-2">
                                        <span>Fortaleza de la contrase&ntilde;a</span><span
                                            id="admin-password-strength-label">Sin evaluar</span>
                                    </div>
                                    <div class="grid grid-cols-4 gap-2"><span
                                            class="admin-password-strength-bar h-1.5 rounded-full bg-surface-container-highest"></span><span
                                            class="admin-password-strength-bar h-1.5 rounded-full bg-surface-container-highest"></span><span
                                            class="admin-password-strength-bar h-1.5 rounded-full bg-surface-container-highest"></span><span
                                            class="admin-password-strength-bar h-1.5 rounded-full bg-surface-container-highest"></span>
                                    </div>
                                </div>
                            </div>
                            <aside class="p-5 rounded-2xl bg-surface-container-low border border-outline-variant h-fit">
                                <span
                                    class="w-11 h-11 rounded-xl bg-primary/10 text-primary flex items-center justify-center mb-4"><span
                                        class="material-symbols-outlined">security</span></span>
                                <h4 class="font-semibold text-on-surface">Una contrase&ntilde;a fuerte</h4>
                                <ul class="mt-3 space-y-2 text-body-sm text-on-surface-variant">
                                    <li class="flex gap-2"><span class="text-secondary">&bull;</span>M&iacute;nimo 8
                                        caracteres</li>
                                    <li class="flex gap-2"><span class="text-secondary">&bull;</span>Combine
                                        may&uacute;sculas y min&uacute;sculas</li>
                                    <li class="flex gap-2"><span class="text-secondary">&bull;</span>Incluya
                                        n&uacute;meros y s&iacute;mbolos</li>
                                    <li class="flex gap-2"><span class="text-secondary">&bull;</span>No reutilice
                                        contrase&ntilde;as</li>
                                </ul>
                            </aside>
                        </div>
                        <div
                            class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-5 border-t border-outline-variant">
                            <button type="button" onclick="toggleAdminProfileModal()"
                                class="px-5 py-2.5 border border-outline-variant rounded-xl text-on-surface-variant hover:bg-surface-container-high font-semibold">Cerrar</button><button
                                id="admin-password-save-button" type="submit"
                                class="px-5 py-2.5 bg-secondary text-on-secondary rounded-xl hover:opacity-90 shadow-sm font-semibold flex items-center justify-center gap-2"><span
                                    class="material-symbols-outlined text-[19px]">lock_reset</span>Actualizar
                                contrase&ntilde;a</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="change-password-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/40 p-4">
        <div
            class="w-full max-w-md bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-xl overflow-hidden transform scale-95 transition-transform duration-300">
            <!-- Header -->
            <div
                class="flex justify-between items-center px-lg py-md border-b border-outline-variant bg-surface-container-low">
                <h2 class="text-headline-md font-headline-md text-on-surface">Cambiar contraseña</h2>
                <button onclick="toggleChangePasswordModal()"
                    class="text-on-surface-variant hover:bg-surface-container-high p-1 rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <!-- Form -->
            <form id="change-password-form" onsubmit="handleChangePasswordSubmit(event)" class="p-lg space-y-lg">
                <input type="hidden" id="change-pass-user-id" value="">
                <div class="space-y-md">
                    <p class="text-body-sm text-on-surface-variant">Establece una nueva contraseña para <span
                            id="change-pass-username" class="font-semibold text-on-surface"></span>.</p>

                    <div class="space-y-xs">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block"
                            for="new-password">Nueva Contraseña</label>
                        <input
                            class="w-full px-4 py-2.5 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-student-accent/20 focus:border-student-accent outline-none transition-all font-body-sm text-body-sm"
                            id="new-password" placeholder="Mínimo 6 caracteres" type="password" required />
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-md pt-lg border-t border-outline-variant">
                    <button type="button" onclick="toggleChangePasswordModal()"
                        class="px-6 py-2.5 border border-student-accent text-student-accent font-label-md text-label-md rounded-xl hover:bg-student-accent-light transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 bg-student-accent text-on-primary font-label-md text-label-md rounded-xl hover:opacity-95 shadow-sm transition-all font-semibold">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de confirmación personalizado para Borrado Masivo de Ofertas -->
    <div id="confirm-bulk-delete-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/40 p-4">
        <div class="w-full max-w-md bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-xl overflow-hidden transform scale-95 transition-transform duration-300">
            <!-- Header -->
            <div class="flex justify-between items-center px-lg py-md border-b border-outline-variant bg-red-50/50">
                <div class="flex items-center gap-2 text-red-700">
                    <span class="material-symbols-outlined text-2xl font-bold">warning</span>
                    <h2 class="text-headline-md font-headline-md font-bold">¿Eliminar ofertas?</h2>
                </div>
                <button onclick="toggleConfirmBulkDeleteModal(false)"
                    class="text-on-surface-variant hover:bg-surface-container-high p-1 rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <!-- Content -->
            <div class="p-lg space-y-md">
                <p class="text-body-md text-on-surface-variant">
                    ¿Está seguro de que desea eliminar permanentemente las <span id="bulk-delete-count" class="font-bold text-red-700">0</span> ofertas seleccionadas?
                </p>
                <div class="bg-red-50/50 border border-red-200/65 rounded-xl p-md flex gap-sm">
                    <span class="material-symbols-outlined text-red-600 text-2xl shrink-0">info</span>
                    <p class="text-[12px] text-red-800 leading-normal">Esta acción no se puede deshacer y también eliminará permanentemente todas las postulaciones asociadas a estas convocatorias.</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-md p-lg border-t border-outline-variant bg-surface-container-low">
                <button type="button" onclick="toggleConfirmBulkDeleteModal(false)"
                    class="px-6 py-2.5 border border-outline-variant text-on-surface font-label-md text-label-md rounded-xl hover:bg-surface-container-high transition-colors font-semibold">
                    Cancelar
                </button>
                <button id="btn-confirm-bulk-delete" type="button" onclick="executeBulkDeleteOffers()"
                    class="px-6 py-2.5 bg-red-600 text-white font-label-md text-label-md rounded-xl hover:bg-red-700 shadow-sm transition-all font-semibold flex items-center gap-1">
                    <span class="material-symbols-outlined text-[18px]">delete</span>
                    Confirmar
                </button>
            </div>
        </div>
    </div>

    <!-- Simple Toast Notification -->
    <div id="toast"
        class="fixed bottom-5 right-5 bg-primary text-on-primary px-lg py-md rounded-xl shadow-lg transform translate-y-20 opacity-0 transition-all duration-300 z-50 flex items-center gap-sm">
        <span class="material-symbols-outlined" id="toast-icon">check_circle</span>
        <span id="toast-message" class="text-body-sm font-semibold">¡Operación realizada con éxito!</span>
    </div>

    <script>
        const currentUserId = {{ auth()->id() }};
        let editingCompanyMode = false;
        const ADMIN_ACTIVE_TAB_KEY = 'talentum.admin.activeTab';
        const ADMIN_MAINTAINER_TYPE_KEY = 'talentum.admin.maintainerType';
        const ADMIN_VALID_TABS = ['dashboard', 'users', 'settings', 'offers', 'companies-manage', 'companies-register', 'applications', 'maintainers', 'support'];

        function getStoredAdminValue(key) {
            try {
                return localStorage.getItem(key);
            } catch (error) {
                return null;
            }
        }

        function storeAdminValue(key, value) {
            try {
                localStorage.setItem(key, value);
            } catch (error) {
                // El panel sigue funcionando aunque el navegador bloquee el almacenamiento.
            }
        }

        // Excel download triggered natively via back-end route redirection

        // Tab switching logic
        function switchTab(tabId) {
            if (!ADMIN_VALID_TABS.includes(tabId)) {
                tabId = 'dashboard';
            }

            storeAdminValue(ADMIN_ACTIVE_TAB_KEY, tabId);

            // If switching to a tab other than users, clear user filters from URL
            if (tabId !== 'users') {
                const url = new URL(window.location.href);
                if (url.searchParams.has('search') || url.searchParams.has('rol_id') || url.searchParams.has('page')) {
                    url.searchParams.delete('search');
                    url.searchParams.delete('rol_id');
                    url.searchParams.delete('page');
                    window.history.replaceState({}, '', url.toString());
                }
            }

            // Find all tabs and panels
            const tabBtns = document.querySelectorAll('.tab-btn');
            const panels = document.querySelectorAll('.tab-panel');
            const headerTitle = document.getElementById('header-page-title');

            // Hide all panels
            panels.forEach(p => p.classList.add('hidden'));

            // Determine target panel
            let targetPanelId = 'panel-placeholder';
            if (tabId === 'dashboard') {
                targetPanelId = 'panel-dashboard';
            } else if (tabId === 'users') {
                targetPanelId = 'panel-users';
            } else if (tabId === 'settings') {
                targetPanelId = 'panel-settings';
            } else if (tabId === 'offers') {
                targetPanelId = 'panel-offers';
                initOffersModule();
            } else if (tabId === 'companies-manage') {
                targetPanelId = 'panel-companies-manage';
                currentCompaniesPage = 1;
                document.getElementById('search-companies-input').value = '';
                selectedCompanyIds.clear();
                updateBulkDeleteButton();
                loadCompanies(1);
            } else if (tabId === 'companies-register') {
                targetPanelId = 'panel-companies-register';
                if (!editingCompanyMode) {
                    resetCompanyForm();
                }
                editingCompanyMode = false;
            } else if (tabId === 'applications') {
                targetPanelId = 'panel-applications';
                loadApplications();
            }
            else if (tabId === 'maintainers') {
                targetPanelId = 'panel-maintainers';
                initMaintainersModule();
            } else if (tabId === 'support') {
                targetPanelId = 'panel-support';
            }

            const targetPanel = document.getElementById(targetPanelId);
            if (targetPanel) {
                targetPanel.classList.remove('hidden');
            }

            // Reset button states
            tabBtns.forEach(btn => {
                const currentTabId = btn.getAttribute('data-tab');
                if (currentTabId === tabId) {
                    // Set active style
                    btn.className = "tab-btn w-full flex items-center gap-3 px-4 py-3 bg-secondary text-on-secondary rounded-lg scale-95 active:scale-90 transition-transform text-left shadow-sm font-semibold";
                } else {
                    // Set inactive style
                    btn.className = "tab-btn w-full flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:bg-surface-variant transition-colors duration-200 rounded-lg scale-95 active:scale-90 transition-transform text-left";
                }
            });

            // Update header title
            const tabTitles = {
                'dashboard': 'Panel de Control',
                'users': 'Gestión de Usuarios',
                'settings': 'Ajustes de Preferencias',
                'offers': 'Ofertas Laborales',
                'companies-manage': 'Gestionar Empresas',
                'companies-register': 'Registrar Empresa',
                'applications': 'Postulaciones Recibidas',
                'maintainers': 'Mantenimiento del Sistema',
                'support': 'Soporte'
            };
            if (headerTitle) {
                headerTitle.textContent = tabTitles[tabId] || 'Panel de Control';
            }

            // On mobile, close sidebar automatically
            if (window.innerWidth < 768) {
                const sidebar = document.getElementById('sidebar');
                const backdrop = document.getElementById('sidebar-backdrop');
                if (sidebar) sidebar.classList.add('-translate-x-full');
                if (backdrop) backdrop.classList.add('hidden');
            }
        }

        // Modal Create User Toggle
        function toggleCreateModal() {
            const modal = document.getElementById('create-user-modal');
            const modalContainer = modal.querySelector('.max-w-4xl');

            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                setTimeout(() => modalContainer.classList.remove('scale-95'), 10);
            } else {
                modalContainer.classList.add('scale-95');
                setTimeout(() => modal.classList.add('hidden'), 300);
            }
        }

        // Open User Modal for Creation
        function openCreateUserModal() {
            document.getElementById('create-user-form').reset();
            document.getElementById('form-user-id').value = '';

            const modal = document.getElementById('create-user-modal');
            modal.querySelector('h2').textContent = 'Creación de usuario';
            modal.querySelector('button[type="submit"]').textContent = 'Agregar';

            if (modal.classList.contains('hidden')) {
                toggleCreateModal();
            }
        }

        // Open User Modal for Editing
        function editUser(id, names, email, phone, roleId, docType, docNumber) {
            document.getElementById('form-user-id').value = id;
            document.getElementById('form-names').value = names;
            document.getElementById('form-email').value = email;
            document.getElementById('form-phone').value = phone;
            document.getElementById('form-role').value = roleId;
            document.getElementById('form-doc-type').value = docType;
            document.getElementById('form-doc-number').value = docNumber;

            const modal = document.getElementById('create-user-modal');
            modal.querySelector('h2').textContent = 'Modificar usuario';
            modal.querySelector('button[type="submit"]').textContent = 'Guardar';

            if (modal.classList.contains('hidden')) {
                toggleCreateModal();
            }
        }

        // Modal Import Toggle
        function toggleImportModal() {
            const modal = document.getElementById('import-users-modal');
            const modalContainer = modal.querySelector('.max-w-2xl');

            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                setTimeout(() => modalContainer.classList.remove('scale-95'), 10);
                resetImportForm();
            } else {
                modalContainer.classList.add('scale-95');
                setTimeout(() => modal.classList.add('hidden'), 300);
            }
        }

        // Trigger File Input Click
        function triggerFileInput() {
            document.getElementById('excel-file-input').click();
        }

        // Handle File Selection
        function handleExcelFileSelect(input) {
            const file = input.files[0];
            const dropzoneText = document.getElementById('dropzone-text');
            const importBtn = document.getElementById('start-import-btn');

            if (file) {
                dropzoneText.innerHTML = `Archivo seleccionado:<br><span class="text-primary font-semibold">${file.name}</span>`;
                importBtn.removeAttribute('disabled');
            } else {
                dropzoneText.textContent = 'Selecciona o arrastra tu archivo Excel';
                importBtn.setAttribute('disabled', 'true');
            }
        }

        // Reset Import Modal State
        function resetImportForm() {
            document.getElementById('excel-file-input').value = '';
            document.getElementById('dropzone-text').textContent = 'Selecciona o arrastra tu archivo Excel';
            document.getElementById('start-import-btn').setAttribute('disabled', 'true');
            document.getElementById('import-progress-container').classList.add('hidden');
            document.getElementById('dropzone').classList.remove('hidden');

            // Reset progress bar elements
            document.getElementById('progress-bar').style.width = '0%';
            document.getElementById('progress-percent').textContent = '0%';
            document.getElementById('progress-status').textContent = 'Procesando archivo...';
            document.getElementById('progress-spinner').className = 'material-symbols-outlined animate-spin text-primary';
            document.getElementById('progress-spinner').textContent = 'autorenew';

            document.getElementById('import-cancel-btn').removeAttribute('disabled');
        }

        // Excel Import with real progress and processing percentages
        function startExcelImport() {
            const fileInput = document.getElementById('excel-file-input');
            const file = fileInput.files[0];
            if (!file) return;

            const dropzone = document.getElementById('dropzone');
            const progressContainer = document.getElementById('import-progress-container');
            const importBtn = document.getElementById('start-import-btn');
            const cancelBtn = document.getElementById('import-cancel-btn');

            dropzone.classList.add('hidden');
            progressContainer.classList.remove('hidden');
            importBtn.setAttribute('disabled', 'true');
            cancelBtn.setAttribute('disabled', 'true');

            const progressBar = document.getElementById('progress-bar');
            const progressPercent = document.getElementById('progress-percent');
            const progressStatus = document.getElementById('progress-status');
            const progressSpinner = document.getElementById('progress-spinner');

            const formData = new FormData();
            formData.append('file', file);

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/admin/users/import', true);

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            xhr.setRequestHeader('X-CSRF-TOKEN', token);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            let currentPercent = 0;
            let targetPercent = 0;

            const smoothInterval = setInterval(() => {
                if (currentPercent < targetPercent) {
                    currentPercent += 1;
                    progressBar.style.width = currentPercent + '%';
                    progressPercent.textContent = currentPercent + '%';
                }
            }, 15);

            xhr.upload.onprogress = function (e) {
                if (e.lengthComputable) {
                    const uploadPercent = Math.round((e.loaded / e.total) * 50);
                    targetPercent = Math.max(targetPercent, uploadPercent);
                    progressStatus.textContent = 'Subiendo archivo Excel...';
                }
            };

            xhr.onload = function () {
                clearInterval(smoothInterval);

                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        let finalizePercent = currentPercent;
                        const finalInterval = setInterval(() => {
                            finalizePercent += 4;
                            if (finalizePercent >= 100) {
                                finalizePercent = 100;
                                clearInterval(finalInterval);

                                progressBar.style.width = '100%';
                                progressPercent.textContent = '100%';
                                progressStatus.textContent = '¡Importación completada con éxito!';

                                progressSpinner.className = 'material-symbols-outlined text-green-600';
                                progressSpinner.textContent = 'check_circle';

                                showToast(response.message || '¡Usuarios importados exitosamente!');

                                if (response.users && response.users.length > 0) {
                                    response.users.forEach(user => {
                                        const displayName = user.person ? user.person.names : (user.company ? user.company.name : 'Usuario');
                                        const docType = user.person ? user.person.document_type : 'RUC';
                                        const docNum = user.person ? user.person.document_number : (user.company ? user.company.ruc : '-');

                                        let roleName = 'ESTUDIANTE';
                                        if (user.rol_id == 1) roleName = 'ADMINISTRADOR';
                                        else if (user.rol_id == 2) roleName = 'DOCENTE';
                                        else if (user.rol_id == 4) roleName = 'EMPRESA';

                                        const phoneNum = user.person ? (user.person.phone || '') : (user.company ? (user.company.phone || '') : '');

                                        addNewUserToTable(user.id, displayName, user.email, docType, docNum, user.rol_id, roleName, user.is_active, phoneNum);
                                    });
                                }

                                setTimeout(() => {
                                    toggleImportModal();
                                }, 1500);
                            } else {
                                progressBar.style.width = finalizePercent + '%';
                                progressPercent.textContent = finalizePercent + '%';
                                progressStatus.textContent = 'Registrando nuevos usuarios en la BD...';
                            }
                        }, 40);
                    } else {
                        showImportError(response.message || 'Error en la importación.');
                    }
                } else {
                    let errMsg = 'Error en el servidor.';
                    try {
                        const response = JSON.parse(xhr.responseText);
                        errMsg = response.message || errMsg;
                    } catch (e) { }
                    showImportError(errMsg);
                }
            };

            xhr.onerror = function () {
                clearInterval(smoothInterval);
                showImportError('Error de red durante la importación.');
            };

            // Simulate database insertion progress from 50% to 85% while waiting for PHP response
            setTimeout(() => {
                if (xhr.readyState < 4) {
                    targetPercent = 85;
                    progressStatus.textContent = 'Procesando registros en la base de datos...';
                }
            }, 800);

            xhr.send(formData);

            function showImportError(msg) {
                progressBar.style.width = '0%';
                progressPercent.textContent = '0%';
                progressStatus.textContent = msg;
                progressStatus.className = 'font-semibold text-body-sm text-red-600';
                progressSpinner.className = 'material-symbols-outlined text-red-600';
                progressSpinner.textContent = 'error';

                cancelBtn.removeAttribute('disabled');
            }
        }

        // Helper to generate the row HTML
        function renderUserRowHTML(id, displayName, email, docType, docNum, roleId, roleName, isActive, phone) {
            let roleBadge = 'bg-surface-container text-on-surface-variant';
            if (roleId == 1) {
                roleBadge = 'bg-primary-fixed text-primary';
            } else if (roleId == 2) {
                roleBadge = 'bg-tertiary-fixed text-on-tertiary-fixed-variant';
            } else if (roleId == 3) {
                roleBadge = 'bg-student-accent-light text-student-accent';
            } else if (roleId == 4) {
                roleBadge = 'bg-secondary-fixed/50 text-on-secondary-container';
            }

            const escName = displayName.replace(/'/g, "\\'").replace(/"/g, '&quot;');
            const escEmail = email.replace(/'/g, "\\'").replace(/"/g, '&quot;');
            const escPhone = phone.replace(/'/g, "\\'").replace(/"/g, '&quot;');
            const escDocType = docType.replace(/'/g, "\\'").replace(/"/g, '&quot;');
            const escDocNum = docNum.replace(/'/g, "\\'").replace(/"/g, '&quot;');

            const isAuthUser = (id == currentUserId);

            return `
            <td class="p-4 w-10">
                ${isAuthUser ? `
                    <span class="w-4 h-4 flex items-center justify-center" title="Este es tu usuario activo"></span>
                ` : `
                    <input type="checkbox"
                        class="user-checkbox w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary/20 bg-surface-container-lowest"
                        value="${id}" onchange="updateSelectedCount()">
                `}
            </td>
            <td class="p-4 flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-surface-container flex items-center justify-center text-primary font-bold text-label-md">
                    ${displayName.charAt(0).toUpperCase()}
                </div>
                <div>
                    <span class="font-medium text-on-background block leading-tight">${displayName}</span>
                    <span class="text-body-sm text-on-surface-variant text-[13px]">${email}</span>
                </div>
                ${isAuthUser ? `
                    <span class="ml-1 px-2 py-0.5 rounded-full bg-primary/10 text-primary text-[11px] font-bold tracking-wide shrink-0">Tú</span>
                ` : ''}
            </td>
            <td class="p-4 text-on-surface-variant">
                <span class="font-semibold text-body-sm">${docType}:</span> ${docNum}
            </td>
            <td class="p-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-label-sm font-label-sm font-semibold ${roleBadge}">
                    ${roleName}
                </span>
            </td>
            <td class="p-4">
                <!-- Switch enable/disable -->
                <label class="switch">
                    <input type="checkbox" ${isActive ? 'checked' : ''} onchange="toggleUserStatus(${id}, '${escName}', this)">
                    <span class="slider"></span>
                </label>
            </td>
            <td class="p-4 text-right space-x-1">
                <button onclick="editUser(${id}, '${escName}', '${escEmail}', '${escPhone}', ${roleId}, '${escDocType}', '${escDocNum}')" class="p-1.5 text-on-surface-variant hover:bg-surface-container rounded-lg transition-colors" title="Editar Usuario">
                    <span class="material-symbols-outlined text-[20px]">edit</span>
                </button>
                <button onclick="openChangePasswordModal(${id}, '${escName}')" class="p-1.5 text-on-surface-variant hover:bg-surface-container rounded-lg transition-colors" title="Cambiar Contraseña">
                    <span class="material-symbols-outlined text-[20px]">lock_reset</span>
                </button>
                ${isAuthUser ? `
                    <span class="p-1.5 text-outline-variant inline-flex" title="No puedes eliminar tu propio usuario">
                        <span class="material-symbols-outlined text-[20px]">shield_person</span>
                    </span>
                ` : `
                    <button onclick="deleteUserRow(${id}, '${escName}')" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Eliminar Usuario">
                        <span class="material-symbols-outlined text-[20px]">delete</span>
                    </button>
                `}
            </td>
        `;
        }

        // Prepend new user row
        function addNewUserToTable(id, displayName, email, docType, docNum, roleId, roleName, isActive, phone) {
            const body = document.getElementById('users-table-body');

            const existing = document.getElementById('user-row-' + id);
            if (existing) {
                existing.remove();
            }

            const tr = document.createElement('tr');
            tr.id = 'user-row-' + id;
            tr.className = 'border-b border-outline-variant hover:bg-surface-container-lowest transition-colors' + (id == currentUserId ? ' bg-primary-fixed/10' : '');
            tr.innerHTML = renderUserRowHTML(id, displayName, email, docType, docNum, roleId, roleName, isActive, phone);

            body.appendChild(tr);
        }

        // Update user row in table
        function updateUserRow(id, displayName, email, docType, docNum, roleId, roleName, isActive, phone) {
            const tr = document.getElementById('user-row-' + id);
            if (tr) {
                tr.className = 'border-b border-outline-variant hover:bg-surface-container-lowest transition-colors' + (id == currentUserId ? ' bg-primary-fixed/10' : '');
                tr.innerHTML = renderUserRowHTML(id, displayName, email, docType, docNum, roleId, roleName, isActive, phone);
            } else {
                addNewUserToTable(id, displayName, email, docType, docNum, roleId, roleName, isActive, phone);
            }
        }

        // Handle Create/Edit user form submit
        function handleCreateUserSubmit(event) {
            event.preventDefault();

            const id = document.getElementById('form-user-id').value;
            const names = document.getElementById('form-names').value;
            const email = document.getElementById('form-email').value;
            const phone = document.getElementById('form-phone').value;
            const roleSelect = document.getElementById('form-role');
            const roleId = roleSelect.value;
            const roleName = roleSelect.options[roleSelect.selectedIndex].text;
            const docType = document.getElementById('form-doc-type').value;
            const docNum = document.getElementById('form-doc-number').value;

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const url = id ? `/admin/users/${id}` : '/admin/users';
            const method = id ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    names: names,
                    email: email,
                    phone: phone,
                    role_id: roleId,
                    doc_type: docType,
                    doc_number: docNum
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const user = data.user;
                        if (id) {
                            updateUserRow(user.id, names, email, docType, docNum, roleId, roleName, user.is_active, phone);
                            showToast(`¡Usuario "${names}" modificado con éxito!`);
                        } else {
                            addNewUserToTable(user.id, names, email, docType, docNum, roleId, roleName, user.is_active, phone);
                            showToast(`¡Usuario "${names}" agregado con éxito!`);
                        }
                        toggleCreateModal();
                        document.getElementById('create-user-form').reset();
                    } else {
                        showToast(data.message || 'Error al guardar el usuario.', 'error');
                    }
                })
                .catch(err => {
                    showToast('Error de red al intentar guardar.', 'error');
                });
        }

        // Toggle user active status in the database
        function toggleUserStatus(id, username, checkbox) {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/admin/users/${id}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const state = data.is_active ? 'habilitado' : 'deshabilitado';
                        showToast(`Usuario "${username}" ha sido ${state}.`);
                    } else {
                        checkbox.checked = !checkbox.checked;
                        showToast(data.message || 'Error al cambiar estado.', 'error');
                    }
                })
                .catch(err => {
                    checkbox.checked = !checkbox.checked;
                    showToast('Error de red al cambiar estado.', 'error');
                });
        }

        // Delete user from the database
        function deleteUserRow(id, username) {
            if (confirm(`¿Estás seguro de que deseas eliminar permanentemente al usuario "${username}"?`)) {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/admin/users/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const row = document.getElementById('user-row-' + id);
                            if (row) row.remove();
                            showToast(`Usuario "${username}" eliminado.`);
                        } else {
                            showToast(data.message || 'Error al eliminar usuario.', 'error');
                        }
                    })
                    .catch(err => {
                        showToast('Error de red al eliminar usuario.', 'error');
                    });
            }
        }

        // ================= FILTRO POR ROL, SELECCIÓN MÚLTIPLE Y PAGINACIÓN =================
        // Advanced Search status and functions
        let currentStatusFilter = '{{ $currentStatus }}';

        function toggleAdvancedSearch() {
            const panel = document.getElementById('advanced-search-panel');
            const toggleBtn = document.getElementById('advanced-search-toggle');
            if (!panel || !toggleBtn) return;

            const isHidden = panel.classList.contains('hidden');
            if (isHidden) {
                panel.classList.remove('hidden');
                panel.setAttribute('aria-hidden', 'false');
                toggleBtn.classList.add('bg-primary/5', 'border-primary', 'text-primary');
                toggleBtn.classList.remove('border-outline-variant', 'text-on-surface-variant');
            } else {
                panel.classList.add('hidden');
                panel.setAttribute('aria-hidden', 'true');
                toggleBtn.classList.remove('bg-primary/5', 'border-primary', 'text-primary');
                toggleBtn.classList.add('border-outline-variant', 'text-on-surface-variant');
            }
        }

        function setStatus(statusVal) {
            currentStatusFilter = statusVal;
            
            // Update button styles on the client side
            const btnAll = document.querySelector('button[onclick^="setStatus(\'\')"]');
            const btnActive = document.querySelector('button[onclick^="setStatus(\'active\')"]');
            const btnInactive = document.querySelector('button[onclick^="setStatus(\'inactive\')"]');

            // Reset all styles
            if (btnAll) {
                btnAll.className = "px-3.5 py-1.5 rounded-full border text-xs font-semibold transition-all bg-surface border-outline-variant text-on-surface-variant hover:bg-surface-container-high";
            }
            if (btnActive) {
                btnActive.className = "px-3.5 py-1.5 rounded-full border text-xs font-semibold transition-all flex items-center gap-1.5 bg-surface border-outline-variant text-on-surface-variant hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-300";
            }
            if (btnInactive) {
                btnInactive.className = "px-3.5 py-1.5 rounded-full border text-xs font-semibold transition-all flex items-center gap-1.5 bg-surface border-outline-variant text-on-surface-variant hover:bg-red-50 hover:text-red-700 hover:border-red-300";
            }

            // Set active style for selected button
            if (statusVal === '' && btnAll) {
                btnAll.className = "px-3.5 py-1.5 rounded-full border text-xs font-semibold transition-all bg-on-surface text-surface border-on-surface";
            } else if (statusVal === 'active' && btnActive) {
                btnActive.className = "px-3.5 py-1.5 rounded-full border text-xs font-semibold transition-all flex items-center gap-1.5 bg-emerald-600 text-white border-emerald-600";
            } else if (statusVal === 'inactive' && btnInactive) {
                btnInactive.className = "px-3.5 py-1.5 rounded-full border text-xs font-semibold transition-all flex items-center gap-1.5 bg-red-600 text-white border-red-600";
            }
        }

        // Filtro de usuarios por búsqueda (se activa con Enter o botón Buscar)
        function filterUsers() {
            const url = new URL(window.location.href);
            const searchInput = document.getElementById('search-users-input');
            const searchVal = searchInput ? searchInput.value.trim() : '';

            if (searchVal) {
                url.searchParams.set('search', searchVal);
            } else {
                url.searchParams.delete('search');
            }

            if (currentStatusFilter) {
                url.searchParams.set('status', currentStatusFilter);
            } else {
                url.searchParams.delete('status');
            }

            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        }

        // Filtro por rol (se activa al pulsar un Pill)
        function filterByRole(rolId) {
            const url = new URL(window.location.href);
            if (rolId !== '') {
                url.searchParams.set('rol_id', rolId);
            } else {
                url.searchParams.delete('rol_id');
            }
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        }

        // Abrir/Cerrar el dropdown de roles
        function toggleRoleDropdown() {
            const dropdown = document.getElementById('role-select-dropdown');
            const trigger = document.getElementById('role-select-trigger');
            if (!dropdown || !trigger) return;

            const isOpen = !dropdown.classList.contains('hidden');
            document.querySelectorAll('[id$="-select-dropdown"]').forEach(d => {
                if (d !== dropdown) d.classList.add('hidden');
            });
            document.querySelectorAll('[id$="-select-trigger"]').forEach(t => {
                if (t !== trigger) t.classList.remove('open');
            });

            if (isOpen) {
                dropdown.classList.add('hidden');
                trigger.classList.remove('open');
                trigger.setAttribute('aria-expanded', 'false');
            } else {
                dropdown.classList.remove('hidden');
                trigger.classList.add('open');
                trigger.setAttribute('aria-expanded', 'true');
            }
        }

        // Seleccionar un rol desde el dropdown y aplicar el filtro
        function selectRole(rolId) {
            const trigger = document.getElementById('role-select-trigger');
            const label = document.getElementById('role-select-label');
            const dropdown = document.getElementById('role-select-dropdown');

            const roleOptions = {
                '': { label: 'Todos los roles', icon: 'group', color: 'text-on-surface-variant' },
                '1': { label: 'Administradores', icon: 'admin_panel_settings', color: 'text-blue-600' },
                '2': { label: 'Docentes', icon: 'school', color: 'text-amber-600' },
                '3': { label: 'Estudiantes', icon: 'grade', color: 'text-student-accent' },
                '4': { label: 'Empresas', icon: 'corporate_fare', color: 'text-emerald-600' },
            };

            const selected = roleOptions[String(rolId)] || roleOptions[''];
            if (label) label.textContent = selected.label;

            if (trigger) {
                const iconSpan = trigger.querySelector('span.material-symbols-outlined');
                if (iconSpan) {
                    iconSpan.className = 'material-symbols-outlined text-[18px] ' + selected.color;
                    iconSpan.textContent = selected.icon;
                }
            }

            if (dropdown) {
                const buttons = dropdown.querySelectorAll('button[role="option"]');
                buttons.forEach(btn => {
                    const onclickAttr = btn.getAttribute('onclick') || '';
                    const isMatch = onclickAttr.includes("'" + rolId + "'");
                    btn.classList.remove('bg-primary/5', 'text-primary', 'font-semibold');
                    btn.classList.add('text-on-surface');
                    btn.setAttribute('aria-selected', 'false');
                    const existingCheck = btn.querySelector('.role-check-icon');
                    if (existingCheck) existingCheck.remove();
                    const optIcon = btn.querySelector('span.material-symbols-outlined');
                    if (optIcon) {
                        optIcon.classList.remove('text-primary', 'text-on-surface-variant', 'text-blue-600', 'text-amber-600', 'text-student-accent', 'text-emerald-600');
                        optIcon.classList.add(selected.color);
                    }
                    if (isMatch) {
                        btn.classList.add('bg-primary/5', 'text-primary', 'font-semibold');
                        btn.classList.remove('text-on-surface');
                        btn.setAttribute('aria-selected', 'true');
                        if (optIcon) {
                            optIcon.classList.remove('text-on-surface-variant', 'text-blue-600', 'text-amber-600', 'text-student-accent', 'text-emerald-600');
                            optIcon.classList.add('text-primary');
                        }
                        const check = document.createElement('span');
                        check.className = 'material-symbols-outlined text-[16px] text-primary role-check-icon';
                        check.textContent = 'check';
                        btn.appendChild(check);
                    }
                });
            }

            if (dropdown) dropdown.classList.add('hidden');
            if (trigger) {
                trigger.classList.remove('open');
                trigger.setAttribute('aria-expanded', 'false');
            }

            filterByRole(rolId);
        }

        // Cerrar dropdown al hacer clic fuera
        document.addEventListener('click', function (e) {
            const wrapper = document.getElementById('role-select-wrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                const dropdown = document.getElementById('role-select-dropdown');
                const trigger = document.getElementById('role-select-trigger');
                if (dropdown && !dropdown.classList.contains('hidden')) {
                    dropdown.classList.add('hidden');
                }
                if (trigger) {
                    trigger.classList.remove('open');
                    trigger.setAttribute('aria-expanded', 'false');
                }
            }
        });

        // Limpiar campo de búsqueda
        function clearSearch() {
            const searchInput = document.getElementById('search-users-input');
            if (searchInput) searchInput.value = '';
            const url = new URL(window.location.href);
            url.searchParams.delete('search');
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        }

        // Limpiar todos los filtros (Empty state)
        function clearAllFilters() {
            const url = new URL(window.location.href);
            url.searchParams.delete('search');
            url.searchParams.delete('rol_id');
            url.searchParams.delete('status');
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        }

        // Seleccionar/Deseleccionar todos los checkboxes
        function toggleSelectAllUsers(masterCheckbox) {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(cb => cb.checked = masterCheckbox.checked);
            updateSelectedCount();
        }

        // Actualizar contador de seleccionados y mostrar/ocultar barra de acciones flotante
        function updateSelectedCount() {
            const checkboxes = document.querySelectorAll('.user-checkbox:checked');
            const count = checkboxes.length;
            const bar = document.getElementById('bulk-actions-bar');
            const label = document.getElementById('selected-count-label');

            if (count > 0) {
                if (bar) {
                    if (bar.classList.contains('hidden')) {
                        bar.classList.remove('hidden');
                        // Forzar reflow
                        bar.offsetHeight;
                    }
                    bar.classList.remove('translate-y-10', 'opacity-0', 'scale-95');
                    bar.classList.add('translate-y-0', 'opacity-100', 'scale-100');
                }
                if (label) {
                    label.textContent = count + ' seleccionado' + (count > 1 ? 's' : '');
                }
            } else {
                if (bar) {
                    bar.classList.remove('translate-y-0', 'opacity-100', 'scale-100');
                    bar.classList.add('translate-y-10', 'opacity-0', 'scale-95');

                    if (bar.timeoutId) clearTimeout(bar.timeoutId);
                    bar.timeoutId = setTimeout(() => {
                        if (!bar.classList.contains('opacity-100')) {
                            bar.classList.add('hidden');
                        }
                    }, 300);
                }
            }

            // Actualizar checkbox maestro
            const masterCheckbox = document.getElementById('select-all-users');
            const totalCheckboxes = document.querySelectorAll('.user-checkbox');
            if (masterCheckbox) {
                masterCheckbox.checked = count === totalCheckboxes.length && totalCheckboxes.length > 0;
                masterCheckbox.indeterminate = count > 0 && count < totalCheckboxes.length;
            }
        }

        // Limpiar selección
        function clearUserSelection() {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(cb => cb.checked = false);
            updateSelectedCount();
        }

        // Eliminar usuarios seleccionados
        function deleteSelectedUsers() {
            const checkboxes = document.querySelectorAll('.user-checkbox:checked');
            const ids = Array.from(checkboxes).map(cb => parseInt(cb.value));

            if (ids.length === 0) {
                showToast('No hay usuarios seleccionados.', 'warning');
                return;
            }

            if (!confirm(`¿Estás seguro de que deseas eliminar ${ids.length} usuario(s) seleccionado(s)?`)) {
                return;
            }

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/admin/users/bulk-delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ ids: ids })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message);
                        // Remove only the IDs confirmed deleted by the server
                        const deletedIds = data.deleted_ids || ids;
                        deletedIds.forEach(id => {
                            const row = document.getElementById('user-row-' + id);
                            if (row) row.remove();
                        });
                        clearUserSelection();
                        // Reload page after a short delay to reflect changes
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showToast(data.message || 'Error al eliminar usuarios.', 'error');
                    }
                })
                .catch(err => {
                    showToast('Error de red al eliminar usuarios.', 'error');
                });
        }

        // ================= ADMIN ACCOUNT & PROFILE =================
        function closeAdminHeaderMenus() {
            document.querySelectorAll('.admin-header-menu').forEach(menu => menu.classList.add('hidden'));
        }

        function toggleAdminHeaderMenu(type) {
            const target = document.getElementById(`admin-${type}-menu`);
            if (!target) return;
            const shouldOpen = target.classList.contains('hidden');
            closeAdminHeaderMenus();
            if (shouldOpen) target.classList.remove('hidden');
        }

        function openAdminProfileModal() {
            closeAdminHeaderMenus();
            selectAdminProfileSection('data');
            toggleAdminProfileModal(true);
        }

        function toggleAdminProfileModal(forceOpen = null) {
            const modal = document.getElementById('admin-profile-modal');
            const dialog = document.getElementById('admin-profile-dialog');
            const shouldOpen = forceOpen === null ? modal.classList.contains('hidden') : forceOpen;

            if (shouldOpen) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
                requestAnimationFrame(() => {
                    dialog.classList.remove('scale-95', 'opacity-0');
                    dialog.classList.add('scale-100', 'opacity-100');
                });
            } else {
                dialog.classList.remove('scale-100', 'opacity-100');
                dialog.classList.add('scale-95', 'opacity-0');
                document.body.classList.remove('overflow-hidden');
                setTimeout(() => modal.classList.add('hidden'), 250);
            }
        }

        function selectAdminProfileSection(section) {
            const dataForm = document.getElementById('admin-profile-form');
            const securityForm = document.getElementById('admin-security-form');
            const dataTab = document.getElementById('admin-profile-tab-data');
            const securityTab = document.getElementById('admin-profile-tab-security');
            const showData = section === 'data';

            dataForm.classList.toggle('hidden', !showData);
            securityForm.classList.toggle('hidden', showData);

            [[dataTab, showData], [securityTab, !showData]].forEach(([tab, active]) => {
                tab.classList.toggle('border-primary', active);
                tab.classList.toggle('text-primary', active);
                tab.classList.toggle('border-transparent', !active);
                tab.classList.toggle('text-on-surface-variant', !active);
            });
        }

        function previewAdminAvatar(input) {
            const file = input.files && input.files[0];
            if (!file) return;

            if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
                input.value = '';
                showToast('Seleccione una imagen JPG, PNG o WEBP.', 'error');
                return;
            }

            if (file.size > 3 * 1024 * 1024) {
                input.value = '';
                showToast('La foto no puede superar los 3 MB.', 'error');
                return;
            }

            const reader = new FileReader();
            reader.onload = event => setAdminAvatarImages(event.target.result, true);
            reader.readAsDataURL(file);
        }

        function setAdminAvatarImages(url, previewOnly = false) {
            const targets = previewOnly
                ? [document.getElementById('admin-profile-avatar-preview')]
                : [
                    document.getElementById('admin-profile-avatar-preview'),
                    document.getElementById('admin-header-avatar'),
                    document.getElementById('admin-menu-avatar')
                ];

            targets.filter(Boolean).forEach(target => {
                target.innerHTML = `<img src="${url}" alt="Foto de perfil" class="w-full h-full object-cover">`;
            });
        }

        function handleAdminProfileSubmit(event) {
            event.preventDefault();
            const form = event.currentTarget;
            const button = document.getElementById('admin-profile-save-button');
            const originalHtml = button.innerHTML;
            const formData = new FormData(form);
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            button.disabled = true;
            button.innerHTML = '<span class="material-symbols-outlined text-[19px] animate-spin">progress_activity</span>Guardando...';

            fetch('/admin/profile', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok || !data.success) throw new Error(data.message || 'No se pudo actualizar el perfil.');
                    return data;
                })
                .then(data => {
                    const profile = data.profile;
                    document.getElementById('admin-header-name').textContent = profile.names;
                    document.getElementById('admin-menu-name').textContent = profile.names;
                    document.getElementById('admin-menu-email').textContent = profile.email;
                    document.getElementById('admin-profile-summary-name').textContent = profile.names;
                    document.getElementById('admin-profile-summary-email').textContent = profile.email;
                    if (profile.avatar_url) setAdminAvatarImages(profile.avatar_url);
                    document.getElementById('admin-profile-avatar-input').value = '';
                    showToast(data.message || 'Perfil actualizado correctamente.');
                })
                .catch(error => showToast(error.message, 'error'))
                .finally(() => {
                    button.disabled = false;
                    button.innerHTML = originalHtml;
                });
        }

        function handleAdminPasswordSubmit(event) {
            event.preventDefault();
            const form = event.currentTarget;
            const button = document.getElementById('admin-password-save-button');
            const originalHtml = button.innerHTML;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const payload = Object.fromEntries(new FormData(form).entries());

            if (payload.password !== payload.password_confirmation) {
                showToast('La confirmaci?n no coincide con la nueva contrase?a.', 'error');
                return;
            }

            button.disabled = true;
            button.innerHTML = '<span class="material-symbols-outlined text-[19px] animate-spin">progress_activity</span>Actualizando...';

            fetch('/admin/profile/password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload)
            })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok || !data.success) throw new Error(data.message || 'No se pudo actualizar la contrase?a.');
                    return data;
                })
                .then(data => {
                    form.reset();
                    updateAdminPasswordStrength('');
                    showToast(data.message || 'Contrase?a actualizada correctamente.');
                })
                .catch(error => showToast(error.message, 'error'))
                .finally(() => {
                    button.disabled = false;
                    button.innerHTML = originalHtml;
                });
        }

        function togglePasswordVisibility(inputId, button) {
            const input = document.getElementById(inputId);
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            button.querySelector('.material-symbols-outlined').textContent = show ? 'visibility_off' : 'visibility';
        }

        function updateAdminPasswordStrength(value) {
            let strength = 0;
            if (value.length >= 8) strength++;
            if (/[a-z]/.test(value) && /[A-Z]/.test(value)) strength++;
            if (/\d/.test(value)) strength++;
            if (/[^A-Za-z0-9]/.test(value)) strength++;

            const labels = ['Sin evaluar', 'D?bil', 'Aceptable', 'Buena', 'Fuerte'];
            const colors = ['bg-surface-container-highest', 'bg-red-500', 'bg-amber-500', 'bg-primary', 'bg-secondary'];
            document.getElementById('admin-password-strength-label').textContent = labels[strength];
            document.querySelectorAll('.admin-password-strength-bar').forEach((bar, index) => {
                bar.className = `admin-password-strength-bar h-1.5 rounded-full ${index < strength ? colors[strength] : 'bg-surface-container-highest'}`;
            });
        }

        document.addEventListener('click', event => {
            if (!event.target.closest('#admin-notifications-button') &&
                !event.target.closest('#admin-notifications-menu') &&
                !event.target.closest('#admin-account-button') &&
                !event.target.closest('#admin-account-menu')) {
                closeAdminHeaderMenus();
            }
        });
        document.addEventListener('keydown', event => {
            if (event.key === 'Escape') {
                closeAdminHeaderMenus();
                const modal = document.getElementById('admin-profile-modal');
                if (modal && !modal.classList.contains('hidden')) toggleAdminProfileModal(false);
            }
        });

        document.getElementById('admin-profile-modal')?.addEventListener('click', event => {
            if (event.target.id === 'admin-profile-modal') toggleAdminProfileModal(false);
        });
        // Toggle Change Password Modal
        function toggleChangePasswordModal() {
            const modal = document.getElementById('change-password-modal');
            const modalContainer = modal.querySelector('.max-w-md');

            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                setTimeout(() => modalContainer.classList.remove('scale-95'), 10);
                document.getElementById('change-password-form').reset();
            } else {
                modalContainer.classList.add('scale-95');
                setTimeout(() => modal.classList.add('hidden'), 300);
            }
        }

        // Open Change Password Modal
        function openChangePasswordModal(id, username) {
            document.getElementById('change-pass-user-id').value = id;
            document.getElementById('change-pass-username').textContent = username;

            toggleChangePasswordModal();
        }

        // Handle Change Password Form Submission
        function handleChangePasswordSubmit(event) {
            event.preventDefault();

            const id = document.getElementById('change-pass-user-id').value;
            const password = document.getElementById('new-password').value;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/admin/users/${id}/change-password`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    password: password
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast('¡Contraseña restablecida exitosamente!');
                        toggleChangePasswordModal();
                    } else {
                        showToast(data.message || 'Error al cambiar la contraseña.', 'error');
                    }
                })
                .catch(err => {
                    showToast('Error de red al intentar restablecer contraseña.', 'error');
                });
        }

        // Select all file extension checkboxes
        function selectAllExtensions(event) {
            if (event) event.preventDefault();
            const container = document.getElementById('extensions-checkbox-container');
            if (container) {
                const checkboxes = container.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(cb => cb.checked = true);
            }
        }

        // Deselect all file extension checkboxes
        function deselectAllExtensions(event) {
            if (event) event.preventDefault();
            const container = document.getElementById('extensions-checkbox-container');
            if (container) {
                const checkboxes = container.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(cb => cb.checked = false);
            }
        }

        function softenColor(hex) {
            const cleanHex = String(hex || '').replace('#', '');
            if (!/^[0-9A-Fa-f]{6}$/.test(cleanHex)) return '#e8f5f2';

            const channels = [0, 2, 4].map(start => parseInt(cleanHex.slice(start, start + 2), 16));
            const softened = channels.map(channel => {
                const value = Math.round(channel + ((255 - channel) * 0.72));
                return value.toString(16).padStart(2, '0');
            });

            return `#${softened.join('')}`;
        }

        function applyThemeColor(target, hex) {
            const normalizedHex = String(hex || '').toLowerCase();
            const field = document.getElementById(`set-${target}-color`);
            const picker = document.getElementById(`set-${target}-color-picker`);
            const valueLabel = document.getElementById(`set-${target}-color-value`);
            const preview = document.getElementById('settings-theme-preview');

            if (field) field.value = normalizedHex;
            if (picker) picker.value = normalizedHex;
            if (valueLabel) valueLabel.textContent = normalizedHex.toUpperCase();
            if (preview) preview.style.setProperty(`--preview-${target}`, normalizedHex);

            if (target === 'primary') {
                document.documentElement.style.setProperty('--primary-color', normalizedHex);
                document.documentElement.style.setProperty('--primary-container-color', softenColor(normalizedHex));
            }

            if (target === 'secondary') {
                document.documentElement.style.setProperty('--secondary-color', normalizedHex);
                document.documentElement.style.setProperty('--secondary-container-color', softenColor(normalizedHex));
            }

            if (target === 'accent') {
                document.documentElement.style.setProperty('--accent-color', normalizedHex);
            }
        }

        function markSelectedThemePreset(btn) {
            const presetButtons = document.querySelectorAll('.theme-preset-btn');
            presetButtons.forEach(item => {
                item.classList.remove('border-primary', 'bg-primary-container');
                item.classList.add('border-outline-variant', 'bg-surface');
            });

            if (btn) {
                btn.classList.remove('border-outline-variant', 'bg-surface');
                btn.classList.add('border-primary', 'bg-primary-container');
            }
        }

        function selectThemePreset(primary, secondary, accent, btn) {
            applyThemeColor('primary', primary);
            applyThemeColor('secondary', secondary);
            applyThemeColor('accent', accent);
            markSelectedThemePreset(btn);
        }

        function syncCustomColor(input, target) {
            applyThemeColor(target, input.value);
            markSelectedThemePreset(null);
        }

        function updateThemePreview() {
            ['primary', 'secondary', 'accent'].forEach(target => {
                const field = document.getElementById(`set-${target}-color`);
                if (field) applyThemeColor(target, field.value);
            });
        }

        // Backward-compatible helper for the previous primary-only palette.
        function selectPrimaryColor(hex, btn) {
            applyThemeColor('primary', hex);
            markSelectedThemePreset(btn);
        }

        document.addEventListener('DOMContentLoaded', updateThemePreview);

        // Image upload preview helper
        function previewImage(input, previewId) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById(previewId).src = e.target.result;
                    
                    // Actualizar el favicon de la pestaña del navegador inmediatamente
                    if (previewId === 'preview-favicon') {
                        const link = document.querySelector("link[rel~='icon']");
                        if (link) link.href = e.target.result;
                    } else if (previewId === 'preview-logo') {
                        // Si no hay un favicon seleccionado explícitamente, usar el logo también como favicon
                        const faviconInput = document.querySelector('input[name="favicon"]');
                        if (faviconInput && !faviconInput.files.length) {
                            const link = document.querySelector("link[rel~='icon']");
                            if (link) link.href = e.target.result;
                        }
                    }
                };
                reader.readAsDataURL(file);
            }
        }

        // Handle AJAX settings form submit
        function handleSettingsSubmit(event) {
            event.preventDefault();

            const form = document.getElementById('settings-form');
            const formData = new FormData(form);
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/admin/settings', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast('¡Configuraciones actualizadas con éxito!');
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        showToast(data.message || 'Error al guardar configuraciones.', 'error');
                    }
                })
                .catch(err => {
                    showToast('Error de red al intentar guardar ajustes.', 'error');
                });
        }

        // Delete a settings image (logo, favicon, banner)
        function deleteSettingsImage(type, previewId) {
            if (!confirm(`¿Deseas eliminar la imagen "${type}" actual? No se puede deshacer.`)) return;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch('/admin/settings/delete-image', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ type })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToast(`Imagen "${type}" eliminada correctamente.`);
                    const preview = document.getElementById(previewId);
                    if (preview) preview.src = data.placeholder || `/assets/${type}.png`;
                    const btn = document.querySelector(`button[onclick="deleteSettingsImage('${type}', '${previewId}')"]`);
                    if (btn) btn.classList.add('hidden');
                } else {
                    showToast(data.message || 'Error al eliminar la imagen.', 'error');
                }
            })
            .catch(() => showToast('Error de red al eliminar imagen.', 'error'));
        }

        // Toast show message with support for types
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMsg = document.getElementById('toast-message');
            const toastIcon = document.getElementById('toast-icon');

            toastMsg.textContent = message;

            if (type === 'error') {
                toast.className = "fixed bottom-5 right-5 bg-red-600 text-on-primary px-lg py-md rounded-xl shadow-lg transform transition-all duration-300 z-50 flex items-center gap-sm";
                toastIcon.textContent = 'error';
            } else if (type === 'warning') {
                toast.className = "fixed bottom-5 right-5 bg-yellow-600 text-on-primary px-lg py-md rounded-xl shadow-lg transform transition-all duration-300 z-50 flex items-center gap-sm";
                toastIcon.textContent = 'warning';
            } else {
                toast.className = "fixed bottom-5 right-5 bg-primary text-on-primary px-lg py-md rounded-xl shadow-lg transform transition-all duration-300 z-50 flex items-center gap-sm";
                toastIcon.textContent = 'check_circle';
            }

            toast.classList.remove('translate-y-20', 'opacity-0');

            setTimeout(() => {
                toast.classList.add('translate-y-20', 'opacity-0');
            }, 3000);
        }

        // PERU DEPARTMENTS & PROVINCES DATA
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

        let metadataLoaded = false;
        let offersList = [];
        let currentOfferId = null;

        // Rich Text Format Simulation
        function formatTextarea(textareaId, format) {
            const txt = document.getElementById(textareaId);
            if (!txt) return;
            const start = txt.selectionStart;
            const end = txt.selectionEnd;
            const selected = txt.value.substring(start, end);
            let replacement = '';

            switch (format) {
                case 'bold':
                    replacement = `**${selected || 'texto'}**`;
                    break;
                case 'italic':
                    replacement = `*${selected || 'texto'}*`;
                    break;
                case 'bullet':
                    replacement = `\n- ${selected || 'item'}`;
                    break;
                case 'numeric':
                    replacement = `\n1. ${selected || 'item'}`;
                    break;
                case 'link':
                    replacement = `[${selected || 'enlace'}](https://example.com)`;
                    break;
                case 'image':
                    replacement = `![${selected || 'imagen'}](https://example.com/imagen.png)`;
                    break;
            }

            txt.value = txt.value.substring(0, start) + replacement + txt.value.substring(end);
            txt.focus();
            txt.setSelectionRange(start + replacement.length, start + replacement.length);
        }

        // Initialize Offers Module (called on tab switch)
        function initOffersModule() {
            if (!metadataLoaded) {
                loadOffersMetadata();
            } else {
                loadOffers();
            }
        }

        // Load Metadata Options from Backend
        function loadOffersMetadata() {
            fetch('/admin/offers/meta', {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Populate selects in filters
                        populateSelect('filter-category', data.categories, 'id', 'name', 'Cualquier categoría');
                        populateSelect('filter-work-schedule', data.work_schedules, 'id', 'name', 'Cualquier jornada');
                        populateSelect('filter-location', data.locations, 'id', 'name', 'Cualquier lugar');
                        populateSelect('filter-contract-type', data.contract_types, 'id', 'name', 'Cualquier contrato');
                        populateSelect('filter-company', data.companies, 'id', 'name', 'Cualquier empresa');

                        // Populate selects in form
                        populateSelect('offer-category', data.categories, 'id', 'name', 'Seleccionar...');
                        populateSelect('offer-work-schedule', data.work_schedules, 'id', 'name', 'Seleccionar...');
                        populateSelect('offer-location', data.locations, 'id', 'name', 'Seleccionar...');
                        populateSelect('offer-contract-type', data.contract_types, 'id', 'name', 'Seleccionar...');
                        populateSelect('offer-company', data.companies, 'id', 'name', 'Seleccionar...');

                        // Populate department dropdown
                        const deptSelect = document.getElementById('offer-department');
                        deptSelect.innerHTML = '<option value="">Seleccionar...</option>';
                        Object.keys(PERU_DEPARTMENTS).sort().forEach(dept => {
                            const opt = document.createElement('option');
                            opt.value = dept;
                            opt.textContent = dept;
                            deptSelect.appendChild(opt);
                        });

                        metadataLoaded = true;
                        loadOffers();
                    } else {
                        showToast('Error al cargar metadatos.', 'error');
                    }
                })
                .catch(err => {
                    showToast('Error de red al cargar metadatos.', 'error');
                });
        }

        function populateSelect(elementId, items, valueKey, textKey, defaultText) {
            const select = document.getElementById(elementId);
            if (!select) return;
            select.innerHTML = `<option value="">${defaultText}</option>`;
            items.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item[valueKey];
                opt.textContent = item[textKey];
                select.appendChild(opt);
            });
        }

        // ================= MANTENEDORES MODULE =================
        const maintainerConfig = {
            work_schedule: { collection: 'work_schedules', singular: 'jornada laboral' },
            category: { collection: 'categories', singular: 'categor\u00eda' },
            location: { collection: 'locations', singular: 'ubicaci\u00f3n' },
            contract_type: { collection: 'contract_types', singular: 'tipo de contrato' }
        };
        const storedMaintainerType = getStoredAdminValue(ADMIN_MAINTAINER_TYPE_KEY);
        let maintainerActiveType = maintainerConfig[storedMaintainerType] ? storedMaintainerType : 'work_schedule';
        let maintainerItems = [];
        let maintainerCurrentPage = 1;
        let maintainerPageSize = 10;
        let maintainerTotalPages = 1;

        function initMaintainersModule() {
            updateMaintainerTabs();
            updateMaintainerLabels();
            loadMaintainers();
        }

        function updateMaintainerTabs() {
            document.querySelectorAll('.maintainer-tab').forEach(tab => {
                const isActive = tab.dataset.maintainerType === maintainerActiveType;
                tab.classList.toggle('border-primary', isActive);
                tab.classList.toggle('text-primary', isActive);
                tab.classList.toggle('border-transparent', !isActive);
                tab.classList.toggle('text-on-surface-variant', !isActive);
            });
        }

        function selectMaintainerType(type) {
            if (!maintainerConfig[type]) return;
            maintainerActiveType = type;
            maintainerCurrentPage = 1;
            storeAdminValue(ADMIN_MAINTAINER_TYPE_KEY, type);
            updateMaintainerTabs();
            updateMaintainerLabels();
            loadMaintainers();
        }

        function updateMaintainerLabels() {
            const config = maintainerConfig[maintainerActiveType];
            document.getElementById('maintainer-create-label').textContent = `Crear ${config.singular}`;
        }

        function loadMaintainers() {
            const tbody = document.getElementById('maintainers-table-body');
            if (!tbody) return;

            tbody.innerHTML = '<tr><td colspan="2" class="p-8 text-center text-on-surface-variant">Cargando opciones...</td></tr>';

            fetch('/admin/offers/meta', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) throw new Error(data.message || 'No se pudieron cargar los mantenedores.');

                    const collection = maintainerConfig[maintainerActiveType].collection;
                    maintainerItems = (data[collection] || []).slice().sort((a, b) =>
                        a.name.localeCompare(b.name, 'es', { sensitivity: 'base' })
                    );
                    renderMaintainersTable();
                })
                .catch(() => {
                    tbody.innerHTML = '<tr><td colspan="2" class="p-8 text-center text-red-600">No se pudieron cargar las opciones.</td></tr>';
                });
        }

        function renderMaintainersTable() {
            const tbody = document.getElementById('maintainers-table-body');
            if (!tbody) return;

            maintainerTotalPages = Math.max(1, Math.ceil(maintainerItems.length / maintainerPageSize));
            maintainerCurrentPage = Math.min(maintainerCurrentPage, maintainerTotalPages);
            const start = (maintainerCurrentPage - 1) * maintainerPageSize;
            const pageItems = maintainerItems.slice(start, start + maintainerPageSize);

            if (pageItems.length === 0) {
                tbody.innerHTML = '<tr><td colspan="2" class="p-8 text-center text-on-surface-variant">No hay opciones registradas.</td></tr>';
            } else {
                tbody.innerHTML = pageItems.map(item => `
                <tr class="border-b border-outline-variant last:border-b-0 hover:bg-surface-container-low transition-colors">
                    <td class="px-4 py-4 text-on-surface">${escapeMaintainerHtml(item.name)}</td>
                    <td class="px-4 py-3">
                        <div class="flex justify-end gap-1">
                            <button type="button" onclick="openMaintainerModal(${item.id})" class="p-2 rounded-full text-primary hover:bg-primary-fixed transition-colors" title="Editar">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </button>
                            <button type="button" onclick="deleteMaintainerItem(${item.id})" class="p-2 rounded-full text-red-600 hover:bg-red-50 transition-colors" title="Eliminar">
                                <span class="material-symbols-outlined text-[20px]">delete</span>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
            }

            const visibleStart = maintainerItems.length ? start + 1 : 0;
            const visibleEnd = Math.min(start + maintainerPageSize, maintainerItems.length);
            document.getElementById('maintainer-page-summary').textContent = `${visibleStart}-${visibleEnd} de ${maintainerItems.length}`;

            const isFirstPage = maintainerCurrentPage <= 1;
            const isLastPage = maintainerCurrentPage >= maintainerTotalPages;
            document.getElementById('maintainer-first-page').disabled = isFirstPage;
            document.getElementById('maintainer-prev-page').disabled = isFirstPage;
            document.getElementById('maintainer-next-page').disabled = isLastPage;
            document.getElementById('maintainer-last-page').disabled = isLastPage;
        }

        function escapeMaintainerHtml(value) {
            const element = document.createElement('div');
            element.textContent = value || '';
            return element.innerHTML;
        }

        function changeMaintainerPageSize() {
            maintainerPageSize = parseInt(document.getElementById('maintainer-page-size').value, 10) || 10;
            maintainerCurrentPage = 1;
            renderMaintainersTable();
        }

        function goToMaintainerPage(page) {
            maintainerCurrentPage = Math.min(Math.max(1, page), maintainerTotalPages);
            renderMaintainersTable();
        }

        function openMaintainerModal(id = null) {
            const config = maintainerConfig[maintainerActiveType];
            const item = id ? maintainerItems.find(entry => entry.id === id) : null;

            document.getElementById('maintainer-form').reset();
            document.getElementById('maintainer-item-id').value = item ? item.id : '';
            document.getElementById('maintainer-name').value = item ? item.name : '';
            document.getElementById('maintainer-modal-title').textContent = item ? `Editar ${config.singular}` : `Crear ${config.singular}`;
            document.getElementById('maintainer-save-button').textContent = item ? 'Guardar' : 'Crear';
            toggleMaintainerModal();
        }

        function toggleMaintainerModal() {
            const modal = document.getElementById('maintainer-modal');
            const modalContainer = modal.querySelector('.max-w-md');

            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modalContainer.classList.remove('scale-95');
                    document.getElementById('maintainer-name').focus();
                }, 10);
            } else {
                modalContainer.classList.add('scale-95');
                setTimeout(() => modal.classList.add('hidden'), 300);
            }
        }

        function handleMaintainerSubmit(event) {
            event.preventDefault();
            const id = document.getElementById('maintainer-item-id').value;
            const name = document.getElementById('maintainer-name').value.trim();
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const button = document.getElementById('maintainer-save-button');
            const originalText = button.textContent;
            if (!name) return;

            button.disabled = true;
            button.textContent = 'Guardando...';

            fetch(id ? `/admin/maintainers/${maintainerActiveType}/${id}` : '/admin/offers/meta/add', {
                method: id ? 'PUT' : 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ type: maintainerActiveType, name })
            })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok || !data.success) throw new Error(data.message || 'No se pudo guardar la opci\u00f3n.');
                    return data;
                })
                .then(data => {
                    toggleMaintainerModal();
                    showToast(data.message || 'Opci\u00f3n guardada exitosamente.');
                    metadataLoaded = false;
                    loadMaintainers();
                })
                .catch(error => showToast(error.message, 'error'))
                .finally(() => {
                    button.disabled = false;
                    button.textContent = originalText;
                });
        }

        function deleteMaintainerItem(id) {
            const item = maintainerItems.find(entry => entry.id === id);
            if (!item || !confirm(`\u00bfDesea eliminar "${item.name}"?`)) return;

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch(`/admin/maintainers/${maintainerActiveType}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok || !data.success) throw new Error(data.message || 'No se pudo eliminar la opci\u00f3n.');
                    return data;
                })
                .then(data => {
                    showToast(data.message || 'Opci\u00f3n eliminada exitosamente.');
                    metadataLoaded = false;
                    loadMaintainers();
                })
                .catch(error => showToast(error.message, 'error'));
        }
        // Handle Department Change
        function handleDepartmentChange() {
            const deptSelect = document.getElementById('offer-department');
            const provSelect = document.getElementById('offer-province');
            const dept = deptSelect.value;

            provSelect.innerHTML = '';
            if (!dept || !PERU_DEPARTMENTS[dept]) {
                provSelect.innerHTML = '<option value="">Seleccionar departamento primero...</option>';
                return;
            }

            provSelect.innerHTML = '<option value="">Seleccionar...</option>';
            PERU_DEPARTMENTS[dept].sort().forEach(prov => {
                const opt = document.createElement('option');
                opt.value = prov;
                opt.textContent = prov;
                provSelect.appendChild(opt);
            });
        }

        // Load Offers List with Filters
        function loadOffers() {
            const tbody = document.getElementById('offers-table-body');
            if (tbody) {
                tbody.innerHTML = '<tr><td colspan="9" class="text-center py-xl text-on-surface-variant">Cargando ofertas...</td></tr>';
            }

            // Reset master checkbox and bulk actions bar on load
            const selectAll = document.getElementById('select-all-offers');
            if (selectAll) selectAll.checked = false;
            const bulkBar = document.getElementById('offers-bulk-actions');
            if (bulkBar) bulkBar.classList.add('hidden');

            const search = document.getElementById('search-offers-input').value;
            const sortBy = document.getElementById('filter-sort').value;
            const dateFilter = document.getElementById('filter-date').value;
            const categoryId = document.getElementById('filter-category').value;
            const salaryFilter = document.getElementById('filter-salary').value;
            const workScheduleId = document.getElementById('filter-work-schedule').value;
            const locationId = document.getElementById('filter-location').value;
            const contractTypeId = document.getElementById('filter-contract-type').value;
            const companyId = document.getElementById('filter-company').value;

            const params = new URLSearchParams({
                search,
                sort_by: sortBy,
                date_filter: dateFilter,
                category_id: categoryId,
                salary_filter: salaryFilter,
                work_schedule_id: workScheduleId,
                location_id: locationId,
                contract_type_id: contractTypeId,
                company_id: companyId
            });

            fetch(`/admin/offers?${params.toString()}`, {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        offersList = data.offers;
                        renderOffersTable(data.offers);
                    } else {
                        tbody.innerHTML = '<tr><td colspan="9" class="text-center py-xl text-red-600">Error al cargar ofertas.</td></tr>';
                    }
                })
                .catch(err => {
                    if (tbody) {
                        tbody.innerHTML = '<tr><td colspan="9" class="text-center py-xl text-red-600">Error de red.</td></tr>';
                    }
                });
        }

        // Render Offers Table rows
        function renderOffersTable(offers) {
            const tbody = document.getElementById('offers-table-body');
            if (!tbody) return;

            if (offers.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" class="text-center py-xl text-on-surface-variant">No se encontraron convocatorias.</td></tr>';
                return;
            }

            tbody.innerHTML = '';
            offers.forEach(offer => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-surface-container-low transition-colors duration-150 border-b border-outline-variant/60';

                // State Chip formatting
                let stateClass = 'bg-surface-container-high text-on-surface-variant';
                let stateName = 'Desconocido';
                if (offer.state) {
                    stateName = offer.state.name;
                    const stateKey = offer.state.key;
                    if (stateKey === 'draft') {
                        stateClass = 'bg-yellow-100 text-yellow-800 border border-yellow-200';
                    } else if (stateKey === 'active') {
                        stateClass = 'bg-green-100 text-green-800 border border-green-200';
                    } else if (stateKey === 'finished') {
                        stateClass = 'bg-red-100 text-red-800 border border-red-200';
                    }
                }

                const salaryText = offer.salary ? `${parseFloat(offer.salary).toFixed(2)} ${offer.salary_currency === 'DOLARES' ? 'USD' : 'SOL'}` : 'Sin especificar';
                const categoryText = offer.category ? offer.category.name : 'General';
                const workdayText = offer.work_schedule ? offer.work_schedule.name : 'Completo';
                const modalityText = `${offer.location ? offer.location.name : 'Presencial'}<br><span class="text-[11px] text-on-surface-variant">${offer.address}, ${offer.province}, ${offer.department}</span>`;
                const contractText = offer.contract_type ? offer.contract_type.name : 'Plazo Fijo';

                tr.innerHTML = `
                <td class="px-4 py-3">
                    <input type="checkbox" value="${offer.id}" onchange="updateSelectedOffers()" class="offer-checkbox rounded border-outline-variant focus:ring-primary text-primary cursor-pointer">
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold uppercase ${stateClass}">
                        ${stateName}
                    </span>
                </td>
                <td class="px-4 py-3 font-semibold text-primary hover:underline cursor-pointer" onclick="handleOfferAction('preview', ${offer.id})">
                    ${offer.title}
                </td>
                <td class="px-4 py-3">${salaryText}</td>
                <td class="px-4 py-3">${categoryText}</td>
                <td class="px-4 py-3">${workdayText}</td>
                <td class="px-4 py-3">${modalityText}</td>
                <td class="px-4 py-3">${contractText}</td>
                <td class="px-4 py-3 text-right">
                    <button type="button" onclick="toggleOfferActions(event, ${offer.id})" class="p-1 rounded-full hover:bg-surface-container-high text-on-surface-variant inline-flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">more_vert</span>
                    </button>
                </td>
            `;
                tbody.appendChild(tr);
            });
        }

        // Clear filters
        function clearOffersFilters() {
            document.getElementById('search-offers-input').value = '';
            document.getElementById('filter-sort').value = 'recent';
            document.getElementById('filter-date').value = '';
            document.getElementById('filter-category').value = '';
            document.getElementById('filter-salary').value = '';
            document.getElementById('filter-work-schedule').value = '';
            document.getElementById('filter-location').value = '';
            document.getElementById('filter-contract-type').value = '';
            document.getElementById('filter-company').value = '';
            loadOffers();
        }

        // Show offer form
        function showCreateOfferForm() {
            document.getElementById('offer-form').reset();
            document.getElementById('form-offer-id').value = '';
            document.getElementById('offer-province').innerHTML = '<option value="">Seleccionar departamento primero...</option>';
            document.getElementById('form-offer-title').textContent = 'Crear Oferta Laboral';
            document.getElementById('btn-save-offer').textContent = 'Crear Oferta Laboral';

            // Default publication date to today
            const todayStr = new Date().toISOString().split('T')[0];
            document.getElementById('offer-pub-date').value = todayStr;

            document.getElementById('offers-list-view').classList.add('hidden');
            document.getElementById('offers-form-view').classList.remove('hidden');
        }

        // Hide offer form
        function hideCreateOfferForm() {
            document.getElementById('offers-form-view').classList.add('hidden');
            document.getElementById('offers-list-view').classList.remove('hidden');
            loadOffers();
        }

        // Handle AJAX offer create/edit submit
        function handleOfferFormSubmit(event) {
            event.preventDefault();

            const id = document.getElementById('form-offer-id').value;
            const payload = {
                title: document.getElementById('offer-title').value,
                publication_date: document.getElementById('offer-pub-date').value,
                description: document.getElementById('offer-description').value,
                requirements: document.getElementById('offer-requirements').value,
                benefits: document.getElementById('offer-benefits').value,
                contract_type_id: document.getElementById('offer-contract-type').value,
                location_id: document.getElementById('offer-location').value,
                address: document.getElementById('offer-address').value,
                work_schedule_id: document.getElementById('offer-work-schedule').value,
                department: document.getElementById('offer-department').value,
                category_id: document.getElementById('offer-category').value,
                salary: document.getElementById('offer-salary').value,
                salary_currency: document.getElementById('offer-currency').value,
                province: document.getElementById('offer-province').value,
                company_id: document.getElementById('offer-company').value,
            };

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const url = id ? `/admin/offers/${id}` : '/admin/offers';
            const method = id ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload)
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast(id ? '¡Oferta actualizada con éxito!' : '¡Oferta creada con éxito!');
                        hideCreateOfferForm();
                    } else {
                        showToast(data.message || 'Error al guardar la oferta.', 'error');
                    }
                })
                .catch(err => {
                    showToast('Error de red al intentar guardar la oferta.', 'error');
                });
        }

        // Toggle Metadata Add Inline Modal
        let activeLookupSelectId = '';
        function openAddLookupModal(type, label) {
            activeLookupSelectId = {
                'contract_type': 'offer-contract-type',
                'location': 'offer-location',
                'work_schedule': 'offer-work-schedule',
                'category': 'offer-category'
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

        // Submit Inline Lookup metadata
        function handleAddLookupSubmit(event) {
            event.preventDefault();

            const type = document.getElementById('lookup-type').value;
            const name = document.getElementById('lookup-name').value;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/admin/offers/meta/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ type, name })
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

                        // If adding from category/location/work_schedule, also sync to list filter selects
                        const filterSelectId = {
                            'offer-contract-type': 'filter-contract-type',
                            'offer-location': 'filter-location',
                            'offer-work-schedule': 'filter-work-schedule',
                            'offer-category': 'filter-category'
                        }[activeLookupSelectId];

                        const filterSelect = document.getElementById(filterSelectId);
                        if (filterSelect) {
                            const fOpt = document.createElement('option');
                            fOpt.value = data.item.id;
                            fOpt.textContent = data.item.name;
                            filterSelect.appendChild(fOpt);
                        }

                        toggleLookupModal();
                    } else {
                        showToast(data.message || 'Error al agregar opción.', 'error');
                    }
                })
                .catch(err => {
                    showToast('Error de red al agregar opción.', 'error');
                });
        }

        // Contextual Actions dropdown (3 puntos)
        function toggleOfferActions(event, id) {
            event.stopPropagation();
            currentOfferId = id;
            const btn = event.currentTarget;
            const rect = btn.getBoundingClientRect();
            const dropdown = document.getElementById('offer-actions-dropdown');

            dropdown.style.top = `${rect.bottom + window.scrollY + 5}px`;
            dropdown.style.left = `${rect.left + window.scrollX - 150}px`;
            dropdown.classList.remove('hidden');

            document.addEventListener('click', closeOfferActions);
        }

        function closeOfferActions() {
            const dropdown = document.getElementById('offer-actions-dropdown');
            if (dropdown) {
                dropdown.classList.add('hidden');
            }
            document.removeEventListener('click', closeOfferActions);
        }

        // Handles contextual clicks
        function handleOfferAction(action, id = null) {
            if (id) currentOfferId = id;
            if (!currentOfferId) return;

            switch (action) {
                case 'preview':
                    openPreviewModal(currentOfferId);
                    break;
                case 'applicants':
                    openOfferApplicantsModal(currentOfferId);
                    break;
                case 'share':
                    copyShareLink(currentOfferId);
                    break;
                case 'edit':
                    editOffer(currentOfferId);
                    break;
                case 'delete':
                    deleteOffer(currentOfferId);
                    break;
                case 'state_draft':
                    updateOfferState(currentOfferId, 1); // Borrador
                    break;
                case 'state_active':
                    updateOfferState(currentOfferId, 2); // Vigente
                    break;
                case 'state_finished':
                    updateOfferState(currentOfferId, 3); // Finalizada
                    break;
            }
        }

        // Copy Share Link to Clipboard
        // Copy/Share Link for Offer
        function copyShareLink(id) {
            const offer = offersList.find(o => o.id === id);
            const title = offer ? offer.title : 'Oferta de Empleo';
            const company = (offer && offer.company) ? offer.company.name : '';
            
            const shareUrl = `${window.location.origin}?offer=${id}`;
            const shareText = `Mira esta oferta de trabajo: ${title}${company ? ' en ' + company : ''} - Bolsa Laboral`;

            if (navigator.share) {
                navigator.share({
                    title: title,
                    text: shareText,
                    url: shareUrl
                }).catch(() => {
                    // Fallback to copy if sharing fails
                    fallbackCopyToClipboard(shareUrl);
                });
            } else {
                fallbackCopyToClipboard(shareUrl);
            }
        }

        function fallbackCopyToClipboard(text) {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text)
                    .then(() => {
                        showToast('¡Enlace de oferta copiado al portapapeles!');
                    })
                    .catch(() => {
                        fallbackCopyExecute(text);
                    });
            } else {
                fallbackCopyExecute(text);
            }
        }

        function fallbackCopyExecute(text) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
                showToast('¡Enlace de oferta copiado al portapapeles!');
            } catch (err) {
                showToast('Error al copiar el enlace.', 'error');
            }
            document.body.removeChild(textArea);
        }

        // Update state
        function updateOfferState(id, stateId) {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/admin/offers/${id}/toggle-state`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ state_id: stateId })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast('Estado de la oferta actualizado.');
                        loadOffers();
                    } else {
                        showToast(data.message || 'Error al cambiar estado.', 'error');
                    }
                })
                .catch(err => {
                    showToast('Error de red al cambiar estado.', 'error');
                });
        }

        // Delete Offer
        function deleteOffer(id) {
            if (!confirm('¿Estás seguro de que deseas eliminar esta oferta laboral?')) return;

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/admin/offers/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast('Oferta laboral eliminada.');
                        loadOffers();
                    } else {
                        showToast(data.message || 'Error al eliminar oferta.', 'error');
                    }
                })
                .catch(err => {
                    showToast('Error de red al eliminar oferta.', 'error');
                });
        }

        // Toggle all checkboxes in the offers list
        function toggleSelectAllOffers(master) {
            const checkboxes = document.querySelectorAll('.offer-checkbox');
            checkboxes.forEach(cb => cb.checked = master.checked);
            updateSelectedOffers();
        }

        // Update selected offers count and show/hide bulk bar
        function updateSelectedOffers() {
            const checkboxes = document.querySelectorAll('.offer-checkbox:checked');
            const count = checkboxes.length;
            
            const bulkBar = document.getElementById('offers-bulk-actions');
            const countLabel = document.getElementById('offers-selected-count');
            const selectAll = document.getElementById('select-all-offers');
            const allCheckboxes = document.querySelectorAll('.offer-checkbox');

            if (count > 0) {
                bulkBar.classList.remove('hidden');
                bulkBar.classList.add('flex');
                countLabel.textContent = `${count} ${count === 1 ? 'oferta seleccionada' : 'ofertas seleccionadas'}`;
            } else {
                bulkBar.classList.add('hidden');
                bulkBar.classList.remove('flex');
            }

            // Sync select-all master checkbox state
            if (selectAll && allCheckboxes.length > 0) {
                selectAll.checked = (count === allCheckboxes.length);
            }
        }

        // Bulk Delete selected job offers
        function bulkDeleteOffers() {
            const checkboxes = document.querySelectorAll('.offer-checkbox:checked');
            const count = checkboxes.length;

            if (count === 0) return;

            // Set count in the modal content
            document.getElementById('bulk-delete-count').textContent = count;

            // Open custom modal
            toggleConfirmBulkDeleteModal(true);
        }

        // Toggle custom bulk delete confirmation modal
        function toggleConfirmBulkDeleteModal(show) {
            const modal = document.getElementById('confirm-bulk-delete-modal');
            if (!modal) return;
            const container = modal.querySelector('.max-w-md');

            if (show) {
                modal.classList.remove('hidden');
                setTimeout(() => container.classList.remove('scale-95'), 10);
            } else {
                container.classList.add('scale-95');
                setTimeout(() => modal.classList.add('hidden'), 300);
            }
        }

        // Execute bulk delete via AJAX
        function executeBulkDeleteOffers() {
            const checkboxes = document.querySelectorAll('.offer-checkbox:checked');
            const ids = Array.from(checkboxes).map(cb => parseInt(cb.value));

            if (ids.length === 0) return;

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const btn = document.getElementById('btn-confirm-bulk-delete');
            const originalHtml = btn.innerHTML;

            // disable buttons
            btn.disabled = true;
            btn.innerHTML = '<span class="material-symbols-outlined text-[18px] animate-spin">progress_activity</span> Eliminando...';

            fetch('/admin/offers/bulk-delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ ids: ids })
            })
                .then(res => res.json())
                .then(data => {
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                    toggleConfirmBulkDeleteModal(false);

                    if (data.success) {
                        showToast(data.message);
                        loadOffers(); // reload completely
                    } else {
                        showToast(data.message || 'Error al eliminar ofertas en masa.', 'error');
                    }
                })
                .catch(err => {
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                    toggleConfirmBulkDeleteModal(false);
                    showToast('Error de red al intentar eliminar en masa.', 'error');
                });
        }

        // Edit Offer - Load values into form
        function editOffer(id) {
            fetch(`/admin/offers/${id}`, {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const offer = data.offer;
                        document.getElementById('form-offer-id').value = offer.id;
                        document.getElementById('offer-title').value = offer.title;
                        document.getElementById('offer-pub-date').value = offer.publication_date.split('T')[0];
                        document.getElementById('offer-description').value = offer.description;
                        document.getElementById('offer-requirements').value = offer.requirements;
                        document.getElementById('offer-benefits').value = offer.benefits || '';
                        document.getElementById('offer-contract-type').value = offer.contract_type_id;
                        document.getElementById('offer-location').value = offer.location_id;
                        document.getElementById('offer-address').value = offer.address;
                        document.getElementById('offer-work-schedule').value = offer.work_schedule_id;
                        document.getElementById('offer-department').value = offer.department;

                        // Trigger department change to populate provinces
                        handleDepartmentChange();
                        document.getElementById('offer-province').value = offer.province;

                        document.getElementById('offer-category').value = offer.category_id;
                        document.getElementById('offer-salary').value = parseFloat(offer.salary);
                        document.getElementById('offer-currency').value = offer.salary_currency;
                        document.getElementById('offer-company').value = offer.company_id;

                        document.getElementById('form-offer-title').textContent = 'Editar Oferta Laboral';
                        document.getElementById('btn-save-offer').textContent = 'Guardar Cambios';

                        document.getElementById('offers-list-view').classList.add('hidden');
                        document.getElementById('offers-form-view').classList.remove('hidden');
                    } else {
                        showToast('No se pudieron cargar los datos de la oferta.', 'error');
                    }
                })
                .catch(err => {
                    showToast('Error de red al cargar detalles de oferta.', 'error');
                });
        }

        // Open Preview modal
        function openPreviewModal(id) {
            togglePreviewOfferModal();
            const content = document.getElementById('preview-offer-content');
            content.innerHTML = '<div class="text-center py-xl">Cargando previsualización...</div>';

            fetch(`/admin/offers/${id}`, {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const offer = data.offer;
                        const companyName = offer.company ? offer.company.name : 'Empresa General';
                        const salaryText = offer.salary ? `${parseFloat(offer.salary).toFixed(2)} ${offer.salary_currency === 'DOLARES' ? 'USD' : 'SOL'}` : 'No especificado';
                        const categoryName = offer.category ? offer.category.name : 'General';
                        const scheduleName = offer.work_schedule ? offer.work_schedule.name : 'Jornada Completa';
                        const contractTypeName = offer.contract_type ? offer.contract_type.name : 'Contrato Fijo';
                        const locationName = offer.location ? offer.location.name : 'Presencial';

                        // Helper to format line breaks
                        const nl2br = (str) => str ? str.replace(/\n/g, '<br>') : '';

                        content.innerHTML = `
                    <div class="space-y-lg">
                        <!-- Top Banner / Headline -->
                        <div class="bg-primary/5 p-lg rounded-2xl border border-primary/20 flex flex-col md:flex-row justify-between items-start md:items-center gap-md shadow-sm">
                            <div class="space-y-md">
                                <span class="px-2 py-1 rounded bg-primary-fixed text-primary font-bold text-xs uppercase">${categoryName}</span>
                                <h1 class="text-headline-md font-headline-md text-primary font-black leading-tight">${offer.title}</h1>
                                <div class="flex items-center gap-md flex-wrap text-body-sm text-on-surface-variant font-medium">
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[18px]">domain</span>
                                        ${companyName}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[18px]">pin_drop</span>
                                        ${locationName} (${offer.address}, ${offer.province}, ${offer.department})
                                    </span>
                                </div>
                            </div>
                            ${offer.company && offer.company.logo ? `
                                <div class="w-16 h-16 bg-surface-bright rounded-xl border border-outline-variant flex items-center justify-center shrink-0 overflow-hidden shadow-sm">
                                    <img src="${offer.company.logo}" class="w-full h-full object-contain" alt="Logo de la empresa">
                                </div>
                            ` : `
                                <div class="w-16 h-16 bg-surface-bright rounded-xl border border-outline-variant flex items-center justify-center shrink-0 shadow-sm">
                                    <span class="material-symbols-outlined text-4xl text-on-surface-variant">corporate_fare</span>
                                </div>
                            `}
                        </div>

                        <!-- Info Badges Grid -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-md">
                            <div class="p-md border border-outline-variant rounded-xl bg-surface-container-low">
                                <span class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant/80">Jornada</span>
                                <span class="font-bold text-body-sm text-on-surface">${scheduleName}</span>
                            </div>
                            <div class="p-md border border-outline-variant rounded-xl bg-surface-container-low">
                                <span class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant/80">Contrato</span>
                                <span class="font-bold text-body-sm text-on-surface">${contractTypeName}</span>
                            </div>
                            <div class="p-md border border-outline-variant rounded-xl bg-surface-container-low">
                                <span class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant/80">Salario</span>
                                <span class="font-bold text-body-sm text-on-surface text-green-700">${salaryText}</span>
                            </div>
                            <div class="p-md border border-outline-variant rounded-xl bg-surface-container-low">
                                <span class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant/80">Publicación</span>
                                <span class="font-bold text-body-sm text-on-surface">${new Date(offer.publication_date).toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' })}</span>
                            </div>
                        </div>

                        <!-- Details Section -->
                        <div class="space-y-md">
                            <div>
                                <h3 class="font-bold text-body-sm text-on-surface border-b border-outline-variant/60 pb-1 mb-2">Descripción del puesto</h3>
                                <p class="text-body-sm leading-relaxed">${nl2br(offer.description)}</p>
                            </div>
                            <div>
                                <h3 class="font-bold text-body-sm text-on-surface border-b border-outline-variant/60 pb-1 mb-2">Requisitos</h3>
                                <p class="text-body-sm leading-relaxed">${nl2br(offer.requirements)}</p>
                            </div>
                            ${offer.benefits ? `
                            <div>
                                <h3 class="font-bold text-body-sm text-on-surface border-b border-outline-variant/60 pb-1 mb-2">Beneficios</h3>
                                <p class="text-body-sm leading-relaxed">${nl2br(offer.benefits)}</p>
                            </div>` : ''}
                        </div>
                    </div>
                `;
                    } else {
                        content.innerHTML = '<div class="text-center py-xl text-red-600">Error al cargar detalles de la oferta.</div>';
                    }
                })
                .catch(err => {
                    content.innerHTML = '<div class="text-center py-xl text-red-600">Error de red al cargar detalles de la oferta.</div>';
                });
        }

        function togglePreviewOfferModal() {
            const modal = document.getElementById('preview-offer-modal');
            const modalContainer = modal.querySelector('.max-w-4xl');

            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                setTimeout(() => modalContainer.classList.remove('scale-95'), 10);
            } else {
                modalContainer.classList.add('scale-95');
                setTimeout(() => modal.classList.add('hidden'), 300);
            }
        }

        function toggleOfferApplicantsModal() {
            const modal = document.getElementById('offer-applicants-modal');
            const modalContainer = modal.querySelector('.max-w-4xl');

            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                setTimeout(() => modalContainer.classList.remove('scale-95'), 10);
            } else {
                modalContainer.classList.add('scale-95');
                setTimeout(() => modal.classList.add('hidden'), 300);
            }
        }

        function openOfferApplicantsModal(offerId) {
            toggleOfferApplicantsModal();
            const titleEl = document.getElementById('offer-applicants-title');
            const container = document.getElementById('offer-applicants-list-container');
            
            titleEl.textContent = 'Cargando detalles de la oferta...';
            container.innerHTML = '<tr><td colspan="6" class="text-center py-xl text-on-surface-variant">Cargando postulantes...</td></tr>';
            
            fetch(`/admin/offers/${offerId}/applicants`, {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const offer = data.offer;
                    titleEl.textContent = `${offer.title} · ${offer.company ? offer.company.name : 'Empresa General'}`;
                    
                    const apps = data.applications;
                    if (!apps || apps.length === 0) {
                        container.innerHTML = '<tr><td colspan="6" class="text-center py-xl text-on-surface-variant">No hay postulantes registrados para esta oferta aún.</td></tr>';
                        return;
                    }
                    
                    container.innerHTML = apps.map(app => {
                        const dateStr = app.created_at ? new Date(app.created_at).toLocaleString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '-';
                        
                        // Status badge styling
                        let badgeClass = 'bg-surface-container text-on-surface';
                        let statusText = 'Postulado';
                        if (app.status === 'under_review') {
                            badgeClass = 'bg-yellow-100 text-yellow-800 border border-yellow-200';
                            statusText = 'En revisión';
                        } else if (app.status === 'accepted') {
                            badgeClass = 'bg-green-100 text-green-800 border border-green-200';
                            statusText = 'Aceptado';
                        } else if (app.status === 'rejected') {
                            badgeClass = 'bg-red-100 text-red-800 border border-red-200';
                            statusText = 'Rechazado';
                        }
                        const statusBadge = `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider ${badgeClass}">${statusText}</span>`;
                        
                        const cvHTML = app.cv 
                            ? `<a href="${app.cv}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1 bg-primary-container text-on-primary-container text-body-xs font-semibold rounded-lg hover:opacity-90 transition-opacity"><span class="material-symbols-outlined text-[14px]">description</span>Ver CV</a>`
                            : `<span class="text-on-surface-variant text-body-xs italic">Sin CV</span>`;
                        
                        const candidateName = app.fullname || (app.user && app.user.person ? app.user.person.names : 'Candidato');
                        const candidateEmail = app.user ? app.user.email : '-';
                        const candidatePhone = app.user && app.user.person ? app.user.person.phone : '-';
                        const studyProgram = app.program_study || 'Computación y Sistemas';
                        
                        return `
                        <tr>
                            <td class="p-4">
                                <div class="font-semibold text-on-surface text-body-sm">${candidateName}</div>
                            </td>
                            <td class="p-4 text-on-surface-variant">${studyProgram}</td>
                            <td class="p-4">
                                <div class="text-body-xs text-on-surface-variant flex flex-col gap-0.5">
                                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[13px]">mail</span>${candidateEmail}</span>
                                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[13px]">call</span>${candidatePhone}</span>
                                </div>
                            </td>
                            <td class="p-4 text-on-surface-variant font-mono text-body-xs">${dateStr}</td>
                            <td class="p-4 text-center">${statusBadge}</td>
                            <td class="p-4 text-center">${cvHTML}</td>
                        </tr>
                        `;
                    }).join('');
                } else {
                    titleEl.textContent = 'Error al cargar';
                    container.innerHTML = `<tr><td colspan="6" class="text-center py-xl text-red-600">${data.message || 'Error al cargar postulantes.'}</td></tr>`;
                }
            })
            .catch(err => {
                titleEl.textContent = 'Error de red';
                container.innerHTML = '<tr><td colspan="6" class="text-center py-xl text-red-600">Error de red al cargar postulantes.</td></tr>';
            });
        }

        // ==========================================
        // MODULE: COMPANY MANAGEMENT FOR ADMIN
        // ==========================================
        let companiesList = [];
        let currentCompanyId = null;
        let companiesPagination = null;
        let currentSearchTerm = '';
        let currentCompaniesPage = 1;
        let selectedCompanyIds = new Set();
        let companiesViewMode = 'list';

        function setCompaniesViewMode(mode) {
            companiesViewMode = mode;
            const btnList = document.getElementById('btn-view-list');
            const btnGrid = document.getElementById('btn-view-grid');
            if (mode === 'list') {
                btnList.classList.remove('bg-surface-container-lowest', 'text-on-surface-variant');
                btnList.classList.add('bg-primary', 'text-on-primary');
                btnGrid.classList.remove('bg-primary', 'text-on-primary');
                btnGrid.classList.add('bg-surface-container-lowest', 'text-on-surface-variant');
            } else {
                btnGrid.classList.remove('bg-surface-container-lowest', 'text-on-surface-variant');
                btnGrid.classList.add('bg-primary', 'text-on-primary');
                btnList.classList.remove('bg-primary', 'text-on-primary');
                btnList.classList.add('bg-surface-container-lowest', 'text-on-surface-variant');
            }
            renderCompanies();
        }

        // Load companies list from backend with pagination and search
        function loadCompanies(page) {
            const container = document.getElementById('companies-cards-container');
            const gridContainer = document.getElementById('companies-grid-view');
            if (container) {
                container.innerHTML = '<tr><td colspan="9" class="text-center py-xl text-on-surface-variant">Cargando empresas...</td></tr>';
            }
            if (gridContainer) {
                gridContainer.innerHTML = '<div class="col-span-full text-center py-xl text-on-surface-variant">Cargando empresas...</div>';
            }

            const searchTerm = document.getElementById('search-companies-input').value.trim();
            currentSearchTerm = searchTerm;
            const pageNum = page || 1;

            let url = '/admin/companies?page=' + pageNum;
            if (searchTerm) {
                url += '&search=' + encodeURIComponent(searchTerm);
            }

            fetch(url, {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        companiesList = data.companies;
                        companiesPagination = data.pagination || null;
                        renderCompanies();
                        renderCompaniesPagination();
                    } else {
                        const errMsg = '<tr><td colspan="9" class="text-center py-xl text-red-600">' + (data.message || 'Error al cargar empresas.') + '</td></tr>';
                        const errGridMsg = '<div class="col-span-full text-center py-xl text-red-600">' + (data.message || 'Error al cargar empresas.') + '</div>';
                        if (container) container.innerHTML = errMsg;
                        if (gridContainer) gridContainer.innerHTML = errGridMsg;
                    }
                })
                .catch(err => {
                    const errStr = '<tr><td colspan="9" class="text-center py-xl text-red-600">Error de red al cargar empresas.</td></tr>';
                    const errGridStr = '<div class="col-span-full text-center py-xl text-red-600">Error de red al cargar empresas.</div>';
                    if (container) container.innerHTML = errStr;
                    if (gridContainer) gridContainer.innerHTML = errGridStr;
                });
        }

        // Render companies table rows or grid cards
        function renderCompanies() {
            const container = document.getElementById('companies-cards-container');
            const gridContainer = document.getElementById('companies-grid-view');
            const listView = document.getElementById('companies-list-view');
            
            if (!container || !gridContainer || !listView) return;

            if (companiesViewMode === 'list') {
                listView.classList.remove('hidden');
                gridContainer.classList.add('hidden');
            } else {
                listView.classList.add('hidden');
                gridContainer.classList.remove('hidden');
            }

            if (!companiesList || companiesList.length === 0) {
                container.innerHTML = '<tr><td colspan="9" class="text-center py-xl text-on-surface-variant">No se encontraron empresas registradas.</td></tr>';
                gridContainer.innerHTML = '<div class="col-span-full text-center py-xl text-on-surface-variant">No se encontraron empresas registradas.</div>';
                return;
            }

            if (companiesViewMode === 'list') {
                container.innerHTML = companiesList.map(company => {
                    const isVerified = company.is_verified;
                    const verifiedBadge = isVerified
                        ? `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-800 border border-green-200"><span class="material-symbols-outlined text-[12px] mr-1">check_circle</span>Aprobado</span>`
                        : `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200"><span class="material-symbols-outlined text-[12px] mr-1">pending</span>Pendiente</span>`;
                    const logoHTML = company.logo
                        ? `<img src="${company.logo}" class="w-full h-full object-cover" onerror="this.outerHTML='<span class=\'text-sm font-bold text-primary\'>${company.name.charAt(0).toUpperCase()}</span>'">`
                        : `<span class="text-sm font-bold text-primary">${company.name.charAt(0).toUpperCase()}</span>`;
                    const verifyTitle = isVerified ? 'Desaprobar empresa (suspender acceso)' : 'Aprobar empresa (permitir acceso)';
                    const verifyIcon  = isVerified ? 'block' : 'check_circle';
                    const verifyColor = isVerified ? 'text-amber-600 hover:bg-amber-50 border border-amber-200' : 'text-green-600 hover:bg-green-50 border border-green-200';
                    const isSelected = selectedCompanyIds.has(company.id);
                    return `<tr id="company-card-${company.id}" class="hover:bg-surface-container-low transition-colors ${isSelected ? 'bg-primary-fixed/30' : ''}">
                        <td class="px-4 py-3 text-center">
                            <input type="checkbox" class="company-checkbox w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary/30 cursor-pointer"
                                value="${company.id}" ${isSelected ? 'checked' : ''}
                                onchange="toggleCompanySelect(${company.id}, this.checked)">
                        </td>
                        <td class="px-4 py-3">
                            <div class="w-10 h-10 rounded-lg bg-surface-container border border-outline-variant overflow-hidden flex items-center justify-center shrink-0">${logoHTML}</div>
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-semibold text-on-surface text-body-sm truncate max-w-[180px]" title="${company.name}">${company.name}</p>
                        </td>
                        <td class="px-4 py-3 font-mono text-body-sm text-on-surface-variant">${company.ruc}</td>
                        <td class="px-4 py-3 text-body-sm text-on-surface-variant truncate max-w-[180px]" title="${company.email}">${company.email}</td>
                        <td class="px-4 py-3 text-body-sm text-on-surface-variant">${company.phone || '-'}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-primary/10 text-primary font-bold text-body-sm">${company.offers_count || 0}</span>
                        </td>
                        <td class="px-4 py-3 text-center">${verifiedBadge}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1">
                                <button onclick="editCompany(${company.id})" title="Editar" class="p-1.5 rounded-lg hover:bg-surface-container-high text-primary transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </button>
                                <button onclick="toggleCompanyVerify(${company.id})" title="${verifyTitle}" class="p-1.5 border rounded-lg transition-colors ${verifyColor}">
                                    <span class="material-symbols-outlined text-[18px]">${verifyIcon}</span>
                                </button>
                                <button onclick="deleteCompany(${company.id})" title="Eliminar" class="p-1.5 rounded-lg hover:bg-error-container text-error transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>`;
                }).join('');
            } else {
                gridContainer.innerHTML = companiesList.map(company => {
                    const isVerified = company.is_verified;
                    const verifiedBadge = isVerified
                        ? `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-800 border border-green-200"><span class="material-symbols-outlined text-[12px] mr-1">check_circle</span>Aprobado</span>`
                        : `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200"><span class="material-symbols-outlined text-[12px] mr-1">pending</span>Pendiente</span>`;
                    const logoHTML = company.logo
                        ? `<img src="${company.logo}" class="w-full h-full object-cover" onerror="this.outerHTML='<span class=\'text-lg font-bold text-primary\'>${company.name.charAt(0).toUpperCase()}</span>'">`
                        : `<span class="text-lg font-bold text-primary">${company.name.charAt(0).toUpperCase()}</span>`;
                    const verifyTitle = isVerified ? 'Desaprobar empresa (suspender acceso)' : 'Aprobar empresa (permitir acceso)';
                    const verifyIcon  = isVerified ? 'block' : 'check_circle';
                    const verifyColor = isVerified ? 'text-amber-600 hover:bg-amber-50 border-amber-200/60' : 'text-green-600 hover:bg-green-50 border-green-200/60';
                    const isSelected = selectedCompanyIds.has(company.id);
                    
                    return `
                    <div class="bg-surface rounded-2xl border border-outline-variant shadow-sm p-md flex flex-col justify-between relative group hover:shadow-md transition-all duration-200 ${isSelected ? 'border-primary bg-primary/5' : ''}">
                        <!-- Top bar (Checkbox and verify status) -->
                        <div class="flex items-center justify-between mb-md">
                            <input type="checkbox" class="company-checkbox w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary/30 cursor-pointer"
                                value="${company.id}" ${isSelected ? 'checked' : ''}
                                onchange="toggleCompanySelect(${company.id}, this.checked)">
                            <div>${verifiedBadge}</div>
                        </div>
                        
                        <!-- Company Info -->
                        <div class="text-center flex-1 flex flex-col items-center mb-md">
                            <div class="w-16 h-16 rounded-2xl bg-surface-container border border-outline-variant overflow-hidden flex items-center justify-center mb-md shadow-inner shrink-0">${logoHTML}</div>
                            <h4 class="font-bold text-on-surface text-body-md line-clamp-2 px-xs leading-snug mb-xs" title="${company.name}">${company.name}</h4>
                            <span class="font-mono text-body-xs text-on-surface-variant bg-surface-container px-2 py-0.5 rounded border border-outline-variant/40 mb-md">${company.ruc}</span>
                            
                            <div class="w-full text-left space-y-2 mt-xs border-t border-outline-variant/40 pt-md text-on-surface-variant font-body-sm text-[13px]">
                                <div class="flex items-center gap-2 truncate" title="${company.email}">
                                    <span class="material-symbols-outlined text-[16px] text-primary/60">mail</span>
                                    <span class="truncate">${company.email}</span>
                                </div>
                                <div class="flex items-center gap-2 truncate">
                                    <span class="material-symbols-outlined text-[16px] text-primary/60">call</span>
                                    <span>${company.phone || '-'}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[16px] text-primary/60">list_alt</span>
                                    <span>Ofertas publicadas: <strong class="text-primary font-bold">${company.offers_count || 0}</strong></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action buttons -->
                        <div class="flex items-center justify-center gap-2 border-t border-outline-variant/40 pt-md mt-auto">
                            <button onclick="editCompany(${company.id})" title="Editar" class="flex-1 py-1.5 bg-surface-container border border-outline-variant hover:bg-surface-container-high text-primary rounded-xl flex items-center justify-center gap-1 transition-colors">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                                <span class="text-body-xs font-semibold">Editar</span>
                            </button>
                            <button onclick="toggleCompanyVerify(${company.id})" title="${verifyTitle}" class="p-2 border rounded-xl flex items-center justify-center transition-colors ${verifyColor}">
                                <span class="material-symbols-outlined text-[18px]">${verifyIcon}</span>
                            </button>
                            <button onclick="deleteCompany(${company.id})" title="Eliminar" class="p-2 border border-outline-variant hover:bg-error-container hover:border-error-container text-error rounded-xl flex items-center justify-center transition-colors">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </button>
                        </div>
                    </div>
                    `;
                }).join('');
            }
        }

        // Filter companies using server-side search
        function filterCompanies() {
            selectedCompanyIds.clear();
            updateBulkDeleteButton();
            loadCompanies(1);
        }

        // Toggle company selection
        function toggleCompanySelect(id, checked) {
            if (checked) {
                selectedCompanyIds.add(id);
            } else {
                selectedCompanyIds.delete(id);
            }
            // Uncheck "select all" when any individual is unchecked
            if (!checked) {
                document.getElementById('check-all-companies').checked = false;
            } else {
                // Check if all visible checkboxes are checked
                const allCheckboxes = document.querySelectorAll('.company-checkbox');
                const allChecked = allCheckboxes.length > 0 && Array.from(allCheckboxes).every(cb => cb.checked);
                document.getElementById('check-all-companies').checked = allChecked;
            }
            updateBulkDeleteButton();
        }

        // Toggle all companies on current page
        function toggleAllCompanies(checked) {
            document.querySelectorAll('.company-checkbox').forEach(cb => {
                cb.checked = checked;
                const id = parseInt(cb.value);
                if (checked) {
                    selectedCompanyIds.add(id);
                } else {
                    selectedCompanyIds.delete(id);
                }
            });
            updateBulkDeleteButton();
            // Re-render to update row highlight
            renderCompanies();
        }

        // Update bulk delete button visibility
        function updateBulkDeleteButton() {
            const btn = document.getElementById('btn-bulk-delete-companies');
            if (btn) {
                btn.classList.toggle('hidden', selectedCompanyIds.size === 0);
                btn.innerHTML = `<span class="material-symbols-outlined text-[20px]">delete_sweep</span> Eliminar seleccionadas (${selectedCompanyIds.size})`;
            }
        }

        // Bulk delete companies
        function bulkDeleteCompanies() {
            const ids = Array.from(selectedCompanyIds);
            if (ids.length === 0) {
                showToast('No hay empresas seleccionadas.', 'warning');
                return;
            }

            if (!confirm(`¿Estás seguro de que deseas eliminar permanentemente ${ids.length} empresa(s) y todos sus usuarios/ofertas asociados?`)) {
                return;
            }

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/admin/companies/bulk-delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ ids })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message);
                        selectedCompanyIds.clear();
                        updateBulkDeleteButton();
                        loadCompanies(currentCompaniesPage);
                    } else {
                        showToast(data.message || 'Error al eliminar empresas.', 'error');
                    }
                })
                .catch(err => {
                    showToast('Error de red al intentar eliminar.', 'error');
                });
        }

        // Render pagination controls
        function renderCompaniesPagination() {
            const paginationDiv = document.getElementById('companies-pagination');
            const infoSpan = document.getElementById('companies-pagination-info');
            const indicator = document.getElementById('companies-page-indicator');
            const prevBtn = document.getElementById('companies-prev-page');
            const nextBtn = document.getElementById('companies-next-page');

            if (!paginationDiv || !companiesPagination) {
                if (paginationDiv) paginationDiv.classList.add('hidden');
                return;
            }

            const { current_page, last_page, total, from, to } = companiesPagination;

            if (total <= 15) {
                paginationDiv.classList.add('hidden');
                return;
            }

            paginationDiv.classList.remove('hidden');
            infoSpan.textContent = `Mostrando ${from || 0}–${to || 0} de ${total} empresas`;
            indicator.textContent = `Página ${current_page} de ${last_page}`;
            prevBtn.disabled = current_page <= 1;
            nextBtn.disabled = current_page >= last_page;
        }

        // Change page
        function changeCompaniesPage(page) {
            if (page < 1) return;
            if (companiesPagination && page > companiesPagination.last_page) return;
            currentCompaniesPage = page;
            selectedCompanyIds.clear();
            updateBulkDeleteButton();
            loadCompanies(page);
        }

        // Toggle Company Actions menu
        function toggleCompanyActions(event, id, isVerified) {
            event.stopPropagation();
            currentCompanyId = id;
            const btn = event.currentTarget;
            const rect = btn.getBoundingClientRect();
            const dropdown = document.getElementById('company-actions-dropdown');

            // Update menu text & icon based on state
            const verifyText = dropdown.querySelector('#comp-verify-menu-text');
            const verifyIcon = dropdown.querySelector('#comp-verify-menu-icon');
            if (isVerified) {
                verifyText.textContent = 'Quitar Verificación';
                verifyIcon.textContent = 'block';
            } else {
                verifyText.textContent = 'Verificar Empresa';
                verifyIcon.textContent = 'verified_user';
            }

            dropdown.style.top = `${rect.bottom + window.scrollY + 5}px`;
            dropdown.style.left = `${rect.left + window.scrollX - 170}px`;
            dropdown.classList.remove('hidden');

            document.addEventListener('click', closeCompanyActions);
        }

        function closeCompanyActions() {
            const dropdown = document.getElementById('company-actions-dropdown');
            if (dropdown) {
                dropdown.classList.add('hidden');
            }
            document.removeEventListener('click', closeCompanyActions);
        }

        // Handle company dropdown actions
        function handleCompanyAction(action) {
            if (!currentCompanyId) return;

            switch (action) {
                case 'toggle-verify':
                    toggleCompanyVerify(currentCompanyId);
                    break;
                case 'edit':
                    editCompany(currentCompanyId);
                    break;
                case 'delete':
                    deleteCompany(currentCompanyId);
                    break;
            }
        }

        // Toggle Verification State
        function toggleCompanyVerify(id) {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/admin/companies/${id}/toggle-verify`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message);
                        loadCompanies(); // Reload list
                    } else {
                        showToast(data.message || 'Error al cambiar verificación.', 'error');
                    }
                })
                .catch(err => {
                    showToast('Error de red al intentar verificar.', 'error');
                });
        }

        // Delete Company
        function deleteCompany(id) {
            const company = companiesList.find(c => c.id === id);
            const name = company ? company.name : 'esta empresa';

            if (confirm(`¿Estás seguro de que deseas eliminar permanentemente a "${name}" y todos sus usuarios/ofertas asociados?`)) {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/admin/companies/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.message);
                            // Animate card removal
                            const card = document.getElementById(`company-card-${id}`);
                            if (card) {
                                card.style.opacity = '0';
                                card.style.transform = 'scale(0.9)';
                                setTimeout(() => {
                                    loadCompanies();
                                }, 200);
                            } else {
                                loadCompanies();
                            }
                        } else {
                            showToast(data.message || 'Error al eliminar empresa.', 'error');
                        }
                    })
                    .catch(err => {
                        showToast('Error de red al intentar eliminar.', 'error');
                    });
            }
        }

        // Edit Company - Loads details and switch tab
        function editCompany(id) {
            const company = companiesList.find(c => c.id === id);
            if (!company) return;

            editingCompanyMode = true;

            document.getElementById('form-company-id').value = company.id;
            document.getElementById('comp-name').value = company.name;
            document.getElementById('comp-ruc').value = company.ruc;
            document.getElementById('comp-email').value = company.email;
            document.getElementById('comp-phone').value = company.phone || '';
            document.getElementById('comp-address').value = company.address || '';
            document.getElementById('comp-mailbox').value = company.mailbox || '';
            document.getElementById('comp-website').value = company.website || '';
            document.getElementById('comp-description').value = company.description || '';

            if (company.logo) {
                document.getElementById('comp-logo-preview').src = company.logo;
            } else {
                document.getElementById('comp-logo-preview').src = 'https://placehold.co/100x100?text=Logo';
            }

            // Hide user linkage field during edit
            const userIdContainer = document.getElementById('container-comp-user-id');
            if (userIdContainer) userIdContainer.classList.add('hidden');

            const emailInput = document.getElementById('comp-email');
            if (emailInput) {
                emailInput.readOnly = false;
                emailInput.classList.remove('bg-surface-container-low', 'cursor-not-allowed');
            }

            // Change Form Visual titles & button
            document.getElementById('companies-form-title').textContent = 'Editar Perfil de Empresa';
            document.getElementById('btn-save-company').textContent = 'Guardar Cambios';

            // Switch to the form view
            switchTab('companies-register');
        }

        // Reset Form to blank (For New Registrations)
        function resetCompanyForm() {
            const form = document.getElementById('companies-form');
            if (form) form.reset();
            document.getElementById('form-company-id').value = '';

            const userIdContainer = document.getElementById('container-comp-user-id');
            if (userIdContainer) userIdContainer.classList.remove('hidden');

            const emailInput = document.getElementById('comp-email');
            if (emailInput) {
                emailInput.readOnly = false;
                emailInput.classList.remove('bg-surface-container-low', 'cursor-not-allowed');
            }

            document.getElementById('comp-logo-preview').src = 'https://placehold.co/100x100?text=Logo';
            document.getElementById('companies-form-title').textContent = 'Registrar Empresa';
            document.getElementById('btn-save-company').textContent = 'Registrar Empresa';
        }

        // Auto-fills form fields when an existing user is selected
        function handleSelectCompanyUser(select) {
            const option = select.options[select.selectedIndex];
            const emailInput = document.getElementById('comp-email');
            const nameInput = document.getElementById('comp-name');
            const rucInput = document.getElementById('comp-ruc');
            const phoneInput = document.getElementById('comp-phone');

            if (option && option.value) {
                const email = option.getAttribute('data-email');
                const name = option.getAttribute('data-name');
                const ruc = option.getAttribute('data-ruc');
                const phone = option.getAttribute('data-phone');

                if (email) {
                    emailInput.value = email;
                    emailInput.readOnly = true;
                    emailInput.classList.add('bg-surface-container-low', 'cursor-not-allowed');
                }
                if (name && name !== 'Usuario Empresa') {
                    nameInput.value = name;
                }
                if (ruc) {
                    rucInput.value = ruc;
                }
                if (phone) {
                    phoneInput.value = phone;
                }
            } else {
                emailInput.value = '';
                emailInput.readOnly = false;
                emailInput.classList.remove('bg-surface-container-low', 'cursor-not-allowed');

                nameInput.value = '';
                rucInput.value = '';
                phoneInput.value = '';
            }
        }

        // Submit Company Form (Creates or Updates)
        function handleCompanyFormSubmit(event) {
            event.preventDefault();

            const form = document.getElementById('companies-form');
            const formData = new FormData(form);
            const id = document.getElementById('form-company-id').value;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const url = id ? `/admin/companies/${id}` : '/admin/companies';

            const saveBtn = document.getElementById('btn-save-company');
            const originalText = saveBtn.textContent;
            saveBtn.textContent = 'Guardando...';
            saveBtn.disabled = true;

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    saveBtn.textContent = originalText;
                    saveBtn.disabled = false;

                    if (data.success) {
                        showToast(data.message);
                        switchTab('companies-manage');
                    } else {
                        showToast(data.message || 'Error al guardar empresa.', 'error');
                    }
                })
                .catch(err => {
                    saveBtn.textContent = originalText;
                    saveBtn.disabled = false;
                    showToast('Error de red al intentar guardar.', 'error');
                });
        }

        // ================= POSTULACIONES (APPLICATIONS) MODULE =================
        let applicationsList = [];

        // Load Applications from API
        function loadApplications() {
            const tbody = document.getElementById('applications-table-body');
            if (tbody) {
                tbody.innerHTML = '<tr><td colspan="6" class="p-8 text-center text-on-surface-variant">Cargando postulaciones...</td></tr>';
            }

            fetch('/admin/applications', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        applicationsList = data.applications || [];

                        // Populate Company Filter
                        const companyFilter = document.getElementById('filter-apps-company');
                        if (companyFilter) {
                            // Unique companies
                            const companies = {};
                            applicationsList.forEach(app => {
                                if (app.offer && app.offer.company) {
                                    companies[app.offer.company.id] = app.offer.company.name;
                                }
                            });

                            let optionsHtml = '<option value="">Todas las empresas</option>';
                            for (const [id, name] of Object.entries(companies)) {
                                optionsHtml += `<option value="${id}">${name}</option>`;
                            }
                            companyFilter.innerHTML = optionsHtml;
                        }

                        renderApplications(applicationsList);
                    } else {
                        showToast(data.message || 'Error al cargar postulaciones.', 'error');
                    }
                })
                .catch(err => {
                    showToast('Error de red al cargar postulaciones.', 'error');
                });
        }

        // Render Applications Table Rows
        function renderApplications(apps) {
            const tbody = document.getElementById('applications-table-body');
            if (!tbody) return;

            // Reset master checkbox and bulk actions bar on render
            const selectAll = document.getElementById('select-all-apps');
            if (selectAll) selectAll.checked = false;
            const bulkBar = document.getElementById('apps-bulk-actions');
            if (bulkBar) bulkBar.classList.add('hidden');

            if (apps.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="p-8 text-center text-on-surface-variant">No se encontraron postulaciones.</td></tr>';
                return;
            }

            let html = '';
            apps.forEach(app => {
                const dateStr = app.created_at ? new Date(app.created_at).toLocaleDateString('es-PE', { day: 'numeric', month: 'short', year: 'numeric' }) : '-';

                // Status classes & text
                let statusBadge = 'bg-surface-container text-on-surface-variant';
                let statusLabel = 'Postulado';
                if (app.status === 'postulated') {
                    statusBadge = 'bg-primary-fixed text-primary';
                    statusLabel = 'Postulado';
                } else if (app.status === 'under_review') {
                    statusBadge = 'bg-amber-100 text-amber-800';
                    statusLabel = 'En revisión';
                } else if (app.status === 'accepted') {
                    statusBadge = 'bg-green-100 text-green-800';
                    statusLabel = 'Aceptado';
                } else if (app.status === 'rejected') {
                    statusBadge = 'bg-red-100 text-red-800';
                    statusLabel = 'Rechazado';
                }

                const offerTitle = app.offer ? app.offer.title : 'Puesto Desconocido';
                const companyName = (app.offer && app.offer.company) ? app.offer.company.name : 'Empresa Desconocida';

                html += `
                <tr id="app-row-${app.id}" class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors">
                    <td class="p-4">
                        <input type="checkbox" value="${app.id}" onchange="updateSelectedApps()" class="app-checkbox rounded border-outline-variant focus:ring-primary text-primary cursor-pointer">
                    </td>
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-surface-container flex items-center justify-center text-primary font-bold text-label-md shrink-0">
                                ${app.fullname ? app.fullname.charAt(0).toUpperCase() : 'C'}
                            </div>
                            <div>
                                <span class="font-medium text-on-background block leading-tight">${app.fullname || 'Candidato'}</span>
                                <span class="text-body-sm text-on-surface-variant text-[13px]">${app.user ? app.user.email : ''}</span>
                            </div>
                        </div>
                    </td>
                    <td class="p-4 text-on-surface-variant text-body-sm">${app.program_study || '-'}</td>
                    <td class="p-4">
                        <span class="font-medium text-on-background block leading-tight text-body-sm">${offerTitle}</span>
                        <span class="text-[11px] text-on-surface-variant font-semibold uppercase tracking-wider">${companyName}</span>
                    </td>
                    <td class="p-4 text-on-surface-variant text-body-sm">${dateStr}</td>
                    <td class="p-4">
                        <span class="${statusBadge} px-2.5 py-1 rounded-full text-label-sm font-label-sm font-semibold inline-block">
                            ${statusLabel}
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button onclick="openAppDetail(${app.id})" class="p-2 hover:bg-surface-container-high rounded-full text-primary flex items-center justify-center transition-colors" title="Ver Detalle / Evaluar">
                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                            </button>
                            <button onclick="deleteApplication(${app.id})" class="p-2 hover:bg-red-50 rounded-full text-red-600 flex items-center justify-center transition-colors" title="Eliminar Postulación">
                                <span class="material-symbols-outlined text-[20px]">delete</span>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            });
            tbody.innerHTML = html;
        }

        // Filter Applications
        function filterApplications() {
            const query = document.getElementById('search-apps-input').value.toLowerCase();
            const status = document.getElementById('filter-apps-status').value;
            const companyId = document.getElementById('filter-apps-company').value;

            const filtered = applicationsList.filter(app => {
                const matchQuery = (app.fullname || '').toLowerCase().includes(query) ||
                    (app.offer ? app.offer.title : '').toLowerCase().includes(query) ||
                    (app.program_study || '').toLowerCase().includes(query);

                const matchStatus = !status || app.status === status;

                const matchCompany = !companyId || (app.offer && app.offer.company && app.offer.company.id.toString() === companyId);

                return matchQuery && matchStatus && matchCompany;
            });

            renderApplications(filtered);
        }

        // Toggle Modal
        function toggleAppModal() {
            const modal = document.getElementById('view-app-modal');
            const modalContainer = modal.querySelector('.max-w-lg');

            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                setTimeout(() => modalContainer.classList.remove('scale-95'), 10);
            } else {
                modalContainer.classList.add('scale-95');
                setTimeout(() => modal.classList.add('hidden'), 300);
            }
        }

        // Open detail modal and fill fields
        function openAppDetail(id) {
            const app = applicationsList.find(a => a.id === id);
            if (!app) return;

            document.getElementById('detail-app-id').value = app.id;
            document.getElementById('detail-app-name').textContent = app.fullname || 'Candidato';
            document.getElementById('detail-app-program').textContent = app.program_study || '-';
            document.getElementById('detail-app-offer').textContent = app.offer ? app.offer.title : 'Puesto Desconocido';
            document.getElementById('detail-app-company').textContent = (app.offer && app.offer.company) ? app.offer.company.name : 'Empresa Desconocida';

            // CV details
            const cvLink = document.getElementById('detail-app-cv-link');
            const cvName = document.getElementById('detail-app-cv-name');
            if (app.cv) {
                cvLink.href = app.cv;
                cvLink.classList.remove('hidden');
                const parts = app.cv.split('/');
                cvName.textContent = parts[parts.length - 1];
            } else {
                cvLink.href = '#';
                cvLink.classList.add('hidden');
                cvName.textContent = 'No se adjuntó CV';
            }

            // Message
            const msgDiv = document.getElementById('detail-app-message');
            msgDiv.textContent = app.message || 'No se adjuntó mensaje de presentación.';

            // Status & feedback
            document.getElementById('detail-app-status').value = app.status || 'postulated';
            document.getElementById('detail-app-feedback').value = app.feedback || '';

            const feedbackDate = document.getElementById('detail-app-feedback-date');
            if (app.feedback_date) {
                const dateStr = new Date(app.feedback_date).toLocaleDateString('es-PE', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
                feedbackDate.textContent = `Última retroalimentación: ${dateStr}`;
                feedbackDate.classList.remove('hidden');
            } else {
                feedbackDate.classList.add('hidden');
            }

            toggleAppModal();
        }

        // Submit App Status change
        function handleAppStatusSubmit(event) {
            event.preventDefault();

            const id = document.getElementById('detail-app-id').value;
            const status = document.getElementById('detail-app-status').value;
            const feedback = document.getElementById('detail-app-feedback').value;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const saveBtn = document.getElementById('btn-save-app-status');
            const originalText = saveBtn.textContent;
            saveBtn.textContent = 'Guardando...';
            saveBtn.disabled = true;

            fetch(`/admin/applications/${id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ status, feedback })
            })
                .then(res => res.json())
                .then(data => {
                    saveBtn.textContent = originalText;
                    saveBtn.disabled = false;

                    if (data.success) {
                        showToast(data.message);
                        toggleAppModal();
                        loadApplications(); // reload
                    } else {
                        showToast(data.message || 'Error al guardar estado de postulación.', 'error');
                    }
                })
                .catch(err => {
                    saveBtn.textContent = originalText;
                    saveBtn.disabled = false;
                    showToast('Error de red al intentar guardar.', 'error');
                });
        }

        // Delete Application
        function deleteApplication(id) {
            if (confirm('¿Está seguro de que desea eliminar permanentemente esta postulación? Esta acción no se puede deshacer.')) {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/admin/applications/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.message);
                            const row = document.getElementById(`app-row-${id}`);
                            if (row) {
                                row.classList.add('opacity-0', 'transition-opacity', 'duration-300');
                                setTimeout(() => row.remove(), 300);
                            }
                            // update local state
                            applicationsList = applicationsList.filter(a => a.id !== id);
                            updateSelectedApps(); // Recalculate selections in case it was checked
                        } else {
                            showToast(data.message || 'Error al eliminar postulación.', 'error');
                        }
                    })
                    .catch(err => {
                        showToast('Error de red al intentar eliminar.', 'error');
                    });
            }
        }

        // Toggle all checkboxes in the applications list
        function toggleSelectAllApps(master) {
            const checkboxes = document.querySelectorAll('.app-checkbox');
            checkboxes.forEach(cb => cb.checked = master.checked);
            updateSelectedApps();
        }

        // Update selected items count and show/hide bulk bar
        function updateSelectedApps() {
            const checkboxes = document.querySelectorAll('.app-checkbox:checked');
            const count = checkboxes.length;
            
            const bulkBar = document.getElementById('apps-bulk-actions');
            const countLabel = document.getElementById('apps-selected-count');
            const selectAll = document.getElementById('select-all-apps');
            const allCheckboxes = document.querySelectorAll('.app-checkbox');

            if (count > 0) {
                bulkBar.classList.remove('hidden');
                bulkBar.classList.add('flex');
                countLabel.textContent = `${count} ${count === 1 ? 'postulación seleccionada' : 'postulaciones seleccionadas'}`;
            } else {
                bulkBar.classList.add('hidden');
                bulkBar.classList.remove('flex');
            }

            // Sync select-all master checkbox state
            if (selectAll && allCheckboxes.length > 0) {
                selectAll.checked = (count === allCheckboxes.length);
            }
        }

        // Bulk Delete selected applications
        function bulkDeleteApplications() {
            const checkboxes = document.querySelectorAll('.app-checkbox:checked');
            const ids = Array.from(checkboxes).map(cb => parseInt(cb.value));

            if (ids.length === 0) return;

            if (confirm(`¿Está seguro de que desea eliminar permanentemente estas ${ids.length} postulaciones? Esta acción no se puede deshacer.`)) {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const bulkBar = document.getElementById('apps-bulk-actions');

                // disable button
                const btn = bulkBar.querySelector('button');
                const originalHtml = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">progress_activity</span> Eliminando...';

                fetch('/admin/applications/bulk-delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ ids: ids })
                })
                    .then(res => res.json())
                    .then(data => {
                        btn.disabled = false;
                        btn.innerHTML = originalHtml;

                        if (data.success) {
                            showToast(data.message);
                            loadApplications(); // reload completely
                        } else {
                            showToast(data.message || 'Error al eliminar postulaciones en masa.', 'error');
                        }
                    })
                    .catch(err => {
                        btn.disabled = false;
                        btn.innerHTML = originalHtml;
                        showToast('Error de red al intentar eliminar en masa.', 'error');
                    });
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Toggle Companies Dropdown
            const toggleCompaniesBtn = document.getElementById('toggle-companies-btn');
            const companiesSubmenu = document.getElementById('companies-submenu');
            const companiesArrow = document.getElementById('companies-arrow');

            if (toggleCompaniesBtn) {
                toggleCompaniesBtn.addEventListener('click', function () {
                    if (companiesSubmenu.classList.contains('hidden')) {
                        companiesSubmenu.classList.remove('hidden');
                        companiesArrow.style.transform = 'rotate(0deg)';
                    } else {
                        companiesSubmenu.classList.add('hidden');
                        companiesArrow.style.transform = 'rotate(-90deg)';
                    }
                });
            }

            // Mobile Sidebar Toggles
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

            // Add click events to tab buttons
            const tabBtns = document.querySelectorAll('.tab-btn');
            tabBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const tabId = this.getAttribute('data-tab');
                    switchTab(tabId);
                });
            });

            // Drag and drop events for dropzone
            const dropzone = document.getElementById('dropzone');
            const fileInput = document.getElementById('excel-file-input');

            if (dropzone) {
                ['dragenter', 'dragover'].forEach(eventName => {
                    dropzone.addEventListener(eventName, (e) => {
                        e.preventDefault();
                        dropzone.classList.add('border-primary', 'bg-surface-container-high');
                    }, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dropzone.addEventListener(eventName, (e) => {
                        e.preventDefault();
                        dropzone.classList.remove('border-primary', 'bg-surface-container-high');
                    }, false);
                });

                dropzone.addEventListener('drop', (e) => {
                    const dt = e.dataTransfer;
                    const files = dt.files;
                    fileInput.files = files;
                    handleExcelFileSelect(fileInput);
                }, false);
            }

            // Restore the last section visited after refreshing the page.
            const urlParams = new URLSearchParams(window.location.search);
            let tabToOpen = urlParams.get('tab') || getStoredAdminValue(ADMIN_ACTIVE_TAB_KEY);
            if (urlParams.has('page') || urlParams.has('search') || urlParams.has('rol_id')) {
                tabToOpen = 'users';
            }
            switchTab(ADMIN_VALID_TABS.includes(tabToOpen) ? tabToOpen : 'dashboard');
        });
    </script>
</body>

</html>
