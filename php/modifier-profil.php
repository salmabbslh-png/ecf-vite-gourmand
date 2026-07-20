<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['utilisateur_id'])) {
    die("Vous devez être connecté.");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Accès non autorisé.");
}

$utilisateur_id = $_SESSION['utilisateur_id'];
$nom = $_POST['nom'] ?? '';
$prenom = $_POST['prenom'] ?? '';
$email = $_POST['email'] ?? '';
$telephone = $_POST['telephone'] ?? '';
$adresse_postale = $_POST['adresse_postale'] ?? '';
$ville = $_POST['ville'] ?? '';
$pays = $_POST['pays'] ?? '';

if (trim($nom) === '' || trim($prenom) === '' || trim($email) === '') {
    die("Le nom, le prénom et l'email sont obligatoires.");
}

// Vérifier que le nouvel email n'est pas déjà utilisé par un AUTRE compte
$stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE email = ? AND utilisateur_id != ?");
$stmt->execute([$email, $utilisateur_id]);
if ($stmt->fetchColumn() > 0) {
    die("Cet email est déjà utilisé par un autre compte.");
}

// Mettre à jour le profil
$stmt = $pdo->prepare("
    UPDATE utilisateur
    SET nom = ?, prenom = ?, email = ?, telephone = ?, adresse_postale = ?, ville = ?, pays = ?
    WHERE utilisateur_id = ?
");
$stmt->execute([$nom, $prenom, $email, $telephone, $adresse_postale, $ville, $pays, $utilisateur_id]);

// Mettre à jour le prénom en session (au cas où il aurait changé)
$_SESSION['prenom'] = $prenom;

header("Location: ../pages/modifier-profil.php?success=1");
exit;
