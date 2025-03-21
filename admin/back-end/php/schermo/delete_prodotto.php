<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../../../front-end/php/index.php");
    exit;
}

// Check if ID is provided via POST
if (!isset($_POST['id']) || empty($_POST['id'])) {
    $_SESSION['error_message'] = "ID del prodotto non specificato!";
    header("Location: ../../../front-end/php/schermo/catalogo_prodotti.php");
    exit;
}

// Include database connection
require_once '../../../../../connessione.php';

$id = intval($_POST['id']);

// Prepare delete statement
$sql = "DELETE FROM prodotto WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

// Execute and check result
if ($stmt->execute()) {
    $_SESSION['success_message'] = "Prodotto eliminato con successo!";
} else {
    $_SESSION['error_message'] = "Errore durante l'eliminazione del prodotto: " . $conn->error;
}

// Close statement and connection
$stmt->close();
$conn->close();

// Redirect back to catalog
header("Location: ../../../front-end/php/schermo/catalogo_prodotti.php");
exit;
?>
