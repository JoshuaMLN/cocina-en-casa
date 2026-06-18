@extends('layouts.app')

@section('title', 'Inicio')

@push('styles')
    @vite('resources/css/home.css')
@endpush

@section('content')
<header id="main-section" class="bg-container">
    <div class="container main-container">
        <div class="row align-items-center h-100">
            <div class="container-text col-md-6 text-center text-md-start pe-4 my-auto">
                {{-- TÍTULO --}}
                <h1 class="display-4 fw-bold text-dark">Comida casera hecha <span class="text-color">en tu hogar</span></h1>
                {{-- DESCRIPCION --}}
                <p class="lead mt-3 pe-5">
                    Te ayudamos a preparar deliciosas comidas en la comodidad de tu casa.
                </p>
                {{-- ICONOS --}}
                <div class="row main-icons mt-3">
                    <div class="col text-center">
                        <i class="fa-regular fa-clock d-inline-flex align-items-center justify-content-center"></i>
                        <small class="d-block fw-bold mt-2">Rápido y fácil</small>
                    </div>
                    <div class="col text-center">
                        <i class="fa-solid fa-shield-halved d-inline-flex align-items-center justify-content-center"></i>
                        <small class="d-block fw-bold mt-2"> Confiable y seguro </small>
                    </div>
                    <div class="col text-center">
                        <i class="fa-solid fa-house d-inline-flex align-items-center justify-content-center"></i>
                        <small class="d-block fw-bold mt-2"> Comodidad en tu hogar</small>
                    </div>
                </div>
                {{-- BOTONES --}}
                <div class="container-btn-main d-flex mt-3 gap-2 gap-sm-4">
                    <a href="{{ $whatsapp_url }}"
                        target="_blank"
                        class="btn btn-whatsapp fw-semibold d-flex align-items-center">
                        <i class="fab fa-whatsapp fs-1"></i>
                        <div class="d-flex flex-column text-start">
                            <span class="ms-2 d-none d-xl-block">Escríbenos al WhatsApp</span>
                            <small class="ms-2 d-none d-xl-block">Respuesta en menos de 1 minuto</small>
                            <span class="ms-2 d-xl-none fs-5">WhatsApp</span>
                        </div>
                    </a>
                    <button type="button"
                        class="btn btn-lg btn-contactar fw-bold d-flex align-items-center"
                        data-bs-toggle="modal"
                        data-bs-target="#solicitudServicioModal">
                        <i class="fa-solid fa-bell-concierge me-2 fs-2"></i>
                        <span class="d-none d-xl-block">Solicitar servicio</span>
                        <span class="d-xl-none">Contactar</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>

{{-- CARRUSEL DE PLATOS --}}
<section id="platos" class="container container-platos pt-5 pb-4"
    aria-labelledby="platos-title">
    <h2 id="platos-title" class="text-center fw-bold mb-4">
        Nuestros platos
    </h2>
    <div class="position-relative">
    <div class="swiper swiper-platos">
        <div class="swiper-wrapper">
            @forelse($platos as $plato)

                <div class="swiper-slide">
                    <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                        <img
                            src="{{ asset('storage/' . $plato->imagen) }}"
                            class="card-img-top object-fit-cover"
                            height="180"
                            alt="{{ $plato->nombre }}">

                        <div class="card-body text-center">
                            <h5 class="fw-bold">
                                {{ $plato->nombre }}
                            </h5>
                            @if($plato->descripcion)
                                <p class="mb-0 text-muted small">
                                    {{ $plato->descripcion }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="swiper-slide">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <h5 class="text-muted mb-0">
                                Próximamente nuevos platos
                            </h5>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
        <div class="swiper-pagination"></div>
    </div>
    <div class="swiper-button-prev text-dark user-select-none"></div>
    <div class="swiper-button-next text-dark user-select-none"></div>
    </div>
</section>

{{-- COMO FUNCIONA --}}
<section id="como-funciona"
    class="bg-container"
    aria-labelledby="como-funciona-title">
    <div class="container py-5 position-relative">
        <h2 id="como-funciona-title" class="text-center fw-bold mb-4 title-curved-line">¿Cómo Funciona?</h2>
        <div class="container-operation row mx-2 mx-sm-0 g-4 g-lg-0">
            <div class="col-6 col-sm d-flex flex-column align-items-center text-center">
                <div class="circle-number">
                    <span>1</span>
                </div>
                <div class="circle-icon">
                    <i class="bi bi-clipboard2-check"></i>
                </div>
                <div class="container-text">
                    <h6>1. Nos contactas</h6>
                    <small>Completa el formulario o escríbrenos al Whatsapp.</small>
                </div>
            </div>
            <div class="d-none d-lg-block col-auto arrow text-muted"><x-arrow-dash /></div>
            <div class="col-6 col-sm d-flex flex-column align-items-center text-center">
                <div class="circle-number bg-green">
                    <span>2</span>
                </div>
                <div class="circle-icon">
                    <img class="color-green" src="{{ asset('icons/chef-icon.png') }}" alt="Icono Chef">
                </div>
                <div class="container-text">
                    <h6>2. Te asesoramos</h6>
                    <small>Te ayudamos a elegir la mejor opción según tus necesidades.</small>
                </div>
            </div>
            <div class="d-none d-lg-block col-auto arrow text-muted"><x-arrow-dash /></div>
            <div class="col-6 col-sm d-flex flex-column align-items-center text-center">
                <div class="circle-number">
                    <span>3</span>
                </div>
                <div class="circle-icon">
                    <img class="color" src="{{ asset('icons/bandeja-de-comida-icon.png') }}" alt="Icono Chef">
                </div>
                <div class="container-text">
                    <h6>3. Preparamos en tu hogar</h6>
                    <small>Llegamos a tu casa y preparamos la comida que necesitas.</small>
                </div>
            </div>
            <div class="d-none d-lg-block col-auto arrow text-muted"><x-arrow-dash /></div>
            <div class="col-6 col-sm d-flex flex-column align-items-center text-center">
                <div class="circle-number bg-green">
                    <span>4</span>
                </div>
                <div class="circle-icon color-green">
                    <i class="fa-regular fa-face-smile"></i>
                </div>
                <div class="container-text">
                    <h6>4. Tú disfrutas</h6>
                    <small>Disfruta de una deliciosa comida casera sin preocuparte por nada.</small>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- NOSOTROS --}}
