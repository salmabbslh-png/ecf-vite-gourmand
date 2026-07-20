<?php
session_start();
require_once '../php/config.php';

// Réservé aux utilisateurs connectés
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: connexion.php");
    exit;
}

$utilisateur_id = $_SESSION['utilisateur_id'];

// Récupérer toutes les commandes de cet utilisateur avec le nom du menu
$stmt = $pdo->prepare("
    SELECT commande.*, menu.nom AS nom_menu
    FROM commande
    JOIN menu ON commande.menu_id = menu.menu_id
    WHERE commande.utilisateur_id = ?
    ORDER BY commande.date_commande DESC
");
$stmt->execute([$utilisateur_id]);
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes commandes - Saveurs de Bordeaux</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">Saveurs de Bordeaux</a>
    <div>
      <a class="nav-link d-inline text-white" href="menus.php">Nos Menus</a>
      <a class="nav-link d-inline text-white" href="modifier-profil.php">Mon profil</a>
      <a class="nav-link d-inline text-warning" href="../php/deconnexion.php">Se déconnecter</a>
    </div>
  </div>
</nav>

<div class="container my-5 flex-grow-1">
  <h1 class="mb-4">Mes commandes</h1>

  <?php if (isset($_GET['avis']) && $_GET['avis'] === 'success'): ?>
    <div class="alert alert-success">Merci, votre avis a bien été envoyé !</div>
  <?php endif; ?>

  <?php if (empty($commandes)): ?>
    <div class="alert alert-info">Vous n'avez pas encore passé de commande. <a href="menus.php">Découvrir nos menus</a></div>
  <?php else: ?>
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>N° commande</th>
          <th>Menu</th>
          <th>Date prestation</th>
          <th>Personnes</th>
          <th>Statut</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($commandes as $commande): ?>
          <tr>
            <td><?= htmlspecialchars($commande['numero_commande']) ?></td>
            <td><?= htmlspecialchars($commande['nom_menu']) ?></td>
            <td><?= htmlspecialchars($commande['date_prestation']) ?></td>
            <td><?= htmlspecialchars($commande['nombre_personne']) ?></td>
            <td><span class="badge bg-info text-dark"><?= htmlspecialchars($commande['statut']) ?></span></td>
            <td>
              <a href="suivi-commande.php?commande_id=<?= $commande['commande_id'] ?>" class="btn btn-sm btn-dark">Suivi</a>
              <?php if ($commande['statut'] === 'terminée'): ?>
                <a href="laisser-avis.php?commande_id=<?= $commande['commande_id'] ?>" class="btn btn-sm btn-warning">Laisser un avis</a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

</body>
</html>
