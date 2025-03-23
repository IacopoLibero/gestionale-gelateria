document.addEventListener('DOMContentLoaded', function () {
    // Auto-resize textareas
    const textareas = document.querySelectorAll('.auto-resize');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
        // Trigger on load
        textarea.dispatchEvent(new Event('input'));
    });

    // Close notification
    window.closeNotification = function () {
        const notification = document.getElementById('notification');
        if (notification) {
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.parentElement.style.display = 'none';
            }, 300);
        }
    };

    // Auto-dismiss success notification after 3 seconds (changed from 5)
    const successNotification = document.querySelector('.success-notification');
    if (successNotification) {
        setTimeout(() => {
            closeNotification();
            // Redirect to catalog page after notification is dismissed
            setTimeout(() => {
                window.location.href = 'catalogo_prodotti.php';
            }, 300);
        }, 3000); // Changed from 5000 to 3000 ms
    }
});
