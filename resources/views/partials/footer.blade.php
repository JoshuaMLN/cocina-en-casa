<footer class="site-footer">
    <div class="container">
        <div class="row g-4 g-lg-5 py-5">
            <div class="col-lg-5">
                <a href="{{ route('home') }}#main-section"
                    class="site-footer-brand">
                    @if($footer['logo'])
                        <img src="{{ asset('storage/' . $footer['logo']) }}"
                            alt="Logo de Cocina en Casa"
                            class="site-footer-logo">
                    @else
                        <span class="site-footer-logo-placeholder">
                            <i class="fa-solid fa-utensils"></i>
                        </span>
                    @endif
                    <span>Cocina <strong>en Casa</strong></span>
                </a>

                @if($footer['description'])
                    <p class="site-footer-description">
                        {{ $footer['description'] }}
                    </p>
                @endif

                @if($footer['service_area'])
                    <p class="site-footer-location">
                        <i class="fa-solid fa-location-dot"
                            aria-hidden="true"></i>
                        <span>{{ $footer['service_area'] }}</span>
                    </p>
                @endif
            </div>

            <div class="col-sm-6 col-lg-3">
                <h2 class="site-footer-title">Secciones</h2>
                <nav aria-label="Secciones del sitio">
                    <ul class="site-footer-links">
                        <li>
                            <a href="{{ route('home') }}#main-section">Inicio</a>
                        </li>
                        <li>
                            <a href="{{ route('home') }}#platos">Servicios</a>
                        </li>
                        <li>
                            <a href="{{ route('home') }}#como-funciona">
                                Cómo funciona
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('home') }}#nosotros">Nosotros</a>
                        </li>
                        <li>
                            <a href="{{ route('home') }}#contacto">Contacto</a>
                        </li>
                    </ul>
                </nav>
            </div>

            <div class="col-sm-6 col-lg-4">
                <h2 class="site-footer-title">Contáctanos</h2>

                <ul class="site-footer-contact">
                    @if($whatsapp_available)
                        <li>
                            <a href="{{ $whatsapp_url }}"
                                target="_blank"
                                rel="noopener noreferrer">
                                <i class="fa-brands fa-whatsapp"
                                    aria-hidden="true"></i>
                                <span>+{{ $whatsapp }}</span>
                            </a>
                        </li>
                    @endif

                    @if($footer['email'])
                        <li>
                            <a href="mailto:{{ $footer['email'] }}">
                                <i class="fa-regular fa-envelope"
                                    aria-hidden="true"></i>
                                <span>{{ $footer['email'] }}</span>
                            </a>
                        </li>
                    @endif
                </ul>

                @if($footer['facebook'] || $footer['instagram'] || $footer['tiktok'])
                    <div class="site-footer-socials"
                        aria-label="Redes sociales">
                        @if($footer['facebook'])
                            <a href="{{ $footer['facebook'] }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label="Facebook">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                        @endif

                        @if($footer['instagram'])
                            <a href="{{ $footer['instagram'] }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label="Instagram">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                        @endif

                        @if($footer['tiktok'])
                            <a href="{{ $footer['tiktok'] }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label="TikTok">
                                <i class="fa-brands fa-tiktok"></i>
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="site-footer-bottom">
            <small>
                © {{ now()->year }} Cocina en Casa. Todos los derechos reservados.
            </small>
        </div>
    </div>
</footer>
