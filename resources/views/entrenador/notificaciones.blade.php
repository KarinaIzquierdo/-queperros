<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Avisos de la Manada | Más Que Perros</title>
        <link rel="icon" type="image/png" href="{{ asset('img/huellita.png') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('css/shared/mq-topbar.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dueño/panel.css') }}">
        <link rel="stylesheet" href="{{ asset('css/entrenador/dashboardentrenador.css') }}">
        <link rel="stylesheet" href="{{ asset('css/entrenador/notificaciones.css') }}">
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
                    'user' => $user,
                    'roleLabel' => 'Entrenador',
                    'profileUrl' => route('entrenador.perfil'),
                    'settingsUrl' => route('entrenador.perfil'),
                    'helpUrl' => route('entrenador.chat'),
                    'notificationsUrl' => route('entrenador.notificaciones'),
                ])

                <section class="nt-list" aria-label="Notificaciones">
                    @forelse (($notifications ?? []) as $n)
                        @php
                            $type = (string) ($n->tipo ?? 'general');
                            $color = $type === 'pago' ? 'blue' : ($type === 'cita' ? 'green' : 'gray');
                            $unread = empty($n->leida_en);
                            $icon = $type === 'pago' ? 'bi-credit-card' : ($type === 'cita' ? 'bi-calendar-check' : 'bi-bell');
                        @endphp
                        <article class="nt-item nt-item--{{ $color }} {{ $unread ? 'nt-item--unread' : '' }}">
                            <div class="nt-left">
                                <div class="nt-ico nt-ico--{{ $color }}" aria-hidden="true">
                                    <i class="bi {{ $icon }}"></i>
                                </div>
                                <div class="nt-meta">
                                    <div class="nt-text">{{ $n->titulo ?? 'Notificación' }}</div>
                                    <div class="nt-time">{{ $n->created_at ? \Carbon\Carbon::parse($n->created_at)->diffForHumans() : '' }}</div>
                                </div>
                            </div>
                            @if ($unread)
                                <button class="nt-btn" type="button">Marcar leída</button>
                            @endif
                        </article>
                    @empty
                        <article class="nt-item">
                            <div class="nt-left">
                                <div class="nt-meta">
                                    <div class="nt-text">No tienes notificaciones</div>
                                    <div class="nt-time">Cuando recibas una notificación, aparecerá aquí.</div>
                                </div>
                            </div>
                        </article>
                    @endforelse
                </section>
            </main>
        </div>
    </body>
</html>
