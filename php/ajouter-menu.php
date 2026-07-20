<?php
require_once 'verif-employe.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Accès non autorisé.");
}

$nom = $_POST['nom'] ?? '';
$description = $_POST['description'] ?? '';
$regime = $_POST['regime'] ?? '';
$nombre_personne_minimum = (int)($_POST['nombre_personne_minimum'] ?? 0);
$prix_par_personne = (float)($_POST['prix_par_personne'] ?? 0);
$quantite_restante = (int)($_POST['quantite_restante'] ?? 0);

if ($nom === '' || $nombre_personne_minimum <= 0 || $prix_par_personne < 0) {
    die("Données invalides.");
}

$stmt = $pdo->prepare("
    INSERT INTO menu (nom, description, regime, nombre_personne_minimum, prix_par_personne, quantite_restante)
    VALUES (?, ?, ?, ?, ?, ?)
");

$stmt->execute([
    $nom,
    $description,
    $regime,
    $nombre_personne_minimum,
    $prix_par_personne,
    $quantite_restante
]);

header("Location: ../pages/gestion-menus.php?success=1");
exit;
