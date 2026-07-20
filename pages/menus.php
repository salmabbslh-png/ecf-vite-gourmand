<?php
require_once '../php/config.php';
$menus = $pdo->query("SELECT * FROM menu ORDER BY menu_id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Menus - Saveurs de Bordeaux</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">Saveurs de Bordeaux</a>
    <div>
      <a class="nav-link d-inline text-white me-3" href="index.php">Accueil</a>
      <a class="nav-link d-inline text-white me-3" href="contact.php">Contact</a>
      <a class="nav-link d-inline text-white me-3" href="connexion.php">Connexion</a>
    </div>
  </div>
</nav>
<div class="container my-5 flex-grow-1">
  <h1 class="text-center mb-4">Nos Menus</h1>
  <div class="card p-3 mb-4">
    <div class="row g-3">
      <div class="col-md-3">
        <label class="form-label">Prix maximum (€)</label>
        <input type="number" id="filtrePrixMax" class="form-control" placeholder="Ex: 100">
      </div>
      <div class="col-md-3">
        <label class="form-label">Prix minimum (€)</label>
        <input type="number" id="filtrePrixMin" class="form-control" placeholder="Ex: 50">
      </div>
      <div class="col-md-3">
        <label class="form-label">Régime</label>
        <select id="filtreRegime" class="form-select">
          <option value="">Tous</option>
          <option value="Classique">Classique</option>
          <option value="Végétarien">Végétarien</option>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Nombre de personnes min.</label>
        <input type="number" id="filtrePersonnes" class="form-control" placeholder="Ex: 10">
      </div>
    </div>
    <button id="resetFiltres" class="btn btn-outline-secondary btn-sm mt-3">Réinitialiser les filtres</button>
  </div>
  <div class="row" id="listeMenus">
    <?php foreach ($menus as $menu): ?>
      <div class="col-md-4 mb-4 carte-menu"
           data-prix="<?= $menu['prix_par_personne'] ?>"
           data-regime="<?= htmlspecialchars($menu['regime']) ?>"
           data-personnes="<?= $menu['nombre_personne_minimum'] ?>">
        <div class="card h-100 p-3">
          <?php if (!empty($menu['image'])): ?>
            <img src="../images/<?= htmlspecialchars($menu['image']) ?>" class="card-img-top mb-2" alt="<?= htmlspecialchars($menu['nom']) ?>" style="width:100%; height:200px; object-fit:cover;">
          <?php endif; ?>
          <h4><?= htmlspecialchars($menu['nom']) ?></h4>
          <p class="text-muted"><?= htmlspecialchars($menu['description']) ?></p>
          <p><strong>À partir de <?= $menu['nombre_personne_minimum'] ?> personnes</strong></p>
          <p class="fs-5"><?= $menu['prix_par_personne'] ?> € / personne</p>
          <a href="detail-menu.php?menu_id=<?= $menu['menu_id'] ?>" class="btn btn-dark mt-auto">Voir le détail</a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <p id="aucunResultat" class="text-center text-muted" style="display:none;">Aucun menu ne correspond à vos critères.</p>
</div>
<script>
const cartes = document.querySelectorAll('.carte-menu');
const filtrePrixMax = document.getElementById('filtrePrixMax');
const filtrePrixMin = document.getElementById('filtrePrixMin');
const filtreRegime = document.getElementById('filtreRegime');
const filtrePersonnes = document.getElementById('filtrePersonnes');
const aucunResultat = document.getElementById('aucunResultat');

function appliquerFiltres() {
  const prixMax = parseFloat(filtrePrixMax.value) || Infinity;
  const prixMin = parseFloat(filtrePrixMin.value) || 0;
  const regime = filtreRegime.value;
  const personnes = parseInt(filtrePersonnes.value) || 0;
  let nbVisibles = 0;
  cartes.forEach(carte => {
    const prix = parseFloat(carte.dataset.prix);
    const regimeCarte = carte.dataset.regime;
    const personnesCarte = parseInt(carte.dataset.personnes);
    const okPrixMax = prix <= prixMax;
    const okPrixMin = prix >= prixMin;
    const okRegime = (regime === '') || (regimeCarte === regime);
    const okPersonnes = personnesCarte >= personnes;
    if (okPrixMax && okPrixMin && okRegime && okPersonnes) {
      carte.style.display = '';
      nbVisibles++;
    } else {
      carte.style.display = 'none';
    }
  });
  aucunResultat.style.display = (nbVisibles === 0) ? 'block' : 'none';
}
filtrePrixMax.addEventListener('input', appliquerFiltres);
filtrePrixMin.addEventListener('input', appliquerFiltres);
filtreRegime.addEventListener('change', appliquerFiltres);
filtrePersonnes.addEventListener('input', appliquerFiltres);
document.getElementById('resetFiltres').addEventListener('click', () => {
  filtrePrixMax.value = '';
  filtrePrixMin.value = '';
  filtreRegime.value = '';
  filtrePersonnes.value = '';
  appliquerFiltres();
});
</script>
</body>
</html>