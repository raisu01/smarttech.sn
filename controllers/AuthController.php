<?php
require_once '../config/database.php';
require_once '../services/MailService.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class AuthController {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc(); // Récupérer les données sous forme de tableau associatif

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            $_SESSION['success'] = "Connexion réussie. Bienvenue " . htmlspecialchars($user['full_name']) . " !";
            header("Location: dashboard.php");
            exit;
        } else {
            $_SESSION['error'] = "Email ou mot de passe incorrect.";
            header("Location: login.php");
            exit;
        }
    }

    public function register($fullName, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        // Vérifier si l'email existe déjà
        $checkStmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        
        if (!$checkStmt) {
            die("Erreur de préparation SQL (vérification email) : " . $this->db->error);
        }
    
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();
        
        if ($checkStmt->num_rows > 0) {
            $_SESSION['error'] = "Cet email est déjà utilisé.";
            header("Location: register.php");
            exit;
        }
    
        $checkStmt->close(); // Fermer la requête précédente
    
        // Préparation de la requête d'insertion
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        
        if (!$stmt) {
            die("Erreur de préparation SQL (insertion) : " . $this->db->error);
        }
    
        $stmt->bind_param("sss", $fullName, $email, $hashedPassword);
    
        if ($stmt->execute()) {
            $_SESSION['success'] = "Inscription réussie. Vous pouvez vous connecter.";
    
            // ✉️ **Envoi de l'email à l'utilisateur**
            $subjectUser = "Bienvenue sur SmartTech";
            $messageUser = "
                <h1>Bienvenue $fullName</h1>
                <p>Votre compte a été créé avec succès.</p>
                <p>Merci de nous rejoindre !</p>
            ";
            MailService::sendEmail($email, $subjectUser, $messageUser);
    
            // ✉️ **Envoi de l'email à l'administrateur**
            $adminEmail = "adminproc@smarttech.com";  // Remplace par l'email réel de l'admin
            $subjectAdmin = "Nouveau compte créé sur SmartTech";
            $messageAdmin = "
                <h1>Un nouvel utilisateur s'est inscrit</h1>
                <p><strong>Nom d'utilisateur :</strong> $fullName</p>
                <p><strong>Email :</strong> $email</p>
            ";
            MailService::sendEmail($adminEmail, $subjectAdmin, $messageAdmin);
    
            header("Location: login.php");
            exit;
        } else {
            $_SESSION['error'] = "Erreur lors de l'inscription : " . $stmt->error;
            header("Location: register.php");
            exit;
        }
    }
    
    public function logout() {
        session_unset(); // Supprime toutes les variables de session
        session_destroy(); // Détruit la session
        header("Location: login.php");
        exit;
    }
}
?>
