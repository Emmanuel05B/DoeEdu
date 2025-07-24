-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 24, 2025 at 11:12 PM
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
-- Database: `newgenesis`
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
  `ChapterName` varchar(50) NOT NULL,
  `GroupName` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`ActivityId`, `ActivityName`, `SubjectId`, `ActivityDate`, `MaxMarks`, `Creator`, `Grade`, `ChapterName`, `GroupName`) VALUES
(1, 'Activity 1', 1, '2025-07-24 18:37:00', 20.00, 'Director', 10, 'Algebraic Expressions', 'A'),
(2, 'Activity 2', 1, '2025-07-24 18:37:13', 2.00, 'Director', 10, 'Algebraic Expressions', 'A'),
(3, 'Activity 1 ', 1, '2025-07-24 18:37:19', 1.00, 'Director', 10, 'Exponents', 'A'),
(4, 'Activity 1', 4, '2025-07-24 18:38:19', 20.00, 'Director', 10, 'Electromagnetic Radiation', 'A'),
(5, 'Activity 1', 3, '2025-07-24 18:38:25', 14.00, 'Director', 12, 'Trigonometry', 'A'),
(6, 'Activity 1', 5, '2025-07-24 18:38:29', 5.00, 'Director', 11, 'Newtons Laws', 'A'),
(7, 'Activity 1', 2, '2025-07-24 18:38:32', 20.00, 'Director', 11, 'Exponents and Surds', 'A'),
(8, 'Activity 1', 2, '2025-07-24 18:38:35', 15.00, 'Director', 11, 'Exponents and Surds', 'A'),
(9, 'Activity 2', 2, '2025-07-24 18:38:38', 24.00, 'Director', 11, 'Exponents and Surds', 'A'),
(10, 'Quiz 1', 2, '2025-07-24 18:40:00', 20.00, 'Director', 11, 'Analytical Geometry', 'A'),
(11, 'QUiz 1', 2, '2025-07-24 18:40:03', 25.00, 'Director', 11, 'Functions', 'A'),
(12, 'QUiz 2', 2, '2025-07-24 18:40:05', 20.00, 'Director', 11, 'Functions', 'A'),
(13, 'Quiz 3', 2, '2025-07-24 18:39:56', 25.00, 'Director', 11, 'Functions', 'A'),
(14, 'Activity 1', 2, '2025-07-24 18:39:54', 20.00, 'Director', 11, 'Trigonometry', 'A'),
(15, 'Activity 1', 2, '2025-07-24 18:39:51', 30.00, 'Director', 11, 'Number Patterns', 'A'),
(16, 'Activity 2', 2, '2025-07-24 18:39:48', 20.00, 'Director', 11, 'Number Patterns', 'A'),
(17, 'Activity 3', 2, '2025-07-24 18:39:46', 20.00, 'Director', 11, 'Number Patterns', 'A'),
(18, 'Quiz 1', 2, '2025-07-24 18:39:43', 10.00, 'Director', 11, 'Statistics', 'A'),
(19, 'Activity 1', 2, '2025-07-24 18:39:41', 25.00, 'Director', 11, 'Probability', 'A'),
(20, 'activity 1', 1, '2025-07-24 18:39:38', 20.00, 'Director', 10, 'Algebraic Expressions', 'A'),
(21, 'activity 2', 1, '2025-07-24 18:39:34', 25.00, 'Malesela', 10, 'Functions', 'A'),
(22, 'activity 2', 1, '2025-07-24 18:39:32', 25.00, 'Malesela', 10, 'Functions', 'A'),
(23, 'activity 2', 1, '2025-07-24 18:39:25', 25.00, 'Director', 10, 'Statistics', 'A'),
(24, '', 5, '2025-07-24 18:39:22', 0.00, 'Malesela', 11, '2d and 3d wavefronts', 'A'),
(25, 'act 12', 5, '2025-07-24 18:39:19', 0.00, 'Malesela', 11, '2d and 3d wavefronts', 'A'),
(26, 'act 12', 5, '2025-07-24 18:39:15', 20.00, 'Malesela', 11, '2d and 3d wavefronts', 'A'),
(27, 'act 1222', 5, '2025-07-24 18:39:12', 20.00, 'Director', 11, 'Quantitative Aspects Of Chemical Change', 'A'),
(28, 'activity 1', 5, '2025-07-24 18:39:09', 10.00, 'Director', 11, 'Electric Circuits', 'A'),
(29, 'activity 1', 5, '2025-07-24 18:39:06', 12.00, 'Director', 11, 'Geometrical Optics', 'A'),
(30, 'activity 1', 1, '2025-07-24 18:38:56', 25.00, 'Malesela', 10, 'Functions', 'A'),
(31, 'Test', 2, '2025-07-24 18:39:03', 25.00, 'Director', 11, 'Finance Growth and Decay', 'B'),
(32, 'The First Group Test', 2, '2025-07-24 18:57:12', 30.00, 'Director', 11, 'Measurement', 'A'),
(33, 'The Second Group Test', 1, '2025-07-24 19:05:31', 25.00, 'Director', 10, 'Functions', 'A'),
(34, 'The third Group Test', 1, '2025-07-24 19:15:23', 25.00, 'Director', 10, 'Functions', 'B'),
(35, 'The fourth Group Test', 4, '2025-07-24 19:41:18', 25.00, 'Director', 10, 'Chemical Bonding', 'A'),
(36, 'The fith Group Test', 1, '2025-07-24 20:25:35', 25.00, 'Director', 10, 'Probability', 'B');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `ClassID` int(11) NOT NULL,
  `SubjectID` int(11) NOT NULL,
  `Grade` varchar(20) NOT NULL,
  `GroupName` varchar(5) DEFAULT NULL,
  `CurrentLearnerCount` int(11) DEFAULT 0,
  `TutorID` int(11) NOT NULL,
  `Status` enum('Full','Not Full') DEFAULT 'Not Full',
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`ClassID`, `SubjectID`, `Grade`, `GroupName`, `CurrentLearnerCount`, `TutorID`, `Status`, `CreatedAt`) VALUES
(20, 2, '11', 'A', 1, 2, 'Not Full', '2025-07-24 09:21:52'),
(21, 1, '10', 'A', 15, 2, 'Full', '2025-07-24 09:37:39'),
(22, 4, '10', 'A', 6, 2, 'Not Full', '2025-07-24 09:37:40'),
(23, 1, '10', 'B', 1, 2, 'Not Full', '2025-07-24 16:41:09');

-- --------------------------------------------------------

--
-- Table structure for table `directorsubjects`
--

