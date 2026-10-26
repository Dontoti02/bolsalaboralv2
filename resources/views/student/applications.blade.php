<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $config['application_name'] ?? 'Bolsa Laboral' }} — Mi Portal y Postulaciones</title>
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
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            padding: 0 clamp(16px, 3.2vw, 56px);
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

        /* ── HERO BANNER ── */
        .portal-hero {
            background: linear-gradient(135deg, #01143c 0%, #0d276b 60%, #1e3a8a 100%);
            color: #ffffff;
            padding: 44px 0 36px;
            position: relative;
            overflow: hidden;
        }
        .portal-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.12) 1.5px, transparent 1.5px);
            background-size: 24px 24px;
            opacity: 0.4;
            pointer-events: none;
        }
        .portal-container {
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            padding: 0 clamp(16px, 3.2vw, 56px);
            position: relative;
            z-index: 2;
        }
        .portal-header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            flex-wrap: wrap;
            margin-bottom: 32px;
        }
        .portal-title-block h1 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: clamp(26px, 2.6vw, 34px);
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .role-tag {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #ffffff;
            padding: 4px 12px;
            border-radius: 50px;
        }
        .portal-title-block p {
            font-size: 14.5px;
            color: rgba(255, 255, 255, 0.8);
            margin-top: 6px;
        }

        /* KPI Cards Strip */
        .kpi-strip {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }
        .kpi-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: transform 0.2s ease, background 0.2s ease;
        }
        .kpi-card:hover {
            background: rgba(255, 255, 255, 0.16);
            transform: translateY(-2px);
        }
        .kpi-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 24px;
            flex-shrink: 0;
        }
        .kpi-data {
            display: flex;
            flex-direction: column;
        }
        .kpi-num {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 26px;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.1;
        }
        .kpi-label {
            font-size: 12px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.75);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        /* ── MAIN CONTENT & TABS ── */
        .portal-body {
            width: 100%;
            max-width: 100%;
            margin: -20px auto 60px;
            padding: 0 clamp(16px, 3.2vw, 56px);
            position: relative;
            z-index: 10;
        }

        /* Tab Navigation Bar */
        .portal-nav-tabs {
            background: #ffffff;
            border: 1px solid var(--bor);
            border-radius: 20px;
            padding: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            margin-bottom: 28px;
            overflow-x: auto;
        }
        .tab-btn {
            flex: 1;
            min-width: 190px;
            padding: 14px 20px;
            border-radius: 14px;
            border: none;
            background: transparent;
            font-size: 14.5px;
            font-weight: 700;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
            white-space: nowrap;
        }
        .tab-btn:hover {
            color: var(--pri);
            background: rgba(1, 20, 60, 0.04);
        }
        .tab-btn.active {
            background: linear-gradient(135deg, #01143c 0%, #1e3a8a 100%);
            color: #ffffff;
            box-shadow: 0 4px 16px rgba(1, 20, 60, 0.2);
        }
        .tab-count-badge {
            font-size: 11px;
            font-weight: 800;
            padding: 2px 8px;
            border-radius: 50px;
            background: rgba(0, 0, 0, 0.08);
            color: inherit;
        }
        .tab-btn.active .tab-count-badge {
            background: rgba(255, 255, 255, 0.25);
            color: #ffffff;
        }

        /* Tab Content Panes */
        .tab-pane {
            display: none;
            animation: fadeIn 0.25s ease both;
        }
        .tab-pane.active {
            display: block;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── SECTION: APPLICATIONS (CARDS GRID) ── */
        .applications-filter-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            margin-bottom: 24px;
        }
        .filter-pills {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .filter-pill {
            padding: 7px 16px;
            border-radius: 50px;
            border: 1.5px solid var(--bor);
            background: #ffffff;
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .filter-pill:hover, .filter-pill.active {
            border-color: #01143c;
            color: #01143c;
            background: #eff6ff;
        }
        .filter-pill.active {
            background: #01143c;
            color: #ffffff;
        }

        .apps-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 22px;
        }
        .app-card {
            background: #ffffff;
            border: 1.5px solid var(--bor);
            border-radius: 20px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }
        .app-card:hover {
            transform: translateY(-4px);
            border-color: #3b82f6;
            box-shadow: 0 12px 32px rgba(1, 20, 60, 0.08);
        }
        .app-card-header {
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

        /* Status Badges */
        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11.5px;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 50px;
            letter-spacing: -0.01em;
        }
        .status-postulated { background: #fef3c7; color: #b45309; }
        .status-review { background: #dbeafe; color: #1e40af; }
        .status-accepted { background: #d1fae5; color: #065f46; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .status-finished { background: #f1f5f9; color: #475569; }

        .app-job-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 17px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.35;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .app-company-name {
            font-size: 13.5px;
            font-weight: 600;
            color: #475eb8;
            margin-top: 4px;
            display: inline-block;
        }
        .app-company-name:hover { text-decoration: underline; }

        .app-meta-row {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 12.5px;
            color: #64748b;
            margin-top: auto;
            padding-top: 12px;
            border-top: 1px dashed var(--bor);
        }

        .feedback-card {
            background: #f8fafc;
            border-left: 3.5px solid #01143c;
            border-radius: 6px;
            padding: 10px 14px;
            font-size: 12.5px;
            color: #334155;
            margin-top: 4px;
        }
        .feedback-label {
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #01143c;
            margin-bottom: 2px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .app-card-actions {
            margin-top: 12px;
        }
        .btn-view-app-offer {
            width: 100%;
            padding: 9px 14px;
            border-radius: 12px;
            background: #f1f5f9;
            border: 1px solid var(--bor);
            color: #01143c;
            font-size: 13px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.15s ease;
            text-decoration: none;
        }
        .btn-view-app-offer:hover {
            background: #01143c;
            color: #ffffff;
            border-color: #01143c;
        }

        /* Empty state */
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

        /* ── SECTION: CV MANAGEMENT ── */
        .cv-grid-layout {
            display: grid;
            grid-template-columns: 1fr 1.35fr;
            gap: 28px;
        }
        .portal-card {
            background: #ffffff;
            border: 1.5px solid var(--bor);
            border-radius: 22px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        }
        .portal-card-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 19px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .portal-card-desc {
            font-size: 13.5px;
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 22px;
        }

        /* Drag & Drop Dropzone */
        .cv-dropzone {
            border: 2.5px dashed #cbd5e1;
            border-radius: 18px;
            padding: 36px 20px;
            text-align: center;
            background: #f8fafc;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }
        .cv-dropzone:hover, .cv-dropzone.dragover {
            border-color: #2563eb;
            background: #eff6ff;
        }
        .dropzone-icon {
            font-size: 48px;
            color: #3b82f6;
            margin-bottom: 12px;
            display: block;
        }
        .dropzone-text {
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
        }
        .dropzone-sub {
            font-size: 12.5px;
            color: #64748b;
        }
        .dropzone-badge {
            display: inline-block;
            margin-top: 14px;
            background: #e2e8f0;
            color: #475569;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 50px;
        }

        .selected-file-preview {
            display: none;
            align-items: center;
            gap: 12px;
            background: #ffffff;
            border: 1px solid #bfdbfe;
            border-radius: 14px;
            padding: 12px 16px;
            margin-top: 16px;
        }
        .preview-file-icon {
            font-size: 28px;
            color: #dc2626;
        }
        .preview-file-details {
            flex: 1;
            min-width: 0;
            text-align: left;
        }
        .preview-file-name {
            font-size: 13.5px;
            font-weight: 700;
            color: #0f172a;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .preview-file-size {
            font-size: 11.5px;
            color: #64748b;
        }

        /* Active CV Showcase Card */
        .active-cv-banner {
            background: linear-gradient(135deg, #01143c 0%, #1e3a8a 100%);
            color: #ffffff;
            border-radius: 18px;
            padding: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 24px;
            box-shadow: 0 8px 24px rgba(1, 20, 60, 0.16);
        }
        .active-cv-left {
            display: flex;
            align-items: center;
            gap: 16px;
            min-width: 0;
        }
        .active-cv-icon-box {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            flex-shrink: 0;
        }
        .active-cv-info h4 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 16px;
            font-weight: 800;
            margin-bottom: 2px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .active-cv-tag {
            font-size: 11px;
            font-weight: 800;
            background: #22c55e;
            color: #ffffff;
            padding: 2px 8px;
            border-radius: 50px;
            display: inline-block;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .active-cv-date {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.75);
        }
        .active-cv-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }
        .btn-cv-download {
            background: #ffffff;
            color: #01143c;
            font-weight: 700;
            font-size: 13px;
            padding: 8px 16px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s ease;
        }
        .btn-cv-download:hover {
            background: #f8fafc;
            transform: translateY(-1px);
        }
        .btn-cv-delete {
            background: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.4);
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.15s;
        }
        .btn-cv-delete:hover {
            background: #ef4444;
            color: #ffffff;
        }

        /* CV Versions List */
        .cv-history-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 14px 18px;
            border: 1px solid var(--bor);
            border-radius: 14px;
            background: #ffffff;
            margin-bottom: 10px;
            transition: background 0.15s;
        }
        .cv-history-item:hover {
            background: #f8fafc;
        }
        .cv-history-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }
        .cv-history-icon {
            font-size: 26px;
            color: #ef4444;
        }
        .cv-version-tag {
            font-size: 11px;
            font-weight: 700;
            background: #e2e8f0;
            color: #334155;
            padding: 2px 7px;
            border-radius: 6px;
        }

        /* ── SECTION: PROFILE EDITING ── */
        .profile-edit-grid {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 32px;
        }
        .avatar-uploader-card {
            background: #ffffff;
            border: 1.5px solid var(--bor);
            border-radius: 22px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .avatar-preview-box {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: linear-gradient(135deg, #01143c, #1e3a8a);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            font-weight: 800;
            overflow: hidden;
            margin-bottom: 18px;
            border: 4px solid #ffffff;
            box-shadow: 0 8px 24px rgba(1, 20, 60, 0.15);
            position: relative;
        }
        .avatar-preview-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .btn-upload-avatar {
            background: #f1f5f9;
            color: #01143c;
            border: 1.5px solid var(--bor);
            padding: 9px 18px;
            border-radius: 50px;
            font-size: 13.5px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.15s ease;
            margin-bottom: 12px;
        }
        .btn-upload-avatar:hover {
            background: #01143c;
            color: #ffffff;
            border-color: #01143c;
        }
        .avatar-tip {
            font-size: 11.5px;
            color: #94a3b8;
            line-height: 1.45;
            max-width: 220px;
        }

        /* Form Inputs */
        .form-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            margin-bottom: 18px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 18px;
        }
        .form-label {
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            letter-spacing: -0.01em;
        }
        .form-label span.req { color: #dc2626; margin-left: 2px; }
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
            transition: all 0.18s ease;
            outline: none;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }
        .form-textarea {
            height: auto;
            padding: 12px 16px;
            resize: vertical;
            min-height: 100px;
        }

        /* ── MODALS (CAMBIAR CONTRASEÑA) ── */
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

        /* Toast Notification */
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
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            padding: 0 clamp(16px, 3.2vw, 56px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 13px;
            color: #64748b;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .apps-grid { grid-template-columns: repeat(2, 1fr); }
            .kpi-strip { grid-template-columns: repeat(2, 1fr); }
            .cv-grid-layout { grid-template-columns: 1fr; }
            .profile-edit-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 640px) {
            .apps-grid { grid-template-columns: 1fr; }
            .kpi-strip { grid-template-columns: 1fr; }
            .form-grid-2 { grid-template-columns: 1fr; }
            .navbar-inner { height: 72px; padding: 0 16px; }
            .brand-title { font-size: 16px; max-width: 160px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
            .brand-sub { display: none; }
            .portal-nav-tabs { flex-wrap: nowrap; overflow-x: auto; }
            .tab-btn { min-width: 150px; padding: 12px 14px; font-size: 13px; }
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
                    <span class="brand-sub">Portal del Postulante</span>
                </div>
            </a>

            <div class="nav-links">
                <a href="{{ route('landing') }}" class="nav-link">
                    <span class="material-symbols-outlined" style="font-size:18px;">travel_explore</span>
                    Buscar Oportunidades
                </a>
                <a href="{{ route('student.saved.offers') }}" class="nav-link">
                    <span class="material-symbols-outlined" style="font-size:18px;">bookmark</span>
                    Ofertas Guardadas ({{ $savedOffersCount ?? 0 }})
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
                                <span class="role-tag" style="background:#01143c;color:#fff;font-size:10px;padding:2px 8px;display:inline-block;margin-top:2px;">{{ $roleTitle }}</span>
                            </div>
                        </div>

                        <button type="button" class="profile-dropdown-item" onclick="switchTab('profile');closeProfileMenu()">
                            <span class="material-symbols-outlined">badge</span>
                            Mi Perfil Profesional
                        </button>
                        <button type="button" class="profile-dropdown-item" onclick="switchTab('cvs');closeProfileMenu()">
                            <span class="material-symbols-outlined">description</span>
                            Gestionar mi CV
                        </button>
                        <a href="{{ route('student.saved.offers') }}" class="profile-dropdown-item">
                            <span class="material-symbols-outlined">bookmark</span>
                            Ofertas Guardadas ({{ $savedOffersCount ?? 0 }})
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

    {{-- ══ HERO HEADER BANNER ══ --}}
    <header class="portal-hero">
        <div class="portal-container">
            <div class="portal-header-row">
                <div class="portal-title-block">
                    <h1>
                        <span>Mi Portal Laboral</span>
                        <span class="role-tag">{{ $roleTitle }}</span>
                    </h1>
                    <p>Monitorea tus candidaturas, mantén actualizado tu currículum vitae y perfecciona tu perfil laboral.</p>
                </div>
            </div>

            {{-- Metric Strip --}}
            <div class="kpi-strip">
                <div class="kpi-card" onclick="switchTab('applications');filterApps('all')" style="cursor:pointer;">
                    <div class="kpi-icon" style="background:rgba(255,255,255,0.2);">
                        <span class="material-symbols-outlined">send</span>
                    </div>
                    <div class="kpi-data">
                        <span class="kpi-num">{{ $totalApplications }}</span>
                        <span class="kpi-label">Postulaciones</span>
                    </div>
                </div>
                <div class="kpi-card" onclick="switchTab('applications');filterApps('review')" style="cursor:pointer;">
                    <div class="kpi-icon" style="background:rgba(245,158,11,0.3);">
                        <span class="material-symbols-outlined">pending_actions</span>
                    </div>
                    <div class="kpi-data">
                        <span class="kpi-num">{{ $pendingApplications }}</span>
                        <span class="kpi-label">En Revisión</span>
                    </div>
                </div>
                <div class="kpi-card" onclick="switchTab('applications');filterApps('accepted')" style="cursor:pointer;">
                    <div class="kpi-icon" style="background:rgba(16,185,129,0.3);">
                        <span class="material-symbols-outlined">check_circle</span>
                    </div>
                    <div class="kpi-data">
                        <span class="kpi-num">{{ $acceptedApplications }}</span>
                        <span class="kpi-label">Aceptadas</span>
                    </div>
                </div>
                <div class="kpi-card" onclick="switchTab('cvs')" style="cursor:pointer;">
                    <div class="kpi-icon" style="background:rgba(99,102,241,0.3);">
                        <span class="material-symbols-outlined">description</span>
                    </div>
                    <div class="kpi-data">
                        <span class="kpi-num">{{ $studentCvs->count() }}</span>
                        <span class="kpi-label">CVs Registrados</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- ══ MAIN PORTAL BODY ══ --}}
    <main class="portal-body">

        {{-- Tab Buttons --}}
        <div class="portal-nav-tabs">
            <button type="button" class="tab-btn {{ $activeTab === 'applications' ? 'active' : '' }}" id="tab-btn-applications" onclick="switchTab('applications')">
                <span class="material-symbols-outlined">assignment_turned_in</span>
                <span>Mis Postulaciones</span>
                <span class="tab-count-badge">{{ $totalApplications }}</span>
            </button>
            <button type="button" class="tab-btn {{ $activeTab === 'cvs' ? 'active' : '' }}" id="tab-btn-cvs" onclick="switchTab('cvs')">
                <span class="material-symbols-outlined">upload_file</span>
                <span>Mi Currículum Vitae (CV)</span>
                <span class="tab-count-badge">{{ $studentCvs->count() }}</span>
            </button>
            <button type="button" class="tab-btn {{ $activeTab === 'profile' ? 'active' : '' }}" id="tab-btn-profile" onclick="switchTab('profile')">
                <span class="material-symbols-outlined">badge</span>
                <span>Editar Mi Perfil</span>
            </button>
        </div>

        {{-- ══ TAB PANE 1: MIS POSTULACIONES ══ --}}
        <div class="tab-pane {{ $activeTab === 'applications' ? 'active' : '' }}" id="pane-applications">
            
            <div class="applications-filter-bar">
                <div class="filter-pills">
                    <button type="button" class="filter-pill active" onclick="filterApps('all')">Todas ({{ $totalApplications }})</button>
                    <button type="button" class="filter-pill" onclick="filterApps('review')">En Revisión ({{ $pendingApplications }})</button>
                    <button type="button" class="filter-pill" onclick="filterApps('accepted')">Aceptadas ({{ $acceptedApplications }})</button>
                    <button type="button" class="filter-pill" onclick="filterApps('rejected')">Descartadas ({{ $rejectedApplications }})</button>
                </div>
                <a href="{{ route('landing') }}#oportunidades" class="btn-action-primary" style="padding:8px 18px;font-size:13px;">
                    <span class="material-symbols-outlined" style="font-size:18px;">add_circle</span>
                    Explorar Más Ofertas
                </a>
            </div>

            @if($studentApplications->count() > 0)
                <div class="apps-grid" id="apps-grid">
                    @foreach($studentApplications as $app)
                    @php
                        $st = $app->app_status;
                        $filterGroup = match($st) {
                            'postulated', 'under_review', 'reviewed' => 'review',
                            'accepted' => 'accepted',
                            'rejected' => 'rejected',
                            default => 'all'
                        };
                    @endphp
                    <div class="app-card" data-group="{{ $filterGroup }}">
                        <div class="app-card-header">
                            <div class="company-logo-box">
                                @if(!empty($app->company_logo))
                                    <img src="{{ $app->company_logo }}" alt="{{ $app->company_name }}">
                                @else
                                    <div class="company-logo-initials">{{ strtoupper(substr($app->company_name, 0, 2)) }}</div>
                                @endif
                            </div>

                            @if($st === 'postulated')
                                <span class="badge-status status-postulated">
                                    <span class="material-symbols-outlined" style="font-size:14px;">schedule</span>
                                    Postulado
                                </span>
                            @elseif(in_array($st, ['under_review', 'reviewed']))
                                <span class="badge-status status-review">
                                    <span class="material-symbols-outlined" style="font-size:14px;">visibility</span>
                                    En Revisión
                                </span>
                            @elseif($st === 'accepted')
                                <span class="badge-status status-accepted">
                                    <span class="material-symbols-outlined filled" style="font-size:14px;">check_circle</span>
                                    ¡Aceptado!
                                </span>
                            @elseif($st === 'rejected')
                                <span class="badge-status status-rejected">
                                    <span class="material-symbols-outlined" style="font-size:14px;">cancel</span>
                                    Descartado
                                </span>
                            @else
                                <span class="badge-status status-finished">
                                    <span class="material-symbols-outlined" style="font-size:14px;">archive</span>
                                    Finalizado
                                </span>
                            @endif
                        </div>

                        <div>
                            <h3 class="app-job-title" title="{{ $app->offer_title }}">{{ $app->offer_title }}</h3>
                            @if(!empty($app->company_id))
                                <a href="/empresas/{{ $app->company_id }}" class="app-company-name">{{ $app->company_name }}</a>
                            @else
                                <span class="app-company-name">{{ $app->company_name }}</span>
                            @endif
                        </div>

                        @if(!empty($app->app_feedback))
                        <div class="feedback-card">
                            <span class="feedback-label">
                                <span class="material-symbols-outlined" style="font-size:14px;">chat</span>
                                Mensaje de la empresa:
                            </span>
                            <p style="font-style:italic;">"{{ $app->app_feedback }}"</p>
                        </div>
                        @endif

                        <div class="app-meta-row">
                            <span class="material-symbols-outlined" style="font-size:16px;">calendar_today</span>
                            <span>Postulado el {{ $app->formatted_date }}</span>
                        </div>

                        <div class="app-card-actions">
                            <a href="/ofertas/{{ $app->offer_id }}" class="btn-view-app-offer">
                                <span>Ver detalles de la vacante</span>
                                <span class="material-symbols-outlined" style="font-size:16px;">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="empty-portal-state">
                    <div class="empty-icon-wrap">
                        <span class="material-symbols-outlined">assignment_late</span>
                    </div>
                    <h2 class="empty-title">Aún no te has presentado a ninguna vacante</h2>
                    <p class="empty-desc">Revisa las convocatorias activas de las empresas aliadas y envía tu postulación en pocos clics con tu CV en PDF.</p>
                    <a href="{{ route('landing') }}#oportunidades" class="btn-action-primary">
                        <span class="material-symbols-outlined">search</span>
                        Explorar Vacantes Disponibles
                    </a>
                </div>
            @endif
        </div>

        {{-- ══ TAB PANE 2: MI CURRÍCULUM VITAE (CV) ══ --}}
        <div class="tab-pane {{ $activeTab === 'cvs' ? 'active' : '' }}" id="pane-cvs">
            <div class="cv-grid-layout">
                
                {{-- Left: Uploader Card --}}
                <div class="portal-card">
                    <h3 class="portal-card-title">
                        <span class="material-symbols-outlined" style="color:#2563eb;">cloud_upload</span>
                        Subir Nuevo Currículum
                    </h3>
                    <p class="portal-card-desc">Sube tu documento en formato PDF. Al cargar un nuevo CV, se convertirá automáticamente en tu versión activa para futuras postulaciones.</p>

                    <form id="form-cv-upload" enctype="multipart/form-data">
                        @csrf
                        <div class="cv-dropzone" id="cv-dropzone" onclick="document.getElementById('input-cv-file').click()">
                            <span class="material-symbols-outlined dropzone-icon">picture_as_pdf</span>
                            <p class="dropzone-text">Arrastra tu archivo PDF aquí</p>
                            <p class="dropzone-sub">o haz clic para examinar tu dispositivo</p>
                            <span class="dropzone-badge">Solo PDF • Máximo 5 MB</span>
                            <input type="file" id="input-cv-file" name="cv" accept=".pdf,application/pdf" style="display:none;" onchange="handleCvFileSelect(this)">
                        </div>

                        <div class="selected-file-preview" id="selected-cv-preview">
                            <span class="material-symbols-outlined preview-file-icon">description</span>
                            <div class="preview-file-details">
                                <p class="preview-file-name" id="preview-file-name">cv.pdf</p>
                                <span class="preview-file-size" id="preview-file-size">0 KB</span>
                            </div>
                            <button type="button" onclick="cancelCvFileSelect()" style="background:none;border:none;color:#94a3af;cursor:pointer;">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>

                        <div style="margin-top:20px;">
                            <button type="button" id="btn-submit-cv" class="btn-action-primary" style="width:100%;justify-content:center;" onclick="submitCvUpload()">
                                <span class="material-symbols-outlined">upload</span>
                                <span id="btn-submit-cv-text">Subir y Registrar CV</span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Right: Current CV & History --}}
                <div class="portal-card">
                    <h3 class="portal-card-title">
                        <span class="material-symbols-outlined" style="color:#01143c;">verified_user</span>
                        Currículum Activo & Historial
                    </h3>
                    <p class="portal-card-desc">Este es el CV que verán las empresas cuando te postules a convocatorias laborales.</p>

                    @if($studentCvs->count() > 0)
                        @php $latestCv = $studentCvs->first(); @endphp
                        {{-- Active CV Banner --}}
                        <div class="active-cv-banner">
                            <div class="active-cv-left">
                                <div class="active-cv-icon-box">
                                    <span class="material-symbols-outlined">picture_as_pdf</span>
                                </div>
                                <div class="active-cv-info">
                                    <span class="active-cv-tag">CV Principal Activo</span>
                                    <h4 title="{{ $latestCv->filename }}">{{ $latestCv->filename }} (v{{ $latestCv->version }})</h4>
                                    <span class="active-cv-date">Subido el {{ $latestCv->uploaded_at }}</span>
                                </div>
                            </div>
                            <div class="active-cv-actions">
                                <a href="/student/cv/download/{{ $latestCv->id }}" class="btn-cv-download">
                                    <span class="material-symbols-outlined" style="font-size:16px;">download</span>
                                    Descargar
                                </a>
                                <button type="button" class="btn-cv-delete" onclick="confirmDeleteCv({{ $latestCv->id }})" title="Eliminar este CV">
                                    <span class="material-symbols-outlined" style="font-size:16px;">delete</span>
                                </button>
                            </div>
                        </div>

                        {{-- Prior versions --}}
                        @if($studentCvs->count() > 1)
                            <h4 style="font-size:13px;font-weight:700;text-transform:uppercase;color:#64748b;letter-spacing:0.04em;margin-bottom:12px;">Versiones Anteriores</h4>
                            <div class="cv-history-list">
                                @foreach($studentCvs->slice(1) as $oldCv)
                                <div class="cv-history-item" id="cv-item-{{ $oldCv->id }}">
                                    <div class="cv-history-left">
                                        <span class="material-symbols-outlined cv-history-icon">picture_as_pdf</span>
                                        <div>
                                            <div style="display:flex;align-items:center;gap:6px;">
                                                <span class="cv-version-tag">v{{ $oldCv->version }}</span>
                                                <strong style="font-size:13.5px;color:#0f172a;">{{ $oldCv->filename }}</strong>
                                            </div>
                                            <span style="font-size:11.5px;color:#94a3b8;">Subido: {{ $oldCv->uploaded_at }}</span>
                                        </div>
                                    </div>
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <a href="/student/cv/download/{{ $oldCv->id }}" class="btn-action-primary" style="padding:6px 12px;font-size:12px;background:#f1f5f9;color:#01143c;box-shadow:none;">
                                            <span class="material-symbols-outlined" style="font-size:15px;">download</span>
                                            Descargar
                                        </a>
                                        <button type="button" onclick="confirmDeleteCv({{ $oldCv->id }})" style="background:none;border:none;color:#dc2626;cursor:pointer;padding:6px;">
                                            <span class="material-symbols-outlined" style="font-size:18px;">delete</span>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div class="empty-portal-state" style="padding:40px 16px;">
                            <div class="empty-icon-wrap" style="width:56px;height:56px;font-size:28px;">
                                <span class="material-symbols-outlined">upload_file</span>
                            </div>
                            <h4 class="empty-title" style="font-size:17px;">Aún no has registrado ningún currículum</h4>
                            <p class="empty-desc" style="font-size:13px;margin-bottom:0;">Sube tu primer archivo en el panel izquierdo para estar listo ante cualquier oportunidad.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>

        {{-- ══ TAB PANE 3: EDITAR PERFIL PROFESIONAL ══ --}}
        <div class="tab-pane {{ $activeTab === 'profile' ? 'active' : '' }}" id="pane-profile">
            <div class="profile-edit-grid">
                
                {{-- Left: Avatar & Account Snapshot --}}
                <div class="avatar-uploader-card">
                    <div class="avatar-preview-box" id="avatar-preview-container">
                        @if($authUser->avatar)
                            <img src="{{ $authUser->avatar }}" class="avatar-preview-img" id="avatar-preview-img" alt="Avatar">
                        @else
                            <span id="avatar-preview-text">{{ $avatarText }}</span>
                        @endif
                    </div>

                    <button type="button" class="btn-upload-avatar" onclick="document.getElementById('input-avatar-file').click()">
                        <span class="material-symbols-outlined">photo_camera</span>
                        Cambiar Foto de Perfil
                    </button>
                    <input type="file" id="input-avatar-file" accept="image/jpeg,image/png,image/webp" style="display:none;" onchange="handleAvatarFileSelect(this)">
                    <p class="avatar-tip">Formatos JPG, PNG o WEBP. Máximo 3 MB. Se recomienda una foto profesional.</p>

                    <div style="width:100%;margin-top:24px;padding-top:20px;border-top:1px solid var(--bor);text-align:left;">
                        <span style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;">Cuenta Registrada</span>
                        <p style="font-size:14px;font-weight:700;color:#0f172a;margin-top:2px;overflow:hidden;text-overflow:ellipsis;">{{ $authUser->email }}</p>
                        
                        <div style="margin-top:12px;">
                            <span style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;">Rol en el Sistema</span>
                            <p style="font-size:13px;font-weight:700;color:#475eb8;margin-top:2px;">{{ $roleTitle }}</p>
                        </div>
                    </div>
                </div>

                {{-- Right: Profile Form --}}
                <div class="portal-card">
                    <h3 class="portal-card-title">
                        <span class="material-symbols-outlined" style="color:#01143c;">person</span>
                        Información Personal y Profesional
                    </h3>
                    <p class="portal-card-desc">Mantén tu información actualizada para que las empresas y reclutadores puedan contactarte fácilmente.</p>

                    <form id="form-profile-edit">
                        @csrf
                        <div class="form-grid-2">
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">Nombres y Apellidos Completos <span class="req">*</span></label>
                                <input type="text" name="names" class="form-input" value="{{ $person->names ?? '' }}" required placeholder="Ej. Juan Pérez Ramos">
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">Teléfono / WhatsApp de Contacto</label>
                                <input type="text" name="phone" class="form-input" value="{{ $person->phone ?? '' }}" placeholder="Ej. 987654321">
                            </div>
                        </div>

                        <div class="form-grid-2">
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">Tipo de Documento <span class="req">*</span></label>
                                <select name="document_type" class="form-select" required>
                                    <option value="DNI" {{ ($person->document_type ?? 'DNI') === 'DNI' ? 'selected' : '' }}>DNI</option>
                                    <option value="CE" {{ ($person->document_type ?? '') === 'CE' ? 'selected' : '' }}>Carné de Extranjería (CE)</option>
                                    <option value="PASAPORTE" {{ ($person->document_type ?? '') === 'PASAPORTE' ? 'selected' : '' }}>Pasaporte</option>
                                </select>
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">Número de Documento <span class="req">*</span></label>
                                <input type="text" name="document_number" class="form-input" value="{{ $person->document_number ?? '' }}" required placeholder="Número de identificación">
                            </div>
                        </div>

                        <div class="form-grid-2">
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">Programa de Estudios / Carrera</label>
                                <select name="study_program_id" class="form-select" onchange="handleProgramSelect(this)">
                                    <option value="">Selecciona tu programa...</option>
                                    @foreach($studyPrograms as $sp)
                                    <option value="{{ $sp->id }}" {{ ($person->study_program_id ?? '') == $sp->id ? 'selected' : '' }}>{{ $sp->name }}</option>
                                    @endforeach
                                    <option value="other">Otro / Carrera Externa</option>
                                </select>
                            </div>
                            <div class="form-group" style="margin-bottom:0;" id="custom-career-wrap">
                                <label class="form-label">Nombre de Carrera (Especificar)</label>
                                <input type="text" name="career" id="input-career" class="form-input" value="{{ $person->career ?? '' }}" placeholder="Ej. Computación e Informática">
                            </div>
                        </div>

                        <div class="form-grid-2">
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">Fecha de Nacimiento</label>
                                <input type="date" name="birth_date" class="form-input" value="{{ $person->birth_date ?? '' }}">
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">Sexo</label>
                                <select name="sex" class="form-select">
                                    <option value="">Selecciona...</option>
                                    <option value="M" {{ ($person->sex ?? '') === 'M' ? 'selected' : '' }}>Masculino</option>
                                    <option value="F" {{ ($person->sex ?? '') === 'F' ? 'selected' : '' }}>Femenino</option>
                                    <option value="O" {{ ($person->sex ?? '') === 'O' ? 'selected' : '' }}>Otro</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Sobre Mí / Resumen de Perfil Profesional</label>
                            <textarea name="about_me" class="form-textarea" rows="3" placeholder="Describe brevemente tus fortalezas, intereses profesionales y logros académicos o laborales...">{{ $person->about_me ?? '' }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Habilidades y Competencias (Separadas por comas)</label>
                            @php
                                $skillsVal = is_array($person->skills ?? null) ? implode(', ', $person->skills) : ($person->skills ?? '');
                            @endphp
                            <input type="text" name="skills" class="form-input" value="{{ $skillsVal }}" placeholder="Ej. Liderazgo, JavaScript, Trabajo en equipo, Excel avanzado">
                        </div>

                        <div style="display:flex;justify-content:flex-end;margin-top:24px;">
                            <button type="button" id="btn-save-profile" class="btn-action-primary" onclick="submitProfileEdit()">
                                <span class="material-symbols-outlined">save</span>
                                <span id="btn-save-profile-text">Guardar Cambios del Perfil</span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </main>

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
                    <label class="form-label">Contraseña Actual <span class="req">*</span></label>
                    <input type="password" name="current_password" id="pwd-current" class="form-input" required placeholder="Tu contraseña actual">
                </div>
                <div class="form-group">
                    <label class="form-label">Nueva Contraseña <span class="req">*</span> (mínimo 8 caracteres)</label>
                    <input type="password" name="new_password" id="pwd-new" class="form-input" required minlength="8" placeholder="Nueva contraseña">
                </div>
                <div class="form-group">
                    <label class="form-label">Confirmar Nueva Contraseña <span class="req">*</span></label>
                    <input type="password" name="new_password_confirmation" id="pwd-confirm" class="form-input" required minlength="8" placeholder="Repite la nueva contraseña">
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
        // ── Tabs Switching ─────────────────────────────────────────────────────────
        function switchTab(tabName) {
            document.querySelectorAll('.tab-pane').forEach(function(p) { p.classList.remove('active'); });
            document.querySelectorAll('.tab-btn').forEach(function(b) { b.classList.remove('active'); });

            var pane = document.getElementById('pane-' + tabName);
            var btn  = document.getElementById('tab-btn-' + tabName);
            if (pane) pane.classList.add('active');
            if (btn) btn.classList.add('active');

            // Update URL without reload
            var url = new URL(window.location);
            url.searchParams.set('tab', tabName);
            window.history.replaceState({}, '', url);
        }

        // ── Applications Filtering ────────────────────────────────────────────────
        function filterApps(group) {
            document.querySelectorAll('.filter-pill').forEach(function(p) { p.classList.remove('active'); });
            if (event && event.target && event.target.classList.contains('filter-pill')) {
                event.target.classList.add('active');
            }

            var cards = document.querySelectorAll('.app-card');
            cards.forEach(function(c) {
                if (group === 'all' || c.getAttribute('data-group') === group) {
                    c.style.display = 'flex';
                } else {
                    c.style.display = 'none';
                }
            });
        }

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

        // ── CV File Drag & Drop ───────────────────────────────────────────────────
        var selectedCvFile = null;
        var dropzone = document.getElementById('cv-dropzone');

        if (dropzone) {
            ['dragenter', 'dragover'].forEach(function(evt) {
                dropzone.addEventListener(evt, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.add('dragover');
                });
            });
            ['dragleave', 'drop'].forEach(function(evt) {
                dropzone.addEventListener(evt, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.remove('dragover');
                });
            });
            dropzone.addEventListener('drop', function(e) {
                var files = e.dataTransfer.files;
                if (files.length) {
                    var file = files[0];
                    if (file.type !== 'application/pdf' && !file.name.toLowerCase().endsWith('.pdf')) {
                        showToast('Solo se permiten archivos en formato PDF.', false);
                        return;
                    }
                    if (file.size > 5 * 1024 * 1024) {
                        showToast('El archivo no debe exceder los 5MB.', false);
                        return;
                    }
                    selectedCvFile = file;
                    showSelectedCvPreview(file);
                }
            });
        }

        function handleCvFileSelect(input) {
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
                selectedCvFile = file;
                showSelectedCvPreview(file);
            }
        }

        function showSelectedCvPreview(file) {
            var prev = document.getElementById('selected-cv-preview');
            var name = document.getElementById('preview-file-name');
            var size = document.getElementById('preview-file-size');
            if (prev) {
                name.textContent = file.name;
                size.textContent = (file.size / 1024).toFixed(1) + ' KB';
                prev.style.display = 'flex';
            }
        }

        function cancelCvFileSelect() {
            selectedCvFile = null;
            var input = document.getElementById('input-cv-file');
            if (input) input.value = '';
            var prev = document.getElementById('selected-cv-preview');
            if (prev) prev.style.display = 'none';
        }

        function submitCvUpload() {
            if (!selectedCvFile) {
                showToast('Por favor selecciona un archivo PDF primero.', false);
                return;
            }

            var btn = document.getElementById('btn-submit-cv');
            var txt = document.getElementById('btn-submit-cv-text');
            btn.disabled = true;
            txt.textContent = 'Subiendo currículum...';

            var formData = new FormData();
            formData.append('cv', selectedCvFile);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch('{{ route("student.cv.upload") }}', {
                method: 'POST',
                body: formData,
                headers: { 'Accept': 'application/json' }
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                btn.disabled = false;
                txt.textContent = 'Subir y Registrar CV';
                if (data.success) {
                    showToast(data.message || 'CV subido exitosamente.');
                    setTimeout(function() { window.location.reload(); }, 900);
                } else {
                    showToast(data.message || 'Error al subir el CV.', false);
                }
            })
            .catch(function(err) {
                btn.disabled = false;
                txt.textContent = 'Subir y Registrar CV';
                showToast('Error de conexión al subir CV.', false);
            });
        }

        function confirmDeleteCv(id) {
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
                    setTimeout(function() { window.location.reload(); }, 800);
                } else {
                    showToast(data.message || 'Error al eliminar CV.', false);
                }
            })
            .catch(function() {
                showToast('Error de red al eliminar el currículum.', false);
            });
        }

        // ── Avatar Upload Preview ─────────────────────────────────────────────────
        function handleAvatarFileSelect(input) {
            if (input.files && input.files[0]) {
                var file = input.files[0];
                var formData = new FormData();
                formData.append('avatar', file);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                showToast('Subiendo nueva foto de perfil...');

                fetch('{{ route("student.avatar.update") }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 'Accept': 'application/json' }
                })
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    if (data.success) {
                        showToast(data.message || 'Foto actualizada con éxito.');
                        var container = document.getElementById('avatar-preview-container');
                        if (container && data.avatar_url) {
                            container.innerHTML = '<img src="' + data.avatar_url + '" class="avatar-preview-img" alt="Avatar">';
                        }
                    } else {
                        showToast(data.message || 'Error al subir la imagen.', false);
                    }
                })
                .catch(function() {
                    showToast('Error de conexión al subir foto.', false);
                });
            }
        }

        // ── Profile Edit Form ─────────────────────────────────────────────────────
        function handleProgramSelect(select) {
            var customWrap = document.getElementById('custom-career-wrap');
            var careerInput = document.getElementById('input-career');
            if (select.value && select.value !== 'other') {
                var selText = select.options[select.selectedIndex].text;
                if (careerInput) careerInput.value = selText;
            } else if (select.value === 'other') {
                if (careerInput) {
                    careerInput.value = '';
                    careerInput.focus();
                }
            }
        }

        function submitProfileEdit() {
            var form = document.getElementById('form-profile-edit');
            var btn = document.getElementById('btn-save-profile');
            var txt = document.getElementById('btn-save-profile-text');

            btn.disabled = true;
            txt.textContent = 'Guardando información...';

            var formData = new FormData(form);

            fetch('{{ route("student.profile.update") }}', {
                method: 'POST',
                body: formData,
                headers: { 'Accept': 'application/json' }
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                btn.disabled = false;
                txt.textContent = 'Guardar Cambios del Perfil';
                if (data.success) {
                    showToast(data.message || 'Perfil actualizado exitosamente.');
                } else {
                    showToast(data.message || 'Error al actualizar el perfil.', false);
                }
            })
            .catch(function() {
                btn.disabled = false;
                txt.textContent = 'Guardar Cambios del Perfil';
                showToast('Error de red al guardar los datos.', false);
            });
        }

        // ── Password Modal ────────────────────────────────────────────────────────
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
            if (e.key === 'Escape') closePasswordModal();
        });
    </script>
</body>
</html>
