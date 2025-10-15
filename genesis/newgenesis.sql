-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 14, 2025 at 12:55 PM
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
  `Grade` varchar(10) NOT NULL,
  `ChapterName` varchar(50) NOT NULL,
  `GroupName` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`ActivityId`, `ActivityName`, `SubjectId`, `ActivityDate`, `MaxMarks`, `Creator`, `Grade`, `ChapterName`, `GroupName`) VALUES
(22, 'activity 1', 1, '2025-09-10 20:46:33', 25.00, 'Director', 'Grade 10', 'Statistics', 'B'),
(23, 'activity 1', 1, '2025-09-10 20:55:14', 25.00, 'Director', 'Grade 10', 'Statistics', 'B'),
(24, 'activity 1', 1, '2025-09-24 16:46:45', 20.00, 'Director', '1', 'Functions', 'Indiv'),
(25, 'activity 2', 1, '2025-09-24 16:45:14', 20.00, 'Director', '1', 'Functions', 'Indiv'),
(26, 'activity 1', 4, '2025-09-27 12:31:15', 25.00, 'Director', 'Grade 10', 'Organic Chemistry25', 'A'),
(27, 'activity 1', 1, '2025-09-27 12:35:04', 25.00, 'Director', 'Grade 10', 'Functions', 'B'),
(28, 'activity 1', 4, '2025-09-27 13:05:05', 25.00, 'Director', 'Grade 10', 'Functions', 'A'),
(29, 'activity 1', 4, '2025-09-28 19:05:43', 25.00, 'Malesela', 'Grade 10', 'Functions', 'B');

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
(68, 1, 'Grade 10', 'A', 1, 2, 'Not Full', '2025-10-09 17:20:20'),
(69, 4, 'Grade 10', 'A', 1, 24, 'Not Full', '2025-10-09 17:20:20'),
(70, 3, 'Grade 12', 'A', 1, 2, 'Not Full', '2025-10-09 17:38:12'),
(71, 6, 'Grade 12', 'A', 1, 2, 'Not Full', '2025-10-09 17:38:14');

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
  `LearnerId` int(11) NOT NULL,
  `TotalFees` decimal(10,2) NOT NULL DEFAULT 0.00,
  `TotalPaid` decimal(10,2) NOT NULL DEFAULT 0.00,
  `Balance` decimal(10,2) GENERATED ALWAYS AS (`TotalFees` - `TotalPaid`) STORED,
  `PaymentStatus` enum('Unpaid','Partial','Paid','Overdue') DEFAULT 'Unpaid',
  `DueDate` date DEFAULT NULL,
  `LastPaymentDate` datetime DEFAULT NULL,
  `Notes` text DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `LastReminderSent` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `finances`
--

INSERT INTO `finances` (`FinanceId`, `LearnerId`, `TotalFees`, `TotalPaid`, `PaymentStatus`, `DueDate`, `LastPaymentDate`, `Notes`, `CreatedAt`, `UpdatedAt`, `LastReminderSent`) VALUES
(38, 105, 1800.00, 1200.00, 'Unpaid', NULL, '2025-09-29 13:31:20', NULL, '2025-09-05 18:06:00', '2025-09-29 11:31:20', '2025-09-29 13:22:00'),
(39, 106, 1200.00, 1500.00, 'Unpaid', NULL, '2025-09-06 11:52:48', NULL, '2025-09-05 18:06:59', '2025-09-06 09:52:48', NULL),
(40, 107, 600.00, 200.00, 'Unpaid', NULL, '2025-09-05 22:03:26', NULL, '2025-09-05 18:07:57', '2025-09-06 08:54:18', NULL),
(41, 108, 900.00, 0.00, 'Unpaid', NULL, NULL, NULL, '2025-09-05 18:09:09', '2025-09-29 11:22:14', '2025-09-29 13:22:14'),
(42, 109, 4200.00, 4000.00, 'Unpaid', NULL, '2025-09-29 13:30:13', NULL, '2025-09-05 18:10:08', '2025-09-29 11:30:13', '2025-09-29 13:22:22'),
(43, 110, 1500.00, 1200.00, 'Unpaid', NULL, '2025-09-29 13:30:58', NULL, '2025-09-05 18:11:06', '2025-09-29 11:30:58', '2025-09-29 13:22:38'),
(44, 111, 600.00, 600.00, 'Unpaid', NULL, '2025-09-29 13:29:53', NULL, '2025-09-05 18:15:06', '2025-09-29 11:29:53', '2025-09-29 13:22:52'),
(46, 112, 1100.00, 0.00, 'Unpaid', NULL, NULL, NULL, '2025-09-05 18:44:03', '2025-09-29 11:23:00', '2025-09-29 13:23:00'),
(72, 113, 600.00, 750.00, 'Unpaid', NULL, '2025-09-06 11:44:56', NULL, '2025-09-06 09:25:35', '2025-09-06 09:44:56', NULL),
(74, 114, 755.00, 0.00, 'Unpaid', NULL, NULL, NULL, '2025-09-06 16:23:49', '2025-09-29 11:23:15', '2025-09-29 13:23:15'),
(75, 115, 1500.00, 520.00, 'Unpaid', NULL, '2025-09-29 12:48:44', NULL, '2025-09-06 18:38:02', '2025-09-29 10:48:44', NULL),
(81, 121, 300.00, 150.00, 'Unpaid', NULL, '2025-09-29 12:49:40', NULL, '2025-09-07 13:07:50', '2025-09-29 10:49:40', NULL),
(82, 122, 300.00, 0.00, 'Unpaid', NULL, NULL, NULL, '2025-09-22 08:44:10', '2025-09-29 11:23:26', '2025-09-29 13:23:26'),
(84, 124, 600.00, 0.00, 'Unpaid', NULL, NULL, NULL, '2025-09-22 09:17:59', '2025-09-29 11:23:40', '2025-09-29 13:23:40'),
(85, 125, 1800.00, 1000.00, 'Unpaid', NULL, '2025-09-29 13:30:35', NULL, '2025-09-22 09:38:50', '2025-09-29 11:30:35', NULL),
(86, 126, 600.00, 0.00, 'Unpaid', NULL, NULL, NULL, '2025-09-22 10:06:45', '2025-09-22 10:06:45', NULL),
(98, 130, 900.00, 0.00, 'Unpaid', NULL, NULL, NULL, '2025-10-09 17:20:21', '2025-10-09 17:20:21', NULL),
(99, 131, 600.00, 0.00, 'Unpaid', NULL, NULL, NULL, '2025-10-09 17:38:14', '2025-10-09 17:38:14', NULL);

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
(37, 16, 'Grade 8', '2025-09-21 14:02:58');

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
(21, 'Walter', 'Jones', 'thedistributorsofedu@gmail.com', '', '2025-09-22 08:37:28', 0),
(22, 'DOE', 'DoE2018', 'distributorsdoe@gmail.com', '', '2025-09-22 08:38:29', 1),
(24, 'Reneilwe', 'Letsholonyane', 'emahlwele05@gmail.com', '', '2025-09-22 09:35:09', 1);

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
(21, 24, '5ed8d9c7874a51cdd903d895738404ff62f833818ce707bc3be1d6222efffd74', 'emahlwele05@gmail.com', 0, '2025-09-22 11:36:00', '2025-09-29 11:36:00'),
(22, 22, '830ae63eea008090f879168e68299c6b14bbd018c19b499907ecf2239b5b7bf6', 'distributorsdoe@gmail.com', 0, '2025-09-22 12:05:33', '2025-09-29 12:05:33'),
(23, 22, '6bcce8a15fa4fe571ffc54db2bc5d433dc6587cca57592477ee1d5305095a29a', 'distributorsdoe@gmail.com', 0, '2025-09-23 16:05:24', '2025-09-30 16:05:24'),
(24, 24, '54390ca133a59bc6a56284d30cd28300427fe7b1b2ff3f282069e4420e552a86', 'emahlwele05@gmail.com', 0, '2025-10-09 19:28:08', '2025-10-16 19:28:08');

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
(59, 110, 22, 1, 19, '2025-09-09 22:00:00', 'present', 'None', 'Yes', 'None'),
(60, 111, 22, 1, 18, '2025-09-09 22:00:00', 'present', 'None', 'Yes', 'None'),
(61, 110, 23, 1, 10, '2025-09-09 22:00:00', 'present', 'None', 'Yes', 'None'),
(62, 111, 23, 1, 5, '2025-09-09 22:00:00', 'present', 'None', 'Yes', 'None'),
(63, 106, 24, 1, 15, '2025-09-23 22:00:00', 'present', 'None', 'Yes', 'None'),
(64, 106, 25, 1, 19, '2025-09-23 22:00:00', 'present', 'None', 'Yes', 'None'),
(65, 110, 27, 1, 20, '2025-09-26 22:00:00', 'present', 'None', 'Yes', 'None'),
(66, 111, 27, 1, 21, '2025-09-26 22:00:00', 'present', 'None', 'Yes', 'None');

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

