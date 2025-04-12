<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit;
}

// Include database connection
require_once '../../../../connessione.php';

// Fetch all products with category info
$sql = "SELECT p.*, c.nome_inglese as categoria_inglese 
        FROM prodotto p 
        JOIN categoria c ON p.tipo = c.nome 
        ORDER BY p.nome";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestionale Gelateria - Catalogo Prodotti</title>
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
    <!-- Contenitore del titolo separato -->
    <div class="title-container">
      <h2>I tuoi Prodotti</h2>
    </div>
    
    <div>
      <!-- Notification area -->
      <div id="notification" class="notification"></div>
      
      <!-- Products table -->
      <div class="table-container">
        <table class="products-table">
          <thead>
            <tr>
              <th></th>
              <th>NOME</th>
              <th>TIPO</th>
              <th>STATO</th>
              <th>OPZIONI</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Tiene traccia della categoria corrente per aggiungere separatori
            $currentCategory = '';
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row['id'];
                    $nome = htmlspecialchars($row['nome']);
                    $nomeInglese = htmlspecialchars($row['nome_inglese']);
                    $ingredienti = htmlspecialchars($row['ingredienti'] ?? '');
                    $tipo = htmlspecialchars($row['tipo']);
                    $categoriaInglese = htmlspecialchars($row['categoria_inglese']);
                    $stato = $row['stato'] ? 'Attivo' : 'Disattivo';
                    $statoClass = $row['stato'] ? 'status-visible' : 'status-hidden';
                    $km0 = $row['km0'] ? 'Sì' : 'No';
                    $vegano = $row['vegano'] ? 'Sì' : 'No';
                    $slowFood = $row['SlowFood'] ? 'Sì' : 'No';
                    $bio = $row['bio'] ? 'Sì' : 'No';
                    $innovativo = $row['innovativo'] ? 'Sì' : 'No';
                    $ingredientiVisibili = $row['ingredienti_visibili'] ? 'Sì' : 'No';
                    
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
                                $iconPath = '../../../../img/icone_menu/frappe.png';
                                break;
                            case 'crepes':
                                $iconPath = '../../../../img/icone_menu/crepes.png';
                                break;
                            case 'pancakes':
                                $iconPath = '../../../../img/icone_menu/pancake.png';
                                break;
                            case 'coppa gelato':
                                $iconPath = '../../../../img/icone_menu/coppa.png';
                                break;
                            case 'bevanda calda':
                                $iconPath = '../../../../img/icone_menu/bevanda_calda.png';
                                break;
                            case 'bevanda fredda':
                                $iconPath = '../../../../img/icone_menu/bevanda_fredda.png';
                                break;
                            case 'cioccolata calda':
                                $iconPath = '../../../../img/icone_menu/cioccolata_calda.png';
                                break;
                            case 'torta':
                                $iconPath = '../../../../img/icone_menu/cake.png';
                                break;
                            default:
                                // Tenta di trovare un'icona che corrisponde al nome della categoria
                                $iconFileName = strtolower(str_replace(' ', '_', $tipo));
                                $possibleIconPath = "../../../../img/icone_menu/{$iconFileName}.png";
                                
                                if (file_exists($_SERVER['DOCUMENT_ROOT'] . str_replace('../../../../', '/', $possibleIconPath))) {
                                    $iconPath = $possibleIconPath;
                                } else {
                                    $iconPath = '../../../../img/icone_menu/gelato.png'; // Default
                                }
                        }
                        
                        // Aggiungi riga intestazione categoria con icone sia a sinistra che a destra
                        echo "<tr class='category-header'>";
                        echo "<td class='category-icon'><img src='{$iconPath}' alt='{$tipo}'></td>";
                        echo "<td colspan='3' class='category-title'>" . ucfirst($tipo) . " / " . ucfirst($categoriaInglese) . "</td>";
                        echo "<td class='category-icon right'><img src='{$iconPath}' alt='{$tipo}'></td>";
                        echo "</tr>";
                    }
                    
                    // Riga prodotto - ora con attributi data per il modal
                    echo "<tr data-id='{$id}' data-nome='{$nome}' data-nome-inglese='{$nomeInglese}' data-tipo='{$tipo}' data-ingredienti='{$ingredienti}' data-stato='{$row['stato']}' data-km0='{$row['km0']}' data-vegano='{$row['vegano']}' data-slowfood='{$row['SlowFood']}' data-bio='{$row['bio']}' data-innovativo='{$row['innovativo']}' data-ingredienti-visibili='{$row['ingredienti_visibili']}'>";
                    echo "<td></td>";
                    echo "<td>{$nome}</td>";
                    echo "<td>" . ucfirst($tipo) . "</td>";
                    echo "<td><span class='status-badge {$statoClass}'>{$stato}</span></td>";
                    echo "<td class='actions-cell'>";
                    echo "<a href='edit_prodotto.php?id=$id' class='edit-btn'>Modifica</a>";
                    echo "<form method='POST' action='../../../back-end/php/schermo/delete_prodotto.php' class='delete-form' style='display:inline-block;'>";
                    echo "<input type='hidden' name='id' value='$id'>";
                    echo "<button type='submit' class='delete-btn'>Elimina</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='no-products'>Nessun prodotto trovato nel database.</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
      
      <div class="actions">
        <a href="new_prodouct.php" class="submit-button">
          <span class="button_top">Aggiungi Nuovo Prodotto</span>
        </a>
      </div>
      
      <!-- Edit form overlay -->
      <div class="edit-overlay" id="editOverlay">
        <div class="edit-form">
          <button type="button" class="close-btn" onclick="closeEditForm()">&times;</button>
          <h2>Dettagli Prodotto</h2>
          <form id="editForm" method="post">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="productId">
            
            <div class="form-row">
              <div class="form-group">
                <label for="nome">Nome Italiano</label>
                <input type="text" id="nome" name="nome" readonly>
              </div>
              <div class="form-group">
                <label for="nome_inglese">Nome Inglese</label>
                <input type="text" id="nome_inglese" name="nome_inglese" readonly>
              </div>
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label for="tipo">Categoria</label>
                <input type="text" id="tipo" name="tipo" readonly>
              </div>
              <div class="form-group">
                <label for="stato">Stato</label>
                <input type="text" id="stato" name="stato" readonly>
              </div>
            </div>
            
            <div class="form-group">
              <label for="ingredienti">Ingredienti</label>
              <textarea id="ingredienti" name="ingredienti" readonly></textarea>
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label>Km0</label>
                <div id="km0"></div>
              </div>
              <div class="form-group">
                <label>Vegano</label>
                <div id="vegano"></div>
              </div>
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label>Slow Food</label>
                <div id="slowfood"></div>
              </div>
              <div class="form-group">
                <label>Biologico</label>
                <div id="bio"></div>
              </div>
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label>Innovativo</label>
                <div id="innovativo"></div>
              </div>
              <div class="form-group">
                <label>Ingredienti Visibili</label>
                <div id="ingredienti_visibili"></div>
              </div>
            </div>
            
            <div class="form-actions">
              <a href="#" id="editButton" class="btn save-btn">Modifica</a>
              <button type="button" class="btn cancel-btn" onclick="closeEditForm()">Chiudi</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>
  
  <script src="../../../js/dashboard.js"></script>
  <script src="../../../js/schermo/catalogo_prodotti.js"></script>
</body>
</html>