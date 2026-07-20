<?php
require_once '../php/verif-employe.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un plat - Saveurs de Bordeaux</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
<nav class="navbar navbar-dark bg-dark">
  <div class="container">
    <span class="navbar-brand">Saveurs de Bordeaux — Espace employé</span>
    <a href="../php/deconnexion.php" class="text-warning">Se déconnecter</a>
  </div>
</nav>
<div class="container my-5 flex-grow-1">
  <a href="gestion-plats.php" class="btn btn-outline-dark mb-4">← Retour</a>
  <h1 class="mb-4">Ajouter un plat</h1>
  <form action="../php/ajouter-plat.php" method="POST">
    <div class="mb-3">
      <label class="form-label">Titre du plat</label>
      <input type="text" name="titre_plat" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Prix (€)</label>
      <input type="number" step="0.01" name="prix" class="form-control" min="0" required>
    </div>
    <button type="submit" class="btn btn-dark">Créer le plat</button>
  </form>
</div>
</body>
</html>
