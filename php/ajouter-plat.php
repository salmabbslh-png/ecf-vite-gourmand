<?php
require_once 'verif-employe.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { die("Accès non autorisé."); }
$titre_plat = $_POST['titre_plat'] ?? '';
$prix = (float)($_POST['prix'] ?? 0);
if ($titre_plat === '' || $prix < 0) { die("Données invalides."); }
$stmt = $pdo->prepare("INSERT INTO plat (titre_plat, prix) VALUES (?, ?)");
$stmt->execute([$titre_plat, $prix]);
header("Location: ../pages/gestion-plats.php?success=1");
exit;
