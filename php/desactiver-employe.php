<?php
require_once 'verif-admin.php';

$utilisateur_id = $_GET['utilisateur_id'] ?? null;
if (!$utilisateur_id) { die("Aucun employé spécifié."); }

// Sécurité : on ne désactive QUE des employés (role_id = 2), jamais un admin
$stmt = $pdo->prepare("UPDATE utilisateur SET actif = 0 WHERE utilisateur_id = ? AND role_id = 2");
$stmt->execute([$utilisateur_id]);

header("Location: ../pages/gestion-employes.php?success=1");
exit;
