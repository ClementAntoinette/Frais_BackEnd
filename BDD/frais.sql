-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Lun 05 Février 2018 à 10:05
-- Version du serveur :  10.0.32-MariaDB-0+deb8u1
-- Version de PHP :  5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `frais`
--

-- --------------------------------------------------------

--
-- Structure de la table `nf_frais`
--

CREATE TABLE `nf_frais` (
  `frais_id` int(11) NOT NULL,
  `frais_comment` varchar(100) DEFAULT NULL,
  `frais_montant` float NOT NULL,
  `frais_id_note` int(11) NOT NULL,
  `frais_id_type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_note`
--

CREATE TABLE `nf_note` (
  `note_id` int(11) NOT NULL,
  `note_comment` varchar(500) NOT NULL,
  `note_verrou` tinyint(1) NOT NULL,
  `note_usr_id` int(11) NOT NULL,
  `note_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nf_type`
--

CREATE TABLE `nf_type` (
  `type_id` int(11) NOT NULL,
  `type_nom` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `nf_type`
--

INSERT INTO `nf_type` (`type_id`, `type_nom`) VALUES
(1, 'Restauration'),
(2, 'Déplacement');

-- --------------------------------------------------------

--
-- Structure de la table `nf_utilisateur_usr`
--

CREATE TABLE `nf_utilisateur_usr` (
  `usr_id` int(11) NOT NULL,
  `usr_nom` varchar(50) NOT NULL,
  `usr_prenom` varchar(50) NOT NULL,
  `usr_genre` varchar(10) NOT NULL,
  `usr_mail` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `nf_frais`
--
ALTER TABLE `nf_frais`
  ADD PRIMARY KEY (`frais_id`);

--
-- Index pour la table `nf_note`
--
ALTER TABLE `nf_note`
  ADD PRIMARY KEY (`note_id`);

--
-- Index pour la table `nf_type`
--
ALTER TABLE `nf_type`
  ADD PRIMARY KEY (`type_id`);

--
-- Index pour la table `nf_utilisateur_usr`
--
ALTER TABLE `nf_utilisateur_usr`
  ADD PRIMARY KEY (`usr_id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `nf_frais`
--
ALTER TABLE `nf_frais`
  MODIFY `frais_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `nf_note`
--
ALTER TABLE `nf_note`
  MODIFY `note_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `nf_type`
--
ALTER TABLE `nf_type`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `nf_utilisateur_usr`
--
ALTER TABLE `nf_utilisateur_usr`
  MODIFY `usr_id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
