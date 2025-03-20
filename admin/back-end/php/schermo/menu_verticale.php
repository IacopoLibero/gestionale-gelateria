<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/gestionale gelateria/connessione.php';

// Funzione per ottenere prodotti attivi per tipo
function getProdottiByTipo($conn) {
    $prodotti = [
        'gelato' => [],
        'granita' => [],
        'semifreddo' => []
    ];
    
    $sql = "SELECT * FROM prodotto WHERE stato = TRUE ORDER BY nome ASC";
    // This SQL query is already filtering to only show products where stato = TRUE
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $prodotti[$row['tipo']][] = $row;
        }
    }
    
    return $prodotti;
}

// Ottenere tutti i prodotti organizzati per tipo
$prodotti_by_tipo = getProdottiByTipo($conn);

// Verificare quali tipi di prodotti sono disponibili
$has_gelato = !empty($prodotti_by_tipo['gelato']);
$has_granita = !empty($prodotti_by_tipo['granita']);
$has_semifreddo = !empty($prodotti_by_tipo['semifreddo']);
?>
