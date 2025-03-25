<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Utente non autenticato']);
    exit;
}

// Include database connection
require_once "../../../../connessione.php";

// Set header for JSON response
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
    
    // For Altervista hosting, use absolute path without DOCUMENT_ROOT which might be incorrect
    $uploadDir = dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/img/video/';
    
    // Generate unique filename
    $fileExtension = strtolower(pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION));
    $uniqueFilename = uniqid() . '.' . $fileExtension;
    $uploadPath = $uploadDir . $uniqueFilename;
    
    // Attempt to upload the file
    if (move_uploaded_file($_FILES['video']['tmp_name'], $uploadPath)) {
        // Save to database with the correct relative path for your hosting
        $relativePath = '/img/video/' . $uniqueFilename;
        
        try {
            $stmt = $conn->prepare("INSERT INTO spot (nome, percorso_video) VALUES (?, ?)");
            if (!$stmt) {
                throw new Exception("Errore nella preparazione della query: " . $conn->error);
            }
            
            $stmt->bind_param("ss", $nome, $relativePath);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Spot aggiunto con successo']);
            } else {
                throw new Exception("Errore durante l'esecuzione della query: " . $stmt->error);
            }
            
            $stmt->close();
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        // Debug info for upload failures
        $errorDetails = error_get_last();
        $debugInfo = [
            'tmp_name' => $_FILES['video']['tmp_name'],
            'target_path' => $uploadPath,
            'upload_dir' => $uploadDir,
            'file_exists' => file_exists($_FILES['video']['tmp_name']) ? 'Sì' : 'No',
            'dir_exists' => file_exists($uploadDir) ? 'Sì' : 'No',
            'dir_writable' => is_writable($uploadDir) ? 'Sì' : 'No',
            'error' => $errorDetails ? $errorDetails['message'] : 'Nessun errore specifico rilevato'
        ];
        
        echo json_encode([
            'success' => false, 
            'message' => 'Errore durante il caricamento del file',
            'debug' => $debugInfo
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Metodo di richiesta non valido']);
}

$conn->close();
?>
