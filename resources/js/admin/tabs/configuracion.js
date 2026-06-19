document.addEventListener('DOMContentLoaded', function() {
    const settingsTab = document.querySelector(
        '[data-admin-settings-tab]'
    );
    const unlockModalElement = document.getElementById(
        'adminSettingsUnlockModal'
    );

    if (!settingsTab || !unlockModalElement) return;

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute('content');
    const unlockModal = bootstrap.Modal.getOrCreateInstance(
        unlockModalElement
    );
    const unlockForm = document.getElementById(
        'adminSettingsUnlockForm'
    );
    const passwordInput = document.getElementById(
        'adminSettingsCurrentPassword'
    );
    const unlockError = unlockModalElement.querySelector(
        '[data-unlock-error]'
    );
    const unlockSubmit = unlockModalElement.querySelector(
        '[data-unlock-submit]'
    );

    settingsTab.addEventListener('show.bs.tab', function(event) {
        if (settingsTab.dataset.settingsUnlocked === 'true') {
            return;
        }

        event.preventDefault();
        unlockModal.show();
    });

    unlockModalElement.addEventListener('shown.bs.modal', function() {
        passwordInput.focus();
    });

    unlockModalElement.addEventListener('hidden.bs.modal', function() {
        unlockForm.reset();
        passwordInput.classList.remove('is-invalid');
        unlockError.textContent = '';
    });

    unlockForm.addEventListener('submit', async function(event) {
        event.preventDefault();

        passwordInput.classList.remove('is-invalid');
        unlockError.textContent = '';
        unlockSubmit.disabled = true;
        unlockSubmit.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2"
                aria-hidden="true"></span>
            Verificando...
        `;

        try {
            const response = await fetch(
                unlockModalElement.dataset.unlockUrl,
                {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        current_password: passwordInput.value
                    })
                }
            );
            const data = await response.json();

            if (!response.ok) {
                const message = response.status === 429
                    ? 'Demasiados intentos. Espera unos minutos.'
                    : data.errors?.current_password?.[0] ??
                        'No se pudo verificar la contraseña.';

                passwordInput.classList.add('is-invalid');
                unlockError.textContent = message;
                return;
            }

            settingsTab.dataset.settingsUnlocked = 'true';
            settingsTab.querySelector('[data-settings-tab-lock]')
                ?.classList.add('d-none');
            unlockModal.hide();
            bootstrap.Tab.getOrCreateInstance(settingsTab).show();
        } catch {
            passwordInput.classList.add('is-invalid');
            unlockError.textContent =
                'No se pudo conectar. Inténtalo nuevamente.';
        } finally {
            unlockSubmit.disabled = false;
            unlockSubmit.innerHTML = `
                <i class="fa-solid fa-unlock-keyhole me-2"></i>
                Desbloquear
            `;
        }
    });

    document.querySelectorAll('[data-password-toggle]').forEach(button => {
        button.addEventListener('click', function() {
            const input = document.getElementById(
                button.dataset.passwordTarget
            );
            const showPassword = input.type === 'password';

            input.type = showPassword ? 'text' : 'password';
            button.setAttribute(
                'aria-label',
                showPassword ? 'Ocultar contraseña' : 'Mostrar contraseña'
            );
            button.querySelector('i').className = showPassword
                ? 'fa-regular fa-eye-slash'
                : 'fa-regular fa-eye';
        });
    });

    const newPasswordInput = document.querySelector(
        '[data-new-password]'
    );
    const strength = document.querySelector(
        '[data-password-strength]'
    );
    const strengthBar = strength?.querySelector(
        '.password-strength-bar span'
    );
    const strengthLabel = strength?.querySelector(
        '[data-password-strength-label]'
    );

    const updatePasswordStrength = () => {
        const password = newPasswordInput.value;
        let score = 0;

        if (password.length >= 8) score++;
        if (/[a-záéíóúñ]/i.test(password)) score++;
        if (/\d/.test(password)) score++;
        if (/[^a-záéíóúñ0-9]/i.test(password)) score++;

        const levels = [
            ['0%', 'Usa al menos 8 caracteres, letras y números.'],
            ['25%', 'Contraseña débil'],
            ['50%', 'Contraseña aceptable'],
            ['75%', 'Contraseña buena'],
            ['100%', 'Contraseña fuerte']
        ];

        strength.dataset.level = String(score);
        strengthBar.style.width = levels[score][0];
        strengthLabel.textContent = levels[score][1];
    };

    newPasswordInput?.addEventListener(
        'input',
        updatePasswordStrength
    );
    updatePasswordStrength();

    const emailForm = document.querySelector(
        '[data-admin-email-form]'
    );
    const passwordForm = document.querySelector(
        '[data-admin-password-form]'
    );

    emailForm?.addEventListener('submit', function(event) {
        event.preventDefault();

        Swal.fire({
            title: '¿Actualizar el correo?',
            text: 'Usarás el nuevo correo en tu próximo inicio de sesión.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, actualizar',
            cancelButtonText: 'Cancelar'
        }).then(result => {
            if (!result.isConfirmed) return;

            showLoadingModal(
                'Actualizando correo',
                'Guardando las nuevas credenciales...'
            );
            emailForm.submit();
        });
    });

    passwordForm?.addEventListener('submit', function(event) {
        event.preventDefault();

        Swal.fire({
            title: '¿Cambiar la contraseña?',
            text: 'Tu sesión se cerrará y deberás ingresar nuevamente.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, cambiar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545'
        }).then(result => {
            if (!result.isConfirmed) return;

            showLoadingModal(
                'Actualizando contraseña',
                'Protegiendo la nueva credencial...'
            );
            passwordForm.submit();
        });
    });
});
