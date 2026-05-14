document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            const message = form.dataset.confirm || 'Confirmer cette action ?';

            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    });
});