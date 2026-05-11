<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Pagos</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('css/shared/mq-topbar.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dueño/pagos.css') }}">
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
                    <section class="pg-page">
                    <div class="pg-head">
                        <h1 class="pg-title">Pagos</h1>
                        <p class="pg-sub">Gestiona tus facturas y pagos de servicios</p>

                        <div class="pg-stats">
                            <div class="pg-stat pg-stat--yellow">
                                <div class="pg-stat-label">Pendiente por Pagar</div>
                                <div class="pg-stat-value">$450,000</div>
                                <div class="pg-stat-sub">2 facturas pendientes</div>
                                <div class="pg-stat-icon" aria-hidden="true"><i class="bi bi-clock"></i></div>
                            </div>

                            <div class="pg-stat pg-stat--green">
                                <div class="pg-stat-label">Total Pagado (2026)</div>
                                <div class="pg-stat-value">$330,000</div>
                                <div class="pg-stat-sub">3 facturas pagadas</div>
                                <div class="pg-stat-icon" aria-hidden="true"><i class="bi bi-check-lg"></i></div>
                            </div>

                            <div class="pg-stat pg-stat--blue">
                                <div class="pg-stat-label">Ultimo Pago</div>
                                <div class="pg-stat-value">$150.000</div>
                                <div class="pg-stat-sub">2026-02-20</div>
                                <div class="pg-stat-icon" aria-hidden="true"><i class="bi bi-receipt"></i></div>
                            </div>
                        </div>

                        <div class="pg-tabs" role="tablist" aria-label="Pagos">
                            <button class="pg-tab pg-tab--active" type="button" role="tab" aria-selected="true">Facturas</button>
                            <button class="pg-tab" type="button" role="tab" aria-selected="false">Pagos Realizados</button>
                        </div>
                    </div>

                    <div class="pg-controls">
                        <div class="pg-filter">
                            <i class="bi bi-funnel" aria-hidden="true"></i>
                            <select aria-label="Filtrar facturas">
                                <option value="">Todas las facturas</option>
                                <option value="pendiente">Pendientes</option>
                                <option value="pagada">Pagadas</option>
                            </select>
                        </div>
                    </div>

                    <div class="pg-list" aria-label="Lista de facturas">
                        <article class="pg-item" data-pg-item>
                            <div class="pg-item-left" data-pg-toggle>
                                <div class="pg-doc" aria-hidden="true"><i class="bi bi-file-earmark-text"></i></div>
                                <div class="pg-main">
                                    <div class="pg-row-top">
                                        <div class="pg-code" data-pg-code>FAC-2026-001</div>
                                        <span class="pg-pill pg-pill--pending">Pendiente</span>
                                    </div>
                                    <div class="pg-desc">Entrenamiento Basico - Marzo - Max</div>
                                </div>
                            </div>
                            <div class="pg-item-right">
                                <div class="pg-price">
                                    <div class="pg-price-amount" data-pg-amount="$150.000">$150.000</div>
                                    <div class="pg-price-sub">Vence: 2026-03-15</div>
                                </div>
                                <button class="pg-chevron pg-chevron-btn" type="button" data-pg-btn aria-expanded="false" aria-label="Ver detalle">
                                    <i class="bi bi-chevron-down" aria-hidden="true"></i>
                                </button>
                            </div>

                            <div class="pg-details" hidden>
                                <div class="pg-detail-cards">
                                    <div class="pg-detail-card">
                                        <div class="pg-detail-label">Fecha Emision</div>
                                        <div class="pg-detail-value">2026-03-01</div>
                                    </div>
                                    <div class="pg-detail-card">
                                        <div class="pg-detail-label">Vencimiento</div>
                                        <div class="pg-detail-value">2026-03-15</div>
                                    </div>
                                </div>
                                <div class="pg-actions">
                                    <a class="pg-action" href="#">
                                        <i class="bi bi-eye" aria-hidden="true"></i>
                                        <span>Ver Factura</span>
                                    </a>
                                    <a class="pg-action" href="#">
                                        <i class="bi bi-download" aria-hidden="true"></i>
                                        <span>Descargar PDF</span>
                                    </a>
                                    <button class="pg-action pg-action--pay" type="button" data-pg-pay>
                                        <i class="bi bi-credit-card" aria-hidden="true"></i>
                                        <span>Pagar Ahora</span>
                                    </button>
                                </div>
                            </div>
                        </article>

                        <article class="pg-item" data-pg-item>
                            <div class="pg-item-left" data-pg-toggle>
                                <div class="pg-doc" aria-hidden="true"><i class="bi bi-file-earmark-text"></i></div>
                                <div class="pg-main">
                                    <div class="pg-row-top">
                                        <div class="pg-code" data-pg-code>FAC-2026-002</div>
                                        <span class="pg-pill pg-pill--pending">Pendiente</span>
                                    </div>
                                    <div class="pg-desc">Hotel Canino (5 noches) - Luna</div>
                                </div>
                            </div>
                            <div class="pg-item-right">
                                <div class="pg-price">
                                    <div class="pg-price-amount" data-pg-amount="$300.000">$300.000</div>
                                    <div class="pg-price-sub">Vence: 2026-03-25</div>
                                </div>
                                <button class="pg-chevron pg-chevron-btn" type="button" data-pg-btn aria-expanded="false" aria-label="Ver detalle">
                                    <i class="bi bi-chevron-down" aria-hidden="true"></i>
                                </button>
                            </div>

                            <div class="pg-details" hidden>
                                <div class="pg-detail-cards">
                                    <div class="pg-detail-card">
                                        <div class="pg-detail-label">Fecha Emision</div>
                                        <div class="pg-detail-value">2026-03-10</div>
                                    </div>
                                    <div class="pg-detail-card">
                                        <div class="pg-detail-label">Vencimiento</div>
                                        <div class="pg-detail-value">2026-03-25</div>
                                    </div>
                                </div>
                                <div class="pg-actions">
                                    <a class="pg-action" href="#">
                                        <i class="bi bi-eye" aria-hidden="true"></i>
                                        <span>Ver Factura</span>
                                    </a>
                                    <a class="pg-action" href="#">
                                        <i class="bi bi-download" aria-hidden="true"></i>
                                        <span>Descargar PDF</span>
                                    </a>
                                    <button class="pg-action pg-action--pay" type="button" data-pg-pay>
                                        <i class="bi bi-credit-card" aria-hidden="true"></i>
                                        <span>Pagar Ahora</span>
                                    </button>
                                </div>
                            </div>
                        </article>

                        <article class="pg-item" data-pg-item>
                            <div class="pg-item-left" data-pg-toggle>
                                <div class="pg-doc" aria-hidden="true"><i class="bi bi-file-earmark-text"></i></div>
                                <div class="pg-main">
                                    <div class="pg-row-top">
                                        <div class="pg-code" data-pg-code>FAC-2026-003</div>
                                        <span class="pg-pill pg-pill--paid">Pagada</span>
                                    </div>
                                    <div class="pg-desc">Entrenamiento Basico - Febrero - Max</div>
                                </div>
                            </div>
                            <div class="pg-item-right">
                                <div class="pg-price">
                                    <div class="pg-price-amount" data-pg-amount="$150.000">$150.000</div>
                                    <div class="pg-price-sub">Vence: 2026-02-28</div>
                                </div>
                                <button class="pg-chevron pg-chevron-btn" type="button" data-pg-btn aria-expanded="false" aria-label="Ver detalle">
                                    <i class="bi bi-chevron-down" aria-hidden="true"></i>
                                </button>
                            </div>

                            <div class="pg-details" hidden>
                                <div class="pg-detail-cards">
                                    <div class="pg-detail-card">
                                        <div class="pg-detail-label">Fecha Emision</div>
                                        <div class="pg-detail-value">2026-02-10</div>
                                    </div>
                                    <div class="pg-detail-card">
                                        <div class="pg-detail-label">Vencimiento</div>
                                        <div class="pg-detail-value">2026-02-28</div>
                                    </div>
                                </div>
                                <div class="pg-actions">
                                    <a class="pg-action" href="#">
                                        <i class="bi bi-eye" aria-hidden="true"></i>
                                        <span>Ver Factura</span>
                                    </a>
                                    <a class="pg-action" href="#">
                                        <i class="bi bi-download" aria-hidden="true"></i>
                                        <span>Descargar PDF</span>
                                    </a>
                                    <button class="pg-action pg-action--pay" type="button" data-pg-pay>
                                        <i class="bi bi-credit-card" aria-hidden="true"></i>
                                        <span>Pagar Ahora</span>
                                    </button>
                                </div>
                            </div>
                        </article>

                        <article class="pg-item" data-pg-item>
                            <div class="pg-item-left" data-pg-toggle>
                                <div class="pg-doc" aria-hidden="true"><i class="bi bi-file-earmark-text"></i></div>
                                <div class="pg-main">
                                    <div class="pg-row-top">
                                        <div class="pg-code" data-pg-code>FAC-2026-004</div>
                                        <span class="pg-pill pg-pill--paid">Pagada</span>
                                    </div>
                                    <div class="pg-desc">Guarderia (2 dias) - Rocky</div>
                                </div>
                            </div>
                            <div class="pg-item-right">
                                <div class="pg-price">
                                    <div class="pg-price-amount" data-pg-amount="$100.000">$100.000</div>
                                    <div class="pg-price-sub">Vence: 2026-02-15</div>
                                </div>
                                <button class="pg-chevron pg-chevron-btn" type="button" data-pg-btn aria-expanded="false" aria-label="Ver detalle">
                                    <i class="bi bi-chevron-down" aria-hidden="true"></i>
                                </button>
                            </div>

                            <div class="pg-details" hidden>
                                <div class="pg-detail-cards">
                                    <div class="pg-detail-card">
                                        <div class="pg-detail-label">Fecha Emision</div>
                                        <div class="pg-detail-value">2026-02-01</div>
                                    </div>
                                    <div class="pg-detail-card">
                                        <div class="pg-detail-label">Vencimiento</div>
                                        <div class="pg-detail-value">2026-02-15</div>
                                    </div>
                                </div>
                                <div class="pg-actions">
                                    <a class="pg-action" href="#">
                                        <i class="bi bi-eye" aria-hidden="true"></i>
                                        <span>Ver Factura</span>
                                    </a>
                                    <a class="pg-action" href="#">
                                        <i class="bi bi-download" aria-hidden="true"></i>
                                        <span>Descargar PDF</span>
                                    </a>
                                    <button class="pg-action pg-action--pay" type="button" data-pg-pay>
                                        <i class="bi bi-credit-card" aria-hidden="true"></i>
                                        <span>Pagar Ahora</span>
                                    </button>
                                </div>
                            </div>
                        </article>

                        <article class="pg-item" data-pg-item>
                            <div class="pg-item-left" data-pg-toggle>
                                <div class="pg-doc" aria-hidden="true"><i class="bi bi-file-earmark-text"></i></div>
                                <div class="pg-main">
                                    <div class="pg-row-top">
                                        <div class="pg-code" data-pg-code>FAC-2026-005</div>
                                        <span class="pg-pill pg-pill--paid">Pagada</span>
                                    </div>
                                    <div class="pg-desc">Dia de Diversion - Max</div>
                                </div>
                            </div>
                            <div class="pg-item-right">
                                <div class="pg-price">
                                    <div class="pg-price-amount" data-pg-amount="$80.000">$80.000</div>
                                    <div class="pg-price-sub">Vence: 2026-01-30</div>
                                </div>
                                <button class="pg-chevron pg-chevron-btn" type="button" data-pg-btn aria-expanded="false" aria-label="Ver detalle">
                                    <i class="bi bi-chevron-down" aria-hidden="true"></i>
                                </button>
                            </div>

                            <div class="pg-details" hidden>
                                <div class="pg-detail-cards">
                                    <div class="pg-detail-card">
                                        <div class="pg-detail-label">Fecha Emision</div>
                                        <div class="pg-detail-value">2026-01-20</div>
                                    </div>
                                    <div class="pg-detail-card">
                                        <div class="pg-detail-label">Vencimiento</div>
                                        <div class="pg-detail-value">2026-01-30</div>
                                    </div>
                                </div>
                                <div class="pg-actions">
                                    <a class="pg-action" href="#">
                                        <i class="bi bi-eye" aria-hidden="true"></i>
                                        <span>Ver Factura</span>
                                    </a>
                                    <a class="pg-action" href="#">
                                        <i class="bi bi-download" aria-hidden="true"></i>
                                        <span>Descargar PDF</span>
                                    </a>
                                    <button class="pg-action pg-action--pay" type="button" data-pg-pay>
                                        <i class="bi bi-credit-card" aria-hidden="true"></i>
                                        <span>Pagar Ahora</span>
                                    </button>
                                </div>
                            </div>
                        </article>
                    </div>

                    <div class="pg-modal" id="pgPayModal" aria-hidden="true">
                        <div class="pg-modal-backdrop" data-pg-modal-close></div>
                        <div class="pg-modal-card" role="dialog" aria-modal="true" aria-label="Realizar Pago">
                            <div class="pg-modal-head">
                                <h2 class="pg-modal-title">Realizar Pago</h2>
                                <div class="pg-modal-sub" data-pg-modal-code>FAC-2026-001</div>
                            </div>
                            <div class="pg-modal-divider" aria-hidden="true"></div>
                            <div class="pg-modal-body">
                                <div class="pg-total">
                                    <div class="pg-total-label">Total a Pagar</div>
                                    <div class="pg-total-value" data-pg-modal-total>$150.000</div>
                                </div>

                                <div class="pg-modal-section">Metodo de Pago</div>

                                <div class="pg-pay-methods">
                                    <label class="pg-radio">
                                        <input type="radio" name="pgMethod" value="credito">
                                        <span>Tarjeta de credito</span>
                                    </label>
                                    <label class="pg-radio">
                                        <input type="radio" name="pgMethod" value="debito">
                                        <span>Tarjeta debito</span>
                                    </label>
                                    <label class="pg-radio">
                                        <input type="radio" name="pgMethod" value="pse">
                                        <span>PSE</span>
                                    </label>
                                    <label class="pg-radio">
                                        <input type="radio" name="pgMethod" value="efectivo">
                                        <span>Efectivo</span>
                                    </label>
                                </div>

                                <div class="pg-modal-actions">
                                    <button class="pg-btn" type="button" data-pg-modal-close>Cancelar</button>
                                    <button class="pg-btn pg-btn--primary" type="button" data-pg-confirm disabled>Confirmar Pago</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                </div>
            </main>
        </div>

        <script>
            (() => {
                const items = Array.from(document.querySelectorAll('[data-pg-item]'));
                const modal = document.getElementById('pgPayModal');
                const modalCode = modal ? modal.querySelector('[data-pg-modal-code]') : null;
                const modalTotal = modal ? modal.querySelector('[data-pg-modal-total]') : null;
                const confirmBtn = modal ? modal.querySelector('[data-pg-confirm]') : null;

                const closeItem = (item) => {
                    const details = item.querySelector('.pg-details');
                    const btn = item.querySelector('[data-pg-btn]');
                    if (details) details.hidden = true;
                    if (btn) btn.setAttribute('aria-expanded', 'false');
                    item.classList.remove('pg-item--open');
                };

                const openItem = (item) => {
                    const details = item.querySelector('.pg-details');
                    const btn = item.querySelector('[data-pg-btn]');
                    if (details) details.hidden = false;
                    if (btn) btn.setAttribute('aria-expanded', 'true');
                    item.classList.add('pg-item--open');
                };

                const toggleItem = (item) => {
                    const isOpen = item.classList.contains('pg-item--open');
                    items.forEach((it) => closeItem(it));
                    if (!isOpen) openItem(item);
                };

                items.forEach((item) => {
                    const btn = item.querySelector('[data-pg-btn]');

                    if (btn) {
                        btn.addEventListener('click', (e) => {
                            e.stopPropagation();
                            toggleItem(item);
                        });
                    }

                    const payBtn = item.querySelector('[data-pg-pay]');
                    if (payBtn) {
                        payBtn.addEventListener('click', (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            if (!modal) return;
                            const code = item.querySelector('[data-pg-code]')?.textContent?.trim() || '';
                            const amount = item.querySelector('[data-pg-amount]')?.getAttribute('data-pg-amount') || '';
                            if (modalCode) modalCode.textContent = code;
                            if (modalTotal) modalTotal.textContent = amount;

                            const radios = Array.from(modal.querySelectorAll('input[name="pgMethod"]'));
                            radios.forEach((r) => (r.checked = false));
                            if (confirmBtn) confirmBtn.disabled = true;

                            document.body.classList.add('pg-lock');
                            modal.classList.add('pg-modal--open');
                            modal.setAttribute('aria-hidden', 'false');
                        });
                    }
                });

                const closeModal = () => {
                    if (!modal) return;
                    modal.classList.remove('pg-modal--open');
                    modal.setAttribute('aria-hidden', 'true');
                    document.body.classList.remove('pg-lock');
                };

                if (modal) {
                    modal.addEventListener('click', (e) => {
                        const closeEl = e.target.closest('[data-pg-modal-close]');
                        if (closeEl) closeModal();
                    });

                    const radios = Array.from(modal.querySelectorAll('input[name="pgMethod"]'));
                    radios.forEach((r) => {
                        r.addEventListener('change', () => {
                            if (confirmBtn) confirmBtn.disabled = false;
                        });
                    });
                }

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') closeModal();
                });
            })();
        </script>
    </body>
</html>
