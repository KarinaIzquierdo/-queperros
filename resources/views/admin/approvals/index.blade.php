<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Aprobación de Servicios</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('css/shared/mq-topbar.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/admin-dashboard.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/admin-sidebar-extras.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/dashboard-admin-v2.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/gestionusuarios.css') }}?v={{ time() }}">
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

                <section class="gu-page-head">
                    <div class="gu-page-head-left">
                        <h1 class="gu-page-title">Aprobación de Servicios</h1>
                        <p class="gu-page-subtitle">Gestiona las solicitudes de servicios de los clientes</p>
                    </div>
                </section>

                <section class="gu-stats">
                    <div class="gu-stat">
                        <div class="gu-stat-icon gu-stat-icon--yellow"><i class="bi bi-clock-history" aria-hidden="true"></i></div>
                        <div class="gu-stat-main">
                            <div class="gu-stat-value">{{ $stats['pending'] }}</div>
                            <div class="gu-stat-label">Pendientes</div>
                        </div>
                    </div>
                    <div class="gu-stat">
                        <div class="gu-stat-icon gu-stat-icon--green"><i class="bi bi-check-circle" aria-hidden="true"></i></div>
                        <div class="gu-stat-main">
                            <div class="gu-stat-value">{{ $stats['approved'] }}</div>
                            <div class="gu-stat-label">Aprobados</div>
                        </div>
                    </div>
                    <div class="gu-stat">
                        <div class="gu-stat-icon gu-stat-icon--red"><i class="bi bi-x-circle" aria-hidden="true"></i></div>
                        <div class="gu-stat-main">
                            <div class="gu-stat-value">{{ $stats['rejected'] }}</div>
                            <div class="gu-stat-label">Rechazados</div>
                        </div>
                    </div>
                    <div class="gu-stat">
                        <div class="gu-stat-icon gu-stat-icon--blue"><i class="bi bi-currency-dollar" aria-hidden="true"></i></div>
                        <div class="gu-stat-main">
                            <div class="gu-stat-value">{{ $stats['paid'] }}</div>
                            <div class="gu-stat-label">Pagados</div>
                        </div>
                    </div>
                </section>

                <section class="gu-table-section">
                    <div class="gu-table-container">
                        <table class="gu-table">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Mascota</th>
                                    <th>Servicio</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($approvals as $approval)
                                    <tr>
                                        <td>
                                            <div class="gu-user-cell">
                                                <div class="gu-avatar">{{ mb_strtoupper(mb_substr($approval->usuario->name ?? 'U', 0, 1)) }}</div>
                                                <div class="gu-user-info">
                                                    <div class="gu-user-name">{{ $approval->usuario->name ?? '—' }}</div>
                                                    <div class="gu-user-email">{{ $approval->usuario->email ?? '—' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $approval->mascota->nombre ?? '—' }}</td>
                                        <td>
                                            <div>
                                                <div class="gu-service-name">{{ $approval->servicio->nombre ?? '—' }}</div>
                                                <div class="gu-service-price">${{ number_format($approval->servicio->precio ?? 0, 0, ',', '.') }}</div>
                                            </div>
                                        </td>
                                        <td>{{ $approval->fecha_solicitada->format('d M Y') }}</td>
                                        <td>
                                            <span class="gu-status gu-status--{{ $approval->estado }}">
                                                {{ ucfirst($approval->estado) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($approval->estado === 'pendiente')
                                                <button class="gu-btn gu-btn--success gu-btn--small" onclick="approveApproval({{ $approval->id }})">
                                                    <i class="bi bi-check"></i> Aprobar
                                                </button>
                                                <button class="gu-btn gu-btn--danger gu-btn--small" onclick="rejectApproval({{ $approval->id }})">
                                                    <i class="bi bi-x"></i> Rechazar
                                                </button>
                                            @elseif ($approval->estado === 'aprobado')
                                                <button class="gu-btn gu-btn--primary gu-btn--small" onclick="confirmPayment({{ $approval->id }})">
                                                    <i class="bi bi-cash"></i> Confirmar Pago
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6">No hay solicitudes de servicios.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </main>
        </div>

        <!-- Modal de Aprobación -->
        <div class="gu-modal" id="approveModal" aria-hidden="true">
            <div class="gu-modal-backdrop" data-gu-action="close-modal"></div>
            <div class="gu-modal-card" role="dialog" aria-modal="true" aria-label="Aprobar Servicio">
                <div class="gu-modal-head">
                    <div class="gu-modal-title">Aprobar Servicio</div>
                    <button type="button" class="gu-modal-x" aria-label="Cerrar" data-gu-action="close-modal">×</button>
                </div>
                <form class="gu-modal-form" method="POST" action="#" id="approveForm">
                    @csrf
                    <div class="gu-modal-body">
                        <p>¿Estás seguro de aprobar esta solicitud de servicio?</p>
                        <div class="gu-field">
                            <label class="gu-label">Notas para el cliente (opcional)</label>
                            <textarea class="gu-input gu-textarea" name="notas_admin" rows="3" placeholder="Agrega notas adicionales para el cliente..."></textarea>
                        </div>
                    </div>
                    <div class="gu-modal-actions">
                        <button type="button" class="gu-btn gu-btn--secondary" data-gu-action="close-modal">Cancelar</button>
                        <button type="submit" class="gu-btn gu-btn--success">Aprobar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal de Rechazo -->
        <div class="gu-modal" id="rejectModal" aria-hidden="true">
            <div class="gu-modal-backdrop" data-gu-action="close-modal"></div>
            <div class="gu-modal-card" role="dialog" aria-modal="true" aria-label="Rechazar Servicio">
                <div class="gu-modal-head">
                    <div class="gu-modal-title">Rechazar Servicio</div>
                    <button type="button" class="gu-modal-x" aria-label="Cerrar" data-gu-action="close-modal">×</button>
                </div>
                <form class="gu-modal-form" method="POST" action="#" id="rejectForm">
                    @csrf
                    <div class="gu-modal-body">
                        <p>¿Estás seguro de rechazar esta solicitud de servicio?</p>
                        <div class="gu-field">
                            <label class="gu-label">Motivo del rechazo *</label>
                            <textarea class="gu-input gu-textarea" name="notas_admin" rows="3" placeholder="Explica el motivo del rechazo..." required></textarea>
                        </div>
                    </div>
                    <div class="gu-modal-actions">
                        <button type="button" class="gu-btn gu-btn--secondary" data-gu-action="close-modal">Cancelar</button>
                        <button type="submit" class="gu-btn gu-btn--danger">Rechazar</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function approveApproval(id) {
                document.getElementById('approveForm').action = `/admin/approvals/${id}/approve`;
                document.getElementById('approveModal').classList.remove('gu-modal--hidden');
            }

            function rejectApproval(id) {
                document.getElementById('rejectForm').action = `/admin/approvals/${id}/reject`;
                document.getElementById('rejectModal').classList.remove('gu-modal--hidden');
            }

            function confirmPayment(id) {
                if (confirm('¿Estás seguro de confirmar el pago de este servicio?')) {
                    window.location.href = `/admin/approvals/${id}/confirm-payment`;
                }
            }

            // Cerrar modales
            document.querySelectorAll('[data-gu-action="close-modal"]').forEach(btn => {
                btn.addEventListener('click', () => {
                    btn.closest('.gu-modal').classList.add('gu-modal--hidden');
                });
            });

            // Cerrar modal al hacer clic fuera
            document.querySelectorAll('.gu-modal-backdrop').forEach(backdrop => {
                backdrop.addEventListener('click', () => {
                    backdrop.closest('.gu-modal').classList.add('gu-modal--hidden');
                });
            });
        </script>

        <style>
            .gu-modal {
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

            .gu-modal--hidden {
                display: none;
            }

            .gu-modal-backdrop {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
            }

            .gu-modal-card {
                position: relative;
                background: white;
                border-radius: 8px;
                max-width: 500px;
                width: 90%;
                max-height: 90vh;
                overflow-y: auto;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            }

            .gu-modal-head {
                padding: 20px;
                border-bottom: 1px solid #e5e7eb;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .gu-modal-title {
                font-size: 18px;
                font-weight: 600;
                color: #111827;
            }

            .gu-modal-x {
                background: none;
                border: none;
                font-size: 24px;
                cursor: pointer;
                color: #6b7280;
            }

            .gu-modal-body {
                padding: 20px;
            }

            .gu-modal-actions {
                padding: 20px;
                border-top: 1px solid #e5e7eb;
                display: flex;
                gap: 12px;
                justify-content: flex-end;
            }

            .gu-field {
                margin-bottom: 16px;
            }

            .gu-label {
                display: block;
                margin-bottom: 4px;
                font-weight: 500;
                color: #374151;
            }

            .gu-input {
                width: 100%;
                padding: 8px 12px;
                border: 1px solid #d1d5db;
                border-radius: 4px;
                font-size: 14px;
            }

            .gu-textarea {
                resize: vertical;
                min-height: 80px;
            }

            .gu-btn {
                padding: 8px 16px;
                border: none;
                border-radius: 4px;
                font-size: 14px;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }

            .gu-btn--small {
                padding: 6px 12px;
                font-size: 13px;
            }

            .gu-btn--primary {
                background: #3b82f6;
                color: white;
            }

            .gu-btn--success {
                background: #10b981;
                color: white;
            }

            .gu-btn--danger {
                background: #ef4444;
                color: white;
            }

            .gu-btn--secondary {
                background: #f3f4f6;
                color: #374151;
            }

            .gu-user-cell {
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .gu-avatar {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: #3b82f6;
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 600;
            }

            .gu-user-info {
                flex: 1;
            }

            .gu-user-name {
                font-weight: 500;
                color: #111827;
            }

            .gu-user-email {
                font-size: 13px;
                color: #6b7280;
            }

            .gu-service-name {
                font-weight: 500;
                color: #111827;
            }

            .gu-service-price {
                font-size: 13px;
                color: #10b981;
                font-weight: 600;
            }

            .gu-status {
                padding: 4px 8px;
                border-radius: 12px;
                font-size: 12px;
                font-weight: 500;
                text-transform: lowercase;
            }

            .gu-status--pendiente {
                background: #fef3c7;
                color: #92400e;
            }

            .gu-status--aprobado {
                background: #d1fae5;
                color: #065f46;
            }

            .gu-status--rechazado {
                background: #fee2e2;
                color: #991b1b;
            }

            .gu-status--pagado {
                background: #dbeafe;
                color: #1e40af;
            }
        </style>
    </body>
</html>
