<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionale Gelateria - Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./css/dashboard.css">
</head>
<body>
    <header>
        <h1>Gestionale Gelateria</h1>
        <div>
            <span>Benvenuto, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="../login/logout.php" class="logout-btn">Logout</a>
        </div>
    </header>
    
    <div class="content-wrapper">
        <!-- Toggle button for mobile -->
        <button class="sidebar-toggle" id="sidebar-toggle">
            <i class="fas fa-bars"></i>
        </button>
        
        <!-- Sidebar menu -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-section">
                <h3>Menu Monitor Gelateria</h3>
                <ul>
                    <li><a href="#">Catalogo Prodotti</a></li>
                    <li><a href="#">Nuovo Prodotto</a></li>
                    <li><a href="#">Menu Verticale</a></li>
                </ul>
            </div>
            
            <div class="sidebar-section">
                <h3>Spot Monitor Gelateria</h3>
                <ul>
                    <li><a href="#">Catalogo Spot</a></li>
                    <li><a href="#">Nuovo Spot</a></li>
                </ul>
            </div>
            
            <div class="sidebar-section">
                <h3>Menu Digitale</h3>
                <ul>
                    <li><a href="#">Catalogo Digitale</a></li>
                    <li><a href="#">Nuovo Prodotto</a></li>
                    <li><a href="#">Menu Digitale</a></li>
                </ul>
            </div>
        </div>
        
        <div class="dashboard">
            <div class="welcome-message">
                <h2>Dashboard Amministratore</h2>
                <p>Benvenuto nel sistema di gestione della gelateria. Da qui puoi gestire i prodotti, gli ordini e i clienti.</p>
            </div>
            
            <!-- Here you will add your dashboard components -->
        </div>
    </div>
    
    <script src="./js/dashboard.js"></script>
</body>
</html>
