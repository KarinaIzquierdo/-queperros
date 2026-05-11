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
        <link rel="stylesheet" href="{{ asset('css/dueño/reservas.css') }}">
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
                        @php
                            $reservasList = $reservas ?? collect();
                            $pillClass = function (string $estado): string {
                                return match ($estado) {
                                    'confirmado' => 'rs-pill--confirmed',
                                    'pendiente' => 'rs-pill--pending',
                                    'cancelado' => 'rs-pill--cancelled',
                                    'finalizado' => 'rs-pill--done',
                                    default => 'rs-pill--active',
                                };
                            };
                            $pillLabel = function (string $estado): string {
                                return match ($estado) {
                                    'confirmado' => 'Confirmada',
                                    'pendiente' => 'Pendiente',
                                    'cancelado' => 'Cancelada',
                                    'finalizado' => 'Completada',
                                    default => 'Activa',
                                };
                            };
                            $fmtMoney = function ($value): string {
                                if ($value === null || $value === '') return '-';
                                $num = (float) $value;
                                return '$' . number_format($num, 0, ',', '.');
                            };
                        @endphp

                        @if($reservasList->isEmpty())
                            <div class="rs-empty">Aún no tienes reservas registradas.</div>
                        @else
                            @foreach($reservasList as $r)
                                @php
                                    $estado = (string) ($r->estado ?? 'pendiente');
                                    $serviceName = (string) ($r->servicio_nombre ?? 'Servicio');
                                    $petName = (string) ($r->mascota_nombre ?? 'Mascota');
                                    $trainerName = (string) ($r->profesional_nombre ?? '');
                                    $fecha = (string) ($r->fecha ?? '');
                                    $hora = (string) ($r->hora ?? '');
                                    $comments = (string) ($r->comentarios ?? '');
                                    $price = $fmtMoney($r->precio_estimado ?? null);
                                    $avatarLetter = mb_strtoupper(mb_substr($petName !== '' ? $petName : $serviceName, 0, 1));
                                    $isPending = $estado === 'pendiente';
                                    $group = in_array($estado, ['cancelado', 'finalizado'], true) ? 'history' : 'active';
                                @endphp

                                <article
                                    class="rs-item"
                                    data-rs-item
                                    data-rs-group="{{ $group }}"
                                    data-reserva-id="{{ $r->id }}"
                                    data-reserva-estado="{{ $estado }}"
                                    data-reserva-servicio="{{ $serviceName }}"
                                    data-reserva-mascota="{{ $petName }}"
                                    data-reserva-entrenador="{{ $trainerName }}"
                                    data-reserva-fecha="{{ $fecha }}"
                                    data-reserva-hora="{{ $hora }}"
                                    data-reserva-comentarios="{{ $comments }}"
                                    data-reserva-precio="{{ $r->precio_estimado ?? '' }}"
                                >
                                    <div class="rs-item-head">
                                        <div class="rs-item-left">
                                            <div class="rs-avatar" aria-hidden="true">{{ $avatarLetter }}</div>
                                            <div class="rs-main">
                                                <div class="rs-row-top">
                                                    <div class="rs-service">{{ $serviceName }}</div>
                                                    <span class="rs-pill {{ $pillClass($estado) }}">{{ $pillLabel($estado) }}</span>
                                                </div>
                                                <div class="rs-meta">
                                                    <span><i class="bi bi-person" aria-hidden="true"></i>{{ $petName }}</span>
                                                    @if($trainerName !== '')
                                                        <span><i class="bi bi-person-badge" aria-hidden="true"></i>{{ $trainerName }}</span>
                                                    @endif
                                                    @if($fecha !== '')
                                                        <span><i class="bi bi-calendar" aria-hidden="true"></i>{{ $fecha }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="rs-item-right">
                                            <div class="rs-price">
                                                <div class="rs-price-amount">{{ $price }}</div>
                                                <div class="rs-price-sub">{{ $hora }}</div>
                                            </div>
                                            <button class="rs-chevron" type="button" data-rs-toggle aria-expanded="false" aria-label="Ver detalle">
                                                <i class="bi bi-chevron-down" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="rs-details" hidden>
                                        <div class="rs-detail-grid">
                                            <div class="rs-detail-card">
                                                <div class="rs-detail-label">Fecha</div>
                                                <div class="rs-detail-value">{{ $fecha }}</div>
                                            </div>
                                            <div class="rs-detail-card">
                                                <div class="rs-detail-label">Horario</div>
                                                <div class="rs-detail-value">{{ $hora }}</div>
                                            </div>
                                            <div class="rs-detail-card">
                                                <div class="rs-detail-label">Total</div>
                                                <div class="rs-detail-value rs-detail-value--green">{{ $price }}</div>
                                            </div>
                                        </div>

                                        <div class="rs-notes">Notas: {{ $comments !== '' ? $comments : '-' }}</div>

                                        @if($isPending)
                                            <div class="rs-actions">
                                                <button class="rs-action rs-action--primary" type="button" data-rs-edit>
                                                    <i class="bi bi-pencil" aria-hidden="true"></i>
                                                    <span>Modificar</span>
                                                </button>
                                                <button class="rs-action rs-action--danger" type="button" data-rs-cancel>
                                                    <i class="bi bi-trash" aria-hidden="true"></i>
                                                    <span>Cancelar</span>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </article>
                            @endforeach
                        @endif
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
