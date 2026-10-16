<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $company->name }} — Perfil y Convocatorias Laborales</title>
    <meta name="description" content="Conoce las vacantes laborales activas de {{ $company->name }} en nuestra Bolsa Laboral.">
    <link rel="icon" href="{{ $config['favicon'] ?? '/assets/favicon.png' }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL@20..48,100..700,0..1&display=swap" rel="stylesheet">

    <style>
        :root {
            --pri: {{ $config['primary_color'] ?? '#01143c' }};
            --pri-hover: color-mix(in srgb, var(--pri) 85%, #000000);
            --pri-tint: color-mix(in srgb, var(--pri) 6%, #ffffff);
            --pri-tint-border: color-mix(in srgb, var(--pri) 16%, transparent);
            --sec: {{ $config['secondary_color'] ?? '#475eb8' }};
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
        button { font-family: inherit; cursor: pointer; border: none; background: none; }
        img { display: block; max-width: 100%; }

        .page-container {
            width: 100%;
            max-width: 1320px;
            margin: 0 auto;
            padding: 0 clamp(16px, 3.2vw, 48px);
        }

        /* ── NAVBAR (Estilo unificado y armonizado) ── */
        .navbar {
            background: #ffffff;
            border-bottom: 1.5px solid rgba(1, 20, 60, 0.08);
            box-shadow: 0 4px 20px -2px rgba(1, 20, 60, 0.04);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .navbar-inner {
            height: 86px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 16px;
            text-decoration: none;
            transition: opacity 0.2s ease;
        }
        .brand:hover { opacity: 0.95; }
        .brand-logo-box {
            width: 54px;
            height: 54px;
            border-radius: 14px;
            background: #ffffff;
            border: 1.5px solid rgba(1, 20, 60, 0.10);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
            box-shadow: 0 4px 14px rgba(1, 20, 60, 0.07);
        }
        .brand-logo-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 3px;
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
            gap: 16px;
        }
        .nav-link {
            font-size: 15px;
            font-weight: 600;
            color: #475569;
            padding: 9px 16px;
            border-radius: 50px;
            transition: all 0.2s ease;
        }
        .nav-link:hover {
            color: #01143c;
            background: rgba(1, 20, 60, 0.05);
        }
        .btn-login-nav {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 22px;
            border: 1.5px solid rgba(1, 20, 60, 0.18);
            border-radius: 50px;
            font-size: 14.5px;
            font-weight: 700;
            color: #01143c;
            background: #ffffff;
            transition: all 0.2s ease;
        }
        .btn-login-nav:hover {
            border-color: #01143c;
            background: rgba(1, 20, 60, 0.05);
            transform: translateY(-1px);
        }
        .btn-empresa-nav {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 11px 24px;
            border-radius: 50px;
            font-size: 14.5px;
            font-weight: 700;
            color: #ffffff;
            background: linear-gradient(135deg, #01143c 0%, #1e3a8a 100%);
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(1, 20, 60, 0.22);
        }
        .btn-empresa-nav:hover {
            transform: translateY(-1.5px);
            box-shadow: 0 6px 20px rgba(1, 20, 60, 0.32);
        }

        /* Profile Menu (Authenticated) */
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
            box-shadow: 0 1px 3px rgba(1, 20, 60, 0.04);
        }
        .profile-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #01143c;
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 13.5px;
            font-weight: 700;
        }
        .profile-name {
            font-size: 14.5px;
            font-weight: 700;
            color: #01143c;
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .profile-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background: #fff;
            border: 1px solid var(--bor);
            border-radius: 16px;
            box-shadow: 0 12px 36px rgba(0,0,0,.12);
            min-width: 230px;
            z-index: 500;
            overflow: hidden;
            display: none;
        }
        .profile-dropdown.open { display: block; }
        .profile-dropdown-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 16px;
            background: var(--pri-tint);
        }
        .profile-dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 600;
            color: var(--tv);
            width: 100%;
            text-align: left;
            transition: all 0.15s;
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

        /* ── HEADER HORIZONTAL DE EMPRESA (PORTADA) ── */
        .company-header-cover {
            width: 100%;
            height: 280px;
            background-size: cover;
            background-position: center;
            position: relative;
            background-color: #01143c;
            background-image: linear-gradient(135deg, rgba(1, 20, 60, 0.92) 0%, rgba(30, 58, 138, 0.88) 100%);
        }
        .cover-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.65) 100%);
        }
        .cover-content {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 24px 0;
        }
        .breadcrumb-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
            background: rgba(0,0,0,0.35);
            backdrop-filter: blur(8px);
            padding: 8px 16px;
            border-radius: 50px;
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.2s;
            width: fit-content;
        }
        .breadcrumb-back:hover {
            background: rgba(0,0,0,0.55);
            transform: translateX(-2px);
        }

        /* ── MAIN LAYOUT GRID (LEFT: PERFIL, RIGHT: VACANTES) ── */
        .profile-layout-container {
            margin-top: -60px;
            position: relative;
            z-index: 10;
            padding-bottom: 70px;
        }
        .profile-main-grid {
            display: grid;
            grid-template-columns: 360px 1fr;
            gap: 32px;
            align-items: start;
        }

        /* ── LEFT COLUMN: COMPANY DETAILS CARD ── */
        .company-sidebar-card {
            background: var(--sur);
            border: 1px solid var(--bor);
            border-radius: var(--rad);
            padding: 30px 26px;
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 105px;
        }
        .company-logo-avatar-box {
            width: 104px;
            height: 104px;
            border-radius: 22px;
            background: #ffffff;
            border: 3.5px solid #ffffff;
            box-shadow: 0 8px 24px rgba(1, 20, 60, 0.12);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: -65px;
            margin-bottom: 18px;
            background: #ffffff;
        }
        .company-logo-avatar-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 4px;
        }
        .company-logo-avatar-box .company-avatar-fallback {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 40px;
            font-weight: 800;
            color: #01143c;
        }
        .company-name-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: #01143c;
            line-height: 1.25;
            margin-bottom: 8px;
        }
        .company-verified-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 50px;
            background: #ecfdf5;
            color: #059669;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .company-section-title {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #64748b;
            margin-bottom: 10px;
        }
        .company-desc-text {
            font-size: 14.5px;
            line-height: 1.65;
            color: #334155;
            margin-bottom: 24px;
            word-break: break-word;
        }
        .company-info-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
            border-top: 1px solid var(--bor);
            padding-top: 20px;
        }
        .company-info-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 13.5px;
            color: #334155;
        }
        .company-info-item .icon {
            font-size: 20px;
            color: #475eb8;
            flex-shrink: 0;
            margin-top: 1px;
        }
        .company-info-link {
            color: #2563eb;
            font-weight: 600;
            word-break: break-all;
            transition: color 0.15s;
        }
        .company-info-link:hover { text-decoration: underline; }

        /* ── RIGHT COLUMN: ACTIVE OFFERS LIST ── */
        .company-offers-col {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .offers-header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e2e8f0;
        }
        .offers-heading {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: #01143c;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .offers-count-pill {
            font-size: 13px;
            font-weight: 700;
            background: #e0e7ff;
            color: #3730a3;
            padding: 3px 12px;
            border-radius: 50px;
        }

        /* ── JOB CARD STYLING ── */
        .company-offer-card {
            background: var(--sur);
            border: 1.5px solid var(--bor);
            border-radius: var(--rad);
            padding: 24px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .company-offer-card:hover {
            transform: translateY(-2px);
            border-color: #cbd5e1;
            box-shadow: 0 12px 28px -6px rgba(1, 20, 60, 0.09);
        }
        .card-top-badges {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }
        .badge-pill {
            font-size: 12px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 50px;
            background: #f1f5f9;
            color: #475569;
        }
        .badge-pill.highlight {
            background: #eff6ff;
            color: #1d4ed8;
        }
        .card-offer-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 19px;
            font-weight: 800;
            color: #01143c;
            line-height: 1.3;
            transition: color 0.15s;
        }
        .card-offer-title:hover { color: #2563eb; }
        .card-meta-list {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            font-size: 13px;
            color: #64748b;
        }
        .card-meta-point {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .card-snippet-desc {
            font-size: 14px;
            line-height: 1.6;
            color: #475569;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .card-bottom-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            padding-top: 16px;
            border-top: 1px solid #f1f5f9;
        }
        .card-salary-box {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
        }
        .card-salary-box small {
            font-size: 12px;
            font-weight: 500;
            color: #64748b;
        }
        .card-buttons-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn-view-detail {
            padding: 9px 18px;
            border: 1.5px solid #cbd5e1;
            border-radius: 50px;
            font-size: 13.5px;
            font-weight: 700;
            color: #334155;
            background: #ffffff;
            transition: all 0.2s;
        }
        .btn-view-detail:hover {
            border-color: #01143c;
            color: #01143c;
            background: #f8fafc;
        }
        .btn-apply-direct {
            padding: 9px 20px;
            border-radius: 50px;
            font-size: 13.5px;
            font-weight: 700;
            color: #ffffff;
            background: linear-gradient(135deg, #01143c 0%, #1e3a8a 100%);
            box-shadow: 0 4px 12px rgba(1, 20, 60, 0.2);
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-apply-direct:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(1, 20, 60, 0.3);
            opacity: 0.95;
        }
        .btn-applied-badge {
            padding: 9px 18px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 700;
            color: #059669;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /* Empty state */
        .empty-offers-box {
            background: #ffffff;
            border: 1.5px dashed #cbd5e1;
            border-radius: var(--rad);
            padding: 48px 24px;
            text-align: center;
            color: #64748b;
        }

        /* ── MODAL POSTULAR ── */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            backdrop-filter: blur(4px);
            z-index: 999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }
        .modal-overlay.open { display: flex; }
        .modal-card {
            background: #ffffff;
            border-radius: 24px;
            max-width: 500px;
            width: 100%;
            padding: 28px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            position: relative;
        }
        .modal-header-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .modal-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: #01143c;
        }
        .btn-close-modal {
            color: #64748b;
            padding: 4px;
            border-radius: 50%;
            transition: all 0.15s;
        }
        .btn-close-modal:hover { background: #f1f5f9; color: #0f172a; }

        /* Toast notification */
        .toast-notify {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #01143c;
            color: #ffffff;
            padding: 12px 24px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 1000;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .toast-notify.show {
            transform: translateY(0);
            opacity: 1;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .profile-main-grid {
                grid-template-columns: 1fr;
            }
            .company-sidebar-card {
                position: static;
            }
            .company-header-cover {
                height: 220px;
            }
        }
        @media (max-width: 640px) {
            .navbar-inner { height: 72px; gap: 12px; }
            .brand-logo-box { width: 44px; height: 44px; font-size: 20px; }
            .brand-title { font-size: 15px; max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
            .brand-sub { display: none; }
            .btn-login-nav { padding: 8px 14px; font-size: 13px; }
            .btn-empresa-nav { padding: 8px 15px; font-size: 13px; }
        }
    </style>
</head>
<body>

    {{-- ══ NAVBAR ══ --}}
    <nav class="navbar">
        <div class="page-container">
            <div class="navbar-inner">
                {{-- Brand --}}
                <a href="{{ route('landing') }}" class="brand">
                    <div class="brand-logo-box">
                        @if(!empty($config['logo']))
                            <img src="{{ $config['logo'] }}" alt="Logo">
                        @else
                            {{ strtoupper(substr($config['application_name'] ?? 'L', 0, 1)) }}
                        @endif
                    </div>
                    <div class="brand-text">
                        <span class="brand-title">{{ $config['application_name'] ?? 'IESP VILCANOTA' }}</span>
                        <span class="brand-sub">Bolsa laboral</span>
                    </div>
                </a>

                {{-- Nav Links --}}
                <div class="nav-links">
                    <a href="{{ route('landing') }}#oportunidades" class="nav-link">Oportunidades</a>

                    @if($authUser)
                        @php
                            $roleLabel = match((int)($authUser->rol_id ?? 0)) {
                                1 => 'Administrador',
                                2 => 'Docente',
                                3 => 'Estudiante',
                                4 => 'Empresa',
                                5 => 'Egresado',
                                default => 'Usuario'
                            };
                        @endphp
                        {{-- Perfil Logueado --}}
                        <div class="profile-menu-wrap" id="profile-menu-wrap">
                            <button class="profile-trigger" onclick="toggleProfileMenu()" aria-haspopup="true">
                                @if($authUser->avatar)
                                    <img src="{{ $authUser->avatar }}" class="profile-avatar" alt="Avatar">
                                @else
                                    <span class="profile-avatar">{{ strtoupper(substr($authUser->person->names ?? $authUser->email, 0, 2)) }}</span>
                                @endif
                                <span class="profile-name">{{ explode(' ', $authUser->person->names ?? $authUser->email)[0] }}</span>
                                <span class="material-symbols-outlined" style="font-size:18px;">expand_more</span>
                            </button>
                            <div class="profile-dropdown" id="profile-dropdown">
                                <div class="profile-dropdown-header">
                                    <span class="profile-avatar">{{ strtoupper(substr($authUser->person->names ?? $authUser->email, 0, 2)) }}</span>
                                    <div>
                                        <p style="font-size:13px;font-weight:700;color:var(--txt);">{{ $authUser->person->names ?? $authUser->email }}</p>
                                        <span style="font-size:10px;font-weight:700;background:#01143c;color:#fff;padding:2px 8px;border-radius:50px;display:inline-block;margin-top:2px;">{{ $roleLabel }}</span>
                                    </div>
                                </div>
                                @if(in_array($authUser->rol_id, [2, 3, 5]))
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
                                    Mis postulaciones
                                </a>
                                <a href="{{ route('student.saved.offers') }}" class="profile-dropdown-item">
                                    <span class="material-symbols-outlined">bookmark</span>
                                    Ofertas guardadas ({{ count($studentSavedOfferIds ?? []) }})
                                </a>
                                @endif
                                <button type="button" class="profile-dropdown-item" onclick="openPasswordModal();closeProfileMenu()">
                                    <span class="material-symbols-outlined">lock</span>
                                    Cambiar contraseña
                                </button>
                                <div class="profile-dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}" style="margin:0">
                                    @csrf
                                    <button type="submit" class="profile-dropdown-item" style="color:#dc2626;">
                                        <span class="material-symbols-outlined">logout</span> Cerrar sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn-login-nav">
                            <span>Iniciar Sesión</span>
                        </a>
                    @endif

                    <a href="{{ route('login') }}#empresa" class="btn-empresa-nav">
                        <span>Registrarme como Empresa</span>
                        <span>↗</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- ══ CABECERA HORIZONTAL DE PORTADA (TIPO HEADER CONFIGURADA EN PERFIL) ══ --}}
    <header class="company-header-cover" @if($company->cover_image) style="background-image: url('{{ asset($company->cover_image) }}');" @endif>
        <div class="cover-overlay"></div>
        <div class="page-container cover-content">
            <div>
                <a href="{{ route('landing') }}" class="breadcrumb-back">
                    <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
                    <span>Volver a la Bolsa Laboral</span>
                </a>
            </div>
        </div>
    </header>

    {{-- ══ CONTENIDO PRINCIPAL (COLUMNA IZQUIERDA: PERFIL/DESCRIPCIÓN, COLUMNA CENTRAL: VACANTES) ══ --}}
    <main class="page-container profile-layout-container">
        <div class="profile-main-grid">

            {{-- ── COLUMNA IZQUIERDA: LOGO + DESCRIPCIÓN COMPLETA + CONTACTO ── --}}
            <aside class="company-sidebar-card">
                {{-- Logo de la empresa --}}
                <div class="company-logo-avatar-box">
                    @if($company->logo)
                        <img src="{{ $company->logo }}" alt="{{ $company->name }}" onerror="this.onerror=null;this.parentNode.innerHTML='<span class=\'company-avatar-fallback\'>{{ strtoupper(substr($company->name, 0, 1)) }}</span>';">
                    @else
                        <span class="company-avatar-fallback">{{ strtoupper(substr($company->name, 0, 1)) }}</span>
                    @endif
                </div>

                <h1 class="company-name-title">{{ $company->name }}</h1>

                <div class="company-verified-badge">
                    <span class="material-symbols-outlined" style="font-size:16px;">verified</span>
                    <span>Empresa Verificada</span>
                </div>

                <h2 class="company-section-title">Acerca de la empresa</h2>
                <div class="company-desc-text">
                    @if(!empty(trim($company->description ?? '')))
                        {!! nl2br(e($company->description)) !!}
                    @else
                        <p style="color:#64748b;font-style:italic;">Empresa verificada y aliada de nuestra bolsa laboral comprometida con la empleabilidad y desarrollo profesional.</p>
                    @endif
                </div>

                <h2 class="company-section-title">Información y Contacto</h2>
                <div class="company-info-list">
                    @if(!empty($company->website))
                    <div class="company-info-item">
                        <span class="material-symbols-outlined icon">language</span>
                        <div>
                            <span style="font-size:11px;color:#64748b;display:block;">Sitio Web</span>
                            <a href="{{ $company->website }}" target="_blank" rel="noopener noreferrer" class="company-info-link">
                                {{ parse_url($company->website, PHP_URL_HOST) ?? $company->website }}
                            </a>
                        </div>
                    </div>
                    @endif

                    @if(!empty($company->address))
                    <div class="company-info-item">
                        <span class="material-symbols-outlined icon">location_on</span>
                        <div>
                            <span style="font-size:11px;color:#64748b;display:block;">Ubicación</span>
                            <span>{{ $company->address }}</span>
                        </div>
                    </div>
                    @endif

                    @if(!empty($company->email) || !empty($company->mailbox))
                    <div class="company-info-item">
                        <span class="material-symbols-outlined icon">mail</span>
                        <div>
                            <span style="font-size:11px;color:#64748b;display:block;">Correo de contacto</span>
                            <span>{{ $company->mailbox ?? $company->email }}</span>
                        </div>
                    </div>
                    @endif

                    @if(!empty($company->phone))
                    <div class="company-info-item">
                        <span class="material-symbols-outlined icon">call</span>
                        <div>
                            <span style="font-size:11px;color:#64748b;display:block;">Teléfono</span>
                            <span>{{ $company->phone }}</span>
                        </div>
                    </div>
                    @endif

                    <div class="company-info-item">
                        <span class="material-symbols-outlined icon">work</span>
                        <div>
                            <span style="font-size:11px;color:#64748b;display:block;">Convocatorias</span>
                            <strong>{{ $companyOffers->count() }} vacantes activas</strong>
                        </div>
                    </div>
                </div>
            </aside>

            {{-- ── COLUMNA CENTRAL: LISTA DE OFERTAS LABORALES Y POSTULACIÓN ── --}}
            <section class="company-offers-col">
                <div class="offers-header-row">
                    <h2 class="offers-heading">
                        <span>Oportunidades de empleo vigentes</span>
                        <span class="offers-count-pill">{{ $companyOffers->count() }}</span>
                    </h2>
                </div>

                @forelse($companyOffers as $off)
                    @php
                        $isPractica = Str::contains(Str::lower($off->title), ['practicante', 'práctica'])
                            || Str::contains(Str::lower($off->category->name ?? ''), ['práctica']);
                        $alreadyApplied = in_array($off->id, $appliedOfferIds ?? []);
                        $salaryText = 'A tratar';
                        if (!$off->hide_salary && $off->salary) {
                            $salaryText = 'S/ ' . number_format($off->salary, 2) . ' / mes';
                        }
                    @endphp

                    <article class="company-offer-card">
                        <div class="card-top-badges">
                            <span class="badge-pill highlight">
                                {{ $isPractica ? 'Prácticas profesionales' : ($off->contractType->name ?? 'Empleo') }}
                            </span>
                            @if($off->modality)
                                <span class="badge-pill">{{ $off->modality->name }}</span>
                            @endif
                            @if($off->workSchedule)
                                <span class="badge-pill">{{ $off->workSchedule->name }}</span>
                            @endif
                            @if($off->category)
                                <span class="badge-pill" style="background:#e0f2fe;color:#0284c7;">{{ $off->category->name }}</span>
                            @endif
                        </div>

                        <div>
                            <a href="/ofertas/{{ $off->id }}">
                                <h3 class="card-offer-title">{{ $off->title }}</h3>
                            </a>
                        </div>

                        <div class="card-meta-list">
                            <span class="card-meta-point">
                                <span class="material-symbols-outlined" style="font-size:16px;">location_on</span>
                                {{ ($off->province ? $off->province . ', ' : '') . ($off->department ?? 'Perú') }}
                            </span>
                            <span class="card-meta-point">
                                <span class="material-symbols-outlined" style="font-size:16px;">calendar_today</span>
                                Publicado el {{ \Carbon\Carbon::parse($off->publication_date)->format('d M, Y') }}
                            </span>
                            @if($off->deadline)
                            <span class="card-meta-point" style="color:#b45309;">
                                <span class="material-symbols-outlined" style="font-size:16px;">hourglass_top</span>
                                Cierra el {{ \Carbon\Carbon::parse($off->deadline)->format('d M, Y') }}
                            </span>
                            @endif
                        </div>

                        <p class="card-snippet-desc">
                            {{ Str::limit(strip_tags($off->description), 160) }}
                        </p>

                        <div class="card-bottom-bar">
                            <div class="card-salary-box">
                                {{ $salaryText }}
                            </div>

                            <div class="card-buttons-group">
                                <a href="/ofertas/{{ $off->id }}" class="btn-view-detail">
                                    Ver detalle
                                </a>

                                @if($alreadyApplied)
                                    <span class="btn-applied-badge">
                                        <span class="material-symbols-outlined" style="font-size:16px;">check_circle</span>
                                        <span>Ya postulado</span>
                                    </span>
                                @elseif($authUser && ($authUser->rol_id == 3 || $authUser->rol_id == 5 || $authUser->rol_id == 2))
                                    <button type="button" class="btn-apply-direct" onclick="openApplyModal({{ $off->id }}, '{{ addslashes($off->title) }}')">
                                        <span class="material-symbols-outlined" style="font-size:16px;">send</span>
                                        <span>Postularme</span>
                                    </button>
                                @else
                                    <a href="{{ route('login') }}?redirect={{ urlencode('/ofertas/' . $off->id) }}" class="btn-apply-direct">
                                        <span class="material-symbols-outlined" style="font-size:16px;">login</span>
                                        <span>Postularme</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="empty-offers-box">
                        <span class="material-symbols-outlined" style="font-size:48px;color:#94a3b8;margin-bottom:12px;">work_off</span>
                        <h3 style="font-size:18px;font-weight:700;color:#0f172a;margin-bottom:6px;">No hay convocatorias activas en este momento</h3>
                        <p style="font-size:14px;color:#64748b;">{{ $company->name }} no tiene vacantes publicadas hoy. Te invitamos a revisar periódicamente su perfil.</p>
                    </div>
                @endforelse
            </section>
        </div>
    </main>

    {{-- ══ MODAL DE POSTULACIÓN DIRECTA ══ --}}
    @if($authUser && ($authUser->rol_id == 3 || $authUser->rol_id == 5 || $authUser->rol_id == 2))
    <div id="apply-modal" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-header-row">
                <div>
                    <h3 class="modal-title">Postular a vacante</h3>
                    <p id="modal-offer-title-text" style="font-size:14px;color:#475569;margin-top:2px;"></p>
                </div>
                <button type="button" class="btn-close-modal" onclick="closeApplyModal()">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form id="apply-form" onsubmit="handleApplySubmit(event)">
                <input type="hidden" id="modal-offer-id" value="">

                <div id="modal-alert-box" style="display:none;padding:12px 14px;border-radius:12px;font-size:13px;margin-bottom:16px;"></div>

                @if($studentCvs->count() > 0)
                    <div style="margin-bottom:18px;">
                        <label for="apply-cv-select" style="display:block;font-size:13px;font-weight:700;color:#0f172a;margin-bottom:6px;">
                            Selecciona tu Currículum Vitae (CV) *
                        </label>
                        <select id="apply-cv-select" required style="width:100%;padding:10px 14px;border-radius:12px;border:1.5px solid #cbd5e1;font-size:14px;outline:none;">
                            @foreach($studentCvs as $cv)
                                <option value="{{ $cv->id }}">{{ $cv->filename }} (v{{ $cv->version }})</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div style="padding:14px;background:#fffbeb;border:1px solid #fef3c7;border-radius:14px;margin-bottom:18px;">
                        <p style="font-size:13px;color:#92400e;margin-bottom:8px;">Aún no has subido ningún CV a tu perfil de estudiante.</p>
                        <a href="{{ route('student.applications') }}" style="font-size:13px;font-weight:700;color:#b45309;text-decoration:underline;">
                            Subir mi CV en mis postulaciones →
                        </a>
                    </div>
                @endif

                <div style="margin-bottom:22px;">
                    <label for="apply-message-input" style="display:block;font-size:13px;font-weight:700;color:#0f172a;margin-bottom:6px;">
                        Mensaje para el reclutador (Opcional)
                    </label>
                    <textarea id="apply-message-input" rows="3" placeholder="Preséntate brevemente y destaca por qué eres ideal para este puesto..."
                              style="width:100%;padding:10px 14px;border-radius:12px;border:1.5px solid #cbd5e1;font-size:14px;outline:none;font-family:inherit;"></textarea>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:12px;">
                    <button type="button" onclick="closeApplyModal()" style="padding:10px 20px;border-radius:50px;border:1px solid #cbd5e1;font-size:14px;font-weight:600;color:#475569;">
                        Cancelar
                    </button>
                    @if($studentCvs->count() > 0)
                    <button type="submit" id="btn-submit-apply" style="padding:10px 24px;border-radius:50px;font-size:14px;font-weight:700;color:#ffffff;background:linear-gradient(135deg, #01143c 0%, #1e3a8a 100%);box-shadow:0 4px 14px rgba(1,20,60,0.25);">
                        Confirmar Postulación
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Toast feedback --}}
    <div id="toast-notify" class="toast-notify">
        <span class="material-symbols-outlined" id="toast-icon">check_circle</span>
        <span id="toast-text"></span>
    </div>

    <script>
        function toggleProfileMenu() {
            var menu = document.getElementById('profile-dropdown');
            if (menu) menu.classList.toggle('open');
        }
        document.addEventListener('click', function(e) {
            var wrap = document.getElementById('profile-menu-wrap');
            if (wrap && !wrap.contains(e.target)) {
                var menu = document.getElementById('profile-dropdown');
                if (menu) menu.classList.remove('open');
            }
        });

        function showToast(msg, icon) {
            var toast = document.getElementById('toast-notify');
            var txt = document.getElementById('toast-text');
            var ic = document.getElementById('toast-icon');
            if (!toast) return;
            txt.textContent = msg;
            if (icon) ic.textContent = icon;
            toast.classList.add('show');
            setTimeout(function() { toast.classList.remove('show'); }, 3500);
        }

        var activeOfferId = null;

        function openApplyModal(offerId, title) {
            activeOfferId = offerId;
            var modal = document.getElementById('apply-modal');
            var txt = document.getElementById('modal-offer-title-text');
            var alertBox = document.getElementById('modal-alert-box');
            if (modal) {
                if (txt) txt.textContent = title;
                if (alertBox) alertBox.style.display = 'none';
                modal.classList.add('open');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeApplyModal() {
            var modal = document.getElementById('apply-modal');
            if (modal) {
                modal.classList.remove('open');
                document.body.style.overflow = '';
            }
        }

        function handleApplySubmit(e) {
            e.preventDefault();
            if (!activeOfferId) return;

            var cvSelect = document.getElementById('apply-cv-select');
            var msgInput = document.getElementById('apply-message-input');
            var btn = document.getElementById('btn-submit-apply');
            var alertBox = document.getElementById('modal-alert-box');

            if (!cvSelect || !cvSelect.value) {
                alertBox.style.display = 'block';
                alertBox.style.background = '#fef2f2';
                alertBox.style.color = '#991b1b';
                alertBox.textContent = 'Por favor selecciona un CV antes de postular.';
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<span class="material-symbols-outlined" style="font-size:16px;">sync</span> Enviando...';

            var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/student/apply/' + activeOfferId, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    cv_id: cvSelect.value,
                    message: msgInput ? msgInput.value : ''
                })
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    closeApplyModal();
                    showToast('¡Postulación enviada exitosamente!', 'check_circle');
                    setTimeout(function() { window.location.reload(); }, 1200);
                } else {
                    alertBox.style.display = 'block';
                    alertBox.style.background = '#fef2f2';
                    alertBox.style.color = '#991b1b';
                    alertBox.textContent = data.message || 'Error al enviar la postulación.';
                    btn.disabled = false;
                    btn.textContent = 'Confirmar Postulación';
                }
            })
            .catch(function() {
                alertBox.style.display = 'block';
                alertBox.style.background = '#fef2f2';
                alertBox.style.color = '#991b1b';
                alertBox.textContent = 'Error de red al procesar la solicitud.';
                btn.disabled = false;
                btn.textContent = 'Confirmar Postulación';
            });
        }

        // ── Password Modal Handlers ────────────────────────────────────────────────
        function openPasswordModal() {
            var m = document.getElementById('modal-password');
            var alertBox = document.getElementById('lpwd-alert');
            if (alertBox) alertBox.style.display = 'none';
            var form = document.getElementById('form-landing-password');
            if (form) form.reset();
            if (m) m.classList.add('open');
        }
        function closePasswordModal() {
            var m = document.getElementById('modal-password');
            if (m) m.classList.remove('open');
        }

        function submitChangePassword() {
            var cur = document.getElementById('lpwd-current').value;
            var np  = document.getElementById('lpwd-new').value;
            var conf = document.getElementById('lpwd-confirm').value;
            var alertBox = document.getElementById('lpwd-alert');

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

            var btn = document.getElementById('btn-lpwd-submit');
            var txt = document.getElementById('btn-lpwd-text');
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
                closeApplyModal();
                closePasswordModal();
            }
        });
    </script>

    {{-- ══ MODAL CAMBIAR CONTRASEÑA ══ --}}
    <div id="modal-password" class="modal-overlay" onclick="if(event.target===this)closePasswordModal()">
        <div class="modal-box" style="max-width:440px;">
            <button type="button" class="modal-close-btn" onclick="closePasswordModal()">
                <span class="material-symbols-outlined">close</span>
            </button>

            <div style="width:52px;height:52px;border-radius:16px;background:var(--pri-tint);color:var(--pri);display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                <span class="material-symbols-outlined filled" style="font-size:26px;">lock_reset</span>
            </div>
            <h3 style="font-family:'Plus Jakarta Sans',sans-serif;font-size:20px;font-weight:800;color:#111827;text-align:center;margin-bottom:6px;">Cambiar Contraseña</h3>
            <p style="font-size:13px;color:#6b7280;text-align:center;margin-bottom:20px;">Ingresa tu clave actual y define una nueva contraseña de al menos 8 caracteres.</p>

            <form id="form-landing-password" onsubmit="event.preventDefault();submitChangePassword()">
                @csrf
                <div style="text-align:left;margin-bottom:14px;">
                    <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;">Contraseña Actual</label>
                    <input type="password" id="lpwd-current" class="form-input" style="width:100%;height:44px;padding:0 14px;border:1.5px solid var(--bor);border-radius:12px;" required placeholder="Tu contraseña actual">
                </div>
                <div style="text-align:left;margin-bottom:14px;">
                    <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;">Nueva Contraseña</label>
                    <input type="password" id="lpwd-new" class="form-input" style="width:100%;height:44px;padding:0 14px;border:1.5px solid var(--bor);border-radius:12px;" required minlength="8" placeholder="Mínimo 8 caracteres">
                </div>
                <div style="text-align:left;margin-bottom:18px;">
                    <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;">Confirmar Nueva Contraseña</label>
                    <input type="password" id="lpwd-confirm" class="form-input" style="width:100%;height:44px;padding:0 14px;border:1.5px solid var(--bor);border-radius:12px;" required minlength="8" placeholder="Repite la nueva contraseña">
                </div>

                <div id="lpwd-alert" style="display:none;padding:10px 14px;border-radius:10px;font-size:13px;font-weight:600;margin-bottom:14px;text-align:left;"></div>

                <div style="display:flex;align-items:center;gap:12px;">
                    <button type="submit" id="btn-lpwd-submit" class="btn-primary" style="flex:1;justify-content:center;">
                        <span class="material-symbols-outlined" style="font-size:18px;">check</span>
                        <span id="btn-lpwd-text">Actualizar Contraseña</span>
                    </button>
                    <button type="button" class="btn-cancel" onclick="closePasswordModal()" style="width:auto;padding:12px 18px;">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
