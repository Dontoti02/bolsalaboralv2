/**
 * ui-alerts.js — Sistema de alertas UI premium para BolsaLaboral
 * Toasts animados + Modal de confirmación personalizado
 */

/* ─────────────────────────────────────────────
   INJECT STYLES (done once)
   ───────────────────────────────────────────── */
(function injectStyles() {
    if (document.getElementById('ua-alerts-styles')) return;
    const style = document.createElement('style');
    style.id = 'ua-alerts-styles';
    style.textContent = `
/* ── TOAST SYSTEM ── */
#ua-toast-container {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9999;
    display: flex;
    flex-direction: column-reverse;
    gap: 10px;
    pointer-events: none;
}

.ua-toast {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 18px 14px 16px;
    border-radius: 14px;
    min-width: 280px;
    max-width: 380px;
    box-shadow:
        0 4px 6px -1px rgba(0,0,0,.14),
        0 12px 32px -4px rgba(0,0,0,.2);
    pointer-events: all;
    cursor: default;
    overflow: hidden;
    position: relative;
    opacity: 0;
    transform: translateX(110%) scale(0.9);
    transition:
        opacity .3s cubic-bezier(.34,1.56,.64,1),
        transform .3s cubic-bezier(.34,1.56,.64,1);
}
.ua-toast.ua-show {
    opacity: 1;
    transform: translateX(0) scale(1);
}
.ua-toast.ua-hide {
    opacity: 0;
    transform: translateX(110%) scale(0.88);
    transition: opacity .22s ease, transform .22s ease;
}

/* progress bar */
.ua-toast-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    border-radius: 0 0 14px 14px;
    transform-origin: left;
}

/* type variants */
.ua-toast-success { background: linear-gradient(135deg,#002741 0%,#0f3d5e 100%); color:#fff; }
.ua-toast-success .ua-toast-progress { background: rgba(110,231,183,.7); }
.ua-toast-success .ua-toast-icon { color: #6ee7b7; }

.ua-toast-error { background: linear-gradient(135deg,#7f1d1d 0%,#991b1b 100%); color:#fff; }
.ua-toast-error .ua-toast-progress { background: rgba(252,165,165,.7); }
.ua-toast-error .ua-toast-icon { color: #fca5a5; }

.ua-toast-warning { background: linear-gradient(135deg,#451a03 0%,#92400e 100%); color:#fff; }
.ua-toast-warning .ua-toast-progress { background: rgba(252,211,77,.7); }
.ua-toast-warning .ua-toast-icon { color: #fcd34d; }

.ua-toast-info { background: linear-gradient(135deg,#1e3a5f 0%,#1e40af 100%); color:#fff; }
.ua-toast-info .ua-toast-progress { background: rgba(147,197,253,.7); }
.ua-toast-info .ua-toast-icon { color: #93c5fd; }

.ua-toast-icon {
    font-size: 22px;
    flex-shrink: 0;
    filter: drop-shadow(0 0 6px currentColor);
    font-variation-settings: 'wght' 300;
}

.ua-toast-body { flex: 1; display: flex; flex-direction: column; gap: 2px; }
.ua-toast-title { font-weight: 700; font-size: 11px; letter-spacing: .08em; opacity: .75; text-transform: uppercase; }
.ua-toast-msg { font-size: 14px; font-weight: 500; line-height: 1.45; }

.ua-toast-close {
    background: none; border: none; color: inherit; opacity: .45;
    cursor: pointer; padding: 2px; display: flex; align-items: center;
    transition: opacity .15s; font-size: 18px; flex-shrink: 0;
}
.ua-toast-close:hover { opacity: 1; }

/* ── CONFIRM MODAL ── */
#ua-confirm-backdrop {
    position: fixed;
    inset: 0;
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0,0,0,.45);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    padding: 16px;
    opacity: 0;
    transition: opacity .22s ease;
}
#ua-confirm-backdrop.ua-show { opacity: 1; }

#ua-confirm-modal {
    background: #fff;
    border-radius: 22px;
    padding: 36px 28px 26px;
    max-width: 420px;
    width: 100%;
    box-shadow: 0 25px 60px rgba(0,0,0,.28), 0 0 0 1px rgba(0,0,0,.04);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    text-align: center;
    transform: scale(.86) translateY(24px);
    transition: transform .28s cubic-bezier(.34,1.56,.64,1);
}
#ua-confirm-backdrop.ua-show #ua-confirm-modal { transform: scale(1) translateY(0); }

#ua-confirm-icon-wrap {
    width: 68px; height: 68px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 6px; position: relative;
}
#ua-confirm-icon-wrap::before {
    content: ''; position: absolute; inset: -5px;
    border-radius: 50%; opacity: .12;
}

.ua-confirm-danger  #ua-confirm-icon-wrap { background:#fee2e2; }
.ua-confirm-danger  #ua-confirm-icon-wrap::before { background:#ef4444; }
.ua-confirm-danger  #ua-confirm-icon-wrap .material-symbols-outlined { color:#dc2626; }

.ua-confirm-warning #ua-confirm-icon-wrap { background:#fef3c7; }
.ua-confirm-warning #ua-confirm-icon-wrap::before { background:#f59e0b; }
.ua-confirm-warning #ua-confirm-icon-wrap .material-symbols-outlined { color:#d97706; }

.ua-confirm-info    #ua-confirm-icon-wrap { background:#e0f2fe; }
.ua-confirm-info    #ua-confirm-icon-wrap::before { background:#0ea5e9; }
.ua-confirm-info    #ua-confirm-icon-wrap .material-symbols-outlined { color:#0284c7; }

#ua-confirm-icon-wrap .material-symbols-outlined {
    font-size: 34px;
    font-variation-settings: 'wght' 200;
}

#ua-confirm-title {
    font-size: 18px; font-weight: 700; color: #111827; margin: 0; line-height: 1.3;
}
#ua-confirm-message {
    font-size: 14px; color: #6b7280; margin: 0; line-height: 1.65;
}
#ua-confirm-actions { display: flex; gap: 10px; width: 100%; margin-top: 10px; }
#ua-confirm-actions button {
    flex: 1; padding: 12px 20px; border-radius: 11px; font-size: 14px;
    font-weight: 600; cursor: pointer; border: none;
    transition: transform .12s ease, box-shadow .12s ease;
    letter-spacing: .01em;
}
#ua-confirm-actions button:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(0,0,0,.14); }
#ua-confirm-actions button:active { transform: translateY(0); }

#ua-confirm-cancel { background:#f3f4f6; color:#374151; }
#ua-confirm-cancel:hover { background:#e5e7eb; }

.ua-confirm-danger  #ua-confirm-ok { background: linear-gradient(135deg,#dc2626,#b91c1c); color:#fff; }
.ua-confirm-warning #ua-confirm-ok { background: linear-gradient(135deg,#d97706,#b45309); color:#fff; }
.ua-confirm-info    #ua-confirm-ok { background: linear-gradient(135deg,#002741,#0f3d5e); color:#fff; }

#ua-confirm-ok { position:relative; overflow:hidden; }
#ua-confirm-ok::after {
    content:''; position:absolute; inset:0;
    background:rgba(255,255,255,.12); opacity:0; transition:opacity .15s;
}
#ua-confirm-ok:hover::after { opacity:1; }
    `;
    document.head.appendChild(style);
})();

