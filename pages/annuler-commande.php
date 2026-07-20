<?php
require_once '../php/verif-employe.php';

$commande_id = $_GET['commande_id'] ?? null;

if (!$commande_id) {
    die("Aucune commande spécifiée.");
}

// Récupérer la commande + infos client pour affichage
$stmt = $pdo->prepare("
    SELECT commande.*, menu.nom AS nom_menu, utilisateur.nom AS nom_client, utilisateur.prenom AS prenom_client, utilisateur.telephone, utilisateur.email
    FROM commande
    JOIN menu ON commande.menu_id = menu.menu_id
    JOIN utilisateur ON commande.utilisateur_id = utilisateur.utilisateur_id
    WHERE commande.commande_id = ?
");
$stmt->execute([$commande_id]);
$commande = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$commande) {
    die("Commande introuvable.");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Annuler une commande - Saveurs de Bordeaux</title>
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
  <a href="gestion-commandes.php" class="btn btn-outline-dark mb-4">← Retour</a>
  <h1 class="mb-4">Annuler la commande</h1>

  <div class="alert alert-warning">
    <strong>Rappel :</strong> vous devez avoir contacté le client avant d'annuler.
  </div>

  <div class="card p-3 mb-4">
    <p><strong>Commande :</strong> <?= htmlspecialchars($commande['numero_commande']) ?></p>
    <p><strong>Client :</strong> <?= htmlspecialchars($commande['prenom_client']) ?> <?= htmlspecialchars($commande['nom_client']) ?></p>
    <p><strong>GSM :</strong> <?= htmlspecialchars($commande['telephone']) ?> — <strong>Email :</strong> <?= htmlspecialchars($commande['email']) ?></p>
    <p><strong>Menu :</strong> <?= htmlspecialchars($commande['nom_menu']) ?></p>
  </div>

  <form action="../php/annuler-commande.php" method="POST">
    <input type="hidden" name="commande_id" value="<?= $commande['commande_id'] ?>">

    <div class="mb-3">
      <label class="form-label">Mode de contact utilisé <span class="text-danger">*</span></label>
      <select name="mode_contact" class="form-select" required>
        <option value="">-- Choisir --</option>
        <option value="appel GSM">Appel GSM</option>
        <option value="email">Email</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Motif de l'annulation <span class="text-danger">*</span></label>
      <textarea name="motif_annulation" class="form-control" rows="3" required></textarea>
    </div>

    <button type="submit" class="btn btn-danger">Confirmer l'annulation</button>
  </form>
</div>

</body>
</html>
