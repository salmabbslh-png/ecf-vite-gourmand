<?php
require_once '../php/verif-employe.php';
$plats = $pdo->query("SELECT * FROM plat ORDER BY plat_id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des plats - Saveurs de Bordeaux</title>
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
  <h1 class="mb-4">Gestion des plats</h1>
  <a href="ajouter-plat.php" class="btn btn-success mb-3">+ Ajouter un plat</a>
  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Opération réalisée avec succès.</div>
  <?php endif; ?>
  <table class="table table-striped align-middle">
    <thead>
      <tr><th>Titre</th><th>Prix</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php foreach ($plats as $plat): ?>
        <tr>
          <td><?= htmlspecialchars($plat['titre_plat']) ?></td>
          <td><?= htmlspecialchars($plat['prix']) ?> €</td>
          <td>
            <a href="modifier-plat.php?plat_id=<?= $plat['plat_id'] ?>" class="btn btn-sm btn-primary">Modifier</a>
            <a href="../php/supprimer-plat.php?plat_id=<?= $plat['plat_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce plat ?');">Supprimer</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>
