document.addEventListener('DOMContentLoaded', function() {
    // This function will run after the page loads
    
    // Auto-refresh the page every 5 minutes to show updated product data
    setTimeout(function() {
        location.reload();
    }, 5 * 60 * 1000);
    
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
