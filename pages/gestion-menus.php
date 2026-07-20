<?php
require_once '../php/verif-employe.php';

// Récupérer tous les menus
$menus = $pdo->query("SELECT * FROM menu ORDER BY menu_id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des menus - Saveurs de Bordeaux</title>
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
  <h1 class="mb-4">Gestion des menus</h1>
<a href="ajouter-menu.php" class="btn btn-success mb-3">+ Ajouter un menu</a>

  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Opération réalisée avec succès.</div>
  <?php endif; ?>

  <table class="table table-striped align-middle">
    <thead>
      <tr>
        <th>Nom</th>
        <th>Régime</th>
        <th>Pers. min</th>
        <th>Prix/pers.</th>
        <th>Stock</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($menus as $menu): ?>
        <tr>
          <td><?= htmlspecialchars($menu['nom']) ?></td>
          <td><?= htmlspecialchars($menu['regime']) ?></td>
          <td><?= htmlspecialchars($menu['nombre_personne_minimum']) ?></td>
          <td><?= htmlspecialchars($menu['prix_par_personne']) ?> €</td>
          <td><?= htmlspecialchars($menu['quantite_restante']) ?></td>
          <td>
            <a href="modifier-menu.php?menu_id=<?= $menu['menu_id'] ?>" class="btn btn-sm btn-primary">Modifier</a>
            <a href="../php/supprimer-menu.php?menu_id=<?= $menu['menu_id'] ?>"
               class="btn btn-sm btn-danger"
               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce menu ?');">
               Supprimer
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

</body>
</html>
