<?php
session_start();
require_once '../php/config.php';

$menu_id = $_GET['menu_id'] ?? null;
if (!$menu_id) {
    die("Aucun menu spécifié.");
}

// Récupérer le menu
$stmt = $pdo->prepare("SELECT * FROM menu WHERE menu_id = ?");
$stmt->execute([$menu_id]);
$menu = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$menu) {
    die("Menu introuvable.");
}

// L'utilisateur est-il connecté ?
$connecte = isset($_SESSION['utilisateur_id']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($menu['nom']) ?> - Saveurs de Bordeaux</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">Saveurs de Bordeaux</a>
    <div>
      <a class="nav-link d-inline text-white" href="index.php">Accueil</a>
      <a class="nav-link d-inline text-white" href="menus.php">Nos Menus</a>
      <a class="nav-link d-inline text-white" href="contact.php">Contact</a>
      <a class="nav-link d-inline text-white" href="connexion.php">Connexion</a>
    </div>
  </div>
</nav>

<div class="container my-5 flex-grow-1">
  <a href="menus.php" class="btn btn-outline-dark mb-4">← Retour aux menus</a>

  <div class="card p-4">
    <?php if (!empty($menu['image'])): ?>
    <img src="../images/<?= htmlspecialchars($menu['image']) ?>" class="mb-3 rounded" alt="<?= htmlspecialchars($menu['nom']) ?>" style="width:100%; height:300px; object-fit:cover;">
  <?php endif; ?>
    <h1><?= htmlspecialchars($menu['nom']) ?></h1>
    <p class="text-muted"><?= htmlspecialchars($menu['regime']) ?></p>

    <hr>

    <h5>Description</h5>
    <p><?= htmlspecialchars($menu['description']) ?></p>

    <div class="row mt-4">
      <div class="col-md-4">
        <div class="card bg-light p-3 text-center">
          <h6>Nombre de personnes minimum</h6>
          <p class="fs-4 mb-0"><?= htmlspecialchars($menu['nombre_personne_minimum']) ?></p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card bg-light p-3 text-center">
          <h6>Prix par personne</h6>
          <p class="fs-4 mb-0"><?= htmlspecialchars($menu['prix_par_personne']) ?> €</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card bg-light p-3 text-center">
          <h6>Stock disponible</h6>
          <p class="fs-4 mb-0"><?= htmlspecialchars($menu['quantite_restante']) ?></p>
        </div>
      </div>
    </div>

    <div class="alert alert-info mt-4">
      <strong>Conditions :</strong> commande minimum pour <?= htmlspecialchars($menu['nombre_personne_minimum']) ?> personnes.
      Une réduction de 10% est appliquée à partir de <?= $menu['nombre_personne_minimum'] + 5 ?> personnes.
    </div>

    <?php if ($connecte): ?>
      <a href="commande.php?menu_id=<?= $menu['menu_id'] ?>" class="btn btn-dark btn-lg mt-3">Commander ce menu</a>
    <?php else: ?>
      <div class="alert alert-warning mt-3">
        Vous devez être connecté pour commander ce menu.
      </div>
      <a href="connexion.php" class="btn btn-dark btn-lg">Se connecter</a>
      <a href="inscription.php" class="btn btn-outline-dark btn-lg">S'inscrire</a>
    <?php endif; ?>

  </div>
</div>

</body>
</html>
