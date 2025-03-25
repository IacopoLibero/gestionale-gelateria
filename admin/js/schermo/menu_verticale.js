document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh the page every 5 minutes to show updated product data
    setTimeout(function() {
        location.reload();
    }, 5 * 60 * 1000);
    
    // Function to ensure all content fits on one page
    function adjustContentForSinglePage() {
        const content = document.querySelector('.content');
        const productsSection = document.querySelector('.products-section');
        const windowHeight = window.innerHeight;
        
        // Adjust font size if content doesn't fit
        let baseFontSize = 16; // Default base font size
        let contentFits = false;
        
        while (!contentFits && baseFontSize > 8) {
            // Check if content fits in viewport
            if (content.offsetHeight <= windowHeight) {
                contentFits = true;
            } else {
                // Reduce font size and try again
                baseFontSize -= 0.5;
                document.documentElement.style.fontSize = baseFontSize + 'px';
            }
        }
        
        // If still doesn't fit, add slight scrolling
        if (!contentFits) {
            productsSection.style.overflowY = 'auto';
        }
    }
    
    // Run adjustment on load and resize
    adjustContentForSinglePage();
    window.addEventListener('resize', adjustContentForSinglePage);
    
    // Function to check if elements are visible in viewport
    function isElementInViewport(el) {
        const rect = el.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }
    
    // Add smooth scrolling if needed for navigation
    const scrollLinks = document.querySelectorAll('.scroll-link');
    if (scrollLinks.length > 0) {
        scrollLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    }
});
