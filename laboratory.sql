-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 13, 2024 at 01:46 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laboratory`
--

-- --------------------------------------------------------

--
-- Table structure for table `administ`
--

CREATE TABLE `administ` (
  `email` varchar(50) NOT NULL,
  `fname` varchar(40) NOT NULL,
  `mname` varchar(40) NOT NULL,
  `lname` varchar(40) NOT NULL,
  `sex` varchar(10) NOT NULL,
  `passw` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `administ`
--

INSERT INTO `administ` (`email`, `fname`, `mname`, `lname`, `sex`, `passw`) VALUES
('leo.villanuevaph@gmail.com', 'Leo', 'Gabriel', 'Villanueva', 'Male', 'psu123');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `idno` varchar(50) NOT NULL,
  `fname` varchar(40) DEFAULT NULL,
  `mname` varchar(40) NOT NULL,
  `lname` varchar(40) NOT NULL,
  `sex` varchar(6) NOT NULL,
  `addrs` varchar(100) DEFAULT NULL,
  `cpno` varchar(11) NOT NULL,
  `department` varchar(30) NOT NULL,
  `position` varchar(30) NOT NULL,
  `faculty_stat` varchar(20) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `passw` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`idno`, `fname`, `mname`, `lname`, `sex`, `addrs`, `cpno`, `department`, `position`, `faculty_stat`, `email`, `passw`) VALUES
('INS-101', 'Jervan', 'Perrion', 'Aficial', 'Male', 'Alcala Pangasinan', '09053392585', 'Department of Computing', 'Instructor ', 'Active', 'jeban@gmail.com', '123'),
('INS-102', 'Monica', 'Recobo', 'Ave', 'Female', 'Camantiles Urdaneta, Pangasinan', '09516124139', 'Department of Computing', 'Instructor ', 'Active', 'monicaAve@gmail.com', 'meow'),
('INS-103', 'Mewnie', 'Mami', 'Meow', 'Male', 'San Manuel Tarlac', '09274635278', 'Department of Meow', 'Mewdean', 'Inactive', 'meow@gmail.com', '123'),
('INS-104', 'Jervan', 'Test', 'Test', 'Male', 'Alcala, Pangasinan', '09057482323', 'Department of Law', 'Dean', 'Active', 'fakecheon@gmail.com', '123');

-- --------------------------------------------------------

--
-- Table structure for table `hardwares`
--

CREATE TABLE `hardwares` (
  `deviceID` varchar(100) NOT NULL,
  `name` varchar(65) NOT NULL,
  `doAcquisition` date NOT NULL,
  `status` varchar(20) NOT NULL,
  `idno` varchar(50) NOT NULL,
  `labID` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hardwares`
--

