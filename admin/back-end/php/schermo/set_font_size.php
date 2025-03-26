<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../../../index.php");
    exit;
}

// Include database connection
require_once '../../../../connessione.php';

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $font_size = isset($_POST['font_size']) ? intval($_POST['font_size']) : 200;
    
    // Ensure font size is within reasonable limits
    if ($font_size < 100) $font_size = 100;
    if ($font_size > 400) $font_size = 400;
    
    // Update the font_size in the user's record
    $sql = "UPDATE utente SET font_size = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $font_size, $_SESSION['username']);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Dimensione del carattere aggiornata con successo!";
    } else {
        $_SESSION['error_message'] = "Errore durante l'aggiornamento della dimensione del carattere: " . $conn->error;
    }
    
    $stmt->close();
    $conn->close();
    
    // Redirect back to catalog page
    header("Location: ../../../front-end/php/schermo/catalogo_prodotti.php");
    exit;
} else {
    // If accessed directly without POST data
    header("Location: ../../../front-end/php/schermo/catalogo_prodotti.php");
    exit;
}
?>
