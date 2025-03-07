<?php
session_start();
?>
<nav class="bg-blue-600 p-4 text-white shadow-md">
    <div class="container mx-auto flex justify-between items-center">
        <a href="dashboard.php" class="text-xl font-bold">SmartTech</a>
        <ul class="flex space-x-4">
            <li><a href="dashboard.php" class="hover:text-gray-300">Dashboard</a></li>
            <li><a href="clients.php" class="hover:text-gray-300">Clients</a></li>
            <li><a href="employees.php" class="hover:text-gray-300">Employés</a></li>
            <li><a href="documents.php" class="hover:text-gray-300">Documents</a></li>
        </ul>
        
        <div class="flex space-x-2">
            <?php if (isset($_SESSION['user'])): ?>
                <span class="mr-4">👋 Bonjour, <?= htmlspecialchars($_SESSION['user']['full_name']) ?></span>
                <a href="auth.php?action=logout" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition">
                    Déconnexion
                </a>
            <?php else: ?>
                <a href="login.php" class="bg-white text-blue-600 px-4 py-2 rounded-md hover:bg-blue-500 hover:text-white transition">
                    Connexion
                </a>
                <a href="register.php" class="bg-white text-blue-600 px-4 py-2 rounded-md hover:bg-blue-500 hover:text-white transition">
                    Inscription
                </a>
                <a href="test.php" class="bg-white text-blue-600 px-4 py-2 rounded-md hover:bg-blue-500 hover:text-white transition">
                    test
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>
