-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 20, 2025 at 12:28 PM
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
(2, 'activity 1111', 6, '2025-08-18 00:27:25', 30.00, 'Director', 12, 'Organic Chemistry', 'A'),
(3, 'activity 1111', 3, '2025-08-18 00:35:51', 25.00, 'Director', 12, 'Functions', 'A'),
(4, 'activity 222', 3, '2025-08-18 00:38:17', 25.00, 'Director', 12, 'Functions', 'A');

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
(20, 2, '11', 'A', 1, 21, 'Not Full', '2025-07-24 09:21:52'),
(21, 1, '10', 'A', 15, 2, 'Full', '2025-07-24 09:37:39'),
(22, 4, '10', 'A', 7, 21, 'Not Full', '2025-07-24 09:37:40'),
(23, 1, '10', 'B', 2, 2, 'Not Full', '2025-07-24 16:41:09'),
(24, 3, '12', 'A', 1, 25, 'Not Full', '2025-08-04 15:08:20'),
(25, 6, '12', 'A', 1, 25, 'Not Full', '2025-08-04 15:08:20');

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
(38, 54, NULL, NULL, 10, 750.00, 0.00, 750.00, 0.00),
(39, 60, NULL, NULL, 10, 1949.00, 0.00, 750.00, 1199.00),
(40, 61, NULL, NULL, 12, 1500.00, 0.00, 750.00, 750.00);

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `GradeId` int(11) NOT NULL,
  `SchoolId` int(11) NOT NULL,
  `GradeName` varchar(50) NOT NULL,
  `CreatedAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`GradeId`, `SchoolId`, `GradeName`, `CreatedAt`) VALUES
(1, 4, 'Grade 10', '2025-08-08 15:58:08'),
(2, 4, 'Grade 11', '2025-08-08 15:58:08'),
(3, 4, 'Grade 12', '2025-08-08 15:58:08'),
(33, 14, 'Grade 10', '2025-08-08 18:05:42'),
(34, 14, 'Grade 11', '2025-08-08 18:05:42'),
(35, 15, 'Grade 10', '2025-08-11 11:11:04'),
(36, 15, 'Grade 11', '2025-08-11 11:11:04');

-- --------------------------------------------------------

--
-- Table structure for table `inviterequests`
--

CREATE TABLE `inviterequests` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `IsAccepted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inviterequests`
--

INSERT INTO `inviterequests` (`id`, `name`, `surname`, `email`, `message`, `created_at`, `IsAccepted`) VALUES
(1, 'Sipho', 'Mbule', 'emahlwele05@gmail.com', 'Hi, I am interested in joining the program.', '2025-07-25 16:46:19', 0),
(4, 'Thandi', 'Nkosi', 'thandi.nkosi@example.com', 'I would love to join your learning platform.', '2025-07-24 08:15:00', 1),
(18, 'Boshielo', 'Boshielo', 'emahlwele05@gmail.com', '', '2025-08-07 11:50:36', 0);

-- --------------------------------------------------------

--
-- Table structure for table `invitetokens`
--

CREATE TABLE `invitetokens` (
  `Id` int(11) NOT NULL,
  `InviteRequestId` int(11) NOT NULL,
  `Token` varchar(64) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `IsUsed` tinyint(1) DEFAULT 0,
  `CreatedAt` datetime DEFAULT current_timestamp(),
  `ExpiresAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invitetokens`
--

INSERT INTO `invitetokens` (`Id`, `InviteRequestId`, `Token`, `Email`, `IsUsed`, `CreatedAt`, `ExpiresAt`) VALUES
(2, 1, '392496c901a6763dd2144ecadb8a91199b7cf5ad7006a162b8f80c39fa58d540', 'emahlwele05@gmail.com', 0, '2025-07-25 19:47:41', '2025-08-01 19:47:41'),
(8, 4, 'da38169ed253b53943796f7766fe679ea786efdbb41c8e4345a00aabd2a8c8f4', 'thandi.nkosi@example.com', 0, '2025-07-28 15:32:52', '2025-08-04 15:32:52');

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
(34, 54, 36, 1, 23, '2025-07-24 20:25:44', 'present', 'None', 'Yes', 'None'),
(35, 38, 37, 1, 21, '2025-07-25 09:58:36', 'present', 'None', 'Yes', 'None'),
(36, 38, 38, 1, 20, '2025-07-25 17:01:15', 'present', 'None', 'Yes', 'None'),
(37, 38, 39, 1, 15, '2025-07-27 16:40:35', 'present', 'None', 'Yes', 'None'),
(38, 38, 40, 1, 20, '2025-07-30 15:46:28', 'present', 'None', 'Yes', 'None'),
(39, 61, 41, 1, 17, '2025-08-16 18:33:53', 'present', 'None', 'Yes', 'None'),
(40, 61, 41, 1, 20, '2025-08-16 18:40:02', 'present', 'None', 'Yes', 'None'),
(41, 61, 41, 1, 14, '2025-08-16 18:47:19', 'present', 'None', 'Yes', 'None'),
(42, 61, 41, 1, 14, '2025-08-16 18:48:15', 'present', 'None', 'Yes', 'None'),
(43, 61, 41, 1, 20, '2025-08-16 18:51:33', 'present', 'None', 'Yes', 'None'),
(44, 61, 41, 1, 20, '2025-08-16 18:52:11', 'present', 'None', 'Yes', 'None'),
(45, 61, 41, 1, 14, '2025-08-16 18:53:35', 'present', 'None', 'Yes', 'None'),
(46, 61, 2, 1, 21, '2025-08-18 00:29:43', 'present', 'None', 'Yes', 'None'),
(47, 61, 3, 1, 20, '2025-08-18 00:36:17', 'present', 'None', 'Yes', 'None'),
(48, 61, 4, 1, 23, '2025-08-18 00:38:24', 'present', 'None', 'Yes', 'None');

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
(22, 42, 15, 26, 'A', '2025-07-24 21:02:36'),
(23, 42, 3, 4, 'B', '2025-07-25 10:04:44'),
(24, 42, 16, 27, 'B', '2025-07-25 12:04:09'),
(25, 42, 16, 28, 'C', '2025-07-25 12:04:09'),
(26, 42, 16, 29, 'A', '2025-07-25 12:04:10'),
(27, 42, 16, 30, 'C', '2025-07-25 12:04:10'),
(28, 42, 16, 31, 'C', '2025-07-25 12:04:10'),
(29, 42, 16, 32, 'C', '2025-07-25 12:04:10'),
(30, 42, 16, 33, 'B', '2025-07-25 12:04:10'),
(31, 42, 16, 34, 'C', '2025-07-25 12:04:10'),
(32, 42, 16, 35, 'B', '2025-07-25 12:04:10'),
(33, 42, 16, 36, 'C', '2025-07-25 12:04:10'),
(34, 43, 16, 27, 'A', '2025-07-25 12:06:15'),
(35, 43, 16, 28, 'A', '2025-07-25 12:06:15'),
(36, 43, 16, 29, 'C', '2025-07-25 12:06:15'),
(37, 43, 16, 30, 'D', '2025-07-25 12:06:16'),
(38, 43, 16, 31, 'C', '2025-07-25 12:06:16'),
(39, 43, 16, 32, 'D', '2025-07-25 12:06:16'),
(40, 43, 16, 33, 'D', '2025-07-25 12:06:16'),
(41, 43, 16, 34, 'C', '2025-07-25 12:06:16'),
(42, 43, 16, 35, 'A', '2025-07-25 12:06:16'),
(43, 43, 16, 36, 'C', '2025-07-25 12:06:16'),
(44, 44, 16, 27, 'A', '2025-07-25 12:07:15'),
(45, 44, 16, 28, 'B', '2025-07-25 12:07:15'),
(46, 44, 16, 29, 'B', '2025-07-25 12:07:15'),
(47, 44, 16, 30, 'C', '2025-07-25 12:07:15'),
(48, 44, 16, 31, 'B', '2025-07-25 12:07:15'),
(49, 44, 16, 32, 'B', '2025-07-25 12:07:15'),
(50, 44, 16, 33, 'C', '2025-07-25 12:07:15'),
(51, 44, 16, 34, 'C', '2025-07-25 12:07:15'),
(52, 44, 16, 35, 'C', '2025-07-25 12:07:15'),
(53, 44, 16, 36, 'C', '2025-07-25 12:07:15'),
(54, 45, 16, 27, 'A', '2025-07-25 12:08:55'),
(55, 45, 16, 28, 'A', '2025-07-25 12:08:55'),
(56, 45, 16, 29, 'B', '2025-07-25 12:08:55'),
(57, 45, 16, 30, 'A', '2025-07-25 12:08:55'),
(58, 45, 16, 31, 'B', '2025-07-25 12:08:55'),
(59, 45, 16, 32, 'A', '2025-07-25 12:08:55'),
(60, 45, 16, 33, 'B', '2025-07-25 12:08:55'),
(61, 45, 16, 34, 'B', '2025-07-25 12:08:55'),
(62, 45, 16, 35, 'B', '2025-07-25 12:08:55'),
(63, 45, 16, 36, 'C', '2025-07-25 12:08:55'),
(64, 46, 16, 27, 'B', '2025-07-25 12:11:01'),
(65, 46, 16, 28, 'C', '2025-07-25 12:11:01'),
(66, 46, 16, 29, 'C', '2025-07-25 12:11:02'),
(67, 46, 16, 30, 'C', '2025-07-25 12:11:02'),
(68, 46, 16, 31, 'C', '2025-07-25 12:11:02'),
(69, 46, 16, 32, 'C', '2025-07-25 12:11:02'),
(70, 46, 16, 33, 'C', '2025-07-25 12:11:02'),
(71, 46, 16, 34, 'B', '2025-07-25 12:11:02'),
(72, 46, 16, 35, 'B', '2025-07-25 12:11:02'),
(73, 46, 16, 36, 'C', '2025-07-25 12:11:02'),
(74, 50, 16, 27, 'B', '2025-07-25 12:11:52'),
(75, 50, 16, 28, 'C', '2025-07-25 12:11:52'),
(76, 50, 16, 29, 'C', '2025-07-25 12:11:52'),
(77, 50, 16, 30, 'A', '2025-07-25 12:11:52'),
(78, 50, 16, 31, 'C', '2025-07-25 12:11:52'),
(79, 50, 16, 32, 'B', '2025-07-25 12:11:52'),
(80, 50, 16, 33, 'C', '2025-07-25 12:11:52'),
(81, 50, 16, 34, 'A', '2025-07-25 12:11:52'),
(82, 50, 16, 35, 'C', '2025-07-25 12:11:53'),
(83, 50, 16, 36, 'C', '2025-07-25 12:11:53'),
(84, 40, 16, 27, 'B', '2025-07-25 12:16:50'),
(85, 40, 16, 28, 'C', '2025-07-25 12:16:50'),
(86, 40, 16, 29, 'C', '2025-07-25 12:16:51'),
(87, 40, 16, 30, 'A', '2025-07-25 12:16:51'),
(88, 40, 16, 31, 'C', '2025-07-25 12:16:51'),
(89, 40, 16, 32, 'A', '2025-07-25 12:16:51'),
(90, 40, 16, 33, 'A', '2025-07-25 12:16:51'),
(91, 40, 16, 34, 'D', '2025-07-25 12:16:51'),
(92, 40, 16, 35, 'B', '2025-07-25 12:16:51'),
(93, 40, 16, 36, 'C', '2025-07-25 12:16:51'),
(94, 41, 16, 27, 'B', '2025-07-25 12:17:47'),
(95, 41, 16, 28, 'B', '2025-07-25 12:17:47'),
(96, 41, 16, 29, 'C', '2025-07-25 12:17:47'),
(97, 41, 16, 30, 'A', '2025-07-25 12:17:47'),
(98, 41, 16, 31, 'C', '2025-07-25 12:17:47'),
(99, 41, 16, 32, 'B', '2025-07-25 12:17:47'),
(100, 41, 16, 33, 'A', '2025-07-25 12:17:47'),
(101, 41, 16, 34, 'C', '2025-07-25 12:17:47'),
(102, 41, 16, 35, 'A', '2025-07-25 12:17:47'),
(103, 41, 16, 36, 'C', '2025-07-25 12:17:47'),
(104, 39, 16, 27, 'B', '2025-07-25 12:18:53'),
(105, 39, 16, 28, 'B', '2025-07-25 12:18:53'),
(106, 39, 16, 29, 'C', '2025-07-25 12:18:53'),
(107, 39, 16, 30, 'B', '2025-07-25 12:18:53'),
(108, 39, 16, 31, 'B', '2025-07-25 12:18:53'),
(109, 39, 16, 32, 'D', '2025-07-25 12:18:53'),
(110, 39, 16, 33, 'A', '2025-07-25 12:18:53'),
(111, 39, 16, 34, 'A', '2025-07-25 12:18:54'),
(112, 39, 16, 35, 'B', '2025-07-25 12:18:54'),
(113, 39, 16, 36, 'C', '2025-07-25 12:18:54'),
(114, 42, 13, 23, 'B', '2025-08-13 19:22:35'),
(115, 42, 4, 5, 'A', '2025-08-16 17:49:38'),
(116, 42, 6, 11, 'B', '2025-08-16 18:12:00'),
(117, 42, 6, 12, 'B', '2025-08-16 18:12:00'),
(118, 42, 2, 3, 'A', '2025-08-16 18:22:25'),
(119, 42, 9, 19, 'B', '2025-08-16 18:24:28'),
(120, 42, 8, 18, 'A', '2025-08-16 18:26:40'),
(121, 42, 1, 1, 'B', '2025-08-16 18:28:07'),
(122, 42, 1, 2, 'B', '2025-08-16 18:28:07'),
(123, 61, 18, 38, 'B', '2025-08-16 22:57:30'),
(124, 61, 18, 39, 'C', '2025-08-16 22:57:30');

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
(41, 54, 23, '2025-07-24 16:41:09'),
(42, 60, 23, '2025-07-27 14:52:25'),
(43, 60, 22, '2025-07-27 14:52:25'),
(44, 61, 24, '2025-08-04 15:08:20'),
(45, 61, 25, '2025-08-04 15:08:20');

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
(12, 42, 15, 100.00, '2025-07-24 21:02:36'),
(13, 42, 3, 0.00, '2025-07-25 10:04:45'),
(14, 42, 16, 50.00, '2025-07-25 12:04:10'),
(15, 43, 16, 30.00, '2025-07-25 12:06:16'),
(16, 44, 16, 20.00, '2025-07-25 12:07:15'),
(17, 45, 16, 50.00, '2025-07-25 12:08:56'),
(18, 46, 16, 80.00, '2025-07-25 12:11:02'),
(19, 50, 16, 70.00, '2025-07-25 12:11:53'),
(20, 40, 16, 80.00, '2025-07-25 12:16:52'),
(21, 41, 16, 50.00, '2025-07-25 12:17:47'),
(22, 39, 16, 40.00, '2025-07-25 12:18:54'),
(23, 42, 13, 0.00, '2025-08-13 19:22:35'),
(24, 42, 4, 100.00, '2025-08-16 17:49:38'),
(25, 42, 6, 0.00, '2025-08-16 18:12:00'),
(26, 42, 2, 100.00, '2025-08-16 18:22:25'),
(27, 42, 9, 0.00, '2025-08-16 18:24:28'),
(28, 42, 8, 100.00, '2025-08-16 18:26:40'),
(29, 42, 1, 50.00, '2025-08-16 18:28:07'),
(30, 61, 18, 0.00, '2025-08-16 22:57:30');

-- --------------------------------------------------------

--
-- Table structure for table `learnerlevel`
--

CREATE TABLE `learnerlevel` (
  `Id` int(11) NOT NULL,
  `LearnerId` int(11) NOT NULL,
  `LevelId` int(11) NOT NULL,
  `ChapterName` varchar(100) DEFAULT NULL,
  `Complete` tinyint(1) NOT NULL DEFAULT 0,
  `Mark` decimal(5,2) DEFAULT NULL,
  `NumberAttempts` int(11) DEFAULT 0,
  `TotalTimeTaken` int(11) DEFAULT 0,
  `NumberQuestionsComplete` int(11) DEFAULT 0,
  `NumberQuestionsLeft` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `learnerlevel`
--

INSERT INTO `learnerlevel` (`Id`, `LearnerId`, `LevelId`, `ChapterName`, `Complete`, `Mark`, `NumberAttempts`, `TotalTimeTaken`, `NumberQuestionsComplete`, `NumberQuestionsLeft`) VALUES
(17, 61, 1, 'Functions', 1, 1.00, 3, 28, 1, 0),
(18, 61, 1, 'Finances', 1, 4.00, 2, 89, 6, 0),
(19, 61, 1, 'Probability', 1, 9.00, 2, 161, 12, 0),
(20, 61, 1, 'Calculus', 1, 4.00, 4, 320, 5, 0),
(21, 61, 1, 'Trigonometry', 1, 1.00, 1, 32, 4, 0),
(22, 61, 2, 'Trigonometry', 0, 0.00, 5, 212, 1, 2),
(23, 61, 1, 'Analytical Geometry', 0, 0.00, 4, 97, 2, 3),
(24, 61, 1, 'Sequences & Series', 1, 5.00, 6, 177, 5, 0),
(25, 61, 1, 'Statistics', 0, 7.00, 3, 220, 9, 1),
(26, 61, 2, 'Finances', 1, 1.00, 3, 9, 1, 0),
(27, 61, 2, 'Probability', 1, 5.00, 2, 34, 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `learnerpracticequestions`
--

CREATE TABLE `learnerpracticequestions` (
  `Id` int(11) NOT NULL,
  `LearnerId` int(11) NOT NULL,
  `QuestionId` int(11) NOT NULL,
  `Status` enum('complete','incomplete') NOT NULL DEFAULT 'incomplete'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `learnerpracticequestions`
--

INSERT INTO `learnerpracticequestions` (`Id`, `LearnerId`, `QuestionId`, `Status`) VALUES
(105, 61, 21, 'complete'),
(109, 61, 16, 'complete'),
(110, 61, 23, 'complete'),
(111, 61, 24, 'complete'),
(122, 61, 25, 'complete'),
(123, 61, 26, 'complete'),
(124, 61, 27, 'complete'),
(125, 61, 28, 'complete'),
(126, 61, 29, 'complete'),
(127, 61, 30, 'complete'),
(128, 61, 31, 'complete'),
(129, 61, 32, 'complete'),
(130, 61, 33, 'complete'),
(131, 61, 34, 'complete'),
(147, 61, 19, 'complete'),
(160, 61, 12, 'complete'),
(176, 61, 102, 'complete'),
(177, 61, 103, 'complete'),
(178, 61, 87, 'complete'),
(179, 61, 88, 'complete'),
(180, 61, 89, 'complete'),
(181, 61, 90, 'complete'),
(182, 61, 91, 'complete'),
(204, 61, 19, 'complete'),
(205, 61, 19, 'complete'),
(206, 61, 19, 'complete'),
(207, 61, 25, 'complete'),
(210, 61, 25, 'complete'),
(211, 61, 16, 'complete'),
(212, 61, 16, 'complete'),
(213, 61, 16, 'complete'),
(218, 61, 52, 'complete'),
(219, 61, 53, 'complete'),
(220, 61, 54, 'complete'),
(221, 61, 55, 'complete'),
(222, 61, 56, 'complete'),
(225, 61, 17, 'complete'),
(231, 61, 40, 'complete'),
(232, 61, 41, 'complete'),
(233, 61, 42, 'complete'),
(234, 61, 43, 'complete'),
(235, 61, 44, 'complete'),
(254, 61, 57, 'complete'),
(255, 61, 58, 'complete'),
(256, 61, 59, 'complete'),
(257, 61, 60, 'complete'),
(258, 61, 61, 'complete'),
(259, 61, 62, 'complete'),
(260, 61, 63, 'complete'),
(261, 61, 64, 'complete'),
(262, 61, 65, 'complete');

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
(38, '11', '2025-07-24', '00:00:17', 750.00, 0.00, 750.00, 65.00, 'Mrs', 'MotherSolo', 'Solo', 'msolo@gmail.com', '5552525458', '2025-08-17 00:44:40'),
(39, '10', '2025-07-24', '00:00:18', 750.00, 750.00, 1250.00, 200.00, 'Dr', 'MotherSisdod', 'Solo', 'msisdod@gmail.com', '5552525452', '2025-08-17 00:43:50'),
(40, '10', '2025-07-24', '00:00:12', 1199.00, 1199.00, 1950.00, 0.00, 'Mr', 'MotherRAshford', 'Rashford', 'rashfordd@gmail.com', '5552525458', NULL),
(41, '10', '2025-07-24', '00:00:12', 750.00, 1199.00, 1949.00, 0.00, 'Ms', 'MotherMessi', 'Messi', 'mlio@gmail.com', '5552525458', NULL),
(42, '10', '2025-07-24', '00:00:12', 750.00, 1199.00, 1949.00, 200.00, 'Ms', 'MotherIniesta', 'Messi', 'mIniesta@gmail.com', '5552525458', '2025-07-25 12:01:39'),
(43, '10', '2025-07-24', '00:00:12', 750.00, 750.00, 1250.00, 0.00, 'Ms', 'MotherHernandes', 'Hernandes', 'mHernandes@gmail.com', '5552525458', NULL),
(44, '10', '2025-07-24', '00:00:12', 750.00, 750.00, 1250.00, 0.00, 'Ms', 'MotherIbrah', 'Ibrah', 'mIbrah@gmail.com', '5552525458', NULL),
(45, '10', '2025-07-24', '00:00:12', 750.00, 0.00, 750.00, 0.00, 'Ms', 'MotherToure', 'Toure', 'mToure@gmail.com', '5552525458', NULL),
(46, '10', '2025-07-24', '00:00:12', 750.00, 0.00, 750.00, 50.00, 'Ms', 'MotherSuarez', 'Suarez', 'mSuarez@gmail.com', '5552525458', '2025-08-17 00:43:02'),
(47, '10', '2025-07-24', '00:00:12', 750.00, 0.00, 750.00, 0.00, 'Ms', 'MotherBusi', 'Busq', 'mBusi@gmail.com', '5552525458', NULL),
(48, '10', '2025-07-24', '00:00:12', 750.00, 0.00, 750.00, 100.00, 'Ms', 'MotherPuyol', 'Puyol', 'mPuyoli@gmail.com', '5552525458', '2025-07-27 18:52:04'),
(49, '10', '2025-07-24', '00:00:12', 750.00, 0.00, 750.00, 0.00, 'Ms', 'MotherAlves', 'Alves', 'mAlvesl@gmail.com', '5552525458', NULL),
(50, '10', '2025-07-24', '00:00:12', 750.00, 0.00, 750.00, 54.00, 'Ms', 'MotherVilla', 'Villa', 'mVilla@gmail.com', '5552525458', '2025-08-17 00:43:15'),
(51, '10', '2025-07-24', '00:00:12', 750.00, 0.00, 750.00, 0.00, 'Ms', 'MotherPique', 'SomethingPique', 'mpiq@gmail.com', '5552525458', NULL),
(52, '10', '2025-07-24', '00:00:12', 750.00, 0.00, 750.00, 0.00, 'Ms', 'MotherGaucho', 'Gaucho', 'mGaucho@gmail.com', '5552525458', NULL),
(53, '10', '2025-07-24', '00:00:12', 750.00, 0.00, 750.00, 0.00, 'Ms', 'MotherEtoo', 'Etoo', 'mEtoo@gmail.com', '5552525458', NULL),
(54, '10', '2025-07-24', '00:00:12', 750.00, 0.00, 750.00, 0.00, 'Ms', 'MotherRamos', 'Ramos', 'mRamos@gmail.com', '5552525458', NULL),
(60, '10', '2025-07-27', '00:00:17', 750.00, 1199.00, 1949.00, 1000.00, 'Ms', 'MotherModric', 'Modric', 'mmodri@gmail.com', '0548854124', '2025-08-17 00:50:54'),
(61, '12', '2025-08-04', '00:00:06', 750.00, 750.00, 1250.00, 0.00, 'Ms', 'MotherKante', 'Kante', 'mkante@gmail.com', '0548787787', NULL);

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
(53, 54, 1, 7, 1, 2, '2026-01-24 18:41:09', 'Active'),
(54, 60, 1, 5, 1, 2, '2026-01-27 16:52:25', 'Active'),
(55, 60, 4, 4, 1, 3, '2026-07-27 16:52:25', 'Active'),
(56, 61, 3, 5, 1, 2, '2026-02-04 17:08:20', 'Active'),
(57, 61, 6, 5, 1, 2, '2026-02-04 17:08:20', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `level`
--

CREATE TABLE `level` (
  `Id` int(11) NOT NULL,
  `LevelName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `level`
