@php
    $solicitudErrors = $errors->solicitudServicio;
    $showModalErrors = old('origen', 'modal') === 'modal';
@endphp

<div class="modal fade"
    id="solicitudServicioModal"
    tabindex="-1"
    aria-labelledby="solicitudServicioModalLabel"
    aria-hidden="true"
    data-open-on-load="{{ $solicitudErrors->any() && $showModalErrors ? 'true' : 'false' }}">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route('solicitudes.store') }}"
                method="POST"
                id="solicitudServicioForm"
                data-service-request-form
                novalidate>
                @csrf

                <div class="modal-header">
                    <div>
                        <h2 class="modal-title fs-5" id="solicitudServicioModalLabel">
                            Solicitar servicio
                        </h2>
                        <p class="text-muted small mb-0 mt-1">
                            Déjanos tus datos y nos comunicaremos contigo.
                        </p>
                    </div>
                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    @include('partials.service-request-fields', [
                        'prefix' => 'solicitudModal',
                        'source' => 'modal',
                        'showErrors' => $showModalErrors,
                    ])
                </div>

                <div class="modal-footer">
                    <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <span class="d-inline-block"
                        data-service-submit-wrapper
                        @if(!$showModalErrors || !old('acepta_contacto'))
                            title="Marca la casilla de aceptación para enviar la solicitud"
                            tabindex="0"
                        @endif>
                        <button type="submit"
                            class="btn bg-color"
                            data-service-submit
                            {{ $showModalErrors && old('acepta_contacto') ? '' : 'disabled' }}>
                            <i class="fa-regular fa-paper-plane me-2"></i>
                            Enviar solicitud
                        </button>
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>
