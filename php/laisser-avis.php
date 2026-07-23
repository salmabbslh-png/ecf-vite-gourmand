<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['utilisateur_id'])) {
    die("Vous devez être connecté.");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Accès non autorisé.");
}

$utilisateur_id = $_SESSION['utilisateur_id'];
$commande_id = $_POST['commande_id'] ?? null;
$note = (int)($_POST['note'] ?? 0);
$description = $_POST['description'] ?? '';

// Vérifications
if (!$commande_id || $note < 1 || $note > 5 || trim($description) === '') {
    die("Données invalides. La note doit être entre 1 et 5 et le commentaire est obligatoire.");
}

// Re-vérifier que la commande appartient à l'utilisateur ET est terminée (sécurité serveur)
$stmt = $pdo->prepare("SELECT statut FROM commande WHERE commande_id = ? AND utilisateur_id = ?");
$stmt->execute([$commande_id, $utilisateur_id]);
$commande = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$commande || $commande['statut'] !== 'terminée') {
    die("Vous ne pouvez pas laisser d'avis pour cette commande.");
}

// Vérifier qu'un avis n'existe pas déjà
$stmt = $pdo->prepare("SELECT COUNT(*) FROM avis WHERE commande_id = ?");
$stmt->execute([$commande_id]);
if ($stmt->fetchColumn() > 0) {
    die("Un avis a déjà été laissé pour cette commande.");
}

// Enregistrer l'avis avec le statut "en attente" (doit être validé par un employé)
$stmt = $pdo->prepare("INSERT INTO avis (note, description, statut, utilisateur_id, commande_id) VALUES (?, ?, 'en attente', ?, ?)");
$stmt->execute([$note, $description, $utilisateur_id, $commande_id]);

header("Location: ../pages/dashboard-utilisateur.php?avis=success");
exit;