--

INSERT INTO `level` (`Id`, `LevelName`) VALUES
(1, 'Easy'),
(2, 'Medium'),
(3, 'Hard');

-- --------------------------------------------------------

--
-- Table structure for table `memos`
--

CREATE TABLE `memos` (
  `Id` int(11) NOT NULL,
  `SubjectName` varchar(100) NOT NULL,
  `GradeName` varchar(100) NOT NULL,
  `LevelName` varchar(100) NOT NULL,
  `Chapter` varchar(255) NOT NULL,
  `MemoFilename` varchar(255) NOT NULL,
  `UploadedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `memos`
--

INSERT INTO `memos` (`Id`, `SubjectName`, `GradeName`, `LevelName`, `Chapter`, `MemoFilename`, `UploadedAt`) VALUES
(1, 'Mathematics', 'Grade 8', 'Easy', 'Functions', 'mathematics-grade-8-easy-functions_1755025085.pdf', '2025-08-12 18:58:05'),
(2, 'Mathematics', 'Grade 12', 'Medium', 'Trigonometry', 'mathematics-grade-12-medium-trigonometry_1755028494.pdf', '2025-08-12 19:54:54'),
(3, 'Mathematics', 'Grade 12', 'Easy', 'Calculus', 'mathematics-grade-12-easy-calculus_1755360079.pdf', '2025-08-16 16:01:19'),
(4, 'Mathematics', 'Grade 12', 'Easy', 'Finances', 'mathematics-grade-12-easy-finances_1755360432.pdf', '2025-08-16 16:07:12'),
(5, 'Mathematics', 'Grade 12', 'Easy', 'Statistics', 'mathematics-grade-12-easy-statistics_1755360497.pdf', '2025-08-16 16:08:17'),
(6, 'Mathematics', 'Grade 12', 'Easy', 'Sequences & Series', 'mathematics-grade-12-easy-sequences-series_1755360789.pdf', '2025-08-16 16:13:09'),
(7, 'Mathematics', 'Grade 12', 'Medium', 'Probability', 'mathematics-grade-12-medium-probability_1755361278.pdf', '2025-08-16 16:21:18');

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `NoticeNo` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Content` text NOT NULL,
  `Date` datetime NOT NULL DEFAULT current_timestamp(),
  `ExpiryDate` date DEFAULT NULL,
  `IsOpened` tinyint(1) NOT NULL DEFAULT 0,
  `CreatedBy` int(11) NOT NULL,
  `CreatedFor` int(11) NOT NULL COMMENT '1 = Learners, 2 = Tutors, 12 = Both'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`NoticeNo`, `Title`, `Content`, `Date`, `ExpiryDate`, `IsOpened`, `CreatedBy`, `CreatedFor`) VALUES
