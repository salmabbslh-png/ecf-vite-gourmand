-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : lun. 20 juil. 2026 à 17:27
-- Version du serveur : 8.0.44
-- Version de PHP : 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `saveurs_bordeaux`
--

-- --------------------------------------------------------

--
-- Structure de la table `allergene`
--

CREATE TABLE `allergene` (
  `allergene_id` int NOT NULL,
  `mollie` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `avis_id` int NOT NULL,
  `note` int DEFAULT NULL,
  `description` varchar(30) DEFAULT NULL,
  `statut` varchar(30) DEFAULT NULL,
  `utilisateur_id` int DEFAULT NULL,
  `commande_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`avis_id`, `note`, `description`, `statut`, `utilisateur_id`, `commande_id`) VALUES
(1, 4, 'tres bien', 'validé', 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `commande_id` int NOT NULL,
  `utilisateur_id` int NOT NULL,
  `menu_id` int NOT NULL,
  `numero_commande` varchar(50) DEFAULT NULL,
  `date_commande` date DEFAULT NULL,
  `date_prestation` date DEFAULT NULL,
  `adresse_livraison` varchar(255) DEFAULT NULL,
  `heure_livraison` varchar(50) DEFAULT NULL,
  `nombre_personne` int DEFAULT NULL,
  `prix_livraison` double DEFAULT NULL,
  `nombre_km` int DEFAULT '0',
  `prix_menu` double DEFAULT NULL,
  `statut` varchar(50) DEFAULT NULL,
  `pret_materiel` tinyint(1) DEFAULT NULL,
  `restituer_materiel` tinyint(1) DEFAULT NULL,
  `motif_annulation` varchar(255) DEFAULT NULL,
  `mode_contact` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`commande_id`, `utilisateur_id`, `menu_id`, `numero_commande`, `date_commande`, `date_prestation`, `adresse_livraison`, `heure_livraison`, `nombre_personne`, `prix_livraison`, `nombre_km`, `prix_menu`, `statut`, `pret_materiel`, `restituer_materiel`, `motif_annulation`, `mode_contact`) VALUES
(1, 2, 1, 'CMD-6a564c13e64fb', '2026-07-14', '2026-07-31', '2 allée hadrien', '10:00', 12, 10.9, 10, 1800, 'terminée', 0, 0, 'empêchement de dernière minute', 'appel GSM'),
(2, 2, 1, 'CMD-6a59021c18a54', '2026-07-16', '2026-08-04', '2 allée hadrien', '11:30', 12, 0, 0, 1800, 'terminée', 0, 0, NULL, NULL),
(3, 2, 1, 'CMD-6a59038562e61', '2026-07-16', '2026-08-04', '2 allée hadrien', '11:30', 12, 0, 0, 1800, 'terminée', 0, 0, 'parce que', 'appel GSM');

-- --------------------------------------------------------

--
-- Structure de la table `historique_statut`
--

CREATE TABLE `historique_statut` (
  `historique_id` int NOT NULL,
  `commande_id` int NOT NULL,
  `statut` varchar(50) NOT NULL,
  `date_changement` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `historique_statut`
--

INSERT INTO `historique_statut` (`historique_id`, `commande_id`, `statut`, `date_changement`) VALUES
(1, 1, 'accepté', '2026-07-15 23:36:05'),
(2, 1, 'en préparation', '2026-07-15 23:36:14'),
(3, 1, 'en cours de livraison', '2026-07-15 23:36:16'),
(4, 1, 'livré', '2026-07-15 23:36:18'),
(5, 1, 'en attente du retour de matériel', '2026-07-15 23:36:20'),
(6, 1, 'terminée', '2026-07-15 23:36:22'),
(7, 1, 'en attente', '2026-07-15 23:36:24'),
(8, 1, 'annulée', '2026-07-15 23:48:55'),
(9, 1, 'annulée', '2026-07-15 23:49:22'),
(10, 2, 'en attente', '2026-07-16 18:09:00'),
(11, 3, 'en attente', '2026-07-16 18:15:01'),
(12, 3, 'annulée', '2026-07-16 22:18:14'),
(13, 2, 'terminée', '2026-07-16 22:18:30'),
(14, 3, 'terminée', '2026-07-16 22:18:57'),
(15, 1, 'en attente', '2026-07-16 22:18:58'),
(16, 1, 'terminée', '2026-07-16 22:19:01'),
(17, 3, 'en attente du retour de matériel', '2026-07-20 02:20:56'),
(18, 3, 'terminée', '2026-07-20 02:20:59'),
(19, 3, 'en attente', '2026-07-20 18:06:20'),
(20, 3, 'livré', '2026-07-20 18:06:24'),
(21, 3, 'terminée', '2026-07-20 18:06:27');

-- --------------------------------------------------------

--
-- Structure de la table `horaire`
--

CREATE TABLE `horaire` (
  `horaire_id` int NOT NULL,
  `jour` int DEFAULT NULL,
  `heure_ouverture` varchar(50) DEFAULT NULL,
  `heure_fermeture` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `horaire`
--

INSERT INTO `horaire` (`horaire_id`, `jour`, `heure_ouverture`, `heure_fermeture`) VALUES
(1, 1, '09:00', '18:00'),
(2, 2, '09:00', '18:00'),
(3, 3, '09:00', '18:00'),
(4, 4, '09:00', '18:00'),
(5, 5, '09:00', '18:00'),
(6, 6, '09:00', '12:00'),
(7, 7, '00:00', '00:00');

-- --------------------------------------------------------

--
-- Structure de la table `menu`
--

CREATE TABLE `menu` (
  `menu_id` int NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `nombre_personne_minimum` int DEFAULT NULL,
  `prix_par_personne` double DEFAULT NULL,
  `regime` varchar(50) DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL,
  `quantite_restante` int DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `menu`
--

INSERT INTO `menu` (`menu_id`, `nom`, `nombre_personne_minimum`, `prix_par_personne`, `regime`, `description`, `quantite_restante`, `image`) VALUES
(1, 'Menu Noël', 12, 150, 'Classique', 'Un menu festif pour Noël', 5, 'menu-noel.jpg'),
(3, 'Menu Végétarien', 6, 65, 'Végétarien', 'Un menu sans viande', 8, 'menu-vege.jpg'),
(4, 'Menu Mariage', 20, 200, 'Classique', 'Un menu pour votre mariage', 3, 'menu-mariage.jpg'),
(5, 'Menu Classique', 8, 80, 'Classique', 'Un menu traditionnel', 10, 'menu-classique.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `plat`
--

CREATE TABLE `plat` (
  `plat_id` int NOT NULL,
  `titre_plat` varchar(50) DEFAULT NULL,
  `prix` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `plat`
--

INSERT INTO `plat` (`plat_id`, `titre_plat`, `prix`) VALUES
(2, 'lasagne du chef', 35);

-- --------------------------------------------------------

--
-- Structure de la table `regime`
--

CREATE TABLE `regime` (
  `regime_id` int NOT NULL,
  `libelle` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `regime`
--

INSERT INTO `regime` (`regime_id`, `libelle`) VALUES
(1, 'Végétarien'),
(2, 'Vegan'),
(3, 'Classique');

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `role_id` int NOT NULL,
  `libelle` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`role_id`, `libelle`) VALUES
(1, 'administrateur'),
(2, 'employe'),
(3, 'utilisateur');

-- --------------------------------------------------------

--
-- Structure de la table `theme`
--

CREATE TABLE `theme` (
  `theme_id` int NOT NULL,
  `libelle` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `theme`
--

INSERT INTO `theme` (`theme_id`, `libelle`) VALUES
(1, 'Noël'),
(2, 'Pâques'),
(3, 'Classique'),
(4, 'Événement');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `utilisateur_id` int NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `telephone` varchar(50) DEFAULT NULL,
  `ville` varchar(50) DEFAULT NULL,
  `pays` varchar(50) DEFAULT NULL,
  `adresse_postale` varchar(50) DEFAULT NULL,
  `role_id` int DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expire` datetime DEFAULT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`utilisateur_id`, `email`, `password`, `nom`, `prenom`, `telephone`, `ville`, `pays`, `adresse_postale`, `role_id`, `reset_token`, `reset_token_expire`, `actif`) VALUES
