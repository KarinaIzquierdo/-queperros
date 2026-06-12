@php
    use Illuminate\Support\Str;
    // Priorizar $user, luego Auth::user()
    $mqTopbarUser = $user ?? (\Illuminate\Support\Facades\Auth::user());
    $mqTopbarName = 'Usuario';
    if ($mqTopbarUser) {
        $mqTopbarName = Str::before($mqTopbarUser->name ?? $mqTopbarUser->nombre ?? 'Usuario', ' ');
    }
    $mqTopbarRoleLabel = $roleLabel ?? '';
    $mqTopbarNotifications = collect();
    $mqTopbarNotifCount = 0;

    if ($mqTopbarUser) {
        $mqTopbarIsAdmin = $mqTopbarUser->rol_id == 1 || ($mqTopbarUser->rol ?? '') === 'admin';
        $mqTopbarIsTrainer = $mqTopbarUser->rol_id == 3 || in_array($mqTopbarUser->rol ?? '', ['entrenador', 'empleado', 'trainer']);
        $mqTopbarIsOwner = $mqTopbarUser->rol_id == 2 || ($mqTopbarUser->rol ?? '') === 'dueno' || ($mqTopbarUser->rol ?? '') === 'dueno';

        $hasSpanish = \Illuminate\Support\Facades\Schema::hasTable('notificaciones');
        $hasEnglish = \Illuminate\Support\Facades\Schema::hasTable('notifications');

        $list = collect();

        if ($hasSpanish) {
            $spanishNotifs = \Illuminate\Support\Facades\DB::table('notificaciones')
                ->where('user_id', (int) $mqTopbarUser->id)
                ->orderByDesc('created_at')
                ->limit(20)
                ->get()
                ->map(function ($n) {
                    return (object) [
                        'id' => $n->id,
                        'source_table' => 'notificaciones',
                        'tipo' => $n->tipo,
                        'titulo' => $n->titulo ?? ucfirst(str_replace('_', ' ', $n->tipo ?? 'notificacion')),
                        'mensaje' => $n->mensaje,
                        'url' => $n->url,
                        'leido' => !empty($n->leida_en),
                        'created_at' => $n->created_at,
                    ];
                });
            $list = $list->concat($spanishNotifs);
        }

        if ($hasEnglish) {
            $englishNotifs = \Illuminate\Support\Facades\DB::table('notifications')
                ->where('id_usuario', (int) $mqTopbarUser->id)
                ->orderByDesc('created_at')
                ->limit(20)
                ->get()
                ->map(function ($n) {
                    return (object) [
                        'id' => $n->id,
                        'source_table' => 'notifications',
                        'tipo' => $n->tipo,
                        'titulo' => $n->titulo ?? ucfirst(str_replace('_', ' ', $n->tipo ?? 'notificacion')),
                        'mensaje' => $n->mensaje,
                        'url' => $n->url,
                        'leido' => (bool) ($n->leido || !empty($n->leido_en)),
                        'created_at' => $n->created_at,
                    ];
                });
            $list = $list->concat($englishNotifs);
        }

        $sorted = $list->sortByDesc('created_at');
        $unread = $sorted->filter(fn($n) => !$n->leido);
        $mqTopbarNotifCount = $unread->count();
        $mqTopbarNotifications = $unread->count() > 0 ? $unread->take(5) : $sorted->take(5);
    } else {
        $mqTopbarIsAdmin = false;
        $mqTopbarIsTrainer = false;
        $mqTopbarIsOwner = false;
        $mqTopbarNotifCount = $notifCount ?? 0;
    }

    $mqTopbarProfileUrl = $profileUrl ?? '#';
    $mqTopbarSettingsUrl = $settingsUrl ?? '#';
    $mqTopbarHelpUrl = $helpUrl ?? '#';
    $mqTopbarNotificationsUrl = $notificationsUrl ?? '#';
    
    // Determinar URL para marcar todas como leídas
    $mqMarkAllReadUrl = '#';
    if ($mqTopbarIsAdmin) $mqMarkAllReadUrl = route('admin.notifications.markAllRead');
    elseif ($mqTopbarIsTrainer) $mqMarkAllReadUrl = route('entrenador.notifications.markAllRead');
    elseif ($mqTopbarIsOwner) $mqMarkAllReadUrl = route('owner.notifications.markAllRead');
@endphp

