{{-- MODAL DE RECORTE DE IMAGEN --}}
<div class="modal fade" id="cropModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="cropModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cropModalLabel">Recortar Imagen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="container-image-crop">
                    <img class="image-crop" id="imageToCrop" src="" alt="">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnAcceptCrop">Aceptar Recorte</button>
            </div>
        </div>
    </div>
</div>
