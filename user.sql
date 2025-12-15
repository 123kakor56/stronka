-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 05, 2025 at 01:47 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bsi_projekty`
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `salt` varchar(256) NOT NULL,
  `password_hash` varchar(512) NOT NULL,
  `login` varchar(128) NOT NULL,
  `gender` varchar(30) NOT NULL,
  `preference` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `last_name`, `salt`, `password_hash`, `login`, `gender`, `preference`) VALUES
(1, 'Jakub', 'Strzelczak', 'i04vlvUho42FuoTElZ3n5Q==', '$2y$12$i04vlvUho42FuoTElZ3n5O/sT.Aox/6MTxwj02SIZf0aTPIcDAoTu', 'lefinek', 'Mężczyzna', 'MacOS'),
(2, 'Kacper', 'Korus', 'XZvWODbphVdGra07zAuCZw==', '$2y$12$XZvWODbphVdGra07zAuCZuRP9fkDq4RgzILGdnOdN2edh.ehmf7aS', '123kakor56', 'Mężczyzna', 'Windows'),
(3, 'Adam', 'Kowalski', 'CBhmsc4U7UGwrCMDcGfb7A==', '$2y$12$CBhmsc4U7UGwrCMDcGfb7.CD1g2TAdazAZjqV3ZVyOPfBj1fv9Uf2', 'adamek203', 'Inna', 'Linux'),
(4, 'das', 'dasda', '5ocHZfFGuuLeMMisOQdvVw==', '$2y$12$5ocHZfFGuuLeMMisOQdvVuimHPaNGhZUrKfggSXlpOXI4N2QTkwBy', 'sadas', 'Kobieta', 'Linux'),
(5, 'asdas', 'asdasd', 'LMUsu7LiqV9GWzY2DQvG1A==', '$2y$12$LMUsu7LiqV9GWzY2DQvG1.fXxPipwtk7/1kaeTWBEY7co5Uca98Y2', 'asdasdas', 'Kobieta', 'Linux');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
