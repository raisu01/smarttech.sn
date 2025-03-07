
<?php include 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - SmartTech</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 min-h-screen ">
<div class="w-screen h-[90vh] flex items-center justify-center">
<div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold text-center mb-4">Inscription</h2>

        <?php if (isset($_SESSION["error"])): ?>
            <div class="bg-red-200 text-red-800 p-3 rounded-md mb-4"><?= $_SESSION["error"] ?></div>
            <?php unset($_SESSION["error"]); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION["success"])): ?>
            <div class="bg-green-200 text-green-800 p-3 rounded-md mb-4"><?= $_SESSION["success"] ?></div>
            <?php unset($_SESSION["success"]); ?>
        <?php endif; ?>

        <form action="auth.php?action=register" method="POST">
            <label class="block mb-2 font-medium">Nom Complet</label>
            <input type="text" name="full_name" class="w-full p-2 border rounded mb-2" required>

            <label class="block mb-2 font-medium">Email</label>
            <input type="email" name="email" class="w-full p-2 border rounded mb-2" required>

            <label class="block mb-2 font-medium">Mot de passe</label>
            <input type="password" name="password" class="w-full p-2 border rounded mb-2" required>

            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
                S'inscrire
            </button>
        </form>

        <p class="mt-4 text-center text-sm">
            Déjà inscrit ? <a href="login.php" class="text-blue-500 hover:underline">Connectez-vous</a>
        </p>
    </div>
</div>
</body>
</html>
