<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Galería - Más Que Perros</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/galeria-publica.css') }}">
    </head>
    <body class="pg-body">
        <header class="pg-header">
            <nav class="pg-navbar" aria-label="Navegación principal">
                <div class="pg-nav-left">
                    <a href="{{ url('/') }}#servicios">Servicios</a>
                    <a class="pg-active" href="{{ route('galeria') }}">Galería</a>
                </div>

                <a class="pg-brand" href="{{ url('/') }}">MAS QUE PERROS</a>

                <div class="pg-nav-right">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}">Mi cuenta</a>
                        @else
                            <a href="{{ route('login') }}">Iniciar Sesion</a>
                        @endauth
                    @endif
                </div>
            </nav>
        </header>

        <main>
            <section class="pg-hero">
                <span class="pg-kicker">Momentos felices</span>
                <h1>Clientes satisfechos y actividades</h1>
                <p>Conoce algunos de los momentos que viven nuestros peluditos en Más Que Perros.</p>
            </section>

            <section class="pg-section" aria-labelledby="clientes-title">
                <div class="pg-section-head">
                    <h2 id="clientes-title">Clientes satisfechos</h2>
                    <p>Peluditos que han disfrutado nuestros espacios, cuidados y acompañamiento.</p>
                </div>

                <div class="pg-grid">
                    <article class="pg-card pg-card--large">
                        <img src="{{ asset('img/QUEEN.jpeg') }}" alt="Cliente satisfecho Queen">
                        <div><strong>Queen</strong><span>Un día lleno de cuidado y tranquilidad.</span></div>
                    </article>
                    <article class="pg-card">
                        <img src="{{ asset('img/REIGY,.jpeg') }}" alt="Cliente satisfecho Reigy">
                        <div><strong>Reigy</strong><span>Acompañamiento personalizado.</span></div>
                    </article>
                    <article class="pg-card">
                        <img src="{{ asset('img/TITAN Y LOLA.jpeg') }}" alt="Clientes satisfechos Titán y Lola">
                        <div><strong>Titán y Lola</strong><span>Socialización y diversión.</span></div>
                    </article>
                </div>
            </section>

            <section class="pg-section pg-section--soft" aria-labelledby="actividades-title">
                <div class="pg-section-head">
                    <h2 id="actividades-title">Actividades</h2>
                    <p>Juegos, descanso, paseos y momentos especiales pensados para su bienestar.</p>
                </div>

                <div class="pg-activities">
                    <div class="pg-activity"><span>🐾</span><strong>Juegos supervisados</strong></div>
                    <div class="pg-activity"><span>🦴</span><strong>Momentos de descanso</strong></div>
                    <div class="pg-activity"><span>🌿</span><strong>Paseos y exploración</strong></div>
                    <div class="pg-activity"><span>📸</span><strong>Recuerdos para la familia</strong></div>
                </div>
            </section>
        </main>
    </body>
</html>
