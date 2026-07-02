<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Agenda de Visitas | Más Que Perros</title>
        <link rel="icon" type="image/png" href="{{ asset('img/huellita.png') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('css/shared/mq-topbar.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dueño/panel.css') }}">
        <link rel="stylesheet" href="{{ asset('css/entrenador/dashboardentrenador.css') }}">
        <link rel="stylesheet" href="{{ asset('css/entrenador/reservas.css') }}">
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
                ])

                <section class="tr-reservas" aria-label="Gestión de reservas">
                    <header class="tr-head">
                        <h1 class="tr-title">Mis Reservas</h1>
                        <p class="tr-subtitle">Gestiona las reservas asignadas a ti</p>
                    </header>

                    <div class="tr-stats">
                        <div class="tr-stat tr-stat--pending">
                            <div class="tr-stat-icon"><i class="bi bi-clock"></i></div>
                            <div class="tr-stat-body">
                                <div class="tr-stat-value">{{ $counts['pendientes'] ?? 0 }}</div>
                                <div class="tr-stat-label">Pendientes</div>
                            </div>
                        </div>
                        <div class="tr-stat tr-stat--confirmed">
                            <div class="tr-stat-icon"><i class="bi bi-check-circle"></i></div>
                            <div class="tr-stat-body">
                                <div class="tr-stat-value">{{ $counts['confirmadas'] ?? 0 }}</div>
                                <div class="tr-stat-label">Confirmadas</div>
                            </div>
                        </div>
                        <div class="tr-stat tr-stat--total">
                            <div class="tr-stat-icon"><i class="bi bi-calendar3"></i></div>
                            <div class="tr-stat-body">
                                <div class="tr-stat-value">{{ $counts['total'] ?? 0 }}</div>
                                <div class="tr-stat-label">Total</div>
                            </div>
                        </div>
                        <div class="tr-stat tr-stat--pending">
                            <div class="tr-stat-icon"><i class="bi bi-x-circle"></i></div>
                            <div class="tr-stat-body">
                                <div class="tr-stat-value">{{ $counts['canceladas'] ?? 0 }}</div>
                                <div class="tr-stat-label">Rechazadas</div>
                            </div>
                        </div>
                    </div>

                    <div class="tr-list">
                        @forelse (($reservas ?? []) as $r)
                            <article class="tr-card {{ $r['status'] === 'pendiente' ? 'tr-card--pending' : 'tr-card--confirmed' }}"
                                     data-eval-date="{{ $r['fecha_evaluacion'] }}"
                                     data-eval-time="{{ $r['hora_evaluacion'] }}"
                                     data-quoted-price="{{ $r['precio_cotizado'] }}"
                                     data-duration="{{ $r['duracion'] }}"
                                     data-observations="{{ $r['observaciones'] }}">
                                <div class="tr-card-header">
                                    <div class="tr-card-pet">
                                        <span class="tr-card-avatar"><i class="bi bi-heart"></i></span>
                                        <div>
                                            <div class="tr-card-pet-name">{{ $r['pet'] }}</div>
                                            <div class="tr-card-owner">{{ $r['owner'] }}</div>
                                        </div>
                                    </div>
                                    <span class="tr-card-status {{ $r['status'] === 'pendiente' ? 'tr-status--pending' : 'tr-status--confirmed' }}">
                                        {{ ucfirst($r['status']) }}
                                    </span>
                                </div>

                                <div class="tr-card-body">
                                    <div class="tr-card-detail">
                                        <i class="bi bi-tag"></i>
                                        <span>{{ $r['service'] }}</span>
                                    </div>
                                    <div class="tr-card-detail">
                                        <i class="bi bi-calendar-event"></i>
                                        <span>{{ $r['date'] }}</span>
                                    </div>
                                    <div class="tr-card-detail">
                                        <i class="bi bi-clock"></i>
                                        <span>{{ $r['time'] }}</span>
                                    </div>
                                    <div class="tr-card-detail">
                                        <i class="bi bi-currency-dollar"></i>
                                        <span>$ {{ number_format($r['price'], 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                @if($r['comments'])
                                    <div class="tr-card-comments">
                                        <i class="bi bi-chat-quote"></i>
                                        {{ $r['comments'] }}
                                    </div>
                                @endif

                                <div class="tr-card-actions">
                                    @if(str_contains($r['status'], 'pendiente de evaluación'))
                                        <button class="tr-btn tr-btn--confirm" type="button" onclick="showCitaModal({{ $r['id'] }}, '{{ $r['service'] }}', '{{ $r['pet'] }}')">
                                            <i class="bi bi-calendar-plus"></i> Asignar Cita
                                        </button>
                                    @elseif(str_contains($r['status'], 'cita de evaluación asignada'))
                                        <button class="tr-btn tr-btn--confirm" type="button" onclick="showDiagnosticoModal({{ $r['id'] }}, '{{ $r['service'] }}', '{{ $r['pet'] }}')">
                                            <i class="bi bi-clipboard-check"></i> Registrar Diagnóstico
                                        </button>
                                    @elseif(str_contains($r['status'], 'cotizado') || str_contains($r['status'], 'aprobación'))
                                        <span class="tr-card-waiting">Esperando aprobación del cliente</span>
                                    @elseif(str_contains($r['status'], 'aceptada') || str_contains($r['status'], 'esperando pago'))
                                        <span class="tr-card-waiting" style="color: #856404;">✅ Cotización Aceptada - Esperando pago</span>
                                    @elseif(str_contains($r['status'], 'pagada'))
                                        <span class="tr-card-waiting" style="color: #155724;">💰 Pago Realizado - Servicio confirmado</span>
                                    @elseif(!str_contains($r['status'], 'confirmada') && !str_contains($r['status'], 'pagado') && !str_contains($r['status'], 'curso') && !str_contains($r['status'], 'evaluación'))
                                        <form action="{{ route('entrenador.reservas.estado', $r['id']) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="estado" value="Confirmada">
                                            <button class="tr-btn tr-btn--confirm" type="submit">
                                                <i class="bi bi-check-lg"></i> Confirmar
                                            </button>
                                        </form>
                                    @endif
                                    @if($r['status'] !== 'cancelada')
                                        <form action="{{ route('entrenador.reservas.estado', $r['id']) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="estado" value="Cancelada">
                                            <button class="tr-btn tr-btn--view" type="submit">
                                                <i class="bi bi-x-lg"></i> Rechazar
                                            </button>
                                        </form>
                                    @endif
                                    @if($r['status'] !== 'pendiente')
                                        <form action="{{ route('entrenador.reservas.estado', $r['id']) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="estado" value="Pendiente">
                                            <button class="tr-btn tr-btn--view" type="submit">
                                                <i class="bi bi-clock"></i> En espera
                                            </button>
                                        </form>
                                    @endif
                                    <button class="tr-btn tr-btn--view" type="button" data-id="{{ $r['id'] }}">
                                        <i class="bi bi-eye"></i> Ver detalles
                                    </button>
                                </div>
                            </article>
                        @empty
                            <article class="tr-card">
                                <div class="tr-card-header">
                                    <div class="tr-card-pet">
                                        <div>
                                            <div class="tr-card-pet-name">No tienes reservas asignadas</div>
                                            <div class="tr-card-owner">Cuando un dueño reserve contigo, aparecerá aquí.</div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforelse
                    </div>
                </section>
            </main>
        </div>

        <!-- Modal para asignar cita de evaluación -->
        <div class="tr-modal" id="citaModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
            <div style="background: white; padding: 30px; border-radius: 10px; max-width: 400px; width: 90%;">
                <h3 style="margin-bottom: 20px;">Asignar Cita de Evaluación</h3>
                <p id="citaModalInfo" style="margin-bottom: 15px; color: #666;"></p>
                <form id="citaForm" method="POST">
                    @csrf
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">Fecha de Evaluación:</label>
                        <input type="date" name="fecha_evaluacion" id="fechaEvaluacion" required 
                               style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">Hora de Evaluación:</label>
                        <input type="time" name="hora_evaluacion" id="horaEvaluacion" required 
                               style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    <div style="display: flex; gap: 10px; justify-content: flex-end;">
                        <button type="button" onclick="closeCitaModal()" 
                                style="padding: 10px 20px; background: #e0e0e0; border: none; border-radius: 5px; cursor: pointer;">
                            Cancelar
                        </button>
                        <button type="submit" 
                                style="padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">
                            Asignar Cita
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal para registrar diagnóstico y cotización -->
        <div class="tr-modal" id="diagnosticoModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
            <div style="background: white; padding: 30px; border-radius: 10px; max-width: 500px; width: 90%;">
                <h3 style="margin-bottom: 20px;">Registrar Diagnóstico y Cotización</h3>
                <p id="diagnosticoModalInfo" style="margin-bottom: 15px; color: #666;"></p>
                <form id="diagnosticoForm" method="POST">
                    @csrf
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">Precio Final (COP):</label>
                        <input type="number" name="precio" id="diagnosticoPrecio" placeholder="200000" min="0" step="1000" required 
                               style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">Duración del Servicio (días):</label>
                        <input type="number" name="duracion" id="diagnosticoDuracion" placeholder="60" min="1" required 
                               style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">Observaciones del Diagnóstico:</label>
                        <textarea name="observaciones" id="diagnosticoObservaciones" rows="4" placeholder="Describe el diagnóstico y las observaciones de la evaluación..."
                               style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; resize: vertical;"></textarea>
                    </div>
                    <div style="display: flex; gap: 10px; justify-content: flex-end;">
                        <button type="button" onclick="closeDiagnosticoModal()" 
                                style="padding: 10px 20px; background: #e0e0e0; border: none; border-radius: 5px; cursor: pointer;">
                            Cancelar
                        </button>
                        <button type="submit" 
                                style="padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">
                            Registrar Cotización
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal para ver detalles -->
        <div class="tr-modal" id="detallesModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
            <div style="background: white; padding: 30px; border-radius: 10px; max-width: 500px; width: 90%;">
                <h3 style="margin-bottom: 20px;">Detalles de la Reserva</h3>
                <div id="detallesContent" style="margin-bottom: 20px;"></div>
                <button type="button" onclick="closeDetallesModal()" 
                        style="padding: 10px 20px; background: #e0e0e0; border: none; border-radius: 5px; cursor: pointer;">
                    Cerrar
                </button>
            </div>
        </div>

        <script>
            function showCitaModal(reservaId, service, pet) {
                document.getElementById('citaModalInfo').textContent = `Servicio: ${service} - Mascota: ${pet}`;
                document.getElementById('citaForm').action = `/entrenador/reservas/${reservaId}/cita-evaluacion`;
                document.getElementById('citaModal').style.display = 'flex';
            }

            function closeCitaModal() {
                document.getElementById('citaModal').style.display = 'none';
                document.getElementById('fechaEvaluacion').value = '';
                document.getElementById('horaEvaluacion').value = '';
            }

            function showDiagnosticoModal(reservaId, service, pet) {
                document.getElementById('diagnosticoModalInfo').textContent = `Servicio: ${service} - Mascota: ${pet}`;
                document.getElementById('diagnosticoForm').action = `/entrenador/reservas/${reservaId}/diagnostico`;
                document.getElementById('diagnosticoModal').style.display = 'flex';
            }

            function closeDiagnosticoModal() {
                document.getElementById('diagnosticoModal').style.display = 'none';
                document.getElementById('diagnosticoPrecio').value = '';
                document.getElementById('diagnosticoDuracion').value = '';
                document.getElementById('diagnosticoObservaciones').value = '';
            }

            // Cerrar modales al hacer clic fuera
            document.getElementById('citaModal').addEventListener('click', function(e) {
                if (e.target === this) closeCitaModal();
            });

            document.getElementById('diagnosticoModal').addEventListener('click', function(e) {
                if (e.target === this) closeDiagnosticoModal();
            });

            document.getElementById('detallesModal').addEventListener('click', function(e) {
                if (e.target === this) closeDetallesModal();
            });

            // Manejar botón Ver detalles
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.tr-btn--view[data-id]');
                if (btn) {
                    const reservaId = btn.getAttribute('data-id');
                    const card = btn.closest('.tr-card');
                    if (card) {
                        const pet = card.querySelector('.tr-card-pet-name')?.textContent || 'N/A';
                        const owner = card.querySelector('.tr-card-owner')?.textContent || 'N/A';
                        const service = card.querySelector('.tr-card-detail:nth-child(1) span')?.textContent || 'N/A';
                        const date = card.querySelector('.tr-card-detail:nth-child(2) span')?.textContent || 'N/A';
                        const time = card.querySelector('.tr-card-detail:nth-child(3) span')?.textContent || 'N/A';
                        const price = card.querySelector('.tr-card-detail:nth-child(4) span')?.textContent || 'N/A';
                        const status = card.querySelector('.tr-card-status')?.textContent || 'N/A';
                        const comments = card.querySelector('.tr-card-comments')?.textContent || '';

                        let detallesHTML = `
                            <p><strong>Mascota:</strong> ${pet}</p>
                            <p><strong>Dueño:</strong> ${owner}</p>
                            <p><strong>Servicio:</strong> ${service}</p>
                            <p><strong>Fecha de Reserva:</strong> ${date}</p>
                            <p><strong>Hora de Reserva:</strong> ${time}</p>
                            <p><strong>Precio Base:</strong> ${price}</p>
                            <p><strong>Estado:</strong> ${status}</p>
                        `;

                        if (comments) {
                            detallesHTML += `<p><strong>Comentarios:</strong> ${comments}</p>`;
                        }

                        // Mostrar información específica del flujo de evaluación
                        if (status.toLowerCase().includes('cita de evaluación') || status.toLowerCase().includes('cotizado') || status.toLowerCase().includes('aprobación')) {
                            detallesHTML += `<hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">`;
                            detallesHTML += `<h4 style="margin-bottom: 10px;">Información de Evaluación</h4>`;
                            
                            // Leer datos de evaluación directamente de la tarjeta
                            const evalDate = card.getAttribute('data-eval-date');
                            const evalTime = card.getAttribute('data-eval-time');
                            const quotedPrice = card.getAttribute('data-quoted-price');
                            const duration = card.getAttribute('data-duration');
                            const observations = card.getAttribute('data-observations');

                            if (evalDate) {
                                detallesHTML += `<p><strong>Fecha de Evaluación:</strong> ${evalDate}</p>`;
                            }
                            if (evalTime) {
                                // Formatear la hora para mostrar solo HH:MM
                                const formattedTime = evalTime.substring(0, 5);
                                detallesHTML += `<p><strong>Hora de Evaluación:</strong> ${formattedTime}</p>`;
                            }
                            if (quotedPrice && quotedPrice !== '0') {
                                detallesHTML += `<p><strong>Precio Cotizado:</strong> $${parseInt(quotedPrice).toLocaleString('es-CO')} COP</p>`;
                            }
                            if (duration && duration !== '0') {
                                detallesHTML += `<p><strong>Duración:</strong> ${duration} días</p>`;
                            }
                            if (observations) {
                                detallesHTML += `<p><strong>Observaciones:</strong> ${observations}</p>`;
                            }
                        }

                        document.getElementById('detallesContent').innerHTML = detallesHTML;
                        document.getElementById('detallesModal').style.display = 'flex';
                    }
                }
            });

            function closeDetallesModal() {
                document.getElementById('detallesModal').style.display = 'none';
            }
        </script>
    </body>
</html>