<header class="mqx-topbar" aria-label="Barra superior">
    <div class="mqx-topbar-left">
        @if($mqTopbarIsAdmin)
            <div class="mqx-sidebar-toggle-wrapper">
                <input type="checkbox" id="checkbox" class="mqx-sidebar-checkbox" data-mqx-sidebar-toggle="true">
                <label for="checkbox" class="toggle">
                    <div class="bars" id="bar1"></div>
                    <div class="bars" id="bar2"></div>
                    <div class="bars" id="bar3"></div>
                </label>
            </div>
        @elseif($mqTopbarIsOwner)
            <div class="mqx-sidebar-toggle-wrapper">
                <input type="checkbox" id="checkbox2" class="mqx-sidebar-checkbox" data-mqx-sidebar-toggle="true">
                <label for="checkbox2" class="toggle toggle2">
                    <div class="bars" id="bar4"></div>
                    <div class="bars" id="bar5"></div>
                    <div class="bars" id="bar6"></div>
                </label>
            </div>
        @elseif($mqTopbarIsTrainer)
            <div class="mqx-sidebar-toggle-wrapper">
                <input type="checkbox" id="checkbox3" class="mqx-sidebar-checkbox" data-mqx-sidebar-toggle="true">
                <label for="checkbox3" class="toggle toggle3">
                    <div class="bars" id="bar7"></div>
                    <div class="bars" id="bar8"></div>
                    <div class="bars" id="bar9"></div>
                </label>
            </div>
        @else
            <div class="mqx-sidebar-toggle-wrapper">
                <input type="checkbox" id="mqxSidebarCheckbox" class="mqx-sidebar-checkbox" data-mqx-sidebar-toggle="true">
                <label for="mqxSidebarCheckbox" class="mqx-sidebar-toggle-btn">
                    <i class="bi bi-x-lg" id="mqxBarX" style="display: none; font-size: 24px; color: #6d28d9;"></i>
                    <div class="mqx-bar-wrapper" id="mqxBarsNormal">
                        <div class="mqx-bar" id="mqxBar1"></div>
                        <div class="mqx-bar" id="mqxBar2"></div>
                        <div class="mqx-bar" id="mqxBar3"></div>
                    </div>
                </label>
            </div>
        @endif
    </div>

    <div class="mqx-topbar-right">
        <button class="mqx-topbar-icon" type="button" aria-label="Notificaciones" data-mqx-toggle="notifications" onclick="handleNotifClick(this, '{{ $mqMarkAllReadUrl }}')">
            <i class="bi bi-bell" aria-hidden="true"></i>
            @if($mqTopbarNotifCount > 0)
                <span class="mqx-topbar-dot" aria-hidden="true" id="mqxNotifBadge">{{ $mqTopbarNotifCount }}</span>
            @endif
        </button>

        <button class="mqx-topbar-user" type="button" aria-label="Menú de usuario" data-mqx-toggle="user">
            <div class="mqx-topbar-user-avatar" aria-hidden="true">{{ strtoupper(mb_substr($mqTopbarUser->name ?? 'U', 0, 1)) }}</div>
            <span class="mqx-topbar-user-name">{{ $mqTopbarName }}</span>
            <i class="bi bi-chevron-down" aria-hidden="true"></i>
        </button>
    </div>

    <div class="mqx-popover" data-mqx-popover="user" aria-hidden="true">
        <div class="mqx-popover-head">
            <div class="mqx-popover-name">{{ $mqTopbarName }}</div>
            @if (!empty($mqTopbarRoleLabel))
                <div class="mqx-popover-role">{{ $mqTopbarRoleLabel }}</div>
            @endif
        </div>

        <div class="mqx-popover-body">
            <a class="mqx-popover-item" href="{{ $mqTopbarProfileUrl }}">
                <i class="bi bi-person" aria-hidden="true"></i>
                <span>Mi perfil</span>
            </a>
            <a class="mqx-popover-item" href="{{ $mqTopbarSettingsUrl }}">
                <i class="bi bi-gear" aria-hidden="true"></i>
                <span>Configuracion</span>
            </a>
            <a class="mqx-popover-item" href="{{ $mqTopbarHelpUrl }}">
                <i class="bi bi-question-circle" aria-hidden="true"></i>
                <span>Ayuda</span>
            </a>

            <div class="mqx-popover-divider" aria-hidden="true"></div>

            <form method="POST" action="{{ route('logout') }}" class="mqx-popover-logout">
                @csrf
                <button type="submit">
                    <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
                    <span>Cerrar sesion</span>
                </button>
            </form>
        </div>
    </div>

    <div class="mqx-popover mqx-popover--wide" data-mqx-popover="notifications" aria-hidden="true">
        <div class="mqx-popover-head mqx-popover-head--row">
            <div class="mqx-popover-name">Notificaciones</div>
            <button class="mqx-popover-action" type="button" onclick="markAllAsRead('{{ $mqMarkAllReadUrl }}')">Marcar todo como leido</button>
        </div>

        <div class="mqx-notif-list">
            @forelse ($mqTopbarNotifications as $notification)
                @php
                    $mqTopbarNotifIcon = match($notification->tipo ?? '') {
                        'pago_confirmado' => 'bi-credit-card-check',
                        'pago_pendiente' => 'bi-credit-card',
                        'servicio_aprobado' => 'bi-check-circle-fill',
                        'servicio_rechazado' => 'bi-x-circle-fill',
                        'cita_evaluacion' => 'bi-calendar-check',
                        'cotizacion' => 'bi-clipboard-check',
                        default => 'bi-bell',
                    };

                    $markReadUrl = '#';
                    if ($mqTopbarIsAdmin) $markReadUrl = route('admin.notifications.markRead', $notification->id);
                    elseif ($mqTopbarIsTrainer) $markReadUrl = route('entrenador.notifications.markRead', $notification->id);
                    elseif ($mqTopbarIsOwner) $markReadUrl = route('owner.notifications.markRead', $notification->id);
                @endphp
                <a class="mqx-notif-item" href="{{ $notification->url ?: $mqTopbarNotificationsUrl }}" 
                   onclick="event.preventDefault(); markAsReadAndRedirect('{{ $markReadUrl }}', '{{ $notification->url ?: $mqTopbarNotificationsUrl }}')">
                    <div class="mqx-notif-icon">
                        <i class="bi {{ $mqTopbarNotifIcon }}" aria-hidden="true"></i>
                    </div>
                    <div class="mqx-notif-body">
                        <div class="mqx-notif-title">{{ ucfirst(str_replace('_', ' ', $notification->tipo ?? 'Notificación')) }}</div>
                        <div class="mqx-notif-sub">{{ $notification->mensaje }}</div>
                        <div class="mqx-notif-time">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</div>
                    </div>
                </a>
            @empty
                <div class="mqx-notif-item mqx-notif-item--plain">
                    <div class="mqx-notif-body">
                        <div class="mqx-notif-title">No tienes notificaciones</div>
                        <div class="mqx-notif-sub">Cuando recibas una notificación, aparecerá aquí.</div>
                        <div class="mqx-notif-time"></div>
                    </div>
                </div>
            @endforelse
        </div>

        <a class="mqx-notif-footer" href="{{ $mqTopbarNotificationsUrl }}">Ver todas las notificaciones</a>
    </div>
