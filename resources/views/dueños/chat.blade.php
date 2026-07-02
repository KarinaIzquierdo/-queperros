<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Línea Directa | Más Que Perros</title>
        <link rel="icon" type="image/png" href="{{ asset('img/huellita.png') }}">

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
                ])

                <div class="mq-dashboard-content">
                    <section class="ch-page">
                    <div class="ch-head">
                        <h1 class="ch-title">Chat con Entrenador</h1>
                        <p class="ch-sub">Resuelve tus dudas directamente con el equipo</p>
                    </div>

                    <div class="ch-card">
                        <div class="ch-top">
                            <div class="ch-avatar">MQ</div>
                            <div class="ch-trainer">
                                <div class="ch-trainer-name">Equipo Mas que Perros</div>
                                <div class="ch-status">
                                    <span class="ch-dot" aria-hidden="true"></span>
                                    <span>En linea</span>
                                </div>
                            </div>
                        </div>

                        <div class="ch-body">
                            <div class="ch-messages" aria-label="Mensajes">
                                @if (empty($messages))
                                    <div class="ch-msg">
                                        <div>No hay mensajes todavía.</div>
                                        <div class="ch-msg-time"></div>
                                    </div>
                                @else
                                    @foreach ($messages as $msg)
                                        <div class="ch-msg {{ ($msg['from'] ?? '') === 'me' ? 'ch-msg--me' : '' }}">
                                            <div>{{ $msg['text'] ?? '' }}</div>
                                            <div class="ch-msg-time">{{ $msg['time'] ?? '' }}{{ ($msg['from'] ?? '') === 'me' ? ' ✓' : '' }}</div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <form class="ch-inputbar" action="#" method="POST">
                                <input class="ch-input" type="text" id="ownerMessageInput" name="message" placeholder="Escribe un mensaje..." aria-label="Escribe un mensaje" onkeypress="if(event.key === 'Enter') sendOwnerMessage();">
                                <button class="ch-send" type="button" id="ownerSendButton" aria-label="Enviar" onclick="sendOwnerMessage()">
                                    <i class="bi bi-send" aria-hidden="true"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </section>
                </div>
            </main>
        </div>

        <script>
            function sendOwnerMessage() {
                const messageInput = document.getElementById('ownerMessageInput');
                const message = messageInput.value.trim();

                if (message) {
                    // Enviar mensaje al servidor
                    fetch('{{ route('owner.chat.send') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: 'message=' + encodeURIComponent(message)
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Recargar la página para mostrar el mensaje guardado
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }
            }
        </script>
    </body>
</html>
