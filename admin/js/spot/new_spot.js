document.addEventListener("DOMContentLoaded", function() {
  const spotForm = document.getElementById("spotForm");
  const fileInput = document.getElementById("video");
  const fileSelected = document.getElementById("file-selected");
  const notificationArea = document.getElementById("notification-area");
  
  // Update the file selected text when a file is chosen
  fileInput.addEventListener("change", function() {
    if (this.files && this.files[0]) {
      fileSelected.textContent = this.files[0].name;
      fileSelected.style.color = "#4CAF50";
    } else {
      fileSelected.textContent = "Nessun file selezionato";
      fileSelected.style.color = "#e8eaed";
    }
  });

  // Make closeNotification function available globally
  window.closeNotification = function() {
    const notification = document.getElementById('notification');
    if (notification) {
      notification.style.opacity = '0';
      setTimeout(() => {
        notification.parentNode.removeChild(notification);
        // Redirect to catalog page after notification is removed
        window.location.href = "catalogo_spot.php";
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

  // Function to create and show a notification
  function showNotification(message, isSuccess) {
    // Create notification container
    const notificationContainer = document.createElement('div');
    notificationContainer.className = 'notification-container';
    
    // Create notification
    const notification = document.createElement('div');
    notification.className = isSuccess ? 'notification success-notification' : 'notification error-notification';
    notification.id = 'notification';
    
    // Create notification content
    const notificationContent = document.createElement('div');
    notificationContent.className = 'notification-content';
    
    // Create icon
    const icon = document.createElement('span');
    icon.className = 'notification-icon';
    icon.textContent = isSuccess ? '✓' : '⚠';
    
    // Create message
    const messageSpan = document.createElement('span');
    messageSpan.textContent = message;
    
    // Create close button
    const closeButton = document.createElement('button');
    closeButton.type = 'button';
    closeButton.className = 'notification-close';
    closeButton.onclick = closeNotification;
    closeButton.textContent = '×';
    
    // Assemble notification
    notificationContent.appendChild(icon);
    notificationContent.appendChild(messageSpan);
    notification.appendChild(closeButton);
    notificationContainer.appendChild(notification);
    
    // Insert notification in page
    notificationArea.innerHTML = '';
    notificationArea.appendChild(notificationContainer);
    
    // Auto close after 3 seconds
    setTimeout(closeNotification, 3000);
  }

  spotForm.addEventListener("submit", function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Show loading message
    showNotification("Caricamento in corso...", true);
    
    fetch("../../../back-end/php/spot/new_spot.php", {
      method: "POST",
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showNotification("Spot aggiunto con successo!", true);
        spotForm.reset();
        fileSelected.textContent = "Nessun file selezionato";
        fileSelected.style.color = "#e8eaed";
      } else {
        showNotification("Errore: " + data.message, false);
      }
    })
    .catch(error => {
      console.error("Error:", error);
      showNotification("Si è verificato un errore durante il caricamento.", false);
    });
  });
});
