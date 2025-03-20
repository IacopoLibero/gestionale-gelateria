<?php
session_start();

// Check for "remember me" cookie
if (!isset($_SESSION['loggedin']) && isset($_COOKIE['remember_user'])) {
    require_once 'connessione.php';
    
    $token = $_COOKIE['remember_user'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $ip_address = $_SERVER['REMOTE_ADDR'];
    
    // Check if token exists and matches current browser/IP
    $stmt = $conn->prepare("SELECT username FROM utente_remember WHERE token = ? AND user_agent = ? AND ip_address = ?");
    $stmt->bind_param("sss", $token, $user_agent, $ip_address);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        
        // Set session variables
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $row['username'];
        
        // Renew the cookie
        setcookie("remember_user", $token, time() + (86400 * 30), "/");
    }
}

// Redirect se l'utente è già loggato
if (isset($_SESSION['username']) && isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: admin/dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionale Gelateria - Login</title>
    <link rel="stylesheet" href="./login/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <img src="img/logo.png" alt="logo">
            <h1>David la Gelateria</h1>
        </div>
        
        <form class="form" method="POST" action="login/login.php">
            <!-- Changed to submit to login.php instead of itself -->
            
            <div class="title">Bentornato gelataio!<br><span>accedi al gestionale</span></div>
            
            <?php
            // Visualizza messaggi di errore
            if (isset($_GET['error'])) {
                $error = $_GET['error'];
                $message = "";
                
                switch ($error) {
                    case 'emptyfields':
                        $message = "Compila tutti i campi!";
                        break;
                    case 'wrongpassword':
                        $message = "Password errata!";
                        break;
                    case 'usernotfound':
                        $message = "Utente non trovato!";
                        break;
                    default:
                        $message = "Si è verificato un errore!";
                }
                
                echo '<div class="error-message">' . $message . '</div>';
            }
            ?>
            
            <input class="input" type="text" name="username" placeholder="Username" required>
            <input class="input" type="password" name="password" placeholder="Password" required>
            
            <div class="checkbox-wrapper">
                <input type="checkbox" class="check" id="remember" name="remember">
                <label for="remember" class="label">
                    <svg width="25" height="25" viewBox="0 0 95 95">
                      <rect x="30" y="20" width="50" height="50" stroke="black" fill="none"></rect>
                      <g transform="translate(0,-952.36222)">
                        <path d="m 56,963 c -102,122 6,9 7,9 17,-5 -66,69 -38,52 122,-77 -7,14 18,4 29,-11 45,-43 23,-4" stroke="black" stroke-width="3" fill="none" class="path1"></path>
                      </g>
                    </svg>
                    <span>Ricordami</span>
                </label>
            </div>
            
            <button type="submit" class="button-confirm">Accedi →</button>
        </form>
    </div>
</body>
</html>
