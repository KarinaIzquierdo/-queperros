<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mis Peticiones Guau | Más Que Perros</title>
        <link rel="icon" type="image/png" href="{{ asset('img/huellita.png') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('css/shared/mq-topbar.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/dueño/panel.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    </head>
    <body>
        @include('partials.page-loader')
        @php
            use Illuminate\Support\Str;
        @endphp
        <div class="mq-dashboard">
            @include('partials.dueno-sidebar')

            <main class="mq-dashboard-main">
                @include('partials.mq-topbar', [
                    'mqTopbarUser' => $user,
                    'mqTopbarName' => $user->name,
                    'roleLabel' => 'Dueño',
                    'profileUrl' => route('owner.perfil'),
                    'settingsUrl' => route('owner.perfil'),
                    'helpUrl' => route('owner.chat'),
                    'notificationsUrl' => route('owner.notificaciones'),
                ])

                @if(session('status'))
                    <div class="alert-modern alert-modern--success">
                        <i class="bi bi-check-circle"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert-modern alert-modern--error">
                        <i class="bi bi-exclamation-triangle"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <section class="page-header">
                    <h1 class="page-title">Mis Servicios</h1>
                    <p class="page-subtitle">Gestiona tus solicitudes de servicios y reservas</p>
                </section>

                <!-- Tabs -->
                <div class="tabs-container">
                    <div class="tabs-nav">
                        <button class="tab-btn tab-btn--active" data-tab="approvals">Solicitudes de Aprobación</button>
                        <button class="tab-btn" data-tab="reservations">Reservas de Entrenamiento</button>
                    </div>

                    <!-- Solicitudes de Aprobación -->
                    <div class="tab-content tab-content--active" id="approvals">
                        <div class="services-grid">
                            @forelse ($serviceApprovals as $approval)
                                <div class="service-card service-card--{{ $approval->estado }}">
                                    <div class="service-card-header">
                                        <div class="service-info">
                                            <h3 class="service-name">{{ $approval->servicio?->nombre ?? 'Servicio no disponible' }}</h3>
                                            <div class="service-price">
                                                @if($approval->servicio)
                                                    ${{ number_format($approval->servicio->precio, 0, ',', '.') }}
                                                @else
                                                    -
                                                @endif
                                            </div>
                                        </div>
                                        <div class="service-status">
                                            <span class="status-badge status-badge--{{ $approval->estado }}">
                                                {{ ucfirst($approval->estado) }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="service-card-body">
                                        <div class="service-details">
                                            <div class="detail-item">
                                                <i class="bi bi-calendar"></i>
                                                <span>{{ $approval->fecha_solicitada->format('d M Y') }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <i class="bi bi-heart"></i>
                                                <span>{{ $approval->mascota->nombre ?? 'Mascota' }}</span>
                                            </div>
                                        </div>
                                        
                                        @if ($approval->notas_cliente)
                                            <div class="service-notes">
                                                <strong>Tus notas:</strong> {{ $approval->notas_cliente }}
                                            </div>
                                        @endif
                                        
                                        @if ($approval->notas_admin)
                                            <div class="service-notes service-notes--admin">
                                                <strong>Notas del admin:</strong> {{ $approval->notas_admin }}
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="service-card-footer">
                                        @if ($approval->estado === 'aprobado' && $approval->servicio)
                                            <button class="btn btn--primary" onclick="showPaymentModal({{ $approval->id }}, '{{ $approval->servicio?->nombre }}', {{ $approval->servicio?->precio }})">
                                                <i class="bi bi-credit-card"></i> Proceder al Pago
                                            </button>
                                        @elseif ($approval->estado === 'aprobado' && !$approval->servicio)
                                            <div class="error-message">
                                                <i class="bi bi-exclamation-triangle"></i>
                                                Servicio ya no disponible
                                            </div>
                                        @elseif ($approval->estado === 'pendiente')
                                            <div class="pending-message">
                                                <i class="bi bi-clock"></i>
                                                Esperando aprobación del administrador
                                            </div>
                                        @elseif ($approval->estado === 'rechazado')
                                            <div class="rejected-message">
                                                <i class="bi bi-x-circle"></i>
                                                Solicitud rechazada
                                            </div>
                                        @elseif ($approval->estado === 'pagado')
                                            <div class="paid-message">
                                                <i class="bi bi-check-circle"></i>
                                                Pago confirmado - Reserva activa
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <h3>No tienes solicitudes de servicios</h3>
                                    <p>Cuando solicites servicios que no sean de entrenamiento, aparecerán aquí para aprobación.</p>
                                    <a href="{{ route('owner.services') }}" class="btn btn--primary">Solicitar Servicio</a>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Reservas de Entrenamiento -->
                    <div class="tab-content" id="reservations">
                        <div class="services-grid">
                            @forelse ($trainingReservations as $reservation)
                                <div class="service-card service-card--{{ strtolower($reservation->estado) }}">
                                    <div class="service-card-header">
                                        <div class="service-info">
                                            <h3 class="service-name">{{ $reservation->servicio?->nombre ?? 'Entrenamiento' }}</h3>
                                            <div class="service-trainer">
                                                <i class="bi bi-person"></i>
                                                {{ $reservation->trainer->name ?? 'Entrenador' }}
                                            </div>
                                        </div>
                                        <div class="service-status">
                                            <span class="status-badge status-badge--{{ strtolower($reservation->estado) }}">
                                                {{ ucfirst($reservation->estado) }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="service-card-body">
                                        <div class="service-details">
                                            <div class="detail-item">
                                                <i class="bi bi-calendar"></i>
                                                <span>{{ \Carbon\Carbon::parse($reservation->fecha)->format('d M Y') }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <i class="bi bi-heart"></i>
                                                <span>{{ $reservation->mascota_nombre ?? 'Mascota' }}</span>
                                            </div>
                                        </div>
                                        
                                        @if (isset($reservation->comentarios))
                                            <div class="service-notes">
                                                <strong>Comentarios:</strong> {{ $reservation->comentarios }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <i class="bi bi-calendar-x"></i>
                                    <h3>No tienes reservas de entrenamiento</h3>
                                    <p>Las reservas de servicios de entrenamiento aparecerán aquí.</p>
                                    <a href="{{ route('owner.services') }}" class="btn btn--primary">Reservar Entrenamiento</a>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <!-- Modal de Pago -->
        <div class="payment-modal payment-modal--hidden" id="paymentModal" aria-hidden="true">
            <div class="payment-backdrop" onclick="closePaymentModal()"></div>
            <div class="payment-card">
                <div class="payment-header">
                    <h3>Confirmar Pago</h3>
                    <button class="payment-close" onclick="closePaymentModal()">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
                <div class="payment-body">
                    <div class="payment-summary">
                        <div class="payment-item">
                            <span class="payment-label">Servicio:</span>
                            <span class="payment-value" id="paymentServiceName">—</span>
                        </div>
                        <div class="payment-item">
                            <span class="payment-label">Total a pagar:</span>
                            <span class="payment-value payment-value--total" id="paymentAmount">$0</span>
                        </div>
                    </div>
                    
                    <form class="payment-form" id="paymentForm" action="{{ route('payment.service.create', ':id') }}" method="POST">
                        @csrf
                        <input type="hidden" name="approval_id" id="paymentApprovalId">
                        
                        <div class="form-group">
                            <label class="form-label">Pasarela Segura</label>
                            <div class="payment-method-info">
                                <i class="bi bi-shield-check"></i>
                                <div>
                                    <span>Mercado Pago</span><br>
                                    <small>PSE, Efecty y Tarjetas de Crédito</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Notas adicionales</label>
                            <textarea class="form-textarea" name="payment_notes" rows="2" placeholder="Ej: Pago de guardería de Toby..."></textarea>
                        </div>
                        
                        <div class="payment-actions">
                            <button type="button" class="btn-modal btn-modal--cancel" onclick="closePaymentModal()">Cancelar</button>
                            <button type="submit" class="btn-modal btn-modal--confirm">Confirmar Pago</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            // Tabs functionality
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const tabName = btn.dataset.tab;
                    
                    // Update buttons
                    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('tab-btn--active'));
                    btn.classList.add('tab-btn--active');
                    
                    // Update content
                    document.querySelectorAll('.tab-content').forEach(content => {
                        content.classList.remove('tab-content--active');
                    });
                    document.getElementById(tabName).classList.add('tab-content--active');
                });
            });

            // Payment modal
            function showPaymentModal(approvalId, serviceName, amount) {
                document.getElementById('paymentApprovalId').value = approvalId;
                document.getElementById('paymentServiceName').textContent = serviceName;
                document.getElementById('paymentAmount').textContent = '$' + amount.toLocaleString('es-CO');
                document.getElementById('paymentModal').classList.remove('payment-modal--hidden');
            }

            function closePaymentModal() {
                document.getElementById('paymentModal').classList.add('payment-modal--hidden');
            }

            // Payment form submission
            const paymentForm = document.getElementById('paymentForm');
            const originalAction = paymentForm.action;

            paymentForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const approvalId = document.getElementById('paymentApprovalId').value;
                
                if (!approvalId) {
                    alert('Error: No se pudo obtener el ID del servicio');
                    return;
                }
                
                // Update form action with dynamic approval ID using the original template
                this.action = originalAction.replace(':id', approvalId);
                
                // Submit the form
                this.submit();
            });
        </script>

        <style>
            .page-header {
                margin-bottom: 2.5rem;
                text-align: center;
            }

            .page-title {
                font-family: 'Lilita One', cursive;
                font-size: 2.8rem;
                color: #2c3e50;
                margin-bottom: 0.5rem;
            }

            .page-subtitle {
                color: #64748b;
                font-size: 1.1rem;
                font-weight: 500;
            }

            .tabs-container {
                background: white;
                border-radius: 30px;
                overflow: hidden;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
                padding: 10px;
            }

            .tabs-nav {
                display: flex;
                gap: 10px;
                background: #f8fafc;
                padding: 8px;
                border-radius: 25px;
                margin-bottom: 2rem;
            }

            .tab-btn {
                flex: 1;
                padding: 1rem;
                background: none;
                border: none;
                font-family: 'Lilita One', cursive;
                font-size: 1.1rem;
                color: #64748b;
                cursor: pointer;
                transition: all 0.3s;
                border-radius: 22px;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            .tab-btn:hover {
                color: #5e94e2;
                background: rgba(94, 148, 226, 0.05);
            }

            .tab-btn--active {
                color: white;
                background: #5e94e2;
                box-shadow: 0 4px 15px rgba(94, 148, 226, 0.3);
            }

            .tab-content {
                display: none;
                padding: 1rem;
            }

            .tab-content--active {
                display: block;
                animation: fadeIn 0.4s ease;
            }

            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }

            .services-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
                gap: 2rem;
            }

            .service-card {
                background: white;
                border: 2px solid #f1f5f9;
                border-radius: 30px;
                overflow: hidden;
                transition: all 0.3s ease;
                display: flex;
                flex-direction: column;
                position: relative;
            }

            .service-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
                border-color: #5e94e2;
            }

            .service-card-header {
                padding: 1.8rem;
                background: #f0f7ff;
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                border-bottom: 2px dashed rgba(94, 148, 226, 0.1);
            }

            .service-name {
                font-family: 'Lilita One', cursive;
                font-size: 1.4rem;
                color: #2c3e50;
                margin: 0 0 0.5rem 0;
            }

            .service-price {
                color: #5e94e2;
                font-family: 'Lilita One', cursive;
                font-size: 1.3rem;
                display: block;
            }

            .status-badge {
                padding: 0.5rem 1rem;
                border-radius: 999px;
                font-size: 0.75rem;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .status-badge--pendiente {
                background: #fef3c7;
                color: #92400e;
            }

            .status-badge--aprobado {
                background: #dcfce7;
                color: #166534;
            }

            .status-badge--rechazado {
                background: #fee2e2;
                color: #991b1b;
            }

            .status-badge--pagado {
                background: #e0f2fe;
                color: #075985;
            }

            .service-card-body {
                padding: 1.8rem;
                flex-grow: 1;
            }

            .service-details {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 1rem;
                margin-bottom: 1.5rem;
            }

            .detail-item {
                display: flex;
                align-items: center;
                gap: 0.8rem;
                color: #64748b;
                font-size: 0.9rem;
                font-weight: 600;
            }

            .detail-item i {
                color: #5e94e2;
                font-size: 1.1rem;
            }

            .service-notes {
                background: #f8fafc;
                padding: 1.2rem;
                border-radius: 18px;
                font-size: 0.85rem;
                color: #475569;
                line-height: 1.5;
                margin-bottom: 0.8rem;
                border: 1px solid #f1f5f9;
            }

            .service-notes strong {
                display: block;
                color: #2c3e50;
                margin-bottom: 0.3rem;
                text-transform: uppercase;
                font-size: 0.75rem;
            }

            .service-notes--admin {
                background: #eff6ff;
                border: 1px solid #dbeafe;
                color: #1e40af;
            }

            .service-notes--admin strong {
                color: #1e40af;
            }

            .service-card-footer {
                padding: 1.5rem 1.8rem 1.8rem;
                border-top: none;
            }

            .btn--primary {
                width: 100%;
                padding: 1rem;
                background: #5e94e2;
                color: white;
                border: none;
                border-radius: 999px;
                font-family: 'Lilita One', cursive;
                font-size: 1.1rem;
                cursor: pointer;
                box-shadow: 0 6px 0 rgba(0, 0, 0, 0.1);
                transition: all 0.2s;
                justify-content: center;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .btn--primary:hover {
                background: #4a82d1;
                transform: translateY(-2px);
                box-shadow: 0 8px 0 rgba(0, 0, 0, 0.1);
            }

            .btn--primary:active {
                transform: translateY(2px);
                box-shadow: 0 4px 0 rgba(0, 0, 0, 0.1);
            }

            .paid-message, .rejected-message, .pending-message {
                display: flex;
                align-items: center;
                gap: 0.8rem;
                padding: 1rem;
                border-radius: 15px;
                font-weight: 700;
                font-size: 0.9rem;
            }

            .paid-message { background: #f0fdf4; color: #15803d; }
            .rejected-message { background: #fef2f2; color: #b91c1c; }
            .pending-message { background: #fffbeb; color: #b45309; }

            .empty-state {
                grid-column: 1 / -1;
                text-align: center;
                padding: 4rem 2rem;
                background: #f8fafc;
                border-radius: 30px;
                border: 3px dashed #e2e8f0;
            }

            .empty-state i {
                font-size: 4rem;
                color: #cbd5e1;
                margin-bottom: 1.5rem;
            }

            .empty-state h3 {
                font-family: 'Lilita One', cursive;
                font-size: 1.8rem;
                margin-bottom: 0.5rem;
                color: #374151;
            }

            .btn {
                padding: 0.5rem 1rem;
                border: none;
                border-radius: 6px;
                font-size: 0.875rem;
                font-weight: 500;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                text-decoration: none;
                transition: all 0.2s;
            }

            .btn--primary {
                background: #5e94e2;
                color: white;
            }

            .btn--primary:hover {
                background: #4a82d1;
            }

            .btn--secondary {
                background: #f3f4f6;
                color: #374151;
            }

            .btn--secondary:hover {
                background: #e5e7eb;
            }

            /* Payment Modal Modernized */
            .payment-modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 1000;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 1rem;
            }

            .payment-modal--hidden {
                display: none;
            }

            .payment-backdrop {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(44, 62, 80, 0.4);
                backdrop-filter: blur(4px);
            }

            .payment-card {
                position: relative;
                background: white;
                border-radius: 30px;
                max-width: 500px;
                width: 100%;
                max-height: 90vh;
                overflow-y: auto;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                border: none;
            }

            .payment-header {
                padding: 2rem 2rem 1rem;
                border-bottom: none;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .payment-header h3 {
                font-family: 'Lilita One', cursive;
                font-size: 1.8rem;
                color: #2c3e50;
                margin: 0;
            }

            .payment-close {
                background: #f1f5f9;
                border: none;
                width: 35px;
                height: 35px;
                border-radius: 50%;
                font-size: 1.2rem;
                cursor: pointer;
                color: #64748b;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.2s;
            }

            .payment-close:hover {
                background: #e2e8f0;
                color: #0f172a;
            }

            .payment-body {
                padding: 0 2rem 2rem;
            }

            .payment-summary {
                background: #f0f7ff;
                padding: 1.5rem;
                border-radius: 20px;
                margin-bottom: 2rem;
                border: 2px dashed #5e94e2;
            }

            .payment-item {
                display: flex;
                justify-content: space-between;
                margin-bottom: 0.8rem;
                align-items: center;
            }

            .payment-item:last-child {
                margin-bottom: 0;
                padding-top: 0.8rem;
                border-top: 1px solid rgba(94, 148, 226, 0.1);
            }

            .payment-label {
                color: #64748b;
                font-size: 0.95rem;
                font-weight: 500;
            }

            .payment-value {
                font-weight: 700;
                color: #2c3e50;
            }

            .payment-value--total {
                font-family: 'Lilita One', cursive;
                font-size: 1.5rem;
                color: #5e94e2;
            }

            .form-group {
                margin-bottom: 1.5rem;
            }

            .form-label {
                display: block;
                margin-bottom: 0.8rem;
                font-weight: 800;
                color: #2c3e50;
                font-size: 0.9rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .payment-method-info {
                background: white;
                border: 2px solid #e2e8f0;
                padding: 1rem;
                border-radius: 15px;
                display: flex;
                align-items: center;
                gap: 1rem;
                transition: all 0.2s;
            }

            .payment-method-info i {
                font-size: 1.5rem;
                color: #5e94e2;
            }

            .payment-method-info span {
                font-weight: 700;
                color: #2c3e50;
            }

            .payment-method-info small {
                color: #64748b;
            }

            .form-textarea {
                width: 100%;
                padding: 1rem;
                border: 2px solid #e2e8f0;
                border-radius: 15px;
                font-size: 0.95rem;
                font-family: inherit;
                transition: all 0.2s;
                background: #f8fafc;
            }

            .form-textarea:focus {
                outline: none;
                border-color: #5e94e2;
                background: white;
                box-shadow: 0 0 0 4px rgba(94, 148, 226, 0.1);
            }

            .payment-actions {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 1rem;
                margin-top: 2rem;
            }

            .btn-modal {
                padding: 1rem;
                border: none;
                border-radius: 999px;
                font-family: 'Lilita One', cursive;
                font-size: 1.1rem;
                cursor: pointer;
                transition: all 0.2s;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
            }

            .btn-modal--cancel {
                background: #f1f5f9;
                color: #64748b;
            }

            .btn-modal--cancel:hover {
                background: #e2e8f0;
                color: #0f172a;
            }

            .btn-modal--confirm {
                background: #5e94e2;
                color: white;
                box-shadow: 0 4px 0 rgba(0, 0, 0, 0.1);
            }

            .btn-modal--confirm:hover {
                background: #4a82d1;
                transform: translateY(-2px);
                box-shadow: 0 6px 0 rgba(0, 0, 0, 0.1);
            }

            .btn-modal--confirm:active {
                transform: translateY(2px);
                box-shadow: 0 2px 0 rgba(0, 0, 0, 0.1);
            }

            .btn-modal--confirm:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 0 rgba(0, 0, 0, 0.1);
            }

            .btn-modal--confirm:active {
                transform: translateY(2px);
                box-shadow: 0 2px 0 rgba(0, 0, 0, 0.1);
            }

            @media (max-width: 768px) {
                .services-grid {
                    grid-template-columns: 1fr;
                }
                
                .tabs-nav {
                    flex-direction: column;
                }
                
                .payment-actions {
                    flex-direction: column;
                }
            }

            /* Modern Alerts */
            .alert-modern {
                margin: 1.5rem 2rem;
                padding: 1rem 1.5rem;
                border-radius: 15px;
                display: flex;
                align-items: center;
                gap: 1rem;
                font-weight: 600;
                animation: slideDown 0.3s ease;
            }

            .alert-modern--success {
                background: #f0fdf4;
                color: #166534;
                border: 1px solid #bbf7d0;
            }

            .alert-modern--error {
                background: #fef2f2;
                color: #991b1b;
                border: 1px solid #fecaca;
            }

            @keyframes slideDown {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
    </body>
</html>
