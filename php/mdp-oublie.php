<?php
require_once 'config.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email = $_POST['email'];

$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
$stmt->execute([$email]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur) {
    die("Aucun compte associé à cet email.");
}

$token = bin2hex(random_bytes(32));
$expire = date('Y-m-d H:i:s', strtotime('+1 hour'));

$stmt = $pdo->prepare("UPDATE utilisateur SET reset_token = ?, reset_token_expire = ? WHERE email = ?");
$stmt->execute([$token, $expire, $email]);

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
    $mail->Subject = 'Réinitialisation de votre mot de passe';
    $domaine = $_SERVER['HTTP_HOST'];
    $lien = "http://" . $domaine . "/pages/reset-password.php?token=" . $token;
    $mail->Body = "Cliquez sur ce lien pour réinitialiser votre mot de passe : " . $lien;
    $mail->send();
    echo "Email envoyé ! Vérifiez votre boîte Mailtrap.";
} catch (Exception $e) {
    echo "Erreur : " . $mail->ErrorInfo;
}
?>
