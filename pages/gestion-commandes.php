<?php
require_once '../php/verif-employe.php';

// Récupérer toutes les commandes avec le nom du menu et les infos du client
$commandes = $pdo->query("
    SELECT commande.*, menu.nom AS nom_menu, utilisateur.nom AS nom_client, utilisateur.prenom AS prenom_client
    FROM commande
    JOIN menu ON commande.menu_id = menu.menu_id
    JOIN utilisateur ON commande.utilisateur_id = utilisateur.utilisateur_id
    ORDER BY commande.date_commande DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Liste ordonnée des statuts possibles (pour le menu déroulant)
$statuts_possibles = ['en attente', 'accepté', 'en préparation', 'en cours de livraison', 'livré', 'en attente du retour de matériel', 'terminée'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des commandes - Saveurs de Bordeaux</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <span class="navbar-brand">Saveurs de Bordeaux — Espace employé</span>
    <div>
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
  <h1 class="mb-4">Gestion des commandes</h1>

  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Statut mis à jour avec succès.</div>
  <?php endif; ?>

  <table class="table table-striped align-middle">
    <thead>
      <tr>
        <th>N° commande</th>
        <th>Client</th>
        <th>Menu</th>
        <th>Date prestation</th>
        <th>Personnes</th>
        <th>Statut actuel</th>
        <th>Changer le statut</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($commandes as $commande): ?>
        <tr>
          <td><?= htmlspecialchars($commande['numero_commande']) ?></td>
          <td><?= htmlspecialchars($commande['prenom_client']) ?> <?= htmlspecialchars($commande['nom_client']) ?></td>
          <td><?= htmlspecialchars($commande['nom_menu']) ?></td>
          <td><?= htmlspecialchars($commande['date_prestation']) ?></td>
          <td><?= htmlspecialchars($commande['nombre_personne']) ?></td>
          <td><span class="badge bg-info text-dark"><?= htmlspecialchars($commande['statut']) ?></span></td>
          <td>
                <form action="../php/changer-statut.php" method="POST" class="d-flex gap-2">
                  <input type="hidden" name="commande_id" value="<?= $commande['commande_id'] ?>">
                  <select name="nouveau_statut" class="form-select form-select-sm">
                    <?php foreach ($statuts_possibles as $statut): ?>
                      <option value="<?= $statut ?>" <?= ($commande['statut'] === $statut) ? 'selected' : '' ?>>
                        <?= ucfirst($statut) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                  <button type="submit" class="btn btn-sm btn-dark">OK</button>
                </form>
                <a href="annuler-commande.php?commande_id=<?= $commande['commande_id'] ?>" class="btn btn-sm btn-danger mt-1">Annuler</a>
              </td>

        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

</body>
</html>
