<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../../../../index.php");
    exit;
}

// Include database connection
require_once '../../../../../connessione.php';

// Ensure the form was submitted and is the category form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_type']) && $_POST['form_type'] == 'category') {
    try {
        // Sanitize input
        $nome_ita = htmlspecialchars(trim($_POST['category_nome_ita']));
        $nome_eng = htmlspecialchars(trim($_POST['category_nome_eng']));
        
        // Validate required fields
        if (empty($nome_ita) || empty($nome_eng)) {
            throw new Exception("Tutti i campi obbligatori devono essere compilati.");
        }
        
        // Check if category already exists
        $check_sql = "SELECT id FROM categoria WHERE nome = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $nome_ita);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            throw new Exception("Questa categoria esiste già nel sistema.");
        }
        
        // Insert new category
        $sql = "INSERT INTO categoria (nome, nome_inglese) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $nome_ita, $nome_eng);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Categoria aggiunta con successo!";
        } else {
            throw new Exception("Errore nell'inserimento della categoria: " . $stmt->error);
        }
        
        // Close statements
        $check_stmt->close();
        $stmt->close();
        
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
    }
    
    // Close connection
    $conn->close();
    
    // Redirect back to the form page
    header("Location: ../../../../front-end/php/schermo/new_category.php");
    exit;
}
?>
