<?php
require_once '../models/DocumentModel.php';
require_once '../services/FtpService.php';

class DocumentController {
    private $documentModel;
    private $ftpService;

    public function __construct() {
        $this->documentModel = new DocumentModel();
        $this->ftpService = new FtpService();
    }

    public function getDocuments() {
        return json_encode($this->documentModel->getAllDocuments());
    }
    

    public function getDocumentById($id) {
        return json_encode($this->documentModel->getDocumentById($id));
    }

    public function createDocument() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["document"])) {
            $file = $_FILES["document"];
            $filename = basename($file["name"]);
            $localFile = $file["tmp_name"];
            $remoteFile = "/ftp/" . $filename;
            $employee_id = 1;
            $client_id = 1;
        
            $result = $this->ftpService->uploadFile($localFile, $remoteFile);
            if ($result['success']) {
                $this->documentModel->createDocument($employee_id, $client_id, $filename, $remoteFile);
                $_SESSION['message'] = "✅ Document ajouté avec succès !";
            } else {
                $_SESSION['error'] = "❌ Erreur: " . $result['message'];
            }
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }

    public function deleteDocument() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["document_id"])) {
            $id = $_POST["document_id"];
            $doc = $this->documentModel->getDocumentById($id);

            if (!$doc) {
                $_SESSION['error'] = "❌ Document introuvable.";
            } else {
                $result = $this->ftpService->deleteFile($doc["file_path"]);
                if ($result['success']) {
                    $this->documentModel->deleteDocument($id);
                    $_SESSION['message'] = "✅ Document supprimé avec succès !";
                } else {
                    $_SESSION['error'] = "❌ Erreur: " . $result['message'];
                }
            }
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
}

// Routeur
$documentController = new DocumentController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $documentController->getDocumentById($_GET['id']);
    } else {
        $documentController->getDocuments();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['document'])) {
        $documentController->createDocument();
    } elseif (isset($_POST['document_id'])) {
        $documentController->deleteDocument();
    }
} else {
    echo json_encode(["success" => false, "message" => "Méthode non autorisée."]);
}