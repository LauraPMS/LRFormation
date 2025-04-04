-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : cs1433044-001.eu.clouddb.ovh.net:35840
-- Généré le : ven. 04 avr. 2025 à 21:40
-- Version du serveur : 8.0.41-32
-- Version de PHP : 8.1.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bddLaura`
--

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `employe`
--

CREATE TABLE `employe` (
  `id` int NOT NULL,
  `login` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mdp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `employe`
--

INSERT INTO `employe` (`id`, `login`, `mdp`, `nom`, `prenom`, `statut`) VALUES
(1, 'testEmploye', 'e6ef44e3de2f263e872c464245707c71', 'bourgeois', 'camille', 1),
(2, 'testResponsable', '2871f781aed39bb64e98ffbbf0411dcc', 'le grand', 'toto', 2),
(3, 'bateau', '2ded3fffad9c6be6ae9105b52f84ae66', 'bateau', 'bateau', 1),
(4, 'azerty', 'c492c8722b411f880ed7428bd785e6e3', 'azerty', 'azerty', 1),
(7, 'testE', 'a16f04f1b9ae484ef5eabd1b3362b3f1', 'testE', 'testE', 1),
(10, 'aze', '021a06822aa859b9c52794f74113c552', 'castaing', 'toto', 0);

-- --------------------------------------------------------

--
-- Structure de la table `formation`
--

CREATE TABLE `formation` (
  `id` int NOT NULL,
  `date_debut` date NOT NULL,
  `nbr_heures` int NOT NULL,
  `departement` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `le_produit_id` int DEFAULT NULL,
  `pays` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `formation`
--

INSERT INTO `formation` (`id`, `date_debut`, `nbr_heures`, `departement`, `le_produit_id`, `pays`) VALUES
(1, '2024-11-21', 10, 'Com', 4, ''),
(2, '2024-11-23', 10, 'Vue', 5, ''),
(3, '2024-11-25', 25, 'Developpement Lourd', 3, ''),
(4, '2024-11-26', 8, 'Developpement web', 1, ''),
(6, '2006-01-10', 24, 'Nanterre', 3, ''),
(7, '2024-11-29', 10, '45000', 2, ''),
(8, '2024-11-29', 30, 'Direction générale', 4, ''),
(9, '2024-12-12', 10, '92', 3, ''),
(10, '2024-12-28', 15, '50', 1, ''),
(11, '2024-12-23', 10, '80', 3, ''),
(13, '2024-12-26', 10, '85', 3, ''),
(14, '2024-12-31', 75, '75', 5, ''),
(15, '2025-02-13', 15, '52000', 2, ''),
(16, '2024-12-19', 15, 'Test Oral', 1, 'France'),
(17, '2025-03-22', 12, 'Chefs de projet - Java FullStack', 3, 'France'),
(18, '2025-03-22', 12, 'Sécurity bundle', 1, 'France'),
(19, '2025-03-28', 10, 'Teams', 4, 'France'),
(20, '2025-03-30', 5, 'DSI', 3, 'Pologne'),
(22, '2025-04-11', 2, 'Epreuve', 3, 'Perou'),
(23, '2025-03-28', 2, '75013', 1, 'Paris');

-- --------------------------------------------------------

--
-- Structure de la table `inscription`
--

CREATE TABLE `inscription` (
  `id` int NOT NULL,
  `statut` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lemploye_id` int DEFAULT NULL,
  `la_formation_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `inscription`
--

INSERT INTO `inscription` (`id`, `statut`, `lemploye_id`, `la_formation_id`) VALUES
(24, '1', 1, 18),
(25, '3', 1, 17),
(27, '0', 1, 19),
(28, '0', 1, 22),
(29, '1', 1, 23);

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id` int NOT NULL,
  `libelle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id`, `libelle`) VALUES
(1, 'Symfony'),
(2, 'Bootsrap'),
(3, 'Java'),
(4, 'Commercial'),
(5, 'Twig');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `employe`
--
ALTER TABLE `employe`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `formation`
--
ALTER TABLE `formation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_404021BF2C340150` (`le_produit_id`);

--
-- Index pour la table `inscription`
--
ALTER TABLE `inscription`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_5E90F6D6D3758FF7` (`lemploye_id`),
  ADD KEY `IDX_5E90F6D6C6E58DBA` (`la_formation_id`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `employe`
--
ALTER TABLE `employe`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `formation`
--
ALTER TABLE `formation`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `inscription`
--
ALTER TABLE `inscription`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `formation`
--
ALTER TABLE `formation`
  ADD CONSTRAINT `FK_404021BF2C340150` FOREIGN KEY (`le_produit_id`) REFERENCES `produit` (`id`);

--
-- Contraintes pour la table `inscription`
--
ALTER TABLE `inscription`
  ADD CONSTRAINT `FK_5E90F6D6C6E58DBA` FOREIGN KEY (`la_formation_id`) REFERENCES `formation` (`id`),
  ADD CONSTRAINT `FK_5E90F6D6D3758FF7` FOREIGN KEY (`lemploye_id`) REFERENCES `employe` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
