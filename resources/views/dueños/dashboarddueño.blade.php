<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Mi Panel</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />
    
        <link rel="stylesheet" href="{{ asset('css/shared/mq-topbar.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dueño/dashboarddueño.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dueño/panel.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    </head>

    <body class="mq-dashboard-page">
        @include('partials.page-loader')
        @php
            use Illuminate\Support\Str;
        @endphp
        <div class="mq-dashboard">
            @include('partials.dueno-sidebar')

            <main class="mq-dashboard-main">
                @include('partials.mq-topbar', [
                    'user' => $user,
                    'roleLabel' => 'Propietario',
                    'profileUrl' => route('owner.perfil'),
                    'settingsUrl' => route('owner.perfil'),
                    'helpUrl' => route('owner.chat'),
                    'notificationsUrl' => route('owner.notificaciones'),
                    'notifCount' => 2,
                ])

                <div class="mq-dashboard-content">
                    <section class="mq-dashboard-hero" aria-label="Bienvenida">
                    <div class="mq-dashboard-hero-greeting">Hola, {{ Str::before($user->name, ' ') }}</div>
                    <h1 class="mq-dashboard-hero-title">Bienvenido a Mas que Perros</h1>
                    <div class="mq-dashboard-hero-sub">Tu perro feliz, tu tranquilo</div>
                </section>

                <section class="mq-feature" aria-label="Resumen de tu perro">
                    <div class="mq-feature-card">
                        <div class="mq-feature-left">
                            <div class="mq-feature-photo" aria-hidden="true">
                                <img src="{{ asset('img/pet.png') }}" alt="">
                            </div>
                        </div>
                        <div class="mq-feature-main">
                            <div class="mq-feature-head">
                                <div class="mq-feature-name">Max</div>
                                <div class="mq-feature-breed">(Pastor Aleman)</div>
                            </div>
                            <div class="mq-feature-status">
                                <div class="mq-feature-status-label">Estado actual:</div>
                                <span class="mq-feature-badge">En entrenamiento</span>
                            </div>
                            <div class="mq-feature-next">
                                <div class="mq-feature-next-ico"><i class="bi bi-calendar2-check" aria-hidden="true"></i></div>
                                <div>
                                    <div class="mq-feature-next-label">Proxima sesion:</div>
                                    <div class="mq-feature-next-value">12 Marzo - 10:00 am</div>
                                </div>
                            </div>
                            <div class="mq-feature-report">
                                <div class="mq-feature-report-label">Ultimo reporte:</div>
                                <div class="mq-feature-report-text">Mejora en obediencia básica. Excelente progreso en comandos de sentado y quieto.</div>
                                <a class="mq-feature-report-link" href="#">Ver reporte completo <i class="bi bi-chevron-right" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mq-kpis">
                    <div class="mq-kpi">
                        <div class="mq-kpi-ico"><i class="bi bi-paw" aria-hidden="true"></i></div>
                        <div class="mq-kpi-value">3</div>
                        <div class="mq-kpi-label">Mis Perros</div>
                    </div>
                    <div class="mq-kpi">
                        <div class="mq-kpi-ico"><i class="bi bi-calendar2-check" aria-hidden="true"></i></div>
                        <div class="mq-kpi-value">2</div>
                        <div class="mq-kpi-label">Reservas Activas</div>
                    </div>
                    <div class="mq-kpi">
                        <div class="mq-kpi-ico"><i class="bi bi-activity" aria-hidden="true"></i></div>
                        <div class="mq-kpi-value">5</div>
                        <div class="mq-kpi-label">Reportes</div>
                    </div>
                    <div class="mq-kpi">
                        <div class="mq-kpi-ico"><i class="bi bi-bell" aria-hidden="true"></i></div>
                        <div class="mq-kpi-value">2</div>
                        <div class="mq-kpi-label">Notificaciones</div>
                    </div>
                </section>

                <section class="mq-panels">
                    <article class="mq-panel">
                        <header class="mq-panel-head">
                            <h2 class="mq-panel-title">Proximas Reservas</h2>
                            <a href="#" class="mq-panel-link">Ver todas <i class="bi bi-chevron-right" aria-hidden="true"></i></a>
                        </header>

                        <div class="mq-panel-list">
                            <div class="mq-row">
                                <div class="mq-row-ico"><i class="bi bi-calendar2-week" aria-hidden="true"></i></div>
                                <div class="mq-row-body">
                                    <div class="mq-row-title">Entrenamiento Basico</div>
                                    <div class="mq-row-sub">Max - 12 Mar</div>
                                </div>
                                <span class="mq-pill mq-pill--ok">Confirmada</span>
                            </div>
                            <div class="mq-row">
                                <div class="mq-row-ico"><i class="bi bi-calendar2-week" aria-hidden="true"></i></div>
                                <div class="mq-row-body">
                                    <div class="mq-row-title">Hotel Canino</div>
                                    <div class="mq-row-sub">Luna - 15-20 Mar</div>
                                </div>
                                <span class="mq-pill mq-pill--warn">Pendiente</span>
                            </div>
                            <div class="mq-row">
                                <div class="mq-row-ico"><i class="bi bi-calendar2-week" aria-hidden="true"></i></div>
                                <div class="mq-row-body">
                                    <div class="mq-row-title">Guarderia</div>
                                    <div class="mq-row-sub">Rocky - 18 Mar</div>
                                </div>
                                <span class="mq-pill mq-pill--ok">Confirmada</span>
                            </div>
                        </div>
                    </article>

                    <article class="mq-panel">
                        <header class="mq-panel-head">
                            <h2 class="mq-panel-title">Notificaciones</h2>
                            <a href="#" class="mq-panel-link">Ver todas <i class="bi bi-chevron-right" aria-hidden="true"></i></a>
                        </header>

                        <div class="mq-panel-list">
                            <div class="mq-note mq-note--blue">
                                <div class="mq-note-dot"></div>
                                <div class="mq-note-body">
                                    <div class="mq-note-title">Sesion manana</div>
                                    <div class="mq-note-sub">Recuerda la sesion de entrenamiento de Max manana a las 10:00 am</div>
                                </div>
                            </div>
                            <div class="mq-note mq-note--blue">
                                <div class="mq-note-dot"></div>
                                <div class="mq-note-body">
                                    <div class="mq-note-title">Nuevo reporte disponible</div>
                                    <div class="mq-note-sub">El entrenador ha subido un nuevo reporte de progreso</div>
                                </div>
                            </div>
                            <div class="mq-note">
                                <div class="mq-note-body">
                                    <div class="mq-note-title">Promocion especial</div>
                                    <div class="mq-note-sub">20% de descuento en guarderia este mes</div>
                                </div>
                            </div>
                        </div>
                    </article>
                </section>

                <section class="mq-activity">
                    <header class="mq-panel-head mq-panel-head--wide">
                        <h2 class="mq-panel-title">Ultimas Actividades</h2>
                        <button class="mq-star" type="button" aria-label="Favorito">
                            <i class="bi bi-star" aria-hidden="true"></i>
                        </button>
                    </header>

                    <div class="mq-activity-list">
                        <div class="mq-act">
                            <div class="mq-act-ico mq-act-ico--pink"><i class="bi bi-file-earmark-text" aria-hidden="true"></i></div>
                            <div class="mq-act-body">
                                <div class="mq-act-title">Nuevo reporte de entrenamiento - Max</div>
                                <div class="mq-act-sub">Hace 2 horas</div>
                            </div>
                        </div>
                        <div class="mq-act">
                            <div class="mq-act-ico"><i class="bi bi-calendar2-check" aria-hidden="true"></i></div>
                            <div class="mq-act-body">
                                <div class="mq-act-title">Reserva confirmada - Hotel Canino</div>
                                <div class="mq-act-sub">Hace 1 dia</div>
                            </div>
                        </div>
                        <div class="mq-act">
                            <div class="mq-act-ico mq-act-ico--green"><i class="bi bi-cash" aria-hidden="true"></i></div>
                            <div class="mq-act-body">
                                <div class="mq-act-title">Pago recibido - Entrenamiento</div>
                                <div class="mq-act-sub">Hace 3 dias</div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mq-helpbar" aria-label="Necesitas ayuda">
                    <div class="mq-helpbar-title">Necesitas ayuda?</div>
                    <div class="mq-helpbar-items">
                        <div class="mq-helpbar-item"><i class="bi bi-telephone" aria-hidden="true"></i><span>+57 300 123 4567</span></div>
                        <div class="mq-helpbar-item"><i class="bi bi-geo-alt" aria-hidden="true"></i><span>La Calera, Colombia</span></div>
                        <div class="mq-helpbar-item"><i class="bi bi-clock" aria-hidden="true"></i><span>Lun - Sab: 8am - 6pm</span></div>
                    </div>
                </section>

                <footer class="mq-footer-mini">Mas que Perros — Tu perro feliz, tu tranquilo</footer>
            </main>
        </div>
    </body>
</html>
