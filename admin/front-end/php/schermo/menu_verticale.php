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
        if ($product['km0']) $icons .= '<img src="../../../../img/icone/km0.png" alt="Km 0" class="product-icon" title="Km 0">';
        if ($product['vegano']) $icons .= '<img src="../../../../img/icone/vegano.png" alt="Vegano" class="product-icon" title="Vegano">';
        if ($product['SlowFood']) $icons .= '<img src="../../../../img/icone/slowfood.png" alt="Slow Food" class="product-icon" title="Slow Food">';
        if ($product['bio']) $icons .= '<img src="../../../../img/icone/bio.png" alt="Biologico" class="product-icon" title="Biologico">';
        if ($product['innovativo']) $icons .= '<img src="../../../../img/icone/innovativo.png" alt="Innovativo" class="product-icon" title="Innovativo">';
        return $icons;
    }
    
    // Funzione per mostrare una sezione di prodotti
    function showProductSection($products, $sectionTitle, $iconPath) {
        if (empty($products)) return; // Non mostrare sezioni vuote
        
        echo '<div class="divider-with-text">';
        echo '<hr><img src="' . $iconPath . '" class="gelatino">' . $sectionTitle . '<img class="gelatino" src="' . $iconPath . '"><hr>';
        echo '</div>';
        
        echo '<div class="products-container">';
        foreach ($products as $product) {
            echo '<div class="product-item">';
            echo '<div class="product-name">' . $product['nome'] . '</div>';
            echo '<div class="product-name-en">' . $product['nome_inglese'] . '</div>';
            
            // Mostrare le icone se presenti
            $icons = showIcons($product);
            if (!empty($icons)) {
                echo '<div class="product-icons">' . $icons . '</div>';
            }
            
            // Mostrare gli ingredienti solo se visibili
            if ($product['ingredienti_visibili'] && !empty($product['ingredienti'])) {
                echo '<div class="product-ingredients">' . $product['ingredienti'] . '</div>';
            }
            
            echo '</div>';
        }
        echo '</div>';
    }
    
    // Mostra le sezioni in base ai prodotti disponibili
    showProductSection($prodotti_by_tipo['gelato'], 'Gelati / Ice Creams', '../../../../img/tipologie/mini_gelato.png');
    showProductSection($prodotti_by_tipo['granita'], 'Granite / Slushes', '../../../../img/tipologie/mini_granita.png');
    showProductSection($prodotti_by_tipo['semifreddo'], 'Semifreddi / Frozen Desserts', '../../../../img/tipologie/mini_semifreddo.png');
    ?>
  </div>
  <script src="../../../js/schermo/menu_verticale.js"></script>
</body>
</html>