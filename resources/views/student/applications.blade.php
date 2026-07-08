<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $config['application_name'] ?? 'Bolsa Laboral' }} — Mis Postulaciones</title>
    <link rel="icon" href="{{ $config['favicon'] ?? '/assets/favicon.png' }}">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL@20..48,100..700,0..1&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --pri:   {{ $config['primary_color']            ?? '#002741' }};
            --sec:   {{ $config['secondary_color']          ?? '#006b60' }};
            --secc:  {{ $config['secondary_container_color']?? '#7df7e4' }};
            --acc:   {{ $config['accent_color']             ?? '#f97316' }};
            --bg:    #f8fafc;
            --sur:   #ffffff;
            --bor:   #e2e8f0;
            --txt:   #0f172a;
            --tv:    #475569;
            --tm:    #94a3b8;
            --rad:   16px;
        }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--txt);-webkit-font-smoothing:antialiased}
        a{text-decoration:none;color:inherit}
        button{font-family:inherit;cursor:pointer}
        img{display:block;max-width:100%}
        .material-symbols-outlined{font-variation-settings:'FILL'0,'wght'400,'GRAD'0,'opsz'24;vertical-align:middle;line-height:1}
        .filled{font-variation-settings:'FILL'1,'wght'400,'GRAD'0,'opsz'24}

        /* ── TOPBAR ── */
        .topbar{background:var(--pri);color:#fff;font-size:12px;padding:6px 0;border-bottom:1px solid rgba(255,255,255,.1)}
        .topbar-inner{max-width:1200px;margin:0 auto;padding:0 20px;display:flex;align-items:center;justify-content:flex-end;gap:16px}
        .topbar-link{color:rgba(255,255,255,.8);font-size:12px;display:flex;align-items:center;gap:4px;transition:color .15s}
        .topbar-link:hover{color:#fff}

        /* ── NAVBAR ── */
        .navbar{background:var(--sur);border-bottom:1px solid var(--bor);position:sticky;top:0;z-index:100;box-shadow:0 1px 3px rgba(0,0,0,.04)}
        .navbar-inner{max-width:1200px;margin:0 auto;padding:0 20px;height:70px;display:flex;align-items:center;justify-content:between;gap:16px}
        .brand{display:flex;align-items:center;gap:8px;font-family:'Plus Jakarta Sans',sans-serif;font-size:20px;font-weight:800;color:var(--pri);flex-shrink:0}
        .brand img{height:38px;width:auto}

        .navbar-actions{display:flex;align-items:center;gap:12px}

        /* ── profile trigger ── */
        .profile-menu-wrap{position:relative}
        .profile-trigger{display:flex;align-items:center;gap:8px;padding:6px 12px;border:1px solid var(--bor);border-radius:50px;background:#fff;cursor:pointer;transition:all .15s}
        .profile-trigger:hover{border-color:var(--tm);background:var(--bg)}
        .profile-avatar{width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,var(--pri),#0a3452);color:#fff;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;overflow:hidden}
        .profile-avatar.lg{width:48px;height:48px;font-size:16px}
        .profile-name{font-size:13.5px;font-weight:500;color:var(--tv);max-width:120px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        
        .profile-dropdown{position:absolute;right:0;top:46px;width:260px;background:#fff;border:1px solid var(--bor);border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,.08);display:none;flex-direction:column;padding:8px 0;z-index:110}
        .profile-dropdown-header{display:flex;align-items:center;gap:12px;padding:12px 16px;border-bottom:1px solid var(--bor);margin-bottom:6px}
        .profile-dropdown-item{display:flex;align-items:center;gap:10px;padding:10px 16px;font-size:13.5px;color:var(--tv);background:none;border:none;width:100%;text-align:left;transition:background .15s}
        .profile-dropdown-item:hover{background:var(--bg);color:var(--txt)}
        .profile-dropdown-divider{height:1px;background:var(--bor);margin:6px 0}
        .cv-badge{background:var(--pri);color:#fff;font-size:10px;font-weight:700;padding:1px 6px;border-radius:10px;margin-left:auto}

        /* CONTAINER */
        .main-container{max-width:1200px;margin:40px auto;padding:0 20px;min-height:70vh}
        .page-header{display:flex;flex-direction:column;gap:6px;margin-bottom:32px}
        .page-title{font-family:'Plus Jakarta Sans',sans-serif;font-size:28px;font-weight:800;color:var(--pri);display:flex;align-items:center;gap:12px}
        .page-title span.count-badge{background:var(--secc);color:var(--sec);font-size:14px;font-weight:700;padding:4px 12px;border-radius:30px}
        .page-subtitle{font-size:14px;color:var(--tv)}

        /* JOB CARDS GRID (3 en 3) */
        .applications-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:24px}
        .app-card{background:var(--sur);border:1px solid var(--bor);border-radius:var(--rad);padding:24px;display:flex;flex-direction:column;gap:16px;transition:all .2s;box-shadow:0 2px 4px rgba(0,0,0,.02);min-height:280px}
        .app-card:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(0,0,0,.06);border-color:var(--tm)}
        
        .app-card-header{display:flex;align-items:center;justify-content:space-between;gap:12px;width:100%}
        .company-logo{width:48px;height:48px;border-radius:12px;border:1px solid var(--bor);background:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden}
        .company-logo img{width:100%;height:100%;object-fit:contain;padding:4px}
        .company-logo-text{width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,var(--pri),#0a3452);color:#fff;font-size:14px;font-weight:700}
        
        .job-details{flex:1;display:flex;flex-direction:column;min-width:0}
        .job-title{font-size:16px;font-weight:700;color:var(--txt);margin-bottom:4px;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;height:44px}
        .company-name-link{font-size:14px;font-weight:600;color:var(--sec);margin-bottom:8px}
        .company-meta{display:flex;align-items:center;flex-wrap:wrap;gap:8px 16px;font-size:12px;color:var(--tm)}
        .meta-item{display:flex;align-items:center;gap:4px}

        /* BADGES */
        .badge-app{font-size:11px;font-weight:600;padding:5px 12px;border-radius:50px;display:inline-flex;align-items:center;gap:4px}
        .badge-app.postulated{background:rgba(249,115,22,.1);color:var(--acc)}
        .badge-app.reviewed{background:rgba(0,39,65,.1);color:var(--pri)}
        .badge-app.selected{background:rgba(0,107,96,.1);color:var(--sec)}
        .badge-app.rejected{background:rgba(220,38,38,.1);color:#dc2626}
        .badge-app.finished{background:rgba(100,116,139,.1);color:#64748b}

        /* FEEDBACK PANEL */
        .feedback-box{background:#f8fafc;border-left:4px solid var(--pri);padding:10px 14px;border-radius:4px;font-size:12.5px;color:var(--txt);margin-top:auto}
        .feedback-title{font-weight:700;margin-bottom:4px;color:var(--pri);font-size:11px;text-transform:uppercase;letter-spacing:.04em;display:flex;align-items:center;gap:4px}
        .feedback-text{margin:0;font-style:italic;line-height:1.4}

        /* EMPTY STATE */
        .empty-state{text-align:center;padding:80px 20px;background:var(--sur);border:1px solid var(--bor);border-radius:var(--rad);box-shadow:0 2px 4px rgba(0,0,0,.02);grid-column:1/-1}
        .empty-state-icon{font-size:64px;color:var(--tm);margin-bottom:16px;display:block}
        .empty-state-title{font-family:'Plus Jakarta Sans',sans-serif;font-size:20px;font-weight:700;color:var(--pri);margin-bottom:8px}
        .empty-state-text{font-size:14px;color:var(--tv);margin-bottom:24px}
        .btn-action{display:inline-flex;align-items:center;gap:8px;background:var(--pri);color:#fff;font-weight:600;font-size:14px;padding:12px 24px;border:none;border-radius:12px;transition:opacity .15s}
        .btn-action:hover{opacity:.9}

        /* FOOTER */
        .footer{background:var(--sur);border-top:1px solid var(--bor);padding:20px;margin-top:auto}
        .footer-inner{max-width:1200px;margin:0 auto;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px}
        .footer-brand{font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;color:var(--pri);font-size:16px;display:flex;align-items:center;gap:8px}
        .footer-brand img{height:28px;width:auto}
        .footer-copy{font-size:12px;color:var(--tm)}

        @media (max-width:1024px) {
            .applications-grid{grid-template-columns:repeat(2,minmax(0,1fr))}
        }
        @media (max-width:640px) {
            .applications-grid{grid-template-columns:repeat(1,minmax(0,1fr))}
        }
    </style>
</head>
<body>

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="topbar-inner">
            <a href="/" class="topbar-link">
                <span class="material-symbols-outlined" style="font-size:16px">arrow_back</span>
                Volver al buscador de ofertas
            </a>
        </div>
    </div>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="/" class="brand">
                @if($config['logo'] ?? '')
                    <img src="{{ $config['logo'] }}" alt="Logo">
                @endif
                <span>{{ $config['application_name'] ?? 'Bolsa Laboral' }}</span>
            </a>

            <div class="navbar-spacer"></div>

            <div class="navbar-actions">
                @if($authUser)
                <!-- Menú de perfil del estudiante -->
                <div class="profile-menu-wrap" id="profile-menu-wrap">
                    <button class="profile-trigger" id="profile-trigger" onclick="toggleProfileMenu()">
                        @if($authUser->avatar)
                            <img src="{{ $authUser->avatar }}" class="profile-avatar" alt="Avatar" style="object-fit:cover">
                        @else
                            <span class="profile-avatar">{{ strtoupper(substr($authUser->person->names ?? $authUser->email, 0, 2)) }}</span>
                        @endif
                        <span class="profile-name">{{ explode(' ', $authUser->person->names ?? $authUser->email)[0] }}</span>
                        <span class="material-symbols-outlined" style="font-size:18px;">expand_more</span>
                    </button>
                    <div class="profile-dropdown" id="profile-dropdown">
                        <div class="profile-dropdown-header">
                            @if($authUser->avatar)
                                <img src="{{ $authUser->avatar }}" class="profile-avatar lg" alt="Avatar" style="object-fit:cover">
                            @else
                                <span class="profile-avatar lg">{{ strtoupper(substr($authUser->person->names ?? $authUser->email, 0, 2)) }}</span>
                            @endif
                            <div style="min-width:0">
                                <p style="font-weight:600;font-size:14px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $authUser->person->names ?? $authUser->email }}</p>
                                <p style="font-size:11px;color:var(--tm);">Estudiante</p>
                            </div>
                        </div>
                        <a href="{{ route('landing') }}" class="profile-dropdown-item">
                            <span class="material-symbols-outlined">search</span>
                            Buscar Ofertas
                        </a>
                        <div class="profile-dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}" style="margin:0">
                            @csrf
                            <button type="submit" class="profile-dropdown-item" style="color:#dc2626;width:100%;text-align:left">
                                <span class="material-symbols-outlined">logout</span> Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="main-container">
        <div class="page-header">
            <h1 class="page-title">
                Mis Postulaciones Laborales
                <span class="count-badge">{{ $studentApplications->count() }}</span>
            </h1>
            <p class="page-subtitle">Sigue el estado en tiempo real de los puestos a los que te has presentado.</p>
        </div>

        @if($studentApplications->count() > 0)
        <div class="applications-grid">
            @foreach($studentApplications as $app)
            <div class="app-card">
                <div class="app-card-header">
                    <div class="company-logo">
                        @if($app->company_logo)
                            <img src="{{ $app->company_logo }}" alt="{{ $app->company_name }}">
                        @else
                            <div class="company-logo-text">{{ strtoupper(substr($app->company_name, 0, 2)) }}</div>
                        @endif
                    </div>
                    
                    <div>
                        @if($app->app_status === 'postulated')
                            <span class="badge-app postulated">
                                <span class="material-symbols-outlined" style="font-size:14px">schedule</span>
                                Postulado
                            </span>
                        @elseif($app->app_status === 'reviewed')
                            <span class="badge-app reviewed">
                                <span class="material-symbols-outlined" style="font-size:14px">visibility</span>
                                Revisado
                            </span>
                        @elseif($app->app_status === 'accepted')
                            <span class="badge-app selected">
                                <span class="material-symbols-outlined" style="font-size:14px">check_circle</span>
                                Aceptado
                            </span>
                        @elseif($app->app_status === 'rejected')
                            <span class="badge-app rejected">
                                <span class="material-symbols-outlined" style="font-size:14px">cancel</span>
                                Descartado
                            </span>
                        @elseif($app->app_status === 'finished')
                            <span class="badge-app finished">
                                <span class="material-symbols-outlined" style="font-size:14px">archive</span>
                                Finalizado
                            </span>
                        @else
                            <span class="badge-app postulated">{{ strtoupper($app->app_status) }}</span>
                        @endif
                    </div>
                </div>

                <div class="job-details">
                    <h2 class="job-title" title="{{ $app->offer_title }}">{{ $app->offer_title }}</h2>
                    <span class="company-name-link">{{ $app->company_name }}</span>
                    
                    <div class="company-meta" style="margin-top:auto">
                        <span class="meta-item">
                            <span class="material-symbols-outlined" style="font-size:15px">calendar_month</span>
                            Postulado: {{ $app->formatted_date }}
                        </span>
                    </div>
                </div>

                @if($app->app_feedback)
                <div class="feedback-box">
                    <div class="feedback-title">
                        <span class="material-symbols-outlined" style="font-size:14px">chat</span>
                        Mensaje de la empresa
                    </div>
                    <p class="feedback-text">"{{ $app->app_feedback }}"</p>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <span class="material-symbols-outlined empty-state-icon">assignment_late</span>
            <h2 class="empty-state-title">Aún no has postulado a ninguna oferta</h2>
            <p class="empty-state-text">Explora los puestos de trabajo disponibles y encuentra tu próxima oportunidad hoy mismo.</p>
            <a href="/" class="btn-action">
                <span class="material-symbols-outlined">search</span>
                Buscar Empleos
            </a>
        </div>
        @endif
    </main>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-brand">
                @if($config['logo'] ?? '')
                    <img src="{{ $config['logo'] }}" alt="Logo">
                @else
                    <span class="material-symbols-outlined filled" style="font-size:22px">work</span>
                @endif
                {{ $config['application_name'] ?? 'Bolsa Laboral' }}
            </div>
            <p class="footer-copy">&copy; {{ date('Y') }} {{ $config['application_name'] ?? 'Bolsa Laboral' }}. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script>
        // ── Dropdown Menú ─────────────────────────────────────────────────────────────
        function toggleProfileMenu() {
            var dropdown = document.getElementById('profile-dropdown');
            var isVisible = dropdown.style.display === 'flex';
            dropdown.style.display = isVisible ? 'none' : 'flex';
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            var wrap = document.getElementById('profile-menu-wrap');
            if (wrap && !wrap.contains(e.target)) {
                var dropdown = document.getElementById('profile-dropdown');
                if (dropdown) dropdown.style.display = 'none';
            }
        });
    </script>
</body>
</html>
