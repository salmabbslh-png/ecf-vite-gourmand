<?php
require_once 'verif-employe.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { die("Accès non autorisé."); }
$plat_id = $_POST['plat_id'] ?? null;
$titre_plat = $_POST['titre_plat'] ?? '';
$prix = (float)($_POST['prix'] ?? 0);
if (!$plat_id || $titre_plat === '' || $prix < 0) { die("Données invalides."); }
$stmt = $pdo->prepare("UPDATE plat SET titre_plat = ?, prix = ? WHERE plat_id = ?");
$stmt->execute([$titre_plat, $prix, $plat_id]);
header("Location: ../pages/gestion-plats.php?success=1");
exit;
