-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mar. 20 mai 2025 à 12:23
-- Version du serveur : 5.7.24
-- Version de PHP : 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `base struct`
--

-- --------------------------------------------------------

--
-- Structure de la table `user`
--
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    moyenne FLOAT,
    role ENUM('user','admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `nom`, `email`, `password`, `created_at`, `updated_at`, `role`,`profile_picture`,`moyenne`) VALUES
(2, 'pikachu', 'david.longuechaud@edu.ece.fr', '$2y$10$qPHsuaTOJgyPDQ/pnbrkB./Hkk3eR.tMmVr8qQwsxoEPdooa1JJze', '2025-05-12 19:02:53', '2025-05-12 19:02:53', 'admin', 'image/pp_682497d45f545.jpg', '16,5'),
(4, 'gtugdcg;g;;;;', 'jkjejenejeje@gmail.com', '$2y$10$uB.wRmTgat8DGTMUqBjvmOijVzWYeTmoXqsHP0q.0OFxLm4hWIuF2', '2025-05-14 13:31:55', '2025-05-14 13:31:55', 'user', NULL,'18,4' );

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
