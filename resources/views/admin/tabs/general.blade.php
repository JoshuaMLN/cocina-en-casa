<div class="row g-3">
    {{-- LOGO --}}
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-4">Cambiar Logo del Navbar</h5>
                
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

                    <div id="previewContainer" class="mb-4 text-center d-none">
                        <p class="text-muted small mb-2">Vista previa del logo recortado:</p>
                        <div class="container-cropped-preview mx-auto bg-light border">
                            <img id="croppedPreview" src="" alt="Vista Previa" class="w-100 h-100 object-fit-contain">
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
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-4">Cambiar Información de WhatsApp</h5>
                
                <form action="/admin/whatsapp" method="POST" id="WspForm">
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
                                value={{ $numero ?? '' }}
                                required>
                        </div>

                        <div class="form-text">
                            Ingrese los dígitos del celular, sin espacios ni guiones.
                        </div>

                        <label for="msgInput" class="form-label mt-2">Mensaje de WhatsApp</label>
                        <textarea
                            class="form-control"
                            name="mensaje"
                            id="msgInput"
                            rows="2"
                            placeholder="Mensaje opcional para WhatsApp">{{ $mensaje ?? '' }}</textarea>
                        <div class="form-text">
                            Si se deja vacío, el botón de WhatsApp abrirá el chat sin mensaje predefinido.
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
