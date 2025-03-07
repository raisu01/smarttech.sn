<?php
require_once '../config/database.php';

class DocumentModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function getAllDocuments() {
        $query = "SELECT * FROM documents";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getDocumentById($id) {
        $query = "SELECT * FROM documents WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function createDocument($employee_id, $client_id, $file_name, $file_path) {
        $query = "INSERT INTO documents (employee_id, client_id, file_name, file_path) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiss", $employee_id, $client_id, $file_name, $file_path);
        return $stmt->execute();
    }

    public function deleteDocument($id) {
        // Récupérer le fichier pour le supprimer du serveur
        $query = "SELECT file_path FROM documents WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $document = $result->fetch_assoc();

        if (!$document) {
            return ["success" => false, "message" => "Le document n'existe pas."];
        }

        $filePath = $document['file_path'];

        // Supprimer le fichier du serveur
        if (file_exists($filePath)) {
            if (!unlink($filePath)) {
                return ["success" => false, "message" => "Échec de la suppression du fichier."];
            }
        }

        // Supprimer l'entrée en base de données
        $deleteQuery = "DELETE FROM documents WHERE id = ?";
        $stmt = $this->conn->prepare($deleteQuery);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            return ["success" => true, "message" => "Document supprimé avec succès."];
        } else {
            return ["success" => false, "message" => "Échec de la suppression en base de données."];
        }
    }
}
?>
