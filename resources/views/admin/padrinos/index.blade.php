<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Muro de Héroes | Más Que Perros</title>
        <link rel="icon" type="image/png" href="{{ asset('img/huellita.png') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/shared/mq-topbar.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/admin-dashboard.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/admin-sidebar-extras.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/dashboard-admin-v2.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/dueño/planpadrino.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <style>
            .ap-page { padding: 28px; background-color: #f8fafc; min-height: 100vh; }
            .ap-head { display: flex; justify-content: space-between; gap: 16px; align-items: flex-start; margin-bottom: 28px; }
            .ap-title { font-family: 'Lilita One', cursive; font-size: 36px; margin: 0; color: #2b2740; letter-spacing: 0.02em; }
            .ap-sub { margin: 6px 0 0; color: #7b748f; font-weight: 600; font-size: 15px; }
            
            .ap-card { background: #fff; border: 1px solid rgba(15, 23, 42, 0.06); border-radius: 20px; padding: 28px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.03); margin-bottom: 28px; }
            .ap-card-title { font-size: 18px; font-weight: 800; color: #2b2740; margin-top: 0; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
            .ap-card-title i { color: #7c3aed; font-size: 20px; }
            
            .ap-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 18px; }
            .ap-field { display: flex; flex-direction: column; gap: 8px; }
            .ap-field--full { grid-column: 1 / -1; }
            .ap-label { font-size: 12px; font-weight: 800; color: #6d6b8b; text-transform: uppercase; letter-spacing: 0.05em; }
            
            .ap-input, .ap-textarea, .ap-select { border: 1px solid rgba(15, 23, 42, 0.12); border-radius: 12px; padding: 11px 14px; font-weight: 600; outline: none; transition: all 0.2s ease; background: #faf9fd; color: #2b2740; font-size: 14px; }
            .ap-input::placeholder, .ap-textarea::placeholder { color: #b0abc4; font-weight: 500; }
            .ap-input:hover, .ap-textarea:hover, .ap-select:hover { border-color: rgba(124, 58, 237, 0.4); background: #fff; }
            .ap-input:focus, .ap-textarea:focus, .ap-select:focus { border-color: #7c3aed; background: #fff; box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.15); }
            
            .ap-textarea { min-height: 100px; resize: vertical; }
            
            .custom-file-upload { display: flex; align-items: center; gap: 10px; border: 1px solid rgba(15, 23, 42, 0.12); border-radius: 12px; padding: 7px 10px; background: #faf9fd; transition: all 0.2s ease; cursor: pointer; }
            .custom-file-upload:hover { border-color: rgba(124, 58, 237, 0.4); background: #fff; }
            .custom-file-upload input[type="file"] { display: none; }
            .file-upload-btn { background: #f1efff; color: #6d28d9; padding: 6px 12px; border-radius: 8px; font-weight: 800; font-size: 12px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s ease; }
            .file-upload-btn:hover { background: #7c3aed; color: #fff; }
            .file-name-text { font-size: 12px; color: #8a84a3; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 150px; }
            
            .input-with-icon { position: relative; display: flex; align-items: center; }
            .input-with-icon .ap-input { padding-left: 28px; width: 100%; }
            .input-icon { position: absolute; left: 12px; font-weight: 800; color: #8a84a3; font-size: 14px; }
            
            .ap-switch-field { flex-direction: row; justify-content: space-between; align-items: center; border: 1px solid rgba(15, 23, 42, 0.12); border-radius: 12px; padding: 10px 14px; background: #faf9fd; height: 100%; display: flex; }
            .switch { position: relative; display: inline-block; width: 44px; height: 24px; margin: 0; }
            .switch input { opacity: 0; width: 0; height: 0; }
            .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .3s; }
            .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .3s; }
            input:checked + .slider { background-color: #10b981; }
            input:checked + .slider:before { transform: translateX(20px); }
            .slider.round { border-radius: 24px; }
            .slider.round:before { border-radius: 50%; }
            
            .ap-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; }
            .ap-btn { border: 0; border-radius: 12px; padding: 11px 22px; font-weight: 800; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s ease; font-size: 14px; }
            .ap-btn--primary { background: #7c3aed; color: #fff; box-shadow: 0 4px 14px rgba(124, 58, 237, 0.3); }
            .ap-btn--primary:hover { background: #6d28d9; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(124, 58, 237, 0.4); }
            .ap-btn--danger { background: #fee2e2; color: #b91c1c; }
            .ap-btn--danger:hover { background: #fca5a5; }
            .ap-btn--soft { background: #f1efff; color: #6d28d9; }
            .ap-btn--soft:hover { background: #e0dbff; }
            
            .ap-table-wrapper { overflow-x: auto; }
            .ap-table { width: 100%; border-collapse: collapse; }
            .ap-table th { color: #8a84a3; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid rgba(15, 23, 42, 0.06); padding: 14px 16px; text-align: left; }
            .ap-table td { padding: 16px; border-bottom: 1px solid rgba(15, 23, 42, 0.06); color: #4c4763; font-weight: 600; font-size: 13px; vertical-align: middle; }
            .ap-table tr:hover td { background-color: #faf9fe; }
            .ap-table tr:last-child td { border-bottom: none; }
            
            .ap-dog { display: flex; align-items: center; gap: 12px; font-weight: 800; color: #2b2740; }
            .ap-photo { width: 50px; height: 50px; border-radius: 14px; object-fit: cover; background: #f1efff; box-shadow: 0 4px 10px rgba(15, 23, 42, 0.05); }
            .ap-status { display: inline-flex; align-items: center; gap: 6px; border-radius: 30px; padding: 6px 12px; background: #ecfdf5; color: #065f46; font-weight: 800; font-size: 12px; border: 1px solid #a7f3d0; }
            .ap-status-no { display: inline-flex; align-items: center; gap: 6px; border-radius: 30px; padding: 6px 12px; background: #f1f5f9; color: #475569; font-weight: 800; font-size: 12px; border: 1px solid #e2e8f0; }
            
            .ap-row-actions { display: flex; gap: 8px; align-items: center; }
            .ap-btn-icon { border: none; border-radius: 10px; padding: 7px 14px; font-size: 12px; font-weight: 800; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s ease; }
            .ap-btn-icon--edit { background: #f1efff; color: #6d28d9; }
            .ap-btn-icon--edit:hover { background: #7c3aed; color: #fff; box-shadow: 0 4px 12px rgba(124, 58, 237, 0.2); }
            .ap-btn-icon--danger { background: #fee2e2; color: #ef4444; }
            .ap-btn-icon--danger:hover { background: #ef4444; color: #fff; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2); }
            
            .ap-alert { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; border-radius: 12px; padding: 14px 20px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; font-weight: 700; font-size: 14px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.05); }
            .ap-alert i { font-size: 18px; color: #10b981; }
            
            /* Modal overlay styling */
            .ap-modal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; opacity: 0; pointer-events: none; transition: all 0.3s ease; z-index: 1000; }
            .ap-modal.modal-active { opacity: 1; pointer-events: auto; }
            .ap-modal-content { background: #fff; border-radius: 24px; width: 90%; max-width: 680px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); transform: scale(0.95); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); padding: 28px; }
            .ap-modal.modal-active .ap-modal-content { transform: scale(1); }
            .ap-modal-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(15,23,42,.08); padding-bottom: 16px; margin-bottom: 24px; }
            .ap-modal-title { font-family: 'Lilita One', cursive; font-size: 26px; color: #2b2740; }
            .ap-modal-close { background: none; border: none; font-size: 28px; color: #8a84a3; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 50%; transition: all 0.2s ease; }
            .ap-modal-close:hover { background: #fee2e2; color: #ef4444; }
            
            @media(max-width: 900px) {
                .ap-grid { grid-template-columns: 1fr; }
                .ap-head { flex-direction: column; }
                .ap-table { display: block; overflow: auto; }
            }
        </style>
    </head>
    <body>
        @include('partials.page-loader')
        <div class="admin-layout">
            @include('partials.admin-sidebar')
            <main class="admin-main">
                @include('partials.mq-topbar', [
                    'user' => $admin,
                    'roleLabel' => 'Administrador',
                    'profileUrl' => route('admin.settings'),
                    'settingsUrl' => route('admin.settings'),
                    'helpUrl' => route('admin.dashboard'),
                    'notificationsUrl' => route('admin.notificaciones'),
                ])
                <section class="ap-page">
                    <div class="ap-head">
                        <div>
                            <h1 class="ap-title">Plan Padrino</h1>
                            <p class="ap-sub">Publica perros disponibles para apadrinar en el panel del dueño y en la página principal.</p>
                        </div>
                    </div>

                    @if (session('status') || session('success'))
                        <div class="ap-alert">
                            <i class="bi bi-check-circle-fill"></i>
                            <div>{{ session('status') ?: session('success') }}</div>
                        </div>
                    @endif

                    <div class="ap-card">
                        <div class="ap-card-title">
                            <i class="fa-solid fa-dog"></i> Agregar Nuevo Perrito
                        </div>
                        <form action="{{ route('admin.planpadrino.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="ap-grid">
                                <div class="ap-field"><label class="ap-label">Nombre</label><input class="ap-input" name="nombre" required placeholder="Nombre del perrito"></div>
                                <div class="ap-field"><label class="ap-label">Raza</label><input class="ap-input" name="raza" placeholder="Ej: Criollo, Labrador"></div>
                                <div class="ap-field"><label class="ap-label">Edad</label><input class="ap-input" name="edad" type="number" min="0" placeholder="Ej: 3"></div>
                                <div class="ap-field">
                                    <label class="ap-label">Sexo</label>
                                    <select class="ap-select" name="sexo">
                                        <option value="">Seleccionar</option>
                                        <option value="Macho">Macho</option>
                                        <option value="Hembra">Hembra</option>
                                    </select>
                                </div>
                                <div class="ap-field">
                                    <label class="ap-label">Foto</label>
                                    <div class="custom-file-upload">
                                        <label for="foto-create" class="file-upload-btn">
                                            <i class="bi bi-cloud-upload"></i> Subir foto
                                        </label>
                                        <input id="foto-create" name="foto" type="file" accept="image/*" onchange="updateFileName(this, 'file-name-create')">
                                        <span id="file-name-create" class="file-name-text">Sin archivo</span>
                                    </div>
                                </div>
                                <div class="ap-field" style="display: none;">
                                    <label class="ap-label">Meta mensual</label>
                                    <div class="input-with-icon">
                                        <span class="input-icon">$</span>
                                        <input class="ap-input" name="meta_mensual" type="number" value="700000">
                                    </div>
                                </div>
                                <input type="hidden" name="estado" value="Disponible">
                                <div class="ap-field">
                                    <div class="ap-switch-field">
                                        <span class="ap-label" style="margin: 0;">Publicado</span>
                                        <label class="switch">
                                            <input name="publicado" type="checkbox" value="1" checked>
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="ap-field ap-field--full"><label class="ap-label">Necesidades</label><input class="ap-input" name="necesidades" placeholder="Alimento, vacunas, medicina"></div>
                                <div class="ap-field ap-field--full"><label class="ap-label">Historia</label><textarea class="ap-textarea" name="historia" placeholder="Cuenta la historia del perrito y por qué necesita padrino"></textarea></div>
                            </div>
                            <div class="ap-actions">
                                <button class="ap-btn ap-btn--primary" type="submit">
                                    <i class="bi bi-plus-circle"></i> Publicar perrito
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="ap-card">
                        <div class="ap-card-title">
                            <i class="bi bi-people-fill"></i> Padrinos Recientes
                        </div>
                        <div class="ap-table-wrapper">
                            <table class="ap-table">
                                <thead>
                                    <tr>
                                        <th>Padrino</th>
                                        <th>Perrito</th>
                                        <th>Monto Mensual</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($sponsorships as $spon)
                                        <tr>
                                            <td>
                                                <div style="font-weight: 800; color: #2b2740;">
                                                    {{ $spon->guest_name ?: ($spon->user_name ?: 'Padrino') }}
                                                </div>
                                                <div style="font-size: 11px; color: #8a84a3;">
                                                    {{ $spon->guest_email ?: ($spon->user_email ?: 'Sin correo') }}
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $dog = $dogs->firstWhere('id', $spon->sponsor_dog_id);
                                                @endphp
                                                <div style="display: flex; align-items: center; gap: 8px;">
                                                    @if($dog)
                                                        <img class="ap-photo" style="width: 30px; height: 30px; border-radius: 8px;" src="{{ $dog->foto ? asset('storage/' . ltrim($dog->foto, '/')) : asset('img/pet.png') }}" alt="">
                                                        <span style="font-weight: 700;">{{ $dog->nombre }}</span>
                                                    @else
                                                        <span style="color: #8a84a3;">—</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td style="font-weight: 800; color: #3b82f6;">
                                                ${{ number_format($spon->monto_mensual, 0, ',', '.') }}
                                            </td>
                                            <td style="font-size: 12px; color: #4c4763;">
                                                {{ $spon->created_at ? \Carbon\Carbon::parse($spon->created_at)->format('d/m/Y') : '—' }}
                                            </td>
                                            <td>
                                                @if($spon->estado === 'Activo' || $spon->estado === 'pagado' || $spon->estado === 'Pagada')
                                                    <span class="ap-status">
                                                        <i class="bi bi-check-circle"></i> {{ ucfirst($spon->estado) }}
                                                    </span>
                                                @else
                                                    <span class="ap-status-no">
                                                        {{ ucfirst($spon->estado) }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" style="text-align: center; padding: 32px; color: #8a84a3;">
                                                No hay apadrinamientos registrados todavía.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="ap-card">
                        <div class="ap-card-title">
                            <i class="bi bi-list-stars"></i> Perritos Registrados
                        </div>
                        <div class="ap-table-wrapper">
                            <table class="ap-table">
                                <thead>
                                    <tr>
                                        <th>Perrito</th>
                                        <th>Necesidades</th>
                                        <th>Meta</th>
                                        <th>Estado</th>
                                        <th>Publicado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($dogs as $dog)
                                        <tr>
                                            <td>
                                                <div class="ap-dog">
                                                    <img class="ap-photo" src="{{ $dog->foto ? asset('storage/' . ltrim($dog->foto, '/')) : asset('img/pet.png') }}" alt="{{ $dog->nombre }}">
                                                    <div>
                                                        <span style="font-size: 15px; color: #2b2740;">{{ $dog->nombre }}</span><br>
                                                        <span style="font-weight:700;color:#8a84a3;font-size:11px;">
                                                            {{ collect([$dog->raza, $dog->edad ? $dog->edad . ' años' : null, $dog->sexo])->filter()->implode(' • ') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $dog->necesidades ?: '—' }}</td>
                                            <td>
                                                <span style="font-weight: 800; color: #4c4763;">
                                                    {{ $dog->meta_mensual ? '$' . number_format($dog->meta_mensual, 0, ',', '.') : '—' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($dog->estado === 'Disponible')
                                                    <span class="ap-status">
                                                        <i class="bi bi-check-circle"></i> Disponible
                                                    </span>
                                                @else
                                                    <span class="ap-status-no">
                                                        {{ $dog->estado }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($dog->publicado)
                                                    <span style="color: #10b981; font-weight: 800;"><i class="bi bi-eye"></i> Sí</span>
                                                @else
                                                    <span style="color: #64748b; font-weight: 800;"><i class="bi bi-eye-slash"></i> No</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="ap-row-actions">
                                                    <button class="ap-btn-icon ap-btn-icon--edit" onclick="openEditModal({{ $dog->id }})">
                                                        <i class="bi bi-pencil-fill"></i> Editar
                                                    </button>
                                                    <form action="{{ route('admin.planpadrino.destroy', $dog) }}" method="POST" onsubmit="return confirm('¿Eliminar esta publicación?')" style="margin:0;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="ap-btn-icon ap-btn-icon--danger" type="submit">
                                                            <i class="bi bi-trash-fill"></i> Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" style="text-align: center; padding: 32px; color: #8a84a3;">
                                                <i class="bi bi-emoji-neutral" style="font-size: 24px; display: block; margin-bottom: 8px;"></i>
                                                No hay perritos publicados todavía.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </main>
        </div>

        {{-- Modales de Edición --}}
        @foreach ($dogs as $dog)
            <div id="edit-modal-{{ $dog->id }}" class="ap-modal">
                <div class="ap-modal-content">
                    <div class="ap-modal-header">
                        <h2 class="ap-modal-title">Editar Perrito: {{ $dog->nombre }}</h2>
                        <button class="ap-modal-close" onclick="closeEditModal({{ $dog->id }})">&times;</button>
                    </div>
                    <form action="{{ route('admin.planpadrino.update', $dog) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="ap-grid">
                            <div class="ap-field"><label class="ap-label">Nombre</label><input class="ap-input" name="nombre" value="{{ $dog->nombre }}" required></div>
                            <div class="ap-field"><label class="ap-label">Raza</label><input class="ap-input" name="raza" value="{{ $dog->raza }}"></div>
                            <div class="ap-field"><label class="ap-label">Edad</label><input class="ap-input" name="edad" type="number" min="0" value="{{ $dog->edad }}"></div>
                            <div class="ap-field">
                                <label class="ap-label">Sexo</label>
                                <select class="ap-select" name="sexo">
                                    <option value="" @selected(empty($dog->sexo))>Seleccionar</option>
                                    <option value="Macho" @selected($dog->sexo === 'Macho')>Macho</option>
                                    <option value="Hembra" @selected($dog->sexo === 'Hembra')>Hembra</option>
                                </select>
                            </div>
                            <div class="ap-field">
                                <label class="ap-label">Foto</label>
                                <div class="custom-file-upload">
                                    <label for="foto-edit-{{ $dog->id }}" class="file-upload-btn">
                                        <i class="bi bi-cloud-upload"></i> Subir foto
                                    </label>
                                    <input id="foto-edit-{{ $dog->id }}" name="foto" type="file" accept="image/*" onchange="updateFileName(this, 'file-name-edit-{{ $dog->id }}')">
                                    <span id="file-name-edit-{{ $dog->id }}" class="file-name-text">Sin archivo</span>
                                </div>
                            </div>
                            <div class="ap-field" style="display: none;">
                                <label class="ap-label">Meta mensual</label>
                                <div class="input-with-icon">
                                    <span class="input-icon">$</span>
                                    <input class="ap-input" name="meta_mensual" type="number" value="700000">
                                </div>
                            </div>
                            <input type="hidden" name="estado" value="Disponible">
                            <div class="ap-field">
                                <div class="ap-switch-field">
                                    <span class="ap-label" style="margin: 0;">Publicado</span>
                                    <label class="switch">
                                        <input name="publicado" type="checkbox" value="1" @checked($dog->publicado)>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="ap-field ap-field--full"><label class="ap-label">Necesidades</label><input class="ap-input" name="necesidades" value="{{ $dog->necesidades }}"></div>
                            <div class="ap-field ap-field--full"><label class="ap-label">Historia</label><textarea class="ap-textarea" name="historia">{{ $dog->historia }}</textarea></div>
                        </div>
                        <div class="ap-actions">
                            <button class="ap-btn ap-btn--soft" type="button" onclick="closeEditModal({{ $dog->id }})">Cancelar</button>
                            <button class="ap-btn ap-btn--primary" type="submit">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

        <script>
            function updateFileName(input, targetId) {
                const span = document.getElementById(targetId);
                if (input.files && input.files.length > 0) {
                    span.textContent = input.files[0].name;
                } else {
                    span.textContent = 'Sin archivo';
                }
            }

            function openEditModal(dogId) {
                const modal = document.getElementById('edit-modal-' + dogId);
                if (modal) {
                    modal.classList.add('modal-active');
                    document.body.style.overflow = 'hidden'; // Evitar scroll de fondo
                }
            }

            function closeEditModal(dogId) {
                const modal = document.getElementById('edit-modal-' + dogId);
                if (modal) {
                    modal.classList.remove('modal-active');
                    document.body.style.overflow = ''; // Restaurar scroll
                }
            }

            // Cerrar modal al hacer clic fuera del contenido
            window.onclick = function(event) {
                if (event.target.classList.contains('ap-modal')) {
                    event.target.classList.remove('modal-active');
                    document.body.style.overflow = '';
                }
            }
        </script>
    </body>
</html>
