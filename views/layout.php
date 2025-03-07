<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartTech</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 overflow-hidden">

    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Contenu de la page -->
    <div class="container mx-auto mt-8 p-4 overflow-hidden">
        <?php echo $content; ?>
    </div>

    <!-- Footer -->
    <footer class="bg-blue-600 text-white text-center p-4 mt-8">
        &copy; <?php echo date("Y"); ?> SmartTech - Tous droits réservés.
    </footer>

</body>
</html>
