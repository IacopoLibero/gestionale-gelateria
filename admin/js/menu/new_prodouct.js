document.addEventListener('DOMContentLoaded', function() {
    // Auto-resize text areas when content changes
    const textAreas = document.querySelectorAll('.auto-resize');
    textAreas.forEach(textArea => {
        textArea.addEventListener('input', autoResizeTextarea);
        // Initial call to set the right height
        autoResizeTextarea.call(textArea);
    });

    // Function to close notifications
    window.closeNotification = function() {
        const notification = document.getElementById('notification');
        if (notification) {
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.style.display = 'none';
            }, 500);
        }
    };

    // Auto-close notifications after 5 seconds
    const notification = document.getElementById('notification');
    if (notification) {
        setTimeout(closeNotification, 5000);
    }
});

// Function to resize textareas automatically based on content
function autoResizeTextarea() {
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
    
    // Add minimum height
    if (this.scrollHeight < 40) {
        this.style.height = '40px';
    }
}

// Handle sidebar toggle
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    if (sidebar) {
        sidebar.classList.toggle('close');
    }
}

// Handle submenu toggle
function toggleSubMenu(btn) {
    const subMenu = btn.nextElementSibling;
    const allSubMenus = document.querySelectorAll('.sub-menu');
    
    // Close other submenus
    allSubMenus.forEach(menu => {
        if (menu !== subMenu) {
            menu.classList.remove('show');
            if (menu.previousElementSibling) {
                menu.previousElementSibling.classList.remove('rotate');
            }
        }
    });
    
    // Toggle current submenu
    subMenu.classList.toggle('show');
    btn.classList.toggle('rotate');
}