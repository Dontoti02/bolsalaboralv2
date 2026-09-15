<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Login - {{ $config['application_name'] ?? 'Bolsa Laboral' }}</title>
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
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&amp;family=Manrope:wght@600;700&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .material-symbols-outlined.filled {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        @keyframes infiniteScroll {
            from { transform: translateX(0); }
            to { transform: translateX(-50%); }
        }
        .animate-infinite-scroll {
            animation: infiniteScroll 25s linear infinite;
        }
        .animate-infinite-scroll:hover {
            animation-play-state: paused;
        }
        .btn-grad-cta {
            background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%) !important;
            color: #ffffff !important;
            box-shadow: 0 3px 10px rgba(91, 33, 182, 0.35) !important;
            border: 1px solid #4c1d95 !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        .btn-grad-cta:hover {
            background: linear-gradient(135deg, #6d28d9 0%, #4c1d95 100%) !important;
            box-shadow: 0 5px 14px rgba(91, 33, 182, 0.45) !important;
            transform: translateY(-1px);
        }
        .btn-grad-cta:active {
            transform: scale(0.98);
        }
        .card-grad-callout {
            background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%) !important;
            border: 1.5px solid #c4b5fd !important;
            box-shadow: 0 2px 8px rgba(109, 40, 217, 0.08) !important;
        }
        .card-grad-callout:hover {
            border-color: #a855f7 !important;
            box-shadow: 0 4px 12px rgba(109, 40, 217, 0.14) !important;
        }
    </style>
