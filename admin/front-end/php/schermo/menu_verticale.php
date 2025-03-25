<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestionale Gelateria - Menu</title>
  <link rel="stylesheet" href="../../../front-end/css/schermo/menu_verticale.css">
</head>
<body>
  <div class="background-container"></div>
  <div class="content">
    <img src="../../../../img/david/david_logo_testo_2.png" alt="logodavid" class="logodavid">
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
        
        // Divide products into those with icons and those without
        $productsWithIcons = [];
        $productsWithoutIcons = [];
        
        foreach ($products as $product) {
            $icons = showIcons($product);
            if (!empty($icons)) {
                $productsWithIcons[] = ['product' => $product, 'icons' => $icons];
            } else {
                $productsWithoutIcons[] = ['product' => $product, 'icons' => ''];
            }
        }
        
        // Display products with icons first
        if (!empty($productsWithIcons)) {
            echo '<div class="products-container">';
            foreach ($productsWithIcons as $item) {
                $product = $item['product'];
                $icons = $item['icons'];
                
                echo '<div class="product-item">';
                echo '<div class="product-name">' . $product['nome'] . '</div>';
                echo '<div class="product-name-en">' . $product['nome_inglese'] . '</div>';
                echo '<div class="product-icons">' . $icons . '</div>';
                echo '<div class="ingredients-divider">ingredients</div>';
                
                if ($product['ingredienti_visibili'] && !empty($product['ingredienti'])) {
                    echo '<div class="product-ingredients">' . $product['ingredienti'] . '</div>';
                }
                
                echo '</div>';
            }
            echo '</div>';
        }
        
        // Then display products without icons
        if (!empty($productsWithoutIcons)) {
            echo '<div class="products-container">';
            foreach ($productsWithoutIcons as $item) {
                $product = $item['product'];
                
                echo '<div class="product-item">';
                echo '<div class="product-name">' . $product['nome'] . '</div>';
                echo '<div class="product-name-en">' . $product['nome_inglese'] . '</div>';
                echo '<div class="ingredients-divider">ingredients</div>';
                
                if ($product['ingredienti_visibili'] && !empty($product['ingredienti'])) {
                    echo '<div class="product-ingredients">' . $product['ingredienti'] . '</div>';
                }
                
                echo '</div>';
            }
            echo '</div>';
        }
    }
    
    // Mostra le sezioni in base alle categorie disponibili
    foreach ($categorie as $categoria) {
        $nome = $categoria['nome'];
        $nome_inglese = $categoria['nome_inglese'];
        $display_name = ucfirst($nome) . " / " . ucfirst($nome_inglese);
        
        // Per gelato, usare la stessa icona come prima
        $iconPath = ($nome == 'gelato') ? '../../../../img/tipologie/mini_gelato.png' : null;
        
        // Mostra la sezione solo se ci sono prodotti nella categoria
        if (!empty($prodotti_by_tipo[$nome])) {
            showProductSection($prodotti_by_tipo[$nome], $display_name, $iconPath);
        }
    }
    ?>
  </div>
  <script src="../../../js/schermo/menu_verticale.js"></script>
</body>
</html>