-- --------------------------------------------------------

--
-- Table structure for table `learnerclasses`
--

CREATE TABLE `learnerclasses` (
  `Id` int(11) NOT NULL,
  `LearnerID` int(11) NOT NULL,
  `ClassID` int(11) NOT NULL,
  `AssignedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `EnrollmentStatus` enum('Active','Deregistered') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `learnerclasses`
--

INSERT INTO `learnerclasses` (`Id`, `LearnerID`, `ClassID`, `AssignedAt`, `EnrollmentStatus`) VALUES
(147, 130, 68, '2025-10-09 17:20:20', 'Active'),
(148, 130, 69, '2025-10-09 17:20:20', 'Active'),
(149, 131, 70, '2025-10-09 17:38:13', 'Active'),
(150, 131, 71, '2025-10-09 17:38:14', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `learnerclasshistory`
--

CREATE TABLE `learnerclasshistory` (
  `HistoryID` int(11) NOT NULL,
  `LearnerID` int(11) NOT NULL,
  `ClassID` int(11) NOT NULL,
  `SubjectId` int(11) NOT NULL,
  `GroupName` varchar(100) NOT NULL,
  `LeftAt` datetime NOT NULL DEFAULT current_timestamp(),
  `Reason` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `learnerclasshistory`
--

INSERT INTO `learnerclasshistory` (`HistoryID`, `LearnerID`, `ClassID`, `SubjectId`, `GroupName`, `LeftAt`, `Reason`) VALUES
(1, 108, 57, 1, 'A', '2025-09-28 20:36:13', 'Cancelled'),
(2, 110, 59, 1, 'B', '2025-09-28 20:37:53', 'Completed'),
(3, 111, 67, 4, 'B', '2025-09-28 20:39:18', 'Completed'),
(4, 111, 59, 1, 'B', '2025-09-28 20:42:51', 'Cancelled');

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

-- --------------------------------------------------------

--
-- Table structure for table `learneronlineactivities`
--

CREATE TABLE `learneronlineactivities` (
  `LearnerOnlineActivityId` int(11) NOT NULL,
  `LearnerId` int(11) NOT NULL,
  `OnlineActivityId` int(11) NOT NULL,
  `IsCompleted` tinyint(1) DEFAULT 0,
  `Score` int(11) DEFAULT NULL,
  `CompletedAt` datetime DEFAULT NULL,
  `Feedback` text DEFAULT NULL,
  `LastUpdated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

INSERT INTO `learners` (`LearnerId`, `Grade`, `RegistrationDate`, `LearnerKnockoffTime`, `ParentTitle`, `ParentName`, `ParentSurname`, `ParentEmail`, `ParentContactNumber`, `LastUpdated`) VALUES
(130, 'Grade 10', NULL, '07:12:00', 'Mr', 'EmmanuelParent', 'BoshieloParent', 'emahlwele05@gmail.com', '0875486468', NULL),
(131, 'Grade 12', NULL, '07:35:00', 'Dr', 'Emmanuel', 'Boshielo', 'emahlwele05@gmail.com', '0875486468', NULL),
(133, 'Grade 11', NULL, '08:09:00', 'Mr', 'TylerParent', 'JosephsParent', 'emahl@gmail.com', '0875486468', NULL);

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
  `ContractStartDate` date DEFAULT NULL,
  `ContractExpiryDate` datetime DEFAULT NULL,
  `ContractFee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `DiscountAmount` decimal(10,2) DEFAULT 0.00,
  `Status` enum('Active','Suspended','Completed','Cancelled') DEFAULT 'Active',
  `LastReminded` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `learnersubject`
--

INSERT INTO `learnersubject` (`LearnerSubjectId`, `LearnerId`, `SubjectId`, `TargetLevel`, `CurrentLevel`, `NumberOfTerms`, `ContractStartDate`, `ContractExpiryDate`, `ContractFee`, `DiscountAmount`, `Status`, `LastReminded`) VALUES
(159, 130, 1, 7, 1, 3, '2025-10-09', '2026-01-07 00:00:00', 300.00, NULL, 'Active', NULL),
(160, 130, 4, 7, 1, 6, '2025-10-09', '2026-04-07 00:00:00', 600.00, NULL, 'Active', NULL),
(161, 131, 3, 7, 1, 3, '2025-10-09', '2026-01-07 00:00:00', 300.00, NULL, 'Active', NULL),
(162, 131, 6, 7, 1, 3, '2025-10-09', '2026-01-07 00:00:00', 300.00, NULL, 'Active', NULL),
(164, 133, 2, 7, 1, 6, '2025-10-09', '2026-04-07 00:00:00', 600.00, NULL, 'Active', NULL);

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
(1, 'Welcome Back to Term 3', 'Dear learners and tutors, Term 3 has officially started. Please check your schedules and submit all pending assignments on time.', '2025-07-09 20:38:47', NULL, 0, 1, 12),
(2, 'System Maintenance Notification', 'The system will be down for maintenance on Saturday from 10 PM to 2 AM. Please save your work accordingly.', '2025-07-09 20:38:47', '2025-09-20', 0, 2, 12),
(4, 'Title', 'This is the first nitice from the form', '2025-07-09 20:56:09', NULL, 0, 1, 12),
(5, 'Mid-Year Exams Preparation', 'Dear Learners, please begin preparing for your mid-year exams scheduled for next month. Study guides have been uploaded.', '2025-07-09 21:08:43', NULL, 0, 1, 1),
(6, 'New Resources Available', 'New Maths and Science videos are now available in your Resources tab.', '2025-07-09 21:08:44', NULL, 0, 1, 1),
(7, 'Friday Q&A Session', 'Join our live Q&A session this Friday at 4PM for help with your homework and recent topics.', '2025-07-09 21:08:44', NULL, 0, 1, 1),
(8, 'Mark Submission Reminder', 'Tutors, please submit all learner marks for the week by Friday 17:00.', '2025-07-09 21:08:44', NULL, 0, 1, 2),
(9, 'Mandatory Tutor Meeting', 'All tutors are required to attend an online meeting this Thursday at 18:00 to discuss Term 3 planning.', '2025-07-09 21:08:44', NULL, 0, 1, 2),
(10, 'New Notice after updates', 'asdasd sfs fds ds fdsf df dfd fdfsdf sdfds fdsf', '2025-07-25 09:49:14', '2025-10-11', 0, 1, 12),
(11, 'Second Notice after updates', 'sssa s dsdf gd dg', '2025-07-25 09:51:14', '2025-08-08', 0, 1, 12),
(12, 'Second Notice after updates', 'sssa s dsdf gd dg', '2025-07-25 09:51:28', '2025-08-08', 0, 1, 12),
(13, '22222222222222', '3333333333333', '2025-07-25 10:20:19', '2025-08-09', 0, 1, 1),
(14, 'Emmanuel Emmanuel', '\"Exam Timetable Updated - Please download the latest PDF from the resources page.\"', '2025-08-11 00:16:04', '2025-08-30', 0, 1, 12),
(15, 'Title d f d f', 'ef w few ew few', '2025-08-20 23:01:46', '2025-08-30', 0, 1, 12),
(16, 'Title 444s', 'r6ftgyuhijkol gbuhnjkml', '2025-09-03 18:10:09', '2025-09-27', 0, 1, 12),
(17, 'Hey there 2', 'g re re re erfreg', '2025-09-24 16:37:00', '2025-10-03', 0, 1, 12);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `NotificationId` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Content` text NOT NULL,
  `SubjectName` varchar(100) DEFAULT NULL,
  `Grade` varchar(50) DEFAULT NULL,
  `CreatedBy` int(11) NOT NULL,
  `CreatedFor` tinyint(4) NOT NULL COMMENT '1 = Learners, 2 = Tutors, 12 = Both',
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `ExpiryDate` date DEFAULT NULL,
  `IsAutomatic` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 = manual, 1 = auto-generated',
  `Link` varchar(255) DEFAULT NULL,
  `Priority` tinyint(4) DEFAULT 2 COMMENT '1 = high, 2 = medium, 3 = low',
  `NotificationType` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`NotificationId`, `Title`, `Content`, `SubjectName`, `Grade`, `CreatedBy`, `CreatedFor`, `CreatedAt`, `ExpiryDate`, `IsAutomatic`, `Link`, `Priority`, `NotificationType`) VALUES
