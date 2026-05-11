<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Gestión de Servicios</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('css/shared/mq-topbar.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/admin-dashboard.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/admin-sidebar-extras.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/dashboard-admin-v2.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/gestionservicios.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/gestionservicios-v2.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    </head>
    <body>
        @include('partials.page-loader')
        @php
            use Illuminate\Support\Str;
        @endphp
        <div class="admin-layout">
            @include('partials.admin-sidebar')

            <main class="admin-main">
                @include('partials.mq-topbar', ['user' => Auth::user(), 'user' => Auth::user(), 
                    'user' => $admin,
                    'roleLabel' => 'Administrador',
                    'profileUrl' => route('admin.settings'),
                    'settingsUrl' => route('admin.settings'),
                    'helpUrl' => route('admin.dashboard'),
                    'notificationsUrl' => route('admin.dashboard'),
                    'notifCount' => 2,
                ])

                @php
                    $totalServices = (int) (($stats['total_services'] ?? 0));
                    $activeServices = (int) (($stats['active_services'] ?? 0));
                    $totalUses = (int) (($stats['total_uses'] ?? 0));
                    $estimatedRevenue = (int) (($stats['estimated_revenue'] ?? 0));
                    $maxUsage = max(1, (int) (collect($services ?? [])->max('usage_month') ?? 0));
                    $categories = $categories ?? ['Todos'];
                    $categoryChipOptions = collect($categories)->filter(fn ($c) => (string) $c !== 'Todos')->values();
                    $categoryOptions = $categoryOptions ?? collect();
                @endphp

                <section class="gs2-page-head">
                    <div class="gs2-page-left">
                        <h1 class="gs2-title">Gestion de Servicios</h1>
                        <p class="gs2-subtitle">Administra los servicios veterinarios disponibles</p>
                    </div>
                    <button type="button" class="gs2-new" id="openGsCreateService">
                        <span class="gs2-new-plus">+</span>
                        <span>Nuevo Servicio</span>
                    </button>
                </section>

                <section class="gs2-stats">
                    <div class="gs2-stat">
                        <div class="gs2-stat-ic gs2-stat-ic--purple"><i class="bi bi-wrench" aria-hidden="true"></i></div>
                        <div class="gs2-stat-main">
                            <div class="gs2-stat-value" id="gsTotalServices">{{ $totalServices }}</div>
                            <div class="gs2-stat-label">Total servicios</div>
                        </div>
                    </div>
                    <div class="gs2-stat">
                        <div class="gs2-stat-ic gs2-stat-ic--green"><i class="bi bi-check-circle" aria-hidden="true"></i></div>
                        <div class="gs2-stat-main">
                            <div class="gs2-stat-value" id="gsActiveServices">{{ $activeServices }}</div>
                            <div class="gs2-stat-label">Activos</div>
                        </div>
                    </div>
                    <div class="gs2-stat">
                        <div class="gs2-stat-ic gs2-stat-ic--blue"><i class="bi bi-graph-up" aria-hidden="true"></i></div>
                        <div class="gs2-stat-main">
                            <div class="gs2-stat-value" id="gsTotalUses">{{ $totalUses }}</div>
                            <div class="gs2-stat-label">Usos totales</div>
                        </div>
                    </div>
                </section>

                <section class="gs2-filters">
                    <div class="gs2-search">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input type="text" id="gsSearchInput" placeholder="Buscar servicios..." />
                    </div>
                    <div class="gs2-chips" aria-label="Filtros por categoría">
                        @foreach ($categories as $c)
                            <button type="button" class="gs2-chip {{ $loop->first ? 'gs2-chip--active' : '' }}" data-category="{{ $c }}">{{ $c }}</button>
                        @endforeach
                    </div>
                </section>

                <section class="gs2-grid" aria-label="Listado de servicios">
                    @foreach (($services ?? []) as $s)
                        @php
                            $progress = min(100, (int) round(((int) ($s['usage_month'] ?? 0) / $maxUsage) * 100));
                            $catClass = match (($s['category_color'] ?? 'gray')) {
                                'blue' => 'gs2-cat--blue',
                                'green' => 'gs2-cat--green',
                                'yellow' => 'gs2-cat--yellow',
                                'purple' => 'gs2-cat--purple',
                                'red' => 'gs2-cat--red',
                                default => 'gs2-cat--gray',
                            };
                        @endphp
                        <article
                            class="gs2-card"
                            data-category="{{ $s['category'] ?? '' }}"
                            data-name="{{ $s['name'] ?? '' }}"
                            data-description="{{ $s['description'] ?? '' }}"
                        >
                            <div class="gs2-card-top">
                                <span class="gs2-cat {{ $catClass }}"><i class="bi bi-tag" aria-hidden="true"></i> {{ $s['category'] ?? 'Categoria' }}</span>
                                @php
                                    $toggleId = 'gsActive_' . ((string) ($s['id'] ?? $loop->index));
                                    $isActive = (bool) ($s['active'] ?? false);
                                @endphp
                                <span class="gs2-active" aria-label="Estado">
                                    <input
                                        type="checkbox"
                                        id="{{ $toggleId }}"
                                        class="gs-toggle-input"
                                        {{ $isActive ? 'checked' : '' }}
                                        data-service-id="{{ $s['id'] ?? '' }}"
                                    />
                                    <label for="{{ $toggleId }}" class="toggleSwitch"></label>
                                </span>
                            </div>

                            <h2 class="gs2-name">{{ $s['name'] ?? 'Servicio' }}</h2>
                            <p class="gs2-desc">{{ $s['description'] ?? '' }}</p>

                            <div class="gs2-meta">
                                <div class="gs2-meta-item"><span class="gs2-meta-ic">$</span> <strong>${{ number_format((int) ($s['price'] ?? 0), 0, ',', '.') }}</strong></div>
                                <div class="gs2-meta-sep">|</div>
                                <div class="gs2-meta-item"><i class="bi bi-clock" aria-hidden="true"></i> <span>{{ $s['duration'] ?? '—' }}</span></div>
                                <div class="gs2-meta-sep">|</div>
                                <div class="gs2-meta-item"><i class="bi bi-star-fill" aria-hidden="true"></i> <span>{{ number_format((float) ($s['rating'] ?? 0), 1) }}</span></div>
                            </div>

                            <div class="gs2-usage">
                                <div class="gs2-usage-top">
                                    <span class="gs2-usage-label">USO ESTE MES</span>
                                    <span class="gs2-usage-value">{{ (int) ($s['usage_month'] ?? 0) }} veces</span>
                                </div>
                                <div class="gs2-bar" aria-hidden="true"><span class="gs2-bar-fill" style="width:{{ $progress }}%"></span></div>
                            </div>

                            <div class="gs2-actions">
                                <button
                                    type="button"
                                    class="gs2-edit"
                                    data-gs-action="open-edit"
                                    data-service-id="{{ $s['id'] ?? '' }}"
                                    data-service-name="{{ $s['name'] ?? '' }}"
                                    data-service-description="{{ $s['description'] ?? '' }}"
                                    data-service-price="{{ (int) ($s['price'] ?? 0) }}"
                                    data-service-duration="{{ $s['duration'] ?? '' }}"
                                    data-service-category-id="{{ (int) ($s['category_id'] ?? 0) }}"
                                ><i class="bi bi-pencil" aria-hidden="true"></i> Editar</button>
                                <button
                                    type="button"
                                    class="gs2-del"
                                    aria-label="Eliminar"
                                    data-gs-action="open-delete"
                                    data-service-id="{{ $s['id'] ?? '' }}"
                                    data-service-name="{{ $s['name'] ?? '' }}"
                                ><i class="bi bi-trash" aria-hidden="true"></i></button>
                            </div>
                        </article>
                    @endforeach
                </section>

                <div class="ad2-modal" id="gsCreateServiceModal" aria-hidden="true">
                    <div class="ad2-modal-backdrop" data-close="true"></div>
                    <div class="ad2-modal-card" role="dialog" aria-modal="true" aria-labelledby="gsCreateServiceTitle">
                        <div class="ad2-modal-head">
                            <h2 class="ad2-modal-title" id="gsCreateServiceTitle">Nuevo Servicio</h2>
                            <button type="button" class="ad2-modal-close" id="closeGsCreateService" aria-label="Cerrar">×</button>
                        </div>
                        <div class="ad2-modal-body">
                            <form class="ad2-modal-form" autocomplete="off" action="{{ route('admin.services.store') }}" method="POST">
                                @csrf
                                <label class="ad2-field">
                                    <span class="ad2-label">Nombre</span>
                                    <input class="ad2-input" type="text" name="name" placeholder="Ej: Consulta General" />
                                </label>

                                <label class="ad2-field">
                                    <span class="ad2-label">Descripcion</span>
                                    <textarea class="ad2-input ad2-textarea" name="description" rows="3" placeholder="Describe el servicio..."></textarea>
                                </label>

                                <div class="ad2-row-2col">
                                    <label class="ad2-field">
                                        <span class="ad2-label">Precio (COP)</span>
                                        <input class="ad2-input" type="number" name="price" placeholder="50000" min="0" step="1" />
                                    </label>

                                    <label class="ad2-field">
                                        <span class="ad2-label">Duracion</span>
                                        <input class="ad2-input" type="number" name="duration" placeholder="30" min="1" step="1" />
                                    </label>
                                </div>

                                <label class="ad2-field">
                                    <span class="ad2-label">Categoria</span>
                                    <select class="ad2-select" name="category_id">
                                        @foreach (($categoryOptions ?? []) as $opt)
                                            <option value="{{ (int) ($opt->id ?? 0) }}">{{ $opt->nombre ?? '' }}</option>
                                        @endforeach
                                    </select>
                                </label>

                                <button type="submit" class="ad2-submit">Crear Servicio</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="ad2-modal" id="gsEditServiceModal" aria-hidden="true">
                    <div class="ad2-modal-backdrop" data-close="true"></div>
                    <div class="ad2-modal-card" role="dialog" aria-modal="true" aria-labelledby="gsEditServiceTitle">
                        <div class="ad2-modal-head">
                            <h2 class="ad2-modal-title" id="gsEditServiceTitle">Editar Servicio</h2>
                            <button type="button" class="ad2-modal-close" id="closeGsEditService" aria-label="Cerrar">×</button>
                        </div>
                        <div class="ad2-modal-body">
                            <form class="ad2-modal-form" autocomplete="off" id="gsEditServiceForm" method="POST" action="">
                                @csrf
                                @method('PUT')
                                <label class="ad2-field">
                                    <span class="ad2-label">Nombre</span>
                                    <input class="ad2-input" type="text" name="name" id="gsEditName" placeholder="Ej: Consulta General" />
                                </label>

                                <label class="ad2-field">
                                    <span class="ad2-label">Descripcion</span>
                                    <textarea class="ad2-input ad2-textarea" name="description" id="gsEditDescription" rows="3" placeholder="Describe el servicio..."></textarea>
                                </label>

                                <div class="ad2-row-2col">
                                    <label class="ad2-field">
                                        <span class="ad2-label">Precio (COP)</span>
                                        <input class="ad2-input" type="number" name="price" id="gsEditPrice" placeholder="50000" min="0" step="1" />
                                    </label>

                                    <label class="ad2-field">
                                        <span class="ad2-label">Duracion</span>
                                        <input class="ad2-input" type="number" name="duration" id="gsEditDuration" placeholder="30" min="1" step="1" />
                                    </label>
                                </div>

                                <label class="ad2-field">
                                    <span class="ad2-label">Categoria</span>
                                    <select class="ad2-select" name="category_id" id="gsEditCategory">
                                        @foreach (($categoryOptions ?? []) as $opt)
                                            <option value="{{ (int) ($opt->id ?? 0) }}">{{ $opt->nombre ?? '' }}</option>
                                        @endforeach
                                    </select>
                                </label>

                                <button type="submit" class="ad2-submit">Actualizar Servicio</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="ad2-modal" id="gsDeleteServiceModal" aria-hidden="true">
                    <div class="ad2-modal-backdrop" data-close="true"></div>
                    <div class="ad2-modal-card" role="dialog" aria-modal="true" aria-labelledby="gsDeleteServiceTitle">
                        <div class="ad2-modal-head">
                            <h2 class="ad2-modal-title" id="gsDeleteServiceTitle">Eliminar Servicio</h2>
                            <button type="button" class="ad2-modal-close" id="closeGsDeleteService" aria-label="Cerrar">×</button>
                        </div>
                        <div class="ad2-modal-body">
                            <form class="ad2-modal-form" id="gsDeleteServiceForm" method="POST" action="">
                                @csrf
                                @method('DELETE')
                                <div class="ad2-label">Estas seguro de eliminar a <strong id="gsDeleteServiceName">—</strong>? Esta accion no se puede deshacer.</div>
                                <div class="ad2-row-2col">
                                    <button type="button" class="ad2-submit" id="gsCancelDelete">Cancelar</button>
                                    <button type="submit" class="ad2-submit" style="background:#e11d48;">Eliminar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                @if (session('success'))
                    <div class="gs-toast" id="gsToast">{{ session('success') }}</div>
                @endif
            </main>
        </div>

        <script>
            (function () {
                const modal = document.getElementById('gsCreateServiceModal');
                const openBtn = document.getElementById('openGsCreateService');
                const closeBtn = document.getElementById('closeGsCreateService');

                const editModal = document.getElementById('gsEditServiceModal');
                const closeEditBtn = document.getElementById('closeGsEditService');
                const editForm = document.getElementById('gsEditServiceForm');
                const editName = document.getElementById('gsEditName');
                const editDescription = document.getElementById('gsEditDescription');
                const editPrice = document.getElementById('gsEditPrice');
                const editDuration = document.getElementById('gsEditDuration');
                const editCategory = document.getElementById('gsEditCategory');

                const deleteModal = document.getElementById('gsDeleteServiceModal');
                const closeDeleteBtn = document.getElementById('closeGsDeleteService');
                const deleteForm = document.getElementById('gsDeleteServiceForm');
                const deleteName = document.getElementById('gsDeleteServiceName');
                const cancelDeleteBtn = document.getElementById('gsCancelDelete');

                const toast = document.getElementById('gsToast');
                const activeServicesEl = document.getElementById('gsActiveServices');
                const searchInput = document.getElementById('gsSearchInput');
                const categoryChips = Array.from(document.querySelectorAll('.gs2-chip'));
                const serviceCards = Array.from(document.querySelectorAll('.gs2-card'));

                function showToast() {
                    if (!toast) return;
                    window.setTimeout(() => {
                        toast.classList.add('gs-toast--open');
                    }, 50);
                    window.setTimeout(() => {
                        toast.classList.remove('gs-toast--open');
                    }, 2300);
                }

                function openModal() {
                    if (!modal) return;
                    modal.classList.add('ad2-modal--open');
                    modal.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';
                }

                function closeModal() {
                    if (!modal) return;
                    modal.classList.remove('ad2-modal--open');
                    modal.setAttribute('aria-hidden', 'true');
                    document.body.style.overflow = '';
                }

                function openAnyModal(m) {
                    if (!m) return;
                    m.classList.add('ad2-modal--open');
                    m.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';
                }

                function closeAnyModal(m) {
                    if (!m) return;
                    m.classList.remove('ad2-modal--open');
                    m.setAttribute('aria-hidden', 'true');
                    document.body.style.overflow = '';
                }

                openBtn?.addEventListener('click', openModal);
                closeBtn?.addEventListener('click', closeModal);
                closeEditBtn?.addEventListener('click', () => closeAnyModal(editModal));
                closeDeleteBtn?.addEventListener('click', () => closeAnyModal(deleteModal));
                cancelDeleteBtn?.addEventListener('click', () => closeAnyModal(deleteModal));

                modal?.addEventListener('click', (e) => {
                    const target = e.target;
                    if (target && target.dataset && target.dataset.close === 'true') {
                        closeModal();
                    }
                });

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const toggleInputs = Array.from(document.querySelectorAll('.gs-toggle-input'));
                toggleInputs.forEach((input) => {
                    input.addEventListener('change', async () => {
                        const id = input.getAttribute('data-service-id') || '';
                        if (!id) return;
                        const active = !!input.checked;
                        const prev = !active;
                        try {
                            const res = await fetch(`/admin/services/${id}/toggle-active`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({ active }),
                            });
                            if (!res.ok) throw new Error('bad_status');
                            await res.json();

                            if (activeServicesEl) {
                                const curr = parseInt(activeServicesEl.textContent || '0', 10) || 0;
                                const next = curr + (active ? 1 : -1);
                                activeServicesEl.textContent = String(Math.max(0, next));
                            }
                        } catch (err) {
                            input.checked = !active;
                        }
                    });
                });

                const normalize = (v) => (v || '').toString().trim().toLowerCase();
                const getActiveCategory = () => {
                    const active = document.querySelector('.gs2-chip--active');
                    return active ? normalize(active.getAttribute('data-category')) : 'todos';
                };

                const applyServiceFilters = () => {
                    const q = normalize(searchInput ? searchInput.value : '');
                    const cat = getActiveCategory();

                    serviceCards.forEach((card) => {
                        const cardCat = normalize(card.getAttribute('data-category'));
                        const haystack = normalize(`${card.getAttribute('data-name') || ''} ${card.getAttribute('data-description') || ''} ${cardCat}`);
                        const catOk = cat === 'todos' ? true : cardCat === cat;
                        const textOk = q ? haystack.includes(q) : true;
                        card.style.display = catOk && textOk ? '' : 'none';
                    });
                };

                categoryChips.forEach((chip) => {
                    chip.addEventListener('click', () => {
                        categoryChips.forEach((c) => c.classList.remove('gs2-chip--active'));
                        chip.classList.add('gs2-chip--active');
                        applyServiceFilters();
                    });
                });

                searchInput?.addEventListener('input', applyServiceFilters);
                applyServiceFilters();

                editModal?.addEventListener('click', (e) => {
                    const target = e.target;
                    if (target && target.dataset && target.dataset.close === 'true') {
                        closeAnyModal(editModal);
                    }
                });

                deleteModal?.addEventListener('click', (e) => {
                    const target = e.target;
                    if (target && target.dataset && target.dataset.close === 'true') {
                        closeAnyModal(deleteModal);
                    }
                });

                document.addEventListener('click', (e) => {
                    const t = e.target;
                    const btn = t && t.closest ? t.closest('[data-gs-action]') : null;
                    const action = btn ? btn.getAttribute('data-gs-action') : null;
                    if (!action) return;

                    if (action === 'open-edit') {
                        const id = btn.getAttribute('data-service-id') || '';
                        if (!id) return;
                        if (editForm) editForm.action = `/admin/services/${id}`;
                        if (editName) editName.value = btn.getAttribute('data-service-name') || '';
                        if (editDescription) editDescription.value = btn.getAttribute('data-service-description') || '';
                        if (editPrice) editPrice.value = btn.getAttribute('data-service-price') || '0';
                        if (editDuration) editDuration.value = btn.getAttribute('data-service-duration') || '';
                        if (editCategory) editCategory.value = btn.getAttribute('data-service-category-id') || '';
                        openAnyModal(editModal);
                        return;
                    }

                    if (action === 'open-delete') {
                        const id = btn.getAttribute('data-service-id') || '';
                        if (!id) return;
                        if (deleteForm) deleteForm.action = `/admin/services/${id}`;
                        if (deleteName) deleteName.textContent = btn.getAttribute('data-service-name') || '—';
                        openAnyModal(deleteModal);
                        return;
                    }
                });

                document.addEventListener('keydown', (e) => {
                    if (e.key !== 'Escape') return;
                    if (modal?.classList.contains('ad2-modal--open')) closeModal();
                    if (editModal?.classList.contains('ad2-modal--open')) closeAnyModal(editModal);
                    if (deleteModal?.classList.contains('ad2-modal--open')) closeAnyModal(deleteModal);
                });

                showToast();
            })();
        </script>
    </body>
</html>
