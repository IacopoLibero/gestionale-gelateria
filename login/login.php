<?php
session_start();
require_once '../connessione.php';

// Se l'utente è già loggato, redirect alla dashboard
if (isset($_SESSION['username']) && isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: ../admin/dashboard.php");
    exit();
}

// Controlla se c'è un cookie remember me e non c'è una sessione attiva
if (!isset($_SESSION['username']) && isset($_COOKIE['remember_user'])) {
    $token = $_COOKIE['remember_user'];
    
    // Query per verificare se il token è valido
    $stmt = $conn->prepare("SELECT username FROM utente_remember WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $row['username'];
        $_SESSION['loggedin'] = true;
        header("Location: ../admin/dashboard.php");
        exit();
    }
}

// Verifica se sono state inviate credenziali (funziona sia con POST che come fallback)
if (isset($_REQUEST['username']) && isset($_REQUEST['password'])) {
    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];
    $remember = isset($_REQUEST['remember']) ? true : false;
    
    // Cerca l'utente nel database
    $stmt = $conn->prepare("SELECT username, password FROM utente WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verifica la password
        if (password_verify($password, $row['password'])) {
            // Password corretta, crea sessione
            $_SESSION['username'] = $row['username'];
            $_SESSION['loggedin'] = true;
            
            // Se "ricordami" è selezionato, crea un cookie persistente
            if ($remember) {
                // Genera un token univoco
                $token = bin2hex(random_bytes(32));
                
                // Ottieni informazioni sul dispositivo
                $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
                $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
                
                // Salva il token nel database con informazioni sul dispositivo
                $stmt = $conn->prepare("INSERT INTO utente_remember (username, token, user_agent, ip_address) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $username, $token, $user_agent, $ip_address);
                $stmt->execute();
                
                // Imposta cookie per 30 giorni
                setcookie("remember_user", $token, time() + (86400 * 30), "/", "", false, true);
            }
            
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
}
?>
