<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Cocina en Casa - @yield('title', 'Chef a Domicilio')</title>

        @vite(['resources/css/app.css'])
    
        @stack('styles')

    </head>
    <body class="bg-light">

        @hasSection('navbar')
            @yield('navbar')
        @else
            @include('partials.navbar')
        @endif

        <main>
            @yield('content')
        </main>

        @unless(request()->routeIs('admin.*'))
            @include('partials.footer')
        @endunless

        {{-- BOTON FLOTANTE DE WSP (OPCIONAL) --}}
        <a href="{{ $whatsapp_url }}" class="whatsapp-float btn-whatsapp d-none" target="_blank">
            <i class="fa-brands fa-whatsapp"></i> Reservar ahora
        </a>

        {{-- BOTÓN PARA VOLVER AL INICIO --}}
        <button type="button"
            class="back-to-top"
            data-back-to-top
            aria-label="Volver al inicio de la página"
            aria-hidden="true"
            tabindex="-1">
            <i class="bi bi-arrow-up" aria-hidden="true"></i>
        </button>

    {{----------------}}
    {{-- JAVASCRIPT --}}
    {{----------------}}

        {{-- 1. Librerías tradicionales desde la carpeta public/) --}}
        {{-- *no pude meterlos en resources (Vite) por problemas de inicializacion --}}
        <script src="{{ asset('js/bootstrap/bootstrap.bundle.min.js') }}"></script> {{-- BOOTSTRAP 5.3.8--}}
        <script src="{{ asset('js/swiper/swiper-bundle.min.js') }}"></script> {{-- CARRUSEL 11.0.0 --}}
        <script src="{{ asset('js/cropper/cropper.min.js') }}"></script> {{-- CROPPER 1.5.13 --}}
        <script src="{{ asset('js/sweetalert/sweetalert2@11.js') }}"></script> {{-- ALERTAS 11.26.25 --}}
        <script src="{{ asset('js/tom-select/tom-select.complete.min.js') }}"></script>{{-- TOM SELECT 2.6.1 --}}
        <script src="{{ asset('js/sorteable/Sortable.min.js') }}"></script> {{-- SORTABLE 1.15.7 --}}

        {{-- 2. JavaScript gestionado por Vite --}}
        @vite(['resources/js/app.js'])

        {{-- 3. Mensajes condicionales de Laravel (Alertas de sesión) --}}
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: @json(session('success')),
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });

                });
            </script>
        @endif
        {{-- TOAST DE ERROR MANUALES --}}
        @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: @json(session('error')),
                        showConfirmButton: false,
                        timer: 5000,
                        timerProgressBar: true
                    });

                });
            </script>
        @endif
        {{-- TOAST DE ERROR LARAVEL (VALIDACIÓN) --}}
        @if($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: @json($errors->first()),
                        showConfirmButton: false,
                        timer: 5000,
                        timerProgressBar: true
                    });
                });
            </script>
        @endif

        {{-- 4. Ranura para los scripts de páginas específicas --}}
        @stack('scripts')
    </body>
</html>
