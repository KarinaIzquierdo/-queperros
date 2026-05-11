<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Mi Perfil</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('css/shared/mq-topbar.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dueño/panel.css') }}">
        <link rel="stylesheet" href="{{ asset('css/entrenador/dashboardentrenador.css') }}">
        <link rel="stylesheet" href="{{ asset('css/entrenador/perfil.css') }}">
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
                @include('partials.mq-topbar', ['user' => Auth::user(), 'user' => Auth::user(), 
                    'user' => $user,
                    'roleLabel' => 'Entrenador',
                    'profileUrl' => route('entrenador.perfil'),
                    'settingsUrl' => route('entrenador.perfil'),
                    'helpUrl' => route('entrenador.chat'),
                    'notificationsUrl' => route('entrenador.notificaciones'),
                    'notifCount' => 2,
                ])

                <section class="pf-card" aria-label="Perfil entrenador">
                    <div class="pf-top">
                        <div class="pf-avatar" aria-hidden="true">
                            {{ strtoupper(mb_substr($profile['first_name'] ?? 'J', 0, 1)) }}{{ strtoupper(mb_substr($profile['last_name'] ?? 'M', 0, 1)) }}
                        </div>
                        <div class="pf-top-meta">
                            <div class="pf-name">{{ $user->name ?? '' }}</div>
                            <div class="pf-role">{{ $profile['title'] ?? 'Entrenador' }}</div>
                        </div>
                    </div>

                    <div class="pf-divider" aria-hidden="true"></div>

                    <form class="pf-form" action="#" method="POST">
                        <div class="pf-row">
                            <div class="pf-field">
                                <label class="pf-label" for="pf-nombre">Nombre</label>
                                <input id="pf-nombre" name="nombre" class="pf-control" type="text" value="{{ $profile['first_name'] ?? '' }}" />
                            </div>
                            <div class="pf-field">
                                <label class="pf-label" for="pf-apellido">Apellido</label>
                                <input id="pf-apellido" name="apellido" class="pf-control" type="text" value="{{ $profile['last_name'] ?? '' }}" />
                            </div>
                        </div>

                        <div class="pf-field">
                            <label class="pf-label" for="pf-email">Email</label>
                            <input id="pf-email" name="email" class="pf-control" type="email" value="{{ $user->email ?? '' }}" />
                        </div>

                        <div class="pf-field">
                            <label class="pf-label" for="pf-telefono">Telefono</label>
                            <input id="pf-telefono" name="telefono" class="pf-control" type="text" value="{{ $profile['phone'] ?? '' }}" />
                        </div>

                        <div class="pf-field">
                            <label class="pf-label" for="pf-especialidad">Especialidad</label>
                            <input id="pf-especialidad" name="especialidad" class="pf-control" type="text" value="{{ $profile['specialty'] ?? '' }}" />
                        </div>

                        <button class="pf-save" type="submit">Guardar Cambios</button>
                    </form>
                </section>
            </main>
        </div>
    </body>
</html>
