<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Reservas</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('css/shared/mq-topbar.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dueño/reservas.css') }}?v={{ time() }}">
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
                    <section class="rs-page">
                    @if(session('success'))
                        <div class="rs-alert rs-alert--success" role="alert">
                            <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="rs-alert rs-alert--error" role="alert">
                            <i class="bi bi-exclamation-circle-fill" aria-hidden="true"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif
                    
                    <div class="rs-head">
                        <h1 class="rs-title">Mis Reservas</h1>
                        <p class="rs-sub">Gestiona tus servicios contratados</p>

                        <div class="rs-stats">
                            <div class="rs-stat">
                                <div class="rs-stat-label">Reservas Activas</div>
                                <div class="rs-stat-value">{{ (int) (($counts['activas'] ?? 0)) }}</div>
                            </div>
                            <div class="rs-stat">
                                <div class="rs-stat-label">Confirmadas</div>
                                <div class="rs-stat-value rs-stat-value--green">{{ (int) (($counts['confirmadas'] ?? 0)) }}</div>
                            </div>
                            <div class="rs-stat">
                                <div class="rs-stat-label">Pendientes</div>
                                <div class="rs-stat-value rs-stat-value--amber">{{ (int) (($counts['pendientes'] ?? 0)) }}</div>
                            </div>
                            <div class="rs-stat">
                                <div class="rs-stat-label">Completadas</div>
                                <div class="rs-stat-value">{{ (int) (($counts['completadas'] ?? 0)) }}</div>
                            </div>
                        </div>

                        <div class="rs-tabs" role="tablist" aria-label="Reservas">
                            <button class="rs-tab rs-tab--active" type="button" role="tab" aria-selected="true" data-rs-tab="active">Reservas Activas ({{ (int) (($counts['activas'] ?? 0)) }})</button>
                            <button class="rs-tab" type="button" role="tab" aria-selected="false" data-rs-tab="history">Historial ({{ (int) (($counts['historial'] ?? 0)) }})</button>
                        </div>
                    </div>

                    <div class="rs-controls">
                        <div class="rs-search">
                            <i class="bi bi-search" aria-hidden="true"></i>
                            <input type="text" placeholder="Buscar por servicio o mascota..." aria-label="Buscar">
                        </div>

                        <div class="rs-filter">
                            <i class="bi bi-funnel" aria-hidden="true"></i>
                            <select aria-label="Filtrar estados">
                                <option value="">Todos los estados</option>
                                <option value="activa">Activa</option>
                                <option value="confirmada">Confirmada</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="completada">Completada</option>
                            </select>
                        </div>
                    </div>

                    <div class="rs-list" aria-label="Lista de reservas">
                        <!-- SECCIÓN PENDIENTES -->
                        <div class="rs-section-container">
                            <h2 class="rs-section-title">Reservas Pendientes ({{ $pendientes->count() ?? 0 }})</h2>
                            <div class="rs-grid">
                            @if(($pendientes ?? collect())->isNotEmpty())
                                @foreach($pendientes as $r)
                                    <div class="rs-card rs-card--pending">
                                        <div class="rs-card-header">
                                            <div class="rs-card-info">
                                                <h3>{{ $r->servicio_nombre ?? 'N/A' }}</h3>
                                                <span class="rs-card-price">$ {{ number_format($r->precio ?? 0, 0, ',', '.') }}</span>
                                            </div>
                                            <span class="rs-status-badge rs-status-badge--pending">{{ $r->estado ?? 'N/A' }}</span>
                                        </div>
                                        <div class="rs-card-body">
                                            <div class="rs-detail-row">
                                                <div class="rs-detail-item">
                                                    <i class="bi bi-heart"></i>
                                                    <span>{{ $r->mascota_nombre ?? 'N/A' }}</span>
                                                </div>
                                                <div class="rs-detail-item">
                                                    <i class="bi bi-person"></i>
                                                    <span>{{ $r->profesional_nombre ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="rs-meta-box">
                                                <p>📅 <strong>Fecha de Solicitud:</strong> {{ $r->fecha ?? 'N/A' }}</p>
                                            </div>

                                            @if($r->fecha_evaluacion)
                                                <div class="rs-meta-box rs-meta-box--eval">
                                                    <p>🏥 <strong>Cita de Evaluación:</strong> {{ $r->fecha_evaluacion }}</p>
                                                    @if($r->hora_evaluacion)
                                                        <p>⏰ <strong>Hora:</strong> {{ substr($r->hora_evaluacion, 0, 5) ?? 'N/A' }}</p>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="rs-empty">No hay reservas pendientes</div>
                            @endif
                            </div>
                        </div>

                        <!-- SECCIÓN CONFIRMADAS -->
                        <div class="rs-section-container">
                            <h2 class="rs-section-title">Reservas Confirmadas ({{ $confirmadas->count() ?? 0 }})</h2>
                            <div class="rs-grid">
                            @if(($confirmadas ?? collect())->isNotEmpty())
                                @foreach($confirmadas as $r)
                                    <div class="rs-card rs-card--confirmed">
                                        <div class="rs-card-header">
                                            <div class="rs-card-info">
                                                <h3>{{ $r->servicio_nombre ?? 'N/A' }}</h3>
                                                <span class="rs-card-price">$ {{ number_format($r->precio ?? 0, 0, ',', '.') }}</span>
                                            </div>
                                            <span class="rs-status-badge rs-status-badge--confirmed">{{ $r->estado ?? 'N/A' }}</span>
                                        </div>
                                        <div class="rs-card-body">
                                            <div class="rs-detail-row">
                                                <div class="rs-detail-item">
                                                    <i class="bi bi-heart"></i>
                                                    <span>{{ $r->mascota_nombre ?? 'N/A' }}</span>
                                                </div>
                                                <div class="rs-detail-item">
                                                    <i class="bi bi-person"></i>
                                                    <span>{{ $r->profesional_nombre ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="rs-meta-box">
                                                <p>📅 <strong>Fecha de Solicitud:</strong> {{ $r->fecha ?? 'N/A' }}</p>
                                            </div>
                                            
                                            @if($r->fecha_evaluacion)
                                                <div class="rs-meta-box rs-meta-box--eval">
                                                    <p>🏥 <strong>Cita de Evaluación:</strong> {{ $r->fecha_evaluacion }}</p>
                                                    @if($r->hora_evaluacion)
                                                        <p>⏰ <strong>Hora:</strong> {{ substr($r->hora_evaluacion, 0, 5) ?? 'N/A' }}</p>
                                                    @endif
                                                </div>
                                            @endif
                                            
                                            @if($r->precio && $r->estado === 'Cotizado / Pendiente de Aprobación')
                                                <div class="rs-meta-box rs-meta-box--cotizado">
                                                    <p>💰 <strong>Precio Cotizado:</strong> $ {{ number_format($r->precio, 0, ',', '.') }}</p>
                                                    <p>⏱️ <strong>Duración:</strong> {{ $r->duracion ?? 'N/A' }} días</p>
                                                    @if($r->observaciones)
                                                        <p>📝 <strong>Notas:</strong> {{ $r->observaciones }}</p>
                                                    @endif
                                                </div>
                                                <div class="rs-card-actions">
                                                    <form action="{{ route('owner.reservas.aceptar-cotizacion', $r->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="rs-btn-confirm">
                                                            Aceptar y Pagar
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('owner.reservas.rechazar-cotizacion', $r->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="rs-btn-reject">
                                                            Rechazar
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif

                                            @if($r->estado === 'Aceptada / Esperando Pago')
                                                <div class="rs-msg-box rs-meta-box--amber">
                                                    <p>✅ <strong>Cotización Aceptada</strong> - Esperando pago</p>
                                                </div>
                                                <form action="{{ route('payment.reservation.create', $r->id) }}" method="POST" style="margin-top: 15px;">
                                                    @csrf
                                                    <button type="submit" class="rs-btn-mercadopago">
                                                        <i class="bi bi-credit-card"></i> Pagar con MercadoPago
                                                    </button>
                                                </form>
                                            @endif

                                            @if($r->estado === 'Pagada')
                                                <div class="rs-msg-box rs-meta-box--success">
                                                    <p>💰 <strong>Pago Realizado</strong> - Servicio confirmado</p>
                                                </div>
                                            @endif
                                            
                                            @if($r->estado === 'Pagado / En Curso')
                                                <form action="{{ route('payment.reservation.create', $r->id) }}" method="POST" style="margin-top: 15px;">
                                                    @csrf
                                                    <button type="submit" class="rs-btn-mercadopago">
                                                        <i class="bi bi-credit-card"></i> Pagar con MercadoPago
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            @if($r->estado === 'Confirmada' || $r->estado === 'confirmada')
                                                <form action="{{ route('payment.reservation.create', $r->id) }}" method="POST" style="margin-top: 15px;">
                                                    @csrf
                                                    <button type="submit" class="rs-btn-mercadopago">
                                                        <i class="bi bi-credit-card"></i> Pagar con MercadoPago
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="rs-empty">No hay reservas confirmadas</div>
                            @endif
                            </div>
                        </div>

                        <!-- SECCIÓN CANCELADAS -->
                        <div class="rs-section-container">
                            <h2 class="rs-section-title">Historial / Canceladas ({{ $canceladas->count() ?? 0 }})</h2>
                            <div class="rs-grid">
                            @if(($canceladas ?? collect())->isNotEmpty())
                                @foreach($canceladas as $r)
                                    <div class="rs-card rs-card--cancelled">
                                        <div class="rs-card-header">
                                            <div class="rs-card-info">
                                                <h3>{{ $r->servicio_nombre ?? 'N/A' }}</h3>
                                            </div>
                                            <span class="rs-status-badge rs-status-badge--cancelled">{{ $r->estado ?? 'N/A' }}</span>
                                        </div>
                                        <div class="rs-card-body">
                                            <div class="rs-detail-row">
                                                <div class="rs-detail-item">
                                                    <i class="bi bi-heart"></i>
                                                    <span>{{ $r->mascota_nombre ?? 'N/A' }}</span>
                                                </div>
                                                <div class="rs-detail-item">
                                                    <i class="bi bi-person"></i>
                                                    <span>{{ $r->profesional_nombre ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            <div class="rs-meta-box">
                                                <p>📅 <strong>Fecha:</strong> {{ $r->fecha ?? 'N/A' }}</p>
                                            </div>
                                            <div class="rs-msg-box rs-meta-box--error">
                                                <p>❌ Reserva Cancelada/Rechazada</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="rs-empty">No hay historial para mostrar</div>
                            @endif
                            </div>
                        </div>
                    </div>
                </section>
                </div>
            </main>
        </div>

        <div class="rs-modal" id="rsEditModal" aria-hidden="true">
            <div class="rs-modal-backdrop" data-rs-close></div>
            <div class="rs-modal-card" role="dialog" aria-modal="true" aria-labelledby="rsEditTitle">
                <div class="rs-modal-head">
                    <div class="rs-modal-title" id="rsEditTitle">Modificar Reserva</div>
                    <button class="rs-modal-close" type="button" aria-label="Cerrar" data-rs-close>
                        <i class="bi bi-x-lg" aria-hidden="true"></i>
                    </button>
                </div>

                <form class="rs-form" id="rsEditForm" method="POST">
                    @csrf
                    @method('PUT')

                    <label class="rs-field">
                        <span class="rs-label">Servicio</span>
                        <input class="rs-input" type="text" id="rsEditServicio" readonly />
                    </label>

                    <label class="rs-field">
                        <span class="rs-label">Mascota</span>
                        <input class="rs-input" type="text" id="rsEditMascota" readonly />
                    </label>

                    <label class="rs-field">
                        <span class="rs-label">Entrenador</span>
                        <input class="rs-input" type="text" id="rsEditEntrenador" readonly />
                    </label>

                    <label class="rs-field">
                        <span class="rs-label">Precio</span>
                        <input class="rs-input" type="text" name="precio_estimado" id="rsEditPrecio" placeholder="$ 0" />
                    </label>

                    <div class="rs-row">
                        <label class="rs-field">
                            <span class="rs-label">Nueva Fecha</span>
                            <input class="rs-input" type="date" name="fecha" id="rsEditFecha" required />
                        </label>
                        <label class="rs-field">
                            <span class="rs-label">Nueva Hora</span>
                            <input class="rs-input" type="time" name="hora" id="rsEditHora" required />
                        </label>
                    </div>

                    <label class="rs-field">
                        <span class="rs-label">Notas adicionales</span>
                        <textarea class="rs-textarea" name="comentarios" id="rsEditComentarios" rows="4"></textarea>
                    </label>

                    <div class="rs-modal-actions">
                        <button class="rs-btn rs-btn--ghost" type="button" data-rs-close>Cancelar</button>
                        <button class="rs-btn rs-btn--primary" type="submit">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="rs-modal" id="rsCancelModal" aria-hidden="true">
            <div class="rs-modal-backdrop" data-rs-close></div>
            <div class="rs-modal-card rs-modal-card--danger" role="dialog" aria-modal="true" aria-labelledby="rsCancelTitle">
                <div class="rs-cancel-top">
                    <div class="rs-cancel-icon" aria-hidden="true"><i class="bi bi-exclamation-lg"></i></div>
                    <div>
                        <div class="rs-modal-title" id="rsCancelTitle">Cancelar Reserva</div>
                        <div class="rs-cancel-sub">Esta accion no se puede deshacer</div>
                    </div>
                    <button class="rs-modal-close" type="button" aria-label="Cerrar" data-rs-close>
                        <i class="bi bi-x-lg" aria-hidden="true"></i>
                    </button>
                </div>

                <div class="rs-cancel-body">
                    Estas seguro de que deseas cancelar esta reserva? Te contactaremos para confirmar la cancelacion y procesar cualquier reembolso aplicable.
                </div>

                <form class="rs-cancel-actions" id="rsCancelForm" method="POST">
                    @csrf
                    <button class="rs-btn rs-btn--ghost" type="button" data-rs-close>No, mantener</button>
                    <button class="rs-btn rs-btn--danger" type="submit">Si, cancelar</button>
                </form>
            </div>
        </div>

        <script>
            (() => {
                const items = Array.from(document.querySelectorAll('[data-rs-item]'));
                const tabButtons = Array.from(document.querySelectorAll('[data-rs-tab]'));
                const editModal = document.getElementById('rsEditModal');
                const cancelModal = document.getElementById('rsCancelModal');
                const editForm = document.getElementById('rsEditForm');
                const cancelForm = document.getElementById('rsCancelForm');

                let activeTab = 'active';

                const editServicio = document.getElementById('rsEditServicio');
                const editMascota = document.getElementById('rsEditMascota');
                const editEntrenador = document.getElementById('rsEditEntrenador');
                const editFecha = document.getElementById('rsEditFecha');
                const editHora = document.getElementById('rsEditHora');
                const editComentarios = document.getElementById('rsEditComentarios');
                const editPrecio = document.getElementById('rsEditPrecio');

                const closeItem = (item) => {
                    const details = item.querySelector('.rs-details');
                    const btn = item.querySelector('[data-rs-toggle]');
                    if (!details || !btn) return;
                    details.hidden = true;
                    btn.setAttribute('aria-expanded', 'false');
                    item.classList.remove('rs-item--open');
                };

                const openItem = (item) => {
                    const details = item.querySelector('.rs-details');
                    const btn = item.querySelector('[data-rs-toggle]');
                    if (!details || !btn) return;
                    details.hidden = false;
                    btn.setAttribute('aria-expanded', 'true');
                    item.classList.add('rs-item--open');
                };

                const applyTabFilter = () => {
                    items.forEach((item) => {
                        const group = item.dataset.rsGroup || 'active';
                        const visible = group === activeTab;
                        item.style.display = visible ? '' : 'none';
                        if (!visible) closeItem(item);
                    });
                };

                const setActiveTab = (tab) => {
                    activeTab = tab;
                    tabButtons.forEach((btn) => {
                        const isActive = btn.dataset.rsTab === tab;
                        btn.classList.toggle('rs-tab--active', isActive);
                        btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
                    });
                    applyTabFilter();
                };

                const openModal = (modal) => {
                    if (!modal) return;
                    modal.classList.add('is-open');
                    modal.setAttribute('aria-hidden', 'false');
                    document.documentElement.classList.add('rs-modal-open');
                };

                const closeModal = (modal) => {
                    if (!modal) return;
                    modal.classList.remove('is-open');
                    modal.setAttribute('aria-hidden', 'true');
                    document.documentElement.classList.remove('rs-modal-open');
                };

                const bindModalClose = (modal) => {
                    if (!modal) return;
                    modal.querySelectorAll('[data-rs-close]').forEach((el) => {
                        el.addEventListener('click', () => closeModal(modal));
                    });
                };

                bindModalClose(editModal);
                bindModalClose(cancelModal);

                const openEditFromItem = (item) => {
                    const id = item.dataset.reservaId;
                    if (!id || !editForm) return;

                    editServicio.value = item.dataset.reservaServicio || '';
                    editMascota.value = item.dataset.reservaMascota || '';
                    editEntrenador.value = item.dataset.reservaEntrenador || '';
                    editFecha.value = item.dataset.reservaFecha || '';
                    editHora.value = item.dataset.reservaHora || '';
                    editComentarios.value = item.dataset.reservaComentarios || '';
                    if (editPrecio) editPrecio.value = item.dataset.reservaPrecio || '';
                    editForm.action = `{{ url('/dashboard/reservas') }}/${id}`;
                    openModal(editModal);
                };

                const openCancelFromItem = (item) => {
                    const id = item.dataset.reservaId;
                    if (!id || !cancelForm) return;
                    cancelForm.action = `{{ url('/dashboard/reservas') }}/${id}/cancel`;
                    openModal(cancelModal);
                };

                items.forEach((item) => {
                    const btn = item.querySelector('[data-rs-toggle]');
                    if (!btn) return;

                    const toggleAccordion = () => {
                        const isOpen = item.classList.contains('rs-item--open');
                        items.forEach((it) => closeItem(it));
                        if (!isOpen) openItem(item);
                    };

                    btn.addEventListener('click', toggleAccordion);

                    const clickable = item.querySelector('.rs-item-left');
                    if (clickable) {
                        clickable.addEventListener('click', () => toggleAccordion());
                    }

                    const editBtn = item.querySelector('[data-rs-edit]');
                    if (editBtn) {
                        editBtn.addEventListener('click', () => openEditFromItem(item));
                    }

                    const cancelBtn = item.querySelector('[data-rs-cancel]');
                    if (cancelBtn) {
                        cancelBtn.addEventListener('click', () => openCancelFromItem(item));
                    }
                });

                tabButtons.forEach((btn) => {
                    btn.addEventListener('click', () => {
                        const tab = btn.dataset.rsTab;
                        if (!tab) return;
                        setActiveTab(tab);
                    });
                });

                setActiveTab(activeTab);

                document.addEventListener('keydown', (e) => {
                    if (e.key !== 'Escape') return;
                    closeModal(editModal);
                    closeModal(cancelModal);
                });
            })();
        </script>
    </body>
</html>
