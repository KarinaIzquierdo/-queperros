<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Muro de Esperanza | Más Que Perros</title>
        <link rel="icon" type="image/png" href="{{ asset('img/huellita.png') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('css/shared/mq-topbar.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dueño/planpadrino.css') }}">
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
                    <section class="pp-page">
                    <div class="pp-head">
                        <h1 class="pp-title">Plan Padrino</h1>
                        <p class="pp-sub">Adopta y patrocina el cuidado de perros en condicion de calle</p>

                        <div class="pp-banner">
                            <div class="pp-banner-icon" aria-hidden="true"><i class="bi bi-heart"></i></div>
                            <div>
                                <h2 class="pp-banner-title">Cambia una vida hoy</h2>
                                <p class="pp-banner-text">
                                    Tu aporte mensual ayuda a alimentar, cuidar y rehabilitar perros rescatados de las calles. Conviertete en padrino y recibe actualizaciones sobre el bienestar de tu ahijado.
                                </p>
                            </div>
                        </div>

                        @if (session('success'))
                            <div class="pp-spon-card" style="margin-top: 16px;">
                                <div class="pp-spon-name">{{ session('success') }}</div>
                            </div>
                        @endif

                        <div class="pp-tabs" role="tablist" aria-label="Plan Padrino">
                            <button class="pp-tab pp-tab--active" type="button" role="tab" aria-selected="true" data-pp-tab="dogs">Perros Disponibles ({{ ($dogs ?? collect())->count() }})</button>
                            <button class="pp-tab" type="button" role="tab" aria-selected="false" data-pp-tab="mine">Mis Padrinazgos ({{ ($sponsorships ?? collect())->count() }})</button>
                        </div>
                    </div>

                    <div class="pp-panels">
                        <div data-pp-panel="dogs">
                            <div class="pp-grid">
                                @forelse (($dogs ?? collect()) as $dog)
                                    @php
                                        $needs = collect(explode(',', (string) ($dog->necesidades ?? '')))->map(fn ($need) => trim($need))->filter()->values();
                                        $photo = $dog->foto ? asset('storage/' . ltrim($dog->foto, '/')) : asset('img/pet.png');
                                        $meta = trim(collect([$dog->raza, $dog->edad ? $dog->edad . ' años' : null, $dog->sexo])->filter()->implode(' • '));
                                    @endphp
                                    <article class="pp-card">
                                        <div class="pp-card-media">
                                            <img src="{{ $photo }}" alt="{{ $dog->nombre }}">
                                            <span class="pp-chip"><i class="bi bi-heart-fill" aria-hidden="true"></i>{{ $dog->estado }}</span>
                                        </div>
                                        <div class="pp-card-body">
                                            <h3 class="pp-dog-name">{{ $dog->nombre }}</h3>
                                            <div class="pp-dog-meta">{{ $meta }}</div>
                                            <p class="pp-dog-desc">{{ $dog->historia ?: 'Este perrito está esperando una persona que ayude a cubrir sus cuidados.' }}</p>
                                            @if ($needs->isNotEmpty())
                                                <div class="pp-needs">
                                                    <div class="pp-needs-label">NECESIDADES</div>
                                                    <div class="pp-needs-row">
                                                        @foreach ($needs->take(3) as $need)
                                                            <span class="pp-pill">{{ $need }}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="pp-card-actions">
                                            <button
                                                class="pp-sponsor-btn"
                                                type="button"
                                                data-pp-sponsor
                                                data-pp-name="{{ $dog->nombre }}"
                                                data-pp-meta="{{ $meta }}"
                                                data-pp-img="{{ $photo }}"
                                                data-pp-story="{{ $dog->historia }}"
                                                data-pp-action="{{ route('payment.sponsorship.create', $dog) }}"
                                            >
                                                <i class="bi bi-heart-fill"></i>
                                                Apadrinar a {{ $dog->nombre }}
                                            </button>
                                        </div>
                                    </article>
                                @empty
                                    <article class="pp-card">
                                        <div class="pp-card-body">
                                            <h3 class="pp-dog-name">No hay perros disponibles</h3>
                                            <div class="pp-dog-meta"></div>
                                            <p class="pp-dog-desc">
                                                Cuando exista un perro disponible para apadrinar, aparecerá aquí.
                                            </p>
                                        </div>
                                    </article>
                                @endforelse
                            </div>
                        </div>

                        <div data-pp-panel="mine" hidden>
                            @forelse (($sponsorships ?? collect()) as $sponsorship)
                                @php
                                    $dog = ($dogs ?? collect())->firstWhere('id', $sponsorship->sponsor_dog_id);
                                @endphp
                                <div class="pp-spon-card">
                                    <div class="pp-spon-row">
                                        <div>
                                            <div class="pp-spon-top">
                                                <h3 class="pp-spon-name">{{ $dog->nombre ?? 'Perrito apadrinado' }}</h3>
                                            </div>
                                            <div class="pp-spon-meta">
                                                <span><i class="bi bi-heart" aria-hidden="true"></i>Plan {{ ucfirst($sponsorship->plan) }}</span>
                                                <span>${{ number_format((int) $sponsorship->monto_mensual, 0, ',', '.') }}/mes</span>
                                                <span>{{ $sponsorship->estado }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="pp-spon-card">
                                    <div class="pp-spon-row">
                                        <div>
                                            <div class="pp-spon-top">
                                                <h3 class="pp-spon-name">No tienes padrinazgos</h3>
                                            </div>
                                            <div class="pp-spon-meta">
                                                <span><i class="bi bi-heart" aria-hidden="true"></i>Cuando tengas un padrinazgo, aparecerá aquí.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="pp-modal" id="ppSponsorModal" aria-hidden="true">
                        <div class="pp-modal-backdrop" data-pp-close></div>
                        <div class="pp-modal-card" role="dialog" aria-modal="true" aria-label="Apadrinar">
                            <div class="pp-modal-hero">
                                <img src="" alt="" data-pp-modal-img>
                                <button class="pp-modal-close" type="button" data-pp-close aria-label="Cerrar">
                                    <i class="bi bi-x-lg" aria-hidden="true"></i>
                                </button>
                                <div class="pp-modal-hero-text">
                                    <h3 class="pp-modal-dog" data-pp-modal-name></h3>
                                    <div class="pp-modal-meta" data-pp-modal-meta></div>
                                </div>
                            </div>

                            <div class="pp-modal-body">
                                <div class="pp-modal-h">Su historia</div>
                                <p class="pp-modal-p" data-pp-modal-story>
                                </p>

                                <div class="pp-modal-title2">Detalles del apadrinamiento</div>

                                <div class="pp-plan-list">
                                    <label class="pp-plan pp-plan--active" style="cursor: default;">
                                        <input type="radio" name="ppPlan" value="unico" checked style="display: none;">
                                        <div style="width: 100%;">
                                            <div class="pp-plan-top">
                                                <div class="pp-plan-name">Plan Único de Apadrinamiento</div>
                                                <div class="pp-plan-price">$700.000/mes</div>
                                            </div>
                                            <ul class="pp-plan-ul" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                                <li><i class="bi bi-check-lg" aria-hidden="true"></i><span>Cuidados</span></li>
                                                <li><i class="bi bi-check-lg" aria-hidden="true"></i><span>Darle la comida</span></li>
                                                <li><i class="bi bi-check-lg" aria-hidden="true"></i><span>Baño</span></li>
                                                <li><i class="bi bi-check-lg" aria-hidden="true"></i><span>Cepillado</span></li>
                                                <li><i class="bi bi-check-lg" aria-hidden="true"></i><span>Paseos ecológicos</span></li>
                                                <li><i class="bi bi-check-lg" aria-hidden="true"></i><span>Juegos lúdicos</span></li>
                                                <li><i class="bi bi-check-lg" aria-hidden="true"></i><span>Recreación</span></li>
                                            </ul>
                                        </div>
                                    </label>
                                </div>

                                <button class="pp-confirm" type="button" data-pp-confirm disabled>
                                    <i class="bi bi-heart" aria-hidden="true"></i>
                                    <span>Confirmar Apadrinamiento</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="pp-modal" id="ppThanksModal" aria-hidden="true">
                        <div class="pp-modal-backdrop" data-pp-thanks-close></div>
                        <div class="pp-toast" role="dialog" aria-modal="true" aria-label="Confirmacion">
                            <div class="pp-thanks-icon" aria-hidden="true"><i class="bi bi-heart-fill"></i></div>
                            <h3 class="pp-thanks-title">Gracias por tu generosidad</h3>
                            <div class="pp-thanks-text" data-pp-thanks-text>
                                Te enviaremos actualizaciones mensuales.
                            </div>
                        </div>
                    </div>
                </section>
                </div>
            </main>
        </div>

        <script>
            (() => {
                const tabs = Array.from(document.querySelectorAll('[data-pp-tab]'));
                const panels = Array.from(document.querySelectorAll('[data-pp-panel]'));

                const sponsorBtns = Array.from(document.querySelectorAll('[data-pp-sponsor]'));
                const sponsorModal = document.getElementById('ppSponsorModal');
                const thanksModal = document.getElementById('ppThanksModal');
                let currentAction = '';

                const modalImg = sponsorModal ? sponsorModal.querySelector('[data-pp-modal-img]') : null;
                const modalName = sponsorModal ? sponsorModal.querySelector('[data-pp-modal-name]') : null;
                const modalMeta = sponsorModal ? sponsorModal.querySelector('[data-pp-modal-meta]') : null;
                const modalStory = sponsorModal ? sponsorModal.querySelector('[data-pp-modal-story]') : null;
                const confirmBtn = sponsorModal ? sponsorModal.querySelector('[data-pp-confirm]') : null;

                const thanksText = thanksModal ? thanksModal.querySelector('[data-pp-thanks-text]') : null;

                const setActive = (key) => {
                    tabs.forEach((t) => {
                        const isActive = t.getAttribute('data-pp-tab') === key;
                        t.classList.toggle('pp-tab--active', isActive);
                        t.setAttribute('aria-selected', isActive ? 'true' : 'false');
                    });

                    panels.forEach((p) => {
                        const isActive = p.getAttribute('data-pp-panel') === key;
                        p.hidden = !isActive;
                    });
                };

                tabs.forEach((t) => {
                    t.addEventListener('click', () => setActive(t.getAttribute('data-pp-tab')));
                });

                const closeSponsor = () => {
                    if (!sponsorModal) return;
                    sponsorModal.classList.remove('pp-modal--open');
                    sponsorModal.setAttribute('aria-hidden', 'true');
                    document.body.classList.remove('pp-lock');

                    const radios = Array.from(sponsorModal.querySelectorAll('input[name="ppPlan"]'));
                    radios.forEach((r) => (r.checked = false));
                    const plans = Array.from(sponsorModal.querySelectorAll('[data-pp-plan]'));
                    plans.forEach((p) => p.classList.remove('pp-plan--active'));
                    if (confirmBtn) {
                        confirmBtn.disabled = true;
                        confirmBtn.classList.remove('pp-confirm--active');
                    }
                };

                const closeThanks = () => {
                    if (!thanksModal) return;
                    thanksModal.classList.remove('pp-modal--open');
                    thanksModal.setAttribute('aria-hidden', 'true');
                    document.body.classList.remove('pp-lock');
                };

                const openSponsor = (data) => {
                    if (!sponsorModal) return;
                    if (modalImg) {
                        modalImg.src = data.img || '';
                        modalImg.alt = data.name || '';
                    }
                    if (modalName) modalName.textContent = data.name || '';
                    if (modalMeta) modalMeta.textContent = data.meta || '';
                    if (modalStory) modalStory.textContent = data.story || '';

                    // Habilitar botón de confirmar automáticamente ya que solo hay un plan
                    if (confirmBtn) {
                        confirmBtn.disabled = false;
                        confirmBtn.classList.add('pp-confirm--active');
                    }

                    document.body.classList.add('pp-lock');
                    sponsorModal.classList.add('pp-modal--open');
                    sponsorModal.setAttribute('aria-hidden', 'false');
                };

                const openThanks = (dogName) => {
                    if (!thanksModal) return;
                    if (thanksText) {
                        thanksText.textContent = `Ahora eres padrino de ${dogName}. Te enviaremos actualizaciones mensuales.`;
                    }
                    document.body.classList.add('pp-lock');
                    thanksModal.classList.add('pp-modal--open');
                    thanksModal.setAttribute('aria-hidden', 'false');
                };

                sponsorBtns.forEach((btn) => {
                    btn.addEventListener('click', () => {
                        const data = {
                            name: btn.getAttribute('data-pp-name') || '',
                            meta: btn.getAttribute('data-pp-meta') || '',
                            img: btn.getAttribute('data-pp-img') || '',
                            story: btn.getAttribute('data-pp-story') || ''
                        };
                        currentAction = btn.getAttribute('data-pp-action') || '';
                        openSponsor(data);
                    });
                });

                if (sponsorModal) {
                    sponsorModal.addEventListener('click', (e) => {
                        const closeEl = e.target.closest('[data-pp-close]');
                        if (closeEl) closeSponsor();
                    });

                    const plans = Array.from(sponsorModal.querySelectorAll('[data-pp-plan]'));
                    plans.forEach((p) => {
                        p.addEventListener('click', () => {
                            plans.forEach((x) => x.classList.remove('pp-plan--active'));
                            p.classList.add('pp-plan--active');
                            const radio = p.querySelector('input[type="radio"]');
                            if (radio) radio.checked = true;
                            if (confirmBtn) {
                                confirmBtn.disabled = false;
                                confirmBtn.classList.add('pp-confirm--active');
                            }
                        });
                    });

                    if (confirmBtn) {
                        confirmBtn.addEventListener('click', () => {
                            const selected = sponsorModal.querySelector('input[name="ppPlan"]:checked');
                            if (!currentAction || !selected) return;

                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = currentAction;
                            form.innerHTML = `
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="plan" value="${selected.value}">
                            `;
                            document.body.appendChild(form);
                            form.submit();
                        });
                    }
                }

                if (thanksModal) {
                    thanksModal.addEventListener('click', (e) => {
                        const closeEl = e.target.closest('[data-pp-thanks-close]');
                        if (closeEl) closeThanks();
                    });
                }

                document.addEventListener('keydown', (e) => {
                    if (e.key !== 'Escape') return;
                    closeSponsor();
                    closeThanks();
                });
            })();
        </script>
    </body>
</html>
