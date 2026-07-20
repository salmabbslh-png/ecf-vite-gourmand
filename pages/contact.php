<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact - Saveurs de Bordeaux</title>
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
          <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
          <li class="nav-item"><a class="nav-link" href="menus.php">Nos Menus</a></li>
          <li class="nav-item"><a class="nav-link active" href="#">Contact</a></li>
          <li class="nav-item"><a class="nav-link" href="connexion.php">Connexion</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- CONTENU -->
  <div class="container my-5 flex-grow-1">
    <h1 class="text-center mb-5">Contactez-nous</h1>

    <?php if (isset($_GET['success'])): ?>
      <div class="alert alert-success text-center">Votre message a bien été envoyé. Nous vous répondrons rapidement.</div>
    <?php endif; ?>
    <?php if (isset($_GET['erreur'])): ?>
      <div class="alert alert-danger text-center">Une erreur est survenue lors de l'envoi. Veuillez réessayer.</div>
    <?php endif; ?>

    <div class="row">

      <!-- COORDONNÉES -->
      <div class="col-md-5">
        <div class="card p-4 h-100">
          <h4>Saveurs de Bordeaux</h4>
          <p>📍 12 Rue des Saveurs, 33000 Bordeaux</p>
          <p>📞 05 56 00 00 00</p>
          <p>📧 contact@saveurs-bordeaux.fr</p>
          <hr>
          <h5>Horaires</h5>
          <p>Lundi - Vendredi : 9h - 18h</p>
          <p>Samedi : 9h - 12h</p>
          <p>Dimanche : Fermé</p>
        </div>
      </div>

      <!-- FORMULAIRE -->
      <div class="col-md-7">
        <div class="card p-4">
          <h4 class="mb-4">Envoyer un message</h4>
          <form action="../php/contact.php" method="POST">
            <div class="mb-3">
              <label class="form-label">Titre</label>
              <input type="text" name="titre" class="form-control" placeholder="Objet de votre message" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Votre email</label>
              <input type="email" name="email" class="form-control" placeholder="votre@email.com" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Message</label>
              <textarea name="message" class="form-control" rows="5" placeholder="Décrivez votre demande..." required></textarea>
            </div>
            <button type="submit" class="btn btn-dark w-100">Envoyer</button>
          </form>
        </div>
      </div>

    </div>
  </div>

  <!-- FOOTER -->
  <footer class="bg-dark text-white text-center py-3 mt-auto">
    <p class="mb-0">© 2026 Saveurs de Bordeaux | Lundi - Dimanche</p>
    <a href="mentions-legales.html" class="text-warning">Mentions légales</a> |
    <a href="cgv.html" class="text-warning">CGV</a>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
