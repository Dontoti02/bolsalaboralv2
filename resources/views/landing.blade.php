<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $config['application_name'] ?? 'Bolsa Laboral' }} — Encuentra tu Oportunidad</title>
    <meta name="description" content="Explora cientos de ofertas laborales verificadas. Postula en un clic.">
    <link rel="icon" href="{{ $config['favicon'] ?? '/assets/favicon.png' }}">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL@20..48,100..700,0..1&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --pri:   {{ $config['primary_color']            ?? '#002741' }};
            --sec:   {{ $config['secondary_color']          ?? '#006b60' }};
            --secc:  {{ $config['secondary_container_color']?? '#7df7e4' }};
            --acc:   {{ $config['accent_color']             ?? '#f97316' }};
            --bg:    #f3f4f6;
            --sur:   #ffffff;
            --bor:   #e5e7eb;
            --txt:   #111827;
            --tv:    #4b5563;
            --tm:    #9ca3af;
            --rad:   12px;
        }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--txt);-webkit-font-smoothing:antialiased}
        a{text-decoration:none;color:inherit}
        button{font-family:inherit;cursor:pointer}
        img{display:block;max-width:100%}
        .material-symbols-outlined{font-variation-settings:'FILL'0,'wght'400,'GRAD'0,'opsz'24;vertical-align:middle;line-height:1}
        .filled{font-variation-settings:'FILL'1,'wght'400,'GRAD'0,'opsz'24}
        .hidden{display:none!important}

        /* ── TOPBAR ── */
        .topbar{background:var(--pri);color:#fff;font-size:12px;padding:5px 0;border-bottom:1px solid rgba(255,255,255,.1)}
        .topbar-inner{max-width:1400px;margin:0 auto;padding:0 20px;display:flex;align-items:center;justify-content:flex-end;gap:16px}
        .topbar-link{color:rgba(255,255,255,.8);font-size:12px;display:flex;align-items:center;gap:4px;transition:color .15s}
        .topbar-link:hover{color:#fff}

        /* ── NAVBAR ── */
        .navbar{background:var(--sur);border-bottom:1px solid var(--bor);position:sticky;top:0;z-index:100;box-shadow:0 1px 3px rgba(0,0,0,.06)}
        .navbar-inner{max-width:1400px;margin:0 auto;padding:0 20px;height:66px;display:flex;align-items:center;gap:16px}
        .brand{display:flex;align-items:center;gap:8px;font-family:'Plus Jakarta Sans',sans-serif;font-size:20px;font-weight:800;color:var(--pri);flex-shrink:0;margin-right:8px}
        .brand img{height:34px;width:auto}

        /* Hero search bar (inline in navbar) */
        .nav-search{flex:1;max-width:680px;display:flex;border:2px solid var(--bor);border-radius:50px;overflow:hidden;background:#fff;transition:border-color .2s}
        .nav-search:focus-within{border-color:var(--pri)}
        .nav-search-field{flex:1;display:flex;align-items:center;border-right:1px solid var(--bor)}
        .nav-search-field:last-of-type{border-right:none}
        .nav-search-ico{padding:0 10px 0 16px;color:var(--tm);font-size:20px;flex-shrink:0}
        .nav-search-input{flex:1;border:none;outline:none;font-size:14px;font-family:'Inter',sans-serif;color:var(--txt);background:transparent;padding:12px 10px 12px 0;width:100%;min-width:0}
        .nav-search-input::placeholder{color:var(--tm)}
        .nav-search-btn{flex-shrink:0;display:flex;align-items:center;justify-content:center;width:52px;height:52px;background:var(--pri);border:none;color:#fff;transition:background .15s;margin:-1px -1px -1px 0}
        .nav-search-btn:hover{background:#0a3452}
        .nav-search-btn .material-symbols-outlined{font-size:22px}

        .navbar-spacer{flex:1}
        .navbar-actions{display:flex;align-items:center;gap:8px;flex-shrink:0}
        .btn-login{padding:9px 22px;border:1.5px solid var(--pri);color:var(--pri);background:transparent;border-radius:50px;font-size:14px;font-weight:600;transition:all .2s}
        .btn-login:hover{background:var(--pri);color:#fff}
        .btn-empresa{padding:10px 24px;background:var(--pri);color:#fff;border:none;border-radius:50px;font-size:14px;font-weight:600;transition:all .2s;display:flex;align-items:center;gap:6px}
        .btn-empresa:hover{opacity:.88}

        /* ── FILTERS BAR ── */
        .filters-bar{background:var(--sur);border-bottom:1px solid var(--bor);padding:10px 0;overflow-x:auto;-webkit-overflow-scrolling:touch}
        .filters-bar-inner{max-width:1400px;margin:0 auto;padding:0 20px;display:flex;align-items:center;gap:8px;white-space:nowrap}
        .filter-pill{display:inline-flex;align-items:center;gap:5px;padding:7px 14px;background:var(--sur);border:1.5px solid var(--bor);border-radius:50px;font-size:13px;font-weight:500;color:var(--tv);cursor:pointer;transition:all .15s;white-space:nowrap}
        .filter-pill:hover,.filter-pill.active{border-color:var(--pri);color:var(--pri);background:rgba(0,39,65,.04)}
        .filter-pill .material-symbols-outlined{font-size:16px}
        .filter-pill select{border:none;outline:none;background:transparent;font-size:13px;font-weight:500;color:inherit;cursor:pointer;padding:0;-webkit-appearance:none;appearance:none}

        /* ── MAIN LAYOUT ── */
        .main{max-width:1400px;margin:0 auto;padding:20px;display:flex;gap:20px;align-items:flex-start}
        .col-list{width:400px;flex-shrink:0}
        .col-detail{flex:1;min-width:0;position:sticky;top:86px;max-height:calc(100vh - 100px)}

        /* ── RESULTS HEADER ── */
        .results-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;flex-wrap:wrap;gap:8px}
        .results-count{font-size:14px;color:var(--tv)}
        .results-count strong{color:var(--txt);font-weight:700}

        /* ── JOB LIST ── */
        .job-list{display:flex;flex-direction:column;gap:0;background:var(--sur);border:1px solid var(--bor);border-radius:var(--rad);overflow:hidden}
        .job-item{padding:16px;border-bottom:1px solid var(--bor);cursor:pointer;transition:background .15s;position:relative}
        .job-item:last-child{border-bottom:none}
        .job-item:hover{background:#f9fafb}
        .job-item.active{background:rgba(0,39,65,.04);border-left:3px solid var(--pri)}
        .job-item-urgent{position:absolute;top:10px;right:10px;font-size:10px;font-weight:700;color:var(--acc);text-transform:uppercase;letter-spacing:.05em}
        .job-item-header{display:flex;align-items:flex-start;gap:10px;margin-bottom:6px}
        .job-item-logo{width:38px;height:38px;border-radius:8px;overflow:hidden;border:1px solid var(--bor);background:var(--bg);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:12px;font-weight:700;color:var(--pri)}
        .job-item-logo img{width:100%;height:100%;object-fit:contain;padding:2px}
        .job-item-meta{flex:1;min-width:0}
        .job-item-title{font-size:14px;font-weight:600;color:var(--txt);line-height:1.35;margin-bottom:3px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
        .job-item:hover .job-item-title,.job-item.active .job-item-title{color:var(--pri)}
        .job-item-company{display:flex;align-items:center;gap:4px;font-size:12px;color:var(--tv)}
        .job-item-company .material-symbols-outlined{font-size:14px;color:var(--sec)}
        .job-item-location{font-size:12px;color:var(--tm);margin-top:2px}
        .job-item-footer{display:flex;align-items:center;justify-content:space-between;margin-top:8px;gap:8px;flex-wrap:wrap}
        .job-item-time{font-size:11px;color:var(--tm)}
        .job-item-tags{display:flex;gap:4px;flex-wrap:wrap}
        .tag-xs{padding:2px 8px;border-radius:50px;font-size:10px;font-weight:500}
        .tag-pri{background:rgba(0,39,65,.07);color:var(--pri)}
        .tag-sec{background:rgba(0,107,96,.07);color:var(--sec)}
        .tag-acc{background:rgba(249,115,22,.1);color:#c2410c;font-weight:700}
        .btn-vista{padding:4px 12px;border:1px solid var(--bor);border-radius:50px;font-size:12px;color:var(--tv);background:transparent;transition:all .15s}
        .btn-vista:hover{border-color:var(--pri);color:var(--pri)}

        /* Skeletons */
        @keyframes shimmer{0%{background-position:-600px 0}100%{background-position:600px 0}}
        .skel{background:linear-gradient(90deg,#f0f2f4 25%,#e4e7ea 50%,#f0f2f4 75%);background-size:1200px 100%;animation:shimmer 1.3s ease-in-out infinite;border-radius:6px}

        /* ── LOAD MORE ── */
        .load-more-wrap{text-align:center;margin-top:16px}
        .btn-load-more{padding:11px 32px;background:transparent;border:1.5px solid var(--pri);color:var(--pri);border-radius:50px;font-size:14px;font-weight:600;transition:all .2s}
        .btn-load-more:hover{background:var(--pri);color:#fff}
        .no-more{font-size:13px;color:var(--tm);padding:8px}

        /* ── DETAIL PANEL ── */
        .detail-panel{background:var(--sur);border:1px solid var(--bor);border-radius:var(--rad);overflow-y:auto;max-height:calc(100vh - 100px);display:flex;flex-direction:column}
        .detail-empty{display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;padding:80px 40px;text-align:center;color:var(--tm)}
        .detail-empty .material-symbols-outlined{font-size:60px;color:var(--bor);margin-bottom:16px}
        .detail-empty p{font-size:15px}

        /* Detail header */
        .d-header{padding:24px 24px 20px;border-bottom:1px solid var(--bor);position:sticky;top:0;background:var(--sur);z-index:5}
        .d-company-row{display:flex;align-items:center;gap:14px;margin-bottom:14px}
        .d-logo{width:64px;height:64px;border-radius:14px;overflow:hidden;border:1px solid var(--bor);background:var(--bg);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:18px;font-weight:700;color:var(--pri)}
        .d-logo img{width:100%;height:100%;object-fit:contain;padding:5px}
        .d-company-info{flex:1;min-width:0}
        .d-title{font-size:20px;font-weight:700;color:var(--txt);line-height:1.3;margin-bottom:4px;font-family:'Plus Jakarta Sans',sans-serif}
        .d-company-name{font-size:14px;color:var(--pri);font-weight:600}
        .d-location{font-size:13px;color:var(--tv);margin-top:2px;display:flex;align-items:center;gap:4px}
        .d-location .material-symbols-outlined{font-size:15px}
        .d-verified{display:inline-flex;align-items:center;gap:4px;color:var(--sec);font-size:12px;font-weight:600;margin-top:4px}
        .d-verified .material-symbols-outlined{font-size:15px}
        .d-actions{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
        .btn-postular{display:inline-flex;align-items:center;gap:8px;padding:13px 32px;background:var(--pri);color:#fff;border:none;border-radius:50px;font-size:15px;font-weight:700;transition:all .2s;cursor:pointer}
        .btn-postular:hover{background:#0a3452;transform:translateY(-1px)}
        .btn-icon{width:40px;height:40px;border-radius:50%;border:1.5px solid var(--bor);background:transparent;display:flex;align-items:center;justify-content:center;color:var(--tv);transition:all .15s}
        .btn-icon:hover{border-color:var(--pri);color:var(--pri)}

        /* Detail body */
        .d-body{padding:24px;display:flex;flex-direction:column;gap:20px}
        .d-chips{display:flex;flex-wrap:wrap;gap:8px}
        .d-chip{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:50px;font-size:13px;font-weight:500;border:1px solid var(--bor);color:var(--tv)}
        .d-chip .material-symbols-outlined{font-size:15px;color:var(--sec)}
        .d-section-title{font-size:13px;font-weight:700;color:var(--txt);text-transform:uppercase;letter-spacing:.06em;margin-bottom:10px}
        .d-text{font-size:14px;line-height:1.75;color:var(--tv);white-space:pre-line}
        .d-salary-box{display:flex;align-items:center;gap:14px;padding:16px;background:rgba(0,107,96,.06);border:1px solid rgba(0,107,96,.15);border-radius:10px}
        .d-salary-box .material-symbols-outlined{font-size:26px;color:var(--sec)}
        .d-salary-lbl{font-size:11px;font-weight:700;color:var(--tm);text-transform:uppercase;letter-spacing:.06em}
        .d-salary-val{font-size:20px;font-weight:700;color:var(--sec)}
        .d-deadline{display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--tv);background:var(--bg);border-radius:50px;padding:6px 14px;border:1px solid var(--bor)}
        .d-deadline .material-symbols-outlined{font-size:15px;color:var(--acc)}

        /* ── PLACE DROPDOWN ── */
        .place-wrap{position:relative;flex:0 0 180px;display:flex;align-items:center}
        .place-dropdown{position:absolute;top:calc(100% + 8px);left:-12px;right:-12px;background:#fff;border:1px solid var(--bor);border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,.1);z-index:500;overflow:hidden;max-height:280px;overflow-y:auto;display:none}
        .place-dropdown.open{display:block}
        .place-option{padding:10px 16px;font-size:14px;color:var(--tv);cursor:pointer;display:flex;align-items:center;gap:8px;transition:background .12s}
        .place-option:hover,.place-option.focused{background:rgba(0,39,65,.05);color:var(--pri)}
        .place-option .material-symbols-outlined{font-size:16px;color:var(--tm)}
        .place-empty{padding:12px 16px;font-size:13px;color:var(--tm);text-align:center}

        /* ── Q SEARCH DROPDOWN ── */
        .q-wrap{position:relative;flex:1;display:flex;align-items:center}
        .q-dropdown{position:absolute;top:calc(100% + 8px);left:-12px;right:-12px;background:#fff;border:1px solid var(--bor);border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,.1);z-index:500;overflow:hidden;max-height:280px;overflow-y:auto;display:none}
        .q-dropdown.open{display:block}
        .q-option{padding:10px 16px;font-size:14px;color:var(--tv);cursor:pointer;display:flex;align-items:center;gap:8px;transition:background .12s}
        .q-option:hover,.q-option.focused{background:rgba(0,39,65,.05);color:var(--pri)}
        .q-option .material-symbols-outlined{font-size:16px;color:var(--tm)}
        .q-empty{padding:12px 16px;font-size:13px;color:var(--tm);text-align:center}

        /* ── MOBILE ── */
        @media(max-width:900px){
            .col-list{width:100%}
            .col-detail{display:none;position:fixed;inset:0;z-index:200;max-height:100vh;top:0}
            .col-detail.open{display:block}
            .detail-panel{border-radius:0;max-height:100vh;min-height:100vh}
            .d-close-mobile{display:flex}
            .main{flex-direction:column;padding:12px}
            .col-list{width:100%}
        }
        .d-close-mobile{display:none;align-items:center;gap:8px;padding:14px 16px;border-bottom:1px solid var(--bor);cursor:pointer;color:var(--tv);font-size:14px;font-weight:500;background:var(--sur)}
        .d-close-mobile .material-symbols-outlined{font-size:20px}

        /* ── MODAL POSTULAR ── */
        .modal-overlay{position:fixed;inset:0;z-index:300;display:none;align-items:center;justify-content:center;padding:20px;background:rgba(0,0,0,.5);backdrop-filter:blur(4px)}
        .modal-overlay.open{display:flex}
        .modal-box{background:var(--sur);border-radius:20px;width:100%;max-width:400px;padding:32px;box-shadow:0 24px 64px rgba(0,0,0,.2);text-align:center;animation:scaleIn .25s ease both}
        @keyframes scaleIn{from{opacity:0;transform:scale(.94)}to{opacity:1;transform:scale(1)}}
        .modal-icon{font-size:52px;color:var(--pri);margin-bottom:16px}
        .modal-title{font-size:22px;font-weight:800;font-family:'Plus Jakarta Sans',sans-serif;margin-bottom:8px}
        .modal-sub{font-size:14px;color:var(--tv);margin-bottom:24px;line-height:1.6}
        .modal-actions{display:flex;flex-direction:column;gap:10px}
        .modal-btn-pri{padding:14px;background:var(--pri);color:#fff;border:none;border-radius:12px;font-size:15px;font-weight:700;transition:all .2s;display:flex;align-items:center;justify-content:center;gap:8px}
        .modal-btn-pri:hover{opacity:.9}
        .modal-btn-sec{padding:13px;background:transparent;color:var(--tv);border:1.5px solid var(--bor);border-radius:12px;font-size:14px;font-weight:500;transition:all .15s}
        .modal-btn-sec:hover{border-color:var(--pri);color:var(--pri)}

        /* ── FOOTER ── */
        .footer{background:var(--sur);border-top:1px solid var(--bor);padding:20px;margin-top:32px}
        .footer-inner{max-width:1400px;margin:0 auto;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px}
        .footer-brand{font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;color:var(--pri);font-size:16px;display:flex;align-items:center;gap:8px}
        .footer-brand img{height:28px;width:auto}
        .footer-copy{font-size:12px;color:var(--tm)}
        .footer-links{display:flex;gap:16px}
        .footer-link{font-size:13px;color:var(--tv);transition:color .15s}
        .footer-link:hover{color:var(--pri)}

        /* Scrollbar thin */
        .detail-panel::-webkit-scrollbar{width:5px}
        .detail-panel::-webkit-scrollbar-track{background:transparent}
        .detail-panel::-webkit-scrollbar-thumb{background:var(--bor);border-radius:3px}

        @media(max-width:640px){
            .navbar-inner{gap:8px;padding:0 12px}
            .brand{font-size:17px}
            .nav-search{display:none}
            .main{padding:8px}
        }
        /* ── PROFILE MENU ── */
        .profile-menu-wrap{position:relative}
        .profile-trigger{display:inline-flex;align-items:center;gap:8px;padding:6px 12px;border:1.5px solid var(--bor);border-radius:50px;background:#fff;cursor:pointer;transition:all .15s;font-family:'Inter',sans-serif}
        .profile-trigger:hover{border-color:var(--pri);background:rgba(0,39,65,.03)}
        .profile-avatar{width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--pri),#0a3452);color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0}
        .profile-avatar.lg{width:44px;height:44px;font-size:17px;flex-shrink:0}
        .profile-name{font-size:14px;font-weight:600;color:var(--txt);max-width:100px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
        .profile-dropdown{position:absolute;top:calc(100% + 10px);right:0;background:#fff;border:1px solid var(--bor);border-radius:16px;box-shadow:0 12px 40px rgba(0,0,0,.12);min-width:240px;z-index:500;overflow:hidden;display:none;animation:scaleIn .2s ease both;transform-origin:top right}
        .profile-dropdown.open{display:block}
        .profile-dropdown-header{display:flex;align-items:center;gap:12px;padding:16px;background:rgba(0,39,65,.03)}
        .profile-dropdown-divider{height:1px;background:var(--bor);margin:4px 0}
        .profile-dropdown-item{display:flex;align-items:center;gap:10px;width:100%;padding:10px 16px;border:none;background:transparent;color:var(--txt);font-size:14px;font-family:'Inter',sans-serif;cursor:pointer;transition:background .12s;text-align:left}
        .profile-dropdown-item:hover{background:rgba(0,39,65,.05);color:var(--pri)}
        .profile-dropdown-item .material-symbols-outlined{font-size:18px;color:var(--tm)}
        .cv-badge{margin-left:auto;background:var(--pri);color:#fff;border-radius:50px;font-size:11px;font-weight:700;padding:1px 7px;min-width:20px;text-align:center}

        /* ── STUDENT MODALS ── */
        .s-modal{position:fixed;inset:0;z-index:400;display:none;align-items:center;justify-content:center;padding:20px;background:rgba(0,0,0,.45);backdrop-filter:blur(4px)}
        .s-modal.open{display:flex}
        .s-modal-box{background:#fff;border-radius:20px;width:100%;max-width:520px;max-height:90vh;overflow-y:auto;box-shadow:0 24px 64px rgba(0,0,0,.18)}
        .s-modal-header{padding:20px 24px 14px;border-bottom:1px solid var(--bor);display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;background:#fff;border-radius:20px 20px 0 0;z-index:5}
        .s-modal-title{font-size:18px;font-weight:700;font-family:'Plus Jakarta Sans',sans-serif;color:var(--txt)}
        .s-modal-close{width:32px;height:32px;border-radius:50%;border:none;background:var(--bg);display:flex;align-items:center;justify-content:center;color:var(--tv);cursor:pointer;transition:background .15s}
        .s-modal-close:hover{background:var(--bor)}
        .s-modal-body{padding:24px;display:flex;flex-direction:column;gap:16px}
        .s-form-group{display:flex;flex-direction:column;gap:6px}
        .s-form-label{font-size:12px;font-weight:700;color:var(--tv);text-transform:uppercase;letter-spacing:.06em}
        .s-form-input{padding:11px 14px;border:1.5px solid var(--bor);border-radius:10px;font-size:14px;font-family:'Inter',sans-serif;color:var(--txt);outline:none;transition:border-color .2s;width:100%;background:#fff}
        .s-form-input:focus{border-color:var(--pri);box-shadow:0 0 0 3px rgba(0,39,65,.08)}
        .s-form-select{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7078' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;padding-right:36px}
        .s-form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
        .s-form-row-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px}
        .btn-s-primary{padding:13px 24px;background:var(--pri);color:#fff;border:none;border-radius:10px;font-size:15px;font-weight:700;font-family:'Inter',sans-serif;cursor:pointer;transition:all .2s;display:flex;align-items:center;justify-content:center;gap:8px;width:100%}
        .btn-s-primary:hover{opacity:.9}
        .s-alert{padding:12px 16px;border-radius:10px;font-size:13px;font-weight:500;display:none}
        .s-alert.success{background:rgba(0,107,96,.08);color:var(--sec);border:1px solid rgba(0,107,96,.2)}
        .s-alert.error{background:rgba(220,38,38,.06);color:#dc2626;border:1px solid rgba(220,38,38,.2)}

        /* CVs list */
        .cv-item{display:flex;align-items:center;gap:12px;padding:12px 14px;border:1px solid var(--bor);border-radius:10px;background:#fff;transition:background .15s}
        .cv-item:hover{background:var(--bg)}
        .cv-item-icon{width:36px;height:36px;background:rgba(220,38,38,.08);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#dc2626;flex-shrink:0}
        .cv-item-info{flex:1;min-width:0}
        .cv-item-name{font-size:13px;font-weight:600;color:var(--txt);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .cv-item-date{font-size:11px;color:var(--tm)}
        .cv-item-actions{display:flex;gap:6px;flex-shrink:0}
        .btn-cv-action{padding:5px 10px;border-radius:6px;border:1px solid var(--bor);background:transparent;font-size:12px;cursor:pointer;display:flex;align-items:center;gap:4px;color:var(--tv);transition:all .15s}
        .btn-cv-action:hover{border-color:var(--pri);color:var(--pri)}
        .btn-cv-delete{color:#dc2626}
        .btn-cv-delete:hover{border-color:#dc2626;color:#dc2626;background:rgba(220,38,38,.05)}
        .cv-upload-area{border:2px dashed var(--bor);border-radius:12px;padding:28px;text-align:center;cursor:pointer;transition:all .2s}
        /* ── WARNING BANNER ── */
        .warning-banner{background:#fffbeb;border-bottom:1px solid #fde68a;padding:12px 20px;transition:all .3s}
        .warning-banner-inner{max-width:1400px;margin:0 auto;display:flex;align-items:center;justify-content:between;gap:16px;flex-wrap:wrap}
        .warning-banner-msg{font-size:13.5px;color:#92400e;font-weight:500;display:flex;align-items:center;gap:10px;flex:1}
        .warning-banner-msg .material-symbols-outlined{color:#d97706;font-size:22px}
        .warning-banner-actions{display:flex;align-items:center;gap:10px}
        .btn-warning-action{padding:7px 16px;font-size:13px;font-weight:700;border-radius:6px;cursor:pointer;font-family:'Inter',sans-serif}
        .btn-warning-pri{background:#d97706;color:#fff;border:none;transition:background .15s}
        .btn-warning-pri:hover{background:#b45309}
        .btn-warning-sec{background:transparent;color:#78350f;border:1px solid #f59e0b;transition:all .15s}
        .btn-warning-sec:hover{background:rgba(217,119,6,.06)}
        .warning-banner.hide{display:none}

        /* ── SIMILAR JOBS ── */
        .similar-card{display:flex;align-items:center;gap:12px;padding:12px;border:1px solid var(--bor);border-radius:10px;cursor:pointer;transition:all .15s;background:#fff}
        .similar-card:hover{border-color:var(--pri);background:#f9fafb}
        .similar-card-logo{width:36px;height:36px;border-radius:6px;overflow:hidden;border:1px solid var(--bor);background:var(--bg);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:11px;font-weight:700;color:var(--pri)}
        .similar-card-logo img{width:100%;height:100%;object-fit:contain;padding:2px}
        .similar-card-info{flex:1;min-width:0}
        .similar-card-title{font-size:13px;font-weight:600;color:var(--txt);margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .similar-card-company{font-size:11px;color:var(--tv);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .similar-card-tag{padding:2px 8px;border-radius:50px;font-size:10px;font-weight:500;background:rgba(0,107,96,.06);color:var(--sec);white-space:nowrap}

        /* ── BADGES POSTULACIONES ── */
        .badge-app{padding:4px 10px;border-radius:50px;font-size:10.5px;font-weight:700;display:inline-block;text-align:center;text-transform:uppercase;letter-spacing:.03em}
        .badge-app.postulated{background:rgba(0,39,65,.08);color:var(--pri)}
        .badge-app.reviewed{background:rgba(217,119,6,.08);color:#d97706}
        .badge-app.selected{background:rgba(0,107,96,.08);color:var(--sec)}
        .badge-app.rejected{background:rgba(220,38,38,.08);color:#dc2626}
        .badge-app.finished{background:rgba(107,114,128,.08);color:#6b7280}

        /* Toast Notification */
        .toast-notify{position:fixed;bottom:24px;left:50%;transform:translateX(-50%) translateY(40px);background:rgba(0,39,65,0.95);color:#fff;padding:12px 28px;border-radius:50px;font-size:14px;font-weight:600;display:flex;align-items:center;gap:10px;box-shadow:0 12px 32px rgba(0,0,0,0.25);z-index:2000;opacity:0;transition:all .3s cubic-bezier(0.175, 0.885, 0.32, 1.275);backdrop-filter:blur(8px)}
        .toast-notify.show{transform:translateX(-50%) translateY(0);opacity:1}

        /* ── APPLY LOADING ── */
        .apply-loading{display:none;flex-direction:column;align-items:center;gap:12px;padding:20px 0}
        .apply-loading.show{display:flex}
        .apply-progress-wrap{width:100%;max-width:280px}
        .apply-progress-bar{width:100%;height:8px;background:var(--bg);border-radius:50px;overflow:hidden}
        .apply-progress-fill{height:100%;background:linear-gradient(90deg,var(--pri),var(--sec));border-radius:50px;width:0;transition:width .3s ease}
        .apply-progress-text{font-size:13px;font-weight:600;color:var(--pri);text-align:center;margin-top:4px}
        .apply-loading-icon{width:48px;height:48px;border-radius:50%;background:rgba(0,39,65,.08);display:flex;align-items:center;justify-content:center;animation:pulse 1.5s ease-in-out infinite}
        @keyframes pulse{0%,100%{transform:scale(1);opacity:1}50%{transform:scale(1.1);opacity:.7}}
        .apply-loading-icon .material-symbols-outlined{font-size:24px;color:var(--pri);animation:spin 1s linear infinite}
        @keyframes spin{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}
        .btn-postular:disabled{opacity:.6;cursor:not-allowed;transform:none}
        .tag-applied{background:rgba(249,115,22,.1);color:#c2410c;font-weight:700}

        /* ── BUSCADOR MÓVIL DEDICADO ── */
        .mobile-search-bar {
            display: none;
            background: var(--sur);
            border-bottom: 1px solid var(--bor);
            padding: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,.03);
        }
        .mobile-search-field {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--bg);
            border: 1.5px solid var(--bor);
            border-radius: 12px;
            padding: 10px 14px;
            margin-bottom: 10px;
            transition: border-color .15s;
        }
        .mobile-search-field:focus-within {
            border-color: var(--pri);
            background: #fff;
        }
        .mobile-search-field span {
            color: var(--tm);
            font-size: 20px;
        }
        .mobile-search-field input {
            border: none;
            outline: none;
            background: transparent;
            font-size: 14px;
            font-family: inherit;
            color: var(--txt);
            width: 100%;
        }
        .mobile-search-btn {
            width: 100%;
            background: var(--pri);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-size: 14px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: opacity .15s;
        }
        .mobile-search-btn:active {
            opacity: .8;
        }

        /* ── MEJORAS MÓVILES EN MEDIA QUERIES ── */
        @media(max-width:640px) {
            .mobile-search-bar {
                display: block;
            }
            .warning-banner {
                padding: 10px 14px;
            }
            .warning-banner-inner {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
            }
            .btn-warning-action {
                width: 100%;
                text-align: center;
            }
            /* Campanita de notificaciones en móvil */
            #student-notifications-menu {
                width: calc(100vw - 24px) !important;
                right: -12px !important;
            }
            /* Ajustar paddings de la lista para aprovechar espacio */
            .main {
                padding: 10px 6px !important;
            }
            .job-item {
                padding: 16px !important;
                border-radius: 12px !important;
            }
        }

    </style>
</head>
<body>

{{-- ══ TOPBAR ══ --}}
<div class="topbar">
    <div class="topbar-inner">
        @if($authUser)
        <span class="topbar-link">
            <span class="material-symbols-outlined" style="font-size:14px;">person</span>
            Bienvenido, {{ $authUser->person->names ?? $authUser->email }}
        </span>
        @elseif(!request()->has('offer'))
        <a href="#empresas" class="topbar-link">
            <span class="material-symbols-outlined" style="font-size:14px;">domain</span>
            ¿Eres una empresa? Publica ofertas
        </a>
        <span style="color:rgba(255,255,255,.3)">|</span>
        <a href="{{ route('login') }}" class="topbar-link">
            <span class="material-symbols-outlined" style="font-size:14px;">person</span>
            Acceso para postulantes
        </a>
        @endif
    </div>
</div>

{{-- ══ NAVBAR ══ --}}
<nav class="navbar">
    <div class="navbar-inner">
        {{-- Brand --}}
        <a href="{{ route('landing') }}" class="brand">
            @if(!empty($config['logo']))
                <img src="{{ $config['logo'] }}" alt="{{ $config['application_name'] ?? 'Bolsa Laboral' }}">
            @else
                <span class="material-symbols-outlined filled" style="font-size:28px;color:var(--pri)">work</span>
            @endif
            <span style="display:none;display:block;">{{ $config['application_name'] ?? 'Bolsa Laboral' }}</span>
        </a>

        {{-- Search bar --}}
        <div class="nav-search">
            <div class="nav-search-field q-wrap" id="q-wrap">
                <span class="material-symbols-outlined nav-search-ico">work</span>
                <input id="nav-search-q" type="text" class="nav-search-input"
                    placeholder="Cargo, categoría o empresa..."
                    autocomplete="off"
                    onkeydown="handleQKey(event)"
                    oninput="filterQDropdown(this.value)"
                    onfocus="openQDropdown()"
                    aria-label="Cargo, categoría o empresa"
                    aria-autocomplete="list"
                    aria-haspopup="listbox">
                <div class="q-dropdown" id="q-dropdown" role="listbox">
                    @if($categories->count() > 0)
                        <div style="padding:6px 12px;font-size:11px;font-weight:700;color:var(--tm);background:var(--bg);text-transform:uppercase;letter-spacing:.05em">Categorías</div>
                        @foreach($categories as $cat)
                        <div class="q-option" role="option" onclick="selectQ('{{ addslashes($cat->name) }}')" data-q="{{ strtolower($cat->name) }}">
                            <span class="material-symbols-outlined">category</span>
                            {{ $cat->name }}
                        </div>
                        @endforeach
                    @endif
                    @if($availableTitles->count() > 0)
                        <div style="padding:6px 12px;font-size:11px;font-weight:700;color:var(--tm);background:var(--bg);text-transform:uppercase;letter-spacing:.05em">Cargos Populares</div>
                        @foreach($availableTitles as $title)
                        <div class="q-option" role="option" onclick="selectQ('{{ addslashes($title) }}')" data-q="{{ strtolower($title) }}">
                            <span class="material-symbols-outlined">work</span>
                            {{ $title }}
                        </div>
                        @endforeach
                    @endif
                    @if(isset($availableCompanies) && $availableCompanies->count() > 0)
                        <div style="padding:6px 12px;font-size:11px;font-weight:700;color:var(--tm);background:var(--bg);text-transform:uppercase;letter-spacing:.05em">Empresas</div>
                        @foreach($availableCompanies as $company)
                        <div class="q-option" role="option" onclick="selectQ('{{ addslashes($company) }}')" data-q="{{ strtolower($company) }}">
                            <span class="material-symbols-outlined">business</span>
                            {{ $company }}
                        </div>
                        @endforeach
                    @endif
                    <div class="q-empty" style="display:none">No se encontraron sugerencias</div>
                </div>
            </div>
            <div class="nav-search-field place-wrap" id="place-wrap">
                <span class="material-symbols-outlined nav-search-ico">location_on</span>
                <input id="nav-search-loc" type="text" class="nav-search-input"
                    placeholder="Departamento"
                    autocomplete="off"
                    onkeydown="handlePlaceKey(event)"
                    oninput="filterPlaceDropdown(this.value)"
                    onfocus="openPlaceDropdown()"
                    aria-label="Departamento de trabajo"
                    aria-autocomplete="list"
                    aria-haspopup="listbox">
                <div class="place-dropdown" id="place-dropdown" role="listbox">
                    @if($availablePlaces->count() > 0)
                    <div style="padding:6px 12px;font-size:11px;font-weight:700;color:var(--tm);background:var(--bg);text-transform:uppercase;letter-spacing:.05em">Departamentos</div>
                    @endif
                    @forelse($availablePlaces as $place)
                    <div class="place-option" role="option" onclick="selectPlace('{{ addslashes($place) }}')" data-place="{{ strtolower($place) }}">
                        <span class="material-symbols-outlined">location_on</span>
                        {{ $place }}
                    </div>
                    @empty
                    <div class="place-empty">No hay departamentos disponibles</div>
                    @endforelse
                </div>
            </div>
            <button class="nav-search-btn" onclick="triggerSearch()" aria-label="Buscar">
                <span class="material-symbols-outlined">search</span>
            </button>
        </div>

        <div class="navbar-spacer"></div>

        {{-- Actions --}}
        <div class="navbar-actions">
            @if($authUser)
            {{-- Campanita de notificaciones --}}
            <div style="position:relative;">
                <button id="student-notifications-button" type="button" onclick="toggleStudentNotifMenu()" style="position:relative;width:40px;height:40px;display:flex;align-items:center;justify-content:center;color:var(--tv);background:transparent;border:none;border-radius:12px;cursor:pointer;transition:background .15s;" onmouseover="this.style.background='var(--bor)'" onmouseout="this.style.background='transparent'">
                    <span class="material-symbols-outlined">notifications</span>
                    <span id="student-notifications-badge" style="position:absolute;top:4px;right:4px;width:10px;height:10px;background:#dc2626;border-radius:50%;display:none;border:2px solid var(--sur);"></span>
                </button>
                <div id="student-notifications-menu" style="display:none;position:absolute;right:0;top:48px;width:320px;background:var(--sur);border:1px solid var(--bor);border-radius:16px;box-shadow:0 20px 40px rgba(0,0,0,.12);overflow:hidden;z-index:200;">
                    <div style="padding:16px 20px;border-bottom:1px solid var(--bor);display:flex;align-items:center;justify-content:space-between;">
                        <div>
                            <p style="font-weight:700;font-size:15px;color:var(--txt);margin:0;">Notificaciones</p>
                            <p style="font-size:12px;color:var(--tm);margin:4px 0 0 0;">Actividad reciente de tu cuenta</p>
                        </div>
                        <button onclick="markStudentNotifRead()" style="width:32px;height:32px;border-radius:50%;background:rgba(0,107,96,.1);color:var(--sec);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;" title="Marcar todo como leído">
                            <span class="material-symbols-outlined" style="font-size:18px;">done_all</span>
                        </button>
                    </div>
                    <div id="student-notifications-list" style="max-height:384px;overflow-y:auto;"></div>
                </div>
            </div>
            @if($authUser->rol_id == 4)
                <a href="/company/dashboard?tab=offers" class="btn-empresa" style="background:var(--sec);margin-right:12px;display:inline-flex;align-items:center;gap:6px;text-decoration:none;padding:8px 16px;border-radius:50px;color:#fff;font-size:13.5px;font-weight:700;">
                    <span class="material-symbols-outlined" style="font-size:18px">add_circle</span>
                    Publicar Oferta
                </a>
                @endif
                @if($authUser->rol_id == 1)
                <a href="/admin/dashboard" class="btn-empresa" style="background:var(--pri);margin-right:12px;display:inline-flex;align-items:center;gap:6px;text-decoration:none;padding:8px 16px;border-radius:50px;color:#fff;font-size:13.5px;font-weight:700;">
                    <span class="material-symbols-outlined" style="font-size:18px">dashboard</span>
                    Panel Admin
                </a>
                @endif
            {{-- Menú de perfil del estudiante --}}
            <div class="profile-menu-wrap" id="profile-menu-wrap">
                <button class="profile-trigger" id="profile-trigger" onclick="toggleProfileMenu()" aria-haspopup="true" aria-expanded="false">
                    @if($authUser->avatar)
                        <img src="{{ $authUser->avatar }}" class="profile-avatar" alt="Avatar" id="nav-avatar-img" style="object-fit:cover">
                    @else
                        <span class="profile-avatar" id="nav-avatar-init">{{ strtoupper(substr($authUser->person->names ?? $authUser->email, 0, 2)) }}</span>
                    @endif
                    <span class="profile-name">{{ explode(' ', $authUser->person->names ?? $authUser->email)[0] }}</span>
                    <span class="material-symbols-outlined" style="font-size:18px;">expand_more</span>
                </button>
                <div class="profile-dropdown" id="profile-dropdown">
                    <div class="profile-dropdown-header">
                        @if($authUser->avatar)
                            <img src="{{ $authUser->avatar }}" class="profile-avatar lg" alt="Avatar" id="drop-avatar-img" style="object-fit:cover">
                        @else
                            <span class="profile-avatar lg" id="drop-avatar-init">{{ strtoupper(substr($authUser->person->names ?? $authUser->email, 0, 2)) }}</span>
                        @endif
                        <div>
                            <p style="font-weight:600;font-size:14px;">{{ $authUser->person->names ?? $authUser->email }}</p>
                            <p style="font-size:12px;color:var(--tm);">{{ $authUser->email }}</p>
                        </div>
                    </div>
                    <div class="profile-dropdown-divider"></div>
                    <button class="profile-dropdown-item" onclick="openModal('modal-perfil');closeProfileMenu()">
                        <span class="material-symbols-outlined">manage_accounts</span> Editar perfil
                    </button>
                    @if($authUser->rol_id == 3)
                    <button class="profile-dropdown-item" onclick="openModal('modal-cvs');closeProfileMenu()">
                        <span class="material-symbols-outlined">description</span>
                        Mis CVs
                        @if($studentCvs->count())
                        <span class="cv-badge">{{ $studentCvs->count() }}</span>
                        @endif
                    </button>
                    <a href="{{ route('student.applications') }}" class="profile-dropdown-item">
                        <span class="material-symbols-outlined">assignment_turned_in</span>
                        Mis postulaciones
                        @if($studentApplications->count())
                        <span class="cv-badge" style="background:var(--sec)">{{ $studentApplications->count() }}</span>
                        @endif
                    </a>
                    @endif
                    <button class="profile-dropdown-item" onclick="openModal('modal-password');closeProfileMenu()">
                        <span class="material-symbols-outlined">lock</span> Cambiar contraseña
                    </button>
                    <div class="profile-dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}" style="margin:0">
                        @csrf
                        <button type="submit" class="profile-dropdown-item" style="color:#dc2626;width:100%;text-align:left">
                            <span class="material-symbols-outlined">logout</span> Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
            @elseif(!request()->has('offer'))
            <a href="{{ route('login') }}" class="btn-login">Ingresar</a>
            <a href="{{ route('login') }}#empresa" class="btn-empresa">
                <span class="material-symbols-outlined" style="font-size:18px">domain_add</span>
                Publicar empleo
            </a>
            @endif
        </div>
</nav>

@if(session('show_password_warning'))
<div class="warning-banner" id="pwd-warning-banner">
    <div class="warning-banner-inner">
        <div class="warning-banner-msg">
            <span class="material-symbols-outlined filled">security</span>
            <span><strong>¡Alerta de Seguridad!</strong> Estás usando una contraseña por defecto (tu DNI). Te recomendamos cambiarla por seguridad.</span>
        </div>
        <div class="warning-banner-actions">
            <button class="btn-warning-action btn-warning-pri" onclick="openModal('modal-password')">Cambiar ahora</button>
            <button class="btn-warning-action btn-warning-sec" onclick="dismissPwdWarning()">Más tarde</button>
        </div>
    </div>
</div>
@endif


{{-- ══ MOBILE SEARCH BAR ══ --}}
<div class="mobile-search-bar">
    <div class="mobile-search-field">
        <span class="material-symbols-outlined">work</span>
        <input id="mobile-search-q" type="text" placeholder="Cargo, categoría o empresa..." onkeydown="if(event.key === 'Enter') triggerSearch()">
    </div>
    <div class="mobile-search-field">
        <span class="material-symbols-outlined">location_on</span>
        <input id="mobile-search-loc" type="text" placeholder="Departamento..." onkeydown="if(event.key === 'Enter') triggerSearch()">
    </div>
    <button class="mobile-search-btn" onclick="triggerSearch()">
        <span class="material-symbols-outlined">search</span> Buscar
    </button>
</div>

{{-- ══ FILTERS BAR ══ --}}
<div class="filters-bar">
    <div class="filters-bar-inner">
        <div class="filter-pill">
            <span class="material-symbols-outlined">sort</span>
            <select id="filter-sort" onchange="applyFilters()">
                <option value="recent">Ordenar</option>
                <option value="recent">Más recientes</option>
                <option value="salary_desc">Mayor salario</option>
                <option value="salary_asc">Menor salario</option>
                <option value="title_asc">A-Z</option>
            </select>
        </div>
        <div class="filter-pill">
            <select id="filter-date-range" onchange="applyFilters()">
                <option value="">Fecha</option>
                <option value="today">Hoy</option>
                <option value="week">Esta semana</option>
                <option value="month">Este mes</option>
            </select>
        </div>
        <div class="filter-pill">
            <select id="filter-location" onchange="applyFilters()">
                <option value="">Lugar de trabajo</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-pill">
            <select id="filter-schedule" onchange="applyFilters()">
                <option value="">Jornada</option>
                @foreach($workSchedules as $ws)
                    <option value="{{ $ws->id }}">{{ $ws->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-pill">
            <select id="filter-contract" onchange="applyFilters()">
                <option value="">Contrato</option>
                @foreach($contractTypes as $ct)
                    <option value="{{ $ct->id }}">{{ $ct->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-pill">
            <select id="filter-category" onchange="applyFilters()">
                <option value="">Categoría</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-pill">
            <select id="filter-salary" onchange="applyFilters()">
                <option value="">Salario</option>
                <option value="under_1000">Menos de S/ 1,000</option>
                <option value="1000_to_2000">S/ 1,000 - 2,000</option>
                <option value="2000_to_4000">S/ 2,000 - 4,000</option>
                <option value="above_4000">Más de S/ 4,000</option>
            </select>
        </div>
        @if(count($categories) > 0)
        <button class="filter-pill" onclick="clearFilters()" style="color:var(--acc);border-color:var(--acc)">
            <span class="material-symbols-outlined" style="font-size:15px">close</span>
            Limpiar
        </button>
        @endif
    </div>
</div>

{{-- ══ MAIN LAYOUT ══ --}}
<main class="main" id="main-content">

    {{-- ── Lista de ofertas ── --}}
    <div class="col-list">
        <div class="results-header">
            <p class="results-count" id="results-count-label">Cargando...</p>
        </div>

        <div class="job-list" id="job-list">
            @for($i=0;$i<8;$i++)
            <div class="job-item">
                <div class="job-item-header">
                    <div class="job-item-logo"><div class="skel" style="width:38px;height:38px;border-radius:8px;"></div></div>
                    <div class="job-item-meta" style="flex:1">
                        <div class="skel" style="height:14px;width:75%;margin-bottom:6px;"></div>
                        <div class="skel" style="height:12px;width:50%;"></div>
                    </div>
                </div>
                <div class="skel" style="height:11px;width:40%;margin-bottom:6px;"></div>
                <div class="skel" style="height:11px;width:60%;"></div>
            </div>
            @endfor
        </div>

        <div class="load-more-wrap">
            <button id="load-more-btn" class="btn-load-more hidden" onclick="loadMoreOffers()">
                Ver más ofertas
            </button>
            <p id="no-more-label" class="no-more hidden"></p>
        </div>
    </div>

    {{-- ── Panel de detalle ── --}}
    <div class="col-detail" id="detail-col">
        <div class="detail-panel" id="detail-panel">
            <div class="d-close-mobile" onclick="closeDetail()">
                <span class="material-symbols-outlined">arrow_back</span>
                Volver a la lista
            </div>
            <div class="detail-empty" id="detail-empty">
                <span class="material-symbols-outlined filled">work</span>
                <p>Selecciona una oferta para ver los detalles</p>
            </div>
            <div id="detail-content" class="hidden">
                <div class="d-header">
                    <div class="d-company-row">
                        <div class="d-logo" id="d-logo-wrap"></div>
                        <div>
                            <h1 class="d-title" id="d-title">—</h1>
                            <div class="d-company-name" id="d-company">—</div>
                            <div class="d-location">
                                <span class="material-symbols-outlined">location_on</span>
                                <span id="d-location">—</span>
                            </div>
                            <div class="d-verified" id="d-verified-row">
                                <span class="material-symbols-outlined filled">verified</span>
                                Empresa verificada
                            </div>
                        </div>
                    </div>
                    <div class="d-actions">
                        @if($authUser && $authUser->rol_id == 2)
                            <div style="font-size:12px;color:var(--tm);font-weight:700;background:var(--bg);padding:10px 16px;border-radius:50px;border:1.5px solid var(--bor);display:flex;align-items:center;gap:6px">
                                <span class="material-symbols-outlined" style="font-size:16px;color:var(--pri)">visibility</span>
                                Vista Docente
                            </div>
                        @else
                            <button class="btn-postular" onclick="openPostularModal()">
                                <span class="material-symbols-outlined" style="font-size:18px">send</span>
                                Postularme
                            </button>
                            <button class="btn-icon" title="Guardar" onclick="openPostularModal()">
                                <span class="material-symbols-outlined" style="font-size:18px">bookmark_add</span>
                            </button>
                        @endif
                        <button class="btn-icon" title="Compartir" onclick="shareOffer()">
                            <span class="material-symbols-outlined" style="font-size:18px">share</span>
                        </button>
                    </div>
                </div>
                <div class="d-body">
                    <div id="d-chips" class="d-chips"></div>

                    <div class="d-salary-box" id="d-salary-box">
                        <span class="material-symbols-outlined filled">payments</span>
                        <div>
                            <p class="d-salary-lbl">Salario</p>
                            <p class="d-salary-val" id="d-salary">—</p>
                        </div>
                    </div>

                    <div>
                        <p class="d-section-title">Descripción del puesto</p>
                        <p class="d-text" id="d-description"></p>
                    </div>

                    <div>
                        <p class="d-section-title">Requisitos</p>
                        <p class="d-text" id="d-requirements"></p>
                    </div>

                    <div id="d-benefits-block" style="display:none">
                        <p class="d-section-title">Beneficios</p>
                        <p class="d-text" id="d-benefits"></p>
                    </div>

                    <div id="d-deadline-row" style="display:none">
                        <span class="d-deadline">
                            <span class="material-symbols-outlined">event</span>
                            <span id="d-deadline-text"></span>
                        </span>
                    </div>

                    {{-- ── Empleos similares ── --}}
                    <div id="d-similar-section" style="margin-top:24px;border-top:1px solid var(--bor);padding-top:20px">
                        <p class="d-section-title">Empleos similares</p>
                        <div id="d-similar-list" style="display:flex;flex-direction:column;gap:10px;margin-top:12px">
                            <!-- Se llena dinámicamente vía JS -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

{{-- ══ EMPRESA CTA ══ --}}
@if(!$authUser && !request()->has('offer'))
<section id="empresas" style="background:linear-gradient(160deg,var(--pri),#0a3452 50%,#004d45);padding:72px 20px;text-align:center;color:#fff;margin-top:40px">
    <div style="max-width:680px;margin:0 auto">
        <span class="material-symbols-outlined filled" style="font-size:56px;color:var(--secc);display:block;margin-bottom:16px">rocket_launch</span>
        <h2 style="font-family:'Plus Jakarta Sans',sans-serif;font-size:32px;font-weight:800;margin-bottom:12px">¿Eres una empresa?</h2>
        <p style="font-size:17px;color:rgba(255,255,255,.78);margin-bottom:32px;line-height:1.6">Publica tus ofertas laborales y conecta con cientos de candidatos calificados de nuestra institución. Rápido y sencillo.</p>
        <div style="display:flex;flex-wrap:wrap;gap:12px;justify-content:center">
            <a href="{{ route('login') }}#empresa" style="display:inline-flex;align-items:center;gap:8px;padding:16px 36px;background:#fff;color:var(--pri);border-radius:50px;font-size:16px;font-weight:800;transition:all .2s;text-decoration:none">
                <span class="material-symbols-outlined" style="font-size:20px">domain_add</span>
                Registrar mi empresa
            </a>
            <a href="{{ route('login') }}" style="display:inline-flex;align-items:center;gap:8px;padding:16px 32px;background:transparent;color:#fff;border:1.5px solid rgba(255,255,255,.4);border-radius:50px;font-size:16px;font-weight:600;transition:all .2s;text-decoration:none">
                <span class="material-symbols-outlined" style="font-size:20px">login</span>
                Iniciar sesión
            </a>
        </div>
    </div>
</section>
@endif

{{-- ══ FOOTER ══ --}}
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
        @if(!$authUser)
        <div class="footer-links">
            <a href="{{ route('login') }}" class="footer-link">Iniciar Sesión</a>
            <a href="{{ route('login') }}#empresa" class="footer-link">Para Empresas</a>
        </div>
        @endif
    </div>
</footer>

{{-- ══ MODAL POSTULAR ══ --}}
<div id="postular-modal" class="modal-overlay" onclick="if(event.target===this)closePostularModal()">
    <div class="modal-box">
        @if($authUser)
        {{-- Formulario de postulación real --}}
        <span class="material-symbols-outlined filled modal-icon">send</span>
        <h2 class="modal-title" id="modal-offer-title">Postular a oferta</h2>
        <p class="modal-sub">Selecciona tu CV y envía tu postulación.</p>
        <div id="postular-alert" class="s-alert" style="margin-bottom:8px;display:none"></div>
        {{-- Loading State --}}
        <div id="apply-loading" class="apply-loading">
            <div class="apply-loading-icon">
                <span class="material-symbols-outlined">progress_activity</span>
            </div>
            <div class="apply-progress-wrap">
                <div class="apply-progress-bar">
                    <div id="apply-progress-fill" class="apply-progress-fill"></div>
                </div>
                <p id="apply-progress-text" class="apply-progress-text">Enviando postulación... 0%</p>
            </div>
        </div>
        {{-- Form State --}}
        <div id="apply-form-content">
        @if($studentCvs->count())
        <div style="margin-bottom:12px">
            <label class="s-form-label" style="display:block;margin-bottom:6px">Selecciona tu CV</label>
            <select id="select-cv-id" class="s-form-input s-form-select">
                @foreach($studentCvs as $cv)
                <option value="{{ $cv->id }}">v{{ $cv->version }} — {{ $cv->filename }} ({{ $cv->uploaded_at }})</option>
                @endforeach
            </select>
        </div>
        <div style="margin-bottom:12px">
            <label class="s-form-label" style="display:block;margin-bottom:6px">Mensaje (opcional)</label>
            <textarea id="apply-message" class="s-form-input" rows="3" placeholder="Cuéntale a la empresa por qué eres el candidato ideal..." style="resize:vertical"></textarea>
        </div>
        <div class="modal-actions">
            <button id="btn-submit-apply" class="modal-btn-pri" onclick="submitApply()">
                <span class="material-symbols-outlined" style="font-size:18px">send</span>
                Enviar postulación
            </button>
            <button class="modal-btn-sec" onclick="closePostularModal()">Cancelar</button>
        </div>
        @else
        <p class="modal-sub" style="color:#dc2626">Necesitas subir al menos un CV antes de postular.</p>
        <div class="modal-actions">
            <button class="modal-btn-pri" onclick="closePostularModal();openModal('modal-cvs')">
                <span class="material-symbols-outlined" style="font-size:18px">upload_file</span>
                Subir mi CV
            </button>
            <button class="modal-btn-sec" onclick="closePostularModal()">Cancelar</button>
        </div>
        @endif
        </div>
        @else
        {{-- No autenticado --}}
        <span class="material-symbols-outlined filled modal-icon">lock_person</span>
        <h2 class="modal-title">Inicia sesión para postular</h2>
        <p class="modal-sub">Crea tu cuenta o inicia sesión para postularte a esta oferta y acceder a todas las funciones de tu perfil.</p>
        <div class="modal-actions">
            <a href="{{ route('login') }}" class="modal-btn-pri">
                <span class="material-symbols-outlined" style="font-size:18px">login</span>
                Iniciar Sesión
            </a>
            <button class="modal-btn-sec" onclick="closePostularModal()">Seguir explorando</button>
        </div>
        @endif
    </div>
</div>

@if($authUser)
{{-- ══ MODAL EDITAR PERFIL ══ --}}
<div id="modal-perfil" class="s-modal" onclick="if(event.target===this)closeModal('modal-perfil')">
    <div class="s-modal-box">
        <div class="s-modal-header">
            <h2 class="s-modal-title">Editar Perfil</h2>
            <button class="s-modal-close" onclick="closeModal('modal-perfil')"><span class="material-symbols-outlined" style="font-size:20px">close</span></button>
        </div>
        <div class="s-modal-body">
            <div id="perfil-alert" class="s-alert"></div>
            
            {{-- Foto de Perfil (Avatar) --}}
            <div style="display:flex;flex-direction:column;align-items:center;margin-bottom:8px">
                <div style="position:relative;cursor:pointer" onclick="document.getElementById('avatar-file-input').click()">
                    @if($authUser->avatar)
                        <img src="{{ $authUser->avatar }}" id="perfil-avatar-preview" style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:2px solid var(--bor)">
                    @else
                        <div id="perfil-avatar-init-preview" style="width:90px;height:90px;border-radius:50%;background:linear-gradient(135deg,var(--pri),#0a3452);color:#fff;display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:700;border:2px solid var(--bor)">
                            {{ strtoupper(substr($authUser->person->names ?? $authUser->email, 0, 2)) }}
                        </div>
                    @endif
                    <div style="position:absolute;bottom:0;right:0;background:var(--pri);color:#fff;width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(0,0,0,.2)">
                        <span class="material-symbols-outlined" style="font-size:16px">photo_camera</span>
                    </div>
                </div>
                <input type="file" id="avatar-file-input" accept="image/*" style="display:none" onchange="uploadAvatar(this.files[0])">
                <p style="font-size:11px;color:var(--tm);margin-top:6px">Haz clic para cambiar tu foto (JPG, PNG · Máx 3MB)</p>
            </div>

            <div class="s-form-row">
                <div class="s-form-group" style="grid-column:1/-1">
                    <label class="s-form-label">Nombre completo *</label>
                    <input type="text" id="p-names" class="s-form-input" value="{{ $authUser->person->names ?? '' }}" placeholder="Nombres y apellidos">
                </div>
            </div>
            <div class="s-form-row">
                <div class="s-form-group">
                    <label class="s-form-label">Tipo de documento *</label>
                    <select id="p-doc-type" class="s-form-input s-form-select">
                        <option value="DNI" {{ ($authUser->person->document_type??'') === 'DNI' ? 'selected' : '' }}>DNI</option>
                        <option value="CE" {{ ($authUser->person->document_type??'') === 'CE' ? 'selected' : '' }}>CE</option>
                        <option value="PASAPORTE" {{ ($authUser->person->document_type??'') === 'PASAPORTE' ? 'selected' : '' }}>Pasaporte</option>
                    </select>
                </div>
                <div class="s-form-group">
                    <label class="s-form-label">Número de documento *</label>
                    <input type="text" id="p-doc-num" class="s-form-input" value="{{ $authUser->person->document_number ?? '' }}" placeholder="12345678">
                </div>
            </div>
            <div class="s-form-row">
                <div class="s-form-group">
                    <label class="s-form-label">Teléfono</label>
                    <input type="text" id="p-phone" class="s-form-input" value="{{ $authUser->person->phone ?? '' }}" placeholder="999 999 999">
                </div>
                <div class="s-form-group">
                    <label class="s-form-label">Sexo</label>
                    <select id="p-sex" class="s-form-input s-form-select">
                        <option value="">Sin especificar</option>
                        <option value="M" {{ ($authUser->person->sex??'') === 'M' ? 'selected' : '' }}>Masculino</option>
                        <option value="F" {{ ($authUser->person->sex??'') === 'F' ? 'selected' : '' }}>Femenino</option>
                        <option value="O" {{ ($authUser->person->sex??'') === 'O' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>
            </div>
            <div class="s-form-row">
                <div class="s-form-group">
                    <label class="s-form-label">Fecha de nacimiento</label>
                    <input type="date" id="p-birth" class="s-form-input" value="{{ $authUser->person->birth_date ?? '' }}">
                </div>
                <div class="s-form-group">
                    <label class="s-form-label">Idioma nativo</label>
                    <input type="text" id="p-lang" class="s-form-input" value="{{ $authUser->person->native_language ?? '' }}" placeholder="Español">
                </div>
            </div>
            <div class="s-form-group">
                <label class="s-form-label">Carrera / Programa de estudios</label>
                <input type="text" id="p-career" class="s-form-input" value="{{ $authUser->person->career ?? '' }}" placeholder="Ej: Contabilidad, Enfermería, Computación...">
            </div>
            <div class="s-form-group">
                <label class="s-form-label">Presentación personal <span style="font-size:10px;color:var(--tm);font-weight:400;text-transform:none">(visible para empresas)</span></label>
                <textarea id="p-about" class="s-form-input" rows="4" placeholder="Cuéntale a las empresas sobre ti, tus objetivos y fortalezas...">{{ $authUser->person->about_me ?? '' }}</textarea>
            </div>
            <div class="s-form-group">
                <label class="s-form-label">Habilidades <span style="font-size:10px;color:var(--tm);font-weight:400;text-transform:none">(escribe y presiona Enter)</span></label>
                <div id="skills-tag-container" style="display:flex;flex-wrap:wrap;gap:6px;align-items:center;min-height:42px;padding:6px 10px;border:1.5px solid var(--bor);border-radius:10px;background:#fff;cursor:text;" onclick="document.getElementById('p-skill-input').focus()">
                    @if(!empty($authUser->person->skills) && is_array($authUser->person->skills))
                        @foreach($authUser->person->skills as $skill)
                        <span class="skill-tag" style="display:inline-flex;align-items:center;gap:4px;background:rgba(0,107,96,.1);color:var(--sec);padding:3px 10px;border-radius:50px;font-size:12px;font-weight:500;">
                            {{ $skill }}
                            <button type="button" onclick="removeSkillTag(this)" style="background:none;border:none;cursor:pointer;color:inherit;font-size:14px;line-height:1;padding:0 0 0 2px;">&times;</button>
                        </span>
                        @endforeach
                    @endif
                    <input type="text" id="p-skill-input" placeholder="Ej: Excel, Atención al cliente..." style="border:none;outline:none;font-size:13px;font-family:inherit;color:var(--txt);background:transparent;min-width:140px;flex:1;padding:2px 4px;"
                        onkeydown="handleSkillInput(event)">
                </div>
                <input type="hidden" id="p-skills-json" value="{{ htmlspecialchars(json_encode($authUser->person->skills ?? []), ENT_QUOTES, 'UTF-8') }}">
            </div>
            <button class="btn-s-primary" onclick="saveProfile()">
                <span class="material-symbols-outlined" style="font-size:18px">save</span>
                Guardar cambios
            </button>
        </div>
    </div>
</div>

{{-- ══ MODAL CVs ══ --}}
<div id="modal-cvs" class="s-modal" onclick="if(event.target===this)closeModal('modal-cvs')">
    <div class="s-modal-box">
        <div class="s-modal-header">
            <h2 class="s-modal-title">Mis CVs</h2>
            <button class="s-modal-close" onclick="closeModal('modal-cvs')"><span class="material-symbols-outlined" style="font-size:20px">close</span></button>
        </div>
        <div class="s-modal-body">
            <div id="cvs-alert" class="s-alert"></div>
            {{-- Lista de CVs --}}
            <div id="cvs-list" style="display:flex;flex-direction:column;gap:8px">
                @forelse($studentCvs as $cv)
                <div class="cv-item" id="cv-row-{{ $cv->id }}">
                    <div class="cv-item-icon"><span class="material-symbols-outlined filled" style="font-size:20px">picture_as_pdf</span></div>
                    <div class="cv-item-info">
                        <p class="cv-item-name">v{{ $cv->version }} — {{ $cv->filename }}</p>
                        <p class="cv-item-date">Subido: {{ $cv->uploaded_at }}</p>
                    </div>
                    <div class="cv-item-actions">
                        <a href="{{ route('student.cv.download', $cv->id) }}" class="btn-cv-action" title="Descargar">
                            <span class="material-symbols-outlined" style="font-size:14px">download</span>
                        </a>
                        <button class="btn-cv-action btn-cv-delete" onclick="deleteCv({{ $cv->id }})" title="Eliminar">
                            <span class="material-symbols-outlined" style="font-size:14px">delete</span>
                        </button>
                    </div>
                </div>
                @empty
                <p id="cvs-empty" style="text-align:center;color:var(--tm);font-size:14px;padding:20px 0">No tienes CVs subidos todavía.</p>
                @endforelse
            </div>
            {{-- Upload area --}}
            <div class="cv-upload-area" id="cv-drop-area" onclick="document.getElementById('cv-file-input').click()"
                ondragover="event.preventDefault();this.classList.add('drag-over')"
                ondragleave="this.classList.remove('drag-over')"
                ondrop="handleCvDrop(event)">
                <span class="material-symbols-outlined" style="font-size:36px;color:var(--tm);display:block;margin-bottom:8px">upload_file</span>
                <p style="font-weight:600;font-size:14px;color:var(--txt);margin-bottom:4px">Sube un nuevo CV</p>
                <p style="font-size:12px;color:var(--tm)">PDF · máx 5MB · Haz clic o arrastra aquí</p>
                <input type="file" id="cv-file-input" accept=".pdf" style="display:none" onchange="uploadCv(this.files[0])">
            </div>
            <div id="cv-upload-progress" style="display:none">
                <p style="font-size:13px;color:var(--tv);margin-bottom:6px">Subiendo CV...</p>
                <div style="background:var(--bg);border-radius:50px;height:6px;overflow:hidden">
                    <div id="cv-progress-bar" style="height:100%;background:var(--pri);border-radius:50px;width:0;transition:width .3s"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ MODAL CONTRASEÑA ══ --}}
<div id="modal-password" class="s-modal" onclick="if(event.target===this)closeModal('modal-password')">
    <div class="s-modal-box">
        <div class="s-modal-header">
            <h2 class="s-modal-title">Cambiar Contraseña</h2>
            <button class="s-modal-close" onclick="closeModal('modal-password')"><span class="material-symbols-outlined" style="font-size:20px">close</span></button>
        </div>
        <div class="s-modal-body">
            <div id="pwd-alert" class="s-alert"></div>
            <div class="s-form-group">
                <label class="s-form-label">Contraseña actual *</label>
                <input type="password" id="pwd-current" class="s-form-input" placeholder="Tu contraseña actual">
            </div>
            <div class="s-form-group">
                <label class="s-form-label">Nueva contraseña *</label>
                <input type="password" id="pwd-new" class="s-form-input" placeholder="Mínimo 8 caracteres">
            </div>
            <div class="s-form-group">
                <label class="s-form-label">Confirmar nueva contraseña *</label>
                <input type="password" id="pwd-confirm" class="s-form-input" placeholder="Repite la nueva contraseña">
            </div>
            <button class="btn-s-primary" onclick="changePassword()">
                <span class="material-symbols-outlined" style="font-size:18px">lock_reset</span>
                Cambiar contraseña
            </button>
        </div>
</div>

@endif

<script>
// ── Estado ──────────────────────────────────────────────────────────────────
var currentPage = 1;
var isLoading   = false;
var searchTimer = null;
var activeItemId= null;

// ── Skills tag input ─────────────────────────────────────────────────────────
var currentSkills = (function(){
    try {
        var el = document.getElementById('p-skills-json');
        return el ? JSON.parse(el.value) : [];
    } catch(e) { return []; }
})();

function handleSkillInput(e) {
    if (e.key === 'Enter' || e.key === ',') {
        e.preventDefault();
        var input = document.getElementById('p-skill-input');
        var val = input.value.replace(/,/g,'').trim();
        if (val && !currentSkills.includes(val)) {
            currentSkills.push(val);
            var container = document.getElementById('skills-tag-container');
            var tag = document.createElement('span');
            tag.className = 'skill-tag';
            tag.style.cssText = 'display:inline-flex;align-items:center;gap:4px;background:rgba(0,107,96,.1);color:var(--sec);padding:3px 10px;border-radius:50px;font-size:12px;font-weight:500;';
            tag.innerHTML = val + '<button type="button" onclick="removeSkillTag(this)" style="background:none;border:none;cursor:pointer;color:inherit;font-size:14px;line-height:1;padding:0 0 0 2px;">&times;</button>';
            container.insertBefore(tag, input);
        }
        input.value = '';
    }
    if (e.key === 'Backspace' && document.getElementById('p-skill-input').value === '') {
        var tags = document.querySelectorAll('#skills-tag-container .skill-tag');
        if (tags.length > 0) {
            var last = tags[tags.length - 1];
            var skillText = last.textContent.replace('×','').trim();
            currentSkills = currentSkills.filter(function(s){ return s !== skillText; });
            last.remove();
        }
    }
}

function removeSkillTag(btn) {
    var tag = btn.parentElement;
    var skillText = tag.textContent.replace('×','').trim();
    currentSkills = currentSkills.filter(function(s){ return s !== skillText; });
    tag.remove();
}
var loadedOffers = [];
var sharedOffer  = @json($sharedOffer);
var appliedOffers = new Set(@json($studentApplicationIds));


// ── Init ────────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
    loadOffers(1, true);
});

// ── Search ──────────────────────────────────────────────────────────────────
function triggerSearch() {
    loadOffers(1, true);
}

function applyFilters() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(function(){ loadOffers(1, true); }, 280);
}

function clearFilters() {
    if (document.getElementById('nav-search-q'))     document.getElementById('nav-search-q').value = '';
    if (document.getElementById('mobile-search-q'))  document.getElementById('mobile-search-q').value = '';
    if (document.getElementById('nav-search-loc'))   document.getElementById('nav-search-loc').value = '';
    if (document.getElementById('mobile-search-loc')) document.getElementById('mobile-search-loc').value = '';
    document.getElementById('filter-sort').value     = 'recent';
    document.getElementById('filter-location').value = '';
    document.getElementById('filter-schedule').value = '';
    document.getElementById('filter-contract').value = '';
    document.getElementById('filter-category').value = '';
    document.getElementById('filter-salary').value   = '';
    loadOffers(1, true);
}

// ── Params ──────────────────────────────────────────────────────────────────
function buildParams(page) {
    var p = new URLSearchParams();
    
    // Read from desktop or mobile search inputs
    var desktopQ = document.getElementById('nav-search-q') ? document.getElementById('nav-search-q').value.trim() : '';
    var mobileQ  = document.getElementById('mobile-search-q') ? document.getElementById('mobile-search-q').value.trim() : '';
    var q = desktopQ || mobileQ;

    var desktopLoc = document.getElementById('nav-search-loc') ? document.getElementById('nav-search-loc').value.trim() : '';
    var mobileLoc  = document.getElementById('mobile-search-loc') ? document.getElementById('mobile-search-loc').value.trim() : '';
    var loc = desktopLoc || mobileLoc;

    if (q)   p.set('search', q);
    if (loc) p.set('province', loc);

    var locId = document.getElementById('filter-location').value;
    var schId = document.getElementById('filter-schedule').value;
    var conId = document.getElementById('filter-contract').value;
    var catId = document.getElementById('filter-category').value;
    var sal   = document.getElementById('filter-salary').value;
    var sort  = document.getElementById('filter-sort').value;

    if (locId) p.set('location_id', locId);
    if (schId) p.set('work_schedule_id', schId);
    if (conId) p.set('contract_type_id', conId);
    if (catId) p.set('category_id', catId);
    if (sal)   p.set('salary_filter', sal);
    if (sort)  p.set('sort_by', sort);

    p.set('page', page);
    p.set('per_page', 15);
    return p;
}

// ── Load offers ──────────────────────────────────────────────────────────────
function loadOffers(page, reset) {
    if (isLoading) return;
    isLoading = true;

    var list       = document.getElementById('job-list');
    var loadBtn    = document.getElementById('load-more-btn');
    var noMoreLbl  = document.getElementById('no-more-label');

    if (reset) {
        currentPage = 1;
        activeItemId = null;
        showDetailEmpty();
        list.innerHTML = renderSkeletons(8);
        loadBtn.classList.add('hidden');
        noMoreLbl.classList.add('hidden');
    }

    var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var url = '/buscar-ofertas?' + buildParams(page).toString() + '&_=' + Date.now();
    console.log('[loadOffers] Fetching:', url);
    fetch(url, {
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
     })
     .then(function(r){ 
         console.log('[loadOffers] Response status:', r.status);
         return r.json(); 
     })
     .then(function(data){
         console.log('[loadOffers] Data received:', data);
         if (!data.success) throw new Error(data.message);
         if (reset) {
             list.innerHTML = '';
             loadedOffers = [];
             if (sharedOffer) {
                 list.insertAdjacentHTML('beforeend', renderItem(sharedOffer));
                 loadedOffers.push(sharedOffer);
             }
         }

         var countEl = document.getElementById('results-count-label');
         if (data.total === 0 && reset && !sharedOffer) {
             list.innerHTML = '<div style="padding:60px 20px;text-align:center;color:var(--tm)"><span class="material-symbols-outlined" style="font-size:48px;color:var(--bor);display:block;margin-bottom:12px">search_off</span><p style="font-size:15px">No se encontraron ofertas</p></div>';
             countEl.innerHTML = 'Sin resultados';
         } else {
             data.offers.forEach(function(o){
                 if (!sharedOffer || o.id !== sharedOffer.id) {
                     list.insertAdjacentHTML('beforeend', renderItem(o));
                     loadedOffers.push(o);
                 }
             });
             currentPage = data.current_page;
             var totalCount = data.total + (sharedOffer ? 1 : 0);
             countEl.innerHTML = '<strong>' + Number(totalCount).toLocaleString('es-PE') + '</strong> oferta' + (totalCount!==1?'s':'') + ' encontrada' + (totalCount!==1?'s':'');

             if (data.has_more) {
                 loadBtn.classList.remove('hidden');
                 noMoreLbl.classList.add('hidden');
              } else {
                  loadBtn.classList.add('hidden');
                  if (!reset){ noMoreLbl.textContent = 'Has visto todas las ofertas.'; noMoreLbl.classList.remove('hidden'); }
              }

              if (reset && sharedOffer) {
                  openDetail(sharedOffer);
              } else if (reset && data.offers.length > 0 && window.innerWidth >= 900) {
                  openDetail(data.offers[0]);
              }
         }
     })
    .catch(function(err){
        console.error('[loadOffers] Error:', err);
        list.innerHTML = '<div style="padding:60px 20px;text-align:center;color:var(--tm)"><span class="material-symbols-outlined" style="font-size:48px;color:var(--bor);display:block;margin-bottom:12px">cloud_off</span><p>Error al cargar: ' + (err.message || err) + '. Intenta de nuevo.</p></div>';
    })
    .finally(function(){ isLoading = false; });
}

function loadMoreOffers() { loadOffers(currentPage + 1, false); }

// ── Render skeleton ──────────────────────────────────────────────────────────
function renderSkeletons(n){
    var h='';
    for(var i=0;i<n;i++){
        h+='<div class="job-item">' +
            '<div class="job-item-header">' +
            '<div class="job-item-logo"><div class="skel" style="width:38px;height:38px;border-radius:8px;"></div></div>' +
            '<div class="job-item-meta">' +
            '<div class="skel" style="height:14px;width:75%;margin-bottom:7px;"></div>' +
            '<div class="skel" style="height:12px;width:50%;"></div>' +
            '</div></div>' +
            '<div class="skel" style="height:11px;width:40%;margin:8px 0 4px;"></div>' +
            '<div class="skel" style="height:22px;width:80px;border-radius:50px;margin-top:6px;"></div>' +
            '</div>';
    }
    return h;
}

// ── Render item ──────────────────────────────────────────────────────────────
function esc(s){ if(!s)return''; return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;'); }

function timeAgo(dateStr){
    if(!dateStr) return '';
    var d = new Date(dateStr);
    var now = new Date();
    var diff = Math.floor((now - d) / 60000);
    if(diff < 60) return 'Hace ' + diff + ' min';
    diff = Math.floor(diff/60);
    if(diff < 24) return 'Hace ' + diff + ' hora' + (diff>1?'s':'');
    diff = Math.floor(diff/24);
    if(diff < 30) return 'Hace ' + diff + ' día' + (diff>1?'s':'');
    return d.toLocaleDateString('es-PE',{day:'2-digit',month:'short'});
}

function renderItem(o){
    var co  = o.company||{};
    var cat = o.category||{};
    var loc = o.location||{};
    var name= co.name||'Empresa';
    var logo= co.logo||'';
    var addr= [o.province, o.department].filter(Boolean).join(', ');
    var salary = o.salary ? 'S/ '+Number(o.salary).toLocaleString('es-PE') : 'A tratar';
    var isApplied = appliedOffers.has(o.id);

    var logoHtml = logo
        ? '<img src="'+esc(logo)+'" alt="'+esc(name)+'">'
        : '<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,var(--pri),#0a3452);color:#fff;font-size:12px;font-weight:700;">'+esc(name.substring(0,2).toUpperCase())+'</div>';

    var oJson = JSON.stringify(o).replace(/\\/g,'\\\\').replace(/'/g,"\\'");

    return '<div class="job-item" id="item-'+o.id+'" onclick=\'openDetail('+oJson+')\'>'+
        '<div class="job-item-header">'+
        '<div class="job-item-logo">'+logoHtml+'</div>'+
        '<div class="job-item-meta">'+
        '<p class="job-item-title">'+esc(o.title)+'</p>'+
        '<p class="job-item-company">'+
        (co.is_verified?'<span class="material-symbols-outlined filled">verified</span>':'')+
        esc(name)+'</p>'+
        '</div></div>'+
        (addr?'<p class="job-item-location">'+esc(addr)+'</p>':'')+
        '<div class="job-item-footer">'+
        '<span class="job-item-time">'+timeAgo(o.publication_date)+'</span>'+
        '<div class="job-item-tags">'+
        (cat.name?'<span class="tag-xs tag-pri">'+esc(cat.name)+'</span>':'')+
        '<span class="tag-xs tag-sec">'+esc(salary)+'</span>'+
        (isApplied?'<span class="tag-xs tag-acc tag-applied">✓ Postulado</span>':'')+
        '</div>'+
        '<button class="btn-vista" onclick="event.stopPropagation();openDetail('+oJson+')">Vista</button>'+
        '</div>'+
        '</div>';
}

// ── Detail panel ─────────────────────────────────────────────────────────────
function openDetail(o) {
    // Quitar activo anterior
    if (activeItemId) {
        var prev = document.getElementById('item-' + activeItemId);
        if (prev) prev.classList.remove('active');
    }
    activeItemId = o.id;
    var el = document.getElementById('item-' + o.id);
    if (el) el.classList.add('active');

    var co  = o.company    || {};
    var cat = o.category   || {};
    var loc = o.location   || {};
    var ws  = o.work_schedule || {};
    var ct  = o.contract_type || {};

    var name= co.name || 'Empresa';
    var logo= co.logo || '';
    var addr= [o.province, o.department].filter(Boolean).join(', ');

    // Logo
    var lw = document.getElementById('d-logo-wrap');
    lw.innerHTML = logo
        ? '<img src="'+esc(logo)+'" alt="'+esc(name)+'" style="width:100%;height:100%;object-fit:contain;padding:4px">'
        : '<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,var(--pri),#0a3452);color:#fff;font-size:18px;font-weight:700;">'+esc(name.substring(0,2).toUpperCase())+'</div>';

    document.getElementById('d-title').textContent   = o.title || '';
    document.getElementById('d-company').textContent = name;
    document.getElementById('d-location').textContent= [loc.name, addr].filter(Boolean).join(' · ') || 'No especificada';

    var verRow = document.getElementById('d-verified-row');
    verRow.style.display = co.is_verified ? 'flex' : 'none';

    // Chips
    var chips = '';
    if(ct.name)  chips += '<span class="d-chip"><span class="material-symbols-outlined">contract</span>'+esc(ct.name)+'</span>';
    if(ws.name)  chips += '<span class="d-chip"><span class="material-symbols-outlined">schedule</span>'+esc(ws.name)+'</span>';
    if(loc.name) chips += '<span class="d-chip"><span class="material-symbols-outlined">location_home</span>'+esc(loc.name)+'</span>';
    if(cat.name) chips += '<span class="d-chip"><span class="material-symbols-outlined">category</span>'+esc(cat.name)+'</span>';
    document.getElementById('d-chips').innerHTML = chips;

    // Salary
    var salary = o.salary ? 'S/ '+Number(o.salary).toLocaleString('es-PE')+' '+(o.salary_currency||'SOLES') : 'A tratar';
    document.getElementById('d-salary').textContent = salary;

    document.getElementById('d-description').textContent  = o.description  || '';
    document.getElementById('d-requirements').textContent = o.requirements || '';

    var benBlock = document.getElementById('d-benefits-block');
    if(o.benefits){ document.getElementById('d-benefits').textContent=o.benefits; benBlock.style.display='block'; }
    else { benBlock.style.display='none'; }

    var dlRow = document.getElementById('d-deadline-row');
    if(o.deadline){
        try{ document.getElementById('d-deadline-text').textContent = 'Postulaciones hasta: '+new Date(o.deadline).toLocaleDateString('es-PE',{day:'2-digit',month:'long',year:'numeric'}); } catch(e){}
        dlRow.style.display='block';
    } else { dlRow.style.display='none'; }

    // Update apply button based on applied status
    var btnPostular = document.querySelector('.btn-postular');
    var btnSave = document.querySelector('.d-actions .btn-icon[title="Guardar"]');
    if(btnPostular){
        var isApplied = appliedOffers.has(o.id);
        if(isApplied){
            btnPostular.innerHTML = '<span class="material-symbols-outlined" style="font-size:18px">check_circle</span> Ya postulado';
            btnPostular.disabled = true;
            btnPostular.style.opacity = '0.6';
            btnPostular.style.cursor = 'not-allowed';
            if(btnSave) btnSave.style.display = 'none';
        } else {
            btnPostular.innerHTML = '<span class="material-symbols-outlined" style="font-size:18px">send</span> Postularme';
            btnPostular.disabled = false;
            btnPostular.style.opacity = '1';
            btnPostular.style.cursor = 'pointer';
            if(btnSave) btnSave.style.display = 'flex';
        }
    }

    // Show content
    document.getElementById('detail-empty').classList.add('hidden');
    document.getElementById('detail-content').classList.remove('hidden');

    // Mobile: show detail col
    if(window.innerWidth < 900){
        document.getElementById('detail-col').classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    // Cargar empleos similares
    renderSimilarJobs(o);

    // Scroll to top of detail
    document.getElementById('detail-panel').scrollTop = 0;
}

// ── Similar Jobs Rendering ───────────────────────────────────────────────────
function renderSimilarJobs(o) {
    var list = document.getElementById('d-similar-list');
    var sect = document.getElementById('d-similar-section');
    if(!list) return;

    // Filtrar similares (misma categoría o departamento, no la actual)
    var matches = loadedOffers.filter(function(item) {
        return item.id !== o.id && (
            (o.category_id && item.category_id === o.category_id) || 
            (o.province && item.province === o.province)
        );
    });

    // Si no hay suficientes, rellenar con otras ofertas recientes en la lista
    if(matches.length < 3) {
        var ids = matches.map(function(m){ return m.id; });
        var others = loadedOffers.filter(function(item) {
            return item.id !== o.id && !ids.includes(item.id);
        });
        matches = matches.concat(others);
    }

    // Tomar máximo 3
    matches = matches.slice(0, 3);

    if(matches.length === 0) {
        if(sect) sect.style.display = 'none';
        return;
    }
    if(sect) sect.style.display = 'block';

    list.innerHTML = '';
    matches.forEach(function(item) {
        var co  = item.company || {};
        var name = co.name || 'Empresa';
        var logo = co.logo || '';
        var loc = item.province || (item.location ? item.location.name : '');
        var salary = item.salary ? 'S/ ' + Number(item.salary).toLocaleString('es-PE') : 'A tratar';
        
        var logoHtml = logo
            ? '<img src="'+esc(logo)+'" alt="'+esc(name)+'">'
            : '<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,var(--pri),#0a3452);color:#fff;font-size:10px;font-weight:700;">'+esc(name.substring(0,2).toUpperCase())+'</div>';

        var itemJson = JSON.stringify(item).replace(/\\/g,'\\\\').replace(/'/g,"\\'");

        var html = '<div class="similar-card" onclick=\'openDetail('+itemJson+')\'>' +
            '<div class="similar-card-logo">' + logoHtml + '</div>' +
            '<div class="similar-card-info">' +
                '<h4 class="similar-card-title">' + esc(item.title) + '</h4>' +
                '<p class="similar-card-company">' + esc(name) + (loc ? ' · ' + esc(loc) : '') + '</p>' +
            '</div>' +
            '<span class="similar-card-tag">' + esc(salary) + '</span>' +
            '</div>';
        list.insertAdjacentHTML('beforeend', html);
    });
}


function showDetailEmpty(){
    document.getElementById('detail-empty').classList.remove('hidden');
    document.getElementById('detail-content').classList.add('hidden');
    if(activeItemId){
        var prev = document.getElementById('item-' + activeItemId);
        if(prev) prev.classList.remove('active');
        activeItemId = null;
    }
}

function closeDetail(){
    document.getElementById('detail-col').classList.remove('open');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e){ if(e.key==='Escape'){ closePostularModal(); closeDetail(); closePlaceDropdown(); } });

// ── Place dropdown ────────────────────────────────────────────────────────────
var placeTimer = null;
var focusedPlaceIdx = -1;

function openPlaceDropdown(){
    var dd = document.getElementById('place-dropdown');
    dd.classList.add('open');
    filterPlaceDropdown(document.getElementById('nav-search-loc').value);
    focusedPlaceIdx = -1;
}

function closePlaceDropdown(){
    document.getElementById('place-dropdown').classList.remove('open');
    focusedPlaceIdx = -1;
}

function filterPlaceDropdown(val){
    var dd = document.getElementById('place-dropdown');
    dd.classList.add('open');
    var opts = dd.querySelectorAll('.place-option');
    var lower = val.toLowerCase().trim();
    var visible = 0;
    opts.forEach(function(o){
        var match = !lower || o.dataset.place.indexOf(lower) !== -1;
        o.style.display = match ? 'flex' : 'none';
        if(match) visible++;
    });
    var empty = dd.querySelector('.place-empty');
    if(empty) empty.style.display = visible === 0 ? 'block' : 'none';
}

function selectPlace(val){
    document.getElementById('nav-search-loc').value = val;
    closePlaceDropdown();
    triggerSearch();
}

function handlePlaceKey(e){
    var dd = document.getElementById('place-dropdown');
    var opts = Array.from(dd.querySelectorAll('.place-option')).filter(o=>o.style.display!=='none');
    if(e.key === 'Enter'){
        if(focusedPlaceIdx >= 0 && opts[focusedPlaceIdx]){
            selectPlace(opts[focusedPlaceIdx].dataset.place.charAt(0).toUpperCase() + opts[focusedPlaceIdx].dataset.place.slice(1));
        } else {
            closePlaceDropdown();
            triggerSearch();
        }
        e.preventDefault();
        return;
    }
    if(e.key === 'ArrowDown'){
        focusedPlaceIdx = Math.min(focusedPlaceIdx + 1, opts.length - 1);
        opts.forEach(function(o,i){ o.classList.toggle('focused', i===focusedPlaceIdx); });
        if(opts[focusedPlaceIdx]) opts[focusedPlaceIdx].scrollIntoView({block:'nearest'});
        e.preventDefault();
        return;
    }
    if(e.key === 'ArrowUp'){
        focusedPlaceIdx = Math.max(focusedPlaceIdx - 1, 0);
        opts.forEach(function(o,i){ o.classList.toggle('focused', i===focusedPlaceIdx); });
        if(opts[focusedPlaceIdx]) opts[focusedPlaceIdx].scrollIntoView({block:'nearest'});
        e.preventDefault();
        return;
    }
    if(e.key === 'Escape'){ closePlaceDropdown(); return; }
}

// ── Q search dropdown ─────────────────────────────────────────────────────────
var qTimer = null;
var focusedQIdx = -1;

function openQDropdown(){
    var dd = document.getElementById('q-dropdown');
    if(dd) {
        dd.classList.add('open');
        filterQDropdown(document.getElementById('nav-search-q').value);
    }
    focusedQIdx = -1;
}

function closeQDropdown(){
    var dd = document.getElementById('q-dropdown');
    if(dd) dd.classList.remove('open');
    focusedQIdx = -1;
}

function filterQDropdown(val){
    var dd = document.getElementById('q-dropdown');
    if(!dd) return;
    dd.classList.add('open');
    var opts = dd.querySelectorAll('.q-option');
    var lower = val.toLowerCase().trim();
    var visible = 0;
    
    opts.forEach(function(o){
        var match = !lower || o.dataset.q.indexOf(lower) !== -1;
        o.style.display = match ? 'flex' : 'none';
        if(match) visible++;
    });
    
    var empty = dd.querySelector('.q-empty');
    if(empty) empty.style.display = visible === 0 ? 'block' : 'none';
}

function selectQ(val){
    document.getElementById('nav-search-q').value = val;
    closeQDropdown();
    triggerSearch();
}

function handleQKey(e){
    var dd = document.getElementById('q-dropdown');
    if(!dd) return;
    var opts = Array.from(dd.querySelectorAll('.q-option')).filter(o=>o.style.display!=='none');
    if(e.key === 'Enter'){
        if(focusedQIdx >= 0 && opts[focusedQIdx]){
            selectQ(opts[focusedQIdx].dataset.q);
        } else {
            closeQDropdown();
            triggerSearch();
        }
        e.preventDefault();
        return;
    }
    if(e.key === 'ArrowDown'){
        focusedQIdx = Math.min(focusedQIdx + 1, opts.length - 1);
        opts.forEach(function(o,i){ o.classList.toggle('focused', i===focusedQIdx); });
        if(opts[focusedQIdx]) opts[focusedQIdx].scrollIntoView({block:'nearest'});
        e.preventDefault();
        return;
    }
    if(e.key === 'ArrowUp'){
        focusedQIdx = Math.max(focusedQIdx - 1, 0);
        opts.forEach(function(o,i){ o.classList.toggle('focused', i===focusedQIdx); });
        if(opts[focusedQIdx]) opts[focusedQIdx].scrollIntoView({block:'nearest'});
        e.preventDefault();
        return;
    }
    if(e.key === 'Escape'){ closeQDropdown(); return; }
}

// Cerrar dropdown al hacer click fuera
document.addEventListener('click', function(e){
    var wrap = document.getElementById('place-wrap');
    if(wrap && !wrap.contains(e.target)) closePlaceDropdown();
    var qWrap = document.getElementById('q-wrap');
    if(qWrap && !qWrap.contains(e.target)) closeQDropdown();
    // Cerrar profile menu
    var pmWrap = document.getElementById('profile-menu-wrap');
    if(pmWrap && !pmWrap.contains(e.target)) closeProfileMenu();
});

// ── Profile menu ──────────────────────────────────────────────────────────────
function toggleProfileMenu(){
    var dd = document.getElementById('profile-dropdown');
    if(!dd) return;
    dd.classList.toggle('open');
    document.getElementById('profile-trigger').setAttribute('aria-expanded', dd.classList.contains('open'));
}
function closeProfileMenu(){
    var dd = document.getElementById('profile-dropdown');
    if(dd) dd.classList.remove('open');
}

// ── Generic modal helpers ─────────────────────────────────────────────────────
function openModal(id){
    var el = document.getElementById(id);
    if(el){ el.classList.add('open'); document.body.style.overflow='hidden'; }
}
function closeModal(id){
    var el = document.getElementById(id);
    if(el){ el.classList.remove('open'); document.body.style.overflow=''; }
}

// ── Offer ID for apply ───────────────────────────────────────────────────────
var currentOfferId = null;

function openPostularModal(){
    if(activeItemId) currentOfferId = activeItemId;
    var modal = document.getElementById('postular-modal');
    if(modal){
        modal.classList.add('open');
        document.body.style.overflow='hidden';
        // Reset alert
        var al = document.getElementById('postular-alert');
        if(al){ al.style.display='none'; al.textContent=''; }
        var msg = document.getElementById('apply-message');
        if(msg) msg.value='';

        // Reset loading state
        var loadingEl = document.getElementById('apply-loading');
        var formContent = document.getElementById('apply-form-content');
        var progressFill = document.getElementById('apply-progress-fill');
        var progressText = document.getElementById('apply-progress-text');
        var btnSubmit = document.getElementById('btn-submit-apply');
        if(loadingEl) loadingEl.classList.remove('show');
        if(formContent) formContent.style.display = 'block';
        if(progressFill) progressFill.style.width = '0';
        if(progressText) progressText.textContent = 'Enviando postulación... 0%';
        if(btnSubmit) btnSubmit.disabled = false;

        // Check if already applied
        if(currentOfferId && appliedOffers.has(currentOfferId)){
            if(formContent) formContent.style.display = 'none';
            if(al){
                al.className = 's-alert error';
                al.innerHTML = '<span class="material-symbols-outlined" style="font-size:18px;vertical-align:middle;margin-right:6px">block</span>Ya has postulado a esta oferta laboral.';
                al.style.display = 'block';
            }
        }
    }
}
function closePostularModal(){
    var modal = document.getElementById('postular-modal');
    if(modal){ modal.classList.remove('open'); document.body.style.overflow=''; }
}

// ── Submit apply ──────────────────────────────────────────────────────────────
function submitApply(){
    if(!currentOfferId){ showAlert('postular-alert','error','No hay oferta seleccionada.'); return; }
    var cvSelect = document.getElementById('select-cv-id');
    var msg      = document.getElementById('apply-message');
    if(!cvSelect){ showAlert('postular-alert','error','Selecciona un CV.'); return; }
    var cvId = cvSelect.value;
    var message = msg ? msg.value : '';

    // Show loading
    var loadingEl = document.getElementById('apply-loading');
    var formContent = document.getElementById('apply-form-content');
    var progressFill = document.getElementById('apply-progress-fill');
    var progressText = document.getElementById('apply-progress-text');
    var btnSubmit = document.getElementById('btn-submit-apply');

    if(loadingEl) loadingEl.classList.add('show');
    if(formContent) formContent.style.display = 'none';
    if(btnSubmit) btnSubmit.disabled = true;

    // Simulate progress
    var progress = 0;
    var progressInterval = setInterval(function(){
        progress += Math.random() * 15;
        if(progress > 90) progress = 90;
        if(progressFill) progressFill.style.width = progress + '%';
        if(progressText) progressText.textContent = 'Enviando postulación... ' + Math.round(progress) + '%';
    }, 200);

    fetch('/student/apply/'+currentOfferId, {
        method:'POST',
        headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},
        body: JSON.stringify({cv_id: cvId, message: message})
    })
    .then(function(r){ return r.json(); })
    .then(function(d){
        clearInterval(progressInterval);
        if(progressFill) progressFill.style.width = '100%';
        if(progressText) progressText.textContent = '¡Postulación enviada!';

        if(d.success){
            // Mark as applied
            if(currentOfferId){
                appliedOffers.add(currentOfferId);
                var item = document.getElementById('item-'+currentOfferId);
                if(item){
                    var footer = item.querySelector('.job-item-footer');
                    if(footer && !footer.querySelector('.tag-applied')){
                        footer.insertAdjacentHTML('beforeend','<span class="tag-xs tag-acc tag-applied">✓ Postulado</span>');
                    }
                }
            }
            setTimeout(function(){
                showAlert('postular-alert','success',d.message);
                setTimeout(function(){ closePostularModal(); }, 1200);
            }, 500);
        } else {
            // Reset form on error
            if(loadingEl) loadingEl.classList.remove('show');
            if(formContent) formContent.style.display = 'block';
            if(btnSubmit) btnSubmit.disabled = false;
            showAlert('postular-alert','error',d.message);
        }
    })
    .catch(function(){
        clearInterval(progressInterval);
        if(loadingEl) loadingEl.classList.remove('show');
        if(formContent) formContent.style.display = 'block';
        if(btnSubmit) btnSubmit.disabled = false;
        showAlert('postular-alert','error','Error de conexión.');
    });
}

// ── Save profile ──────────────────────────────────────────────────────────────
function saveProfile(){
    var data = {
        names:            document.getElementById('p-names').value,
        document_type:    document.getElementById('p-doc-type').value,
        document_number:  document.getElementById('p-doc-num').value,
        phone:            document.getElementById('p-phone').value,
        sex:              document.getElementById('p-sex').value,
        birth_date:       document.getElementById('p-birth').value,
        native_language:  document.getElementById('p-lang').value,
        about_me:         document.getElementById('p-about').value,
        career:           document.getElementById('p-career').value,
        skills:           currentSkills,
    };
    fetch('/student/profile', {
        method:'POST',
        headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},
        body: JSON.stringify(data)
    })
    .then(function(r){ return r.json(); })
    .then(function(d){
        showAlert('perfil-alert', d.success ? 'success' : 'error', d.message);
        if(d.success) setTimeout(function(){ closeModal('modal-perfil'); location.reload(); }, 1500);
    })
    .catch(function(){ showAlert('perfil-alert','error','Error de conexión.'); });
}

// ── Change password ───────────────────────────────────────────────────────────
function changePassword(){
    var current = document.getElementById('pwd-current').value;
    var nw      = document.getElementById('pwd-new').value;
    var confirm = document.getElementById('pwd-confirm').value;
    if(nw !== confirm){ showAlert('pwd-alert','error','Las contraseñas no coinciden.'); return; }
    fetch('/student/password', {
        method:'POST',
        headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},
        body: JSON.stringify({current_password: current, new_password: nw, new_password_confirmation: confirm})
    })
    .then(function(r){ return r.json(); })
    .then(function(d){
        showAlert('pwd-alert', d.success ? 'success' : 'error', d.message);
        if(d.success){ document.getElementById('pwd-current').value=''; document.getElementById('pwd-new').value=''; document.getElementById('pwd-confirm').value=''; }
    })
    .catch(function(){ showAlert('pwd-alert','error','Error de conexión.'); });
}

// ── Upload CV ─────────────────────────────────────────────────────────────────
function handleCvDrop(e){
    e.preventDefault();
    document.getElementById('cv-drop-area').classList.remove('drag-over');
    var file = e.dataTransfer.files[0];
    if(file) uploadCv(file);
}

function uploadCv(file){
    if(!file) return;
    if(file.type !== 'application/pdf'){ showAlert('cvs-alert','error','Solo se permiten archivos PDF.'); return; }
    if(file.size > 5*1024*1024){ showAlert('cvs-alert','error','El archivo no debe exceder 5MB.'); return; }

    var progress = document.getElementById('cv-upload-progress');
    var bar      = document.getElementById('cv-progress-bar');
    progress.style.display='block';
    bar.style.width='30%';

    var form = new FormData();
    form.append('cv', file);
    form.append('_token', document.querySelector('meta[name=csrf-token]').content);

    fetch('/student/cv/upload', { method:'POST', headers:{'Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content}, body: form })
    .then(function(r){ bar.style.width='80%'; return r.json(); })
    .then(function(d){
        bar.style.width='100%';
        setTimeout(function(){ progress.style.display='none'; bar.style.width='0'; }, 600);
        if(d.success){
            showAlert('cvs-alert','success',d.message);
            var cv = d.cv;
            var list = document.getElementById('cvs-list');
            var empty = document.getElementById('cvs-empty');
            if(empty) empty.remove();
            list.insertAdjacentHTML('afterbegin',
                '<div class="cv-item" id="cv-row-'+cv.id+'">' +
                '<div class="cv-item-icon"><span class="material-symbols-outlined filled" style="font-size:20px">picture_as_pdf</span></div>' +
                '<div class="cv-item-info"><p class="cv-item-name">v'+cv.version+' — '+esc(cv.filename)+'</p><p class="cv-item-date">Subido: '+cv.uploaded_at+'</p></div>' +
                '<div class="cv-item-actions">' +
                '<button class="btn-cv-action btn-cv-delete" onclick="deleteCv('+cv.id+')" title="Eliminar"><span class="material-symbols-outlined" style="font-size:14px">delete</span></button>' +
                '</div></div>'
            );
            // Update badge
            var badge = document.querySelector('.cv-badge');
            if(badge){ badge.textContent = parseInt(badge.textContent||0)+1; }
            else {
                var cvBtn = document.querySelector('[onclick*="modal-cvs"]');
                if(cvBtn) cvBtn.insertAdjacentHTML('beforeend','<span class="cv-badge">1</span>');
            }
            // Reset file input
            document.getElementById('cv-file-input').value='';
        } else {
            showAlert('cvs-alert','error',d.message);
        }
    })
    .catch(function(){ progress.style.display='none'; showAlert('cvs-alert','error','Error al subir el CV.'); });
}

// ── Delete CV ─────────────────────────────────────────────────────────────────
function deleteCv(id){
    if(!confirm('¿Eliminar este CV?')) return;
    fetch('/student/cv/delete/'+id, {
        method:'POST',
        headers:{'Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},
        body: new URLSearchParams({_token: document.querySelector('meta[name=csrf-token]').content})
    })
    .then(function(r){ return r.json(); })
    .then(function(d){
        if(d.success){
            var row = document.getElementById('cv-row-'+id);
            if(row) row.remove();
            showAlert('cvs-alert','success',d.message);
            // Update badge
            var badge = document.querySelector('.cv-badge');
            if(badge){ var n=parseInt(badge.textContent||0)-1; if(n<=0) badge.remove(); else badge.textContent=n; }
        } else {
            showAlert('cvs-alert','error',d.message);
        }
    })
    .catch(function(){ showAlert('cvs-alert','error','Error al eliminar el CV.'); });
}

// ── Alert helper ─────────────────────────────────────────────────────────────
function showAlert(id, type, msg){
    var el = document.getElementById(id);
    if(!el) return;
    el.className = 's-alert ' + type;
    el.textContent = msg;
    el.style.display = 'block';
    setTimeout(function(){ if(el) el.style.display='none'; }, 5000);
}

// ── Dismiss Password Warning ──────────────────────────────────────────────────
function dismissPwdWarning(){
    var banner = document.getElementById('pwd-warning-banner');
    if(banner) banner.classList.add('hide');
    fetch('/clear-password-warning', {
        method:'POST',
        headers:{'Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content}
    });
}

// ── Upload Avatar (Profile Photo) ─────────────────────────────────────────────
function uploadAvatar(file){
    if(!file) return;
    if(!file.type.startsWith('image/')){ showAlert('perfil-alert','error','Debe seleccionar un archivo de imagen.'); return; }
    if(file.size > 3*1024*1024){ showAlert('perfil-alert','error','La imagen no debe superar los 3MB.'); return; }

    var form = new FormData();
    form.append('avatar', file);
    form.append('_token', document.querySelector('meta[name=csrf-token]').content);

    fetch('/student/avatar', {
        method:'POST',
        headers:{'Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},
        body: form
    })
    .then(function(r){ return r.json(); })
    .then(function(d){
        if(d.success){
            showAlert('perfil-alert','success', d.message);
            // Actualizar preview en modal
            var preview = document.getElementById('perfil-avatar-preview');
            var initPreview = document.getElementById('perfil-avatar-init-preview');
            if(initPreview) {
                // Si no había imagen antes, reemplazamos el div de iniciales por un tag img
                initPreview.outerHTML = '<img src="'+d.avatar_url+'" id="perfil-avatar-preview" style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:2px solid var(--bor)">';
            } else if(preview) {
                preview.src = d.avatar_url;
            }

            // Actualizar avatar en el navbar
            var navImg = document.getElementById('nav-avatar-img');
            var navInit = document.getElementById('nav-avatar-init');
            if(navInit) {
                navInit.outerHTML = '<img src="'+d.avatar_url+'" class="profile-avatar" alt="Avatar" id="nav-avatar-img" style="object-fit:cover">';
            } else if(navImg) {
                navImg.src = d.avatar_url;
            }

            // Actualizar avatar en el dropdown
            var dropImg = document.getElementById('drop-avatar-img');
            var dropInit = document.getElementById('drop-avatar-init');
            if(dropInit) {
                dropInit.outerHTML = '<img src="'+d.avatar_url+'" class="profile-avatar lg" alt="Avatar" id="drop-avatar-img" style="object-fit:cover">';
            } else if(dropImg) {
                dropImg.src = d.avatar_url;
            }
        } else {
            showAlert('perfil-alert','error', d.message);
        }
    })
    .catch(function(){ showAlert('perfil-alert','error','Error al subir la imagen.'); });
}

// ── Share Offer (Compartir oferta) ──────────────────────────────────────────
function shareOffer() {
    if (!activeItemId) return;
    
    // Buscar la oferta en memoria para obtener el título
    var offer = loadedOffers.find(function(item) { return item.id === activeItemId; });
    var title = offer ? offer.title : 'Oferta de Empleo';
    var company = (offer && offer.company) ? offer.company.name : '';
    
    // Generar URL robusta para producción (usando window.location.origin y path principal)
    var shareUrl = window.location.origin + window.location.pathname + '?offer=' + activeItemId;
    var shareText = 'Mira esta oferta de trabajo: ' + title + (company ? ' en ' + company : '') + ' - Bolsa Laboral';

    if (navigator.share) {
        navigator.share({
            title: title,
            text: shareText,
            url: shareUrl
        }).catch(function() {
            // Fallback si el usuario cancela o hay error
            copyToClipboard(shareUrl);
        });
    } else {
        copyToClipboard(shareUrl);
    }
}

function copyToClipboard(text) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(function() {
            showToastNotify('¡Enlace de oferta copiado al portapapeles!');
        }).catch(function() {
            fallbackCopy(text);
        });
    } else {
        fallbackCopy(text);
    }
}

function fallbackCopy(text) {
    var textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.position = "fixed";  // Evitar scroll
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    try {
        document.execCommand('copy');
        showToastNotify('¡Enlace de oferta copiado al portapapeles!');
    } catch (err) {
        console.error('Error al copiar', err);
    }
    document.body.removeChild(textArea);
}

function showToastNotify(msg) {
    var toast = document.getElementById('toast-notify-el');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'toast-notify-el';
        toast.className = 'toast-notify';
        document.body.appendChild(toast);
    }
    toast.innerHTML = '<span class="material-symbols-outlined" style="font-size:18px;color:var(--secc)">link</span> ' + msg;
    toast.classList.add('show');
    
    setTimeout(function() {
        if (toast) toast.classList.remove('show');
    }, 3000);
}

/* ===== Notificaciones del estudiante ===== */
var studentNotifOpen = false;
var studentNotifData = [];

function toggleStudentNotifMenu() {
    var menu = document.getElementById('student-notifications-menu');
    studentNotifOpen = !studentNotifOpen;
    if (studentNotifOpen) {
        menu.style.display = 'block';
        fetchStudentNotifications();
    } else {
        menu.style.display = 'none';
    }
}

function fetchStudentNotifications() {
    fetch('/notifications', {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) {
            studentNotifData = data.notifications || [];
            renderStudentNotifications();
            updateStudentBadge(data.unread_count || 0);
        }
    });
}

function handleNotifClick(el) {
    var link = el.getAttribute('data-link');
    if (link && link !== '#' && link !== '') {
        window.location.href = link;
    }
}

function renderStudentNotifications() {
    var list = document.getElementById('student-notifications-list');
    if (!list) return;

    var now = new Date();
    // Filter out read notifications that are older than 1 minute (60000 ms)
    var visibleNotifications = studentNotifData.filter(function(n) {
        if (!n.read_at) return true; // Unread notifications are always shown
        var readTime = new Date(n.read_at);
        return (now - readTime) < 60000;
    });

    if (!visibleNotifications.length) {
        list.innerHTML = '<div style="padding:32px 20px;text-align:center;"><span class="material-symbols-outlined" style="font-size:40px;color:var(--bor);display:block;margin-bottom:8px;">notifications_none</span><p style="font-weight:600;color:var(--txt);">Todo está al día</p><p style="font-size:13px;color:var(--tm);margin-top:4px;">Las novedades importantes aparecerán aquí.</p></div>';
        return;
    }
    list.innerHTML = visibleNotifications.map(function(n) {
        var isUnread = !n.read_at;
        var border = isUnread ? 'border-left:3px solid var(--pri);background:rgba(0,39,65,.03);' : '';
        var time = n.created_at ? new Date(n.created_at).toLocaleString('es-ES', {day:'numeric',month:'short',hour:'2-digit',minute:'2-digit'}) : '';
        var bgOut = isUnread ? 'rgba(0,39,65,.03)' : 'transparent';
        var link = esc(n.link || '');
        return '<div data-link="' + link + '" onclick="handleNotifClick(this)" style="padding:12px 16px;border-bottom:1px solid var(--bor);cursor:pointer;transition:background .15s;' + border + '" onmouseover="this.style.background=\'var(--bg)\'" onmouseout="this.style.background=\'' + bgOut + '\'">' +
            '<div style="display:flex;align-items:flex-start;gap:10px;">' +
            '<span class="material-symbols-outlined" style="font-size:20px;color:var(--pri);margin-top:2px;">notifications</span>' +
            '<div style="flex:1;min-width:0;">' +
            '<p style="font-weight:600;font-size:13px;color:var(--txt);margin:0;line-height:1.3;">' + (n.title||'') + '</p>' +
            '<p style="font-size:12px;color:var(--tv);margin:4px 0 0 0;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">' + (n.message||'') + '</p>' +
            '<div style="display:flex;align-items:center;justify-content:space-between;margin-top:6px;">' +
            '<span style="font-size:11px;color:var(--tm);">' + time + '</span>' +
            (isUnread ? '<span style="width:8px;height:8px;background:#dc2626;border-radius:50%;display:inline-block;"></span>' : '') +
            '</div></div></div></div>';
    }).join('');
}

function updateStudentBadge(count) {
    var badge = document.getElementById('student-notifications-badge');
    if (!badge) return;
    badge.style.display = count > 0 ? 'block' : 'none';
}

function markStudentNotifRead() {
    fetch('/notifications/read-all', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json', 'Content-Type': 'application/json' }
    })
    .then(function(r) { return r.json(); })
    .then(function(data) { if (data.success) fetchStudentNotifications(); });
}

document.addEventListener('click', function(e) {
    var menu = document.getElementById('student-notifications-menu');
    var btn = document.getElementById('student-notifications-button');
    if (menu && menu.style.display === 'block') {
        if (!menu.contains(e.target) && btn && !btn.contains(e.target)) {
            studentNotifOpen = false;
            menu.style.display = 'none';
        }
    }
});
</script>


</body>
</html>
