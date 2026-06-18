document.addEventListener('DOMContentLoaded', function() {

/*---------------------------------------------------*/
/* TOM SELECT */
/*------------------------------------------------- -*/
    globalThis.myTomSelects.general = new TomSelect('#countrySelect', {

        valueField: 'value',
        labelField: 'text',
        searchField: 'text',
        controlInput: null,

        render: {
            option: function(data, escape) {
                return `
                    <div class="d-flex align-items-center">
                        <img
                            src="${data.icon}"
                            width="20"
                            height="20"
                            class="me-2">
                        ${escape(data.text)}
                    </div>
                `;
            },

            item: function(data, escape) {
                return `
                    <div class="d-flex align-items-center">
                        <img
                            src="${data.icon}"
                            width="20"
                            height="20"
                            class="me-2">
                        ${escape(data.text)}
                    </div>
                `;
            }
        },

        options: Array.from(
            document.querySelectorAll('#countrySelect option')
        ).map(option => ({
            value: option.value,
            text: option.textContent.trim(),
            icon: option.dataset.icon
        }))
    });

/*---------------------------------------------------*/
/* CROPPER */
/*------------------------------------------------- -*/
    const logoInput = document.getElementById('logoInput');
    const croppedPreview = document.getElementById('croppedPreview');
    const croppedLogoInput = document.getElementById('cropped_logo');
    const btnSubmit = document.getElementById('btnSubmit');
    const previewContainer = document.getElementById('previewContainer');

    logoInput.addEventListener('change', function(e) {
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
            logoInput.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = function(event) {
            // Función del Cropper en crop-modal.blade.php
            openCropper({
                title: 'Recortar Logo',
                imageSrc: event.target.result,

                aspectRatio: 1,
                width: 300,
                height: 300,

                onCrop: function(base64data) {
                    croppedPreview.src = base64data;
                    previewContainer.classList.remove('d-none');
                    croppedLogoInput.value = base64data;
                    btnSubmit.disabled = false;
                }
            });
            // reset input para permitir re-subir mismo archivo
            logoInput.value = '';
        };
        reader.readAsDataURL(file);
    });

/*---------------------------------------------------*/
/* LOADER */
/*------------------------------------------------- -*/
    // Escuchar el submit del formulario de Logo
    const logoForm = document.getElementById('logoForm');

    logoForm.addEventListener('submit', function() {

        btnSubmit.disabled = true;

        showLoadingModal(
            'Actualizando Logo',
            'Procesando imagen y guardando cambios...'
        );

    });

/*---------------------------------------------------*/
/* SWAL CONFIRM */
/*------------------------------------------------- -*/
    // Escuchar el submit del formulario de WhatsApp
    const WspForm = document.getElementById('WspForm');

    WspForm.addEventListener('submit', function(e) {
        e.preventDefault(); // detener envío

        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Se actualizará el número y mensaje de WhatsApp',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, actualizar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {

            if (result.isConfirmed) {

                showLoadingModal(
                    'Actualizando WhatsApp',
                    'Guardando cambios...'
                );

                WspForm.submit(); // enviar manualmente
            }
        });
    });

/*---------------------------------------------------*/
/* SECCIÓN NOSOTROS */
/*---------------------------------------------------*/
    const nosotrosForm = document.getElementById('nosotrosForm');
    const nosotrosImageInput = document.getElementById('nosotrosImageInput');
    const croppedNosotrosImage = document.getElementById('cropped_nosotros_image');
    const nosotrosPreview = document.getElementById('nosotrosPreview');
    const nosotrosPreviewContainer = document.getElementById('nosotrosPreviewContainer');

    nosotrosImageInput.addEventListener('change', function(e) {
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
            nosotrosImageInput.value = '';
            return;
        }

        const reader = new FileReader();

        reader.onload = function(event) {
            openCropper({
                title: 'Recortar imagen de Nosotros',
                imageSrc: event.target.result,
                aspectRatio: 4 / 3,
                width: 1200,
                height: 900,
                mimeType: 'image/webp',
                quality: 0.88,

                onCrop: function(base64data) {
                    croppedNosotrosImage.value = base64data;
                    nosotrosPreview.src = base64data;
                    nosotrosPreviewContainer.classList.remove('d-none');
                }
            });

            nosotrosImageInput.value = '';
        };

        reader.readAsDataURL(file);
    });

    document.querySelectorAll('[data-character-count]').forEach(counter => {
        const input = document.getElementById(counter.dataset.characterCount);

        const updateCount = () => {
            counter.textContent = input.value.length;
        };

        input.addEventListener('input', updateCount);
        updateCount();
    });

    const footerForm = document.getElementById('footerForm');

    footerForm.addEventListener('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: '¿Actualizar el footer?',
            text: 'Los cambios se mostrarán en la parte inferior del sitio.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, actualizar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (!result.isConfirmed) return;

            showLoadingModal(
                'Actualizando Footer',
                'Guardando información de contacto...'
            );

            footerForm.submit();
        });
    });

    nosotrosForm.addEventListener('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: '¿Actualizar la sección Nosotros?',
            text: 'Los cambios se mostrarán en la página principal.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, actualizar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (!result.isConfirmed) return;

            showLoadingModal(
                'Actualizando Nosotros',
                'Guardando imagen y contenido...'
            );

            nosotrosForm.submit();
        });
    });

});
