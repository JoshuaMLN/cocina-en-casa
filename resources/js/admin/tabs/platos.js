document.addEventListener('DOMContentLoaded', function () {
        
/*---------------------------------------------------*/
/* CROPPER */
/*---------------------------------------------------*/
    const platoModal = new bootstrap.Modal(document.getElementById('platoModal'));
    const platoImageInput = document.getElementById('platoImageInput');
    const croppedPreview = document.getElementById('platoPreview');
    const croppedPlatoImageInput = document.getElementById('cropped_image');
    const btnSubmit = document.getElementById('btnGuardarPlato');
    const previewContainer = document.getElementById('platoPreviewContainer');
    const platoImagenActualText = document.getElementById('platoImagenActualText');

    platoImageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        if (!allowedTypes.includes(file.type)) {
            Swal.fire({
                icon: 'error',
                title: 'Archivo no permitido',
                text: 'Solo se permiten imágenes JPG, PNG o WEBP'
            });
            platoImageInput.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(event) {
            // Ocultar modal plato antes del cropper
            platoModal.hide();

            openCropper({
                title: 'Recortar Plato',
                imageSrc: event.target.result,

                aspectRatio: 4 / 3,
                width: 800,
                height: 600,

                onCrop: function(base64data) {
                    croppedPreview.src = base64data;
                    previewContainer.classList.remove('d-none');
                    platoImagenActualText.textContent = 'Imagen Anterior'
                    croppedPlatoImageInput.value = base64data;
                    btnSubmit.disabled = false;

                    // Volver a mostrar modal plato
                    platoModal.show();
                }
            });
            // reset input para permitir re-subir mismo archivo
            platoImageInput.value = '';
        };
        reader.readAsDataURL(file);
    });

/*---------------------------------------------------*/
/* ORDENAR PLATOS */
/*---------------------------------------------------*/
    const tbody = document.getElementById('sortablePlatos');
    if (!tbody) return;
    new Sortable(tbody, {
        animation: 150,
        handle: '.drag-handle',
        ghostClass: 'sortable-ghost',

        onEnd: function () {
            const platos = [];
            tbody.querySelectorAll('tr').forEach((row, index) => {
                platos.push({
                    id: row.dataset.id,
                    orden: index + 1
                });
            });

            fetch('/admin/platos/reordenar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content')
                },
                body: JSON.stringify({
                    platos: platos
                })

            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Orden actualizado',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            })
            .catch(error => {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo actualizar el orden.'
                });
            });
        }
    });

/*---------------------------------------------------*/
/* MODAL PLATO (CREAR / EDITAR)*/
/*---------------------------------------------------*/
    // platoModal desde CROPPER
    // platoImagenActualText desde CROPPER
    const platoForm = document.getElementById('platoForm');
    const platoModalTitle = document.getElementById('platoModalTitle');
    const platoMethodContainer = document.getElementById('platoMethodContainer');

    const platoNombre = document.getElementById('platoNombre');
    const platoDescripcion = document.getElementById('platoDescripcion');

    const platoPreviewContainer = document.getElementById('platoPreviewContainer');
    const platoPreview = document.getElementById('platoPreview');
    const platoPreviewText = document.getElementById('platoPreviewText');

    const platoImagenActualContainer = document.getElementById('platoImagenActualContainer');
    const platoImagenActual = document.getElementById('platoImagenActual');

    const croppedImageInput = document.getElementById('cropped_image');
    const btnGuardarPlato = document.getElementById('btnGuardarPlato');

    /*---------------------------------------------------*/
    /* NUEVO PLATO */
    /*---------------------------------------------------*/
    document.getElementById('btnNuevoPlato').addEventListener('click', function () {

        platoModalTitle.textContent = 'Nuevo Plato';

        platoForm.action = '/admin/platos';
        platoForm.title = 'Añadiendo Plato';

        platoMethodContainer.innerHTML = '';

        platoNombre.value = '';
        platoDescripcion.value = '';

        croppedImageInput.value = '';

        platoPreview.src = '';
        platoPreviewContainer.classList.add('d-none');

        platoImagenActual.src = '';
        platoImagenActualContainer.classList.add('d-none');

        btnGuardarPlato.disabled = true;

        platoModal.show();
    });

    /*---------------------------------------------------*/
    /* EDITAR PLATO */
    /*---------------------------------------------------*/
    document.querySelectorAll('.btn-editar-plato').forEach(button => {

        button.addEventListener('click', function () {

            const id = this.dataset.id;
            const nombre = this.dataset.nombre;
            const descripcion = this.dataset.descripcion ?? '';
            const imagen = this.dataset.imagen;

            platoModalTitle.textContent = 'Editar Plato';

            platoForm.action = `/admin/platos/${id}`;
            platoForm.title = 'Actualizando Plato';

            platoMethodContainer.innerHTML = `
                <input type="hidden" name="_method" value="PUT">
            `;

            platoNombre.value = nombre;
            platoDescripcion.value = descripcion;

            croppedImageInput.value = '';

            platoPreview.src = '';
            platoPreviewText.textContent = 'Imagen Nueva'
            platoPreviewContainer.classList.add('d-none');

            platoImagenActual.src = imagen;
            platoImagenActualText.textContent = 'Imagen Actual'
            platoImagenActualContainer.classList.remove('d-none');

            btnGuardarPlato.disabled = false;

            platoModal.show();
        });

    });

    /*---------------------------------------------------*/
    /* LOADER DEL MODAL PLATO */
    /*---------------------------------------------------*/
    let formAdd = document.getElementById('platoForm');
    formAdd.addEventListener('submit', function(e) {
        e.preventDefault();
        showLoadingModal(
            platoForm.title,
            'Por favor espere...'
        );
        formAdd.submit();
    });

/*---------------------------------------------------*/
/* ACTIVAR/DESACTIVAR PLATO */
/*---------------------------------------------------*/
    document.querySelectorAll('.form-toggle-plato').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const isActive = form.querySelector('button').classList.contains('btn-secondary');

            Swal.fire({
                title: isActive
                    ? '¿Desactivar plato?'
                    : '¿Activar plato?',
                text: isActive
                    ? 'El plato dejará de mostrarse en la página principal.'
                    : 'El plato volverá a mostrarse en la página principal.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: isActive
                    ? 'Sí, desactivar'
                    : 'Sí, activar',

                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoadingModal(
                        isActive
                            ? 'Desactivando Plato'
                            : 'Activando Plato',
                        'Por favor espere...'
                    );
                    form.submit();
                }
            });
        });
    });

/*---------------------------------------------------*/
/* ELIMINAR PLATO */
/*---------------------------------------------------*/
    document.querySelectorAll('.form-delete-plato').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Eliminar plato?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoadingModal(
                        'Eliminando Plato',
                        'Por favor espere...'
                    );
                    form.submit();
                }
            });
        });
    });

});