/* ─────────────────────────────────────────────
   TOAST SYSTEM
   ───────────────────────────────────────────── */
let _toastContainer = null;
function _getToastContainer() {
    if (!_toastContainer || !_toastContainer.isConnected) {
        _toastContainer = document.createElement('div');
        _toastContainer.id = 'ua-toast-container';
        document.body.appendChild(_toastContainer);
    }
    return _toastContainer;
}

const TOAST_CFG = {
    success: { icon: 'check_circle', title: 'Éxito',  cls: 'ua-toast-success' },
    error:   { icon: 'cancel',       title: 'Error',  cls: 'ua-toast-error'   },
    warning: { icon: 'warning',      title: 'Aviso',  cls: 'ua-toast-warning' },
    info:    { icon: 'info',         title: 'Info',   cls: 'ua-toast-info'    },
};

/**
 * showToast(message, type?, duration?)
 * type: 'success' | 'error' | 'warning' | 'info'
 */
window.showToast = function(message, type = 'success', duration = 4000) {
    const cfg = TOAST_CFG[type] || TOAST_CFG.success;
    const container = _getToastContainer();

    const toast = document.createElement('div');
    toast.className = `ua-toast ${cfg.cls}`;

    toast.innerHTML = `
        <span class="ua-toast-icon material-symbols-outlined">${cfg.icon}</span>
        <div class="ua-toast-body">
            <span class="ua-toast-title">${cfg.title}</span>
            <span class="ua-toast-msg">${message}</span>
        </div>
        <button class="ua-toast-close material-symbols-outlined" aria-label="Cerrar">close</button>
        <div class="ua-toast-progress" style="width:100%;animation:none;"></div>
    `;

    const progress = toast.querySelector('.ua-toast-progress');
    toast.querySelector('.ua-toast-close').addEventListener('click', () => _dismissToast(toast));
    container.appendChild(toast);

    // Animate in
    requestAnimationFrame(() => requestAnimationFrame(() => {
        toast.classList.add('ua-show');
        // Start progress shrink
        progress.style.transition = `width ${duration}ms linear`;
        requestAnimationFrame(() => { progress.style.width = '0%'; });
    }));

    toast._timer = setTimeout(() => _dismissToast(toast), duration);
};

