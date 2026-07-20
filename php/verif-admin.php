<?php
session_start();
require_once 'config.php';

// Seul l'admin (role_id = 1) peut accéder
if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] != 1) {
    header("Location: ../pages/connexion.php");
    exit;
}
