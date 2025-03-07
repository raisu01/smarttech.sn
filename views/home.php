<?php
session_start(); // Démarre la session

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit; // Arrête l'exécution du script
}

$content = '
    <div class="text-center">
        <h1 class="text-3xl font-bold text-blue-600">Bienvenue sur SmartTech</h1>
        <p class="text-gray-700 mt-2">Plateforme de gestion des employés, clients et documents.</p>
        <a href="dashboard.php" class="mt-4 inline-block bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition">
            Accéder au Dashboard
        </a>
    </div>
';
include 'layout.php';
?>
