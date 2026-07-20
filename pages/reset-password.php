<?php
require_once '../php/config.php';

$token = $_GET['token'] ?? '';

if (empty($token)) {
    die("Lien invalide : aucun token fourni.");
}

$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE reset_token = ?");
$stmt->execute([$token]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur) {
    die("Lien invalide ou déjà utilisé.");
}

if ($utilisateur['reset_token_expire'] < date('Y-m-d H:i:s')) {
    die("Ce lien a expiré. Veuillez refaire une demande de réinitialisation.");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe - Saveurs de Bordeaux</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
<div class="container my-5 flex-grow-1">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card p-4">
        <h2 class="text-center mb-3">Nouveau mot de passe</h2>
        <form action="../php/reset-password.php" method="POST">
          <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
          <div class="mb-3">
            <label class="form-label">Nouveau mot de passe</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Confirmer le mot de passe</label>
            <input type="password" name="confirm_password" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-dark w-100">Réinitialiser</button>
        </form>
      </div>
    </div>
  </div>
</div>
</body>
</html>