function _dismissToast(toast) {
    if (!toast) return;
    clearTimeout(toast._timer);
    toast.classList.remove('ua-show');
    toast.classList.add('ua-hide');
    toast.addEventListener('transitionend', () => toast.remove(), { once: true });
}

/* ─────────────────────────────────────────────
   CONFIRM MODAL
   ───────────────────────────────────────────── */
let _confirmBackdrop = null;
let _confirmResolve  = null;

function _resolveConfirm(result) {
    if (!_confirmBackdrop) return;
    const bd = _confirmBackdrop;
    _confirmBackdrop = null;
    bd.classList.remove('ua-show');
    bd.addEventListener('transitionend', () => bd.remove(), { once: true });
    if (_confirmResolve) { _confirmResolve(result); _confirmResolve = null; }
}

function _onKeyDown(e) {
    if (!_confirmBackdrop) return;
    if (e.key === 'Escape') _resolveConfirm(false);
    if (e.key === 'Enter')  _resolveConfirm(true);
}

/**
 * showConfirm(options) → Promise<boolean>
 *
 * options: {
 *   title?,   message?,  type?: 'danger'|'warning'|'info',
 *   icon?,    okText?,   cancelText?
 * }
 */
window.showConfirm = function({
    title      = '¿Estás seguro?',
    message    = 'Esta acción no se puede deshacer.',
    type       = 'danger',
    icon       = null,
    okText     = 'Confirmar',
    cancelText = 'Cancelar',
} = {}) {
    return new Promise(resolve => {
        _confirmResolve = resolve;

        const backdrop = document.createElement('div');
        backdrop.id = 'ua-confirm-backdrop';

        const iconMap = { danger: 'delete_forever', warning: 'warning_amber', info: 'help' };
        backdrop.innerHTML = `
            <div id="ua-confirm-modal" class="ua-confirm-${type}">
                <div id="ua-confirm-icon-wrap">
                    <span class="material-symbols-outlined" id="ua-confirm-icon">${icon || iconMap[type] || 'help'}</span>
                </div>
                <h2 id="ua-confirm-title">${title}</h2>
                <p  id="ua-confirm-message">${message}</p>
                <div id="ua-confirm-actions">
                    <button id="ua-confirm-cancel" type="button">${cancelText}</button>
                    <button id="ua-confirm-ok"     type="button">${okText}</button>
                </div>
            </div>
        `;

        backdrop.addEventListener('click', e => { if (e.target === backdrop) _resolveConfirm(false); });
        backdrop.querySelector('#ua-confirm-cancel').addEventListener('click', () => _resolveConfirm(false));
        backdrop.querySelector('#ua-confirm-ok').addEventListener('click',     () => _resolveConfirm(true));
        document.addEventListener('keydown', _onKeyDown);

        _confirmBackdrop = backdrop;
        document.body.appendChild(backdrop);

        requestAnimationFrame(() => requestAnimationFrame(() => backdrop.classList.add('ua-show')));
    }).finally(() => {
        document.removeEventListener('keydown', _onKeyDown);
    });
};
