<div class="modal fade"
    id="adminSettingsUnlockModal"
    tabindex="-1"
    aria-labelledby="adminSettingsUnlockModalLabel"
    aria-hidden="true"
    data-unlock-url="{{ route('admin.settings.unlock') }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content settings-unlock-modal">
            <form id="adminSettingsUnlockForm" novalidate>
                <div class="modal-header border-0 pb-0">
                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>

                <div class="modal-body text-center px-4 px-sm-5 pb-4">
                    <span class="settings-unlock-icon">
                        <i class="fa-solid fa-shield-halved"></i>
                    </span>
                    <h2 class="modal-title fs-4 fw-bold mt-3"
                        id="adminSettingsUnlockModalLabel">
                        Confirma que eres tú
                    </h2>
                    <p class="text-muted small mb-4">
                        Ingresa tu contraseña actual para acceder a la
                        configuración de la cuenta.
                    </p>

                    <div class="text-start">
                        <label for="adminSettingsCurrentPassword"
                            class="form-label">
                            Contraseña actual
                        </label>
                        <div class="input-group">
                            <input type="password"
                                class="form-control"
                                id="adminSettingsCurrentPassword"
                                name="current_password"
                                autocomplete="current-password"
                                required
                                autofocus>
                            <button type="button"
                                class="btn btn-outline-secondary"
                                data-password-toggle
                                data-password-target="adminSettingsCurrentPassword"
                                aria-label="Mostrar contraseña">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                            <div class="invalid-feedback"
                                data-unlock-error></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0 px-4 px-sm-5 pb-4">
                    <button type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="btn btn-primary"
                        data-unlock-submit>
                        <i class="fa-solid fa-unlock-keyhole me-2"></i>
                        Desbloquear
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
