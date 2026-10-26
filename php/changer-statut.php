<?php
require_once 'verif-employe.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Accès non autorisé.");
}

$commande_id = $_POST['commande_id'] ?? null;
$nouveau_statut = $_POST['nouveau_statut'] ?? '';

// Liste blanche des statuts autorisés (sécurité : on n'accepte que ces valeurs)
$statuts_autorises = ['en attente', 'accepté', 'en préparation', 'en cours de livraison', 'livré', 'en attente du retour de matériel', 'terminée'];

if (!$commande_id || !in_array($nouveau_statut, $statuts_autorises)) {
    die("Données invalides.");
}

// 1. Mettre à jour le statut actuel de la commande
$stmt = $pdo->prepare("UPDATE commande SET statut = ? WHERE commande_id = ?");
$stmt->execute([$nouveau_statut, $commande_id]);

// 2. Enregistrer ce changement dans l'historique (avec date/heure automatique)
$stmt = $pdo->prepare("INSERT INTO historique_statut (commande_id, statut) VALUES (?, ?)");
$stmt->execute([$commande_id, $nouveau_statut]);

// 3. Envoyer un email au client pour les statuts qui le demandent
if ($nouveau_statut === 'en attente du retour de matériel' || $nouveau_statut === 'terminée') {

    // Récupérer l'email et le prénom du client de cette commande
    $stmt = $pdo->prepare("SELECT utilisateur.email, utilisateur.prenom, commande.numero_commande
                           FROM commande
                           JOIN utilisateur ON commande.utilisateur_id = utilisateur.utilisateur_id
                           WHERE commande.commande_id = ?");
    $stmt->execute([$commande_id]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($client) {
        if ($nouveau_statut === 'en attente du retour de matériel') {
            $sujet = "Restitution du matériel - commande " . $client['numero_commande'];
            $message = "Bonjour " . $client['prenom'] . ",\n\n"
                     . "Votre commande " . $client['numero_commande'] . " est en attente du retour du matériel prêté.\n"
                     . "Merci de prendre contact avec nous pour le restituer.\n"
                     . "Sans restitution sous 10 jours ouvrés, des frais de 600 € vous seront facturés, "
                     . "conformément à nos conditions générales de vente.\n\n"
                     . "L'équipe Saveurs de Bordeaux";
        } else {
            $sujet = "Votre commande est terminée - donnez-nous votre avis";
            $message = "Bonjour " . $client['prenom'] . ",\n\n"
                     . "Votre commande " . $client['numero_commande'] . " est terminée. Merci pour votre confiance !\n"
                     . "Vous pouvez dès maintenant laisser un avis (une note de 1 à 5 et un commentaire) "
                     . "depuis votre espace client.\n\n"
                     . "L'équipe Saveurs de Bordeaux";
        }

        try {
            $mail = new PHPMailer(true);
            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Username = 'ad3ef176d028ee';
            $mail->Password = '157c79e0e9549e';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('noreply@saveurs-bordeaux.fr', 'Saveurs de Bordeaux');
            $mail->addAddress($client['email']);
            $mail->Subject = $sujet;
            $mail->Body = $message;
            $mail->send();
        } catch (Exception $e) {
            // L'échec de l'email ne bloque pas le changement de statut
            error_log("Erreur mail changement de statut : " . $mail->ErrorInfo);
        }
    }
}

// Retour à la liste des commandes avec message de succès
header("Location: ../pages/gestion-commandes.php?success=1");
exit;
