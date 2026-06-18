document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute('content');
    const unreadBadge = document.getElementById(
        'solicitudesUnreadBadge'
    );

    const decrementUnreadBadge = () => {
        if (!unreadBadge) return;

        const currentCount = Number(unreadBadge.dataset.count || 0);
        const nextCount = Math.max(currentCount - 1, 0);

        unreadBadge.dataset.count = String(nextCount);
        unreadBadge.textContent = String(nextCount);
        unreadBadge.classList.toggle('d-none', nextCount === 0);
    };

    document.querySelectorAll('[data-solicitud-modal]').forEach(modal => {
        modal.addEventListener('shown.bs.modal', function() {
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
                decrementUnreadBadge();
            })
            .catch(error => console.error(error));
        });
    });

    document.querySelectorAll('.form-status-solicitud').forEach(form => {
        form.addEventListener('submit', function() {
            showLoadingModal(
                'Actualizando solicitud',
                'Guardando el nuevo estado...'
            );
        });
    });

    document.querySelectorAll('.form-delete-solicitud').forEach(form => {
        form.addEventListener('submit', function(event) {
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
                form.submit();
            });
        });
    });
});
