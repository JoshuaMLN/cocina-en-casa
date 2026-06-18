@php
    $solicitudErrors = $errors->solicitudServicio;
    $hasFormErrors = $showErrors && $solicitudErrors->any();
    $fieldValue = fn (string $field) => $hasFormErrors
        ? old($field)
        : '';
@endphp

<input type="hidden" name="origen" value="{{ $source }}">

<div class="mb-3">
    <label for="{{ $prefix }}Nombre" class="form-label">
        Nombre completo <span class="text-danger">*</span>
    </label>
    <input type="text"
        class="form-control {{ $hasFormErrors && $solicitudErrors->has('nombre') ? 'is-invalid' : '' }}"
        id="{{ $prefix }}Nombre"
        name="nombre"
        maxlength="100"
        autocomplete="name"
        value="{{ $fieldValue('nombre') }}"
        required>
    @if($hasFormErrors && $solicitudErrors->has('nombre'))
        <div class="invalid-feedback">
            {{ $solicitudErrors->first('nombre') }}
        </div>
    @endif
</div>

<div class="row g-3">
    <div class="col-md-6">
        <label for="{{ $prefix }}Telefono" class="form-label">Teléfono</label>
        <input type="tel"
            class="form-control {{ $hasFormErrors && $solicitudErrors->has('telefono') ? 'is-invalid' : '' }}"
            id="{{ $prefix }}Telefono"
            name="telefono"
            maxlength="16"
            inputmode="tel"
            autocomplete="tel"
            placeholder="+51 999 999 999"
            value="{{ $fieldValue('telefono') }}"
            data-service-phone>
        @if($hasFormErrors && $solicitudErrors->has('telefono'))
            <div class="invalid-feedback">
                {{ $solicitudErrors->first('telefono') }}
            </div>
        @endif
    </div>

    <div class="col-md-6">
        <label for="{{ $prefix }}Email" class="form-label">
            Correo electrónico
        </label>
        <input type="email"
            class="form-control {{ $hasFormErrors && $solicitudErrors->has('email') ? 'is-invalid' : '' }}"
            id="{{ $prefix }}Email"
            name="email"
            maxlength="150"
            autocomplete="email"
            placeholder="nombre@correo.com"
            value="{{ $fieldValue('email') }}"
            data-service-email>
        @if($hasFormErrors && $solicitudErrors->has('email'))
            <div class="invalid-feedback">
                {{ $solicitudErrors->first('email') }}
            </div>
        @endif
    </div>
</div>

<div class="form-text mb-3">
    Ingresa al menos un medio de contacto: teléfono o correo.
</div>

<div class="mb-3">
    <label for="{{ $prefix }}Mensaje" class="form-label">
        Mensaje
        <span class="text-muted fw-normal">(opcional)</span>
    </label>
    <textarea
        class="form-control {{ $hasFormErrors && $solicitudErrors->has('mensaje') ? 'is-invalid' : '' }}"
        id="{{ $prefix }}Mensaje"
        name="mensaje"
        rows="4"
        maxlength="1000"
        placeholder="Cuéntanos brevemente qué servicio necesitas"
        data-service-message>{{ $fieldValue('mensaje') }}</textarea>
    <div class="d-flex justify-content-between gap-2">
        @if($hasFormErrors && $solicitudErrors->has('mensaje'))
            <div class="invalid-feedback d-block">
                {{ $solicitudErrors->first('mensaje') }}
            </div>
        @else
            <span class="form-text">Máximo 1000 caracteres.</span>
        @endif
        <span class="form-text text-nowrap">
            <span data-service-message-count>0</span>/1000
        </span>
    </div>
</div>

<div class="form-check">
    <input
        class="form-check-input {{ $hasFormErrors && $solicitudErrors->has('acepta_contacto') ? 'is-invalid' : '' }}"
        type="checkbox"
        value="1"
        id="{{ $prefix }}AceptaContacto"
        name="acepta_contacto"
        {{ $hasFormErrors && old('acepta_contacto') ? 'checked' : '' }}
        data-service-acceptance
        required>
    <label class="form-check-label" for="{{ $prefix }}AceptaContacto">
        Acepto que utilicen mis datos para responder esta solicitud.
    </label>
    @if($hasFormErrors && $solicitudErrors->has('acepta_contacto'))
        <div class="invalid-feedback">
            {{ $solicitudErrors->first('acepta_contacto') }}
        </div>
    @endif
</div>

<div class="honeypot-field" aria-hidden="true">
    <label for="{{ $prefix }}Website">Sitio web</label>
    <input type="text"
        id="{{ $prefix }}Website"
        name="website"
        tabindex="-1"
        autocomplete="off">
</div>
