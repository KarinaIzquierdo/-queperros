<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Gestión de Usuarios</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('css/shared/mq-topbar.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/admin-dashboard.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/admin-sidebar-extras.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/dashboard-admin-v2.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/gestionusuarios.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/gestionusuarios-v2.css') }}?v={{ time() }}">
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
                    $totalUsers = $stats['total_users'] ?? ($users ?? collect())->count();
                    $activeUsers = $stats['active_users'] ?? ($users ?? collect())->whereNotNull('email_verified_at')->count();
                    $inactiveUsers = $stats['inactive_users'] ?? ($users ?? collect())->whereNull('email_verified_at')->count();
                    $definedRoles = $stats['defined_roles'] ?? ($users ?? collect())->pluck('rol')->filter()->unique()->count();
                @endphp

                <section class="gu-page-head">
                    <div class="gu-page-head-left">
                        <h1 class="gu-page-title">Gestion de Usuarios</h1>
                        <p class="gu-page-subtitle">Administra los usuarios del sistema</p>
                    </div>
                    <button type="button" class="gu-new-user" id="openAdminRegisterUserFromUsers">
                        <span class="gu-new-user-plus">+</span>
                        <span>Nuevo Usuario</span>
                    </button>
                </section>

                <section class="gu-stats">
                    <div class="gu-stat">
                        <div class="gu-stat-icon gu-stat-icon--blue"><i class="bi bi-people" aria-hidden="true"></i></div>
                        <div class="gu-stat-main">
                            <div class="gu-stat-value">{{ $totalUsers }}</div>
                            <div class="gu-stat-label">Total usuarios</div>
                        </div>
                    </div>
                    <div class="gu-stat">
                        <div class="gu-stat-icon gu-stat-icon--green"><i class="bi bi-person-check" aria-hidden="true"></i></div>
                        <div class="gu-stat-main">
                            <div class="gu-stat-value">{{ $activeUsers }}</div>
                            <div class="gu-stat-label">Activos</div>
                        </div>
                    </div>
                    <div class="gu-stat">
                        <div class="gu-stat-icon gu-stat-icon--red"><i class="bi bi-person-x" aria-hidden="true"></i></div>
                        <div class="gu-stat-main">
                            <div class="gu-stat-value">{{ $inactiveUsers }}</div>
                            <div class="gu-stat-label">Inactivos</div>
                        </div>
                    </div>
                    <div class="gu-stat">
                        <div class="gu-stat-icon gu-stat-icon--blue"><i class="bi bi-shield-lock" aria-hidden="true"></i></div>
                        <div class="gu-stat-main">
                            <div class="gu-stat-value">{{ $definedRoles }}</div>
                            <div class="gu-stat-label">Roles</div>
                        </div>
                    </div>
                </section>

                <section class="gu-toolbar">
                    <div class="gu-search">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input type="text" id="guSearchInput" placeholder="Buscar usuarios..." />
                    </div>
                    <div class="gu-filters" role="tablist" aria-label="Filtros por rol">
                        <button type="button" class="gu-filter-chip gu-filter-chip--active" data-role="all">Todos</button>
                        <button type="button" class="gu-filter-chip" data-role="admin">Administrador</button>
                        <button type="button" class="gu-filter-chip" data-role="dueno">Dueño</button>
                        <button type="button" class="gu-filter-chip" data-role="padrino">Padrino</button>
                        <button type="button" class="gu-filter-chip" data-role="entrenador">Entrenador</button>
                    </div>
                </section>

                <section class="gu-table-wrap">
                    <table class="gu-table">
                        <thead>
                            <tr>
                                <th>USUARIO</th>
                                <th>CONTACTO</th>
                                <th>ROL</th>
                                <th>REGISTRO</th>
                                <th>ESTADO</th>
                                <th class="gu-th-actions">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (($users ?? []) as $user)
                                <tr data-role="{{ $user->rol ?? '' }}">
                                    <td>
                                        <div class="gu-user-cell">
                                            <div class="gu-avatar">
                                                {{ mb_substr($user->name ?? 'U', 0, 1) }}{{ mb_substr($user->name ?? '', 1, 1) }}
                                            </div>
                                            <div class="gu-user-meta">
                                                <div class="gu-user-name">{{ $user->name }}</div>
                                                <div class="gu-user-sub">0 mascotas</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="gu-contact">
                                            <div class="gu-contact-row"><i class="bi bi-envelope" aria-hidden="true"></i> <span>{{ $user->email }}</span></div>
                                            <div class="gu-contact-row"><i class="bi bi-telephone" aria-hidden="true"></i> <span>+57 300 000 0000</span></div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $roleText = match ($user->rol ?? '') {
                                                'admin' => 'Administrador',
                                                'empleado' => 'Cuidador',
                                                'dueno' => 'Propietario',
                                                'padrino' => 'Padrino',
                                                'entrenador' => 'Entrenador',
                                                default => ucfirst((string) ($user->rol ?? 'Sin rol')),
                                            };
                                            $roleClass = in_array($user->rol ?? '', ['admin', 'empleado'], true) ? 'gu-role-chip--purple' : 'gu-role-chip--blue';
                                        @endphp
                                        <span class="gu-role-chip {{ $roleClass }}">{{ $roleText }}</span>
                                    </td>
                                    <td>
                                        <span class="gu-date">{{ optional($user->created_at)->format('d M Y') ?? '—' }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $isActive = !is_null($user->email_verified_at);
                                        @endphp
                                        <span class="gu-status {{ $isActive ? 'gu-status--ok' : 'gu-status--off' }}">{{ $isActive ? 'Activo' : 'Inactivo' }}</span>
                                    </td>
                                    <td class="gu-actions">
                                        @php
                                            $initials = mb_strtoupper(mb_substr($user->name ?? 'U', 0, 1) . mb_substr($user->name ?? '', 1, 1));
                                        @endphp
                                        <div class="gu-actions-icons">
                                            <button
                                                type="button"
                                                class="gu-action-icon-btn"
                                                aria-label="Ver detalle"
                                                data-gu-action="open-detail"
                                                data-user-id="{{ $user->id }}"
                                                data-user-name="{{ $user->name }}"
                                                data-user-initials="{{ $initials }}"
                                                data-user-email="{{ $user->email }}"
                                                data-user-phone="+57 310 987 6543"
                                                data-user-role="{{ $roleText }}"
                                                data-user-rol-code="{{ $user->rol ?? '' }}"
                                                data-user-created="{{ optional($user->created_at)->format('d/n/Y') ?? '—' }}"
                                                data-user-pets="0"
                                                data-user-status="{{ $isActive ? 'Activo' : 'Inactivo' }}"
                                            >
                                                <i class="bi bi-eye" aria-hidden="true"></i>
                                            </button>
                                            <button
                                                type="button"
                                                class="gu-action-icon-btn"
                                                aria-label="Editar"
                                                data-gu-action="open-edit"
                                                data-user-id="{{ $user->id }}"
                                                data-user-name="{{ $user->name }}"
                                                data-user-initials="{{ $initials }}"
                                                data-user-email="{{ $user->email }}"
                                                data-user-phone="+57 310 987 6543"
                                                data-user-role="{{ $roleText }}"
                                                data-user-rol-code="{{ $user->rol ?? '' }}"
                                                data-user-created="{{ optional($user->created_at)->format('d/n/Y') ?? '—' }}"
                                                data-user-pets="0"
                                                data-user-status="{{ $isActive ? 'Activo' : 'Inactivo' }}"
                                            >
                                                <i class="bi bi-pencil" aria-hidden="true"></i>
                                            </button>
                                            <button
                                                type="button"
                                                class="gu-action-icon-btn gu-action-icon-btn--danger"
                                                aria-label="Eliminar"
                                                data-gu-action="open-delete"
                                                data-user-id="{{ $user->id }}"
                                                data-user-name="{{ $user->name }}"
                                                data-user-initials="{{ $initials }}"
                                                data-user-email="{{ $user->email }}"
                                                data-user-phone="+57 310 987 6543"
                                                data-user-role="{{ $roleText }}"
                                                data-user-rol-code="{{ $user->rol ?? '' }}"
                                                data-user-created="{{ optional($user->created_at)->format('d/n/Y') ?? '—' }}"
                                                data-user-pets="0"
                                                data-user-status="{{ $isActive ? 'Activo' : 'Inactivo' }}"
                                            >
                                                <i class="bi bi-trash" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="gu-footer" id="guFooter">Mostrando {{ ($users ?? collect())->count() }} de {{ $totalUsers }} usuarios</div>
                </section>

                <div class="gu-modal" id="guDetailModal" aria-hidden="true">
                    <div class="gu-modal-backdrop" data-gu-action="close-modal"></div>
                    <div class="gu-modal-card" role="dialog" aria-modal="true" aria-label="Detalle del Usuario">
                        <div class="gu-modal-head">
                            <div class="gu-modal-title">Detalle del Usuario</div>
                            <button type="button" class="gu-modal-x" aria-label="Cerrar" data-gu-action="close-modal">×</button>
                        </div>
                        <div class="gu-modal-body">
                            <div class="gu-detail-top">
                                <div class="gu-detail-avatar" id="guDetailInitials">AL</div>
                                <div class="gu-detail-title">
                                    <div class="gu-detail-name" id="guDetailName">—</div>
                                    <div class="gu-detail-status"><span class="gu-status gu-status--ok" id="guDetailStatus">Activo</span></div>
                                </div>
                            </div>

                            <div class="gu-detail-grid">
                                <div class="gu-detail-box">
                                    <div class="gu-detail-label">EMAIL</div>
                                    <div class="gu-detail-value" id="guDetailEmail">—</div>
                                </div>
                                <div class="gu-detail-box">
                                    <div class="gu-detail-label">TELEFONO</div>
                                    <div class="gu-detail-value" id="guDetailPhone">—</div>
                                </div>
                                <div class="gu-detail-box">
                                    <div class="gu-detail-label">ROL</div>
                                    <div class="gu-detail-value" id="guDetailRole">—</div>
                                </div>
                                <div class="gu-detail-box">
                                    <div class="gu-detail-label">REGISTRO</div>
                                    <div class="gu-detail-value" id="guDetailCreated">—</div>
                                </div>
                                <div class="gu-detail-box gu-detail-box--full">
                                    <div class="gu-detail-label">MASCOTAS</div>
                                    <div class="gu-detail-value" id="guDetailPets">0</div>
                                </div>
                            </div>

                            <div class="gu-detail-actions">
                                <button type="button" class="gu-modal-btn gu-modal-btn--primary" data-gu-action="detail-edit">Editar</button>
                                <button type="button" class="gu-modal-btn gu-modal-btn--danger-outline" data-gu-action="detail-delete">Eliminar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="gu-modal" id="guEditModal" aria-hidden="true">
                    <div class="gu-modal-backdrop" data-gu-action="close-modal"></div>
                    <div class="gu-modal-card" role="dialog" aria-modal="true" aria-label="Editar Usuario">
                        <div class="gu-modal-head">
                            <div class="gu-modal-title">Editar Usuario</div>
                            <button type="button" class="gu-modal-x" aria-label="Cerrar" data-gu-action="close-modal">×</button>
                        </div>
                        <div class="gu-modal-body">
                            <form class="gu-form" onsubmit="return false;">
                                <label class="gu-field">
                                    <span class="gu-field-label">Nombre completo</span>
                                    <input class="gu-input" type="text" id="guEditName" value="" />
                                </label>
                                <label class="gu-field">
                                    <span class="gu-field-label">Email</span>
                                    <input class="gu-input" type="email" id="guEditEmail" value="" />
                                </label>
                                <label class="gu-field">
                                    <span class="gu-field-label">Telefono</span>
                                    <input class="gu-input" type="text" id="guEditPhone" value="" />
                                </label>
                                <label class="gu-field">
                                    <span class="gu-field-label">Rol</span>
                                    <select class="gu-input" id="guEditRole">
                                        <option value="dueno">Propietario</option>
                                        <option value="admin">Administrador</option>
                                        <option value="empleado">Cuidador</option>
                                        <option value="padrino">Padrino</option>
                                    </select>
                                </label>
                                <button type="submit" class="gu-save" data-gu-action="save-edit">Guardar Cambios</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="gu-modal" id="guDeleteModal" aria-hidden="true">
                    <div class="gu-modal-backdrop" data-gu-action="close-modal"></div>
                    <div class="gu-modal-card gu-modal-card--delete" role="dialog" aria-modal="true" aria-label="Eliminar Usuario">
                        <div class="gu-modal-head">
                            <div class="gu-modal-title">Eliminar Usuario</div>
                            <button type="button" class="gu-modal-x" aria-label="Cerrar" data-gu-action="close-modal">×</button>
                        </div>
                        <div class="gu-modal-body gu-delete-body">
                            <div class="gu-delete-icon"><i class="bi bi-trash" aria-hidden="true"></i></div>
                            <p class="gu-delete-text">Estas seguro de eliminar a <strong id="guDeleteName">—</strong>? Esta accion no se puede deshacer.</p>
                            <div class="gu-delete-actions">
                                <button type="button" class="gu-modal-btn gu-modal-btn--light" data-gu-action="close-modal">Cancelar</button>
                                <button type="button" class="gu-modal-btn gu-modal-btn--danger" data-gu-action="confirm-delete">Eliminar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ad2-modal" id="adminRegisterUserModalFromUsers" aria-hidden="true">
                    <div class="ad2-modal-backdrop" data-close="true"></div>
                    <div class="ad2-modal-card" role="dialog" aria-modal="true" aria-labelledby="ad2UsersModalTitle">
                        <div class="ad2-modal-head">
                            <h2 class="ad2-modal-title" id="ad2UsersModalTitle">Registrar Nuevo Usuario</h2>
                            <button type="button" class="ad2-modal-close" id="closeAdminRegisterUserFromUsers" aria-label="Cerrar">×</button>
                        </div>
                        <div class="ad2-modal-body">
                            <form action="{{ url('/admin/users') }}" method="POST" class="ad2-modal-form">
                                @csrf

                                <label class="ad2-field">
                                    <span class="ad2-label">Nombre completo</span>
                                    <input class="ad2-input" type="text" name="name" placeholder="Ej: Juan Perez" />
                                </label>

                                <label class="ad2-field">
                                    <span class="ad2-label">Email</span>
                                    <input class="ad2-input" type="email" name="email" placeholder="usuario@email.com" />
                                </label>

                                <label class="ad2-field">
                                    <span class="ad2-label">Rol</span>
                                    <select class="ad2-select" name="rol">
                                        <option value="admin">Administrador</option>
                                        <option value="empleado">Cuidador</option>
                                        <option value="dueno">Dueño</option>
                                        <option value="padrino">Padrino</option>
                                        <option value="entrenador">Entrenador</option>
                                    </select>
                                </label>

                                <button type="submit" class="ad2-submit" formaction="{{ url('/admin/users') }}" formmethod="POST">Registrar Usuario</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="gu-toast" id="guSuccessToast" aria-hidden="true">operacion exitosa</div>
            </main>
        </div>

        <script>
            (function () {
                const state = {
                    currentUser: null,
                };

                const searchInput = document.getElementById('guSearchInput');
                const filterChips = Array.from(document.querySelectorAll('.gu-filter-chip'));
                const tableRows = Array.from(document.querySelectorAll('.gu-table tbody tr'));
                const footer = document.getElementById('guFooter');

                const normalize = (v) => (v || '').toString().trim().toLowerCase();
                const getActiveRoleFilter = () => {
                    const active = document.querySelector('.gu-filter-chip--active');
                    return active ? active.getAttribute('data-role') : 'all';
                };

                const applyFilters = () => {
                    const q = normalize(searchInput ? searchInput.value : '');
                    const role = normalize(getActiveRoleFilter());
                    let visible = 0;

                    tableRows.forEach((row) => {
                        const rowRole = normalize(row.getAttribute('data-role'));
                        const haystack = normalize(row.innerText);

                        const roleOk = role === 'all' ? true : rowRole === role;
                        const textOk = q ? haystack.includes(q) : true;

                        const show = roleOk && textOk;
                        row.style.display = show ? '' : 'none';
                        if (show) visible += 1;
                    });

                    if (footer) {
                        const totalText = footer.textContent || '';
                        const match = totalText.match(/de\s+(\d+)\s+usuarios/i);
                        const total = match ? match[1] : '';
                        footer.textContent = total ? `Mostrando ${visible} de ${total} usuarios` : `Mostrando ${visible} usuarios`;
                    }
                };

                const menus = () => Array.from(document.querySelectorAll('.gu-actions-menu'));
                const closeAllMenus = () => {
                    menus().forEach((m) => {
                        m.classList.remove('gu-actions-menu--open');
                        m.setAttribute('aria-hidden', 'true');
                    });
                };

                const openModal = (el) => {
                    if (!el) return;
                    el.classList.add('gu-modal--open');
                    el.setAttribute('aria-hidden', 'false');
                };

                const closeModal = (el) => {
                    if (!el) return;
                    el.classList.remove('gu-modal--open');
                    el.setAttribute('aria-hidden', 'true');
                };

                const detailModal = document.getElementById('guDetailModal');
                const editModal = document.getElementById('guEditModal');
                const deleteModal = document.getElementById('guDeleteModal');
                const toast = document.getElementById('guSuccessToast');

                const setUserFromBtn = (btn) => {
                    if (!btn) return;
                    state.currentUser = {
                        id: btn.getAttribute('data-user-id') || '',
                        name: btn.getAttribute('data-user-name') || '',
                        initials: btn.getAttribute('data-user-initials') || '',
                        email: btn.getAttribute('data-user-email') || '',
                        phone: btn.getAttribute('data-user-phone') || '',
                        role: btn.getAttribute('data-user-role') || '',
                        rolCode: btn.getAttribute('data-user-rol-code') || '',
                        created: btn.getAttribute('data-user-created') || '',
                        pets: btn.getAttribute('data-user-pets') || '0',
                        status: btn.getAttribute('data-user-status') || 'Activo',
                    };
                };

                const fillDetail = () => {
                    if (!state.currentUser) return;
                    document.getElementById('guDetailInitials').textContent = state.currentUser.initials || 'U';
                    document.getElementById('guDetailName').textContent = state.currentUser.name || '—';
                    document.getElementById('guDetailEmail').textContent = state.currentUser.email || '—';
                    document.getElementById('guDetailPhone').textContent = state.currentUser.phone || '—';
                    document.getElementById('guDetailRole').textContent = state.currentUser.role || '—';
                    document.getElementById('guDetailCreated').textContent = state.currentUser.created || '—';
                    document.getElementById('guDetailPets').textContent = state.currentUser.pets || '0';
                    document.getElementById('guDetailStatus').textContent = state.currentUser.status || 'Activo';
                };

                const fillEdit = () => {
                    if (!state.currentUser) return;
                    document.getElementById('guEditName').value = state.currentUser.name || '';
                    document.getElementById('guEditEmail').value = state.currentUser.email || '';
                    document.getElementById('guEditPhone').value = state.currentUser.phone || '';
                    document.getElementById('guEditRole').value = state.currentUser.rolCode || 'dueno';
                };

                const fillDelete = () => {
                    if (!state.currentUser) return;
                    document.getElementById('guDeleteName').textContent = state.currentUser.name || '—';
                };

                const showToast = (text) => {
                    if (!toast) return;
                    toast.textContent = text || 'operacion exitosa';
                    toast.classList.add('gu-toast--open');
                    toast.setAttribute('aria-hidden', 'false');
                    window.setTimeout(() => {
                        toast.classList.remove('gu-toast--open');
                        toast.setAttribute('aria-hidden', 'true');
                    }, 2200);
                };

                document.addEventListener('click', (e) => {
                    const t = e.target;
                    const btn = t && t.closest ? t.closest('[data-gu-action]') : null;
                    const action = btn ? btn.getAttribute('data-gu-action') : null;

                    const chip = t && t.closest ? t.closest('.gu-filter-chip') : null;
                    if (chip) {
                        filterChips.forEach((c) => c.classList.remove('gu-filter-chip--active'));
                        chip.classList.add('gu-filter-chip--active');
                        applyFilters();
                        return;
                    }

                    if (action === 'toggle-menu') {
                        e.preventDefault();
                        const menu = btn.parentElement ? btn.parentElement.querySelector('.gu-actions-menu') : null;
                        const isOpen = menu && menu.classList.contains('gu-actions-menu--open');
                        closeAllMenus();
                        if (menu && !isOpen) {
                            menu.classList.add('gu-actions-menu--open');
                            menu.setAttribute('aria-hidden', 'false');
                            setUserFromBtn(btn);
                        }
                        return;
                    }

                    if (action === 'open-detail' || action === 'open-edit' || action === 'open-delete') {
                        setUserFromBtn(btn);
                        closeAllMenus();

                        if (action === 'open-detail') {
                            fillDetail();
                            openModal(detailModal);
                        }
                        if (action === 'open-edit') {
                            fillEdit();
                            openModal(editModal);
                        }
                        if (action === 'open-delete') {
                            fillDelete();
                            openModal(deleteModal);
                        }
                        return;
                    }

                    if (action === 'detail-edit') {
                        closeModal(detailModal);
                        fillEdit();
                        openModal(editModal);
                        return;
                    }

                    if (action === 'detail-delete') {
                        closeModal(detailModal);
                        fillDelete();
                        openModal(deleteModal);
                        return;
                    }

                    if (action === 'save-edit') {
                        closeModal(editModal);
                        showToast('operacion exitosa');
                        return;
                    }

                    if (action === 'confirm-delete') {
                        closeModal(deleteModal);
                        showToast('operacion exitosa');
                        return;
                    }

                    if (action === 'close-modal') {
                        closeModal(detailModal);
                        closeModal(editModal);
                        closeModal(deleteModal);
                        closeAllMenus();
                        return;
                    }

                    if (!t.closest('.gu-actions-dd')) {
                        closeAllMenus();
                    }
                });

                searchInput?.addEventListener('input', applyFilters);
                applyFilters();

                const registerModal = document.getElementById('adminRegisterUserModalFromUsers');
                const openRegisterBtn = document.getElementById('openAdminRegisterUserFromUsers');
                const closeRegisterBtn = document.getElementById('closeAdminRegisterUserFromUsers');

                function openRegisterModal() {
                    if (!registerModal) return;
                    registerModal.classList.add('ad2-modal--open');
                    registerModal.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';
                }

                function closeRegisterModal() {
                    if (!registerModal) return;
                    registerModal.classList.remove('ad2-modal--open');
                    registerModal.setAttribute('aria-hidden', 'true');
                    document.body.style.overflow = '';
                }

                openRegisterBtn?.addEventListener('click', () => {
                    closeAllMenus();
                    closeModal(detailModal);
                    closeModal(editModal);
                    closeModal(deleteModal);
                    openRegisterModal();
                });

                closeRegisterBtn?.addEventListener('click', closeRegisterModal);

                registerModal?.addEventListener('click', (e) => {
                    const target = e.target;
                    if (target && target.dataset && target.dataset.close === 'true') {
                        closeRegisterModal();
                    }
                });

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        closeModal(detailModal);
                        closeModal(editModal);
                        closeModal(deleteModal);
                        closeAllMenus();
                        closeRegisterModal();
                    }
                });
            })();
        </script>
    </body>
</html>
