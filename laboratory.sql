-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 27, 2024 at 09:22 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

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
('joannamarieo.areniego@gmail.com', 'Joanna Marie', 'Olayta', 'Areniego', 'Female', 'Joanna1003'),
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
('INS-1116', 'Joanna', 'Olayta', 'Areniego', 'Female', 'Nama', '09950244067', 'IT Department', 'Faculty', 'Active', 'joannamarie@gmail.com', '1234');

-- --------------------------------------------------------

--
-- Table structure for table `hardwares`
--

CREATE TABLE `hardwares` (
  `deviceID` int(50) NOT NULL,
  `name` varchar(65) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `sponsorName` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `serialNo` varchar(100) NOT NULL,
  `doAcquisition` date NOT NULL,
  `status` varchar(20) NOT NULL,
  `idno` varchar(50) NOT NULL,
  `labID` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hardwares`
--

INSERT INTO `hardwares` (`deviceID`, `name`, `brand`, `sponsorName`, `category`, `serialNo`, `doAcquisition`, `status`, `idno`, `labID`) VALUES
(1, 'sasdad', 'fdasf', 'adsd', 'adsada', 'sadasda', '2024-12-01', 'adasdas', 'INS-1116', 5);

-- --------------------------------------------------------

--
-- Table structure for table `hardware_images`
--

CREATE TABLE `hardware_images` (
  `imageID` int(11) NOT NULL,
  `deviceID` int(50) DEFAULT NULL,
  `imagePath` varchar(255) DEFAULT NULL,
  `uploadDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hardware_transfer_history`
--

CREATE TABLE `hardware_transfer_history` (
  `transferID` int(11) NOT NULL,
  `deviceID` int(50) NOT NULL,
  `fromLabID` int(50) NOT NULL,
  `toLabID` int(50) NOT NULL,
  `transferDate` date NOT NULL,
  `adminEmail` varchar(50) NOT NULL,
  `remarks` text DEFAULT NULL,
  `fromFaculty` varchar(50) DEFAULT NULL,
  `toFaculty` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laboratory`
--

CREATE TABLE `laboratory` (
  `labID` int(50) NOT NULL,
  `idno` varchar(50) NOT NULL,
  `labname` varchar(20) NOT NULL,
  `labLoc` varchar(50) NOT NULL,
  `labStatus` varchar(50) NOT NULL,
  `depLocation` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laboratory`
--

INSERT INTO `laboratory` (`labID`, `idno`, `labname`, `labLoc`, `labStatus`, `depLocation`) VALUES
(5, 'INS-1116', 'Lab2', 'Beside Lab1', 'Active', 'Engineering Department'),
(6, 'INS-1116', 'Lab2', 'Beside Lab1', 'Active', 'IT Department'),
(7, 'INS-1116', 'dffd', 'gdfg', 'Maintenance', 'Math Department'),
(8, 'INS-1116', 'da', 'gdfg', 'Active', 'Math Department');

-- --------------------------------------------------------

--
-- Table structure for table `lab_images`
--

CREATE TABLE `lab_images` (
  `imageID` int(11) NOT NULL,
  `labID` int(50) DEFAULT NULL,
  `imagePath` varchar(255) DEFAULT NULL,
  `uploadDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
('INS-1116', 'Joanna', 'Areniego', '2024-12-27 08:20:58');

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
-- Indexes for table `hardware_images`
--
ALTER TABLE `hardware_images`
  ADD PRIMARY KEY (`imageID`),
  ADD KEY `hardware_images_ibfk_1` (`deviceID`);

--
-- Indexes for table `hardware_transfer_history`
--
ALTER TABLE `hardware_transfer_history`
  ADD PRIMARY KEY (`transferID`),
  ADD KEY `adminEmail` (`adminEmail`),
  ADD KEY `hardware_transfer_history_ibfk_2` (`deviceID`),
  ADD KEY `hardware_transfer_history_ibfk_3` (`fromFaculty`),
  ADD KEY `hardware_transfer_history_ibfk_4` (`toFaculty`),
  ADD KEY `hardware_transfer_history_ibfk_5` (`fromLabID`),
  ADD KEY `hardware_transfer_history_ibfk_6` (`toLabID`);

--
-- Indexes for table `laboratory`
--
ALTER TABLE `laboratory`
  ADD PRIMARY KEY (`labID`),
  ADD KEY `idno` (`idno`);

--
-- Indexes for table `lab_images`
--
ALTER TABLE `lab_images`
  ADD PRIMARY KEY (`imageID`),
  ADD KEY `labID` (`labID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hardwares`
--
ALTER TABLE `hardwares`
  MODIFY `deviceID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hardware_images`
--
ALTER TABLE `hardware_images`
  MODIFY `imageID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hardware_transfer_history`
--
ALTER TABLE `hardware_transfer_history`
  MODIFY `transferID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laboratory`
--
ALTER TABLE `laboratory`
  MODIFY `labID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `lab_images`
--
ALTER TABLE `lab_images`
  MODIFY `imageID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hardwares`
--
ALTER TABLE `hardwares`
  ADD CONSTRAINT `hardwares_ibfk_1` FOREIGN KEY (`labID`) REFERENCES `laboratory` (`labID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hardwares_ibfk_2` FOREIGN KEY (`idno`) REFERENCES `faculty` (`idno`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hardware_images`
--
ALTER TABLE `hardware_images`
  ADD CONSTRAINT `hardware_images_ibfk_1` FOREIGN KEY (`deviceID`) REFERENCES `hardwares` (`deviceID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hardware_transfer_history`
--
ALTER TABLE `hardware_transfer_history`
  ADD CONSTRAINT `hardware_transfer_history_ibfk_1` FOREIGN KEY (`adminEmail`) REFERENCES `administ` (`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hardware_transfer_history_ibfk_2` FOREIGN KEY (`deviceID`) REFERENCES `hardwares` (`deviceID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hardware_transfer_history_ibfk_3` FOREIGN KEY (`fromFaculty`) REFERENCES `faculty` (`idno`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hardware_transfer_history_ibfk_4` FOREIGN KEY (`toFaculty`) REFERENCES `faculty` (`idno`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hardware_transfer_history_ibfk_5` FOREIGN KEY (`fromLabID`) REFERENCES `laboratory` (`labID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hardware_transfer_history_ibfk_6` FOREIGN KEY (`toLabID`) REFERENCES `laboratory` (`labID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `laboratory`
--
ALTER TABLE `laboratory`
  ADD CONSTRAINT `laboratory_ibfk_1` FOREIGN KEY (`idno`) REFERENCES `faculty` (`idno`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `lab_images`
--
ALTER TABLE `lab_images`
  ADD CONSTRAINT `lab_images_ibfk_1` FOREIGN KEY (`labID`) REFERENCES `laboratory` (`labID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
