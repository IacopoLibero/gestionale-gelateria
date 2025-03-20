<?php
session_start();
require_once '../connessione.php';

// Controlla se il form è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Recupera i dati del form
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    // Valida gli input
    if (empty($username) || empty($password)) {
        header("Location: ../index.php?error=emptyfields");
        exit();
    }
    
    // Query per ottenere l'utente e la sua password hashata
    $sql = "SELECT * FROM utente WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verifica la password utilizzando password_verify
        if (password_verify($password, $user['password'])) {
            // Login riuscito
            $_SESSION['username'] = $username;
            $_SESSION['loggedin'] = true;
            
            // Imposta un timestamp di login per scopi di sicurezza
            $_SESSION['login_time'] = time();
            
            // Reindirizza alla dashboard
            header("Location: ../admin/dashboard.php");
            exit();
        } else {
            // Password errata
            header("Location: ../index.php?error=wrongpassword");
            exit();
        }
    } else {
        // Username non trovato
        header("Location: ../index.php?error=usernotfound");
        exit();
    }
} else {
    // Se acceduto direttamente senza invio del form
    header("Location: ../index.php");
    exit();
}
?>
