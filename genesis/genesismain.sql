-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2025 at 01:12 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `genesismain`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `ActivityId` int(11) NOT NULL,
  `ActivityName` varchar(255) NOT NULL,
  `SubjectId` int(11) DEFAULT NULL,
  `ActivityDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `MaxMarks` decimal(10,2) DEFAULT NULL,
  `Creator` varchar(255) DEFAULT NULL,
  `Grade` int(3) NOT NULL,
  `ChapterName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`ActivityId`, `ActivityName`, `SubjectId`, `ActivityDate`, `MaxMarks`, `Creator`, `Grade`, `ChapterName`) VALUES
(99, 'activity 1', 2, '2025-02-17 17:01:37', 8.00, 'Boshielo', 12, 'Momentum and Impulse'),
(100, 'activity 1', 1, '2025-02-17 17:01:22', 27.00, 'Boshielo', 12, 'Sequences and Series'),
(101, 'activity 1', 2, '2025-02-20 18:44:26', 20.00, 'Boshielo', 12, 'Vertical Projectile'),
(102, 'activity 1', 3, '2025-02-20 18:46:56', 13.00, 'Boshielo', 11, 'Exponents and Surds'),
(103, 'activity 1', 1, '2025-02-24 17:32:00', 20.00, 'Boshielo', 12, 'Functions'),
(104, 'Inverse', 1, '2025-03-03 20:04:44', 20.00, 'Boshielo', 12, 'Functions'),
(105, 'activity 1', 2, '2025-03-03 20:07:45', 14.00, 'Boshielo', 12, 'Organic Chemistry'),
(106, 'activity 1', 3, '2025-03-03 20:08:58', 27.00, 'Boshielo', 11, 'Equations and Inequalities'),
(107, 'activity 1', 5, '2025-03-06 18:35:38', 16.00, 'Boshielo', 10, 'Algebraic Expressions'),
(108, 'activity 1', 1, '2025-03-11 20:15:56', 18.00, 'Boshielo', 12, 'Trigonometry'),
(109, 'activity 2', 3, '2025-03-11 20:17:13', 27.00, 'Boshielo', 11, 'Equations and Inequalities'),
(110, 'activity 2', 3, '2025-03-15 11:44:04', 23.00, 'Boshielo', 11, 'Trigonometry'),
(111, 'activity 2', 2, '2025-03-15 12:07:23', 24.00, 'Boshielo', 12, 'Organic Chemistry'),
(112, 'activity 1', 3, '2025-03-15 12:00:09', 20.00, 'Boshielo', 11, 'Trigonometry'),
(113, 'activity 1', 5, '2025-03-17 17:53:48', 30.00, 'Boshielo', 10, 'Exponents'),
(114, 'Test 1', 3, '2025-03-17 17:57:52', 60.00, 'Boshielo', 11, 'Statistics'),
(115, 'activity 3', 2, '2025-03-17 17:59:03', 42.00, 'Boshielo', 12, 'Organic Chemistry'),
(116, 'activity 4', 2, '2025-03-27 19:26:29', 17.00, 'Boshielo', 12, 'Organic Chemistry'),
(117, 'activity 2', 5, '2025-03-27 19:28:24', 49.00, 'Boshielo', 10, 'Equations and Inequalities'),
(118, 'activity 2', 1, '2025-03-27 19:33:39', 16.00, 'Boshielo', 12, 'Trigonometry');

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `Id` int(11) NOT NULL,
  `ActivityName` varchar(25) NOT NULL,
  `Creator` varchar(50) NOT NULL,
  `Grade` int(5) NOT NULL,
  `Sub` varchar(25) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `Total` int(11) DEFAULT NULL,
  `Chapter` varchar(50) DEFAULT NULL
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
  `employeeType` int(11) NOT NULL,
  `Specialisation` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`Id`, `Qualifications`, `Experience`, `StartDate`, `employeeType`, `Specialisation`) VALUES
(65, 'Bed', '10', '2024-07-09', 0, 'ASD Level 3'),
(103, 'Bed', '10', '2024-07-10', 1, 'ASD Level 3'),
(119, '', '0', '2024-07-22', 1, 'ASD Level 1'),
(132, '', '', '2024-08-25', 1, 'ASD Level 2'),
(135, '', '', '2024-09-30', 1, 'ASD Level 3');

-- --------------------------------------------------------

--
-- Table structure for table `finances`
--

