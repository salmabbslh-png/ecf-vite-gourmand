<?php
require_once '../php/verif-admin.php';

$mongoDisponible = false;
$labels = [];
$donnees_commandes = [];
$donnees_ca = [];

try {
    require_once '../vendor/autoload.php';
    $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
    $collectionMongo = $mongoClient->saveurs_bordeaux->commandes;
    $resultats = $collectionMongo->aggregate([
        ['$group' => [
            '_id' => '$nom_menu',
            'nombre_commandes' => ['$sum' => 1],
            'chiffre_affaires' => ['$sum' => '$prix_total']
        ]],
        ['$sort' => ['nombre_commandes' => -1]]
    ]);
    foreach ($resultats as $ligne) {
        $labels[] = $ligne['_id'];
        $donnees_commandes[] = $ligne['nombre_commandes'];
        $donnees_ca[] = $ligne['chiffre_affaires'];
    }
    $mongoDisponible = true;
} catch (Throwable $e) {
    error_log("MongoDB indisponible : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Statistiques - Saveurs de Bordeaux</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="d-flex flex-column min-vh-100">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <span class="navbar-brand">Saveurs de Bordeaux — Espace admin</span>
    <div>
      <a class="nav-link d-inline text-white me-3" href="dashboard-admin-stats.php">Statistiques</a>
      <a class="nav-link d-inline text-white me-3" href="gestion-employes.php">Employes</a>
      <a class="nav-link d-inline text-white me-3" href="gestion-commandes.php">Commandes</a>
      <a class="nav-link d-inline text-white me-3" href="gestion-menus.php">Menus</a>
      <a class="nav-link d-inline text-white me-3" href="gestion-plats.php">Plats</a>
      <a class="nav-link d-inline text-white me-3" href="gestion-horaires.php">Horaires</a>
      <a class="nav-link d-inline text-white me-3" href="gestion-avis.php">Avis</a>
      <a class="nav-link d-inline text-warning" href="../php/deconnexion.php">Se deconnecter</a>
    </div>
  </div>
</nav>
<div class="container my-5 flex-grow-1">
  <h1 class="mb-4">Statistiques des commandes</h1>
  <?php if ($mongoDisponible): ?>
    <p class="text-muted">Donnees issues de la base NoSQL (MongoDB)</p>
    <div class="card p-4 mb-4">
      <h3>Nombre de commandes par menu</h3>
      <canvas id="graphiqueCommandes"></canvas>
    </div>
    <div class="card p-4">
      <h3>Chiffre d'affaires par menu (EUR)</h3>
      <canvas id="graphiqueCA"></canvas>
    </div>
  <?php else: ?>
    <div class="alert alert-warning">
      Les statistiques issues de la base NoSQL (MongoDB) ne sont pas disponibles dans cet environnement.
      Cette fonctionnalite est operationnelle en environnement local.
    </div>
  <?php endif; ?>
</div>
<?php if ($mongoDisponible): ?>
<script>
const labels = <?= json_encode($labels) ?>;
const donneesCommandes = <?= json_encode($donnees_commandes) ?>;
const donneesCA = <?= json_encode($donnees_ca) ?>;
new Chart(document.getElementById('graphiqueCommandes'), {
  type: 'bar',
  data: { labels: labels, datasets: [{ label: 'Nombre de commandes', data: donneesCommandes, backgroundColor: 'rgba(54,162,235,0.6)' }] }
});
new Chart(document.getElementById('graphiqueCA'), {
  type: 'bar',
  data: { labels: labels, datasets: [{ label: "Chiffre d'affaires (EUR)", data: donneesCA, backgroundColor: 'rgba(75,192,192,0.6)' }] }
});
</script>
<?php endif; ?>
</body>
</html>
