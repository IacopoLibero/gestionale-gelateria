<?php
session_start();
require_once '../connessione.php';

// Delete only the current device's "remember me" token from database if it exists
if (isset($_COOKIE['remember_user'])) {
    $token = $_COOKIE['remember_user'];
    
    // Remove only the specific token from database
    $stmt = $conn->prepare("DELETE FROM utente_remember WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    
    // Delete the cookie
    setcookie("remember_user", "", time() - 3600, "/"); // Set expiration time in the past
}

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: ../index.php");
exit;
?>
