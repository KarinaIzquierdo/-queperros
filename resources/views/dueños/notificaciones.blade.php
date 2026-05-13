<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Notificaciones</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('css/shared/mq-topbar.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dueño/notificaciones.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dueño/panel.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="{{ asset('css/Admin/admin-sidebar-extras.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    </head>

    <body class="mq-dashboard-page">
        @include('partials.page-loader')
        @php
            use Illuminate\Support\Str;
        @endphp

        <div class="mq-dashboard">
            @include("partials.dueno-sidebar")

            <main class="mq-dashboard-main">
                @include('partials.mq-topbar', ['user' => Auth::user(), 'user' => Auth::user(), 
                    'user' => $user,
                    'roleLabel' => 'Propietario',
                    'profileUrl' => route('owner.perfil'),
                    'settingsUrl' => route('owner.perfil'),
                    'helpUrl' => route('owner.chat'),
                    'notificationsUrl' => route('owner.notificaciones'),
                    'notifCount' => 2,
                ])

                <div class="mq-dashboard-content">
                    <section class="nt-page">
                    <div class="nt-head-row">
                        <div>
                            <h1 class="nt-title">Notificaciones</h1>
                            <p class="nt-sub">{{ $unreadCount ?? 0 }} sin leer</p>
                        </div>
                        <a href="#" class="nt-mark-all">Marcar todas como leidas</a>
                    </div>

                    <div class="nt-filters" role="tablist">
                        <button class="nt-filter nt-filter--active">Todas</button>
                        <button class="nt-filter">No leidas</button>
                        <button class="nt-filter">Cita</button>
                        <button class="nt-filter">Reporte</button>
                        <button class="nt-filter">Pago</button>
                        <button class="nt-filter">Promocion</button>
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
                                        <h2 class="nt-item-title">{{ $notification->titulo }}</h2>
                                    </div>
                                    <p class="nt-desc">{{ $notification->mensaje }}</p>
                                    <div class="nt-time">{{ optional($notification->created_at ? \Carbon\Carbon::parse($notification->created_at) : null)->diffForHumans() }}</div>
                                    @if(!empty($notification->url))
                                        <a class="nt-mark-all" href="{{ $notification->url }}">Ver detalle</a>
                                    @endif
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
                                    <div class="nt-time"></div>
                                </div>
                            </article>
                        @endforelse
                    </div>
                </section>
                </div>
            </main>
        </div>
    </body>
</html>