(1, 'Welcome Back to Term 3', 'Dear learners and tutors, Term 3 has officially started. Please check your schedules and submit all pending assignments on time.', '2025-07-09 20:38:47', NULL, 1, 1, 12),
(2, 'System Maintenance Notification', 'The system will be down for maintenance on Saturday from 10 PM to 2 AM. Please save your work accordingly.', '2025-07-09 20:38:47', NULL, 1, 2, 12),
(4, 'Title', 'This is the first nitice from the form', '2025-07-09 20:56:09', NULL, 1, 1, 12),
(5, 'Mid-Year Exams Preparation', 'Dear Learners, please begin preparing for your mid-year exams scheduled for next month. Study guides have been uploaded.', '2025-07-09 21:08:43', NULL, 0, 1, 1),
(6, 'New Resources Available', 'New Maths and Science videos are now available in your Resources tab.', '2025-07-09 21:08:44', NULL, 1, 1, 1),
(7, 'Friday Q&A Session', 'Join our live Q&A session this Friday at 4PM for help with your homework and recent topics.', '2025-07-09 21:08:44', NULL, 1, 1, 1),
(8, 'Mark Submission Reminder', 'Tutors, please submit all learner marks for the week by Friday 17:00.', '2025-07-09 21:08:44', NULL, 0, 1, 2),
(9, 'Mandatory Tutor Meeting', 'All tutors are required to attend an online meeting this Thursday at 18:00 to discuss Term 3 planning.', '2025-07-09 21:08:44', NULL, 0, 1, 2),
(10, 'New Notice after updates', 'asdasd sfs fds ds fdsf df dfd fdfsdf sdfds fdsf', '2025-07-25 09:49:14', '2025-10-11', 0, 1, 12),
(11, 'Second Notice after updates', 'sssa s dsdf gd dg', '2025-07-25 09:51:14', '2025-08-08', 0, 1, 12),
(12, 'Second Notice after updates', 'sssa s dsdf gd dg', '2025-07-25 09:51:28', '2025-08-08', 0, 1, 12),
(13, '22222222222222', '3333333333333', '2025-07-25 10:20:19', '2025-08-09', 0, 1, 1),
(14, 'Emmanuel Emmanuel', '\"Exam Timetable Updated - Please download the latest PDF from the resources page.\"', '2025-08-11 00:16:04', '2025-08-30', 0, 1, 12);

-- --------------------------------------------------------

--
-- Table structure for table `oldsubjects`
--

