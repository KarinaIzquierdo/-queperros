<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Mis Tareas</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('css/shared/mq-topbar.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dueño/panel.css') }}">
        <link rel="stylesheet" href="{{ asset('css/entrenador/dashboardentrenador.css') }}">
        <link rel="stylesheet" href="{{ asset('css/entrenador/mistareas.css') }}">
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

                <section class="mt-card" aria-label="Tareas del día">
                    <div class="mt-card-head">
                        <h2 class="mt-card-title">Tareas del dia - {{ now()->translatedFormat('d \d\e F') }}</h2>
                        <div class="mt-actions">
                            <button class="mt-btn mt-btn--ghost" type="button">Filtrar</button>
                            <button class="mt-btn mt-btn--primary" type="button">Nueva Tarea</button>
                        </div>
                    </div>

                    <div class="mt-list">
                        @foreach ($tasks as $task)
                            <article class="mt-row {{ !empty($task['done']) ? 'mt-row--done' : '' }}" data-status="{{ $task['status'] }}">
                                <div class="mt-left">
                                    <span class="mt-box" aria-hidden="true">
                                        @if (!empty($task['done']))
                                            <i class="bi bi-check-lg" aria-hidden="true"></i>
                                        @endif
                                    </span>
                                    <div class="mt-meta">
                                        <div class="mt-name">{{ $task['title'] }}</div>
                                        <div class="mt-time">{{ $task['time'] }}</div>
                                    </div>
                                </div>
                                <div class="mt-right">
                                    <span class="mt-pill">{{ strtoupper($task['status']) }}</span>
                                    <button class="mt-btn mt-btn--details" type="button">Detalles</button>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            </main>
        </div>
    </body>
</html>
