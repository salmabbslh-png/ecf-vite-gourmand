<?php
require_once '../php/verif-employe.php';

// Récupérer tous les avis avec le nom du client et du menu
$avis = $pdo->query("
    SELECT avis.*, utilisateur.prenom, utilisateur.nom, menu.nom AS nom_menu
    FROM avis
    JOIN utilisateur ON avis.utilisateur_id = utilisateur.utilisateur_id
    JOIN commande ON avis.commande_id = commande.commande_id
    JOIN menu ON commande.menu_id = menu.menu_id
    ORDER BY avis.avis_id DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des avis - Saveurs de Bordeaux</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <span class="navbar-brand">Saveurs de Bordeaux — Espace employé</span>
    <div>
      <a class="nav-link d-inline text-white me-3" href="gestion-commandes.php">Commandes</a>
      <a class="nav-link d-inline text-white me-3" href="gestion-menus.php">Menus</a>
      <a class="nav-link d-inline text-white me-3" href="gestion-plats.php">Plats</a>
      <a class="nav-link d-inline text-white me-3" href="gestion-horaires.php">Horaires</a>
      <a class="nav-link d-inline text-white me-3" href="gestion-avis.php">Avis</a>
      <a class="nav-link d-inline text-warning" href="../php/deconnexion.php">Se déconnecter</a>
    </div>
  </div>
</nav>

<div class="container my-5 flex-grow-1">
  <h1 class="mb-4">Gestion des avis</h1>
  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Avis mis à jour.</div>
  <?php endif; ?>

  <table class="table table-striped align-middle">
    <thead>
      <tr><th>Client</th><th>Menu</th><th>Note</th><th>Commentaire</th><th>Statut</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php foreach ($avis as $a): ?>
        <tr>
          <td><?= htmlspecialchars($a['prenom']) ?> <?= htmlspecialchars($a['nom']) ?></td>
          <td><?= htmlspecialchars($a['nom_menu']) ?></td>
          <td><?= str_repeat('⭐', (int)$a['note']) ?></td>
          <td><?= htmlspecialchars($a['description']) ?></td>
          <td>
            <?php if ($a['statut'] === 'validé'): ?>
              <span class="badge bg-success">Validé</span>
            <?php elseif ($a['statut'] === 'refusé'): ?>
              <span class="badge bg-danger">Refusé</span>
            <?php else: ?>
              <span class="badge bg-warning text-dark">En attente</span>
            <?php endif; ?>
          </td>
          <td>
            <a href="../php/valider-avis.php?avis_id=<?= $a['avis_id'] ?>&action=valider" class="btn btn-sm btn-success">Valider</a>
            <a href="../php/valider-avis.php?avis_id=<?= $a['avis_id'] ?>&action=refuser" class="btn btn-sm btn-danger">Refuser</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>
