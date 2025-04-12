document.addEventListener('DOMContentLoaded', function() {
  console.log("Catalogo digitale JS caricato");
  
  // Funzioni per la gestione della sidebar
  const toggleButton = document.getElementById('toggle-btn');
  const sidebar = document.getElementById('sidebar');

  // Funzione per rilevare se è un dispositivo mobile
  function isMobile() {
    return window.innerWidth <= 800;
  }

  // Esponemos le funzioni globalmente per gli handler onclick
  window.toggleSidebar = function () {
    if (!isMobile()) {
      // Su desktop, comportamento normale
      sidebar.classList.toggle('close');
      toggleButton.classList.toggle('rotate');
    }
    closeAllSubMenus();
  }

  window.toggleSubMenu = function (button) {
    // Salva un riferimento al sottomenu
    const submenu = button.nextElementSibling;
    
    // Se questo sottomenu non è già aperto, chiudi tutti i sottomenu
    if (!submenu.classList.contains('show')) {
      closeAllSubMenus();
    } else {
      // Se il sottomenu è già aperto, lo chiudiamo e usciamo
      submenu.classList.remove('show');
      button.classList.remove('rotate');
      return;
    }

    // Mostra il sottomenu
    submenu.classList.add('show');
    button.classList.add('rotate');

    // Su mobile, posiziona correttamente il sottomenu
    if (isMobile()) {
      const buttonRect = button.getBoundingClientRect();
      
      // Posiziona il sottomenu accanto al pulsante che l'ha attivato
      submenu.style.top = buttonRect.top + 'px';
      
      // Aggiungi un event listener per chiudere il menu quando si clicca altrove
      setTimeout(() => {
        const closeMenu = function(e) {
          if (!submenu.contains(e.target) && e.target !== button) {
            submenu.classList.remove('show');
            button.classList.remove('rotate');
            document.removeEventListener('click', closeMenu);
          }
        };
        document.addEventListener('click', closeMenu);
      }, 100);
    }

    // Su desktop, se la sidebar è chiusa, la apriamo quando si apre un menu
    if (!isMobile() && sidebar.classList.contains('close')) {
      sidebar.classList.remove('close');
      toggleButton.classList.remove('rotate');
    }
  }

  function closeAllSubMenus() {
    if (!sidebar) return;
    Array.from(sidebar.getElementsByClassName('show')).forEach(ul => {
      ul.classList.remove('show');
      if (ul.previousElementSibling) {
        ul.previousElementSibling.classList.remove('rotate');
      }
    });
  }
  
  // Chiudi i sottomenu quando si ridimensiona la finestra
  window.addEventListener('resize', closeAllSubMenus);
  
  // Previeni il comportamento predefinito sui form per evitare refresh non voluti
  document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      console.log("Submit form intercettato");
    });
  });
  
  // Per la nuova visualizzazione tabellare - Aggiungi event listener alle righe della tabella
  const productRows = document.querySelectorAll('.products-table tbody tr');
  console.log("Trovate " + productRows.length + " righe di prodotto");
  
  productRows.forEach(row => {
    row.addEventListener('click', function(e) {
      e.preventDefault(); // Previene comportamenti indesiderati
      const productId = this.dataset.id;
      if (productId) {
        console.log("Click sulla riga del prodotto ID: " + productId);
        openEditForm(productId);
      } else {
        console.error("Questa riga non ha un ID prodotto valido");
      }
    });
  });
  
  // Handle form submission
  const editForm = document.getElementById('editForm');
  if (editForm) {
    editForm.addEventListener('submit', function(e) {
      e.preventDefault();
      console.log("Form di modifica inviato");
      
      const formData = new FormData(editForm);
      
      fetch('catalogo_digitale.php', {
        method: 'POST',
        body: formData
      })
      .then(response => {
        if (!response.ok) {
          console.error("Errore nella risposta del server:", response.status);
          throw new Error('Risposta server non valida: ' + response.status);
        }
        return response.json();
      })
      .then(data => {
        console.log("Risposta ricevuta dal server:", data);
        if (data.success) {
          showNotification('success', data.message);
          setTimeout(() => {
            window.location.reload();
          }, 1500);
        } else {
          showNotification('error', data.message || 'Errore sconosciuto');
        }
      })
      .catch(error => {
        console.error('Errore:', error);
        showNotification('error', 'Si è verificato un errore: ' + error.message);
      });
    });
  } else {
    console.error('Form di modifica non trovato!');
  }
});

