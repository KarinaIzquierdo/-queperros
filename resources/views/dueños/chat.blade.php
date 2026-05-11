<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Chat con Entrenador</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('css/shared/mq-topbar.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dueño/chat.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dueño/panel.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('css/Admin/admin-sidebar-extras.css') }}?v={{ time() }}">
    </head>

    <body class="mq-dashboard-page">
        @include('partials.page-loader')
        @php
            use Illuminate\Support\Str;
        @endphp

        <div class="mq-dashboard">
            @include('partials.dueno-sidebar')

            <main class="mq-dashboard-main">
                @include('partials.mq-topbar', ['user' => Auth::user(), 'user' => Auth::user(), 
                    'user' => $user,
                    'roleLabel' => 'Propietario',
                    'profileUrl' => route('owner.perfil'),
                    'settingsUrl' => route('owner.perfil'),
                    'helpUrl' => route('owner.chat'),
                    'notificationsUrl' => route('owner.notificaciones'),
                    'notifCount' => 2,
                ])

                <div class="mq-dashboard-content">
                    <section class="ch-page">
                    <div class="ch-head">
                        <h1 class="ch-title">Chat con Entrenador</h1>
                        <p class="ch-sub">Resuelve tus dudas directamente con el equipo</p>
                    </div>

                    <div class="ch-card">
                        <div class="ch-top">
                            <div class="ch-avatar">CM</div>
                            <div class="ch-trainer">
                                <div class="ch-trainer-name">Carlos Martinez</div>
                                <div class="ch-status">
                                    <span class="ch-dot" aria-hidden="true"></span>
                                    <span>En linea</span>
                                </div>
                            </div>
                        </div>

                        <div class="ch-body">
                            <div class="ch-messages" aria-label="Mensajes">
                                <div class="ch-msg">
                                    <div>Hola! Como estas? Te cuento que Max tuvo una excelente sesion hoy.</div>
                                    <div class="ch-msg-time">10:30 am</div>
                                </div>

                                <div class="ch-msg ch-msg--me">
                                    <div>Que bueno! Como le fue con los comandos nuevos?</div>
                                    <div class="ch-msg-time">10:35 am ✓</div>
                                </div>

                                <div class="ch-msg">
                                    <div>Muy bien! Ya domina el 'quieto' por 30 segundos. Recomiendo practicarlo en casa 10 minutos diarios.</div>
                                    <div class="ch-msg-time">10:38 am</div>
                                </div>

                                <div class="ch-msg">
                                    <div>Te envie algunas fotos de la sesion en la galeria.</div>
                                    <div class="ch-msg-time">10:40 am</div>
                                </div>
                            </div>

                            <form class="ch-inputbar" action="#" method="POST">
                                <input class="ch-input" type="text" placeholder="Escribe un mensaje..." aria-label="Escribe un mensaje">
                                <button class="ch-send" type="button" aria-label="Enviar">
                                    <i class="bi bi-send" aria-hidden="true"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </section>
                </div>
            </main>
        </div>
    </body>
</html>
