<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top"
    data-smart-navbar>
    <div class="container">

        <!-- Logo -->
        <a class="navbar-brand fw-bold fs-4 d-flex align-items-center" href="/">
            @php
                // Consultamos a la base de datos si existe la llave del logo
                $logoSetting = \App\Models\Setting::where('key', 'navbar_logo')->first();
            @endphp
            {{-- Si existe el logo, mostramos la imagen --}}
            @if($logoSetting && $logoSetting->value)
                <img src="{{ asset('storage/' . $logoSetting->value) }}" alt="" height="40" class="me-3 object-fit-contain">
            @else
                {{-- Si no hay logo en la BD, mostramos el ícono por defecto --}}
                <i class="fa-solid fa-utensils text-color me-3 fs-1"></i>
            @endif

            <div>
                Cocina <span class="text-color"> en Casa </span>
            </div>
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav"
                aria-controls="navbarNav"
                aria-expanded="false"
                aria-label="Mostrar menú de navegación">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <!-- Menú centrado -->
            <ul class="navbar-nav mx-auto">
                <li class="nav-item mx-3">
                    <a class="nav-link" href="{{ url('/') }}#main-section" data-section-link>Inicio</a>
                </li>

                <li class="nav-item mx-3">
                    <a class="nav-link" href="{{ url('/') }}#platos" data-section-link>Servicios</a>
                </li>

                <li class="nav-item mx-3">
                    <a class="nav-link" href="{{ url('/') }}#como-funciona" data-section-link>Cómo funciona</a>
                </li>

                <li class="nav-item mx-3">
                    <a class="nav-link" href="{{ url('/') }}#nosotros" data-section-link>Nosotros</a>
                </li>

                <li class="nav-item mx-3">
                    <a class="nav-link" href="{{ url('/') }}#contacto" data-section-link>Contacto</a>
                </li>

                <li class="nav-item item-wsp d-lg-none">
                    <a href="{{ $whatsapp_url }}"
                        target="_blank"
                        class="nav-link d-flex align-items-center gap-2">
                        <i class="fab fa-whatsapp fs-4"></i>
                        <span>Escríbenos al WhatsApp</span>
                    </a>
                </li>

            </ul>

            <!-- Botón WhatsApp -->
            <div class="d-flex d-none d-lg-block">
                <a href="{{ $whatsapp_url }}"
                    target="_blank"
                    class="btn btn-whatsapp fw-semibold d-flex align-items-center">
                    <i class="fab fa-whatsapp fs-2"></i>
                    <span class="ms-2 d-none d-xl-block">Escríbenos al WhatsApp</span>
                </a>
            </div>

        </div>
    </div>
</nav>
