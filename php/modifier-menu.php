<?php
require_once 'verif-employe.php';

// On vérifie qu'on arrive bien par un envoi de formulaire (POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Accès non autorisé.");
}

// Récupération des données du formulaire
$menu_id = $_POST['menu_id'] ?? null;
$nom = $_POST['nom'] ?? '';
$description = $_POST['description'] ?? '';
$regime = $_POST['regime'] ?? '';
$nombre_personne_minimum = (int)($_POST['nombre_personne_minimum'] ?? 0);
$prix_par_personne = (float)($_POST['prix_par_personne'] ?? 0);
$quantite_restante = (int)($_POST['quantite_restante'] ?? 0);

// Vérifications de base
if (!$menu_id || $nom === '' || $nombre_personne_minimum <= 0 || $prix_par_personne < 0) {
    die("Données invalides.");
}

// Mise à jour du menu en base
$stmt = $pdo->prepare("
    UPDATE menu SET
        nom = ?,
        description = ?,
        regime = ?,
        nombre_personne_minimum = ?,
        prix_par_personne = ?,
        quantite_restante = ?
    WHERE menu_id = ?
");

$stmt->execute([
    $nom,
    $description,
    $regime,
    $nombre_personne_minimum,
    $prix_par_personne,
    $quantite_restante,
    $menu_id
]);

// Redirection vers la liste avec un message de succès
header("Location: ../pages/gestion-menus.php?success=1");
exit;
