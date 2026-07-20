<?php
session_start();
require_once '../php/config.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: connexion.php");
    exit;
}

$utilisateur_id = $_SESSION['utilisateur_id'];

// Récupérer les infos actuelles de l'utilisateur pour pré-remplir
$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE utilisateur_id = ?");
$stmt->execute([$utilisateur_id]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon profil - Saveurs de Bordeaux</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">Saveurs de Bordeaux</a>
    <div>
      <a class="nav-link d-inline text-white" href="dashboard-utilisateur.php">Mes commandes</a>
      <a class="nav-link d-inline text-warning" href="../php/deconnexion.php">Se déconnecter</a>
    </div>
  </div>
</nav>

<div class="container my-5 flex-grow-1">
  <div class="row justify-content-center">
    <div class="col-md-7">
      <div class="card p-4">
        <h1 class="mb-4">Mon profil</h1>

        <?php if (isset($_GET['success'])): ?>
          <div class="alert alert-success">Vos informations ont été mises à jour.</div>
        <?php endif; ?>

        <form action="../php/modifier-profil.php" method="POST">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Nom</label>
              <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($utilisateur['ville'] ?? '') ?>" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Prénom</label>
              <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($utilisateur['prenom'] ?? '') ?>" required>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($utilisateur['email'] ?? '') ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Téléphone</label>
            <input type="text" name="telephone" class="form-control" value="<?= htmlspecialchars($utilisateur['telephone'] ?? '') ?>">
          </div>

          <div class="mb-3">
            <label class="form-label">Adresse postale</label>
            <input type="text" name="adresse_postale" class="form-control" value="<?= htmlspecialchars($utilisateur['adresse_postale'] ?? '') ?>">
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Ville</label>
              <input type="text" name="ville" class="form-control" value="<?= htmlspecialchars($utilisateur['ville'] ?? '') ?>">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Pays</label>
              <input type="text" name="pays" class="form-control" value="<?= htmlspecialchars($utilisateur['pays'] ?? '') ?>">
            </div>
          </div>

          <button type="submit" class="btn btn-dark">Enregistrer les modifications</button>
        </form>
      </div>
    </div>
  </div>
</div>

</body>
</html>
