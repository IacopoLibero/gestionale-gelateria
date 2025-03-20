<?php
session_start();
require_once '../connessione.php'; // Include database connection

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize input data
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $remember = isset($_POST['remember']) ? true : false;
    
    // Validate form fields
    if (empty($username) || empty($password)) {
        header("Location: ../index.php?error=emptyfields");
        exit();
    }
    
    // Check if user exists in database
    $stmt = $conn->prepare("SELECT username, password FROM utente WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Password is correct, start a new session
            session_regenerate_id(true); // For security, regenerate session ID
            
            // Set session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user['username'];
            
            // Handle "Remember Me" functionality
            if ($remember) {
                // Generate a secure token
                $token = bin2hex(random_bytes(32)); // 64 characters long
                
                // Get client info for additional security
                $user_agent = $_SERVER['HTTP_USER_AGENT'];
                $ip_address = $_SERVER['REMOTE_ADDR'];
                
                // Store token in database
                $stmt = $conn->prepare("INSERT INTO utente_remember (username, token, user_agent, ip_address) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $username, $token, $user_agent, $ip_address);
                $stmt->execute();
                
                // Set cookie that expires in 30 days
                setcookie("remember_user", $token, time() + (86400 * 30), "/"); // 86400 = 1 day
            }
            
            // Redirect to dashboard
            header("Location: ../admin/dashboard.php");
            exit();
        } else {
            // Incorrect password
            header("Location: ../index.php?error=wrongpassword");
            exit();
        }
    } else {
        // User not found
        header("Location: ../index.php?error=usernotfound");
        exit();
    }
} else {
    // If not POST request, redirect to login page
    header("Location: ../index.php");
    exit();
}
?>
