<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Diario de Avances | Más Que Perros</title>
        <link rel="icon" type="image/png" href="{{ asset('img/huellita.png') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('css/shared/mq-topbar.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dueño/seguimiento.css') }}">
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
                ])

                <div class="mq-dashboard-content">
                    <section class="sg-page">
                    <div class="sg-head">
                        <h1 class="sg-title">Seguimiento del Perro</h1>
                        <p class="sg-sub">Reportes del entrenador, fotos y evaluaciones de comportamiento</p>

                        <div class="sg-pets" aria-label="Mascotas">
                            @forelse (($pets ?? []) as $pet)
                                <div class="sg-pet">
                                    <div class="sg-pet-row">
                                        <div class="sg-avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr($pet->name ?? 'M', 0, 1)) }}</div>
                                        <div class="sg-pet-main">
                                            <div class="sg-pet-name"><i class="bi bi-paw" aria-hidden="true"></i><span>{{ $pet->name }}</span></div>
                                            <div class="sg-pet-sub">{{ $pet->breed }}</div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="sg-pet">
                                    <div class="sg-pet-row">
                                        <div class="sg-avatar" aria-hidden="true">0</div>
                                        <div class="sg-pet-main">
                                            <div class="sg-pet-name"><i class="bi bi-paw" aria-hidden="true"></i><span>No tienes mascotas registradas</span></div>
                                            <div class="sg-pet-sub">Cuando registres una mascota, aparecerá aquí.</div>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        <div class="sg-tabs" role="tablist" aria-label="Categorías">
                            <button class="sg-tab sg-tab--active" type="button" role="tab" aria-selected="true">Todos los seguimientos</button>
                        </div>
                    </div>

                    <div class="sg-list" aria-label="Reportes">
                        @forelse (($reports ?? []) as $report)
                            <article class="sg-item" data-sg-item>
                                <div class="sg-item-head" data-sg-toggle>
                                    <div class="sg-item-left">
                                        <div class="sg-avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr($report->pet_name ?? 'M', 0, 1)) }}</div>
                                        <div class="sg-item-main">
                                            <div class="sg-item-title-row">
                                                <div class="sg-item-title">{{ $report->pet_name }} - {{ $report->activity_name ?: 'Seguimiento' }}</div>
                                            </div>
                                            <div class="sg-meta">
                                                <span><i class="bi bi-calendar-event" aria-hidden="true"></i>{{ optional($report->created_at ? \Carbon\Carbon::parse($report->created_at) : null)->format('d/m/Y H:i') }}</span>
                                                <span><i class="bi bi-person" aria-hidden="true"></i>{{ $report->trainer_name ?: 'Entrenador' }}</span>
                                                <span><i class="bi bi-graph-up" aria-hidden="true"></i>{{ ucfirst($report->nivel_progreso ?? '') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="sg-more" type="button" data-sg-btn aria-expanded="false">
                                        <i class="bi bi-chevron-down" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <div class="sg-details" hidden>
                                    <div class="sg-meta">
                                        <span><i class="bi bi-heart" aria-hidden="true"></i>Estado: {{ $report->estado_animo ?: 'No registrado' }}</span>
                                        <span><i class="bi bi-clock" aria-hidden="true"></i>Duración: {{ $report->duracion ? $report->duracion . ' minutos' : 'No registrada' }}</span>
                                    </div>
                                    @if($report->notas)
                                        <p>{{ $report->notas }}</p>
                                    @endif
                                    @if($report->mensaje_dueno)
                                        <p>{{ $report->mensaje_dueno }}</p>
                                    @endif
                                </div>
                            </article>
                        @empty
                            <article class="sg-item" data-sg-item>
                                <div class="sg-item-head" data-sg-toggle>
                                    <div class="sg-item-left">
                                        <div class="sg-avatar" aria-hidden="true">0</div>
                                        <div class="sg-item-main">
                                            <div class="sg-item-title-row">
                                                <div class="sg-item-title">No tienes reportes registrados</div>
                                            </div>
                                            <div class="sg-meta">
                                                <span><i class="bi bi-file-earmark-text" aria-hidden="true"></i>Cuando exista un reporte, aparecerá aquí.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforelse
                    </div>
                </section>
                </div>
            </main>
        </div>
        <script>
            (() => {
                const items = Array.from(document.querySelectorAll('[data-sg-item]'));

                const closeItem = (item) => {
                    const details = item.querySelector('.sg-details');
                    const btn = item.querySelector('[data-sg-btn]');
                    if (details) details.hidden = true;
                    if (btn) btn.setAttribute('aria-expanded', 'false');
                    item.classList.remove('sg-item--open');
                };

                const openItem = (item) => {
                    const details = item.querySelector('.sg-details');
                    const btn = item.querySelector('[data-sg-btn]');
                    if (details) details.hidden = false;
                    if (btn) btn.setAttribute('aria-expanded', 'true');
                    item.classList.add('sg-item--open');
                };

                const toggleItem = (item) => {
                    const isOpen = item.classList.contains('sg-item--open');
                    items.forEach((it) => closeItem(it));
                    if (!isOpen) openItem(item);
                };

                items.forEach((item) => {
                    const head = item.querySelector('[data-sg-toggle]');
                    const btn = item.querySelector('[data-sg-btn]');

                    if (head) {
                        head.addEventListener('click', (e) => {
                            if (e.target && e.target.closest && e.target.closest('[data-sg-btn]')) return;
                            toggleItem(item);
                        });
                    }

                    if (btn) {
                        btn.addEventListener('click', () => toggleItem(item));
                    }
                });
            })();
        </script>
    </body>
</html>
