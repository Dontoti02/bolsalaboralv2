<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Portal Docente</title>
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
<body class="bg-background text-on-background min-h-screen flex flex-col">

<!-- TopNavBar -->
<header class="sticky top-0 w-full bg-surface-bright border-b border-outline-variant z-20 h-[72px]">
    <div class="flex justify-between items-center px-lg md:px-2xl h-full max-w-container-max mx-auto w-full">
        <!-- Institution Logo & Brand -->
        <div class="flex items-center gap-3 shrink-0">
            <img src="{{ $config['logo'] ?? '/assets/logo.png' }}" class="h-10 w-auto object-contain" alt="Logo">
            <div class="hidden sm:block whitespace-nowrap">
                <p class="text-body-sm font-bold text-on-surface leading-tight">{{ $config['application_name'] ?? 'Bolsa Laboral' }}</p>
                <span class="text-[10px] text-on-surface-variant uppercase font-bold tracking-wider block">Portal Docente</span>
            </div>
        </div>

        <!-- Search Bar in center -->
        <div class="relative flex-1 max-w-md mx-6">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px] pointer-events-none">search</span>
            <input id="search-input" oninput="filterJobs(this.value)" class="w-full pl-10 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-body-sm font-body-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" placeholder="Buscar por título o empresa..." type="text">
        </div>

        <!-- Trailing Actions & Profile -->
        <div class="flex items-center gap-4 shrink-0">
            <div class="flex items-center gap-3">
                <div class="text-right hidden md:block whitespace-nowrap">
                    <p class="text-body-sm font-semibold text-on-surface leading-none">{{ Auth::user()->person->names ?? 'Docente' }}</p>
                    <span class="text-[11px] text-on-surface-variant font-medium">Docente</span>
                </div>
                <div class="w-10 h-10 rounded-full overflow-hidden border border-outline-variant bg-primary-container text-on-primary flex items-center justify-center font-bold text-body-lg">
                    {{ substr(Auth::user()->person->names ?? 'D', 0, 1) }}
                </div>
            </div>
            <div class="w-px h-6 bg-outline-variant"></div>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-error hover:bg-error/10 hover:text-error-container rounded-xl px-3 py-2 flex items-center gap-1.5 transition-all text-label-sm font-semibold whitespace-nowrap" title="Cerrar Sesión">
                    <span class="material-symbols-outlined text-[20px]">logout</span>
                    <span class="hidden sm:inline">Cerrar Sesión</span>
                </button>
            </form>
        </div>
    </div>
</header>