(2, 'salmabbslh@gmail.com', '$2y$10$qJIaHzz1ajmoDQ.W7v1pyemfICxl4fBY1dM4Im/myi7MpAAsRjZay', 'BENBOUSSELHAM', 'salma', '0646080301', 'Templeuve', 'France', '2 allée hadrien', 2, NULL, NULL, 1),
(3, 'employe1@test.fr', '$2y$10$LoWb.0nCfBT6wvrs3h1yX.VY33BwyYJvKesaXh1DHLjMB0f/r9x0S', 'test', 'nouveau', NULL, NULL, NULL, NULL, 2, NULL, NULL, 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `allergene`
--
ALTER TABLE `allergene`
  ADD PRIMARY KEY (`allergene_id`);

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`avis_id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`),
  ADD KEY `commande_id` (`commande_id`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`commande_id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Index pour la table `historique_statut`
--
ALTER TABLE `historique_statut`
  ADD PRIMARY KEY (`historique_id`),
  ADD KEY `commande_id` (`commande_id`);

--
-- Index pour la table `horaire`
--
ALTER TABLE `horaire`
  ADD PRIMARY KEY (`horaire_id`);

--
-- Index pour la table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`menu_id`);

--
-- Index pour la table `plat`
--
ALTER TABLE `plat`
  ADD PRIMARY KEY (`plat_id`);

--
-- Index pour la table `regime`
--
ALTER TABLE `regime`
  ADD PRIMARY KEY (`regime_id`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_id`);

--
-- Index pour la table `theme`
--
ALTER TABLE `theme`
  ADD PRIMARY KEY (`theme_id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`utilisateur_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `allergene`
--
ALTER TABLE `allergene`
  MODIFY `allergene_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `avis_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `commande_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `historique_statut`
--
ALTER TABLE `historique_statut`
  MODIFY `historique_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `horaire`
--
ALTER TABLE `horaire`
  MODIFY `horaire_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `menu`
--
ALTER TABLE `menu`
  MODIFY `menu_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `plat`
--
ALTER TABLE `plat`
  MODIFY `plat_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `regime`
--
ALTER TABLE `regime`
  MODIFY `regime_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `theme`
--
ALTER TABLE `theme`
  MODIFY `theme_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `utilisateur_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`utilisateur_id`),
  ADD CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`commande_id`);

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`utilisateur_id`),
  ADD CONSTRAINT `commande_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`menu_id`);

--
-- Contraintes pour la table `historique_statut`
--
ALTER TABLE `historique_statut`
  ADD CONSTRAINT `historique_statut_ibfk_1` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`commande_id`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
