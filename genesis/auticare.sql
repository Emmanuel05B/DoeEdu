-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2024 at 12:11 AM
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
-- Database: `auticare`
--

-- --------------------------------------------------------

--
-- Table structure for table `contactus`
--

CREATE TABLE `contactus` (
  `Id` int(11) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `ContactNumber` varchar(10) NOT NULL,
  `StreetDetails` varchar(255) NOT NULL,
  `Suburb` varchar(30) NOT NULL,
  `AddressCode` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `Id` int(11) NOT NULL,
  `Qualifications` varchar(255) NOT NULL,
  `Experience` varchar(255) NOT NULL,
  `StartDate` date NOT NULL,
  `employeeType` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`Id`, `Qualifications`, `Experience`, `StartDate`, `employeeType`) VALUES
(1, 'Bed', '12 years of Experience', '0000-00-00', 0),
(2, 'Bed Teacher', '2 years experience', '0000-00-00', 1),
(4, 'Bed', '5 years', '0000-00-00', 2),
(6, 'Bed', '2 years', '0000-00-00', 2),
(7, 'Bed', '5 years', '0000-00-00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `grade`
--

CREATE TABLE `grade` (
  `GradeId` int(11) NOT NULL,
  `GradeName` varchar(30) NOT NULL,
  `SchoolPhase` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grade`
--

INSERT INTO `grade` (`GradeId`, `GradeName`, `SchoolPhase`) VALUES
(1, 'Grade 1', 'Foundation Phas'),
(2, 'Grade 2', 'Foundation Phas'),
(3, 'Grade 3', 'Foundation Phas'),
(4, 'Grade 4', 'Intermediary Ph'),
(5, 'Grade 5', 'Intermediate Ph'),
(6, 'Grade 6', 'Intermediate Ph'),
(7, 'Grade 7', 'Senior Phase');

-- --------------------------------------------------------

--
-- Table structure for table `learner`
--

CREATE TABLE `learner` (
  `LearnerId` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Surname` varchar(255) NOT NULL,
  `Gender` varchar(6) DEFAULT NULL,
  `GradeId` int(11) NOT NULL,
  `IdentityNo` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `learner`
--

INSERT INTO `learner` (`LearnerId`, `Name`, `Surname`, `Gender`, `GradeId`, `IdentityNo`) VALUES
(26, 'Itachi', 'Uchiha', 'Male', 6, '0205095859080'),
(30, 'Mesut', 'Ozil', 'Male', 4, '8805422257845'),
(31, 'Angel', 'DiMaria', 'Male', 7, '0005034748080'),
(32, 'Luka', 'Modric', 'Male', 4, '0005085253080'),
(33, 'Gareth ', 'Bale', 'Male', 1, '0005085256080'),
(34, 'Josh', 'Dun', 'Female', 6, '0025065355080'),
(41, 'Kamogelo', 'Maleka', 'Female', 7, '214444444407');

-- --------------------------------------------------------

--
-- Table structure for table `parent`
--

CREATE TABLE `parent` (
  `Id` int(11) NOT NULL,
  `LearnerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `trackId` int(11) NOT NULL,
  `Describtion` varchar(250) DEFAULT NULL,
  `LearnerId` int(11) DEFAULT NULL,
  `Id` int(11) DEFAULT NULL,
  `Picture` varchar(250) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `VidFilePath` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`trackId`, `Describtion`, `LearnerId`, `Id`, `Picture`, `created_at`, `VidFilePath`) VALUES
(4, 'This is Angel', 31, 2, 'uploadspk.png ', '2024-05-05 10:11:30', 'uploadspk.png'),
(5, '22222222fghjk', 32, 6, 'uploads1.jpg ', '2024-05-05 14:54:59', 'uploads1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Id` int(11) NOT NULL,
  `UserId` varchar(20) NOT NULL,
  `Surname` varchar(20) NOT NULL,
  `Name` varchar(20) NOT NULL,
  `UserPassword` varchar(255) NOT NULL,
  `Gender` varchar(6) DEFAULT NULL,
  `Email` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Id`, `UserId`, `Surname`, `Name`, `UserPassword`, `Gender`, `Email`) VALUES
(1, '0005035457585', 'Seloane', 'Martha', '12345', 'Female', 'emahlwele0@gmail.com'),
(2, '1111111111111', 'Biko', 'Martha', '12345', 'Male', 'emahlwele05@gmail.com'),
(4, '2154785412548', 'Maleka', 'Lorence', '12345', 'Male', 'lorence@gmail.com'),
(6, '1112223334445556', 'Matlala', 'Bofa', '12345', 'Male', 'bofa@gmail.com'),
(7, '2020202020202', 'Ncube', 'Themba', '12345', 'Male', 'ncube@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contactus`
--
ALTER TABLE `contactus`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `grade`
--
ALTER TABLE `grade`
  ADD PRIMARY KEY (`GradeId`);

--
-- Indexes for table `learner`
--
ALTER TABLE `learner`
  ADD PRIMARY KEY (`LearnerId`),
  ADD KEY `GradeId` (`GradeId`);

--
-- Indexes for table `parent`
--
ALTER TABLE `parent`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `LearnerID` (`LearnerID`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`trackId`),
  ADD KEY `LearnerId` (`LearnerId`),
  ADD KEY `Id` (`Id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `UserId` (`UserId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `grade`
--
ALTER TABLE `grade`
  MODIFY `GradeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `learner`
--
ALTER TABLE `learner`
  MODIFY `LearnerId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `trackId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contactus`
--
ALTER TABLE `contactus`
  ADD CONSTRAINT `contactus_ibfk_1` FOREIGN KEY (`Id`) REFERENCES `users` (`Id`);

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`Id`) REFERENCES `users` (`Id`);

--
-- Constraints for table `learner`
--
ALTER TABLE `learner`
  ADD CONSTRAINT `learner_ibfk_1` FOREIGN KEY (`GradeId`) REFERENCES `grade` (`GradeId`);

--
-- Constraints for table `parent`
--
ALTER TABLE `parent`
  ADD CONSTRAINT `parent_ibfk_1` FOREIGN KEY (`Id`) REFERENCES `users` (`Id`),
  ADD CONSTRAINT `parent_ibfk_2` FOREIGN KEY (`LearnerID`) REFERENCES `learner` (`LearnerId`);

--
-- Constraints for table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `report_ibfk_1` FOREIGN KEY (`LearnerId`) REFERENCES `learner` (`LearnerId`),
  ADD CONSTRAINT `report_ibfk_2` FOREIGN KEY (`Id`) REFERENCES `users` (`Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
