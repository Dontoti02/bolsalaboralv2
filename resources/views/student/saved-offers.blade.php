<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $config['application_name'] ?? 'Bolsa Laboral' }} — Ofertas Guardadas</title>
    <link rel="icon" href="{{ $config['favicon'] ?? '/assets/favicon.png' }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL@20..48,100..700,0..1&display=swap" rel="stylesheet">

    <style>
        :root {
            --pri: {{ $config['primary_color'] ?? '#01143c' }};
            --pri-hover: #1e3a8a;
            --pri-tint: #eff6ff;
            --pri-tint-border: #bfdbfe;
            --sec: {{ $config['secondary_color'] ?? '#475eb8' }};
            --acc: {{ $config['accent_color'] ?? '#f97316' }};
            --bg: #f8fafc;
            --sur: #ffffff;
            --bor: #e2e8f0;
            --txt: #0f172a;
            --tv: #475569;
            --tm: #94a3b8;
            --rad: 18px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg);
            color: var(--txt);
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        a { text-decoration: none; color: inherit; }
        button { font-family: inherit; cursor: pointer; }
        img { display: block; max-width: 100%; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
            line-height: 1;
        }
        .material-symbols-outlined.filled {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        /* ── NAVBAR ── */
        .navbar {
            background: #ffffff;
            border-bottom: 1.5px solid rgba(1, 20, 60, 0.08);
            box-shadow: 0 4px 20px -2px rgba(1, 20, 60, 0.04);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .navbar-inner {
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 24px;
            height: 86px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 16px;
            text-decoration: none;
        }
        .brand-logo-box {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: #ffffff;
            border: 1.5px solid rgba(1, 20, 60, 0.10);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(1, 20, 60, 0.06);
            flex-shrink: 0;
        }
        .brand-logo-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 4px;
        }
        .brand-text {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .brand-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: #01143c;
            line-height: 1.15;
            letter-spacing: -0.02em;
        }
        .brand-sub {
            font-size: 12px;
            font-weight: 700;
            color: #475eb8;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            line-height: 1;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .nav-link {
            font-size: 14.5px;
            font-weight: 600;
            color: #475569;
            padding: 8px 16px;
            border-radius: 50px;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .nav-link:hover {
            color: #01143c;
            background: rgba(1, 20, 60, 0.05);
        }

        /* Profile Trigger */
        .profile-menu-wrap { position: relative; }
        .profile-trigger {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 5px 16px 5px 6px;
            border: 1.5px solid rgba(1, 20, 60, 0.16);
            border-radius: 50px;
            background: #ffffff;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(1, 20, 60, 0.04);
        }
        .profile-trigger:hover {
            border-color: #01143c;
            background: rgba(1, 20, 60, 0.02);
            box-shadow: 0 4px 12px rgba(1, 20, 60, 0.08);
        }
        .profile-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #01143c 0%, #1e3a8a 100%);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 13.5px;
            font-weight: 700;
            object-fit: cover;
            flex-shrink: 0;
        }
        .profile-name {
            font-size: 14.5px;
            font-weight: 700;
            color: #01143c;
            max-width: 140px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .profile-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background: #ffffff;
            border: 1px solid var(--bor);
            border-radius: 18px;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.12);
            min-width: 260px;
            z-index: 500;
            overflow: hidden;
            display: none;
        }
        .profile-dropdown.open { display: block; }
        .profile-dropdown-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            background: var(--pri-tint);
            border-bottom: 1px solid var(--pri-tint-border);
        }
        .profile-dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 18px;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--tv);
            width: 100%;
            text-align: left;
            background: transparent;
            border: none;
            transition: all 0.15s ease;
        }
        .profile-dropdown-item:hover {
            background: var(--pri-tint);
            color: var(--txt);
        }
        .profile-dropdown-divider {
            height: 1px;
            background: var(--bor);
            margin: 4px 0;
        }
        .role-tag {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            background: #01143c;
            color: #ffffff;
            padding: 2px 8px;
            border-radius: 50px;
            display: inline-block;
        }

        /* ── HERO BANNER ── */
        .page-hero {
            background: linear-gradient(135deg, #01143c 0%, #0d276b 60%, #1e3a8a 100%);
            color: #ffffff;
            padding: 40px 0 32px;
            position: relative;
            overflow: hidden;
        }
        .page-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.12) 1.5px, transparent 1.5px);
            background-size: 24px 24px;
            opacity: 0.4;
            pointer-events: none;
        }
        .page-container {
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 24px;
            position: relative;
            z-index: 2;
        }
        .hero-title-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }
        .hero-title-block h1 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: clamp(24px, 2.5vw, 32px);
            font-weight: 800;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .badge-count {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.3);
            font-size: 14px;
            font-weight: 800;
            padding: 4px 14px;
            border-radius: 50px;
        }
        .hero-title-block p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
            margin-top: 6px;
        }

        /* ── MAIN CONTENT ── */
        .main-content {
            max-width: 1240px;
            margin: 32px auto 60px;
            padding: 0 24px;
            flex: 1;
            width: 100%;
        }

        /* Filter & Search Bar */
        .toolbar-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }
        .search-box-wrap {
            position: relative;
            flex: 1;
            max-width: 400px;
        }
        .search-icon-inside {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 20px;
            pointer-events: none;
        }
        .search-input-field {
            width: 100%;
            height: 44px;
            padding: 0 16px 0 44px;
            border-radius: 50px;
            border: 1.5px solid var(--bor);
            background: #ffffff;
            font-size: 13.5px;
            outline: none;
            transition: all 0.2s;
        }
        .search-input-field:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        /* Job Cards Grid (Same as landing page) */
        .saved-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 22px;
        }
        .saved-card {
            background: #ffffff;
            border: 1.5px solid var(--bor);
            border-radius: 20px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }
        .saved-card:hover {
            transform: translateY(-4px);
            border-color: #3b82f6;
            box-shadow: 0 12px 32px rgba(1, 20, 60, 0.08);
        }
        .saved-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
        }
        .company-logo-box {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            border: 1px solid var(--bor);
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(0,0,0,0.03);
        }
        .company-logo-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 4px;
        }
        .company-logo-initials {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #01143c, #1e3a8a);
            color: #ffffff;
            font-size: 15px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-bookmark-remove {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fee2e2;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-bookmark-remove:hover {
            background: #dc2626;
            color: #ffffff;
            transform: scale(1.08);
        }

        .job-type-pill {
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 50px;
            display: inline-block;
            margin-bottom: 6px;
        }
        .badge-practica { background: #dcfce7; color: #15803d; }
        .badge-empleo { background: #f3e8ff; color: #6b21a8; }

        .job-title-link {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 17px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.35;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            transition: color 0.15s;
        }
        .job-title-link:hover { color: #2563eb; }

        .company-link {
            font-size: 13.5px;
            font-weight: 600;
            color: #475eb8;
            margin-top: 4px;
            display: inline-block;
        }
        .company-link:hover { text-decoration: underline; }

        .meta-tags-row {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }
        .meta-item {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .card-footer-strip {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: auto;
            padding-top: 14px;
            border-top: 1px dashed var(--bor);
        }
        .salary-label {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 15px;
            font-weight: 800;
            color: #0f172a;
        }
        .btn-apply-fast {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #01143c, #1e3a8a);
            color: #ffffff;
            font-size: 13px;
            font-weight: 700;
            padding: 8px 16px;
            border-radius: 10px;
            border: none;
            transition: all 0.2s ease;
        }
        .btn-apply-fast:hover {
            opacity: 0.95;
            transform: translateY(-1px);
        }

        /* Empty State */
        .empty-portal-state {
            background: #ffffff;
            border: 1.5px dashed var(--bor);
            border-radius: 24px;
            padding: 60px 24px;
            text-align: center;
            grid-column: 1 / -1;
        }
        .empty-icon-wrap {
            width: 72px;
            height: 72px;
            border-radius: 24px;
            background: #eff6ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 36px;
        }
        .empty-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 6px;
        }
        .empty-desc {
            font-size: 14px;
            color: #64748b;
            max-width: 460px;
            margin: 0 auto 20px;
            line-height: 1.55;
        }
        .btn-action-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #01143c, #1e3a8a);
            color: #ffffff;
            font-size: 14px;
            font-weight: 700;
            padding: 11px 24px;
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 14px rgba(1, 20, 60, 0.2);
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .btn-action-primary:hover {
            opacity: 0.95;
            transform: translateY(-1px);
        }

        /* ── MODALS ── */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .modal-overlay.open { display: flex; }
        .modal-box {
            background: #ffffff;
            border-radius: 24px;
            padding: 32px;
            width: 100%;
            max-width: 480px;
            position: relative;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.2);
            animation: modalPop 0.2s cubic-bezier(0.16, 1, 0.3, 1) both;
        }
        @keyframes modalPop {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        .modal-close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 1px solid var(--bor);
            background: #ffffff;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s;
        }
        .modal-close-btn:hover { background: #f1f5f9; color: #0f172a; }
        .modal-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 21px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .modal-sub {
            font-size: 13.5px;
            color: #64748b;
            margin-bottom: 22px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 18px;
            text-align: left;
        }
        .form-label {
            font-size: 13px;
            font-weight: 700;
            color: #334155;
        }
        .form-input, .form-select, .form-textarea {
            width: 100%;
            height: 46px;
            padding: 0 16px;
            border-radius: 12px;
            border: 1.5px solid var(--bor);
            background: #ffffff;
            color: #0f172a;
            font-size: 14px;
            font-family: inherit;
            outline: none;
            transition: all 0.18s;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        /* Toast */
        .toast-notify {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #0f172a;
            color: #ffffff;
            padding: 14px 22px;
            border-radius: 14px;
            font-size: 13.5px;
            font-weight: 600;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            z-index: 2000;
            display: none;
            align-items: center;
            gap: 10px;
            animation: slideUp 0.25s ease both;
        }
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* Footer */
        .footer {
            background: #ffffff;
            border-top: 1px solid var(--bor);
            padding: 24px 0;
            margin-top: auto;
        }
        .footer-inner {
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 13px;
            color: #64748b;
        }

        @media (max-width: 1024px) {
            .saved-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 640px) {
            .saved-grid { grid-template-columns: 1fr; }
            .navbar-inner { height: 72px; padding: 0 16px; }
            .brand-title { font-size: 16px; max-width: 160px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
            .brand-sub { display: none; }
        }
    </style>
</head>
<body>

    @php
        $roleId = (int)($authUser->rol_id ?? 3);
        $roleTitle = match($roleId) {
            1 => 'Administrador',
            2 => 'Docente',
            3 => 'Estudiante',
            4 => 'Empresa',
            5 => 'Egresado',
            default => 'Usuario'
        };
        $person = $authUser->person ?? new \App\Models\Person();
        $avatarText = strtoupper(substr($person->names ?? $authUser->email, 0, 2));
    @endphp

    {{-- ══ NAVBAR ══ --}}
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="{{ route('landing') }}" class="brand">
                <div class="brand-logo-box">
                    @if(!empty($config['logo']))
                        <img src="{{ $config['logo'] }}" alt="Logo">
                    @else
                        <span style="font-size:22px;font-weight:800;color:#01143c;">{{ strtoupper(substr($config['application_name'] ?? 'L', 0, 1)) }}</span>
                    @endif
                </div>
                <div class="brand-text">
                    <span class="brand-title">{{ $config['application_name'] ?? 'Bolsa Laboral' }}</span>
                    <span class="brand-sub">Ofertas Guardadas</span>
                </div>
            </a>

            <div class="nav-links">
                <a href="{{ route('landing') }}" class="nav-link">
                    <span class="material-symbols-outlined" style="font-size:18px;">travel_explore</span>
                    Buscar Oportunidades
                </a>
                <a href="{{ route('student.applications') }}" class="nav-link">
                    <span class="material-symbols-outlined" style="font-size:18px;">assignment_turned_in</span>
                    Mis Postulaciones ({{ $totalApplications ?? 0 }})
                </a>

                {{-- Profile Menu Dropdown --}}
                <div class="profile-menu-wrap" id="profile-menu-wrap">
                    <button class="profile-trigger" onclick="toggleProfileMenu()" aria-haspopup="true">
                        @if($authUser->avatar)
                            <img src="{{ $authUser->avatar }}" class="profile-avatar" alt="Avatar">
                        @else
                            <span class="profile-avatar">{{ $avatarText }}</span>
                        @endif
                        <span class="profile-name">{{ explode(' ', $person->names ?? $authUser->email)[0] }}</span>
                        <span class="material-symbols-outlined" style="font-size:16px;">expand_more</span>
                    </button>

                    <div class="profile-dropdown" id="profile-dropdown">
                        <div class="profile-dropdown-header">
                            @if($authUser->avatar)
                                <img src="{{ $authUser->avatar }}" class="profile-avatar" alt="Avatar">
                            @else
                                <span class="profile-avatar">{{ $avatarText }}</span>
                            @endif
                            <div style="min-width:0;">
                                <p style="font-size:13.5px;font-weight:700;color:var(--txt);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $person->names ?? $authUser->email }}</p>
                                <span class="role-tag">{{ $roleTitle }}</span>
                            </div>
                        </div>

                        <a href="{{ route('student.applications') }}?tab=profile" class="profile-dropdown-item">
                            <span class="material-symbols-outlined">badge</span>
                            Mi Perfil Profesional
                        </a>
                        <a href="{{ route('student.applications') }}?tab=cvs" class="profile-dropdown-item">
                            <span class="material-symbols-outlined">description</span>
                            Gestionar mi CV
                        </a>
                        <a href="{{ route('student.applications') }}" class="profile-dropdown-item">
                            <span class="material-symbols-outlined">assignment_turned_in</span>
                            Mis Postulaciones ({{ $totalApplications ?? 0 }})
                        </a>
                        <button type="button" class="profile-dropdown-item" onclick="openPasswordModal();closeProfileMenu()">
                            <span class="material-symbols-outlined">lock</span>
                            Cambiar Contraseña
                        </button>

                        <div class="profile-dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                            @csrf
                            <button type="submit" class="profile-dropdown-item" style="color:#dc2626;">
                                <span class="material-symbols-outlined">logout</span>
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- ══ HERO HEADER ══ --}}
    <header class="page-hero">
        <div class="page-container">
            <div class="hero-title-row">
                <div class="hero-title-block">
                    <h1>
                        <span>Mis Ofertas Guardadas</span>
                        <span class="badge-count" id="badge-saved-total">{{ $savedOffers->count() }}</span>
                    </h1>
                    <p>Revisa las oportunidades laborales que has guardado para postular más tarde.</p>
                </div>
            </div>
        </div>
    </header>

    {{-- ══ MAIN CONTENT ══ --}}
    <main class="main-content">
        
        <div class="toolbar-bar">
            <div class="search-box-wrap">
                <span class="material-symbols-outlined search-icon-inside">search</span>
                <input type="text" id="filter-saved-input" class="search-input-field" placeholder="Buscar por puesto o empresa..." oninput="handleSearchFilter(this.value)">
            </div>
            <a href="{{ route('landing') }}#oportunidades" class="btn-action-primary" style="padding:9px 18px;font-size:13px;">
                <span class="material-symbols-outlined" style="font-size:18px;">travel_explore</span>
                Explorar Más Empleos
            </a>
        </div>

        @if($savedOffers->count() > 0)
            <div class="saved-grid" id="saved-grid">
                @foreach($savedOffers as $offer)
                @php
                    $isPractice = false;
                    if ($offer->category && str_contains(strtolower($offer->category->name ?? ''), 'práct')) {
                        $isPractice = true;
                    }
                    $salaryStr = 'A convenir';
                    if (!empty($offer->salary)) {
                        $salaryStr = 'S/ ' . number_format($offer->salary, 2);
                    }
                @endphp
                <div class="saved-card" id="card-offer-{{ $offer->id }}" data-title="{{ strtolower($offer->title) }}" data-company="{{ strtolower($offer->company_name) }}">
                    <div class="saved-card-header">
                        <div class="company-logo-box">
                            @if(!empty($offer->company && $offer->company->logo))
                                <img src="{{ $offer->company->logo }}" alt="{{ $offer->company_name }}">
                            @else
                                <div class="company-logo-initials">{{ strtoupper(substr($offer->company_name, 0, 2)) }}</div>
                            @endif
                        </div>

                        <button type="button" class="btn-bookmark-remove" onclick="removeSavedOffer({{ $offer->id }})" title="Quitar de ofertas guardadas">
                            <span class="material-symbols-outlined filled" style="font-size:20px;">bookmark_remove</span>
                        </button>
                    </div>

                    <div>
                        <span class="job-type-pill {{ $isPractice ? 'badge-practica' : 'badge-empleo' }}">
                            {{ $isPractice ? 'Prácticas' : 'Empleo' }}
                        </span>
                        <a href="/ofertas/{{ $offer->id }}" class="job-title-link" title="{{ $offer->title }}">
                            {{ $offer->title }}
                        </a>
                        @if(!empty($offer->company_id))
                            <a href="/empresas/{{ $offer->company_id }}" class="company-link">{{ $offer->company_name }}</a>
                        @else
                            <span class="company-link">{{ $offer->company_name }}</span>
                        @endif
                    </div>

                    <div class="meta-tags-row">
                        <span class="meta-item">
                            <span class="material-symbols-outlined" style="font-size:15px;">location_on</span>
                            {{ $offer->department ?: 'Nacional' }}
                        </span>
                        <span class="meta-item">
                            <span class="material-symbols-outlined" style="font-size:15px;">domain</span>
                            {{ $offer->location_name }}
                        </span>
                        @if(!empty($offer->workSchedule))
                        <span class="meta-item">
                            <span class="material-symbols-outlined" style="font-size:15px;">schedule</span>
                            {{ $offer->workSchedule->name }}
                        </span>
                        @endif
                    </div>

                    <div class="card-footer-strip">
                        <div>
                            <span style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;display:block;">Remuneración</span>
                            <span class="salary-label">{{ $salaryStr }}</span>
                        </div>
                        <button type="button" class="btn-apply-fast" onclick="openApplyModal({{ $offer->id }}, '{{ addslashes($offer->title) }}')">
                            <span>Postularme</span>
                            <span class="material-symbols-outlined" style="font-size:16px;">send</span>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="empty-portal-state">
                <div class="empty-icon-wrap">
                    <span class="material-symbols-outlined">bookmark_border</span>
                </div>
                <h2 class="empty-title">Aún no has guardado ninguna vacante</h2>
                <p class="empty-desc">Guarda las ofertas que más te interesen desde la lista de empleos para revisarlas detalladamente y postularte después.</p>
                <a href="{{ route('landing') }}#oportunidades" class="btn-action-primary">
                    <span class="material-symbols-outlined">search</span>
                    Explorar Vacantes de Interés
                </a>
            </div>
        @endif

    </main>

    {{-- ══ MODAL POSTULAR ══ --}}
    <div id="modal-apply" class="modal-overlay" onclick="if(event.target===this)closeApplyModal()">
        <div class="modal-box" style="text-align:center;">
            <button type="button" class="modal-close-btn" onclick="closeApplyModal()">
                <span class="material-symbols-outlined">close</span>
            </button>

            <div style="width:56px;height:56px;border-radius:18px;background:var(--pri-tint);color:var(--pri);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                <span class="material-symbols-outlined filled" style="font-size:28px;">send</span>
            </div>
            <h3 class="modal-title" style="justify-content:center;" id="modal-apply-title">Postular a vacante</h3>
            <p class="modal-sub">Selecciona tu currículum registrado para enviar tu postulación a la empresa.</p>

            <div id="apply-alert" style="display:none;padding:10px 14px;border-radius:10px;font-size:13px;font-weight:600;margin-bottom:14px;text-align:left;"></div>

            @if($studentCvs->count() > 0)
                <form id="form-apply-job" style="text-align:left;">
                    @csrf
                    <input type="hidden" id="apply-offer-id" value="">
                    
                    <div class="form-group">
                        <label class="form-label">Selecciona tu CV (PDF)</label>
                        <select id="apply-cv-id" class="form-select" required>
                            @foreach($studentCvs as $cv)
                            <option value="{{ $cv->id }}">v{{ $cv->version }} — {{ $cv->filename }} ({{ $cv->uploaded_at }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Mensaje de presentación (opcional)</label>
                        <textarea id="apply-message" class="form-textarea" rows="3" placeholder="Preséntate brevemente ante la empresa..."></textarea>
                    </div>

                    <div style="display:flex;align-items:center;justify-content:flex-end;gap:12px;margin-top:20px;">
                        <button type="button" class="filter-pill" onclick="closeApplyModal()">Cancelar</button>
                        <button type="button" id="btn-submit-apply" class="btn-action-primary" onclick="submitApplyJob()">
                            <span class="material-symbols-outlined">send</span>
                            <span id="btn-submit-apply-text">Enviar Postulación</span>
                        </button>
                    </div>
                </form>
            @else
                <div style="background:#fef2f2;border:1px solid #fee2e2;border-radius:14px;padding:16px;margin-bottom:20px;">
                    <p style="font-size:13.5px;font-weight:700;color:#dc2626;">Necesitas registrar al menos un currículum PDF antes de postular.</p>
                </div>
                <div style="display:flex;align-items:center;justify-content:center;gap:12px;">
                    <a href="{{ route('student.applications') }}?tab=cvs" class="btn-action-primary">
                        <span class="material-symbols-outlined">upload_file</span>
                        Subir mi CV ahora
                    </a>
                    <button type="button" class="filter-pill" onclick="closeApplyModal()">Cancelar</button>
                </div>
            @endif
        </div>
    </div>

    {{-- ══ MODAL CAMBIAR CONTRASEÑA ══ --}}
    <div id="modal-password" class="modal-overlay" onclick="if(event.target===this)closePasswordModal()">
        <div class="modal-box">
            <button type="button" class="modal-close-btn" onclick="closePasswordModal()">
                <span class="material-symbols-outlined">close</span>
            </button>

            <h3 class="modal-title">
                <span class="material-symbols-outlined" style="color:#01143c;">lock_reset</span>
                Cambiar Contraseña
            </h3>
            <p class="modal-sub">Ingresa tu contraseña actual y define una nueva clave segura.</p>

            <form id="form-change-password">
                @csrf
                <div class="form-group">
                    <label class="form-label">Contraseña Actual</label>
                    <input type="password" id="pwd-current" class="form-input" required placeholder="Tu contraseña actual">
                </div>
                <div class="form-group">
                    <label class="form-label">Nueva Contraseña (mínimo 8 caracteres)</label>
                    <input type="password" id="pwd-new" class="form-input" required minlength="8" placeholder="Nueva contraseña">
                </div>
                <div class="form-group">
                    <label class="form-label">Confirmar Nueva Contraseña</label>
                    <input type="password" id="pwd-confirm" class="form-input" required minlength="8" placeholder="Repite la nueva contraseña">
                </div>

                <div id="pwd-alert" style="display:none;padding:10px 14px;border-radius:10px;font-size:13px;font-weight:600;margin-bottom:14px;"></div>

                <div style="display:flex;align-items:center;justify-content:flex-end;gap:12px;margin-top:20px;">
                    <button type="button" class="filter-pill" onclick="closePasswordModal()">Cancelar</button>
                    <button type="button" id="btn-submit-pwd" class="btn-action-primary" onclick="submitChangePassword()">
                        <span class="material-symbols-outlined">check</span>
                        <span id="btn-submit-pwd-text">Actualizar Contraseña</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══ TOAST NOTIFICATION ══ --}}
    <div id="toast-notify" class="toast-notify">
        <span class="material-symbols-outlined" id="toast-icon">check_circle</span>
        <span id="toast-text">Mensaje</span>
    </div>

    {{-- ══ FOOTER ══ --}}
    <footer class="footer">
        <div class="footer-inner">
            <span>&copy; {{ date('Y') }} {{ $config['application_name'] ?? 'Bolsa Laboral' }}. Todos los derechos reservados.</span>
            <a href="{{ route('landing') }}" style="color:#01143c;font-weight:600;">Volver al Portal Principal ↗</a>
        </div>
    </footer>

    <script>
        // ── Dropdown Menú ─────────────────────────────────────────────────────────
        function toggleProfileMenu() {
            var dd = document.getElementById('profile-dropdown');
            if (dd) dd.classList.toggle('open');
        }
        function closeProfileMenu() {
            var dd = document.getElementById('profile-dropdown');
            if (dd) dd.classList.remove('open');
        }
        document.addEventListener('click', function(e) {
            var wrap = document.getElementById('profile-menu-wrap');
            if (wrap && !wrap.contains(e.target)) closeProfileMenu();
        });

        // ── Toast ─────────────────────────────────────────────────────────────────
        function showToast(msg, isSuccess) {
            var t = document.getElementById('toast-notify');
            var txt = document.getElementById('toast-text');
            var ico = document.getElementById('toast-icon');
            if (!t) return;
            txt.textContent = msg;
            ico.textContent = (isSuccess !== false) ? 'check_circle' : 'error';
            ico.style.color = (isSuccess !== false) ? '#4ade80' : '#f87171';
            t.style.display = 'flex';
            setTimeout(function() { t.style.display = 'none'; }, 3500);
        }

        // ── Search in Saved ───────────────────────────────────────────────────────
        function handleSearchFilter(q) {
            var term = (q || '').toLowerCase().trim();
            var cards = document.querySelectorAll('.saved-card');
            cards.forEach(function(c) {
                var title = c.getAttribute('data-title') || '';
                var comp  = c.getAttribute('data-company') || '';
                if (!term || title.includes(term) || comp.includes(term)) {
                    c.style.display = 'flex';
                } else {
                    c.style.display = 'none';
                }
            });
        }

        // ── Remove Saved Offer ────────────────────────────────────────────────────
        function removeSavedOffer(offerId) {
            fetch('/student/save-offer/' + offerId, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (data.success) {
                    showToast('Oferta retirada de tus guardadas.');
                    var card = document.getElementById('card-offer-{{ "" }}' + offerId);
                    if (card) {
                        card.style.transition = 'all 0.3s ease';
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.9)';
                        setTimeout(function() {
                            card.remove();
                            var remaining = document.querySelectorAll('.saved-card').length;
                            var badge = document.getElementById('badge-saved-total');
                            if (badge) badge.textContent = remaining;
                            if (remaining === 0) {
                                window.location.reload();
                            }
                        }, 300);
                    }
                } else {
                    showToast(data.message || 'Error al actualizar oferta guardada.', false);
                }
            })
            .catch(function() {
                showToast('Error de conexión al actualizar.', false);
            });
        }

        // ── Apply Modal ───────────────────────────────────────────────────────────
        var targetApplyOfferId = null;
        function openApplyModal(offerId, offerTitle) {
            targetApplyOfferId = offerId;
            var hidden = document.getElementById('apply-offer-id');
            if (hidden) hidden.value = offerId;
            var title = document.getElementById('modal-apply-title');
            if (title) title.textContent = 'Postular a: ' + offerTitle;
            var alertBox = document.getElementById('apply-alert');
            if (alertBox) alertBox.style.display = 'none';
            var m = document.getElementById('modal-apply');
            if (m) m.classList.add('open');
        }
        function closeApplyModal() {
            var m = document.getElementById('modal-apply');
            if (m) m.classList.remove('open');
        }

        function submitApplyJob() {
            var cvSelect = document.getElementById('apply-cv-id');
            if (!cvSelect || !cvSelect.value) {
                showToast('Debes seleccionar un currículum.', false);
                return;
            }

            var msg = document.getElementById('apply-message') ? document.getElementById('apply-message').value : '';
            var btn = document.getElementById('btn-submit-apply');
            var txt = document.getElementById('btn-submit-apply-text');
            var alertBox = document.getElementById('apply-alert');

            btn.disabled = true;
            txt.textContent = 'Enviando...';

            fetch('/student/apply/' + targetApplyOfferId, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    cv_id: cvSelect.value,
                    message: msg
                })
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                btn.disabled = false;
                txt.textContent = 'Enviar Postulación';
                if (data.success) {
                    closeApplyModal();
                    showToast(data.message || '¡Postulación enviada exitosamente!');
                } else {
                    alertBox.style.display = 'block';
                    alertBox.style.background = '#fee2e2';
                    alertBox.style.color = '#991b1b';
                    alertBox.textContent = data.message || 'Error al postular.';
                }
            })
            .catch(function() {
                btn.disabled = false;
                txt.textContent = 'Enviar Postulación';
                alertBox.style.display = 'block';
                alertBox.style.background = '#fee2e2';
                alertBox.style.color = '#991b1b';
                alertBox.textContent = 'Error de conexión con el servidor.';
            });
        }

        // ── Change Password Modal ─────────────────────────────────────────────────
        function openPasswordModal() {
            var m = document.getElementById('modal-password');
            var alertBox = document.getElementById('pwd-alert');
            if (alertBox) alertBox.style.display = 'none';
            var form = document.getElementById('form-change-password');
            if (form) form.reset();
            if (m) m.classList.add('open');
        }
        function closePasswordModal() {
            var m = document.getElementById('modal-password');
            if (m) m.classList.remove('open');
        }

        function submitChangePassword() {
            var cur = document.getElementById('pwd-current').value;
            var np  = document.getElementById('pwd-new').value;
            var conf = document.getElementById('pwd-confirm').value;
            var alertBox = document.getElementById('pwd-alert');

            if (!cur || !np || !conf) {
                alertBox.style.display = 'block';
                alertBox.style.background = '#fee2e2';
                alertBox.style.color = '#991b1b';
                alertBox.textContent = 'Todos los campos son obligatorios.';
                return;
            }
            if (np !== conf) {
                alertBox.style.display = 'block';
                alertBox.style.background = '#fee2e2';
                alertBox.style.color = '#991b1b';
                alertBox.textContent = 'La nueva contraseña y su confirmación no coinciden.';
                return;
            }
            if (np.length < 8) {
                alertBox.style.display = 'block';
                alertBox.style.background = '#fee2e2';
                alertBox.style.color = '#991b1b';
                alertBox.textContent = 'La nueva contraseña debe tener al menos 8 caracteres.';
                return;
            }

            var btn = document.getElementById('btn-submit-pwd');
            var txt = document.getElementById('btn-submit-pwd-text');
            btn.disabled = true;
            txt.textContent = 'Actualizando...';

            fetch('{{ route("student.password.change") }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    current_password: cur,
                    new_password: np,
                    new_password_confirmation: conf
                })
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                btn.disabled = false;
                txt.textContent = 'Actualizar Contraseña';
                if (data.success) {
                    closePasswordModal();
                    showToast('¡Contraseña actualizada exitosamente!');
                } else {
                    alertBox.style.display = 'block';
                    alertBox.style.background = '#fee2e2';
                    alertBox.style.color = '#991b1b';
                    alertBox.textContent = data.message || 'Error al cambiar contraseña.';
                }
            })
            .catch(function() {
                btn.disabled = false;
                txt.textContent = 'Actualizar Contraseña';
                alertBox.style.display = 'block';
                alertBox.style.background = '#fee2e2';
                alertBox.style.color = '#991b1b';
                alertBox.textContent = 'Error de conexión con el servidor.';
            });
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePasswordModal();
                closeApplyModal();
            }
        });
    </script>
</body>
</html>
