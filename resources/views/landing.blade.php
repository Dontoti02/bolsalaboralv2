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
            background: #ffffff;
            border-bottom: 1.5px solid rgba(1, 20, 60, 0.08);
            box-shadow: 0 4px 20px -2px rgba(1, 20, 60, 0.04);
            position: sticky;
            top: 0;
            z-index: 100;
            transition: all 0.25s ease;
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
        .brand:hover {
            opacity: 0.95;
        }
        .brand-logo-box {
            width: 54px;
            height: 54px;
            border-radius: 14px;
            background: #ffffff;
            border: 1.5px solid rgba(1, 20, 60, 0.10);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            font-weight: 800;
            overflow: hidden;
            flex-shrink: 0;
            box-shadow: 0 4px 14px rgba(1, 20, 60, 0.07);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .brand:hover .brand-logo-box {
            transform: translateY(-1px) scale(1.02);
            box-shadow: 0 6px 18px rgba(1, 20, 60, 0.12);
            border-color: rgba(1, 20, 60, 0.22);
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
            transition: color 0.15s ease;
        }
        .brand:hover .brand-title {
            color: #1e3a8a;
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
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
        }
        .nav-link:hover {
            color: #01143c;
            background: rgba(1, 20, 60, 0.05);
        }
        .nav-link-institution {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #1e3a8a;
            background: rgba(30, 58, 138, 0.05);
            border: 1.5px solid rgba(30, 58, 138, 0.15);
            font-size: 14.5px;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 50px;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
        }
        .nav-link-institution:hover {
            color: #ffffff;
            background: #01143c;
            border-color: #01143c;
            box-shadow: 0 2px 8px rgba(1, 20, 60, 0.15);
        }
        @media (max-width: 640px) {
            .nav-link-institution {
                display: none;
            }
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
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
            letter-spacing: -0.01em;
            box-shadow: 0 1px 3px rgba(1, 20, 60, 0.05);
        }
        .btn-login-nav:hover {
            border-color: #01143c;
            color: #01143c;
            background: rgba(1, 20, 60, 0.05);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(1, 20, 60, 0.09);
        }
        .btn-empresa-nav {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 11px 24px;
            border: 1.5px solid transparent;
            border-radius: 50px;
            font-size: 14.5px;
            font-weight: 700;
            color: #ffffff;
            background: linear-gradient(135deg, #01143c 0%, #1e3a8a 100%);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
            letter-spacing: -0.01em;
            box-shadow: 0 4px 14px rgba(1, 20, 60, 0.22);
        }
        .btn-empresa-nav:hover {
            color: #ffffff;
            background: linear-gradient(135deg, #000a20 0%, #172554 100%);
            transform: translateY(-1.5px);
            box-shadow: 0 6px 20px rgba(1, 20, 60, 0.32);
        }
        .btn-empresa-nav .btn-arrow-icon {
            font-size: 15px;
            font-weight: 800;
            line-height: 1;
            transition: transform 0.2s ease;
        }
        .btn-empresa-nav:hover .btn-arrow-icon {
            transform: translate(2px, -2px);
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
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 1px 3px rgba(1, 20, 60, 0.04);
        }
        .profile-trigger:hover {
            border-color: #01143c;
            background: rgba(1, 20, 60, 0.02);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(1, 20, 60, 0.08);
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
            flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(1, 20, 60, 0.15);
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

        /* ── HERO BANNER (COMPUTRABAJO / BUMERAN STYLE) ── */
        .hero-computrabajo-section {
            background: radial-gradient(circle at 10% 20%, rgba(224, 231, 255, 0.75) 0%, rgba(245, 243, 255, 0.55) 35%, rgba(238, 242, 255, 0.35) 70%, #ffffff 100%);
            border: 1px solid rgba(226, 232, 240, 0.85);
            border-radius: 32px;
            padding: clamp(32px, 3.8vw, 56px) clamp(24px, 4.5vw, 68px);
            margin-top: 24px;
            margin-bottom: 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: clamp(28px, 4vw, 56px);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 28px rgba(30, 27, 75, 0.03);
        }

        /* Subtle background glow effect */
        .hero-computrabajo-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -20%;
            width: 70%;
            height: 180%;
            background: radial-gradient(circle, rgba(199, 210, 254, 0.35) 0%, rgba(255, 255, 255, 0) 70%);
            pointer-events: none;
            z-index: 1;
        }

        /* Left Hero Content */
        .hero-content-col {
            flex: 1 1 500px;
            max-width: 620px;
            position: relative;
            z-index: 2;
        }
        .hero-ct-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: clamp(30px, 3vw, 44px);
            font-weight: 700;
            line-height: 1.18;
            color: #1e1b4b;
            letter-spacing: -0.03em;
            margin-bottom: 28px;
        }
        .hero-ct-title strong {
            font-weight: 900;
            color: #0f172a;
        }

        /* Hero Search Box (Stacked) */
        .hero-search-box {
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-width: 480px;
        }
        .hero-input-group {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
        }
        .hero-input-icon {
            position: absolute;
            left: 16px;
            font-size: 20px;
            color: #2563eb;
            pointer-events: none;
            z-index: 2;
        }
        .hero-text-input,
        .hero-select-input {
            width: 100%;
            height: 50px;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 0 16px 0 46px;
            font-size: 14px;
            font-family: inherit;
            color: #1e293b;
            outline: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
            transition: all 0.15s ease;
        }
        .hero-text-input::placeholder {
            color: #64748b;
        }
        .hero-text-input:focus,
        .hero-select-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }
        .hero-select-input {
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
        }
        .hero-select-arrow {
            position: absolute;
            right: 14px;
            font-size: 20px;
            color: #64748b;
            pointer-events: none;
            z-index: 2;
        }
        .btn-hero-search {
            width: 100%;
            height: 50px;
            background: #2563eb;
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
            transition: all 0.2s ease;
        }
        .btn-hero-search:hover {
            background: #1d4ed8;
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
            transform: translateY(-1px);
        }

        /* Right Hero Arch Visual */
        .hero-arch-col {
            flex: 0 0 auto;
            width: clamp(290px, 30vw, 420px);
            position: relative;
            z-index: 2;
        }
        .hero-arch-frame {
            width: 100%;
            height: clamp(390px, 34vw, 460px);
            border-radius: 200px 200px 24px 24px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 45px rgba(30, 27, 75, 0.18);
            background: #1e1b4b;
        }
        .hero-arch-slides-container {
            position: relative;
            width: 100%;
            height: 100%;
        }
        .hero-arch-slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.7s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .hero-arch-slide.active {
            opacity: 1;
            visibility: visible;
        }
        .arch-carousel-dots {
            position: absolute;
            top: 18px;
            right: 20px;
            display: flex;
            align-items: center;
            gap: 6px;
            z-index: 10;
        }
        .arch-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.45);
            border: none;
            padding: 0;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .arch-dot.active {
            background: #ffffff;
            width: 22px;
            border-radius: 10px;
        }
        .hero-arch-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center 15%;
            display: block;
        }
        .hero-arch-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(30, 27, 75, 0) 30%, rgba(37, 24, 88, 0.65) 60%, rgba(20, 12, 54, 0.95) 95%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 24px 22px;
        }
        .hero-arch-question {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: clamp(20px, 1.8vw, 25px);
            font-weight: 800;
            color: #ffffff;
            line-height: 1.25;
            margin-bottom: 14px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            letter-spacing: -0.02em;
        }
        .companies-empty-message {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 24px 16px;
            color: #64748b;
            font-size: 14px;
            font-weight: 500;
            text-align: center;
            width: 100%;
        }
        .arch-company-pill {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 12px;
            padding: 10px 14px;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            width: fit-content;
            max-width: 100%;
        }
        .arch-pill-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 28px;
        }
        .arch-pill-logo-img {
            max-height: 32px;
            max-width: 95px;
            object-fit: contain;
            filter: brightness(0) invert(1);
        }
        .arch-pill-logo-badge {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .arch-pill-text {
            display: flex;
            flex-direction: column;
            font-size: 11px;
            font-weight: 800;
            line-height: 1.15;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            border-left: 1px solid rgba(255, 255, 255, 0.3);
            padding-left: 10px;
        }
        .arch-btn-more {
            align-self: flex-end;
            background: #ffffff;
            color: #2563eb;
            font-weight: 700;
            font-size: 13px;
            padding: 8px 18px;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.18);
            transition: all 0.2s ease;
        }
        .arch-btn-more:hover {
            background: #f8fafc;
            transform: translateX(3px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.25);
        }

        /* ── COMPANIES CAROUSEL SECTION ── */
        .companies-section {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 24px;
            padding: 32px 24px;
            margin-bottom: 32px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 14px rgba(0, 0, 0, 0.02);
        }
        .companies-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(#e2e8f0 0.75px, transparent 0.75px);
            background-size: 18px 18px;
            opacity: 0.45;
            pointer-events: none;
        }
        .companies-heading {
            text-align: center;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: clamp(20px, 2.2vw, 28px);
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 24px;
            position: relative;
            z-index: 2;
            letter-spacing: -0.02em;
        }
        .companies-carousel-row {
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 2;
        }
        .carousel-arrow-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1.5px solid #e2e8f0;
            background: #ffffff;
            color: #1e293b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            cursor: pointer;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            transition: all 0.2s ease;
        }
        .carousel-arrow-btn:hover {
            border-color: #2563eb;
            color: #2563eb;
            background: #eff6ff;
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
        }
        .companies-slider-track {
            display: flex;
            align-items: center;
            gap: 16px;
            overflow-x: auto;
            scroll-behavior: smooth;
            scrollbar-width: none;
            -ms-overflow-style: none;
            padding: 8px 4px;
            flex: 1;
        }
        .companies-slider-track::-webkit-scrollbar {
            display: none;
        }
        .company-card-item {
            flex: 0 0 170px;
            width: 170px;
            height: 84px;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.03);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px 14px;
            cursor: pointer;
            transition: all 0.22s ease;
            user-select: none;
        }
        .company-card-item:hover {
            transform: translateY(-4px);
            border-color: #3b82f6;
            box-shadow: 0 10px 24px rgba(37, 99, 235, 0.12);
        }
        .company-logo-svg {
            max-width: 100%;
            max-height: 48px;
            object-fit: contain;
        }
        .company-card-fallback {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            justify-content: center;
        }
        .company-fallback-avatar {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #2563eb;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 13px;
            flex-shrink: 0;
        }
        .company-fallback-name {
            font-size: 12px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.2;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 105px;
        }
        .companies-action-center {
            display: flex;
            justify-content: center;
            margin-top: 22px;
            position: relative;
            z-index: 2;
        }
        .btn-view-all-companies {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 22px;
            border: 1.5px solid #2563eb;
            background: #ffffff;
            color: #2563eb;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.18s ease;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.05);
        }
        .btn-view-all-companies:hover {
            background: #eff6ff;
            border-color: #1d4ed8;
            color: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.12);
        }

        /* ── SECONDARY FILTERS BAR (Directly above Opportunities) ── */
        .secondary-filters-bar {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
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
            flex: 1 1 170px;
            min-width: 160px;
        }
        .filter-item-label {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .sub-select {
            width: 100%;
            height: 40px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 0 12px;
            font-size: 13px;
            font-family: inherit;
            color: #334155;
            background: #ffffff;
            cursor: pointer;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .sub-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        .btn-clear-filters {
            background: transparent;
            border: none;
            color: #64748b;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 8px 12px;
            transition: color 0.15s;
            margin-bottom: 2px;
            white-space: nowrap;
        }
        .btn-clear-filters:hover {
            color: #2563eb;
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
            .hero-computrabajo-section {
                flex-direction: column;
                padding: 32px 20px;
                text-align: center;
                gap: 32px;
            }
            .hero-content-col {
                max-width: 100%;
            }
            .hero-search-box {
                margin: 0 auto;
            }
            .hero-arch-col {
                width: 100%;
                max-width: 360px;
                margin: 0 auto;
            }
            .hero-arch-frame {
                height: 380px;
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
            .secondary-filters-bar {
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
                justify-content: center;
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
                height: 72px;
                gap: 12px;
            }
            .brand-logo-box {
                width: 44px;
                height: 44px;
                border-radius: 11px;
                font-size: 20px;
            }
            .brand-title {
                font-size: 15px;
                max-width: 140px;
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
            .btn-login-nav {
                padding: 8px 14px;
                font-size: 13px;
            }
            .btn-empresa-nav {
                padding: 8px 15px;
                font-size: 13px;
                gap: 5px;
            }
            .hero-ct-title {
                font-size: 24px;
                margin-bottom: 20px;
            }
            .hero-arch-frame {
                height: 340px;
                border-radius: 160px 160px 20px 20px;
            }
            .company-card-item {
                flex: 0 0 145px;
                width: 145px;
                height: 74px;
                padding: 8px 10px;
            }
            .carousel-arrow-btn {
                width: 34px;
                height: 34px;
                font-size: 18px;
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

                    @if(!empty($config['institution_url']))
                        <a href="{{ $config['institution_url'] }}" target="_blank" rel="noopener noreferrer" class="nav-link nav-link-institution" title="Visitar portal web oficial de {{ $config['application_name'] ?? 'la institución' }}">
                            <span class="material-symbols-outlined" style="font-size:18px;">language</span>
                            <span>Portal Web</span>
                            <span class="material-symbols-outlined" style="font-size:14px;opacity:0.75;">open_in_new</span>
                        </a>
                    @endif

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
                                <span class="material-symbols-outlined" style="font-size:16px;">expand_more</span>
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
                                <button type="button" class="profile-dropdown-item" onclick="openCvModal();closeProfileMenu()">
                                    <span class="material-symbols-outlined">upload_file</span>
                                    Gestionar / Subir CV
                                </button>
                                <a href="{{ route('student.applications') }}" class="profile-dropdown-item">
                                    <span class="material-symbols-outlined">assignment_turned_in</span>
                                    Mis postulaciones ({{ $studentApplications->count() }})
                                </a>
                                <a href="{{ route('student.saved.offers') }}" class="profile-dropdown-item">
                                    <span class="material-symbols-outlined">bookmark</span>
                                    Ofertas guardadas ({{ count($studentSavedOfferIds ?? []) }})
                                </a>
                                <button type="button" class="profile-dropdown-item" onclick="openPasswordModal();closeProfileMenu()">
                                    <span class="material-symbols-outlined">lock</span> Cambiar contraseña
                                </button>
                                @elseif($authUser->rol_id == 4)
                                <a href="{{ route('company.dashboard') }}" class="profile-dropdown-item">
                                    <span class="material-symbols-outlined">dashboard</span>
                                    Ir a mi Panel de Empresa
                                </a>
                                @elseif($authUser->rol_id == 1)
                                <a href="{{ route('admin.dashboard') }}" class="profile-dropdown-item">
                                    <span class="material-symbols-outlined">admin_panel_settings</span>
                                    Ir al Panel de Administración
                                </a>
                                @endif
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
                        <a href="{{ route('login') }}#empresa" class="btn-empresa-nav">
                            <span>Registrarme como Empresa</span>
                            <span class="btn-arrow-icon">↗</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="page-container">

        {{-- ══ HERO SECTION (COMPUTRABAJO / BUMERAN STYLE) ══ --}}
        <section class="hero-computrabajo-section">
            {{-- Left Content & Stacked Search --}}
            <div class="hero-content-col">
                <h1 class="hero-ct-title">
                    Hay <strong id="hero-count-number">{{ $totalActiveOffers > 0 ? number_format($totalActiveOffers) : '35,698' }}</strong> trabajos<br>
                    esperándote en {{ $config['institution_country'] ?? 'Aqui' }}
                </h1>

                <div class="hero-search-box">
                    {{-- Input 1: Keyword, Job, Company --}}
                    <div class="hero-input-group">
                        <span class="material-symbols-outlined hero-input-icon">search</span>
                        <input type="text" id="search-input" class="hero-text-input" placeholder="Puesto, empresa o palabra clave" autocomplete="off" onkeydown="if(event.key==='Enter')triggerSearch(true)">
                    </div>

                    {{-- Input 2: Location --}}
                    <div class="hero-input-group">
                        <span class="material-symbols-outlined hero-input-icon">location_on</span>
                        <select id="location-select" class="hero-select-input" onchange="triggerSearch(true)">
                            <option value="">Todo el país</option>
                            @php
                                $peruRegions = ['Lima', 'Arequipa', 'La Libertad', 'Cusco', 'Piura', 'Lambayeque', 'Junín', 'Callao', 'Ancash', 'Ica', 'San Martín', 'Loreto', 'Puno', 'Tacna'];
                                $allPlaces = collect($availablePlaces)->concat($peruRegions)->unique()->sort();
                            @endphp
                            @foreach($allPlaces as $place)
                                <option value="{{ $place }}">{{ $place }}</option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined hero-select-arrow">expand_more</span>
                    </div>

                    {{-- Button: Buscar Trabajo --}}
                    <button type="button" class="btn-hero-search" onclick="triggerSearch(true)">
                        <span>Buscar trabajo</span>
                    </button>
                </div>
            </div>

            {{-- Right Arch Visual Card (Dynamic Carousel with Uploaded Banners & Fallback) --}}
            <div class="hero-arch-col">
                <div class="hero-arch-frame" id="hero-arch-frame">
                    @if($bannerOffers->count() > 1)
                        <div class="arch-carousel-dots" id="arch-carousel-dots">
                            @foreach($bannerOffers as $idx => $bo)
                                <button type="button" class="arch-dot {{ $idx === 0 ? 'active' : '' }}" onclick="switchArchSlide({{ $idx }})" aria-label="Slide {{ $idx + 1 }}"></button>
                            @endforeach
                        </div>
                    @endif

                    <div class="hero-arch-slides-container" id="hero-arch-slides">
                        @forelse($bannerOffers as $index => $bOffer)
                            <div class="hero-arch-slide {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}">
                                <img src="{{ asset($bOffer->banner_image) }}" alt="{{ $bOffer->title }}" class="hero-arch-img" onerror="this.onerror=null;this.style.opacity='0';">
                                <div class="hero-arch-overlay">
                                    <div class="hero-arch-question">
                                        ¿Listo para tu próximo empleo?
                                    </div>
                                    <div class="arch-company-pill" onclick="window.location.href='/ofertas/{{ $bOffer->id }}'" style="cursor:pointer;" title="{{ $bOffer->title }}">
                                        <div class="arch-pill-brand">
                                            @if($bOffer->company && $bOffer->company->logo)
                                                <img src="{{ $bOffer->company->logo }}" alt="{{ $bOffer->company->name }}" class="arch-pill-logo-img">
                                            @else
                                                <div class="arch-pill-logo-badge" title="{{ $bOffer->company->name ?? 'Empresa' }}">
                                                    <span class="material-symbols-outlined" style="font-size:20px;color:#ffffff;">apartment</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="arch-pill-text">
                                            <span>TIENE</span>
                                            <strong>VACANTES</strong>
                                            <span>PARA TI</span>
                                        </div>
                                    </div>
                                    <a href="/ofertas/{{ $bOffer->id }}" class="arch-btn-more">
                                        <span>Conoce más</span>
                                        <span style="font-size:15px;line-height:1;">›</span>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="hero-arch-slide active" data-slide="0" style="background: radial-gradient(circle at 50% 28%, #312e81 0%, #1e1b4b 60%, #0f172a 100%); display: flex; flex-direction: column; justify-content: space-between; position: relative; overflow: hidden;">
                                <div style="position: absolute; top: -30px; left: -30px; width: 140px; height: 140px; border-radius: 50%; background: radial-gradient(circle, rgba(99, 102, 241, 0.28) 0%, transparent 70%); pointer-events: none;"></div>
                                <div style="position: absolute; top: 35%; right: -25px; width: 130px; height: 130px; border-radius: 50%; background: radial-gradient(circle, rgba(168, 85, 247, 0.22) 0%, transparent 70%); pointer-events: none;"></div>

                                <div style="position: relative; z-index: 1; padding: 42px 24px 0 24px; text-align: center;">
                                    <div style="width: 64px; height: 64px; margin: 0 auto 16px auto; border-radius: 20px; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(8px); border: 1px solid rgba(255, 255, 255, 0.2); display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);">
                                        <span class="material-symbols-outlined" style="font-size: 32px; color: #a5b4fc;">work</span>
                                    </div>
                                    <span style="display: inline-block; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #c7d2fe; background: rgba(99, 102, 241, 0.25); border: 1px solid rgba(165, 180, 252, 0.3); padding: 4px 12px; border-radius: 20px; margin-bottom: 10px;">
                                        Oportunidades Laborales
                                    </span>
                                    <h3 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 19px; font-weight: 800; color: #ffffff; line-height: 1.3; margin: 0;">
                                        {{ $config['application_name'] ?? 'Bolsa de Trabajo' }}
                                    </h3>
                                </div>

                                <div class="hero-arch-overlay" style="position: relative; background: linear-gradient(180deg, transparent 0%, rgba(15, 23, 42, 0.8) 40%, rgba(15, 23, 42, 0.98) 100%);">
                                    <div class="hero-arch-question">
                                        ¿Listo para tu próximo empleo?
                                    </div>
                                    @php
                                        $firstCompany = $companies->first();
                                    @endphp
                                    <div class="arch-company-pill" onclick="window.location.href='#oportunidades'" style="cursor:pointer;">
                                        <div class="arch-pill-brand">
                                            @if($firstCompany && $firstCompany->logo)
                                                <img src="{{ $firstCompany->logo }}" alt="{{ $firstCompany->name }}" class="arch-pill-logo-img">
                                            @else
                                                <div class="arch-pill-logo-badge">
                                                    <span class="material-symbols-outlined" style="font-size:20px;color:#ffffff;">apartment</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="arch-pill-text">
                                            <span>TIENE</span>
                                            <strong>VACANTES</strong>
                                            <span>PARA TI</span>
                                        </div>
                                    </div>
                                    <a href="#oportunidades" class="arch-btn-more">
                                        <span>Conoce más</span>
                                        <span style="font-size:15px;line-height:1;">›</span>
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        {{-- ══ EMPRESAS CAROUSEL SECTION (ESTAS EMPRESAS NECESITAN TU TALENTO) ══ --}}
        <section class="companies-section">
            <h2 class="companies-heading">Estas empresas necesitan tu talento</h2>

            <div class="companies-carousel-row">
                <button type="button" class="carousel-arrow-btn" onclick="scrollCompanies(-1)" aria-label="Ver empresas anteriores">
                    <span class="material-symbols-outlined" style="font-size:22px;">chevron_left</span>
                </button>

                <div class="companies-slider-track" id="companies-scroll-track">
                    {{-- Empresas registradas en BD (únicamente las que existen en la bolsa laboral) --}}
                    @forelse($companies as $co)
                        <div class="company-card-item" onclick="window.location.href='/empresas/{{ $co->id }}'" title="Ver perfil y vacantes de {{ $co->name }}">
                            @if($co->logo)
                                <img src="{{ $co->logo }}" alt="{{ $co->name }}" class="company-logo-svg" onerror="this.onerror=null;this.parentNode.innerHTML='<div class=\'company-card-fallback\'><span class=\'company-fallback-avatar\'>{{ strtoupper(substr($co->name, 0, 1)) }}</span><span class=\'company-fallback-name\'>{{ $co->name }}</span></div>';">
                            @else
                                <div class="company-card-fallback">
                                    <span class="company-fallback-avatar">{{ strtoupper(substr($co->name, 0, 1)) }}</span>
                                    <span class="company-fallback-name">{{ $co->name }}</span>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="companies-empty-message">
                            <span class="material-symbols-outlined" style="font-size:24px;color:#94a3b8;">domain</span>
                            <span>Próximamente nuevas empresas aliadas se sumarán a nuestra bolsa laboral.</span>
                        </div>
                    @endforelse
                </div>

                <button type="button" class="carousel-arrow-btn" onclick="scrollCompanies(1)" aria-label="Ver empresas siguientes">
                    <span class="material-symbols-outlined" style="font-size:22px;">chevron_right</span>
                </button>
            </div>

            <div class="companies-action-center">
                <button type="button" class="btn-view-all-companies" onclick="showAllCompanies()">
                    <span class="material-symbols-outlined" style="font-size:18px;">domain</span>
                    <span>Ver todas las empresas</span>
                </button>
            </div>
        </section>

        {{-- ══ SECONDARY FILTERS BAR (CATEGORÍA, MODALIDAD, ORDEN) ══ --}}
        <div class="secondary-filters-bar">
            <div class="filter-selectors">
                <div class="filter-item-wrap">
                    <label class="filter-item-label" for="category-select">Área / Especialidad</label>
                    <select id="category-select" class="sub-select" onchange="triggerSearch(true)">
                        <option value="">Todas las áreas</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-item-wrap">
                    <label class="filter-item-label" for="modality-select">Modalidad</label>
                    <select id="modality-select" class="sub-select" onchange="triggerSearch(true)">
                        <option value="">Todas las modalidades</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-item-wrap">
                    <label class="filter-item-label" for="sort-select">Ordenar por</label>
                    <select id="sort-select" class="sub-select" onchange="triggerSearch(true)">
                        <option value="recent">Más recientes</option>
                        <option value="salary_desc">Mayor salario</option>
                        <option value="salary_asc">Menor salario</option>
                        <option value="title_asc">A - Z</option>
                    </select>
                </div>
            </div>

            <button type="button" class="btn-clear-filters" onclick="clearAllFilters()">
                <span class="material-symbols-outlined" style="font-size:17px;">filter_alt_off</span>
                <span>Limpiar filtros</span>
            </button>
        </div>

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
        @if(!$authUser || !in_array($authUser->rol_id, [2, 3, 5]))
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
        @endif

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

            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:14px;">
                <span id="m-badge-type" class="card-type-badge badge-practica">Prácticas</span>
                <span id="m-badge-schedule" class="card-type-badge" style="background:#f1f5f9;color:#334155;">Tiempo Completo</span>
                <span id="m-badge-category" class="card-type-badge" style="background:#e0f2fe;color:#0369a1;">Categoría</span>
                <span id="m-badge-external" class="card-type-badge" style="background:#f3e8ff;color:#7e22ce;display:none;">Externa ↗</span>
            </div>

            <div id="m-status-banner" style="display:none;padding:12px 16px;border-radius:12px;margin-bottom:16px;font-size:13px;font-weight:600;align-items:center;gap:10px;"></div>

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
                    <input type="password" id="lpwd-current" class="field-input" required placeholder="Tu contraseña actual">
                </div>
                <div style="text-align:left;margin-bottom:14px;">
                    <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;">Nueva Contraseña</label>
                    <input type="password" id="lpwd-new" class="field-input" required minlength="8" placeholder="Mínimo 8 caracteres">
                </div>
                <div style="text-align:left;margin-bottom:18px;">
                    <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;">Confirmar Nueva Contraseña</label>
                    <input type="password" id="lpwd-confirm" class="field-input" required minlength="8" placeholder="Repite la nueva contraseña">
                </div>

                <div id="lpwd-alert" style="display:none;padding:10px 14px;border-radius:10px;font-size:13px;font-weight:600;margin-bottom:14px;text-align:left;"></div>

                <div class="modal-actions">
                    <button type="submit" id="btn-lpwd-submit" class="modal-btn-pri">
                        <span class="material-symbols-outlined" style="font-size:18px;">check</span>
                        <span id="btn-lpwd-text">Actualizar Contraseña</span>
                    </button>
                    <button type="button" class="modal-btn-sec" onclick="closePasswordModal()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══ MODAL GESTIONAR / SUBIR CV ══ --}}
    <div id="modal-cvs" class="modal-overlay" onclick="if(event.target===this)closeCvModal()">
        <div class="modal-box" style="max-width:540px;">
            <button type="button" class="modal-close-btn" onclick="closeCvModal()">
                <span class="material-symbols-outlined">close</span>
            </button>

            <div style="width:52px;height:52px;border-radius:16px;background:var(--pri-tint);color:var(--pri);display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                <span class="material-symbols-outlined filled" style="font-size:26px;">picture_as_pdf</span>
            </div>
            <h3 style="font-family:'Plus Jakarta Sans',sans-serif;font-size:20px;font-weight:800;color:#111827;text-align:center;margin-bottom:6px;">Mi Currículum Vitae (PDF)</h3>
            <p style="font-size:13px;color:#6b7280;text-align:center;margin-bottom:20px;">Sube o actualiza tu CV para que las empresas conozcan tu talento.</p>

            {{-- Uploader --}}
            <div style="border:2px dashed #cbd5e1;border-radius:16px;padding:24px 16px;text-align:center;background:#f8fafc;cursor:pointer;margin-bottom:18px;" onclick="document.getElementById('input-landing-cv').click()" id="landing-cv-dropzone">
                <span class="material-symbols-outlined" style="font-size:40px;color:#3b82f6;display:block;margin-bottom:6px;">upload_file</span>
                <p style="font-size:14px;font-weight:700;color:#1e293b;margin-bottom:2px;">Haz clic para seleccionar tu CV en PDF</p>
                <span style="font-size:11px;color:#64748b;background:#e2e8f0;padding:2px 8px;border-radius:50px;font-weight:600;">Máximo 5 MB • Formato PDF</span>
                <input type="file" id="input-landing-cv" accept=".pdf,application/pdf" style="display:none;" onchange="handleLandingCvFile(this)">
            </div>

            <div id="landing-cv-selected" style="display:none;align-items:center;gap:10px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;padding:10px 14px;margin-bottom:18px;">
                <span class="material-symbols-outlined" style="color:#ef4444;font-size:24px;">description</span>
                <div style="flex:1;min-width:0;text-align:left;">
                    <p id="landing-cv-name" style="font-size:13px;font-weight:700;color:#0f172a;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">archivo.pdf</p>
                    <span id="landing-cv-size" style="font-size:11px;color:#64748b;">0 KB</span>
                </div>
                <button type="button" onclick="submitLandingCvUpload()" id="btn-upload-landing-cv" class="modal-btn-pri" style="width:auto;padding:6px 14px;font-size:12px;">
                    Subir ahora
                </button>
            </div>

            {{-- List of Registered CVs --}}
            <h4 style="font-size:12px;font-weight:800;text-transform:uppercase;color:#64748b;letter-spacing:0.04em;text-align:left;margin-bottom:10px;">Mis CVs Registrados</h4>
            <div id="landing-cvs-list" style="max-height:200px;overflow-y:auto;text-align:left;display:flex;flex-direction:column;gap:8px;margin-bottom:20px;">
                @if(isset($studentCvs) && $studentCvs->count() > 0)
                    @foreach($studentCvs as $cv)
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;padding:10px 14px;border:1px solid var(--bor);border-radius:12px;background:#ffffff;">
                        <div style="display:flex;align-items:center;gap:10px;min-width:0;">
                            <span class="material-symbols-outlined" style="color:#ef4444;font-size:22px;">picture_as_pdf</span>
                            <div style="min-width:0;">
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <span style="font-size:10px;font-weight:800;background:#01143c;color:#fff;padding:1px 6px;border-radius:4px;">v{{ $cv->version }}</span>
                                    <p style="font-size:13px;font-weight:700;color:#0f172a;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:200px;">{{ $cv->filename }}</p>
                                </div>
                                <span style="font-size:11px;color:#94a3b8;">{{ $cv->uploaded_at }}</span>
                            </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:6px;">
                            <a href="/student/cv/download/{{ $cv->id }}" class="modal-btn-sec" style="padding:4px 10px;font-size:12px;" title="Descargar">
                                <span class="material-symbols-outlined" style="font-size:15px;">download</span>
                            </a>
                            <button type="button" onclick="deleteLandingCv({{ $cv->id }})" class="modal-btn-sec" style="padding:4px 8px;font-size:12px;color:#dc2626;border-color:#fee2e2;" title="Eliminar">
                                <span class="material-symbols-outlined" style="font-size:15px;">delete</span>
                            </button>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div style="padding:16px;background:#f8fafc;border-radius:12px;text-align:center;color:#64748b;font-size:13px;">
                        Aún no tienes ningún currículum registrado.
                    </div>
                @endif
            </div>

            <div class="modal-actions">
                <a href="{{ route('student.applications') }}?tab=profile" class="modal-btn-sec" style="font-size:13px;">
                    <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                    Editar mis datos completos
                </a>
                <button type="button" class="modal-btn-pri" onclick="closeCvModal()">Listo</button>
            </div>
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
var authRoleId = {{ $authUser ? (int)$authUser->rol_id : 0 }};
var isNonApplicantAuth = (authRoleId === 1 || authRoleId === 4);

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

function triggerSearch(scrollToGrid) {
    loadOffers(1, true);
    if (scrollToGrid) {
        var el = document.getElementById('oportunidades');
        if (el) el.scrollIntoView({ behavior: 'smooth' });
    }
}

function clearAllFilters() {
    if (document.getElementById('search-input')) document.getElementById('search-input').value = '';
    if (document.getElementById('location-select')) document.getElementById('location-select').value = '';
    if (document.getElementById('category-select')) document.getElementById('category-select').value = '';
    if (document.getElementById('modality-select')) document.getElementById('modality-select').value = '';
    if (document.getElementById('sort-select')) document.getElementById('sort-select').value = 'recent';
    loadOffers(1, true);
}

function scrollCompanies(direction) {
    var track = document.getElementById('companies-scroll-track');
    if (track) {
        track.scrollBy({ left: direction * 280, behavior: 'smooth' });
    }
}

function filterByCompany(name) {
    var searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.value = name;
    }
    triggerSearch(true);
    showToast('Filtrando por: ' + name);
}

function showAllCompanies() {
    clearAllFilters();
    var el = document.getElementById('oportunidades');
    if (el) el.scrollIntoView({ behavior: 'smooth' });
    showToast('Mostrando todas las ofertas disponibles');
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

    // Cierre / Vencimiento
    var now = new Date();
    var isExpired = (o.state_id === 3) || (o.state && o.state.key === 'finished');
    var deadlineDateObj = null;
    if (o.deadline) {
        var dp = String(o.deadline).split('T')[0].split(' ')[0].split('-');
        if (dp.length === 3) {
            deadlineDateObj = new Date(Number(dp[0]), Number(dp[1]) - 1, Number(dp[2]), 23, 59, 59);
        } else {
            var dFallback = new Date(o.deadline);
            deadlineDateObj = new Date(dFallback.getFullYear(), dFallback.getMonth(), dFallback.getDate(), 23, 59, 59);
        }
        if (!isExpired && deadlineDateObj < now) {
            isExpired = true;
        }
    }

    var closeHtml = 'Convocatoria abierta';
    if (isExpired) {
        closeHtml = '<span style="background:#fee2e2;color:#b91c1c;padding:3px 8px;border-radius:6px;font-weight:700;font-size:11px;">Convocatoria Finalizada</span>';
    } else if (deadlineDateObj) {
        var months = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Set', 'Oct', 'Nov', 'Dic'];
        closeHtml = esc('Cierra ' + String(deadlineDateObj.getDate()).padStart(2, '0') + ' ' + (months[deadlineDateObj.getMonth()] || ''));
    }

    var externalBadgeHtml = o.external_url ? '<span class="card-type-badge" style="background:#f3e8ff;color:#7e22ce;font-size:11px;margin-left:4px;">Externa ↗</span>' : '';

    // Avatar
    var avatarHtml = '';
    if (logo) {
        avatarHtml = '<img src="' + esc(logo) + '" alt="' + esc(name) + '" class="card-logo-img" onerror="this.onerror=null;this.parentNode.innerHTML=\'<span class=\\\'card-avatar-text\\\'>' + initial + '</span>\';">';
    } else {
        avatarHtml = '<span class="card-avatar-text">' + initial + '</span>';
    }

    return '<div class="job-card ' + (isApplied ? 'card-applied' : '') + '" onclick="window.location.href=\'/ofertas/' + o.id + '\'" data-id="' + o.id + '">' +
        '<div class="card-header">' +
            '<div class="card-header-left">' +
                '<div class="card-avatar" style="background:' + getAvatarBg(initial) + ';color:' + getAvatarColor(initial) + ';">' + avatarHtml + '</div>' +
                '<span class="card-type-badge ' + badgeClass + '">' + esc(typeBadge) + '</span>' +
                externalBadgeHtml +
            '</div>' +
            (!isNonApplicantAuth ? (
                '<button type="button" class="btn-card-star ' + (isSaved ? 'active' : '') + '" onclick="handleStarClick(event, ' + o.id + ')" title="' + (isSaved ? 'Quitar de guardadas' : 'Guardar oportunidad') + '">' +
                    '<span class="material-symbols-outlined ' + (isSaved ? 'filled' : '') + '">' + (isSaved ? 'bookmark' : 'bookmark_border') + '</span>' +
                '</button>'
            ) : '') +
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
            '<span class="card-close-date">' + closeHtml + '</span>' +
            '<a href="/ofertas/' + o.id + '" class="card-link-action" onclick="event.stopPropagation();">Ver oportunidad ↗</a>' +
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

    // Cierre / Vencimiento
    var now = new Date();
    var isExpired = (o.state_id === 3) || (o.state && o.state.key === 'finished');
    if (!isExpired && o.deadline) {
        var dp = String(o.deadline).split('T')[0].split(' ')[0].split('-');
        var deadlineEnd = dp.length === 3
            ? new Date(Number(dp[0]), Number(dp[1]) - 1, Number(dp[2]), 23, 59, 59)
            : new Date(new Date(o.deadline).getFullYear(), new Date(o.deadline).getMonth(), new Date(o.deadline).getDate(), 23, 59, 59);
        if (deadlineEnd < now) isExpired = true;
    }

    // Status banner & badge
    var statusBanner = document.getElementById('m-status-banner');
    var extBadge = document.getElementById('m-badge-external');
    if (extBadge) {
        extBadge.style.display = o.external_url ? 'inline-flex' : 'none';
    }

    if (isExpired) {
        if (statusBanner) {
            statusBanner.style.display = 'flex';
            statusBanner.style.background = '#fef2f2';
            statusBanner.style.border = '1px solid #fecaca';
            statusBanner.style.color = '#991b1b';
            statusBanner.innerHTML = '<span class="material-symbols-outlined" style="font-size:22px;color:#dc2626;flex-shrink:0;">event_busy</span><div><strong style="display:block;font-size:14px;">Convocatoria Finalizada</strong>Esta oferta laboral ya cumplió su fecha límite de postulación y no recibe nuevas solicitudes.</div>';
        }
    } else if (o.external_url) {
        if (statusBanner) {
            statusBanner.style.display = 'flex';
            statusBanner.style.background = '#f5f3ff';
            statusBanner.style.border = '1px solid #ddd6fe';
            statusBanner.style.color = '#5b21b6';
            statusBanner.innerHTML = '<span class="material-symbols-outlined" style="font-size:22px;color:#7c3aed;flex-shrink:0;">open_in_new</span><div><strong style="display:block;font-size:14px;">Convocatoria Externa</strong>Esta vacante se gestiona fuera de la bolsa laboral. Al postular serás redirigido al portal oficial de la empresa.</div>';
        }
    } else {
        if (statusBanner) {
            statusBanner.style.display = 'none';
            statusBanner.innerHTML = '';
        }
    }

    // Botón postular estado
    var applyBtn = document.getElementById('m-btn-apply');
    var saveBtn = document.getElementById('m-btn-save');
    if (saveBtn) {
        saveBtn.style.display = isNonApplicantAuth ? 'none' : '';
    }
    if (isNonApplicantAuth) {
        var panelUrl = (authRoleId === 4) ? '{{ route("company.dashboard") }}' : '{{ route("admin.dashboard") }}';
        var roleName = (authRoleId === 4) ? 'Empresa' : 'Administración';
        applyBtn.innerHTML = '<span class="material-symbols-outlined" style="font-size:18px;">dashboard</span> Modo Vista Previa — Volver a mi Panel de ' + roleName;
        applyBtn.disabled = false;
        applyBtn.style.background = 'var(--pri)';
        applyBtn.style.cursor = 'pointer';
        applyBtn.style.opacity = '1';
        applyBtn.onclick = function() { window.location.href = panelUrl; };
    } else if (isExpired) {
        applyBtn.innerHTML = '<span class="material-symbols-outlined" style="font-size:18px;">block</span> Convocatoria finalizada';
        applyBtn.disabled = true;
        applyBtn.style.background = '#9ca3af';
        applyBtn.style.cursor = 'not-allowed';
        applyBtn.style.opacity = '0.8';
        applyBtn.onclick = null;
    } else if (o.external_url) {
        applyBtn.innerHTML = '<span class="material-symbols-outlined" style="font-size:18px;">open_in_new</span> Postular en sitio externo ↗';
        applyBtn.disabled = false;
        applyBtn.style.background = 'linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%)';
        applyBtn.style.cursor = 'pointer';
        applyBtn.style.opacity = '1';
        applyBtn.onclick = function() {
            window.open(o.external_url, '_blank');
        };
    } else if (appliedOffers.has(o.id)) {
        applyBtn.innerHTML = '<span class="material-symbols-outlined" style="font-size:18px;">check_circle</span> Ya has postulado';
        applyBtn.disabled = true;
        applyBtn.style.background = 'var(--pri)';
        applyBtn.style.cursor = 'default';
        applyBtn.style.opacity = '0.7';
        applyBtn.onclick = null;
    } else {
        applyBtn.innerHTML = '<span class="material-symbols-outlined" style="font-size:18px;">send</span> Postularme ahora';
        applyBtn.disabled = false;
        applyBtn.style.background = 'var(--pri)';
        applyBtn.style.cursor = 'pointer';
        applyBtn.style.opacity = '1';
        applyBtn.onclick = handleModalApplyClick;
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

    // Check expiration
    var now = new Date();
    var isExpired = (o.state_id === 3) || (o.state && o.state.key === 'finished');
    if (!isExpired && o.deadline) {
        var dp = String(o.deadline).split('T')[0].split(' ')[0].split('-');
        var deadlineEnd = dp.length === 3
            ? new Date(Number(dp[0]), Number(dp[1]) - 1, Number(dp[2]), 23, 59, 59)
            : new Date(new Date(o.deadline).getFullYear(), new Date(o.deadline).getMonth(), new Date(o.deadline).getDate(), 23, 59, 59);
        if (deadlineEnd < now) isExpired = true;
    }
    if (isExpired) {
        showToast('Esta convocatoria ha finalizado y ya no recibe postulaciones.');
        return;
    }
    if (o.external_url) {
        window.open(o.external_url, '_blank');
        return;
    }

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

// ── Carrusel en Arco ────────────────────────────────────────────────────────
var currentArchSlide = 0;
var archInterval = null;

function switchArchSlide(idx) {
    var slides = document.querySelectorAll('.hero-arch-slide');
    var dots   = document.querySelectorAll('.arch-dot');
    if (!slides.length) return;

    slides.forEach(function(s) { s.classList.remove('active'); });
    dots.forEach(function(d) { d.classList.remove('active'); });

    currentArchSlide = (idx + slides.length) % slides.length;
    if (slides[currentArchSlide]) slides[currentArchSlide].classList.add('active');
    if (dots[currentArchSlide]) dots[currentArchSlide].classList.add('active');
}

function startArchAutoplay() {
    var slides = document.querySelectorAll('.hero-arch-slide');
    if (slides.length <= 1) return;
    if (archInterval) clearInterval(archInterval);
    archInterval = setInterval(function() {
        switchArchSlide(currentArchSlide + 1);
    }, 5000);
}

// ── Init on DOMContentLoaded ────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
    loadOffers(1, true);
    startArchAutoplay();

    var archFrame = document.getElementById('hero-arch-frame');
    if (archFrame) {
        archFrame.addEventListener('mouseenter', function() { if (archInterval) clearInterval(archInterval); });
        archFrame.addEventListener('mouseleave', function() { startArchAutoplay(); });
    }

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

// ── CV Modal Handlers ──────────────────────────────────────────────────────
var selectedLandingCvFile = null;
function openCvModal() {
    var m = document.getElementById('modal-cvs');
    if (m) m.classList.add('open');
}
function closeCvModal() {
    var m = document.getElementById('modal-cvs');
    if (m) m.classList.remove('open');
}

function handleLandingCvFile(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        if (file.type !== 'application/pdf' && !file.name.toLowerCase().endsWith('.pdf')) {
            showToast('Solo se permiten archivos en formato PDF.', false);
            input.value = '';
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            showToast('El archivo no debe exceder los 5MB.', false);
            input.value = '';
            return;
        }
        selectedLandingCvFile = file;
        var prev = document.getElementById('landing-cv-selected');
        var name = document.getElementById('landing-cv-name');
        var size = document.getElementById('landing-cv-size');
        if (prev) {
            name.textContent = file.name;
            size.textContent = (file.size / 1024).toFixed(1) + ' KB';
            prev.style.display = 'flex';
        }
    }
}

function submitLandingCvUpload() {
    if (!selectedLandingCvFile) return;

    var btn = document.getElementById('btn-upload-landing-cv');
    btn.disabled = true;
    btn.textContent = 'Subiendo...';

    var formData = new FormData();
    formData.append('cv', selectedLandingCvFile);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    fetch('{{ route("student.cv.upload") }}', {
        method: 'POST',
        body: formData,
        headers: { 'Accept': 'application/json' }
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        btn.disabled = false;
        btn.textContent = 'Subir ahora';
        if (data.success) {
            showToast(data.message || 'CV subido exitosamente.');
            setTimeout(function() { window.location.reload(); }, 800);
        } else {
            showToast(data.message || 'Error al subir CV.', false);
        }
    })
    .catch(function() {
        btn.disabled = false;
        btn.textContent = 'Subir ahora';
        showToast('Error de conexión al subir CV.', false);
    });
}

function deleteLandingCv(id) {
    if (!confirm('¿Estás seguro de eliminar este currículum?')) return;

    fetch('/student/cv/delete/' + id, {
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
            showToast(data.message || 'Currículum eliminado.');
            setTimeout(function() { window.location.reload(); }, 700);
        } else {
            showToast(data.message || 'Error al eliminar CV.', false);
        }
    })
    .catch(function() {
        showToast('Error de conexión al eliminar CV.', false);
    });
}

function openModal(id) {
    if (id === 'modal-password') openPasswordModal();
    else if (id === 'modal-cvs') openCvModal();
    else {
        var el = document.getElementById(id);
        if (el) el.classList.add('open');
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDetailModal();
        closePostularModal();
        closePasswordModal();
        closeCvModal();
    }
});
</script>

</body>
</html>
