<?php
require_once '../php/verif-admin.php';

// Récupérer tous les employés (role_id = 2)
$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE role_id = 2 ORDER BY utilisateur_id ASC");
$stmt->execute();
$employes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des employés - Saveurs de Bordeaux</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

  <div class="container">
    <span class="navbar-brand">Saveurs de Bordeaux — Espace admin</span>
    <div>
      <a class="nav-link d-inline text-white me-3" href="dashboard-admin-stats.php">Statistiques</a>
      <a class="nav-link d-inline text-white me-3" href="gestion-employes.php">Employés</a>
      <a class="nav-link d-inline text-white me-3" href="gestion-commandes.php">Commandes</a>
      <a class="nav-link d-inline text-white me-3" href="gestion-menus.php">Menus</a>
      <a class="nav-link d-inline text-white me-3" href="gestion-plats.php">Plats</a>
      <a class="nav-link d-inline text-white me-3" href="gestion-horaires.php">Horaires</a>
      <a class="nav-link d-inline text-white me-3" href="gestion-avis.php">Avis</a>
      <a class="nav-link d-inline text-warning" href="../php/deconnexion.php">Se déconnecter</a>
    </div>
  </div>
</nav>

<div class="container my-5 flex-grow-1">
  <h1 class="mb-4">Gestion des employés</h1>
  <a href="creer-employe.php" class="btn btn-success mb-3">+ Créer un compte employé</a>

  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Opération réalisée avec succès.</div>
  <?php endif; ?>

  <table class="table table-striped align-middle">
    <thead>
      <tr><th>Email</th><th>Nom</th><th>Prénom</th><th>Statut</th><th>Action</th></tr>
    </thead>
    <tbody>
      <?php foreach ($employes as $employe): ?>
        <tr>
          <td><?= htmlspecialchars($employe['email']) ?></td>
          <td><?= htmlspecialchars($employe['nom']) ?></td>
          <td><?= htmlspecialchars($employe['prenom']) ?></td>
          <td>
            <?php if ($employe['actif'] == 1): ?>
              <span class="badge bg-success">Actif</span>
            <?php else: ?>
              <span class="badge bg-danger">Désactivé</span>
            <?php endif; ?>
          </td>
          <td>
            <?php if ($employe['actif'] == 1): ?>
              <a href="../php/desactiver-employe.php?utilisateur_id=<?= $employe['utilisateur_id'] ?>"
                 class="btn btn-sm btn-danger"
                 onclick="return confirm('Désactiver ce compte employé ?');">Désactiver</a>
            <?php else: ?>
              <a href="../php/activer-employe.php?utilisateur_id=<?= $employe['utilisateur_id'] ?>"
                 class="btn btn-sm btn-success">Réactiver</a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>
