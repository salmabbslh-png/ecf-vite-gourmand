<?php
require_once 'config.php';

// On indique au navigateur que la réponse est du JSON, pas une page HTML
header('Content-Type: application/json; charset=utf-8');

// On récupère les 6 derniers avis validés, avec le prénom de leur auteur 
$stmt = $pdo->query("
    SELECT avis.note, avis.description, utilisateur.prenom
    FROM avis
    JOIN utilisateur ON avis.utilisateur_id = utilisateur.utilisateur_id
    WHERE avis.statut = 'validé'
    ORDER BY avis.avis_id DESC
    LIMIT 6
");

// On transforme le résultat en JSON et on l'envoie
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);