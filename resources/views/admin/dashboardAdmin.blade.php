<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Panel Administrativo - Mascotas</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('css/shared/mq-topbar.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/admin-dashboard.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/admin-sidebar-extras.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/dashboard-admin-v2.css') }}?v={{ time() }}">
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
                    'user' => $user,
                    'roleLabel' => 'Administrador',
                    'profileUrl' => route('admin.settings'),
                    'settingsUrl' => route('admin.settings'),
                    'helpUrl' => route('admin.dashboard'),
                    'notificationsUrl' => route('admin.dashboard'),
                    'notifCount' => 3,
                ])

                <section class="ad2-hero" aria-label="Bienvenida">
                    <div class="ad2-hero-left">
                        <h1 class="ad2-hero-title">Panel Administrativo</h1>
                        <p class="ad2-hero-kicker"><span class="ad2-hero-kicker-icon">✨</span> Buenas tardes</p>
                        <h1 class="ad2-hero-title">BIENVENIDO, {{ Str::upper(Str::limit($user->name ?? 'NOMBRE', 14, '')) }}</h1>
                        <p class="ad2-hero-sub">Administrador del sistema</p>

                        <div class="ad2-hero-chips" aria-label="Estado del sistema">
                            <span class="ad2-chip ad2-chip--green"><i class="bi bi-graph-up" aria-hidden="true"></i> Sistema activo</span>
                            <span class="ad2-chip"><i class="bi bi-clock" aria-hidden="true"></i> Última sesión: Hoy 9:30 AM</span>
                        </div>
                    </div>

                    <div class="ad2-metrics" aria-label="Métricas">
                        <div class="ad2-metric">
                            <div class="ad2-metric-value">14</div>
                            <div class="ad2-metric-label">CITAS HOY</div>
                        </div>
                        <div class="ad2-metric ad2-metric--gold">
                            <div class="ad2-metric-value">98%</div>
                            <div class="ad2-metric-label">UPTIME</div>
                        </div>
                    </div>
                </section>

                <section class="ad2-cards" aria-label="Resumen">
                    <div class="ad2-card">
                        <div class="ad2-card-top">
                            <div class="ad2-card-icon ad2-card-icon--blue"><i class="bi bi-people" aria-hidden="true"></i></div>
                            <span class="ad2-pill ad2-pill--blue">↗ +2 esta semana</span>
                        </div>
                        <div class="ad2-card-number">{{ $stats['total_users'] }}</div>
                        <div class="ad2-card-label">Total Usuarios</div>
                        <div class="ad2-card-hover">
                            <div class="ad2-card-divider"></div>
                            <div class="ad2-card-hover-text">3 propietarios, 2 veterinarios</div>
                            <a href="{{ route('admin.users') }}" class="ad2-card-hover-link">Ver detalle <span aria-hidden="true">→</span></a>
                        </div>
                    </div>

                    <div class="ad2-card">
                        <div class="ad2-card-top">
                            <div class="ad2-card-icon ad2-card-icon--purple"><i class="bi bi-wrench" aria-hidden="true"></i></div>
                            <span class="ad2-pill ad2-pill--purple">↗ +1 este mes</span>
                        </div>
                        <div class="ad2-card-number">{{ $stats['active_services'] }}</div>
                        <div class="ad2-card-label">Servicios Activos</div>
                        <div class="ad2-card-hover">
                            <div class="ad2-card-divider"></div>
                            <div class="ad2-card-hover-text">Consulta, Vacunacion, Guarderia...</div>
                            <a href="#" class="ad2-card-hover-link">Ver detalle <span aria-hidden="true">→</span></a>
                        </div>
                    </div>

                    <div class="ad2-card">
                        <div class="ad2-card-top">
                            <div class="ad2-card-icon ad2-card-icon--yellow"><i class="bi bi-shield" aria-hidden="true"></i></div>
                            <span class="ad2-pill ad2-pill--gray">↔ Sin cambios</span>
                        </div>
                        <div class="ad2-card-number">{{ $stats['defined_roles'] }}</div>
                        <div class="ad2-card-label">Roles Definidos</div>
                        <div class="ad2-card-hover">
                            <div class="ad2-card-divider"></div>
                            <div class="ad2-card-hover-text">Admin, Veterinario, Propietario</div>
                            <a href="#" class="ad2-card-hover-link">Ver detalle <span aria-hidden="true">→</span></a>
                        </div>
                    </div>
                </section>

                <section class="ad2-block" aria-label="Acciones rápidas">
                    <h2 class="ad2-section-title">Acciones Rapidas</h2>
                    <p class="ad2-section-sub">Operaciones frecuentes del sistema</p>

                    <div class="ad2-actions">
                        <button type="button" class="ad2-action ad2-action--purple ad2-action--modal" id="openAdminRegisterUser">
                            <div class="ad2-action-icon"><i class="bi bi-person-plus" aria-hidden="true"></i></div>
                            <div>
                                <p class="ad2-action-title">Registrar Usuarios</p>
                                <p class="ad2-action-desc">Agregar nuevo usuario al sistema</p>
                            </div>
                            <div class="ad2-action-open">Abrir <span aria-hidden="true">→</span></div>
                        </button>

                        <button type="button" class="ad2-action ad2-action--blue" id="openAdminUsersModal">
                            <div class="ad2-action-icon"><i class="bi bi-eye" aria-hidden="true"></i></div>
                            <div>
                                <p class="ad2-action-title">Ver Usuarios</p>
                                <p class="ad2-action-desc">Lista completa de usuarios</p>
                            </div>
                            <div class="ad2-action-open">Abrir <span aria-hidden="true">→</span></div>
                        </button>

                        <button type="button" class="ad2-action ad2-action--purple ad2-action--modal" id="openAdminCreateService">
                            <div class="ad2-action-icon"><i class="bi bi-plus-lg" aria-hidden="true"></i></div>
                            <div>
                                <p class="ad2-action-title">Crear Servicio</p>
                                <p class="ad2-action-desc">Nuevo servicio veterinario</p>
                            </div>
                            <div class="ad2-action-open">Abrir <span aria-hidden="true">→</span></div>
                        </button>

                        <button type="button" class="ad2-action ad2-action--yellow" id="openAdminAssignRole">
                            <div class="ad2-action-icon"><i class="bi bi-shield-check" aria-hidden="true"></i></div>
                            <div>
                                <p class="ad2-action-title">Asignar Rol</p>
                                <p class="ad2-action-desc">Asignar permisos a usuarios</p>
                            </div>
                            <div class="ad2-action-open">Abrir <span aria-hidden="true">→</span></div>
                        </button>
                    </div>
                </section>

                <div class="ad2-bottom-grid" aria-label="Actividad y usuarios">
                    <section class="ad2-activity" aria-label="Actividad reciente">
                        <div class="ad2-activity-top">
                        <div>
                            <h2 class="ad2-section-title" style="margin-bottom:.15rem;">Actividad Reciente</h2>
                            <p class="ad2-section-sub" style="margin:0;">Últimos movimientos del sistema</p>
                        </div>

                        <div class="ad2-tabs" aria-label="Tabs">
                            <button type="button" class="ad2-tab ad2-tab--active">Actividad</button>
                            <button type="button" class="ad2-tab">Servicios</button>
                        </div>
                    </div>

                    <div class="ad2-activity-item">
                        <div class="ad2-activity-dot ad2-activity-dot--purple"><i class="bi bi-wrench" aria-hidden="true"></i></div>
                        <div class="ad2-activity-text">
                            <div class="ad2-activity-main">Servicio 'Guardería Premium' fue activado</div>
                            <div class="ad2-activity-sub"><i class="bi bi-clock" aria-hidden="true"></i> Hace 1 hora</div>
                        </div>
                    </div>

                    <div class="ad2-activity-item">
                        <div class="ad2-activity-dot ad2-activity-dot--yellow"><i class="bi bi-shield" aria-hidden="true"></i></div>
                        <div class="ad2-activity-text">
                            <div class="ad2-activity-main">Rol de Dr. Pedro Ruiz actualizado a Veterinario Senior</div>
                            <div class="ad2-activity-sub"><i class="bi bi-clock" aria-hidden="true"></i> Hace 2 horas</div>
                        </div>
                    </div>

                    <div class="ad2-activity-item ad2-activity-item--arrow">
                        <div class="ad2-activity-dot ad2-activity-dot--blue"><i class="bi bi-check2-circle" aria-hidden="true"></i></div>
                        <div class="ad2-activity-text">
                            <div class="ad2-activity-main">Backup automatico completado exitosamente</div>
                            <div class="ad2-activity-sub"><i class="bi bi-clock" aria-hidden="true"></i> Hace 3 horas</div>
                        </div>
                        <div class="ad2-activity-arrow">›</div>
                    </div>

                    <div class="ad2-activity-item">
                        <div class="ad2-activity-dot ad2-activity-dot--red"><i class="bi bi-calendar-event" aria-hidden="true"></i></div>
                        <div class="ad2-activity-text">
                            <div class="ad2-activity-main">Cita cancelada: Luna - Vacunacion</div>
                            <div class="ad2-activity-sub"><i class="bi bi-clock" aria-hidden="true"></i> Hace 4 horas</div>
                        </div>
                    </div>

                        <a href="#" class="ad2-activity-footer">Ver todo el historial</a>
                    </section>

                    <section class="ad2-users" aria-label="Usuarios del sistema">
                        <div class="ad2-users-top">
                        <div>
                            <h2 class="ad2-section-title" style="margin-bottom:.15rem;">Usuarios del Sistema</h2>
                            <p class="ad2-section-sub" style="margin:0;">Resumen de cuentas registradas</p>
                        </div>
                        <a href="{{ route('admin.users') }}" class="ad2-users-new">+ Nuevo</a>
                        </div>

                    <div class="ad2-users-summary" aria-label="Resumen">
                        <div class="ad2-users-summary-card">
                            <div class="ad2-users-count ad2-users-count--blue">{{ $stats['owners_count'] ?? 0 }}</div>
                            <div class="ad2-users-summary-label">Propietarios</div>
                        </div>
                        <div class="ad2-users-summary-card">
                            <div class="ad2-users-count ad2-users-count--purple">{{ $stats['vets_count'] ?? 0 }}</div>
                            <div class="ad2-users-summary-label">Cuidadores</div>
                        </div>
                        <div class="ad2-users-summary-card">
                            <div class="ad2-users-count ad2-users-count--yellow">{{ $stats['admins_count'] ?? 0 }}</div>
                            <div class="ad2-users-summary-label">Admins</div>
                        </div>
                    </div>

                    <div class="ad2-users-list" role="list">
                        @foreach (($recentUsers ?? collect())->take(4) as $u)
                            @php
                                $initial = mb_strtoupper(mb_substr($u->name ?? 'U', 0, 1));
                                $rolLabel = match($u->rol) {
                                    'admin' => 'Administrador',
                                    'empleado' => 'Cuidador',
                                    'dueno' => 'Propietario',
                                    'padrino' => 'Padrino',
                                    'entrenador' => 'Entrenador',
                                    default => ucfirst((string) ($u->rol ?? 'Sin rol')),
                                };
                                $isActive = !is_null($u->email_verified_at);
                            @endphp
                            <div class="ad2-user-row" role="listitem">
                                <div class="ad2-user-avatar">{{ $initial }}</div>
                                <div class="ad2-user-meta">
                                    <div class="ad2-user-name">{{ $u->name }}</div>
                                    <div class="ad2-user-email">{{ $u->email }}</div>
                                </div>
                                <div class="ad2-user-right">
                                    <span class="ad2-user-role-pill">{{ $rolLabel }}</span>
                                    <span class="ad2-user-status {{ $isActive ? 'ad2-user-status--on' : '' }}" aria-label="{{ $isActive ? 'Activo' : 'Inactivo' }}"></span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                        <a href="{{ route('admin.users') }}" class="ad2-users-footer">Gestionar todos los usuarios</a>
                    </section>
                </div>
            </main>
                <div class="ad2-modal" id="adminRegisterUserModal" aria-hidden="true">
                    <div class="ad2-modal-backdrop" data-close="true"></div>
                    <div class="ad2-modal-card" role="dialog" aria-modal="true" aria-labelledby="ad2ModalTitle">
                        <div class="ad2-modal-head">
                            <h2 class="ad2-modal-title" id="ad2ModalTitle">Registrar Nuevo Usuario</h2>
                            <button type="button" class="ad2-modal-close" id="closeAdminRegisterUser" aria-label="Cerrar">×</button>
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

                <div class="ad2-modal" id="adminCreateServiceModal" aria-hidden="true">
                    <div class="ad2-modal-backdrop" data-close="true"></div>
                    <div class="ad2-modal-card" role="dialog" aria-modal="true" aria-labelledby="ad2CreateServiceTitle">
                        <div class="ad2-modal-head">
                            <h2 class="ad2-modal-title" id="ad2CreateServiceTitle">Nuevo Servicio</h2>
                            <button type="button" class="ad2-modal-close" id="closeAdminCreateService" aria-label="Cerrar">×</button>
                        </div>
                        <div class="ad2-modal-body">
                            <form class="ad2-modal-form" autocomplete="off">
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
                                        <input class="ad2-input" type="text" name="duration" placeholder="30 min" />
                                    </label>
                                </div>

                                <label class="ad2-field">
                                    <span class="ad2-label">Categoria</span>
                                    <select class="ad2-select" name="category">
                                        <option value="Medicina">Medicina</option>
                                        <option value="Vacunacion">Vacunacion</option>
                                        <option value="Grooming">Grooming</option>
                                        <option value="Guarderia">Guarderia</option>
                                    </select>
                                </label>

                                <button type="button" class="ad2-submit" id="submitAdminCreateService">Crear Servicio</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="ad2-modal" id="adminUsersModal" aria-hidden="true">
                    <div class="ad2-modal-backdrop" data-close="true"></div>
                    <div class="ad2-modal-card" role="dialog" aria-modal="true" aria-labelledby="ad2UsersModalTitle">
                        <div class="ad2-modal-head">
                            <h2 class="ad2-modal-title" id="ad2UsersModalTitle">Usuarios del Sistema</h2>
                            <button type="button" class="ad2-modal-close" id="closeAdminUsersModal" aria-label="Cerrar">×</button>
                        </div>
                        <div class="ad2-modal-body">
                            <div class="ad2-um-list" role="list">
                                @foreach (($users ?? []) as $u)
                                    <div class="ad2-um-row" role="listitem">
                                        <div class="ad2-um-avatar">{{ mb_substr($u->name ?? 'U', 0, 1) }}</div>
                                        <div class="ad2-um-meta">
                                            <div class="ad2-um-name">{{ $u->name }}</div>
                                            <div class="ad2-um-email">{{ $u->email }}</div>
                                        </div>
                                        <div class="ad2-um-right">
                                            <span class="ad2-um-role">
                                                @php
                                                    $rolLabel = match($u->rol) {
                                                        'admin' => 'Administrador',
                                                        'empleado' => 'Cuidador',
                                                        'dueno' => 'Propietario',
                                                        'padrino' => 'Padrino',
                                                        default => ucfirst((string) $u->rol),
                                                    };
                                                @endphp
                                                {{ $rolLabel }}
                                            </span>
                                            <span class="ad2-um-status ad2-um-status--on" aria-label="Activo"></span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ad2-modal" id="adminAssignRoleModal" aria-hidden="true">
                    <div class="ad2-modal-backdrop" data-close="true"></div>
                    <div class="ad2-modal-card" role="dialog" aria-modal="true" aria-labelledby="ad2AssignRoleTitle">
                        <div class="ad2-modal-head">
                            <h2 class="ad2-modal-title" id="ad2AssignRoleTitle">Asignar Rol</h2>
                            <button type="button" class="ad2-modal-close" id="closeAdminAssignRole" aria-label="Cerrar">×</button>
                        </div>
                        <div class="ad2-modal-body">
                            <form class="ad2-modal-form" action="{{ route('admin.users.assignRole') }}" method="POST" autocomplete="off">
                                @csrf

                                <input type="hidden" name="user_id" id="adminAssignRoleUserId" value="" />

                                <label class="ad2-field">
                                    <span class="ad2-label">Buscar usuario</span>
                                    <input class="ad2-input" type="text" id="adminAssignRoleSearch" placeholder="Escribe un nombre..." />
                                </label>

                                <div class="ad2-ar-current" aria-label="Rol actual">
                                    Rol actual: <span class="ad2-ar-current-pill" id="adminAssignRoleCurrentRole">—</span>
                                </div>

                                <div class="ad2-ar-users" id="adminAssignRoleUsers" aria-label="Usuarios registrados">
                                    @foreach (collect($users ?? [])->take(6) as $u)
                                        @php
                                            $rolLabel = match($u->rol) {
                                                'admin' => 'Administrador',
                                                'empleado' => 'Cuidador',
                                                'dueno' => 'Propietario',
                                                'padrino' => 'Padrino',
                                                'entrenador' => 'Entrenador',
                                                default => ucfirst((string) ($u->rol ?? 'Sin rol')),
                                            };
                                        @endphp
                                        <button type="button" class="ad2-ar-user" data-user-id="{{ $u->id }}" data-user-name="{{ mb_strtolower($u->name ?? '') }}" data-user-role-label="{{ $rolLabel }}" data-user-role-code="{{ $u->rol }}">
                                            <span class="ad2-ar-avatar">{{ mb_strtoupper(mb_substr($u->name ?? 'U', 0, 1)) }}</span>
                                            <span class="ad2-ar-name">{{ $u->name }}</span>
                                        </button>
                                    @endforeach
                                </div>

                                <label class="ad2-field">
                                    <span class="ad2-label">Nuevo rol</span>
                                    <select class="ad2-select" id="adminAssignRoleNewRole" name="rol">
                                        <option value="dueno">Propietario</option>
                                        <option value="empleado">Cuidador</option>
                                        <option value="padrino">Padrino</option>
                                        <option value="entrenador">Entrenador</option>
                                        <option value="admin">Administrador</option>
                                    </select>
                                </label>

                                <button type="button" class="ad2-submit" id="submitAdminAssignRole">Asignar Rol</button>
                            </form>
                        </div>
                    </div>
                </div>

                @if (session('status'))
                    <div class="ad2-success" id="adminSuccessModal" aria-hidden="true">
                        <div class="ad2-success-backdrop" data-close="true"></div>
                        <div class="ad2-success-card" role="dialog" aria-modal="true" aria-labelledby="ad2SuccessTitle">
                            <div class="ad2-success-head">
                                <h2 class="ad2-success-title" id="ad2SuccessTitle">Registrar Nuevo Usuario</h2>
                                <button type="button" class="ad2-success-close" id="closeAdminSuccess" aria-label="Cerrar">×</button>
                            </div>
                            <div class="ad2-success-body">
                                <div class="ad2-success-icon" aria-hidden="true">
                                    <span class="ad2-success-icon-inner">✓</span>
                                </div>
                                <div class="ad2-success-main">Operacion exitosa</div>
                                <div class="ad2-success-sub">La accion fue realizada correctamente</div>
                            </div>
                        </div>
                    </div>
                @endif

        <script>
            (function () {
                const modal = document.getElementById('adminRegisterUserModal');
                const openBtn = document.getElementById('openAdminRegisterUser');
                const closeBtn = document.getElementById('closeAdminRegisterUser');
                const usersModal = document.getElementById('adminUsersModal');
                const openUsersBtn = document.getElementById('openAdminUsersModal');
                const closeUsersBtn = document.getElementById('closeAdminUsersModal');
                const successModal = document.getElementById('adminSuccessModal');
                const successCloseBtn = document.getElementById('closeAdminSuccess');
                const createServiceModal = document.getElementById('adminCreateServiceModal');
                const openCreateServiceBtn = document.getElementById('openAdminCreateService');
                const closeCreateServiceBtn = document.getElementById('closeAdminCreateService');
                const assignRoleModal = document.getElementById('adminAssignRoleModal');
                const openAssignRoleBtn = document.getElementById('openAdminAssignRole');
                const closeAssignRoleBtn = document.getElementById('closeAdminAssignRole');
                const assignRoleSearch = document.getElementById('adminAssignRoleSearch');
                const assignRoleUsers = document.getElementById('adminAssignRoleUsers');
                const assignRoleForm = assignRoleModal ? assignRoleModal.querySelector('form') : null;
                const assignRoleUserId = document.getElementById('adminAssignRoleUserId');
                const assignRoleNewRole = document.getElementById('adminAssignRoleNewRole');
                const submitAssignRoleBtn = document.getElementById('submitAdminAssignRole');
                const assignRoleCurrentRole = document.getElementById('adminAssignRoleCurrentRole');

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

                openBtn?.addEventListener('click', openModal);
                closeBtn?.addEventListener('click', closeModal);

                modal?.addEventListener('click', (e) => {
                    const target = e.target;
                    if (target && target.dataset && target.dataset.close === 'true') {
                        closeModal();
                    }
                });

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && modal?.classList.contains('ad2-modal--open')) {
                        closeModal();
                    }
                });

                function openUsersModal() {
                    if (!usersModal) return;
                    usersModal.classList.add('ad2-modal--open');
                    usersModal.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';
                }

                function closeUsersModal() {
                    if (!usersModal) return;
                    usersModal.classList.remove('ad2-modal--open');
                    usersModal.setAttribute('aria-hidden', 'true');
                    document.body.style.overflow = '';
                }

                openUsersBtn?.addEventListener('click', openUsersModal);
                closeUsersBtn?.addEventListener('click', closeUsersModal);

                usersModal?.addEventListener('click', (e) => {
                    const target = e.target;
                    if (target && target.dataset && target.dataset.close === 'true') {
                        closeUsersModal();
                    }
                });

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && usersModal?.classList.contains('ad2-modal--open')) {
                        closeUsersModal();
                    }
                });

                function openCreateServiceModal() {
                    if (!createServiceModal) return;
                    createServiceModal.classList.add('ad2-modal--open');
                    createServiceModal.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';
                }

                function closeCreateServiceModal() {
                    if (!createServiceModal) return;
                    createServiceModal.classList.remove('ad2-modal--open');
                    createServiceModal.setAttribute('aria-hidden', 'true');
                    document.body.style.overflow = '';
                }

                openCreateServiceBtn?.addEventListener('click', openCreateServiceModal);
                closeCreateServiceBtn?.addEventListener('click', closeCreateServiceModal);

                createServiceModal?.addEventListener('click', (e) => {
                    const target = e.target;
                    if (target && target.dataset && target.dataset.close === 'true') {
                        closeCreateServiceModal();
                    }
                });

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && createServiceModal?.classList.contains('ad2-modal--open')) {
                        closeCreateServiceModal();
                    }
                });

                function openAssignRoleModal() {
                    if (!assignRoleModal) return;
                    assignRoleModal.classList.add('ad2-modal--open');
                    assignRoleModal.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';
                    assignRoleSearch?.focus();
                }

                function closeAssignRoleModal() {
                    if (!assignRoleModal) return;
                    assignRoleModal.classList.remove('ad2-modal--open');
                    assignRoleModal.setAttribute('aria-hidden', 'true');
                    document.body.style.overflow = '';
                }

                function filterAssignRoleUsers() {
                    const q = (assignRoleSearch?.value || '').trim().toLowerCase();

                    if (assignRoleUsers) {
                        Array.from(assignRoleUsers.querySelectorAll('.ad2-ar-user')).forEach((btn) => {
                            const name = (btn.getAttribute('data-user-name') || '').toLowerCase();
                            const show = q === '' || name.includes(q);
                            btn.style.display = show ? '' : 'none';
                        });
                    }
                }

                function setSelectedAssignRoleUser(btn) {
                    if (!btn || !assignRoleUsers) return;
                    const id = btn.getAttribute('data-user-id') || '';
                    const roleLabel = btn.getAttribute('data-user-role-label') || '—';
                    const roleCode = btn.getAttribute('data-user-role-code') || '';
                    if (assignRoleUserId) {
                        assignRoleUserId.value = id;
                    }

                    if (assignRoleCurrentRole) {
                        assignRoleCurrentRole.textContent = roleLabel;
                    }

                    if (roleCode && assignRoleNewRole) {
                        assignRoleNewRole.value = roleCode;
                    }

                    Array.from(assignRoleUsers.querySelectorAll('.ad2-ar-user')).forEach((b) => {
                        b.classList.toggle('ad2-ar-user--selected', b === btn);
                    });

                    submitAssignRoleBtn?.removeAttribute('disabled');
                }

                openAssignRoleBtn?.addEventListener('click', () => {
                    openAssignRoleModal();
                    filterAssignRoleUsers();
                });
                closeAssignRoleBtn?.addEventListener('click', closeAssignRoleModal);

                assignRoleModal?.addEventListener('click', (e) => {
                    const target = e.target;
                    if (target && target.dataset && target.dataset.close === 'true') {
                        closeAssignRoleModal();
                    }
                });

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && assignRoleModal?.classList.contains('ad2-modal--open')) {
                        closeAssignRoleModal();
                    }
                });

                assignRoleSearch?.addEventListener('input', filterAssignRoleUsers);

                assignRoleUsers?.addEventListener('click', (e) => {
                    const t = e.target;
                    const btn = t && t.closest ? t.closest('.ad2-ar-user') : null;
                    if (!btn) return;
                    setSelectedAssignRoleUser(btn);
                });

                submitAssignRoleBtn?.addEventListener('click', () => {
                    const uid = (assignRoleUserId?.value || '').trim();
                    const role = (assignRoleNewRole?.value || '').trim();

                    if (!uid) {
                        alert('Selecciona un usuario');
                        return;
                    }

                    if (!role) {
                        alert('Selecciona un rol');
                        return;
                    }

                    assignRoleForm?.submit();
                });

                function openSuccess() {
                    if (!successModal) return;
                    successModal.classList.add('ad2-success--open');
                    successModal.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';
                }

                function closeSuccess() {
                    if (!successModal) return;
                    successModal.classList.remove('ad2-success--open');
                    successModal.setAttribute('aria-hidden', 'true');
                    document.body.style.overflow = '';
                }

                successCloseBtn?.addEventListener('click', closeSuccess);

                successModal?.addEventListener('click', (e) => {
                    const target = e.target;
                    if (target && target.dataset && target.dataset.close === 'true') {
                        closeSuccess();
                    }
                });

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && successModal?.classList.contains('ad2-success--open')) {
                        closeSuccess();
                    }
                });

                openSuccess();
            })();
        </script>
    </body>
</html>
