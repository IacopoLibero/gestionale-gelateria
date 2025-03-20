document.addEventListener('DOMContentLoaded', function () {
    const toggleButton = document.getElementById('toggle-btn');
    const sidebar = document.getElementById('sidebar');

    // Esponiamo le funzioni globalmente per gli handler onclick
    window.toggleSidebar = function () {
        sidebar.classList.toggle('close');
        toggleButton.classList.toggle('rotate');
        closeAllSubMenus();
    }

    window.toggleSubMenu = function (button) {
        if (!button.nextElementSibling.classList.contains('show')) {
            closeAllSubMenus();
        }

        button.nextElementSibling.classList.toggle('show');
        button.classList.toggle('rotate');

        if (sidebar.classList.contains('close')) {
            sidebar.classList.toggle('close');
            toggleButton.classList.toggle('rotate');
        }
    }

    function closeAllSubMenus() {
        Array.from(sidebar.getElementsByClassName('show')).forEach(ul => {
            ul.classList.remove('show');
            ul.previousElementSibling.classList.remove('rotate');
        });
    }
});