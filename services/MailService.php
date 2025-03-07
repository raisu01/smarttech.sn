<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

class MailService {
    public static function sendEmail($to, $subject, $body, $isHtml = true) {
        $mail = new PHPMailer(true);

        try {
            // Configuration du serveur SMTP
            $mail->isSMTP();
            $mail->Host = 'mail.smarttech.sn'; // Serveur SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'adminserver@smarttech.sn'; // Adresse email d'envoi
            $mail->Password = 'Adminproc12?'; // Mot de passe
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // STARTTLS pour sécurité
            $mail->Port = 587; // Port SMTP

            // Désactiver la vérification du certificat SSL (optionnel, pour les environnements de test)
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ];

            // Informations de l'expéditeur
            $mail->setFrom('adminserver@smarttech.sn', 'SmartTech');
            $mail->addAddress($to);
            $mail->CharSet = 'UTF-8';

            // Format du message
            $mail->isHTML($isHtml);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AltBody = strip_tags($body); // Version texte brut

            // Envoi de l'email
            $mail->send();
            return ["success" => true, "message" => "📧 Email envoyé à $to"];

        } catch (Exception $e) {
            // Log des erreurs SMTP
            $errorMessage = date('[Y-m-d H:i:s] ') . "Erreur d'envoi à $to: " . $e->getMessage() . PHP_EOL;
            error_log($errorMessage, 3, '../logs/mail_errors.log'); // Assurez-vous que le dossier logs existe et est accessible

            // Ajoutez une erreur dans la session pour la notification de l'utilisateur
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['error'] = "❌ Erreur d'envoi : " . $e->getMessage();

            return ["success" => false, "message" => "❌ Erreur d'envoi : " . $e->getMessage()];
        }
    }
}