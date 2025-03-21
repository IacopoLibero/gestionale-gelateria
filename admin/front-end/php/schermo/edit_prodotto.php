<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit;
}

// Include database connection
require_once '../../../../connessione.php';

// Initialize variables
$error = '';
$success = '';
$id = '';

// Check if product ID was provided in URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // If form is submitted, process the update
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sanitize and validate input
        $nome = htmlspecialchars($_POST['nome']);
        $nome_inglese = htmlspecialchars($_POST['nome_inglese']);
        $ingredienti = htmlspecialchars($_POST['ingredienti']);
        $tipo = $_POST['tipo'];
        
        // Boolean values
        $km0 = isset($_POST['km0']) ? 1 : 0;
        $vegano = isset($_POST['vegano']) ? 1 : 0;
        $slowFood = isset($_POST['SlowFood']) ? 1 : 0;
        $bio = isset($_POST['bio']) ? 1 : 0;
        $innovativo = isset($_POST['innovativo']) ? 1 : 0;
        $ingredienti_visibili = isset($_POST['ingredienti_visibili']) ? 1 : 0;
        $stato = isset($_POST['stato']) ? 1 : 0;
        
        // Update the product in the database
        $sql = "UPDATE prodotto 
                SET nome = ?, nome_inglese = ?, ingredienti = ?, tipo = ?, 
                    km0 = ?, vegano = ?, SlowFood = ?, bio = ?, 
                    innovativo = ?, ingredienti_visibili = ?, stato = ? 
                WHERE id = ?";
        
        $stmt = $conn->prepare($sql);
        // Fixed type string: "s" for strings, "i" for integers
        $stmt->bind_param("ssssiiiiiiii", $nome, $nome_inglese, $ingredienti, $tipo, 
                         $km0, $vegano, $slowFood, $bio, 
                         $innovativo, $ingredienti_visibili, $stato, $id);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Prodotto aggiornato con successo!";
            header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $id);
            exit;
        } else {
            $_SESSION['error_message'] = "Errore durante l'aggiornamento del prodotto: " . $conn->error;
        }
        
        $stmt->close();
    }
    
    // Fetch product data from database
    $sql = "SELECT * FROM prodotto WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $product = $result->fetch_assoc();
    } else {
        $error = "Prodotto non trovato!";
    }
    
    $stmt->close();
} else {
    $error = "ID del prodotto non specificato!";
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modifica Prodotto - Gestionale Gelateria</title>
  <link rel="stylesheet" href="../../../front-end/css/dashboard.css">
  <link rel="stylesheet" href="../../../front-end/css/schermo/catalogo_prodotti.css">
  <link rel="stylesheet" href="../../../front-end/css/schermo/new_prodouct.css">
</head>
<body>
  <nav id="sidebar">
    <ul>
      <li>
        <span class="logo">David la Gelateria</span>
        <button onclick="toggleSidebar()" id="toggle-btn">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="m313-480 155 156q11 11 11.5 27.5T468-268q-11 11-28 11t-28-11L228-452q-6-6-8.5-13t-2.5-15q0-8 2.5-15t8.5-13l184-184q11-11 27.5-11.5T468-692q11 11 11 28t-11 28L313-480Zm264 0 155 156q11 11 11.5 27.5T732-268q-11 11-28 11t-28-11L492-452q-6-6-8.5-13t-2.5-15q0-8 2.5-15t8.5-13l184-184q11-11 27.5-11.5T732-692q11 11 11 28t-11 28L577-480Z"/></svg>
        </button>
      </li>
      <li class="active">
        <a href="../../../dashboard.php">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M520-640v-160q0-17 11.5-28.5T560-840h240q17 0 28.5 11.5T840-800v160q0 17-11.5 28.5T800-600H560q-17 0-28.5-11.5T520-640ZM120-480v-320q0-17 11.5-28.5T160-840h240q17 0 28.5 11.5T440-800v320q0 17-11.5 28.5T400-440H160q-17 0-28.5-11.5T120-480Zm400 320v-320q0-17 11.5-28.5T560-520h240q17 0 28.5 11.5T840-480v320q0 17-11.5 28.5T800-120H560q-17 0-28.5-11.5T520-160Zm-400 0v-160q0-17 11.5-28.5T160-360h240q17 0 28.5 11.5T440-320v160q0 17-11.5 28.5T400-120H160q-17 0-28.5-11.5T120-160Zm80-360h160v-240H200v240Zm400 320h160v-240H600v240Zm0-480h160v-80H600v80ZM200-200h160v-80H200v80Zm160-320Zm240-160Zm0 240ZM360-280Z"/></svg>
          <span>Dashboard</span>
        </a>
      </li>
      <li>
        <button onclick="toggleSubMenu(this)" class="dropdown-btn">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h207q16 0 30.5 6t25.5 17l57 57h320q33 0 56.5 23.5T880-640v400q0 33-23.5 56.5T800-160H160Zm0-80h640v-400H447l-80-80H160v480Zm0 0v-480 480Z"/></svg>
          <span>Menu Monitor</span>
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z"/></svg>
        </button>
        <ul class="sub-menu">
          <div>
            <li><a href="./catalogo_prodotti.php">Catalogo Prodotti</a></li>
            <li><a href="./new_prodouct.php">Nuovo Prodotto</a></li>
            <li><a href="./menu_verticale.php">Menu Verticale</a></li>
          </div>
        </ul>
      </li>
      <li>
        <button onclick="toggleSubMenu(this)" class="dropdown-btn">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h207q16 0 30.5 6t25.5 17l57 57h320q33 0 56.5 23.5T880-640v400q0 33-23.5 56.5T800-160H160Zm0-80h640v-400H447l-80-80H160v480Zm0 0v-480 480Z"/></svg>
          <span>Spot Monitor</span>
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z"/></svg>
        </button>
        <ul class="sub-menu">
          <div>
            <li><a href="../spot/catalogo_spot.php">Catalogo Spot</a></li>
            <li><a href="../spot/new_spot.php">Nuovo Spot</a></li>
          </div>
        </ul>
      </li>
      <li>
        <button onclick="toggleSubMenu(this)" class="dropdown-btn">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h207q16 0 30.5 6t25.5 17l57 57h320q33 0 56.5 23.5T880-640v400q0 33-23.5 56.5T800-160H160Zm0-80h640v-400H447l-80-80H160v480Zm0 0v-480 480Z"/></svg>
          <span>Menu Digitale</span>
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z"/></svg>
        </button>
        <ul class="sub-menu">
          <div>
            <li><a href="../menu_digitale/catalogo_digitale.php">Catalogo Digitale</a></li>
            <li><a href="../menu_digitale/new_prodouct.php">Nuovo Prodotto</a></li>
            <li><a href="../menu_digitale/menu_digitale.php">Menu Digitale</a></li>
          </div>
        </ul>
      </li>
      <li>
        <a href="../../../../login/logout.php">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>
          <span>Logout</span>
        </a>
      </li>
    </ul>
  </nav>
  <main>
    <h2 id="titolo" class="container">Modifica Prodotto</h2>
    
    <!-- Notification system -->
    <?php if(isset($_SESSION['success_message'])): ?>
      <div class="notification-container">
        <div class="notification success-notification" id="notification">
          <div class="notification-content">
            <span class="notification-icon">✓</span>
            <span><?php echo $_SESSION['success_message']; ?></span>
          </div>
          <button type="button" class="notification-close" onclick="closeNotification()">×</button>
        </div>
      </div>
      <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error_message'])): ?>
      <div class="notification-container">
        <div class="notification error-notification" id="notification">
          <div class="notification-content">
            <span class="notification-icon">⚠</span>
            <span><?php echo $_SESSION['error_message']; ?></span>
          </div>
          <button type="button" class="notification-close" onclick="closeNotification()">×</button>
        </div>
      </div>
      <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
    
    <?php if (!empty($error)): ?>
      <div class="notification-container">
        <div class="notification error-notification" id="notification">
          <div class="notification-content">
            <span class="notification-icon">⚠</span>
            <span><?php echo $error; ?></span>
          </div>
          <button type="button" class="notification-close" onclick="closeNotification()">×</button>
        </div>
      </div>
    <?php endif; ?>
    
    <?php if (empty($error) || !empty($success)): ?>
      <div class="container">
        <h2 class="h2class">Nome e ingredienti</h2>
        <form method="POST" action="edit_prodotto.php?id=<?php echo $id; ?>">
          <div class="name-columns">
            <div class="column">
              <label for="nome">NOME DEL PRODOTTO</label>
              <textarea name="nome" id="nome" class="auto-resize" required><?php echo htmlspecialchars($product['nome']); ?></textarea>
            </div>
            <div class="column">
              <label for="nome_inglese">NOME DEL PRODOTTO (INGLESE)</label>
              <textarea name="nome_inglese" id="nome_inglese" class="auto-resize" required><?php echo htmlspecialchars($product['nome_inglese']); ?></textarea>
            </div>
          </div>

          <div>
            <label for="ingredienti">INGREDIENTI PRINCIPALI</label>
            <textarea name="ingredienti" id="ingredienti" class="auto-resize"><?php echo htmlspecialchars($product['ingredienti']); ?></textarea>
          </div>
        </div>
      
        <div class="container">
          <h2 class="h2class">Opzioni</h2>
          <div class="checkbox-columns">
            <div class="column">
              <div class="checkbox-wrapper">
                <input type="checkbox" class="check" id="ingredienti_visibili" name="ingredienti_visibili" <?php echo $product['ingredienti_visibili'] ? 'checked' : ''; ?>>
                <label for="ingredienti_visibili" class="label">
                  <svg width="25" height="25" viewBox="0 0 95 95">
                    <rect x="30" y="20" width="50" height="50" stroke="#e8eaed" fill="none"></rect>
                    <g transform="translate(0,-952.36222)">
                      <path d="m 56,963 c -102,122 6,9 7,9 17,-5 -66,69 -38,52 122,-77 -7,14 18,4 29,-11 45,-43 23,-4" stroke="#e8eaed" stroke-width="3" fill="none" class="path1"></path>
                    </g>
                  </svg>
                  <span>Ingredienti visibili nel monitor</span>
                </label>
              </div>
              <div class="checkbox-wrapper">
                <input type="checkbox" class="check" id="km0" name="km0" <?php echo $product['km0'] ? 'checked' : ''; ?>>
                <label for="km0" class="label">
                  <svg width="25" height="25" viewBox="0 0 95 95">
                    <rect x="30" y="20" width="50" height="50" stroke="#e8eaed" fill="none"></rect>
                    <g transform="translate(0,-952.36222)">
                      <path d="m 56,963 c -102,122 6,9 7,9 17,-5 -66,69 -38,52 122,-77 -7,14 18,4 29,-11 45,-43 23,-4" stroke="#e8eaed" stroke-width="3" fill="none" class="path1"></path>
                    </g>
                  </svg>
                  <span>Km 0</span>
                </label>
              </div>
              <div class="checkbox-wrapper">
                <input type="checkbox" class="check" id="vegano" name="vegano" <?php echo $product['vegano'] ? 'checked' : ''; ?>>
                <label for="vegano" class="label">
                  <svg width="25" height="25" viewBox="0 0 95 95">
                    <rect x="30" y="20" width="50" height="50" stroke="#e8eaed" fill="none"></rect>
                    <g transform="translate(0,-952.36222)">
                      <path d="m 56,963 c -102,122 6,9 7,9 17,-5 -66,69 -38,52 122,-77 -7,14 18,4 29,-11 45,-43 23,-4" stroke="#e8eaed" stroke-width="3" fill="none" class="path1"></path>
                    </g>
                  </svg>
                  <span>Vegano</span>
                </label>
              </div>
              <div class="checkbox-wrapper">
                <input type="checkbox" class="check" id="SlowFood" name="SlowFood" <?php echo $product['SlowFood'] ? 'checked' : ''; ?>>
                <label for="SlowFood" class="label">
                  <svg width="25" height="25" viewBox="0 0 95 95">
                    <rect x="30" y="20" width="50" height="50" stroke="#e8eaed" fill="none"></rect>
                    <g transform="translate(0,-952.36222)">
                      <path d="m 56,963 c -102,122 6,9 7,9 17,-5 -66,69 -38,52 122,-77 -7,14 18,4 29,-11 45,-43 23,-4" stroke="#e8eaed" stroke-width="3" fill="none" class="path1"></path>
                    </g>
                  </svg>
                  <span>Slow Food</span>
                </label>
              </div>
            </div>
            <div class="column">
              <div class="checkbox-wrapper">
                <input type="checkbox" class="check" id="innovativo" name="innovativo" <?php echo $product['innovativo'] ? 'checked' : ''; ?>>
                <label for="innovativo" class="label">
                  <svg width="25" height="25" viewBox="0 0 95 95">
                    <rect x="30" y="20" width="50" height="50" stroke="#e8eaed" fill="none"></rect>
                    <g transform="translate(0,-952.36222)">
                      <path d="m 56,963 c -102,122 6,9 7,9 17,-5 -66,69 -38,52 122,-77 -7,14 18,4 29,-11 45,-43 23,-4" stroke="#e8eaed" stroke-width="3" fill="none" class="path1"></path>
                    </g>
                  </svg>
                  <span>Innovativo</span>
                </label>
              </div>
              <div class="checkbox-wrapper">
                <input type="checkbox" class="check" id="bio" name="bio" <?php echo $product['bio'] ? 'checked' : ''; ?>>
                <label for="bio" class="label">
                  <svg width="25" height="25" viewBox="0 0 95 95">
                    <rect x="30" y="20" width="50" height="50" stroke="#e8eaed" fill="none"></rect>
                    <g transform="translate(0,-952.36222)">
                      <path d="m 56,963 c -102,122 6,9 7,9 17,-5 -66,69 -38,52 122,-77 -7,14 18,4 29,-11 45,-43 23,-4" stroke="#e8eaed" stroke-width="3" fill="none" class="path1"></path>
                    </g>
                  </svg>
                  <span>Bio</span>
                </label>
              </div>
              <div class="checkbox-wrapper">
                <input type="checkbox" class="check" id="stato" name="stato" <?php echo $product['stato'] ? 'checked' : ''; ?>>
                <label for="stato" class="label">
                  <svg width="25" height="25" viewBox="0 0 95 95">
                    <rect x="30" y="20" width="50" height="50" stroke="#e8eaed" fill="none"></rect>
                    <g transform="translate(0,-952.36222)">
                      <path d="m 56,963 c -102,122 6,9 7,9 17,-5 -66,69 -38,52 122,-77 -7,14 18,4 29,-11 45,-43 23,-4" stroke="#e8eaed" stroke-width="3" fill="none" class="path1"></path>
                    </g>
                  </svg>
                  <span>Prodotto attivo</span>
                </label>
              </div>
              <div class="form-field">
                <select name="tipo" id="tipo" required>
                  <option value="" disabled>TIPO DI PRODOTTO</option>
                  <option value="gelato" <?php echo $product['tipo'] == 'gelato' ? 'selected' : ''; ?>>Gelato</option>
                  <option value="granita" <?php echo $product['tipo'] == 'granita' ? 'selected' : ''; ?>>Granita</option>
                  <option value="semifreddo" <?php echo $product['tipo'] == 'semifreddo' ? 'selected' : ''; ?>>Semifreddo</option>
                </select>
              </div>
            </div>
          </div>
          <div class="button-container">
            <button type="button" class="submit-button" onclick="window.location.href='catalogo_prodotti.php'">
              <span class="button_top">Annulla</span>
            </button>
            <button type="submit" class="submit-button">
              <span class="button_top">Salva Modifiche</span>
            </button>
          </div>
        </form>
      </div>
    <?php endif; ?>
  </main>
  
  <script src="../../../js/dashboard.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Auto-resize textareas
      const textareas = document.querySelectorAll('.auto-resize');
      textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
          this.style.height = 'auto';
          this.style.height = (this.scrollHeight) + 'px';
        });
        // Trigger on load
        textarea.dispatchEvent(new Event('input'));
      });
      
      // Close notification
      window.closeNotification = function() {
        const notification = document.getElementById('notification');
        if (notification) {
          notification.style.opacity = '0';
          setTimeout(() => {
            notification.parentElement.style.display = 'none';
          }, 300);
        }
      };
      
      // Auto-dismiss success notification after 5 seconds
      const successNotification = document.querySelector('.success-notification');
      if (successNotification) {
        setTimeout(() => {
          closeNotification();
        }, 5000);
      }
    });
  </script>
</body>
</html>