</header>

<script src="{{ asset('js/shared/mq-topbar.js') }}?v={{ time() }}" defer></script>
<script>
    function clearNotifBadge() {
        const badge = document.getElementById('mqxNotifBadge');
        if (badge) {
            badge.style.display = 'none';
        }
    }

    function handleNotifClick(btn, markAllUrl) {
        clearNotifBadge();
        
        // El toggle del popover lo maneja mq-topbar.js via data-mqx-toggle
        // Solo necesitamos disparar el fetch aquí para asegurar que se ejecute
        // incluso si el evento se detiene en otros niveles.
        if (markAllUrl && markAllUrl !== '#') {
            fetch(markAllUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            }).catch(error => console.error('Error auto-marking read:', error));
        }
    }

    function markAsReadAndRedirect(url, redirectUrl) {
        if (!url || url === '#') {
            window.location.href = redirectUrl;
            return;
        }
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        }).finally(() => {
            window.location.href = redirectUrl;
        });
    }

    function markAllAsRead(url) {
        if (!url || url === '#') return;
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                clearNotifBadge();
                const notifList = document.querySelector('.mqx-notif-list');
                if (notifList) {
                    notifList.innerHTML = `
                        <div class="mqx-notif-item mqx-notif-item--plain">
                            <div class="mqx-notif-body">
                                <div class="mqx-notif-title">No tienes notificaciones</div>
                                <div class="mqx-notif-sub">Cuando recibas una notificación, aparecerá aquí.</div>
                                <div class="mqx-notif-time"></div>
                            </div>
                        </div>
                    `;
                }
            }
        }).catch(error => console.error('Error marking all read:', error));
    }
</script>
