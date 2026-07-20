<?php
require_once 'connexion.php';

$stmt = $pdo->query("SELECT * FROM menu");
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($menus as $menu) {
    echo $menu['nom'] . " - " . $menu['prix_par_personne'] . "€<br>";
}
?>