CREATE TABLE `finances` (
  `FinanceId` int(11) NOT NULL,
  `LearnerId` int(11) DEFAULT NULL,
  `Grade` int(3) DEFAULT NULL,
  `TotalFees` decimal(10,2) DEFAULT NULL,
  `TotalPaid` decimal(10,2) DEFAULT NULL,
  `Math` decimal(15,2) DEFAULT NULL,
  `Physics` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `finances`
--

INSERT INTO `finances` (`FinanceId`, `LearnerId`, `Grade`, `TotalFees`, `TotalPaid`, `Math`, `Physics`) VALUES
(45, 89, 12, 2398.00, 0.00, 1199.00, 1199.00),
(46, 90, 12, 2398.00, 0.00, 1199.00, 1199.00),
(47, 91, 12, 1199.00, 0.00, 1199.00, 0.00),
(49, 93, 12, 1199.00, 0.00, 1199.00, 0.00),
(50, 94, 11, 1199.00, 0.00, 1199.00, 0.00),
(51, 95, 11, 750.00, 0.00, 750.00, 0.00),
(52, 96, 12, 1500.00, 0.00, 750.00, 750.00),
(53, 97, 12, 1500.00, 0.00, 750.00, 750.00),
(54, 98, 12, 750.00, 0.00, 0.00, 750.00);

-- --------------------------------------------------------

--
-- Table structure for table `learner`
--

CREATE TABLE `learner` (
  `LearnerId` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Surname` varchar(255) NOT NULL,
  `Gender` varchar(6) DEFAULT NULL,
  `GradeId` varchar(255) DEFAULT NULL,
  `DateOfBirth` date DEFAULT NULL,
  `FunctionalLevel` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `learner`
--

INSERT INTO `learner` (`LearnerId`, `Name`, `Surname`, `Gender`, `GradeId`, `DateOfBirth`, `FunctionalLevel`) VALUES
(32, 'Luka', 'Modric', 'Male', '2', '0000-00-00', 'ASD Level 2'),
(65, 'Sydney', 'Mamogobo', 'Male', '2', '2009-05-04', 'ASD Level 2'),
(66, 'Tebogo', 'Makua', 'Male', '1', '2005-02-01', 'ASD Level 1'),
(67, 'Jimmy', 'Moshia', 'Male', '2', '2000-12-05', 'ASD Level 2'),
(68, 'Orelia', 'Tjiane', 'Female', '3', '2000-02-12', 'ASD Level 3'),
(69, 'Felicia', 'Mashifane', 'Female', '1', '2010-02-12', 'ASD Level 1'),
(70, 'Thato', 'Mahlare', 'Male', '1', '2005-12-14', 'ASD Level 1'),
(71, 'Lesiba', 'Masilela', 'Male', '1', '2000-12-14', 'ASD Level 1'),
(72, 'Danny', 'Silver', 'Male', '1', '1999-12-15', 'ASD Level 1'),
(73, 'Thiago', 'Alcantara', 'Male', '3', '2015-12-04', 'ASD Level 3'),
(74, 'Harold', 'February', 'Male', '2', '2000-12-15', 'ASD Level 2'),
(75, 'Tinto', 'Thesela', 'Male', '2', '2000-12-12', 'ASD Level 2'),
(77, 'Nicky', 'Reeds', 'Female', '3', '2015-07-29', 'ASD Level 3');

-- --------------------------------------------------------

--
-- Table structure for table `learneractivitymarks`
--

CREATE TABLE `learneractivitymarks` (
  `Id` int(11) NOT NULL,
  `LearnerId` int(11) NOT NULL,
  `ActivityId` int(11) NOT NULL,
  `MarkerId` int(11) NOT NULL,
  `MarksObtained` double NOT NULL,
  `DateAssigned` timestamp NOT NULL DEFAULT current_timestamp(),
  `Attendance` varchar(255) NOT NULL,
  `AttendanceReason` varchar(255) NOT NULL,
  `Submission` varchar(255) NOT NULL,
  `SubmissionReason` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `learneractivitymarks`
--

INSERT INTO `learneractivitymarks` (`Id`, `LearnerId`, `ActivityId`, `MarkerId`, `MarksObtained`, `DateAssigned`, `Attendance`, `AttendanceReason`, `Submission`, `SubmissionReason`) VALUES
(131, 89, 99, 65, 8, '2025-02-16 10:19:46', 'present', 'None', 'Yes', 'None'),
(132, 90, 99, 65, 2, '2025-02-16 10:19:46', 'present', 'None', 'Yes', 'None'),
(133, 89, 100, 65, 0, '2025-02-17 16:28:26', 'present', 'None', 'No', 'Did Not Write'),
(134, 90, 100, 65, 9, '2025-02-17 16:28:26', 'present', 'None', 'Yes', 'None'),
(135, 91, 100, 65, 9, '2025-02-17 16:28:27', 'present', 'None', 'Yes', 'None'),
(136, 93, 100, 65, 0, '2025-02-17 16:28:27', 'present', 'None', 'No', 'Did Not Write'),
(137, 89, 101, 65, 9, '2025-02-20 18:44:44', 'present', 'None', 'Yes', 'None'),
(138, 90, 101, 65, 8, '2025-02-20 18:44:44', 'present', 'None', 'Yes', 'None'),
(139, 94, 102, 65, 6, '2025-02-20 18:47:09', 'present', 'None', 'Yes', 'None'),
(140, 89, 103, 65, 16, '2025-02-24 17:50:44', 'present', 'None', 'Yes', 'None'),
(141, 90, 103, 65, 0, '2025-02-24 17:50:44', 'absent', 'None', 'No', 'None'),
(142, 91, 103, 65, 8, '2025-02-24 17:50:45', 'absent', 'Data Issues', 'Yes', 'None'),
(143, 93, 103, 65, 0, '2025-02-24 17:50:45', 'present', 'None', 'Yes', 'None'),
(144, 89, 104, 65, 0, '2025-03-03 20:05:46', 'absent', 'None', 'No', 'None'),
(145, 90, 104, 65, 0, '2025-03-03 20:05:46', 'absent', 'Data Issues', 'No', 'None'),
(146, 91, 104, 65, 0, '2025-03-03 20:05:46', 'absent', 'Other', 'No', 'None'),
(147, 93, 104, 65, 0, '2025-03-03 20:05:46', 'absent', 'Data Issues', 'No', 'None'),
(148, 89, 105, 65, 14, '2025-03-03 20:08:18', 'present', 'None', 'Yes', 'None'),
(149, 90, 105, 65, 1, '2025-03-03 20:08:18', 'present', 'None', 'Yes', 'None'),
(150, 94, 106, 65, 9, '2025-03-03 20:09:12', 'present', 'None', 'Yes', 'None'),
(151, 95, 107, 65, 6, '2025-03-06 18:35:50', 'present', 'None', 'Yes', 'None'),
(152, 89, 108, 65, 0, '2025-03-11 20:11:21', 'present', 'None', 'Yes', 'None'),
(153, 90, 108, 65, 0, '2025-03-11 20:11:21', 'present', 'None', 'No', 'None'),
(154, 91, 108, 65, 18, '2025-03-11 20:11:21', 'present', 'None', 'Yes', 'None'),
(155, 93, 108, 65, 0, '2025-03-11 20:11:21', 'present', 'None', 'No', 'None'),
(156, 94, 109, 65, 13, '2025-03-11 20:17:19', 'present', 'None', 'Yes', 'None'),
(157, 94, 110, 65, 12, '2025-03-15 11:44:12', 'present', 'None', 'Yes', 'None'),
(158, 89, 111, 65, 0, '2025-03-15 11:45:50', 'absent', 'None', 'No', 'None'),
(159, 90, 111, 65, 21, '2025-03-15 11:45:50', 'present', 'None', 'Yes', 'None'),
(160, 94, 112, 65, 10, '2025-03-15 12:00:15', 'present', 'None', 'Yes', 'None'),
(161, 95, 113, 65, 16, '2025-03-17 17:54:06', 'present', 'None', 'Yes', 'None'),
(162, 94, 114, 65, 27, '2025-03-17 17:58:04', 'present', 'None', 'Yes', 'None'),
(163, 89, 115, 65, 0, '2025-03-17 17:59:21', 'present', 'None', 'Yes', 'None'),
(164, 90, 115, 65, 25, '2025-03-17 17:59:22', 'present', 'None', 'Yes', 'None'),
(165, 90, 116, 65, 14, '2025-03-27 19:26:46', 'present', 'None', 'Yes', 'None'),
(166, 96, 116, 65, 17, '2025-03-27 19:26:46', 'present', 'None', 'Yes', 'None'),
(167, 97, 116, 65, 17, '2025-03-27 19:26:46', 'present', 'None', 'Yes', 'None'),
(168, 95, 117, 65, 4, '2025-03-27 19:28:33', 'present', 'None', 'Yes', 'None'),
(169, 90, 118, 65, 0, '2025-03-27 19:35:35', 'present', 'None', 'No', 'None'),
(170, 91, 118, 65, 0, '2025-03-27 19:35:35', 'present', 'None', 'No', 'None'),
(171, 93, 118, 65, 14, '2025-03-27 19:35:35', 'present', 'None', 'Yes', 'None'),
(172, 96, 118, 65, 0, '2025-03-27 19:35:35', 'present', 'None', 'No', 'None'),
(173, 97, 118, 65, 0, '2025-03-27 19:35:35', 'absent', 'None', 'No', 'None');

-- --------------------------------------------------------

--
-- Table structure for table `learnerregistrationanswers`
--

CREATE TABLE `learnerregistrationanswers` (
  `LearnerId` int(11) NOT NULL,
  `QuestionId` int(11) NOT NULL,
  `AnswerText` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `learners`
--

CREATE TABLE `learners` (
  `LearnerId` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Surname` varchar(255) NOT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `ContactNumber` int(255) NOT NULL,
  `Grade` int(10) DEFAULT NULL,
  `RegistrationDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `LearnerKnockoffTime` time(6) DEFAULT NULL,
  `Math` decimal(10,2) DEFAULT NULL,
  `Physics` decimal(10,2) DEFAULT NULL,
  `TotalFees` decimal(10,2) DEFAULT NULL,
  `TotalPaid` decimal(10,2) DEFAULT NULL,
  `TotalOwe` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `learners`
--

INSERT INTO `learners` (`LearnerId`, `Name`, `Surname`, `Email`, `ContactNumber`, `Grade`, `RegistrationDate`, `LearnerKnockoffTime`, `Math`, `Physics`, `TotalFees`, `TotalPaid`, `TotalOwe`) VALUES
(89, 'California', 'Malata', 'smokeyflab13.02.4e@gmail.com', 727227777, 12, '2025-02-07 18:57:23', '17:00:00.000000', 1199.00, 1199.00, 1950.00, 150.00, 1800.00),
(90, 'Sindi', 'Matianyane', 'matianyanesindi09@gmail.com', 606102144, 12, '2025-02-07 19:00:59', '14:30:00.000000', 1199.00, 1199.00, 1950.00, 700.00, 1250.00),
(91, 'Beauty', 'Malatji', 'malatjibeauty37@gmail.com', 674864174, 12, '2025-02-07 19:03:09', '16:00:00.000000', 1199.00, 0.00, 1199.00, 120.00, 1079.00),
(93, 'Lesedi Lebogang', 'Maeyane', 'leboganglesedi41@gmail.com', 699548120, 12, '2025-02-15 12:45:46', '16:45:00.000000', 1199.00, 0.00, 1199.00, 150.00, 1049.00),
(94, 'Angel', 'Moruni', 'moruniangel@gmail.com', 663744561, 11, '2025-02-15 21:52:01', '16:00:00.000000', 1199.00, 0.00, 1199.00, 450.00, 749.00),
(95, 'Neo', 'Mampana', 'neo@gmail.com', 609268321, 10, '2025-02-18 10:53:11', '16:00:00.000000', 750.00, 0.00, 750.00, 750.00, 0.00),
(96, 'Nombulelo', 'Dlamini', 'ndlamini2411@gmail.com', 648126426, 12, '2025-03-27 05:05:53', '16:00:00.000000', 750.00, 750.00, 1250.00, 200.00, 1050.00),
(97, 'Precious', 'Nkaiseng', 'nkaiengtshegofatso2@gmail.com', 719543166, 12, '2025-03-27 05:09:06', '16:00:00.000000', 750.00, 750.00, 1250.00, 200.00, 1050.00),
(98, 'Khomotso', 'Mahole', 'kgomomahole@gmail.com', 697730300, 12, '2025-04-25 19:22:19', '15:00:00.000000', 0.00, 750.00, 750.00, 150.00, 600.00);

-- --------------------------------------------------------

--
-- Table structure for table `learnersubject`
--

CREATE TABLE `learnersubject` (
  `LearnerSubjectId` int(11) NOT NULL,
  `LearnerId` int(11) NOT NULL,
  `SubjectId` int(11) NOT NULL,
  `TargetLevel` int(11) DEFAULT NULL,
  `CurrentLevel` int(11) DEFAULT NULL,
  `NumberOfTerms` int(11) DEFAULT NULL,
  `ContractExpiryDate` datetime DEFAULT NULL,
  `Status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `learnersubject`
--

INSERT INTO `learnersubject` (`LearnerSubjectId`, `LearnerId`, `SubjectId`, `TargetLevel`, `CurrentLevel`, `NumberOfTerms`, `ContractExpiryDate`, `Status`) VALUES
(46, 89, 1, 7, 4, 0, '2000-02-07 18:28:48', 'Not Active'),
(47, 89, 2, 7, 2, 0, '2000-02-07 18:28:48', 'Not Active'),
(48, 90, 1, 4, 1, 0, '2026-02-07 18:28:48', 'Not Active'),
(49, 90, 2, 4, 2, 0, '2026-02-07 18:28:48', 'Not Active'),
(50, 91, 1, 4, 2, 0, '2026-02-07 18:28:48', 'Not Active'),
(52, 93, 1, 5, 3, 3, '2026-02-15 13:45:47', 'Active'),
(53, 94, 3, 5, 1, 3, '2026-02-15 22:52:02', 'Active'),
(54, 95, 5, 7, 4, 2, '2025-08-18 11:53:11', 'Active'),
(55, 96, 1, 6, 2, 2, '2025-09-27 06:05:53', 'Active'),
(56, 96, 2, 6, 3, 2, '2025-09-27 06:05:53', 'Active'),
(57, 97, 1, 7, 2, 2, '2025-09-27 06:09:06', 'Active'),
(58, 97, 2, 7, 2, 2, '2025-09-27 06:09:06', 'Active'),
(59, 98, 2, 4, 2, 2, '2025-10-25 21:22:19', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `NoticeNo` int(11) NOT NULL,
  `LearnerId` int(11) NOT NULL,
  `Notice` varchar(50) DEFAULT NULL,
  `Reason` text DEFAULT NULL,
  `Date` timestamp NOT NULL DEFAULT current_timestamp(),
  `IsOpened` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`NoticeNo`, `LearnerId`, `Notice`, `Reason`, `Date`, `IsOpened`) VALUES
(2, 74, 'Absent', 'Sick', '2024-08-22 22:00:00', 1),
(3, 74, 'Absent', 'Doctor’s appointment ', '2024-08-08 22:00:00', 1),
(4, 67, 'Absent', 'Doctor’s appointment \n', '2024-08-08 22:00:00', 1),
(5, 67, 'Meeting', 'Teachers meeting at 12PM', '2024-08-10 22:00:00', 1),
(7, 68, 'late', 'traffic issues', '2024-08-22 22:50:13', 1),
(8, 74, 'Absent', 'back late from vacation', '2024-08-22 22:00:00', 1),
(9, 75, 'Absent', 'Woke up late', '2024-08-24 11:38:56', 1);

-- --------------------------------------------------------

--
-- Table structure for table `parentlearner`
--

CREATE TABLE `parentlearner` (
  `ParentId` int(11) NOT NULL,
  `LearnerId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parentlearner`
--

INSERT INTO `parentlearner` (`ParentId`, `LearnerId`) VALUES
(118, 89),
(119, 90),
(121, 91),
(122, 92),
(122, 93),
(123, 94),
(124, 95),
(125, 96),
(126, 97),
(127, 98);

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

CREATE TABLE `parents` (
  `ParentId` int(11) NOT NULL,
  `ParentTitle` varchar(255) NOT NULL,
  `ParentName` varchar(255) NOT NULL,
  `ParentEmail` varchar(255) DEFAULT NULL,
  `ParentContactNumber` varchar(20) DEFAULT NULL,
  `ParentSurname` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parents`
--

INSERT INTO `parents` (`ParentId`, `ParentTitle`, `ParentName`, `ParentEmail`, `ParentContactNumber`, `ParentSurname`) VALUES
(118, 'Mrs', 'Letty', 'malatasuccess2@gmail.com', '820508470', 'Malata'),
(119, 'Ms', 'Thokozile', 'noneprovided@gmail.com', '607136879', 'Matlanyane'),
(120, 'Ms', 'Mokgadi', 'yvonnemala@gmail.com', '822597551', 'Malatji'),
(121, 'Ms', 'Mokgadi', 'yvonnemala16@gmail.com', '822597551', 'Malatji'),
(122, 'Mrs', 'Cecilia', 'noneprovided2@gmail.com', '793643050', 'Maeyane'),
(123, 'Ms', 'Emmah', 'emmahmasterm@gmail.com', '768585649', 'Moruni'),
(124, 'Ms', 'Tshepiso', 'kanyanetshepiso35@gmail.com', '728436832', 'Matlanyane'),
(125, 'Mr', 'Johannes', 'thakisip@gmail.com', '723262280', 'Thakisi'),
(126, 'Mr', 'Johannes', 'thakhisip@gmail.com', '723262280', 'Thakisi'),
(127, 'Mr', 'Michael', 'michaelmahole6@gmail.com', '789354912', 'Mahole');

-- --------------------------------------------------------

--
-- Table structure for table `registrationquestions`
--

CREATE TABLE `registrationquestions` (
  `QuestionId` int(11) NOT NULL,
  `QuestionText` varchar(255) NOT NULL,
  `QuestionType` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `SubjectId` int(11) NOT NULL,
  `SubjectName` varchar(255) NOT NULL,
  `SubjectCode` varchar(50) DEFAULT NULL,
  `ThreeMonthsPrice` decimal(10,2) DEFAULT 0.00,
  `SixMonthsPrice` decimal(10,2) DEFAULT 0.00,
  `TwelveMonthsPrice` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`SubjectId`, `SubjectName`, `SubjectCode`, `ThreeMonthsPrice`, `SixMonthsPrice`, `TwelveMonthsPrice`) VALUES
(1, 'Mathematics12', 'Mat12', 450.00, 750.00, 1199.00),
(2, 'Physics12', 'Phy12', 450.00, 750.00, 1199.00),
(3, 'Mathematics11', 'Mat11', 0.00, 0.00, 0.00),
(4, 'Physics11', 'Phy11', 0.00, 0.00, 0.00),
(5, 'Mathematics10', 'Mat10', 0.00, 0.00, 0.00),
(6, 'Physics10', 'Phy10', 0.00, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `todolist`
--

CREATE TABLE `todolist` (
  `TodoId` int(11) NOT NULL,
  `CreatorId` int(11) NOT NULL,
  `TaskText` text NOT NULL,
  `CreationDate` datetime DEFAULT current_timestamp(),
  `DueDate` datetime DEFAULT NULL,
  `Priority` varchar(10) DEFAULT NULL,
  `Status` int(11) DEFAULT 0,
  `TimeSpent` time DEFAULT NULL,
  `CompletionDate` datetime DEFAULT NULL,
  `Category` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `todolist`
--

INSERT INTO `todolist` (`TodoId`, `CreatorId`, `TaskText`, `CreationDate`, `DueDate`, `Priority`, `Status`, `TimeSpent`, `CompletionDate`, `Category`) VALUES
(5, 65, 'hi 2025', '2024-12-30 00:18:38', '2025-02-01 00:19:00', 'High', 0, NULL, NULL, 'General'),
(6, 132, 'SDFGHJK', '2024-12-31 10:27:35', '2025-01-02 10:29:00', 'Low', 0, NULL, NULL, 'General'),
(8, 65, 'Work on the Submit Marks function', '2025-01-16 10:39:48', '2025-01-18 10:40:00', 'High', 0, NULL, NULL, 'General'),
(9, 65, 'Work on the Class list PDFF', '2025-01-16 10:40:38', '2025-01-17 10:41:00', 'High', 0, NULL, NULL, 'General'),
(10, 65, 'Verify the code for sendimg emails to parents', '2025-01-16 10:41:26', '2025-01-18 10:43:00', 'Medium', 0, NULL, NULL, 'General'),
(11, 65, 'Search to see if we can\'t send SmS', '2025-01-16 10:42:27', '2025-01-23 14:02:00', 'Low', 0, NULL, NULL, 'General'),
(12, 65, 'Finish the ToDo List Functionality', '2025-01-16 10:43:20', '2025-01-24 17:25:00', 'Low', 0, NULL, NULL, 'General'),
(13, 65, 'Update the Finance page', '2025-01-16 11:07:24', '2025-01-28 11:08:00', 'Low', 0, NULL, NULL, 'General'),
(14, 65, 'finalize the attendace graph', '2025-01-16 11:09:18', '2025-01-31 14:02:00', 'Low', 0, NULL, NULL, 'General');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Id` int(11) NOT NULL,
  `UserId` varchar(20) NOT NULL,
  `Surname` varchar(100) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `UserPassword` varchar(255) NOT NULL,
  `Gender` varchar(6) DEFAULT NULL,
  `Contact` int(10) NOT NULL,
  `AlternativeContact` int(10) DEFAULT NULL,
  `Email` varchar(100) NOT NULL,
  `IsVerified` int(2) NOT NULL,
  `ResetCode` varchar(64) NOT NULL,
  `ResetTimestamp` timestamp NULL DEFAULT current_timestamp(),
  `VerificationToken` varchar(64) NOT NULL,
  `RegistrationDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Id`, `UserId`, `Surname`, `Name`, `UserPassword`, `Gender`, `Contact`, `AlternativeContact`, `Email`, `IsVerified`, `ResetCode`, `ResetTimestamp`, `VerificationToken`, `RegistrationDate`) VALUES
(65, '1254125412541', 'Boshielo', 'Emmanuel', '$2y$10$2wdu5zdWMu.wG.wa.JxuMed9QzPuYlif1w7wIxlk7RSdZlAZFpGgG', 'Mr', 723620225, 0, 'eboshielo@doe.com', 1, '$2y$10$ayEYoSGc9mNzw6awXvmWgOY0Ye6LOh..osu6A3bO0HMHhftKecoWG', '2024-07-09 09:13:38', 'f3659d1c92546274b30c2ade4b5e3012da1cfabc13f04bb3b9cf9a76007090eb', '2024-07-09 08:47:11'),
(103, '2032021202012', 'Malesela', 'Shirley', '$2y$10$t2VD2G8anvVZ/8IQ5f43RO9fq33OI38c8WU3gfNnn/EEHCy9rRL7G', 'Mr', 878458885, 2147483647, 'shirley@gmail.com', 1, '', '2024-07-10 19:49:48', 'f9c5c52aee8b1fd0a72553256e2a425ef8aece59d855a99b710cc1fe0dbf0f52', '2024-07-10 19:49:48'),
(119, '1254114125223', 'Chagane', 'Boitumelo', '$2y$10$Jt29xcBdUZj9wjuhSJQ6TO2VxBWpmNjXbKnujoncviUbFkhd1kptm', 'Mr', 795674125, 0, 'geminikiddow1@gmail.com', 0, '', '2024-07-22 07:56:32', 'd77a3c725391e9599409712ba40eb71e85f7e4049a4dfa18786c25928bb0a706', '2024-07-22 07:56:32'),
(120, '1252241587500', 'Boshielo', 'Emmanuel Thopane', '$2b$10$rZ8.4asvy88c7iE3ClTajejcd1eX.uoxgizIqev.6Ke0I4EPpA4AK', 'Mr', 0, 0, 'emahlwele005@gmail.com', 0, '$2y$10$cIDWcNZRANxWSnZ4a23RtOsEfyKrZxlNuV1W8602mPixujrRJJo/C', '2024-09-30 06:14:16', '301ac8770c5442db0cc392462e27e7d81d920ff545498513108af63e0e54823f', '2024-07-22 08:12:36'),
(121, '2021452552141', 'Maleka', 'Kamogelo', '$2y$10$fTQvHPHC5QGr6mZRv6oRIenzcfEGGjVmRqWgIcuRjFiOgAHONPIgS', 'Mr', 2025521144, 0, 'kahahhd@gmail.com', 0, '', '2024-07-22 10:49:05', '4829d1c4b97cdd0bf70728632a07d187a1c619f67e2812af8cb6635731ba41c3', '2024-07-22 10:49:05'),
(122, '972524272524', 'Bia', 'Jack', '$2b$10$5UOmI6VjvXe5MZ/BMqXU6eHkYfwSAZ/UQU9kMAOGPmmSZdxVTaV1S', 'Mr', 752423232, 0, 'jack@gmail.com', 0, '', '2024-08-06 19:36:59', '8269d5812f54bebccb82fad2ab277875457174e03f79f0b85388659ddebf42de', '2024-08-06 19:36:59'),
(123, '6254223233222', 'Messi', 'Leo', '$2b$10$lOaHzdeuoLUeQqpBLVV5euHHJlamtFYq3E0JjVVufzMl1COyw/O.a', 'Mr', 0, 0, 'messi@gmail.com', 0, '', '2024-08-06 19:37:43', '8b1ac7917747ceac02ec21ddfd402b871735f5f2c64a7ae9b5de5271befc3661', '2024-08-06 19:37:43'),
(124, '9854223233222', 'Diaz', 'Sharon', '$2b$10$FhpfFftnHm2F7j15PlHHXeH22/2ut/TxlSWn57I1O5xEdo.OgYQVi', 'Mrs', 764251416, 0, 'shaz@gmail.com', 0, '', '2024-08-06 19:42:27', '1ab92f6c05378200330ff9e5a4dfce46ecfd64604dc8e691bf899fe7319b6a40', '2024-08-06 19:42:27'),
(125, '9022726534344', 'Fiasco', 'Lupe', '$2b$10$hmUgJMsxv2WP4DGMf.Z05O7aM/qgB5bzEjzTwnJEHltacNKJFWOL.', 'Mr', 762424266, 0, 'lupe@gmail.com', 0, '', '2024-08-06 19:47:10', '8a61076ffff8acc6e5ba549633ee4d0b1ddf039875de28ef5fbeff258a7bf2fb', '2024-08-06 19:47:10'),
(126, '8882752444353', 'Kani', 'John', '$2b$10$IpyHSCoEPrpnj6U7JQmOg.wiChjdrF/ct8WWVAkyExpvt7.szPnVa', 'Mr', 862525346, 0, 'kani@gmail.com', 0, '', '2024-08-06 19:49:01', 'eeb6f362b013b7667820ce4999427e4d5878695eb3f606420e106f429818d7da', '2024-08-06 19:49:01'),
(127, '7654322120998', 'Yamal', 'Lamine', '$2b$10$VaVMF/9yWNaam6RxXDgK9eebLL77vBspNE3xA4sYqOQtkxf.Oaufy', 'Mr', 832411114, 0, 'yamal@gmail.com', 0, '', '2024-08-06 19:50:21', 'd3dbb93f43d6b046aa1f2194a3a7a58f29e87411671dd1b7b409e06133be5faf', '2024-08-06 19:50:21'),
(129, '7098434352524', 'Reigns', 'Nia', '$2b$10$kzvVvHMWRCWOuTAI4BdZE.sdOYOp8jwAgiHixHbvT9FJWDeDqMnu6', 'Mr', 826544178, 0, 'nia@gmail.com', 0, '', '2024-08-06 19:52:54', '8b0faece40ac31b7b8db69f46d0b0a37add178e031fa93032fa8206cfd31adc4', '2024-08-06 19:52:54'),
(130, '9466577762542', 'Mkhonto', 'Felicity', '$2b$10$E4wLqndHqdyJ2XqmKldk8OZ8TLO3LiToYMW4h7H4dZ8oRRsoT2KQW', 'Mrs', 762243252, 0, 'felicity@gmail.com', 0, '', '2024-08-06 19:53:55', '5c50efbf3f745a915fec69b7e087f0734874f5733632d063d42e2b18ba9aea92', '2024-08-06 19:53:55'),
(131, '8907127635534', 'Pule', 'Mako', '$2b$10$2ov00jutEB2jBjEhN997KeRYarhMRjjL8FdTX1D2rgxdnkNN3f02u', 'Mr', 726543256, 0, 'pule@gmail.com', 0, '', '2024-08-06 19:54:54', '0d043903fdf50b1921496c03607a97fef4df8c08cb36e2f3a88f70bc5c4d034a', '2024-08-06 19:54:54'),
(132, '2056232223200', 'Dlamini', 'Makies', '$2y$10$BeoRqCe2Q5Z179jlrvFuy.tjUafIIbNWUH7cyKiS.riTL9k8QdgBG', 'Mr', 2147483647, 2147483647, 'dlamini@gmail.com', 0, '', '2024-08-25 10:12:55', '32023ee833c8b2787d7022dc4566e5e535a5e274b701f3bb171a255a90a4a954', '2024-08-25 10:12:55'),
(135, '2222228222323', 'Test', 'Mbongeni', '$2y$10$4/RdBLdSpJggDhuVFLwHC.kZjiA7ke6OAjishKGpFy4NjBSJDNxZq', 'Mr', 2147483647, 0, 'distributorsdoe@gmail.com', 1, '', '2024-09-29 22:23:28', '', '2024-09-29 22:23:28'),
(136, '2151514580000', 'Busines', 'DOE dssad', '$2y$10$Vahg4G9ThyNHu6SPJS2XHO3Ylw97EEq4ysYwx0ep5pgv/KMWL1Ffe', 'Mr', 745323516, 0, 'emahlweldce05@gmail.com', 0, '', '2024-10-01 09:32:07', '4c8e301224db2012194fd0e7072cac8ab73f87f5fadd4c01d6b10a083d2f3b20', '2024-10-01 09:32:07'),
(138, '3020214502022', 'Matlala', 'Thabo', '$2y$10$tHZGELWRGaIgdqlJFOejO.IsRSMLahd/vsx4a1yspHKCD15IcewR2', 'Mr', 2020202020, 0, 'emahlwele05@gmail.com', 1, '$2y$10$HiTJv38/k42jvppSYldZbuxuEll8cX1cm7TJwTnk6zVuKP785v3F6', '2025-01-07 03:17:17', '', '2024-11-22 12:40:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`ActivityId`),
  ADD KEY `SubjectId` (`SubjectId`);

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `finances`
--
ALTER TABLE `finances`
  ADD PRIMARY KEY (`FinanceId`),
  ADD KEY `LearnerId` (`LearnerId`);

--
-- Indexes for table `learner`
--
ALTER TABLE `learner`
  ADD PRIMARY KEY (`LearnerId`),
  ADD KEY `GradeId` (`GradeId`);

--
-- Indexes for table `learneractivitymarks`
--
ALTER TABLE `learneractivitymarks`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `learnerregistrationanswers`
--
ALTER TABLE `learnerregistrationanswers`
  ADD PRIMARY KEY (`LearnerId`,`QuestionId`),
  ADD KEY `QuestionId` (`QuestionId`);

--
-- Indexes for table `learners`
--
ALTER TABLE `learners`
  ADD PRIMARY KEY (`LearnerId`);

--
-- Indexes for table `learnersubject`
--
ALTER TABLE `learnersubject`
  ADD PRIMARY KEY (`LearnerSubjectId`),
  ADD KEY `LearnerId` (`LearnerId`),
  ADD KEY `SubjectId` (`SubjectId`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`NoticeNo`),
  ADD KEY `LearnerId` (`LearnerId`);

--
-- Indexes for table `parentlearner`
--
ALTER TABLE `parentlearner`
  ADD PRIMARY KEY (`ParentId`,`LearnerId`),
  ADD KEY `LearnerId` (`LearnerId`);

--
-- Indexes for table `parents`
--
ALTER TABLE `parents`
  ADD PRIMARY KEY (`ParentId`),
  ADD UNIQUE KEY `ParentEmail` (`ParentEmail`);

--
-- Indexes for table `registrationquestions`
--
ALTER TABLE `registrationquestions`
  ADD PRIMARY KEY (`QuestionId`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`SubjectId`),
  ADD UNIQUE KEY `SubjectCode` (`SubjectCode`);

--
-- Indexes for table `todolist`
--
ALTER TABLE `todolist`
  ADD PRIMARY KEY (`TodoId`),
  ADD KEY `CreatorId` (`CreatorId`);

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
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `ActivityId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `finances`
--
ALTER TABLE `finances`
  MODIFY `FinanceId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `learner`
--
ALTER TABLE `learner`
  MODIFY `LearnerId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `learneractivitymarks`
--
ALTER TABLE `learneractivitymarks`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=174;

--
-- AUTO_INCREMENT for table `learners`
--
ALTER TABLE `learners`
  MODIFY `LearnerId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `learnersubject`
--
ALTER TABLE `learnersubject`
  MODIFY `LearnerSubjectId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `NoticeNo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `parents`
--
ALTER TABLE `parents`
  MODIFY `ParentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `registrationquestions`
--
ALTER TABLE `registrationquestions`
  MODIFY `QuestionId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `SubjectId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `todolist`
--
ALTER TABLE `todolist`
  MODIFY `TodoId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_ibfk_1` FOREIGN KEY (`SubjectId`) REFERENCES `subjects` (`SubjectId`);

--
-- Constraints for table `finances`
--
ALTER TABLE `finances`
  ADD CONSTRAINT `finances_ibfk_1` FOREIGN KEY (`LearnerId`) REFERENCES `learners` (`LearnerId`);

--
-- Constraints for table `learnerregistrationanswers`
--
ALTER TABLE `learnerregistrationanswers`
  ADD CONSTRAINT `learnerregistrationanswers_ibfk_1` FOREIGN KEY (`LearnerId`) REFERENCES `learners` (`LearnerId`),
  ADD CONSTRAINT `learnerregistrationanswers_ibfk_2` FOREIGN KEY (`QuestionId`) REFERENCES `registrationquestions` (`QuestionId`);

--
-- Constraints for table `learnersubject`
--
ALTER TABLE `learnersubject`
  ADD CONSTRAINT `learnersubject_ibfk_1` FOREIGN KEY (`LearnerId`) REFERENCES `learners` (`LearnerId`),
  ADD CONSTRAINT `learnersubject_ibfk_2` FOREIGN KEY (`SubjectId`) REFERENCES `subjects` (`SubjectId`);

--
-- Constraints for table `notices`
--
ALTER TABLE `notices`
  ADD CONSTRAINT `notices_ibfk_1` FOREIGN KEY (`LearnerId`) REFERENCES `learner` (`LearnerId`);

--
-- Constraints for table `todolist`
--
ALTER TABLE `todolist`
  ADD CONSTRAINT `todolist_ibfk_1` FOREIGN KEY (`CreatorId`) REFERENCES `users` (`Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
