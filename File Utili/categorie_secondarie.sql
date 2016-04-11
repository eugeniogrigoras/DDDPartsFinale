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
-- Struttura della tabella `categorie_secondarie`
--

DROP TABLE IF EXISTS `categorie_secondarie`;
CREATE TABLE `categorie_secondarie` (
  `ID` int(11) NOT NULL,
  `NOME` varchar(30) DEFAULT NULL,
  `FK_CATEGORIA_PRIMARIA` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `categorie_secondarie`
--

INSERT INTO `categorie_secondarie` (`ID`, `NOME`, `FK_CATEGORIA_PRIMARIA`) VALUES
(1, '3D Printing', 1),
(2, '3D Printer Accessories', 1),
(3, '3D Printer Extruders', 1),
(4, '3D Printer Parts', 1),
(5, '3D Printers', 1),
(6, '3D Printing Tests', 1),
(7, '2D Art', 2),
(8, 'Art Tools', 2),
(9, 'Coins & Badges', 2),
(10, 'Interactive Art', 2),
(11, 'Math Art', 2),
(12, 'Scans & Replicas', 2),
(13, 'Sculptures', 2),
(14, 'Signs & Logos', 2),
(15, 'Accessories', 3),
(16, 'Bracelets', 3),
(17, 'Costume', 3),
(18, 'Earrings', 3),
(19, 'Glasses', 3),
(20, 'Jewelry', 3),
(21, 'Keychains', 3),
(22, 'Rings', 3),
(23, 'Audio', 4),
(24, 'Camera', 4),
(25, 'Computer', 4),
(26, 'Mobile Phone', 4),
(27, 'Tablet', 4),
(28, 'Video Games', 4),
(29, 'Automotive', 5),
(30, 'Diy', 5),
(31, 'Electronics', 5),
(32, 'Music', 5),
(33, 'R/C Vehicles', 5),
(34, 'Robotics', 5),
(35, 'Sport & Outdoors', 5),
(36, 'Bathroom', 6),
(37, 'Containers', 6),
(38, 'Decor', 6),
(39, 'Household Supplies', 6),
(40, 'Kitchen & Dinning', 6),
(41, 'Office', 6),
(42, 'Organization', 6),
(43, 'Outdoor & Garden', 6),
(44, 'Pets', 6),
(45, 'Replacement Parts', 6),
(46, 'Biology', 7),
(47, 'Engineering', 7),
(48, 'Math', 7),
(49, 'Physics & Astronomy', 7),
(50, 'Animals', 8),
(51, 'Buildings & Structures', 8),
(52, 'Creatures', 8),
(53, 'Food & Drink', 8),
(54, 'Model Furniture', 8),
(55, 'Model Robots', 8),
(56, 'People', 8),
(57, 'Props', 8),
(58, 'Vehicles', 8),
(59, 'Hand Tools', 9),
(60, 'Machine Tools', 9),
(61, 'Parts', 9),
(62, 'Tool Holders & Boxes', 9),
(63, 'Chess', 10),
(64, 'Construction Toys', 10),
(65, 'Dice', 10),
(66, 'Games', 10),
(67, 'Mechanical Toys', 10),
(68, 'Playsets', 10),
(69, 'Puzzles', 10),
(70, 'Toy & Game Accessories', 10);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `categorie_secondarie`
--
ALTER TABLE `categorie_secondarie`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_CATEGORIA_PRIMARIA` (`FK_CATEGORIA_PRIMARIA`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `categorie_secondarie`
--
ALTER TABLE `categorie_secondarie`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;
--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `categorie_secondarie`
--
ALTER TABLE `categorie_secondarie`
  ADD CONSTRAINT `categorie_secondarie_ibfk_1` FOREIGN KEY (`FK_CATEGORIA_PRIMARIA`) REFERENCES `categorie_primarie` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
