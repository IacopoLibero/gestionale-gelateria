document.addEventListener("DOMContentLoaded", function() {
  const spotForm = document.getElementById("spotForm");
  const messageDiv = document.getElementById("message");
  const fileInput = document.getElementById("video");
  const fileSelected = document.getElementById("file-selected");
  
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

  spotForm.addEventListener("submit", function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Show loading message
    messageDiv.innerHTML = "Caricamento in corso...";
    messageDiv.className = "";
    
    fetch("../../../back-end/php/spot/new_spot.php", {
      method: "POST",
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        messageDiv.innerHTML = "Spot aggiunto con successo!";
        messageDiv.className = "success";
        spotForm.reset();
        fileSelected.textContent = "Nessun file selezionato";
        fileSelected.style.color = "#e8eaed";
      } else {
        messageDiv.innerHTML = "Errore: " + data.message;
        messageDiv.className = "error";
      }
    })
    .catch(error => {
      console.error("Error:", error);
      messageDiv.innerHTML = "Si è verificato un errore durante il caricamento.";
      messageDiv.className = "error";
    });
  });
});
