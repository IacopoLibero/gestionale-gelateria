<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit;
}

// Include database connection
require_once '../../../../connessione.php';

// Process form submission if submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $response = ['success' => false, 'message' => ''];

    if ($_POST['action'] === 'update') {
        // Update existing product
        if (isset($_POST['id']) && is_numeric($_POST['id'])) {
            $id = $_POST['id'];
            $nome = $_POST['nome'];
            $nome_inglese = $_POST['nome_inglese'];
            $prezzo = $_POST['prezzo'];
            $tipo = $_POST['tipo'];
            $ingredienti_it = $_POST['ingredienti_it'];
            $ingredienti_en = $_POST['ingredienti_en'];
            $extra = $_POST['extra'];
            $visibile = isset($_POST['visibile']) ? 1 : 0;

            $sql = "UPDATE menu SET 
                    nome = ?, 
                    nome_inglese = ?, 
                    prezzo = ?, 
                    tipo = ?, 
                    ingredienti_it = ?, 
                    ingredienti_en = ?, 
                    extra = ?,
                    visibile = ? 
                    WHERE id = ?";
                    
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdssssii", $nome, $nome_inglese, $prezzo, $tipo, $ingredienti_it, $ingredienti_en, $extra, $visibile, $id);
            
            if ($stmt->execute()) {
                $response = ['success' => true, 'message' => 'Prodotto aggiornato con successo!'];
            } else {
                $response = ['success' => false, 'message' => 'Errore durante l\'aggiornamento del prodotto: ' . $conn->error];
            }
            
            $stmt->close();
        }
    }
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Fetch all categories from database
$cat_sql = "SELECT * FROM categoria ORDER BY nome ASC";
$cat_result = $conn->query($cat_sql);

