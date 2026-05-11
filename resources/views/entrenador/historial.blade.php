<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Historial de Servicios</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('css/shared/mq-topbar.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dueño/panel.css') }}">
        <link rel="stylesheet" href="{{ asset('css/entrenador/dashboardentrenador.css') }}">
        <link rel="stylesheet" href="{{ asset('css/entrenador/historial.css') }}">
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

                <section class="hs-card" aria-label="Servicios Realizados">
                    <div class="hs-card-head">
                        <h2 class="hs-card-title">Servicios Realizados</h2>
                        <button class="hs-export" type="button">Exportar</button>
                    </div>

                    <div class="hs-table-wrap" role="region" aria-label="Tabla de servicios">
                        <table class="hs-table">
                            <thead>
                                <tr>
                                    <th>FECHA</th>
                                    <th>MASCOTA</th>
                                    <th>SERVICIO</th>
                                    <th>DURACION</th>
                                    <th>NOTAS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (($records ?? []) as $row)
                                    <tr>
                                        <td>{{ $row['date'] ?? '' }}</td>
                                        <td>{{ $row['pet'] ?? '' }}</td>
                                        <td>{{ $row['service'] ?? '' }}</td>
                                        <td>{{ $row['duration'] ?? '' }}</td>
                                        <td>{{ $row['notes'] ?? '' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </main>
        </div>
    </body>
</html>
