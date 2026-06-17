@extends('layouts.app')

@section('title', 'Inicio')

@push('styles')
    @vite('resources/css/home.css')
@endpush

@section('content')
<div class="bg-container">
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
                    <button class="btn btn-lg btn-contactar fw-bold d-flex align-items-center">
                        <i class="fa-solid fa-bell-concierge me-2 fs-2"></i>
                        <span class="d-none d-xl-block">Solicitar servicio</span>
                        <span class="d-xl-none">Contactar</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CARRUSEL DE PLATOS --}}
<div class="container container-platos mt-5 position-relative">
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

{{-- COMO FUNCIONA --}}
<div id="como-funciona" class="bg-container">
    <div class="container pt-3 mt-2 pb-3 mb-2 position-relative">
        <h3 class="text-center fw-bold mb-4 title-curved-line">¿Cómo Funciona?</h3>
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
</div>
@endsection

@push('scripts')
    @vite('resources/js/home.js')
@endpush
