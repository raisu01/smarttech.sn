<?php
require_once '../config/database.php';

class EmployeeModels {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    // ✅ Récupérer tous les employés
    public function getAllEmployees() {
        $query = "SELECT * FROM employees";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // ✅ Récupérer un employé par son ID
    public function getEmployeeById($id) {
        $query = "SELECT * FROM employees WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // ✅ Créer un employé (adapté au contrôleur)
    public function createEmployee($name, $email, $phone, $position) {
        $query = "INSERT INTO employees (name, email, phone, position) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssss", $name, $email, $phone, $position);
        return $stmt->execute();
    }

    // ✅ Supprimer un employé
    public function deleteEmployee($id) {
        $query = "DELETE FROM employees WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
