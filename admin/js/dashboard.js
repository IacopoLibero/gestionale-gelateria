document.addEventListener('DOMContentLoaded', function() {
  const sidebar = document.getElementById('sidebar');
  const toggleBtn = document.getElementById('toggle-btn');
  const body = document.body;
  
  // Crea il pulsante per il toggle su mobile
  const mobileToggle = document.createElement('button');
  mobileToggle.className = 'mobile-toggle';
  mobileToggle.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" fill="currentColor" viewBox="0 0 24 24"><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"></path></svg>';
  document.body.appendChild(mobileToggle);
  
  // Funzione per il toggle della sidebar
  window.toggleSidebar = function() {
    if (window.innerWidth <= 768) {
      // Comportamento mobile
      sidebar.classList.toggle('mobile-visible');
      body.classList.toggle('sidebar-mobile-active');
    } else {
      // Comportamento desktop
      sidebar.classList.toggle('collapsed');
      body.classList.toggle('sidebar-collapsed');
    }
  };
  
  // Evento click per il toggle mobile
  mobileToggle.addEventListener('click', toggleSidebar);
  
  // Funzione per il toggle dei sottomenu
  window.toggleSubMenu = function(button) {
    // Prima chiudi tutti gli altri sottomenu
    const submenus = document.querySelectorAll('.sub-menu.show');
    const activeButtons = document.querySelectorAll('.dropdown-btn.active');
    
    // Verifica se questo sottomenu è già aperto
    const wasActive = button.nextElementSibling.classList.contains('show');
    
    // Chiudi tutti i sottomenu
    submenus.forEach(menu => {
      if (menu !== button.nextElementSibling) {
        menu.classList.remove('show');
      }
    });
    
    activeButtons.forEach(btn => {
      if (btn !== button) {
        btn.classList.remove('active');
      }
    });
    
    // Toggle per questo sottomenu
    if (!wasActive) {
      button.nextElementSibling.classList.add('show');
      button.classList.add('active');
    } else {
      button.nextElementSibling.classList.remove('show');
      button.classList.remove('active');
    }
    
    // Se la sidebar è collassata, espandila
    if (sidebar.classList.contains('collapsed') && window.innerWidth > 768) {
      sidebar.classList.remove('collapsed');
      body.classList.remove('sidebar-collapsed');
    }
  };
  
  // Chiudi il menu mobile quando si clicca fuori
  document.addEventListener('click', function(event) {
    if (window.innerWidth <= 768 && 
        sidebar.classList.contains('mobile-visible') && 
        !sidebar.contains(event.target) && 
        !mobileToggle.contains(event.target)) {
      toggleSidebar();
    }
  });
  
  // Gestisci il ridimensionamento della finestra
  window.addEventListener('resize', function() {
    if (window.innerWidth > 768) {
      sidebar.classList.remove('mobile-visible');
      body.classList.remove('sidebar-mobile-active');
    }
  });
});