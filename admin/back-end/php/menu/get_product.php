<?php
// Include database connection
require_once '../../../../connessione.php';

// Checking if the request has a valid product ID
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Prepare SQL query to fetch product data
    $sql = "SELECT * FROM menu WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        // Fetch product data and return as JSON
        $product = $result->fetch_assoc();
        header('Content-Type: application/json');
        echo json_encode($product);
    } else {
        // Product not found
        header('HTTP/1.1 404 Not Found');
        echo json_encode(['error' => 'Prodotto non trovato']);
    }
    
    $stmt->close();
} else {
    // Invalid or missing product ID
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'ID prodotto non valido o mancante']);
}

// Close database connection
$conn->close();
?>