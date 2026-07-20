<?php
require_once '../php/verif-employe.php';
$plat_id = $_GET['plat_id'] ?? null;
if (!$plat_id) { die("Aucun plat spécifié."); }
$stmt = $pdo->prepare("SELECT * FROM plat WHERE plat_id = ?");
$stmt->execute([$plat_id]);
$plat = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$plat) { die("Plat introuvable."); }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un plat - Saveurs de Bordeaux</title>
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
  <a href="gestion-plats.php" class="btn btn-outline-dark mb-4">← Retour</a>
  <h1 class="mb-4">Modifier le plat</h1>
  <form action="../php/modifier-plat.php" method="POST">
    <input type="hidden" name="plat_id" value="<?= $plat['plat_id'] ?>">
    <div class="mb-3">
      <label class="form-label">Titre du plat</label>
      <input type="text" name="titre_plat" class="form-control" value="<?= htmlspecialchars($plat['titre_plat']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Prix (€)</label>
      <input type="number" step="0.01" name="prix" class="form-control" value="<?= htmlspecialchars($plat['prix']) ?>" min="0" required>
    </div>
    <button type="submit" class="btn btn-dark">Enregistrer les modifications</button>
  </form>
</div>
</body>
</html>
