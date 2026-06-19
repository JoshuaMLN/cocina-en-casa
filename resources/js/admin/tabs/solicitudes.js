document.addEventListener('DOMContentLoaded', function() {
    const inbox = document.querySelector('[data-solicitudes-inbox]');

    if (!inbox) return;

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute('content');
    const unreadBadge = document.getElementById(
        'solicitudesUnreadBadge'
    );
    const pollInterval = Number(inbox.dataset.pollInterval || 30000);

    let requestInProgress = false;
    let refreshPending = false;

    const updateUnreadBadge = (count) => {
        if (!unreadBadge) return;

        unreadBadge.dataset.count = String(count);
        unreadBadge.textContent = String(count);
        unreadBadge.classList.toggle('d-none', count === 0);
        inbox.dataset.unread = String(count);
    };

    const setSyncStatus = (message, isError = false) => {
        const status = inbox.querySelector(
            '[data-solicitudes-sync-status]'
        );

        if (!status) return;

        status.textContent = message;
        status.classList.toggle('text-danger', isError);
        status.classList.toggle('text-muted', !isError);
    };

    const setRefreshLoading = (isLoading) => {
        const button = inbox.querySelector(
            '[data-solicitudes-refresh]'
        );

        if (!button) return;

        button.disabled = isLoading;
        button.querySelector('i')?.classList.toggle(
            'solicitudes-refresh-spin',
            isLoading
        );
    };

    const showNewRequestNotification = () => {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'info',
            title: 'Nueva solicitud recibida',
            text: 'El buzón se actualizó automáticamente.',
            showConfirmButton: false,
            timer: 4500,
            timerProgressBar: true
        });
    };

    const applySnapshot = (snapshot, notify = true) => {
        const previousLatestId = Number(
            inbox.dataset.latestId || 0
        );
        const hasNewRequest =
            Number(snapshot.latest_id) > previousLatestId;

        if (snapshot.html) {
            inbox.innerHTML = snapshot.html;
        }

        inbox.dataset.latestId = String(snapshot.latest_id);
        inbox.dataset.total = String(snapshot.total);
        updateUnreadBadge(Number(snapshot.unread));

        const updatedAt = new Intl.DateTimeFormat('es-PE', {
            hour: '2-digit',
            minute: '2-digit'
        }).format(new Date());

        setSyncStatus(`Actualizado a las ${updatedAt}`);

        if (notify && hasNewRequest) {
            showNewRequestNotification();
        }

        inbox.dispatchEvent(new CustomEvent(
            'solicitudes:snapshot-applied',
            { detail: snapshot }
        ));
    };

    const refresh = async ({ force = false, notify = true } = {}) => {
        if (requestInProgress || document.hidden) return;

        if (inbox.querySelector('.modal.show')) {
            refreshPending = true;
            return;
        }

        requestInProgress = true;
        setRefreshLoading(true);
        setSyncStatus('Buscando nuevas solicitudes...');

        const url = new URL(
            inbox.dataset.snapshotUrl,
            window.location.origin
        );
        const page = new URLSearchParams(
            window.location.search
        ).get('solicitudes_page') || '1';

        url.searchParams.set(
            'latest_id',
            inbox.dataset.latestId || '0'
        );
        url.searchParams.set('total', inbox.dataset.total || '0');
        url.searchParams.set('unread', inbox.dataset.unread || '0');
        url.searchParams.set('solicitudes_page', page);

        if (force) {
            url.searchParams.set('force', '1');
        }

        try {
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json'
                },
                cache: 'no-store'
            });

            if (!response.ok) {
                throw new Error('No se pudo actualizar el buzón.');
            }

            const snapshot = await response.json();

            if (snapshot.changed || snapshot.html) {
                applySnapshot(snapshot, notify);
            } else {
                const checkedAt = new Intl.DateTimeFormat('es-PE', {
                    hour: '2-digit',
                    minute: '2-digit'
                }).format(new Date());

                setSyncStatus(`Sin novedades · ${checkedAt}`);
                updateUnreadBadge(Number(snapshot.unread));
            }
        } catch (error) {
            console.error(error);
            setSyncStatus(
                'No se pudo actualizar. Intenta nuevamente.',
                true
            );
        } finally {
            requestInProgress = false;
            setRefreshLoading(false);
        }
    };

    globalThis.solicitudesInbox = {
        refresh,
        applySnapshot
    };

    inbox.addEventListener('click', function(event) {
        if (!event.target.closest('[data-solicitudes-refresh]')) {
            return;
        }

        refresh({ force: true });
    });

    inbox.addEventListener('shown.bs.modal', function(event) {
        const modal = event.target.closest('[data-solicitud-modal]');

        if (!modal) return;

        const row = document.getElementById(modal.dataset.rowId);

        if (
            modal.dataset.readProcessed === 'true' ||
            !row?.classList.contains('solicitud-unread-row')
        ) {
            return;
        }

        fetch(modal.dataset.readUrl, {
            method: 'PATCH',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('No se pudo marcar como leída.');
            }

            row.classList.remove('solicitud-unread-row');
            row.querySelector('.solicitud-unread-dot')
                ?.classList.add('d-none');
            modal.dataset.readProcessed = 'true';

            const currentUnread = Number(
                inbox.dataset.unread || 0
            );
            updateUnreadBadge(Math.max(currentUnread - 1, 0));
        })
        .catch(error => console.error(error));
    });

    inbox.addEventListener('hidden.bs.modal', function() {
        if (!refreshPending) return;

        refreshPending = false;
        refresh();
    });

    inbox.addEventListener('submit', function(event) {
        const statusForm = event.target.closest(
            '.form-status-solicitud'
        );
        const deleteForm = event.target.closest(
            '.form-delete-solicitud'
        );

        if (statusForm) {
            showLoadingModal(
                'Actualizando solicitud',
                'Guardando el nuevo estado...'
            );
            return;
        }

        if (!deleteForm) return;

        event.preventDefault();

        Swal.fire({
            title: '¿Eliminar solicitud?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then(result => {
            if (!result.isConfirmed) return;

            showLoadingModal(
                'Eliminando solicitud',
                'Por favor espere...'
            );
            deleteForm.submit();
        });
    });

    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            refresh();
        }
    });

    window.setInterval(function() {
        refresh();
    }, pollInterval);
});
