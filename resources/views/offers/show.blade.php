<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $offer->title }} | {{ $offer->company->name ?? ($config['application_name'] ?? 'Bolsa Laboral') }}</title>
    <meta name="description" content="{{ Str::limit(strip_tags($offer->description), 160) }}">
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

        /* Profile Dropdown */
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

        /* ── BREADCRUMB & BACK ROW ── */
        .breadcrumb-wrap {
            padding: 24px 0 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }
        .btn-back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 700;
            color: var(--pri);
            padding: 8px 16px;
            border-radius: 50px;
            background: var(--pri-tint);
            border: 1px solid var(--pri-tint-border);
            transition: all 0.2s;
        }
        .btn-back-link:hover {
            background: color-mix(in srgb, var(--pri) 12%, #ffffff);
            transform: translateX(-2px);
        }
        .breadcrumb-path {
            font-size: 12px;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .breadcrumb-path a { color: #4b5563; font-weight: 500; }
        .breadcrumb-path a:hover { color: var(--pri); }

        /* ── DETAIL 2-COLUMN LAYOUT ── */
        .detail-layout-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 380px;
            gap: 32px;
            align-items: start;
            margin-bottom: 56px;
        }

        /* Main Left Column */
        .offer-main-card {
            background: #ffffff;
            border: 1px solid var(--bor);
            border-radius: 24px;
            padding: clamp(24px, 3.5vw, 44px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        }
        .offer-header-row {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 24px;
            padding-bottom: 24px;
            border-bottom: 1px solid #f3f4f6;
        }
        .company-large-avatar {
            width: 68px;
            height: 68px;
            border-radius: 16px;
            background: var(--pri);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 28px;
            font-weight: 800;
            overflow: hidden;
            flex-shrink: 0;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
        }
        .company-large-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .offer-title-wrap {
            flex: 1;
            min-width: 0;
        }
        .offer-badges-row {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }
        .badge-type {
            font-size: 11px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 50px;
        }
        .badge-practica { background: #dcfce7; color: #15803d; }
        .badge-empleo { background: #f3e8ff; color: #6b21a8; }
        .badge-sub {
            background: #f1f5f9;
            color: #475569;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 50px;
        }
        .offer-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: clamp(24px, 2.5vw, 34px);
            font-weight: 800;
            color: #111827;
            line-height: 1.25;
            letter-spacing: -0.02em;
            margin-bottom: 8px;
        }
        .offer-company-info {
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 13px;
            color: #6b7280;
            flex-wrap: wrap;
        }
        .offer-company-name {
            font-weight: 700;
            color: #374151;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .verified-badge {
            color: var(--pri);
            font-size: 16px;
        }

        /* Highlight Stats Strip */
        .stats-highlight-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            background: var(--pri-tint);
            border: 1px solid var(--pri-tint-border);
            border-radius: 18px;
            padding: 20px 24px;
            margin-bottom: 32px;
        }
        .stat-highlight-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .stat-highlight-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
        }
        .stat-highlight-val {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 15px;
            font-weight: 800;
            color: #111827;
        }
        .stat-highlight-salary {
            color: var(--pri);
            font-size: 18px;
        }

        /* Content Blocks */
        .offer-block {
            margin-bottom: 32px;
        }
        .offer-block-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 16px;
            font-weight: 800;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .offer-block-content {
            font-size: 14.5px;
            line-height: 1.75;
            color: #4b5563;
            white-space: pre-line;
        }
        .offer-block-content p { margin-bottom: 10px; }

        /* Company Card inside Main */
        .about-company-box {
            background: #f8fafc;
            border: 1px solid var(--bor);
            border-radius: 18px;
            padding: 24px;
            margin-top: 36px;
        }
        .about-company-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 12px;
        }
        .about-company-desc {
            font-size: 13.5px;
            line-height: 1.65;
            color: #4b5563;
            margin-bottom: 14px;
        }
        .company-link {
            font-size: 13px;
            font-weight: 700;
            color: var(--pri);
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        /* ── SIDEBAR STICKY CARD ── */
        .offer-sidebar-card {
            background: #ffffff;
            border: 1px solid var(--bor);
            border-radius: 24px;
            padding: 28px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            position: sticky;
            top: 96px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .applied-status-banner {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 14px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #065f46;
        }
        .applied-status-title {
            font-size: 14px;
            font-weight: 800;
            color: #065f46;
        }
        .applied-status-sub {
            font-size: 12px;
            color: #047857;
            margin-top: 2px;
        }

        .btn-apply-main {
            width: 100%;
            padding: 15px 20px;
            background: var(--pri);
            color: #ffffff;
            border: none;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            transition: all 0.2s;
        }
        .btn-apply-main:hover {
            opacity: 0.93;
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.15);
        }

        .btn-save-offer {
            width: 100%;
            padding: 12px 18px;
            background: transparent;
            color: var(--txt);
            border: 1.5px solid var(--bor);
            border-radius: 14px;
            font-size: 13.5px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.15s;
        }
        .btn-save-offer:hover {
            border-color: var(--pri);
            color: var(--pri);
            background: var(--pri-tint);
        }
        .btn-save-offer.active {
            border-color: var(--pri);
            color: var(--pri);
            background: var(--pri-tint);
        }

        .sidebar-meta-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
            padding: 16px 0;
            border-top: 1px solid #f3f4f6;
            border-bottom: 1px solid #f3f4f6;
        }
        .sidebar-meta-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 13px;
        }
        .sidebar-meta-label {
            color: #6b7280;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .sidebar-meta-val {
            font-weight: 700;
            color: #111827;
        }

        .share-widget-wrap {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .share-label {
            font-size: 12px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .share-buttons-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn-share-icon {
            flex: 1;
            height: 40px;
            border-radius: 10px;
            border: 1px solid var(--bor);
            background: #ffffff;
            color: #4b5563;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s;
        }
        .btn-share-icon:hover {
            border-color: var(--pri);
            color: var(--pri);
            background: var(--pri-tint);
        }

        /* ── OFERTAS SIMILARES SECTION ── */
        .similar-section-wrap {
            margin-top: 24px;
            margin-bottom: 48px;
            padding-top: 36px;
            border-top: 1px solid var(--bor);
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
        .similar-title-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }
        .similar-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: clamp(22px, 2.2vw, 28px);
            font-weight: 800;
            color: #111827;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: -0.02em;
        }
        .similar-subtitle {
            font-size: 13px;
            color: #6b7280;
            font-weight: 500;
        }

        /* ── JOB GRID & CARDS (SAME SYSTEM AS LANDING) ── */
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
            text-decoration: none;
            color: inherit;
        }
        .job-card:hover {
            border-color: var(--pri);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.06);
            transform: translateY(-2px);
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
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .card-type-badge {
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 50px;
            display: inline-block;
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
            font-size: 14px;
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
            flex-wrap: wrap;
            gap: 12px;
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

        /* ── MODALS (POSTULAR & NOTIFY) ── */
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
            max-width: 480px;
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
        .modal-close-btn:hover { background: var(--bg); color: #111827; }
        .modal-btn-pri {
            width: 100%;
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
            width: 100%;
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

        /* ── RESPONSIVE ADAPTATIONS ── */
        @media (max-width: 960px) {
            .detail-layout-grid {
                grid-template-columns: 1fr;
                gap: 24px;
            }
            .offer-sidebar-card {
                position: static;
                order: -1;
            }
            .stats-highlight-grid {
                grid-template-columns: repeat(2, 1fr);
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
        @media (max-width: 640px) {
            .navbar-inner { height: 72px; gap: 12px; }
            .brand-logo-box { width: 44px; height: 44px; border-radius: 11px; font-size: 20px; }
            .brand-title { font-size: 15px; max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
            .brand-sub { display: none; }
            .nav-link-opportunities { display: none; }
            .btn-login-nav { padding: 8px 14px; font-size: 13px; }
            .btn-empresa-nav { padding: 8px 15px; font-size: 13px; gap: 5px; }
            .offer-header-row { flex-direction: column; }
            .stats-highlight-grid { grid-template-columns: 1fr; }
            .footer-inner { flex-direction: column; text-align: center; gap: 10px; }
            .modal-box { padding: 22px 18px; border-radius: 20px; }
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
                    <a href="{{ route('landing') }}#oportunidades" class="nav-link nav-link-opportunities">Oportunidades</a>

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
                                    Mis postulaciones ({{ count($studentApplications ?? []) }})
                                </a>
                                <a href="{{ route('student.saved.offers') }}" class="profile-dropdown-item">
                                    <span class="material-symbols-outlined">bookmark</span>
                                    Ofertas guardadas ({{ count($studentSavedOfferIds ?? []) }})
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
                    @endif

                    <a href="{{ route('login') }}#empresa" class="btn-empresa-nav">
                        <span>Registrarme como Empresa</span>
                        <span class="btn-arrow-icon">↗</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- ══ MAIN CONTENT ══ --}}
    <main class="page-container">

        {{-- Breadcrumbs & Back Action --}}
        <div class="breadcrumb-wrap">
            <a href="{{ route('landing') }}#oportunidades" class="btn-back-link">
                <span>← Volver a todas las oportunidades</span>
            </a>
            <div class="breadcrumb-path">
                <a href="{{ route('landing') }}">Inicio</a>
                <span>/</span>
                <a href="{{ route('landing') }}#oportunidades">Oportunidades</a>
                <span>/</span>
                <span style="color:var(--txt);font-weight:600;">{{ Str::limit($offer->title, 35) }}</span>
            </div>
        </div>

        {{-- 2-Column Detail Grid --}}
        <div class="detail-layout-grid">

            {{-- ── LEFT: MAIN JOB CONTENT ── --}}
            <article class="offer-main-card">
                
                {{-- Header --}}
                <div class="offer-header-row">
                    @php
                        $coName = $offer->company->name ?? 'Empresa';
                        $initial = strtoupper(substr($coName, 0, 1));
                        $isPractica = Str::contains(Str::lower($offer->title), ['practicante', 'práctica']) 
                            || Str::contains(Str::lower($offer->category->name ?? ''), ['práctica']);
                    @endphp

                    <div class="company-large-avatar" style="background: {{ $offer->company->logo ? 'transparent' : 'var(--pri)' }};">
                        @if(!empty($offer->company->logo))
                            <img src="{{ $offer->company->logo }}" alt="{{ $coName }}" onerror="this.onerror=null;this.parentNode.innerHTML='{{ $initial }}';">
                        @else
                            {{ $initial }}
                        @endif
                    </div>

                    <div class="offer-title-wrap">
                        <div class="offer-badges-row">
                            <span class="card-type-badge {{ $isPractica ? 'badge-practica' : 'badge-empleo' }}">
                                {{ $isPractica ? 'Prácticas profesionales' : ($offer->contractType->name ?? 'Empleo') }}
                            </span>
                            @if($offer->modality)
                                <span class="badge-sub">{{ $offer->modality->name }}</span>
                            @endif
                            @if($offer->category)
                                <span class="badge-sub" style="background:#e0f2fe;color:#0369a1;">{{ $offer->category->name }}</span>
                            @endif
                        </div>

                        <h1 class="offer-title">{{ $offer->title }}</h1>

                        <div class="offer-company-info">
                            <span class="offer-company-name">
                                {{ $coName }}
                                @if(!empty($offer->company->is_verified))
                                    <span class="material-symbols-outlined verified-badge filled" title="Empresa Verificada">verified</span>
                                @endif
                            </span>
                            <span>•</span>
                            <span style="display:inline-flex;align-items:center;gap:4px;">
                                <span class="material-symbols-outlined" style="font-size:16px;">location_on</span>
                                {{ ($offer->province ? $offer->province . ', ' : '') . ($offer->department ?? 'Perú') }}
                            </span>
                            <span>•</span>
                            <span style="display:inline-flex;align-items:center;gap:4px;">
                                <span class="material-symbols-outlined" style="font-size:16px;">schedule</span>
                                Publicado {{ $offer->publication_date ? \Carbon\Carbon::parse($offer->publication_date)->diffForHumans() : 'recientemente' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Highlights Strip --}}
                <div class="stats-highlight-grid">
                    <div class="stat-highlight-item">
                        <span class="stat-highlight-label">Remuneración</span>
                        <span class="stat-highlight-val stat-highlight-salary">
                            @if(!$offer->hide_salary && $offer->salary)
                                S/ {{ number_format($offer->salary, 2) }}
                            @else
                                A tratar
                            @endif
                        </span>
                    </div>
                    <div class="stat-highlight-item">
                        <span class="stat-highlight-label">Modalidad</span>
                        <span class="stat-highlight-val">{{ $offer->modality->name ?? 'Presencial' }}</span>
                    </div>
                    <div class="stat-highlight-item">
                        <span class="stat-highlight-label">Jornada</span>
                        <span class="stat-highlight-val">{{ $offer->workSchedule->name ?? 'Tiempo Completo' }}</span>
                    </div>
                    <div class="stat-highlight-item">
                        <span class="stat-highlight-label">Fecha límite</span>
                        <span class="stat-highlight-val">
                            {{ $offer->deadline ? \Carbon\Carbon::parse($offer->deadline)->format('d M Y') : 'Convocatoria abierta' }}
                        </span>
                    </div>
                </div>

                {{-- Descripción del puesto --}}
                <section class="offer-block">
                    <h2 class="offer-block-title">
                        <span class="material-symbols-outlined" style="color:var(--pri);">description</span>
                        Descripción del puesto
                    </h2>
                    <div class="offer-block-content">
                        {!! nl2br(e($offer->description)) !!}
                    </div>
                </section>

                {{-- Requisitos --}}
                @if(!empty($offer->requirements))
                <section class="offer-block">
                    <h2 class="offer-block-title">
                        <span class="material-symbols-outlined" style="color:var(--pri);">checklist</span>
                        Requisitos y perfil buscado
                    </h2>
                    <div class="offer-block-content">
                        {!! nl2br(e($offer->requirements)) !!}
                    </div>
                </section>
                @endif

                {{-- Beneficios --}}
                @if(!empty($offer->benefits))
                <section class="offer-block">
                    <h2 class="offer-block-title">
                        <span class="material-symbols-outlined" style="color:var(--pri);">card_giftcard</span>
                        Beneficios
                    </h2>
                    <div class="offer-block-content">
                        {!! nl2br(e($offer->benefits)) !!}
                    </div>
                </section>
                @endif

                {{-- Acerca de la empresa --}}
                <div class="about-company-box">
                    <div class="about-company-header">
                        <span class="material-symbols-outlined" style="color:var(--pri);font-size:24px;">domain</span>
                        <h3 style="font-family:'Plus Jakarta Sans',sans-serif;font-size:16px;font-weight:800;color:#111827;">
                            Acerca de {{ $coName }}
                        </h3>
                    </div>
                    @if(!empty($offer->company->description))
                        <p class="about-company-desc">{{ $offer->company->description }}</p>
                    @else
                        <p class="about-company-desc">Empresa registrada en la bolsa laboral institucional que promueve el desarrollo profesional de nuestros talentos.</p>
                    @endif
                    @if(!empty($offer->company->website))
                        <a href="{{ $offer->company->website }}" target="_blank" rel="noopener noreferrer" class="company-link">
                            <span>Visitar sitio web oficial</span>
                            <span style="font-size:13px;">↗</span>
                        </a>
                    @endif
                </div>

            </article>

            {{-- ── RIGHT: STICKY APPLICATION SIDEBAR ── --}}
            <aside class="offer-sidebar-card">
                
                @if($hasApplied)
                    <div class="applied-status-banner">
                        <span class="material-symbols-outlined" style="font-size:28px;">check_circle</span>
                        <div>
                            <div class="applied-status-title">¡Ya has postulado!</div>
                            <div class="applied-status-sub">Tu postulación está siendo evaluada por la empresa.</div>
                        </div>
                    </div>
                @else
                    <button type="button" class="btn-apply-main" onclick="openApplyFlow()">
                        <span class="material-symbols-outlined">send</span>
                        <span>Postularme ahora</span>
                    </button>
                @endif

                <button type="button" id="btn-save-toggle" class="btn-save-offer {{ $isSaved ? 'active' : '' }}" onclick="handleSaveOffer({{ $offer->id }})">
                    <span class="material-symbols-outlined" id="save-icon">{{ $isSaved ? 'bookmark' : 'bookmark_border' }}</span>
                    <span id="save-label">{{ $isSaved ? 'Guardada en tus ofertas' : 'Guardar oferta' }}</span>
                </button>

                {{-- Metadata List --}}
                <div class="sidebar-meta-list">
                    <div class="sidebar-meta-item">
                        <span class="sidebar-meta-label">
                            <span class="material-symbols-outlined" style="font-size:16px;">payments</span>
                            Salario
                        </span>
                        <span class="sidebar-meta-val">
                            @if(!$offer->hide_salary && $offer->salary)
                                S/ {{ number_format($offer->salary, 2) }}
                            @else
                                A tratar
                            @endif
                        </span>
                    </div>

                    <div class="sidebar-meta-item">
                        <span class="sidebar-meta-label">
                            <span class="material-symbols-outlined" style="font-size:16px;">work</span>
                            Tipo de puesto
                        </span>
                        <span class="sidebar-meta-val">{{ $offer->contractType->name ?? 'Indeterminado' }}</span>
                    </div>

                    <div class="sidebar-meta-item">
                        <span class="sidebar-meta-label">
                            <span class="material-symbols-outlined" style="font-size:16px;">domain</span>
                            Modalidad
                        </span>
                        <span class="sidebar-meta-val">{{ $offer->modality->name ?? 'Presencial' }}</span>
                    </div>

                    <div class="sidebar-meta-item">
                        <span class="sidebar-meta-label">
                            <span class="material-symbols-outlined" style="font-size:16px;">group</span>
                            Vacantes
                        </span>
                        <span class="sidebar-meta-val">{{ $offer->vacancies ?? 1 }} {{ ($offer->vacancies ?? 1) > 1 ? 'vacantes' : 'vacante' }}</span>
                    </div>

                    <div class="sidebar-meta-item">
                        <span class="sidebar-meta-label">
                            <span class="material-symbols-outlined" style="font-size:16px;">event</span>
                            Fecha límite
                        </span>
                        <span class="sidebar-meta-val">
                            {{ $offer->deadline ? \Carbon\Carbon::parse($offer->deadline)->format('d/m/Y') : 'Abierta' }}
                        </span>
                    </div>
                </div>

                {{-- Compartir --}}
                <div class="share-widget-wrap">
                    <span class="share-label">Compartir oferta</span>
                    <div class="share-buttons-row">
                        <button type="button" class="btn-share-icon" onclick="copyOfferLink()" title="Copiar enlace">
                            <span class="material-symbols-outlined" style="font-size:16px;">content_copy</span>
                            <span>Copiar</span>
                        </button>
                        <a href="https://wa.me/?text={{ urlencode('Oportunidad laboral: ' . $offer->title . ' en ' . $coName . ' - ' . url()->current()) }}" target="_blank" rel="noopener noreferrer" class="btn-share-icon" title="Compartir en WhatsApp">
                            <span class="material-symbols-outlined" style="font-size:16px;">chat</span>
                            <span>WhatsApp</span>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer" class="btn-share-icon" title="Compartir en LinkedIn">
                            <span class="material-symbols-outlined" style="font-size:16px;">share</span>
                            <span>LinkedIn</span>
                        </a>
                    </div>
                </div>

            </aside>
        </div>

        {{-- ── OFERTAS SIMILARES SECTION ── --}}
        <section class="similar-section-wrap" id="similares">
            <span class="section-tagline">EXPLORA MÁS OPCIONES</span>
            <div class="similar-title-row">
                <h2 class="similar-title">
                    Ofertas similares
                    <span style="font-size:13px;font-weight:700;padding:2px 10px;border-radius:50px;background:var(--pri-pill-bg);color:var(--pri);">
                        {{ $similarOffers->count() }}
                    </span>
                </h2>
                <span class="similar-subtitle">
                    Oportunidades relacionadas que podrían interesarte.
                </span>
            </div>

            @if($similarOffers->isNotEmpty())
                <div class="job-grid">
                    @foreach($similarOffers as $sim)
                        @php
                            $simCo = $sim->company->name ?? 'Empresa';
                            $simInitial = strtoupper(substr($simCo, 0, 1));
                            $simPractica = Str::contains(Str::lower($sim->title), ['practicante', 'práctica']) 
                                || Str::contains(Str::lower($sim->category->name ?? ''), ['práctica']);
                            $simType = $simPractica ? 'Prácticas profesionales' : ($sim->contractType->name ?? 'Empleo');
                            $simBadgeClass = $simPractica ? 'badge-practica' : 'badge-empleo';
                            $simSalary = (!$sim->hide_salary && $sim->salary) 
                                ? 'S/ ' . number_format($sim->salary, 2) 
                                : 'A tratar';
                            $simDeadline = $sim->deadline 
                                ? 'Cierra ' . \Carbon\Carbon::parse($sim->deadline)->format('d M') 
                                : 'Convocatoria abierta';
                            $isSimSaved = in_array($sim->id, $studentSavedOfferIds ?? []);
                        @endphp

                        <div class="job-card" onclick="window.location.href='{{ route('landing.offer.show', $sim->id) }}'">
                            <div>
                                <div class="card-header">
                                    <div class="card-header-left">
                                        <div class="card-avatar" style="background:var(--pri-pill-bg);color:var(--pri);">
                                            @if(!empty($sim->company->logo))
                                                <img src="{{ $sim->company->logo }}" alt="{{ $simCo }}" style="width:100%;height:100%;object-fit:cover;">
                                            @else
                                                {{ $simInitial }}
                                            @endif
                                        </div>
                                        <span class="card-type-badge {{ $simBadgeClass }}">{{ $simType }}</span>
                                    </div>
                                    <button type="button" class="btn-card-star {{ $isSimSaved ? 'active' : '' }}" onclick="event.stopPropagation();handleSaveOffer({{ $sim->id }}, this)" title="{{ $isSimSaved ? 'Quitar de guardadas' : 'Guardar oferta' }}">
                                        <span class="material-symbols-outlined {{ $isSimSaved ? 'filled' : '' }}">{{ $isSimSaved ? 'bookmark' : 'bookmark_border' }}</span>
                                    </button>
                                </div>
                                <div class="card-company-name">{{ $simCo }}</div>
                                <h3 class="card-job-title">{{ $sim->title }}</h3>
                                <div class="card-meta-row">
                                    <span class="card-meta-item">
                                        <span class="material-symbols-outlined">location_on</span>
                                        {{ ($sim->province ? $sim->province . ', ' : '') . ($sim->department ?? 'Perú') }}
                                    </span>
                                    <span class="card-meta-item">
                                        <span class="material-symbols-outlined">domain</span>
                                        {{ $sim->modality->name ?? 'Presencial' }}
                                    </span>
                                </div>
                                <p class="card-desc">{{ Str::limit(strip_tags($sim->description), 110) }}</p>
                                <div class="card-salary">{{ $simSalary }} <span class="card-salary-sub">/ mes</span></div>
                            </div>
                            <div class="card-footer">
                                <span class="card-close-date">{{ $simDeadline }}</span>
                                <a href="{{ route('landing.offer.show', $sim->id) }}" class="card-link-action" onclick="event.stopPropagation();">
                                    Ver oportunidad ↗
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="background:#ffffff;border:1px solid var(--bor);border-radius:20px;padding:36px 24px;text-align:center;margin-bottom:32px;">
                    <span class="material-symbols-outlined" style="font-size:40px;color:#9ca3af;margin-bottom:8px;">search_off</span>
                    <h3 style="font-size:16px;font-weight:700;color:#111827;margin-bottom:4px;">No hay otras ofertas similares por el momento</h3>
                    <p style="font-size:13.5px;color:#6b7280;margin-bottom:16px;">Puedes explorar todas las convocatorias vigentes en la página principal.</p>
                    <a href="{{ route('landing') }}#oportunidades" class="btn-back-link">Explorar todas las oportunidades</a>
                </div>
            @endif
        </section>

        {{-- ── CTA BANNER EMPRESAS ── --}}
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

    {{-- ══ MODAL POSTULAR (AUTH VS GUEST) ══ --}}
    <div id="postular-modal" class="modal-overlay" onclick="if(event.target===this)closePostularModal()">
        <div class="modal-box" style="text-align:center;">
            <button type="button" class="modal-close-btn" onclick="closePostularModal()">
                <span class="material-symbols-outlined">close</span>
            </button>

            @if($authUser)
                {{-- Formulario para usuario autenticado --}}
                <div style="width:56px;height:56px;border-radius:18px;background:var(--pri-tint);color:var(--pri);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <span class="material-symbols-outlined filled" style="font-size:28px;">send</span>
                </div>
                <h2 style="font-family:'Plus Jakarta Sans',sans-serif;font-size:20px;font-weight:800;color:#111827;margin-bottom:6px;">Postular a vacante</h2>
                <p style="font-size:13px;color:#6b7280;margin-bottom:18px;">Selecciona tu CV registrado para enviar tu postulación a <strong>{{ $offer->title }}</strong>.</p>

                <div id="postular-alert" style="display:none;padding:10px 14px;border-radius:10px;font-size:13px;font-weight:600;margin-bottom:14px;text-align:left;"></div>

                @if($studentCvs->isNotEmpty())
                    <form id="apply-form" onsubmit="submitApplication(event)" style="text-align:left;">
                        @csrf
                        <div style="margin-bottom:16px;">
                            <label style="display:block;font-size:12.5px;font-weight:700;color:#374151;margin-bottom:6px;">Selecciona tu CV</label>
                            <select id="apply-cv-id" name="cv_id" style="width:100%;height:44px;border:1.5px solid var(--bor);border-radius:10px;padding:0 12px;font-size:13.5px;outline:none;" required>
                                @foreach($studentCvs as $cv)
                                    <option value="{{ $cv->id }}">Versión {{ $cv->version }} — {{ $cv->filename }} ({{ $cv->uploaded_at }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div style="margin-bottom:20px;">
                            <label style="display:block;font-size:12.5px;font-weight:700;color:#374151;margin-bottom:6px;">Mensaje de presentación (opcional)</label>
                            <textarea id="apply-msg" name="message" rows="3" placeholder="Coméntale a la empresa por qué eres el candidato ideal..." style="width:100%;border:1.5px solid var(--bor);border-radius:10px;padding:10px 12px;font-size:13px;font-family:inherit;outline:none;resize:vertical;"></textarea>
                        </div>
                        <button type="submit" id="btn-submit-apply" class="modal-btn-pri">
                            <span class="material-symbols-outlined" style="font-size:18px;">send</span>
                            <span>Enviar postulación</span>
                        </button>
                    </form>
                @else
                    <div style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;border-radius:12px;padding:16px;font-size:13px;margin-bottom:16px;text-align:left;">
                        <strong>Aún no tienes ningún CV cargado.</strong>
                        <p style="margin-top:4px;color:#b91c1c;">Sube tu CV en PDF en tu perfil para poder postular a esta y más vacantes.</p>
                    </div>
                    <a href="{{ route('student.dashboard') }}?tab=cvs" class="modal-btn-pri" style="margin-bottom:8px;">
                        <span>Subir mi CV ahora</span>
                        <span>↗</span>
                    </a>
                @endif

            @else
                {{-- Invitado: requiere login --}}
                <div style="width:56px;height:56px;border-radius:18px;background:var(--pri-tint);color:var(--pri);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <span class="material-symbols-outlined filled" style="font-size:28px;">lock</span>
                </div>
                <h2 style="font-family:'Plus Jakarta Sans',sans-serif;font-size:20px;font-weight:800;color:#111827;margin-bottom:6px;">Inicia sesión para postular</h2>
                <p style="font-size:13.5px;color:#6b7280;margin-bottom:24px;">Debes ingresar con tu cuenta de estudiante o egresado para poder postular a esta vacante.</p>

                <div style="display:flex;flex-direction:column;gap:10px;">
                    <a href="{{ route('login') }}?redirect_offer={{ $offer->id }}" class="modal-btn-pri">
                        <span>Iniciar sesión</span>
                        <span>➔</span>
                    </a>
                    <a href="{{ route('register.graduate') }}?redirect_offer={{ $offer->id }}" class="modal-btn-sec">
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
var isAuth = {{ $authUser ? 'true' : 'false' }};
var offerId = {{ $offer->id }};

function showToast(text, icon) {
    var t = document.getElementById('toast-notify');
    var txt = document.getElementById('toast-text');
    var ic = document.getElementById('toast-icon');
    if (!t) return;
    txt.textContent = text;
    ic.textContent = icon || 'check_circle';
    t.style.display = 'flex';
    setTimeout(function() { t.style.display = 'none'; }, 3500);
}

function openApplyFlow() {
    var m = document.getElementById('postular-modal');
    if (m) m.classList.add('open');
}

function closePostularModal() {
    var m = document.getElementById('postular-modal');
    if (m) m.classList.remove('open');
}

function toggleProfileMenu() {
    var d = document.getElementById('profile-dropdown');
    if (d) d.classList.toggle('open');
}

document.addEventListener('click', function(e) {
    var wrap = document.getElementById('profile-menu-wrap');
    var d = document.getElementById('profile-dropdown');
    if (wrap && d && !wrap.contains(e.target)) {
        d.classList.remove('open');
    }
});

function copyOfferLink() {
    var dummy = document.createElement('input');
    document.body.appendChild(dummy);
    dummy.value = window.location.href;
    dummy.select();
    document.execCommand('copy');
    document.body.removeChild(dummy);
    showToast('Enlace copiado al portapapeles', 'content_copy');
}

// ── Guardar / Desguardar Oferta ──
function handleSaveOffer(id, el) {
    if (!isAuth) {
        openApplyFlow();
        return;
    }
    var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch('/student/save-offer/' + id, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) {
            var isSaved = data.saved;
            if (id === offerId) {
                var btn = document.getElementById('btn-save-toggle');
                var icon = document.getElementById('save-icon');
                var label = document.getElementById('save-label');
                if (btn && icon && label) {
                    if (isSaved) {
                        btn.classList.add('active');
                        icon.textContent = 'bookmark';
                        label.textContent = 'Guardada en tus ofertas';
                    } else {
                        btn.classList.remove('active');
                        icon.textContent = 'bookmark_border';
                        label.textContent = 'Guardar oferta';
                    }
                }
            }
            if (el) {
                if (isSaved) {
                    el.classList.add('active');
                    el.querySelector('.material-symbols-outlined').textContent = 'bookmark';
                    el.querySelector('.material-symbols-outlined').classList.add('filled');
                } else {
                    el.classList.remove('active');
                    el.querySelector('.material-symbols-outlined').textContent = 'bookmark_border';
                    el.querySelector('.material-symbols-outlined').classList.remove('filled');
                }
            }
            showToast(data.message || (isSaved ? 'Oferta guardada' : 'Oferta removida de guardadas'));
        } else {
            showToast(data.message || 'Error al guardar la oferta', 'error');
        }
    })
    .catch(function(err) {
        console.error(err);
        showToast('Error de conexión', 'error');
    });
}

// ── Enviar Postulación (AJAX) ──
function submitApplication(e) {
    e.preventDefault();
    var cvId = document.getElementById('apply-cv-id').value;
    var msg = document.getElementById('apply-msg').value;
    var btn = document.getElementById('btn-submit-apply');
    var alertBox = document.getElementById('postular-alert');

    if (!cvId) {
        alertBox.style.display = 'block';
        alertBox.style.background = '#fef2f2';
        alertBox.style.color = '#991b1b';
        alertBox.textContent = 'Por favor selecciona un CV.';
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined" style="animation:spin 1s infinite;">progress_activity</span> Enviando...';

    var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/student/apply/' + offerId, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ cv_id: cvId, message: msg })
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) {
            closePostularModal();
            showToast('¡Postulación enviada exitosamente!', 'check_circle');
            setTimeout(function() { window.location.reload(); }, 1200);
        } else {
            alertBox.style.display = 'block';
            alertBox.style.background = '#fef2f2';
            alertBox.style.color = '#991b1b';
            alertBox.textContent = data.message || 'Error al procesar la postulación.';
            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-outlined" style="font-size:18px;">send</span> Enviar postulación';
        }
    })
    .catch(function(err) {
        console.error(err);
        alertBox.style.display = 'block';
        alertBox.style.background = '#fef2f2';
        alertBox.style.color = '#991b1b';
        alertBox.textContent = 'Ocurrió un error inesperado al enviar.';
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-outlined" style="font-size:18px;">send</span> Enviar postulación';
    });
}

// Auto abrir modal de postulación si viene con query param ?apply=1
window.addEventListener('DOMContentLoaded', function() {
    var params = new URLSearchParams(window.location.search);
    if (params.get('apply') === '1') {
        openApplyFlow();
    }
});
</script>

</body>
</html>
