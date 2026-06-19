import './tabs/general.js';
import './tabs/platos.js';
import './tabs/solicitudes.js';
import './tabs/configuracion.js';

document.addEventListener('DOMContentLoaded', function () {

/*---------------------------------------------------*/
/* TABS */
/*------------------------------------------------- -*/
    const tabs = document.querySelectorAll(
        '#adminTabs button[data-bs-toggle="tab"]'
    );

    // Recuperar último tab abierto
    const queryParams = new URLSearchParams(window.location.search);
    const activeTab = queryParams.has('solicitudes_page')
        ? '#solicitudes'
        : localStorage.getItem('adminActiveTab');

    if (activeTab) {

        const trigger = document.querySelector(
            `#adminTabs button[data-bs-target="${activeTab}"]`
        );

        if (trigger) {
            bootstrap.Tab.getOrCreateInstance(trigger).show();
        }
    }

    document.getElementById('tabs-spinner').classList.add('d-none'); // Oculta el cargando
    document.getElementById('tabs-wrapper').classList.remove('d-none'); // Muestra las pestañas listas

    // Guardar tab al cambiar
    tabs.forEach(tab => {
        tab.addEventListener('shown.bs.tab', function () {
            localStorage.setItem(
                'adminActiveTab',
                this.dataset.bsTarget
            );
        });
    });

/*---------------------------------------------------*/
/* CROPPER GENÉRICO */
/*------------------------------------------------- -*/
    globalThis.cropper = null;
    globalThis.cropConfig = null;

    const cropModalElement = document.getElementById('cropModal');
    const cropModal = new bootstrap.Modal(cropModalElement);

    const imageToCrop = document.getElementById('imageToCrop');
    const btnAcceptCrop = document.getElementById('btnAcceptCrop');

    globalThis.openCropper = function(config) {
        globalThis.cropConfig = config;
        document.getElementById('cropModalLabel').textContent =
            config.title;

        imageToCrop.src = config.imageSrc;
        cropModal.show();
    };

    cropModalElement.addEventListener('shown.bs.modal', function() {
        if (globalThis.cropper) {
            globalThis.cropper.destroy();
        }
        globalThis.cropper = new Cropper(imageToCrop, {
            aspectRatio: globalThis.cropConfig.aspectRatio,
            viewMode: 1,
            background: false,
            dragMode: 'none'
        });
    });

    cropModalElement.addEventListener('hidden.bs.modal', function() {
        if (globalThis.cropper) {
            globalThis.cropper.destroy();
            globalThis.cropper = null;
        }
    });

    btnAcceptCrop.addEventListener('click', function() {
        if (!globalThis.cropper) return;
        const canvas = globalThis.cropper.getCroppedCanvas({
            width: globalThis.cropConfig.width,
            height: globalThis.cropConfig.height
        });
        const base64data = canvas.toDataURL(
            globalThis.cropConfig.mimeType ?? 'image/png',
            globalThis.cropConfig.quality ?? 0.92
        );
        globalThis.cropConfig.onCrop(base64data);
        cropModal.hide();
    });

});