INSERT INTO `hardwares` (`deviceID`, `name`, `doAcquisition`, `status`, `idno`, `labID`) VALUES
('HP-103', '3HP Aircon Inverter 3HP', '2023-11-15', 'Working', 'INS-103', 'L-103'),
('LG-707', 'LG Aircon Inverter', '2018-07-17', 'Not Working', 'INS-101', 'L-101'),
('MO-101', 'ASUS ROG Swift OLED PG27AQDM', '2024-04-17', 'Working', 'INS-102', 'L-102'),
('PC1-101', 'IntelCore5115H1 x64 bit 8gb RAM', '2024-04-14', 'Working', 'INS-101', 'L-101'),
('PC1-102', 'IntelCore 31115G4 x64 Bit 4GB RAM', '2024-04-14', 'Not Working', 'INS-102', 'L-102'),
('PC1-103', 'IMAC M3 8GB RAM', '2024-04-14', 'Working', 'INS-103', 'L-103'),
('PC2-101', 'IntelCore5115H1 x64 bit 8gb RAM', '2024-04-15', 'Not Working', 'INS-101', 'L-101'),
('PC2-102', 'IntelCore 31115G4 x64 Bit 4GB RAM', '2024-04-14', 'Working', 'INS-102', 'L-102'),
('PC2-103', 'IMAC M3 8GB RAM', '2024-04-14', 'Working', 'INS-103', 'L-103'),
('PC3-101', 'IntelCore5115H1 x64 bit 8gb RAM', '2024-04-15', 'Not Working', 'INS-101', 'L-101'),
('PC3-102', 'IntelCore 31115G4 x64 Bit 4GB RAM', '2024-04-14', 'Working', 'INS-102', 'L-102'),
('PC3-103', 'IMAC M3 8GB RAM', '2024-04-14', 'Working', 'INS-103', 'L-103'),
('PC4-102', 'IntelCore 31115G4 x64 Bit 4GB RAM', '2024-04-15', 'Not Working', 'INS-102', 'L-102'),
('PC4-103', 'IMAC M3 8GB RAM', '2024-04-15', 'Working', 'INS-103', 'L-103'),
('PC5-102', 'IntelCore 31115G4 x64 Bit 4GB RAM', '2024-04-14', 'Working', 'INS-102', 'L-102'),
('S-101', 'Samsung Monitor AZ1A', '2024-03-15', 'Working', 'INS-101', 'L-101');

-- --------------------------------------------------------

--
-- Table structure for table `laboratory`
--

