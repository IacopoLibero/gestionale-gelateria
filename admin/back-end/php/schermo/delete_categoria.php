<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../../../../index.php");
    exit;
}

// Include database connection
require_once '../../../../../connessione.php';

// Check if ID is provided and is a valid number
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = intval($_POST['id']);
    
    try {
        // First check if there are any products using this category
        $check_sql = "SELECT COUNT(*) as count FROM prodotto WHERE tipo = (SELECT nome FROM categoria WHERE id = ?)";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row['count'] > 0) {
            $_SESSION['error_message'] = "Non puoi eliminare questa categoria perché ci sono prodotti associati.";
        } else {
            // Delete the category if no products are using it
            $sql = "DELETE FROM categoria WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Categoria eliminata con successo!";
            } else {
                $_SESSION['error_message'] = "Errore durante l'eliminazione della categoria: " . $stmt->error;
            }
            
            $stmt->close();
        }
        
        $check_stmt->close();
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Errore: " . $e->getMessage();
    }
} else {
    $_SESSION['error_message'] = "ID categoria non valido.";
}

// Close connection
$conn->close();

// Redirect back to catalog
header("Location: ../../../../front-end/php/schermo/catalogo_categorie.php");
exit;
?>
