<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Seguimiento</title>

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
                    'notifCount' => 2,
                ])

                <div class="mq-dashboard-content">
                    <section class="sg-page">
                    <div class="sg-head">
                        <h1 class="sg-title">Seguimiento del Perro</h1>
                        <p class="sg-sub">Reportes del entrenador, fotos y evaluaciones de comportamiento</p>

                        <div class="sg-pets" aria-label="Mascotas">
                            <div class="sg-pet">
                                <div class="sg-pet-row">
                                    <div class="sg-avatar" aria-hidden="true">M</div>
                                    <div class="sg-pet-main">
                                        <div class="sg-pet-name"><i class="bi bi-paw" aria-hidden="true"></i><span>Max</span></div>
                                        <div class="sg-pet-sub">En entrenamiento</div>
                                        <div class="sg-progress">
                                            <div class="sg-progress-head">
                                                <span>Progreso</span>
                                                <span>75%</span>
                                            </div>
                                            <div class="sg-progress-bar"><div class="sg-progress-fill" style="width: 75%"></div></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="sg-pet">
                                <div class="sg-pet-row">
                                    <div class="sg-avatar" aria-hidden="true">L</div>
                                    <div class="sg-pet-main">
                                        <div class="sg-pet-name"><i class="bi bi-paw" aria-hidden="true"></i><span>Luna</span></div>
                                        <div class="sg-pet-sub">Socializacion</div>
                                        <div class="sg-progress">
                                            <div class="sg-progress-head">
                                                <span>Progreso</span>
                                                <span>60%</span>
                                            </div>
                                            <div class="sg-progress-bar"><div class="sg-progress-fill" style="width: 60%"></div></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="sg-pet">
                                <div class="sg-pet-row">
                                    <div class="sg-avatar" aria-hidden="true">R</div>
                                    <div class="sg-pet-main">
                                        <div class="sg-pet-name"><i class="bi bi-paw" aria-hidden="true"></i><span>Rocky</span></div>
                                        <div class="sg-pet-sub">Sin servicio activo</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="sg-tabs" role="tablist" aria-label="Categorias">
                            <button class="sg-tab sg-tab--active" type="button" role="tab" aria-selected="true">Todos</button>
                            <button class="sg-tab" type="button" role="tab" aria-selected="false">Entrenamiento</button>
                            <button class="sg-tab" type="button" role="tab" aria-selected="false">Comportamiento</button>
                            <button class="sg-tab" type="button" role="tab" aria-selected="false">General</button>
                        </div>
                    </div>

                    <div class="sg-list" aria-label="Reportes">
                        <article class="sg-item" data-sg-item>
                            <div class="sg-item-head" data-sg-toggle>
                                <div class="sg-item-left">
                                    <div class="sg-avatar" aria-hidden="true">M</div>
                                    <div class="sg-item-main">
                                        <div class="sg-item-title-row">
                                            <div class="sg-item-title">Sesion de Obediencia Basica</div>
                                            <span class="sg-pill sg-pill--entreno">Entrenamiento</span>
                                        </div>
                                        <div class="sg-meta">
                                            <span><i class="bi bi-person" aria-hidden="true"></i>Max</span>
                                            <span><i class="bi bi-calendar" aria-hidden="true"></i>2026-03-08</span>
                                            <span><i class="bi bi-activity" aria-hidden="true"></i>Carlos Martinez</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="sg-item-right">
                                    <div class="sg-stars" aria-label="Calificacion 5 de 5">
                                        <i class="bi bi-star-fill sg-star sg-star--on" aria-hidden="true"></i>
                                        <i class="bi bi-star-fill sg-star sg-star--on" aria-hidden="true"></i>
                                        <i class="bi bi-star-fill sg-star sg-star--on" aria-hidden="true"></i>
                                        <i class="bi bi-star-fill sg-star sg-star--on" aria-hidden="true"></i>
                                        <i class="bi bi-star-fill sg-star sg-star--on" aria-hidden="true"></i>
                                    </div>
                                    <button class="sg-chevron" type="button" data-sg-btn aria-expanded="false" aria-label="Ver detalle">
                                        <i class="bi bi-chevron-down" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="sg-details" hidden>
                                <div class="sg-section sg-section--summary">
                                    <div class="sg-section-head">
                                        <i class="bi bi-file-earmark-text" aria-hidden="true"></i>
                                        <div class="sg-section-title">Resumen</div>
                                    </div>
                                    <div class="sg-section-body">Excelente progreso en comandos basicos. Max muestra gran disposicion para aprender y responde muy bien a los estimulos positivos.</div>
                                </div>

                                <div class="sg-section">
                                    <div class="sg-section-head">
                                        <i class="bi bi-graph-up" aria-hidden="true"></i>
                                        <div class="sg-section-title">Detalles de la Sesion</div>
                                    </div>
                                    <div class="sg-checklist">
                                        <div class="sg-check"><i class="bi bi-check-circle-fill" aria-hidden="true"></i><span>Trabajamos el comando 'sentado' con 95% de precision</span></div>
                                        <div class="sg-check"><i class="bi bi-check-circle-fill" aria-hidden="true"></i><span>Mejora notable en 'quieto' - mantiene posicion por 30 segundos</span></div>
                                        <div class="sg-check"><i class="bi bi-check-circle-fill" aria-hidden="true"></i><span>Comenzamos entrenamiento de 'ven aqui' con correa larga</span></div>
                                        <div class="sg-check"><i class="bi bi-check-circle-fill" aria-hidden="true"></i><span>Socializacion con otros perros: comportamiento tranquilo</span></div>
                                    </div>
                                </div>

                                <div class="sg-section">
                                    <div class="sg-section-head">
                                        <i class="bi bi-lightbulb" aria-hidden="true"></i>
                                        <div class="sg-section-title">Recomendaciones para el Tutor</div>
                                    </div>
                                    <div class="sg-recos">
                                        <div class="sg-reco"><div class="sg-reco-num">1</div><div class="sg-reco-text">Practicar 'sentado' 10 minutos diarios en casa</div></div>
                                        <div class="sg-reco"><div class="sg-reco-num">2</div><div class="sg-reco-text">Usar premios pequenos para reforzar comandos</div></div>
                                        <div class="sg-reco"><div class="sg-reco-num">3</div><div class="sg-reco-text">Evitar gritar los comandos, usar tono firme pero calmo</div></div>
                                        <div class="sg-reco"><div class="sg-reco-num">4</div><div class="sg-reco-text">Continuar socializacion en parques con correa</div></div>
                                    </div>
                                </div>

                                <div class="sg-section">
                                    <div class="sg-section-head">
                                        <i class="bi bi-image" aria-hidden="true"></i>
                                        <div class="sg-section-title">Fotos de la Sesion</div>
                                    </div>
                                    <div class="sg-photos">
                                        <div class="sg-photo" aria-hidden="true"></div>
                                    </div>
                                </div>
                            </div>
                        </article>

                        <article class="sg-item" data-sg-item>
                            <div class="sg-item-head" data-sg-toggle>
                                <div class="sg-item-left">
                                    <div class="sg-avatar" aria-hidden="true">M</div>
                                    <div class="sg-item-main">
                                        <div class="sg-item-title-row">
                                            <div class="sg-item-title">Evaluacion de Comportamiento</div>
                                            <span class="sg-pill sg-pill--comp">Comportamiento</span>
                                        </div>
                                        <div class="sg-meta">
                                            <span><i class="bi bi-person" aria-hidden="true"></i>Max</span>
                                            <span><i class="bi bi-calendar" aria-hidden="true"></i>2026-03-01</span>
                                            <span><i class="bi bi-activity" aria-hidden="true"></i>Carlos Martinez</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="sg-item-right">
                                    <div class="sg-stars" aria-label="Calificacion 4 de 5">
                                        <i class="bi bi-star-fill sg-star sg-star--on" aria-hidden="true"></i>
                                        <i class="bi bi-star-fill sg-star sg-star--on" aria-hidden="true"></i>
                                        <i class="bi bi-star-fill sg-star sg-star--on" aria-hidden="true"></i>
                                        <i class="bi bi-star-fill sg-star sg-star--on" aria-hidden="true"></i>
                                        <i class="bi bi-star sg-star" aria-hidden="true"></i>
                                    </div>
                                    <button class="sg-chevron" type="button" data-sg-btn aria-expanded="false" aria-label="Ver detalle">
                                        <i class="bi bi-chevron-down" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="sg-details" hidden>
                                <div class="sg-section sg-section--summary">
                                    <div class="sg-section-head">
                                        <i class="bi bi-file-earmark-text" aria-hidden="true"></i>
                                        <div class="sg-section-title">Resumen</div>
                                    </div>
                                    <div class="sg-section-body">Max presenta ansiedad leve por separacion que estamos trabajando. Su nivel de energia es alto y necesita actividad fisica regular.</div>
                                </div>
                            </div>
                        </article>

                        <article class="sg-item" data-sg-item>
                            <div class="sg-item-head" data-sg-toggle>
                                <div class="sg-item-left">
                                    <div class="sg-avatar" aria-hidden="true">L</div>
                                    <div class="sg-item-main">
                                        <div class="sg-item-title-row">
                                            <div class="sg-item-title">Sesion de Socializacion</div>
                                            <span class="sg-pill sg-pill--entreno">Entrenamiento</span>
                                        </div>
                                        <div class="sg-meta">
                                            <span><i class="bi bi-person" aria-hidden="true"></i>Luna</span>
                                            <span><i class="bi bi-calendar" aria-hidden="true"></i>2026-03-05</span>
                                            <span><i class="bi bi-activity" aria-hidden="true"></i>Maria Rodriguez</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="sg-item-right">
                                    <div class="sg-stars" aria-label="Calificacion 5 de 5">
                                        <i class="bi bi-star-fill sg-star sg-star--on" aria-hidden="true"></i>
                                        <i class="bi bi-star-fill sg-star sg-star--on" aria-hidden="true"></i>
                                        <i class="bi bi-star-fill sg-star sg-star--on" aria-hidden="true"></i>
                                        <i class="bi bi-star-fill sg-star sg-star--on" aria-hidden="true"></i>
                                        <i class="bi bi-star-fill sg-star sg-star--on" aria-hidden="true"></i>
                                    </div>
                                    <button class="sg-chevron" type="button" data-sg-btn aria-expanded="false" aria-label="Ver detalle">
                                        <i class="bi bi-chevron-down" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="sg-details" hidden>
                                <div class="sg-section sg-section--summary">
                                    <div class="sg-section-head">
                                        <i class="bi bi-file-earmark-text" aria-hidden="true"></i>
                                        <div class="sg-section-title">Resumen</div>
                                    </div>
                                    <div class="sg-section-body">Luna tuvo un excelente dia de socializacion. Interactuo muy bien con perros de diferentes tamanos y mostro un temperamento equilibrado.</div>
                                </div>
                            </div>
                        </article>

                        <article class="sg-item" data-sg-item>
                            <div class="sg-item-head" data-sg-toggle>
                                <div class="sg-item-left">
                                    <div class="sg-avatar" aria-hidden="true">R</div>
                                    <div class="sg-item-main">
                                        <div class="sg-item-title-row">
                                            <div class="sg-item-title">Reporte de Guarderia</div>
                                            <span class="sg-pill sg-pill--general">General</span>
                                        </div>
                                        <div class="sg-meta">
                                            <span><i class="bi bi-person" aria-hidden="true"></i>Rocky</span>
                                            <span><i class="bi bi-calendar" aria-hidden="true"></i>2026-02-28</span>
                                            <span><i class="bi bi-activity" aria-hidden="true"></i>Carlos Martinez</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="sg-item-right">
                                    <div class="sg-stars" aria-label="Calificacion 4 de 5">
                                        <i class="bi bi-star-fill sg-star sg-star--on" aria-hidden="true"></i>
                                        <i class="bi bi-star-fill sg-star sg-star--on" aria-hidden="true"></i>
                                        <i class="bi bi-star-fill sg-star sg-star--on" aria-hidden="true"></i>
                                        <i class="bi bi-star-fill sg-star sg-star--on" aria-hidden="true"></i>
                                        <i class="bi bi-star sg-star" aria-hidden="true"></i>
                                    </div>
                                    <button class="sg-chevron" type="button" data-sg-btn aria-expanded="false" aria-label="Ver detalle">
                                        <i class="bi bi-chevron-down" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="sg-details" hidden>
                                <div class="sg-section sg-section--summary">
                                    <div class="sg-section-head">
                                        <i class="bi bi-file-earmark-text" aria-hidden="true"></i>
                                        <div class="sg-section-title">Resumen</div>
                                    </div>
                                    <div class="sg-section-body">Rocky disfruto mucho su dia en guarderia. Mostro buen comportamiento general y se integro bien con el grupo.</div>
                                </div>
                            </div>
                        </article>
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
