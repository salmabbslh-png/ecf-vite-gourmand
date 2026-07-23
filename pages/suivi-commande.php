<?php
session_start();
require_once '../php/config.php';

// Sécurité : utilisateur connecté uniquement
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: connexion.php");
    exit;
}

$utilisateur_id = $_SESSION['utilisateur_id'];
$commande_id = $_GET['commande_id'] ?? null;

if (!$commande_id) {
    die("Aucune commande spécifiée.");
}

// Récupérer la commande + le nom du menu, en vérifiant que la commande appartient bien à l'utilisateur connecté
$stmt = $pdo->prepare("
    SELECT commande.*, menu.nom AS nom_menu
    FROM commande
    JOIN menu ON commande.menu_id = menu.menu_id
    WHERE commande.commande_id = ? AND commande.utilisateur_id = ?
");
$stmt->execute([$commande_id, $utilisateur_id]);
$commande = $stmt->fetch(PDO::FETCH_ASSOC);

// Si aucune commande trouvée = soit elle n'existe pas, soit elle n'appartient pas à cet utilisateur
if (!$commande) {
    die("Commande introuvable ou accès non autorisé.");
}

// Récupérer l'historique des statuts, du plus ancien au plus récent
$stmt = $pdo->prepare("
    SELECT statut, date_changement
    FROM historique_statut
    WHERE commande_id = ?
    ORDER BY date_changement ASC
");
$stmt->execute([$commande_id]);
$historique = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Liste ordonnée de tous les statuts possibles (pour afficher les étapes à venir en grisé)
$tous_statuts = ['en attente', 'accepté', 'en préparation', 'en cours de livraison', 'livré', 'terminée'];

// Extraire les statuts déjà atteints
$statuts_atteints = array_column($historique, 'statut');

$total = $commande['prix_menu'] + $commande['prix_livraison'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi de commande - Saveurs de Bordeaux</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-dark bg-dark">
  <div class="container">
    <span class="navbar-brand">Saveurs de Bordeaux</span>
    <div>
      <a href="dashboard-utilisateur.php" class="text-white me-3">Mes commandes</a>
      <a href="modifier-profil.php" class="text-white me-3">Mon profil</a>
      <a href="../php/deconnexion.php" class="text-warning">Se déconnecter</a>
    </div>
  </div>
</nav>

<div class="container my-5 flex-grow-1">

  <a href="dashboard-utilisateur.php" class="btn btn-outline-dark mb-4">← Retour à mes commandes</a>

  <h1 class="mb-4">Suivi de commande — <?= htmlspecialchars($commande['nom_menu']) ?></h1>

  <div class="card p-4 mb-4">
    <div class="row">
      <div class="col-md-6">
        <p><strong>Menu :</strong> <?= htmlspecialchars($commande['nom_menu']) ?></p>
        <p><strong>Date de prestation :</strong> <?= htmlspecialchars($commande['date_prestation']) ?></p>
        <p><strong>Personnes :</strong> <?= htmlspecialchars($commande['nombre_personne']) ?></p>
      </div>
      <div class="col-md-6">
        <p><strong>Adresse :</strong> <?= htmlspecialchars($commande['adresse_livraison']) ?></p>
        <p><strong>Total :</strong> <?= number_format($total, 2) ?> €</p>
        <p><strong>Statut actuel :</strong>
          <span class="badge bg-success"><?= htmlspecialchars($commande['statut']) ?></span>
        </p>
      </div>
    </div>
  </div>

  <div class="card p-4">
    <h3 class="mb-4">Historique</h3>
    <ul class="list-group list-group-flush">
      <?php foreach ($tous_statuts as $statut): ?>
        <?php
          // Chercher si ce statut a été atteint et à quelle date
          $date_atteinte = null;
          foreach ($historique as $h) {
              if ($h['statut'] === $statut) {
                  $date_atteinte = $h['date_changement'];
                  break;
              }
          }
          $atteint = ($date_atteinte !== null);
        ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <span>
            <?= $atteint ? '✅' : '⏳' ?>
            <?= htmlspecialchars(ucfirst($statut)) ?>
          </span>
          <span class="text-muted">
            <?= $atteint ? date('d/m/Y - H\hi', strtotime($date_atteinte)) : '—' ?>
          </span>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>

</div>

</body>
</html>
