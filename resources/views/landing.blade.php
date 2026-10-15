<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $config['application_name'] ?? 'Bolsa Laboral' }} — Oportunidades Laborales</title>
    <meta name="description" content="Prácticas y empleos para estudiantes y egresados. Conecta con empresas y da el siguiente paso en tu carrera.">
    <link rel="icon" href="{{ $config['favicon'] ?? '/assets/favicon.png' }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL@20..48,100..700,0..1&display=swap" rel="stylesheet">

    <style>
        :root {
            --pri: {{ $config['primary_color'] ?? '#4f46e5' }};
            --pri-hover: color-mix(in srgb, var(--pri) 85%, #000000);
            --pri-tint: color-mix(in srgb, var(--pri) 6%, #ffffff);
            --pri-tint-border: color-mix(in srgb, var(--pri) 16%, transparent);
            --pri-pill-bg: color-mix(in srgb, var(--pri) 12%, #ffffff);
            --sec: {{ $config['secondary_color'] ?? '#006b60' }};
            --secc: {{ $config['secondary_container_color'] ?? '#7df7e4' }};
            --acc: {{ $config['accent_color'] ?? '#f97316' }};
            --bg: #fafbfc;
            --sur: #ffffff;
            --bor: #e5e7eb;
            --txt: #111827;
            --tv: #4b5563;
            --tm: #9ca3af;
            --rad: 16px;
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
        .hidden { display: none !important; }

        /* Container - Full Fluid Width */
        .page-container {
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            padding: 0 clamp(16px, 3.2vw, 56px);
        }

        /* ── NAVBAR ── */
        .navbar {
            background: var(--sur);
            border-bottom: 1px solid var(--bor);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .navbar-inner {
            height: 74px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .brand-logo-box {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--pri);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 800;
            overflow: hidden;
            flex-shrink: 0;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }
        .brand-logo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .brand-text {
            display: flex;
            flex-direction: column;
        }
        .brand-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 15px;
            font-weight: 800;
            color: #111827;
            line-height: 1.2;
            letter-spacing: -0.01em;
        }
        .brand-sub {
            font-size: 11px;
            font-weight: 500;
            color: #6b7280;
            margin-top: 1px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 24px;
        }
        .nav-link {
            font-size: 14px;
            font-weight: 600;
            color: #4b5563;
            transition: color 0.15s;
        }
        .nav-link:hover {
            color: var(--pri);
        }
        .btn-empresa-nav {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            border: 1.5px solid var(--bor);
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            color: var(--txt);
            background: #ffffff;
            transition: all 0.2s;
        }
        .btn-empresa-nav:hover {
            border-color: var(--pri);
            color: var(--pri);
            background: var(--pri-tint);
        }

        /* Profile Menu (Authenticated) */
        .profile-menu-wrap { position: relative; }
        .profile-trigger {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 5px 12px;
            border: 1.5px solid var(--bor);
            border-radius: 50px;
            background: #fff;
            cursor: pointer;
            transition: all 0.15s;
        }
        .profile-trigger:hover {
            border-color: var(--pri);
        }
        .profile-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--pri);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            flex-shrink: 0;
        }
        .profile-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--txt);
            max-width: 100px;
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
            animation: fadeIn .15s ease both;
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
            width: 100%;
            padding: 10px 16px;
            border: none;
            background: transparent;
            color: var(--txt);
            font-size: 13px;
            cursor: pointer;
            transition: background .12s;
            text-align: left;
        }
        .profile-dropdown-item:hover {
            background: var(--pri-tint);
            color: var(--pri);
        }
        .profile-dropdown-divider {
            height: 1px;
            background: var(--bor);
            margin: 4px 0;
        }

        /* ── HERO BANNER ── */
        .hero-wrap {
            margin-top: 24px;
            margin-bottom: 24px;
        }
        .hero-banner-card {
            background: var(--pri-tint);
            border: 1px solid var(--pri-tint-border);
            border-radius: 28px;
            padding: clamp(36px, 3.5vw, 56px) clamp(24px, 4vw, 64px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
            position: relative;
            overflow: visible;
        }
        .hero-left {
            max-width: 760px;
            flex: 1;
        }
        .hero-tagline {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--pri);
            margin-bottom: 16px;
        }
        .hero-tagline-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--pri);
        }
        .hero-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: clamp(30px, 2.7vw, 44px);
            font-weight: 800;
            line-height: 1.15;
            color: #111827;
            letter-spacing: -0.03em;
            margin-bottom: 16px;
        }
        .hero-desc {
            font-size: clamp(14px, 1.05vw, 16px);
            line-height: 1.6;
            color: #4b5563;
            margin-bottom: 28px;
            max-width: 640px;
        }
        .hero-bottom-info {
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 13px;
            color: #4b5563;
            flex-wrap: wrap;
        }
        .hero-offers-count strong {
            color: var(--pri);
            font-size: 15px;
            font-weight: 800;
        }
        .hero-profile-link {
            font-weight: 700;
            color: var(--pri);
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: opacity 0.15s;
        }
        .hero-profile-link:hover { opacity: 0.8; }

        /* Right Tilted 3D Card */
        .hero-right-visual {
            position: relative;
            flex-shrink: 0;
            width: 270px;
            height: 250px;
        }
        .tilted-card {
            width: 250px;
            height: 230px;
            background: linear-gradient(145deg, var(--pri), color-mix(in srgb, var(--pri) 85%, #000000));
            border-radius: 24px;
            padding: 24px;
            color: #ffffff;
            transform: rotate(-5deg);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.16);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            transition: transform 0.3s ease;
        }
        .tilted-card:hover {
            transform: rotate(-2deg) scale(1.02);
        }
        .tilted-card-arrow-wrap {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 700;
            color: #ffffff;
        }
        .tilted-card-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 24px;
            font-weight: 800;
            line-height: 1.25;
            color: #ffffff;
            letter-spacing: -0.02em;
        }
        .tilted-card-footer {
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 0.12em;
            color: rgba(255, 255, 255, 0.75);
            text-transform: uppercase;
        }
        .floating-pill {
            position: absolute;
            bottom: -6px;
            right: -10px;
            background: #ffffff;
            border-radius: 50px;
            padding: 8px 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--bor);
            font-size: 12px;
            font-weight: 700;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            z-index: 5;
        }

        /* ── SEARCH & FILTER CARD ── */
        .search-card {
            background: #ffffff;
            border: 1px solid var(--bor);
            border-radius: 20px;
            padding: clamp(20px, 2.4vw, 32px) clamp(18px, 3vw, 36px);
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.02);
            margin-bottom: 36px;
        }
        .search-fields-grid {
            display: grid;
            grid-template-columns: 1.8fr 1.2fr;
            gap: 20px;
            margin-bottom: 16px;
        }
        .field-label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
        }
        .field-input, .field-select {
            width: 100%;
            height: 48px;
            border: 1.5px solid var(--bor);
            border-radius: 12px;
            padding: 0 16px;
            font-size: 14px;
            font-family: inherit;
            color: var(--txt);
            background: #ffffff;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .field-input:focus, .field-select:focus {
            border-color: var(--pri);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--pri) 15%, transparent);
        }
        .field-select {
            cursor: pointer;
        }

        .btn-search-main {
            width: 100%;
            height: 48px;
            background: var(--pri);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.15s;
            margin-bottom: 20px;
        }
        .btn-search-main:hover {
            opacity: 0.94;
            transform: translateY(-1px);
        }

        .filter-row {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }
        .filter-selectors {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            flex: 1;
        }
        .filter-item-wrap {
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1 1 180px;
            min-width: 170px;
        }
        .filter-item-label {
            font-size: 11px;
            font-weight: 700;
            color: #6b7280;
        }
        .sub-select {
            width: 100%;
            height: 40px;
            border: 1.5px solid var(--bor);
            border-radius: 10px;
            padding: 0 14px;
            font-size: 13px;
            font-family: inherit;
            color: #374151;
            background: #ffffff;
            cursor: pointer;
            outline: none;
            transition: border-color 0.15s;
        }
        .sub-select:focus {
            border-color: var(--pri);
        }
        .btn-clear-filters {
            background: transparent;
            border: none;
            color: #6b7280;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: underline;
            padding: 8px 10px;
            transition: color 0.15s;
            margin-bottom: 2px;
            white-space: nowrap;
        }
        .btn-clear-filters:hover {
            color: var(--pri);
        }

        /* ── SECTION HEADER ── */
        .section-header-wrap {
            margin-bottom: 20px;
        }
        .section-tagline {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--pri);
            margin-bottom: 6px;
            display: block;
        }
        .section-title-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }
        .section-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 26px;
            font-weight: 800;
            color: #111827;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: -0.02em;
        }
        .offers-count-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--pri-pill-bg);
            color: var(--pri);
            font-size: 13px;
            font-weight: 800;
            padding: 2px 10px;
            border-radius: 50px;
        }
        .section-subtitle {
            font-size: 13px;
            color: #6b7280;
            font-weight: 500;
        }

        /* ── RESPONSIVE JOB CARDS GRID ── */
        .job-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(min(100%, 360px), 1fr));
            gap: 22px;
            margin-bottom: 36px;
        }
        .job-card {
            background: #ffffff;
            border: 1.5px solid var(--bor);
            border-radius: 20px;
            padding: 22px 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }
        .job-card:hover {
            border-color: var(--pri);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.06);
            transform: translateY(-2px);
        }
        .job-card.card-applied {
            border-color: #cbd5e1;
            background: #f8fafc;
        }
        .card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 12px;
        }
        .card-header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .card-avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            font-weight: 800;
            overflow: hidden;
            flex-shrink: 0;
        }
        .card-avatar-text {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .card-logo-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .card-type-badge {
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 50px;
            display: inline-block;
        }
        .badge-practica {
            background: #dcfce7;
            color: #15803d;
        }
        .badge-empleo {
            background: #f3e8ff;
            color: #6b21a8;
        }

        .btn-card-star {
            background: transparent;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.15s, transform 0.15s;
        }
        .btn-card-star:hover {
            color: var(--pri);
            transform: scale(1.15);
        }
        .btn-card-star.active {
            color: var(--pri);
        }

        .card-company-name {
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 4px;
        }
        .card-job-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 17px;
            font-weight: 800;
            color: #111827;
            line-height: 1.35;
            margin-bottom: 8px;
            transition: color 0.15s;
        }
        .job-card:hover .card-job-title {
            color: var(--pri);
        }
        .card-meta-row {
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 12px;
        }
        .card-meta-item {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .card-meta-item .material-symbols-outlined {
            font-size: 15px;
            color: #9ca3af;
        }
        .card-desc {
            font-size: 13px;
            color: #4b5563;
            line-height: 1.55;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 16px;
        }
        .card-salary {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 16px;
            font-weight: 800;
            color: #111827;
            margin-bottom: 16px;
        }
        .card-salary-sub {
            font-size: 12px;
            font-weight: 500;
            color: #9ca3af;
        }

        .card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 1px solid #f3f4f6;
            padding-top: 14px;
            font-size: 12px;
        }
        .card-close-date {
            color: #9ca3af;
            font-weight: 500;
        }
        .card-link-action {
            color: var(--pri);
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        /* ── SKELETONS ── */
        .skel-box {
            background: linear-gradient(90deg, #f3f4f6 25%, #e5e7eb 50%, #f3f4f6 75%);
            background-size: 200% 100%;
            animation: skelPulse 1.4s infinite;
            border-radius: 8px;
        }
        @keyframes skelPulse {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* ── BOTTOM CTA BANNER (COMPANIES) ── */
        .cta-company-card {
            background: var(--pri-tint);
            border: 1px solid var(--pri-tint-border);
            border-radius: 24px;
            padding: clamp(28px, 3.2vw, 44px) clamp(24px, 3.8vw, 56px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 48px;
        }
        .cta-company-left {
            max-width: 820px;
        }
        .cta-company-tagline {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--pri);
            margin-bottom: 8px;
            display: block;
        }
        .cta-company-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: clamp(20px, 2vw, 26px);
            font-weight: 800;
            color: #111827;
            margin-bottom: 6px;
            letter-spacing: -0.02em;
        }
        .cta-company-desc {
            font-size: 13px;
            color: #4b5563;
        }
        .btn-cta-company {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 26px;
            background: var(--pri);
            color: #ffffff;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            white-space: nowrap;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
            transition: all 0.2s;
        }
        .btn-cta-company:hover {
            opacity: 0.92;
            transform: translateY(-1px);
        }

        /* ── FOOTER ── */
        .footer {
            border-top: 1px solid var(--bor);
            background: #ffffff;
            padding: 22px 0;
            margin-top: auto;
        }
        .footer-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
            color: #9ca3af;
        }
        .footer-brand {
            font-weight: 600;
            color: #6b7280;
        }
        .footer-link {
            color: #6b7280;
            transition: color 0.15s;
        }
        .footer-link:hover {
            color: var(--pri);
        }

        /* ── MODALS (OPPORTUNITY DETAIL & POSTULAR) ── */
        .modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 400;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }
        .modal-overlay.open { display: flex; }
        .modal-box {
            background: var(--sur);
            border-radius: 24px;
            width: 100%;
            max-width: 640px;
            max-height: 90vh;
            overflow-y: auto;
            padding: 32px;
            box-shadow: 0 24px 64px rgba(0, 0, 0, 0.2);
            animation: popIn 0.2s ease both;
            position: relative;
        }
        @keyframes popIn {
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
            color: #6b7280;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s;
        }
        .modal-close-btn:hover {
            background: var(--bg);
            color: #111827;
        }

        .modal-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .modal-btn-pri {
            padding: 14px;
            background: var(--pri);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.15s;
            text-decoration: none;
        }
        .modal-btn-pri:hover { opacity: 0.92; }
        .modal-btn-sec {
            padding: 12px;
            background: transparent;
            color: var(--txt);
            border: 1.5px solid var(--bor);
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.15s;
            text-decoration: none;
        }
        .modal-btn-sec:hover { border-color: var(--pri); color: var(--pri); }

        /* Toast Alert */
        .toast-notify {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #111827;
            color: #ffffff;
            padding: 12px 20px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            z-index: 1000;
            display: none;
            align-items: center;
            gap: 8px;
            animation: slideUp 0.25s ease both;
        }
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* ── RESPONSIVE DESIGN ── */
        @media (max-width: 960px) {
            .hero-banner-card {
                flex-direction: column;
                padding: 32px 20px;
                text-align: center;
                gap: 28px;
            }
            .hero-left {
                max-width: 100%;
            }
            .hero-desc {
                margin: 0 auto 20px;
            }
            .hero-bottom-info {
                justify-content: center;
            }
            .hero-right-visual {
                margin: 0 auto;
            }
            .search-fields-grid {
                grid-template-columns: 1fr;
            }
            .cta-company-card {
                flex-direction: column;
                text-align: center;
                padding: 28px 20px;
                gap: 20px;
            }
            .btn-cta-company {
                width: 100%;
                justify-content: center;
            }
        }
        @media (max-width: 768px) {
            .filter-row {
                flex-direction: column;
                align-items: stretch;
                gap: 14px;
            }
            .filter-selectors {
                width: 100%;
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }
            .filter-item-wrap {
                width: 100%;
                min-width: 100%;
            }
            .btn-clear-filters {
                text-align: center;
                width: 100%;
                margin-top: 0;
            }
            .section-title-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 6px;
            }
        }
        @media (max-width: 640px) {
            .navbar-inner {
                height: 60px;
                gap: 10px;
            }
            .brand-logo-box {
                width: 34px;
                height: 34px;
                font-size: 16px;
            }
            .brand-title {
                font-size: 13px;
                max-width: 150px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            .brand-sub {
                display: none;
            }
            .nav-link-opportunities {
                display: none;
            }
            .btn-empresa-nav {
                padding: 6px 14px;
                font-size: 12px;
            }
            .hero-title {
                font-size: 26px;
            }
            .search-card {
                padding: 18px 14px;
            }
            .footer-inner {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
            .modal-box {
                padding: 22px 18px;
                border-radius: 20px;
            }
        }
        @media (max-width: 420px) {
            .hero-right-visual {
                width: 240px;
                height: 220px;
            }
            .tilted-card {
                width: 220px;
                height: 200px;
                padding: 18px;
            }
            .tilted-card-title {
                font-size: 20px;
            }
            .floating-pill {
                font-size: 11px;
                padding: 6px 12px;
                right: -6px;
                bottom: -8px;
            }
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
                        <span class="brand-title">{{ $config['application_name'] ?? 'Instituto de prueba LOOPZ' }}</span>
                        <span class="brand-sub">Bolsa laboral</span>
                    </div>
                </a>

                {{-- Nav Links --}}
                <div class="nav-links">
                    <a href="#oportunidades" class="nav-link nav-link-opportunities">Oportunidades</a>

                    @if($authUser)
                        {{-- Perfil Logueado --}}
                        <div class="profile-menu-wrap" id="profile-menu-wrap">
                            <button class="profile-trigger" onclick="toggleProfileMenu()" aria-haspopup="true">
                                @if($authUser->avatar)
                                    <img src="{{ $authUser->avatar }}" class="profile-avatar" alt="Avatar">
                                @else
                                    <span class="profile-avatar">{{ strtoupper(substr($authUser->person->names ?? $authUser->email, 0, 2)) }}</span>
                                @endif
                                <span class="profile-name">{{ explode(' ', $authUser->person->names ?? $authUser->email)[0] }}</span>
                                <span class="material-symbols-outlined" style="font-size:16px;">expand_more</span>
                            </button>
                            <div class="profile-dropdown" id="profile-dropdown">
                                <div class="profile-dropdown-header">
                                    <span class="profile-avatar">{{ strtoupper(substr($authUser->person->names ?? $authUser->email, 0, 2)) }}</span>
                                    <div>
                                        <p style="font-size:13px;font-weight:700;color:var(--txt);">{{ $authUser->person->names ?? $authUser->email }}</p>
                                        <p style="font-size:11px;color:var(--tv);">{{ $authUser->email }}</p>
                                    </div>
                                </div>
                                @if($authUser->rol_id == 3 || $authUser->rol_id == 5)
                                <a href="{{ route('student.applications') }}" class="profile-dropdown-item">
                                    <span class="material-symbols-outlined">assignment_turned_in</span>
                                    Mis postulaciones ({{ $studentApplications->count() }})
                                </a>
                                <a href="{{ route('student.saved.offers') }}" class="profile-dropdown-item">
                                    <span class="material-symbols-outlined">bookmark</span>
                                    Ofertas guardadas ({{ count($studentSavedOfferIds ?? []) }})
                                </a>
                                @endif
                                <button class="profile-dropdown-item" onclick="openModal('modal-password');closeProfileMenu()">
                                    <span class="material-symbols-outlined">lock</span> Cambiar contraseña
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
                        <a href="{{ route('login') }}" class="nav-link">Mi espacio</a>
                    @endif

                    <a href="{{ route('login') }}#empresa" class="btn-empresa-nav">
                        <span>Mi empresa</span>
                        <span style="font-size:13px;">↗</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="page-container">

        {{-- ══ HERO BANNER ══ --}}
        <section class="hero-wrap">
            <div class="hero-banner-card">
                <div class="hero-left">
                    <div class="hero-tagline">
                        <span class="hero-tagline-dot"></span>
                        <span>TALENTO QUE CONECTA</span>
                    </div>
                    <h1 class="hero-title">
                        Tu talento, tu próxima<br>oportunidad.
                    </h1>
                    <p class="hero-desc">
                        Prácticas y empleos para estudiantes y egresados. Conecta con empresas y da el siguiente paso en tu carrera.
                    </p>
                    <div class="hero-bottom-info">
                        <span class="hero-offers-count">
                            <strong id="hero-count-number">{{ $totalActiveOffers }}</strong> oportunidades vigentes
                        </span>
                        @if($authUser)
                            <a href="#" onclick="openModal('modal-perfil');return false;" class="hero-profile-link">
                                Completar mi perfil ↗
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="hero-profile-link">
                                Iniciar sesión ↗
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Tilted 3D Card --}}
                <div class="hero-right-visual">
                    <div class="tilted-card">
                        <div class="tilted-card-arrow-wrap">
                            ↗
                        </div>
                        <div class="tilted-card-title">
                            Tu próximo<br>paso<br>empieza aquí.
                        </div>
                        <div class="tilted-card-footer">
                            APRENDE • CONECTA • CRECE
                        </div>
                    </div>
                    <div class="floating-pill">
                        De tu potencial a tu logro ✨
                    </div>
                </div>
            </div>
        </section>

        {{-- ══ SEARCH & FILTERS CARD ══ --}}
        <section class="search-card">
            <div class="search-fields-grid">
                <div>
                    <label class="field-label" for="search-input">¿Qué oportunidad buscas?</label>
                    <input type="text" id="search-input" class="field-input" placeholder="Puesto, empresa o palabra clave" autocomplete="off" onkeydown="if(event.key==='Enter')triggerSearch()">
                </div>
                <div>
                    <label class="field-label" for="location-select">Ubicación</label>
                    <select id="location-select" class="field-select" onchange="triggerSearch()">
                        <option value="">Todo el Perú</option>
                        @foreach($availablePlaces as $place)
                            <option value="{{ $place }}">{{ $place }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="button" class="btn-search-main" onclick="triggerSearch()">
                <span>Buscar oportunidades</span>
                <span>➔</span>
            </button>

            <div class="filter-row">
                <div class="filter-selectors">
                    <div class="filter-item-wrap">
                        <label class="filter-item-label" for="category-select">Oportunidad</label>
                        <select id="category-select" class="sub-select" onchange="triggerSearch()">
                            <option value="">Todas las oportunidades</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-item-wrap">
                        <label class="filter-item-label" for="modality-select">Modalidad</label>
                        <select id="modality-select" class="sub-select" onchange="triggerSearch()">
                            <option value="">Todas las modalidades</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-item-wrap">
                        <label class="filter-item-label" for="sort-select">Ordenar por</label>
                        <select id="sort-select" class="sub-select" onchange="triggerSearch()">
                            <option value="recent">Más recientes</option>
                            <option value="salary_desc">Mayor salario</option>
                            <option value="salary_asc">Menor salario</option>
                            <option value="title_asc">A - Z</option>
                        </select>
                    </div>
                </div>

                <button type="button" class="btn-clear-filters" onclick="clearAllFilters()">
                    Limpiar filtros
                </button>
            </div>
        </section>

        {{-- ══ OPPORTUNITIES SECTION HEADER ══ --}}
        <section class="section-header-wrap" id="oportunidades">
            <span class="section-tagline">ENCUENTRA TU LUGAR</span>
            <div class="section-title-row">
                <h2 class="section-title">
                    Oportunidades para ti
                    <span class="offers-count-pill" id="grid-offers-count">{{ $totalActiveOffers }}</span>
                </h2>
                <span class="section-subtitle">
                    El siguiente paso en tu carrera está más cerca.
                </span>
            </div>
        </section>

        {{-- ══ 2-COLUMN JOB CARDS GRID ══ --}}
        <section class="job-grid" id="job-list">
            @for($i = 0; $i < 6; $i++)
            <div class="job-card" style="min-height: 240px;">
                <div class="card-header">
                    <div class="card-header-left">
                        <div class="skel-box" style="width:36px;height:36px;border-radius:10px;"></div>
                        <div class="skel-box" style="width:110px;height:22px;border-radius:50px;"></div>
                    </div>
                    <div class="skel-box" style="width:24px;height:24px;border-radius:50%;"></div>
                </div>
                <div class="skel-box" style="width:40%;height:14px;margin-bottom:8px;"></div>
                <div class="skel-box" style="width:80%;height:20px;margin-bottom:12px;"></div>
                <div class="skel-box" style="width:60%;height:14px;margin-bottom:16px;"></div>
                <div class="skel-box" style="width:100%;height:32px;margin-bottom:16px;"></div>
                <div class="skel-box" style="width:35%;height:18px;"></div>
            </div>
            @endfor
        </section>

        {{-- Load More / Empty State Container --}}
        <div id="grid-empty-msg" class="hidden" style="text-align:center;padding:50px 20px;background:#fff;border:1px solid var(--bor);border-radius:20px;margin-bottom:32px;">
            <span class="material-symbols-outlined" style="font-size:48px;color:#9ca3af;margin-bottom:12px;">search_off</span>
            <h3 style="font-size:17px;font-weight:700;color:#111827;margin-bottom:6px;">No se encontraron oportunidades</h3>
            <p style="font-size:14px;color:#6b7280;">Prueba ajustando los términos de búsqueda o limpiando los filtros seleccionados.</p>
        </div>

        <div style="text-align:center;margin-bottom:40px;">
            <button id="load-more-btn" type="button" class="btn-empresa-nav hidden" style="padding:12px 28px;font-size:14px;cursor:pointer;" onclick="loadMoreOffers()">
                Cargar más oportunidades
            </button>
        </div>

        {{-- ══ CALL TO ACTION: EMPRESAS ══ --}}
        <section class="cta-company-card">
            <div class="cta-company-left">
                <span class="cta-company-tagline">EMPRESAS QUE IMPULSAN EL FUTURO</span>
                <h3 class="cta-company-title">El talento de mañana puede estar en tu equipo.</h3>
                <p class="cta-company-desc">Publica oportunidades y conecta con estudiantes y egresados de nuestra institución.</p>
            </div>
            <a href="{{ route('login') }}#empresa" class="btn-cta-company">
                <span>Registrar mi empresa</span>
                <span>↗</span>
            </a>
        </section>

    </main>

    {{-- ══ FOOTER ══ --}}
    <footer class="footer">
        <div class="page-container">
            <div class="footer-inner">
                <span class="footer-brand">
                    Creado por <strong>{{ $config['application_name'] ?? 'LOOPZ' }}</strong> • v2.0.0
                </span>
                <a href="{{ route('login') }}" class="footer-link">
                    Contactar a la institución
                </a>
            </div>
        </div>
    </footer>

    {{-- ══ MODAL DE DETALLE DE OPORTUNIDAD ══ --}}
    <div id="detail-modal" class="modal-overlay" onclick="if(event.target===this)closeDetailModal()">
        <div class="modal-box" style="max-width:680px;">
            <button type="button" class="modal-close-btn" onclick="closeDetailModal()">
                <span class="material-symbols-outlined">close</span>
            </button>

            <div style="display:flex;align-items:flex-start;gap:16px;margin-bottom:18px;">
                <div id="m-logo" class="card-avatar" style="width:48px;height:48px;border-radius:12px;font-size:20px;"></div>
                <div>
                    <h2 id="m-title" style="font-family:'Plus Jakarta Sans',sans-serif;font-size:22px;font-weight:800;color:#111827;line-height:1.25;">—</h2>
                    <p id="m-company" style="font-size:13px;font-weight:600;color:#6b7280;margin-top:2px;">—</p>
                    <div style="display:flex;align-items:center;gap:12px;font-size:12px;color:#6b7280;margin-top:6px;">
                        <span id="m-location" style="display:inline-flex;align-items:center;gap:4px;"><span class="material-symbols-outlined" style="font-size:15px;">location_on</span>—</span>
                        <span id="m-modality" style="display:inline-flex;align-items:center;gap:4px;"><span class="material-symbols-outlined" style="font-size:15px;">domain</span>—</span>
                    </div>
                </div>
            </div>

            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:20px;">
                <span id="m-badge-type" class="card-type-badge badge-practica">Prácticas</span>
                <span id="m-badge-schedule" class="card-type-badge" style="background:#f1f5f9;color:#334155;">Tiempo Completo</span>
                <span id="m-badge-category" class="card-type-badge" style="background:#e0f2fe;color:#0369a1;">Categoría</span>
            </div>

            <div style="background:#f8fafc;border:1px solid var(--bor);border-radius:14px;padding:14px 18px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <span style="font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;">Remuneración</span>
                    <p id="m-salary" style="font-family:'Plus Jakarta Sans',sans-serif;font-size:18px;font-weight:800;color:#111827;">—</p>
                </div>
                <div style="text-align:right;">
                    <span style="font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;">Fecha límite</span>
                    <p id="m-deadline" style="font-size:13px;font-weight:600;color:#4b5563;">—</p>
                </div>
            </div>

            <div style="margin-bottom:20px;">
                <h4 style="font-size:13px;font-weight:700;text-transform:uppercase;color:#111827;letter-spacing:0.04em;margin-bottom:8px;">Descripción del puesto</h4>
                <div id="m-desc" style="font-size:14px;line-height:1.65;color:#4b5563;white-space:pre-line;"></div>
            </div>

            <div id="m-req-section" style="margin-bottom:20px;">
                <h4 style="font-size:13px;font-weight:700;text-transform:uppercase;color:#111827;letter-spacing:0.04em;margin-bottom:8px;">Requisitos</h4>
                <div id="m-req" style="font-size:14px;line-height:1.65;color:#4b5563;white-space:pre-line;"></div>
            </div>

            <div id="m-ben-section" style="margin-bottom:24px;display:none;">
                <h4 style="font-size:13px;font-weight:700;text-transform:uppercase;color:#111827;letter-spacing:0.04em;margin-bottom:8px;">Beneficios</h4>
                <div id="m-ben" style="font-size:14px;line-height:1.65;color:#4b5563;white-space:pre-line;"></div>
            </div>

            <div style="display:flex;align-items:center;gap:12px;border-top:1px solid var(--bor);padding-top:20px;">
                <button type="button" id="m-btn-apply" class="modal-btn-pri" style="flex:1;" onclick="handleModalApplyClick()">
                    <span class="material-symbols-outlined" style="font-size:18px;">send</span>
                    <span>Postularme ahora</span>
                </button>
                <button type="button" id="m-btn-save" class="modal-btn-sec" onclick="handleModalSaveClick()" title="Guardar oferta">
                    <span class="material-symbols-outlined" id="m-save-icon">bookmark_border</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ══ MODAL POSTULAR (AUTH VS GUEST) ══ --}}
    <div id="postular-modal" class="modal-overlay" onclick="if(event.target===this)closePostularModal()">
        <div class="modal-box" style="max-width:440px;text-align:center;">
            <button type="button" class="modal-close-btn" onclick="closePostularModal()">
                <span class="material-symbols-outlined">close</span>
            </button>

            @if($authUser)
                {{-- Formulario de postulación autenticado --}}
                <div style="width:56px;height:56px;border-radius:18px;background:var(--pri-tint);color:var(--pri);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <span class="material-symbols-outlined filled" style="font-size:28px;">send</span>
                </div>
                <h2 class="modal-title" id="modal-offer-title" style="font-family:'Plus Jakarta Sans',sans-serif;font-size:20px;font-weight:800;margin-bottom:6px;">Postular a vacante</h2>
                <p class="modal-sub" style="font-size:13px;color:#6b7280;margin-bottom:18px;">Selecciona tu CV registrado para enviar tu postulación.</p>

                <div id="postular-alert" style="display:none;padding:10px 14px;border-radius:10px;font-size:13px;font-weight:600;margin-bottom:14px;text-align:left;"></div>

                <div id="apply-form-content">
                    @if($studentCvs->count())
                    <div style="text-align:left;margin-bottom:14px;">
                        <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;">Selecciona tu CV</label>
                        <select id="select-cv-id" class="field-select" style="height:44px;font-size:13px;">
                            @foreach($studentCvs as $cv)
                            <option value="{{ $cv->id }}">v{{ $cv->version }} — {{ $cv->filename }} ({{ $cv->uploaded_at }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="text-align:left;margin-bottom:18px;">
                        <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;">Mensaje de presentación (opcional)</label>
                        <textarea id="apply-message" class="field-input" rows="3" placeholder="Preséntate brevemente a la empresa..." style="height:auto;padding:10px;resize:vertical;"></textarea>
                    </div>
                    <div class="modal-actions">
                        <button id="btn-submit-apply" type="button" class="modal-btn-pri" onclick="submitApply()">
                            <span class="material-symbols-outlined" style="font-size:18px;">send</span>
                            Enviar postulación
                        </button>
                        <button type="button" class="modal-btn-sec" onclick="closePostularModal()">Cancelar</button>
                    </div>
                    @else
                    <div style="background:#fef2f2;border:1px solid #fee2e2;border-radius:12px;padding:14px;margin-bottom:18px;text-align:center;">
                        <p style="font-size:13px;font-weight:600;color:#dc2626;">Necesitas subir al menos un CV antes de postular.</p>
                    </div>
                    <div class="modal-actions">
                        <button type="button" class="modal-btn-pri" onclick="closePostularModal();openModal('modal-cvs')">
                            <span class="material-symbols-outlined" style="font-size:18px;">upload_file</span>
                            Subir mi CV ahora
                        </button>
                        <button type="button" class="modal-btn-sec" onclick="closePostularModal()">Cancelar</button>
                    </div>
                    @endif
                </div>
            @else
                {{-- No autenticado: modal para iniciar sesión --}}
                <div style="width:64px;height:64px;border-radius:20px;background:var(--pri-tint);color:var(--pri);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <span class="material-symbols-outlined filled" style="font-size:34px;">lock_person</span>
                </div>
                <h2 style="font-family:'Plus Jakarta Sans',sans-serif;font-size:22px;font-weight:800;color:#111827;margin-bottom:8px;">Inicia sesión para postular</h2>
                <p id="guest-modal-sub" style="font-size:14px;color:#4b5563;line-height:1.55;margin-bottom:24px;">
                    Para postular a <strong id="guest-modal-offer-title">esta vacante</strong>, necesitas acceder con tu cuenta de estudiante o egresado.
                </p>
                <div class="modal-actions">
                    <a id="btn-guest-login" href="{{ route('login') }}" class="modal-btn-pri">
                        <span class="material-symbols-outlined" style="font-size:18px;">login</span>
                        Iniciar Sesión
                    </a>
                    <a id="btn-guest-register-grad" href="{{ route('register.graduate') }}" class="modal-btn-sec">
                        <span class="material-symbols-outlined" style="font-size:18px;color:var(--pri);">school</span>
                        Registrarme como Egresado
                    </a>
                    <button type="button" class="modal-btn-sec" onclick="closePostularModal()" style="border:none;color:#9ca3af;font-size:13px;">
                        Seguir explorando
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- Toast Notification --}}
    <div id="toast-notify" class="toast-notify">
        <span class="material-symbols-outlined" id="toast-icon">check_circle</span>
        <span id="toast-text">Mensaje</span>
    </div>

