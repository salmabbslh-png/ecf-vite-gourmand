<?php
session_start();
require_once 'config.php';

// Vérifier que l'utilisateur est connecté ET qu'il est employé (2) ou admin (1)
if (!isset($_SESSION['utilisateur_id']) || !in_array($_SESSION['role'], [1, 2])) {
    header("Location: ../pages/connexion.php");
    exit;
}
