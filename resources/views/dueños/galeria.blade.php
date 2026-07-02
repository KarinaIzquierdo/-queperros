<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Álbum de Recuerdos | Más Que Perros</title>
        <link rel="icon" type="image/png" href="{{ asset('img/huellita.png') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('css/shared/mq-topbar.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dueño/galeria.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dueño/panel.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="{{ asset('css/Admin/admin-sidebar-extras.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
                    <section class="gl-page">
                    <div class="gl-head">
                        <h1 class="gl-title">Galeria</h1>
                        <p class="gl-sub">Mira las fotos y recuerdos de tus mascotas compartidos por nuestro equipo.</p>
                    </div>

                    <div class="gl-card">
                        @if (session('success'))
                            <div class="gl-alert gl-alert--success">{{ session('success') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="gl-alert gl-alert--error">{{ $errors->first() }}</div>
                        @endif
                    </div>

                    <div class="gl-card">
                        <div class="gl-grid" aria-label="Galeria">
                            @forelse ($photos as $photo)
                                <a class="gl-photo" href="{{ $photo['url'] }}" target="_blank" rel="noopener">
                                    <img src="{{ $photo['url'] }}" alt="Foto de galería">
                                </a>
                            @empty
                                <div class="gl-empty">Aún no has subido fotos.</div>
                            @endforelse
                        </div>
                    </div>
                </section>
                </div>
            </main>
        </div>
    </body>
</html>
