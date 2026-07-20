<?php
session_start();
require_once '../php/config.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: connexion.php");
    exit;
}

$utilisateur_id = $_SESSION['utilisateur_id'];
$commande_id = $_GET['commande_id'] ?? null;

if (!$commande_id) {
    die("Aucune commande spécifiée.");
}

// Vérifier que la commande appartient à l'utilisateur ET qu'elle est terminée
$stmt = $pdo->prepare("
    SELECT commande.*, menu.nom AS nom_menu
    FROM commande
    JOIN menu ON commande.menu_id = menu.menu_id
    WHERE commande.commande_id = ? AND commande.utilisateur_id = ?
");
$stmt->execute([$commande_id, $utilisateur_id]);
$commande = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$commande) {
    die("Commande introuvable ou accès non autorisé.");
}

if ($commande['statut'] !== 'terminée') {
    die("Vous ne pouvez laisser un avis que sur une commande terminée.");
}

// Vérifier qu'un avis n'a pas déjà été laissé pour cette commande
$stmt = $pdo->prepare("SELECT COUNT(*) FROM avis WHERE commande_id = ?");
$stmt->execute([$commande_id]);
if ($stmt->fetchColumn() > 0) {
    die("Vous avez déjà laissé un avis pour cette commande.");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laisser un avis - Saveurs de Bordeaux</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
<nav class="navbar navbar-dark bg-dark">
  <div class="container">
    <span class="navbar-brand">Saveurs de Bordeaux</span>
    <a href="../php/deconnexion.php" class="text-warning">Se déconnecter</a>
  </div>
</nav>
<div class="container my-5 flex-grow-1">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card p-4">
        <h1 class="mb-4">Votre avis</h1>
        <p class="text-muted">Commande : <?= htmlspecialchars($commande['nom_menu']) ?></p>

        <form action="../php/laisser-avis.php" method="POST">
          <input type="hidden" name="commande_id" value="<?= $commande['commande_id'] ?>">

          <div class="mb-3">
            <label class="form-label">Note</label>
            <select name="note" class="form-select" required>
              <option value="">-- Choisir une note --</option>
              <option value="1">⭐ (1/5)</option>
              <option value="2">⭐⭐ (2/5)</option>
              <option value="3">⭐⭐⭐ (3/5)</option>
              <option value="4">⭐⭐⭐⭐ (4/5)</option>
              <option value="5">⭐⭐⭐⭐⭐ (5/5)</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Commentaire</label>
            <textarea name="description" class="form-control" rows="4" required></textarea>
          </div>

          <button type="submit" class="btn btn-dark w-100">Envoyer mon avis</button>
        </form>
      </div>
    </div>
  </div>
</div>
</body>
</html>
