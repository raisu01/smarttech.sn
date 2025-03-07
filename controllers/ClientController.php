<?php
require_once '../models/ClientModels.php';

class ClientController {
    private $clientModel;

    public function __construct() {
        $this->clientModel = new ClientModels();
    }

    public function getClients() {
        echo json_encode($this->clientModel->getAllClients());
    }

    public function getClientById($id) {
        echo json_encode($this->clientModel->getClientById($id));
    }

    public function createClient() {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($this->clientModel->createClient($data['company_name'], $data['contact_person'], $data['email'], $data['phone'], $data['address'])) {
            echo json_encode(["message" => "Client ajouté"]);
        } else {
            echo json_encode(["message" => "Erreur lors de l'ajout"]);
        }
    }
}

// Routeur
$clientController = new ClientController();
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $clientController->getClientById($_GET['id']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $clientController->getClients();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientController->createClient();
}
?>
