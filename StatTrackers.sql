-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 30 sep 2022 om 11:59
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
  `goal_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `goals` int(11) NOT NULL DEFAULT 0,
  `assists` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `mtm_user_team`
--

DROP TABLE IF EXISTS `mtm_user_team`;
CREATE TABLE `mtm_user_team` (
  `mtm_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `mtm_user_team`
--

REPLACE INTO `mtm_user_team` (`mtm_id`, `user_id`, `team_id`, `active`) VALUES
(1, 1, 1, 0),
(2, 1, 2, 1),
(3, 2, 2, 1);

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
-- Gegevens worden geëxporteerd voor tabel `team`
--

REPLACE INTO `team` (`team_id`, `name`) VALUES
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
  `password` varchar(255) NOT NULL,
  `game_contribution` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT '{""}' COMMENT 'team { game { goals = ?, assists = ? } }'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `user`
--

REPLACE INTO `user` (`user_id`, `email`, `json`, `name`, `password`, `game_contribution`) VALUES
(1, 'r.b.jansen03@gmail.com', '{\"roles\": [\"owner\",\"dev\"]}', 'Blazion', '$2y$10$0UNkSc7SlSW3ePERTRGeeeKQElBkx0jHcXh/IZbRYhtUEv8WSV/0i', '{\"TestTeam\":[{\"goals\":2,\"assists\":0},{\"goals\":1,\"assists\":1}],\"BetaTeam\":[{\"goals\":1,\"assists\":2}]}'),
(2, '6010444@mborijnland.nl', NULL, 'Razor', '$2y$10$f7BN6QugZEFtFR9k989OhOLyatbJFOKSFp/fyD84R5m1Pstx7iZTK', '{\"\"}');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`goal_id`),
  ADD KEY `SECONDARY` (`team_id`,`user_id`,`game_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexen voor tabel `mtm_user_team`
--
ALTER TABLE `mtm_user_team`
  ADD PRIMARY KEY (`mtm_id`),
  ADD UNIQUE KEY `user_team_combination` (`user_id`,`team_id`) USING BTREE;

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
  ADD UNIQUE KEY `email` (`email`) USING HASH;

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `goals`
--
ALTER TABLE `goals`
  MODIFY `goal_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `mtm_user_team`
--
ALTER TABLE `mtm_user_team`
  MODIFY `mtm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  ADD CONSTRAINT `goals_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `team` (`team_id`),
  ADD CONSTRAINT `goals_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Beperkingen voor tabel `mtm_user_team`
--
ALTER TABLE `mtm_user_team`
  ADD CONSTRAINT `mtm_user_team_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `team` (`team_id`),
  ADD CONSTRAINT `mtm_user_team_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `team` (`team_id`),
  ADD CONSTRAINT `mtm_user_team_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
