<?php
require_once '../php/config.php';

// Récupérer les avis validés pour la page d'accueil
$avis_valides = $pdo->query("
    SELECT avis.note, avis.description, utilisateur.prenom
    FROM avis
    JOIN utilisateur ON avis.utilisateur_id = utilisateur.utilisateur_id
    WHERE avis.statut = 'validé'
    ORDER BY avis.avis_id DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Saveurs de Bordeaux - Traiteur événementiel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="index.php">Saveurs de Bordeaux</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link active" href="index.php">Accueil</a></li>
          <li class="nav-item"><a class="nav-link" href="menus.php">Nos Menus</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
          <li class="nav-item"><a class="nav-link" href="connexion.php">Connexion</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- HERO / PRÉSENTATION -->
  <section class="bg-dark text-white text-center py-5">
    <div class="container">
      <h1 class="display-4">Saveurs de Bordeaux</h1>
      <p class="lead">Votre traiteur événementiel pour tous vos moments d'exception</p>
      <a href="menus.php" class="btn btn-warning btn-lg mt-3">Découvrir nos menus</a>
    </div>
  </section>

  <!-- PRÉSENTATION ENTREPRISE -->
  <section class="container my-5">
    <div class="row align-items-center">
      <div class="col-md-12 text-center">
        <h2>Notre entreprise</h2>
        <p class="mt-3">
          Depuis notre création, Saveurs de Bordeaux met son savoir-faire au service de vos événements.
          Mariages, anniversaires, réceptions professionnelles : nous concevons des menus sur mesure,
          élaborés avec des produits frais et locaux, pour faire de chaque prestation un moment inoubliable.
        </p>
      </div>
    </div>
  </section>

  <!-- ÉQUIPE -->
  <section class="bg-light py-5">
    <div class="container text-center">
      <h2>Notre équipe</h2>
      <p>Des professionnels passionnés à votre service pour chaque événement.</p>
    </div>
  </section>

  <!-- AVIS CLIENTS -->
  <section class="container my-5">
    <h2 class="text-center mb-4">Avis clients</h2>
    <div class="row">
      <?php if (empty($avis_valides)): ?>
        <p class="text-center text-muted">Aucun avis pour le moment.</p>
      <?php else: ?>
        <?php foreach ($avis_valides as $avis): ?>
          <div class="col-md-4 mb-3">
            <div class="card p-3 h-100">
              <p>"<?= htmlspecialchars($avis['description']) ?>"</p>
              <strong>— <?= htmlspecialchars($avis['prenom']) ?> <?= str_repeat('⭐', (int)$avis['note']) ?></strong>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="bg-dark text-white text-center py-3 mt-auto">
    <p class="mb-0">© 2026 Saveurs de Bordeaux | Lundi - Dimanche</p>
    <a href="mentions-legales.html" class="text-warning">Mentions légales</a> |
    <a href="cgv.html" class="text-warning">CGV</a>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
