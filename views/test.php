<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../services/FtpService.php'; // Inclure la classe FtpService

// Démarrer la session pour afficher les messages d'erreur ou de succès
session_start();

// Initialiser les variables
$uploadMessage = '';
$deleteMessage = '';

// Vérifier si un fichier a été soumis pour upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $ftpService = new FtpService();

    $localFile = $_FILES['file']['tmp_name']; // Chemin temporaire du fichier uploadé
    $remoteFile = '/ftp/' . basename($_FILES['file']['name']); // Chemin distant sur le serveur FTP

    // Téléverser le fichier
    $result = $ftpService->uploadFile($localFile, $remoteFile);
    if ($result['success']) {
        $uploadMessage = $result['message'];
    } else {
        $uploadMessage = $result['message'];
    }
}

// Vérifier si un fichier doit être supprimé
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_file'])) {
    $ftpService = new FtpService();

    $remoteFile = $_POST['delete_file']; // Chemin distant du fichier à supprimer

    // Supprimer le fichier
    $result = $ftpService->deleteFile($remoteFile);
    if ($result['success']) {
        $deleteMessage = $result['message'];
    } else {
        $deleteMessage = $result['message'];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test FTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        form {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Test de connexion FTP</h1>

    <!-- Formulaire pour uploader un fichier -->
    <form action="" method="post" enctype="multipart/form-data">
        <h2>Uploader un fichier</h2>
        <input type="file" name="file" required>
        <button type="submit">Uploader</button>
    </form>

    <!-- Afficher le résultat de l'upload -->
    <?php if ($uploadMessage): ?>
        <div class="message <?php echo strpos($uploadMessage, '✅') !== false ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars($uploadMessage); ?>
        </div>
    <?php endif; ?>

    <!-- Formulaire pour supprimer un fichier -->
    <form action="" method="post">
        <h2>Supprimer un fichier</h2>
        <label for="delete_file">Chemin du fichier sur le serveur FTP :</label>
        <input type="text" name="delete_file" id="delete_file" placeholder="/uploads/fichier.txt" required>
        <button type="submit">Supprimer</button>
    </form>

    <!-- Afficher le résultat de la suppression -->
    <?php if ($deleteMessage): ?>
        <div class="message <?php echo strpos($deleteMessage, '✅') !== false ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars($deleteMessage); ?>
        </div>
    <?php endif; ?>
</body>
</html>