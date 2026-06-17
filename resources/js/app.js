// resources/js/app.js

// Preparar el contenedor global para almacenar carruseles en cualquier página de la web
globalThis.mySwipers = {};
// Preparar el contenedor global para almacenar Tom Selects en cualquier página de la web
globalThis.myTomSelects = {};

// Funciones globales para el Modal de Carga con SweetAlert2
globalThis.showLoadingModal = function(title = 'Procesando', text = 'Por favor espere...') {
    if (Swal !== undefined) {
        Swal.fire({
            title: title,
            text: text,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }
};

globalThis.closeLoadingModal = function() {
    if (Swal !== undefined) {
        Swal.close();
    }
};
