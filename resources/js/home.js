document.addEventListener('DOMContentLoaded', function () {
    
/*---------------------------------------------------*/
/* SWIPER (CARRUSEL) */
/*------------------------------------------------- -*/
    globalThis.mySwipers.platos = new Swiper('.swiper-platos', {
        loop: false,
        // Cuando llegue al último slide y presiones "Siguiente", regresará al inicio
        rewind: true,
        // Espacio entre las tarjetas
        spaceBetween: 20,
        // Deslizar de 1 en 1 sin importar cuántos se vean
        slidesPerGroup: 1,
        // Activamos los dots
        pagination: {
            el: '.swiper-pagination',
            clickable: true, // Permite hacer click en los puntitos para navegar
            dynamicBullets: true, // Si hay muchos platos, hace que los puntitos extremos se vean más pequeños
        },
        // Navegación por flechas
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },

        // Responsividad progresiva (Mobile First)
        breakpoints: {
            // Pantallas muy pequeñas (celulares en vertical)
            0: {
                slidesPerView: 1,
            },
            // Celulares grandes o tablets pequeñas (>= 576px)
            576: {
                slidesPerView: 2,
            },
            // Tablets y laptops pequeñas (>= 768px)
            768: {
                slidesPerView: 3,
            },
            // Pantallas de escritorio normales (>= 992px)
            992: {
                slidesPerView: 4,
            },
            1200: {
                slidesPerView: 5,
            },
            // Pantallas muy grandes / Monitores anchos (>= 1200px)
            1400: {
                slidesPerView: 6,
            }
        }
    });
});