<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../controllers/AuthController.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$authController = new AuthController();

if (isset($_GET['action'])) {
    if ($_GET['action'] === 'login' && $_SERVER["REQUEST_METHOD"] === "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $authController->login($email, $password);
    }

    if ($_GET['action'] === 'register' && $_SERVER["REQUEST_METHOD"] === "POST") {
        $fullName = $_POST['full_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $authController->register($fullName, $email, $password);
    }

    if ($_GET['action'] === 'logout') {
        $authController->logout();
    }
}
?>
