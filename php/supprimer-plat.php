<?php
require_once 'verif-employe.php';
$plat_id = $_GET['plat_id'] ?? null;
if (!$plat_id) { die("Aucun plat spécifié."); }
$stmt = $pdo->prepare("DELETE FROM plat WHERE plat_id = ?");
$stmt->execute([$plat_id]);
header("Location: ../pages/gestion-plats.php?success=1");
exit;
