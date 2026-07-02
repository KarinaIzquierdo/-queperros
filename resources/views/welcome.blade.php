<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Mas Que Perros') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('img/huellita.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Chango&family=Delius&family=Delius+Swash+Caps&family=Noto+Znamenny+Musical+Notation&family=Ranchers&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
        <link rel="stylesheet" href="{{ asset('css/auth/partials/login-footer.css') }}">
    </head>
    <body class="mq-body">
        @include('partials.page-loader')
        <div class="mq-hero-wrap">
            <div class="mq-hero" aria-label="Banner principal">
                <div class="mq-hero-carousel" aria-hidden="true">
                    <div class="mq-hero-slide">
                        <img src="{{ asset('img/QUEEN.jpeg') }}" alt="QUEEN">
                    </div>
                    <div class="mq-hero-slide">
                        <img src="{{ asset('img/REIGY,.jpeg') }}" alt="REIGY">
                    </div>
                    <div class="mq-hero-slide">
                        <img src="{{ asset('img/REIGY%20-%20RELAX.jpeg') }}" alt="REIGY - RELAX">
                    </div>
                    <div class="mq-hero-slide">
                        <img src="{{ asset('js/TITAN%20Y%20LOLA.jpeg') }}" alt="TITAN Y LOLA">
                    </div>
                </div>

            </div>

            <div class="mq-navbar" role="navigation" aria-label="Navegación principal">
                <div class="mq-nav-left">
                    <a class="mq-nav-link" href="#servicios">Servicios</a>
                    <a class="mq-nav-link" href="{{ route('galeria') }}">Galería</a>
                </div>

                <div class="mq-nav-center">MAS QUE PERROS</div>

                <div class="mq-nav-right">
                    <div class="mq-user-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 21c0-4.418-3.582-8-8-8s-8 3.582-8 8" stroke="white" stroke-width="2" stroke-linecap="round" />
                            <path d="M12 13a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9Z" stroke="white" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </div>

                    @if (Route::has('login'))
                        @auth
                            <a class="mq-nav-login" href="{{ url('/dashboard') }}">Mi cuenta</a>
                        @else
                            <a class="mq-nav-login" href="{{ route('login') }}">Iniciar Sesión</a>
                        @endauth
                    @else
                        <a class="mq-nav-login" href="#">Iniciar Sesión</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="mq-landing">
            <section class="mq-about-wrap" id="sobre-nosotros" aria-label="Sobre nosotros">
                <div class="mq-container">
                    <div class="mq-about-grid">
                        <div class="mq-about-card">
                            <div class="mq-about-tagline">
                                <img class="mq-rainbow" src="{{ asset('img/rainbow.svg') }}" alt="" aria-hidden="true">
                                <div class="mq-tagline-text">
                                    TU PERRO FELIZ,
                                    <br>
                                    TU TRANQUILO
                                </div>
                            </div>

                            <div class="mq-about-box">
                                <img class="mq-about-photo" src="{{ asset('img/tuperro.jpg') }}" alt="Tu perro feliz">
                            </div>
                        </div>

                        <h2 class="mq-about-title">SOBRE NOSOTROS</h2>
                        <div class="mq-about-text">
                            <p>Más que Perros nace en 2018 con el propósito de brindar cuidado, hospedaje y acompañamiento personalizado para perros, adaptándose a las necesidades de cada animal cuando sus tutores no pueden atenderlos por motivos de tiempo, trabajo o espacio.</p>
                            <p>Nos especializamos en entrenamiento básico, integral y deportivo, enfocado en corregir conductas, fortalecer el bienestar emocional y físico de los perros y mejorar la convivencia en las familias multiespecie.</p>
                            <p>Además, desarrollamos la selección, crianza y formación ética de perros de trabajo a través de nuestro criadero Obedience Badge, registrado ante la Asociación Club Canino Colombiano, garantizando procesos responsables y ejemplares con pedigrí.</p>
                        </div>
                    </div>
                </div>
            </section>

            <div class="mq-bg-page">
                <img src="{{ asset('img/fondo-page.jpg') }}" alt="" class="mq-bg-page-img" aria-hidden="true">
            <section class="mq-services-wrap" id="servicios">
                <div class="mq-services-shape mq-services-shape--a" aria-hidden="true"></div>
                <div class="mq-services-shape mq-services-shape--b" aria-hidden="true"></div>
                <div class="mq-services-shape mq-services-shape--c" aria-hidden="true"></div>
                <div class="mq-services-shape mq-services-shape--d" aria-hidden="true"></div>

                <div class="mq-container" style="max-width: 1000px; margin: 0 auto; padding: 4rem 1rem;">
                    <h2 class="mq-title mq-title--xl mq-title--light mq-services-title" style="text-align: center; margin-bottom: 4rem; font-size: 3.5rem; text-shadow: 2px 2px 0px rgba(0,0,0,0.1);">NUESTROS SERVICIOS</h2>

                    <div class="mq-services-grid" style="display: flex; flex-direction: column; align-items: center; gap: 2.5rem;">
                        <!-- Fila Superior -->
                        <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 2.5rem; width: 100%;">
                            <article class="mq-card-ui" style="background: #DCEBFA; border-radius: 20px; border: 1px solid #B4C7D9; box-shadow: 0 4px 15px rgba(0,0,0,0.08); padding: 2.5rem; width: 380px; min-height: 260px; display: flex; flex-direction: column; justify-content: flex-start;">
                                <h3 style="font-family: 'Lilita One', cursive; font-size: 1.8rem; color: #1B1B18; text-align: center; margin-bottom: 1.5rem; letter-spacing: 1px; line-height: 1.1;">ENTRENAMIENTO CANINO</h3>
                                <ul style="list-style: none; padding: 0; margin: 0;">
                                    <li class="mq-service-item" style="display: flex; align-items: flex-start; gap: 12px; margin-bottom: 1.2rem; color: #1B1B18; font-size: 1.1rem; line-height: 1.3; position: relative;">
                                        <img src="{{ asset('img/huellita.png') }}" alt="" style="width: 20px; margin-top: 4px; flex-shrink: 0;">
                                        <div style="display: flex; flex-direction: column;">
                                            <span>Entrenamiento básico integral</span>
                                            <button type="button" class="mq-ver-mas" data-service-name="Entrenamiento básico integral" data-service-desc="Servicio diseñado para todos los perros de todas las edades y razas por que un perro entrenado mejora la convivencia y el bienestar de la familia multiespecie o para aquellos perros que necesitan apoyo en el aprendizaje o refuerzo de normas de convivencia. Incluye trabajo en obediencia, autocontrol, socialización, manejo de ansiedad y modificación de comportamientos no adecuados. Nuestro entrenamiento está orientado a la disciplina, el ejercicio mental y el desarrollo de capacidades físicas, estimulando su potencial mediante trabajo estructurado.">Ver más</button>
                                        </div>
                                    </li>
                                    <li class="mq-service-item" style="display: flex; align-items: flex-start; gap: 12px; margin-bottom: 1.2rem; color: #1B1B18; font-size: 1.1rem; line-height: 1.3; position: relative;">
                                        <img src="{{ asset('img/huellita.png') }}" alt="" style="width: 20px; margin-top: 4px; flex-shrink: 0;">
                                        <div style="display: flex; flex-direction: column;">
                                            <span>Entrenamiento deportivo</span>
                                            <button type="button" class="mq-ver-mas" data-service-name="Entrenamiento deportivo" data-service-desc="Entrenamos en deportes caninos como: • OCI: Obediencia clase internacional. • DISC DOG: Lanzamiento de disco. • PSA: (Protection Sport Association). obediencia y el trabajo de protección del perro en escenarios basados en la vida real.">Ver más</button>
                                        </div>
                                    </li>
                                    <li class="mq-service-item" style="display: flex; align-items: flex-start; gap: 12px; color: #1B1B18; font-size: 1.1rem; line-height: 1.3; position: relative;">
                                        <img src="{{ asset('img/huellita.png') }}" alt="" style="width: 20px; margin-top: 4px; flex-shrink: 0;">
                                        <div style="display: flex; flex-direction: column;">
                                            <span>Formación y crianza para perros de trabajo o especialidades</span>
                                            <button type="button" class="mq-ver-mas" data-service-name="Formación y crianza para perros de trabajo" data-service-desc="Programa especializado en la selección, socialización, formación y desarrollo para perros de trabajo, incluye el cruce, la crianza, el entrenamiento estructurado, la estimulación temprana del cachorro, trabajo de obediencia, fortalecimiento emocional y preparación según la función que desempeñara. Nuestro enfoque combina genética adecuada, la socialización correcta para formar perros capaces, seguros y confiables en su labor. Priorizamos el bienestar, la ética y el respeto por la naturaleza del animal.">Ver más</button>
                                        </div>
                                    </li>
                                </ul>
                            </article>

                            <article class="mq-card-ui" style="background: #DCEBFA; border-radius: 20px; border: 1px solid #B4C7D9; box-shadow: 0 4px 15px rgba(0,0,0,0.08); padding: 2.5rem; width: 380px; height: 260px; display: flex; flex-direction: column; justify-content: flex-start;">
                                <h3 style="font-family: 'Lilita One', cursive; font-size: 1.8rem; color: #1B1B18; text-align: center; margin-bottom: 1.5rem; letter-spacing: 1px; line-height: 1.1;">CUIDADO Y ALOJAMIENTO</h3>
                                <ul style="list-style: none; padding: 0; margin: 0;">
                                    <li class="mq-service-item" style="display: flex; align-items: flex-start; gap: 12px; margin-bottom: 1.2rem; color: #1B1B18; font-size: 1.1rem; line-height: 1.3; position: relative;">
                                        <img src="{{ asset('img/huellita.png') }}" alt="" style="width: 20px; margin-top: 4px; flex-shrink: 0;">
                                        <div style="display: flex; flex-direction: column;">
                                            <span>Hotel canino</span>
                                            <button type="button" class="mq-ver-mas" data-service-name="Hotel canino" data-service-desc="Servicio de hospedaje para estancias de corta y larga duración. Ofrecemos un entorno seguro, cómodo y supervisado donde los perros reciben rutinas de cuidado, alimentación, ejercicio, descanso y acompañamiento profesional. Nos enfocamos en su bienestar físico, emocional y conductual para que se sientan tranquilos durante la ausencia del tutor. Ofrecemos servicio de recogida para estancias mayores a 8 días en la ciudad de Bogotá.">Ver más</button>
                                        </div>
                                    </li>
                                </ul>
                            </article>
                        </div>

                        <!-- Fila Inferior -->
                        <article class="mq-card-ui" style="background: #DCEBFA; border-radius: 20px; border: 1px solid #B4C7D9; box-shadow: 0 4px 15px rgba(0,0,0,0.08); padding: 2.5rem; width: 500px; display: flex; flex-direction: column; align-items: center;">
                            <h3 style="font-family: 'Lilita One', cursive; font-size: 1.8rem; color: #1B1B18; text-align: center; margin-bottom: 1.5rem; letter-spacing: 1px; line-height: 1.1;">OTRAS ACTIVIDADES</h3>
                            <ul style="list-style: none; padding: 0; margin: 0;">
                                <li class="mq-service-item" style="display: flex; align-items: flex-start; gap: 12px; margin-bottom: 1.2rem; color: #1B1B18; font-size: 1.1rem; line-height: 1.3; position: relative;">
                                    <img src="{{ asset('img/huellita.png') }}" alt="" style="width: 20px; margin-top: 4px; flex-shrink: 0;">
                                    <div style="display: flex; flex-direction: column;">
                                        <span>Día de diversión en Más que Perros</span>
                                        <button type="button" class="mq-ver-mas" data-service-name="Día de diversión" data-service-desc="Un plan para disfrutar en familia junto a tu mejor amigo, en Más que Perros abrimos nuestras puertas para que tú, tu familia y tu perro vivan un día lleno de conexión, aprendizaje y diversión. Es una experiencia creada para fortalecer el vínculo con tu peludito mientras comparten actividades al aire libre en un ambiente seguro, amplio y natural.">Ver más</button>
                                    </div>
                                </li>
                                <li class="mq-service-item" style="display: flex; align-items: flex-start; gap: 12px; color: #1B1B18; font-size: 1.1rem; line-height: 1.3; position: relative;">
                                    <img src="{{ asset('img/huellita.png') }}" alt="" style="width: 20px; margin-top: 4px; flex-shrink: 0;">
                                    <div style="display: flex; flex-direction: column;">
                                        <span>Plan padrino</span>
                                        <button type="button" class="mq-ver-mas" data-service-name="Plan padrino" data-service-desc="Más que perros dispuestos a desarrollar su labor social, plantea el plan padrino que consiste en conseguir personas o empresas con alta capacidad económica que puedan adoptar un perro en condición de calle y estén dispuestos a patrocinar su cuidado y su tenencia en sus instalaciones de Más que perros.">Ver más</button>
                                    </div>
                                </li>
                            </ul>
                        </article>
                    </div>

                    @if (Route::has('register'))
                        <div class="mq-services-cta">
                            <a class="mq-services-register" href="{{ route('register') }}">Regístrate para solicitar un servicio</a>
                        </div>
                    @endif
                </div>
            </section>

            @if (($sponsorDogs ?? collect())->isNotEmpty())
                <section class="mq-sponsor-home" id="plan-padrino">
                    <div class="mq-container">
                        <h2 class="mq-title mq-title--xl mq-title--light">APADRINA UN PERRITO</h2>
                        <p class="mq-sponsor-home-sub">Ayuda a cubrir alimentación, salud y cuidado de perros rescatados.</p>
                        <div class="mq-sponsor-home-grid">
                            @foreach (($sponsorDogs ?? collect()) as $dog)
                                @php
                                    $photo = $dog->foto ? asset('storage/' . ltrim($dog->foto, '/')) : asset('img/pet.png');
                                    $meta = collect([$dog->raza, $dog->edad ? $dog->edad . ' años' : null, $dog->sexo])->filter()->implode(' • ');
                                @endphp
                                <article class="mq-sponsor-home-card">
                                    <img src="{{ $photo }}" alt="{{ $dog->nombre }}">
                                    <div class="mq-sponsor-home-body">
                                        <h3>{{ $dog->nombre }}</h3>
                                        <div>{{ $meta }}</div>
                                        <p>{{ \Illuminate\Support\Str::limit($dog->historia ?: 'Este perrito necesita apoyo para cubrir sus cuidados.', 120) }}</p>
                                        <a href="{{ route('public.padrino.form', $dog) }}">Quiero apadrinar</a>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

            <section class="mq-why" id="por-que-elegirnos" aria-label="Por qué elegirnos">
                <div class="mq-container">
                    <h2 class="mq-title mq-title--xl mq-title--light">¿POR QUÉ ELEGIRNOS?</h2>
                    <p>Nos distinguimos por nuestro enfoque ético y basado en bienestar animal</p>

                    <div class="mq-mv-grid">
                        <article class="mq-mv-card" aria-label="Misión">
                            <h3>MISIÓN</h3>
                            <p>En Más que Perros, brindamos servicios integrales de entrenamiento, crianza y cuidado para perros de familia y de trabajo, promoviendo su bienestar físico, emocional y conductual mediante procesos humanos, transparentes y confiables que fortalecen el vínculo con sus tutores.</p>
                        </article>

                        <article class="mq-mv-card" aria-label="Visión" style="position: relative;">
                            <div class="mq-bark">BARK!</div>
                            <h3>VISIÓN</h3>
                            <p>Consolidarnos como empresa líder en Colombia en bienestar, formación y crianza canina, reconocida por nuestro profesionalismo, ética y uso innovador de herramientas que facilitan la comunicación y seguimiento del progreso de cada perro, siendo referente en entrenamiento de perros de trabajo y vínculos armónicos familia-mascota.</p>
                        </article>

                    </div>
                </div>
            </section>

            <section class="mq-values-wrap" id="valores" aria-label="Valores">
                <div class="mq-container">
                    <h2 class="mq-title mq-title--xl mq-title--dark">VALORES</h2>

                    <div class="mq-values-grid" aria-label="Valores de la empresa">
                        <article class="mq-value-card">
                            <div class="mq-value-bone">
                                <img src="{{ asset('img/mq-bone.svg') }}" alt="">
                                <span>AMOR Y RESPETO POR LOS ANIMALES</span>
                            </div>
                            <p>Atendemos cada perro como un ser sintiente, con sensibilidad, paciencia y comprensión, reconociendo su individualidad y sus necesidades.</p>
                        </article>

                        <article class="mq-value-card">
                            <div class="mq-value-bone">
                                <img src="{{ asset('img/mq-bone.svg') }}" alt="">
                                <span>PROFESIONALISMO Y ÉTICA</span>
                            </div>
                            <p>Actuamos con responsabilidad y coherencia en cada proceso de entrenamiento, cuidado y crianza.</p>
                        </article>

                        <article class="mq-value-card">
                            <div class="mq-value-bone">
                                <img src="{{ asset('img/mq-bone.svg') }}" alt="">
                                <span>BIENESTAR INTEGRAL</span>
                            </div>
                            <p>Promovemos el equilibrio físico, mental y emocional del perro como base de una vida saludable.</p>
                        </article>

                        <article class="mq-value-card">
                            <div class="mq-value-bone">
                                <img src="{{ asset('img/mq-bone.svg') }}" alt="">
                                <span>TRANSPARENCIA Y CONFIANZA</span>
                            </div>
                            <p>Brindamos información clara y seguimiento real del progreso del perro para que el tutor siempre se sienta acompañado.</p>
                        </article>

                        <article class="mq-value-card">
                            <div class="mq-value-bone">
                                <img src="{{ asset('img/mq-bone.svg') }}" alt="">
                                <span>COMPROMISO CON EL TRABAJO CANINO</span>
                            </div>
                            <p>Formamos perros de trabajo de manera ética, responsable y con métodos que respetan su naturaleza y sus capacidades.</p>
                        </article>

                        <article class="mq-value-card">
                            <div class="mq-value-bone">
                                <img src="{{ asset('img/mq-bone.svg') }}" alt="">
                                <span>COMUNICACIÓN CONSTANTE</span>
                            </div>
                            <p>Guiamos a los tutores con orientación clara, honesta y constante para fortalecer el vínculo con sus perros.</p>
                        </article>
                    </div>
                </div>
            </section>
            </div>

            <section class="mq-numbers-footer" aria-label="Más que números y pie de página">
                <div class="mq-container-post ">

                    <div class="mq-instagram-row">
                        <div class="instagram-post">
                            <blockquote
                                class="instagram-media"
                                data-instgrm-permalink="https://www.instagram.com/reel/DTDp4l3kc_r/"
                                data-instgrm-version="14">
                            </blockquote>
                        </div>

                        <div class="mq-instagram-phrase">Ellos también aprenden con amor</div>

                        <div class="instagram-post">
                            <blockquote
                                class="instagram-media"
                                data-instgrm-permalink="https://www.instagram.com/reel/DTIWjdZkWQI/"
                                data-instgrm-version="14">
                            </blockquote>
                        </div>
                    </div>
                </div>
            </section>

            @include('auth.partials.login-footer')
        </div>

        <!-- Modal para descripción de servicios en Landing -->
        <div id="mqServiceModal" class="mq-modal" aria-hidden="true">
            <div class="mq-modal-overlay"></div>
            <div class="mq-modal-content">
                <button type="button" class="mq-modal-close" aria-label="Cerrar">&times;</button>
                <div class="mq-modal-header">
                    <img src="{{ asset('img/huellita.png') }}" alt="" class="mq-modal-icon">
                    <h2 id="mqModalTitle">Nombre del Servicio</h2>
                </div>
                <div class="mq-modal-body">
                    <p id="mqModalDesc">Descripción detallada del servicio...</p>
                </div>
                <div class="mq-modal-footer">
                    <button type="button" class="mq-modal-btn" id="mqCloseModal">Entendido</button>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('mqServiceModal');
                const modalTitle = document.getElementById('mqModalTitle');
                const modalDesc = document.getElementById('mqModalDesc');
                const closeBtns = document.querySelectorAll('.mq-modal-close, #mqCloseModal, .mq-modal-overlay');

                document.querySelectorAll('.mq-ver-mas').forEach(btn => {
                    btn.addEventListener('click', function() {
                        modalTitle.textContent = this.dataset.serviceName;
                        modalDesc.textContent = this.dataset.serviceDesc;
                        modal.classList.add('is-active');
                        document.body.style.overflow = 'hidden';
                    });
                });

                closeBtns.forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        modal.classList.remove('is-active');
                        document.body.style.overflow = '';
                    });
                });

                // Cerrar con Escape
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && modal.classList.contains('is-active')) {
                        modal.classList.remove('is-active');
                        document.body.style.overflow = '';
                    }
                });
            });
        </script>


        <section class="mq-cookie" id="mq-cookie" aria-label="Cookies" hidden>
            <div class="mq-cookie-inner">
                <button class="mq-cookie-close" type="button" aria-label="Cerrar">×</button>

                <div class="mq-cookie-bone" aria-hidden="true">
                    <svg width="121" height="107" viewBox="0 0 121 107" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6.34729 51.9206C-8.60091 66.2722 8.61234 81.5498 19.0307 76.4573C22.292 77.198 22.8055 80.1609 22.6546 81.5498C18.6684 98.2162 31.2612 101.457 38.0559 100.994C51.464 96.9199 51.1922 86.3336 49.3803 81.5498C60.6142 68.587 85.1656 52.6921 96.037 46.365C99.2985 50.0687 105.55 50.3773 108.267 50.0687C129.557 44.5132 117.78 25.9949 107.361 25.9949C101.19 25.9949 99.3588 21.5197 99.2079 19.5135C99.9326 4.69894 88.9404 0.686667 83.3536 0.532359C68.8584 -0.208372 67.3484 11.952 68.4054 18.1247C61.5201 27.3838 37.1499 42.3528 25.8255 48.6799C10.4242 46.3651 7.70636 51.9206 6.34729 51.9206Z" stroke="#574C13"/>
                        <path d="M0.616283 63.8809C0.16006 66.2597 0.889915 75.0316 5.63463 78.5999C6.54714 79.0459 8.82826 81.276 14.7592 80.83C14.7592 81.0077 20.2338 79.9379 22.0587 85.7363" stroke="#574C13"/>
                        <path d="M22.0586 85.19C20.9953 90.8417 21.6221 103.53 35.8409 106.463C41.9172 106.921 53.7965 103.255 52.7028 84.9247C54.07 84.9247 81.8695 61.0957 88.2497 58.8044C90.2245 57.8879 94.5387 56.1466 95.9971 56.5132C103.441 59.8737 118.692 61.4622 120.151 40.9326" stroke="#574C13"/>
                        <path d="M41.4614 57.8242C43.5602 57.8242 45.2729 59.5509 45.2729 61.6953C45.2729 63.8397 43.5602 65.5664 41.4614 65.5664C39.3628 65.5663 37.6499 63.8397 37.6499 61.6953C37.6499 59.551 39.3628 57.8243 41.4614 57.8242Z" stroke="#574C13"/>
                        <path d="M60.3254 43.6182C62.7785 43.6182 64.676 45.399 64.676 47.4893C64.676 49.5795 62.7785 51.3604 60.3254 51.3604C57.8724 51.3603 55.9749 49.5795 55.9749 47.4893C55.9749 45.399 57.8724 43.6182 60.3254 43.6182Z" stroke="#574C13"/>
                        <path d="M78.1111 29.4121C80.1565 29.4121 81.9226 31.3266 81.9226 33.8301C81.9224 36.3333 80.1564 38.2471 78.1111 38.2471C76.0659 38.2469 74.2998 36.3332 74.2996 33.8301C74.2996 31.3267 76.0657 29.4122 78.1111 29.4121Z" stroke="#574C13"/>
                    </svg>
                </div>

                <div class="mq-cookie-text">
                    <div class="mq-cookie-title">RESPETAMOS TU PRIVACIDAD</div>
                    <div class="mq-cookie-sub">
                        En MAS QUE PERROS utilizamos cookies para mejorar tu experiencia y personalizar nuestros servicios.
                    </div>
                </div>

                <div class="mq-cookie-actions">
                    <button class="mq-cookie-btn mq-cookie-btn--primary" type="button" data-consent="all">Aceptar todas</button>
                    <button class="mq-cookie-btn mq-cookie-btn--primary" type="button" data-consent="essential">Solo Esenciales</button>
                    <button class="mq-cookie-gear" type="button" aria-label="Ajustes" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0"/>
                            <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z"/>
                        </svg>
                    </button>

                    <div class="mq-cookie-settings" id="mq-cookie-settings" hidden>
                        <div class="mq-cookie-settings-title">Preferencias</div>
                        <div class="mq-cookie-settings-row">
                            <span>Esenciales</span>
                            <span class="mq-cookie-settings-pill">Siempre activas</span>
                        </div>
                        <div class="mq-cookie-settings-row">
                            <span>Analíticas</span>
                            <span class="mq-cookie-settings-pill">Solo con “Aceptar todas”</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <script async src="//www.instagram.com/embed.js"></script>

        <script>
            (function () {
                const banner = document.getElementById('mq-cookie');
                if (!banner) return;

                const KEY = 'mq_cookie_consent';

                const getCookie = (name) => {
                    const value = `; ${document.cookie}`;
                    const parts = value.split(`; ${name}=`);
                    if (parts.length === 2) return parts.pop().split(';').shift();
                    return '';
                };

                const setCookie = (name, val, days) => {
                    const maxAge = days * 24 * 60 * 60;
                    document.cookie = `${name}=${encodeURIComponent(val)}; Max-Age=${maxAge}; Path=/; SameSite=Lax`;
                };

                const existing = window.localStorage.getItem(KEY) || getCookie(KEY);
                if (existing) {
                    banner.hidden = true;
                    return;
                }

                banner.hidden = false;

                const closeBtn = banner.querySelector('.mq-cookie-close');
                const consentBtns = banner.querySelectorAll('[data-consent]');
                const gearBtn = banner.querySelector('.mq-cookie-gear');
                const settings = document.getElementById('mq-cookie-settings');

                const persist = (val) => {
                    try { window.localStorage.setItem(KEY, val); } catch (e) {}
                    setCookie(KEY, val, 365);
                    banner.hidden = true;
                };

                consentBtns.forEach((btn) => {
                    btn.addEventListener('click', () => persist(btn.getAttribute('data-consent') || 'essential'));
                });

                if (closeBtn) {
                    closeBtn.addEventListener('click', () => persist('dismissed'));
                }

                if (gearBtn && settings) {
                    gearBtn.addEventListener('click', () => {
                        const isOpen = !settings.hidden;
                        settings.hidden = isOpen;
                        gearBtn.setAttribute('aria-expanded', String(!isOpen));
                    });
                }
            })();
        </script>
    </body>
</html>
