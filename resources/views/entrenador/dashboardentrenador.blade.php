<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Dashboard entrenador</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('css/shared/mq-topbar.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dueño/panel.css') }}">
        <link rel="stylesheet" href="{{ asset('css/entrenador/dashboardentrenador.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    </head>
    <body>
        @include('partials.page-loader')
        @php
            use Illuminate\Support\Str;
        @endphp
        <div class="mq-dashboard et-dashboard">
            @include('partials.entrenador-sidebar')

            <main class="mq-dashboard-main et-main">
                @include('partials.mq-topbar', [
                    'user' => $user,
                    'roleLabel' => 'Entrenador',
                    'profileUrl' => route('entrenador.perfil'),
                    'settingsUrl' => route('entrenador.perfil'),
                    'helpUrl' => route('entrenador.chat'),
                    'notificationsUrl' => route('entrenador.notificaciones'),
                    'notifCount' => 2,
                ])

                <section class="et-hero">
                    <h1 class="et-title">Bienvenido, {{ strtok($user->name ?? 'Entrenador', ' ') }}</h1>
                    <p class="et-sub">{{ now()->translatedFormat('l, d \d\e F Y') }}</p>
                </section>

                <section class="et-kpis" aria-label="Resumen">
                    <div class="et-kpi et-kpi--pending">
                        <div class="et-kpi-icon" aria-hidden="true"><i class="bi bi-calendar2"></i></div>
                        <div class="et-kpi-body">
                            <div class="et-kpi-value">{{ (int) ($kpis['pending_reservations'] ?? 0) }}</div>
                            <div class="et-kpi-label">Reservas Pendientes</div>
                        </div>
                    </div>
                    <div class="et-kpi et-kpi--confirmed">
                        <div class="et-kpi-icon" aria-hidden="true"><i class="bi bi-check-circle"></i></div>
                        <div class="et-kpi-body">
                            <div class="et-kpi-value">{{ (int) ($kpis['confirmed_reservations'] ?? 0) }}</div>
                            <div class="et-kpi-label">Reservas Confirmadas</div>
                        </div>
                    </div>
                    <div class="et-kpi et-kpi--weekly">
                        <div class="et-kpi-icon" aria-hidden="true"><i class="bi bi-clock"></i></div>
                        <div class="et-kpi-body">
                            <div class="et-kpi-value">{{ (int) ($kpis['weekly_appointments'] ?? 0) }}</div>
                            <div class="et-kpi-label">Citas Esta Semana</div>
                        </div>
                    </div>
                    <div class="et-kpi et-kpi--income">
                        <div class="et-kpi-icon" aria-hidden="true"><i class="bi bi-currency-dollar"></i></div>
                        <div class="et-kpi-body">
                            <div class="et-kpi-value">$ {{ number_format((int) ($kpis['monthly_income'] ?? 0), 0, ',', '.') }}</div>
                            <div class="et-kpi-label">Ingresos del Mes</div>
                        </div>
                    </div>
                </section>

                <section class="et-shortcuts" aria-label="Accesos rápidos">
                    <a class="et-shortcut" href="{{ route('entrenador.reservas') }}">
                        <span class="et-shortcut-icon" aria-hidden="true"><i class="bi bi-calendar2"></i></span>
                        <div class="et-shortcut-title">Gestionar Reservas</div>
                        <div class="et-shortcut-sub">Confirmar y modificar citas</div>
                    </a>
                    <a class="et-shortcut" href="{{ route('entrenador.horario') }}">
                        <span class="et-shortcut-icon" aria-hidden="true"><i class="bi bi-clock"></i></span>
                        <div class="et-shortcut-title">Mi Horario</div>
                        <div class="et-shortcut-sub">Ver y ajustar disponibilidad</div>
                    </a>
                    <a class="et-shortcut" href="{{ route('entrenador.chat') }}">
                        <span class="et-shortcut-icon" aria-hidden="true"><i class="bi bi-chat-dots"></i></span>
                        <div class="et-shortcut-title">Mensajes</div>
                        <div class="et-shortcut-sub">Comunicarse con dueños</div>
                    </a>
                </section>

                <section class="et-reservas" aria-label="Reservas pendientes">
                    <div class="et-reservas-card">
                        <div class="et-reservas-head">
                            <div class="et-reservas-title">
                                <span class="et-reservas-dot" aria-hidden="true"></span>
                                Reservas Pendientes de Confirmacion
                            </div>
                            <a class="et-reservas-all" href="{{ route('entrenador.reservas') }}">Ver todas</a>
                        </div>

                        <div class="et-reservas-list">
                            @foreach (($pendingReservations ?? []) as $r)
                                <article class="et-reserva">
                                    <div class="et-reserva-top">
                                        <div class="et-reserva-avatar" aria-hidden="true">
                                            <i class="bi bi-person"></i>
                                        </div>
                                        <div class="et-reserva-main">
                                            <div class="et-reserva-pet">{{ $r['pet'] ?? '' }}</div>
                                            <div class="et-reserva-owner">{{ $r['owner'] ?? '' }}</div>
                                        </div>
                                        <div class="et-reserva-status">{{ $r['status'] ?? 'PENDIENTE' }}</div>
                                    </div>

                                    <div class="et-reserva-details">
                                        <div class="et-reserva-field">
                                            <div class="et-reserva-k">SERVICIO</div>
                                            <div class="et-reserva-v">{{ $r['service'] ?? '' }}</div>
                                        </div>
                                        <div class="et-reserva-field">
                                            <div class="et-reserva-k">FECHA</div>
                                            <div class="et-reserva-v">{{ $r['date'] ?? '' }}</div>
                                        </div>
                                        <div class="et-reserva-field">
                                            <div class="et-reserva-k">PRECIO</div>
                                            <div class="et-reserva-v">$ {{ number_format((int) ($r['price'] ?? 0), 0, ',', '.') }}</div>
                                        </div>
                                    </div>

                                    <div class="et-reserva-actions">
                                        <button class="et-action et-action--confirm" type="button">
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            Confirmar
                                        </button>
                                        <button class="et-action et-action--edit" type="button">
                                            <i class="bi bi-pencil" aria-hidden="true"></i>
                                            Modificar
                                        </button>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </body>
</html>
