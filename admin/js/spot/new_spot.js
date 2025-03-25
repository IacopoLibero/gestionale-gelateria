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
  function showNotification(message, type) {
    // Create notification container
    const notificationContainer = document.createElement('div');
    notificationContainer.className = 'notification-container';
    
    // Create notification
    const notification = document.createElement('div');
    
    // Set class based on type
    if (type === 'loading') {
      notification.className = 'notification loading-notification';
    } else if (type === 'success') {
      notification.className = 'notification success-notification';
    } else {
      notification.className = 'notification error-notification';
    }
    
    notification.id = 'notification';
    
    // Create notification content
    const notificationContent = document.createElement('div');
    notificationContent.className = 'notification-content';
    
    // Create icon
    const icon = document.createElement('span');
    icon.className = 'notification-icon';
    
    // Set icon based on type
    if (type === 'loading') {
      icon.textContent = '⏳';
    } else if (type === 'success') {
      icon.textContent = '✓';
    } else {
      icon.textContent = '⚠';
    }
    
    // Create message
    const messageSpan = document.createElement('span');
    messageSpan.textContent = message;
    
    // Create close button (only for success and error notifications)
    if (type !== 'loading') {
      const closeButton = document.createElement('button');
      closeButton.type = 'button';
      closeButton.className = 'notification-close';
      closeButton.onclick = closeNotification;
      closeButton.textContent = '×';
      notification.appendChild(closeButton);
    }
    
    // Assemble notification
    notificationContent.appendChild(icon);
    notificationContent.appendChild(messageSpan);
    notification.appendChild(notificationContent);
    notificationContainer.appendChild(notification);
    
    // Insert notification in page
    notificationArea.innerHTML = '';
    notificationArea.appendChild(notificationContainer);
    
    // Auto close after 3 seconds (only for success and error)
    if (type !== 'loading') {
      setTimeout(() => {
        if (document.getElementById('notification')) {
          // If success, redirect after closing
          if (type === 'success') {
            closeNotification();
          } else {
            // Just close error messages
            const notification = document.getElementById('notification');
            if (notification) {
              notification.style.opacity = '0';
              setTimeout(() => {
                notification.parentNode.removeChild(notification);
              }, 500);
            }
          }
        }
      }, 3000);
    }
  }

  spotForm.addEventListener("submit", function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Show loading message in notification area
    showNotification("Caricamento in corso...", "loading");
    
    fetch("../../../back-end/php/spot/new_spot.php", {
      method: "POST",
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Show success notification
        showNotification("Spot aggiunto con successo!", "success");
        
        // Reset form
        spotForm.reset();
        fileSelected.textContent = "Nessun file selezionato";
        fileSelected.style.color = "#e8eaed";
      } else {
        // Show error notification
        showNotification("Errore: " + data.message, "error");
      }
    })
    .catch(error => {
      console.error("Error:", error);
      showNotification("Si è verificato un errore durante il caricamento.", "error");
    });
  });
});
