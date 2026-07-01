@props(['id' => 'notifications-button', 'menuId' => 'notifications-menu'])

<div class="relative">
    <button id="{{ $id }}" type="button"
        onclick="toggleNotificationsMenu('{{ $id }}', '{{ $menuId }}')"
        class="relative w-10 h-10 flex items-center justify-center text-on-surface-variant hover:text-primary hover:bg-surface-container-high rounded-xl transition-colors"
        aria-label="Notificaciones">
        <span class="material-symbols-outlined">notifications</span>
        <span id="{{ $id }}-badge" class="absolute top-1 right-1 w-2.5 h-2.5 bg-error rounded-full hidden"></span>
    </button>
    <div id="{{ $menuId }}"
        class="hidden absolute right-0 top-12 w-80 bg-surface-container-lowest border border-outline-variant rounded-2xl shadow-xl overflow-hidden z-50">
        <div class="px-5 py-4 border-b border-outline-variant flex items-center justify-between">
            <div>
                <p class="font-headline-sm text-[16px] text-on-surface">Notificaciones</p>
                <p class="text-[12px] text-on-surface-variant">Actividad reciente de su cuenta</p>
            </div>
            <button type="button" onclick="markAllNotificationsAsRead()"
                class="w-9 h-9 rounded-full bg-secondary/10 text-secondary flex items-center justify-center hover:bg-secondary/20 transition-colors"
                title="Marcar todo como leído">
                <span class="material-symbols-outlined text-[20px]">done_all</span>
            </button>
        </div>
        <div id="{{ $menuId }}-list" class="max-h-96 overflow-y-auto">
            <!-- Notifications will load here -->
        </div>
    </div>
</div>
<script>
(function() {
    const menuId = '{{ $menuId }}';
    let notificationsData = [];
    let unreadCount = 0;

    function fetchNotifications() {
        fetch('/notifications', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    notificationsData = data.notifications || [];
                    unreadCount = data.unread_count || 0;
                    renderNotifications();
                    updateBadge(unreadCount);
                }
            });
    }

    function renderNotifications() {
        const list = document.getElementById(menuId + '-list');
        if (!list) return;
        if (!notificationsData.length) {
            list.innerHTML = '<div class="p-6 text-center"><span class="material-symbols-outlined text-4xl text-outline mb-2">notifications_none</span>' +
                '<p class="font-semibold text-on-surface">Todo est&aacute; al d&iacute;a</p>' +
                '<p class="text-body-sm text-on-surface-variant mt-1">Las novedades importantes aparecer&aacute;n aqu&iacute;.</p></div>';
            return;
        }
        list.innerHTML = notificationsData.map(n => {
            const isUnread = !n.read_at;
            const borderClass = isUnread ? 'border-l-4 border-primary bg-surface-container-high/30' : '';
            const timeText = n.created_at ? new Date(n.created_at).toLocaleString('es-ES', {day:'numeric', month:'short', hour:'2-digit', minute:'2-digit'}) : '';
            return '<div class="px-4 py-3 border-b border-outline-variant/50 hover:bg-surface-container-high transition-colors cursor-pointer ' + borderClass + '" onclick="if(\'' + (n.link || '#') + '\' !== \'#\') window.location.href=\'' + (n.link || '#') + '\'">' +
                '<div class="flex items-start gap-3">' +
                '<span class="material-symbols-outlined text-primary mt-0.5">notifications</span>' +
                '<div class="flex-1 min-w-0">' +
                '<p class="text-label-md font-semibold text-on-surface leading-tight">' + (n.title || '') + '</p>' +
                '<p class="text-body-sm text-on-surface-variant mt-0.5 line-clamp-2">' + (n.message || '') + '</p>' +
                '<div class="flex items-center justify-between mt-1">' +
                '<span class="text-[11px] text-on-surface-variant/70">' + timeText + '</span>' +
                (isUnread ? '<span class="w-2 h-2 bg-error rounded-full"></span>' : '') +
                '</div></div></div></div>';
        }).join('');
    }

    function updateBadge(count) {
        const badge = document.getElementById('{{ $id }}' + '-badge');
        if (!badge) return;
        if (count > 0) {
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    }

    window.toggleNotificationsMenu = function(btnId, mId) {
        const menu = document.getElementById(mId);
        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
            fetchNotifications();
        } else {
            menu.classList.add('hidden');
        }
    };

    window.markAllNotificationsAsRead = function() {
        fetch('/notifications/read-all', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    fetchNotifications();
                }
            });
    };
})();
</script>