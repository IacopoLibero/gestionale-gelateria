document.addEventListener('DOMContentLoaded', function() {
    console.log("Catalogo prodotti JS caricato");
    
    // Funzioni per la gestione della sidebar
    const toggleButton = document.getElementById('toggle-btn');
    const sidebar = document.getElementById('sidebar');

    // Gestiamo il click sulle righe della tabella per aprire il modal
    const productRows = document.querySelectorAll('.products-table tbody tr:not(.category-header)');
    const editOverlay = document.getElementById('editOverlay');
    
    console.log("Trovate " + productRows.length + " righe di prodotto");
    
    productRows.forEach(row => {
        row.addEventListener('click', function(e) {
            // Non apriamo il modal se si clicca sui pulsanti di azione
            if (e.target.closest('.edit-btn') || e.target.closest('.delete-btn') || e.target.closest('.delete-form')) {
                return;
            }
            
            const productId = this.dataset.id;
            const nome = this.dataset.nome;
            const nomeInglese = this.dataset.nomeInglese;
            const tipo = this.dataset.tipo;
            const ingredienti = this.dataset.ingredienti;
            const stato = this.dataset.stato === "1" ? "Attivo" : "Non attivo";
            const km0 = this.dataset.km0 === "1" ? "Sì" : "No";
            const vegano = this.dataset.vegano === "1" ? "Sì" : "No";
            const slowFood = this.dataset.slowfood === "1" ? "Sì" : "No";
            const bio = this.dataset.bio === "1" ? "Sì" : "No";
            const innovativo = this.dataset.innovativo === "1" ? "Sì" : "No";
            const ingredientiVisibili = this.dataset.ingredientiVisibili === "1" ? "Sì" : "No";
            
            // Aggiorniamo i campi del modal
            document.getElementById('productId').value = productId;
            document.getElementById('nome').value = nome;
            document.getElementById('nome_inglese').value = nomeInglese;
            document.getElementById('tipo').value = tipo;
            document.getElementById('stato').value = stato;
            document.getElementById('ingredienti').value = ingredienti;
            document.getElementById('km0').textContent = km0;
            document.getElementById('vegano').textContent = vegano;
            document.getElementById('slowfood').textContent = slowFood;
            document.getElementById('bio').textContent = bio;
            document.getElementById('innovativo').textContent = innovativo;
            document.getElementById('ingredienti_visibili').textContent = ingredientiVisibili;
            
            // Impostiamo il link al pulsante modifica
            document.getElementById('editButton').href = 'edit_prodotto.php?id=' + productId;
            
            // Mostriamo il modal
            showEditForm();
        });
    });
    
    // Conferma prima di eliminare
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('Sei sicuro di voler eliminare questo prodotto? Questa azione non può essere annullata.')) {
                this.submit();
            }
        });
    });
    
    // Funzione per mostrare il form di modifica
    window.showEditForm = function() {
        editOverlay.classList.add('active');
        document.body.style.overflow = 'hidden'; // Blocca lo scroll della pagina
    };
    
    // Funzione per chiudere il form di modifica
    window.closeEditForm = function() {
        editOverlay.classList.remove('active');
        document.body.style.overflow = ''; // Ripristina lo scroll della pagina
    };
    
    // Chiudi il modal cliccando fuori dal form
    editOverlay.addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditForm();
        }
    });
    
    // Funzione per chiudere la notifica
    window.closeNotification = function() {
        const notification = document.getElementById('notification');
        if (notification) {
            notification.classList.remove('show');
        }
    };
    
    // Auto-chiusura delle notifiche dopo 3 secondi
    const notification = document.getElementById('notification');
    if (notification && notification.textContent.trim() !== '') {
        notification.classList.add('show');
        setTimeout(() => {
            closeNotification();
        }, 3000);
    }

    // Add animation to product cards
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
            this.style.boxShadow = '0 8px 20px rgba(0,0,0,0.15)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.1)';
        });
        
        // Add touch event handling for mobile to ensure proper z-index management
        card.addEventListener('touchstart', function() {
            // Reset z-index on all cards first
            productCards.forEach(c => {
                c.style.zIndex = '1';
            });
            // Set higher z-index only for the touched card
            this.style.zIndex = '2';
        });
    });
    
    // Ensure sidebar menu always has highest z-index
    document.addEventListener('click', function(e) {
        if (e.target.closest('.dropdown-btn') || e.target.closest('.sub-menu')) {
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                sidebar.style.zIndex = '1000';
            }
            const subMenus = document.querySelectorAll('.sub-menu');
            subMenus.forEach(menu => {
                menu.style.zIndex = '999';
            });
        }
    });
    
    // Fade out success messages after 3 seconds
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        setTimeout(function() {
            successAlert.style.transition = 'opacity 1s';
            successAlert.style.opacity = '0';
            setTimeout(function() {
                successAlert.style.display = 'none';
            }, 1000);
        }, 3000);
    }
});

// Function to close notification
function closeNotification() {
    const notification = document.getElementById('notification');
    if (notification) {
        notification.style.opacity = '0';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 500);
    }
}

// Add slide out animation
document.head.insertAdjacentHTML('beforeend', `
    <style>
        @keyframes slideOut {
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
    </style>
`);
