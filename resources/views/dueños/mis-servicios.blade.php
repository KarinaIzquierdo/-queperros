<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mis Servicios</title>

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
                    'notifCount' => DB::table('notifications')->where('id_usuario', $user->id)->where('leido', false)->count(),
                ])

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
                                            <h3 class="service-name">{{ $approval->servicio->nombre }}</h3>
                                            <div class="service-price">${{ number_format($approval->servicio->precio, 0, ',', '.') }}</div>
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
                                        @if ($approval->estado === 'aprobado')
                                            <button class="btn btn--primary" onclick="showPaymentModal({{ $approval->id }}, '{{ $approval->servicio->nombre }}', {{ $approval->servicio->precio }})">
                                                <i class="bi bi-credit-card"></i> Proceder al Pago
                                            </button>
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
                                            <h3 class="service-name">{{ $reservation->servicio->nombre ?? 'Entrenamiento' }}</h3>
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
                    <button class="payment-close" onclick="closePaymentModal()">×</button>
                </div>
                <div class="payment-body">
                    <div class="payment-summary">
                        <div class="payment-item">
                            <span class="payment-label">Servicio:</span>
                            <span class="payment-value" id="paymentServiceName">—</span>
                        </div>
                        <div class="payment-item">
                            <span class="payment-label">Total:</span>
                            <span class="payment-value payment-value--total" id="paymentAmount">$0</span>
                        </div>
                    </div>
                    
                    <form class="payment-form" id="paymentForm" action="{{ route('payment.service.create', ':id') }}" method="POST">
                        @csrf
                        <input type="hidden" name="approval_id" id="paymentApprovalId">
                        
                        <div class="form-group">
                            <label class="form-label">Método de pago</label>
                            <div class="payment-method-info">
                                <i class="bi bi-credit-card"></i>
                                <span>Pagar con MercadoPago</span>
                                <small style="color: #6b7280; margin-left: 8px;">Acepta tarjetas, PSE, efectivo y más</small>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Notas adicionales (opcional)</label>
                            <textarea class="form-textarea" name="payment_notes" rows="3" placeholder="Agrega notas sobre el pago..."></textarea>
                        </div>
                        
                        <div class="payment-actions">
                            <button type="button" class="btn btn--secondary" onclick="closePaymentModal()">Cancelar</button>
                            <button type="submit" class="btn btn--primary">Confirmar Pago</button>
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
            document.getElementById('paymentForm').addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const formData = new FormData(e.target);
                const approvalId = formData.get('approval_id');
                
                console.log('Payment form submission:', { approvalId, formAction: e.target.action });
                
                if (!approvalId) {
                    alert('Error: No se pudo obtener el ID del servicio');
                    return;
                }
                
                // Update form action with dynamic approval ID
                const form = e.target;
                const newAction = form.action.replace(':id', approvalId);
                form.action = newAction;
                
                console.log('Updated form action:', newAction);
                
                // Submit the form to redirect to MercadoPago
                form.submit();
            });
        </script>

        <style>
            .page-header {
                margin-bottom: 2rem;
            }

            .page-title {
                font-size: 2rem;
                font-weight: 600;
                color: #111827;
                margin-bottom: 0.5rem;
            }

            .page-subtitle {
                color: #6b7280;
                font-size: 1rem;
            }

            .tabs-container {
                background: white;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            }

            .tabs-nav {
                display: flex;
                border-bottom: 1px solid #e5e7eb;
            }

            .tab-btn {
                flex: 1;
                padding: 1rem;
                background: none;
                border: none;
                font-size: 0.875rem;
                font-weight: 500;
                color: #6b7280;
                cursor: pointer;
                transition: all 0.2s;
            }

            .tab-btn:hover {
                color: #374151;
                background: #f9fafb;
            }

            .tab-btn--active {
                color: #3b82f6;
                border-bottom: 2px solid #3b82f6;
                background: #f0f9ff;
            }

            .tab-content {
                display: none;
                padding: 1.5rem;
            }

            .tab-content--active {
                display: block;
            }

            .services-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: 1.5rem;
            }

            .service-card {
                border: 1px solid #e5e7eb;
                border-radius: 12px;
                overflow: hidden;
                transition: all 0.2s;
            }

            .service-card:hover {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }

            .service-card--pendiente {
                border-left: 4px solid #f59e0b;
            }

            .service-card--aprobado {
                border-left: 4px solid #10b981;
            }

            .service-card--rechazado {
                border-left: 4px solid #ef4444;
            }

            .service-card--pagado {
                border-left: 4px solid #3b82f6;
            }

            .service-card-header {
                padding: 1rem;
                background: #f9fafb;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .service-name {
                font-size: 1.125rem;
                font-weight: 600;
                color: #111827;
                margin-bottom: 0.25rem;
            }

            .service-price {
                color: #10b981;
                font-weight: 600;
                font-size: 1.125rem;
            }

            .service-trainer {
                color: #6b7280;
                font-size: 0.875rem;
                display: flex;
                align-items: center;
                gap: 0.25rem;
            }

            .status-badge {
                padding: 0.25rem 0.75rem;
                border-radius: 9999px;
                font-size: 0.75rem;
                font-weight: 500;
                text-transform: lowercase;
            }

            .status-badge--pendiente {
                background: #fef3c7;
                color: #92400e;
            }

            .status-badge--aprobado {
                background: #d1fae5;
                color: #065f46;
            }

            .status-badge--rechazado {
                background: #fee2e2;
                color: #991b1b;
            }

            .status-badge--pagado {
                background: #dbeafe;
                color: #1e40af;
            }

            .service-card-body {
                padding: 1rem;
            }

            .service-details {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
                margin-bottom: 1rem;
            }

            .detail-item {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                color: #6b7280;
                font-size: 0.875rem;
            }

            .service-notes {
                background: #f9fafb;
                padding: 0.75rem;
                border-radius: 6px;
                font-size: 0.875rem;
                margin-bottom: 0.5rem;
            }

            .service-notes--admin {
                background: #eff6ff;
                border-left: 3px solid #3b82f6;
            }

            .service-card-footer {
                padding: 1rem;
                border-top: 1px solid #e5e7eb;
            }

            .pending-message, .rejected-message, .paid-message {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                font-size: 0.875rem;
                color: #6b7280;
            }

            .paid-message {
                color: #10b981;
                font-weight: 500;
            }

            .rejected-message {
                color: #ef4444;
            }

            .empty-state {
                text-align: center;
                padding: 3rem 1rem;
                color: #6b7280;
            }

            .empty-state i {
                font-size: 3rem;
                margin-bottom: 1rem;
                opacity: 0.5;
            }

            .empty-state h3 {
                font-size: 1.25rem;
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
                background: #3b82f6;
                color: white;
            }

            .btn--primary:hover {
                background: #2563eb;
            }

            .btn--secondary {
                background: #f3f4f6;
                color: #374151;
            }

            .btn--secondary:hover {
                background: #e5e7eb;
            }

            /* Payment Modal */
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
                background: rgba(0, 0, 0, 0.5);
            }

            .payment-card {
                position: relative;
                background: white;
                border-radius: 12px;
                max-width: 500px;
                width: 90%;
                max-height: 90vh;
                overflow-y: auto;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            }

            .payment-header {
                padding: 1.5rem;
                border-bottom: 1px solid #e5e7eb;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .payment-header h3 {
                font-size: 1.25rem;
                font-weight: 600;
                color: #111827;
            }

            .payment-close {
                background: none;
                border: none;
                font-size: 1.5rem;
                cursor: pointer;
                color: #6b7280;
            }

            .payment-body {
                padding: 1.5rem;
            }

            .payment-summary {
                background: #f9fafb;
                padding: 1rem;
                border-radius: 8px;
                margin-bottom: 1.5rem;
            }

            .payment-item {
                display: flex;
                justify-content: space-between;
                margin-bottom: 0.5rem;
            }

            .payment-label {
                color: #6b7280;
                font-size: 0.875rem;
            }

            .payment-value {
                font-weight: 500;
                color: #111827;
            }

            .payment-value--total {
                font-size: 1.125rem;
                color: #10b981;
            }

            .form-group {
                margin-bottom: 1rem;
            }

            .form-label {
                display: block;
                margin-bottom: 0.5rem;
                font-weight: 500;
                color: #374151;
            }

            .form-select, .form-textarea {
                width: 100%;
                padding: 0.75rem;
                border: 1px solid #d1d5db;
                border-radius: 6px;
                font-size: 0.875rem;
            }

            .form-textarea {
                resize: vertical;
                min-height: 80px;
            }

            .payment-actions {
                display: flex;
                gap: 1rem;
                justify-content: flex-end;
                margin-top: 1.5rem;
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
        </style>
    </body>
</html>
