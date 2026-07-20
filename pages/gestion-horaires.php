<?php
require_once '../php/verif-employe.php';
$horaires = $pdo->query("SELECT * FROM horaire ORDER BY horaire_id ASC")->fetchAll(PDO::FETCH_ASSOC);
$noms_jours = [
    1 => 'Lundi',
    2 => 'Mardi',
    3 => 'Mercredi',
    4 => 'Jeudi',
    5 => 'Vendredi',
    6 => 'Samedi',
    7 => 'Dimanche'
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des horaires - Saveurs de Bordeaux</title>
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
  <h1 class="mb-4">Gestion des horaires</h1>
  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Horaire mis à jour avec succès.</div>
  <?php endif; ?>
  <table class="table table-striped align-middle">
    <thead>
      <tr><th>Jour</th><th>Ouverture</th><th>Fermeture</th><th>Action</th></tr>
    </thead>
    <tbody>
      <?php foreach ($horaires as $horaire): ?>
        <tr>
          <form action="../php/modifier-horaire.php" method="POST">
            <td>
              <?= $noms_jours[$horaire['jour']] ?? $horaire['jour'] ?>
              <input type="hidden" name="horaire_id" value="<?= $horaire['horaire_id'] ?>">
            </td>
            <td><input type="time" name="heure_ouverture" class="form-control form-control-sm" value="<?= htmlspecialchars($horaire['heure_ouverture']) ?>"></td>
            <td><input type="time" name="heure_fermeture" class="form-control form-control-sm" value="<?= htmlspecialchars($horaire['heure_fermeture']) ?>"></td>
            <td><button type="submit" class="btn btn-sm btn-dark">Enregistrer</button></td>
          </form>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>
