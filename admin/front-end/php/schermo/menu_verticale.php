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
        
        // Group products into pairs for display in rows
        $pairs = array_chunk($products, 2);
        
        foreach ($pairs as $pair) {
            // Check if both products in the pair have no icons
            $bothNoIcons = true;
            $pairIcons = [];
            
            foreach ($pair as $index => $product) {
                $icons = showIcons($product);
                $pairIcons[$index] = $icons;
                if (!empty($icons)) {
                    $bothNoIcons = false;
                }
            }
            
            echo '<div class="products-container' . ($bothNoIcons ? ' no-icons-row' : '') . '">';
            
            foreach ($pair as $index => $product) {
                $hasIcons = !empty($pairIcons[$index]);
                $rowClass = $bothNoIcons ? 'row-no-icons' : '';
                $itemClass = $hasIcons ? 'has-icons' : 'no-icons';
                
                echo '<div class="product-item ' . $itemClass . ' ' . $rowClass . '">';
                echo '<div class="product-name">' . $product['nome'] . '</div>';
                echo '<div class="product-name-en">' . $product['nome_inglese'] . '</div>';
                
                // Only show icons div if this product has icons
                if ($hasIcons) {
                    echo '<div class="product-icons">' . $pairIcons[$index] . '</div>';
                }
                
                // Always show the ingredients divider
                echo '<div class="ingredients-divider">ingredients</div>';
                
                // Always include the ingredients container for consistency
                echo '<div class="product-ingredients">';
                if ($product['ingredienti_visibili'] && !empty($product['ingredienti'])) {
                    echo $product['ingredienti'];
                }
                echo '</div>';
                
                echo '</div>';
            }
            
            // If there's only one product in the last pair, add an empty div to maintain the grid
            if (count($pair) == 1) {
                echo '<div class="product-item empty-item"></div>';
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