<?php
require_once 'verif-employe.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Accès non autorisé.");
}

$commande_id = $_POST['commande_id'] ?? null;
$mode_contact = $_POST['mode_contact'] ?? '';
$motif_annulation = $_POST['motif_annulation'] ?? '';

// Revérification côté serveur : les deux champs sont obligatoires (on ne fait pas confiance au required HTML)
if (!$commande_id || $mode_contact === '' || trim($motif_annulation) === '') {
    die("Le mode de contact et le motif sont obligatoires pour annuler une commande.");
}

// Enregistrer l'annulation : statut + motif + mode de contact
$stmt = $pdo->prepare("
    UPDATE commande
    SET statut = 'annulée', motif_annulation = ?, mode_contact = ?
    WHERE commande_id = ?
");
$stmt->execute([$motif_annulation, $mode_contact, $commande_id]);

// Tracer l'annulation dans l'historique des statuts
$stmt = $pdo->prepare("INSERT INTO historique_statut (commande_id, statut) VALUES (?, ?)");
$stmt->execute([$commande_id, 'annulée']);

header("Location: ../pages/gestion-commandes.php?success=1");
exit;
