<?php
require_once 'verif-employe.php';

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

// Retour à la liste des commandes avec message de succès
header("Location: ../pages/gestion-commandes.php?success=1");
exit;
