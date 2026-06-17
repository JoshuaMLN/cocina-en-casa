<div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h5 class="mb-1">Administración de Platos</h5>
            <small class="text-muted">
                Gestiona los platos que se muestran en el carrusel principal.
            </small>
        </div>

        <button type="button" class="btn btn-primary"
            id="btnNuevoPlato">
            <i class="fa-solid fa-plus me-2"></i>
            Nuevo Plato
        </button>
    </div>

    @if($platos->isEmpty())
    <div class="alert alert-info mb-0">
        <i class="fa-solid fa-circle-info me-2"></i>
        Aún no hay platos registrados.
    </div>
    @else
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th class="col-sorteable"></th>
                    <th class="col-imagen">Imagen</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th class="col-estado">Estado</th>
                    <th class="col-acciones">Acciones</th>
                </tr>
            </thead>
            <tbody id="sortablePlatos">
                @foreach($platos as $plato)
                    <tr data-id="{{ $plato->id }}">
                        {{-- ARRASTABLE --}}
                        <td class="drag-handle text-center">
                            <i class="fa-solid fa-grip-vertical"></i>
                        </td>
                        {{-- IMAGEN --}}
                        <td>
                            <img
                                src="{{ asset('storage/' . $plato->imagen) }}"
                                alt="{{ $plato->nombre }}"
                                class="rounded border object-fit-cover"
                                width="70"
                                height="70">
                        </td>
                        {{-- NOMBRE --}}
                        <td>
                            <strong>
                                {{ $plato->nombre }}
                            </strong>
                        </td>
                        {{-- DESCRIPCIÓN --}}
                        <td>
                            {{ $plato->descripcion ?: '—' }}
                        </td>
                        {{-- ESTADO --}}
                        <td>
                            @if($plato->activo)
                                <span class="badge text-bg-success">
                                    Activo
                                </span>
                            @else
                                <span class="badge text-bg-secondary">
                                    Inactivo
                                </span>
                            @endif
                        </td>
                        {{-- ACCIONES --}}
                        <td>
                            {{-- EDITAR --}}
                            <button
                                type="button"
                                title="Editar plato"
                                class="btn btn-sm btn-warning btn-editar-plato"

                                data-id="{{ $plato->id }}"
                                data-nombre="{{ $plato->nombre }}"
                                data-descripcion="{{ $plato->descripcion }}"
                                data-imagen="{{ asset('storage/' . $plato->imagen) }}">

                                <i class="fa-solid fa-pen"></i>
                            </button>
                            {{-- ACTIVAR / DESACTIVAR --}}
                            <form action="/admin/platos/{{ $plato->id }}/toggle"
                                method="POST"
                                class="d-inline form-toggle-plato">

                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm
                                    {{ $plato->activo ? 'btn-secondary' : 'btn-success' }}"
                                    title="{{ $plato->activo ? 'Desactivar plato' : 'Activar plato' }}">
                                    <i class="fa-solid {{ $plato->activo ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                </button>
                            </form>
                            {{-- ELIMINAR --}}
                            <form action="/admin/platos/{{ $plato->id }}"
                                method="POST"
                                class="d-inline form-delete-plato">

                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar plato">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

{{-- MODAL CREAR / EDITAR --}}
<div class="modal fade" id="platoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="/admin/platos" method="POST" id="platoForm" title="">
                @csrf
                {{-- INYECCIÓN DE METODO PUT --}}
                <div id="platoMethodContainer"></div>
                {{-- HEADER --}}
                <div class="modal-header">
                    <h5 class="modal-title" id="platoModalTitle">
                        Nuevo Plato
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                {{-- BODY --}}
                <div class="modal-body">
                    {{-- NOMBRE --}}
                    <div class="mb-3">
                        <label class="form-label" for="platoNombre">
                            Nombre <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="platoNombre"
                            name="nombre" maxlength="100" required>
                    </div>

                    {{-- DESCRIPCIÓN --}}
                    <div class="mb-3">
                        <label class="form-label" for="platoDescripcion">
                            Descripción
                        </label>
                        <textarea class="form-control" id="platoDescripcion"
                            name="descripcion" rows="3" maxlength="150"></textarea>
                    </div>

                    {{-- IMAGEN --}}
                    <div class="mb-3">
                        <label class="form-label" for="platoImageInput">
                            Imagen  <span class="text-danger">*</span>
                        </label>
                        <input type="file" class="form-control"
                            id="platoImageInput" accept="image/*">
                        <div class="form-text">
                            Formatos permitidos: JPG, PNG, WEBP
                        </div>
                    </div>
                    <input type="hidden"
                        name="cropped_image" id="cropped_image">

                    {{-- ROW RESPONSIVE PARA LAS IMAGENES --}}
                    <div class="row g-3 justify-content-center align-items-start">
                        {{-- IMAGEN ACTUAL --}}
                        <div
                            class="col-12 col-lg-6 text-center mt-3 d-none"
                            id="platoImagenActualContainer">

                            <p class="small text-muted mb-2" id="platoImagenActualText">
                                Imagen actual
                            </p>

                            <img
                                id="platoImagenActual"
                                class="img-fluid border rounded h-max-250"
                                alt="">
                        </div>

                        {{-- PREVIEW --}}
                        <div
                            class="col-12 col-lg-6 text-center d-none"
                            id="platoPreviewContainer">

                            <p class="small text-muted mb-2" id="platoPreviewText">
                                Vista previa
                            </p>

                            <img
                                class="img-fluid border rounded h-max-250"
                                id="platoPreview"
                                alt="">
                        </div>

                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit" class="btn btn-primary"
                        id="btnGuardarPlato" disabled>
                        Guardar Plato
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
