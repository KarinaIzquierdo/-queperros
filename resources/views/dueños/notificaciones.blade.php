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
                            <p class="nt-sub">2 sin leer</p>
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
                        <article class="nt-item nt-item--unread">
                            <div class="nt-ico-wrap nt-ico--blue">
                                <i class="bi bi-calendar-event" aria-hidden="true"></i>
                            </div>
                            <div class="nt-main">
                                <div class="nt-top-row">
                                    <h2 class="nt-item-title">Sesion manana</h2>
                                    <span class="nt-dot" aria-hidden="true"></span>
                                </div>
                                <p class="nt-desc">Recuerda la sesion de entrenamiento de Max manana a las 10:00 am</p>
                                <div class="nt-time">Hace 2 horas</div>
                            </div>
                        </article>

                        <article class="nt-item nt-item--unread">
                            <div class="nt-ico-wrap nt-ico--purple">
                                <i class="bi bi-paw" aria-hidden="true"></i>
                            </div>
                            <div class="nt-main">
                                <div class="nt-top-row">
                                    <h2 class="nt-item-title">Nuevo reporte disponible</h2>
                                    <span class="nt-dot" aria-hidden="true"></span>
                                </div>
                                <p class="nt-desc">El entrenador ha subido un nuevo reporte de progreso para Max</p>
                                <div class="nt-time">Hace 5 horas</div>
                            </div>
                        </article>

                        <article class="nt-item">
                            <div class="nt-ico-wrap nt-ico--green">
                                <i class="bi bi-check2-circle" aria-hidden="true"></i>
                            </div>
                            <div class="nt-main">
                                <div class="nt-top-row">
                                    <h2 class="nt-item-title">Pago recibido</h2>
                                </div>
                                <p class="nt-desc">Hemos recibido tu pago de $150.000 por el entrenamiento de Marzo</p>
                                <div class="nt-time">Hace 1 dia</div>
                            </div>
                        </article>

                        <article class="nt-item">
                            <div class="nt-ico-wrap nt-ico--pink">
                                <i class="bi bi-heart" aria-hidden="true"></i>
                            </div>
                            <div class="nt-main">
                                <div class="nt-top-row">
                                    <h2 class="nt-item-title">Promocion especial</h2>
                                </div>
                                <p class="nt-desc">20% de descuento en guarderia durante todo el mes de Marzo</p>
                                <div class="nt-time">Hace 3 dias</div>
                            </div>
                        </article>

                        <article class="nt-item">
                            <div class="nt-ico-wrap nt-ico--gray">
                                <i class="bi bi-bell" aria-hidden="true"></i>
                            </div>
                            <div class="nt-main">
                                <div class="nt-top-row">
                                    <h2 class="nt-item-title">Vacuna pendiente</h2>
                                </div>
                                <p class="nt-desc">Luna necesita su vacuna de refuerzo. Agenda una cita pronto.</p>
                                <div class="nt-time">Hace 5 dias</div>
                            </div>
                        </article>
                    </div>
                </section>
                </div>
            </main>
        </div>
    </body>
</html>
