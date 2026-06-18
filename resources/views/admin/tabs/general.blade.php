<div class="row g-3">
    {{-- LOGO --}}
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-4">1. Cambiar Logo del Navbar</h5>
                
                <form action="/admin/logo" method="POST" id="logoForm">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="logoInput" class="form-label">Sube una imagen</label>
                        <input class="form-control" type="file" id="logoInput" accept="image/*">
                        <div class="form-text">
                            Formatos permitidos: .jpg .jpeg .png .webp
                        </div>
                    </div>

                    <input type="hidden" name="cropped_logo" id="cropped_logo" required>

                    <div class="row g-3 mb-4 justify-content-center">
                        <div class="col-6 mt-1 text-center">
                            <p class="text-muted small mb-2">Logo actual</p>
                            <div class="container-cropped-preview mx-auto bg-light border">
                                @if($logo)
                                    <img
                                        src="{{ asset('storage/' . $logo) }}"
                                        alt="Logo actual"
                                        class="w-100 h-100 object-fit-contain">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                        <i class="fa-solid fa-utensils text-color fs-1"></i>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div id="previewContainer" class="col-6 mt-1 text-center d-none">
                            <p class="text-muted small mb-2">Nuevo logo</p>
                            <div class="container-cropped-preview mx-auto bg-light border">
                                <img
                                    id="croppedPreview"
                                    src=""
                                    alt="Vista previa del nuevo logo"
                                    class="w-100 h-100 object-fit-contain">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100" id="btnSubmit" disabled>
                        <i class="fa-solid fa-save me-2"></i> Actualizar Logo
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    {{-- WHATSAPP --}}
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex flex-column">
                <h5 class="card-title mb-4">2. Cambiar Información de WhatsApp</h5>
                
                <form action="/admin/whatsapp" method="POST" id="WspForm" class="d-flex flex-column flex-grow-1 justify-content-between">
                    @csrf

                    <div class="mb-3">
                        <label for="wspInput" class="form-label">Número de WhatsApp</label>

                        <div class="input-group">
                            <select id="countrySelect" name="codigo_pais">
                                <option value="1" data-icon="{{ asset('icons/estados-unidos-icon.png') }}" {{ $codigoPais == '1' ? 'selected' : '' }}>(+01)</option>
                                <option value="51" data-icon="{{ asset('icons/peru-icon.png') }}" {{ $codigoPais == '51' ? 'selected' : '' }}>(+51)</option>
                                <option value="52" data-icon="{{ asset('icons/mexico-icon.png') }}" {{ $codigoPais == '52' ? 'selected' : '' }}>(+52)</option>
                                <option value="54" data-icon="{{ asset('icons/argentina-icon.png') }}" {{ $codigoPais == '54' ? 'selected' : '' }}>(+54)</option>
                                <option value="56" data-icon="{{ asset('icons/chile-icon.png') }}" {{ $codigoPais == '56' ? 'selected' : '' }}>(+56)</option>
                                <option value="57" data-icon="{{ asset('icons/colombia-icon.png') }}" {{ $codigoPais == '57' ? 'selected' : '' }}>(+57)</option>
                            </select>

                            <input type="tel" class="form-control"
                                name="numero" id="wspInput"
                                placeholder="Ingrese el número"
                                minlength="6" maxlength="15"
                                pattern="[0-9]{6,15}"
                                oninput="this.value=this.value.replace(/\D/g,'')"
                                value="{{ $numero ?? '' }}"
                                required>
                        </div>

                        <div class="form-text d-flex justify-content-between gap-2">
                            <span>Ingrese entre 6 y 15 dígitos, sin espacios ni guiones.</span>
                            <span class="text-nowrap">
                                <span data-character-count="wspInput">0</span>/15
                            </span>
                        </div>

                        <label for="msgInput" class="form-label mt-2">Mensaje de WhatsApp</label>
                        <textarea
                            class="form-control"
                            name="mensaje"
                            id="msgInput"
                            rows="3"
                            maxlength="180"
                            placeholder="Mensaje opcional para WhatsApp">{{ $mensaje ?? '' }}</textarea>
                        <div class="form-text d-flex justify-content-between gap-2">
                            <span>Si se deja vacío, WhatsApp abrirá sin mensaje predefinido.</span>
                            <span class="text-nowrap">
                                <span data-character-count="msgInput">0</span>/180
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-save me-2"></i>
                        Actualizar WhatsApp
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- FOOTER --}}
<div class="card shadow-sm mt-3">
    <div class="card-body">
        <div class="mb-4">
            <h5 class="card-title mb-1">3. Personalizar Footer</h5>
            <small class="text-muted">
                El logo y WhatsApp se toman de la configuración general existente.
                Los campos vacíos no se mostrarán en la página.
            </small>
        </div>

        <form action="{{ route('admin.footer.update') }}"
            method="POST"
            id="footerForm">
            @csrf

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="footerDescription" class="form-label">
                            Descripción breve
                        </label>
                        <textarea
                            class="form-control @error('footer_description') is-invalid @enderror"
                            id="footerDescription"
                            name="footer_description"
                            rows="3"
                            maxlength="180"
                            placeholder="Describe brevemente tu servicio">{{ old('footer_description', $footer['description']) }}</textarea>
                        @error('footer_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text d-flex justify-content-between gap-2">
                            <span>Se mostrará junto al logo y nombre del negocio.</span>
                            <span class="text-nowrap">
                                <span data-character-count="footerDescription">0</span>/180
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="footerServiceArea" class="form-label">
                            Zona de atención
                        </label>
                        <input type="text"
                            class="form-control @error('footer_service_area') is-invalid @enderror"
                            id="footerServiceArea"
                            name="footer_service_area"
                            maxlength="120"
                            placeholder="Ej. Lima Metropolitana y zonas cercanas"
                            value="{{ old('footer_service_area', $footer['service_area']) }}">
                        @error('footer_service_area')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text d-flex justify-content-between gap-2">
                            <span>Indica dónde está disponible el servicio.</span>
                            <span class="text-nowrap">
                                <span data-character-count="footerServiceArea">0</span>/120
                            </span>
                        </div>
                    </div>

                    <div>
                        <label for="footerEmail" class="form-label">
                            Correo de contacto
                        </label>
                        <input type="email"
                            class="form-control @error('contact_email') is-invalid @enderror"
                            id="footerEmail"
                            name="contact_email"
                            maxlength="150"
                            autocomplete="email"
                            placeholder="contacto@ejemplo.com"
                            value="{{ old('contact_email', $footer['email']) }}">
                        @error('contact_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-6">
                    <p class="form-label mb-2">Redes sociales</p>
                    <p class="small text-muted">
                        Ingresa la URL completa del perfil. Por ejemplo:
                        https://instagram.com/tu_usuario
                    </p>

                    <div class="input-group mb-3">
                        <span class="input-group-text footer-social-prefix">
                            <i class="fa-brands fa-facebook-f"></i>
                        </span>
                        <input type="url"
                            class="form-control @error('social_facebook') is-invalid @enderror"
                            name="social_facebook"
                            maxlength="255"
                            placeholder="https://facebook.com/..."
                            aria-label="URL de Facebook"
                            value="{{ old('social_facebook', $footer['facebook']) }}">
                        @error('social_facebook')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text footer-social-prefix">
                            <i class="fa-brands fa-instagram"></i>
                        </span>
                        <input type="url"
                            class="form-control @error('social_instagram') is-invalid @enderror"
                            name="social_instagram"
                            maxlength="255"
                            placeholder="https://instagram.com/..."
                            aria-label="URL de Instagram"
                            value="{{ old('social_instagram', $footer['instagram']) }}">
                        @error('social_instagram')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <span class="input-group-text footer-social-prefix">
                            <i class="fa-brands fa-tiktok"></i>
                        </span>
                        <input type="url"
                            class="form-control @error('social_tiktok') is-invalid @enderror"
                            name="social_tiktok"
                            maxlength="255"
                            placeholder="https://tiktok.com/@..."
                            aria-label="URL de TikTok"
                            value="{{ old('social_tiktok', $footer['tiktok']) }}">
                        @error('social_tiktok')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fa-solid fa-save me-2"></i>
                    Actualizar Footer
                </button>
            </div>
        </form>
    </div>
</div>

{{-- NOSOTROS --}}
@php
    $palabrasClaveNosotros = old(
        'nosotros_palabras_clave',
        $nosotros['palabras_clave']
    );
@endphp

<div class="card shadow-sm mt-3">
    <div class="card-body">
        <div class="mb-4">
            <h5 class="card-title mb-1">4. Personalizar Sección Nosotros</h5>
            <small class="text-muted">
                Configura la imagen y el contenido que se muestran en la página principal.
            </small>
        </div>

        <form action="{{ route('admin.nosotros.update') }}"
            method="POST"
            id="nosotrosForm">
            @csrf

            <div class="row g-4">
                {{-- COLUMNA DE IMAGEN --}}
                <div class="col-lg-5 mt-3 d-flex flex-column justify-content-between">
                    <!-- Este div se mantendrá siempre pegado ARRIBA -->
                    <div>
                        {{-- LABEL --}}
                        <label for="nosotrosImageInput" class="form-label">
                            Imagen
                        </label>
                        {{-- INPUT FILE --}}
                        <input class="form-control"
                            type="file"
                            id="nosotrosImageInput"
                            accept="image/jpeg,image/png,image/webp">
                        <div class="form-text">
                            JPG, PNG o WEBP. La imagen se recortará en formato horizontal 4:3.
                        </div>
                        <input type="hidden"
                            name="cropped_nosotros_image"
                            id="cropped_nosotros_image">
                        {{-- MOSTRAR IMAGENES --}}
                        <div class="row g-3 mt-1 justify-content-center">
                            <div class="col-sm-6 mt-1 text-center">
                                <p class="small text-muted mb-2">Imagen actual</p>
                                <img
                                    src="{{ $nosotros['imagen']
                                        ? asset('storage/' . $nosotros['imagen'])
                                        : asset('images/fondo-container-main-zoom-no.png') }}"
                                    alt="Actual"
                                    class="nosotros-admin-preview border">
                            </div>

                            <div class="col-sm-6 mt-1 text-center d-none"
                                id="nosotrosPreviewContainer">
                                <p class="small text-muted mb-2">Nueva imagen</p>
                                <img
                                    src=""
                                    id="nosotrosPreview"
                                    alt="Nuevo"
                                    class="nosotros-admin-preview border">
                            </div>
                        </div>
                    </div>
                    <!-- Este div se mantendrá siempre pegado ABAJO -->
                    <div class="mt-2">
                        <label for="nosotrosMensaje" class="form-label">
                            Mensaje sobre la imagen
                        </label>
                        <input type="text"
                            class="form-control"
                            id="nosotrosMensaje"
                            name="nosotros_mensaje"
                            maxlength="45"
                            value="{{ old('nosotros_mensaje', $nosotros['mensaje']) }}"
                            required>
                        <div class="form-text text-end">
                            <span data-character-count="nosotrosMensaje">0</span>/45
                        </div>
                    </div>
                </div>

                {{-- COLUMNA DE TEXTOS --}}
                <div class="col-lg-7 mt-3">
                    {{-- SLOGAN --}}
                    <div class="mb-2">
                        <label for="nosotrosSlogan" class="form-label">
                            Slogan
                        </label>
                        <textarea class="form-control"
                            id="nosotrosSlogan"
                            name="nosotros_slogan"
                            rows="3"
                            maxlength="180"
                            required>{{ old('nosotros_slogan', $nosotros['slogan']) }}</textarea>
                        <div class="form-text text-end">
                            <span data-character-count="nosotrosSlogan">0</span>/180
                        </div>
                    </div>
                    {{-- DESCRIPCIÓN --}}
                    <div class="mb-2">
                        <label for="nosotrosDescripcion" class="form-label">
                            Descripción
                        </label>
                        <textarea class="form-control"
                            id="nosotrosDescripcion"
                            name="nosotros_descripcion"
                            rows="4"
                            maxlength="600"
                            required>{{ old('nosotros_descripcion', $nosotros['descripcion']) }}</textarea>
                        <div class="form-text text-end">
                            <span data-character-count="nosotrosDescripcion">0</span>/600
                        </div>
                    </div>
                    {{-- PALABRAS CALVE --}}
                    <div>
                        <label class="form-label">
                            Palabras clave
                        </label>
                        <div class="row g-2">
                            @for($i = 0; $i < 3; $i++)
                                <div class="col-md-4">
                                    <input type="text"
                                        class="form-control"
                                        name="nosotros_palabras_clave[]"
                                        maxlength="40"
                                        placeholder="Palabra clave {{ $i + 1 }}"
                                        value="{{ $palabrasClaveNosotros[$i] ?? '' }}"
                                        {{ $i === 0 ? 'required' : '' }}>
                                </div>
                            @endfor
                        </div>
                        <div class="form-text">
                            Puedes mostrar entre una y tres palabras o frases cortas.
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fa-solid fa-save me-2"></i>
                    Actualizar Nosotros
                </button>
            </div>
        </form>
    </div>
</div>
