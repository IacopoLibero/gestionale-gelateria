<?php
session_start();
require_once '../../../../connessione.php';

// Determinare la lingua dalla query string
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'it';

// Verificare che la lingua sia valida (solo 'it' o 'en')
if ($lang !== 'it' && $lang !== 'en') {
    $lang = 'it'; // Default a italiano se la lingua non è valida
}

// Ottenere il tipo di menu dalla query string (default a 100 = menu completo)
$type = isset($_GET['type']) ? intval($_GET['type']) : 100;

// Percorso alle immagini delle icone
$imgPath = '../../../../img/mini/';

// Array di mappatura tra il tipo numerico e il nome della categoria
$typeToCategory = [
    0 => 'gelato',
    1 => 'granita',
    3 => 'milkshake',
    4 => 'crepes',
    5 => 'pancakes',
    7 => 'coppa gelato',
    9 => 'bevanda calda',
    10 => 'bevanda fredda',
    11 => 'torta',
    12 => 'cioccolata calda',
    100 => 'all' // Tutti i prodotti
];

// Array per le traduzioni dei titoli in inglese
$categoryTitles = [
    'it' => [
        'gelato' => 'Gelati',
        'granita' => 'Granite',
        'milkshake' => 'Milkshake',
        'crepes' => 'Crepes',
        'pancakes' => 'Pancakes',
        'coppa gelato' => 'Coppe Gelato',
        'bevanda calda' => 'Bevande Calde',
        'bevanda fredda' => 'Bevande Fredde',
        'torta' => 'Torte',
        'cioccolata calda' => 'Cioccolata Calda'
    ],
    'en' => [
        'gelato' => 'Ice Creams',
        'granita' => 'Granitas',
        'milkshake' => 'Milkshakes',
        'crepes' => 'Crepes',
        'pancakes' => 'Pancakes',
        'coppa gelato' => 'Ice Cream Cups',
        'bevanda calda' => 'Hot Drinks',
        'bevanda fredda' => 'Cold Drinks',
        'torta' => 'Cakes',
        'cioccolata calda' => 'Hot Chocolate'
    ]
];

// Mappatura delle icone per le categorie
$categoryIcons = [
    'gelato' => 'mini_gelato.png',
    'granita' => 'mini_granita.png',
    'milkshake' => 'mini_milkshake.png',
    'crepes' => 'mini_crepe.png',
    'pancakes' => 'mini_pancake.png',
    'coppa gelato' => 'mini_coppa.png',
    'bevanda calda' => 'mini_bevanda_calda.png',
    'bevanda fredda' => 'mini_bevanda_fredda.png',
    'torta' => 'mini_torta.png',
    'cioccolata calda' => 'mini_cioccolata_calda.png'
];

// Seleziona la categoria corretta in base al tipo
$selectedCategory = isset($typeToCategory[$type]) ? $typeToCategory[$type] : 'all';

// Prepara HTML per il contenuto del menu
$menuHTML = '';

