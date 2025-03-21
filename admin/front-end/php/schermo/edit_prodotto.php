<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit;
}

// Include database connection
require_once '../../../../../connessione.php';

// Initialize variables
$error = '';
$success = '';
$nome = '';

// Check if product name was provided in URL
if (isset($_GET['nome']) && !empty($_GET['nome'])) {
    $nome = $_GET['nome'];
    
    // If form is submitted, process the update
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sanitize and validate input
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
                SET nome_inglese = ?, ingredienti = ?, tipo = ?, 
                    km0 = ?, vegano = ?, SlowFood = ?, bio = ?, 
                    innovativo = ?, ingredienti_visibili = ?, stato = ? 
                WHERE nome = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssiiiiiisi", $nome_inglese, $ingredienti, $tipo, 
                          $km0, $vegano, $slowFood, $bio, 
                          $innovativo, $ingredienti_visibili, $stato, $nome);
        
        if ($stmt->execute()) {
            $success = "Prodotto aggiornato con successo!";
        } else {
            $error = "Errore durante l'aggiornamento del prodotto: " . $conn->error;
        }
        
        $stmt->close();
    }
    
    // Fetch product data from database
    $sql = "SELECT * FROM prodotto WHERE nome = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nome);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $product = $result->fetch_assoc();
    } else {
        $error = "Prodotto non trovato!";
    }
    
    $stmt->close();
} else {
    $error = "Nome del prodotto non specificato!";
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
            <li><a href="./catalogo_prodotti.html">Catalogo Prodotti</a></li>
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
    <div class="container">
      <h2>Modifica Prodotto</h2>
      
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
      <?php endif; ?>
      
      <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
      <?php endif; ?>
      
      <?php if (empty($error) || !empty($success)): ?>
        <form class="edit-form" method="POST" action="edit_prodotto.php?nome=<?php echo urlencode($nome); ?>">
          <div class="form-row">
            <div class="form-group">
              <label for="nome">Nome (non modificabile)</label>
              <input type="text" id="nome" value="<?php echo htmlspecialchars($product['nome']); ?>" readonly>
            </div>
            
            <div class="form-group">
              <label for="nome_inglese">Nome Inglese</label>
              <input type="text" id="nome_inglese" name="nome_inglese" value="<?php echo htmlspecialchars($product['nome_inglese']); ?>" required>
            </div>
          </div>
          
          <div class="form-group">
            <label for="ingredienti">Ingredienti</label>
            <textarea id="ingredienti" name="ingredienti"><?php echo htmlspecialchars($product['ingredienti']); ?></textarea>
          </div>
          
          <div class="form-row">
            <div class="form-group">
              <label for="tipo">Tipo</label>
              <select id="tipo" name="tipo" required>
                <option value="gelato" <?php echo $product['tipo'] == 'gelato' ? 'selected' : ''; ?>>Gelato</option>
                <option value="granita" <?php echo $product['tipo'] == 'granita' ? 'selected' : ''; ?>>Granita</option>
                <option value="semifreddo" <?php echo $product['tipo'] == 'semifreddo' ? 'selected' : ''; ?>>Semifreddo</option>
              </select>
            </div>
          </div>
          
          <div class="form-group">
            <label>Caratteristiche:</label>
            <div class="checkbox-group">
              <label>
                <input type="checkbox" name="km0" <?php echo $product['km0'] ? 'checked' : ''; ?>>
                Km0
              </label>
              
              <label>
                <input type="checkbox" name="vegano" <?php echo $product['vegano'] ? 'checked' : ''; ?>>
                Vegano
              </label>
              
              <label>
                <input type="checkbox" name="SlowFood" <?php echo $product['SlowFood'] ? 'checked' : ''; ?>>
                Slow Food
              </label>
              
              <label>
                <input type="checkbox" name="bio" <?php echo $product['bio'] ? 'checked' : ''; ?>>
                Bio
              </label>
              
              <label>
                <input type="checkbox" name="innovativo" <?php echo $product['innovativo'] ? 'checked' : ''; ?>>
                Innovativo
              </label>
            </div>
          </div>
          
          <div class="form-group">
            <div class="checkbox-group">
              <label>
                <input type="checkbox" name="ingredienti_visibili" <?php echo $product['ingredienti_visibili'] ? 'checked' : ''; ?>>
                Mostra ingredienti
              </label>
              
              <label>
                <input type="checkbox" name="stato" <?php echo $product['stato'] ? 'checked' : ''; ?>>
                Prodotto attivo
              </label>
            </div>
          </div>
          
          <div class="form-actions">
            <a href="catalogo_prodotti.php" class="btn cancel-btn">Annulla</a>
            <button type="submit" class="save-btn">Salva Modifiche</button>
          </div>
        </form>
      <?php endif; ?>
    </div>
  </main>
  
  <script src="../../../js/dashboard.js"></script>
</body>
</html>