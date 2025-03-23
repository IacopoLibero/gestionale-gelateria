// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Make closeNotification function available globally
    window.closeNotification = function() {
        const notification = document.getElementById('notification');
        if (notification) {
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.parentNode.removeChild(notification);
                // Redirect to catalog page after notification is removed
                window.location.href = ".catalogo_prodotti.php";
            }, 500);
        }
    }

    // Auto-close notification after 3 seconds if it exists
    const notification = document.getElementById('notification');
    if (notification) {
        setTimeout(() => {
            closeNotification();
        }, 3000);
    }

    // Add event listener to close button
    const closeButton = document.querySelector('.notification-close');
    if (closeButton) {
        closeButton.addEventListener('click', function(e) {
            e.preventDefault();
            closeNotification();
        });
    }
    
    // Auto-resize textareas (now for all textareas with auto-resize class)
    const autoResizeTextareas = document.querySelectorAll('.auto-resize');
    if (autoResizeTextareas.length > 0) {
        // Function to adjust textarea height
        function adjustHeight(textarea) {
            // Reset height first
            textarea.style.height = 'auto';
            // Set new height based on scrollHeight
            textarea.style.height = textarea.scrollHeight + 'px';
        }
        
        // Apply to all auto-resize textareas
        autoResizeTextareas.forEach(textarea => {
            // Set initial height
            adjustHeight(textarea);
            
            // Add event listener for input changes
            textarea.addEventListener('input', function() {
                adjustHeight(this);
            });
        });
    }
});