// Funzione per generare l'HTML di una categoria
function generateCategoryHTML($conn, $categoryName, $lang, $categoryTitles, $categoryIcons, $imgPath) {
    $html = '';
    $categoryTitle = $categoryTitles[$lang][$categoryName] ?? ucfirst($categoryName);
    $categoryIcon = $categoryIcons[$categoryName] ?? 'mini_gelato.png';
    
    // Intestazione categoria
    $html .= "<div class='col-12 center_row divider_categoria'>";
    $html .= "<img src='{$imgPath}{$categoryIcon}' class='img_logo_categoria'>";
    $html .= "<span class='titolo_menu'>{$categoryTitle}</span></div>";
    
    // Per la categoria 'gelato', prendi i dati dalla tabella menu invece che dalla tabella prodotto
    if ($categoryName === 'gelato') {
        $sql = "SELECT id, nome, nome_inglese, prezzo FROM menu WHERE tipo = ? ORDER BY nome";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $categoryName);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $productName = ($lang === 'it') ? $row['nome'] : $row['nome_inglese'];
                $price = number_format($row['prezzo'], 2);
                
                $html .= "<div class='col-12 space_between_row'>";
                $html .= "<div class='start_column col-10'>";
                
                // Verificare se esiste un'immagine per il prodotto
                $imagePath = "uploads/menu_{$row['id']}.jpg";
                if (file_exists($imagePath)) {
                    $html .= "<img src='{$imagePath}' class='img_menu'>";
                }
                
                $html .= "<span class='nome_prodotto'>{$productName}</span>";
                
                $html .= "</div>";
                $html .= "<span class='prezzo_prodotto col-2'>{$price} &euro;</span>";
                $html .= "</div>";
                $html .= "<div class='center_row'><hr class='divider_el_menu_digitale'></div>";
            }
            
            // Aggiungi il link per visualizzare la pagina con il menu verticale dei gusti gelato
            $html .= "<div class='col-12 center_row mb-3'>";
            $html .= "<div class='gusti_gelato' onclick=\"location.href = '../schermo/menu_verticale.php'\"><b>";
            $html .= ($lang === 'it') ? "Mostra i Gusti Gelato" : "Show Ice Cream Flavors";
            $html .= "</b></div></div>";
        }
    } else {
        // Per altre categorie, prendi i dati dalla tabella menu
        $sql = "SELECT id, nome, nome_inglese, prezzo FROM menu WHERE tipo = ? ORDER BY nome";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $categoryName);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $productName = ($lang === 'it') ? $row['nome'] : $row['nome_inglese'];
                $price = number_format($row['prezzo'], 2);
                
                $html .= "<div class='col-12 space_between_row'>";
                $html .= "<div class='start_column col-10'>";
                
                // Verificare se esiste un'immagine per il prodotto
                $imagePath = "uploads/menu_{$row['id']}.jpg";
                if (file_exists($imagePath)) {
                    $html .= "<img src='{$imagePath}' class='img_menu'>";
                }
                
                $html .= "<span class='nome_prodotto'>{$productName}</span>";
                
                // Controllo per ingredienti e extra
                // Questo è un esempio semplice, potrebbero essere necessari campi aggiuntivi nella tabella menu
                if ($categoryName == 'milkshake' && $productName == 'Mokaccino') {
                    $ingText = ($lang === 'it') ? '(Caffe espresso, gelato caramello salato, latte)' : '(Espresso coffee, salted caramel ice cream, milk)';
                    $html .= "<span class='ingredienti_prodotto'>{$ingText}</span>";
                }
                
                // Esempio di extra per alcuni prodotti 
                if ($categoryName == 'crepes' && $productName == 'Nutella') {
                    $extraTitle = ($lang === 'it') ? '<b>Extra</b>' : '<b>Extras</b>';
                    $extraItem = ($lang === 'it') ? 'panna: +1€' : 'whipped cream: +1€';
                    
                    $html .= "<div class='extra_prodotto'>{$extraTitle}";
                    $html .= "<ul><li>{$extraItem}</li></ul>";
                    $html .= "</div>";
                }
                
                $html .= "</div>";
                $html .= "<span class='prezzo_prodotto col-2'>{$price} &euro;</span>";
                $html .= "</div>";
                $html .= "<div class='center_row'><hr class='divider_el_menu_digitale'></div>";
            }
        }
    }
    
    return $html;
}

// Genera il contenuto del menu in base al tipo selezionato
if ($selectedCategory === 'all') {
    // Menu completo: mostra tutte le categorie
    foreach ($typeToCategory as $key => $category) {
        if ($key !== 100) { // Salta l'opzione 'all'
            $menuHTML .= generateCategoryHTML($conn, $category, $lang, $categoryTitles, $categoryIcons, $imgPath);
        }
    }
} else {
    // Menu specifico: mostra solo la categoria selezionata
    $menuHTML .= generateCategoryHTML($conn, $selectedCategory, $lang, $categoryTitles, $categoryIcons, $imgPath);
}

// Chiudi la connessione al database
$conn->close();
?>

<!doctype html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Scopri il gusto autentico della tradizione gelatiera fiorentina presso la storica gelateria David a Firenze. Offriamo prelibatezze artigianali create con ingredienti freschi e genuini. Deliziati con i nostri gelati artigianali, cioccolati e sorbetti, e immergiti nell'atmosfera unica di Firenze. Una tappa imprescindibile per gli amanti del gelato e della storia culinaria fiorentina.">
    <title>Menu Gelateria David</title>

    <!--css-->
    <link rel="stylesheet" href="../../../front-end/css/font.css">
    <link rel="stylesheet" href="../../../front-end/css/menu/menu_digitale.css">

    <!--js-->
    <script src="../../../js/jquery.js"></script>

    <!-- Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
<body class="container-fluid">
    <div class="row">
        <?php echo $menuHTML; ?>
    </div>
</body>
</html>