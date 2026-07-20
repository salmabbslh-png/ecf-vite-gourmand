<?php
require_once 'config.php';

$token = $_POST['token'] ?? '';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if (empty($token)) {
    die("Token manquant.");
}

// Revérifier le token (sécurité : ne jamais faire confiance au premier passage)
$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE reset_token = ?");
$stmt->execute([$token]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur) {
    die("Lien invalide ou déjà utilisé.");
}

if ($utilisateur['reset_token_expire'] < date('Y-m-d H:i:s')) {
    die("Ce lien a expiré.");
}

// Vérifier la correspondance des deux champs
if ($password !== $confirm_password) {
    die("Les mots de passe ne correspondent pas.");
}

// Revalider la règle ECF : 10 caractères min, 1 maj, 1 min, 1 chiffre, 1 spécial
$regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{10,}$/';
if (!preg_match($regex, $password)) {
    die("Le mot de passe doit contenir au moins 10 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.");
}

// Hasher et enregistrer, puis invalider le token
$hashed = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE utilisateur SET password = ?, reset_token = NULL, reset_token_expire = NULL WHERE reset_token = ?");
$stmt->execute([$hashed, $token]);

header("Location: ../pages/connexion.php?reset=success");
exit;
