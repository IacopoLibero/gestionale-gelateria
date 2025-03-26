<?php
session_start();
require_once '../../../connessione.php';

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../../../index.php");
    exit;
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = isset($_POST['action']) ? $_POST['action'] : 'change_password';
    $username = $_SESSION['username'];
    
    // Handle username change
    if ($action === 'change_username') {
        $new_username = isset($_POST['new_username']) ? trim($_POST['new_username']) : '';
        
        // Validate username
        if (empty($new_username)) {
            $_SESSION['error_message'] = "Il nome utente non può essere vuoto.";
            header("Location: ../../dashboard.php");
            exit;
        }
        
        // Check if username already exists
        $check_sql = "SELECT username FROM utente WHERE username = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $new_username);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows > 0) {
            $_SESSION['error_message'] = "Questo nome utente è già in uso. Scegline un altro.";
            header("Location: ../../dashboard.php");
            exit;
        }
        
        // Start transaction to update both tables
        $conn->begin_transaction();
        
        try {
            // Update username in the utente table
            $user_sql = "UPDATE utente SET username = ? WHERE username = ?";
            $user_stmt = $conn->prepare($user_sql);
            $user_stmt->bind_param("ss", $new_username, $username);
            $user_stmt->execute();
            
            // Update username in the remember_tokens table
            $token_sql = "UPDATE remember_tokens SET username = ? WHERE username = ?";
            $token_stmt = $conn->prepare($token_sql);
            $token_stmt->bind_param("ss", $new_username, $username);
            $token_stmt->execute();
            
            // Commit the transaction
            $conn->commit();
            
            // Update session with new username
            $_SESSION['username'] = $new_username;
            
            $_SESSION['success_message'] = "Nome utente aggiornato con successo!";
        } catch (Exception $e) {
            // Rollback on error
            $conn->rollback();
            
            $_SESSION['error_message'] = "Si è verificato un errore durante l'aggiornamento del nome utente.";
        }
        
        // Redirect back to dashboard
        header("Location: ../../dashboard.php");
        exit;
    }
    
    // Handle password change
    if ($action === 'change_password') {
        $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
        $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
        
        // Validate passwords
        if (empty($new_password) || empty($confirm_password)) {
            $_SESSION['error_message'] = "Per favore compila tutti i campi.";
            header("Location: ../../dashboard.php");
            exit;
        }
        
        // Check if passwords match
        if ($new_password !== $confirm_password) {
            $_SESSION['error_message'] = "Le password non corrispondono.";
            header("Location: ../../dashboard.php");
            exit;
        }
        
        // Hash the password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Update the password in the database
        $sql = "UPDATE utente SET password = ? WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashed_password, $username);
        
        if ($stmt->execute()) {
            // Password updated successfully
            $_SESSION['success_message'] = "Password aggiornata con successo!";
        } else {
            // Error updating password
            $_SESSION['error_message'] = "Si è verificato un errore durante l'aggiornamento della password.";
        }
        
        // Redirect back to dashboard
        header("Location: ../../dashboard.php");
        exit;
    }
    
} else {
    // If accessed directly without POST data
    header("Location: ../../dashboard.php");
    exit;
}
?>
