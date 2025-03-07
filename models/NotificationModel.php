<?php
require_once '../config/database.php';

class NotificationModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function getAllNotifications() {
        $query = "SELECT * FROM notifications";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function createNotification($user_id, $email, $subject, $message) {
        $query = "INSERT INTO notifications (user_id, email, subject, message) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("isss", $user_id, $email, $subject, $message);
        return $stmt->execute();
    }
}
?>