<script>
var currentPage = 1;
var isLoading = false;
var loadedOffers = [];
var currentSelectedOffer = null;
var appliedOffers = new Set(@json($studentApplicationIds ?? []));
var savedOffers = new Set(@json($studentSavedOfferIds ?? []));
var isAuth = {{ $authUser ? 'true' : 'false' }};

// Paletas aleatorias armoniosas para avatares
var avatarPalettes = [
    { bg: '#eff6ff', color: '#1d4ed8' },
    { bg: '#f5f3ff', color: '#6d28d9' },
    { bg: '#ecfdf5', color: '#047857' },
    { bg: '#fff7ed', color: '#c2410c' },
    { bg: '#fdf2f8', color: '#be185d' },
    { bg: '#f0fdfa', color: '#0f766e' }
];

function getAvatarBg(str) {
    var code = (str || 'A').charCodeAt(0);
    return avatarPalettes[code % avatarPalettes.length].bg;
}
function getAvatarColor(str) {
    var code = (str || 'A').charCodeAt(0);
    return avatarPalettes[code % avatarPalettes.length].color;
}

function esc(str) {
    if (!str) return '';
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function stripHtml(html) {
    var tmp = document.createElement("DIV");
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || "";
}

// ── Cargar Ofertas ─────────────────────────────────────────────────────────────
function loadOffers(page, reset) {
    if (isLoading) return;
    isLoading = true;

    var list = document.getElementById('job-list');
    var emptyMsg = document.getElementById('grid-empty-msg');
    var loadBtn = document.getElementById('load-more-btn');

    if (reset) {
        currentPage = 1;
        list.innerHTML = renderSkeletons(6);
        emptyMsg.classList.add('hidden');
        loadBtn.classList.add('hidden');
    }

    var q = document.getElementById('search-input') ? document.getElementById('search-input').value.trim() : '';
    var loc = document.getElementById('location-select') ? document.getElementById('location-select').value : '';
    var cat = document.getElementById('category-select') ? document.getElementById('category-select').value : '';
    var mod = document.getElementById('modality-select') ? document.getElementById('modality-select').value : '';
    var sort = document.getElementById('sort-select') ? document.getElementById('sort-select').value : 'recent';

    var params = new URLSearchParams();
    if (q) params.set('search', q);
    if (loc) params.set('province', loc);
    if (cat) params.set('category_id', cat);
    if (mod) params.set('modality_id', mod);
    if (sort) params.set('sort_by', sort);
    params.set('page', page);
    params.set('per_page', 8);

    fetch('/buscar-ofertas?' + params.toString() + '&_=' + Date.now(), {
        headers: { 'Accept': 'application/json' }
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (!data.success) throw new Error(data.message);

        if (reset) {
            list.innerHTML = '';
            loadedOffers = [];
        }

        var countBadge = document.getElementById('grid-offers-count');
        var heroCount = document.getElementById('hero-count-number');
        if (countBadge) countBadge.textContent = data.total;
        if (heroCount) heroCount.textContent = data.total;

        if (data.total === 0 && reset) {
            emptyMsg.classList.remove('hidden');
            loadBtn.classList.add('hidden');
        } else {
            data.offers.forEach(function(o) {
                list.insertAdjacentHTML('beforeend', renderCard(o));
                loadedOffers.push(o);
            });

            if (data.has_more) {
                loadBtn.classList.remove('hidden');
            } else {
                loadBtn.classList.add('hidden');
            }
        }
    })
    .catch(function(err) {
        console.error('[loadOffers] Error:', err);
        if (reset) {
            list.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:40px;color:#ef4444;">Error al cargar las oportunidades. Por favor intenta de nuevo.</div>';
        }
    })
    .finally(function() {
        isLoading = false;
    });
}

function loadMoreOffers() {
    currentPage++;
    loadOffers(currentPage, false);
}

function triggerSearch() {
    loadOffers(1, true);
}

function clearAllFilters() {
    if (document.getElementById('search-input')) document.getElementById('search-input').value = '';
    if (document.getElementById('location-select')) document.getElementById('location-select').value = '';
    if (document.getElementById('category-select')) document.getElementById('category-select').value = '';
    if (document.getElementById('modality-select')) document.getElementById('modality-select').value = '';
    if (document.getElementById('sort-select')) document.getElementById('sort-select').value = 'recent';
    loadOffers(1, true);
}

// ── Render Card ─────────────────────────────────────────────────────────────
function renderCard(o) {
    var co = o.company || {};
    var name = co.name || 'Empresa';
    var logo = co.logo;
    var initial = name.charAt(0).toUpperCase();
    var isSaved = savedOffers.has(o.id);
    var isApplied = appliedOffers.has(o.id);

    // Tipo / Badge
    var typeBadge = 'Empleo';
    var badgeClass = 'badge-empleo';
    var catName = (o.category ? o.category.name : '').toLowerCase();
    var titleLower = (o.title || '').toLowerCase();
    if (titleLower.includes('practicante') || titleLower.includes('práctica') || catName.includes('práctica')) {
        typeBadge = 'Prácticas profesionales';
        badgeClass = 'badge-practica';
    } else if (o.contract_type && o.contract_type.name) {
        typeBadge = o.contract_type.name;
    }

    var modalityName = o.modality ? o.modality.name : 'Presencial';
    var locationStr = (o.province ? o.province + ', ' : '') + (o.department || 'Perú');

    // Salario
    var salaryStr = 'A tratar';
    if (!o.hide_salary && o.salary) {
        salaryStr = 'S/ ' + Number(o.salary).toLocaleString('es-PE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    // Cierre
    var closeStr = 'Convocatoria abierta';
    if (o.deadline) {
        var d = new Date(o.deadline);
        var months = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Set', 'Oct', 'Nov', 'Dic'];
        closeStr = 'Cierra ' + String(d.getDate()).padStart(2, '0') + ' ' + (months[d.getMonth()] || '');
    }

    // Avatar
    var avatarHtml = '';
    if (logo) {
        avatarHtml = '<img src="' + esc(logo) + '" alt="' + esc(name) + '" class="card-logo-img" onerror="this.onerror=null;this.parentNode.innerHTML=\'<span class=\\\'card-avatar-text\\\'>' + initial + '</span>\';">';
    } else {
        avatarHtml = '<span class="card-avatar-text">' + initial + '</span>';
    }

    return '<div class="job-card ' + (isApplied ? 'card-applied' : '') + '" onclick="openOpportunityDetail(' + o.id + ')" data-id="' + o.id + '">' +
        '<div class="card-header">' +
            '<div class="card-header-left">' +
                '<div class="card-avatar" style="background:' + getAvatarBg(initial) + ';color:' + getAvatarColor(initial) + ';">' + avatarHtml + '</div>' +
                '<span class="card-type-badge ' + badgeClass + '">' + esc(typeBadge) + '</span>' +
            '</div>' +
            '<button type="button" class="btn-card-star ' + (isSaved ? 'active' : '') + '" onclick="handleStarClick(event, ' + o.id + ')" title="' + (isSaved ? 'Quitar de guardadas' : 'Guardar oportunidad') + '">' +
                '<span class="material-symbols-outlined ' + (isSaved ? 'filled' : '') + '">' + (isSaved ? 'bookmark' : 'bookmark_border') + '</span>' +
            '</button>' +
        '</div>' +
        '<div class="card-body">' +
            '<div class="card-company-name">' + esc(name) + '</div>' +
            '<h3 class="card-job-title">' + esc(o.title) + '</h3>' +
            '<div class="card-meta-row">' +
                '<span class="card-meta-item"><span class="material-symbols-outlined">location_on</span>' + esc(locationStr) + '</span>' +
                '<span class="card-meta-item"><span class="material-symbols-outlined">domain</span>' + esc(modalityName) + '</span>' +
            '</div>' +
            '<p class="card-desc">' + esc(stripHtml(o.description || '')) + '</p>' +
            '<div class="card-salary">' + esc(salaryStr) + ' <span class="card-salary-sub">/ mes</span></div>' +
        '</div>' +
        '<div class="card-footer">' +
            '<span class="card-close-date">' + esc(closeStr) + '</span>' +
            '<span class="card-link-action">Ver oportunidad ↗</span>' +
        '</div>' +
    '</div>';
}

function renderSkeletons(count) {
    var h = '';
    for(var i=0; i<count; i++) {
        h += '<div class="job-card" style="min-height: 240px;">' +
            '<div class="card-header">' +
                '<div class="card-header-left">' +
                    '<div class="skel-box" style="width:36px;height:36px;border-radius:10px;"></div>' +
                    '<div class="skel-box" style="width:110px;height:22px;border-radius:50px;"></div>' +
                '</div>' +
                '<div class="skel-box" style="width:24px;height:24px;border-radius:50%;"></div>' +
            '</div>' +
            '<div class="skel-box" style="width:40%;height:14px;margin-bottom:8px;"></div>' +
            '<div class="skel-box" style="width:80%;height:20px;margin-bottom:12px;"></div>' +
            '<div class="skel-box" style="width:60%;height:14px;margin-bottom:16px;"></div>' +
            '<div class="skel-box" style="width:100%;height:32px;margin-bottom:16px;"></div>' +
            '<div class="skel-box" style="width:35%;height:18px;"></div>' +
        '</div>';
    }
    return h;
}

// ── Detalle de Oportunidad (Modal) ───────────────────────────────────────────
function openOpportunityDetail(id) {
    var o = loadedOffers.find(function(item) { return item.id === id; });
    if (!o) return;
    currentSelectedOffer = o;

    var co = o.company || {};
    var name = co.name || 'Empresa';
    var initial = name.charAt(0).toUpperCase();

    // Fill Modal
    document.getElementById('m-title').textContent = o.title;
    document.getElementById('m-company').textContent = name;
    document.getElementById('m-location').innerHTML = '<span class="material-symbols-outlined" style="font-size:15px;">location_on</span>' + esc((o.province ? o.province + ', ' : '') + (o.department || 'Perú'));
    document.getElementById('m-modality').innerHTML = '<span class="material-symbols-outlined" style="font-size:15px;">domain</span>' + esc(o.modality ? o.modality.name : 'Presencial');

    var mLogo = document.getElementById('m-logo');
    mLogo.style.background = getAvatarBg(initial);
    mLogo.style.color = getAvatarColor(initial);
    if (co.logo) {
        mLogo.innerHTML = '<img src="' + esc(co.logo) + '" alt="' + esc(name) + '" style="width:100%;height:100%;object-fit:cover;">';
    } else {
        mLogo.innerHTML = initial;
    }

    // Badges
    document.getElementById('m-badge-schedule').textContent = o.work_schedule ? o.work_schedule.name : 'Jornada regular';
    document.getElementById('m-badge-category').textContent = o.category ? o.category.name : 'General';

    // Salario
    var salaryStr = 'A convenir';
    if (!o.hide_salary && o.salary) {
        salaryStr = 'S/ ' + Number(o.salary).toLocaleString('es-PE', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' / mes';
    }
    document.getElementById('m-salary').textContent = salaryStr;

    // Fecha
    document.getElementById('m-deadline').textContent = o.deadline ? o.deadline : 'Sin fecha límite';

    // Textos
    document.getElementById('m-desc').textContent = o.description || 'Sin descripción detallada.';
    document.getElementById('m-req').textContent = o.requirements || 'No especificados.';
    if (o.benefits) {
        document.getElementById('m-ben-section').style.display = 'block';
        document.getElementById('m-ben').textContent = o.benefits;
    } else {
        document.getElementById('m-ben-section').style.display = 'none';
    }

    // Botón Guardar estado
    var isSaved = savedOffers.has(o.id);
    var saveIcon = document.getElementById('m-save-icon');
    if (saveIcon) {
        saveIcon.textContent = isSaved ? 'bookmark' : 'bookmark_border';
        saveIcon.className = 'material-symbols-outlined ' + (isSaved ? 'filled' : '');
    }

    // Botón postular estado
    var applyBtn = document.getElementById('m-btn-apply');
    if (appliedOffers.has(o.id)) {
        applyBtn.innerHTML = '<span class="material-symbols-outlined" style="font-size:18px;">check_circle</span> Ya has postulado';
        applyBtn.style.opacity = '0.7';
    } else {
        applyBtn.innerHTML = '<span class="material-symbols-outlined" style="font-size:18px;">send</span> Postularme ahora';
        applyBtn.style.opacity = '1';
    }

    document.getElementById('detail-modal').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeDetailModal() {
    document.getElementById('detail-modal').classList.remove('open');
    document.body.style.overflow = '';
}

// ── Postular Handler ────────────────────────────────────────────────────────
function handleModalApplyClick() {
    if (!currentSelectedOffer) return;
    closeDetailModal();
    openPostularModal(currentSelectedOffer);
}

function openPostularModal(offer) {
    var o = offer || currentSelectedOffer;
    if (!o) return;
    currentSelectedOffer = o;

    var modal = document.getElementById('postular-modal');
    if (!modal) return;

    if (!isAuth) {
        // Visitante
        var guestTitle = document.getElementById('guest-modal-offer-title');
        var guestLoginBtn = document.getElementById('btn-guest-login');
        var guestGradBtn = document.getElementById('btn-guest-register-grad');

        if (guestTitle) {
            guestTitle.textContent = o.title + (o.company ? ' en ' + o.company.name : '');
        }
        if (guestLoginBtn) {
            guestLoginBtn.href = '{{ route("login") }}?redirect_offer=' + encodeURIComponent(o.id) + '&apply=1';
        }
        if (guestGradBtn) {
            guestGradBtn.href = '{{ route("register.graduate") }}?redirect_offer=' + encodeURIComponent(o.id);
        }
    } else {
        var al = document.getElementById('postular-alert');
        if (al) { al.style.display = 'none'; al.textContent = ''; }
        var modalTitle = document.getElementById('modal-offer-title');
        if (modalTitle) modalTitle.textContent = 'Postular a ' + o.title;
    }

    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closePostularModal() {
    var modal = document.getElementById('postular-modal');
    if (modal) modal.classList.remove('open');
    document.body.style.overflow = '';
}

function submitApply() {
    if (!currentSelectedOffer) return;
    var cvSelect = document.getElementById('select-cv-id');
    var msg = document.getElementById('apply-message');
    var al = document.getElementById('postular-alert');

    if (!cvSelect) {
        if (al) { al.style.display = 'block'; al.style.background = '#fef2f2'; al.style.color = '#dc2626'; al.textContent = 'Selecciona un CV.'; }
        return;
    }
    var cvId = cvSelect.value;
    var message = msg ? msg.value : '';
    var btn = document.getElementById('btn-submit-apply');
    if (btn) btn.disabled = true;

    var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/student/apply/' + currentSelectedOffer.id, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({ cv_id: cvId, message: message })
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        if (d.success) {
            appliedOffers.add(currentSelectedOffer.id);
            closePostularModal();
            showToast('¡Postulación enviada exitosamente!');
            loadOffers(currentPage, true);
        } else {
            if (al) { al.style.display = 'block'; al.style.background = '#fef2f2'; al.style.color = '#dc2626'; al.textContent = d.message || 'Error al postular.'; }
        }
    })
    .catch(function(err) {
        if (al) { al.style.display = 'block'; al.style.background = '#fef2f2'; al.style.color = '#dc2626'; al.textContent = 'Error de conexión.'; }
    })
    .finally(function() {
        if (btn) btn.disabled = false;
    });
}

// ── Guardar Ofertas ─────────────────────────────────────────────────────────
function handleStarClick(e, offerId) {
    e.stopPropagation();
    if (!isAuth) {
        var o = loadedOffers.find(function(item) { return item.id === offerId; });
        openPostularModal(o);
        return;
    }

    var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch('/student/save-offer/' + offerId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success) {
            if (data.saved) {
                savedOffers.add(offerId);
                showToast('Oferta guardada.');
            } else {
                savedOffers.delete(offerId);
                showToast('Oferta quitada de guardadas.');
            }
            // Update Card Icons
            var cards = document.querySelectorAll('.job-card[data-id="' + offerId + '"] .btn-card-star');
            cards.forEach(function(btn) {
                btn.classList.toggle('active', data.saved);
                var icon = btn.querySelector('.material-symbols-outlined');
                if (icon) {
                    icon.textContent = data.saved ? 'bookmark' : 'bookmark_border';
                    icon.className = 'material-symbols-outlined ' + (data.saved ? 'filled' : '');
                }
            });
        }
    })
    .catch(function() {
        showToast('Error de red al guardar oferta.');
    });
}

function handleModalSaveClick() {
    if (!currentSelectedOffer) return;
    handleStarClick(new Event('click'), currentSelectedOffer.id);
    var isSaved = savedOffers.has(currentSelectedOffer.id);
    var icon = document.getElementById('m-save-icon');
    if (icon) {
        icon.textContent = isSaved ? 'bookmark' : 'bookmark_border';
        icon.className = 'material-symbols-outlined ' + (isSaved ? 'filled' : '');
    }
}

// ── Toast ───────────────────────────────────────────────────────────────────
function showToast(msg) {
    var t = document.getElementById('toast-notify');
    var txt = document.getElementById('toast-text');
    if (t && txt) {
        txt.textContent = msg;
        t.style.display = 'flex';
        setTimeout(function() { t.style.display = 'none'; }, 3200);
    }
}

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

// ── Init on DOMContentLoaded ────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
    loadOffers(1, true);

    @if(request()->has('offer'))
        setTimeout(function() {
            var offerId = {{ (int) request('offer') }};
            if (offerId) {
                var o = loadedOffers.find(function(item) { return item.id === offerId; });
                if (o) {
                    openOpportunityDetail(o.id);
                    @if(request()->has('apply'))
                        setTimeout(function() { handleModalApplyClick(); }, 400);
                    @endif
                }
            }
        }, 800);
    @endif
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDetailModal();
        closePostularModal();
    }
});
</script>

</body>
</html>
