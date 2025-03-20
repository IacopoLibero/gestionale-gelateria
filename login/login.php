<?php
session_start();
require_once '../connessione.php';

// Controlla se il form è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Recupera i dati del form
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    // Valida gli input
    if (empty($username) || empty($password)) {
        header("Location: ../index.php?error=emptyfields");
        exit();
    }
    
    // Query semplice per verificare username e password in chiaro
    $sql = "SELECT * FROM utente WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        // Login riuscito
        $_SESSION['username'] = $username;
        $_SESSION['loggedin'] = true;
        
        // Reindirizza alla dashboard
        header("Location: ../admin/dashboard.php");
        exit();
    } else {
        // Login fallito - verifica se l'username esiste per dare un errore specifico
        $sql_check_user = "SELECT * FROM utente WHERE username = ?";
        $stmt_check = $conn->prepare($sql_check_user);
        $stmt_check->bind_param("s", $username);
        $stmt_check->execute();
        $check_result = $stmt_check->get_result();
        
        if ($check_result->num_rows === 1) {
            // Username esiste, quindi la password è sbagliata
            header("Location: ../index.php?error=wrongpassword");
        } else {
            // Username non esiste
            header("Location: ../index.php?error=usernotfound");
        }
        exit();
    }
} else {
    // Se acceduto direttamente senza invio del form
    header("Location: ../index.php");
    exit();
}
?>
