<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'navbar.php';
require_once '../controllers/DocumentController.php';

$documentController = new DocumentController();
$message = "";
$error = "";

// Traitement de l'upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["document"])) {
    $documentController->createDocument();
    exit();
}

// Suppression d'un document
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_document"])) {
    $_DELETE = ["id" => $_POST["document_id"]];
    $documentController->deleteDocument();
    exit();
}

$documents = json_decode($documentController->getDocuments(), true);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documents - SmartTech</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-4">Gestion des Documents</h1>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="bg-green-500 text-white p-4 rounded-md mb-4 shadow-md border-l-4 border-green-700">
                <strong>Succès :</strong> <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-500 text-white p-4 rounded-md mb-4 shadow-md border-l-4 border-red-700">
                <strong>Erreur :</strong> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire d'upload -->
       <!-- Bouton pour ajouter un document -->
       <div class="relative inline-block mb-4">
    <button onclick="toggleDropdown()" class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-blue-600 transition">
        + Ajouter un document
    </button>
    <div id="dropdown" class="hidden relative bg-white p-4 shadow-md mt-2 rounded-md w-full max-w-sm">
        <form action="" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md border border-gray-300">
            <label for="document" class="block text-sm font-medium text-gray-700 mb-2">Sélectionner un fichier</label>

            <div class="flex items-center justify-center w-full">
                <label class="flex flex-col items-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-10 h-10 text-gray-400 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V12m0 0V8m0 4h10m-5-5l5 5m-5 5l-5-5m5-5v12"></path>
                        </svg>
                        <p class="text-sm text-gray-600">Cliquez pour ajouter un fichier</p>
                        <p class="text-xs text-gray-500">(PDF, DOCX, JPG, PNG, max 5MB)</p>
                    </div>
                    <input type="file" name="document" id="document" class="hidden" required onchange="updateFileName()">
                </label>
            </div>

            <p id="file-name" class="mt-2 text-sm text-gray-600 text-center"></p>

            <button type="submit" class="w-full bg-green-500 text-white p-3 rounded-lg mt-4 font-semibold text-sm hover:bg-green-600 transition shadow-md">
                📁 Ajouter le fichier
            </button>
        </form>
    </div>
</div>

<script>
    function toggleDropdown() {
        document.getElementById("dropdown").classList.toggle("hidden");
    }

    function updateFileName() {
        const fileInput = document.getElementById('document');
        const fileName = document.getElementById('file-name');

        if (fileInput.files.length > 0) {
            fileName.textContent = "Fichier sélectionné : " + fileInput.files[0].name;
        } else {
            fileName.textContent = "";
        }
    }
</script>


        <!-- Liste des documents -->
        <table class="w-full bg-white shadow-md rounded-lg mt-6">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-3 text-left">Nom du fichier</th>
                    <th class="p-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($documents as $document): ?>
                    <tr class="border-b">
                        <td class="p-3"><?= htmlspecialchars($document['file_name']) ?></td>
                        <td class="p-3 flex space-x-2">
                            <a href="../download.php?file=<?= urlencode($document['file_name']) ?>" class="bg-blue-500 text-white px-2 py-1 rounded">Télécharger</a>
                            <form action="" method="POST">
                                <input type="hidden" name="document_id" value="<?= $document['id'] ?>">
                                <button type="submit" name="delete_document" class="bg-red-500 text-white px-2 py-1 rounded">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>