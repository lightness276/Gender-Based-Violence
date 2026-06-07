const menuButton = document.querySelector('[data-menu-toggle]');
const sidebar = document.querySelector('[data-sidebar]');

if (menuButton && sidebar) {
    menuButton.addEventListener('click', () => {
        sidebar.classList.toggle('open');
    });
}

document.querySelectorAll('[data-confirm]').forEach((button) => {
    button.addEventListener('click', (event) => {
        const message = button.getAttribute('data-confirm') || 'Are you sure?';
        if (!confirm(message)) {
            event.preventDefault();
        }
    });
});
