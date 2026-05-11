<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Configuracion</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('css/Admin/admin-dashboard.css') }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/admin-sidebar-extras.css') }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/dashboard-admin-v2.css') }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/configuracion.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    </head>
    <body>
        @php
            use Illuminate\Support\Str;
        @endphp
        <div class="admin-layout">
            <aside class="admin-sidebar">
                <div class="admin-brand">
                    <div class="admin-logo"><i class="bi bi-paw" aria-hidden="true"></i></div>
                    <div class="admin-brand-text">
                        <span class="admin-brand-title">MAS QUE PERROS</span>
                        <span class="admin-brand-subtitle">Panel Administrativo</span>
                    </div>
                </div>

                <p class="admin-sidebar-section">ADMINISTRACION</p>

                <nav class="admin-menu">
                    <a href="{{ route('admin.dashboard') }}" class="admin-menu-item {{ request()->routeIs('admin.dashboard') ? 'admin-menu-item--active' : '' }}">
                        <span class="admin-menu-left">
                            <i class="admin-menu-icon bi bi-grid-1x2-fill" aria-hidden="true"></i>
                            <span>Dashboard</span>
                        </span>
                        <span class="admin-menu-right"></span>
                    </a>
                    <a href="{{ route('admin.users') }}" class="admin-menu-item {{ request()->routeIs('admin.users') ? 'admin-menu-item--active' : '' }}">
                        <span class="admin-menu-left">
                            <i class="admin-menu-icon bi bi-people-fill" aria-hidden="true"></i>
                            <span>Gestión de usuarios</span>
                        </span>
                        <span class="admin-menu-right">
                            <span class="admin-menu-badge">5</span>
                        </span>
                    </a>
                    <a href="{{ route('admin.services') }}" class="admin-menu-item {{ request()->routeIs('admin.services') ? 'admin-menu-item--active' : '' }}">
                        <span class="admin-menu-left">
                            <i class="admin-menu-icon bi bi-heart-pulse-fill" aria-hidden="true"></i>
                            <span>Gestión de servicios</span>
                        </span>
                        <span class="admin-menu-right">
                            <span class="admin-menu-badge">6</span>
                        </span>
                    </a>
                    <a href="{{ route('admin.pets') }}" class="admin-menu-item {{ request()->routeIs('admin.pets') ? 'admin-menu-item--active' : '' }}">
                        <span class="admin-menu-left">
                            <i class="admin-menu-icon bi bi-paw-fill" aria-hidden="true"></i>
                            <span>Gestión de mascotas</span>
                        </span>
                        <span class="admin-menu-right"></span>
                    </a>
                    <a href="{{ route('admin.settings') }}" class="admin-menu-item {{ request()->routeIs('admin.settings') ? 'admin-menu-item--active' : '' }}">
                        <span class="admin-menu-left">
                            <i class="admin-menu-icon bi bi-gear-fill" aria-hidden="true"></i>
                            <span>Configuracion</span>
                        </span>
                        <span class="admin-menu-right"></span>
                    </a>
                </nav>

                <div class="admin-sidebar-spacer"></div>

                <div class="admin-sidebar-divider"></div>

                <form method="POST" action="{{ route('logout') }}" class="admin-logout">
                    @csrf
                    <button type="submit">
                        <span class="admin-logout-icon">⤴</span>
                        <span>Cerrar sesion</span>
                    </button>
                </form>
            </aside>

            <main class="admin-main">
                <header class="cfg-top">
                    <div class="cfg-top-left">
                        <h1 class="cfg-title">Configuracion</h1>
                        <p class="cfg-subtitle">Ajustes generales del sistema</p>
                    </div>
                </header>

                @if (session('success'))
                    <div class="cfg-toast" id="cfgToast" role="status" aria-live="polite">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('admin.settings.update') }}">
                    @csrf
                    <div class="cfg-save">
                        <button type="submit" class="cfg-save-btn">
                            <i class="bi bi-floppy" aria-hidden="true"></i>
                            <span>Guardar cambios</span>
                        </button>
                    </div>

                <section class="cfg-grid">
                    <article class="cfg-card">
                        <header class="cfg-card-head">
                            <div class="cfg-ico cfg-ico--purple"><i class="bi bi-building" aria-hidden="true"></i></div>
                            <div class="cfg-card-meta">
                                <div class="cfg-card-title">Informacion del negocio</div>
                                <div class="cfg-card-sub">Datos principales</div>
                            </div>
                        </header>

                        <div class="cfg-fields">
                            <div class="cfg-field">
                                <label class="cfg-label">Nombre del negocio</label>
                                <input class="cfg-input" type="text" name="nombre_negocio" value="{{ old('nombre_negocio', (string) ($settings->nombre_negocio ?? '')) }}" />
                            </div>

                            <div class="cfg-field">
                                <label class="cfg-label">Direccion</label>
                                <input class="cfg-input" type="text" name="direccion" value="{{ old('direccion', (string) ($settings->direccion ?? '')) }}" />
                            </div>

                            <div class="cfg-row">
                                <div class="cfg-field">
                                    <label class="cfg-label">Telefono</label>
                                    <input class="cfg-input" type="text" name="telefono" value="{{ old('telefono', (string) ($settings->telefono ?? '')) }}" />
                                </div>
                                <div class="cfg-field">
                                    <label class="cfg-label">Email</label>
                                    <input class="cfg-input" type="email" name="email" value="{{ old('email', (string) ($settings->email ?? '')) }}" />
                                </div>
                            </div>
                        </div>
                    </article>

                    <article class="cfg-card">
                        <header class="cfg-card-head">
                            <div class="cfg-card-meta">
                                <div class="cfg-card-title">Horario de atencion</div>
                                <div class="cfg-card-sub">Configura los horarios de apertura</div>
                            </div>
                        </header>

                        <div class="cfg-fields">
                            <div class="cfg-row">
                                <div class="cfg-field">
                                    <label class="cfg-label">Hora apertura</label>
                                    <div class="cfg-input-ico">
                                        <input class="cfg-input" type="time" name="hora_apertura" value="{{ old('hora_apertura', (string) ($settings->hora_apertura ?? '')) }}" />
                                    </div>
                                </div>
                                <div class="cfg-field">
                                    <label class="cfg-label">Hora cierre</label>
                                    <div class="cfg-input-ico">
                                        <input class="cfg-input" type="time" name="hora_cierre" value="{{ old('hora_cierre', (string) ($settings->hora_cierre ?? '')) }}" />
                                    </div>
                                </div>
                            </div>

                            <div class="cfg-toggle-card">
                                <div class="cfg-toggle-text">
                                    <div class="cfg-toggle-title">Atencion los fines de semana</div>
                                    <div class="cfg-toggle-sub">Habilitar sabados y domingos</div>
                                </div>
                                <label class="cfg-switch">
                                    <input type="checkbox" name="atiende_fines_semana" value="1" {{ old('atiende_fines_semana', (bool) ($settings->atiende_fines_semana ?? false)) ? 'checked' : '' }} />
                                    <span class="cfg-slider"></span>
                                </label>
                            </div>

                            <div class="cfg-note">
                                <div class="cfg-note-title"><i class="bi bi-people" aria-hidden="true"></i><span>Horario guarderia</span></div>
                                <div class="cfg-note-body">La guarderia opera de lunes a sabado, de 7:00 AM a 6:00 PM. Los domingos solo atiende recogidas.</div>
                            </div>
                        </div>
                    </article>

                    <article class="cfg-card">
                        <header class="cfg-card-head">
                            <div class="cfg-ico cfg-ico--green"><i class="bi bi-bell" aria-hidden="true"></i></div>
                            <div class="cfg-card-meta">
                                <div class="cfg-card-title">Notificaciones</div>
                                <div class="cfg-card-sub">Configura como recibir alertas</div>
                            </div>
                        </header>

                        <div class="cfg-fields">
                            <div class="cfg-toggle-list">
                                <div class="cfg-toggle-card cfg-toggle-card--list">
                                    <div class="cfg-toggle-text">
                                        <div class="cfg-toggle-title">Notificaciones por email</div>
                                        <div class="cfg-toggle-sub">Recibir alertas en tu correo electronico</div>
                                    </div>
                                    <label class="cfg-switch">
                                        <input type="checkbox" name="notificaciones_email" value="1" {{ old('notificaciones_email', (bool) ($settings->notificaciones_email ?? false)) ? 'checked' : '' }} />
                                        <span class="cfg-slider"></span>
                                    </label>
                                </div>

                                <div class="cfg-toggle-card cfg-toggle-card--list">
                                    <div class="cfg-toggle-text">
                                        <div class="cfg-toggle-title">Notificaciones por SMS</div>
                                        <div class="cfg-toggle-sub">Recibir alertas por mensaje de texto</div>
                                    </div>
                                    <label class="cfg-switch">
                                        <input type="checkbox" name="notificaciones_sms" value="1" {{ old('notificaciones_sms', (bool) ($settings->notificaciones_sms ?? false)) ? 'checked' : '' }} />
                                        <span class="cfg-slider"></span>
                                    </label>
                                </div>

                                <div class="cfg-toggle-card cfg-toggle-card--list">
                                    <div class="cfg-toggle-text">
                                        <div class="cfg-toggle-title">Recordatorio de citas</div>
                                        <div class="cfg-toggle-sub">Enviar recordatorios automaticos antes de cada cita</div>
                                    </div>
                                    <label class="cfg-switch">
                                        <input type="checkbox" name="recordatorio_citas" value="1" {{ old('recordatorio_citas', (bool) ($settings->recordatorio_citas ?? false)) ? 'checked' : '' }} />
                                        <span class="cfg-slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </article>

                    <article class="cfg-card">
                        <header class="cfg-card-head">
                            <div class="cfg-ico cfg-ico--red"><i class="bi bi-shield" aria-hidden="true"></i></div>
                            <div class="cfg-card-meta">
                                <div class="cfg-card-title">Seguridad</div>
                                <div class="cfg-card-sub">Opciones de seguridad del sistema</div>
                            </div>
                        </header>

                        <div class="cfg-fields">
                            <div class="cfg-toggle-card cfg-toggle-card--list">
                                <div class="cfg-toggle-text">
                                    <div class="cfg-toggle-title">Autenticacion de dos factores</div>
                                    <div class="cfg-toggle-sub">Requerir codigo adicional al iniciar sesion</div>
                                </div>
                                <label class="cfg-switch cfg-switch--off">
                                    <input type="checkbox" name="autenticacion_dos_factores" value="1" {{ old('autenticacion_dos_factores', (bool) ($settings->autenticacion_dos_factores ?? false)) ? 'checked' : '' }} />
                                    <span class="cfg-slider"></span>
                                </label>
                            </div>

                            <div class="cfg-field">
                                <label class="cfg-label">Cierre automatico de sesion (minutos)</label>
                                @php
                                    $sessionMinutes = (int) (old('cierre_sesion_minutos', (int) ($settings->cierre_sesion_minutos ?? 30)));
                                @endphp
                                <select class="cfg-input" name="cierre_sesion_minutos">
                                    <option value="30" {{ $sessionMinutes === 30 ? 'selected' : '' }}>30 minutos</option>
                                    <option value="15" {{ $sessionMinutes === 15 ? 'selected' : '' }}>15 minutos</option>
                                    <option value="60" {{ $sessionMinutes === 60 ? 'selected' : '' }}>60 minutos</option>
                                </select>
                            </div>
                        </div>
                    </article>
                </section>
                </form>
            </main>
        </div>

        <script>
            (function () {
                const toast = document.getElementById('cfgToast');
                if (!toast) return;
                setTimeout(() => {
                    toast.classList.add('cfg-toast--hide');
                }, 2200);
            })();
        </script>
    </body>
</html>
