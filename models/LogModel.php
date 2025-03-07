<?php
require_once '../config/database.php';

class LogModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function getAllLogs() {
        $query = "SELECT * FROM logs";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function createLog($user_id, $action) {
        $query = "INSERT INTO logs (user_id, action) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("is", $user_id, $action);
        return $stmt->execute();
    }
}
?>

