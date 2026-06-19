@php
    $emailErrors = $errors->adminEmail;
    $passwordErrors = $errors->adminPassword;
@endphp

@if($errors->adminSettings->any())
    <div class="alert alert-warning d-flex align-items-center gap-2">
        <i class="fa-solid fa-lock" aria-hidden="true"></i>
        <span>{{ $errors->adminSettings->first() }}</span>
    </div>
@endif

<div class="admin-settings-header mb-4">
    <div>
        <span class="admin-settings-kicker">Cuenta del administrador</span>
        <h2 class="h4 fw-bold mb-2">Configuración de acceso</h2>
        <p class="text-muted mb-0">
            Actualiza las credenciales utilizadas para ingresar al panel.
        </p>
    </div>
    <span class="admin-settings-verified">
        <i class="fa-solid fa-shield-halved"></i>
        Acceso verificado
    </span>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card shadow-sm h-100 settings-card">
            <div class="card-body">
                <div class="settings-card-icon">
                    <i class="fa-regular fa-envelope"></i>
                </div>
                <h3 class="h5 fw-bold">Cambiar correo</h3>
                <p class="small text-muted">
                    Este correo será el que utilices en tu próximo inicio de sesión.
                </p>

                <div class="settings-current-value mb-4">
                    <span>Correo actual</span>
                    <strong>{{ auth('admin')->user()->email }}</strong>
                </div>

                <form action="{{ route('admin.settings.email') }}"
                    method="POST"
                    data-admin-email-form>
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label for="adminEmail" class="form-label">
                            Nuevo correo
                        </label>
                        <input type="email"
                            class="form-control {{ $emailErrors->has('email') ? 'is-invalid' : '' }}"
                            id="adminEmail"
                            name="email"
                            maxlength="150"
                            autocomplete="email"
                            value="{{ old('email', auth('admin')->user()->email) }}"
                            required>
                        @if($emailErrors->has('email'))
                            <div class="invalid-feedback">
                                {{ $emailErrors->first('email') }}
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="adminEmailConfirmation" class="form-label">
                            Repetir nuevo correo
                        </label>
                        <input type="email"
                            class="form-control"
                            id="adminEmailConfirmation"
                            name="email_confirmation"
                            maxlength="150"
                            autocomplete="email"
                            required>
                    </div>

                    <div class="mb-4">
                        <label for="adminEmailCurrentPassword" class="form-label">
                            Contraseña actual
                        </label>
                        <div class="input-group">
                            <input type="password"
                                class="form-control {{ $emailErrors->has('current_password') ? 'is-invalid' : '' }}"
                                id="adminEmailCurrentPassword"
                                name="current_password"
                                autocomplete="current-password"
                                required>
                            <button type="button"
                                class="btn btn-outline-secondary"
                                data-password-toggle
                                data-password-target="adminEmailCurrentPassword"
                                aria-label="Mostrar contraseña">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                            @if($emailErrors->has('current_password'))
                                <div class="invalid-feedback">
                                    {{ $emailErrors->first('current_password') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-at me-2"></i>
                        Actualizar correo
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm h-100 settings-card">
            <div class="card-body">
                <div class="settings-card-icon">
                    <i class="fa-solid fa-key"></i>
                </div>
                <h3 class="h5 fw-bold">Cambiar contraseña</h3>
                <p class="small text-muted">
                    Al guardar la nueva contraseña se cerrará tu sesión.
                </p>

                <form action="{{ route('admin.settings.password') }}"
                    method="POST"
                    data-admin-password-form>
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label for="adminPasswordCurrent" class="form-label">
                            Contraseña actual
                        </label>
                        <div class="input-group">
                            <input type="password"
                                class="form-control {{ $passwordErrors->has('current_password') ? 'is-invalid' : '' }}"
                                id="adminPasswordCurrent"
                                name="current_password"
                                autocomplete="current-password"
                                required>
                            <button type="button"
                                class="btn btn-outline-secondary"
                                data-password-toggle
                                data-password-target="adminPasswordCurrent"
                                aria-label="Mostrar contraseña">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                            @if($passwordErrors->has('current_password'))
                                <div class="invalid-feedback">
                                    {{ $passwordErrors->first('current_password') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-2">
                        <label for="adminNewPassword" class="form-label">
                            Nueva contraseña
                        </label>
                        <div class="input-group">
                            <input type="password"
                                class="form-control {{ $passwordErrors->has('password') ? 'is-invalid' : '' }}"
                                id="adminNewPassword"
                                name="password"
                                minlength="8"
                                autocomplete="new-password"
                                data-new-password
                                required>
                            <button type="button"
                                class="btn btn-outline-secondary"
                                data-password-toggle
                                data-password-target="adminNewPassword"
                                aria-label="Mostrar contraseña">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                            @if($passwordErrors->has('password'))
                                <div class="invalid-feedback">
                                    {{ $passwordErrors->first('password') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="password-strength mb-3"
                        data-password-strength>
                        <div class="password-strength-bar">
                            <span></span>
                        </div>
                        <small data-password-strength-label>
                            Usa al menos 8 caracteres, letras y números.
                        </small>
                    </div>

                    <div class="mb-4">
                        <label for="adminNewPasswordConfirmation"
                            class="form-label">
                            Repetir nueva contraseña
                        </label>
                        <div class="input-group">
                            <input type="password"
                                class="form-control"
                                id="adminNewPasswordConfirmation"
                                name="password_confirmation"
                                minlength="8"
                                autocomplete="new-password"
                                required>
                            <button type="button"
                                class="btn btn-outline-secondary"
                                data-password-toggle
                                data-password-target="adminNewPasswordConfirmation"
                                aria-label="Mostrar contraseña">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fa-solid fa-lock me-2"></i>
                        Cambiar contraseña
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
