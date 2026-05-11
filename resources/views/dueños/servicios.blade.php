<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Nuestros Servicios</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('css/shared/mq-topbar.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dueño/servicios.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dueño/panel.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="{{ asset('css/Admin/admin-sidebar-extras.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    </head>

    <body class="mq-dashboard-page">
        @include('partials.page-loader')
        @php
            use Illuminate\Support\Str;

            $catMeta = function (string $cat): array {
                $c = mb_strtolower(trim($cat));

                if (str_contains($c, 'entren')) {
                    return ['key' => 'purple', 'label' => 'ENTRENAMIENTO CANINO', 'icon' => 'bi-mortarboard'];
                }

                if (str_contains($c, 'cuidado') || str_contains($c, 'aloj')) {
                    return ['key' => 'blue', 'label' => 'CUIDADO Y ALOJAMIENTO', 'icon' => 'bi-house-door'];
                }

                if (str_contains($c, 'otra') || str_contains($c, 'activ')) {
                    return ['key' => 'yellow', 'label' => 'OTRAS ACTIVIDADES', 'icon' => 'bi-stars'];
                }

                return ['key' => 'slate', 'label' => Str::upper($cat), 'icon' => 'bi-shield'];
            };

            $formatPrice = function ($value, string $name): string {
                if ($value === null || $value === '' || (string) $value === '0') {
                    return 'Consultar';
                }

                $num = (float) $value;
                $formatted = '$ ' . number_format($num, 0, ',', '.');

                $lower = mb_strtolower($name);
                if (str_contains($lower, 'hotel')) {
                    return $formatted . '/noche';
                }

                if (str_contains($lower, 'guarder')) {
                    return $formatted . '/dia';
                }

                return $formatted;
            };
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
                    <section class="sv-page">
                    <div class="sv-head">
                        <h1 class="sv-title">Nuestros Servicios</h1>
                        <p class="sv-sub">Tu perro feliz, tu tranquilo. Conoce todo lo que ofrecemos para el bienestar de tu mascota.</p>
                    </div>

                    <div class="sv-toolbar">
                        <form method="GET" action="{{ route('owner.services') }}" class="sv-search">
                            <i class="bi bi-search" aria-hidden="true"></i>
                            <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Buscar servicios..." />
                            @if (($activeCategory ?? '') !== '')
                                <input type="hidden" name="categoria" value="{{ $activeCategory }}" />
                            @endif
                        </form>

                        <div class="sv-filters" role="tablist" aria-label="Filtro de categoria">
                            @php
                                $activeCat = (string) ($activeCategory ?? '');
                                $activeCat = $activeCat === '' ? 'all' : $activeCat;
                            @endphp

                            <a href="{{ route('owner.services', ['q' => $search]) }}" class="sv-pill {{ $activeCat === 'all' ? 'sv-pill--active' : '' }}">Todos</a>

                            @foreach ($categoryOptions as $cat)
                                <a href="{{ route('owner.services', ['categoria' => $cat->id, 'q' => $search]) }}" class="sv-pill {{ (string) $activeCat === (string) $cat->id ? 'sv-pill--active' : '' }}">{{ $cat->nombre }}</a>
                            @endforeach
                        </div>
                    </div>

                    <div class="sv-grid">
                        @foreach ($services as $service)
                            @php
                                $meta = $catMeta((string) ($service['category'] ?? ''));
                                $priceText = $formatPrice($service['price'] ?? null, (string) ($service['name'] ?? ''));
                                $isConsult = mb_strtolower($priceText) === 'consultar' || str_contains(mb_strtolower($priceText), 'consultar');
                            @endphp

                            <article class="sv-card sv-card--{{ $meta['key'] }}" data-service-id="{{ $service['id'] }}" data-service-name="{{ $service['name'] }}" data-service-price="{{ $priceText }}" data-service-category="{{ $meta['label'] }}" data-service-icon="{{ $meta['icon'] }}">
                                <header class="sv-card-head">
                                    <div class="sv-card-icon"><i class="bi {{ $meta['icon'] }}" aria-hidden="true"></i></div>
                                    <div class="sv-card-head-text">
                                        <div class="sv-card-cat">{{ $meta['label'] }}</div>
                                        <h3 class="sv-card-title">{{ $service['name'] }}</h3>
                                        <div class="sv-card-meta">
                                            <span class="sv-price {{ $isConsult ? 'sv-price--consult' : '' }}">{{ $priceText }}</span>
                                            @if (!empty($service['duration']))
                                                <span class="sv-meta-dot">•</span>
                                                <span class="sv-meta-small">{{ $service['duration'] }}</span>
                                            @elseif (str_contains(mb_strtolower($meta['label']), 'entren'))
                                                <span class="sv-meta-dot">•</span>
                                                <span class="sv-meta-small">Programa completo</span>
                                            @endif
                                        </div>
                                    </div>
                                </header>

                                <div class="sv-card-body">
                                    <p class="sv-desc">{{ $service['description'] }}</p>

                                    <div class="sv-expand" hidden>
                                        <div class="sv-expand-desc">Servicio disenado para todos los perros de todas las edades y razas. Incluye obediencia, autocontrol, socializacion y modificacion de comportamientos.</div>

                                        <div class="sv-expand-block">
                                            <div class="sv-expand-h">
                                                <span class="sv-expand-check"><i class="bi bi-check-lg" aria-hidden="true"></i></span>
                                                <span>Incluye:</span>
                                            </div>
                                            <ul class="sv-expand-ul">
                                                <li>Trabajo en obediencia y autocontrol</li>
                                                <li>Socializacion intra e inter especifica</li>
                                                <li>Manejo de ansiedad</li>
                                                <li>Modificacion de comportamientos no adecuados</li>
                                                <li>Deportes caninos: OCI y DISC DOG</li>
                                            </ul>
                                        </div>

                                        <div class="sv-expand-block">
                                            <div class="sv-expand-h sv-expand-h--star">
                                                <span class="sv-expand-star"><i class="bi bi-star" aria-hidden="true"></i></span>
                                                <span>Ideal para:</span>
                                            </div>
                                            <ul class="sv-expand-ul sv-expand-ul--star">
                                                <li>Todos los perros</li>
                                                <li>Familias que buscan mejorar la convivencia</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <footer class="sv-card-foot">
                                    <button class="sv-more" type="button" aria-expanded="false">Ver mas <i class="bi bi-chevron-down" aria-hidden="true"></i></button>
                                    <button class="sv-cta" type="button">Solicitar</button>
                                </footer>
                            </article>
                        @endforeach
                    </div>

                    <section class="sv-helpbar" aria-label="Necesitas mas informacion">
                        <div class="sv-helpbar-title">Necesitas mas informacion?</div>
                        <div class="sv-helpbar-items">
                            <div class="sv-helpbar-item"><i class="bi bi-telephone" aria-hidden="true"></i><span>+57 300 123 4567</span></div>
                            <div class="sv-helpbar-item"><i class="bi bi-geo-alt" aria-hidden="true"></i><span>La Calera, Colombia</span></div>
                            <div class="sv-helpbar-item"><i class="bi bi-clock" aria-hidden="true"></i><span>Lun - Sab: 8am - 6pm</span></div>
                        </div>
                    </section>
                </section>
                </div>
            </main>
        </div>

        <div class="sv-modal" id="svModal" aria-hidden="true">
            <div class="sv-modal-backdrop" data-sv-close></div>
            <div class="sv-modal-card" role="dialog" aria-modal="true" aria-labelledby="svModalTitle">
                <div class="sv-modal-head">
                    <div>
                        <div class="sv-modal-title" id="svModalTitle">Solicitar Servicio</div>
                        <div class="sv-modal-sub" id="svModalServiceName">Servicio</div>
                    </div>
                    <button class="sv-modal-close" type="button" aria-label="Cerrar" data-sv-close>
                        <i class="bi bi-x-lg" aria-hidden="true"></i>
                    </button>
                </div>

                <form class="sv-form" id="svRequestForm" action="{{ route('owner.reservas.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="servicio_id" id="svFormServiceId" value="" />
                    <input type="hidden" name="precio_estimado" id="svFormPrecioEstimado" value="" />

                    <label class="sv-field">
                        <span class="sv-label">Mascota</span>
                        <select name="mascota_id" class="sv-select" required>
                            <option value="" selected disabled>Selecciona una mascota</option>
                            @foreach (($pets ?? []) as $pet)
                                <option value="{{ $pet->id }}">{{ $pet->nombre }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="sv-field">
                        <span class="sv-label">Entrenador</span>
                        <select name="profesional_id" class="sv-select" required>
                            <option value="" selected disabled>Selecciona un entrenador</option>
                            @foreach (($trainers ?? []) as $trainer)
                                <option value="{{ $trainer->id }}">{{ $trainer->name }}</option>
                            @endforeach
                        </select>
                    </label>

                    <div class="sv-row">
                        <label class="sv-field">
                            <span class="sv-label">Fecha preferida</span>
                            <input class="sv-input" type="date" name="fecha" required />
                        </label>
                        <label class="sv-field">
                            <span class="sv-label">Hora preferida</span>
                            <input class="sv-input" type="time" name="hora" required />
                        </label>
                    </div>

                    <label class="sv-field">
                        <span class="sv-label">Comentarios adicionales</span>
                        <textarea class="sv-textarea" name="comentarios" rows="4" placeholder="Cuentanos mas sobre lo que necesitas..."></textarea>
                    </label>

                    <div class="sv-est">
                        <span class="sv-est-left">Precio estimado:</span>
                        <span class="sv-est-right" id="svModalPrice">-</span>
                    </div>

                    <button class="sv-submit" type="submit">Enviar Solicitud</button>
                </form>
            </div>
        </div>

        <script>
            (function () {
                const modal = document.getElementById('svModal');
                const modalServiceName = document.getElementById('svModalServiceName');
                const modalPrice = document.getElementById('svModalPrice');
                const formServiceId = document.getElementById('svFormServiceId');
                const formPrecioEstimado = document.getElementById('svFormPrecioEstimado');
                const form = document.getElementById('svRequestForm');

                const openModal = (card) => {
                    formServiceId.value = card.dataset.serviceId || '';
                    formPrecioEstimado.value = card.dataset.servicePrice || '';
                    modalServiceName.textContent = card.dataset.serviceName || 'Servicio';
                    modalPrice.textContent = card.dataset.servicePrice || '-';
                    modal.classList.add('is-open');
                    modal.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';
                };

                const closeModal = () => {
                    modal.classList.remove('is-open');
                    modal.setAttribute('aria-hidden', 'true');
                    document.body.style.overflow = '';
                };

                document.addEventListener('click', (e) => {
                    const moreBtn = e.target.closest('.sv-more');
                    if (moreBtn) {
                        const card = moreBtn.closest('.sv-card');
                        const expand = card ? card.querySelector('.sv-expand') : null;
                        if (!card || !expand) return;

                        const closeCard = (c) => {
                            c.classList.remove('sv-card--open');
                            const ex = c.querySelector('.sv-expand');
                            if (ex) ex.hidden = true;
                            const btn = c.querySelector('.sv-more');
                            if (btn) {
                                btn.setAttribute('aria-expanded', 'false');
                                btn.innerHTML = 'Ver mas <i class="bi bi-chevron-down" aria-hidden="true"></i>';
                            }
                        };

                        document.querySelectorAll('.sv-card.sv-card--open').forEach((openCard) => {
                            if (openCard !== card) {
                                closeCard(openCard);
                            }
                        });

                        const isOpen = card.classList.toggle('sv-card--open');
                        expand.hidden = !isOpen;
                        moreBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                        moreBtn.innerHTML = isOpen
                            ? 'Ver menos <i class="bi bi-chevron-up" aria-hidden="true"></i>'
                            : 'Ver mas <i class="bi bi-chevron-down" aria-hidden="true"></i>';
                        return;
                    }

                    const ctaBtn = e.target.closest('.sv-cta');
                    if (ctaBtn) {
                        const card = ctaBtn.closest('.sv-card');
                        if (!card) return;
                        openModal(card);
                        return;
                    }

                    if (e.target.closest('[data-sv-close]')) {
                        closeModal();
                    }
                });

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && modal.classList.contains('is-open')) {
                        closeModal();
                    }
                });

            })();
        </script>
    </body>
</html>
