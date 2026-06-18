document.addEventListener('DOMContentLoaded', function () {

/*---------------------------------------------------*/
/* SWIPER (CARRUSEL) */
/*---------------------------------------------------*/
    globalThis.mySwipers.platos = new Swiper('.swiper-platos', {
        loop: false,
        rewind: true,
        spaceBetween: 20,
        slidesPerGroup: 1,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
            dynamicBullets: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            0: {
                slidesPerView: 1,
            },
            576: {
                slidesPerView: 2,
            },
            768: {
                slidesPerView: 3,
            },
            992: {
                slidesPerView: 4,
            },
            1200: {
                slidesPerView: 5,
            },
            1400: {
                slidesPerView: 6,
            }
        }
    });

/*---------------------------------------------------*/
/* SOLICITUD DE SERVICIO */
/*---------------------------------------------------*/
    const solicitudModalElement = document.getElementById(
        'solicitudServicioModal'
    );
    const acceptanceTooltipText =
        'Marca la casilla de aceptación para enviar la solicitud';

    if (
        solicitudModalElement &&
        solicitudModalElement.dataset.openOnLoad === 'true'
    ) {
        bootstrap.Modal.getOrCreateInstance(
            solicitudModalElement
        ).show();
    }

    const contactSection = document.querySelector(
        '[data-contact-section]'
    );

    if (
        contactSection &&
        contactSection.dataset.scrollOnLoad === 'true'
    ) {
        window.requestAnimationFrame(() => {
            contactSection.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });
    }

    document.querySelectorAll(
        '[data-service-request-form]'
    ).forEach((solicitudForm) => {
        const telefonoInput = solicitudForm.querySelector(
            '[data-service-phone]'
        );
        const emailInput = solicitudForm.querySelector(
            '[data-service-email]'
        );
        const mensajeInput = solicitudForm.querySelector(
            '[data-service-message]'
        );
        const mensajeCount = solicitudForm.querySelector(
            '[data-service-message-count]'
        );
        const aceptaContactoInput = solicitudForm.querySelector(
            '[data-service-acceptance]'
        );
        const submitButton = solicitudForm.querySelector(
            '[data-service-submit]'
        );
        const submitButtonWrapper = solicitudForm.querySelector(
            '[data-service-submit-wrapper]'
        );
        let acceptanceTooltip = null;

        const syncContactRequirements = () => {
            const hasPhone = telefonoInput.value.trim() !== '';
            const hasEmail = emailInput.value.trim() !== '';

            telefonoInput.required = !hasEmail;
            emailInput.required = !hasPhone;
        };

        const updateMessageCount = () => {
            mensajeCount.textContent = mensajeInput.value.length;
        };

        const updateSubmitState = () => {
            const requiresAcceptance = !aceptaContactoInput.checked;

            submitButton.disabled = requiresAcceptance;

            if (requiresAcceptance) {
                submitButtonWrapper.setAttribute(
                    'title',
                    acceptanceTooltipText
                );
                submitButtonWrapper.tabIndex = 0;

                if (!acceptanceTooltip) {
                    acceptanceTooltip = new bootstrap.Tooltip(
                        submitButtonWrapper,
                        {
                            title: acceptanceTooltipText,
                            trigger: 'hover focus click',
                            placement: 'top',
                            customClass: 'service-request-tooltip'
                        }
                    );
                }
            } else {
                if (acceptanceTooltip) {
                    acceptanceTooltip.dispose();
                    acceptanceTooltip = null;
                }

                submitButtonWrapper.removeAttribute('title');
                submitButtonWrapper.removeAttribute(
                    'data-bs-original-title'
                );
                submitButtonWrapper.removeAttribute('tabindex');
            }
        };

        telefonoInput.addEventListener(
            'input',
            syncContactRequirements
        );
        emailInput.addEventListener(
            'input',
            syncContactRequirements
        );
        mensajeInput.addEventListener(
            'input',
            updateMessageCount
        );
        aceptaContactoInput.addEventListener(
            'change',
            updateSubmitState
        );

        syncContactRequirements();
        updateMessageCount();
        updateSubmitState();

        solicitudForm.addEventListener('submit', function (event) {
            syncContactRequirements();
            updateSubmitState();

            if (submitButton.disabled) {
                event.preventDefault();
                event.stopPropagation();
                return;
            }

            if (!solicitudForm.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                solicitudForm.classList.add('was-validated');
                return;
            }

            submitButton.disabled = true;
            submitButton.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2"
                    aria-hidden="true"></span>
                Enviando...
            `;
        });
    });
});
