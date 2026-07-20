<?php
require_once 'config.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Accès non autorisé.");
}

$titre = $_POST['titre'] ?? '';
$email = $_POST['email'] ?? '';
$message = $_POST['message'] ?? '';

// Vérifications côté serveur
if (trim($titre) === '' || trim($email) === '' || trim($message) === '') {
    header("Location: ../pages/contact.php?erreur=1");
    exit;
}

$mail = new PHPMailer(true);
try {
    $mail->CharSet = 'UTF-8';
    $mail->isSMTP();
    $mail->Host = 'sandbox.smtp.mailtrap.io';
    $mail->SMTPAuth = true;
    $mail->Username = 'ad3ef176d028ee';
    $mail->Password = '157c79e0e9549e';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // L'email part vers l'entreprise
    $mail->setFrom('noreply@saveurs-bordeaux.fr', 'Formulaire de contact');
    $mail->addAddress('contact@saveurs-bordeaux.fr', 'Saveurs de Bordeaux');
    // On met l'email du visiteur en "répondre à" pour que l'entreprise puisse lui répondre
    $mail->addReplyTo($email);

    $mail->Subject = 'Nouveau message de contact : ' . $titre;
    $mail->Body = "Message reçu via le formulaire de contact.\n\n"
                . "De : " . $email . "\n"
                . "Titre : " . $titre . "\n\n"
                . "Message :\n" . $message;

    $mail->send();
    header("Location: ../pages/contact.php?success=1");
    exit;
} catch (Exception $e) {
    header("Location: ../pages/contact.php?erreur=1");
    exit;
}
