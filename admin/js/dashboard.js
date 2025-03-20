// Function to toggle sidebar collapse state
function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  const body = document.body;
  
  if (sidebar && body) {
    sidebar.classList.toggle('collapsed');
    body.classList.toggle('sidebar-collapsed');
    
    // For mobile view
    if (window.innerWidth <= 768) {
      sidebar.classList.toggle('mobile-visible');
      body.classList.toggle('sidebar-mobile-active');
    }
  }
}

// Function to toggle submenu
function toggleSubMenu(element) {
  if (!element) return;
  
  // Toggle active class on the dropdown button
  element.classList.toggle('active');
  
  // Find the next sibling which is the submenu
  const subMenu = element.nextElementSibling;
  if (subMenu) {
    subMenu.classList.toggle('open');
  }
}

// Check screen size on load and resize
function checkScreenSize() {
  const sidebar = document.getElementById('sidebar');
  if (!sidebar) return;
  
  if (window.innerWidth <= 768) {
    sidebar.classList.add('collapsed');
    sidebar.classList.remove('mobile-visible');
    document.body.classList.add('sidebar-collapsed');
    document.body.classList.remove('sidebar-mobile-active');
  }
}

// Initialize on document ready
document.addEventListener('DOMContentLoaded', function() {
  // Check if sidebar exists
  const sidebar = document.getElementById('sidebar');
  if (!sidebar) return;
  
  checkScreenSize();
  
  // Add resize event listener
  window.addEventListener('resize', checkScreenSize);
  
  // Close sidebar when clicking outside on mobile
  document.addEventListener('click', function(event) {
    if (!sidebar) return;
    
    if (window.innerWidth <= 768 && 
        sidebar.classList.contains('mobile-visible') && 
        !sidebar.contains(event.target) && 
        event.target.id !== 'toggle-btn') {
      toggleSidebar();
    }
  });
});
