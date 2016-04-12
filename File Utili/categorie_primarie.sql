-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Creato il: Apr 11, 2016 alle 11:50
-- Versione del server: 10.1.9-MariaDB
-- Versione PHP: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `my_dddparts`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `categorie_primarie`
--

--
-- Dump dei dati per la tabella `categorie_primarie`
--

INSERT INTO `categorie_primarie` (`ID`, `NOME`) VALUES
(1, '3D Printing'),
(2, 'Art'),
(3, 'Fashion'),
(4, 'Gadgets'),
(5, 'Hobby'),
(6, 'Household'),
(7, 'Learning'),
(8, 'Models'),
(9, 'Tools'),
(10, 'Toys & Games');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `categorie_primarie`

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `categorie_primarie`
--
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
