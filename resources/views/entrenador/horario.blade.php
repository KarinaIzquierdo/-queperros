<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Mi Horario</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('css/shared/mq-topbar.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dueño/panel.css') }}">
        <link rel="stylesheet" href="{{ asset('css/entrenador/dashboardentrenador.css') }}">
        <link rel="stylesheet" href="{{ asset('css/entrenador/horario.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="{{ asset('css/Admin/admin-sidebar-extras.css') }}?v={{ time() }}">
    </head>
    <body>
        @include('partials.page-loader')
        @php
            use Illuminate\Support\Str;
        @endphp
        <div class="mq-dashboard et-dashboard">
            @include('partials.entrenador-sidebar')

            <main class="mq-dashboard-main et-main">
                @include('partials.mq-topbar', [
                    'user' => $user,
                    'roleLabel' => 'Entrenador',
                    'profileUrl' => route('entrenador.perfil'),
                    'settingsUrl' => route('entrenador.perfil'),
                    'helpUrl' => route('entrenador.chat'),
                    'notificationsUrl' => route('entrenador.notificaciones'),
                    'notifCount' => 2,
                ])

                @if(session('success'))
                    <div class="hr-alert hr-alert--success">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="hr-alert hr-alert--error">
                        <i class="bi bi-exclamation-circle"></i> {{ $errors->first() }}
                    </div>
                @endif

                <header class="hr-head">
                    <h1 class="hr-title">Mi Horario</h1>
                    <p class="hr-subtitle">Define tu disponibilidad semanal (08:00 - 22:00)</p>
                </header>

                <section class="hr-grid" aria-label="Horario semanal">
                    @php
                        $dayNumbers = ['Lunes' => 1, 'Martes' => 2, 'Miercoles' => 3, 'Jueves' => 4, 'Viernes' => 5, 'Sabado' => 6, 'Domingo' => 0];
                    @endphp
                    @foreach (($week ?? []) as $day => $data)
                        <article class="hr-col {{ ($data['available'] ?? true) ? '' : 'hr-col--unavailable' }}">
                            <div class="hr-col-head">
                                <span>{{ $day }}</span>
                                <span class="hr-col-date">{{ $data['date'] ?? '' }}</span>
                            </div>
                            <div class="hr-col-info">
                                <span class="hr-avail-badge {{ ($data['available'] ?? true) ? 'hr-avail--on' : 'hr-avail--off' }}">
                                    {{ ($data['available'] ?? true) ? 'Disponible' : 'No disponible' }}
                                </span>
                                <span class="hr-avail-time">{{ $data['start_time'] ?? '08:00' }} - {{ $data['end_time'] ?? '22:00' }}</span>
                            </div>
                            <button class="hr-edit-btn" data-day="{{ $dayNumbers[$day] }}" data-start="{{ $data['start_time'] ?? '08:00' }}" data-end="{{ $data['end_time'] ?? '22:00' }}" data-available="{{ ($data['available'] ?? true) ? '1' : '0' }}">
                                <i class="bi bi-pencil"></i> Editar
                            </button>
                            <div class="hr-col-body">
                                @foreach (($data['items'] ?? []) as $it)
                                    <div class="hr-block {{ ($it['status'] ?? '') === 'confirmado' ? 'hr-block--confirmed' : '' }}">
                                        <div class="hr-time">{{ $it['time'] }}</div>
                                        <div class="hr-pet">{{ $it['pet'] }}</div>
                                        <div class="hr-activity">{{ $it['activity'] }}</div>
                                        @if($it['status'] ?? '')
                                            <div class="hr-status">{{ ucfirst($it['status']) }}</div>
                                        @endif
                                    </div>
                                @endforeach
                                @if(empty($data['items'] ?? []))
                                    <div class="hr-empty">
                                        <i class="bi bi-calendar-x"></i>
                                        <span>Sin reservas</span>
                                    </div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </section>
            </main>
        </div>

        <!-- Modal para editar disponibilidad -->
        <div class="hr-modal" id="hrModal" style="display:none;">
            <div class="hr-modal-content">
                <div class="hr-modal-head">
                    <h3>Editar Disponibilidad</h3>
                    <button class="hr-modal-close" type="button">&times;</button>
                </div>
                <form action="{{ route('entrenador.availability.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="day_of_week" id="hrDayOfWeek">
                    <div class="hr-modal-body">
                        <div class="hr-field">
                            <label>Disponibilidad</label>
                            <select name="is_available" id="hrIsAvailable">
                                <option value="1">Disponible</option>
                                <option value="0">No disponible</option>
                            </select>
                        </div>
                        <div class="hr-field">
                            <label>Hora inicio</label>
                            <input type="time" name="start_time" id="hrStartTime" min="08:00" max="22:00" value="08:00">
                        </div>
                        <div class="hr-field">
                            <label>Hora fin</label>
                            <input type="time" name="end_time" id="hrEndTime" min="08:00" max="22:00" value="22:00">
                        </div>
                        <p class="hr-modal-note">El horario debe estar entre 08:00 y 22:00</p>
                    </div>
                    <div class="hr-modal-foot">
                        <button type="button" class="hr-btn hr-btn--secondary" id="hrCancelBtn">Cancelar</button>
                        <button type="submit" class="hr-btn hr-btn--primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            document.querySelectorAll('.hr-edit-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.getElementById('hrDayOfWeek').value = this.dataset.day;
                    document.getElementById('hrStartTime').value = this.dataset.start;
                    document.getElementById('hrEndTime').value = this.dataset.end;
                    document.getElementById('hrIsAvailable').value = this.dataset.available;
                    document.getElementById('hrModal').style.display = 'flex';
                });
            });

            document.getElementById('hrCancelBtn').addEventListener('click', function() {
                document.getElementById('hrModal').style.display = 'none';
            });

            document.querySelector('.hr-modal-close').addEventListener('click', function() {
                document.getElementById('hrModal').style.display = 'none';
            });

            document.getElementById('hrModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    this.style.display = 'none';
                }
            });
        </script>
    </body>
</html>
