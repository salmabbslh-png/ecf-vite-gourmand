<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Saveurs de Bordeaux</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="index.php">Saveurs de Bordeaux</a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
          <li class="nav-item"><a class="nav-link" href="menus.php">Nos Menus</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
          <li class="nav-item"><a class="nav-link" href="connexion.php">Connexion</a></li>
        </ul>
      </div>
    </div>
  </nav>

<!-- CONTENU -->
  <div class="container my-5 flex-grow-1">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card p-4">
          <h2 class="text-center mb-4">Connexion</h2>
          <form action="../php/connexion.php" method="POST">
            <div class="mb-3">
              <label class="form-label">Adresse email</label>
              <input type="email" class="form-control" placeholder="votre@email.com" name="email">
            </div>
            <div class="mb-3">
              <label class="form-label">Mot de passe</label>
              <input type="password" class="form-control" placeholder="Votre mot de passe" name="password">
            </div>
            <div class="mb-3 text-end">
              <a href="mdp-oublie.php">Mot de passe oublié ?</a>
            </div>
            <button type="submit" class="btn btn-dark w-100">Se connecter</button>
          </form>
          <hr>
          <p class="text-center mt-2">Pas encore de compte ? <a href="inscription.php">S'inscrire</a></p>
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
