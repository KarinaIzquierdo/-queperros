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
        <link rel="stylesheet" href="{{ asset('css/Admin/aprobaciones-v2.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    </head>
    <body>
        @include('partials.page-loader')
        <div class="admin-layout">
            @include('partials.admin-sidebar')
            <main class="admin-main">
                @include('partials.mq-topbar', [
                    'mqTopbarUser' => $admin,
                    'mqTopbarName' => $admin->name,
                    'roleLabel' => 'Administrador',
                    'profileUrl' => route('admin.settings'),
                    'settingsUrl' => route('admin.settings'),
                    'helpUrl' => route('admin.dashboard'),
                    'notificationsUrl' => route('admin.notificaciones'),
                    'notifCount' => DB::table('notifications')->where('id_usuario', $admin->id)->where('leido', false)->count(),
                ])

                <section class="admin-page">
                    <div class="page-header">
                        <h1 class="page-title">Aprobación de Servicios</h1>
                        <p class="page-description">Gestiona las solicitudes de servicios de los clientes</p>
                    </div>

                    <!-- Estadísticas -->
                    <div class="stats-grid">
                        <div class="stat-card stat-card--primary">
                            <div class="stat-icon">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $stats['pending'] ?? 0 }}</div>
                                <div class="stat-label">Pendientes</div>
                            </div>
                        </div>
                        
                        <div class="stat-card stat-card--success">
                            <div class="stat-icon">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $stats['approved'] ?? 0 }}</div>
                                <div class="stat-label">Aprobados</div>
                            </div>
                        </div>
                        
                        <div class="stat-card stat-card--danger">
                            <div class="stat-icon">
                                <i class="bi bi-x-circle"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $stats['rejected'] ?? 0 }}</div>
                                <div class="stat-label">Rechazados</div>
                            </div>
                        </div>
                        
                        <div class="stat-card stat-card--info">
                            <div class="stat-icon">
                                <i class="bi bi-cash"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $stats['paid'] ?? 0 }}</div>
                                <div class="stat-label">Pagados</div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="filters-section">
                        <div class="filter-group">
                            <button class="filter-btn filter-btn--active" data-filter="all">Todos</button>
                            <button class="filter-btn" data-filter="pending">Pendientes</button>
                            <button class="filter-btn" data-filter="approved">Aprobados</button>
                            <button class="filter-btn" data-filter="rejected">Rechazados</button>
                            <button class="filter-btn" data-filter="paid">Pagados</button>
                        </div>
                    </div>

                    <!-- Lista de Aprobaciones -->
                    <div class="approvals-list">
                        @forelse ($approvals as $approval)
                            <div class="approval-card approval-card--{{ $approval->estado ?? 'unknown' }}" data-status="{{ $approval->estado ?? 'unknown' }}">
                                <div class="approval-header">
                                    <div class="approval-info">
                                        <h3 class="approval-service">{{ $approval->servicio_nombre ?? 'Servicio desconocido' }}</h3>
                                        <div class="approval-meta">
                                            <span class="approval-client">
                                                <i class="bi bi-person"></i>
                                                {{ $approval->usuario_nombre ?? 'Cliente' }}
                                            </span>
                                            <span class="approval-pet">
                                                <i class="bi bi-heart"></i>
                                                {{ $approval->mascota_nombre ?? 'Mascota' }}
                                            </span>
                                            <span class="approval-date">
                                                <i class="bi bi-calendar"></i>
                                                {{ \Carbon\Carbon::parse($approval->fecha_solicitada)->format('d M Y') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="approval-status">
                                        @if ($approval->estado === 'aprobado')
                                            <span class="status-badge status-badge--aprobado">
                                                Aprobado (esperando pago)
                                            </span>
                                        @else
                                            <span class="status-badge status-badge--{{ strtolower($approval->estado ?? 'unknown') }}">
                                                {{ ucfirst($approval->estado ?? 'Desconocido') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="approval-content">
                                    @if ($approval->notas_cliente)
                                        <div class="approval-notes">
                                            <strong>Notas del cliente:</strong>
                                            <p>{{ $approval->notas_cliente }}</p>
                                        </div>
                                    @endif
                                    
                                    @if ($approval->notas_admin)
                                        <div class="approval-notes approval-notes--admin">
                                            <strong>Notas del administrador:</strong>
                                            <p>{{ $approval->notas_admin }}</p>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="approval-actions">
                                    @if ($approval->estado === 'pendiente')
                                        <button class="btn btn--success btn--sm" onclick="openApproveModal({{ $approval->id }})">
                                            <i class="bi bi-check-circle"></i>
                                            Aprobar
                                        </button>
                                        <button class="btn btn--danger btn--sm" onclick="openRejectModal({{ $approval->id }})">
                                            <i class="bi bi-x-circle"></i>
                                            Rechazar
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <h3>No hay solicitudes de servicios</h3>
                                <p>No hay solicitudes de servicios para mostrar en este momento.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Modal de Aprobación -->
                    <div class="modal" id="approveModal" style="display: none;">
                        <div class="modal-backdrop" onclick="closeApproveModal()"></div>
                        <div class="modal-card">
                            <div class="modal-header">
                                <h3>Aprobar Servicio</h3>
                                <button class="modal-close" onclick="closeApproveModal()">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                            <form method="POST" action="{{ route('admin.approvals.approve', ':id') }}" id="approveForm">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="approve_notes">Notas del administrador (opcional)</label>
                                        <textarea name="notas_admin" id="approve_notes" rows="3" placeholder="Agrega notas sobre la aprobación..."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn--secondary" onclick="closeApproveModal()">Cancelar</button>
                                    <button type="submit" class="btn btn--success">Aprobar Servicio</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Modal de Rechazo -->
                    <div class="modal" id="rejectModal" style="display: none;">
                        <div class="modal-backdrop" onclick="closeRejectModal()"></div>
                        <div class="modal-card">
                            <div class="modal-header">
                                <h3>Rechazar Servicio</h3>
                                <button class="modal-close" onclick="closeRejectModal()">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                            <form method="POST" action="{{ route('admin.approvals.reject', ':id') }}" id="rejectForm">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="reject_notes">Motivo del rechazo *</label>
                                        <textarea name="notas_admin" id="reject_notes" rows="3" required placeholder="Explica por qué se rechaza este servicio..."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn--secondary" onclick="closeRejectModal()">Cancelar</button>
                                    <button type="submit" class="btn btn--danger">Rechazar Servicio</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Modal de Confirmación de Pago -->
                    <div class="modal" id="paymentModal" style="display: none;">
                        <div class="modal-backdrop" onclick="closePaymentModal()"></div>
                        <div class="modal-card">
                            <div class="modal-header">
                                <h3>Confirmar Pago</h3>
                                <button class="modal-close" onclick="closePaymentModal()">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                            <form method="POST" action="{{ route('admin.approvals.confirmPayment', ':id') }}" id="paymentForm">
                                @csrf
                                <div class="modal-body">
                                    <p>¿Estás seguro de que deseas confirmar el pago de este servicio?</p>
                                    <p class="text-muted">Esta acción creará la reserva definitiva en el sistema.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn--secondary" onclick="closePaymentModal()">Cancelar</button>
                                    <button type="submit" class="btn btn--primary">Confirmar Pago</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            </main>
        </div>

        
        <script>
        // Guardar las rutas base para no perder el placeholder
        const approveBaseUrl = "{{ route('admin.approvals.approve', ':id') }}";
        const rejectBaseUrl = "{{ route('admin.approvals.reject', ':id') }}";
        const paymentBaseUrl = "{{ route('admin.approvals.confirmPayment', ':id') }}";

        function openApproveModal(approvalId) {
            const modal = document.getElementById('approveModal');
            const form = document.getElementById('approveForm');
            form.action = approveBaseUrl.replace(':id', approvalId);
            modal.style.display = 'flex';
        }

        function closeApproveModal() {
            document.getElementById('approveModal').style.display = 'none';
        }

        function openRejectModal(approvalId) {
            const modal = document.getElementById('rejectModal');
            const form = document.getElementById('rejectForm');
            form.action = rejectBaseUrl.replace(':id', approvalId);
            modal.style.display = 'flex';
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').style.display = 'none';
            document.getElementById('reject_notes').value = '';
        }

        function openPaymentModal(approvalId) {
            const modal = document.getElementById('paymentModal');
            const form = document.getElementById('paymentForm');
            form.action = paymentBaseUrl.replace(':id', approvalId);
            modal.style.display = 'flex';
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').style.display = 'none';
        }

        // Filtros
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const approvalCards = document.querySelectorAll('.approval-card');

            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const filter = this.dataset.filter;
                    
                    // Actualizar botón activo
                    filterButtons.forEach(btn => btn.classList.remove('filter-btn--active'));
                    this.classList.add('filter-btn--active');
                    
                    // Filtrar tarjetas
                    approvalCards.forEach(card => {
                        if (filter === 'all' || card.dataset.status === filter) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });
        });
        </script>
    </body>
</html>
