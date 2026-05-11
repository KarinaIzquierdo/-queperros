<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Plan Padrino</title>

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
                    'notifCount' => 2,
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

                        <div class="pp-tabs" role="tablist" aria-label="Plan Padrino">
                            <button class="pp-tab pp-tab--active" type="button" role="tab" aria-selected="true" data-pp-tab="dogs">Perros Disponibles (2)</button>
                            <button class="pp-tab" type="button" role="tab" aria-selected="false" data-pp-tab="mine">Mis Padrinazgos (1)</button>
                        </div>
                    </div>

                    <div class="pp-panels">
                        <div data-pp-panel="dogs">
                            <div class="pp-grid">
                                <article class="pp-card">
                                    <div class="pp-card-media">
                                        <img src="https://images.unsplash.com/photo-1525253086316-d0c936c814f8?auto=format&fit=crop&w=1000&q=80" alt="Canela">
                                    </div>
                                    <div class="pp-card-body">
                                        <h3 class="pp-dog-name">Canela</h3>
                                        <div class="pp-dog-meta">Mestizo - 4 anos</div>
                                        <p class="pp-dog-desc">
                                            Canela fue rescatada de las calles hace 2 anos. Es una perrita muy carinosa que busca un padrino que le ayude con su alimentacion y cuidados medicos.
                                        </p>
                                        <div class="pp-needs">
                                            <div class="pp-needs-label">Necesidades:</div>
                                            <div class="pp-needs-row">
                                                <span class="pp-pill">Alimentacion mensual</span>
                                                <span class="pp-pill">Vacunas anuales</span>
                                                <span class="pp-pill pp-pill--more">+1 mas</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pp-card-actions">
                                        <button class="pp-btn pp-btn--primary" type="button" data-pp-sponsor data-pp-name="Canela" data-pp-meta="Mestizo - 4 anos" data-pp-img="https://images.unsplash.com/photo-1525253086316-d0c936c814f8?auto=format&fit=crop&w=1200&q=80">
                                            <i class="bi bi-heart" aria-hidden="true"></i>
                                            <span>Quiero ser padrino</span>
                                        </button>
                                    </div>
                                </article>

                                <article class="pp-card">
                                    <div class="pp-card-media">
                                        <img src="https://images.unsplash.com/photo-1530281700549-e82e7bf110d6?auto=format&fit=crop&w=1000&q=80" alt="Toby">
                                    </div>
                                    <div class="pp-card-body">
                                        <h3 class="pp-dog-name">Toby</h3>
                                        <div class="pp-dog-meta">Labrador Mestizo - 6 anos</div>
                                        <p class="pp-dog-desc">
                                            Toby llego al refugio despues de ser abandonado. Es un perro muy tranquilo y obediente que necesita apoyo para sus tratamientos medicos.
                                        </p>
                                        <div class="pp-needs">
                                            <div class="pp-needs-label">Necesidades:</div>
                                            <div class="pp-needs-row">
                                                <span class="pp-pill">Tratamiento de artritis</span>
                                                <span class="pp-pill">Alimentacion especial</span>
                                                <span class="pp-pill pp-pill--more">+1 mas</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pp-card-actions">
                                        <button class="pp-btn pp-btn--primary" type="button" data-pp-sponsor data-pp-name="Toby" data-pp-meta="Labrador Mestizo - 6 anos" data-pp-img="https://images.unsplash.com/photo-1587300003388-59208cc962cb?auto=format&fit=crop&w=1200&q=80" data-pp-story="Toby llego al refugio despues de ser abandonado. Es un perro muy tranquilo y obediente que necesita apoyo para sus tratamientos medicos.">
                                            <i class="bi bi-heart" aria-hidden="true"></i>
                                            <span>Quiero ser padrino</span>
                                        </button>
                                    </div>
                                </article>

                                <article class="pp-card">
                                    <div class="pp-card-media" style="opacity: 0.6;">
                                        <img src="https://images.unsplash.com/photo-1518020382113-a7e8fc38eac9?auto=format&fit=crop&w=1000&q=80" alt="Nina">
                                        <span class="pp-chip"><i class="bi bi-heart-fill" aria-hidden="true"></i>Apadrinado</span>
                                    </div>
                                    <div class="pp-card-body">
                                        <h3 class="pp-dog-name">Nina</h3>
                                        <div class="pp-dog-meta">Pitbull - 2 anos</div>
                                        <p class="pp-dog-desc">
                                            Nina fue victima de maltrato animal. Ahora esta en rehabilitacion y necesita mucho amor y cuidados especiales.
                                        </p>
                                        <div class="pp-needs">
                                            <div class="pp-needs-label">Necesidades:</div>
                                            <div class="pp-needs-row">
                                                <span class="pp-pill">Terapia de comportamiento</span>
                                                <span class="pp-pill">Alimentacion premium</span>
                                                <span class="pp-pill pp-pill--more">+1 mas</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pp-card-actions">
                                        <button class="pp-btn pp-btn--ghost" type="button" disabled>
                                            <i class="bi bi-heart" aria-hidden="true"></i>
                                            <span>Ya tiene padrino</span>
                                        </button>
                                    </div>
                                </article>
                            </div>
                        </div>

                        <div data-pp-panel="mine" hidden>
                            <div class="pp-spon-card">
                                <div class="pp-spon-row">
                                    <div class="pp-spon-avatar">
                                        <img src="https://images.unsplash.com/photo-1548199973-03cce0bbc87b?auto=format&fit=crop&w=600&q=80" alt="Coco">
                                    </div>
                                    <div>
                                        <div class="pp-spon-top">
                                            <h3 class="pp-spon-name">Coco</h3>
                                            <span class="pp-status">Activo</span>
                                        </div>
                                        <div class="pp-spon-meta">
                                            <span><i class="bi bi-calendar3" aria-hidden="true"></i> Padrino desde: 2025-06-15</span>
                                            <span><i class="bi bi-gift" aria-hidden="true"></i> Aporte: $50.000</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="pp-report">
                                    Ultimo reporte: Coco esta muy feliz y saludable. Ha ganado 2kg y su pelaje brilla.
                                </div>
                            </div>
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
                                    <h3 class="pp-modal-dog" data-pp-modal-name>Toby</h3>
                                    <div class="pp-modal-meta" data-pp-modal-meta>Labrador Mestizo - 6 anos</div>
                                </div>
                            </div>

                            <div class="pp-modal-body">
                                <div class="pp-modal-h">Su historia</div>
                                <p class="pp-modal-p" data-pp-modal-story>
                                    Toby llego al refugio despues de ser abandonado. Es un perro muy tranquilo y obediente que necesita apoyo para sus tratamientos medicos.
                                </p>

                                <div class="pp-modal-title2">Elige tu plan de apadrinamiento</div>

                                <div class="pp-plan-list" role="radiogroup" aria-label="Planes">
                                    <label class="pp-plan" data-pp-plan>
                                        <input type="radio" name="ppPlan" value="basico">
                                        <div>
                                            <div class="pp-plan-top">
                                                <div class="pp-plan-name">Basico</div>
                                                <div class="pp-plan-price">$30.000/mes</div>
                                            </div>
                                            <ul class="pp-plan-ul">
                                                <li><i class="bi bi-check-lg" aria-hidden="true"></i><span>Alimentacion mensual</span></li>
                                                <li><i class="bi bi-check-lg" aria-hidden="true"></i><span>Foto mensual del perro</span></li>
                                                <li><i class="bi bi-check-lg" aria-hidden="true"></i><span>Reporte trimestral</span></li>
                                            </ul>
                                        </div>
                                    </label>

                                    <label class="pp-plan" data-pp-plan>
                                        <input type="radio" name="ppPlan" value="cuidador">
                                        <div>
                                            <div class="pp-plan-top">
                                                <div class="pp-plan-name">Cuidador</div>
                                                <div class="pp-plan-price">$50.000/mes</div>
                                            </div>
                                            <ul class="pp-plan-ul">
                                                <li><i class="bi bi-check-lg" aria-hidden="true"></i><span>Todo lo del plan Basico</span></li>
                                                <li><i class="bi bi-check-lg" aria-hidden="true"></i><span>Vacunas incluidas</span></li>
                                                <li><i class="bi bi-check-lg" aria-hidden="true"></i><span>Visitas mensuales permitidas</span></li>
                                                <li><i class="bi bi-check-lg" aria-hidden="true"></i><span>Reporte mensual con fotos</span></li>
                                            </ul>
                                        </div>
                                    </label>

                                    <label class="pp-plan" data-pp-plan>
                                        <input type="radio" name="ppPlan" value="protector">
                                        <div>
                                            <div class="pp-plan-top">
                                                <div class="pp-plan-name">Protector</div>
                                                <div class="pp-plan-price">$100.000/mes</div>
                                            </div>
                                            <ul class="pp-plan-ul">
                                                <li><i class="bi bi-check-lg" aria-hidden="true"></i><span>Todo lo del plan Cuidador</span></li>
                                                <li><i class="bi bi-check-lg" aria-hidden="true"></i><span>Cuidados medicos completos</span></li>
                                                <li><i class="bi bi-check-lg" aria-hidden="true"></i><span>Video mensual</span></li>
                                                <li><i class="bi bi-check-lg" aria-hidden="true"></i><span>Nombre del padrino en placa</span></li>
                                                <li><i class="bi bi-check-lg" aria-hidden="true"></i><span>Prioridad en adopcion</span></li>
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
                                Ahora eres padrino de Toby. Te enviaremos actualizaciones mensuales.
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
                            story: btn.getAttribute('data-pp-story') || 'Toby llego al refugio despues de ser abandonado. Es un perro muy tranquilo y obediente que necesita apoyo para sus tratamientos medicos.'
                        };
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
                            const dogName = modalName ? modalName.textContent.trim() : '';
                            closeSponsor();
                            openThanks(dogName);
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
