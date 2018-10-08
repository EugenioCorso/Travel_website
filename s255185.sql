-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Giu 26, 2018 alle 17:23
-- Versione del server: 10.1.32-MariaDB
-- Versione PHP: 7.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mydb`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `member`
--

DROP TABLE IF EXISTS `member`;
CREATE TABLE `member` (
  `name` varchar(40) CHARACTER SET ascii NOT NULL,
  `password` varchar(64) CHARACTER SET ascii DEFAULT NULL,
  `start` varchar(64) CHARACTER SET ascii NOT NULL,
  `arrive` varchar(64) CHARACTER SET ascii NOT NULL,
  `persons` int(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `member`
--

INSERT INTO `member` (`name`, `password`, `start`, `arrive`, `persons`) VALUES
('u1@p.it', '$2y$10$uxLiRTsg8WszifPbRU.YTOPx1t3asfF.ME7a24ae85c7M/1q7ms7a', 'FF', 'KK', 4),
('u2@p.it', '$2y$10$jszQNFj.lWLwo6nr4N9Yhela2WsIp7QjzE6/lqkd2hQ31gn3WFcB6', 'BB', 'EE', 1),
('u3@p.it', '$2y$10$knoHHzNI3GOPaiKCLR7qU.c2EIMuHQy88CBb.UZVYPdfm6cZisrxG', 'DD', 'EE', 1),
('u4@p.it', '$2y$10$YYqd066W3WJCx8pQv.Aby.20zplAzC.N0LyljZyeTmt.AIj6EuLsC', 'AL', 'DD', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `places`
--

DROP TABLE IF EXISTS `places`;
CREATE TABLE `places` (
  `place` varchar(64) CHARACTER SET ascii NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `places`
--

INSERT INTO `places` (`place`) VALUES
('AL'),
('BB'),
('DD'),
('EE'),
('FF'),
('KK');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`name`);

--
-- Indici per le tabelle `places`
--
ALTER TABLE `places`
  ADD PRIMARY KEY (`place`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
