<?php include 'navbar.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SmartTech</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Dashboard</h1>
        <div class="grid grid-cols-3 gap-6">
            <a href="clients.php" class="block bg-blue-500 text-white p-4 rounded-lg text-center shadow-md hover:bg-blue-600">
                Gérer les Clients
            </a>
            <a href="employees.php" class="block bg-green-500 text-white p-4 rounded-lg text-center shadow-md hover:bg-green-600">
                Gérer les Employés
            </a>
            <a href="documents.php" class="block bg-purple-500 text-white p-4 rounded-lg text-center shadow-md hover:bg-purple-600">
                Gérer les Documents
            </a>
        </div>
    </div>
</body>
</html>
