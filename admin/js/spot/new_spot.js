document.addEventListener("DOMContentLoaded", function() {
  const spotForm = document.getElementById("spotForm");
  const messageDiv = document.getElementById("message");

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