<!-- Main Content Wrapper -->
<div class="flex-1 flex flex-col w-full min-h-screen">
    <!-- Main Dashboard Canvas -->
    <main class="flex-1 p-lg md:p-2xl w-full max-w-container-max mx-auto space-y-xl">
        
        <!-- Job List Page Container -->
        <div id="panel-job-list" class="space-y-xl">
            <!-- Page Header -->
            <div>
                <h1 class="font-headline-lg text-headline-lg text-on-surface mb-xs">Ofertas Laborales</h1>
                <p class="font-body-md text-body-md text-on-surface-variant">Monitorea y comparte las convocatorias vigentes con tus estudiantes.</p>
            </div>
            
            <!-- Job Grid (2 cards per row on larger screens) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-lg">
                @forelse($activeOffers as $offer)
                <div onclick="openJobDetailPage({{ $offer->id }})" 
                     data-title="{{ $offer->title }}" 
                     data-company="{{ $offer->company_name }}"
                     class="job-card bg-surface-container-lowest rounded-xl p-lg border border-outline-variant shadow-sm hover:border-primary transition-colors flex flex-col justify-between cursor-pointer group">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <div class="w-12 h-12 bg-surface-container rounded-lg flex items-center justify-center border border-outline-variant overflow-hidden shrink-0">
                                @if(!empty($offer->company->logo))
                                    <img src="{{ $offer->company->logo }}" class="w-full h-full object-contain" alt="Logo">
                                @else
                                    <span class="material-symbols-outlined text-on-surface-variant text-[24px]">corporate_fare</span>
                                @endif
                            </div>
                            <span class="bg-secondary-fixed/20 text-on-secondary-container px-2 py-0.5 rounded text-label-sm font-label-sm font-semibold">{{ $offer->state_name }}</span>
                        </div>
                        <h3 class="font-headline-sm text-[18px] text-on-surface mb-1 leading-tight group-hover:text-primary transition-colors">{{ $offer->title }}</h3>
                        <p class="text-body-sm text-on-surface-variant mb-4 font-medium">{{ $offer->company_name }} • {{ $offer->location_name }}</p>
                        <p class="text-body-sm text-on-surface-variant line-clamp-3 mb-4 leading-relaxed">{{ $offer->description }}</p>
                    </div>
                    <div class="border-t border-outline-variant pt-4 flex items-center justify-between mt-auto">
                        <span class="text-[11px] text-outline font-medium">Publicado el {{ $offer->publication_formatted }}</span>
                        <button onclick="event.stopPropagation(); shareJob('{{ addslashes($offer->title) }}')" class="text-primary hover:bg-primary/5 font-label-md text-label-md px-3.5 py-1.5 rounded-lg border border-outline-variant flex items-center gap-1 font-semibold transition-all">
                            <span class="material-symbols-outlined text-[16px]">share</span>
                            Compartir
                        </button>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-xl text-on-surface-variant">
                    <span class="material-symbols-outlined text-4xl text-outline mb-2 block">work_off</span>
                    <p class="font-semibold">No hay ofertas activas disponibles</p>
                    <p class="text-body-sm mt-1">Las nuevas ofertas aparecerán aquí.</p>
                </div>
                @endforelse

                @foreach($closedOffers as $offer)
                <div onclick="openJobDetailPage({{ $offer->id }})" 
                     data-title="{{ $offer->title }}" 
                     data-company="{{ $offer->company_name }}"
                     class="job-card bg-surface-container-lowest rounded-xl p-lg border border-outline-variant shadow-sm hover:border-primary transition-colors flex flex-col justify-between cursor-pointer group opacity-90">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <div class="w-12 h-12 bg-surface-container rounded-lg flex items-center justify-center border border-outline-variant overflow-hidden shrink-0">
                                @if(!empty($offer->company->logo))
                                    <img src="{{ $offer->company->logo }}" class="w-full h-full object-contain" alt="Logo">
                                @else
                                    <span class="material-symbols-outlined text-on-surface-variant text-[24px]">corporate_fare</span>
                                @endif
                            </div>
                            <span class="bg-surface-container text-on-surface-variant px-2 py-0.5 rounded text-label-sm font-label-sm font-semibold">{{ $offer->state_name }}</span>
                        </div>
                        <h3 class="font-headline-sm text-[18px] text-on-surface mb-1 leading-tight group-hover:text-primary transition-colors">{{ $offer->title }}</h3>
                        <p class="text-body-sm text-on-surface-variant mb-4 font-medium">{{ $offer->company_name }} • {{ $offer->location_name }}</p>
                        <p class="text-body-sm text-on-surface-variant line-clamp-3 mb-4 leading-relaxed">Oferta finalizada. No se aceptan más postulaciones.</p>
                    </div>
                    <div class="border-t border-outline-variant pt-4 flex items-center justify-between mt-auto">
                        <span class="text-[11px] text-outline font-medium">Publicado el {{ $offer->publication_formatted }}</span>
                        <button onclick="event.stopPropagation(); shareJob('{{ addslashes($offer->title) }}')" class="text-primary hover:bg-primary/5 font-label-md text-label-md px-3.5 py-1.5 rounded-lg border border-outline-variant flex items-center gap-1 font-semibold transition-all">
                            <span class="material-symbols-outlined text-[16px]">share</span>
                            Compartir
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Job Detail Page Container -->
        <div id="panel-job-detail" class="hidden space-y-lg">
            <!-- Header with Back button -->
            <div class="flex items-center gap-md">
                <button onclick="closeJobDetailPage()" class="px-4 py-2 border border-outline-variant text-on-surface hover:bg-surface-container-high rounded-xl text-label-sm font-semibold transition-colors flex items-center gap-1.5 shadow-sm bg-surface-bright">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    Volver al listado
                </button>
            </div>

            <!-- Two-Column Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-lg">
                <!-- Column 1: Main details (2/3 width) -->
                <div class="lg:col-span-2 space-y-lg">
                    <!-- Top Banner / Headline -->
                    <div class="bg-surface-container-lowest p-lg rounded-2xl border border-outline-variant space-y-md shadow-sm">
                        <div class="flex items-center gap-md">
                            <div id="detail-company-logo" class="w-16 h-16 bg-surface-container rounded-xl flex items-center justify-center border border-outline-variant shrink-0 overflow-hidden">
                                <span class="material-symbols-outlined text-4xl text-on-surface-variant">corporate_fare</span>
                            </div>
                            <div>
                                <span id="detail-category" class="px-2.5 py-0.5 rounded-full bg-primary/10 text-primary font-bold text-xs uppercase">Categoría</span>
                                <h1 id="detail-title" class="text-headline-md font-headline-md text-primary font-black leading-tight mt-1">Título de la Oferta</h1>
                                <p id="detail-company-name" class="text-body-sm font-semibold text-on-surface-variant"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Description, Requirements, Benefits tabs -->
                    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
                        <div class="flex border-b border-outline-variant bg-surface-container-low/50">
                            <button id="tab-btn-detail" class="flex-1 py-3.5 px-4 font-bold text-label-sm border-b-2 border-primary text-primary transition-all">Detalle del empleo</button>
                        </div>
                        <div class="p-lg">
                            <!-- Detail content -->
                            <div id="tab-content-detail" class="space-y-lg">
                                <div>
                                    <h3 class="text-label-md font-bold text-on-surface-variant mb-2">Descripción del puesto</h3>
                                    <div id="detail-description" class="text-body-md text-on-surface space-y-2 leading-relaxed"></div>
                                </div>
                                <div>
                                    <h3 class="text-label-md font-bold text-on-surface-variant mb-2">Requisitos</h3>
                                    <div id="detail-requirements" class="text-body-md text-on-surface space-y-2 leading-relaxed"></div>
                                </div>
                                <div>
                                    <h3 class="text-label-md font-bold text-on-surface-variant mb-2">Beneficios</h3>
                                    <div id="detail-benefits" class="text-body-md text-on-surface space-y-2 leading-relaxed"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Column 2: Sidebar (1/3 width) -->
                <div class="lg:col-span-1 space-y-lg">
                    <!-- General Details Card -->
                    <div class="bg-surface-container-lowest p-lg rounded-2xl border border-outline-variant shadow-sm space-y-md">
                        <h2 class="text-headline-sm font-headline-sm text-on-surface">Información de la oferta</h2>
                        
                        <div class="space-y-sm">
                            <div class="flex items-center gap-3 py-2 border-b border-outline-variant/50">
                                <span class="material-symbols-outlined text-outline">schedule</span>
                                <div>
                                    <span class="block text-[11px] text-on-surface-variant uppercase font-semibold">Jornada</span>
                                    <span id="detail-schedule" class="font-bold text-body-sm text-on-surface">Jornada Completa</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 py-2 border-b border-outline-variant/50">
                                <span class="material-symbols-outlined text-outline">description</span>
                                <div>
                                    <span class="block text-[11px] text-on-surface-variant uppercase font-semibold">Tipo de Contrato</span>
                                    <span id="detail-contract" class="font-bold text-body-sm text-on-surface">Contrato</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 py-2 border-b border-outline-variant/50">
                                <span class="material-symbols-outlined text-outline">payments</span>
                                <div>
                                    <span class="block text-[11px] text-on-surface-variant uppercase font-semibold">Salario</span>
                                    <span id="detail-salary" class="font-bold text-body-sm text-on-surface text-green-700">S/. 1500.00</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 py-2 border-b border-outline-variant/50">
                                <span class="material-symbols-outlined text-outline">pin_drop</span>
                                <div>
                                    <span class="block text-[11px] text-on-surface-variant uppercase font-semibold">Ubicación</span>
                                    <span id="detail-location" class="font-bold text-body-sm text-on-surface">Ubicación</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 py-2">
                                <span class="material-symbols-outlined text-outline">calendar_today</span>
                                <div>
                                    <span class="block text-[11px] text-on-surface-variant uppercase font-semibold">Fecha Límite</span>
                                    <span id="detail-deadline" class="font-bold text-body-sm text-on-surface">Fecha</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Actions -->
                    <div class="bg-surface-container-lowest p-lg rounded-2xl border border-outline-variant shadow-sm space-y-md">
                        <button id="btn-share-job" class="w-full py-3 border border-outline-variant text-on-surface hover:bg-surface-container-high rounded-xl text-label-md font-semibold transition-colors flex items-center justify-center gap-1.5 shadow-sm bg-surface-bright">
                            <span class="material-symbols-outlined text-[20px]">share</span>
                            Compartir Empleo
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>

