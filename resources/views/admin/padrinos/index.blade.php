<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Plan Padrino</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/shared/mq-topbar.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/admin-dashboard.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/admin-sidebar-extras.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/dashboard-admin-v2.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/dueño/planpadrino.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <style>
            .ap-page{padding:24px}.ap-head{display:flex;justify-content:space-between;gap:16px;align-items:flex-start;margin-bottom:20px}.ap-title{font-family:'Lilita One',cursive;font-size:34px;margin:0;color:#2b2740}.ap-sub{margin:6px 0 0;color:#7b748f;font-weight:700}.ap-form,.ap-list{background:#fff;border:1px solid rgba(15,23,42,.08);border-radius:18px;padding:18px;box-shadow:0 14px 34px rgba(15,23,42,.08);margin-bottom:18px}.ap-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px}.ap-field{display:flex;flex-direction:column;gap:6px}.ap-field--full{grid-column:1/-1}.ap-label{font-size:12px;font-weight:900;color:#8a84a3}.ap-input,.ap-textarea,.ap-select{border:1px solid rgba(15,23,42,.12);border-radius:12px;padding:10px 12px;font-weight:700;outline:none}.ap-textarea{min-height:92px;resize:vertical}.ap-check{display:flex;align-items:center;gap:8px;font-weight:800;color:#4c4763}.ap-actions{display:flex;justify-content:flex-end;gap:10px;margin-top:14px}.ap-btn{border:0;border-radius:12px;padding:10px 14px;font-weight:900;cursor:pointer}.ap-btn--primary{background:#7c3aed;color:#fff}.ap-btn--danger{background:#fee2e2;color:#b91c1c}.ap-btn--soft{background:#f1efff;color:#6d28d9}.ap-table{width:100%;border-collapse:collapse}.ap-table th,.ap-table td{padding:12px;border-bottom:1px solid rgba(15,23,42,.08);text-align:left;font-size:13px}.ap-table th{color:#8a84a3;font-size:11px;text-transform:uppercase}.ap-dog{display:flex;align-items:center;gap:10px;font-weight:900}.ap-photo{width:46px;height:46px;border-radius:12px;object-fit:cover;background:#f1efff}.ap-status{display:inline-flex;border-radius:999px;padding:6px 10px;background:#ecfdf5;color:#15803d;font-weight:900;font-size:12px}.ap-edit{margin-top:10px;padding-top:10px;border-top:1px dashed rgba(15,23,42,.12)}@media(max-width:900px){.ap-grid{grid-template-columns:1fr}.ap-head{flex-direction:column}.ap-table{display:block;overflow:auto}}
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
                    'notifCount' => 0,
                ])
                <section class="ap-page">
                    <div class="ap-head">
                        <div>
                            <h1 class="ap-title">Plan Padrino</h1>
                            <p class="ap-sub">Publica perros disponibles para apadrinar en el panel del dueño y en la página principal.</p>
                        </div>
                    </div>

                    @if (session('status'))
                        <div class="ap-form">{{ session('status') }}</div>
                    @endif

                    <form class="ap-form" action="{{ route('admin.planpadrino.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="ap-grid">
                            <div class="ap-field"><label class="ap-label">Nombre</label><input class="ap-input" name="nombre" required></div>
                            <div class="ap-field"><label class="ap-label">Raza</label><input class="ap-input" name="raza"></div>
                            <div class="ap-field"><label class="ap-label">Edad</label><input class="ap-input" name="edad" type="number" min="0"></div>
                            <div class="ap-field"><label class="ap-label">Sexo</label><select class="ap-select" name="sexo"><option value="">Seleccionar</option><option>Macho</option><option>Hembra</option></select></div>
                            <div class="ap-field"><label class="ap-label">Foto</label><input class="ap-input" name="foto" type="file" accept="image/*"></div>
                            <div class="ap-field"><label class="ap-label">Meta mensual</label><input class="ap-input" name="meta_mensual" type="number" min="0" placeholder="Ej: 300000"></div>
                            <input type="hidden" name="estado" value="Disponible">
                            <label class="ap-check"><input name="publicado" type="checkbox" value="1" checked> Publicado</label>
                            <div class="ap-field ap-field--full"><label class="ap-label">Necesidades</label><input class="ap-input" name="necesidades" placeholder="Alimento, vacunas, medicina"></div>
                            <div class="ap-field ap-field--full"><label class="ap-label">Historia</label><textarea class="ap-textarea" name="historia" placeholder="Cuenta la historia del perrito y por qué necesita padrino"></textarea></div>
                        </div>
                        <div class="ap-actions"><button class="ap-btn ap-btn--primary" type="submit">Publicar perrito</button></div>
                    </form>

                    <div class="ap-list">
                        <table class="ap-table">
                            <thead><tr><th>Perrito</th><th>Necesidades</th><th>Meta</th><th>Estado</th><th>Publicado</th><th>Acciones</th></tr></thead>
                            <tbody>
                                @forelse ($dogs as $dog)
                                    <tr>
                                        <td><div class="ap-dog"><img class="ap-photo" src="{{ $dog->foto ? asset('storage/' . ltrim($dog->foto, '/')) : asset('img/pet.png') }}" alt="{{ $dog->nombre }}"><div>{{ $dog->nombre }}<br><span style="font-weight:700;color:#8a84a3">{{ collect([$dog->raza, $dog->edad ? $dog->edad . ' años' : null, $dog->sexo])->filter()->implode(' • ') }}</span></div></div></td>
                                        <td>{{ $dog->necesidades ?: '—' }}</td>
                                        <td>{{ $dog->meta_mensual ? '$' . number_format($dog->meta_mensual, 0, ',', '.') : '—' }}</td>
                                        <td><span class="ap-status">{{ $dog->estado }}</span></td>
                                        <td>{{ $dog->publicado ? 'Sí' : 'No' }}</td>
                                        <td>
                                            <details>
                                                <summary class="ap-btn ap-btn--soft">Editar</summary>
                                                <form class="ap-edit" action="{{ route('admin.planpadrino.update', $dog) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="ap-grid">
                                                        <div class="ap-field"><label class="ap-label">Nombre</label><input class="ap-input" name="nombre" value="{{ $dog->nombre }}" required></div>
                                                        <div class="ap-field"><label class="ap-label">Raza</label><input class="ap-input" name="raza" value="{{ $dog->raza }}"></div>
                                                        <div class="ap-field"><label class="ap-label">Edad</label><input class="ap-input" name="edad" type="number" min="0" value="{{ $dog->edad }}"></div>
                                                        <div class="ap-field"><label class="ap-label">Sexo</label><input class="ap-input" name="sexo" value="{{ $dog->sexo }}"></div>
                                                        <div class="ap-field"><label class="ap-label">Foto</label><input class="ap-input" name="foto" type="file" accept="image/*"></div>
                                                        <div class="ap-field"><label class="ap-label">Meta</label><input class="ap-input" name="meta_mensual" type="number" min="0" value="{{ $dog->meta_mensual }}"></div>
                                                        <input type="hidden" name="estado" value="Disponible">
                                                        <label class="ap-check"><input name="publicado" type="checkbox" value="1" @checked($dog->publicado)> Publicado</label>
                                                        <div class="ap-field ap-field--full"><label class="ap-label">Necesidades</label><input class="ap-input" name="necesidades" value="{{ $dog->necesidades }}"></div>
                                                        <div class="ap-field ap-field--full"><label class="ap-label">Historia</label><textarea class="ap-textarea" name="historia">{{ $dog->historia }}</textarea></div>
                                                    </div>
                                                    <div class="ap-actions"><button class="ap-btn ap-btn--primary" type="submit">Guardar</button></div>
                                                </form>
                                            </details>
                                            <form action="{{ route('admin.planpadrino.destroy', $dog) }}" method="POST" onsubmit="return confirm('¿Eliminar esta publicación?')" style="margin-top:8px">
                                                @csrf
                                                @method('DELETE')
                                                <button class="ap-btn ap-btn--danger" type="submit">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6">No hay perritos publicados todavía.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </main>
        </div>
    </body>
</html>