</head>
<body class="bg-background text-on-background min-h-screen font-body-md text-body-md antialiased overflow-y-auto lg:overflow-hidden">
<div class="flex min-h-screen w-full">
    <!-- Left Side: Banner -->
    <div class="hidden lg:flex w-1/2 relative bg-surface-container-highest">
        <div class="absolute inset-0 z-0">
            <img class="w-full h-full object-cover opacity-90 mix-blend-multiply" alt="Banner de inicio de sesión" src="{{ $config['banner'] ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuB0GCPqRfHk17qwRO9ArDGmQnjimcWlSydHNLZPPosOjB7wl4sfjABEYZ63bbQWTpKF4t1350ibna8LW9_jKpDw1CC6XjPNaMQh7RwNXNmK9pB3V0bwMduZ4yQwJj4ZALYfElGZw8SBckCVCr0WaQ-2xrscdj_70LknBDHoHJjI_fKaRlRO5N6DYbXwbA-0oCU7s55OUjx-oFtp_Fe8U-XH2gFdHEWIcHM3L2vcx0v6kaOlBR32mI5_XY47gMD-BkZB0drkFXcmCL6e' }}"/>
            <div class="absolute inset-0 bg-gradient-to-t from-primary/80 to-transparent"></div>
        </div>
        <div class="relative z-10 flex flex-col justify-end p-2xl text-on-primary w-full h-full">
            <div class="max-w-md">
                <span class="material-symbols-outlined text-4xl mb-md">work</span>
                <h2 class="font-headline-lg text-headline-lg mb-sm">Bolsa Laboral - {{ $config['application_name'] ?? 'Institucional' }}</h2>
                <p class="font-body-lg text-body-lg opacity-90">Conectando el talento de nuestros estudiantes con las mejores oportunidades del mercado.</p>
            </div>
        </div>
    </div>
    <!-- Right Side: Login Form -->
    <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-lg sm:p-2xl bg-surface-container-lowest overflow-y-auto max-h-screen py-8">
        <div class="w-full max-w-[400px]">
            <!-- Logo -->
            <div class="mb-xl flex items-center justify-center lg:justify-start gap-3">
                @if(!empty($config['logo']))
                    <img src="{{ $config['logo'] }}" alt="Logo" class="h-10 w-auto object-contain">
                @endif
                <span class="font-display-lg text-display-lg text-primary tracking-tight leading-none">{{ $config['application_name'] ?? 'Bolsa Laboral' }}</span>
            </div> <!-- Section: Login -->
            <div id="login-section" class="space-y-md">
                <div class="mb-xl text-center lg:text-left">
                    <h1 class="font-headline-md text-headline-md text-on-surface mb-xs">Iniciar Sesión</h1>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Bolsa Laboral - Accede al portal institucional.</p>
                </div>
                <!-- Form -->
                <form action="{{ route('login') }}" method="POST" class="space-y-md">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="p-4 rounded-lg bg-error-container text-on-error-container text-body-sm font-medium">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="space-y-xs">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block" for="login">Correo Electrónico</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">badge</span>
                            <input class="w-full pl-10 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-sm text-body-sm placeholder-outline" id="login" name="login" placeholder="Ej. example@gmail.com" type="text" required autocomplete="username" value="{{ old('login', old('email')) }}"/>
                        </div>
                    </div>
                    <div class="space-y-xs">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block" for="password">Contraseña</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">lock</span>
                            <input class="w-full pl-10 pr-10 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-sm text-body-sm placeholder-outline" id="password" name="password" placeholder="••••••••" type="password" required autocomplete="current-password"/>
                            <button id="toggle-password" class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-on-surface-variant transition-colors" type="button">
                                <span class="material-symbols-outlined text-xl" id="toggle-icon">visibility_off</span>
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center justify-between pt-sm">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input class="w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary/20 bg-surface-container-lowest" name="remember" type="checkbox"/>
                            <span class="font-body-sm text-body-sm text-on-surface-variant">Recordarme</span>
                        </label>
                        <button type="button" onclick="toggleAuthMode('forgot')" class="font-label-sm text-label-sm text-primary hover:underline decoration-primary/50 transition-all">Olvidé mi contraseña</button>
                    </div>
                    <button class="w-full mt-xl bg-primary hover:opacity-90 text-on-primary font-label-md text-label-md py-3 px-4 rounded-lg shadow-sm hover:shadow-md transition-all flex items-center justify-center gap-2" type="submit">
                        Ingresar
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </button>
                </form>
                
                <div class="pt-md border-t border-outline-variant/60 mt-md space-y-2.5">
                    <!-- Callout Egresado -->
                    <div class="flex items-center justify-between p-3.5 rounded-2xl card-grad-callout transition-all group">
                        <div class="flex items-center gap-3 text-left">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 shadow-xs group-hover:scale-105 transition-transform" style="background-color: #ede9fe; color: #6d28d9; border: 1px solid #ddd6fe;">
                                <span class="material-symbols-outlined text-[22px]" style="color: #6d28d9;">school</span>
                            </div>
                            <div>
                                <span class="block font-bold text-body-sm leading-tight" style="color: #4c1d95;">¿Eres un egresado?</span>
                                <span class="text-[11.5px] font-medium leading-snug block" style="color: #6d28d9;">Crea tu cuenta y postula hoy</span>
                            </div>
                        </div>
                        <button onclick="toggleAuthMode('register-graduate')" 
                            class="px-4 py-2 rounded-xl text-white font-bold text-xs btn-grad-cta shrink-0 ml-2 flex items-center gap-1.5 cursor-pointer shadow-md" 
                            type="button">
                            <span style="color: #ffffff !important;">Regístrate aquí</span>
                            <span class="material-symbols-outlined text-[15px]" style="color: #ffffff !important;">arrow_forward</span>
                        </button>
                    </div>

                    <!-- Callout Empresa -->
                    <div class="text-center pt-1">
                        <span class="font-body-sm text-body-sm text-on-surface-variant">¿Eres una empresa?</span>
                        <button onclick="toggleAuthMode('register')" class="font-label-sm text-label-sm text-primary hover:underline ml-1 font-semibold" type="button">Regístrate aquí</button>
                    </div>
                </div>
            </div>

            <!-- Section: Forgot Password -->
            <div id="forgot-section" class="space-y-md hidden">
                <div class="mb-xl text-center lg:text-left">
                    <h1 class="font-headline-md text-headline-md text-on-surface mb-xs">Recuperar Contraseña</h1>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Ingresa tu correo registrado para recibir un enlace de recuperación.</p>
                </div>
                <!-- Forgot Form -->
                <form id="forgot-password-form" onsubmit="handleForgotPassword(event)" class="space-y-md">
                    @csrf
                    
                    <div id="forgot-error-container" class="p-4 rounded-lg bg-error-container text-on-error-container text-body-sm font-medium hidden">
                        <p id="forgot-error-text"></p>
                    </div>
                    <div id="forgot-success-container" class="p-4 rounded-lg bg-secondary-container text-on-secondary-container text-body-sm font-medium hidden">
                        <p id="forgot-success-text"></p>
                    </div>

                    <div class="space-y-xs">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block" for="forgot-email">Correo Electrónico</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">mail</span>
                            <input class="w-full pl-10 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-sm text-body-sm placeholder-outline" id="forgot-email" name="email" placeholder="correo@ejemplo.com" type="email" required />
                        </div>
                    </div>

                    <button id="btn-forgot-submit" class="w-full mt-xl bg-primary hover:opacity-90 text-on-primary font-label-md text-label-md py-3 px-4 rounded-lg shadow-sm hover:shadow-md transition-all flex items-center justify-center gap-2 font-semibold" type="submit">
                        Enviar Enlace
                        <span class="material-symbols-outlined text-sm">send</span>
                    </button>
                </form>
                
                <div class="text-center pt-md border-t border-outline-variant/60 mt-md">
                    <span class="font-body-sm text-body-sm text-on-surface-variant">¿Recordaste tu contraseña?</span>
                    <button onclick="toggleAuthMode('login')" class="font-label-sm text-label-sm text-primary hover:underline ml-1 font-semibold" type="button">Inicia sesión</button>
                </div>
            </div>

            <!-- Section: Register Company -->
            <div id="register-section" class="space-y-md hidden">
                <div class="mb-xl text-center lg:text-left">
                    <h1 class="font-headline-md text-headline-md text-on-surface mb-xs">Registrar Empresa</h1>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Regístrate con tus datos básicos para comenzar a publicar ofertas.</p>
                </div>
                <!-- Register Form -->
                <form id="register-company-form" onsubmit="handleCompanyRegister(event)" class="space-y-md">
                    @csrf
                    
                    <div id="register-error-container" class="p-4 rounded-lg bg-error-container text-on-error-container text-body-sm font-medium hidden">
                        <p id="register-error-text"></p>
                    </div>

                    <div class="space-y-xs">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block" for="reg-name">Nombre de la Empresa</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">apartment</span>
                            <input class="w-full pl-10 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-sm text-body-sm placeholder-outline" id="reg-name" name="name" placeholder="Ej. Innova Tech SAC" type="text" required />
                        </div>
                    </div>

                    <div class="space-y-xs">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block" for="reg-email">Correo Electrónico Corporativo</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">mail</span>
                            <input class="w-full pl-10 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-sm text-body-sm placeholder-outline" id="reg-email" name="email" placeholder="contacto@empresa.com" type="email" required />
                        </div>
                    </div>

                    <div class="space-y-xs">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block" for="reg-ruc">RUC (11 dígitos)</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">fingerprint</span>
                            <input class="w-full pl-10 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-sm text-body-sm placeholder-outline" id="reg-ruc" name="ruc" placeholder="20123456789" type="text" minlength="11" maxlength="11" pattern="\d{11}" required />
                        </div>
                    </div>

                    <div class="space-y-xs">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block" for="reg-password">Contraseña</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">lock</span>
                            <input class="w-full pl-10 pr-10 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-sm text-body-sm placeholder-outline" id="reg-password" name="password" placeholder="Mínimo 8 caracteres" type="password" required />
                            <button id="toggle-reg-password" class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-on-surface-variant transition-colors" type="button">
                                <span class="material-symbols-outlined text-xl" id="toggle-reg-icon">visibility_off</span>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-xs">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block" for="reg-password-confirm">Confirmar Contraseña</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">lock</span>
                            <input class="w-full pl-10 pr-10 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-sm text-body-sm placeholder-outline" id="reg-password-confirm" name="password_confirmation" placeholder="••••••••" type="password" required />
                            <button id="toggle-reg-password-confirm" class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-on-surface-variant transition-colors" type="button">
                                <span class="material-symbols-outlined text-xl" id="toggle-reg-icon-confirm">visibility_off</span>
                            </button>
                        </div>
                    </div>

                    <button id="btn-register-company" class="w-full mt-xl bg-primary hover:opacity-90 text-on-primary font-label-md text-label-md py-3 px-4 rounded-lg shadow-sm hover:shadow-md transition-all flex items-center justify-center gap-2" type="submit">
                        Registrar Empresa
                        <span class="material-symbols-outlined text-sm">how_to_reg</span>
                    </button>
                </form>
                
                <div class="text-center pt-md border-t border-outline-variant/60 mt-md">
                    <span class="font-body-sm text-body-sm text-on-surface-variant">¿Ya tienes cuenta?</span>
                    <button onclick="toggleAuthMode('login')" class="font-label-sm text-label-sm text-primary hover:underline ml-1 font-semibold" type="button">Inicia sesión</button>
                </div>
            </div>

            <!-- Section: Register Graduate (Egresado) -->
            <div id="register-graduate-section" class="space-y-md hidden">
                <div class="mb-lg text-center lg:text-left">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-purple-100 text-purple-800 text-label-sm font-semibold text-[11px] mb-2">
                        <span class="material-symbols-outlined text-[14px]">school</span>
                        Comunidad de Egresados
                    </div>
                    <h1 class="font-headline-md text-headline-md text-on-surface mb-xs">Registro de Egresados</h1>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Crea tu cuenta profesional para acceder a ofertas laborales exclusivas.</p>
                </div>

                <!-- Register Graduate Form -->
                <form id="register-graduate-form" onsubmit="handleGraduateRegister(event)" class="space-y-3">
                    @csrf
                    
                    <div id="register-graduate-error-container" class="p-3 rounded-xl bg-error-container text-on-error-container text-body-sm font-medium hidden">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] text-red-600 shrink-0">error</span>
                            <p id="register-graduate-error-text" class="text-xs sm:text-sm"></p>
                        </div>
                    </div>

                    <!-- Nombres y Apellidos -->
                    <div class="space-y-1">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block" for="reg-grad-names">Nombres y Apellidos completos</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px]">person</span>
                            <input class="w-full pl-9 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600 transition-all font-body-sm text-body-sm placeholder-outline" id="reg-grad-names" name="names" placeholder="Ej. Ana Torres Quispe" type="text" required />
                        </div>
                    </div>

                    <!-- Documento DNI -->
                    <div class="space-y-1">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block" for="reg-grad-dni">DNI (8 dígitos)</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px]">badge</span>
                            <input class="w-full pl-9 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600 transition-all font-body-sm text-body-sm placeholder-outline" id="reg-grad-dni" name="document_number" placeholder="Ej. 45678912" type="text" minlength="8" maxlength="8" pattern="\d{8}" required />
                        </div>
                    </div>

                    <!-- Programa de Estudio / Carrera -->
                    <div class="space-y-1">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block" for="reg-grad-program">Programa de Estudio / Carrera</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px]">school</span>
                            <select class="w-full pl-9 pr-8 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600 transition-all font-body-sm text-body-sm text-on-surface appearance-none cursor-pointer" id="reg-grad-program" name="study_program_id">
                                <option value="">Selecciona tu programa de estudio</option>
                                @if(isset($studyPrograms))
                                    @foreach($studyPrograms as $sp)
                                        <option value="{{ $sp->id }}">{{ $sp->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">expand_more</span>
                        </div>
                    </div>

                    <!-- Teléfono / Celular -->
                    <div class="space-y-1">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block" for="reg-grad-phone">Teléfono / Celular</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px]">call</span>
                            <input class="w-full pl-9 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600 transition-all font-body-sm text-body-sm placeholder-outline" id="reg-grad-phone" name="phone" placeholder="Ej. 944556677" type="tel" maxlength="12" />
                        </div>
                    </div>

                    <!-- Correo Electrónico -->
                    <div class="space-y-1">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block" for="reg-grad-email">Correo Electrónico</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px]">mail</span>
                            <input class="w-full pl-9 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600 transition-all font-body-sm text-body-sm placeholder-outline" id="reg-grad-email" name="email" placeholder="egresado@ejemplo.com" type="email" required />
                        </div>
                    </div>

                    <!-- Contraseña -->
                    <div class="space-y-1">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block" for="reg-grad-password">Contraseña</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px]">lock</span>
                            <input class="w-full pl-9 pr-10 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600 transition-all font-body-sm text-body-sm placeholder-outline" id="reg-grad-password" name="password" placeholder="Mínimo 8 caracteres" type="password" required />
                            <button id="toggle-reg-grad-password" class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-on-surface-variant transition-colors" type="button">
                                <span class="material-symbols-outlined text-[18px]" id="toggle-reg-grad-icon">visibility_off</span>
                            </button>
                        </div>
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div class="space-y-1">
                        <label class="font-label-sm text-label-sm text-on-surface-variant block" for="reg-grad-password-confirm">Confirmar Contraseña</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px]">lock</span>
                            <input class="w-full pl-9 pr-10 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600 transition-all font-body-sm text-body-sm placeholder-outline" id="reg-grad-password-confirm" name="password_confirmation" placeholder="••••••••" type="password" required />
                            <button id="toggle-reg-grad-password-confirm" class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-on-surface-variant transition-colors" type="button">
                                <span class="material-symbols-outlined text-[18px]" id="toggle-reg-grad-icon-confirm">visibility_off</span>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button id="btn-register-graduate" class="w-full mt-4 btn-grad-cta font-bold font-label-md text-label-md py-3 px-4 rounded-xl flex items-center justify-center gap-2 cursor-pointer shadow-md" type="submit">
                        <span style="color: #ffffff !important;">Crear Cuenta de Egresado</span>
                        <span class="material-symbols-outlined text-sm" style="color: #ffffff !important;">how_to_reg</span>
                    </button>
                </form>
                
                <div class="text-center pt-md border-t border-outline-variant/60 mt-md">
                    <span class="font-body-sm text-body-sm text-on-surface-variant">¿Ya tienes cuenta?</span>
                    <button onclick="toggleAuthMode('login')" class="font-label-sm text-label-sm text-primary hover:underline ml-1 font-semibold" type="button">Inicia sesión</button>
                </div>
            </div>

            <!-- Carousel of Registered Companies -->
            @if(isset($companies) && $companies->isNotEmpty())
                <div class="mt-xl border-t border-outline-variant/60 pt-lg overflow-hidden w-full">
                    <p class="font-label-sm text-label-sm text-on-surface-variant text-center mb-md tracking-wider uppercase font-semibold text-[10px]">Empresas en la plataforma</p>
                    <div class="relative w-full overflow-hidden [mask-image:_linear-gradient(to_right,transparent_0,_black_15%,_black_85%,transparent_100%)]">
                        <div class="flex gap-lg items-center animate-infinite-scroll w-max whitespace-nowrap">
                            <!-- First loop -->
                            <div class="flex items-center gap-lg whitespace-nowrap shrink-0">
                                @foreach($companies as $company)
                                    <div class="inline-flex items-center justify-center px-md py-sm bg-surface-container-low border border-outline-variant/60 rounded-xl hover:border-primary transition-all hover:-translate-y-0.5 duration-200 cursor-pointer shadow-sm group" title="{{ $company->name }}">
                                        @if(!empty($company->logo))
                                            <img src="{{ $company->logo }}" alt="{{ $company->name }}" class="h-8 w-auto object-contain max-w-[100px]" />
                                        @else
                                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary-container/80 to-secondary-container/80 flex items-center justify-center text-xs font-bold text-on-primary-container uppercase">
                                                {{ substr($company->name, 0, 2) }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <!-- Second loop (seamless duplication) -->
                            <div class="flex items-center gap-lg whitespace-nowrap shrink-0" aria-hidden="true">
                                @foreach($companies as $company)
                                    <div class="inline-flex items-center justify-center px-md py-sm bg-surface-container-low border border-outline-variant/60 rounded-xl hover:border-primary transition-all hover:-translate-y-0.5 duration-200 cursor-pointer shadow-sm group" title="{{ $company->name }}">
                                        @if(!empty($company->logo))
                                            <img src="{{ $company->logo }}" alt="{{ $company->name }}" class="h-8 w-auto object-contain max-w-[100px]" />
                                        @else
                                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary-container/80 to-secondary-container/80 flex items-center justify-center text-xs font-bold text-on-primary-container uppercase">
                                                {{ substr($company->name, 0, 2) }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Footer / Security -->
            <div class="mt-xl text-center border-t border-outline-variant pt-lg">
                <div class="flex items-center justify-center gap-2 text-on-surface-variant mb-xs">
                    <span class="material-symbols-outlined text-sm filled">verified_user</span>
                    <span class="font-label-sm text-label-sm">Conexión Segura</span>
                </div>
                <p class="font-body-sm text-body-sm text-outline text-[12px]">Este portal está protegido por políticas de seguridad institucionales.</p>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification Element -->
<div id="toast" class="fixed bottom-5 right-5 bg-primary text-on-primary px-lg py-md rounded-xl shadow-lg transform translate-y-20 opacity-0 transition-all duration-300 z-50 flex items-center gap-sm">
    <span class="material-symbols-outlined" id="toast-icon">check_circle</span>
    <span id="toast-message" class="font-label-md text-label-md"></span>
</div>

<script>
    function toggleAuthMode(mode) {
        const loginSec = document.getElementById('login-section');
        const regSec = document.getElementById('register-section');
        const regGradSec = document.getElementById('register-graduate-section');
        const forgotSec = document.getElementById('forgot-section');
        
        loginSec.classList.add('hidden');
        regSec.classList.add('hidden');
        if (regGradSec) regGradSec.classList.add('hidden');
        forgotSec.classList.add('hidden');
        
        if (mode === 'register') {
            regSec.classList.remove('hidden');
            document.getElementById('register-company-form').reset();
            document.getElementById('register-error-container').classList.add('hidden');
        } else if (mode === 'register-graduate') {
            if (regGradSec) {
                regGradSec.classList.remove('hidden');
                document.getElementById('register-graduate-form').reset();
                document.getElementById('register-graduate-error-container').classList.add('hidden');
            }
        } else if (mode === 'forgot') {
            forgotSec.classList.remove('hidden');
            document.getElementById('forgot-password-form').reset();
            document.getElementById('forgot-error-container').classList.add('hidden');
            document.getElementById('forgot-success-container').classList.add('hidden');
        } else {
            loginSec.classList.remove('hidden');
        }
    }

    function handleForgotPassword(event) {
        event.preventDefault();
        
        const form = document.getElementById('forgot-password-form');
        const btn = document.getElementById('btn-forgot-submit');
        const errContainer = document.getElementById('forgot-error-container');
        const errText = document.getElementById('forgot-error-text');
        const succContainer = document.getElementById('forgot-success-container');
        const succText = document.getElementById('forgot-success-text');
        
        const email = document.getElementById('forgot-email').value;
        const token = form.querySelector('input[name="_token"]').value;
        
        btn.setAttribute('disabled', 'true');
        btn.innerHTML = `<span class="material-symbols-outlined animate-spin text-[16px] leading-none align-middle mr-1">autorenew</span> Enviando...`;
        errContainer.classList.add('hidden');
        succContainer.classList.add('hidden');
        
        fetch('/forgot-password', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ email })
        })
        .then(res => res.json())
        .then(data => {
            btn.removeAttribute('disabled');
            btn.innerHTML = 'Enviar Enlace <span class="material-symbols-outlined text-sm">send</span>';
            if (data.success) {
                succText.textContent = data.message;
                succContainer.classList.remove('hidden');
                form.reset();
            } else {
                errText.textContent = data.message || 'Error al solicitar el enlace.';
                errContainer.classList.remove('hidden');
            }
        })
        .catch(err => {
            btn.removeAttribute('disabled');
            btn.innerHTML = 'Enviar Enlace <span class="material-symbols-outlined text-sm">send</span>';
            errText.textContent = 'Error de red. Intente de nuevo más tarde.';
            errContainer.classList.remove('hidden');
        });
    }

    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        const toastMsg = document.getElementById('toast-message');
        const toastIcon = document.getElementById('toast-icon');
        
        toastMsg.textContent = message;
        
        if (type === 'error') {
            toast.className = "fixed bottom-5 right-5 bg-red-600 text-on-primary px-lg py-md rounded-xl shadow-lg transform transition-all duration-300 z-50 flex items-center gap-sm";
            toastIcon.textContent = 'error';
        } else {
            toast.className = "fixed bottom-5 right-5 bg-primary text-on-primary px-lg py-md rounded-xl shadow-lg transform transition-all duration-300 z-50 flex items-center gap-sm";
            toastIcon.textContent = 'check_circle';
        }
        
        toast.classList.remove('translate-y-20', 'opacity-0');
        
        setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0');
        }, 3000);
    }

    function handleCompanyRegister(event) {
        event.preventDefault();
        
        const form = document.getElementById('register-company-form');
        const btn = document.getElementById('btn-register-company');
        const errContainer = document.getElementById('register-error-container');
        const errText = document.getElementById('register-error-text');
        
        const name = document.getElementById('reg-name').value;
        const email = document.getElementById('reg-email').value;
        const ruc = document.getElementById('reg-ruc').value;
        const password = document.getElementById('reg-password').value;
        const password_confirmation = document.getElementById('reg-password-confirm').value;
        const token = form.querySelector('input[name="_token"]').value;
        
        if (password !== password_confirmation) {
            errText.textContent = 'Las contraseñas no coinciden.';
            errContainer.classList.remove('hidden');
            return;
        }
        
        btn.setAttribute('disabled', 'true');
        btn.innerHTML = `<span class="material-symbols-outlined animate-spin">autorenew</span> Registrando...`;
        errContainer.classList.add('hidden');
        
        fetch('/register/company', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ name, email, ruc, password, password_confirmation })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast('¡Registro exitoso! Redirigiendo...');
                setTimeout(() => {
                    window.location.href = '/company/dashboard';
                }, 1500);
            } else {
                btn.removeAttribute('disabled');
                btn.innerHTML = 'Registrar Empresa <span class="material-symbols-outlined text-sm">how_to_reg</span>';
                errText.textContent = data.message || 'Error al registrar la empresa.';
                errContainer.classList.remove('hidden');
            }
        })
        .catch(err => {
            btn.removeAttribute('disabled');
            btn.innerHTML = 'Registrar Empresa <span class="material-symbols-outlined text-sm">how_to_reg</span>';
            errText.textContent = 'Error de red. Intente de nuevo más tarde.';
            errContainer.classList.remove('hidden');
        });
    }

    function handleGraduateRegister(event) {
        event.preventDefault();
        
        const form = document.getElementById('register-graduate-form');
        const btn = document.getElementById('btn-register-graduate');
        const errContainer = document.getElementById('register-graduate-error-container');
        const errText = document.getElementById('register-graduate-error-text');
        
        const names = document.getElementById('reg-grad-names').value.trim();
        const document_number = document.getElementById('reg-grad-dni').value.trim();
        const study_program_id = document.getElementById('reg-grad-program').value;
        const phone = document.getElementById('reg-grad-phone').value.trim();
        const email = document.getElementById('reg-grad-email').value.trim();
        const password = document.getElementById('reg-grad-password').value;
        const password_confirmation = document.getElementById('reg-grad-password-confirm').value;
        const token = form.querySelector('input[name="_token"]').value;
        
        if (!/^\d{8}$/.test(document_number)) {
            errText.textContent = 'El DNI debe tener exactamente 8 dígitos numéricos.';
            errContainer.classList.remove('hidden');
            return;
        }

        if (password !== password_confirmation) {
            errText.textContent = 'Las contraseñas no coinciden.';
            errContainer.classList.remove('hidden');
            return;
        }

        if (password.length < 8) {
            errText.textContent = 'La contraseña debe tener al menos 8 caracteres.';
            errContainer.classList.remove('hidden');
            return;
        }
        
        btn.setAttribute('disabled', 'true');
        btn.innerHTML = `<span class="material-symbols-outlined animate-spin text-[16px]">autorenew</span> Creando cuenta...`;
        errContainer.classList.add('hidden');
        
        fetch('/register/graduate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                names,
                document_number,
                study_program_id: study_program_id || null,
                phone,
                email,
                password,
                password_confirmation
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message || '¡Registro exitoso! Redirigiendo...');
                setTimeout(() => {
                    window.location.href = data.redirect || '/';
                }, 1200);
            } else {
                btn.removeAttribute('disabled');
                btn.innerHTML = 'Crear Cuenta de Egresado <span class="material-symbols-outlined text-sm">how_to_reg</span>';
                errText.textContent = data.message || 'Error al registrar egresado.';
                errContainer.classList.remove('hidden');
            }
        })
        .catch(err => {
            btn.removeAttribute('disabled');
            btn.innerHTML = 'Crear Cuenta de Egresado <span class="material-symbols-outlined text-sm">how_to_reg</span>';
            errText.textContent = 'Error de conexión. Intente nuevamente más tarde.';
            errContainer.classList.remove('hidden');
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggle-icon');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            toggleIcon.textContent = type === 'password' ? 'visibility_off' : 'visibility';
        });

        // Register company form password toggles
        const toggleRegPass = document.getElementById('toggle-reg-password');
        const regPassInput = document.getElementById('reg-password');
        const toggleRegPassIcon = document.getElementById('toggle-reg-icon');
        
        toggleRegPass.addEventListener('click', function() {
            const type = regPassInput.getAttribute('type') === 'password' ? 'text' : 'password';
            regPassInput.setAttribute('type', type);
            toggleRegPassIcon.textContent = type === 'password' ? 'visibility_off' : 'visibility';
        });

        const toggleRegPassConfirm = document.getElementById('toggle-reg-password-confirm');
        const regPassConfirmInput = document.getElementById('reg-password-confirm');
        const toggleRegPassConfirmIcon = document.getElementById('toggle-reg-icon-confirm');
        
        toggleRegPassConfirm.addEventListener('click', function() {
            const type = regPassConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
            regPassConfirmInput.setAttribute('type', type);
            toggleRegPassConfirmIcon.textContent = type === 'password' ? 'visibility_off' : 'visibility';
        });

        // Register graduate form password toggles
        const toggleRegGradPass = document.getElementById('toggle-reg-grad-password');
        const regGradPassInput = document.getElementById('reg-grad-password');
        const toggleRegGradPassIcon = document.getElementById('toggle-reg-grad-icon');
        
        if (toggleRegGradPass && regGradPassInput && toggleRegGradPassIcon) {
            toggleRegGradPass.addEventListener('click', function() {
                const type = regGradPassInput.getAttribute('type') === 'password' ? 'text' : 'password';
                regGradPassInput.setAttribute('type', type);
                toggleRegGradPassIcon.textContent = type === 'password' ? 'visibility_off' : 'visibility';
            });
        }

        const toggleRegGradPassConfirm = document.getElementById('toggle-reg-grad-password-confirm');
        const regGradPassConfirmInput = document.getElementById('reg-grad-password-confirm');
        const toggleRegGradPassConfirmIcon = document.getElementById('toggle-reg-icon-confirm');
        
        if (toggleRegGradPassConfirm && regGradPassConfirmInput && toggleRegGradPassConfirmIcon) {
            toggleRegGradPassConfirm.addEventListener('click', function() {
                const type = regGradPassConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
                regGradPassConfirmInput.setAttribute('type', type);
                toggleRegGradPassConfirmIcon.textContent = type === 'password' ? 'visibility_off' : 'visibility';
            });
        }

        // Activar modo según hash o URL
        if (window.location.hash === '#egresado' || window.location.hash === '#registro-egresado' || window.location.pathname.includes('registro-egresado')) {
            toggleAuthMode('register-graduate');
        } else if (window.location.hash === '#empresa' || window.location.hash === '#register' || window.location.pathname.includes('registro-empresa')) {
            toggleAuthMode('register');
        }
    });
</script>
</body>
</html>
