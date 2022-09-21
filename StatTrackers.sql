-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 21 sep 2022 om 14:47
-- Serverversie: 10.4.24-MariaDB
-- PHP-versie: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stattrackers`
--
CREATE DATABASE IF NOT EXISTS `stattrackers` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `stattrackers`;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `goals`
--

CREATE TABLE IF NOT EXISTS `goals` (
  `goal_id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `goals` int(11) NOT NULL DEFAULT 0,
  `assists` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`goal_id`),
  KEY `SECONDARY` (`team_id`,`user_id`,`game_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `mtm_user_team`
--

CREATE TABLE IF NOT EXISTS `mtm_user_team` (
  `mtm_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`mtm_id`),
  KEY `SECONDARY` (`user_id`,`team_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `team`
--

CREATE TABLE IF NOT EXISTS `team` (
  `team_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`team_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` text NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`roles`)),
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `game_contribution` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT '{""}' COMMENT 'team { game { goals = ?, assists = ? } }',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `user`
--

INSERT IGNORE INTO `user` (`user_id`, `email`, `roles`, `name`, `password`, `game_contribution`) VALUES
(1, 'r.b.jansen03@gmail.com', NULL, 'Blazion', '$2y$10$0UNkSc7SlSW3ePERTRGeeeKQElBkx0jHcXh/IZbRYhtUEv8WSV/0i', '{\"TestTeam\":[{\"goals\":2,\"assists\":0},{\"goals\":1,\"assists\":1}],\"BetaTeam\":[{\"goals\":1,\"assists\":2}]}');

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `goals`
--
ALTER TABLE `goals`
  ADD CONSTRAINT `goals_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `team` (`team_id`),
  ADD CONSTRAINT `goals_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Beperkingen voor tabel `mtm_user_team`
--
ALTER TABLE `mtm_user_team`
  ADD CONSTRAINT `mtm_user_team_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `mtm_user_team_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `team` (`team_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
