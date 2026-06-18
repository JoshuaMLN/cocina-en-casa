<div class="card-body">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h5 class="mb-1">Solicitudes de servicio</h5>
            <small class="text-muted">
                Revisa y gestiona los mensajes enviados desde la página principal.
            </small>
        </div>

        <span class="badge text-bg-light border fs-6">
            {{ $solicitudes->total() }}
            {{ $solicitudes->total() === 1 ? 'solicitud' : 'solicitudes' }}
        </span>
    </div>

    @if($solicitudes->isEmpty())
        <div class="alert alert-info mb-0">
            <i class="fa-solid fa-inbox me-2"></i>
            Aún no hay solicitudes de servicio.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th class="solicitud-unread-column"></th>
                        <th>Persona</th>
                        <th>Contacto</th>
                        <th>Mensaje</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($solicitudes as $solicitud)
                        <tr id="solicitud-row-{{ $solicitud->id }}"
                            class="{{ $solicitud->leido_at ? '' : 'solicitud-unread-row' }}">
                            <td class="text-center">
                                <span class="solicitud-unread-dot {{ $solicitud->leido_at ? 'd-none' : '' }}"
                                    aria-label="Solicitud no leída"></span>
                            </td>
                            <td>
                                <strong>{{ $solicitud->nombre }}</strong>
                            </td>
                            <td>
                                @if($solicitud->telefono)
                                    <a href="tel:{{ $solicitud->telefono }}"
                                        class="d-block text-decoration-none">
                                        <i class="fa-solid fa-phone me-1"></i>
                                        {{ $solicitud->telefono }}
                                    </a>
                                @endif
                                @if($solicitud->email)
                                    <a href="mailto:{{ $solicitud->email }}"
                                        class="d-block text-decoration-none text-truncate solicitud-email">
                                        <i class="fa-regular fa-envelope me-1"></i>
                                        {{ $solicitud->email }}
                                    </a>
                                @endif
                            </td>
                            <td class="solicitud-message-preview">
                                {{ $solicitud->mensaje
                                    ? \Illuminate\Support\Str::limit($solicitud->mensaje, 70)
                                    : 'Sin mensaje' }}
                            </td>
                            <td>
                                @switch($solicitud->estado)
                                    @case(\App\Models\SolicitudServicio::ESTADO_EN_PROCESO)
                                        <span class="badge text-bg-warning">En proceso</span>
                                        @break
                                    @case(\App\Models\SolicitudServicio::ESTADO_ATENDIDO)
                                        <span class="badge text-bg-success">Atendido</span>
                                        @break
                                    @default
                                        <span class="badge text-bg-primary">Nuevo</span>
                                @endswitch
                            </td>
                            <td class="text-nowrap">
                                {{ $solicitud->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="text-end">
                                <button type="button"
                                    class="btn btn-sm btn-outline-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#solicitudModal{{ $solicitud->id }}">
                                    <i class="fa-regular fa-eye me-1"></i>
                                    Ver
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $solicitudes->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

@foreach($solicitudes as $solicitud)
    <div class="modal fade"
        id="solicitudModal{{ $solicitud->id }}"
        tabindex="-1"
        aria-labelledby="solicitudModalLabel{{ $solicitud->id }}"
        aria-hidden="true"
        data-solicitud-modal
        data-row-id="solicitud-row-{{ $solicitud->id }}"
        data-read-url="{{ route('admin.solicitudes.leer', $solicitud) }}">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h2 class="modal-title fs-5"
                            id="solicitudModalLabel{{ $solicitud->id }}">
                            Solicitud de {{ $solicitud->nombre }}
                        </h2>
                        <small class="text-muted">
                            Recibida el {{ $solicitud->created_at->format('d/m/Y \a \l\a\s H:i') }}
                        </small>
                    </div>
                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <span class="d-block small text-muted">Teléfono</span>
                            @if($solicitud->telefono)
                                <a href="tel:{{ $solicitud->telefono }}"
                                    class="fw-semibold text-decoration-none">
                                    <i class="fa-solid fa-phone me-1"></i>
                                    {{ $solicitud->telefono }}
                                </a>
                            @else
                                <span class="text-muted">No proporcionado</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <span class="d-block small text-muted">Correo</span>
                            @if($solicitud->email)
                                <a href="mailto:{{ $solicitud->email }}"
                                    class="fw-semibold text-decoration-none text-break">
                                    <i class="fa-regular fa-envelope me-1"></i>
                                    {{ $solicitud->email }}
                                </a>
                            @else
                                <span class="text-muted">No proporcionado</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <span class="d-block small text-muted mb-1">Mensaje</span>
                        <div class="solicitud-full-message">
                            {{ $solicitud->mensaje ?: 'El usuario no agregó un mensaje.' }}
                        </div>
                    </div>

                    <form action="{{ route('admin.solicitudes.estado', $solicitud) }}"
                        method="POST"
                        class="row g-2 align-items-end form-status-solicitud">
                        @csrf
                        @method('PATCH')

                        <div class="col-sm">
                            <label for="solicitudEstado{{ $solicitud->id }}"
                                class="form-label">
                                Estado
                            </label>
                            <select class="form-select"
                                id="solicitudEstado{{ $solicitud->id }}"
                                name="estado">
                                <option value="nuevo"
                                    {{ $solicitud->estado === 'nuevo' ? 'selected' : '' }}>
                                    Nuevo
                                </option>
                                <option value="en_proceso"
                                    {{ $solicitud->estado === 'en_proceso' ? 'selected' : '' }}>
                                    En proceso
                                </option>
                                <option value="atendido"
                                    {{ $solicitud->estado === 'atendido' ? 'selected' : '' }}>
                                    Atendido
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-auto">
                            <button type="submit" class="btn btn-primary w-100">
                                Actualizar estado
                            </button>
                        </div>
                    </form>
                </div>

                <div class="modal-footer justify-content-between">
                    <form action="{{ route('admin.solicitudes.destroy', $solicitud) }}"
                        method="POST"
                        class="form-delete-solicitud">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fa-regular fa-trash-can me-1"></i>
                            Eliminar
                        </button>
                    </form>

                    <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach
