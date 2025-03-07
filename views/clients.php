<?php
include 'navbar.php'; 

require_once '../models/ClientModels.php';
require_once '../services/MailService.php';

$clientModel = new ClientModels();
$clients = $clientModel->getAllClients();

$response = null;

// Ajouter un client
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_client'])) {
    $company_name = $_POST['company_name'];
    $contact_person = $_POST['contact_person'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    if ($clientModel->createClient($company_name, $contact_person, $email, $phone, $address)) {
        // Envoyer un mail de notification
        MailService::sendEmail(
            $email,
            "Bienvenue chez SmartTech",
            "<p>Bonjour $contact_person, votre entreprise $company_name a été ajoutée avec succès.</p>"
        );
        echo "<script>window.location.reload();</script>";
        exit();
    } else {
        $response = "Erreur lors de l'ajout du client.";
    }
}

// Supprimer un client
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if ($clientModel->deleteClient($id)) {
        echo "<script>window.location.reload();</script>";
        exit();
    } else {
        $response = "Erreur lors de la suppression du client.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients - SmartTech</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script>
        function toggleForm() {
            let form = document.getElementById("clientForm");
            form.classList.toggle("hidden");
        }

        function showError(errorMessage) {
            alert(errorMessage); // Affiche un message d'erreur en popup
        }
    </script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-4">Gestion des Clients</h1>

        <!-- Bouton pour afficher/masquer le formulaire -->
        <button onclick="toggleForm()" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition">
            Ajouter un client
        </button>

        <!-- Formulaire d'ajout de client (masqué par défaut) -->
        <div id="clientForm" class="bg-white p-4 shadow-md rounded mb-4 mt-4 hidden">
            <h2 class="text-xl font-semibold mb-2">Ajouter un client</h2>
            <form method="POST">
                <input type="text" name="company_name" placeholder="Nom de l'entreprise" class="border p-2 w-full mb-2" required>
                <input type="text" name="contact_person" placeholder="Contact" class="border p-2 w-full mb-2" required>
                <input type="email" name="email" placeholder="Email" class="border p-2 w-full mb-2" required>
                <input type="text" name="phone" placeholder="Téléphone" class="border p-2 w-full mb-2" required>
                <input type="text" name="address" placeholder="Adresse" class="border p-2 w-full mb-2" required>
                <button type="submit" name="add_client" class="bg-blue-500 text-white p-2 w-full rounded hover:bg-blue-600">
                    Enregistrer
                </button>
            </form>
        </div>

        <!-- Tableau des clients -->
        <table class="w-full bg-white shadow-md rounded-lg">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-3 text-left">Nom de l'entreprise</th>
                    <th class="p-3 text-left">Contact</th>
                    <th class="p-3 text-left">Email</th>
                    <th class="p-3 text-left">Téléphone</th>
                    <th class="p-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                    <tr class="border-b">
                        <td class="p-3"><?= htmlspecialchars($client['company_name']) ?></td>
                        <td class="p-3"><?= htmlspecialchars($client['contact_person']) ?></td>
                        <td class="p-3"><?= htmlspecialchars($client['email']) ?></td>
                        <td class="p-3"><?= htmlspecialchars($client['phone']) ?></td>
                        <td class="p-3">
                            <a href="clients.php?delete=<?= $client['id'] ?>" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                                Supprimer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ($response): ?>
        <script>showError("<?= $response ?>");</script>
    <?php endif; ?>

</body>
</html>
