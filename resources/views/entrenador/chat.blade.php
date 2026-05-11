<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Chat</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('css/shared/mq-topbar.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dueño/panel.css') }}">
        <link rel="stylesheet" href="{{ asset('css/entrenador/dashboardentrenador.css') }}">
        <link rel="stylesheet" href="{{ asset('css/entrenador/chat.css') }}">
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

                <section class="ch-layout" aria-label="Chat">
                    <aside class="ch-left" aria-label="Conversaciones">
                        <div class="ch-left-head">Conversaciones</div>

                        <div class="ch-conv-list">
                            @foreach (($conversations ?? []) as $conv)
                                <button type="button" class="ch-conv {{ !empty($conv['active']) ? 'ch-conv--active' : '' }}">
                                    <div class="ch-avatar" aria-hidden="true">{{ $conv['initial'] ?? 'C' }}</div>
                                    <div class="ch-conv-meta">
                                        <div class="ch-conv-name">{{ $conv['name'] ?? '' }}</div>
                                        <div class="ch-conv-sub">{{ $conv['subtitle'] ?? '' }}</div>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </aside>

                    <div class="ch-right" aria-label="Conversación">
                        <div class="ch-chat-head">
                            <div class="ch-chat-head-left">
                                <div class="ch-avatar ch-avatar--big" aria-hidden="true">
                                    {{ mb_substr(($activeConversation['name'] ?? 'C'), 0, 1) }}
                                </div>
                                <div>
                                    <div class="ch-chat-name">{{ $activeConversation['name'] ?? '' }}</div>
                                    <div class="ch-chat-sub">{{ $activeConversation['subtitle'] ?? '' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="ch-chat-body">
                            @foreach (($messages ?? []) as $msg)
                                <div class="ch-msg-row {{ ($msg['from'] ?? '') === 'trainer' ? 'ch-msg-row--me' : '' }}">
                                    <div class="ch-msg {{ ($msg['from'] ?? '') === 'trainer' ? 'ch-msg--me' : 'ch-msg--them' }}">
                                        {{ $msg['text'] ?? '' }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <form class="ch-chat-input" action="#" method="POST">
                            <input class="ch-input" type="text" placeholder="Escribe un mensaje..." />
                            <button class="ch-send" type="submit">Enviar</button>
                        </form>
                    </div>
                </section>
            </main>
        </div>
    </body>
</html>
