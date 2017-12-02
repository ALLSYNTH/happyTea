-- phpMyAdmin SQL Dump
-- version 4.7.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le :  sam. 02 déc. 2017 à 11:12
-- Version du serveur :  5.6.35
-- Version de PHP :  5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `happy_tea`
--

-- --------------------------------------------------------

--
-- Structure de la table `seller`
--

CREATE TABLE `seller` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `adress` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `seller`
--

INSERT INTO `seller` (`id`, `name`, `adress`) VALUES
(1, 'test1', 'test2'),
(2, 'toto1', 'totoad1'),
(3, 'toto2', 'adtoto2'),
(4, 'toto3', 'toto3'),
(5, 'toto3', 'toto3'),
(6, 'test5', 'test5'),
(7, 'test6', 'test6'),
(8, 'test6', 'test6'),
(9, 'test7', 'test7'),
(10, 'test7', 'test7');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `seller`
--
ALTER TABLE `seller`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `seller`
--
ALTER TABLE `seller`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;