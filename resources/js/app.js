// resources/js/app.js

// Preparar el contenedor global para almacenar carruseles en cualquier página de la web
globalThis.mySwipers = {};
// Preparar el contenedor global para almacenar Tom Selects en cualquier página de la web
globalThis.myTomSelects = {};

// Funciones globales para el Modal de Carga con SweetAlert2
globalThis.showLoadingModal = function(title = 'Procesando', text = 'Por favor espere...') {
    if (Swal !== undefined) {
        Swal.fire({
            title: title,
            text: text,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }
};

globalThis.closeLoadingModal = function() {
    if (Swal !== undefined) {
        Swal.close();
    }
};

/*---------------------------------------------------*/
/* SMART NAVBAR */
/*---------------------------------------------------*/
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector('[data-smart-navbar]');

    if (!navbar) return;

    const root = document.documentElement;
    const collapseElement = navbar.querySelector('.navbar-collapse');
    const sectionLinks = Array.from(
        navbar.querySelectorAll('[data-section-link]')
    );
    const sections = sectionLinks
        .map(link => {
            const url = new URL(link.href, window.location.href);
            const section = document.querySelector(url.hash);

            return section
                ? { link, section }
                : null;
        })
        .filter(Boolean);
    const minScrollToHide = 20;
    const downThreshold = 6;
    const upThreshold = 2;

    let lastScrollY = Math.max(window.scrollY, 0);
    let ticking = false;
    let keyboardNavigation = false;

    const updateNavbarHeight = () => {
        root.style.setProperty(
            '--navbar-height',
            `${navbar.offsetHeight}px`
        );
    };

    const navbarIsInUse = () => {
        const openCollapse = navbar.querySelector('.navbar-collapse.show');

        const keyboardFocusInside =
            keyboardNavigation &&
            navbar.contains(document.activeElement);

        return Boolean(openCollapse) || keyboardFocusInside;
    };

    const showNavbar = () => {
        navbar.classList.remove('smart-navbar-hidden');
    };

    const setActiveSection = (activeLink) => {
        sectionLinks.forEach(link => {
            const isActive = link === activeLink;

            link.classList.toggle('active', isActive);

            if (isActive) {
                link.setAttribute('aria-current', 'location');
            } else {
                link.removeAttribute('aria-current');
            }
        });
    };

    const updateActiveSection = () => {
        if (sections.length === 0) return;

        const marker = window.scrollY + navbar.offsetHeight + 32;
        const atPageBottom =
            window.innerHeight + window.scrollY >=
            document.documentElement.scrollHeight - 2;
        let activeEntry = sections[0];

        if (atPageBottom) {
            activeEntry = sections[sections.length - 1];
        } else {
            sections.forEach(entry => {
                if (entry.section.offsetTop <= marker) {
                    activeEntry = entry;
                }
            });
        }

        setActiveSection(activeEntry.link);
    };

    const hideNavbar = () => {
        if (!navbarIsInUse()) {
            navbar.classList.add('smart-navbar-hidden');
        }
    };

    const handleScroll = () => {
        const currentScrollY = Math.max(window.scrollY, 0);
        const scrollDifference = currentScrollY - lastScrollY;

        if (currentScrollY <= minScrollToHide || navbarIsInUse()) {
            showNavbar();
        } else if (scrollDifference >= downThreshold) {
            hideNavbar();
        } else if (scrollDifference <= -upThreshold) {
            showNavbar();
        }

        updateActiveSection();
        lastScrollY = currentScrollY;
        ticking = false;
    };

    updateNavbarHeight();
    updateActiveSection();

    window.addEventListener('resize', updateNavbarHeight);

    window.addEventListener('keydown', function(event) {
        if (event.key === 'Tab') {
            keyboardNavigation = true;
        }
    });

    window.addEventListener('pointerdown', function() {
        keyboardNavigation = false;
    }, { passive: true });

    window.addEventListener('scroll', function() {
        if (ticking) return;

        ticking = true;
        window.requestAnimationFrame(handleScroll);
    }, { passive: true });

    navbar.addEventListener('focusin', showNavbar);

    navbar.addEventListener('shown.bs.collapse', showNavbar);

    sectionLinks.forEach(link => {
        link.addEventListener('click', function() {
            setActiveSection(link);

            if (
                collapseElement &&
                collapseElement.classList.contains('show')
            ) {
                bootstrap.Collapse.getOrCreateInstance(
                    collapseElement,
                    { toggle: false }
                ).hide();
            }
        });
    });
});

/*---------------------------------------------------*/
/* VOLVER AL INICIO */
/*---------------------------------------------------*/
document.addEventListener('DOMContentLoaded', function() {
    const backToTopButton = document.querySelector('[data-back-to-top]');

    if (!backToTopButton) return;

    const showAfter = 500;
    const reducedMotion = window.matchMedia(
        '(prefers-reduced-motion: reduce)'
    );

    const updateBackToTopButton = () => {
        const isVisible = window.scrollY >= showAfter;

        backToTopButton.classList.toggle('is-visible', isVisible);
        backToTopButton.setAttribute(
            'aria-hidden',
            isVisible ? 'false' : 'true'
        );
        backToTopButton.tabIndex = isVisible ? 0 : -1;
    };

    updateBackToTopButton();

    window.addEventListener('scroll', updateBackToTopButton, {
        passive: true
    });

    backToTopButton.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: reducedMotion.matches ? 'auto' : 'smooth'
        });
    });
});
