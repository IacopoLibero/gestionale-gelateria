<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit;
}

// Include database connection
require_once '../../../../connessione.php';

// Fetch all spots from database
$sql = "SELECT * FROM spot ORDER BY data_creazione DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestionale Gelateria - Catalogo spot</title>
  <link rel="stylesheet" href="../../../front-end/css/dashboard.css">
  <link rel="stylesheet" href="../../../front-end/css/spot/catalogo_spot.css">
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
            <li><a href="../schermo/catalogo_prodotti.php">Catalogo Gelati</a></li>
            <li><a href="../schermo/new_prodouct.php">Nuovo Gelato</a></li>
            <li><a href="../schermo/menu_verticale.php">Menu Verticale</a></li>
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
            <li><a href="./catalogo_spot.php">Catalogo Spot</a></li>
            <li><a href="./new_spot.php">Nuovo Spot</a></li>
          </div>
        </ul>
      </li>
      <li>
        <button onclick="toggleSubMenu(this)" class="dropdown-btn">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h207q16 0 30.5 6t25.5 17l57 57h320q33 0 56.5 23.5T880-640v400q0 33-23.5 56.5T800-160H160Zm0-80h640v-400H447l-80-80H160v480Zm0 0v-480 480Z"/></svg>
          <span>Menu Digitale<span>
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
    <div class="container">
      <h2>Catalogo Spot</h2>
      <p>Gestisci i video spot per il monitor</p>
    </div>
    
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

    <div class="container">
      <div class="spot-grid">
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id = $row['id'];
                $nome = htmlspecialchars($row['nome']);
                $percorso_video = $row['percorso_video'];
                $data_creazione = date('d/m/Y H:i', strtotime($row['data_creazione']));
                
                echo "<div class='spot-card'>";
                echo "<h3>$nome</h3>";
                echo "<p>Data creazione: $data_creazione</p>";
                echo "<div class='video-container' data-video-src='$percorso_video'>";
                echo "<video preload='metadata'><source src='$percorso_video' type='video/mp4'>Il tuo browser non supporta il tag video.</video>";
                echo "<div class='play-overlay'><span>▶</span></div>";
                echo "</div>";
                echo "<div class='card-actions'>";
                echo "<form method='POST' action='../../../back-end/php/spot/catalogo_spot.php' onsubmit='return confirm(\"Sei sicuro di voler eliminare questo spot? Questa azione non può essere annullata.\")'>";
                echo "<input type='hidden' name='id' value='$id'>";
                echo "<input type='hidden' name='delete_spot' value='1'>";
                echo "<button type='submit' class='delete-btn'>Elimina</button>";
                echo "</form>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p class='no-spots'>Nessuno spot trovato nel database.</p>";
        }
        ?>
      </div>
      
      <div class="actions">
        <a href="new_spot.php" class="submit-button">
          <span class="button_top">Aggiungi Nuovo Spot</span>
        </a>
      </div>
    </div>
  </main>
  
  <!-- Video Modal for fullscreen -->
  <div id="video-modal" class="video-modal">
    <div class="modal-content">
      <span class="close-modal">&times;</span>
      <video id="modal-video" controls></video>
    </div>
  </div>
  
  <script src="../../../js/dashboard.js"></script>
  <script src="../../../js/spot/catalogo_spot.js"></script>
</body>
</html>