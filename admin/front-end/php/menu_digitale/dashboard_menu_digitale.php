<?php
session_start();

// Determinare la lingua dalla query string
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'it';

// Verificare che la lingua sia valida (solo 'it' o 'en')
if ($lang !== 'it' && $lang !== 'en') {
    $lang = 'it'; // Default a italiano se la lingua non è valida
}

// Array per gestire le differenze tra le lingue
$language_specific = [
    'it' => [
        'html_lang' => 'it',
        'gelato' => 'gelato',
        'coppa_gelato' => 'coppa_gelato',
        'cioccolata_calda' => 'cioccolata_calda',
        'bevande_calde' => 'bevande_calde',
        'bevande_fredde' => 'bevande_fredde'
    ],
    'en' => [
        'html_lang' => 'en',
        'gelato' => 'gelato_ing',
        'coppa_gelato' => 'coppa_gelato_ing',
        'cioccolata_calda' => 'cioccolata_calda_ing',
        'bevande_calde' => 'bevande_calde_ing',
        'bevande_fredde' => 'bevande_fredde_ing'
    ]
];

// Accedi ai valori specifici della lingua selezionata
$current_lang = $language_specific[$lang];
?>

<!doctype html>
<html lang="<?php echo $current_lang['html_lang']; ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../../../../img/favicon2.svg" type="image/x-icon">
    <title>Menu Gelateria David</title>

    <!--css-->
    <link rel="stylesheet" href="../../../front-end/css/menu/menu_digitale.css">

    <!--js-->
    <script src="../../../js/menu/dashboard_menu_digitale.js"></script>

    <!-- Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
<body class="no_margin no_padding container-fluid" style="overflow: auto;">
    <div class="row" style="margin-bottom: 8vw;">
        <div class="col-6 riga_dash">
            <div class="space_evenly_column box_dash" onclick="menu(100,'<?php echo $lang; ?>')">
                <img class="img_dash2" src="../../../../img/grafico/menu.png">
            </div>
        </div>
        <div class="col-6 riga_dash">
            <div class="space_evenly_column box_dash" onclick="menu(0,'<?php echo $lang; ?>')">
                <img class="img_dash2" src="../../../../img/grafico/<?php echo $current_lang['gelato']; ?>.png">
            </div>
        </div>
        <div class="col-6 riga_dash">
            <div class="space_evenly_column box_dash" onclick="menu(1,'<?php echo $lang; ?>')">
                <img class="img_dash2" src="../../../../img/grafico/granite.png">
            </div>
        </div>
        <div class="col-6 riga_dash">
            <div class="space_evenly_column box_dash" onclick="menu(3,'<?php echo $lang; ?>')">
                <img class="img_dash2" src="../../../../img/grafico/milkshake.png">
                <!--<span class="txt_dash">Frapp&eacute;</span>-->
            </div>
        </div>
        <div class="col-6 riga_dash">
            <div class="space_evenly_column box_dash" onclick="menu(4,'<?php echo $lang; ?>')">
                <img class="img_dash2" src="../../../../img/grafico/crepes.png">
                <!--<span class="txt_dash">Crepes</span>-->
            </div>
        </div>
        <div class="col-6 riga_dash">
            <div class="space_evenly_column box_dash" onclick="menu(5,'<?php echo $lang; ?>')">
                <img class="img_dash2" src="../../../../img/grafico/pancakes.png">
                <!--<span class="txt_dash">Pancakes</span>-->
            </div>
        </div>
        <div class="col-6 riga_dash">
            <div class="space_evenly_column box_dash" onclick="menu(7,'<?php echo $lang; ?>')">
                <img class="img_dash2" src="../../../../img/grafico/<?php echo $current_lang['coppa_gelato']; ?>.png">
            </div>
        </div>
        <div class="col-6 riga_dash">
            <div class="space_evenly_column box_dash" onclick="menu(11,'<?php echo $lang; ?>')">
                <img class="img_dash2" src="../../../../img/grafico/cake.png">
            </div>
        </div>
        <div class="col-6 riga_dash">
            <div class="space_evenly_column box_dash" onclick="menu(12,'<?php echo $lang; ?>')">
                <img class="img_dash2" src="../../../../img/grafico/<?php echo $current_lang['cioccolata_calda']; ?>.png">
            </div>
        </div>
        <div class="col-6 riga_dash">
            <div class="space_evenly_column box_dash" onclick="menu(9,'<?php echo $lang; ?>')">
                <img class="img_dash2" src="../../../../img/grafico/<?php echo $current_lang['bevande_calde']; ?>.png">
            </div>
        </div>
        <div class="col-6 riga_dash">
            <div class="space_evenly_column box_dash" onclick="menu(10,'<?php echo $lang; ?>')">
                <img class="img_dash2" src="../../../../img/grafico/<?php echo $current_lang['bevande_fredde']; ?>.png">
            </div>
        </div>
    </div>
</body>
</html>