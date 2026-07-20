<?php
// On inclut la connexion à la base de données
require_once 'config.php';

// On récupère les données envoyées par le formulaire
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$email = $_POST['email'];
$telephone = $_POST['telephone'];
$adresse = $_POST['adresse'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// On vérifie que les deux mots de passe correspondent
if ($password !== $confirm_password) {
    die("Les mots de passe ne correspondent pas.");
}

// On vérifie que le mot de passe respecte les règles
if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{10,}$/', $password)) {
    die("Le mot de passe ne respecte pas les règles de sécurité.");
}

// On vérifie que l'email n'existe pas déjà
$stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetchColumn() > 0) {
    die("Cet email est déjà utilisé.");
}

// On hache le mot de passe
$password_hache = password_hash($password, PASSWORD_DEFAULT);

// On insère le nouvel utilisateur en base
$stmt = $pdo->prepare("INSERT INTO utilisateur (email, password, nom, prenom, telephone, adresse_postale, role_id) VALUES (?, ?, ?, ?, ?, ?, 3)");
$stmt->execute([$email, $password_hache, $nom, $prenom, $telephone, $adresse]);

// Redirection vers la page de connexion
header("Location: ../pages/connexion.html");
exit;
?>