<section id="nosotros"
    class="container container-nosotros py-5"
    aria-labelledby="nosotros-title">
    <div class="row align-items-center g-4 g-lg-5">
        <div class="col-lg-5">
            <div class="nosotros-visual mx-auto">
                <img
                    src="{{ $nosotros['imagen']
                        ? asset('storage/' . $nosotros['imagen'])
                        : asset('images/fondo-container-main-zoom-no.png') }}"
                    alt="Equipo de Cocina en Casa">
                <span class="nosotros-badge">
                    <i class="fa-solid fa-heart me-2"></i>
                    {{ $nosotros['mensaje'] }}
                </span>
            </div>
        </div>

        <div class="col-lg-7 text-center text-lg-start">
            <h2 id="nosotros-title" class="fw-bold mb-4 title-curved-line title-curved-line-start">
                ¿Quiénes somos?
            </h2>
            <p class="lead text-dark mb-3">
                {{ $nosotros['slogan'] }}
            </p>
            <p class="text-muted mb-4">
                {{ $nosotros['descripcion'] }}
            </p>

            <div class="row g-3 justify-content-center justify-content-lg-start">
                @foreach($nosotros['palabras_clave'] as $palabraClave)
                    <div class="col-sm">
                        <div class="nosotros-value h-100">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>{{ $palabraClave }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- CONTACTO --}}
<section id="contacto"
    class="contact-section"
    aria-labelledby="contacto-title"
    data-contact-section
    data-scroll-on-load="{{ $errors->solicitudServicio->any() && old('origen') === 'contacto' ? 'true' : 'false' }}">
    <div class="container py-5">
        <div class="row align-items-center g-4 g-lg-5">
            <div class="col-lg-5">
                <span class="contact-eyebrow">Estamos para ayudarte</span>
                <h2 id="contacto-title"
                    class="fw-bold mt-2 mb-4 title-curved-line title-curved-line-start">
                    Conversemos sobre lo que necesitas
                </h2>
                <p class="text-muted mb-4">
                    Envíanos una solicitud y nos pondremos en contacto contigo.
                    Si prefieres una respuesta más rápida, también puedes
                    escribirnos directamente por WhatsApp.
                </p>

                <div class="contact-whatsapp-card">
                    <span class="contact-whatsapp-icon">
                        <i class="fa-brands fa-whatsapp"></i>
                    </span>
                    <div class="flex-grow-1">
                        <h3 class="h5 fw-bold mb-1">¿Prefieres WhatsApp?</h3>
                        <p class="small text-muted mb-3">
                            Cuéntanos qué necesitas y conversemos directamente.
                        </p>
                        <a href="{{ $whatsapp_url }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="btn btn-whatsapp fw-semibold">
                            <i class="fa-brands fa-whatsapp me-2"></i>
                            Escribir por WhatsApp
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="contact-form-card">
                    <div class="mb-4">
                        <h3 class="h4 fw-bold mb-1">Solicitar servicio</h3>
                        <p class="text-muted small mb-0">
                            Déjanos tus datos y nos comunicaremos contigo.
                        </p>
                    </div>

                    <form action="{{ route('solicitudes.store') }}"
                        method="POST"
                        data-service-request-form
                        novalidate>
                        @csrf

                        @include('partials.service-request-fields', [
                            'prefix' => 'solicitudContacto',
                            'source' => 'contacto',
                            'showErrors' => old('origen') === 'contacto',
                        ])

                        <div class="d-flex justify-content-end mt-4">
                            <span class="d-inline-block"
                                data-service-submit-wrapper
                                @if(old('origen') !== 'contacto' || !old('acepta_contacto'))
                                    title="Marca la casilla de aceptación para enviar la solicitud"
                                    tabindex="0"
                                @endif>
                                <button type="submit"
                                    class="btn bg-color px-4"
                                    data-service-submit
                                    {{ old('origen') === 'contacto' && old('acepta_contacto') ? '' : 'disabled' }}>
                                    <i class="fa-regular fa-paper-plane me-2"></i>
                                    Enviar solicitud
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@include('partials.service-request-modal')
@endsection

@push('scripts')
    @vite('resources/js/home.js')
@endpush
