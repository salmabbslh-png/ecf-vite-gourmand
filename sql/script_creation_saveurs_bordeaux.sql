-- =====================================================================
--  Saveurs de Bordeaux - Script de création de la base de données
--  SGBD : MySQL 8 (ou MariaDB) - moteur InnoDB - encodage utf8mb4
--
--  Utilisation : créer d'abord une base VIDE (par exemple
--  "saveurs_bordeaux" dans phpMyAdmin), la sélectionner, puis importer
--  ce fichier (onglet Importer) ou coller son contenu dans l'onglet SQL.
--
--  Le script est en deux parties :
--    1. Création des tables (structure, clés primaires et étrangères)
--    2. Insertion des données de test
--
--  Les tables "parents" sont créées avant les tables qui les
--  référencent, sinon les clés étrangères seraient refusées.
-- =====================================================================

SET NAMES utf8mb4;

-- =====================================================================
--  1. CRÉATION DES TABLES
-- =====================================================================

-- ---------------------------------------------------------------------
-- Table role : les trois rôles de l'application
-- (1 = administrateur, 2 = employé, 3 = utilisateur)
-- ---------------------------------------------------------------------
CREATE TABLE role (
    role_id INT NOT NULL AUTO_INCREMENT,
    libelle VARCHAR(50),
    PRIMARY KEY (role_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- Table theme : les thèmes des menus (Noël, Pâques...)
-- ---------------------------------------------------------------------
CREATE TABLE theme (
    theme_id INT NOT NULL AUTO_INCREMENT,
    libelle VARCHAR(50),
    PRIMARY KEY (theme_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- Table regime : les régimes alimentaires proposés
-- ---------------------------------------------------------------------
CREATE TABLE regime (
    regime_id INT NOT NULL AUTO_INCREMENT,
    libelle VARCHAR(50),
    PRIMARY KEY (regime_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- Table utilisateur : tous les comptes (clients, employés, admin)
-- password : VARCHAR(255), car le mot de passe est stocké sous forme
--            de hachage bcrypt (password_hash), jamais en clair
-- reset_token / reset_token_expire : jeton de réinitialisation
--            du mot de passe et sa date d'expiration
-- actif : 1 = compte actif, 0 = compte désactivé par l'administrateur
-- ---------------------------------------------------------------------
CREATE TABLE utilisateur (
    utilisateur_id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(50),
    password VARCHAR(255),
    nom VARCHAR(50),
    prenom VARCHAR(50),
    telephone VARCHAR(50),
    ville VARCHAR(50),
    pays VARCHAR(50),
    adresse_postale VARCHAR(50),
    role_id INT,
    reset_token VARCHAR(255),
    reset_token_expire DATETIME,
    actif TINYINT(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (utilisateur_id),
    FOREIGN KEY (role_id) REFERENCES role (role_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- Table menu : les menus proposés par le traiteur
-- theme_id : clé étrangère vers la table theme
-- ---------------------------------------------------------------------
CREATE TABLE menu (
    menu_id INT NOT NULL AUTO_INCREMENT,
    nom VARCHAR(50),
    nombre_personne_minimum INT,
    prix_par_personne DOUBLE,
    regime VARCHAR(50),
    theme_id INT,
    description VARCHAR(255),
    quantite_restante INT,
    image VARCHAR(255),
    PRIMARY KEY (menu_id),
    CONSTRAINT fk_menu_theme FOREIGN KEY (theme_id) REFERENCES theme (theme_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- Table plat : les plats gérés par les employés
-- ---------------------------------------------------------------------
CREATE TABLE plat (
    plat_id INT NOT NULL AUTO_INCREMENT,
    titre_plat VARCHAR(50),
    prix DOUBLE,
    PRIMARY KEY (plat_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- Table allergene : liste des allergènes
-- ---------------------------------------------------------------------
CREATE TABLE allergene (
    allergene_id INT NOT NULL AUTO_INCREMENT,
    libelle VARCHAR(50),
    PRIMARY KEY (allergene_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- Table horaire : horaires d'ouverture (jour 1 = lundi ... 7 = dimanche)
-- ---------------------------------------------------------------------
CREATE TABLE horaire (
    horaire_id INT NOT NULL AUTO_INCREMENT,
    jour INT,
    heure_ouverture VARCHAR(50),
    heure_fermeture VARCHAR(50),
    PRIMARY KEY (horaire_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- Table commande : une commande passée par un utilisateur pour un menu
-- pret_materiel / restituer_materiel : 1 = oui, 0 = non
-- motif_annulation / mode_contact : renseignés quand un employé
--            modifie ou annule une commande après avoir contacté le client
-- ---------------------------------------------------------------------
CREATE TABLE commande (
    commande_id INT NOT NULL AUTO_INCREMENT,
    utilisateur_id INT NOT NULL,
    menu_id INT NOT NULL,
    numero_commande VARCHAR(50),
    date_commande DATE,
    date_prestation DATE,
    adresse_livraison VARCHAR(255),
    heure_livraison VARCHAR(50),
    nombre_personne INT,
    prix_livraison DOUBLE,
    nombre_km INT DEFAULT 0,
    prix_menu DOUBLE,
    statut VARCHAR(50),
    pret_materiel TINYINT(1),
    restituer_materiel TINYINT(1),
    motif_annulation VARCHAR(255),
    mode_contact VARCHAR(50),
    PRIMARY KEY (commande_id),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (utilisateur_id),
    FOREIGN KEY (menu_id) REFERENCES menu (menu_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- Table historique_statut : chaque changement de statut d'une commande,
-- avec sa date et son heure (pour le suivi de commande du client)
-- ---------------------------------------------------------------------
CREATE TABLE historique_statut (
    historique_id INT NOT NULL AUTO_INCREMENT,
    commande_id INT NOT NULL,
    statut VARCHAR(50) NOT NULL,
    date_changement DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (historique_id),
    FOREIGN KEY (commande_id) REFERENCES commande (commande_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- Table avis : avis laissé par un utilisateur sur une commande terminée
-- statut : "en attente" à la création, puis "validé" ou "refusé"
--          par un employé ; seuls les avis validés sont affichés
-- ---------------------------------------------------------------------
CREATE TABLE avis (
    avis_id INT NOT NULL AUTO_INCREMENT,
    note INT,
    description VARCHAR(255),
    statut VARCHAR(30),
    utilisateur_id INT,
    commande_id INT,
    PRIMARY KEY (avis_id),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (utilisateur_id),
    FOREIGN KEY (commande_id) REFERENCES commande (commande_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- =====================================================================
--  2. DONNÉES DE TEST
-- =====================================================================

INSERT INTO role (role_id, libelle) VALUES
(1, 'administrateur'),
(2, 'employe'),
(3, 'utilisateur');

INSERT INTO theme (theme_id, libelle) VALUES
(1, 'Noël'),
(2, 'Pâques'),
(3, 'Classique'),
(4, 'Événement');

INSERT INTO regime (regime_id, libelle) VALUES
(1, 'Végétarien'),
(2, 'Vegan'),
(3, 'Classique');

-- Comptes de test (un par rôle).
-- Les mots de passe sont hachés avec password_hash() de PHP.
-- Mots de passe en clair, pour les tests uniquement :
--   admin@saveurs-bordeaux.fr   -> Admin#Saveurs2026
--   employe@saveurs-bordeaux.fr -> Employe#Saveurs2026
--   client@saveurs-bordeaux.fr  -> Client#Saveurs2026
-- Le compte administrateur ne peut pas être créé depuis l'application :
-- il est créé directement en base, par ce script.
INSERT INTO utilisateur (utilisateur_id, email, password, nom, prenom, telephone, ville, pays, adresse_postale, role_id, actif) VALUES
(1, 'admin@saveurs-bordeaux.fr', '$2y$10$CZpY7QOtUwMT.iPePvLjwOcXSbMTPWdXsRtY3fVRPDm9CA4bK9Bu6', 'Martin', 'Julie', '0600000001', 'Bordeaux', 'France', '1 rue Sainte-Catherine', 1, 1),
(2, 'employe@saveurs-bordeaux.fr', '$2y$10$Rsof389.wNkDBk.ITlcy0eMr//WwDb./0kryRc.7OPxfhBSl2SOCS', 'Garcia', 'José', '0600000002', 'Bordeaux', 'France', '2 rue Sainte-Catherine', 2, 1),
(3, 'client@saveurs-bordeaux.fr', '$2y$10$ASYYLPCDqNKrpRYaJ1SbrOvzr6WXBhs5UfeQbLeixPJS4aPpmeLgK', 'Durand', 'Claire', '0600000003', 'Bordeaux', 'France', '3 cours de l''Intendance', 3, 1);

INSERT INTO menu (menu_id, nom, nombre_personne_minimum, prix_par_personne, regime, theme_id, description, quantite_restante, image) VALUES
(1, 'Menu Noël', 12, 150, 'Classique', 1, 'Un menu festif pour Noël', 5, 'menu-noel.jpg'),
(3, 'Menu Végétarien', 6, 65, 'Végétarien', 3, 'Un menu sans viande', 8, 'menu-vege.jpg'),
(4, 'Menu Mariage', 20, 200, 'Classique', 4, 'Un menu pour votre mariage', 3, 'menu-mariage.jpg'),
(5, 'Menu Classique', 8, 80, 'Classique', 3, 'Un menu traditionnel', 10, 'menu-classique.jpg');

INSERT INTO plat (plat_id, titre_plat, prix) VALUES
(1, 'Lasagnes du chef', 35);

INSERT INTO allergene (allergene_id, libelle) VALUES
(1, 'Gluten'),
(2, 'Lait'),
(3, 'Œufs'),
(4, 'Fruits à coque');

INSERT INTO horaire (horaire_id, jour, heure_ouverture, heure_fermeture) VALUES
(1, 1, '09:00', '18:00'),
(2, 2, '09:00', '18:00'),
(3, 3, '09:00', '18:00'),
(4, 4, '09:00', '18:00'),
(5, 5, '09:00', '18:00'),
(6, 6, '09:00', '12:00'),
(7, 7, '00:00', '00:00');

-- Une commande terminée du client de test (Menu Classique, 8 personnes,
-- livraison dans Bordeaux donc sans frais kilométriques)
INSERT INTO commande (commande_id, utilisateur_id, menu_id, numero_commande, date_commande, date_prestation, adresse_livraison, heure_livraison, nombre_personne, prix_livraison, nombre_km, prix_menu, statut, pret_materiel, restituer_materiel) VALUES
(1, 3, 5, 'CMD-0001', '2026-06-01', '2026-06-15', '3 cours de l''Intendance, 33000 Bordeaux', '12:00', 8, 0, 0, 640, 'terminée', 0, 0);

-- Historique des statuts de cette commande
INSERT INTO historique_statut (commande_id, statut, date_changement) VALUES
(1, 'accepté', '2026-06-01 10:00:00'),
(1, 'en préparation', '2026-06-15 08:00:00'),
(1, 'en cours de livraison', '2026-06-15 11:15:00'),
(1, 'livré', '2026-06-15 11:55:00'),
(1, 'terminée', '2026-06-15 18:00:00');

-- Un avis validé, affiché sur la page d'accueil
INSERT INTO avis (avis_id, note, description, statut, utilisateur_id, commande_id) VALUES
(1, 5, 'Excellent, je recommande !', 'validé', 3, 1);
