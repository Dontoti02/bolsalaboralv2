<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $config['application_name'] ?? 'Bolsa Laboral' }} — Encuentra tu Oportunidad</title>
    <meta name="description" content="Explora cientos de ofertas laborales verificadas de las mejores empresas. Encuentra tu próximo trabajo aquí.">
    <meta name="theme-color" content="{{ $config['primary_color'] ?? '#002741' }}">
    <link rel="icon" href="{{ $config['favicon'] ?? '/assets/favicon.png' }}" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL@20..48,100..700,0..1&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')

    <style>
        :root {
            --pri: {{ $config['primary_color'] ?? '#002741' }};
            --pri-rgb: 0, 39, 65;
            --sec: {{ $config['secondary_color'] ?? '#006b60' }};
            --sec-c: {{ $config['secondary_container_color'] ?? '#7df7e4' }};
            --acc: {{ $config['accent_color'] ?? '#ff9f43' }};
            --bg: #f4f6fa;
            --sur: #ffffff;
            --sur-c: #eef0f4;
            --bor: #d6d9de;
            --txt: #1a1d21;
            --txt-v: #4a5058;
            --txt-m: #8a919c;
            --radius: 16px;
            --radius-sm: 10px;
        }

        .dark {
            --bg: #0f1117;
            --sur: #1a1d24;
            --sur-c: #252830;
            --bor: #2d3038;
            --txt: #e8eaed;
            --txt-v: #abafb6;
            --txt-m: #6b7078;
        }

        @media (prefers-color-scheme: dark) {
            :root:not(.light) {
                --bg: #0f1117;
                --sur: #1a1d24;
                --sur-c: #252830;
                --bor: #2d3038;
                --txt: #e8eaed;
                --txt-v: #abafb6;
                --txt-m: #6b7078;
            }
        }

        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--txt);line-height:1.6;-webkit-font-smoothing:antialiased}
        img{max-width:100%;display:block}
        button{cursor:pointer;font-family:inherit}
        a{text-decoration:none;color:inherit}
        :focus-visible{outline:2px solid var(--pri);outline-offset:2px;border-radius:4px}

        .material-symbols-outlined{font-variation-settings:'FILL'0,'wght'400,'GRAD'0,'opsz'24;vertical-align:middle;line-height:1}
        .filled{font-variation-settings:'FILL'1,'wght'400,'GRAD'0,'opsz'24}
        .opsz20{font-variation-settings:'FILL'0,'wght'400,'GRAD'0,'opsz'20}
        .opsz28{font-variation-settings:'FILL'0,'wght'400,'GRAD'0,'opsz'28}

        .container{max-width:1280px;margin:0 auto;padding:0 24px}
        @media(max-width:640px){.container{padding:0 16px}}

        .truncate{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
        .line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
        .sr-only{position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0,0,0,0)}

        @keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
        @keyframes fadeIn{from{opacity:0}to{opacity:1}}
        @keyframes scaleIn{from{opacity:0;transform:scale(.94)}to{opacity:1;transform:scale(1)}}
        @keyframes pulseGlow{0%,100%{opacity:.5}50%{opacity:1}}
        @keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-12px)}}
        @keyframes shimmer{0%{background-position:-800px 0}100%{background-position:800px 0}}

        .anim-fade-up{animation:fadeUp .6s cubic-bezier(.16,1,.3,1) both}
        .anim-fade-in{animation:fadeIn .5s ease both}
        .anim-scale{animation:scaleIn .35s cubic-bezier(.16,1,.3,1) both}

        /* ── NAVBAR ── */
        .navbar{position:sticky;top:0;z-index:100;background:rgba(255,255,255,.85);backdrop-filter:blur(16px) saturate(180%);border-bottom:1px solid var(--bor);transition:background .3s}
        .dark .navbar,.navbar.dark-mode{background:rgba(15,17,23,.85)}
        .navbar-inner{display:flex;align-items:center;justify-content:space-between;height:68px;gap:16px}
        .navbar-brand{display:flex;align-items:center;gap:10px;font-family:'Plus Jakarta Sans',sans-serif;font-size:20px;font-weight:800;color:var(--pri);letter-spacing:-.03em;transition:opacity .15s}
        .navbar-brand:hover{opacity:.8}
        .nav-links{display:none;gap:32px}@media(min-width:768px){.nav-links{display:flex}}
        .nav-link{font-size:14px;font-weight:500;color:var(--txt-v);transition:color .15s;position:relative;padding:4px 0}
        .nav-link::after{content:'';position:absolute;bottom:-2px;left:0;width:0;height:2px;background:var(--pri);transition:width .25s;border-radius:2px}
        .nav-link:hover{color:var(--pri)}.nav-link:hover::after{width:100%}
        .navbar-actions{display:flex;align-items:center;gap:8px}
        .btn-ghost{display:inline-flex;align-items:center;gap:6px;padding:9px 18px;border-radius:var(--radius-sm);font-size:14px;font-weight:600;color:var(--txt);background:transparent;border:none;transition:background .15s}
        .btn-ghost:hover{background:var(--sur-c)}
        .btn-primary{display:inline-flex;align-items:center;gap:6px;padding:10px 22px;background:var(--pri);color:#fff;border:none;border-radius:var(--radius-sm);font-size:14px;font-weight:600;transition:all .2s;box-shadow:0 2px 8px rgba(var(--pri-rgb),.25)}
        .btn-primary:hover{transform:translateY(-1px);box-shadow:0 4px 14px rgba(var(--pri-rgb),.35)}
        .btn-primary:active{transform:translateY(0)}

        /* ── HERO ── */
        .hero{position:relative;padding:96px 24px 100px;text-align:center;overflow:hidden;background:linear-gradient(160deg,var(--pri) 0%,#0a3452 40%,#004d45 70%,var(--sec) 100%)}
        .hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 600px 400px at 20% 30%,rgba(255,255,255,.06),transparent),radial-gradient(ellipse 500px 500px at 80% 70%,rgba(125,247,228,.08),transparent);pointer-events:none}
        .hero-grid{position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.04) 1px,transparent 0);background-size:40px 40px;pointer-events:none;opacity:.5}
        .hero-blob{position:absolute;border-radius:50%;filter:blur(80px);pointer-events:none}
        .hero-content{position:relative;z-index:5;max-width:820px;margin:0 auto}
        .hero-badge{display:inline-flex;align-items:center;gap:6px;padding:6px 18px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);border-radius:100px;color:#fff;font-size:13px;font-weight:500;margin-bottom:28px;backdrop-filter:blur(8px)}
        .hero-title{font-family:'Plus Jakarta Sans',sans-serif;font-size:clamp(34px,5.5vw,56px);font-weight:800;color:#fff;line-height:1.12;letter-spacing:-.03em;margin-bottom:18px}
        .hero-title .highlight{background:linear-gradient(135deg,var(--sec-c),#b8f7ec);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
        .hero-sub{font-size:17px;color:rgba(255,255,255,.75);max-width:560px;margin:0 auto 36px;line-height:1.7}
        .hero-stats{display:flex;flex-wrap:wrap;justify-content:center;gap:12px;margin-bottom:36px}
        .hero-stat{display:flex;align-items:center;gap:10px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);backdrop-filter:blur(8px);border-radius:100px;padding:10px 22px;color:#fff;transition:background .2s}
        .hero-stat:hover{background:rgba(255,255,255,.14)}
        .hero-stat-num{font-size:22px;font-weight:700;font-variant-numeric:tabular-nums}
        .hero-stat-lbl{font-size:13px;color:rgba(255,255,255,.7)}
        .hero-search-wrap{max-width:680px;margin:0 auto 24px;background:rgba(255,255,255,.08);backdrop-filter:blur(16px);border:1px solid rgba(255,255,255,.18);border-radius:18px;padding:8px;transition:background .2s}
        .hero-search-wrap:focus-within{background:rgba(255,255,255,.12)}
        .hero-search-inner{display:flex;gap:8px}
        .hero-search-field{position:relative;flex:1;min-width:0}
        .hero-search-field .ico{position:absolute;left:16px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,.5);font-size:22px;pointer-events:none}
        .hero-search-input{width:100%;padding:16px 18px 16px 50px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);border-radius:12px;color:#fff;font-size:15px;font-family:'Inter',sans-serif;outline:none;transition:border-color .2s}
        .hero-search-input::placeholder{color:rgba(255,255,255,.4)}
        .hero-search-input:focus{border-color:rgba(255,255,255,.4)}
        .hero-search-btn{flex-shrink:0;display:flex;align-items:center;gap:8px;padding:16px 28px;background:#fff;color:var(--pri);border:none;border-radius:12px;font-size:15px;font-weight:700;font-family:'Inter',sans-serif;transition:all .2s;white-space:nowrap;box-shadow:0 4px 12px rgba(0,0,0,.15)}
        .hero-search-btn:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(0,0,0,.2)}
        .hero-search-btn:active{transform:translateY(0)}
        .quick-cats{display:flex;flex-wrap:wrap;justify-content:center;gap:8px}
        .quick-cat-btn{padding:7px 18px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.18);color:rgba(255,255,255,.8);border-radius:100px;font-size:13px;font-family:'Inter',sans-serif;transition:all .2s}
        .quick-cat-btn:hover,.quick-cat-btn.active{background:#fff;color:var(--pri);font-weight:600;border-color:#fff;transform:translateY(-1px)}

        /* ── MARQUEE ── */
        .marquee-bar{background:var(--sur);border-bottom:1px solid var(--bor);padding:16px 0;overflow:hidden}
        .marquee-track{display:flex;gap:40px;width:max-content;animation:marquee 35s linear infinite;will-change:transform}
        .marquee-track:hover{animation-play-state:paused}
        @keyframes marquee{0%{transform:translateX(0)}100%{transform:translateX(-50%)}}
        .marquee-item{display:flex;align-items:center;gap:12px;flex-shrink:0;white-space:nowrap;padding:4px 16px 4px 4px;background:var(--sur-c);border-radius:100px;border:1px solid var(--bor);transition:background .15s}
        .marquee-item:hover{background:var(--sur)}
        .marquee-logo-wrap{width:32px;height:32px;border-radius:10px;overflow:hidden;display:flex;align-items:center;justify-content:center;background:var(--sur);flex-shrink:0}
        .marquee-logo-wrap img{width:100%;height:100%;object-fit:contain;padding:3px}
        .marquee-name{font-size:13px;font-weight:500;color:var(--txt)}

        /* ── MAIN ── */
        .main-section{padding:56px 0 64px}
        .content-layout{display:flex;gap:28px;align-items:flex-start}
        @media(max-width:900px){.content-layout{flex-direction:column}}

        /* ── FILTERS ── */
        .filters-sidebar{width:270px;flex-shrink:0;background:var(--sur);border:1px solid var(--bor);border-radius:var(--radius);padding:24px;position:sticky;top:88px;max-height:calc(100vh - 100px);overflow-y:auto;transition:all .3s}
        @media(max-width:900px){.filters-sidebar{display:none;position:fixed;top:0;left:0;right:0;bottom:0;z-index:300;max-height:none;border-radius:0;padding:24px;overflow-y:auto;animation:fadeIn .2s ease}}
        .filters-sidebar.open{display:block}
        .filters-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;padding-bottom:14px;border-bottom:2px solid var(--sur-c)}
        .filters-title{font-size:16px;font-weight:700}
        .filters-close{display:none;background:none;border:none;color:var(--txt-v);padding:6px;border-radius:8px}
        @media(max-width:900px){.filters-close{display:flex}}
        .filter-group{margin-bottom:20px}
        .filter-label{display:block;font-size:11px;font-weight:700;color:var(--txt-m);text-transform:uppercase;letter-spacing:.07em;margin-bottom:8px}
        .filter-select{width:100%;padding:10px 14px;background:var(--bg);border:1px solid var(--bor);border-radius:var(--radius-sm);font-size:14px;font-family:'Inter',sans-serif;color:var(--txt);outline:none;transition:border-color .2s;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7078' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;padding-right:36px}
        .filter-select:focus{border-color:var(--pri)}
        .filter-radios{display:flex;flex-direction:column;gap:6px}
        .filter-radio-label{display:flex;align-items:center;gap:8px;font-size:14px;color:var(--txt);cursor:pointer;padding:5px 8px;border-radius:8px;transition:background .15s}
        .filter-radio-label:hover{background:var(--sur-c)}
        .filter-radio-label input{accent-color:var(--pri);width:16px;height:16px;cursor:pointer;flex-shrink:0}
        .btn-clear-filters{width:100%;padding:10px;border:1px solid var(--bor);background:transparent;color:var(--txt-v);border-radius:var(--radius-sm);font-size:13px;font-family:'Inter',sans-serif;transition:all .15s}
        .btn-clear-filters:hover{background:var(--sur-c);border-color:var(--txt-m)}
        .mobile-filter-btn{display:none;align-items:center;gap:6px;padding:10px 18px;background:var(--sur);border:1px solid var(--bor);border-radius:var(--radius-sm);font-size:14px;font-weight:500;color:var(--txt);transition:background .15s}
        .mobile-filter-btn:hover{background:var(--sur-c)}
        @media(max-width:900px){.mobile-filter-btn{display:inline-flex}}

        /* ── OFFERS ── */
        .offers-col{flex:1;min-width:0}
        .offers-header{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:24px;flex-wrap:wrap}
        .offers-header-left h2{font-family:'Plus Jakarta Sans',sans-serif;font-size:24px;font-weight:700;color:var(--txt)}
        .offers-count-label{font-size:13px;color:var(--txt-m);margin-top:4px}
        .offers-header-actions{display:flex;align-items:center;gap:10px}
        .inline-search-wrap{position:relative}
        .inline-search-wrap .ico{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--txt-m);font-size:18px;pointer-events:none;font-variation-settings:'FILL'0,'wght'300,'GRAD'0,'opsz'20}
        .inline-search-input{padding:10px 14px 10px 38px;border:1px solid var(--bor);background:var(--sur);border-radius:var(--radius-sm);font-size:14px;font-family:'Inter',sans-serif;outline:none;width:220px;transition:all .2s;color:var(--txt)}
        .inline-search-input:focus{border-color:var(--pri);width:260px;box-shadow:0 0 0 3px rgba(var(--pri-rgb),.08)}
        .inline-search-input::placeholder{color:var(--txt-m)}

        .offers-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px}

        /* ── JOB CARD ── */
        .job-card{background:var(--sur);border:1px solid var(--bor);border-radius:var(--radius);padding:20px;display:flex;flex-direction:column;gap:14px;cursor:pointer;transition:all .25s cubic-bezier(.16,1,.3,1)}
        .job-card:hover{transform:translateY(-4px);box-shadow:0 12px 32px rgba(var(--pri-rgb),.1);border-color:rgba(var(--pri-rgb),.2)}
        .job-card:active{transform:translateY(-1px)}
        .card-top{display:flex;align-items:center;gap:12px}
        .card-logo{width:44px;height:44px;border-radius:12px;overflow:hidden;border:1px solid var(--bor);background:var(--sur-c);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:14px;font-weight:700;color:#fff}
        .card-logo img{width:100%;height:100%;object-fit:contain;padding:3px}
        .card-company{font-size:13px;color:var(--txt-v);font-weight:500}
        .card-date{font-size:11px;color:var(--txt-m)}
        .card-title{font-size:16px;font-weight:600;color:var(--txt);line-height:1.4;transition:color .2s}
        .job-card:hover .card-title{color:var(--pri)}
        .card-tags{display:flex;flex-wrap:wrap;gap:6px}
        .tag{padding:4px 12px;border-radius:100px;font-size:11px;font-weight:500}
        .tag-cat{background:rgba(var(--pri-rgb),.08);color:var(--pri)}
        .tag-loc{background:var(--sur-c);color:var(--txt-v)}
        .card-divider{height:1px;background:var(--bor);margin:2px 0}
        .card-footer{display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap}
        .card-salary{display:inline-flex;align-items:center;gap:6px;background:rgba(0,107,96,.08);border-radius:100px;padding:5px 14px;font-size:12px;font-weight:600;color:var(--sec)}
        .card-location{display:flex;align-items:center;gap:4px;font-size:12px;color:var(--txt-m)}
        .card-location .material-symbols-outlined{font-size:14px}

        /* ── SKELETON ── */
        .skeleton{background:linear-gradient(90deg,var(--sur-c) 25%,var(--bg) 50%,var(--sur-c) 75%);background-size:800px 100%;animation:shimmer 1.4s ease-in-out infinite;border-radius:8px}
        .skeleton-card{background:var(--sur);border:1px solid var(--bor);border-radius:var(--radius);padding:20px;display:flex;flex-direction:column;gap:14px}

        /* ── PAGINATION ── */
        .load-more-wrap{text-align:center;margin-top:32px}
        .btn-load-more{display:inline-flex;align-items:center;gap:8px;padding:14px 36px;background:var(--pri);color:#fff;border:none;border-radius:var(--radius-sm);font-size:15px;font-weight:600;font-family:'Inter',sans-serif;transition:all .2s;box-shadow:0 2px 8px rgba(var(--pri-rgb),.2)}
        .btn-load-more:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(var(--pri-rgb),.3)}
        .btn-load-more:disabled{opacity:.5;cursor:not-allowed;transform:none}
        .no-more-label{font-size:13px;color:var(--txt-m);padding:8px 0}

        /* ── COMPANIES SECTION ── */
        .section-header{text-align:center;margin-bottom:40px}
        .section-title{font-family:'Plus Jakarta Sans',sans-serif;font-size:30px;font-weight:800;color:var(--txt);margin-bottom:8px;letter-spacing:-.02em}
        .section-sub{font-size:16px;color:var(--txt-v);max-width:480px;margin:0 auto}
        .companies-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(170px,1fr));gap:16px}
        .company-card{background:var(--sur);border:1px solid var(--bor);border-radius:var(--radius);padding:22px 16px;display:flex;flex-direction:column;align-items:center;gap:12px;cursor:pointer;transition:all .25s cubic-bezier(.16,1,.3,1);text-align:center}
        .company-card:hover{transform:translateY(-4px);box-shadow:0 12px 32px rgba(var(--pri-rgb),.08);border-color:rgba(var(--pri-rgb),.2)}
        .company-logo-wrap{width:64px;height:64px;border-radius:16px;overflow:hidden;border:1px solid var(--bor);background:var(--sur-c);display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .company-logo-wrap img{width:100%;height:100%;object-fit:contain;padding:6px}
        .company-logo-placeholder{width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,var(--pri),#0a3452);color:#fff;font-size:18px;font-weight:700}
        .company-card-name{font-size:14px;font-weight:600;color:var(--txt);line-height:1.3;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
        .company-card:hover .company-card-name{color:var(--pri)}
        .company-card-count{font-size:12px;color:var(--txt-m)}

        /* ── CTA ── */
        .cta-section{margin:56px 0}
        .cta-card{position:relative;overflow:hidden;background:linear-gradient(160deg,var(--pri) 0%,#0a3452 50%,#004d45 100%);border-radius:24px;padding:64px 40px;text-align:center;color:#fff;isolation:isolate}
        .cta-card::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 500px 300px at 70% 50%,rgba(125,247,228,.08),transparent),radial-gradient(ellipse 300px 300px at 20% 80%,rgba(255,159,67,.06),transparent);pointer-events:none;z-index:0}
        .cta-card>*{position:relative;z-index:1}
        .cta-icon{font-size:52px;color:var(--sec-c);display:inline-block;margin-bottom:16px;animation:float 4s ease-in-out infinite}
        .cta-title{font-family:'Plus Jakarta Sans',sans-serif;font-size:30px;font-weight:800;margin-bottom:12px}
        .cta-sub{font-size:17px;color:rgba(255,255,255,.75);max-width:500px;margin:0 auto 32px;line-height:1.6}
        .cta-btn-group{display:flex;flex-wrap:wrap;gap:12px;justify-content:center}
        .cta-btn-primary{display:inline-flex;align-items:center;gap:8px;padding:16px 32px;background:#fff;color:var(--pri);border:none;border-radius:14px;font-size:16px;font-weight:700;transition:all .2s;box-shadow:0 4px 16px rgba(0,0,0,.15)}
        .cta-btn-primary:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(0,0,0,.2)}
        .cta-btn-outline{display:inline-flex;align-items:center;gap:8px;padding:16px 32px;background:transparent;color:#fff;border:1.5px solid rgba(255,255,255,.3);border-radius:14px;font-size:16px;font-weight:600;transition:all .2s}
        .cta-btn-outline:hover{background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.5)}

        /* ── FOOTER ── */
        .footer{background:var(--sur);border-top:1px solid var(--bor);padding:32px 0}
        .footer-inner{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:16px}
        .footer-brand{display:flex;align-items:center;gap:8px;font-weight:700;color:var(--pri);font-family:'Plus Jakarta Sans',sans-serif;font-size:16px}
        .footer-copy{font-size:13px;color:var(--txt-m);text-align:center;flex:1}
        .footer-links{display:flex;gap:16px}
        .footer-link{font-size:14px;font-weight:500;color:var(--txt-v);transition:color .15s}
        .footer-link:hover{color:var(--pri)}

        /* ── MODAL ── */
        .modal-overlay{position:fixed;inset:0;z-index:200;display:none;align-items:center;justify-content:center;padding:20px}
        .modal-overlay.open{display:flex}
        @media(max-width:600px){.modal-overlay{align-items:flex-end;padding:0}.modal-overlay.open .modal-box{border-radius:20px 20px 0 0;max-height:92vh}}
        .modal-bg{position:absolute;inset:0;background:rgba(0,0,0,.5);backdrop-filter:blur(6px);animation:fadeIn .2s ease}
        .modal-box{position:relative;background:var(--sur);border-radius:24px;width:100%;max-width:680px;max-height:88vh;overflow-y:auto;box-shadow:0 32px 80px rgba(0,0,0,.25);animation:scaleIn .3s cubic-bezier(.16,1,.3,1)}
        .modal-header{position:sticky;top:0;background:var(--sur);border-bottom:1px solid var(--bor);padding:24px 24px 18px;display:flex;gap:16px;align-items:flex-start;z-index:10;border-radius:24px 24px 0 0}
        .modal-logo{width:56px;height:56px;border-radius:14px;overflow:hidden;border:1px solid var(--bor);background:var(--sur-c);flex-shrink:0;display:flex;align-items:center;justify-content:center}
        .modal-logo img{width:100%;height:100%;object-fit:contain;padding:4px}
        .modal-title-wrap{flex:1;min-width:0}
        .modal-title{font-size:20px;font-weight:700;color:var(--txt);line-height:1.3;font-family:'Plus Jakarta Sans',sans-serif}
        .modal-company{font-size:14px;color:var(--txt-v);margin-top:4px}
        .modal-close{flex-shrink:0;width:36px;height:36px;border-radius:50%;border:none;background:var(--sur-c);display:flex;align-items:center;justify-content:center;color:var(--txt-v);transition:background .15s}
        .modal-close:hover{background:var(--bor)}
        .modal-body{padding:24px;display:flex;flex-direction:column;gap:20px}
        .modal-tags{display:flex;flex-wrap:wrap;gap:8px}
        .modal-tag{padding:6px 16px;border-radius:100px;font-size:13px;font-weight:500}
        .modal-salary-box{display:flex;align-items:center;gap:14px;padding:20px;background:rgba(0,107,96,.06);border:1px solid rgba(0,107,96,.15);border-radius:14px;transition:background .2s}
        .modal-salary-box .material-symbols-outlined{font-size:28px;color:var(--sec)}
        .modal-salary-lbl{font-size:11px;font-weight:700;color:var(--txt-m);text-transform:uppercase;letter-spacing:.06em}
        .modal-salary-val{font-size:22px;font-weight:700;color:var(--sec);font-variant-numeric:tabular-nums}
        .modal-section-title{font-size:11px;font-weight:700;color:var(--txt-m);text-transform:uppercase;letter-spacing:.07em;margin-bottom:10px}
        .modal-text{font-size:15px;line-height:1.7;color:var(--txt);white-space:pre-line}
        .modal-meta{display:flex;align-items:center;gap:6px;font-size:14px;color:var(--txt-v)}
        .modal-meta .material-symbols-outlined{font-size:18px;color:var(--txt-m)}
        .modal-footer{position:sticky;bottom:0;background:var(--sur);border-top:1px solid var(--bor);padding:18px 24px;display:flex;gap:12px;border-radius:0 0 24px 24px}
        @media(max-width:600px){.modal-footer{padding:16px 20px}}
        .modal-apply-btn{flex:1;display:flex;align-items:center;justify-content:center;gap:8px;padding:14px;background:var(--pri);color:#fff;border:none;border-radius:12px;font-size:15px;font-weight:700;transition:all .2s}
        .modal-apply-btn:hover{opacity:.92;transform:translateY(-1px)}
        .modal-cancel-btn{padding:14px 24px;border:1px solid var(--bor);color:var(--txt-v);background:transparent;border-radius:12px;font-size:14px;font-family:'Inter',sans-serif;transition:background .15s}
        .modal-cancel-btn:hover{background:var(--sur-c)}

        /* ── EMPTY STATE ── */
        .empty-state{text-align:center;padding:80px 20px;grid-column:1/-1}
        .empty-state-icon{font-size:56px;color:var(--txt-m);display:block;margin-bottom:16px}
        .empty-state-title{font-size:18px;font-weight:600;color:var(--txt);margin-bottom:6px}
        .empty-state-desc{font-size:14px;color:var(--txt-m)}

        /* ── BACK TO TOP ── */
        .back-to-top{position:fixed;bottom:32px;right:32px;z-index:50;width:48px;height:48px;border-radius:50%;background:var(--pri);color:#fff;border:none;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 16px rgba(var(--pri-rgb),.3);transition:all .3s;opacity:0;pointer-events:none;transform:translateY(12px)}
        .back-to-top.visible{opacity:1;pointer-events:auto;transform:translateY(0)}
        .back-to-top:hover{transform:translateY(-3px);box-shadow:0 6px 24px rgba(var(--pri-rgb),.4)}

        @media(max-width:640px){
            .hero{padding:56px 16px 64px}
            .hero-title{font-size:28px}
            .hero-sub{font-size:15px}
            .hero-search-inner{flex-direction:column}
            .hero-search-btn{width:100%;justify-content:center}
            .inline-search-input{width:100%}.inline-search-input:focus{width:100%}
            .offers-header{flex-direction:column;align-items:stretch}
            .offers-header-actions{flex-wrap:wrap}
            .offers-grid{grid-template-columns:1fr}
            .companies-grid{grid-template-columns:repeat(2,1fr)}
            .cta-card{padding:40px 24px;border-radius:16px}
            .cta-title{font-size:24px}
            .back-to-top{bottom:20px;right:20px;width:44px;height:44px}
        }
        @media(min-width:640px){.sm\\:inline{display:inline}}
        .hidden{display:none!important}
    </style>
</head>
<body>

<!-- ═══════════ NAVBAR ═══════════ -->
<nav class="navbar" role="navigation">
    <div class="container">
        <div class="navbar-inner">
            <a href="{{ route('landing') }}" class="navbar-brand" aria-label="Inicio">
                @if(!empty($config['logo']))
                    <img src="{{ $config['logo'] }}" alt="Logo" style="height:32px;width:auto;object-fit:contain;">
                @else
                    <span class="material-symbols-outlined filled" style="font-size:26px;color:var(--pri)">work</span>
                @endif
                <span>{{ $config['application_name'] ?? 'Bolsa Laboral' }}</span>
            </a>
            <div class="nav-links">
                <a href="#ofertas" class="nav-link">Empleos</a>
                <a href="#empresas" class="nav-link">Empresas</a>
                <a href="#registro" class="nav-link">Registro</a>
            </div>
            <div class="navbar-actions">
                <a href="{{ route('login') }}" class="btn-ghost" aria-label="Iniciar sesión">
                    <span class="material-symbols-outlined opsz20">login</span>
                    <span class="hidden sm:inline">Entrar</span>
                </a>
                <a href="{{ route('login') }}#register" class="btn-primary" aria-label="Registrarse">
                    <span class="material-symbols-outlined opsz20">person_add</span>
                    <span>Registrarse</span>
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- ═══════════ HERO ═══════════ -->
<section class="hero" aria-label="Buscador de empleo">
    <div class="hero-grid"></div>
    <div class="hero-blob" style="width:400px;height:400px;background:rgba(125,247,228,.08);top:-120px;right:-60px;"></div>
    <div class="hero-blob" style="width:500px;height:500px;background:rgba(255,255,255,.03);bottom:-180px;left:-120px;animation-delay:2s;"></div>
    <div class="hero-blob" style="width:280px;height:280px;background:rgba(255,159,67,.06);top:40%;right:10%;animation-delay:1s;"></div>

    <div class="hero-content anim-fade-up">
        <div class="hero-badge">
            <span class="material-symbols-outlined filled" style="font-size:16px;color:#fde047;">verified</span>
            Portal oficial de empleo institucional
        </div>

        <h1 class="hero-title">
            Encuentra tu<br>
            <span class="highlight">oportunidad laboral ideal</span>
        </h1>
        <p class="hero-sub">
            Conectamos talento con las mejores empresas verificadas. Explora ofertas,
            postula con un clic y construye tu futuro profesional.
        </p>

        <div class="hero-stats">
            <div class="hero-stat">
                <span class="material-symbols-outlined filled" style="font-size:24px;color:#fde047;">work</span>
                <span><span class="hero-stat-num" id="stat-offers">{{ $totalActiveOffers }}</span> <span class="hero-stat-lbl">ofertas activas</span></span>
            </div>
            <div class="hero-stat">
                <span class="material-symbols-outlined filled" style="font-size:24px;color:var(--sec-c);">domain</span>
                <span><span class="hero-stat-num" id="stat-companies">{{ $totalCompanies }}</span> <span class="hero-stat-lbl">empresas verificadas</span></span>
            </div>
        </div>

        <div class="hero-search-wrap">
            <div class="hero-search-inner">
                <div class="hero-search-field">
                    <span class="material-symbols-outlined ico opsz28">search</span>
                    <input id="hero-search-input" type="text" class="hero-search-input"
                        placeholder="Buscar por cargo, empresa o palabra clave..."
                        enterkeyhint="search"
                        onkeydown="if(event.key==='Enter') triggerSearch()">
                </div>
                <button class="hero-search-btn" onclick="triggerSearch()">
                    <span class="material-symbols-outlined opsz20">search</span>
                    Buscar
                </button>
            </div>
        </div>

        <div class="quick-cats">
            @foreach($categories->take(6) as $cat)
                <button class="quick-cat-btn" onclick="quickFilter('category_id','{{ $cat->id }}',this)" aria-label="Filtrar por {{ $cat->name }}">
                    {{ $cat->name }}
                </button>
            @endforeach
        </div>
    </div>
</section>

<!-- ═══════════ MARQUEE ═══════════ -->
@if($companies->count() > 0)
<div class="marquee-bar" aria-label="Empresas que publican ofertas">
    <div class="marquee-track">
        @php $mq = $companies->concat($companies); @endphp
        @foreach($mq as $c)
        <div class="marquee-item">
            <div class="marquee-logo-wrap">
                @if(!empty($c->logo))
                    <img src="{{ $c->logo }}" alt="{{ $c->name }}" loading="lazy">
                @else
                    <span style="font-size:12px;font-weight:700;color:var(--pri);">{{ strtoupper(substr($c->name,0,2)) }}</span>
                @endif
            </div>
            <span class="marquee-name">{{ $c->name }}</span>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- ═══════════ MAIN CONTENT ═══════════ -->
<div class="container main-section" id="ofertas">
    <!-- Mobile filter button + header -->
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
        <button class="mobile-filter-btn" onclick="toggleMobileFilters()" aria-label="Abrir filtros">
            <span class="material-symbols-outlined opsz20">filter_list</span>
            Filtros
        </button>
        <div class="offers-header-actions">
            <div class="inline-search-wrap">
                <span class="material-symbols-outlined ico">search</span>
                <input id="inline-search-input" type="text" class="inline-search-input"
                    placeholder="Buscar..."
                    enterkeyhint="search"
                    oninput="debounceSearch()"
                    aria-label="Buscar ofertas">
            </div>
        </div>
    </div>

    <div class="content-layout" style="margin-top:4px;">
        <!-- ── FILTERS SIDEBAR ── -->
        <aside class="filters-sidebar" id="filters-sidebar" aria-label="Filtros de búsqueda">
            <div class="filters-header">
                <span class="filters-title">Filtros</span>
                <button class="filters-close" onclick="toggleMobileFilters()" aria-label="Cerrar filtros">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <div class="filter-group">
                <label class="filter-label" for="filter-category">Categoría</label>
                <select id="filter-category" class="filter-select" onchange="applyFilters()">
                    <option value="">Todas las categorías</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label" for="filter-location">Modalidad</label>
                <select id="filter-location" class="filter-select" onchange="applyFilters()">
                    <option value="">Todas las modalidades</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label" for="filter-schedule">Jornada</label>
                <select id="filter-schedule" class="filter-select" onchange="applyFilters()">
                    <option value="">Cualquier jornada</option>
                    @foreach($workSchedules as $ws)
                        <option value="{{ $ws->id }}">{{ $ws->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label" for="filter-contract">Tipo de Contrato</label>
                <select id="filter-contract" class="filter-select" onchange="applyFilters()">
                    <option value="">Cualquier contrato</option>
                    @foreach($contractTypes as $ct)
                        <option value="{{ $ct->id }}">{{ $ct->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">Rango Salarial</label>
                <div class="filter-radios">
                    @php $salaryOpts = ['' => 'Cualquier salario', 'under_1000' => 'Menos de S/ 1,000', '1000_to_2000' => 'S/ 1,000 — 2,000', '2000_to_4000' => 'S/ 2,000 — 4,000', 'above_4000' => 'Más de S/ 4,000']; @endphp
                    @foreach($salaryOpts as $val => $lbl)
                    <label class="filter-radio-label">
                        <input type="radio" name="salary_filter" value="{{ $val }}" {{ $val===''?'checked':'' }} onchange="applyFilters()">
                        {{ $lbl }}
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="filter-group">
                <label class="filter-label" for="filter-sort">Ordenar por</label>
                <select id="filter-sort" class="filter-select" onchange="applyFilters()">
                    <option value="recent">Más recientes</option>
                    <option value="salary_desc">Mayor salario</option>
                    <option value="salary_asc">Menor salario</option>
                    <option value="title_asc">Título A-Z</option>
                </select>
            </div>

            <button class="btn-clear-filters" onclick="clearFilters()">Limpiar filtros</button>
        </aside>

        <!-- ── OFFERS COLUMN ── -->
        <div class="offers-col">
            <div class="offers-header">
                <div class="offers-header-left">
                    <h2>Ofertas Laborales</h2>
                    <p id="offers-count-label" class="offers-count-label">Cargando...</p>
                </div>
            </div>

            <div id="offers-grid" class="offers-grid" role="list" aria-label="Lista de ofertas laborales">
                @for($i=0;$i<6;$i++)
                <div class="skeleton-card">
                    <div style="display:flex;gap:12px;align-items:center;">
                        <div class="skeleton" style="width:44px;height:44px;border-radius:12px;flex-shrink:0;"></div>
                        <div style="flex:1;display:flex;flex-direction:column;gap:6px;">
                            <div class="skeleton" style="height:13px;width:60%;"></div>
                            <div class="skeleton" style="height:11px;width:40%;"></div>
                        </div>
                    </div>
                    <div class="skeleton" style="height:16px;width:85%;"></div>
                    <div class="skeleton" style="height:14px;width:70%;"></div>
                    <div style="display:flex;gap:6px;">
                        <div class="skeleton" style="height:22px;width:70px;border-radius:100px;"></div>
                        <div class="skeleton" style="height:22px;width:60px;border-radius:100px;"></div>
                    </div>
                    <div style="height:1px;background:transparent;margin:2px 0;"></div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <div class="skeleton" style="height:26px;width:110px;border-radius:100px;"></div>
                        <div class="skeleton" style="height:14px;width:80px;"></div>
                    </div>
                </div>
                @endfor
            </div>

            <div class="load-more-wrap">
                <button id="load-more-btn" class="btn-load-more hidden" onclick="loadMoreOffers()">
                    <span class="material-symbols-outlined opsz20">expand_more</span>
                    Cargar más ofertas
                </button>
                <p id="no-more-label" class="no-more-label hidden"></p>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════ COMPANIES SECTION ═══════════ -->
@if($companies->count() > 0)
<div class="container" id="empresas">
    <div style="padding:56px 0 20px;">
        <div class="section-header anim-fade-up" data-observe>
            <h2 class="section-title">Empresas que confían en nosotros</h2>
            <p class="section-sub">Organizaciones verificadas que publican ofertas en nuestra plataforma</p>
        </div>
        <div class="companies-grid">
            @foreach($companies as $company)
            <button class="company-card" onclick="filterByCompanySearch('{{ addslashes($company->name) }}')" aria-label="Ver ofertas de {{ $company->name }}" data-observe style="animation:fadeUp .5s cubic-bezier(.16,1,.3,1) both;animation-delay:{{ $loop->index * 0.05 }}s;opacity:0;">
                <div class="company-logo-wrap">
                    @if(!empty($company->logo))
                        <img src="{{ $company->logo }}" alt="{{ $company->name }}" loading="lazy">
                    @else
                        <div class="company-logo-placeholder">{{ strtoupper(substr($company->name,0,2)) }}</div>
                    @endif
                </div>
                <p class="company-card-name">{{ $company->name }}</p>
                <span class="company-card-count">
                    {{ $company->active_offers_count }} {{ $company->active_offers_count === 1 ? 'oferta' : 'ofertas' }}
                </span>
            </button>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- ═══════════ CTA BANNER ═══════════ -->
<div class="container cta-section" id="registro">
    <div class="cta-card" data-observe style="animation:scaleIn .5s cubic-bezier(.16,1,.3,1) both;opacity:0;">
        <span class="material-symbols-outlined cta-icon filled">rocket_launch</span>
        <h2 class="cta-title">¿Eres una empresa?</h2>
        <p class="cta-sub">Publica tus ofertas laborales y conecta con cientos de candidatos calificados de nuestra institución.</p>
        <div class="cta-btn-group">
            <a href="{{ route('login') }}" class="cta-btn-primary">
                <span class="material-symbols-outlined opsz20">domain_add</span>
                Registrar mi empresa
            </a>
            <a href="{{ route('login') }}" class="cta-btn-outline">
                <span class="material-symbols-outlined opsz20">login</span>
                Iniciar sesión
            </a>
        </div>
    </div>
</div>

<!-- ═══════════ FOOTER ═══════════ -->
<footer class="footer">
    <div class="container">
        <div class="footer-inner">
            <div class="footer-brand">
                <span class="material-symbols-outlined filled" style="font-size:22px;">work</span>
                {{ $config['application_name'] ?? 'Bolsa Laboral' }}
            </div>
            <p class="footer-copy">&copy; {{ date('Y') }} {{ $config['application_name'] ?? 'Bolsa Laboral' }}. Todos los derechos reservados.</p>
            <div class="footer-links">
                <a href="{{ route('login') }}" class="footer-link">Iniciar Sesión</a>
                <a href="#ofertas" class="footer-link">Empleos</a>
            </div>
        </div>
    </div>
</footer>

<!-- ═══════════ BACK TO TOP ═══════════ -->
<button class="back-to-top" id="back-to-top" onclick="window.scrollTo({top:0,behavior:'smooth'})" aria-label="Volver arriba">
    <span class="material-symbols-outlined opsz20">arrow_upward</span>
</button>

<!-- ═══════════ MODAL ═══════════ -->
<div id="offer-modal" class="modal-overlay" role="dialog" aria-modal="true" aria-label="Detalle de oferta">
    <div class="modal-bg" onclick="closeOfferModal()"></div>
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-logo" id="modal-logo-wrap">
                <span id="modal-logo-initials" style="font-weight:700;color:var(--pri);font-size:18px;"></span>
            </div>
            <div class="modal-title-wrap">
                <h2 class="modal-title" id="modal-title">—</h2>
                <p class="modal-company" id="modal-company">—</p>
            </div>
            <button class="modal-close" onclick="closeOfferModal()" aria-label="Cerrar">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="modal-body">
            <div id="modal-tags" class="modal-tags"></div>

            <div class="modal-salary-box">
                <span class="material-symbols-outlined filled">payments</span>
                <div>
                    <p class="modal-salary-lbl">Salario</p>
                    <p class="modal-salary-val" id="modal-salary">—</p>
                </div>
            </div>

            <div>
                <p class="modal-section-title">Descripción</p>
                <p class="modal-text" id="modal-description"></p>
            </div>

            <div id="modal-req-section">
                <p class="modal-section-title">Requisitos</p>
                <p class="modal-text" id="modal-requirements"></p>
            </div>

            <div id="modal-ben-section" style="display:none;">
                <p class="modal-section-title">Beneficios</p>
                <p class="modal-text" id="modal-benefits"></p>
            </div>

            <div class="modal-meta" id="modal-address-row">
                <span class="material-symbols-outlined">location_on</span>
                <span id="modal-address"></span>
            </div>

            <div class="modal-meta" id="modal-deadline-row" style="display:none;">
                <span class="material-symbols-outlined">event</span>
                <span id="modal-deadline"></span>
            </div>
        </div>
        <div class="modal-footer">
            <a id="modal-apply-btn" href="{{ route('login') }}" class="modal-apply-btn">
                <span class="material-symbols-outlined opsz20">send</span>
                Postularme ahora
            </a>
            <button class="modal-cancel-btn" onclick="closeOfferModal()">Cerrar</button>
        </div>
    </div>
</div>

<script>
// ─── STATE ───────────────────────────────────────────────────────────────────
var currentPage   = 1;
var isLoading     = false;
var totalPages    = 1;
var searchTimer   = null;
var quickCatActive= {};

// ─── INIT ────────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
    loadOffers(1, true);
    initScrollObserver();
});

// ─── SCROLL OBSERVER (animaciones al hacer scroll) ────────────────────────────
function initScrollObserver() {
    if (!window.IntersectionObserver) return;
    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(e) {
            if (e.isIntersecting) {
                e.target.style.opacity = '1';
                e.target.style.animationPlayState = 'running';
                observer.unobserve(e.target);
            }
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('[data-observe]').forEach(function(el) {
        el.style.animationPlayState = 'paused';
        observer.observe(el);
    });
}

// ─── BACK TO TOP ─────────────────────────────────────────────────────────────
var backToTop = document.getElementById('back-to-top');
window.addEventListener('scroll', function() {
    backToTop.classList.toggle('visible', window.scrollY > 500);
}, { passive: true });

// ─── MOBILE FILTERS ──────────────────────────────────────────────────────────
function toggleMobileFilters() {
    document.getElementById('filters-sidebar').classList.toggle('open');
    document.body.style.overflow = document.getElementById('filters-sidebar').classList.contains('open') ? 'hidden' : '';
}

// ─── SEARCH ──────────────────────────────────────────────────────────────────
function triggerSearch() {
    var heroVal = document.getElementById('hero-search-input').value.trim();
    if (heroVal) document.getElementById('inline-search-input').value = heroVal;
    else document.getElementById('inline-search-input').value = '';
    quickCatActive = {};
    document.querySelectorAll('.quick-cat-btn').forEach(function(b) { b.classList.remove('active'); });
    applyFilters();
    document.getElementById('ofertas').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function debounceSearch() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(applyFilters, 350);
}

function applyFilters() {
    loadOffers(1, true);
}

function clearFilters() {
    document.getElementById('filter-category').value = '';
    document.getElementById('filter-location').value = '';
    document.getElementById('filter-schedule').value = '';
    document.getElementById('filter-contract').value = '';
    document.getElementById('filter-sort').value     = 'recent';
    document.getElementById('inline-search-input').value = '';
    document.getElementById('hero-search-input').value   = '';
    var radios = document.querySelectorAll('input[name="salary_filter"]');
    radios.forEach(function(r) { r.checked = (r.value === ''); });
    quickCatActive = {};
    document.querySelectorAll('.quick-cat-btn').forEach(function(b) { b.classList.remove('active'); });
    loadOffers(1, true);
}

function quickFilter(key, value, btn) {
    document.querySelectorAll('.quick-cat-btn').forEach(function(b) { b.classList.remove('active'); });
    if (quickCatActive[key] === value) {
        quickCatActive = {};
        document.getElementById('filter-category').value = '';
    } else {
        quickCatActive = {};
        quickCatActive[key] = value;
        btn.classList.add('active');
        document.getElementById('filter-category').value = value;
    }
    loadOffers(1, true);
    document.getElementById('ofertas').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function filterByCompanySearch(name) {
    document.getElementById('inline-search-input').value = name;
    quickCatActive = {};
    document.querySelectorAll('.quick-cat-btn').forEach(function(b) { b.classList.remove('active'); });
    loadOffers(1, true);
    document.getElementById('ofertas').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// ─── BUILD PARAMS ────────────────────────────────────────────────────────────
function buildParams(page) {
    var params = new URLSearchParams();
    var search = document.getElementById('inline-search-input').value.trim();
    if (search) params.set('search', search);

    var cat  = document.getElementById('filter-category').value;
    var loc  = document.getElementById('filter-location').value;
    var sch  = document.getElementById('filter-schedule').value;
    var con  = document.getElementById('filter-contract').value;
    var sort = document.getElementById('filter-sort').value;
    var salEl= document.querySelector('input[name="salary_filter"]:checked');
    var sal  = salEl ? salEl.value : '';

    if (cat)  params.set('category_id', cat);
    if (loc)  params.set('location_id', loc);
    if (sch)  params.set('work_schedule_id', sch);
    if (con)  params.set('contract_type_id', con);
    if (sort) params.set('sort_by', sort);
    if (sal)  params.set('salary_filter', sal);

    params.set('page', page);
    params.set('per_page', 9);
    return params;
}

// ─── LOAD OFFERS ─────────────────────────────────────────────────────────────
function loadOffers(page, reset) {
    if (isLoading) return;
    isLoading = true;

    var grid       = document.getElementById('offers-grid');
    var loadMoreBtn= document.getElementById('load-more-btn');
    var noMoreLbl  = document.getElementById('no-more-label');

    if (reset) {
        currentPage = 1;
        grid.innerHTML = renderSkeletons(6);
        loadMoreBtn.classList.add('hidden');
        noMoreLbl.classList.add('hidden');
    }

    var params = buildParams(page);
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/buscar-ofertas?' + params.toString(), {
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (!data.success) throw new Error(data.message || 'Error');

        if (reset) grid.innerHTML = '';

        if (data.offers.length === 0 && reset) {
            grid.innerHTML = '<div class="empty-state"><span class="material-symbols-outlined empty-state-icon">search_off</span><p class="empty-state-title">No se encontraron ofertas</p><p class="empty-state-desc">Prueba con otros filtros o palabras clave.</p></div>';
            document.getElementById('offers-count-label').textContent = 'Sin resultados';
        } else {
            data.offers.forEach(function(offer) {
                grid.insertAdjacentHTML('beforeend', renderCard(offer));
            });
            totalPages  = data.last_page;
            currentPage = data.current_page;

            var total = data.total;
            document.getElementById('offers-count-label').textContent =
                total + (total === 1 ? ' oferta encontrada' : ' ofertas encontradas');

            if (data.has_more) {
                loadMoreBtn.classList.remove('hidden');
                noMoreLbl.classList.add('hidden');
            } else {
                loadMoreBtn.classList.add('hidden');
                if (!reset) {
                    noMoreLbl.textContent = 'Has visto todas las ofertas disponibles.';
                    noMoreLbl.classList.remove('hidden');
                }
            }
        }
    })
    .catch(function() {
        grid.innerHTML = '<div class="empty-state"><span class="material-symbols-outlined empty-state-icon">cloud_off</span><p class="empty-state-title">Error al cargar las ofertas</p><p class="empty-state-desc">Verifica tu conexión e inténtalo de nuevo.</p></div>';
    })
    .finally(function() { isLoading = false; });
}

function loadMoreOffers() { loadOffers(currentPage + 1, false); }

// ─── RENDER ──────────────────────────────────────────────────────────────────
function renderSkeletons(n) {
    var html = '';
    for (var i = 0; i < n; i++) {
        html += '<div class="skeleton-card">' +
            '<div style="display:flex;gap:12px;align-items:center;">' +
            '<div class="skeleton" style="width:44px;height:44px;border-radius:12px;flex-shrink:0;"></div>' +
            '<div style="flex:1;display:flex;flex-direction:column;gap:6px;">' +
            '<div class="skeleton" style="height:13px;width:60%;"></div>' +
            '<div class="skeleton" style="height:11px;width:40%;"></div>' +
            '</div></div>' +
            '<div class="skeleton" style="height:16px;width:85%;"></div>' +
            '<div class="skeleton" style="height:14px;width:70%;"></div>' +
            '<div style="display:flex;gap:6px;">' +
            '<div class="skeleton" style="height:22px;width:70px;border-radius:100px;"></div>' +
            '<div class="skeleton" style="height:22px;width:60px;border-radius:100px;"></div>' +
            '</div>' +
            '<div style="height:1px;background:transparent;margin:2px 0;"></div>' +
            '<div style="display:flex;justify-content:space-between;align-items:center;">' +
            '<div class="skeleton" style="height:26px;width:110px;border-radius:100px;"></div>' +
            '<div class="skeleton" style="height:14px;width:80px;"></div>' +
            '</div></div>';
    }
    return html;
}

function esc(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
        .replace(/"/g,'&quot;').replace(/'/g,'&#39;');
}

function renderCard(o) {
    var company  = o.company  || {};
    var category = o.category || {};
    var location = o.location || {};
    var schedule = o.work_schedule || {};

    var name    = company.name || 'Empresa';
    var initials= name.substring(0,2).toUpperCase();
    var logo    = company.logo || '';
    var catName = category.name || '';
    var locName = location.name || '';
    var schName = schedule.name || '';
    var salary  = o.salary ? Number(o.salary).toLocaleString('es-PE') + ' ' + (o.salary_currency || 'SOLES') : 'A tratar';
    var pubDate = '';
    if (o.publication_date) {
        try { pubDate = new Date(o.publication_date).toLocaleDateString('es-PE',{day:'2-digit',month:'short',year:'numeric'}); } catch(e){}
    }
    var addr = [o.province, o.department].filter(Boolean).join(', ');

    var logoHtml = logo
        ? '<img src="' + esc(logo) + '" alt="' + esc(name) + '" loading="lazy">'
        : '<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,var(--pri),#0a3452);color:#fff;font-size:14px;font-weight:700;">' + esc(initials) + '</div>';

    var tags = '';
    if (catName) tags += '<span class="tag tag-cat">' + esc(catName) + '</span>';
    if (locName) tags += '<span class="tag tag-loc">' + esc(locName) + '</span>';
    if (schName) tags += '<span class="tag tag-loc">' + esc(schName) + '</span>';

    var offerJson = JSON.stringify(o).replace(/\\/g,'\\\\').replace(/'/g,"\\'");

    return '<div class="job-card anim-fade-up" onclick=\'openOfferModal(' + offerJson + ')\' style="animation-delay:0s;">' +
        '<div class="card-top">' +
        '<div class="card-logo">' + logoHtml + '</div>' +
        '<div style="min-width:0;">' +
        '<p class="card-company">' + esc(name) + '</p>' +
        (pubDate ? '<p class="card-date">' + esc(pubDate) + '</p>' : '') +
        '</div></div>' +
        '<h3 class="card-title line-clamp-2">' + esc(o.title) + '</h3>' +
        '<div class="card-tags">' + tags + '</div>' +
        '<div class="card-divider"></div>' +
        '<div class="card-footer">' +
        '<div class="card-salary"><span class="material-symbols-outlined filled" style="font-size:14px;">payments</span>' + esc(salary) + '</div>' +
        (addr ? '<span class="card-location"><span class="material-symbols-outlined">location_on</span>' + esc(addr) + '</span>' : '') +
        '</div>' +
        '</div>';
}

// ─── MODAL ────────────────────────────────────────────────────────────────────
function openOfferModal(offer) {
    var company = offer.company || {};
    var name    = company.name || 'Empresa';
    var logo    = company.logo || '';

    document.getElementById('modal-title').textContent   = offer.title || '';
    document.getElementById('modal-company').textContent = name;

    var logoWrap = document.getElementById('modal-logo-wrap');
    if (logo) {
        logoWrap.innerHTML = '<img src="' + esc(logo) + '" alt="' + esc(name) + '" style="width:100%;height:100%;object-fit:contain;padding:5px;">';
    } else {
        logoWrap.innerHTML = '<span style="font-weight:700;color:var(--pri);font-size:18px;">' + name.substring(0,2).toUpperCase() + '</span>';
    }

    var salary = offer.salary
        ? Number(offer.salary).toLocaleString('es-PE') + ' ' + (offer.salary_currency || 'SOLES')
        : 'A tratar';
    document.getElementById('modal-salary').textContent = salary;

    var tagsHtml = '';
    if (offer.category && offer.category.name) tagsHtml += '<span class="modal-tag tag-cat">' + esc(offer.category.name) + '</span>';
    if (offer.location && offer.location.name) tagsHtml += '<span class="modal-tag tag-loc">' + esc(offer.location.name) + '</span>';
    if (offer.work_schedule && offer.work_schedule.name) tagsHtml += '<span class="modal-tag tag-loc">' + esc(offer.work_schedule.name) + '</span>';
    if (offer.contract_type && offer.contract_type.name) tagsHtml += '<span class="modal-tag tag-loc">' + esc(offer.contract_type.name) + '</span>';
    document.getElementById('modal-tags').innerHTML = tagsHtml;

    document.getElementById('modal-description').textContent  = offer.description  || '';
    document.getElementById('modal-requirements').textContent = offer.requirements || '';

    var benSec = document.getElementById('modal-ben-section');
    if (offer.benefits) {
        document.getElementById('modal-benefits').textContent = offer.benefits;
        benSec.style.display = 'block';
    } else {
        benSec.style.display = 'none';
    }

    var addr = [offer.address, offer.province, offer.department].filter(Boolean).join(', ');
    document.getElementById('modal-address').textContent = addr || 'No especificada';

    var dlRow = document.getElementById('modal-deadline-row');
    if (offer.deadline) {
        try {
            var dlDate = new Date(offer.deadline).toLocaleDateString('es-PE',{day:'2-digit',month:'long',year:'numeric'});
            document.getElementById('modal-deadline').textContent = 'Fecha límite: ' + dlDate;
        } catch(e) {
            document.getElementById('modal-deadline').textContent = offer.deadline;
        }
        dlRow.style.display = 'flex';
    } else {
        dlRow.style.display = 'none';
    }

    document.getElementById('offer-modal').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeOfferModal() {
    document.getElementById('offer-modal').classList.remove('open');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeOfferModal();
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay') && e.target.classList.contains('open')) {
        closeOfferModal();
    }
});
</script>
</body>
</html>
