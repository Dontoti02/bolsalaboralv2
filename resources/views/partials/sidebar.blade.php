@php
    // Configuración por defecto del sidebar
    $sidebarConfig = $sidebarConfig ?? [];
    $sidebarLogo = $sidebarConfig['logo'] ?? ($config['logo'] ?? '/assets/logo.png');
    $sidebarBrand = $sidebarConfig['brand'] ?? 'Bolsa Laboral';
    $sidebarSubtitle = $sidebarConfig['subtitle'] ?? '';
    $sidebarActive = $sidebarConfig['active'] ?? '';
    $sidebarItems = $sidebarConfig['items'] ?? [];
    $sidebarShowPublish = $sidebarConfig['show_publish'] ?? false;
    $sidebarPublishTab = $sidebarConfig['publish_tab'] ?? 'offers';
    $sidebarPublishLabel = $sidebarConfig['publish_label'] ?? 'Publicar Oferta';
    $sidebarShowHelp = $sidebarConfig['show_help'] ?? true;
    $sidebarHelpLabel = $sidebarConfig['help_label'] ?? 'Soporte';
    $sidebarHelpTab = $sidebarConfig['help_tab'] ?? null;
    $sidebarHelpUrl = $sidebarConfig['help_url'] ?? null;
@endphp

<!-- SideNavBar -->
<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-40 w-64 bg-surface border-r border-outline-variant flex flex-col h-full py-lg px-md transform -translate-x-full md:translate-x-0 md:sticky md:h-screen transition-transform duration-300 ease-in-out">

    <div class="mb-lg px-4 flex items-center justify-center relative w-full">
        <div class="cursor-pointer py-1 flex items-center justify-center"
            onclick="switchTab('{{ $sidebarConfig['home_tab'] ?? 'dashboard' }}')">
            <img src="{{ $sidebarLogo }}" alt="Logo de la institución" class="h-10 max-w-[180px] object-contain"
                onerror="this.onerror=null;this.src='/assets/logo.png';">
        </div>
        <!-- Close sidebar button for mobile -->
        <button type="button" id="close-sidebar-btn"
            class="md:hidden text-on-surface-variant hover:bg-surface-variant p-1 rounded-full absolute right-2 top-1/2 -translate-y-1/2">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>

    @if($sidebarShowPublish)
        <button type="button" onclick="switchTab('{{ $sidebarPublishTab }}')"
            class="mb-lg w-full bg-primary text-on-primary text-label-md font-label-md py-3 rounded-lg hover:bg-primary/90 transition-colors shadow-sm flex items-center justify-center gap-2">
            <span class="material-symbols-outlined">add</span>
            <span class="sidebar-label">{{ $sidebarPublishLabel }}</span>
        </button>
    @endif

    <nav class="flex-1 overflow-y-auto space-y-6">
        @foreach($sidebarItems as $group)
            <div>
                @if(!empty($group['label']))
                    <p class="sidebar-section-label px-4 text-label-sm font-label-sm text-on-surface-variant uppercase tracking-wider mb-2">
                        {{ $group['label'] }}
                    </p>
                @endif
                @foreach($group['items'] ?? [] as $item)
                    @php
                        $isActive = ($sidebarActive === ($item['key'] ?? ''));
                        $baseClasses = 'tab-btn w-full flex items-center gap-3 px-4 py-3 scale-95 active:scale-90 transition-all text-left';
                        $stateClasses = $isActive
                            ? 'bg-surface-container-high text-on-surface font-semibold border-l-4 border-primary rounded-r-lg rounded-l-none shadow-sm'
                            : 'text-on-surface-variant hover:bg-surface-container-high rounded-lg transition-colors duration-200';
                    @endphp
                    <button type="button" data-tab="{{ $item['key'] ?? '' }}" class="{{ $baseClasses }} {{ $stateClasses }}">
                        <span class="material-symbols-outlined">{{ $item['icon'] ?? 'circle' }}</span>
                        <span
                            class="sidebar-label text-label-md font-label-md {{ $isActive ? 'font-semibold' : '' }}">{{ $item['label'] ?? '' }}</span>
                    </button>
                @endforeach
            </div>
        @endforeach
    </nav>

    <div class="mt-auto pt-4 border-t border-outline-variant space-y-1">
        @if($sidebarShowHelp)
            @if($sidebarHelpTab)
                <button type="button" data-tab="{{ $sidebarHelpTab }}"
                    class="tab-btn w-full flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:bg-surface-container-high transition-colors duration-200 rounded-lg scale-95 active:scale-90 transition-transform text-left">
                    <span class="material-symbols-outlined">help</span>
                    <span class="sidebar-label text-label-md font-label-md">{{ $sidebarHelpLabel }}</span>
                </button>
            @elseif($sidebarHelpUrl)
                <a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:bg-surface-container-high transition-colors duration-200 rounded-lg scale-95 active:scale-90 transition-transform"
                    href="{{ $sidebarHelpUrl }}">
                    <span class="material-symbols-outlined">help</span>
                    <span class="sidebar-label text-label-md font-label-md">{{ $sidebarHelpLabel }}</span>
                </a>
            @else
                <button type="button"
                    onclick="if (typeof showToast === 'function') { showToast('El centro de ayuda aún no está configurado.', 'warning'); } else { alert('El centro de ayuda aún no está configurado.'); }"
                    class="w-full flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:bg-surface-container-high transition-colors duration-200 rounded-lg scale-95 active:scale-90 transition-transform text-left">
                    <span class="material-symbols-outlined">help</span>
                    <span class="sidebar-label text-label-md font-label-md">{{ $sidebarHelpLabel }}</span>
                </button>
            @endif
        @endif
        <a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:bg-surface-container-high transition-colors duration-200 rounded-lg scale-95 active:scale-90 transition-transform"
            href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <span class="material-symbols-outlined">logout</span>
            <span class="sidebar-label text-label-md font-label-md">Cerrar Sesión</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>
</aside>

<!-- Backdrop for mobile sidebar -->
<div id="sidebar-backdrop" class="fixed inset-0 bg-black/40 z-30 hidden md:hidden"></div>
