<?php
require_once 'verif-employe.php';

// Récupérer l'id du menu à supprimer depuis l'URL
$menu_id = $_GET['menu_id'] ?? null;

if (!$menu_id) {
    die("Aucun menu spécifié.");
}

// Supprimer le menu
$stmt = $pdo->prepare("DELETE FROM menu WHERE menu_id = ?");
$stmt->execute([$menu_id]);

// Retour à la liste avec message de succès
header("Location: ../pages/gestion-menus.php?success=1");
exit;
