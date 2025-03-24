document.addEventListener('DOMContentLoaded', function() {
    // Add animation to product cards
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
            this.style.boxShadow = '0 8px 20px rgba(0,0,0,0.15)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.1)';
        });
        
        // Add touch event handling for mobile to ensure proper z-index management
        card.addEventListener('touchstart', function() {
            // Reset z-index on all cards first
            productCards.forEach(c => {
                c.style.zIndex = '1';
            });
            // Set higher z-index only for the touched card
            this.style.zIndex = '2';
        });
    });
    
    // Ensure sidebar menu always has highest z-index
    document.addEventListener('click', function(e) {
        if (e.target.closest('.dropdown-btn') || e.target.closest('.sub-menu')) {
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                sidebar.style.zIndex = '1000';
            }
            const subMenus = document.querySelectorAll('.sub-menu');
            subMenus.forEach(menu => {
                menu.style.zIndex = '999';
            });
        }
    });
    
    // Fade out success messages after 3 seconds
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        setTimeout(function() {
            successAlert.style.transition = 'opacity 1s';
            successAlert.style.opacity = '0';
            setTimeout(function() {
                successAlert.style.display = 'none';
            }, 1000);
        }, 3000);
    }

    // Auto-close notification after 3 seconds if it exists
    const notification = document.getElementById('notification');
    if (notification) {
        setTimeout(() => {
            closeNotification();
        }, 3000);
    }
});

// Function to close notification
function closeNotification() {
    const notification = document.getElementById('notification');
    if (notification) {
        notification.style.opacity = '0';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 500);
    }
}

// Add slide out animation
document.head.insertAdjacentHTML('beforeend', `
    <style>
        @keyframes slideOut {
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
    </style>
`);