<!-- Simple Toast Notification for Sharing -->
<div id="toast" class="fixed bottom-5 right-5 bg-primary text-on-primary px-lg py-md rounded-xl shadow-lg transform translate-y-20 opacity-0 transition-all duration-300 z-50 flex items-center gap-sm">
    <span class="material-symbols-outlined">check_circle</span>
    <span id="toast-message" class="text-body-sm font-semibold">¡Enlace copiado al portapapeles!</span>
</div>

<script>
    // Serialized offers
    const activeOffersList = @json($activeOffers);
    const closedOffersList = @json($closedOffers);
    const allOffers = [...activeOffersList, ...closedOffersList];

    function filterJobs(query) {
        const q = query.toLowerCase().trim();
        const cards = document.querySelectorAll('.job-card');
        
        cards.forEach(card => {
            const title = card.getAttribute('data-title').toLowerCase();
            const company = card.getAttribute('data-company').toLowerCase();
            if (title.includes(q) || company.includes(q)) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });
    }

    function openJobDetailPage(id) {
        const offer = allOffers.find(o => o.id === id);
        if (!offer) return;

        // Populate details
        document.getElementById('detail-title').textContent = offer.title;
        document.getElementById('detail-company-name').textContent = offer.company_name;
        document.getElementById('detail-category').textContent = offer.category_name || 'General';
        
        const nl2br = (str) => str ? str.replace(/\n/g, '<br>') : 'No especificado';
        document.getElementById('detail-description').innerHTML = nl2br(offer.description);
        document.getElementById('detail-requirements').innerHTML = nl2br(offer.requirements);
        document.getElementById('detail-benefits').innerHTML = nl2br(offer.benefits);

        document.getElementById('detail-schedule').textContent = offer.schedule_name || 'Jornada completa';
        document.getElementById('detail-contract').textContent = offer.contractType ? offer.contractType.name : 'Contrato';
        
        const salaryVal = offer.salary ? parseFloat(offer.salary).toFixed(2) : null;
        document.getElementById('detail-salary').textContent = salaryVal ? `${salaryVal} ${offer.salary_currency === 'DOLARES' ? 'USD' : 'SOL'}` : 'Sin especificar';
        
        document.getElementById('detail-location').textContent = `${offer.location_name} (${offer.address || ''})`;
        
        const deadlineStr = offer.deadline_date ? new Date(offer.deadline_date).toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' }) : 'Vigente';
        document.getElementById('detail-deadline').textContent = deadlineStr;

        // Company Logo
        const logoContainer = document.getElementById('detail-company-logo');
        if (offer.company && offer.company.logo) {
            logoContainer.innerHTML = `<img src="${offer.company.logo}" class="w-full h-full object-contain" alt="logo" onerror="this.outerHTML='<span class=\'material-symbols-outlined text-4xl text-on-surface-variant\'>corporate_fare</span>'">`;
        } else {
            logoContainer.innerHTML = `<span class="material-symbols-outlined text-4xl text-on-surface-variant">corporate_fare</span>`;
        }

        // Configure share button
        const shareBtn = document.getElementById('btn-share-job');
        shareBtn.onclick = function() {
            shareJob(offer.title);
        };

        // Switch panels
        document.getElementById('panel-job-list').classList.add('hidden');
        document.getElementById('panel-job-detail').classList.remove('hidden');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function closeJobDetailPage() {
        document.getElementById('panel-job-detail').classList.add('hidden');
        document.getElementById('panel-job-list').classList.remove('hidden');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function shareJob(jobTitle) {
        const dummyUrl = window.location.origin + '/student/dashboard?offer_id=' + allOffers.find(o => o.title === jobTitle).id;
        
        navigator.clipboard.writeText(dummyUrl).then(() => {
            showToast('¡Enlace de "' + jobTitle + '" copiado para compartir!');
        }).catch(err => {
            showToast('¡Enlace copiado al portapapeles!');
        });
    }

    function showToast(message) {
        const toast = document.getElementById('toast');
        const toastMsg = document.getElementById('toast-message');
        toastMsg.textContent = message;
        
        toast.classList.remove('translate-y-20', 'opacity-0');
        
        setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0');
        }, 3000);
    }
</script>
</body>
</html>