(1, 'The first manual notification', 'This is the first manual notification which im using for testing.', NULL, NULL, 1, 12, '2025-08-20 23:13:57', '2025-11-08', 0, NULL, 0, NULL),
(2, 'The Second manual notification', 'This is the first manual notification which im using for testing.', NULL, NULL, 1, 12, '2025-08-20 23:24:32', '2025-11-08', 0, NULL, 0, NULL),
(3, 'Welcome Back!', 'We hope you are ready for a productive term! Check out the latest resources in your dashboard.', NULL, NULL, 1, 1, '2025-08-20 23:27:31', '2025-09-19', 0, NULL, 2, NULL),
(4, 'System Maintenance', 'The platform will undergo maintenance this weekend. Expect brief downtime.', NULL, NULL, 1, 12, '2025-08-20 23:27:34', '2025-08-27', 0, NULL, 2, NULL),
(5, 'Title 444s', 'x x  xxxx x x x', NULL, NULL, 1, 12, '2025-09-03 18:09:38', '2025-09-27', 0, NULL, 0, NULL),
(6, 'Hey There Title', 'ef ef erg er re reg', NULL, NULL, 1, 12, '2025-09-24 16:36:14', '2025-10-10', 0, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `onlineactivities`
--

CREATE TABLE `onlineactivities` (
  `Id` int(11) NOT NULL,
  `TutorId` int(11) NOT NULL,
  `SubjectId` int(11) NOT NULL,
  `Grade` varchar(20) NOT NULL,
  `Topic` varchar(100) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Instructions` text DEFAULT NULL,
  `TotalMarks` int(11) NOT NULL,
  `CreatedAt` datetime DEFAULT current_timestamp(),
  `ImagePath` varchar(255) DEFAULT NULL,
  `LastFeedbackSent` datetime DEFAULT NULL,
  `MemoPath` varchar(255) DEFAULT NULL,
  `GroupName` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `onlineactivities`
--

INSERT INTO `onlineactivities` (`Id`, `TutorId`, `SubjectId`, `Grade`, `Topic`, `Title`, `Instructions`, `TotalMarks`, `CreatedAt`, `ImagePath`, `LastFeedbackSent`, `MemoPath`, `GroupName`) VALUES
(47, 1, 3, 'Grade 12', 'Stati Stati', 'Quiz 10000020000 11dd', '3 3 3 3 3 3 3 3 444 4 4 4 4', 5, '2025-08-22 19:48:27', 'uploads/images/activity_47_1755890044.png', NULL, NULL, 'A'),
(49, 1, 1, 'Grade 10', 'Stati Stati', '77777777666666newly', '', 1, '2025-09-03 18:28:33', NULL, '2025-09-26 00:09:47', NULL, 'A'),
(50, 1, 1, 'Grade 10', 'Sequences & Series', 'Quiz zxzxzxzzxzx', 'hhgf hf jh jh jh hj jg gj jhj h hj hj hj', 1, '2025-09-08 22:35:46', NULL, NULL, NULL, 'A'),
(51, 1, 1, 'Grade 10', 'Finances', 'Quiz Class B', '', 1, '2025-09-08 22:40:42', NULL, '2025-09-26 00:05:43', NULL, 'B'),
(52, 1, 3, 'Grade 12', 'Finances  for 12', 'Quiz Class A', 'erth', 1, '2025-09-08 23:05:57', NULL, NULL, NULL, 'A'),
(53, 1, 1, 'Grade 10', 'Analytical  for 10', 'tghjkm', '', 1, '2025-09-09 14:23:27', NULL, NULL, NULL, 'A'),
(54, 1, 1, 'Grade 10', 'Analytical  for 10', 'tghjkm', 'This isthd d', 1, '2025-09-09 14:24:37', '../uploads/1757420677_2024-removebg-preview.png', NULL, '../uploads/memos/1757420677_Diesel Mech N4.pdf', 'A'),
(55, 1, 1, 'Grade 10', 'Analytical Geo  for 10', 'dfd rf er', '', 1, '2025-09-09 14:27:58', NULL, NULL, NULL, 'A'),
(56, 1, 4, 'Grade 10', 'Elec  for 10', 'Physics 10 fdgd', '', 1, '2025-09-10 21:12:20', NULL, NULL, NULL, 'A');

-- --------------------------------------------------------

--
-- Table structure for table `onlineactivitiesassignments`
--

CREATE TABLE `onlineactivitiesassignments` (
  `AssignmentId` int(11) NOT NULL,
  `ClassID` int(11) NOT NULL,
  `OnlineActivityId` int(11) NOT NULL,
  `AssignedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `DueDate` date DEFAULT NULL,
  `LastFeedBackSent` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(72, 47, 'sfg arrrrrrrrrrrrrrrrrrrr ghre htr tr g  tr tr trh thtr h tr ds s', '5', 'Ohm', '44', 'Plastic', 'A'),
(73, 47, '1111111111111111thtr h trsfg arrrrrrrrrrrrrrrrrrrr ghre htr tr g  tr tr trh thtr h trsfg arrrrrrrrrrrrrrrrrrrr ghre htr tr g  tr tr trh thtr h tr', 'Albert Einstein', 'Isaac Newton', 'Galileo Galilei', 'Nikola Tesla', 'A'),
(74, 47, 'sfg arrrrrrrrrrrrrrrrrrrr ghre htr tr g  tr tr trh thtr h trsfg arrrrrrrrrrrrrrrrrrrr ghre htr tr g  tr tr trh thtr h trsfg arrrrrrrrrrrrrrrrrrrr ghre htr tr g  tr tr trh thtr h tr', 'sedf', 'fd', '7', '9 m/s²', 'A'),
(75, 47, 'sfg arrrrrrrrrrrrrrrrrrrr ghre htr tr g  tr tr trh thtr h trsfg arrrrrrrrrrrrrrrrrrrr ghre htr tr g  tr tr trh thtr h tr', 'The most frequent value', 'The difference between highest and lowest values', 'The average of all values', 'fdsf', 'A'),
(76, 47, 'sfg arrrrrrrrrrrrrrrrrrrr ghre htr tr g  tr tr trh thtr h tr', '70', 'sadvfbg', 'asdfvasfesf', 'asdsfv', 'A'),
(78, 49, '676  7687y 89 889', 'Copper', '12', 'Iron', '7', 'A'),
(79, 50, 'SDfghjk lk  kl jl jjl kl kjkl jl kjkl j k;l lk', 'Copper', 'Aluminum', '44', 'Watt', 'A'),
(80, 51, 'erthyj uhijou hijok uhilj', 'Copper', 'Aluminum', '44', 'Watt', 'A'),
(81, 52, 'awf fsfsdgdfddg ', 'Copper', 'Aluminum', '44', 'Watt', 'A'),
(82, 53, 'e fewf', 'Copper', 'Aluminum', '44', 'Watt', 'A'),
(83, 54, 'df grg re r ', 'Copper', 'Aluminum', '44', 'Watt', 'A'),
(84, 55, ' sdfg rb rbh ', 'Copper', 'Aluminum', '44', 'Watt', 'A'),
(85, 56, 'fd bfd bf bgf', 'Copper', 'Aluminum', '44', 'Watt', 'A');

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
(116, 'The equation of a line with slope 2 and passing through (3,4) is: ggg', 'y - 4 = 2(x - 3)', 'y = 2x + 4', 'y - 3 = 2(x - 4)', 'y = 2x - 3', 'A', 3, 'Analytical Geometry', 'Mathematics', 'Grade 12', NULL),
(117, 'dfjhdsmf kj he hkfew ljkflkj ewf', 'etgre', 'etrghtregerghtserght', 'ertghsr', 'rethjyae5sh', 'B', 2, 'Analytical Geometry', 'Mathematics', 'Grade 12', NULL),
(118, 'etryfgjhk', '13', '345', '24', '52', 'C', 2, 'Calculus', 'Mathematics', 'Grade 12', NULL),
(119, 'sdrf re r gr re err', '3w45', 'rgvr', 'sfdvfgrdbv', 'vdfgreb', 'C', 1, 'Electricity', 'Physical Sciences', 'Grade 10', NULL),
(120, 'dgg tr tr tr trgtr', '3w45', 'rgvr', 'sfdvfgrdbv', 'vdfgreb', 'B', 1, 'Electrostatics', 'Physical Sciences', 'Grade 12', NULL),
(121, 'dfbgnhm htr trh', '3w45', 'rgvr', 'sfdvfgrdbv', 'vdfgreb', 'A', 2, 'Electrostatics', 'Physical Sciences', 'Grade 12', NULL);

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
(37, 'The first resource', '68aeef854ef25_Diesel_Mech_N4.pdf', 'pdf', 1, 'Grade 10', '', 'private', 1, '2025-08-27 13:44:05'),
(38, 'The second resource', '68aeefbf4f553_R_Kelly__The_Worlds_Greatest.mp3', 'audio', 4, 'Grade 10', '', 'private', 1, '2025-08-27 13:45:03'),
(39, 'First zip', '68b867758c4d9_Lewis_Capaldi__Someone_You_Loved.mp3', 'audio', 4, 'Grade 10', '', 'private', 1, '2025-09-03 18:06:13'),
(40, 'dfghhhh', '68b8679f357e5_mathematics-grade-12-easy-finances_1755360432.pdf', 'pdf', 2, 'Grade 11', '', 'private', 1, '2025-09-03 18:06:55');

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
(15, 'School 200', '', '', '', '2025-08-11 09:11:04'),
(16, 'School 500', '', '', '', '2025-09-21 12:02:58');

-- --------------------------------------------------------

--
-- Table structure for table `studentvoices`
--

CREATE TABLE `studentvoices` (
  `Id` int(10) UNSIGNED NOT NULL,
  `UserId` int(10) UNSIGNED DEFAULT NULL,
  `Subject` varchar(255) DEFAULT NULL,
  `Message` text NOT NULL,
  `IsAnonymous` tinyint(1) NOT NULL DEFAULT 0,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `IsRead` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `studentvoices`
--

INSERT INTO `studentvoices` (`Id`, `UserId`, `Subject`, `Message`, `IsAnonymous`, `CreatedAt`, `IsRead`) VALUES
(1, 106, 'Suggestion', 'How about we we w we we', 0, '2025-09-23 20:06:59', 1),
(2, NULL, 'Complaint', 'df dgd bdf gf bgf bgf btfb', 1, '2025-09-23 20:07:15', 1),
(3, 106, 'Appreciation', 'sgdhftj fykgujjt fhj th tyhjtr tr', 0, '2025-09-23 20:08:33', 1),
(4, 106, 'Suggestion', 'How about we improve the app interface?', 0, '2025-09-27 19:32:34', 0),
(5, NULL, 'Complaint', 'The platform is sometimes slow.', 1, '2025-09-27 19:32:34', 1),
(6, 107, 'Appreciation', 'I really like the new resources section!', 0, '2025-09-27 19:32:34', 0),
(7, 108, 'Feedback', 'Please add more exercises on algebra.', 0, '2025-09-27 19:32:34', 0),
(8, NULL, 'Suggestion', 'Consider adding dark mode.', 1, '2025-09-27 19:32:34', 0),
(9, 109, 'Complaint', 'Video playback sometimes lags.', 0, '2025-09-27 19:32:34', 0),
(10, 110, 'Appreciation', 'The tutor feedback is very helpful.', 0, '2025-09-27 19:32:34', 0),
(11, NULL, 'Feedback', 'Anonymous suggestion for group discussions.', 1, '2025-09-27 19:32:34', 0),
(12, 111, 'Suggestion', 'Add more quizzes for practice.', 0, '2025-09-27 19:32:34', 0),
(13, 112, 'Appreciation', 'I enjoyed the last live session.', 0, '2025-09-27 19:32:34', 0),
(14, 112, 'Appreciation', 'I really appreciate the consistent support from the tutors. The explanations are detailed, and the additional resources provided have helped me understand complex topics better. I hope this continues!', 0, '2025-09-27 19:34:49', 0);

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
(8, 'Extra Tutoring Sessions Available', 'Starting next week, extra tutoring sessions will be held every Wednesday after school in room 12. All Grade 10 Mathematics learners are encouraged to attend.', 'Mathematics', '10', 2, '2025-07-09 22:14:36', 0);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `SubjectId` int(11) NOT NULL,
  `GradeId` int(11) NOT NULL,
  `SubjectName` varchar(100) NOT NULL,
  `MaxClassSize` int(11) DEFAULT 30,
  `DefaultTutorId` int(11) DEFAULT NULL,
  `ThreeMonthsPrice` decimal(10,2) DEFAULT NULL,
  `SixMonthsPrice` decimal(10,2) DEFAULT NULL,
  `TwelveMonthsPrice` decimal(10,2) DEFAULT NULL,
  `CreatedAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`SubjectId`, `GradeId`, `SubjectName`, `MaxClassSize`, `DefaultTutorId`, `ThreeMonthsPrice`, `SixMonthsPrice`, `TwelveMonthsPrice`, `CreatedAt`) VALUES
(1, 1, 'Mathematics', 5, 25, 300.00, 600.00, 1200.00, '2025-08-08 15:58:08'),
(2, 2, 'Mathematics', 5, 25, 300.00, 600.00, 1200.00, '2025-08-08 15:58:08'),
(3, 3, 'Mathematics', 5, 25, 300.00, 600.00, 1200.00, '2025-08-08 15:58:08'),
(4, 1, 'Physical Sciences', 5, 25, 300.00, 600.00, 1200.00, '2025-08-08 15:58:08'),
(6, 3, 'Physical Sciences', 5, 25, 300.00, 600.00, 1200.00, '2025-08-08 15:58:08'),
(47, 37, 'English First Additional Language', 30, NULL, NULL, NULL, NULL, '2025-09-21 14:02:58'),
(48, 37, 'Afrikaans First Additional Language', 30, NULL, NULL, NULL, NULL, '2025-09-21 14:02:58'),
(49, 37, 'Creative Arts', 30, NULL, NULL, NULL, NULL, '2025-09-21 14:02:58');

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
(38, 1, 'Work on the notifications', '2025-09-24 16:41:47', '2025-09-24 16:41:00', 'Low', 0, NULL, NULL, 'General'),
(41, 1, 'Mmantwas groove!', '2025-09-27 19:39:25', '2025-09-27 19:39:00', 'Low', 0, NULL, NULL, 'General'),
(42, 1, 'managestudymaterials.php  needs some work! (for class resources)', '2025-09-27 19:45:52', '2025-09-27 19:45:00', 'Low', 0, NULL, NULL, 'General');

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
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `AvailabilityType` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutoravailability`
--

INSERT INTO `tutoravailability` (`Id`, `TutorId`, `DayOfWeek`, `StartTime`, `EndTime`, `CreatedAt`, `AvailabilityType`) VALUES
(27, 24, 'Monday', '01:40:00', '05:42:00', '2025-10-10 20:44:44', 'Recurring'),
(28, 24, 'Thursday', '10:36:00', '11:36:00', '2025-10-10 20:44:44', 'Recurring'),
(29, 24, 'Saturday', '21:45:00', '22:45:00', '2025-10-10 20:44:44', 'Recurring'),
(45, 2, 'Monday', '22:00:00', '22:30:00', '2025-10-11 20:27:15', 'OnceOff'),
(46, 2, 'Monday', '18:30:00', '19:00:00', '2025-10-11 20:28:11', 'OnceOff'),
(48, 2, 'Friday', '13:30:00', '14:30:00', '2025-10-11 20:29:57', 'OnceOff'),
(67, 2, 'Tuesday', '01:09:00', '02:00:00', '2025-10-13 10:38:47', 'Recurring'),
(68, 2, 'Thursday', '19:00:00', '20:00:00', '2025-10-13 10:38:47', 'Recurring'),
(69, 2, 'Sunday', '22:30:00', '23:30:00', '2025-10-13 10:38:47', 'Recurring'),
(71, 2, 'Wednesday', '15:00:00', '16:00:00', '2025-10-13 10:39:54', 'OnceOff'),
(72, 2, 'Monday', '20:00:00', '21:00:00', '2025-10-13 10:48:40', 'OnceOff'),
(73, 2, 'Sunday', '22:00:00', '22:10:00', '2025-10-13 10:51:59', 'OnceOff');

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
-- Table structure for table `tutorfeedback`
--

CREATE TABLE `tutorfeedback` (
  `FeedbackId` int(11) NOT NULL,
  `SessionId` int(11) NOT NULL,
  `TutorId` int(11) NOT NULL,
  `LearnerId` int(11) NOT NULL,
  `Grade` varchar(50) NOT NULL,
  `SubjectName` varchar(100) DEFAULT NULL,
  `Clarity` tinyint(4) NOT NULL,
  `Engagement` tinyint(4) NOT NULL,
  `Understanding` enum('Yes, all of them','Some of them','No, not really') NOT NULL,
  `OverallRating` tinyint(4) NOT NULL,
  `FeedbackDate` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutorfeedback`
--

INSERT INTO `tutorfeedback` (`FeedbackId`, `SessionId`, `TutorId`, `LearnerId`, `Grade`, `SubjectName`, `Clarity`, `Engagement`, `Understanding`, `OverallRating`, `FeedbackDate`) VALUES
(3, 5, 2, 130, 'Grade 10', 'Mathematics', 4, 3, 'Yes, all of them', 7, '2025-10-14 12:51:49');

-- --------------------------------------------------------

--
-- Table structure for table `tutorpayments`
--

CREATE TABLE `tutorpayments` (
  `PaymentId` int(11) NOT NULL,
  `TutorId` int(11) NOT NULL,
  `Amount` decimal(10,2) NOT NULL,
  `PaymentDate` datetime NOT NULL DEFAULT current_timestamp(),
  `PaymentMethod` varchar(50) DEFAULT 'Cash',
  `Notes` varchar(255) DEFAULT NULL,
  `CreatedAt` datetime DEFAULT current_timestamp(),
  `UpdatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutorpayments`
--

INSERT INTO `tutorpayments` (`PaymentId`, `TutorId`, `Amount`, `PaymentDate`, `PaymentMethod`, `Notes`, `CreatedAt`, `UpdatedAt`) VALUES
(1, 2, 250.00, '2025-08-30 22:56:14', 'Cash', 'Jan Pay', '2025-08-30 22:56:14', '2025-08-30 22:56:14'),
(2, 2, 300.00, '2025-08-30 22:56:29', 'Cash', 'Feb Pay', '2025-08-30 22:56:29', '2025-08-30 22:56:29'),
(3, 21, 1500.00, '2025-08-30 22:57:19', 'Cash', 'Jan Payment', '2025-08-30 22:57:19', '2025-08-30 22:57:19'),
(4, 2, 80000.00, '2025-09-03 17:54:31', 'Cash', 'march Pay', '2025-09-03 17:54:31', '2025-09-03 17:54:31');

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
(59, '', '', 0, '', ''),
(127, '', '', 0, '', ''),
(128, '', '', 0, '', ''),
(129, '', '', 0, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tutorsessions`
--

CREATE TABLE `tutorsessions` (
  `SessionId` int(11) NOT NULL,
  `TutorId` int(11) NOT NULL,
  `LearnerId` int(11) NOT NULL,
  `SlotDateTime` datetime NOT NULL,
  `Subject` varchar(255) NOT NULL,
  `Grade` varchar(50) NOT NULL,
  `Notes` text DEFAULT NULL,
  `AttachmentPath` varchar(255) DEFAULT NULL,
  `MeetingLink` varchar(255) DEFAULT NULL,
  `Status` enum('Pending','Confirmed','Declined','Completed','Missed') DEFAULT 'Pending',
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `Attendance` enum('NotJoined','Joined') DEFAULT 'NotJoined',
  `Hidden` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutorsessions`
--

INSERT INTO `tutorsessions` (`SessionId`, `TutorId`, `LearnerId`, `SlotDateTime`, `Subject`, `Grade`, `Notes`, `AttachmentPath`, `MeetingLink`, `Status`, `CreatedAt`, `Attendance`, `Hidden`) VALUES
(1, 2, 131, '2025-10-12 22:54:00', 'Mathematics', 'Grade 12', ',./', NULL, NULL, 'Confirmed', '2025-10-11 22:19:01', 'NotJoined', 0),
(4, 2, 131, '2025-10-19 22:54:00', 'Mathematics', 'Grade 12', 'rshjetj', NULL, NULL, 'Declined', '2025-10-11 22:19:52', 'NotJoined', 0),
(5, 2, 130, '2025-10-13 22:00:00', 'Mathematics', 'Grade 10', 'xfhbnfm', NULL, 'https://teams.microsoft.com/l/meetup-join/19%3ameeting_NjQxZmI2ZjItMjY1ZS00ZmY1LWFlYTQtMjQ1NzM0ZDJkYmI5%40thread.v2/0?context=%7b%22Tid%22%3axxxxxx%22%7d', 'Completed', '2025-10-11 22:20:32', 'Joined', 1),
(6, 2, 130, '2025-10-15 13:34:00', 'Mathematics', 'Grade 10', 'xfndm', NULL, 'https://teams.microsoft.com/l/meetup-join/19%3ameeting_NjQxZmI2ZjItMjY1ZS00ZmY1LWFlYTQtMjQ1NzM0ZDJkYmI5%40thread.v2/0?context=%7b%22Tid%22%3axxxxxx%22%7d', 'Confirmed', '2025-10-11 22:20:48', 'Joined', 0),
(7, 2, 130, '2025-10-13 18:30:00', 'Mathematics', 'Grade 10', 'new request of onced declined', NULL, NULL, 'Pending', '2025-10-11 22:23:38', 'NotJoined', 0),
(8, 2, 130, '2025-10-13 20:30:00', 'Mathematics', 'Grade 10', 'xfdghb', NULL, NULL, 'Declined', '2025-10-11 22:26:32', 'NotJoined', 0),
(10, 2, 131, '2025-10-13 20:00:00', 'Mathematics', 'Grade 12', 'I need help with FInanciallfkdnv', NULL, NULL, 'Confirmed', '2025-10-13 11:07:55', 'NotJoined', 0),
(13, 2, 131, '2025-10-17 13:30:00', 'Mathematics', 'Grade 12', 'test upload 5', '../../uploads/attachments/1760354561_doe.png', 'https://teams.microsoft.com/l/meetup-join/19%3ameeting_NjQxZmI2ZjItMjY1ZS00ZmY1LWFlYTQtMjQ1NzM0ZDJkYmI5%40thread.v2/0?context=%7b%22Tid%22%3axxxxxx%22%7d', 'Confirmed', '2025-10-13 11:22:41', 'NotJoined', 0),
(14, 2, 131, '2025-10-14 01:09:00', 'Mathematics', 'Grade 12', 'ryh h 6j6 j56', '../../uploads/attachments/1760355103_Neo  (1).pdf', NULL, 'Pending', '2025-10-13 11:31:43', 'NotJoined', 0),
(15, 2, 131, '2025-10-15 15:00:00', 'Mathematics', 'Grade 12', 'rt hth tyjty', '../../uploads/attachments/1760355163_Rachel\'s (1).pdf', NULL, 'Confirmed', '2025-10-13 11:32:43', 'NotJoined', 0),
(16, 2, 131, '2025-10-16 19:00:00', 'Mathematics', 'Grade 12', 'uyhj ijj iuji', '../../uploads/attachments/1760355197_IMG_20250527_095318.jpg', NULL, 'Confirmed', '2025-10-13 11:33:17', 'NotJoined', 0),
(17, 2, 131, '2025-10-19 22:00:00', 'Mathematics', 'Grade 12', 'fdg gf t ', NULL, NULL, 'Declined', '2025-10-13 11:49:51', 'NotJoined', 0),
(18, 2, 130, '2025-10-13 15:45:00', 'Mathematics', 'Grade 10', 'today today test', NULL, 'https://teams.microsoft.com/l/meetup-join/19%3ameeting_NjQxZmI2ZjItMjY1ZS00ZmY1LWFlYTQtMjQ1NzM0ZDJkYmI5%40thread.v2/0?context=%7b%22Tid%22%3axxxxxx%22%7d', 'Missed', '2025-10-13 13:42:06', 'NotJoined', 0),
(19, 2, 130, '2025-10-19 22:00:00', 'Mathematics', 'Grade 10', ' btrb ngf n', NULL, NULL, 'Confirmed', '2025-10-14 09:13:10', 'NotJoined', 0),
(20, 2, 130, '2025-10-26 22:30:00', 'Mathematics', 'Grade 10', 'rg er h', NULL, NULL, 'Confirmed', '2025-10-14 09:13:23', 'NotJoined', 0),
(21, 2, 130, '2025-10-21 01:09:00', 'Mathematics', 'Grade 10', 'sg g', NULL, 'https://teams.microsoft.com/l/meetup-join/19%3ameeting_NjQxZmI2ZjItMjY1ZS00ZmY1LWFlYTQtMjQ1NzM0ZDJkYmI5%40thread.v2/0?context=%7b%22Tid%22%3axxxxxx%22%7d', 'Confirmed', '2025-10-14 09:13:39', 'NotJoined', 0),
(22, 2, 130, '2025-10-20 22:00:00', 'Mathematics', 'Grade 10', ' ver bre bb', NULL, NULL, 'Declined', '2025-10-14 09:13:51', 'NotJoined', 0);

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
(2, 4, 1, 0.00),
(2, 6, 1, 0.00),
(2, 49, 1, 0.00),
(19, 1, 1, 0.00),
(19, 2, 1, 0.00),
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
(59, 5, 1, 0.00),
(127, 1, 1, 0.00),
(127, 6, 1, 0.00),
(128, 4, 1, 0.00),
(129, 4, 1, 0.00);

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
  `UserType` int(5) NOT NULL,
  `FailedAttempts` int(11) DEFAULT 0,
  `LastFailedAttempt` datetime DEFAULT NULL,
  `PermanentlyBlocked` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Id`, `Surname`, `Name`, `UserPassword`, `Gender`, `Contact`, `AlternativeContact`, `Email`, `IsVerified`, `ResetCode`, `ResetTimestamp`, `VerificationToken`, `RegistrationDate`, `UserType`, `FailedAttempts`, `LastFailedAttempt`, `PermanentlyBlocked`) VALUES
(1, 'Director', 'DOE', '$2y$10$xcisHOBXh6RzLzS9hERHaeSQna4BZAzJCuu.uJNn5Fon6btiUto8.', 'Mr', 1234567890, NULL, 'thedistributorsofedu@gmail.com', 1, '', NULL, 'f3659d1c92546274b30c2ade4b5e3012da1cfabc13f04bb3b9cf9a76007090eb', '2025-06-30 11:18:57', 0, 0, NULL, 0),
(2, 'Malesela', 'Shirley M M v', '$2y$10$t2VD2G8anvVZ/8IQ5f43RO9fq33OI38c8WU3gfNnn/EEHCy9rRL7G', 'Ms', 2048567858, NULL, 'shirley@gmail.com', 1, '', '2025-06-30 11:32:56', 'f9c5c52aee8b1fd0a72553256e2a425ef8aece59d855a99b710cc1fe0dbf0f52', '2025-06-30 11:32:56', 1, 0, NULL, 0),
(19, 'Boshielo', 'Emmanuel', '$2y$10$GbYLySVSA8qlvSfKzAWi1uFblIzipSMocSvyMKBf3.diQbqH.Re8a', 'Mr', 2147483647, NULL, 'emahlwele055@gmail.com', 0, '', '2025-07-15 08:29:51', '9942c2923fa78adba6dc3f77cb829159', '2025-07-15 08:29:51', 1, 0, NULL, 0),
(20, 'Sandjon', 'Nicole', '$2y$10$LFVceoKvwMuku/4ldEDt3.E8A1yjkYGVU2qXU/QZCLVUU4kf8VV8m', 'Ms', 2147483647, NULL, 'nsandj@gmail.com', 0, '', '2025-07-15 08:34:57', '3d8397d11dcf5fefbef1f2f587121e90', '2025-07-15 08:34:57', 1, 0, NULL, 0),
(21, 'Mamogobo', 'Sydney', '$2y$10$iQBqDgcGuAqFWFWUQDJD6OczA1.eFSjB1/OPKuLWwTFY5nlq2NNYe', 'Mr', 728547485, NULL, 'mamogobo@gmail.com', 0, '', '2025-07-15 09:24:07', '0e6d551816c26c72bad0ab26cc87edbf', '2025-07-15 09:24:07', 1, 0, NULL, 0),
(24, 'Mbuyane', 'Sanele', '$2y$10$Hx025ygsL1ffOQXVaL0Sb.teIGUrL2JkT3WWtM2Xbs8fBJBMPf94q', 'Ms', 854285425, NULL, 'mbuyane@gmail.com', 0, '', '2025-07-19 12:31:58', 'c859b2dd65d4551d371314cc2c09670b', '2025-07-19 12:31:58', 1, 0, NULL, 0),
(25, 'Temp', 'Temp', '$2y$10$Gbi4R5wR6v85AdDAfXSwtuu8r8eXMTTDtKiYbJLU1/F0N38zDMgjO', 'Dr', 2147483647, NULL, 'doe@gmail.com', 0, '', '2025-07-19 14:51:08', '36a628e323d45abefaa4c10d8c0f3f59', '2025-07-19 14:51:08', 1, 0, NULL, 0),
(55, 'Parkar', 'Letty', '$2y$10$jnos60lQhNpspOQ1pPWCj.GUubh8s5K/Qx3XHNjKwazmIXfQSk5nO', 'Mrs', 2147483647, NULL, 'parkar@gmail.com', 0, '', '2025-07-24 17:10:55', '0669703be53a55438658c4dda7e5bcec', '2025-07-24 17:10:55', 1, 0, NULL, 0),
(56, 'Jones', 'Molly', '$2y$10$X20DJj8saNZRr2x/3qRteubET9E0/ODNhfQ6ew7RKiPFMMqjDlIWS', 'Mrs', 2147483647, NULL, 'jones@gmail.com', 0, '', '2025-07-24 17:16:07', '84124d7519dc470701bb0a964fe7726a', '2025-07-24 17:16:07', 1, 0, NULL, 0),
(57, 'Boorn', 'Joris', '$2y$10$TTTZbLPOmdEyBtYaiAgDfelndBJKj0qYkuzmEkYzoMt1s3RNWJai6', 'Mr', 2147483647, NULL, 'boorn@gmail.com', 0, '', '2025-07-24 17:18:56', '81b420a23b1b2a503779767ac000fb17', '2025-07-24 17:18:56', 1, 0, NULL, 0),
(58, 'Pierce', 'Alexander', '$2y$10$k5peCRhgnOMM706SpYdyBeEi2UMIE7icxKFyWccnfTNAiE9al6vkq', 'Mr', 2147483647, NULL, 'pierce@gmail.com', 0, '', '2025-07-24 17:20:10', 'e3661c1b3f8684f54c7b40b4760a9e2a', '2025-07-24 17:20:10', 1, 0, NULL, 0),
(59, 'wswsw', 'wswswsw', '$2y$10$pen8U65c24d2/RvKFc4d5e0O24OrxqML5DvDN28Ha1sbxVsZGvMRe', 'Mr', 2147483647, NULL, 'wsww@gmail.com', 0, '', '2025-07-24 17:21:20', 'dca23cd7890ea2d70fb61b839452a30d', '2025-07-24 17:21:20', 1, 0, NULL, 0),
(127, '22222222222', '1111111', '$2y$10$u/rMsJZV/gHKB/RaIK2qWOKDSDX9fSS6H4guzBxi6IcHIsP2HbNly', 'Mr', 657089357, NULL, 'thedistributorsofedu@gmail.com', 0, '', '2025-09-24 18:53:22', '04d3bf29d6f51fb52eb3a8d42373bffd', '2025-09-24 18:53:22', 1, 0, NULL, 0),
(128, '144444', 'asdfgh', '$2y$10$WS3/wUfSi/4gJYCe/yo.NuWmluGNCBO58lQDyjOJ/aBy2hfnpeLR6', 'Mr', 2020202020, NULL, 'thedistributorsofedu@gmail.com', 0, '', '2025-09-24 19:08:25', '0d2d6870dc5521c2c346f27e2609a38c', '2025-09-24 19:08:25', 1, 0, NULL, 0),
(129, '144444', 'asdfgh', '$2y$10$pOsNoBHN5W8WMqadav9N6.pDyVcRP4q2C3vY8BbBwj4REiQau/hi.', 'Mr', 2020202020, NULL, 'thedistributorsofedu@gmail.com', 0, '', '2025-09-24 19:08:46', 'f2ba900bb81f0ad04e35606cad40af3e', '2025-09-24 19:08:46', 1, 0, NULL, 0),
(130, 'Boshielo', 'Emmanuel', '$2y$10$ghV9OZVg1avxRmY9HVfYlOYqNOi4tHkFK6icMoVi7Ql12P1OOQpna', 'Mr', 655708932, NULL, 'emmanuel@gmail.com', 1, '', '2025-10-09 17:14:21', '', '2025-10-09 17:14:21', 2, 0, NULL, 0),
(131, 'Letsholonyane', 'Reneilwe', '$2y$10$vc.Hnk0LffZLtS6lI.bngebQUiSWgHD2oqYvxXkhoeRDu4IHVr4GG', 'Mr', 655709888, NULL, 'emahlwele05@gmail.com', 1, '', '2025-10-09 17:35:57', '', '2025-10-09 17:35:57', 2, 0, NULL, 0),
(133, 'Josephs', 'Tyler', '$2y$10$ziDaGuXPLb5OsUB7mjrXnOqIYH49iUduZ9YE5sa65s/W6NqupuS2.', 'Mr', 655708932, NULL, 'tyler@gmail.com', 0, '', '2025-10-09 18:10:14', '88fad6b0e1e7ebdae3a49d9ea170fc53755998bc7f475582678673c1c317d37c', '2025-10-09 18:10:14', 2, 0, NULL, 0);

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
  ADD KEY `TutorID` (`TutorID`),
  ADD KEY `fk_classes_subject` (`SubjectID`);

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
  ADD UNIQUE KEY `uc_finances` (`LearnerId`);

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
-- Indexes for table `learnerclasshistory`
--
ALTER TABLE `learnerclasshistory`
  ADD PRIMARY KEY (`HistoryID`),
  ADD KEY `LearnerID` (`LearnerID`),
  ADD KEY `ClassID` (`ClassID`),
  ADD KEY `SubjectId` (`SubjectId`);

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
-- Indexes for table `learneronlineactivities`
--
ALTER TABLE `learneronlineactivities`
  ADD PRIMARY KEY (`LearnerOnlineActivityId`),
  ADD UNIQUE KEY `LearnerId` (`LearnerId`,`OnlineActivityId`),
  ADD KEY `fk_loa_activity` (`OnlineActivityId`);

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
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`NotificationId`),
  ADD KEY `idx_createdfor` (`CreatedFor`),
  ADD KEY `idx_grade_subject` (`Grade`,`SubjectName`),
  ADD KEY `idx_expirydate` (`ExpiryDate`);

--
-- Indexes for table `onlineactivities`
--
ALTER TABLE `onlineactivities`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `onlineactivitiesassignments`
--
ALTER TABLE `onlineactivitiesassignments`
  ADD PRIMARY KEY (`AssignmentId`),
  ADD KEY `fk_onlineactivitiesassignments_class` (`ClassID`),
  ADD KEY `fk_onlineactivitiesassignments_activity` (`OnlineActivityId`);

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
  ADD KEY `UploadedBy` (`UploadedBy`),
  ADD KEY `fk_resources_subject` (`SubjectID`);

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`SchoolId`);

--
-- Indexes for table `studentvoices`
--
ALTER TABLE `studentvoices`
  ADD PRIMARY KEY (`Id`);

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
  ADD KEY `GradeId` (`GradeId`),
  ADD KEY `fk_default_tutor` (`DefaultTutorId`);

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
-- Indexes for table `tutorfeedback`
--
ALTER TABLE `tutorfeedback`
  ADD PRIMARY KEY (`FeedbackId`);

--
-- Indexes for table `tutorpayments`
--
ALTER TABLE `tutorpayments`
  ADD PRIMARY KEY (`PaymentId`),
  ADD KEY `TutorId` (`TutorId`);

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
  ADD UNIQUE KEY `unique_booking_per_learner` (`TutorId`,`SlotDateTime`,`LearnerId`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `ActivityId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `ClassID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `feedbacklog`
--
ALTER TABLE `feedbacklog`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `finances`
--
ALTER TABLE `finances`
  MODIFY `FinanceId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `GradeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `inviterequests`
--
ALTER TABLE `inviterequests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `invitetokens`
--
ALTER TABLE `invitetokens`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `learneractivitymarks`
--
ALTER TABLE `learneractivitymarks`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `learneranswers`
--
ALTER TABLE `learneranswers`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT for table `learnerclasses`
--
ALTER TABLE `learnerclasses`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `learnerclasshistory`
--
ALTER TABLE `learnerclasshistory`
  MODIFY `HistoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `learnerhomeworkresults`
--
ALTER TABLE `learnerhomeworkresults`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `learnerlevel`
--
ALTER TABLE `learnerlevel`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `learneronlineactivities`
--
ALTER TABLE `learneronlineactivities`
  MODIFY `LearnerOnlineActivityId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `learnerpracticequestions`
--
ALTER TABLE `learnerpracticequestions`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=292;

--
-- AUTO_INCREMENT for table `learners`
--
ALTER TABLE `learners`
  MODIFY `LearnerId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- AUTO_INCREMENT for table `learnersubject`
--
ALTER TABLE `learnersubject`
  MODIFY `LearnerSubjectId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

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
  MODIFY `NoticeNo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `NotificationId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `onlineactivities`
--
ALTER TABLE `onlineactivities`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `onlineactivitiesassignments`
--
ALTER TABLE `onlineactivitiesassignments`
  MODIFY `AssignmentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `onlinequestions`
--
ALTER TABLE `onlinequestions`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `practicequestions`
--
ALTER TABLE `practicequestions`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

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
  MODIFY `AssignmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `ResourceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `SchoolId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `studentvoices`
--
ALTER TABLE `studentvoices`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `subjectnotices`
--
ALTER TABLE `subjectnotices`
  MODIFY `NoticeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `SubjectId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `todolist`
--
ALTER TABLE `todolist`
  MODIFY `TodoId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `tutoravailability`
--
ALTER TABLE `tutoravailability`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `tutordateexceptions`
--
ALTER TABLE `tutordateexceptions`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tutorfeedback`
--
ALTER TABLE `tutorfeedback`
  MODIFY `FeedbackId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tutorpayments`
--
ALTER TABLE `tutorpayments`
  MODIFY `PaymentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tutorsessions`
--
ALTER TABLE `tutorsessions`
  MODIFY `SessionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

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
  ADD CONSTRAINT `classes_ibfk_2` FOREIGN KEY (`TutorID`) REFERENCES `tutors` (`TutorId`),
  ADD CONSTRAINT `fk_classes_subject` FOREIGN KEY (`SubjectID`) REFERENCES `subjects` (`SubjectId`);

--
-- Constraints for table `feedbacklog`
--
ALTER TABLE `feedbacklog`
  ADD CONSTRAINT `feedbacklog_ibfk_1` FOREIGN KEY (`ActivityId`) REFERENCES `onlineactivities` (`Id`),
  ADD CONSTRAINT `feedbacklog_ibfk_2` FOREIGN KEY (`TutorId`) REFERENCES `users` (`Id`);

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
-- Constraints for table `learneronlineactivities`
--
ALTER TABLE `learneronlineactivities`
  ADD CONSTRAINT `fk_loa_activity` FOREIGN KEY (`OnlineActivityId`) REFERENCES `onlineactivities` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_loa_learner` FOREIGN KEY (`LearnerId`) REFERENCES `learners` (`LearnerId`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `fk_learnersubject_subject` FOREIGN KEY (`SubjectId`) REFERENCES `subjects` (`SubjectId`),
  ADD CONSTRAINT `learnersubject_ibfk_1` FOREIGN KEY (`LearnerId`) REFERENCES `learners` (`LearnerId`);

--
-- Constraints for table `notices`
--
ALTER TABLE `notices`
  ADD CONSTRAINT `notices_ibfk_1` FOREIGN KEY (`CreatedBy`) REFERENCES `users` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `onlineactivitiesassignments`
--
ALTER TABLE `onlineactivitiesassignments`
  ADD CONSTRAINT `fk_onlineactivitiesassignments_activity` FOREIGN KEY (`OnlineActivityId`) REFERENCES `onlineactivities` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_onlineactivitiesassignments_class` FOREIGN KEY (`ClassID`) REFERENCES `classes` (`ClassID`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `fk_resources_subject` FOREIGN KEY (`SubjectID`) REFERENCES `subjects` (`SubjectId`),
  ADD CONSTRAINT `resources_ibfk_2` FOREIGN KEY (`UploadedBy`) REFERENCES `users` (`Id`);

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `fk_default_tutor` FOREIGN KEY (`DefaultTutorId`) REFERENCES `tutors` (`TutorId`) ON DELETE SET NULL,
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
-- Constraints for table `tutorpayments`
--
ALTER TABLE `tutorpayments`
  ADD CONSTRAINT `tutorpayments_ibfk_1` FOREIGN KEY (`TutorId`) REFERENCES `tutors` (`TutorId`) ON DELETE CASCADE;

--
-- Constraints for table `tutors`
--
ALTER TABLE `tutors`
  ADD CONSTRAINT `tutors_ibfk_1` FOREIGN KEY (`TutorId`) REFERENCES `users` (`Id`);

--
-- Constraints for table `tutorsubject`
--
ALTER TABLE `tutorsubject`
  ADD CONSTRAINT `tutorsubject_ibfk_1` FOREIGN KEY (`TutorId`) REFERENCES `users` (`Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
