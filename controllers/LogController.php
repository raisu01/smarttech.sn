<?php
require_once '../models/LogModel.php';

class LogController {
    private $logModel;

    public function __construct() {
        $this->logModel = new LogModel();
    }

    public function getLogs() {
        echo json_encode($this->logModel->getAllLogs());
    }

    public function createLog() {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($this->logModel->createLog($data['user_id'], $data['action'])) {
            echo json_encode(["message" => "Log ajouté"]);
        } else {
            echo json_encode(["message" => "Erreur lors de l'ajout"]);
        }
    }
}

// Routeur
$logController = new LogController();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $logController->getLogs();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $logController->createLog();
}
?>
