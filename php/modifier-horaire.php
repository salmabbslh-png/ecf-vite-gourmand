<?php
require_once 'verif-employe.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { die("Accès non autorisé."); }

$horaire_id = $_POST['horaire_id'] ?? null;
$heure_ouverture = $_POST['heure_ouverture'] ?? '';
$heure_fermeture = $_POST['heure_fermeture'] ?? '';

if (!$horaire_id) { die("Horaire non spécifié."); }

$stmt = $pdo->prepare("UPDATE horaire SET heure_ouverture = ?, heure_fermeture = ? WHERE horaire_id = ?");
$stmt->execute([$heure_ouverture, $heure_fermeture, $horaire_id]);

header("Location: ../pages/gestion-horaires.php?success=1");
exit;
