<?php
session_start();

require_once '../../../../../../connessione.php';

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Sanitize and validate input data
        $nome_ita = trim(htmlspecialchars($_POST['nome_ita']));
        $nome_eng = trim(htmlspecialchars($_POST['nome_eng']));
        $ingredienti = trim(htmlspecialchars($_POST['ingredienti']));
        $tipo = trim(htmlspecialchars($_POST['tipo']));
        
        // Handle checkboxes (which will only be present if checked)
        $ingredienti_visibili = isset($_POST['ingredienti_monitor']) ? 1 : 0;
        $km0 = isset($_POST['Km0']) ? 1 : 0;
        $vegano = isset($_POST['Vegano']) ? 1 : 0;
        $slowFood = isset($_POST['Slow_Food']) ? 1 : 0;
        $innovativo = isset($_POST['Innovativo']) ? 1 : 0;
        $bio = isset($_POST['Bio']) ? 1 : 0;
        $stato = isset($_POST['Visibile']) ? 1 : 0;
        
        // Validate required fields
        if (empty($nome_ita) || empty($nome_eng) || empty($ingredienti) || empty($tipo)) {
            throw new Exception("Tutti i campi obbligatori devono essere compilati.");
        }
        
        // Validate product type
        if (!in_array($tipo, ['gelato', 'granita', 'semifreddo'])) {
            throw new Exception("Tipo di prodotto non valido.");
        }
        
        // Prepare SQL statement to insert data
        $sql = "INSERT INTO prodotto (nome, nome_inglese, ingredienti, tipo, km0, vegano, SlowFood, bio, innovativo, ingredienti_visibili, stato) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssiiiiiis", $nome_ita, $nome_eng, $ingredienti, $tipo, $km0, $vegano, $slowFood, $bio, $innovativo, $ingredienti_visibili, $stato);
        
        // Execute query
        if ($stmt->execute()) {
            // Success message
            $_SESSION['success_message'] = "Prodotto aggiunto con successo!";
        } else {
            throw new Exception("Errore nell'inserimento del prodotto: " . $stmt->error);
        }
        
        // Close statement
        $stmt->close();
        
    } catch (Exception $e) {
        // Error message
        $_SESSION['error_message'] = $e->getMessage();
    }
    
    // Close connection
    $conn->close();
    
    // Redirect back to the form page
    header("Location: ../../../front-end/php/schermo/new_prodouct.php");
    exit;
}
?>
