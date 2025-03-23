<?php
require_once '../../../../connessione.php';

// Funzione per ottenere tutte le categorie
function getCategorie($conn) {
    $categorie = [];
    
    $sql = "SELECT * FROM categoria ORDER BY nome ASC";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $categorie[] = $row;
        }
    }
    
    return $categorie;
}

// Funzione per ottenere prodotti attivi per tipo
function getProdottiByTipo($conn) {
    $prodotti = [];
    
    // Prima otteniamo tutte le categorie per inizializzare l'array
    $cats = getCategorie($conn);
    foreach ($cats as $cat) {
        $prodotti[$cat['nome']] = [];
    }
    
    $sql = "SELECT p.*, c.nome_inglese as categoria_inglese 
            FROM prodotto p 
            JOIN categoria c ON p.tipo = c.nome 
            WHERE p.stato = TRUE 
            ORDER BY p.nome ASC";
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

// Ottenere tutte le categorie
$categorie = getCategorie($conn);
?>
