<?php
include 'navbar.php';
require_once '../controllers/EmployeeController.php';

// Récupération des employés via le contrôleur
$employeeController = new EmployeeController();
$employees = $employeeController->getEmployees();

// Gestion des messages
$message = "";
if (isset($_GET['success'])) {
    $message = "✅ Employé ajouté avec succès !";
} elseif (isset($_GET['deleted'])) {
    $message = "✅ Employé supprimé avec succès !";
} elseif (isset($_GET['error'])) {
    $message = "❌ Une erreur s'est produite.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employés - SmartTech</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-4">Liste des Employés</h1>

        <!-- Notifications -->
        <?php if ($message): ?>
            <div class="bg-green-200 text-green-800 p-3 rounded-md mb-4"><?= $message ?></div>
        <?php endif; ?>

        <!-- Bouton d'ajout avec dropdown -->
        <div class="relative inline-block mb-4">
            <button onclick="toggleDropdown()" class="bg-blue-500 text-white px-4 py-2 rounded-md">
                + Ajouter un employé
            </button>
            <div id="dropdown" class="hidden absolute bg-white p-4 shadow-md mt-2 rounded-md">
                <form action="../controllers/EmployeeController.php" method="POST">
                    <input type="text" name="name" placeholder="Nom" class="w-full p-2 border rounded mb-2" required>
                    <input type="email" name="email" placeholder="Email" class="w-full p-2 border rounded mb-2" required>
                    <input type="text" name="phone" placeholder="Téléphone" class="w-full p-2 border rounded mb-2" required>
                    <input type="text" name="position" placeholder="Poste" class="w-full p-2 border rounded mb-2" required>
                    <button type="submit" name="add_employee" class="bg-green-500 text-white p-2 rounded w-full">
                        Ajouter
                    </button>
                </form>
            </div>
        </div>

        <!-- Tableau des employés -->
        <table class="w-full bg-white shadow-md rounded-lg">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-3 text-left">Nom</th>
                    <th class="p-3 text-left">Email</th>
                    <th class="p-3 text-left">Téléphone</th>
                    <th class="p-3 text-left">Poste</th>
                    <th class="p-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $employee): ?>
                    <tr class="border-b">
                        <td class="p-3"><?= htmlspecialchars($employee['name']) ?></td>
                        <td class="p-3"><?= htmlspecialchars($employee['email']) ?></td>
                        <td class="p-3"><?= htmlspecialchars($employee['phone']) ?></td>
                        <td class="p-3"><?= htmlspecialchars($employee['position']) ?></td>
                        <td class="p-3">
                            <form action="../controllers/EmployeeController.php" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cet employé ?')">
                                <input type="hidden" name="employee_id" value="<?= $employee['id'] ?>">
                                <button type="submit" name="delete_employee" class="bg-red-500 text-white px-2 py-1 rounded">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        function toggleDropdown() {
            document.getElementById("dropdown").classList.toggle("hidden");
        }
    </script>
</body>
</html>
