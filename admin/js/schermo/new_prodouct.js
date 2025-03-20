// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Make closeNotification function available globally
    window.closeNotification = function() {
        const notification = document.getElementById('notification');
        if (notification) {
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.parentNode.removeChild(notification);
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

    // Add event listener to close button
    const closeButton = document.querySelector('.notification-close');
    if (closeButton) {
        closeButton.addEventListener('click', function(e) {
            e.preventDefault();
            closeNotification();
        });
    }
});
