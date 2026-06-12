<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Notificaciones - Admin</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/shared/mq-topbar.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/admin-dashboard.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/admin-sidebar-extras.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/dueño/notificaciones.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    </head>
    <body>
        @include('partials.page-loader')
        <div class="admin-layout">
            @include('partials.admin-sidebar')

            <main class="admin-main">
                @include('partials.mq-topbar', [
                    'user' => $user,
                    'roleLabel' => 'Administrador',
                    'profileUrl' => route('admin.settings'),
                    'settingsUrl' => route('admin.settings'),
                    'helpUrl' => route('admin.dashboard'),
                    'notificationsUrl' => route('admin.notificaciones'),
                ])

                <section class="nt-page">
                    <div class="nt-head-row">
                        <div>
                            <h1 class="nt-title">Notificaciones</h1>
                            <p class="nt-sub">{{ $unreadCount ?? 0 }} sin leer</p>
                        </div>
                    </div>

                    <div class="nt-list">
                        @forelse (($notifications ?? []) as $notification)
                            @php
                                $type = (string) ($notification->tipo ?? 'general');
                                $icon = $type === 'pago' ? 'bi-credit-card' : ($type === 'cita' ? 'bi-calendar-check' : 'bi-bell');
                                $color = $type === 'pago' ? 'blue' : ($type === 'cita' ? 'green' : 'gray');
                            @endphp
                            <article class="nt-item {{ empty($notification->leida_en) ? 'nt-item--unread' : '' }}">
                                <div class="nt-ico-wrap nt-ico--{{ $color }}">
                                    <i class="bi {{ $icon }}" aria-hidden="true"></i>
                                </div>
                                <div class="nt-main">
                                    <div class="nt-top-row">
                                        <h2 class="nt-item-title">{{ $notification->titulo ?? 'Notificación' }}</h2>
                                    </div>
                                    <p class="nt-desc">{{ $notification->mensaje ?? '' }}</p>
                                    <div class="nt-time">{{ $notification->created_at ? \Carbon\Carbon::parse($notification->created_at)->diffForHumans() : '' }}</div>
                                </div>
                            </article>
                        @empty
                            <article class="nt-item">
                                <div class="nt-ico-wrap nt-ico--gray">
                                    <i class="bi bi-bell" aria-hidden="true"></i>
                                </div>
                                <div class="nt-main">
                                    <div class="nt-top-row">
                                        <h2 class="nt-item-title">No tienes notificaciones</h2>
                                    </div>
                                    <p class="nt-desc">Cuando recibas una notificación, aparecerá aquí.</p>
                                </div>
                            </article>
                        @endforelse
                    </div>
                </section>
            </main>
        </div>
    </body>
</html>
