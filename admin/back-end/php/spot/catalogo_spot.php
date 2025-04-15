<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../../../../index.php");
    exit;
}

// Include database connection
require_once '../../../../connessione.php';

// Handle spot interval settings update
if (isset($_POST['update_settings'])) {
    $spot_interval = intval($_POST['spot_interval']);
    $spot_active = isset($_POST['spot_active']) ? 1 : 0;
    
    // Update user settings
    $username = $_SESSION['username'];
    
    $update_sql = "UPDATE utente SET spot_interval = ?, spot_active = ? WHERE username = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("iis", $spot_interval, $spot_active, $username);
    
    if ($update_stmt->execute()) {
        $_SESSION['success_message'] = "Impostazioni aggiornate con successo";
    } else {
        $_SESSION['error_message'] = "Errore durante l'aggiornamento delle impostazioni: " . $update_stmt->error;
    }
    
    $update_stmt->close();
    header("Location: ../../../front-end/php/spot/catalogo_spot.php");
    exit;
}

// Toggle spot visibility
if (isset($_POST['toggle_visibility']) && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $visibile = intval($_POST['visible']) ? 0 : 1; // Toggle current value
    
    $toggle_sql = "UPDATE spot SET visibile = ? WHERE id = ?";
    $toggle_stmt = $conn->prepare($toggle_sql);
    $toggle_stmt->bind_param("ii", $visibile, $id);
    
    if ($toggle_stmt->execute()) {
        $_SESSION['success_message'] = "Stato dello spot aggiornato con successo";
    } else {
        $_SESSION['error_message'] = "Errore durante l'aggiornamento dello stato: " . $toggle_stmt->error;
    }
    
    $toggle_stmt->close();
    header("Location: ../../../front-end/php/spot/catalogo_spot.php");
    exit;
}

// Delete spot if requested
if (isset($_POST['delete_spot']) && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    
    // First get the file path to delete the file
    $sql = "SELECT percorso_video FROM spot WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $filePath = $_SERVER['DOCUMENT_ROOT'] . $row['percorso_video'];
        
        // Delete the spot from database
        $delete_sql = "DELETE FROM spot WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $id);
        
        if ($delete_stmt->execute()) {
            // Try to delete the file
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $_SESSION['success_message'] = "Spot eliminato con successo";
        } else {
            $_SESSION['error_message'] = "Errore durante l'eliminazione dello spot: " . $delete_stmt->error;
        }
        
        $delete_stmt->close();
    } else {
        $_SESSION['error_message'] = "Spot non trovato";
    }
    
    $stmt->close();
    header("Location: ../../../front-end/php/spot/catalogo_spot.php");
    exit;
}
?>