CREATE TABLE `laboratory` (
  `labID` varchar(50) NOT NULL,
  `labname` varchar(20) NOT NULL,
  `labLoc` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laboratory`
--

INSERT INTO `laboratory` (`labID`, `labname`, `labLoc`) VALUES
('L-101', 'Lab-1', 'Administrative Building 2nd Floor'),
('L-102', 'Lab-2', 'Administrative Building 2nd Floor'),
('L-103', 'Lab-3', 'Architecture Building'),
('L-105', 'Lab-5', 'ABEL Building');

-- --------------------------------------------------------

--
-- Table structure for table `logz`
--

CREATE TABLE `logz` (
  `idno` varchar(50) DEFAULT NULL,
  `fname` varchar(40) DEFAULT NULL,
  `lname` varchar(40) DEFAULT NULL,
  `login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logz`
--

INSERT INTO `logz` (`idno`, `fname`, `lname`, `login`) VALUES
('INS-101', 'Jervan', 'Aficial', '2024-04-10 04:05:48'),
('INS-102', 'Monica', 'Ave', '2024-04-10 12:38:26'),
('INS-101', 'Jervan', 'Aficial', '2024-04-11 02:03:15'),
('INS-102', 'Monica', 'Ave', '2024-04-11 02:06:16'),
('INS-102', 'Monica', 'Ave', '2024-04-13 06:07:58'),
('INS-102', 'Monica', 'Ave', '2024-04-13 06:54:25'),
('INS-102', 'Monica', 'Ave', '2024-04-13 07:35:42'),
('INS-101', 'Jervan', 'Aficial', '2024-04-13 14:12:57'),
('INS-102', 'Monica', 'Ave', '2024-04-13 15:55:22'),
('INS-102', 'Monica', 'Ave', '2024-04-14 00:42:39'),
('INS-102', 'Monica', 'Ave', '2024-04-14 01:40:17'),
('INS-102', 'Monica', 'Ave', '2024-04-14 09:39:36'),
('INS-102', 'Monica', 'Ave', '2024-04-14 09:39:47'),
('INS-102', 'Monica', 'Ave', '2024-04-14 09:54:30'),
('INS-102', 'Monica', 'Ave', '2024-04-14 09:55:51'),
('INS-102', 'Monica', 'Ave', '2024-04-14 10:08:37'),
('INS-102', 'Monica', 'Ave', '2024-04-14 14:08:28'),
('INS-102', 'Monica', 'Ave', '2024-04-15 10:22:08'),
('INS-102', 'Monica', 'Ave', '2024-04-15 11:23:42'),
('INS-102', 'Monica', 'Ave', '2024-04-15 11:29:03'),
('INS-102', 'Monica', 'Ave', '2024-04-15 11:33:49'),
('INS-102', 'Monica', 'Ave', '2024-04-15 11:42:36'),
('INS-102', 'Monica', 'Ave', '2024-04-15 12:05:03'),
('INS-102', 'Monica', 'Ave', '2024-04-15 12:07:50'),
('INS-102', 'Monica', 'Ave', '2024-04-15 12:12:12'),
('INS-102', 'Monica', 'Ave', '2024-04-15 12:28:34'),
('INS-102', 'Monica', 'Ave', '2024-04-15 12:28:59'),
('INS-101', 'Jervan', 'Aficial', '2024-04-15 12:29:49'),
('INS-102', 'Monica', 'Ave', '2024-04-15 12:31:40'),
('INS-102', 'Monica', 'Ave', '2024-04-15 12:52:04'),
('INS-102', 'Monica', 'Ave', '2024-04-15 13:15:54'),
('INS-102', 'Monica', 'Ave', '2024-04-15 13:33:49'),
('INS-102', 'Monica', 'Ave', '2024-04-15 13:49:24'),
('INS-102', 'Monica', 'Ave', '2024-04-15 13:51:14'),
('INS-102', 'Monica', 'Ave', '2024-04-15 13:58:21'),
('INS-102', 'Monica', 'Ave', '2024-04-15 14:03:06'),
('INS-102', 'Monica', 'Ave', '2024-04-15 14:28:47'),
('INS-102', 'Monica', 'Ave', '2024-04-15 15:06:48'),
('INS-102', 'Monica', 'Ave', '2024-04-15 15:43:47'),
('INS-102', 'Monica', 'Ave', '2024-04-15 16:48:52'),
('INS-102', 'Monica', 'Ave', '2024-04-15 18:07:11'),
('INS-102', 'Monica', 'Ave', '2024-04-15 18:41:34'),
('INS-102', 'Monica', 'Ave', '2024-04-15 18:44:33'),
('INS-102', 'Monica', 'Ave', '2024-04-15 19:06:13'),
('INS-102', 'Monica', 'Ave', '2024-04-15 19:27:58'),
('INS-102', 'Monica', 'Ave', '2024-04-16 08:27:11'),
('INS-102', 'Monica', 'Ave', '2024-04-16 08:27:32'),
('INS-102', 'Monica', 'Ave', '2024-04-16 08:54:57'),
('INS-102', 'Monica', 'Ave', '2024-04-16 08:58:45'),
('INS-102', 'Monica', 'Ave', '2024-04-16 08:58:54'),
('INS-102', 'Monica', 'Ave', '2024-04-16 09:14:31'),
('INS-102', 'Monica', 'Ave', '2024-04-16 09:41:44'),
('INS-102', 'Monica', 'Ave', '2024-04-16 09:48:40'),
('INS-102', 'Monica', 'Ave', '2024-04-16 09:53:36'),
('INS-102', 'Monica', 'Ave', '2024-04-16 09:56:41'),
('INS-102', 'Monica', 'Ave', '2024-04-16 10:14:01'),
('INS-102', 'Monica', 'Ave', '2024-04-16 10:20:01'),
('INS-102', 'Monica', 'Ave', '2024-04-16 10:24:14'),
('INS-102', 'Monica', 'Ave', '2024-04-16 10:27:00'),
('INS-102', 'Monica', 'Ave', '2024-04-16 10:32:33'),
('INS-102', 'Monica', 'Ave', '2024-04-16 10:37:47'),
('INS-102', 'Monica', 'Ave', '2024-04-16 10:44:55'),
('INS-102', 'Monica', 'Ave', '2024-04-16 10:46:18'),
('INS-102', 'Monica', 'Ave', '2024-04-16 10:47:33'),
('INS-102', 'Monica', 'Ave', '2024-04-16 10:51:05'),
('INS-102', 'Monica', 'Ave', '2024-04-16 10:55:40'),
('INS-102', 'Monica', 'Ave', '2024-04-16 10:57:03'),
('INS-102', 'Monica', 'Ave', '2024-04-16 10:57:54'),
('INS-102', 'Monica', 'Ave', '2024-04-16 10:58:58'),
('INS-102', 'Monica', 'Ave', '2024-04-16 11:05:52'),
('INS-102', 'Monica', 'Ave', '2024-04-16 11:17:42'),
('INS-102', 'Monica', 'Ave', '2024-04-16 11:25:35'),
('INS-102', 'Monica', 'Ave', '2024-04-16 11:36:27'),
('INS-102', 'Monica', 'Ave', '2024-04-16 11:43:30'),
('INS-102', 'Monica', 'Ave', '2024-04-16 11:52:20'),
('INS-102', 'Monica', 'Ave', '2024-04-16 12:15:52'),
('INS-102', 'Monica', 'Ave', '2024-04-16 12:31:24'),
('INS-102', 'Monica', 'Ave', '2024-04-16 12:54:15'),
('INS-102', 'Monica', 'Ave', '2024-04-16 13:15:24'),
('INS-102', 'Monica', 'Ave', '2024-04-16 14:07:01'),
('INS-102', 'Monica', 'Ave', '2024-04-16 14:38:13'),
('INS-102', 'Monica', 'Ave', '2024-04-17 02:43:12'),
('INS-102', 'Monica', 'Ave', '2024-04-17 02:50:05'),
('INS-102', 'Monica', 'Ave', '2024-04-17 02:54:38'),
('INS-102', 'Monica', 'Ave', '2024-04-17 06:11:24'),
('INS-102', 'Monica', 'Ave', '2024-04-17 06:13:26'),
('INS-102', 'Monica', 'Ave', '2024-04-17 07:42:49'),
('INS-102', 'Monica', 'Ave', '2024-04-17 07:45:45'),
('INS-102', 'Monica', 'Ave', '2024-04-17 08:44:06'),
('INS-102', 'Monica', 'Ave', '2024-04-17 12:29:02'),
('INS-102', 'Monica', 'Ave', '2024-04-17 12:40:36'),
('INS-102', 'Monica', 'Ave', '2024-04-17 13:00:44'),
('INS-102', 'Monica', 'Ave', '2024-04-17 13:04:22'),
('INS-102', 'Monica', 'Ave', '2024-04-17 13:13:05'),
('INS-102', 'Monica', 'Ave', '2024-04-17 13:16:28'),
('INS-102', 'Monica', 'Ave', '2024-04-17 13:39:15'),
('INS-102', 'Monica', 'Ave', '2024-04-17 13:45:43'),
('INS-102', 'Monica', 'Ave', '2024-04-17 13:52:35'),
('INS-102', 'Monica', 'Ave', '2024-04-17 14:14:32'),
('INS-102', 'Monica', 'Ave', '2024-04-22 01:44:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administ`
--
ALTER TABLE `administ`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`idno`);

--
-- Indexes for table `hardwares`
--
ALTER TABLE `hardwares`
  ADD PRIMARY KEY (`deviceID`),
  ADD KEY `facultyID` (`idno`),
  ADD KEY `labID` (`labID`),
  ADD KEY `idno` (`idno`);

--
-- Indexes for table `laboratory`
--
ALTER TABLE `laboratory`
  ADD PRIMARY KEY (`labID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hardwares`
--
ALTER TABLE `hardwares`
  ADD CONSTRAINT `hardwares_ibfk_3` FOREIGN KEY (`idno`) REFERENCES `faculty` (`idno`),
  ADD CONSTRAINT `hardwares_ibfk_4` FOREIGN KEY (`labID`) REFERENCES `laboratory` (`labID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
