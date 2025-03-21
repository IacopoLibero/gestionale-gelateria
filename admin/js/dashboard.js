document.addEventListener('DOMContentLoaded', function () {
    const toggleButton = document.getElementById('toggle-btn');
    const sidebar = document.getElementById('sidebar');

    // Funzione per rilevare se è un dispositivo mobile
    function isMobile() {
        return window.innerWidth <= 800;
    }

    // Esponiamo le funzioni globalmente per gli handler onclick
    window.toggleSidebar = function () {
        if (!isMobile()) {
            // Su desktop, comportamento normale
            sidebar.classList.toggle('close');
            toggleButton.classList.toggle('rotate');
        }
        closeAllSubMenus();
    }

    window.toggleSubMenu = function (button) {
        // Salva un riferimento al sottomenu
        const submenu = button.nextElementSibling;
        
        // Se questo sottomenu non è già aperto, chiudi tutti i sottomenu
        if (!submenu.classList.contains('show')) {
            closeAllSubMenus();
        } else {
            // Se il sottomenu è già aperto, lo chiudiamo e usciamo
            submenu.classList.remove('show');
            button.classList.remove('rotate');
            return;
        }

        // Mostra il sottomenu
        submenu.classList.add('show');
        button.classList.add('rotate');

        // Su mobile, posiziona correttamente il sottomenu
        if (isMobile()) {
            const buttonRect = button.getBoundingClientRect();
            
            // Posiziona il sottomenu accanto al pulsante che l'ha attivato
            submenu.style.top = buttonRect.top + 'px';
            
            // Aggiungi un event listener per chiudere il menu quando si clicca altrove
            setTimeout(() => {
                const closeMenu = function(e) {
                    if (!submenu.contains(e.target) && e.target !== button) {
                        submenu.classList.remove('show');
                        button.classList.remove('rotate');
                        document.removeEventListener('click', closeMenu);
                    }
                };
                document.addEventListener('click', closeMenu);
            }, 100);
        }

        // Su desktop, se la sidebar è chiusa, la apriamo quando si apre un menu
        if (!isMobile() && sidebar.classList.contains('close')) {
            sidebar.classList.remove('close');
            toggleButton.classList.remove('rotate');
        }
    }

    function closeAllSubMenus() {
        Array.from(sidebar.getElementsByClassName('show')).forEach(ul => {
            ul.classList.remove('show');
            ul.previousElementSibling.classList.remove('rotate');
        });
    }
    
    // Chiudi i sottomenu quando si ridimensiona la finestra
    window.addEventListener('resize', closeAllSubMenus);
});