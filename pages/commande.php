<?php
session_start();
require_once '../php/config.php';

// Sécurité : seul un utilisateur connecté peut commander
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: connexion.php");
    exit;
}

$utilisateur_id = $_SESSION['utilisateur_id'];

// Récupérer les infos du compte pour pré-remplir le formulaire
$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE utilisateur_id = ?");
$stmt->execute([$utilisateur_id]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupérer le menu si on vient du bouton "commander" (?menu_id=X dans l'URL)
$menu_id = $_GET['menu_id'] ?? null;
$menu = null;

if ($menu_id) {
    $stmt = $pdo->prepare("SELECT * FROM menu WHERE menu_id = ?");
    $stmt->execute([$menu_id]);
    $menu = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Récupérer la liste de tous les menus pour le select (si pas de menu pré-sélectionné)
$menus = $pdo->query("SELECT menu_id, nom, nombre_personne_minimum, prix_par_personne FROM menu")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commander - Saveurs de Bordeaux</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
<div class="container my-5 flex-grow-1">
  <div class="row justify-content-center">
    <div class="col-md-7">
      <div class="card p-4">
        <h2 class="text-center mb-4">Passer une commande</h2>

        <form action="../php/commande.php" method="POST" id="formCommande">

          <div class="mb-3">
            <label class="form-label">Menu</label>
            <select name="menu_id" id="menu_id" class="form-select" required <?= $menu ? 'disabled' : '' ?>>
              <option value="">-- Choisir un menu --</option>
              <?php foreach ($menus as $m): ?>
                <option
                  value="<?= $m['menu_id'] ?>"
                  data-min="<?= $m['nombre_personne_minimum'] ?>"
                  data-prix="<?= $m['prix_par_personne'] ?>"
                  <?= ($menu && $menu['menu_id'] == $m['menu_id']) ? 'selected' : '' ?>
                >
                  <?= htmlspecialchars($m['nom']) ?> (min. <?= $m['nombre_personne_minimum'] ?> pers. - <?= $m['prix_par_personne'] ?>€/pers.)
                </option>
              <?php endforeach; ?>
            </select>
            <?php if ($menu): ?>
              <input type="hidden" name="menu_id" value="<?= $menu['menu_id'] ?>">
            <?php endif; ?>
          </div>

          <div class="row mb-3">
            <div class="col-md-4">
              <label class="form-label">Nom</label>
              <input type="text" class="form-control" value="<?= htmlspecialchars($utilisateur['nom']) ?>" readonly>
            </div>
            <div class="col-md-4">
              <label class="form-label">Prénom</label>
              <input type="text" class="form-control" value="<?= htmlspecialchars($utilisateur['prenom']) ?>" readonly>
            </div>
            <div class="col-md-4">
              <label class="form-label">GSM</label>
              <input type="text" class="form-control" value="<?= htmlspecialchars($utilisateur['telephone']) ?>" readonly>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" value="<?= htmlspecialchars($utilisateur['email']) ?>" readonly>
          </div>

          <div class="mb-3">
            <label class="form-label">Adresse de la prestation</label>
            <input type="text" name="adresse_livraison" class="form-control" required>
          </div>

          <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="hors_bordeaux" name="hors_bordeaux" value="1">
            <label class="form-check-label" for="hors_bordeaux">Livraison hors de Bordeaux</label>
          </div>

          <div class="mb-3" id="km_wrapper" style="display:none;">
            <label class="form-label">Distance depuis Bordeaux (km)</label>
            <input type="number" name="nombre_km" id="nombre_km" class="form-control" min="0" value="0">
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Date de la prestation</label>
              <input type="date" name="date_prestation" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Heure de livraison souhaitée</label>
              <input type="time" name="heure_livraison" class="form-control" required>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Nombre de personnes</label>
            <input type="number" name="nombre_personne" id="nombre_personne" class="form-control" required>
            <small class="text-muted" id="min_info"></small>
          </div>

          <div class="card bg-light p-3 mb-3">
            <h5>Récapitulatif</h5>
            <p class="mb-1">Prix du menu : <span id="prix_menu_affiche">0.00</span> €</p>
            <p class="mb-1">Prix de la livraison : <span id="prix_livraison_affiche">0.00</span> €</p>
            <p class="mb-0 fw-bold">Total : <span id="prix_total_affiche">0.00</span> €</p>
          </div>

          <button type="submit" class="btn btn-dark w-100">Valider la commande</button>
        </form>

      </div>
    </div>
  </div>
</div>

<script>
const menuSelect = document.getElementById('menu_id');
const nbPersonneInput = document.getElementById('nombre_personne');
const horsBordeaux = document.getElementById('hors_bordeaux');
const kmWrapper = document.getElementById('km_wrapper');
const kmInput = document.getElementById('nombre_km');
const minInfo = document.getElementById('min_info');

function getSelectedOption() {
  return menuSelect.options[menuSelect.selectedIndex];
}

function updatePrix() {
  const option = getSelectedOption();
  if (!option || !option.value) return;

  const min = parseInt(option.dataset.min);
  const prixParPersonne = parseFloat(option.dataset.prix);
  let nbPersonne = parseInt(nbPersonneInput.value) || 0;

  minInfo.textContent = `Minimum requis pour ce menu : ${min} personnes`;
  nbPersonneInput.min = min;

  let prixMenu = prixParPersonne * nbPersonne;

  // Réduction de 10% si nb personnes >= min + 5
  if (nbPersonne >= min + 5) {
    prixMenu = prixMenu * 0.9;
  }

  // Prix livraison
  let prixLivraison = 0;
  if (horsBordeaux.checked) {
    const km = parseFloat(kmInput.value) || 0;
    prixLivraison = 5 + (0.59 * km);
  }

  document.getElementById('prix_menu_affiche').textContent = prixMenu.toFixed(2);
  document.getElementById('prix_livraison_affiche').textContent = prixLivraison.toFixed(2);
  document.getElementById('prix_total_affiche').textContent = (prixMenu + prixLivraison).toFixed(2);
}

horsBordeaux.addEventListener('change', () => {
  kmWrapper.style.display = horsBordeaux.checked ? 'block' : 'none';
  updatePrix();
});

menuSelect.addEventListener('change', updatePrix);
nbPersonneInput.addEventListener('input', updatePrix);
kmInput.addEventListener('input', updatePrix);

// Calcul initial si un menu est déjà pré-sélectionné
window.addEventListener('DOMContentLoaded', updatePrix);
</script>

</body>
</html>
