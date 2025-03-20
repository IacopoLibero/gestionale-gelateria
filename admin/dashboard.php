<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionale Gelateria - Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        
        .dashboard {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background-color: #4a90e2;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .welcome-message {
            margin-top: 20px;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .logout-btn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <h1>Gestionale Gelateria</h1>
        <div>
            <span>Benvenuto, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="../login/logout.php" class="logout-btn">Logout</a>
        </div>
    </header>
    
    <div class="dashboard">
        <div class="welcome-message">
            <h2>Dashboard Amministratore</h2>
            <p>Benvenuto nel sistema di gestione della gelateria. Da qui puoi gestire i prodotti, gli ordini e i clienti.</p>
        </div>
        
        <!-- Here you will add your dashboard components -->
    </div>
</body>
</html>
