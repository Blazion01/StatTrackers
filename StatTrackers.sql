-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 19 okt 2022 om 13:24
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

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `goals`
--

DROP TABLE IF EXISTS `goals`;
CREATE TABLE `goals` (
  `game_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `goal_amount` int(11) NOT NULL,
  `assists` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tabel leegmaken voor invoegen `goals`
--

TRUNCATE TABLE `goals`;
--
-- Gegevens worden geëxporteerd voor tabel `goals`
--

INSERT INTO `goals` (`game_id`, `team_id`, `user_id`, `goal_amount`, `assists`) VALUES
(1, 1, 1, 2, 1),
(1, 1, 2, 1, 2),
(1, 2, 1, 0, 1),
(1, 2, 2, 1, 1),
(2, 2, 1, 2, 0),
(2, 2, 2, 0, 2),
(3, 2, 1, 1, 1),
(3, 2, 2, 0, 0),
(4, 2, 1, 2, 2),
(4, 2, 2, 2, 2);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `mtm_user_team`
--

DROP TABLE IF EXISTS `mtm_user_team`;
CREATE TABLE `mtm_user_team` (
  `team_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tabel leegmaken voor invoegen `mtm_user_team`
--

TRUNCATE TABLE `mtm_user_team`;
--
-- Gegevens worden geëxporteerd voor tabel `mtm_user_team`
--

INSERT INTO `mtm_user_team` (`team_id`, `user_id`, `active`) VALUES
(1, 1, 0),
(1, 2, 0),
(2, 1, 1),
(2, 2, 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `team`
--

DROP TABLE IF EXISTS `team`;
CREATE TABLE `team` (
  `team_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tabel leegmaken voor invoegen `team`
--

TRUNCATE TABLE `team`;
--
-- Gegevens worden geëxporteerd voor tabel `team`
--

INSERT INTO `team` (`team_id`, `name`) VALUES
(2, 'Beta_Team'),
(1, 'Test_Team');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `email` text NOT NULL,
  `json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tabel leegmaken voor invoegen `user`
--

TRUNCATE TABLE `user`;
--
-- Gegevens worden geëxporteerd voor tabel `user`
--

INSERT INTO `user` (`user_id`, `email`, `json`, `name`, `password`) VALUES
(1, 'r.b.jansen03@gmail.com', '{\"roles\": [\"owner\",\"dev\"]}', 'Blazion', 'Leeg'),
(2, '6010444@mborijnland.nl', NULL, 'Razor', 'Leeg');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `goals`
--
ALTER TABLE `goals`
  ADD UNIQUE KEY `ID_COMBO` (`game_id`,`team_id`,`user_id`) USING BTREE,
  ADD KEY `user_id` (`user_id`),
  ADD KEY `team_id` (`team_id`);

--
-- Indexen voor tabel `mtm_user_team`
--
ALTER TABLE `mtm_user_team`
  ADD UNIQUE KEY `ID_COMBO` (`team_id`,`user_id`) USING BTREE,
  ADD KEY `user_id` (`user_id`);

--
-- Indexen voor tabel `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`team_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexen voor tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `email` (`email`) USING HASH;

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `team`
--
ALTER TABLE `team`
  MODIFY `team_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT voor een tabel `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `goals`
--
ALTER TABLE `goals`
  ADD CONSTRAINT `goals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `goals_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `team` (`team_id`);

--
-- Beperkingen voor tabel `mtm_user_team`
--
ALTER TABLE `mtm_user_team`
  ADD CONSTRAINT `mtm_user_team_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `team` (`team_id`),
  ADD CONSTRAINT `mtm_user_team_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
