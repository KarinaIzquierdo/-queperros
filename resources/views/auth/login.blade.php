<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Iniciar sesión</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
        <link rel="stylesheet" href="{{ asset('css/auth/partials/login-footer.css') }}">
    </head>

    <body>
        @include('partials.page-loader')
        <div class="mq-login">
            <div class="mq-login-top">
                <div class="mq-login-left">
                    <a class="mq-back-link" href="{{ url('/') }}" aria-label="Volver">
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
                    <div class="mq-login-card" aria-label="Iniciar sesión">
                        <img class="mq-login-card-img" src="{{ asset('img/Recurso 1.png') }}" alt="" aria-hidden="true">
                        <h1 class="mq-login-title">INICIAR SESION</h1>

                        @if (session('status'))
                            <div style="margin-bottom: 1rem; padding: 0.75rem 1rem; border-radius: 0.5rem; background-color: #dcfce7; color: #166534; font-size: 0.9rem;">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mq-errors">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form class="mq-login-form" method="POST" action="{{ url('/login') }}">
                            @csrf

                            <div class="mq-field">
                                <label for="email">Usuario</label>
                                <input class="mq-input" id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username">
                            </div>

                            <div class="mq-field">
                                <label for="password">Contraseña</label>
                                <div style="position: relative;">
                                    <input class="mq-input" id="password" name="password" type="password" required autocomplete="current-password" style="padding-right: 2.5rem;">
                                    <button type="button" class="toggle-password" data-target="password" aria-label="Mostrar u ocultar contraseña" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 0; display: flex; align-items: center; justify-content: center;">
                                        <span class="bi bi-eye-slash">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                              <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7 7 0 0 0-2.79.588l.77.771A6 6 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755q-.247.248-.517.486z"/>
                                              <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829"/>
                                              <path d="M3.35 5.47q-.27.24-.518.487A13 13 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7 7 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12z"/>
                                            </svg>
                                        </span>
                                        <span class="bi bi-eye" style="display: none;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                              <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                              <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </div>

                            <div class="mq-actions">
                                <a class="mq-forgot" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                                <button class="mq-btn" type="submit">INICIAR SESION</button>
                            </div>

                            <div class="mq-bottom-text">
                                ¿No tienes una cuenta?
                                <a href="{{ route('register') }}">Registrate AQUI</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="mq-login-right">
                    <img class="mq-login-illustration" src="{{ asset('img/login.jpg') }}" alt="Ilustración">
                </div>
            </div>

            @include('auth.partials.login-footer')
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.toggle-password').forEach(function (button) {
                    button.addEventListener('click', function () {
                        var targetId = this.getAttribute('data-target');
                        var input = document.getElementById(targetId);
                        if (!input) return;

                        var isPassword = input.type === 'password';
                        input.type = isPassword ? 'text' : 'password';

                        var eye = this.querySelector('.bi-eye');
                        var eyeSlash = this.querySelector('.bi-eye-slash');
                        if (eye && eyeSlash) {
                            if (isPassword) {
                                eye.style.display = 'inline';
                                eyeSlash.style.display = 'none';
                            } else {
                                eye.style.display = 'none';
                                eyeSlash.style.display = 'inline';
                            }
                        }
                    });
                });
            });
        </script>
    </body>
</html>
