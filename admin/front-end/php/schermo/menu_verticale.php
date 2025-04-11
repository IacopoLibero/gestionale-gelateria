<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestionale Gelateria - Menu</title>
  <link rel="stylesheet" href="../../../front-end/css/schermo/menu_verticale.css">
  <?php
  // Get font size setting from database
  require_once '../../../../connessione.php';
  
  // Start session if not already started
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
  
  // Default font size
  $font_size = 200;  
  $ingredients_size = 150; // 75% of product name size (30% less)
  
  // If user is logged in, get their font size preference
  if (isset($_SESSION['username'])) {
      $font_sql = "SELECT font_size FROM utente WHERE username = ?";
      $font_stmt = $conn->prepare($font_sql);
      $font_stmt->bind_param("s", $_SESSION['username']);
      $font_stmt->execute();
      $font_result = $font_stmt->get_result();
      
      if ($font_result->num_rows > 0) {
          $font_row = $font_result->fetch_assoc();
          $font_size = intval($font_row['font_size']);
          $ingredients_size = intval($font_size * 0.7); // 30% smaller for ingredients
      }
      
      $font_stmt->close();
  }
  // Do NOT close the database connection here as it will be needed later
  ?>
  <style>
    .product-name {
      font-size: <?php echo $font_size; ?>% !important;
    }
    .product-name-en {
      font-size: <?php echo $font_size; ?>% !important;
    }
    .product-ingredients {
      font-size: <?php echo $ingredients_size; ?>% !important;
    }
  </style>
</head>
<body>
  <div class="background-container"></div>
  <div class="content">
    <img src="../../../../img/david/david_logo_testo_2.png" alt="logodavid" class="logodavid">
    <div class="products-section">
    <?php
    // Include il file back-end per recuperare i dati
    require_once '../../../back-end/php/schermo/menu_verticale.php';
    
    // Funzione per mostrare le icone dei prodotti
    function showIcons($product) {
        $icons = '';
        if ($product['km0']) $icons .= '<img src="../../../../img/tipologie/km0.png" alt="Km 0" class="product-icon" title="Km 0">';
        if ($product['vegano']) $icons .= '<img src="../../../../img/tipologie/vegano.png" alt="Vegano" class="product-icon" title="Vegano">';
        if ($product['SlowFood']) $icons .= '<img src="../../../../img/tipologie/slowfood.png" alt="Slow Food" class="product-icon" title="Slow Food">';
        if ($product['bio']) $icons .= '<img src="../../../../img/tipologie/bio.png" alt="Biologico" class="product-icon" title="Biologico">';
        if ($product['innovativo']) $icons .= '<img src="../../../../img/tipologie/innovativo.png" alt="Innovativo" class="product-icon" title="Innovativo">';
        return $icons;
    }
    
    // Funzione per mostrare una sezione di prodotti
    function showProductSection($products, $sectionTitle, $iconPath = null) {
        if (empty($products)) return; // Non mostrare sezioni vuote
        
        echo '<div class="divider-with-text">';
        if ($iconPath) {
            echo '<hr><img src="' . $iconPath . '" class="gelatino">' . $sectionTitle . '<img class="gelatino" src="' . $iconPath . '"><hr>';
        } else {
            echo '<hr>' . $sectionTitle . '<hr>';
        }
        echo '</div>';
        
        // Split products into two equal columns
        $totalProducts = count($products);
        $productsPerColumn = ceil($totalProducts / 2);
        
        $leftColumnProducts = array_slice($products, 0, $productsPerColumn);
        $rightColumnProducts = array_slice($products, $productsPerColumn);
        
        echo '<div class="two-column-container">';
        
        // Left column
        echo '<div class="column left-column">';
        foreach ($leftColumnProducts as $product) {
            renderProduct($product);
        }
        echo '</div>';
        
        // Right column
        echo '<div class="column right-column">';
        foreach ($rightColumnProducts as $product) {
            renderProduct($product);
        }
        echo '</div>';
        
        echo '</div>';
    }
    
    // Helper function to render a single product
    function renderProduct($product) {
        $icons = showIcons($product);
        $hasIcons = !empty($icons);
        $hasIngredients = $product['ingredienti_visibili'] && !empty($product['ingredienti']);
        
        $itemClasses = [];
        $itemClasses[] = $hasIcons ? 'has-icons' : 'no-icons';
        $itemClasses[] = $hasIngredients ? 'has-ingredients' : 'no-ingredients';
        
        echo '<div class="product-item ' . implode(' ', $itemClasses) . '">';
        echo '<div class="product-name">' . $product['nome'] . '</div>';
        echo '<div class="product-name-en">' . $product['nome_inglese'] . '</div>';
        
        // Only show icons div if this product has icons
        if ($hasIcons) {
            echo '<div class="product-icons">' . $icons . '</div>';
        }
        
        // Always show the ingredients divider
        echo '<div class="ingredients-divider">ingredients</div>';
        
        // Only include the ingredients container if there are ingredients to show
        if ($hasIngredients) {
            echo '<div class="product-ingredients">' . $product['ingredienti'] . '</div>';
        } else {
            echo '<div class="product-ingredients empty"></div>';
        }
        
        echo '</div>';
    }
    
    // Mostro solo i gelati (categoria 'gelato')
    $gelatoCategory = null;
    
    // Cerco la categoria 'gelato' nell'array delle categorie
    foreach ($categorie as $categoria) {
        if ($categoria['nome'] == 'gelato') {
            $gelatoCategory = $categoria;
            break;
        }
    }
    
    // Se la categoria gelato è stata trovata ed esistono prodotti di tipo gelato, li mostro
    if ($gelatoCategory && !empty($prodotti_by_tipo['gelato'])) {
        $display_name = ucfirst($gelatoCategory['nome']) . " / " . ucfirst($gelatoCategory['nome_inglese']);
        $iconPath = '../../../../img/tipologie/mini_gelato.png';
        showProductSection($prodotti_by_tipo['gelato'], $display_name, $iconPath);
    }
    ?>
    </div> <!-- Close products-section -->
  </div> <!-- Close content -->
  <script src="../../../js/schermo/menu_verticale.js"></script>
</body>
</html>