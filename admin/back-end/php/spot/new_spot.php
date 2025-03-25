<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit;
}

// Include database connection
require_once "../../../../connessione.php";

// Set headers for JSON response
header('Content-Type: application/json');

// Function to validate file extension
function isValidVideoFile($file) {
    $allowedExtensions = ['mp4', 'mov', 'avi', 'wmv', 'flv', 'webm'];
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    return in_array($fileExtension, $allowedExtensions);
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate nome
    if (empty($_POST['nome'])) {
        echo json_encode(['success' => false, 'message' => 'Il nome dello spot è obbligatorio']);
        exit;
    }
    
    $nome = htmlspecialchars(trim($_POST['nome']));
    
    // Validate video file
    if (!isset($_FILES['video']) || $_FILES['video']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'File video non caricato o errore durante l\'upload']);
        exit;
    }
    
    if (!isValidVideoFile($_FILES['video'])) {
        echo json_encode(['success' => false, 'message' => 'Formato file non supportato. Utilizzare MP4, MOV, AVI, WMV, FLV o WEBM']);
        exit;
    }
    
    // Create upload directory if it doesn't exist
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/img/video/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    // Generate unique filename
    $fileExtension = strtolower(pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION));
    $uniqueFilename = uniqid() . '.' . $fileExtension;
    $uploadPath = $uploadDir . $uniqueFilename;
    
    // Move the uploaded file
    if (move_uploaded_file($_FILES['video']['tmp_name'], $uploadPath)) {
        // Save to database
        $relativePath = '/img/video/' . $uniqueFilename;
        
        $stmt = $conn->prepare("INSERT INTO spot (nome, percorso_video) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome, $relativePath);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Spot aggiunto con successo']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Errore durante il salvataggio nel database: ' . $stmt->error]);
        }
        
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Errore durante il caricamento del file']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Metodo di richiesta non valido']);
}

$conn->close();
?>
