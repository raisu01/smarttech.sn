<?php
require_once '../models/NotificationModel.php';

class NotificationController {
    private $notificationModel;

    public function __construct() {
        $this->notificationModel = new NotificationModel();
    }

    public function getNotifications() {
        echo json_encode($this->notificationModel->getAllNotifications());
    }

    public function createNotification() {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($this->notificationModel->createNotification($data['user_id'], $data['email'], $data['subject'], $data['message'])) {
            echo json_encode(["message" => "Notification envoyée"]);
        } else {
            echo json_encode(["message" => "Erreur lors de l'envoi"]);
        }
    }
}

// Routeur
$notificationController = new NotificationController();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $notificationController->getNotifications();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notificationController->createNotification();
}
?>
