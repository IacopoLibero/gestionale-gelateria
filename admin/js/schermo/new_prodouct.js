// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Function to close the notification
    window.closeNotification = function() {
        const notification = document.getElementById('notification');
        if (notification) {
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.style.display = 'none';
            }, 500);
        }
    }

    // Auto-close notification after 5 seconds if it exists
    const notification = document.getElementById('notification');
    if (notification) {
        setTimeout(() => {
            closeNotification();
        }, 5000);
    }
});
