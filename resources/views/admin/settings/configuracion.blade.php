@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/Admin/configuracion.css') }}">

<div class="conf-container">
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        <header class="conf-header">
            <div class="conf-title-group">
                <h1>Configuracion</h1>
                <p>Ajustes generales del sistema</p>
            </div>
            <button type="submit" class="conf-save-btn">
                <i class="bi bi-floppy"></i>
                Guardar cambios
            </button>
        </header>

        <div class="conf-layout">
            <!-- Sidebar de navegación -->
            <aside class="conf-nav">
                <a href="#" class="conf-nav-item conf-nav-item--active" data-conf-tab="general">
                    <div class="conf-nav-icon"><i class="bi bi-house-door"></i></div>
                    <div class="conf-nav-text">
                        <span class="conf-nav-title">General</span>
                        <span class="conf-nav-sub">Nombre del negoci...</span>
                    </div>
                    <i class="bi bi-chevron-right conf-nav-chevron"></i>
                </a>
                <a href="#" class="conf-nav-item" data-conf-tab="notificaciones">
                    <div class="conf-nav-icon"><i class="bi bi-bell"></i></div>
                    <div class="conf-nav-text">
                        <span class="conf-nav-title">Notificaciones</span>
                        <span class="conf-nav-sub">Configura alertas por...</span>
                    </div>
                </a>
                <a href="#" class="conf-nav-item" data-conf-tab="horarios">
                    <div class="conf-nav-icon"><i class="bi bi-clock"></i></div>
                    <div class="conf-nav-text">
                        <span class="conf-nav-title">Horarios</span>
                        <span class="conf-nav-sub">Horarios de atencion y...</span>
                    </div>
                </a>
            </aside>

            <!-- Panel principal -->
            <main class="conf-main-card">
                <!-- GENERAL -->
                <section class="conf-pane conf-pane--active" data-conf-pane="general">
                    <h2 class="conf-section-title">Informacion del Negocio</h2>
                    <p class="conf-section-sub">Datos principales de tu clinica veterinaria</p>

                    <div class="conf-logo-box">
                        <div class="conf-logo-preview">
                            <i class="bi bi-paw-fill"></i>
                        </div>
                        <div class="conf-logo-info">
                            <h4>Logo del negocio</h4>
                            <p>PNG o JPG, maximo 2MB</p>
                            <button type="button" class="conf-logo-btn">Cambiar logo</button>
                        </div>
                    </div>

                    <div class="conf-grid">
                        <div class="conf-field">
                            <label class="conf-label">Nombre del negocio</label>
                            <input type="text" name="nombre_negocio" class="conf-input" 
                                value="{{ old('nombre_negocio', $settings->nombre_negocio ?? '') }}" 
                                placeholder="Ej: Mas que Perros">
                        </div>
                        <div class="conf-field">
                            <label class="conf-label">Slogan</label>
                            <input type="text" name="slogan" class="conf-input" 
                                value="{{ old('slogan', $settings->slogan ?? '') }}" 
                                placeholder="Ej: Tu perro feliz, tu tranquilo">
                        </div>
                    </div>

                    <div class="conf-grid">
                        <div class="conf-field">
                            <label class="conf-label">Email</label>
                            <div class="conf-input-wrap">
                                <i class="bi bi-envelope conf-input-icon"></i>
                                <input type="email" name="email" class="conf-input conf-input--has-icon" 
                                    value="{{ old('email', $settings->email ?? '') }}" 
                                    placeholder="contacto@masqueperros.com">
                            </div>
                        </div>
                        <div class="conf-field">
                            <label class="conf-label">Telefono</label>
                            <input type="text" name="telefono" class="conf-input" 
                                value="{{ old('telefono', $settings->telefono ?? '') }}" 
                                placeholder="+57 300 000 0000">
                        </div>
                    </div>

                    <div class="conf-field conf-field--full">
                        <label class="conf-label">Direccion</label>
                        <div class="conf-input-wrap">
                            <i class="bi bi-geo-alt conf-input-icon"></i>
                            <input type="text" name="direccion" class="conf-input conf-input--has-icon" 
                                value="{{ old('direccion', $settings->direccion ?? '') }}" 
                                placeholder="Calle 123 #45-67, Bogota, Colombia">
                        </div>
                    </div>
                </section>

                <!-- NOTIFICACIONES -->
                <section class="conf-pane" data-conf-pane="notificaciones">
                    <h2 class="conf-section-title">Notificaciones</h2>
                    <p class="conf-section-sub">Gestiona como y cuando recibes alertas</p>

                    <div class="conf-block-title">CANALES</div>
                    <div class="conf-switch-list">
                        <div class="conf-switch-row">
                            <div class="conf-switch-text">
                                <div class="conf-switch-title">Notificaciones por email</div>
                                <div class="conf-switch-sub">Recibe alertas en tu correo electronico</div>
                            </div>
                            <label class="conf-switch">
                                <input type="checkbox" name="notificaciones_email" value="1" {{ old('notificaciones_email', (bool) ($settings->notificaciones_email ?? false)) ? 'checked' : '' }}>
                                <span class="conf-slider"></span>
                            </label>
                        </div>

                        <div class="conf-switch-row">
                            <div class="conf-switch-text">
                                <div class="conf-switch-title">Notificaciones push</div>
                                <div class="conf-switch-sub">Alertas en el navegador en tiempo real</div>
                            </div>
                            <label class="conf-switch">
                                <input type="checkbox" name="notificaciones_push" value="1" {{ old('notificaciones_push', (bool) ($settings->notificaciones_push ?? false)) ? 'checked' : '' }}>
                                <span class="conf-slider"></span>
                            </label>
                        </div>
                    </div>

                    <div class="conf-block-title conf-block-title--mt">EVENTOS</div>
                    <div class="conf-switch-list">
                        <div class="conf-switch-row">
                            <div class="conf-switch-text">
                                <div class="conf-switch-title">Nuevos usuarios registrados</div>
                            </div>
                            <label class="conf-switch">
                                <input type="checkbox" name="evento_nuevos_usuarios" value="1" {{ old('evento_nuevos_usuarios', (bool) ($settings->evento_nuevos_usuarios ?? false)) ? 'checked' : '' }}>
                                <span class="conf-slider"></span>
                            </label>
                        </div>

                        <div class="conf-switch-row">
                            <div class="conf-switch-text">
                                <div class="conf-switch-title">Citas agendadas o canceladas</div>
                            </div>
                            <label class="conf-switch">
                                <input type="checkbox" name="evento_citas" value="1" {{ old('evento_citas', (bool) ($settings->evento_citas ?? false)) ? 'checked' : '' }}>
                                <span class="conf-slider"></span>
                            </label>
                        </div>

                        <div class="conf-switch-row">
                            <div class="conf-switch-text">
                                <div class="conf-switch-title">Alertas del sistema</div>
                            </div>
                            <label class="conf-switch">
                                <input type="checkbox" name="evento_alertas_sistema" value="1" {{ old('evento_alertas_sistema', (bool) ($settings->evento_alertas_sistema ?? false)) ? 'checked' : '' }}>
                                <span class="conf-slider"></span>
                            </label>
                        </div>
                    </div>
                </section>

                <!-- HORARIOS -->
                <section class="conf-pane" data-conf-pane="horarios">
                    <h2 class="conf-section-title">Horarios de Atencion</h2>
                    <p class="conf-section-sub">Configura los dias y horas disponibles para citas</p>

                    <div class="conf-schedule">
                        @php
                            $days = [
                                ['key' => 'lunes', 'label' => 'Lunes'],
                                ['key' => 'martes', 'label' => 'Martes'],
                                ['key' => 'miercoles', 'label' => 'Miercoles'],
                                ['key' => 'jueves', 'label' => 'Jueves'],
                                ['key' => 'viernes', 'label' => 'Viernes'],
                                ['key' => 'sabado', 'label' => 'Sabado'],
                                ['key' => 'domingo', 'label' => 'Domingo'],
                            ];
                        @endphp

                        @foreach ($days as $d)
                            @php
                                $activeField = $d['key'] . '_activo';
                                $startField = $d['key'] . '_inicio';
                                $endField = $d['key'] . '_fin';
                                $isActive = (bool) old($activeField, (bool) ($settings->{$activeField} ?? false));
                                $startVal = old($startField, (string) ($settings->{$startField} ?? ''));
                                $endVal = old($endField, (string) ($settings->{$endField} ?? ''));
                            @endphp

                            <div class="conf-day-row {{ $isActive ? '' : 'conf-day-row--off' }}" data-conf-day-row>
                                <label class="conf-switch">
                                    <input type="checkbox" name="{{ $activeField }}" value="1" {{ $isActive ? 'checked' : '' }} data-conf-day-toggle>
                                    <span class="conf-slider"></span>
                                </label>

                                <div class="conf-day-label">{{ $d['label'] }}</div>

                                <div class="conf-time">
                                    <i class="bi bi-clock"></i>
                                    <input type="time" name="{{ $startField }}" value="{{ $startVal }}" {{ $isActive ? '' : 'disabled' }}>
                                </div>

                                <div class="conf-time-sep">a</div>

                                <div class="conf-time">
                                    <i class="bi bi-clock"></i>
                                    <input type="time" name="{{ $endField }}" value="{{ $endVal }}" {{ $isActive ? '' : 'disabled' }}>
                                </div>

                                @if ($d['key'] === 'domingo' && ! $isActive)
                                    <div class="conf-day-closed">Cerrado</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>
            </main>
        </div>
    </form>
</div>

<script>
    (function () {
        const navItems = Array.from(document.querySelectorAll('[data-conf-tab]'));
        const panes = Array.from(document.querySelectorAll('[data-conf-pane]'));

        function activate(tab) {
            navItems.forEach((a) => a.classList.toggle('conf-nav-item--active', a.getAttribute('data-conf-tab') === tab));
            panes.forEach((p) => p.classList.toggle('conf-pane--active', p.getAttribute('data-conf-pane') === tab));
        }

        navItems.forEach((a) => {
            a.addEventListener('click', (e) => {
                e.preventDefault();
                activate(a.getAttribute('data-conf-tab'));
            });
        });

        const dayToggles = Array.from(document.querySelectorAll('[data-conf-day-toggle]'));
        dayToggles.forEach((toggle) => {
            toggle.addEventListener('change', () => {
                const row = toggle.closest('[data-conf-day-row]');
                if (!row) return;
                const inputs = Array.from(row.querySelectorAll('input[type="time"]'));
                inputs.forEach((i) => (i.disabled = !toggle.checked));
                row.classList.toggle('conf-day-row--off', !toggle.checked);
            });
        });
    })();
</script>
@endsection

