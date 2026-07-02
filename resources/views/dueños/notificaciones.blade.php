<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Ladridos Informativos | Más Que Perros</title>
        <link rel="icon" type="image/png" href="{{ asset('img/huellita.png') }}">

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
                @include('partials.mq-topbar', [
                    'mqTopbarUser' => $user,
                    'mqTopbarName' => $user->name,
                    'roleLabel' => 'Propietario',
                    'profileUrl' => route('owner.perfil'),
                    'settingsUrl' => route('owner.perfil'),
                    'helpUrl' => route('owner.chat'),
                    'notificationsUrl' => route('owner.notificaciones'),
                ])

                <div class="mq-dashboard-content">
                    <section class="nt-page">
                    <div class="nt-head-row">
                        <div>
                            <h1 class="nt-title">Notificaciones</h1>
                            <p class="nt-sub">{{ $unreadCount ?? 0 }} sin leer</p>
                        </div>
                        <form action="{{ route('owner.notifications.markAllRead') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="nt-mark-all">Marcar todas como leídas</button>
                        </form>
                    </div>

                    <div class="nt-filters" role="tablist">
                        <button class="nt-filter nt-filter--active" data-filter="all">Todas</button>
                        <button class="nt-filter" data-filter="unread">No leídas</button>
                        <button class="nt-filter" data-filter="cita">Cita</button>
                        <button class="nt-filter" data-filter="reporte">Reporte</button>
                        <button class="nt-filter" data-filter="pago">Pago</button>
                        <button class="nt-filter" data-filter="promo">Promoción</button>
                    </div>

                    <div class="nt-list" id="notificationsList">
                        @forelse (($notifications ?? []) as $notification)
                            @php
                                $type = (string) ($notification->tipo ?? 'general');
                                $icon = 'bi-bell';
                                $color = 'gray';
                                $category = 'general';

                                if (str_contains($type, 'cita') || str_contains($type, 'evaluacion')) {
                                    $category = 'cita';
                                    $icon = 'bi-calendar-event';
                                    $color = 'blue';
                                } elseif (str_contains($type, 'pago') || str_contains($type, 'cotizacion')) {
                                    $category = 'pago';
                                    $icon = 'bi-credit-card';
                                    $color = 'green';
                                } elseif (str_contains($type, 'reporte') || str_contains($type, 'seguimiento')) {
                                    $category = 'reporte';
                                    $icon = 'bi-file-earmark-text';
                                    $color = 'purple';
                                } elseif (str_contains($type, 'promo')) {
                                    $category = 'promo';
                                    $icon = 'bi-tag';
                                    $color = 'orange';
                                }

                                if ($type === 'servicio_aprobado') { $icon = 'bi-check-circle'; $color = 'green'; }
                                if ($type === 'servicio_rechazado') { $icon = 'bi-x-circle'; $color = 'red'; }
                            @endphp
                            <article class="nt-item {{ !$notification->leido ? 'nt-item--unread' : '' }}" 
                                     data-category="{{ $category }}" 
                                     data-unread="{{ !$notification->leido ? 'true' : 'false' }}">
                                <div class="nt-ico-wrap nt-ico--{{ $color }}">
                                    <i class="bi {{ $icon }}" aria-hidden="true"></i>
                                </div>
                                <div class="nt-main">
                                    <div class="nt-top-row">
                                        <h2 class="nt-item-title">{{ ucfirst(str_replace(['_', 'servicio_'], ['', ' '], $notification->tipo ?? 'Notificación')) }}</h2>
                                    </div>
                                    <p class="nt-desc">{{ $notification->mensaje }}</p>
                                    <div class="nt-time">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</div>
                                    @if(!empty($notification->url))
                                        <a class="nt-mark-all" href="{{ $notification->url }}">Ver detalle</a>
                                    @endif
                                    @if(!$notification->leido)
                                        <form action="{{ route('owner.notifications.markRead', $notification->id) }}" method="POST" style="display: inline; margin-left: 10px;">
                                            @csrf
                                            <button type="submit" class="nt-mark-all">Marcar como leída</button>
                                        </form>
                                    @endif
                                </div>
                            </article>
                        @empty
                            <article class="nt-item" data-category="all">
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

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const filters = document.querySelectorAll('.nt-filter');
                const items = document.querySelectorAll('.nt-item');

                filters.forEach(filter => {
                    filter.addEventListener('click', () => {
                        // Cambiar clase activa en los botones
                        filters.forEach(f => f.classList.remove('nt-filter--active'));
                        filter.classList.add('nt-filter--active');

                        const filterValue = filter.getAttribute('data-filter');

                        items.forEach(item => {
                            if (filterValue === 'all') {
                                item.style.display = 'flex';
                            } else if (filterValue === 'unread') {
                                item.style.display = item.getAttribute('data-unread') === 'true' ? 'flex' : 'none';
                            } else {
                                item.style.display = item.getAttribute('data-category') === filterValue ? 'flex' : 'none';
                            }
                        });
                    });
                });
            });
        </script>
    </body>
</html>
