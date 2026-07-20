<?php
require_once 'verif-employe.php';

$avis_id = $_GET['avis_id'] ?? null;
$action = $_GET['action'] ?? '';

if (!$avis_id || !in_array($action, ['valider', 'refuser'])) {
    die("Action invalide.");
}

// Traduire l'action en statut
$nouveau_statut = ($action === 'valider') ? 'validé' : 'refusé';

$stmt = $pdo->prepare("UPDATE avis SET statut = ? WHERE avis_id = ?");
$stmt->execute([$nouveau_statut, $avis_id]);

header("Location: ../pages/gestion-avis.php?success=1");
exit;