// Fetch all menu products with category info
$sql = "SELECT m.*, c.nome_inglese as categoria_inglese 
        FROM menu m 
        JOIN categoria c ON m.tipo = c.nome 
        ORDER BY m.nome";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestionale Gelateria - Catalogo digitale</title>
  <link rel="stylesheet" href="../../../front-end/css/dashboard.css">
  <link rel="stylesheet" href="../../../front-end/css/menu/catalogo_digitale.css">
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
            <li><a href="../schermo/catalogo_prodotti.php">Catalogo Prodotti</a></li>
            <li><a href="../schermo/new_prodouct.php">Nuovo Prodotto</a></li>
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
            <li><a href="./catalogo_digitale.php">Catalogo Digitale</a></li>
            <li><a href="./new_prodouct.php">Nuovo Prodotto</a></li>
            <li><a href="./menu_digitale.php">Menu Digitale</a></li>
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
      <div class="table-header">
        <h2 class="table-title">I tuoi Prodotti</h2>
      </div>
      
      <!-- Notification area -->
      <div id="notification" class="notification"></div>
      
      <!-- Products table -->
      <div class="table-container">
        <table class="products-table">
          <thead>
            <tr>
              <th></th>
              <th>NOME</th>
              <th>INGREDIENTI</th>
              <th>EXTRA</th>
              <th>PREZZO</th>
              <th>TIPO</th>
              <th>STATO</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Reimposta il puntatore dei risultati
                $result->data_seek(0);
                
                // Tiene traccia della categoria corrente per aggiungere separatori
                $currentCategory = '';
                
                while ($row = $result->fetch_assoc()) {
                    $id = $row['id'];
                    $nome = htmlspecialchars($row['nome']);
                    $nome_inglese = htmlspecialchars($row['nome_inglese']);
                    $tipo = htmlspecialchars($row['tipo']);
                    $categoriaInglese = htmlspecialchars($row['categoria_inglese']);
                    $prezzo = number_format($row['prezzo'], 2);
                    $ingredienti_it = htmlspecialchars($row['ingredienti_it'] ?? '');
                    $extra = htmlspecialchars($row['extra'] ?? '');
                    $stato = $row['visibile'] ? 'VISIBILE' : 'NON VISIBILE';
                    $statoClass = $row['visibile'] ? 'status-visible' : 'status-hidden';
                    
                    // Se la categoria è cambiata, aggiungi riga di categoria
                    if ($tipo != $currentCategory) {
                        $currentCategory = $tipo;
                        
                        // Determina l'icona in base alla categoria
                        $iconPath = '';
                        switch (strtolower($tipo)) {
                            case 'gelato':
                                $iconPath = '../../../../img/icone_menu/gelato.png';
                                break;
                            case 'granita':
                                $iconPath = '../../../../img/icone_menu/granita.png';
                                break;
                            case 'milkshake':
                                $iconPath = '../../../../img/icone_menu/frappe.png'; // Utilizzo frappe.png per milkshake
                                break;
                            case 'crepes':
                            case 'crepe':    
                                $iconPath = '../../../../img/icone_menu/crepes.png'; // Nota la 's' finale
                                break;
                            case 'pancake':
                            case 'pancakes':
                                $iconPath = '../../../../img/icone_menu/pancake.png';
                                break;
                            case 'coppa gelato':
                                $iconPath = '../../../../img/icone_menu/coppa.png';
                                break;
                            case 'bevande calde':
                                $iconPath = '../../../../img/icone_menu/bevanda_calda.png';
                                break;
                            case 'bevande fredde':
                                $iconPath = '../../../../img/icone_menu/bevanda_fredda.png';
                                break;
                            case 'cioccolata calda':
                                $iconPath = '../../../../img/icone_menu/cioccolata_calda.png';
                                break;
                            case 'torte':
                                $iconPath = '../../../../img/icone_menu/cake.png';
                                break;
                            default:
                                // Verifichiamo se esiste un'icona specifica per questa categoria
                                $iconFileName = strtolower(str_replace(' ', '_', $tipo));
                                $possibleIconPath = "../../../../img/icone_menu/{$iconFileName}.png";
                                
                                // Usa il percorso personalizzato se il file esiste, altrimenti usa gelato.png come fallback
                                if (file_exists($_SERVER['DOCUMENT_ROOT'] . str_replace('../../../../', '/', $possibleIconPath))) {
                                    $iconPath = $possibleIconPath;
                                } else {
                                    $iconPath = '../../../../img/icone_menu/gelato.png';
                                }
                        }
                        
                        // Aggiungi riga intestazione categoria
                        echo "<tr class='category-header'>";
                        echo "<td class='category-icon'><img src='{$iconPath}' alt='{$tipo}'></td>";
                        echo "<td colspan='6'>" . ucfirst($tipo) . "</td>";
                        echo "</tr>";
                    }
                    
                    echo "<tr data-id='{$id}'>";
                    echo "<td></td>";
                    echo "<td>{$nome}</td>";
                    echo "<td>" . (empty($ingredienti_it) ? '-' : $ingredienti_it) . "</td>";
                    echo "<td>" . (empty($extra) ? '-' : $extra) . "</td>";
                    echo "<td class='price-cell'>{$prezzo} €</td>";
                    echo "<td>" . ucfirst($tipo) . "</td>";
                    echo "<td><span class='status-badge {$statoClass}'>{$stato}</span></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='no-products'>Nessun prodotto trovato.</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
      
      <!-- Edit form overlay -->
      <div class="edit-overlay" id="editOverlay">
        <div class="edit-form">
          <button type="button" class="close-btn" onclick="closeEditForm()">&times;</button>
          <h2>Modifica Prodotto</h2>
          <form id="editForm" method="post">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="productId">
            
            <div class="form-row">
              <div class="form-group">
                <label for="nome">Nome Italiano</label>
                <input type="text" id="nome" name="nome" required>
              </div>
              <div class="form-group">
                <label for="nome_inglese">Nome Inglese</label>
                <input type="text" id="nome_inglese" name="nome_inglese" required>
              </div>
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label for="prezzo">Prezzo (€)</label>
                <input type="number" id="prezzo" name="prezzo" step="0.01" min="0" required>
              </div>
              <div class="form-group">
                <label for="tipo">Categoria</label>
                <select id="tipo" name="tipo" required>
                  <?php 
                  if ($cat_result->num_rows > 0) {
                      $cat_result->data_seek(0);
                      while($cat = $cat_result->fetch_assoc()) {
                          echo '<option value="' . htmlspecialchars($cat['nome']) . '">' . 
                               htmlspecialchars(ucfirst($cat['nome'])) . ' / ' . htmlspecialchars(ucfirst($cat['nome_inglese'])) . '</option>';
                      }
                  }
                  ?>
                </select>
              </div>
            </div>
            
            <div class="form-group">
              <label for="ingredienti_it">Ingredienti (Italiano)</label>
              <textarea id="ingredienti_it" name="ingredienti_it"></textarea>
            </div>
            
            <div class="form-group">
              <label for="ingredienti_en">Ingredienti (Inglese)</label>
              <textarea id="ingredienti_en" name="ingredienti_en"></textarea>
            </div>
            
            <div class="form-group">
              <label for="extra">Extra (Separati da punto e virgola)</label>
              <textarea id="extra" name="extra" placeholder="Es: Panna;Cioccolato;Caffè"></textarea>
            </div>
            
            <div class="form-group">
              <div class="checkbox-wrapper">
                <input type="checkbox" id="visibile" name="visibile">
                <label for="visibile">Visibile nel menu</label>
              </div>
            </div>
            
            <div class="form-actions">
              <button type="submit" class="btn save-btn">Salva Modifiche</button>
              <button type="button" class="btn cancel-btn" onclick="closeEditForm()">Annulla</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>
  
  <script src="../../../js/menu/catalogo_digitale.js"></script>
</body>
</html>