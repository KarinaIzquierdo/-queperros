<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Mis Reservas</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('css/shared/mq-topbar.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dueño/panel.css') }}">
        <link rel="stylesheet" href="{{ asset('css/entrenador/dashboardentrenador.css') }}">
        <link rel="stylesheet" href="{{ asset('css/entrenador/reservas.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="{{ asset('css/Admin/admin-sidebar-extras.css') }}?v={{ time() }}">
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

                <section class="tr-reservas" aria-label="Gestión de reservas">
                    <header class="tr-head">
                        <h1 class="tr-title">Mis Reservas</h1>
                        <p class="tr-subtitle">Gestiona las reservas asignadas a ti</p>
                    </header>

                    <div class="tr-stats">
                        <div class="tr-stat tr-stat--pending">
                            <div class="tr-stat-icon"><i class="bi bi-clock"></i></div>
                            <div class="tr-stat-body">
                                <div class="tr-stat-value">{{ $counts['pendientes'] ?? 0 }}</div>
                                <div class="tr-stat-label">Pendientes</div>
                            </div>
                        </div>
                        <div class="tr-stat tr-stat--confirmed">
                            <div class="tr-stat-icon"><i class="bi bi-check-circle"></i></div>
                            <div class="tr-stat-body">
                                <div class="tr-stat-value">{{ $counts['confirmadas'] ?? 0 }}</div>
                                <div class="tr-stat-label">Confirmadas</div>
                            </div>
                        </div>
                        <div class="tr-stat tr-stat--total">
                            <div class="tr-stat-icon"><i class="bi bi-calendar3"></i></div>
                            <div class="tr-stat-body">
                                <div class="tr-stat-value">{{ $counts['total'] ?? 0 }}</div>
                                <div class="tr-stat-label">Total</div>
                            </div>
                        </div>
                    </div>

                    <div class="tr-list">
                        @foreach (($reservas ?? []) as $r)
                            <article class="tr-card {{ $r['status'] === 'pendiente' ? 'tr-card--pending' : 'tr-card--confirmed' }}">
                                <div class="tr-card-header">
                                    <div class="tr-card-pet">
                                        <span class="tr-card-avatar"><i class="bi bi-heart"></i></span>
                                        <div>
                                            <div class="tr-card-pet-name">{{ $r['pet'] }}</div>
                                            <div class="tr-card-owner">{{ $r['owner'] }}</div>
                                        </div>
                                    </div>
                                    <span class="tr-card-status {{ $r['status'] === 'pendiente' ? 'tr-status--pending' : 'tr-status--confirmed' }}">
                                        {{ ucfirst($r['status']) }}
                                    </span>
                                </div>

                                <div class="tr-card-body">
                                    <div class="tr-card-detail">
                                        <i class="bi bi-tag"></i>
                                        <span>{{ $r['service'] }}</span>
                                    </div>
                                    <div class="tr-card-detail">
                                        <i class="bi bi-calendar-event"></i>
                                        <span>{{ $r['date'] }}</span>
                                    </div>
                                    <div class="tr-card-detail">
                                        <i class="bi bi-clock"></i>
                                        <span>{{ $r['time'] }}</span>
                                    </div>
                                    <div class="tr-card-detail">
                                        <i class="bi bi-currency-dollar"></i>
                                        <span>$ {{ number_format($r['price'], 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                @if($r['comments'])
                                    <div class="tr-card-comments">
                                        <i class="bi bi-chat-quote"></i>
                                        {{ $r['comments'] }}
                                    </div>
                                @endif

                                <div class="tr-card-actions">
                                    @if($r['status'] === 'pendiente')
                                        <button class="tr-btn tr-btn--confirm" data-id="{{ $r['id'] }}">
                                            <i class="bi bi-check-lg"></i> Confirmar
                                        </button>
                                    @endif
                                    <button class="tr-btn tr-btn--view" data-id="{{ $r['id'] }}">
                                        <i class="bi bi-eye"></i> Ver detalles
                                    </button>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            </main>
        </div>

        <script>
            document.querySelectorAll('.tr-btn--confirm').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    if (confirm('¿Confirmar esta reserva?')) {
                        console.log('Confirmar reserva:', id);
                        // TODO: Implement confirm reservation via AJAX
                    }
                });
            });
        </script>
    </body>
</html>
