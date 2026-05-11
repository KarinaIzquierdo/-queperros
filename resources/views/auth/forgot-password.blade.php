<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Olvidé mi contraseña</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('css/auth/forgot-password.css') }}">
        <link rel="stylesheet" href="{{ asset('css/auth/partials/login-footer.css') }}">
    </head>

    <body>
        @include('partials.page-loader')
        <div class="mq-forgot">
            <div class="mq-forgot-top">
                <div class="mq-forgot-left">
                    <a class="mq-back-link" href="{{ route('login') }}" aria-label="Volver">
                        <span class="mq-back-button-box" aria-hidden="true">
                            <span class="mq-back-button-elem">
                                <svg viewBox="0 0 46 40" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M46 20.038c0-.7-.3-1.5-.8-2.1l-16-17c-1.1-1-3.2-1.4-4.4-.3-1.2 1.1-1.2 3.3 0 4.4l11.3 11.9H3c-1.7 0-3 1.3-3 3s1.3 3 3 3h33.1l-11.3 11.9c-1 1-1.2 3.3 0 4.4 1.2 1.1 3.3.8 4.4-.3l16-17c.5-.5.8-1.1.8-1.9z"></path>
                                </svg>
                            </span>
                            <span class="mq-back-button-elem">
                                <svg viewBox="0 0 46 40" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M46 20.038c0-.7-.3-1.5-.8-2.1l-16-17c-1.1-1-3.2-1.4-4.4-.3-1.2 1.1-1.2 3.3 0 4.4l11.3 11.9H3c-1.7 0-3 1.3-3 3s1.3 3 3 3h33.1l-11.3 11.9c-1 1-1.2 3.3 0 4.4 1.2 1.1 3.3.8 4.4-.3l16-17c.5-.5.8-1.1.8-1.9z"></path>
                                </svg>
                            </span>
                        </span>
                    </a>

                    <div class="mq-forgot-card" aria-label="Olvidaste tu contraseña">
                        <img class="mq-forgot-card-img" src="{{ asset('img/Recurso 1.png') }}" alt="" aria-hidden="true">

                        <h1 class="mq-forgot-title">¿Olvidaste tu contraseña?</h1>
                        <p class="mq-forgot-subtitle">Ingresa su correo electrónico y te enviaremos un enlace para restablecer tu contraseña.</p>

                        @if (session('status'))
                            <div class="mq-status">{{ session('status') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="mq-errors">{{ $errors->first() }}</div>
                        @endif

                        <form class="mq-forgot-form" method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="mq-field">
                                <label for="email">Correo electrónico</label>
                                <input class="mq-input" id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email">
                            </div>

                            <button class="mq-btn" type="submit">ENVIAR ENLACE</button>

                            <div class="mq-bottom-text">
                                <a href="{{ route('login') }}">Volver a Iniciar Sesión</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="mq-forgot-right" aria-hidden="true">
                    <img class="mq-forgot-illustration" src="{{ asset('img/login.jpg') }}" alt="">
                </div>
            </div>

            @include('auth.partials.login-footer')
        </div>
    </body>
</html>
