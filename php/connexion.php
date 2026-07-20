<?php
session_start();

require_once 'config.php';

// On récupère les données du formulaire
$email = $_POST['email'];
$password = $_POST['password'];

// On cherche l'utilisateur par son email
$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
$stmt->execute([$email]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

// On vérifie que l'email existe ET que le mot de passe correspond
if (!$utilisateur || !password_verify($password, $utilisateur['password'])) {
    die("Email ou mot de passe incorrect.");
}
// Vérifier que le compte n'est pas désactivé
if (isset($utilisateur['actif']) && $utilisateur['actif'] == 0) {
    die("Ce compte a été désactivé. Veuillez contacter l'administrateur.");
}

// On ouvre la session avec les infos de l'utilisateur
$_SESSION['utilisateur_id'] = $utilisateur['utilisateur_id'];
$_SESSION['role'] = $utilisateur['role_id'];
$_SESSION['prenom'] = $utilisateur['prenom'];

// On redirige selon le rôle
if ($utilisateur['role_id'] == 3) {
    header("Location: ../pages/dashboard-utilisateur.php");
} elseif ($utilisateur['role_id'] == 2) {
    header("Location: ../pages/gestion-commandes.php");
} elseif ($utilisateur['role_id'] == 1) {
    header("Location: ../pages/dashboard-admin-stats.php");
}
exit;
?>
