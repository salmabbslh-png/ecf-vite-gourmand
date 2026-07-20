<?php
// Détection de l'environnement : local (MAMP) ou en ligne (Alwaysdata)
$estEnLigne = (strpos($_SERVER['HTTP_HOST'] ?? '', 'alwaysdata') !== false);

if ($estEnLigne) {
    // Configuration EN LIGNE (Alwaysdata)
    $host = 'mysql-benbousselham.alwaysdata.net';
    $port = '3306';
    $dbname = 'benbousselham_saveurs_bordeaux';
    $username = 'benbousselham';
    $password = 'TON_MOT_DE_PASSE_ALWAYSDATA';
} else {
    // Configuration LOCALE (MAMP)
    $host = 'localhost';
    $port = '8889';
    $dbname = 'saveurs_bordeaux';
    $username = 'root';
    $password = 'root';
}

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8",
        $username,
        $password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}