CREATE TABLE `directorsubjects` (
  `Id` int(11) NOT NULL,
  `DirectorId` int(11) NOT NULL,
  `SubjectId` int(11) DEFAULT NULL,
  `SubjectName` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `directorsubjects`
--

INSERT INTO `directorsubjects` (`Id`, `DirectorId`, `SubjectId`, `SubjectName`) VALUES
(1, 1, 1, 'Mathematics_10'),
(2, 1, 2, 'Mathematics_11'),
(3, 1, 3, 'Mathematics_12'),
(4, 1, 4, 'Physical Sciences_10'),
(5, 1, 5, 'Physical Sciences_11'),
(6, 1, 6, 'Physical Sciences_12');

-- --------------------------------------------------------

--
-- Table structure for table `feedbacklog`
--

CREATE TABLE `feedbacklog` (
  `Id` int(11) NOT NULL,
  `ActivityId` int(11) NOT NULL,
  `TutorId` int(11) NOT NULL,
  `SentAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `finances`
--

CREATE TABLE `finances` (
  `FinanceId` int(11) NOT NULL,
  `LearnerId` int(11) DEFAULT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Surname` varchar(100) DEFAULT NULL,
  `Grade` int(3) DEFAULT NULL,
  `TotalFees` decimal(10,2) DEFAULT NULL,
  `TotalPaid` decimal(10,2) DEFAULT NULL,
  `Math` decimal(15,2) DEFAULT NULL,
  `Physics` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `finances`
--

INSERT INTO `finances` (`FinanceId`, `LearnerId`, `Name`, `Surname`, `Grade`, `TotalFees`, `TotalPaid`, `Math`, `Physics`) VALUES
(22, 38, NULL, NULL, 11, 750.00, 0.00, 750.00, 0.00),
(23, 39, NULL, NULL, 10, 1500.00, 0.00, 750.00, 750.00),
(24, 40, NULL, NULL, 10, 2398.00, 0.00, 1199.00, 1199.00),
(25, 41, NULL, NULL, 10, 1949.00, 0.00, 750.00, 1199.00),
(26, 42, NULL, NULL, 10, 1949.00, 0.00, 750.00, 1199.00),
(27, 43, NULL, NULL, 10, 1500.00, 0.00, 750.00, 750.00),
(28, 44, NULL, NULL, 10, 1500.00, 0.00, 750.00, 750.00),
(29, 45, NULL, NULL, 10, 750.00, 0.00, 750.00, 0.00),
(30, 46, NULL, NULL, 10, 750.00, 0.00, 750.00, 0.00),
(31, 47, NULL, NULL, 10, 750.00, 0.00, 750.00, 0.00),
(32, 48, NULL, NULL, 10, 750.00, 0.00, 750.00, 0.00),
(33, 49, NULL, NULL, 10, 750.00, 0.00, 750.00, 0.00),
(34, 50, NULL, NULL, 10, 750.00, 0.00, 750.00, 0.00),
(35, 51, NULL, NULL, 10, 750.00, 0.00, 750.00, 0.00),
(36, 52, NULL, NULL, 10, 750.00, 0.00, 750.00, 0.00),
(37, 53, NULL, NULL, 10, 750.00, 0.00, 750.00, 0.00),
(38, 54, NULL, NULL, 10, 750.00, 0.00, 750.00, 0.00);

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
(26, 38, 32, 1, 24, '2025-07-24 18:57:42', 'present', 'None', 'Yes', 'None'),
(27, 54, 34, 1, 21, '2025-07-24 19:15:33', 'present', 'None', 'Yes', 'None'),
(28, 39, 35, 1, 18, '2025-07-24 19:41:53', 'present', 'None', 'Yes', 'None'),
(29, 40, 35, 1, 17, '2025-07-24 19:41:54', 'present', 'None', 'Yes', 'None'),
(30, 41, 35, 1, 20, '2025-07-24 19:41:54', 'present', 'None', 'Yes', 'None'),
(31, 42, 35, 1, 25, '2025-07-24 19:41:55', 'present', 'None', 'Yes', 'None'),
(32, 43, 35, 1, 24, '2025-07-24 19:41:55', 'present', 'None', 'Yes', 'None'),
(33, 44, 35, 1, 21, '2025-07-24 19:41:55', 'present', 'None', 'Yes', 'None'),
(34, 54, 36, 1, 23, '2025-07-24 20:25:44', 'present', 'None', 'Yes', 'None');

-- --------------------------------------------------------

--
-- Table structure for table `learneranswers`
--

CREATE TABLE `learneranswers` (
  `Id` int(11) NOT NULL,
  `UserId` int(11) NOT NULL,
  `ActivityId` int(11) NOT NULL,
  `QuestionId` int(11) NOT NULL,
  `SelectedAnswer` char(1) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `learneranswers`
--

INSERT INTO `learneranswers` (`Id`, `UserId`, `ActivityId`, `QuestionId`, `SelectedAnswer`, `CreatedAt`) VALUES
(20, 42, 14, 24, 'B', '2025-07-24 21:00:35'),
(21, 42, 14, 25, 'A', '2025-07-24 21:00:36'),
(22, 42, 15, 26, 'A', '2025-07-24 21:02:36');

-- --------------------------------------------------------

--
-- Table structure for table `learnerclasses`
--

CREATE TABLE `learnerclasses` (
  `Id` int(11) NOT NULL,
  `LearnerID` int(11) NOT NULL,
  `ClassID` int(11) NOT NULL,
  `AssignedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `learnerclasses`
--

INSERT INTO `learnerclasses` (`Id`, `LearnerID`, `ClassID`, `AssignedAt`) VALUES
(19, 38, 20, '2025-07-24 09:21:52'),
(20, 39, 21, '2025-07-24 09:37:39'),
(21, 39, 22, '2025-07-24 09:37:40'),
(22, 40, 21, '2025-07-24 16:25:16'),
(23, 40, 22, '2025-07-24 16:25:16'),
(24, 41, 21, '2025-07-24 16:28:41'),
(25, 41, 22, '2025-07-24 16:28:41'),
(26, 42, 21, '2025-07-24 16:29:36'),
(27, 42, 22, '2025-07-24 16:29:36'),
(28, 43, 21, '2025-07-24 16:30:38'),
(29, 43, 22, '2025-07-24 16:30:38'),
(30, 44, 21, '2025-07-24 16:31:39'),
(31, 44, 22, '2025-07-24 16:31:39'),
(32, 45, 21, '2025-07-24 16:32:26'),
(33, 46, 21, '2025-07-24 16:33:18'),
(34, 47, 21, '2025-07-24 16:34:20'),
(35, 48, 21, '2025-07-24 16:35:02'),
(36, 49, 21, '2025-07-24 16:35:43'),
(37, 50, 21, '2025-07-24 16:36:35'),
(38, 51, 21, '2025-07-24 16:37:37'),
(39, 52, 21, '2025-07-24 16:38:55'),
(40, 53, 21, '2025-07-24 16:39:24'),
(41, 54, 23, '2025-07-24 16:41:09');

-- --------------------------------------------------------

--
-- Table structure for table `learnerhomeworkresults`
--

CREATE TABLE `learnerhomeworkresults` (
  `Id` int(11) NOT NULL,
  `UserId` int(11) NOT NULL,
  `ActivityId` int(11) NOT NULL,
  `Score` decimal(5,2) NOT NULL,
  `SubmittedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `learnerhomeworkresults`
--

INSERT INTO `learnerhomeworkresults` (`Id`, `UserId`, `ActivityId`, `Score`, `SubmittedAt`) VALUES
(11, 42, 14, 50.00, '2025-07-24 21:00:36'),
(12, 42, 15, 100.00, '2025-07-24 21:02:36');

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
  `Grade` varchar(20) DEFAULT NULL,
  `RegistrationDate` date DEFAULT NULL,
  `LearnerKnockoffTime` time DEFAULT NULL,
  `Math` decimal(10,2) DEFAULT 0.00,
  `Physics` decimal(10,2) DEFAULT 0.00,
  `TotalFees` decimal(10,2) DEFAULT 0.00,
  `TotalPaid` decimal(10,2) DEFAULT 0.00,
  `TotalOwe` decimal(10,2) GENERATED ALWAYS AS (`TotalFees` - `TotalPaid`) STORED,
  `ParentTitle` varchar(10) DEFAULT NULL,
  `ParentName` varchar(100) DEFAULT NULL,
  `ParentSurname` varchar(100) DEFAULT NULL,
  `ParentEmail` varchar(100) DEFAULT NULL,
  `ParentContactNumber` varchar(20) DEFAULT NULL,
  `LastUpdated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `learners`
--

INSERT INTO `learners` (`LearnerId`, `Grade`, `RegistrationDate`, `LearnerKnockoffTime`, `Math`, `Physics`, `TotalFees`, `TotalPaid`, `ParentTitle`, `ParentName`, `ParentSurname`, `ParentEmail`, `ParentContactNumber`, `LastUpdated`) VALUES
(38, '11', '2025-07-24', '00:00:17', 750.00, 0.00, 750.00, 0.00, 'Mrs', 'MotherSolo', 'Solo', 'msolo@gmail.com', '5552525458', NULL),
(39, '10', '2025-07-24', '00:00:18', 750.00, 750.00, 1250.00, 0.00, 'Dr', 'MotherSisdod', 'Solo', 'msisdod@gmail.com', '5552525452', NULL),
(40, '10', '2025-07-24', '00:00:12', 1199.00, 1199.00, 1950.00, 0.00, 'Mr', 'MotherRAshford', 'Rashford', 'rashfordd@gmail.com', '5552525458', NULL),
(41, '10', '2025-07-24', '00:00:12', 750.00, 1199.00, 1949.00, 0.00, 'Ms', 'MotherMessi', 'Messi', 'mlio@gmail.com', '5552525458', NULL),
(42, '10', '2025-07-24', '00:00:12', 750.00, 1199.00, 1949.00, 0.00, 'Ms', 'MotherIniesta', 'Messi', 'mIniesta@gmail.com', '5552525458', NULL),
(43, '10', '2025-07-24', '00:00:12', 750.00, 750.00, 1250.00, 0.00, 'Ms', 'MotherHernandes', 'Hernandes', 'mHernandes@gmail.com', '5552525458', NULL),
(44, '10', '2025-07-24', '00:00:12', 750.00, 750.00, 1250.00, 0.00, 'Ms', 'MotherIbrah', 'Ibrah', 'mIbrah@gmail.com', '5552525458', NULL),
(45, '10', '2025-07-24', '00:00:12', 750.00, 0.00, 750.00, 0.00, 'Ms', 'MotherToure', 'Toure', 'mToure@gmail.com', '5552525458', NULL),
(46, '10', '2025-07-24', '00:00:12', 750.00, 0.00, 750.00, 0.00, 'Ms', 'MotherSuarez', 'Suarez', 'mSuarez@gmail.com', '5552525458', NULL),
(47, '10', '2025-07-24', '00:00:12', 750.00, 0.00, 750.00, 0.00, 'Ms', 'MotherBusi', 'Busq', 'mBusi@gmail.com', '5552525458', NULL),
(48, '10', '2025-07-24', '00:00:12', 750.00, 0.00, 750.00, 0.00, 'Ms', 'MotherPuyol', 'Puyol', 'mPuyoli@gmail.com', '5552525458', NULL),
(49, '10', '2025-07-24', '00:00:12', 750.00, 0.00, 750.00, 0.00, 'Ms', 'MotherAlves', 'Alves', 'mAlvesl@gmail.com', '5552525458', NULL),
(50, '10', '2025-07-24', '00:00:12', 750.00, 0.00, 750.00, 0.00, 'Ms', 'MotherVilla', 'Villa', 'mVilla@gmail.com', '5552525458', NULL),
(51, '10', '2025-07-24', '00:00:12', 750.00, 0.00, 750.00, 0.00, 'Ms', 'MotherPique', 'SomethingPique', 'mpiq@gmail.com', '5552525458', NULL),
(52, '10', '2025-07-24', '00:00:12', 750.00, 0.00, 750.00, 0.00, 'Ms', 'MotherGaucho', 'Gaucho', 'mGaucho@gmail.com', '5552525458', NULL),
(53, '10', '2025-07-24', '00:00:12', 750.00, 0.00, 750.00, 0.00, 'Ms', 'MotherEtoo', 'Etoo', 'mEtoo@gmail.com', '5552525458', NULL),
(54, '10', '2025-07-24', '00:00:12', 750.00, 0.00, 750.00, 0.00, 'Ms', 'MotherRamos', 'Ramos', 'mRamos@gmail.com', '5552525458', NULL);

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
(31, 38, 2, 6, 2, 2, '2026-01-24 11:21:52', 'Active'),
(32, 39, 1, 6, 2, 2, '2026-01-24 11:37:39', 'Active'),
(33, 39, 4, 7, 2, 2, '2026-01-24 11:37:39', 'Active'),
(34, 40, 1, 6, 1, 3, '2026-07-24 18:25:16', 'Active'),
(35, 40, 4, 5, 1, 3, '2026-07-24 18:25:16', 'Active'),
(36, 41, 1, 5, 1, 2, '2026-01-24 18:28:41', 'Active'),
(37, 41, 4, 5, 2, 3, '2026-07-24 18:28:41', 'Active'),
(38, 42, 1, 5, 1, 2, '2026-01-24 18:29:36', 'Active'),
(39, 42, 4, 5, 2, 3, '2026-07-24 18:29:36', 'Active'),
(40, 43, 1, 5, 1, 2, '2026-01-24 18:30:38', 'Active'),
(41, 43, 4, 5, 2, 2, '2026-01-24 18:30:38', 'Active'),
(42, 44, 1, 7, 1, 2, '2026-01-24 18:31:39', 'Active'),
(43, 44, 4, 7, 4, 2, '2026-01-24 18:31:39', 'Active'),
(44, 45, 1, 7, 1, 2, '2026-01-24 18:32:25', 'Active'),
(45, 46, 1, 7, 1, 2, '2026-01-24 18:33:18', 'Active'),
(46, 47, 1, 7, 1, 2, '2026-01-24 18:34:20', 'Active'),
(47, 48, 1, 7, 1, 2, '2026-01-24 18:35:02', 'Active'),
(48, 49, 1, 7, 1, 2, '2026-01-24 18:35:42', 'Active'),
(49, 50, 1, 7, 1, 2, '2026-01-24 18:36:35', 'Active'),
(50, 51, 1, 7, 1, 2, '2026-01-24 18:37:37', 'Active'),
(51, 52, 1, 7, 1, 2, '2026-01-24 18:38:55', 'Active'),
(52, 53, 1, 7, 1, 2, '2026-01-24 18:39:24', 'Active'),
(53, 54, 1, 7, 1, 2, '2026-01-24 18:41:09', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `NoticeNo` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Content` text NOT NULL,
  `Date` datetime NOT NULL DEFAULT current_timestamp(),
  `IsOpened` tinyint(1) NOT NULL DEFAULT 0,
  `CreatedBy` int(11) NOT NULL,
  `CreatedFor` int(11) NOT NULL COMMENT '1 = Learners, 2 = Tutors, 12 = Both'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`NoticeNo`, `Title`, `Content`, `Date`, `IsOpened`, `CreatedBy`, `CreatedFor`) VALUES
(1, 'Welcome Back to Term 3', 'Dear learners and tutors, Term 3 has officially started. Please check your schedules and submit all pending assignments on time.', '2025-07-09 20:38:47', 1, 1, 12),
(2, 'System Maintenance Notification', 'The system will be down for maintenance on Saturday from 10 PM to 2 AM. Please save your work accordingly.', '2025-07-09 20:38:47', 1, 2, 12),
(4, 'Title', 'This is the first nitice from the form', '2025-07-09 20:56:09', 1, 1, 12),
(5, 'Mid-Year Exams Preparation', 'Dear Learners, please begin preparing for your mid-year exams scheduled for next month. Study guides have been uploaded.', '2025-07-09 21:08:43', 0, 1, 1),
(6, 'New Resources Available', 'New Maths and Science videos are now available in your Resources tab.', '2025-07-09 21:08:44', 1, 1, 1),
(7, 'Friday Q&A Session', 'Join our live Q&A session this Friday at 4PM for help with your homework and recent topics.', '2025-07-09 21:08:44', 1, 1, 1),
(8, 'Mark Submission Reminder', 'Tutors, please submit all learner marks for the week by Friday 17:00.', '2025-07-09 21:08:44', 0, 1, 2),
(9, 'Mandatory Tutor Meeting', 'All tutors are required to attend an online meeting this Thursday at 18:00 to discuss Term 3 planning.', '2025-07-09 21:08:44', 0, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `onlineactivities`
--

CREATE TABLE `onlineactivities` (
  `Id` int(11) NOT NULL,
  `TutorId` int(11) NOT NULL,
  `SubjectName` varchar(100) NOT NULL,
  `Grade` varchar(20) NOT NULL,
  `Topic` varchar(100) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Instructions` text DEFAULT NULL,
  `TotalMarks` int(11) NOT NULL,
  `DueDate` date DEFAULT NULL,
  `CreatedAt` datetime DEFAULT current_timestamp(),
  `ImagePath` varchar(255) DEFAULT NULL,
  `LastFeedbackSent` datetime DEFAULT NULL,
  `GroupName` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `onlineactivities`
--

INSERT INTO `onlineactivities` (`Id`, `TutorId`, `SubjectName`, `Grade`, `Topic`, `Title`, `Instructions`, `TotalMarks`, `DueDate`, `CreatedAt`, `ImagePath`, `LastFeedbackSent`, `GroupName`) VALUES
(1, 1, '1', '10', 'Algebraic Expressions', 'Quiz 2', 'Default instructions here', 2, '2025-07-04', '2025-06-30 18:37:04', '../uploads/1751301424_7.png', NULL, 'A'),
(2, 1, '1', '10', 'Algebraic Expressions', 'Test 2', 'Default instructions here', 1, '2025-07-11', '2025-06-30 18:40:10', '../uploads/1751301610_4.png', NULL, 'A'),
(3, 1, '1', '10', 'Functions', 'Test 1', 'Default instructions here', 1, '2025-07-04', '2025-06-30 18:56:36', NULL, NULL, 'A'),
(4, 2, '1', '10', 'Trigonometry', 'Quiz', 'Default instructions here', 1, '2025-07-16', '2025-07-01 18:20:39', NULL, '2025-07-16 22:18:55', 'A'),
(5, 2, '1', '10', 'Statistics', 'Activity', 'Default instructions here', 5, '2025-08-09', '2025-07-01 19:06:29', '../uploads/1751389589_7.png', NULL, 'A'),
(6, 1, '1', '10', 'Finance and Growth', 'Finances', 'Default instructions here', 2, '2025-07-24', '2025-07-01 19:19:31', NULL, NULL, 'A'),
(7, 1, '4', '10', 'Magnetism', 'Quiz 1', 'Default instructions here', 5, '2025-08-09', '2025-07-15 11:34:10', NULL, NULL, 'A'),
(8, 2, '1', '10', 'Probability', 'Quiz 1111', 'Default instructions here', 1, '2025-07-14', '2025-07-15 18:13:27', NULL, NULL, 'A'),
(9, 2, '1', '10', 'Probability', 'Quiz 22222', 'Default instructions here', 1, '2025-07-14', '2025-07-15 18:15:23', NULL, NULL, 'A'),
(10, 2, '5', '11', 'Newtons Laws', 'Quiz 22222', 'Default instructions here', 1, '2025-07-25', '2025-07-15 18:19:07', NULL, NULL, 'A'),
(11, 2, '5', '11', 'Quantitative Aspects Of Chemical Change', 'Quiz 9999', 'Default instructions here', 1, '2025-08-01', '2025-07-16 21:31:48', NULL, NULL, 'A'),
(12, 2, '5', '11', 'Electric Circuits', 'Quiz 9999', 'Default instructions here', 1, '2025-07-15', '2025-07-16 21:33:01', NULL, NULL, 'A'),
(13, 2, '1', '10', 'Trigonometry', 'Test 1', 'Default instructions here', 1, '2025-04-21', '2025-07-19 15:27:26', '../uploads/1752931646_3.png', NULL, 'A'),
(14, 1, '1', '10', 'Statistics', 'The first quiz with Groups', 'Default instructions here', 2, '2025-05-24', '2025-07-24 22:13:26', '../uploads/1753388006_Picture5.jpg', NULL, 'B'),
(15, 1, '1', '10', 'Statistics', 'The second quiz with Groups', 'Default instructions here', 1, '2025-05-24', '2025-07-24 22:16:54', '../uploads/1753388214_Picture5.jpg', NULL, 'A');

-- --------------------------------------------------------

--
-- Table structure for table `onlinequestions`
--

CREATE TABLE `onlinequestions` (
  `Id` int(11) NOT NULL,
  `ActivityId` int(11) NOT NULL,
  `QuestionText` text NOT NULL,
  `OptionA` text NOT NULL,
  `OptionB` text NOT NULL,
  `OptionC` text NOT NULL,
  `OptionD` text NOT NULL,
  `CorrectAnswer` enum('A','B','C','D') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `onlinequestions`
--

INSERT INTO `onlinequestions` (`Id`, `ActivityId`, `QuestionText`, `OptionA`, `OptionB`, `OptionC`, `OptionD`, `CorrectAnswer`) VALUES
(1, 1, 'This is also an edited question.', '5', '12', 'Ampere', '7', 'B'),
(2, 1, 'Lets edit this one as well.', 'aaaa', 'bbb', 'ccc', 'ddd', 'A'),
(3, 2, 'Question edited to make it make sense', '5', '12', 'Ampere', '7', 'A'),
(4, 3, 'frte tt ht hy th gtr', '5', '12', 'Ampere', '7', 'A'),
(5, 4, 'This is an edited question 22', '5', '12', 'Ampere', '7', 'A'),
(6, 5, 'What is the mean of the following data set?\nData: 6, 8, 10, 4, 12', '7', '8', '9', '10', 'B'),
(7, 5, 'Which value represents the median of this data set?\nData: 15, 10, 20, 25, 5', '10', '15', '20', '25', 'B'),
(8, 5, 'What is the mode of the following set of numbers?\r\nData: 3, 7, 3, 5, 8, 3, 2', '3', '5', '7', '2', 'A'),
(9, 5, 'What does the range of a data set measure?\r\n', 'The most frequent value', 'The difference between highest and lowest values', 'The average of all values', 'The middle value', 'B'),
(10, 5, 'The marks of 6 students in a test are: 70, 65, 80, 75, 90, 70. What is the mode?\r\n', '70', '80', '75', '90', 'A'),
(11, 6, 'Simple Interest\r\nThabo invests R5,000 in a savings account that pays 6% simple interest per annum. How much interest will he earn after 3 years?', 'R900', 'R800', 'R1,200', 'R750', 'A'),
(12, 6, 'Budgeting\r\nLebo earns R3,500 per month. She spends 30% on groceries, 25% on transport, and 20% on school fees. How much money does she have left after these expenses?', 'R875', 'R1,000', 'R1,225', 'R1,200', 'C'),
(13, 7, 'Which of the following materials is magnetic? fkrehjlt;k;rl4mwvrhtjlhe5', 'Copper', 'Aluminum', 'Iron', 'Plastic', 'C'),
(14, 7, 'What happens when like poles of two magnets are brought close together?', 'They attract each other', 'They repel each other', 'They become neutral', 'They lose magnetism', 'B'),
(15, 7, 'The region around a magnet where magnetic forces can be detected is called:', ' Electric field', 'Magnetic domain', 'Magnetic field', 'Current field', 'C'),
(16, 7, 'Which instrument is commonly used to show the direction of a magnetic field?', 'Voltmeter', 'Ammeter', 'Compass', 'Thermometer', 'C'),
(17, 7, 'When a magnet is broken into two pieces, what happens?\r\n', 'Only one piece remains magnetic', 'The magnet loses its properties', 'Each piece becomes a smaller magnet with two poles', 'The pieces attract each other permanently', 'C'),
(18, 8, 'ertyr fh rtht jtyj tyj tyjtyj', 'Copper', 'Aluminum', 'Iron', 'Plastic', 'A'),
(19, 9, 'ertyr fh rtht jtyj tyj tyjtyj', 'Copper', 'Aluminum', 'Iron', 'Plastic', 'A'),
(20, 10, 'aesrtrhy ghy gt hty yt h ty', 'Copper', 'Aluminum', 'Iron', 'Plastic', 'A'),
(21, 11, 'ase sdfr frg regege regre g regre', 'Copper', 'Aluminum', 'Iron', 'Plastic', 'A'),
(22, 12, 'sdfg grg rg reg er hrege', 'Copper', 'Aluminum', 'Iron', 'Plastic', 'B'),
(23, 13, 'hdghjfydb dggf d dr  ', '5', 'dsf', 'sdfg', 'dfsd', 'A'),
(24, 14, 'This is the first question of this first online quiz of the group.', '5', '44', 's5', 'sd', 'A'),
(25, 14, 'This is the second question of this first online quiz of the group.', 'R875', 'R1,000', 'R1,225', 'R1,200', 'A'),
(26, 15, 'This is the first question of this second online quiz of the group.', '5', '44', 's5', 'sd', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `RatingId` int(11) NOT NULL,
  `TutorId` int(11) NOT NULL,
  `LearnerId` int(11) NOT NULL,
  `Rating` int(11) DEFAULT NULL CHECK (`Rating` between 1 and 10),
  `RatingDate` datetime DEFAULT current_timestamp(),
  `Comments` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `Id` int(11) NOT NULL,
  `UploadedBy` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Subject` varchar(100) NOT NULL,
  `Grade` varchar(20) NOT NULL,
  `Type` varchar(50) NOT NULL,
  `FilePath` varchar(255) NOT NULL,
  `UploadedAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjectnotices`
--

CREATE TABLE `subjectnotices` (
  `NoticeId` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Content` text NOT NULL,
  `SubjectName` varchar(100) NOT NULL,
  `Grade` varchar(10) NOT NULL,
  `CreatedBy` int(11) NOT NULL,
  `CreatedAt` datetime DEFAULT current_timestamp(),
  `IsOpened` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjectnotices`
--

INSERT INTO `subjectnotices` (`NoticeId`, `Title`, `Content`, `SubjectName`, `Grade`, `CreatedBy`, `CreatedAt`, `IsOpened`) VALUES
(1, 'Title 222', 'This only applies to maths 10 learners', 'Mathematics', '10', 2, '2025-07-09 21:38:20', 0),
(2, 'Title 101010', 'This is the tenth message for the grade 10 Maths learners', 'Mathematics', '10', 2, '2025-07-09 21:52:35', 0),
(7, 'Reminder: Assignment Deadline Approaching', 'Please remember to submit your Algebra assignments by Friday. Late submissions will not be accepted. Reach out if you need any help.', 'Mathematics', '10', 2, '2025-07-09 22:14:08', 0),
(8, 'Extra Tutoring Sessions Available', 'Starting next week, extra tutoring sessions will be held every Wednesday after school in room 12. All Grade 10 Mathematics learners are encouraged to attend.', 'Mathematics', '10', 2, '2025-07-09 22:14:36', 0),
(11, 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'Today today today', 'Mathematics_10', '10', 2, '2025-07-20 20:01:46', 0);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `SubjectId` int(11) NOT NULL,
  `SubjectName` varchar(255) NOT NULL,
  `MaxClassSize` int(11) NOT NULL DEFAULT 15,
  `Grade` varchar(10) NOT NULL,
  `SubjectCode` varchar(50) DEFAULT NULL,
  `ThreeMonthsPrice` decimal(10,2) DEFAULT 0.00,
  `SixMonthsPrice` decimal(10,2) DEFAULT 0.00,
  `TwelveMonthsPrice` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`SubjectId`, `SubjectName`, `MaxClassSize`, `Grade`, `SubjectCode`, `ThreeMonthsPrice`, `SixMonthsPrice`, `TwelveMonthsPrice`) VALUES
(1, 'Mathematics_10', 15, '10', 'MATH10', 450.00, 800.00, 1500.00),
(2, 'Mathematics_11', 15, '11', 'MATH11', 450.00, 800.00, 1500.00),
(3, 'Mathematics_12', 15, '12', 'MATH12', 450.00, 900.00, 1600.00),
(4, 'Physical Sciences_10', 15, '10', 'PHY10', 450.00, 91200.00, 1600.00),
(5, 'Physical Sciences_11', 15, '11', 'PHY11', 450.00, 0.00, 0.00),
(6, 'Physical Sciences_12', 15, '12', 'PHY12', 450.00, 0.00, 0.00);

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
(2, 1, 'Fix the reset password page', '2025-07-05 19:33:19', '2025-06-04 17:20:00', 'Low', 0, NULL, NULL, 'General'),
(4, 1, 'Fix the sweet alert for registering a learner', '2025-07-05 19:34:36', '2025-05-20 02:10:00', 'Low', 0, NULL, NULL, 'General'),
(7, 1, 'Work on resources pages for both the learner and the Tutors/Director', '2025-07-15 14:09:19', '2025-05-21 14:11:00', 'Low', 0, NULL, NULL, 'General'),
(8, 1, 'make overview.php dynamic', '2025-07-15 16:49:23', '2025-08-01 21:08:00', 'Low', 0, NULL, NULL, 'General'),
(9, 1, 'Work on the Main Sidebar color', '2025-07-16 10:11:52', '2025-08-09 22:11:00', 'Low', 0, NULL, NULL, 'General'),
(10, 1, 'Work on the feedback for the parents', '2025-07-16 18:14:44', '2025-05-20 17:06:00', 'Low', 0, NULL, NULL, 'General'),
(11, 1, 'work on the tutor perfomance button', '2025-07-19 19:47:46', '0000-00-00 00:00:00', 'Low', 0, NULL, NULL, 'General'),
(12, 1, 'Update the Direcor\'s activity overview page with that of a Tutor', '2025-07-19 19:49:45', '2025-08-02 12:59:00', 'Low', 0, NULL, NULL, 'General'),
(13, 1, 'Work on the Announcement modal, No mark as read', '2025-07-19 20:31:12', '2025-07-31 11:59:00', 'High', 0, NULL, NULL, 'General'),
(14, 1, 'Work on learner Profile Settings', '2025-07-19 20:49:41', '2025-08-02 11:59:00', 'Low', 0, NULL, NULL, 'General'),
(15, 1, 'Work on the learner Help & Support', '2025-07-19 20:50:42', '2025-11-29 12:58:00', 'Low', 0, NULL, NULL, 'General'),
(16, 1, 'Work on the learner \'Student Voices\' page', '2025-07-19 20:51:23', '2025-08-01 11:59:00', 'Low', 0, NULL, NULL, 'General'),
(17, 1, 'Work on the learner home page(Dynamic)', '2025-07-19 20:52:24', '2025-08-09 08:52:00', 'Low', 0, NULL, NULL, 'General');

-- --------------------------------------------------------

--
-- Table structure for table `tutoravailability`
--

CREATE TABLE `tutoravailability` (
  `Id` int(11) NOT NULL,
  `TutorId` int(11) NOT NULL,
  `DayOfWeek` varchar(10) NOT NULL,
  `StartTime` time NOT NULL,
  `EndTime` time NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutoravailability`
--

INSERT INTO `tutoravailability` (`Id`, `TutorId`, `DayOfWeek`, `StartTime`, `EndTime`, `CreatedAt`) VALUES
(8, 2, 'Sunday', '20:00:00', '22:00:00', '2025-07-08 17:07:54');

-- --------------------------------------------------------

--
-- Table structure for table `tutordateexceptions`
--

CREATE TABLE `tutordateexceptions` (
  `Id` int(11) NOT NULL,
  `TutorId` int(11) NOT NULL,
  `ExceptionDate` date NOT NULL,
  `IsAvailable` tinyint(1) NOT NULL DEFAULT 0,
  `CustomStart` time DEFAULT NULL,
  `CustomEnd` time DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tutors`
--

CREATE TABLE `tutors` (
  `TutorId` int(11) NOT NULL,
  `Bio` text DEFAULT NULL,
  `Qualifications` text DEFAULT NULL,
  `ExperienceYears` int(11) DEFAULT NULL,
  `ProfilePicture` varchar(255) DEFAULT NULL,
  `Availability` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutors`
--

INSERT INTO `tutors` (`TutorId`, `Bio`, `Qualifications`, `ExperienceYears`, `ProfilePicture`, `Availability`) VALUES
(2, 'I am a passionate and dedicated tutor with a strong commitment to helping learners reach their full academic potential. I create a supportive and engaging environment tailored to individual learning styles. My goal is to make complex concepts simple and enjoyable to understand.', 'Bachelor of Science in Mathematics and Education\r\nTEFL Certification (Teaching English as a Foreign Language)', 3, '../uploads/1752932354_Shirley.jpg', 'evenings'),
(19, 'HI, Im Emmanuel', 'Bsc Com Sciences', 6, '../uploads/1752933967_1749938603_Picture5.jpg', 'weekends'),
(20, '', '', 0, '0', ''),
(21, '', '', 0, '0', ''),
(24, '', '', 0, '', ''),
(25, '', '', 0, '', ''),
(55, '', '', 0, '', ''),
(56, '', '', 0, '', ''),
(57, '', '', 0, '', ''),
(58, '', '', 0, '', ''),
(59, '', '', 0, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tutorsessions`
--

CREATE TABLE `tutorsessions` (
  `SessionId` int(11) NOT NULL,
  `TutorId` int(11) NOT NULL,
  `LearnerId` int(11) NOT NULL,
  `SlotDateTime` datetime NOT NULL,
  `Subject` varchar(100) NOT NULL,
  `Notes` text DEFAULT NULL,
  `Status` enum('Pending','Confirmed','Cancelled') DEFAULT 'Pending',
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tutorsubject`
--

CREATE TABLE `tutorsubject` (
  `TutorId` int(11) NOT NULL,
  `SubjectId` int(11) NOT NULL,
  `Active` tinyint(1) DEFAULT 1,
  `AvgRating` decimal(3,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutorsubject`
--

INSERT INTO `tutorsubject` (`TutorId`, `SubjectId`, `Active`, `AvgRating`) VALUES
(2, 1, 1, 0.00),
(2, 3, 1, 0.00),
(2, 5, 1, 0.00),
(19, 1, 1, 0.00),
(19, 2, 1, 0.00),
(19, 5, 1, 0.00),
(20, 1, 1, 0.00),
(20, 4, 1, 0.00),
(21, 1, 1, 0.00),
(21, 2, 1, 0.00),
(21, 3, 1, 0.00),
(21, 6, 1, 0.00),
(24, 2, 1, 0.00),
(24, 4, 1, 0.00),
(24, 6, 1, 0.00),
(25, 1, 1, 0.00),
(25, 2, 1, 0.00),
(25, 3, 1, 0.00),
(25, 4, 1, 0.00),
(25, 5, 1, 0.00),
(25, 6, 1, 0.00),
(55, 1, 1, 0.00),
(55, 2, 1, 0.00),
(55, 3, 1, 0.00),
(56, 4, 1, 0.00),
(56, 5, 1, 0.00),
(56, 6, 1, 0.00),
(57, 4, 1, 0.00),
(57, 5, 1, 0.00),
(58, 1, 1, 0.00),
(58, 5, 1, 0.00),
(59, 1, 1, 0.00),
(59, 5, 1, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Id` int(11) NOT NULL,
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
  `RegistrationDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `UserType` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Id`, `Surname`, `Name`, `UserPassword`, `Gender`, `Contact`, `AlternativeContact`, `Email`, `IsVerified`, `ResetCode`, `ResetTimestamp`, `VerificationToken`, `RegistrationDate`, `UserType`) VALUES
(1, 'Director', 'DOE', '$2y$10$2wdu5zdWMu.wG.wa.JxuMed9QzPuYlif1w7wIxlk7RSdZlAZFpGgG', 'Male', 1234567890, NULL, 'distributorsdoe@gmail.com', 1, '$2y$10$ayEYoSGc9mNzw6awXvmWgOY0Ye6LOh..osu6A3bO0HMHhftKecoWG', '2025-06-30 11:18:57', 'f3659d1c92546274b30c2ade4b5e3012da1cfabc13f04bb3b9cf9a76007090eb', '2025-06-30 11:18:57', 0),
(2, 'Malesela', 'Shirley', '$2y$10$t2VD2G8anvVZ/8IQ5f43RO9fq33OI38c8WU3gfNnn/EEHCy9rRL7G', 'Ms', 1234567891, NULL, 'shirley@gmail.com', 1, '', '2025-06-30 11:32:56', 'f9c5c52aee8b1fd0a72553256e2a425ef8aece59d855a99b710cc1fe0dbf0f52', '2025-06-30 11:32:56', 1),
(19, 'Boshielo', 'Emmanuel', '$2y$10$GbYLySVSA8qlvSfKzAWi1uFblIzipSMocSvyMKBf3.diQbqH.Re8a', 'Mr', 562065285, NULL, 'emahlwele055@gmail.com', 0, '', '2025-07-15 08:29:51', '9942c2923fa78adba6dc3f77cb829159', '2025-07-15 08:29:51', 1),
(20, 'Sandjon', 'Nicole', '$2y$10$LFVceoKvwMuku/4ldEDt3.E8A1yjkYGVU2qXU/QZCLVUU4kf8VV8m', 'Ms', 825652352, NULL, 'nsandj@gmail.com', 0, '', '2025-07-15 08:34:57', '3d8397d11dcf5fefbef1f2f587121e90', '2025-07-15 08:34:57', 1),
(21, 'Mamogobo', 'Sydney', '$2y$10$iQBqDgcGuAqFWFWUQDJD6OczA1.eFSjB1/OPKuLWwTFY5nlq2NNYe', 'Mr', 728547485, NULL, 'mamogobo@gmail.com', 0, '', '2025-07-15 09:24:07', '0e6d551816c26c72bad0ab26cc87edbf', '2025-07-15 09:24:07', 1),
(24, 'Mbuyane', 'Sanele', '$2y$10$Hx025ygsL1ffOQXVaL0Sb.teIGUrL2JkT3WWtM2Xbs8fBJBMPf94q', 'Ms', 854285425, NULL, 'mbuyane@gmail.com', 0, '', '2025-07-19 12:31:58', 'c859b2dd65d4551d371314cc2c09670b', '2025-07-19 12:31:58', 1),
(25, 'Temp', 'Temp', '$2y$10$Gbi4R5wR6v85AdDAfXSwtuu8r8eXMTTDtKiYbJLU1/F0N38zDMgjO', 'Dr', 2147483647, NULL, 'doe@gmail.com', 0, '', '2025-07-19 14:51:08', '36a628e323d45abefaa4c10d8c0f3f59', '2025-07-19 14:51:08', 1),
(38, 'Solo', 'Duo', '$2y$10$LrQXVn75HkfXw6dVbQPOuOpRvar3eiQYlPADNFLnztnA5sZl9H4SC', 'Mr', 2147483647, NULL, 'solo@gmail.com', 0, '', '2025-07-24 09:21:51', '5ec27c5de6af4e97d96190f73b8365e2111ce347fef60eed3c6d544ce64b0fb0', '2025-07-24 09:21:51', 2),
(39, 'Sisdod', 'MotherSisdoh', '$2y$10$EPebr0WpwVhyJ6mss1t13.p3bfvYRyZ6x3TGk3g7zWTDT5mLfVr.u', 'Mr', 2147483647, NULL, 'msisdoh@gmail.com', 0, '', '2025-07-24 09:37:38', '82e232876381ba7fa7c1078680682f58bfbeddfee19bf31b1dbe11fa1b2f154d', '2025-07-24 09:37:38', 2),
(40, 'Rashford', 'Marcus', '$2y$10$nVPqVo9SjMSnFRNP0nuX0OUm8vLZ8tiNs4QekwUgR4CDBH66bwh82', 'Mr', 2147483647, NULL, 'rashfordd@gmail.com', 0, '', '2025-07-24 16:25:15', '2a619503334648455c93911d038192ddadde4d50c6cf18efd780c9ea6ca362e6', '2025-07-24 16:25:15', 2),
(41, 'Messi', 'Lionel', '$2y$10$w2nvS/m1i08UyzUrXmocceB8EFsBFZMMfs206nY/skDyhwWzYQSKy', 'Mr', 2147483647, NULL, 'messi@gmail.com', 0, '', '2025-07-24 16:28:41', '720a424e51ea92417b36ccf147a7c85ee226b4d1b059f6e5ce6db3e079de9982', '2025-07-24 16:28:41', 2),
(42, 'Iniesta', 'Andres', '$2y$10$1MYyb9oFQ7tO4dwxCqYGnuLe7w5p.kLrlr7SAyFD3KclVJ7u1RefC', 'Mr', 2147483647, NULL, 'Iniesta@gmail.com', 0, '', '2025-07-24 16:29:36', '928687b78113af0e026cd28ed27c96ca310951048751821ed8b51a21c0c0ad0e', '2025-07-24 16:29:36', 2),
(43, 'Hernandes', 'Xavi', '$2y$10$iY.9fqN95mBmUk0QITJvr.fko/LUswElagP3grD8L28muRUwzZqAi', 'Mr', 2147483647, NULL, 'Hernandes@gmail.com', 0, '', '2025-07-24 16:30:38', '43f3584ddb0b19b4a339c407b6efaacf14e38ce31dc5bece8f0edc2d0cc00419', '2025-07-24 16:30:38', 2),
(44, 'Ibrah', 'Zlatan', '$2y$10$UrbnJX6f1niQoTOdKB5yteEC/ckx.HU56skjtr1UtkMY8S8JZaMiu', 'Mr', 2147483647, NULL, 'Ibrah@gmail.com', 0, '', '2025-07-24 16:31:39', '4d4a5b644f3479ba1f1c95971eac4456d9eb989a176d1573544144f93fa22efa', '2025-07-24 16:31:39', 2),
(45, 'Toure', 'Yaya', '$2y$10$CdLJPDyeTXhrmNbbaKapyukRCElsfJQjdHEV/jMSEdmF75RQaHiPC', 'Mr', 2147483647, NULL, 'Toure@gmail.com', 0, '', '2025-07-24 16:32:25', 'defafde26e84ca69788f470efb7bf4531b6b803deabc6b47e6b4c03ac882a067', '2025-07-24 16:32:25', 2),
(46, 'Suarez', 'Luis', '$2y$10$JTO5Hc31FYxL721VA1XP8OkznLT6GQ9l32mIcJxuL1UdeT2grK40W', 'Mr', 2147483647, NULL, 'Suarez@gmail.com', 0, '', '2025-07-24 16:33:18', '3ce3d814d77778ec75f4f08204424a0d9e8ad10192e4656564ed001929bcb4d6', '2025-07-24 16:33:18', 2),
(47, 'Busi', 'Sergio', '$2y$10$bUTjxTFAEblxTJCrIzAOOO.NWhk2ZVr8uRk1N4Oj.NwJMEWp0jfbG', 'Mr', 2147483647, NULL, 'busi@gmail.com', 0, '', '2025-07-24 16:34:20', '739d28a6156299a2e8c2d433502ac793424b4cbb713d4d5d211fe98367d44043', '2025-07-24 16:34:20', 2),
(48, 'Puyol', 'Charles', '$2y$10$vHvZa1GsThfbL3mv1U24Z.8A7xbmVgiWPBsQE26nHRvvQiKZqsNHu', 'Mr', 2147483647, NULL, 'Puyol@gmail.com', 0, '', '2025-07-24 16:35:01', '093c67faff9d534e9352ea2ddfc06878afbf68355ec3373c0cc690542ecabe36', '2025-07-24 16:35:01', 2),
(49, 'Alves', 'Dani', '$2y$10$g4P1j9nAmzgK9Kg9irFsH.95u5GEJ7/k0YaY7N0H9fa4ghHcz9V4O', 'Mr', 2147483647, NULL, 'Alves@gmail.com', 0, '', '2025-07-24 16:35:42', '70ad5c217f1facbb41ba2a5d36c90a617a2eeaea6b346519fabacceca02218ee', '2025-07-24 16:35:42', 2),
(50, 'Villa', 'David', '$2y$10$SSOUv/xPar0RTVi9HExhkO8RvBQwAeroC1t1pl6kkmSbxAGhAY.ma', 'Mr', 2147483647, NULL, 'Villas@gmail.com', 0, '', '2025-07-24 16:36:35', 'cff98d494f903f48fc76a3ef307bab819bbde4a30d1a1b5a2dd886fd4ffc7110', '2025-07-24 16:36:35', 2),
(51, 'Something', 'Pique', '$2y$10$FS.HK94En2HeKsMLGD211e9HY6rS6g9.BivHxRVLEdwSrl4ZGzeRi', 'Mr', 2147483647, NULL, 'pique@gmail.com', 0, '', '2025-07-24 16:37:36', '4507cf88fbe8dd6627e0ebe1ae410099a003b4b2e4b986b2b27ad4b8ed6d948f', '2025-07-24 16:37:36', 2),
(52, 'Gaucho', 'Ronaldinho', '$2y$10$jf1gcAWQ.nRfshE13TvWEuSd2JwRhRbbgL/IRBbTXmKa2TsDAfZwO', 'Mr', 2147483647, NULL, 'Gaucho@gmail.com', 0, '', '2025-07-24 16:38:55', '1d2d1d942bdc371111d2e34d5f776f821cbd2c3ca1ff722dd6c69146e06cc850', '2025-07-24 16:38:55', 2),
(53, 'Etoo', 'Samuel', '$2y$10$AI30OgF3sszyt5Y98Cs7serogGYrkGBn8SKoXIZk09uJ5gyeJG8PK', 'Mr', 2147483647, NULL, 'Etoo@gmail.com', 0, '', '2025-07-24 16:39:24', 'e726af57cd1b60b0e0f261123d3f6cb69051e25737235794d24dbb17de6165fe', '2025-07-24 16:39:24', 2),
(54, 'Ramos', 'Sergio', '$2y$10$IpAKxz567lJXC4ctcpykQu35FzCx4yZpc15vUfIJs/0x2gv3XIH9.', 'Mr', 2147483647, NULL, 'Ramos@gmail.com', 0, '', '2025-07-24 16:41:09', '258d1928b1dcb5b8f36856d5d598869a1e9413ded12ba44db8ed53aa6e50d12a', '2025-07-24 16:41:09', 2),
(55, 'Parkar', 'Letty', '$2y$10$jnos60lQhNpspOQ1pPWCj.GUubh8s5K/Qx3XHNjKwazmIXfQSk5nO', 'Mrs', 2147483647, NULL, 'parkar@gmail.com', 0, '', '2025-07-24 17:10:55', '0669703be53a55438658c4dda7e5bcec', '2025-07-24 17:10:55', 1),
(56, 'Jones', 'Molly', '$2y$10$X20DJj8saNZRr2x/3qRteubET9E0/ODNhfQ6ew7RKiPFMMqjDlIWS', 'Mrs', 2147483647, NULL, 'jones@gmail.com', 0, '', '2025-07-24 17:16:07', '84124d7519dc470701bb0a964fe7726a', '2025-07-24 17:16:07', 1),
(57, 'Boorn', 'Joris', '$2y$10$TTTZbLPOmdEyBtYaiAgDfelndBJKj0qYkuzmEkYzoMt1s3RNWJai6', 'Mr', 2147483647, NULL, 'boorn@gmail.com', 0, '', '2025-07-24 17:18:56', '81b420a23b1b2a503779767ac000fb17', '2025-07-24 17:18:56', 1),
(58, 'FFFFF', 'EEEEEE', '$2y$10$k5peCRhgnOMM706SpYdyBeEi2UMIE7icxKFyWccnfTNAiE9al6vkq', 'Mr', 2147483647, NULL, 'fffffff@gmail.com', 0, '', '2025-07-24 17:20:10', 'e3661c1b3f8684f54c7b40b4760a9e2a', '2025-07-24 17:20:10', 1),
(59, 'wswsw', 'wswswsw', '$2y$10$pen8U65c24d2/RvKFc4d5e0O24OrxqML5DvDN28Ha1sbxVsZGvMRe', 'Mr', 2147483647, NULL, 'wsww@gmail.com', 0, '', '2025-07-24 17:21:20', 'dca23cd7890ea2d70fb61b839452a30d', '2025-07-24 17:21:20', 1);

-- --------------------------------------------------------

--
-- Table structure for table `usersubject`
--

CREATE TABLE `usersubject` (
  `Id` int(11) NOT NULL,
  `SubjectId` int(11) DEFAULT NULL,
  `UserId` int(11) NOT NULL,
  `SubjectName` varchar(100) DEFAULT NULL,
  `SubjectCode` varchar(20) DEFAULT NULL,
  `ThreeMonthsPrice` decimal(10,2) DEFAULT NULL,
  `SixMonthsPrice` decimal(10,2) DEFAULT NULL,
  `TwelveMonthsPrice` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`ClassID`),
  ADD KEY `SubjectID` (`SubjectID`),
  ADD KEY `TutorID` (`TutorID`);

--
-- Indexes for table `directorsubjects`
--
ALTER TABLE `directorsubjects`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `DirectorId` (`DirectorId`),
  ADD KEY `SubjectId` (`SubjectId`);

--
-- Indexes for table `feedbacklog`
--
ALTER TABLE `feedbacklog`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `ActivityId` (`ActivityId`),
  ADD KEY `TutorId` (`TutorId`);

--
-- Indexes for table `finances`
--
ALTER TABLE `finances`
  ADD PRIMARY KEY (`FinanceId`),
  ADD KEY `LearnerId` (`LearnerId`);

--
-- Indexes for table `learneractivitymarks`
--
ALTER TABLE `learneractivitymarks`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `learneranswers`
--
ALTER TABLE `learneranswers`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `idx_user_activity` (`UserId`,`ActivityId`),
  ADD KEY `ActivityId` (`ActivityId`),
  ADD KEY `QuestionId` (`QuestionId`);

--
-- Indexes for table `learnerclasses`
--
ALTER TABLE `learnerclasses`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `LearnerID` (`LearnerID`),
  ADD KEY `ClassID` (`ClassID`);

--
-- Indexes for table `learnerhomeworkresults`
--
ALTER TABLE `learnerhomeworkresults`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `idx_user_activity` (`UserId`,`ActivityId`),
  ADD KEY `ActivityId` (`ActivityId`);

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
  ADD KEY `CreatedBy` (`CreatedBy`);

--
-- Indexes for table `onlineactivities`
--
ALTER TABLE `onlineactivities`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `onlinequestions`
--
ALTER TABLE `onlinequestions`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `ActivityId` (`ActivityId`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`RatingId`),
  ADD KEY `TutorId` (`TutorId`),
  ADD KEY `LearnerId` (`LearnerId`);

--
-- Indexes for table `registrationquestions`
--
ALTER TABLE `registrationquestions`
  ADD PRIMARY KEY (`QuestionId`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `UploadedBy` (`UploadedBy`);

--
-- Indexes for table `subjectnotices`
--
ALTER TABLE `subjectnotices`
  ADD PRIMARY KEY (`NoticeId`);

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
-- Indexes for table `tutoravailability`
--
ALTER TABLE `tutoravailability`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `TutorId` (`TutorId`);

--
-- Indexes for table `tutordateexceptions`
--
ALTER TABLE `tutordateexceptions`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `unique_exception` (`TutorId`,`ExceptionDate`);

--
-- Indexes for table `tutors`
--
ALTER TABLE `tutors`
  ADD PRIMARY KEY (`TutorId`);

--
-- Indexes for table `tutorsessions`
--
ALTER TABLE `tutorsessions`
  ADD PRIMARY KEY (`SessionId`),
  ADD UNIQUE KEY `unique_booking` (`TutorId`,`SlotDateTime`),
  ADD KEY `LearnerId` (`LearnerId`);

--
-- Indexes for table `tutorsubject`
--
ALTER TABLE `tutorsubject`
  ADD PRIMARY KEY (`TutorId`,`SubjectId`),
  ADD KEY `SubjectId` (`SubjectId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `usersubject`
--
ALTER TABLE `usersubject`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `UserId` (`UserId`),
  ADD KEY `SubjectId` (`SubjectId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `ActivityId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `ClassID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `directorsubjects`
--
ALTER TABLE `directorsubjects`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `feedbacklog`
--
ALTER TABLE `feedbacklog`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `finances`
--
ALTER TABLE `finances`
  MODIFY `FinanceId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `learneractivitymarks`
--
ALTER TABLE `learneractivitymarks`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `learneranswers`
--
ALTER TABLE `learneranswers`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `learnerclasses`
--
ALTER TABLE `learnerclasses`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `learnerhomeworkresults`
--
ALTER TABLE `learnerhomeworkresults`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `learners`
--
ALTER TABLE `learners`
  MODIFY `LearnerId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `learnersubject`
--
ALTER TABLE `learnersubject`
  MODIFY `LearnerSubjectId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `NoticeNo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `onlineactivities`
--
ALTER TABLE `onlineactivities`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `onlinequestions`
--
ALTER TABLE `onlinequestions`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `RatingId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `registrationquestions`
--
ALTER TABLE `registrationquestions`
  MODIFY `QuestionId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subjectnotices`
--
ALTER TABLE `subjectnotices`
  MODIFY `NoticeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `SubjectId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `todolist`
--
ALTER TABLE `todolist`
  MODIFY `TodoId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tutoravailability`
--
ALTER TABLE `tutoravailability`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tutordateexceptions`
--
ALTER TABLE `tutordateexceptions`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tutorsessions`
--
ALTER TABLE `tutorsessions`
  MODIFY `SessionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `usersubject`
--
ALTER TABLE `usersubject`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_ibfk_1` FOREIGN KEY (`SubjectId`) REFERENCES `subjects` (`SubjectId`);

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`SubjectID`) REFERENCES `subjects` (`SubjectId`),
  ADD CONSTRAINT `classes_ibfk_2` FOREIGN KEY (`TutorID`) REFERENCES `tutors` (`TutorId`);

--
-- Constraints for table `directorsubjects`
--
ALTER TABLE `directorsubjects`
  ADD CONSTRAINT `directorsubjects_ibfk_1` FOREIGN KEY (`DirectorId`) REFERENCES `users` (`Id`),
  ADD CONSTRAINT `directorsubjects_ibfk_2` FOREIGN KEY (`SubjectId`) REFERENCES `subjects` (`SubjectId`);

--
-- Constraints for table `feedbacklog`
--
ALTER TABLE `feedbacklog`
  ADD CONSTRAINT `feedbacklog_ibfk_1` FOREIGN KEY (`ActivityId`) REFERENCES `onlineactivities` (`Id`),
  ADD CONSTRAINT `feedbacklog_ibfk_2` FOREIGN KEY (`TutorId`) REFERENCES `users` (`Id`);

--
-- Constraints for table `finances`
--
ALTER TABLE `finances`
  ADD CONSTRAINT `finances_ibfk_1` FOREIGN KEY (`LearnerId`) REFERENCES `learners` (`LearnerId`);

--
-- Constraints for table `learneranswers`
--
ALTER TABLE `learneranswers`
  ADD CONSTRAINT `learneranswers_ibfk_1` FOREIGN KEY (`UserId`) REFERENCES `users` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `learneranswers_ibfk_2` FOREIGN KEY (`ActivityId`) REFERENCES `onlineactivities` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `learneranswers_ibfk_3` FOREIGN KEY (`QuestionId`) REFERENCES `onlinequestions` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `learnerclasses`
--
ALTER TABLE `learnerclasses`
  ADD CONSTRAINT `learnerclasses_ibfk_1` FOREIGN KEY (`LearnerID`) REFERENCES `learners` (`LearnerId`),
  ADD CONSTRAINT `learnerclasses_ibfk_2` FOREIGN KEY (`ClassID`) REFERENCES `classes` (`ClassID`);

--
-- Constraints for table `learnerhomeworkresults`
--
ALTER TABLE `learnerhomeworkresults`
  ADD CONSTRAINT `learnerhomeworkresults_ibfk_1` FOREIGN KEY (`UserId`) REFERENCES `users` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `learnerhomeworkresults_ibfk_2` FOREIGN KEY (`ActivityId`) REFERENCES `onlineactivities` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `learnerregistrationanswers`
--
ALTER TABLE `learnerregistrationanswers`
  ADD CONSTRAINT `learnerregistrationanswers_ibfk_1` FOREIGN KEY (`LearnerId`) REFERENCES `learners` (`LearnerId`),
  ADD CONSTRAINT `learnerregistrationanswers_ibfk_2` FOREIGN KEY (`QuestionId`) REFERENCES `registrationquestions` (`QuestionId`);

--
-- Constraints for table `learners`
--
ALTER TABLE `learners`
  ADD CONSTRAINT `learner_ibfk_1` FOREIGN KEY (`LearnerId`) REFERENCES `users` (`Id`);

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
  ADD CONSTRAINT `notices_ibfk_1` FOREIGN KEY (`CreatedBy`) REFERENCES `users` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `onlinequestions`
--
ALTER TABLE `onlinequestions`
  ADD CONSTRAINT `onlinequestions_ibfk_1` FOREIGN KEY (`ActivityId`) REFERENCES `onlineactivities` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`TutorId`) REFERENCES `users` (`Id`),
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`LearnerId`) REFERENCES `users` (`Id`);

--
-- Constraints for table `resources`
--
ALTER TABLE `resources`
  ADD CONSTRAINT `resources_ibfk_1` FOREIGN KEY (`UploadedBy`) REFERENCES `users` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `todolist`
--
ALTER TABLE `todolist`
  ADD CONSTRAINT `todolist_ibfk_1` FOREIGN KEY (`CreatorId`) REFERENCES `users` (`Id`);

--
-- Constraints for table `tutoravailability`
--
ALTER TABLE `tutoravailability`
  ADD CONSTRAINT `tutoravailability_ibfk_1` FOREIGN KEY (`TutorId`) REFERENCES `users` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `tutordateexceptions`
--
ALTER TABLE `tutordateexceptions`
  ADD CONSTRAINT `tutordateexceptions_ibfk_1` FOREIGN KEY (`TutorId`) REFERENCES `users` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `tutors`
--
ALTER TABLE `tutors`
  ADD CONSTRAINT `tutors_ibfk_1` FOREIGN KEY (`TutorId`) REFERENCES `users` (`Id`);

--
-- Constraints for table `tutorsessions`
--
ALTER TABLE `tutorsessions`
  ADD CONSTRAINT `tutorsessions_ibfk_1` FOREIGN KEY (`TutorId`) REFERENCES `users` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tutorsessions_ibfk_2` FOREIGN KEY (`LearnerId`) REFERENCES `users` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `tutorsubject`
--
ALTER TABLE `tutorsubject`
  ADD CONSTRAINT `tutorsubject_ibfk_1` FOREIGN KEY (`TutorId`) REFERENCES `users` (`Id`),
  ADD CONSTRAINT `tutorsubject_ibfk_2` FOREIGN KEY (`SubjectId`) REFERENCES `subjects` (`SubjectId`);

--
-- Constraints for table `usersubject`
--
ALTER TABLE `usersubject`
  ADD CONSTRAINT `usersubject_ibfk_1` FOREIGN KEY (`UserId`) REFERENCES `users` (`Id`),
  ADD CONSTRAINT `usersubject_ibfk_2` FOREIGN KEY (`SubjectId`) REFERENCES `subjects` (`SubjectId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
