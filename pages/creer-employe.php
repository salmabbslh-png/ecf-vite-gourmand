<?php
require_once '../php/verif-admin.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un employé - Saveurs de Bordeaux</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
<nav class="navbar navbar-dark bg-dark">
  <div class="container">
    <span class="navbar-brand">Saveurs de Bordeaux — Espace admin</span>
    <a href="../php/deconnexion.php" class="text-warning">Se déconnecter</a>
  </div>
</nav>
<div class="container my-5 flex-grow-1">
  <a href="gestion-employes.php" class="btn btn-outline-dark mb-4">← Retour</a>
  <h1 class="mb-4">Créer un compte employé</h1>

  <?php if (isset($_GET['erreur']) && $_GET['erreur'] === 'email'): ?>
    <div class="alert alert-danger">Cet email est déjà utilisé.</div>
  <?php endif; ?>

  <div class="alert alert-info">
    Le mot de passe ne sera pas envoyé par email à l'employé. Il devra vous le demander directement.
  </div>

  <form action="../php/creer-employe.php" method="POST">
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Nom</label>
        <input type="text" name="nom" class="form-control" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Prénom</label>
        <input type="text" name="prenom" class="form-control" required>
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Mot de passe</label>
      <input type="password" name="password" class="form-control" required>
      <small class="text-muted">Min. 10 caractères, 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial.</small>
    </div>
    <button type="submit" class="btn btn-dark">Créer le compte</button>
  </form>
</div>
</body>
</html>
