<footer class="mq-login-bottom" aria-label="Pie de página">
    @php
        $bizPhone = trim((string) ($settings->telefono ?? ''));
        $bizAddress = trim((string) ($settings->direccion ?? ''));
        $bizEmail = trim((string) ($settings->email ?? ''));

        if ($bizPhone === '') $bizPhone = '3102792128 - 3177487468';
        if ($bizAddress === '') $bizAddress = 'Municipio la Calera, Kilometro 28 Vía Quizquiza';
        if ($bizEmail === '') $bizEmail = 'masqueperrosshows@gmail.com';
    @endphp
    <div class="mq-login-footer-inner">
        <div class="mq-login-footer-left">
            <img class="mq-login-footer-brand" src="{{ asset('img/footer-brand.png') }}" alt="Mas Que Perros">
            <img class="mq-login-footer-tagline" src="{{ asset('img/footer-tagline.png') }}" alt="Tu perro feliz, tu tranquilo">
        </div>

        <div class="mq-login-footer-col">
            <img class="mq-login-footer-heading" src="{{ asset('img/footer-contacto.png') }}" alt="Contacto">
            <div class="mq-footer-contact-info" aria-label="Información de contacto">
                <div class="mq-footer-contact-row">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mq-footer-contact-ico" viewBox="0 0 16 16" aria-hidden="true">
                        <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877zM12.5 1a.5.5 0 0 1 .5.5V3h1.5a.5.5 0 0 1 0 1H13v1.5a.5.5 0 0 1-1 0V4h-1.5a.5.5 0 0 1 0-1H12V1.5a.5.5 0 0 1 .5-.5"/>
                    </svg>
                    <div class="mq-footer-contact-text">
                        <div>{{ $bizPhone }}</div>
                    </div>
                </div>

                <div class="mq-footer-contact-row">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mq-footer-contact-ico" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6"/>
                    </svg>
                    <div class="mq-footer-contact-text">
                        <div>{{ $bizAddress }}</div>
                    </div>
                </div>

                <div class="mq-footer-contact-row">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mq-footer-contact-ico" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z"/>
                    </svg>
                    <div class="mq-footer-contact-text">
                        <div>{{ $bizEmail }}</div>
                    </div>
                </div>
            </div>
            <ul class="mq-footer-social example-2" aria-label="Redes sociales">
                <li class="icon-content">
                    <a data-social="whatsapp" aria-label="WhatsApp" href="https://wa.me/573102792128" target="_blank" rel="noopener noreferrer">
                        <div class="filled"></div>
                        <svg xml:space="preserve" viewBox="0 0 24 24" class="bi bi-whatsapp" fill="currentColor" height="24" width="24" xmlns="http://www.w3.org/2000/svg">
                            <path fill="currentColor" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"></path>
                        </svg>
                    </a>
                    <div class="tooltip">Whatsapp</div>
                </li>
                <li class="icon-content">
                    <a data-social="instagram" aria-label="Instagram" href="https://www.instagram.com/masqueperrosadiestramiento?igsh=bHY1dmYzaTlocW1j&utm_source=qr" target="_blank" rel="noopener noreferrer">
                        <div class="filled"></div>
                        <svg xml:space="preserve" viewBox="0 0 16 16" class="bi bi-instagram" fill="currentColor" height="16" width="16" xmlns="http://www.w3.org/2000/svg">
                            <path fill="currentColor" d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.0 0 0 0-.923-1.417A3.9 3.0 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334" />
                        </svg>
                    </a>
                    <div class="tooltip">Instagram</div>
                </li>

                <li class="icon-content">
                    <a data-social="facebook" aria-label="Facebook" href="https://www.facebook.com/share/1DDhMJK4WJ/?mibextid=wwXIfr" target="_blank" rel="noopener noreferrer">
                        <div class="filled"></div>
                        <svg xml:space="preserve" viewBox="0 0 24 24" class="bi bi-facbook" fill="currentColor" height="24" width="24" xmlns="http://www.w3.org/2000/svg">
                            <path fill="currentColor" d="M23.9981 11.9991C23.9981 5.37216 18.626 0 11.9991 0C5.37216 0 0 5.37216 0 11.9991C0 17.9882 4.38789 22.9522 10.1242 23.8524V15.4676H7.07758V11.9991H10.1242V9.35553C10.1242 6.34826 11.9156 4.68714 14.6564 4.68714C15.9692 4.68714 17.3424 4.92149 17.3424 4.92149V7.87439H15.8294C14.3388 7.87439 13.8739 8.79933 13.8739 9.74824V11.9991H17.2018L16.6698 15.4676H13.8739V23.8524C19.6103 22.9522 23.9981 17.9882 23.9981 11.9991Z" />
                        </svg>
                    </a>
                    <div class="tooltip">Facebook</div>
                </li>

                <li class="icon-content">
                    <a data-social="tiktok" aria-label="TikTok" href="https://vm.tiktok.com/ZSHTxwt6r3TPH-uvJj0/" target="_blank" rel="noopener noreferrer">
                        <div class="filled"></div>
                        <svg viewBox="0 0 24 24" fill="currentColor" height="24" width="24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.5 3c.4 3.5 2.6 5.6 6 5.8v3.1c-1.9.1-3.5-.5-5.1-1.6v6.1c0 4.4-4.8 7.2-8.7 5.1-3.6-1.9-3.9-7.2-.6-9.6 1.3-1 2.9-1.3 4.5-1.1v3.2c-.5-.1-1-.1-1.5 0-1.5.4-2.4 2-2 3.5.4 1.6 2.2 2.5 3.7 1.9 1.1-.4 1.8-1.4 1.8-2.6V3h1.9z" />
                        </svg>
                    </a>
                    <div class="tooltip">TikTok</div>
                </li>
            </ul>
        </div>

        <div class="mq-login-footer-services">
            <img class="mq-login-footer-heading" src="{{ asset('img/footer-servicios.png') }}" alt="Servicios">
            <ul class="mq-footer-services-list" aria-label="Listado de servicios">
                <li class="mq-footer-service-item">
                    <span class="mq-footer-service-icon-wrap" aria-hidden="true">
                        <img class="mq-footer-service-icon" src="{{ asset('img/icono1.png') }}" alt="">
                    </span>
                    <span class="mq-footer-service-text">Entrenamiento básico integral y deportivo</span>
                </li>
                <li class="mq-footer-service-item">
                    <span class="mq-footer-service-icon-wrap" aria-hidden="true">
                        <img class="mq-footer-service-icon" src="{{ asset('img/icono2.png') }}" alt="">
                    </span>
                    <span class="mq-footer-service-text">Formación y crianza para perros de trabajo o especialidades</span>
                </li>
                <li class="mq-footer-service-item">
                    <span class="mq-footer-service-icon-wrap" aria-hidden="true">
                        <img class="mq-footer-service-icon" src="{{ asset('img/icono3.png') }}" alt="">
                    </span>
                    <span class="mq-footer-service-text">Hotel canino</span>
                </li>
                <li class="mq-footer-service-item">
                    <span class="mq-footer-service-icon-wrap" aria-hidden="true">
                        <img class="mq-footer-service-icon" src="{{ asset('img/icono4.png') }}" alt="">
                    </span>
                    <span class="mq-footer-service-text">Día de diversión en Más que Perros</span>
                </li>
                <li class="mq-footer-service-item">
                    <span class="mq-footer-service-icon-wrap" aria-hidden="true">
                        <img class="mq-footer-service-icon" src="{{ asset('img/icono5.png') }}" alt="">
                    </span>
                    <span class="mq-footer-service-text">Plan padrino</span>
                </li>
            </ul>
        </div>
    </div>
</footer>
