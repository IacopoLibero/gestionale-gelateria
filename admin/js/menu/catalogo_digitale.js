document.addEventListener('DOMContentLoaded', function() {
  // Get all product cards
  const productCards = document.querySelectorAll('.product-card');
  
  // Add click event to each card
  productCards.forEach(card => {
    card.addEventListener('click', function() {
      const productId = this.dataset.id;
      openEditForm(productId);
    });
  });
  
  // Handle form submission
  const editForm = document.getElementById('editForm');
  editForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(editForm);
    
    fetch('catalogo_digitale.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showNotification('success', data.message);
        setTimeout(() => {
          window.location.reload();
        }, 1500);
      } else {
        showNotification('error', data.message);
      }
    })
    .catch(error => {
      showNotification('error', 'Si è verificato un errore: ' + error);
    });
  });
});

// Function to open edit form and load product data
function openEditForm(productId) {
  // Fetch product data
  fetch(`../../../../admin/back-end/php/menu/get_product.php?id=${productId}`)
    .then(response => response.json())
    .then(product => {
      // Populate form with product data
      document.getElementById('productId').value = product.id;
      document.getElementById('nome').value = product.nome;
      document.getElementById('nome_inglese').value = product.nome_inglese;
      document.getElementById('prezzo').value = product.prezzo;
      document.getElementById('tipo').value = product.tipo;
      document.getElementById('ingredienti_it').value = product.ingredienti_it;
      document.getElementById('ingredienti_en').value = product.ingredienti_en;
      document.getElementById('extra').value = product.extra;
      document.getElementById('visibile').checked = product.visibile == 1;
      
      // Show overlay
      document.getElementById('editOverlay').classList.add('active');
    })
    .catch(error => {
      showNotification('error', 'Errore durante il caricamento dei dati: ' + error);
    });
}

// Function to close edit form
function closeEditForm() {
  document.getElementById('editOverlay').classList.remove('active');
  document.getElementById('editForm').reset();
}

// Function to show notification
function showNotification(type, message) {
  const notification = document.getElementById('notification');
  notification.innerHTML = `
    <div class="notification-content">
      <strong>${type === 'success' ? 'Successo!' : 'Errore!'}</strong>
      <p>${message}</p>
    </div>
    <button type="button" class="close" onclick="closeNotification()">&times;</button>
  `;
  notification.className = `notification ${type} show`;
  
  // Auto hide after 5 seconds
  setTimeout(() => {
    closeNotification();
  }, 5000);
}

// Function to close notification
function closeNotification() {
  const notification = document.getElementById('notification');
  notification.classList.remove('show');
}