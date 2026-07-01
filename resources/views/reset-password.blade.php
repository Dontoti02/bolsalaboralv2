<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Restablecer Contraseña - {{ $config['application_name'] ?? 'Bolsa Laboral' }}</title>
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
    </style>
</head>
<body class="bg-background text-on-background min-h-screen font-body-md text-body-md antialiased overflow-hidden flex justify-center items-center p-lg bg-surface-container-lowest">
    <div class="w-full max-w-[400px] bg-white border border-outline-variant rounded-2xl p-xl shadow-lg space-y-lg">
        <!-- Header -->
        <div class="text-center">
            <div class="mb-md flex items-center justify-center gap-2">
                <span class="font-display-lg text-display-lg text-primary tracking-tight leading-none">Bolsa Laboral</span>
            </div>
            <h1 class="font-headline-md text-headline-md text-on-surface mb-xs">Nueva Contraseña</h1>
            <p class="font-body-sm text-body-sm text-on-surface-variant">Establece tu nueva contraseña de acceso.</p>
        </div>

        <!-- Reset Form -->
        <form id="reset-password-form" onsubmit="handleResetPassword(event)" class="space-y-md">
            @csrf
            <input type="hidden" id="reset-token" value="{{ $token }}" />
            <input type="hidden" id="reset-email" value="{{ $email }}" />

            <!-- Display Email -->
            <div class="p-3 bg-surface-container-low rounded-lg border border-outline-variant/50 text-body-sm text-on-surface-variant flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">mail</span>
                <span class="font-medium">{{ $email }}</span>
            </div>

            <div id="reset-error-container" class="p-4 rounded-lg bg-error-container text-on-error-container text-body-sm font-medium hidden">
                <p id="reset-error-text"></p>
            </div>

            <!-- New Password -->
            <div class="space-y-xs">
                <label class="font-label-sm text-label-sm text-on-surface-variant block" for="new-password">Nueva Contraseña</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">lock</span>
                    <input class="w-full pl-10 pr-10 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-sm text-body-sm" 
                        id="new-password" placeholder="Mínimo 8 caracteres" type="password" required />
                    <button id="toggle-new-password" class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-on-surface-variant transition-colors" type="button">
                        <span class="material-symbols-outlined text-xl" id="toggle-icon-new">visibility_off</span>
                    </button>
                </div>
            </div>

            <!-- Confirm New Password -->
            <div class="space-y-xs">
                <label class="font-label-sm text-label-sm text-on-surface-variant block" for="confirm-password">Confirmar Contraseña</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">lock</span>
                    <input class="w-full pl-10 pr-10 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-sm text-body-sm" 
                        id="confirm-password" placeholder="••••••••" type="password" required />
                    <button id="toggle-confirm-password" class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-on-surface-variant transition-colors" type="button">
                        <span class="material-symbols-outlined text-xl" id="toggle-icon-confirm">visibility_off</span>
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <button id="btn-reset-submit" class="w-full mt-xl bg-primary hover:opacity-90 text-on-primary font-label-md text-label-md py-3 px-4 rounded-lg shadow-sm hover:shadow-md transition-all flex items-center justify-center gap-2 font-semibold" type="submit">
                Restablecer Contraseña
                <span class="material-symbols-outlined text-sm">lock_reset</span>
            </button>
        </form>

        <div class="text-center pt-md border-t border-outline-variant/60">
            <a class="font-label-sm text-label-sm text-primary hover:underline font-semibold" href="/login">Volver al inicio de sesión</a>
        </div>
    </div>

    <!-- Toast Notification Element -->
    <div id="toast" class="fixed bottom-5 right-5 bg-primary text-on-primary px-lg py-md rounded-xl shadow-lg transform translate-y-20 opacity-0 transition-all duration-300 z-50 flex items-center gap-sm">
        <span class="material-symbols-outlined" id="toast-icon">check_circle</span>
        <span id="toast-message" class="font-label-md text-label-md"></span>
    </div>

    <script>
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

        function handleResetPassword(event) {
            event.preventDefault();
            const form = document.getElementById('reset-password-form');
            const btn = document.getElementById('btn-reset-submit');
            const errContainer = document.getElementById('reset-error-container');
            const errText = document.getElementById('reset-error-text');
            
            const token = document.getElementById('reset-token').value;
            const email = document.getElementById('reset-email').value;
            const password = document.getElementById('new-password').value;
            const password_confirmation = document.getElementById('confirm-password').value;
            const csrfToken = form.querySelector('input[name="_token"]').value;

            if (password !== password_confirmation) {
                errText.textContent = 'Las contraseñas no coinciden.';
                errContainer.classList.remove('hidden');
                return;
            }

            btn.setAttribute('disabled', 'true');
            btn.innerHTML = `<span class="material-symbols-outlined animate-spin">autorenew</span> Guardando...`;
            errContainer.classList.add('hidden');

            fetch('/reset-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ token, email, password, password_confirmation })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast('¡Contraseña restablecida! Redirigiendo...');
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 2000);
                } else {
                    btn.removeAttribute('disabled');
                    btn.innerHTML = 'Restablecer Contraseña <span class="material-symbols-outlined text-sm">lock_reset</span>';
                    errText.textContent = data.message || 'Error al restablecer la contraseña.';
                    errContainer.classList.remove('hidden');
                }
            })
            .catch(err => {
                btn.removeAttribute('disabled');
                btn.innerHTML = 'Restablecer Contraseña <span class="material-symbols-outlined text-sm">lock_reset</span>';
                errText.textContent = 'Error de red. Intente de nuevo más tarde.';
                errContainer.classList.remove('hidden');
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const toggleNew = document.getElementById('toggle-new-password');
            const newPassInput = document.getElementById('new-password');
            const toggleIconNew = document.getElementById('toggle-icon-new');
            
            toggleNew.addEventListener('click', function() {
                const type = newPassInput.getAttribute('type') === 'password' ? 'text' : 'password';
                newPassInput.setAttribute('type', type);
                toggleIconNew.textContent = type === 'password' ? 'visibility_off' : 'visibility';
            });

            const toggleConfirm = document.getElementById('toggle-confirm-password');
            const confirmPassInput = document.getElementById('confirm-password');
            const toggleIconConfirm = document.getElementById('toggle-icon-confirm');
            
            toggleConfirm.addEventListener('click', function() {
                const type = confirmPassInput.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmPassInput.setAttribute('type', type);
                toggleIconConfirm.textContent = type === 'password' ? 'visibility_off' : 'visibility';
            });
        });
    </script>
</body>
</html>
