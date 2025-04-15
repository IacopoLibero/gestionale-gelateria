<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../../../../login/login.php");
    exit;
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection
    require_once '../../../../connessione.php';

    // Retrieve form data
    $nome = $_POST['nome'];
    $nome_inglese = $_POST['nome_inglese'];
    $prezzo = $_POST['prezzo'];
    $tipo = $_POST['tipo'];
    $ingredienti_it = isset($_POST['ingredienti_it']) ? $_POST['ingredienti_it'] : '';
    $ingredienti_en = isset($_POST['ingredienti_en']) ? $_POST['ingredienti_en'] : '';
    $extra = isset($_POST['extra']) ? $_POST['extra'] : '';
    // Check if visibile is set, if not it means checkbox was unchecked
    $visibile = isset($_POST['visibile']) ? 1 : 0;

    // Validate inputs (basic validation)
    if (empty($nome) || empty($nome_inglese) || empty($prezzo) || empty($tipo)) {
        $_SESSION['error_message'] = "Compila tutti i campi obbligatori: nome, nome inglese, prezzo e categoria.";
        header("Location: ../../../../admin/front-end/php/menu_digitale/new_prodouct.php");
        exit;
    }

    // Prepare SQL statement to insert new product
    $sql = "INSERT INTO menu (nome, nome_inglese, prezzo, tipo, ingredienti_it, ingredienti_en, extra, visibile)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsssis", $nome, $nome_inglese, $prezzo, $tipo, $ingredienti_it, $ingredienti_en, $extra, $visibile);
    
    // Execute the statement
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Nuovo prodotto aggiunto con successo al menu digitale!";
    } else {
        $_SESSION['error_message'] = "Errore durante l'aggiunta del prodotto: " . $conn->error;
    }
    
    // Close statement
    $stmt->close();
    
    // Redirect back to the form page
    header("Location: ../../../../admin/front-end/php/menu_digitale/new_prodouct.php");
    exit;
} else {
    // If not a POST request, redirect to the form page
    header("Location: ../../../../admin/front-end/php/menu_digitale/new_prodouct.php");
    exit;
}
?>