<?php
require_once '../php/verif-employe.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un menu - Saveurs de Bordeaux</title>
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
  <a href="gestion-menus.php" class="btn btn-outline-dark mb-4">← Retour</a>
  <h1 class="mb-4">Ajouter un menu</h1>

  <form action="../php/ajouter-menu.php" method="POST">

    <div class="mb-3">
      <label class="form-label">Nom du menu</label>
      <input type="text" name="nom" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea name="description" class="form-control" rows="3"></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Régime</label>
      <input type="text" name="regime" class="form-control">
    </div>

    <div class="row">
      <div class="col-md-4 mb-3">
        <label class="form-label">Nombre de personnes minimum</label>
        <input type="number" name="nombre_personne_minimum" class="form-control" min="1" required>
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">Prix par personne (€)</label>
        <input type="number" step="0.01" name="prix_par_personne" class="form-control" min="0" required>
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">Stock (quantité restante)</label>
        <input type="number" name="quantite_restante" class="form-control" min="0" value="0">
      </div>
    </div>

    <button type="submit" class="btn btn-dark">Créer le menu</button>
  </form>
</div>

</body>
</html>
