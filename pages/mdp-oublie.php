<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mot de passe oublié - Saveurs de Bordeaux</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
<div class="container my-5 flex-grow-1">
<div class="row justify-content-center">
<div class="col-md-5">
<div class="card p-4">
<h2 class="text-center mb-3">Mot de passe oublié</h2>
<form action="../php/mdp-oublie.php" method="POST">
<div class="mb-3">
<label class="form-label">Adresse email</label>
<input type="email" name="email" class="form-control" placeholder="votre@email.com" required>
</div>
<button type="submit" class="btn btn-dark w-100">Envoyer le lien</button>
</form>
</div>
</div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
