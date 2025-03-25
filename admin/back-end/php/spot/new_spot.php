<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit;
}

// Include database connection
require_once "../../../../connessione.php";

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
        $_SESSION['error_message'] = 'Il nome dello spot è obbligatorio';
        header("Location: ../../../front-end/php/spot/new_spot.php");
        exit;
    }
    
    $nome = htmlspecialchars(trim($_POST['nome']));
    
    // Validate video file
    if (!isset($_FILES['video']) || $_FILES['video']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error_message'] = 'File video non caricato o errore durante l\'upload';
        header("Location: ../../../front-end/php/spot/new_spot.php");
        exit;
    }
    
    if (!isValidVideoFile($_FILES['video'])) {
        $_SESSION['error_message'] = 'Formato file non supportato. Utilizzare MP4, MOV, AVI, WMV, FLV o WEBM';
        header("Location: ../../../front-end/php/spot/new_spot.php");
        exit;
    }
    
    // Define upload directory using the manually created path
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/img/video/';
    
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
            $_SESSION['success_message'] = 'Spot aggiunto con successo';
            header("Location: ../../../front-end/php/spot/new_spot.php");
        } else {
            $_SESSION['error_message'] = 'Errore durante il salvataggio nel database: ' . $stmt->error;
            header("Location: ../../../front-end/php/spot/new_spot.php");
        }
        
        $stmt->close();
    } else {
        $_SESSION['error_message'] = 'Errore durante il caricamento del file';
        header("Location: ../../../front-end/php/spot/new_spot.php");
    }
} else {
    $_SESSION['error_message'] = 'Metodo di richiesta non valido';
    header("Location: ../../../front-end/php/spot/new_spot.php");
}

$conn->close();
exit;
?>
