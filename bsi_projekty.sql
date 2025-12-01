-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 24, 2025 at 10:31 PM
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
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `nr_indeksu` char(6) NOT NULL,
  `imie` varchar(30) NOT NULL,
  `nazwisko` varchar(30) NOT NULL,
  `wiek` int(11) NOT NULL,
  `ulica` varchar(50) NOT NULL,
  `kod_pocztowy` char(6) NOT NULL,
  `miasto` varchar(50) NOT NULL,
  `plec` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `nr_indeksu`, `imie`, `nazwisko`, `wiek`, `ulica`, `kod_pocztowy`, `miasto`, `plec`) VALUES
(1, '227684', 'Jakub', 'Strzelczak', 21, 'Spacerowa 6', '05-135', 'Wieliszew', 'Mężczyzna'),
(2, '123123', 'Test', 'Test', 10, 'Testowa', '00-000', 'Test', 'Inna'),
(4, '230984', 'Kacper', 'Korus', 21, 'Myśliborska', '01-932', 'Warszawa', 'Mężczyzna'),
(6, '228001', 'Wojciech', 'Guziewski', 21, 'Dowolna 33', '07-132', 'Piaseczno', 'Mężczyzna');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
