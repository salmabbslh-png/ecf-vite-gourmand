<?php
session_start();
require_once 'config.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['utilisateur_id'])) {
    die("Vous devez être connecté pour passer une commande.");
}

$utilisateur_id = $_SESSION['utilisateur_id'];
$menu_id = $_POST['menu_id'] ?? null;
$adresse_livraison = $_POST['adresse_livraison'] ?? '';
$date_prestation = $_POST['date_prestation'] ?? '';
$heure_livraison = $_POST['heure_livraison'] ?? '';
$nombre_personne = (int)($_POST['nombre_personne'] ?? 0);
$hors_bordeaux = isset($_POST['hors_bordeaux']);
$nombre_km = $hors_bordeaux ? (int)($_POST['nombre_km'] ?? 0) : 0;

// Vérifications de base
if (!$menu_id || !$adresse_livraison || !$date_prestation || !$heure_livraison || $nombre_personne <= 0) {
    die("Tous les champs sont obligatoires.");
}

// Récupérer le menu pour valider le minimum et calculer le prix (JAMAIS confiance au prix envoyé par le formulaire)
$stmt = $pdo->prepare("SELECT * FROM menu WHERE menu_id = ?");
$stmt->execute([$menu_id]);
$menu = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$menu) {
    die("Menu introuvable.");
}

if ($nombre_personne < $menu['nombre_personne_minimum']) {
    die("Le nombre de personnes est inférieur au minimum requis pour ce menu (" . $menu['nombre_personne_minimum'] . ").");
}

// Calcul du prix menu (avec réduction éventuelle)
$prix_menu = $menu['prix_par_personne'] * $nombre_personne;
if ($nombre_personne >= $menu['nombre_personne_minimum'] + 5) {
    $prix_menu = $prix_menu * 0.9;
}

// Calcul du prix livraison
$prix_livraison = $hors_bordeaux ? (5 + (0.59 * $nombre_km)) : 0;

// Génération d'un numéro de commande unique
$numero_commande = uniqid('CMD-');

// Insertion en base
$stmt = $pdo->prepare("INSERT INTO commande 
    (numero_commande, utilisateur_id, menu_id, date_commande, date_prestation, heure_livraison, adresse_livraison, nombre_personne, nombre_km, prix_menu, prix_livraison, statut, pret_materiel, restituer_materiel)
    VALUES (?, ?, ?, CURDATE(), ?, ?, ?, ?, ?, ?, ?, 'en attente', 0, 0)");

$stmt->execute([
    $numero_commande,
    $utilisateur_id,
    $menu_id,
    $date_prestation,
    $heure_livraison,
    $adresse_livraison,
    $nombre_personne,
    $nombre_km,
    $prix_menu,
    $prix_livraison
]);
// Récupérer l'ID de la commande qu'on vient de créer
$commande_id = $pdo->lastInsertId();

// Enregistrer le statut initial dans l'historique
$stmt = $pdo->prepare("INSERT INTO historique_statut (commande_id, statut) VALUES (?, ?)");
$stmt->execute([$commande_id, 'en attente']);


// Récupérer l'email de l'utilisateur pour la confirmation
$stmt = $pdo->prepare("SELECT email, prenom FROM utilisateur WHERE utilisateur_id = ?");
$stmt->execute([$utilisateur_id]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

// Envoi de l'email de confirmation
$mail = new PHPMailer(true);
try {
    $mail->CharSet = 'UTF-8';
    $mail->isSMTP();
    $mail->Host = 'sandbox.smtp.mailtrap.io';
    $mail->SMTPAuth = true;
    $mail->Username = 'ad3ef176d028ee';
    $mail->Password = '157c79e0e9549e';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('noreply@saveurs-bordeaux.fr', 'Saveurs de Bordeaux');
    $mail->addAddress($utilisateur['email']);
    $mail->Subject = 'Confirmation de votre commande';
    $mail->Body = "Bonjour " . $utilisateur['prenom'] . ",\n\nVotre commande n°" . $numero_commande . " a bien été enregistrée.\nMenu : " . $menu['nom'] . "\nNombre de personnes : " . $nombre_personne . "\nPrix total : " . ($prix_menu + $prix_livraison) . " €\n\nMerci de votre confiance !";
    $mail->send();
} catch (Exception $e) {
    // On n'interrompt pas la commande si l'email échoue, mais on log l'erreur
    error_log("Erreur envoi email confirmation commande : " . $mail->ErrorInfo);
}
// --- Enregistrement dans MongoDB (base NoSQL pour les statistiques) ---
try {
    // Charger la librairie MongoDB installée via Composer
    require_once '../vendor/autoload.php';

    // Connexion au serveur MongoDB local
    $mongoClient = new MongoDB\Client("mongodb://localhost:27017");

    // Base "saveurs_bordeaux", collection "commandes"
    $collectionMongo = $mongoClient->saveurs_bordeaux->commandes;

    // Insérer un document représentant cette commande
    $collectionMongo->insertOne([
        'menu_id' => (int)$menu_id,
        'nom_menu' => $menu['nom'],
        'prix_total' => $prix_menu + $prix_livraison,
        'nombre_personne' => $nombre_personne,
        'date_commande' => date('Y-m-d')
    ]);
} catch (Exception $e) {
    // Si MongoDB échoue, on ne bloque pas la commande (déjà enregistrée dans MySQL)
    error_log("Erreur MongoDB : " . $e->getMessage());
}
// --- Fin enregistrement MongoDB ---
header("Location: ../pages/suivi-commande.php?commande_id=" . $commande_id . "&success=1");
exit;