CREATE TABLE `oldsubjects` (
  `SubjectId` int(11) NOT NULL,
  `SubjectName` varchar(255) NOT NULL,
  `Grade` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `oldsubjects`
--

INSERT INTO `oldsubjects` (`SubjectId`, `SubjectName`, `Grade`) VALUES
(1, 'Mathematics_10', '10'),
(2, 'Mathematics_11', '11'),
(3, 'Mathematics_12', '12'),
(4, 'Physical Sciences_10', '10'),
(5, 'Physical Sciences_11', '11'),
(6, 'Physical Sciences_12', '12');

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
(15, 1, '1', '10', 'Statistics', 'The second quiz with Groups', 'Default instructions here', 1, '2025-05-24', '2025-07-24 22:16:54', '../uploads/1753388214_Picture5.jpg', NULL, 'A'),
(16, 1, '1', '10', 'Statistics', 'Demo Quiz', 'Default instructions here', 10, '2025-07-24', '2025-07-17 13:58:53', NULL, NULL, 'A'),
(17, 1, '2', '11', 'Functions', 'LAst o0', 'Default instructions here', 1, '2025-08-09', '2025-07-30 17:47:25', NULL, NULL, 'A'),
(18, 1, '3', '12', 'Functions', 'Quiz dsdfhgj', 'Default instructions here', 2, '2025-08-30', '2025-08-16 23:09:54', NULL, NULL, 'A'),
(19, 1, '3', '12', 'Probability', 'Quiz dsdfhgj hjgjk', 'Default instructions here', 1, '2025-07-12', '2025-08-16 23:40:56', NULL, NULL, 'A'),
(20, 1, '3', '12', 'Probability', 'Quiz dsdfhgj hjgjkgerg reg', 'Default instructions here', 1, '2025-07-12', '2025-08-16 23:42:34', NULL, NULL, 'A'),
(21, 1, '3', '12', 'Statistics', 'quiz xxxxx', 'Default instructions here', 1, '2025-08-31', '2025-08-16 23:47:23', NULL, NULL, 'A'),
(22, 1, '3', '12', 'Measurement', 'quiz xxxxx', 'Default instructions here', 1, '2025-10-04', '2025-08-16 23:54:42', NULL, NULL, 'A'),
(23, 1, '3', '12', 'Analytical Geometry', 'quizyyyy', 'Default instructions here', 1, '2025-09-06', '2025-08-17 00:01:19', NULL, NULL, 'A'),
(24, 1, '3', '12', 'Analytical Geometry', 'quizyyyy', 'Default instructions here', 1, '2025-08-27', '2025-08-17 00:03:01', NULL, NULL, 'A'),
(25, 1, '3', '12', 'Analytical Geometry', 'quizyyyy', 'Default instructions here', 1, '2025-08-27', '2025-08-17 00:05:09', NULL, NULL, 'A'),
(26, 1, '3', '12', 'Sequences and Series', 'quizyyyy', 'Default instructions here', 1, '2025-08-28', '2025-08-17 00:07:48', NULL, NULL, 'A');

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
(23, 13, 'hdghjfydb dggf d dr  kj', '5', 'dsf', 'sdfg', 'dfsd', 'A'),
(24, 14, 'This is the first question of this first online quiz of the group.', '5', '44', 's5', 'sd', 'A'),
(25, 14, 'This is the second question of this first online quiz of the group.', 'R875', 'R1,000', 'R1,225', 'R1,200', 'A'),
(26, 15, 'This is the first question of this second online quiz of the group.', '5', '44', 's5', 'sd', 'A'),
(27, 16, 'What is the mode of the data set: 2, 4, 4, 6, 7, 4, 9? ?', '2', '4', '6', '9', 'B'),
(28, 16, ' The median of the numbers 10, 15, 12, 17, 14 is:', '12', '13', '14', '15', 'C'),
(29, 16, 'If the mean of 5 numbers is 20, what is their total?', '20', '25', '100', '120', 'C'),
(30, 16, ' Which of the following is a measure of central tendency?', 'Mean', 'Range', 'Variance', 'Standard deviation', 'A'),
(31, 16, 'What is the range of the data set: 3, 8, 10, 15, 21?', '12', '15', '18', '21', 'C'),
(32, 16, 'In a histogram, what is represented by the height of each bar?', 'Frequency', 'Class width', 'Mean', 'Median', 'A'),
(33, 16, 'Which of the following is affected most by extreme values (outliers)?', 'Mode', 'Median', 'Mean', 'Frequency', 'C'),
(34, 16, 'A student scored the following marks: 60, 65, 70, 75, 80. What is the mean?', '65', '70', '75', '80', 'B'),
(35, 16, 'What is the probability of selecting a red card from a standard deck of 52 cards?', '1/4', '1/2', '1/3', '1/52', 'B'),
(36, 16, 'If the frequency of a class is 10 and the class width is 5, what is the class interval?', '5–10', ' 0–5', 'Cannot be determined', '10', 'C'),
(37, 17, 'a a a  a a a aa a', 'dfg', 'dfg', 'fg', 'fg', 'A'),
(38, 18, '354,m5,m5m,56 5 6r 6tu6 u6u65', 'Volt', 'Aluminum', 'Ampere', 'Watt', 'A'),
(39, 18, '65e  66y 5y 5 5  e6 ', 'R875', 'R1,000', 'R1,225', 'R1,200', 'A'),
(40, 19, 'ewtry r gft gtfh h hd', 'Volt', 'Aluminum', 'Ampere', 'Watt', 'A'),
(41, 20, 'ewtry r gft gtfh h hd', 'Volt', 'Aluminum', 'Ampere', 'Watt', 'A'),
(42, 21, 'This is the first questionThis is the first question', 'Volt', 'Aluminum', 'Ampere', 'Watt', 'A'),
(43, 22, 'af grg g rgrg', 'Volt', 'Aluminum', 'Ampere', 'Watt', 'A'),
(44, 23, 'dsfg gtr re rg erfr', 'Volt', 'Aluminum', 'Ampere', 'Watt', 'A'),
(45, 24, 'sdfghyj', 'Volt', 'Aluminum', 'Ampere', 'Watt', 'A'),
(46, 25, 'sdfghyj', 'Volt', 'Aluminum', 'Ampere', 'Watt', 'A'),
(47, 26, 'jhjgukhlj lkj  fvf fdgd f rf', 'Volt', 'Aluminum', 'Ampere', 'Watt', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `practicequestions`
--

CREATE TABLE `practicequestions` (
  `Id` int(11) NOT NULL,
  `Text` text NOT NULL,
  `OptionA` varchar(255) NOT NULL,
  `OptionB` varchar(255) NOT NULL,
  `OptionC` varchar(255) NOT NULL,
  `OptionD` varchar(255) NOT NULL,
  `Answer` varchar(50) NOT NULL,
  `LevelId` int(11) NOT NULL,
  `Chapter` varchar(100) NOT NULL,
  `SubjectName` varchar(100) NOT NULL,
  `GradeName` varchar(50) NOT NULL,
  `ImagePath` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `practicequestions`
--

INSERT INTO `practicequestions` (`Id`, `Text`, `OptionA`, `OptionB`, `OptionC`, `OptionD`, `Answer`, `LevelId`, `Chapter`, `SubjectName`, `GradeName`, `ImagePath`) VALUES
(1, 'Question 1', '3w45', '345', 'dsf', 'ds', 'B', 1, 'Functions', 'Mathematics', 'Grade 8', NULL),
(2, 'Question 2', '25', '345', '26', '55', 'C', 1, 'Functions', 'Mathematics', 'Grade 8', NULL),
(3, 'Question 3', '13', '22', '24', '52', 'C', 1, 'Functions', 'Mathematics', 'Grade 8', NULL),
(4, 'Question 4', '13', '22', '24', '52', 'C', 1, 'Functions', 'Mathematics', 'Grade 8', NULL),
(5, 'Question 5', '13', '22', '24', '52', 'C', 1, 'Functions', 'Mathematics', 'Grade 8', NULL),
(6, 'Question 2', '13', '22', '242', '52', 'B', 1, 'Newtons', 'Physical Science', 'Grade 9', NULL),
(7, 'Question 5k', '13', '22', '24', '52', 'C', 1, 'Functions', 'Mathematics', 'Grade 8', NULL),
(8, 'Question 1', '13', '22', '24', '52', 'B', 1, 'Newtons', 'Physical Science', 'Grade 9', NULL),
(9, 'Question 5dfgh', '13', '22', '24', '52', 'C', 1, 'Functions', 'Mathematics', 'Grade 8', NULL),
(10, 'Hey there lets test the image upload', '13', '22', '24', '52', 'B', 1, 'Functions', 'Mathematics', 'Grade 8', 'uploads/practice_question_images/qimg_689b9203993856.50184252.jpg'),
(11, 'test image upload 2', '13', '22', '24', '52', 'B', 1, 'Functions', 'Mathematics', 'Grade 8', 'uploads/practice_question_images/qimg_689b9268a39326.82032723.jpg'),
(12, 'Question 1 for Trig vl', '13', '22', '24', '52', 'B', 2, 'Trigonometry', 'Mathematics', 'Grade 12', 'uploads/practice_question_images/qimg_689b985ad398a5.41456484.png'),
(13, 'Question 2 for Trig vl', '13', '22', '24', '52', 'C', 2, 'Trigonometry', 'Mathematics', 'Grade 12', NULL),
(14, 'The last ques for today testing', '13', '22', '24', '52', 'C', 1, 'Functions', 'Mathematics', 'Grade 8', NULL),
(15, 'question 333 3 3 3 3 33', '13', '22', '24', '52', 'C', 2, 'Trigonometry', 'Mathematics', 'Grade 12', NULL),
(16, 'Question 1', '13', '22', '24', '52', 'B', 1, 'Finances', 'Mathematics', 'Grade 12', NULL),
(17, 'Questions 11 2', '13', '22', '24', '52', 'C', 2, 'Finances', 'Mathematics', 'Grade 12', NULL),
(18, 'questions 23 23 23', '13', '22', '24', '52', 'A', 3, 'Finances', 'Mathematics', 'Grade 12', NULL),
(19, '<div class=\"box-header with-border\">rfg reg gregreg', '13', '22', '24', '52', 'B', 1, 'Trigonometry', 'Mathematics', 'Grade 12', NULL),
(20, 'e fee f ef ef fwef eewf eewf  qrwqd', '13', '22', '24', '52', 'B', 3, 'Trigonometry', 'Mathematics', 'Grade 12', NULL),
(21, 'sdfh erg r trhtrhtrh', '13', '22', '24', '52', 'C', 1, 'Functions', 'Mathematics', 'Grade 12', NULL),
(22, 'saf ger ger re ger', '13', '22', '24', '52', 'C', 2, 'Sequences & Series', 'Mathematics', 'Grade 12', NULL),
(23, 'dfs gd gdr rdg rdg rg rdg', '13', '22', '24', '52', 'B', 1, 'Finances', 'Mathematics', 'Grade 12', 'uploads/practice_question_images/qimg_689d3631234a34.26021899.png'),
(24, 'af f gr g rg rrrg', '13', '22', '24', '52', 'B', 1, 'Finances', 'Mathematics', 'Grade 12', NULL),
(25, 'A coin is tossed once. What is the probability of getting a head?', '1/2', '1/3', '1/4', '2/3', 'A', 1, 'Probability', 'Mathematics', 'Grade 12', 'uploads/practice_question_images/qimg_689d374c6127d5.16224093.png'),
(26, 'A dice is rolled. What is the probability of getting an even number?', '1/3', '1/2', '2/3', '1/6', 'B', 1, 'Probability', 'Mathematics', 'Grade 12', ''),
(27, 'A bag contains 3 red balls and 2 blue balls. A ball is picked at random. Probability that it is blue?', '2/5', '3/5', '1/2', '1/5', 'A', 1, 'Probability', 'Mathematics', 'Grade 12', ''),
(28, 'A coin is tossed twice. Probability of getting two tails?', '1/4', '1/2', '1/3', '3/4', 'A', 1, 'Probability', 'Mathematics', 'Grade 12', ''),
(29, 'A dice is rolled. Probability of getting a number greater than 4?', '1/3', '1/2', '1/6', '2/3', 'A', 1, 'Probability', 'Mathematics', 'Grade 12', ''),
(30, 'There are 5 cards numbered 1 to 5. One card is drawn at random. Probability it is less than 4?', '3/5', '2/5', '1/5', '4/5', 'A', 1, 'Probability', 'Mathematics', 'Grade 12', ''),
(31, 'A bag contains 4 white and 6 black balls. A ball is drawn randomly. Probability it is white?', '2/5', '3/5', '1/2', '4/10', 'A', 1, 'Probability', 'Mathematics', 'Grade 12', ''),
(32, 'A coin is tossed thrice. Probability of getting exactly one head?', '3/8', '1/8', '1/2', '1/4', 'A', 1, 'Probability', 'Mathematics', 'Grade 12', ''),
(33, 'A dice is rolled twice. Probability the sum is 7?', '1/6', '1/12', '1/8', '1/3', 'A', 1, 'Probability', 'Mathematics', 'Grade 12', ''),
(34, 'A bag contains 2 red, 3 green, 5 blue balls. One ball is picked at random. Probability it is red?', '1/5', '2/5', '1/2', '3/10', 'A', 1, 'Probability', 'Mathematics', 'Grade 12', ''),
(35, 'A bag contains 3 red balls, 2 green balls, and 5 blue balls. If one ball is drawn at random, what is the probability that it is green?', '1/5', '1/2', '2/10', '2/5', 'D', 2, 'Probability', 'Mathematics', 'Grade 10', NULL),
(36, 'A coin is tossed twice. What is the probability of getting exactly one head?', '1/4', '1/2', '3/4', '2/3', 'B', 2, 'Probability', 'Mathematics', 'Grade 10', NULL),
(37, 'A box contains 4 white and 6 black balls. Two balls are drawn without replacement. What is the probability that both are black?', '1/3', '3/5', '1/5', '1/15', 'C', 2, 'Probability', 'Mathematics', 'Grade 10', NULL),
(38, 'A die is rolled. What is the probability of getting a number greater than 4?', '1/3', '1/2', '2/3', '1/6', 'A', 2, 'Probability', 'Mathematics', 'Grade 10', NULL),
(39, 'Two coins are tossed simultaneously. What is the probability that at least one head appears?', '1/4', '1/2', '3/4', '1', 'C', 2, 'Probability', 'Mathematics', 'Grade 10', NULL),
(40, 'A bag contains 5 red, 3 green, and 2 blue balls. One ball is drawn at random. What is the probability that it is green?', '1/10', '3/10', '1/2', '3/5', 'B', 2, 'Probability', 'Mathematics', 'Grade 12', NULL),
(41, 'A coin is tossed three times. What is the probability of getting exactly two heads?', '1/8', '3/8', '1/2', '3/4', 'B', 2, 'Probability', 'Mathematics', 'Grade 12', NULL),
(42, 'A box contains 6 white and 4 black balls. Two balls are drawn without replacement. What is the probability that both are black?', '3/20', '1/5', '2/5', '1/2', 'A', 2, 'Probability', 'Mathematics', 'Grade 12', NULL),
(43, 'A die is rolled twice. What is the probability that the sum of numbers is 8?', '5/36', '1/6', '1/8', '1/12', 'A', 2, 'Probability', 'Mathematics', 'Grade 12', NULL),
(44, 'Two dice are thrown. What is the probability that at least one 6 appears?', '11/36', '25/36', '1/6', '1/36', 'A', 2, 'Probability', 'Mathematics', 'Grade 12', NULL),
(45, 'The function f(x) = 2x^2 - 3x + 1 has its vertex at:', '(3/4, -1/8)', '(3/4, -5/8)', '(1/2, 1)', '(2, -3)', 'B', 2, 'Functions', 'Mathematics', 'Grade 12', NULL),
(46, 'If f(x) = √(x+4), the domain of f(x) is:', 'x > -4', 'x ≥ -4', 'x ≤ -4', 'All real numbers', 'B', 2, 'Functions', 'Mathematics', 'Grade 12', NULL),
(47, 'The inverse of f(x) = 3x - 5 is:', 'f⁻¹(x) = (x+5)/3', 'f⁻¹(x) = (x-5)/3', 'f⁻¹(x) = 3x + 5', 'f⁻¹(x) = x/3 - 5', 'A', 2, 'Functions', 'Mathematics', 'Grade 12', NULL),
(48, 'For f(x) = x^2 and g(x) = 2x+1, the composition f(g(x)) is:', '(2x+1)^2', '4x^2 + 4x + 1', 'Both a and b', 'None of the above', 'C', 2, 'Functions', 'Mathematics', 'Grade 12', NULL),
(49, 'The range of f(x) = ln(x) is:', 'x ≥ 0', 'x > 0', 'All real numbers', 'x < 0', 'C', 2, 'Functions', 'Mathematics', 'Grade 12', NULL),
(50, 'If f(x) = 1/(x-2), the vertical asymptote is at:', 'x = 0', 'x = 1', 'x = 2', 'y = 2', 'C', 2, 'Functions', 'Mathematics', 'Grade 12', NULL),
(51, 'If f(x) = x^2 - 9, the x-intercepts are:', '(-3,0) and (3,0)', '(0,-9)', '(-9,0) and (9,0)', '(0,3) and (0,-3)', 'A', 2, 'Functions', 'Mathematics', 'Grade 12', NULL),
(52, 'The first three terms of a sequence are 2, 4, 6. What type of sequence is this?', 'Arithmetic', 'Geometric', 'Neither', 'Harmonic', 'A', 1, 'Sequences & Series', 'Mathematics', 'Grade 12', NULL),
(53, 'What is the common difference in the arithmetic sequence: 5, 8, 11, 14, ...?', '2', '3', '4', '5', 'B', 1, 'Sequences & Series', 'Mathematics', 'Grade 12', NULL),
(54, 'The sequence 3, 6, 12, 24, ... is:', 'Arithmetic with d=3', 'Arithmetic with d=6', 'Geometric with r=2', 'Geometric with r=3', 'C', 1, 'Sequences & Series', 'Mathematics', 'Grade 12', NULL),
(55, 'In an arithmetic sequence, the 1st term is 7 and the common difference is 5. What is the 4th term?', '17', '20', '22', '25', 'C', 1, 'Sequences & Series', 'Mathematics', 'Grade 12', NULL),
(56, 'The sum of the first 4 terms of the sequence 2, 4, 6, 8 is:', '16', '18', '20', '22', 'C', 1, 'Sequences & Series', 'Mathematics', 'Grade 12', NULL),
(57, 'The mode of the data set {2, 4, 4, 6, 8} is:', '2', '4', '6', '8', 'B', 1, 'Statistics', 'Mathematics', 'Grade 12', NULL),
(58, 'The median of {5, 7, 9, 11, 13} is:', '7', '9', '11', '13', 'B', 1, 'Statistics', 'Mathematics', 'Grade 12', NULL),
(59, 'The mean of {10, 20, 30, 40} is:', '20', '25', '30', '35', 'B', 1, 'Statistics', 'Mathematics', 'Grade 12', NULL),
(60, 'Which of the following best describes the range?', 'The middle value of data', 'The difference between largest and smallest value', 'The average of data', 'The most frequent value', 'B', 1, 'Statistics', 'Mathematics', 'Grade 12', NULL),
(61, 'The median of {1, 2, 3, 4, 5, 6} is:', '2.5', '3', '3.5', '4', 'C', 1, 'Statistics', 'Mathematics', 'Grade 12', NULL),
(62, 'The range of {15, 22, 8, 10, 30} is:', '15', '22', '20', '30', 'B', 1, 'Statistics', 'Mathematics', 'Grade 12', NULL),
(63, 'The mean of 5, 10, 15, 20 is:', '10', '12.5', '15', '20', 'B', 1, 'Statistics', 'Mathematics', 'Grade 12', NULL),
(64, 'Which of the following is not a measure of central tendency?', 'Mean', 'Mode', 'Variance', 'Median', 'C', 1, 'Statistics', 'Mathematics', 'Grade 12', NULL),
(65, 'If a number is added to each value in a data set, the mean will:', 'Stay the same', 'Increase by that number', 'Decrease by that number', 'Double', 'B', 1, 'Statistics', 'Mathematics', 'Grade 12', NULL),
(66, 'The most frequently occurring value in a data set is called the:', 'Mean', 'Median', 'Mode', 'Range', 'C', 1, 'Statistics', 'Mathematics', 'Grade 12', NULL),
(67, 'A student scored 50, 60, 70, 80, 90 in 5 tests. What is the variance?', '200', '250', '300', '400', 'A', 2, 'Statistics', 'Mathematics', 'Grade 12', NULL),
(68, 'In a set of 10 numbers, the mean is 12. What is the total sum of the numbers?', '100', '110', '120', '130', 'C', 2, 'Statistics', 'Mathematics', 'Grade 12', NULL),
(69, 'The probability of selecting a red ball from a box is 0.25. What is the probability of not selecting a red ball?', '0.25', '0.50', '0.75', '1', 'C', 2, 'Statistics', 'Mathematics', 'Grade 12', NULL),
(70, 'The mean deviation of {2, 4, 6, 8} about the mean is:', '1.5', '2', '2.5', '3', 'B', 2, 'Statistics', 'Mathematics', 'Grade 12', NULL),
(71, 'If the mean is 40 and variance is 25, the standard deviation is:', '5', '10', '15', '20', 'A', 2, 'Statistics', 'Mathematics', 'Grade 12', NULL),
(72, 'The probability of tossing a coin and getting heads is:', '0.25', '0.5', '0.75', '1', 'B', 2, 'Statistics', 'Mathematics', 'Grade 12', NULL),
(73, 'If P(A) = 0.6, P(B) = 0.5, and A, B are independent, then P(A ∩ B) = ?', '0.1', '0.2', '0.3', '0.4', 'C', 2, 'Statistics', 'Mathematics', 'Grade 12', NULL),
(74, 'A data set has mean 50 and standard deviation 5. A score of 55 has a z-score of:', '0.5', '1', '1.5', '2', 'B', 2, 'Statistics', 'Mathematics', 'Grade 12', NULL),
(75, 'The variance of the first 5 natural numbers is:', '2', '2.5', '3', '4', 'B', 2, 'Statistics', 'Mathematics', 'Grade 12', NULL),
(76, 'The probability of rolling a 4 on a fair die is:', '1/2', '1/3', '1/6', '1/4', 'C', 2, 'Statistics', 'Mathematics', 'Grade 12', NULL),
(77, 'The marks of 6 students are: 2, 4, 6, 8, 10, 12. Find the standard deviation.', '2.58', '3.42', '4.0', '5.0', 'B', 3, 'Statistics', 'Mathematics', 'Grade 12', NULL),
(78, 'In a binomial distribution with n=5 and p=0.4, what is the probability of exactly 2 successes?', '0.2304', '0.3456', '0.2592', '0.4100', 'B', 3, 'Statistics', 'Mathematics', 'Grade 12', NULL),
(79, 'The regression line of y on x is given by y = 2x + 5. If x=7, what is the predicted value of y?', '12', '14', '19', '21', 'C', 3, 'Statistics', 'Mathematics', 'Grade 12', NULL),
(80, 'If the correlation coefficient r = 0.8, then the coefficient of determination is:', '0.16', '0.36', '0.64', '0.80', 'C', 3, 'Statistics', 'Mathematics', 'Grade 12', NULL),
(81, 'In a normal distribution, about 95% of the data lies within how many standard deviations from the mean?', '1', '2', '3', '4', 'B', 3, 'Statistics', 'Mathematics', 'Grade 12', NULL),
(82, 'The probability density function of a uniform distribution on (0,1) is:', '1', '0.5', 'x', '1-x', 'A', 3, 'Statistics', 'Mathematics', 'Grade 12', NULL),
(83, 'If X ~ N(0,1), then P(-1 < X < 1) ≈ ?', '0.34', '0.50', '0.68', '0.95', 'C', 3, 'Statistics', 'Mathematics', 'Grade 12', NULL),
(84, 'The mean of a Poisson distribution is 3. What is its variance?', '1', '2', '3', '6', 'C', 3, 'Statistics', 'Mathematics', 'Grade 12', NULL),
(85, 'If the regression line is y = 5x + 2, what is the slope?', '2', '5', '7', '10', 'B', 3, 'Statistics', 'Mathematics', 'Grade 12', NULL),
(86, 'The skewness of a perfectly symmetrical distribution is:', '-1', '0', '1', 'Undefined', 'B', 3, 'Statistics', 'Mathematics', 'Grade 12', NULL),
(87, 'The derivative of f(x) = x² is:', '2x', 'x', 'x²', '2', 'A', 1, 'Calculus', 'Mathematics', 'Grade 12', NULL),
(88, 'The derivative of f(x) = 5x is:', '5', 'x', '0', '1', 'A', 1, 'Calculus', 'Mathematics', 'Grade 12', NULL),
(89, 'The integral of f(x) = 2x is:', 'x² + C', '2x² + C', 'ln(x) + C', '2x + C', 'A', 1, 'Calculus', 'Mathematics', 'Grade 12', NULL),
(90, 'The derivative of a constant (e.g., f(x) = 7) is:', '0', '7', '1', 'x', 'A', 1, 'Calculus', 'Mathematics', 'Grade 12', NULL),
(91, 'The integral of f(x) = 1 is:', 'x + C', '1/x + C', 'ln(x) + C', '0', 'A', 1, 'Calculus', 'Mathematics', 'Grade 12', NULL),
(92, 'Find dy/dx if y = 3x³ + 2x² - 5x.', '9x² + 4x - 5', '6x + 2', '3x² - 5', 'None of these', 'A', 2, 'Calculus', 'Mathematics', 'Grade 12', NULL),
(93, 'If f(x) = √x, then f’(x) = ?', '1/(2√x)', '√x', 'x²', '1/x', 'A', 2, 'Calculus', 'Mathematics', 'Grade 12', NULL),
(94, 'Evaluate ∫ (3x²) dx.', '3x³ + C', 'x³ + C', 'x² + C', '9x² + C', 'B', 2, 'Calculus', 'Mathematics', 'Grade 12', NULL),
(95, 'Find the slope of y = x² at x = 2.', '2', '3', '4', '5', 'C', 2, 'Calculus', 'Mathematics', 'Grade 12', NULL),
(96, 'The second derivative of y = x³ is:', '3x²', '6x', '9x²', '12x', 'B', 2, 'Calculus', 'Mathematics', 'Grade 12', NULL),
(97, 'Evaluate ∫ (1/x) dx.', '1/x + C', 'ln(x) + C', 'x + C', 'e^x + C', 'B', 3, 'Calculus', 'Mathematics', 'Grade 12', NULL),
(98, 'Find d/dx of y = e^x.', '1', 'x e^x', 'e^x', 'ln(x)', 'C', 3, 'Calculus', 'Mathematics', 'Grade 12', NULL),
(99, 'If y = ln(x²), then dy/dx = ?', '1/x²', '2/x', '1/x', '2ln(x)', 'B', 3, 'Calculus', 'Mathematics', 'Grade 12', NULL),
(100, 'Find ∫ (2x e^(x²)) dx.', 'e^(x²) + C', '2xe^(x²) + C', 'x²e^x + C', 'ln(x²) + C', 'A', 3, 'Calculus', 'Mathematics', 'Grade 12', NULL),
(101, 'If y = sin(x), then d²y/dx² = ?', '-sin(x)', 'cos(x)', '-cos(x)', 'sin(x)', 'A', 3, 'Calculus', 'Mathematics', 'Grade 12', NULL),
(102, 'The distance between the points (0,0) and (3,4) is:', '3', '4', '5', '7', 'C', 1, 'Analytical Geometry', 'Mathematics', 'Grade 12', NULL),
(103, 'The midpoint of (2,4) and (6,8) is:', '(4,6)', '(3,6)', '(5,7)', '(2,8)', 'A', 1, 'Analytical Geometry', 'Mathematics', 'Grade 12', NULL),
(104, 'The slope of the line through (1,2) and (3,6) is:', '2', '3', '4', '5', 'A', 1, 'Analytical Geometry', 'Mathematics', 'Grade 12', NULL),
(105, 'If the slope of a line is 0, the line is:', 'Vertical', 'Horizontal', 'Slanted', 'Undefined', 'B', 1, 'Analytical Geometry', 'Mathematics', 'Grade 12', NULL),
(106, 'Which equation represents a straight line?', 'y = 2x + 3', 'y = x² + 3', 'x² + y² = 25', 'y = √x', 'A', 1, 'Analytical Geometry', 'Mathematics', 'Grade 12', NULL),
(107, 'Find the distance between (1,2) and (4,6).', '3', '4', '5', '6', 'C', 2, 'Analytical Geometry', 'Mathematics', 'Grade 12', NULL),
(108, 'The equation of a line with slope 3 passing through (0,2) is:', 'y = 3x + 2', 'y = 2x + 3', 'y = 3x - 2', 'y = x + 3', 'A', 2, 'Analytical Geometry', 'Mathematics', 'Grade 12', NULL),
(109, 'The slope of a line perpendicular to y = 2x + 1 is:', '-1/2', '1/2', '-2', '2', 'A', 2, 'Analytical Geometry', 'Mathematics', 'Grade 12', NULL),
(110, 'The coordinates of the centroid of the triangle with vertices (0,0), (6,0), (0,6) are:', '(2,2)', '(3,3)', '(4,4)', '(6,6)', 'A', 2, 'Analytical Geometry', 'Mathematics', 'Grade 12', NULL),
(111, 'The equation of a circle with center (0,0) and radius 5 is:', 'x² + y² = 25', 'x² + y² = 5', '(x-5)² + y² = 0', 'x² + y² = √25', 'A', 2, 'Analytical Geometry', 'Mathematics', 'Grade 12', NULL),
(112, 'Find the equation of the line through (1,2) parallel to y = 2x + 3.', 'y = 2x + 1', 'y = 2x', 'y = 2x - 1', 'y = 2x + 2', 'B', 3, 'Analytical Geometry', 'Mathematics', 'Grade 12', NULL),
(113, 'Find the equation of the circle with center (2,3) and radius 4.', '(x-2)² + (y-3)² = 16', '(x+2)² + (y+3)² = 4', 'x² + y² = 16', '(x-4)² + (y-3)² = 2', 'A', 3, 'Analytical Geometry', 'Mathematics', 'Grade 12', NULL),
(114, 'The equation of the perpendicular bisector of the line joining (2,2) and (6,4) is:', 'y - 3 = -2(x - 4)', 'y - 3 = 2(x - 4)', 'y = 2x + 1', 'y = -1/2x + 3', 'A', 3, 'Analytical Geometry', 'Mathematics', 'Grade 12', NULL),
(115, 'If a line passes through (1,2) and has slope -3, its equation is:', 'y - 2 = -3(x - 1)', 'y = -3x + 1', 'y - 1 = -3(x - 2)', 'y = -3x - 2', 'A', 3, 'Analytical Geometry', 'Mathematics', 'Grade 12', NULL),
(116, 'The equation of a line with slope 2 and passing through (3,4) is: ggg', 'y - 4 = 2(x - 3)', 'y = 2x + 4', 'y - 3 = 2(x - 4)', 'y = 2x - 3', 'A', 3, 'Analytical Geometry', 'Mathematics', 'Grade 12', NULL);

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
-- Table structure for table `resourceassignments`
--

CREATE TABLE `resourceassignments` (
  `AssignmentID` int(11) NOT NULL,
  `ResourceID` int(11) NOT NULL,
  `ClassID` int(11) NOT NULL,
  `AssignedBy` int(11) NOT NULL,
  `AssignedAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resourceassignments`
--

INSERT INTO `resourceassignments` (`AssignmentID`, `ResourceID`, `ClassID`, `AssignedBy`, `AssignedAt`) VALUES
(8, 5, 21, 1, '2025-07-30 17:20:15'),
(9, 20, 23, 1, '2025-08-17 00:36:35');

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `ResourceID` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `FilePath` varchar(255) NOT NULL,
  `ResourceType` varchar(100) NOT NULL,
  `SubjectID` int(11) NOT NULL,
  `Grade` varchar(20) NOT NULL,
  `Description` text DEFAULT NULL,
  `Visibility` enum('public','private') NOT NULL DEFAULT 'private',
  `UploadedBy` int(11) NOT NULL,
  `UploadedAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`ResourceID`, `Title`, `FilePath`, `ResourceType`, `SubjectID`, `Grade`, `Description`, `Visibility`, `UploadedBy`, `UploadedAt`) VALUES
(4, 'First Vid', '688a0230dbc62_2025-07-28_18-49-40.mp4', 'Video', 1, '10', '', 'private', 1, '2025-07-30 13:29:52'),
(5, 'First Slides', '688a027a03901_g11_Trig_Rules.pptx', 'Slides', 1, '10', '', 'private', 1, '2025-07-30 13:31:06'),
(18, 'First ima', '688a2eb136f8c_7.png', 'image', 2, '11', '', 'private', 1, '2025-07-30 16:39:45'),
(19, 'Calculus P1', '688a51734a570_2025-01-25_00-26-52.mp4', 'video', 1, '10', '', 'private', 1, '2025-07-30 19:08:03'),
(20, 'Calculus P2', '688a51871b3ca_2025-01-25_00-26-52.mp4', 'video', 1, '10', '', 'private', 1, '2025-07-30 19:08:23'),
(21, 'Probability Memo', '688a534ca7dc1_MyJAVA_Notes.pdf', 'pdf', 1, '10', '', 'private', 1, '2025-07-30 19:15:56'),
(22, 'Finances Cheat Sheet', '688a5379c8ae6_Java_String_Class_Cheat_Sheet.pdf', 'pdf', 1, '10', '', 'private', 1, '2025-07-30 19:16:41'),
(23, 'Question 2 explanation', '688a543de744f_Childrens_World.mp3', 'audio', 1, '10', '', 'private', 1, '2025-07-30 19:19:57'),
(24, 'Question 7 explanation', '688a54ee2e410_There_Is_Still_Pain_Left_Laolu_Remix.mp3', 'audio', 1, '10', '', 'private', 1, '2025-07-30 19:22:54');

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `SchoolId` int(11) NOT NULL,
  `SchoolName` varchar(255) NOT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `ContactNumber` varchar(20) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schools`
--

INSERT INTO `schools` (`SchoolId`, `SchoolName`, `Address`, `ContactNumber`, `Email`, `CreatedAt`) VALUES
(4, 'The DoE', 'Terrace Road, Bertrams', '0795674125', 'distributorsdoe@gmail.com', '2025-08-08 13:58:08'),
(14, 'School 100', '', '', '', '2025-08-08 16:05:42'),
(15, 'School 200', '', '', '', '2025-08-11 09:11:04');

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
  `GradeId` int(11) NOT NULL,
  `SubjectName` varchar(100) NOT NULL,
  `MaxClassSize` int(11) DEFAULT 30,
  `ThreeMonthsPrice` decimal(10,2) DEFAULT NULL,
  `SixMonthsPrice` decimal(10,2) DEFAULT NULL,
  `TwelveMonthsPrice` decimal(10,2) DEFAULT NULL,
  `CreatedAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`SubjectId`, `GradeId`, `SubjectName`, `MaxClassSize`, `ThreeMonthsPrice`, `SixMonthsPrice`, `TwelveMonthsPrice`, `CreatedAt`) VALUES
(1, 1, 'Mathematics', 15, NULL, NULL, NULL, '2025-08-08 15:58:08'),
(2, 2, 'Mathematics', 30, NULL, NULL, NULL, '2025-08-08 15:58:08'),
(3, 3, 'Mathematics', 30, NULL, NULL, NULL, '2025-08-08 15:58:08'),
(4, 1, 'Physical Sciences', 30, NULL, NULL, NULL, '2025-08-08 15:58:08'),
(5, 2, 'Physical Sciences', 30, NULL, NULL, NULL, '2025-08-08 15:58:08'),
(6, 3, 'Physical Sciences', 30, NULL, NULL, NULL, '2025-08-08 15:58:08'),
(39, 33, 'Accounting', 30, NULL, NULL, NULL, '2025-08-08 18:05:42'),
(40, 34, 'Accounting', 30, NULL, NULL, NULL, '2025-08-08 18:05:42'),
(41, 33, 'Agricultural Sciences', 30, NULL, NULL, NULL, '2025-08-08 18:05:42'),
(42, 33, 'Business Studies', 30, NULL, NULL, NULL, '2025-08-08 18:05:42'),
(43, 35, 'Mathematics', 30, NULL, NULL, NULL, '2025-08-11 11:11:04'),
(44, 36, 'Mathematics', 30, NULL, NULL, NULL, '2025-08-11 11:11:04'),
(45, 35, 'Physical Sciences', 30, NULL, NULL, NULL, '2025-08-11 11:11:04'),
(46, 36, 'Physical Sciences', 30, NULL, NULL, NULL, '2025-08-11 11:11:04');

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
(7, 1, 'Work on resources pages for both the learner and the Tutors/Director', '2025-07-15 14:09:19', '2025-05-21 14:11:00', 'Low', 0, NULL, NULL, 'General'),
(8, 1, 'make overview.php dynamic', '2025-07-15 16:49:23', '2025-08-01 21:08:00', 'Low', 0, NULL, NULL, 'General'),
(9, 1, 'Work on the Main Sidebar color', '2025-07-16 10:11:52', '2025-08-09 22:11:00', 'Low', 0, NULL, NULL, 'General'),
(10, 1, 'Work on the feedback for the parents', '2025-07-16 18:14:44', '2025-05-20 17:06:00', 'Low', 0, NULL, NULL, 'General'),
(11, 1, 'work on the tutor perfomance button', '2025-07-19 19:47:46', '0000-00-00 00:00:00', 'Low', 0, NULL, NULL, 'General'),
(12, 1, 'Update the Direcor\'s activity overview page with that of a Tutor', '2025-07-19 19:49:45', '2025-08-02 12:59:00', 'Low', 0, NULL, NULL, 'General'),
(13, 1, 'Work on the Announcement modal, No mark as read', '2025-07-19 20:31:12', '2025-07-31 11:59:00', 'High', 0, NULL, NULL, 'General'),
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
(2, 'Malesela', 'Shirley', '$2y$10$t2VD2G8anvVZ/8IQ5f43RO9fq33OI38c8WU3gfNnn/EEHCy9rRL7G', 'Ms', 2048567858, NULL, 'shirley@gmail.com', 1, '', '2025-06-30 11:32:56', 'f9c5c52aee8b1fd0a72553256e2a425ef8aece59d855a99b710cc1fe0dbf0f52', '2025-06-30 11:32:56', 1),
(19, 'Boshielo', 'Emmanuel', '$2y$10$GbYLySVSA8qlvSfKzAWi1uFblIzipSMocSvyMKBf3.diQbqH.Re8a', 'Mr', 562065285, NULL, 'emahlwele055@gmail.com', 0, '', '2025-07-15 08:29:51', '9942c2923fa78adba6dc3f77cb829159', '2025-07-15 08:29:51', 1),
(20, 'Sandjon', 'Nicole', '$2y$10$LFVceoKvwMuku/4ldEDt3.E8A1yjkYGVU2qXU/QZCLVUU4kf8VV8m', 'Ms', 2147483647, NULL, 'nsandj@gmail.com', 0, '', '2025-07-15 08:34:57', '3d8397d11dcf5fefbef1f2f587121e90', '2025-07-15 08:34:57', 1),
(21, 'Mamogobo', 'Sydney', '$2y$10$iQBqDgcGuAqFWFWUQDJD6OczA1.eFSjB1/OPKuLWwTFY5nlq2NNYe', 'Mr', 728547485, NULL, 'mamogobo@gmail.com', 0, '', '2025-07-15 09:24:07', '0e6d551816c26c72bad0ab26cc87edbf', '2025-07-15 09:24:07', 1),
(24, 'Mbuyane', 'Sanele', '$2y$10$Hx025ygsL1ffOQXVaL0Sb.teIGUrL2JkT3WWtM2Xbs8fBJBMPf94q', 'Ms', 854285425, NULL, 'mbuyane@gmail.com', 0, '', '2025-07-19 12:31:58', 'c859b2dd65d4551d371314cc2c09670b', '2025-07-19 12:31:58', 1),
(25, 'Temp', 'Temp', '$2y$10$Gbi4R5wR6v85AdDAfXSwtuu8r8eXMTTDtKiYbJLU1/F0N38zDMgjO', 'Dr', 2147483647, NULL, 'doe@gmail.com', 0, '', '2025-07-19 14:51:08', '36a628e323d45abefaa4c10d8c0f3f59', '2025-07-19 14:51:08', 1),
(38, 'Solo', 'Duo', '$2y$10$LrQXVn75HkfXw6dVbQPOuOpRvar3eiQYlPADNFLnztnA5sZl9H4SC', 'Mr', 2147483647, NULL, 'solo@gmail.com', 1, '', '2025-07-24 09:21:51', '5ec27c5de6af4e97d96190f73b8365e2111ce347fef60eed3c6d544ce64b0fb0', '2025-07-24 09:21:51', 2),
(39, 'Scon', 'Gavi', '$2y$10$EPebr0WpwVhyJ6mss1t13.p3bfvYRyZ6x3TGk3g7zWTDT5mLfVr.u', 'Mr', 2147483647, NULL, 'msisdoh@gmail.com', 0, '', '2025-07-24 09:37:38', '82e232876381ba7fa7c1078680682f58bfbeddfee19bf31b1dbe11fa1b2f154d', '2025-07-24 09:37:38', 2),
(40, 'Rashford', 'Marcus', '$2y$10$nVPqVo9SjMSnFRNP0nuX0OUm8vLZ8tiNs4QekwUgR4CDBH66bwh82', 'Mr', 2147483647, NULL, 'rashfordd@gmail.com', 1, '', '2025-07-24 16:25:15', '2a619503334648455c93911d038192ddadde4d50c6cf18efd780c9ea6ca362e6', '2025-07-24 16:25:15', 2),
(41, 'Messi', 'Lionel', '$2y$10$w2nvS/m1i08UyzUrXmocceB8EFsBFZMMfs206nY/skDyhwWzYQSKy', 'Mr', 2147483647, NULL, 'messi@gmail.com', 1, '', '2025-07-24 16:28:41', '720a424e51ea92417b36ccf147a7c85ee226b4d1b059f6e5ce6db3e079de9982', '2025-07-24 16:28:41', 2),
(42, 'Iniesta', 'Andres', '$2y$10$1MYyb9oFQ7tO4dwxCqYGnuLe7w5p.kLrlr7SAyFD3KclVJ7u1RefC', 'Mr', 2147483647, NULL, 'iniesta@gmail.com', 1, '', '2025-07-24 16:29:36', '', '2025-07-24 16:29:36', 2),
(43, 'Hernandes', 'Xavi', '$2y$10$iY.9fqN95mBmUk0QITJvr.fko/LUswElagP3grD8L28muRUwzZqAi', 'Mr', 2147483647, NULL, 'Hernandes@gmail.com', 0, '', '2025-07-24 16:30:38', '43f3584ddb0b19b4a339c407b6efaacf14e38ce31dc5bece8f0edc2d0cc00419', '2025-07-24 16:30:38', 2),
(44, 'Ibrah', 'Zlatan', '$2y$10$UrbnJX6f1niQoTOdKB5yteEC/ckx.HU56skjtr1UtkMY8S8JZaMiu', 'Mr', 2147483647, NULL, 'ibrah@gmail.com', 1, '', '2025-07-24 16:31:39', '', '2025-07-24 16:31:39', 2),
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
(58, 'Pierce', 'Alexander', '$2y$10$k5peCRhgnOMM706SpYdyBeEi2UMIE7icxKFyWccnfTNAiE9al6vkq', 'Mr', 2147483647, NULL, 'pierce@gmail.com', 0, '', '2025-07-24 17:20:10', 'e3661c1b3f8684f54c7b40b4760a9e2a', '2025-07-24 17:20:10', 1),
(59, 'wswsw', 'wswswsw', '$2y$10$pen8U65c24d2/RvKFc4d5e0O24OrxqML5DvDN28Ha1sbxVsZGvMRe', 'Mr', 2147483647, NULL, 'wsww@gmail.com', 0, '', '2025-07-24 17:21:20', 'dca23cd7890ea2d70fb61b839452a30d', '2025-07-24 17:21:20', 1),
(60, 'Modric', 'Luka', '$2y$10$0Ppb3fkKbruRd8xcJq9i6OOdQDsPuRGc181w8nSxex3pv4zGxyKby', 'Mr', 215484521, NULL, 'emahlwele05@gmail.com', 1, '$2y$10$imVKMV2GELte5V1DPfZWse0blNWBEuGXUKa9tv672MZ2XsGG7RVw2', '2025-07-28 08:34:18', '', '2025-07-27 14:52:24', 2),
(61, 'Kante', 'Ngolo', '$2y$10$W9KRqeAh5E0pSYzvCWNbpurjA5Y77RMb2CAAM.7Ls6G0CcpAzP0lW', 'Mr', 215455454, NULL, 'kante@gmail.com', 0, '', '2025-08-04 15:08:20', '681e151f7b4b20d5f68b7a764874b520cdea43d42166814f6ab32a06e73eec0f', '2025-08-04 15:08:20', 2);

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
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`GradeId`),
  ADD KEY `SchoolId` (`SchoolId`);

--
-- Indexes for table `inviterequests`
--
ALTER TABLE `inviterequests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invitetokens`
--
ALTER TABLE `invitetokens`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Token` (`Token`),
  ADD KEY `InviteRequestId` (`InviteRequestId`);

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
-- Indexes for table `learnerlevel`
--
ALTER TABLE `learnerlevel`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `LearnerId` (`LearnerId`),
  ADD KEY `LevelId` (`LevelId`);

--
-- Indexes for table `learnerpracticequestions`
--
ALTER TABLE `learnerpracticequestions`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `LearnerId` (`LearnerId`),
  ADD KEY `QuestionId` (`QuestionId`);

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
-- Indexes for table `level`
--
ALTER TABLE `level`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `memos`
--
ALTER TABLE `memos`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `unique_memo` (`SubjectName`,`GradeName`,`LevelName`,`Chapter`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`NoticeNo`),
  ADD KEY `CreatedBy` (`CreatedBy`);

--
-- Indexes for table `oldsubjects`
--
ALTER TABLE `oldsubjects`
  ADD PRIMARY KEY (`SubjectId`);

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
-- Indexes for table `practicequestions`
--
ALTER TABLE `practicequestions`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `LevelId` (`LevelId`);

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
-- Indexes for table `resourceassignments`
--
ALTER TABLE `resourceassignments`
  ADD PRIMARY KEY (`AssignmentID`),
  ADD KEY `ResourceID` (`ResourceID`),
  ADD KEY `ClassID` (`ClassID`),
  ADD KEY `AssignedBy` (`AssignedBy`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`ResourceID`),
  ADD KEY `SubjectID` (`SubjectID`),
  ADD KEY `UploadedBy` (`UploadedBy`);

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`SchoolId`);

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
  ADD KEY `GradeId` (`GradeId`);

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
  MODIFY `ActivityId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `ClassID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

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
  MODIFY `FinanceId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `GradeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `inviterequests`
--
ALTER TABLE `inviterequests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `invitetokens`
--
ALTER TABLE `invitetokens`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `learneractivitymarks`
--
ALTER TABLE `learneractivitymarks`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `learneranswers`
--
ALTER TABLE `learneranswers`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT for table `learnerclasses`
--
ALTER TABLE `learnerclasses`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `learnerhomeworkresults`
--
ALTER TABLE `learnerhomeworkresults`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `learnerlevel`
--
ALTER TABLE `learnerlevel`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `learnerpracticequestions`
--
ALTER TABLE `learnerpracticequestions`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=263;

--
-- AUTO_INCREMENT for table `learners`
--
ALTER TABLE `learners`
  MODIFY `LearnerId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `learnersubject`
--
ALTER TABLE `learnersubject`
  MODIFY `LearnerSubjectId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `level`
--
ALTER TABLE `level`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `memos`
--
ALTER TABLE `memos`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `NoticeNo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `oldsubjects`
--
ALTER TABLE `oldsubjects`
  MODIFY `SubjectId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `onlineactivities`
--
ALTER TABLE `onlineactivities`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `onlinequestions`
--
ALTER TABLE `onlinequestions`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `practicequestions`
--
ALTER TABLE `practicequestions`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

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
-- AUTO_INCREMENT for table `resourceassignments`
--
ALTER TABLE `resourceassignments`
  MODIFY `AssignmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `ResourceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `SchoolId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `subjectnotices`
--
ALTER TABLE `subjectnotices`
  MODIFY `NoticeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `SubjectId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

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
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

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
  ADD CONSTRAINT `fk_activities_subject` FOREIGN KEY (`SubjectId`) REFERENCES `subjects` (`SubjectId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`SubjectID`) REFERENCES `oldsubjects` (`SubjectId`),
  ADD CONSTRAINT `classes_ibfk_2` FOREIGN KEY (`TutorID`) REFERENCES `tutors` (`TutorId`);

--
-- Constraints for table `directorsubjects`
--
ALTER TABLE `directorsubjects`
  ADD CONSTRAINT `directorsubjects_ibfk_1` FOREIGN KEY (`DirectorId`) REFERENCES `users` (`Id`),
  ADD CONSTRAINT `directorsubjects_ibfk_2` FOREIGN KEY (`SubjectId`) REFERENCES `oldsubjects` (`SubjectId`);

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
-- Constraints for table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`SchoolId`) REFERENCES `schools` (`SchoolId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `invitetokens`
--
ALTER TABLE `invitetokens`
  ADD CONSTRAINT `invitetokens_ibfk_1` FOREIGN KEY (`InviteRequestId`) REFERENCES `inviterequests` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `learnerlevel`
--
ALTER TABLE `learnerlevel`
  ADD CONSTRAINT `learnerlevel_ibfk_1` FOREIGN KEY (`LearnerId`) REFERENCES `learners` (`LearnerId`),
  ADD CONSTRAINT `learnerlevel_ibfk_2` FOREIGN KEY (`LevelId`) REFERENCES `level` (`Id`);

--
-- Constraints for table `learnerpracticequestions`
--
ALTER TABLE `learnerpracticequestions`
  ADD CONSTRAINT `learnerpracticequestions_ibfk_1` FOREIGN KEY (`LearnerId`) REFERENCES `learners` (`LearnerId`),
  ADD CONSTRAINT `learnerpracticequestions_ibfk_2` FOREIGN KEY (`QuestionId`) REFERENCES `practicequestions` (`Id`);

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
  ADD CONSTRAINT `learnersubject_ibfk_2` FOREIGN KEY (`SubjectId`) REFERENCES `oldsubjects` (`SubjectId`);

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
-- Constraints for table `practicequestions`
--
ALTER TABLE `practicequestions`
  ADD CONSTRAINT `practicequestions_ibfk_1` FOREIGN KEY (`LevelId`) REFERENCES `level` (`Id`);

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`TutorId`) REFERENCES `users` (`Id`),
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`LearnerId`) REFERENCES `users` (`Id`);

--
-- Constraints for table `resourceassignments`
--
ALTER TABLE `resourceassignments`
  ADD CONSTRAINT `resourceassignments_ibfk_1` FOREIGN KEY (`ResourceID`) REFERENCES `resources` (`ResourceID`) ON DELETE CASCADE,
  ADD CONSTRAINT `resourceassignments_ibfk_2` FOREIGN KEY (`ClassID`) REFERENCES `classes` (`ClassID`) ON DELETE CASCADE,
  ADD CONSTRAINT `resourceassignments_ibfk_3` FOREIGN KEY (`AssignedBy`) REFERENCES `users` (`Id`);

--
-- Constraints for table `resources`
--
ALTER TABLE `resources`
  ADD CONSTRAINT `resources_ibfk_1` FOREIGN KEY (`SubjectID`) REFERENCES `oldsubjects` (`SubjectId`),
  ADD CONSTRAINT `resources_ibfk_2` FOREIGN KEY (`UploadedBy`) REFERENCES `users` (`Id`);

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`GradeId`) REFERENCES `grades` (`GradeId`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `tutorsubject_ibfk_2` FOREIGN KEY (`SubjectId`) REFERENCES `oldsubjects` (`SubjectId`);

--
-- Constraints for table `usersubject`
--
ALTER TABLE `usersubject`
  ADD CONSTRAINT `usersubject_ibfk_1` FOREIGN KEY (`UserId`) REFERENCES `users` (`Id`),
  ADD CONSTRAINT `usersubject_ibfk_2` FOREIGN KEY (`SubjectId`) REFERENCES `oldsubjects` (`SubjectId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
