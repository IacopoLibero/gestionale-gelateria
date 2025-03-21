<?php
session_start();
require_once '../connessione.php';

// Se c'è un utente loggato e un cookie remember_me, elimina solo quel token specifico
if (isset($_SESSION['username']) && isset($_COOKIE['remember_me'])) {
    $token = $_COOKIE['remember_me'];
    
    // Elimina il token specifico dal database
    $sql = "DELETE FROM remember_tokens WHERE username = ? AND token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $_SESSION['username'], $token);
    $stmt->execute();
    
    // Rimuovi il cookie
    setcookie("remember_me", "", time() - 3600, "/");
}

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: ../index.php");
exit;
?>
