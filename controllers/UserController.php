<?php
require_once '../models/UserModel.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function getUsers() {
        echo json_encode($this->userModel->getAllUsers());
    }

    public function getUserByUsername($username) {
        echo json_encode($this->userModel->getUserByUsername($username));
    }

    public function createUser() {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($this->userModel->createUser($data['username'], $data['password'], $data['email'], $data['role'])) {
            echo json_encode(["message" => "Utilisateur ajouté"]);
        } else {
            echo json_encode(["message" => "Erreur lors de l'ajout"]);
        }
    }
}

// Routeur
$userController = new UserController();
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['username'])) {
    $userController->getUserByUsername($_GET['username']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userController->getUsers();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userController->createUser();
}
?>