// Function to open edit form and load product data
function openEditForm(productId) {
  console.log("Apertura form di modifica per prodotto ID:", productId);
  
  // Mostra un indicatore di caricamento
  showNotification('info', 'Caricamento dati in corso...');
  
  // Definisci l'URL con percorso assoluto
  const url = '../../../../admin/back-end/php/menu/get_product.php?id=' + productId;
  console.log("URL chiamata fetch:", url);
  
  // Fetch product data
  fetch(url)
    .then(response => {
      console.log("Risposta ricevuta:", response.status);
      if (!response.ok) {
        throw new Error('Errore nel caricamento dei dati (Status: ' + response.status + ')');
      }
      return response.json();
    })
    .then(product => {
      console.log("Dati prodotto ricevuti:", product);
      
      // Nascondi notifica di caricamento
      closeNotification();
      
      // Populate form with product data
      document.getElementById('productId').value = product.id;
      document.getElementById('nome').value = product.nome;
      document.getElementById('nome_inglese').value = product.nome_inglese;
      document.getElementById('prezzo').value = product.prezzo;
      document.getElementById('tipo').value = product.tipo;
      document.getElementById('ingredienti_it').value = product.ingredienti_it || '';
      document.getElementById('ingredienti_en').value = product.ingredienti_en || '';
      document.getElementById('extra').value = product.extra || '';
      document.getElementById('visibile').checked = product.visibile == 1;
      
      // Show overlay
      const overlay = document.getElementById('editOverlay');
      if (overlay) {
        overlay.classList.add('active');
      } else {
        console.error('Overlay non trovato!');
        showNotification('error', 'Errore: elemento overlay non trovato');
      }
    })
    .catch(error => {
      console.error('Errore durante il caricamento:', error);
      showNotification('error', 'Errore durante il caricamento dei dati: ' + error.message);
    });
}

// Function to close edit form
function closeEditForm() {
  console.log("Chiusura form di modifica");
  const overlay = document.getElementById('editOverlay');
  if (overlay) {
    overlay.classList.remove('active');
  }
  
  const form = document.getElementById('editForm');
  if (form) {
    form.reset();
  }
}

// Function to show notification
function showNotification(type, message) {
  console.log("Mostra notifica:", type, message);
  const notification = document.getElementById('notification');
  if (!notification) {
    console.error('Elemento notifica non trovato!');
    alert(message); // Fallback a un alert base
    return;
  }
  
  // Icona in base al tipo
  let iconHTML = '';
  if (type === 'success') {
    iconHTML = '<div class="notification-icon success">✓</div>';
  } else if (type === 'error') {
    iconHTML = '<div class="notification-icon error">✗</div>';
  } else if (type === 'info') {
    iconHTML = '<div class="notification-icon info">ℹ</div>';
  }
  
  notification.innerHTML = `
    ${iconHTML}
    <div class="notification-content">
      <strong>${type === 'success' ? 'Successo!' : type === 'error' ? 'Errore!' : 'Info'}</strong>
      <p>${message}</p>
    </div>
    <button type="button" class="close" onclick="closeNotification()">&times;</button>
  `;
  notification.className = `notification ${type} show`;
  
  // Auto hide after 5 seconds for success and info messages
  if (type !== 'error') {
    setTimeout(() => {
      closeNotification();
    }, 5000);
  }
}

// Function to close notification
function closeNotification() {
  console.log("Chiusura notifica");
  const notification = document.getElementById('notification');
  if (notification) {
    notification.classList.remove('show');
  }
}