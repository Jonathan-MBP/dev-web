-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 19 juin 2025 à 23:01
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `school_travel`
--

-- --------------------------------------------------------

--
-- Structure de la table `destinations`
--

CREATE TABLE `destinations` (
  `id` int(11) NOT NULL,
  `ville` varchar(30) NOT NULL,
  `description` text NOT NULL,
  `category` enum('high','medium','low') NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `destinations`
--

INSERT INTO `destinations` (`id`, `ville`, `description`, `category`, `image`) VALUES
(1, 'Londres', '', 'high', 'londres.png'),
(2, 'Tokyo', '', 'high', 'tokyo.png\r\n'),
(3, 'Boston', '', 'high', 'boston.png\r\n'),
(4, 'Zurich', '', 'high', 'zurich.png'),
(5, 'Lausanne', '', 'medium', 'lausanne.png'),
(6, 'Hong Kong', '', 'medium', 'hongkong.png'),
(7, 'Barcelonne', '', 'medium', 'barcelonne.png\r\n');

-- --------------------------------------------------------

--
-- Structure de la table `recommandations`
--

CREATE TABLE `recommandations` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `average` float NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `students`
--

INSERT INTO `students` (`id`, `name`, `email`, `password`, `average`, `role`) VALUES
(9, 'Admin Test', 'admin@test.com', '$2y$10$QrOWyXdO.4jSumCrKN./MuTdxRVA4jyXixiEDygEFCKM6Hjh7VHTC', 15, 'admin'),
(10, 'bay', 'bay@gmail.com', '$2y$10$4YRxhl7sVeeL/cVC1ahd0e8pE2SRmCEbUWCqTSWp8.liDPFpIIQfW', 13, 'user');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `destinations`
--
ALTER TABLE `destinations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `recommandations`
--
ALTER TABLE `recommandations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `etudiant_recommande` (`student_id`),
  ADD KEY `destination_recommandee` (`destination_id`);

--
-- Index pour la table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `destinations`
--
ALTER TABLE `destinations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `recommandations`
--
ALTER TABLE `recommandations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `recommandations`
--
ALTER TABLE `recommandations`
  ADD CONSTRAINT `destination_recommandee` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`),
  ADD CONSTRAINT `etudiant_recommande` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
