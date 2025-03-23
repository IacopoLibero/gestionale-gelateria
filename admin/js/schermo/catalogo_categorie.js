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

    // Error message for category deletion
    window.showCategoryDeleteError = function() {
        // Create notification if it doesn't exist
        if(!document.getElementById('notification')) {
            const notificationContainer = document.createElement('div');
            notificationContainer.className = 'notification-container';
            
            const notification = document.createElement('div');
            notification.className = 'notification error-notification';
            notification.id = 'notification';
            
            const notificationContent = document.createElement('div');
            notificationContent.className = 'notification-content';
            
            const icon = document.createElement('span');
            icon.className = 'notification-icon';
            icon.textContent = '⚠';
            
            const message = document.createElement('span');
            message.textContent = 'Non puoi eliminare questa categoria perché ha prodotti associati.';
            
            const closeButton = document.createElement('button');
            closeButton.type = 'button';
            closeButton.className = 'notification-close';
            closeButton.onclick = closeNotification;
            closeButton.textContent = '×';
            
            notificationContent.appendChild(icon);
            notificationContent.appendChild(message);
            notification.appendChild(notificationContent);
            notification.appendChild(closeButton);
            notificationContainer.appendChild(notification);
            
            // Add to DOM at the beginning of main content
            const container = document.querySelector('.container');
            container.insertBefore(notificationContainer, container.firstChild.nextSibling);
            
            // Auto-close after 5 seconds
            setTimeout(closeNotification, 5000);
        }
    }
});
