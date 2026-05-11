<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Mascotas Asignadas</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('css/shared/mq-topbar.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dueño/panel.css') }}">
        <link rel="stylesheet" href="{{ asset('css/entrenador/dashboardentrenador.css') }}">
        <link rel="stylesheet" href="{{ asset('css/entrenador/mascotas.css') }}">
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
                    'user' => Auth::user(),
                    'roleLabel' => 'Entrenador',
                    'profileUrl' => route('entrenador.perfil'),
                    'settingsUrl' => route('entrenador.perfil'),
                    'helpUrl' => route('entrenador.chat'),
                    'notificationsUrl' => route('entrenador.notificaciones'),
                    'notifCount' => 2,
                ])

                <section class="ma-grid" aria-label="Mascotas asignadas">
                    @foreach (($pets ?? []) as $pet)
                        <article class="ma-card">
                            <div class="ma-top">
                                <div class="ma-avatar" aria-hidden="true">
                                    <i class="bi bi-person" aria-hidden="true"></i>
                                </div>
                                <div class="ma-main">
                                    <div class="ma-name">{{ $pet['name'] ?? '' }}</div>
                                    <div class="ma-breed">{{ $pet['breed'] ?? '' }}</div>
                                </div>
                            </div>

                            <div class="ma-info">
                                <div class="ma-line">
                                    <span class="ma-k">Edad:</span>
                                    <span class="ma-v">{{ $pet['age'] ?? '' }}</span>
                                </div>
                                <div class="ma-line">
                                    <span class="ma-k">Dueno:</span>
                                    <span class="ma-v ma-v--purple">{{ $pet['owner'] ?? '' }}</span>
                                </div>
                                <div class="ma-line">
                                    <span class="ma-k">Telefono:</span>
                                    <span class="ma-v ma-v--purple">{{ $pet['phone'] ?? '' }}</span>
                                </div>
                            </div>

                            <div class="ma-tags" aria-label="Servicios">
                                @foreach (($pet['tags'] ?? []) as $tag)
                                    <span class="ma-tag">{{ $tag }}</span>
                                @endforeach
                            </div>

                            <div class="ma-actions">
                                <a class="ma-btn ma-btn--primary" href="#">Seguimiento</a>
                                <a class="ma-btn ma-btn--ghost" href="#">Mensaje</a>
                            </div>
                        </article>
                    @endforeach
                </section>
            </main>
        </div>
    </body>
</html>
