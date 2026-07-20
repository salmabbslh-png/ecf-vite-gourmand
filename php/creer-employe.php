<?php
require_once 'verif-admin.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Accès non autorisé.");
}

$nom = $_POST['nom'] ?? '';
$prenom = $_POST['prenom'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Validation du mot de passe (même règle que l'inscription)
$regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{10,}$/';
if (!preg_match($regex, $password)) {
    die("Le mot de passe ne respecte pas les règles de sécurité.");
}

// Vérifier que l'email n'existe pas déjà
$stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetchColumn() > 0) {
    header("Location: ../pages/creer-employe.php?erreur=email");
    exit;
}

// Hasher le mot de passe et créer le compte employé (role_id = 2)
$password_hache = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO utilisateur (email, password, nom, prenom, role_id, actif) VALUES (?, ?, ?, ?, 2, 1)");
$stmt->execute([$email, $password_hache, $nom, $prenom]);

// Envoyer un email de notification À L'EMPLOYÉ (sans le mot de passe)
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

    $mail->setFrom('noreply@saveurs-bordeaux.fr', 'Saveurs de Bordeaux');
    $mail->addAddress($email);
    $mail->Subject = 'Votre compte employé a été créé';
    $mail->Body = "Bonjour " . $prenom . ",\n\n"
                . "Un compte employé a été créé pour vous sur l'application Saveurs de Bordeaux.\n"
                . "Votre identifiant est votre adresse email : " . $email . "\n\n"
                . "Pour des raisons de sécurité, votre mot de passe ne figure pas dans cet email. "
                . "Merci de le demander directement à votre administrateur.\n\n"
                . "Cordialement,\nL'équipe Saveurs de Bordeaux";
    $mail->send();
} catch (Exception $e) {
    error_log("Erreur email création employé : " . $mail->ErrorInfo);
}

header("Location: ../pages/gestion-employes.php?success=1");
exit;
