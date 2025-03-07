<?php
require_once '../config/database.php';

class ClientModels {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function getAllClients() {
        $query = "SELECT * FROM clients";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getClientById($id) {
        $query = "SELECT * FROM clients WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function createClient($company_name, $contact_person, $email, $phone, $address) {
        $query = "INSERT INTO clients (company_name, contact_person, email, phone, address) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssss", $company_name, $contact_person, $email, $phone, $address);
        return $stmt->execute();
    }
    public function deleteClient($id) {
        $query = "DELETE FROM clients WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>
