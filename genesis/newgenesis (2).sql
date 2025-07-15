-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 15, 2025 at 10:07 AM
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
  `ChapterName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`ActivityId`, `ActivityName`, `SubjectId`, `ActivityDate`, `MaxMarks`, `Creator`, `Grade`, `ChapterName`) VALUES
(1, 'Activity 1', 1, '2025-07-05 08:07:57', 20.00, 'Director', 10, 'Algebraic Expressions'),
(2, 'Activity 2', 1, '2025-07-05 08:40:58', 2.00, 'Director', 10, 'Algebraic Expressions'),
(3, 'Activity 1 ', 1, '2025-07-05 08:41:15', 1.00, 'Director', 10, 'Exponents'),
(4, 'Activity 1', 4, '2025-07-05 08:41:09', 20.00, 'Director', 10, 'Electromagnetic Radiation'),
(5, 'Activity 1', 3, '2025-07-05 08:41:05', 14.00, 'Director', 12, 'Trigonometry'),
(6, 'Activity 1', 5, '2025-07-05 08:41:02', 5.00, 'Director', 11, 'Newtons Laws'),
(7, 'Activity 1', 2, '2025-07-08 07:11:13', 20.00, 'Director', 11, 'Exponents and Surds'),
(8, 'Activity 1', 2, '2025-07-08 07:24:53', 15.00, 'Director', 11, 'Exponents and Surds'),
(9, 'Activity 2', 2, '2025-07-08 07:25:41', 24.00, 'Director', 11, 'Exponents and Surds'),
(10, 'Quiz 1', 2, '2025-07-08 07:26:15', 20.00, 'Director', 11, 'Analytical Geometry'),
(11, 'QUiz 1', 2, '2025-07-08 07:26:50', 25.00, 'Director', 11, 'Functions'),
(12, 'QUiz 2', 2, '2025-07-08 07:27:17', 20.00, 'Director', 11, 'Functions'),
(13, 'Quiz 3', 2, '2025-07-08 07:27:49', 25.00, 'Director', 11, 'Functions'),
(14, 'Activity 1', 2, '2025-07-08 07:28:25', 20.00, 'Director', 11, 'Trigonometry'),
(15, 'Activity 1', 2, '2025-07-08 07:29:00', 30.00, 'Director', 11, 'Number Patterns'),
(16, 'Activity 2', 2, '2025-07-08 07:29:27', 20.00, 'Director', 11, 'Number Patterns'),
(17, 'Activity 3', 2, '2025-07-08 07:29:56', 20.00, 'Director', 11, 'Number Patterns'),
(18, 'Quiz 1', 2, '2025-07-08 09:16:53', 10.00, 'Director', 11, 'Statistics'),
(19, 'Activity 1', 2, '2025-07-08 09:23:59', 25.00, 'Director', 11, 'Probability');

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
(1, 3, 'Lerato', 'Nkosi', 10, 450.00, 0.00, 450.00, 0.00),
(2, 12, NULL, NULL, 10, 1500.00, 0.00, 750.00, 750.00),
(6, 17, NULL, NULL, 11, 750.00, 0.00, 750.00, 0.00),
(7, 18, NULL, NULL, 10, 1199.00, 0.00, 1199.00, 0.00);

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
(1, 17, 8, 1, 12, '2025-07-08 07:25:05', 'present', 'None', 'Yes', 'None'),
(2, 17, 9, 1, 19, '2025-07-08 07:25:49', 'present', 'None', 'Yes', 'None'),
(3, 17, 10, 1, 15, '2025-07-08 07:26:21', 'present', 'None', 'Yes', 'None'),
(4, 17, 11, 1, 21, '2025-07-08 07:26:56', 'present', 'None', 'Yes', 'None'),
(6, 17, 13, 1, 15, '2025-07-08 07:27:54', 'present', 'None', 'Yes', 'None'),
(8, 17, 15, 1, 27, '2025-07-08 07:29:09', 'present', 'None', 'Yes', 'None'),
(9, 17, 16, 1, 10, '2025-07-08 07:29:32', 'present', 'None', 'Yes', 'None'),
(10, 17, 17, 1, 16, '2025-07-08 07:30:04', 'present', 'None', 'Yes', 'None'),
(11, 17, 18, 1, 0, '2025-07-08 09:18:28', 'absent', 'Family Emergency', 'No', 'Missed the Deadline'),
(13, 17, 19, 1, 24, '2025-07-08 09:25:44', 'absent', 'Network Issues', 'Yes', 'None');

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
(1, 3, 1, 1, 'B', '2025-06-30 17:04:44'),
(2, 3, 1, 2, 'B', '2025-06-30 17:04:44'),
(3, 3, 3, 4, 'A', '2025-07-01 16:29:58'),
(4, 3, 2, 3, 'B', '2025-07-01 16:34:27'),
(5, 3, 4, 5, 'B', '2025-07-01 16:42:24'),
(6, 3, 5, 6, 'B', '2025-07-01 17:08:38'),
(7, 3, 5, 7, 'B', '2025-07-01 17:08:38'),
(8, 3, 5, 8, 'C', '2025-07-01 17:08:38'),
(9, 3, 5, 9, 'B', '2025-07-01 17:08:38'),
(10, 3, 5, 10, 'C', '2025-07-01 17:08:38');

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
(1, 3, 1, 50.00, '2025-06-30 17:04:45'),
(2, 3, 3, 100.00, '2025-07-01 16:29:58'),
(3, 3, 2, 0.00, '2025-07-01 16:34:27'),
(4, 3, 4, 0.00, '2025-07-01 16:42:24'),
(5, 3, 5, 60.00, '2025-07-01 17:08:38');

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
  `ParentContactNumber` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `learners`
--

INSERT INTO `learners` (`LearnerId`, `Grade`, `RegistrationDate`, `LearnerKnockoffTime`, `Math`, `Physics`, `TotalFees`, `TotalPaid`, `ParentTitle`, `ParentName`, `ParentSurname`, `ParentEmail`, `ParentContactNumber`) VALUES
(3, '10', '2025-06-30', '15:00:00', 450.00, 0.00, 450.00, 900.00, 'Mrs', 'Thandi', 'Nkosi', 'thandi.nkosi@gmail.com', '0823456789'),
(12, '10', '2025-07-05', '00:00:05', 750.00, 750.00, 1250.00, 0.00, 'Mr', 'Mathews', 'Pogba', 'mathew@gmail.com', '0754854521'),
(17, '11', '2025-07-05', '00:00:16', 750.00, 0.00, 750.00, 600.00, 'Mrs', 'Zakaria', 'Mahlwlele', 'zakazaka@gmail.com', '5663285202'),
(18, '10', '2025-07-05', '00:00:02', 1199.00, 0.00, 1199.00, 0.00, 'Prof', 'Zakaria', 'Mahlwlele', 'zakazaka@gmail.com', '5663285202');

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
(1, 3, 1, 6, 4, 3, '2025-09-30 00:00:00', 'Active'),
(2, 12, 1, 5, 1, 2, '2026-01-05 18:17:07', 'Active'),
(3, 12, 4, 6, 2, 2, '2026-01-05 18:17:08', 'Active'),
(7, 17, 2, 4, 4, 2, '2026-01-05 19:03:02', 'Active'),
(8, 18, 1, 5, 2, 3, '2026-07-05 19:14:40', 'Active');

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
(2, 'System Maintenance Notification', 'The system will be down for maintenance on Saturday from 10 PM to 2 AM. Please save your work accordingly.', '2025-07-09 20:38:47', 0, 2, 12),
(3, 'New Tutor Onboarding Session', 'All new tutors are invited to an onboarding session next Monday at 9 AM in the main conference room.', '2025-07-09 20:38:47', 0, 3, 2),
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
  `ImagePath` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `onlineactivities`
--

INSERT INTO `onlineactivities` (`Id`, `TutorId`, `SubjectName`, `Grade`, `Topic`, `Title`, `Instructions`, `TotalMarks`, `DueDate`, `CreatedAt`, `ImagePath`) VALUES
(1, 1, '1', '10', 'Algebraic Expressions', 'first one', 'Default instructions here', 2, '2025-07-04', '2025-06-30 18:37:04', '../uploads/1751301424_7.png'),
(2, 1, '1', '10', 'Algebraic Expressions', 'first one', 'Default instructions here', 1, '2025-07-11', '2025-06-30 18:40:10', '../uploads/1751301610_4.png'),
(3, 1, '1', '10', 'Functions', 'first one', 'Default instructions here', 1, '2025-07-04', '2025-06-30 18:56:36', NULL),
(4, 1, '1', '10', 'Trigonometry', 'first one', 'Default instructions here', 1, '2025-07-16', '2025-07-01 18:20:39', NULL),
(5, 1, '1', '10', 'Statistics', 'first one', 'Default instructions here', 5, '2025-08-09', '2025-07-01 19:06:29', '../uploads/1751389589_7.png'),
(6, 1, '1', '10', 'Finance and Growth', 'Finances', 'Default instructions here', 2, '2025-07-24', '2025-07-01 19:19:31', NULL);

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
(3, 2, 'r  eg tgr t r tr tr  rrgtrrt', '5', '12', 'Ampere', '7', 'A'),
(4, 3, 'frte tt ht hy th gtr', '5', '12', 'Ampere', '7', 'A'),
(5, 4, 'This is an edited question 22', '5', '12', 'Ampere', '7', 'A'),
(6, 5, 'What is the mean of the following data set?\nData: 6, 8, 10, 4, 12', '7', '8', '9', '10', 'B'),
(7, 5, 'Which value represents the median of this data set?\nData: 15, 10, 20, 25, 5', '10', '15', '20', '25', 'B'),
(8, 5, 'What is the mode of the following set of numbers?\r\nData: 3, 7, 3, 5, 8, 3, 2', '3', '5', '7', '2', 'A'),
(9, 5, 'What does the range of a data set measure?\r\n', 'The most frequent value', 'The difference between highest and lowest values', 'The average of all values', 'The middle value', 'B'),
(10, 5, 'The marks of 6 students in a test are: 70, 65, 80, 75, 90, 70. What is the mode?\r\n', '70', '80', '75', '90', 'A'),
(11, 6, 'Simple Interest\r\nThabo invests R5,000 in a savings account that pays 6% simple interest per annum. How much interest will he earn after 3 years?', 'R900', 'R800', 'R1,200', 'R750', 'A'),
(12, 6, 'Budgeting\r\nLebo earns R3,500 per month. She spends 30% on groceries, 25% on transport, and 20% on school fees. How much money does she have left after these expenses?', 'R875', 'R1,000', 'R1,225', 'R1,200', 'C');

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
  `ResourceId` int(11) NOT NULL,
  `UserId` int(11) NOT NULL,
  `SubjectId` int(11) NOT NULL,
  `ResourceType` varchar(50) DEFAULT NULL,
  `ResourceName` varchar(150) DEFAULT NULL,
  `ResourceURL` text DEFAULT NULL,
  `UploadDate` datetime DEFAULT current_timestamp()
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
(8, 'Extra Tutoring Sessions Available', 'Starting next week, extra tutoring sessions will be held every Wednesday after school in room 12. All Grade 10 Mathematics learners are encouraged to attend.', 'Mathematics', '10', 2, '2025-07-09 22:14:36', 0);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `SubjectId` int(11) NOT NULL,
  `SubjectName` varchar(255) NOT NULL,
  `Grade` varchar(10) NOT NULL,
  `SubjectCode` varchar(50) DEFAULT NULL,
  `ThreeMonthsPrice` decimal(10,2) DEFAULT 0.00,
  `SixMonthsPrice` decimal(10,2) DEFAULT 0.00,
  `TwelveMonthsPrice` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`SubjectId`, `SubjectName`, `Grade`, `SubjectCode`, `ThreeMonthsPrice`, `SixMonthsPrice`, `TwelveMonthsPrice`) VALUES
(1, 'Mathematics_10', '10', 'MATH10', 450.00, 800.00, 1500.00),
(2, 'Mathematics_11', '11', 'MATH11', 450.00, 800.00, 1500.00),
(3, 'Mathematics_12', '12', 'MATH12', 450.00, 900.00, 1600.00),
(4, 'Physical Sciences_10', '10', 'PHY10', 450.00, 91200.00, 1600.00),
(5, 'Physical Sciences_11', '11', 'PHY11', 450.00, 0.00, 0.00),
(6, 'Physical Sciences_12', '12', 'PHY12', 450.00, 0.00, 0.00);

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
(3, 1, 'Register Tutors functionality', '2025-07-05 19:33:48', '2025-05-04 14:00:00', 'Low', 0, NULL, NULL, 'General'),
(4, 1, 'Fix the sweet alert for registering a learner', '2025-07-05 19:34:36', '2025-05-20 02:10:00', 'Low', 0, NULL, NULL, 'General');

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
(2, NULL, NULL, NULL, NULL, NULL);

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

--
-- Dumping data for table `tutorsessions`
--

INSERT INTO `tutorsessions` (`SessionId`, `TutorId`, `LearnerId`, `SlotDateTime`, `Subject`, `Notes`, `Status`, `CreatedAt`) VALUES
(1, 2, 3, '2025-07-13 20:00:00', 'Mathematics', 'Hey there, this is my first booking.', 'Pending', '2025-07-08 17:15:15'),
(2, 2, 3, '2025-07-13 21:00:00', 'Mathematics', 'dsfghnb', 'Pending', '2025-07-08 17:25:18'),
(3, 2, 3, '2025-07-20 21:00:00', 'Mathematics', 'wdefrgtyujyi', 'Pending', '2025-07-08 17:25:36');

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
(2, 5, 1, 0.00);

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
(3, 'Nkosi', 'Lerato', '$2y$10$4/RdBLdSpJggDhuVFLwHC.kZjiA7ke6OAjishKGpFy4NjBSJDNxZq', 'Female', 1234567892, NULL, 'nkosi@gmail.com', 1, '', '2025-06-30 11:37:57', '', '2025-06-30 11:37:57', 2),
(12, 'Pogba', 'Paul', '$2y$10$.fvGH32mmsBc4ZgmW.KssuAc1v70BMvmQtNXdBVTBoNt/wQL6yqt.', 'Mr', 754285523, NULL, 'pogba@gmail.com', 0, '', '2025-07-05 16:17:07', '33105b032afc1dd3f0aacd2048c98d0913298d1fe49eb05332346019676b868a', '2025-07-05 16:17:07', 2),
(17, 'Mokoena', 'Anele', '$2y$10$xKkTtueave/E3tZWFbfMkeFIMyhhSgANRF08wBGEdsZ6m.tvkCzbq', 'Mr', 728452216, NULL, 'amokoena05@gmail.com', 0, '', '2025-07-05 17:03:02', 'e42e49c80b7a4f4d852681c9c9d9779f89988b9136e272bb41d925b9040abd94', '2025-07-05 17:03:02', 2),
(18, 'Modric', 'Marry ', '$2y$10$egEtAgthSRZxBeZJGMXOB.CO.MQqx12dRkoSG//p6SfOL9lPd4T0e', 'Mrs', 728452216, NULL, 'emahlwele05@gmail.com', 1, '$2y$10$QmyhqO51zqTs5pq3M.0fHeKaxdVoAH1Mrwx4UeQqjqqY1gP4ODHmC', '2025-07-05 17:29:04', 'ee75b2423e0ed76c7197ad6c9c985d72b0eca8dfe851fe51cd2d1e20ccc5cb3b', '2025-07-05 17:14:40', 2);

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
-- Indexes for table `directorsubjects`
--
ALTER TABLE `directorsubjects`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `DirectorId` (`DirectorId`),
  ADD KEY `SubjectId` (`SubjectId`);

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
  ADD PRIMARY KEY (`ResourceId`),
  ADD KEY `UserId` (`UserId`),
  ADD KEY `SubjectId` (`SubjectId`);

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
  MODIFY `ActivityId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `directorsubjects`
--
ALTER TABLE `directorsubjects`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `finances`
--
ALTER TABLE `finances`
  MODIFY `FinanceId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `learneractivitymarks`
--
ALTER TABLE `learneractivitymarks`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `learneranswers`
--
ALTER TABLE `learneranswers`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `learnerhomeworkresults`
--
ALTER TABLE `learnerhomeworkresults`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `learners`
--
ALTER TABLE `learners`
  MODIFY `LearnerId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `learnersubject`
--
ALTER TABLE `learnersubject`
  MODIFY `LearnerSubjectId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `NoticeNo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `onlineactivities`
--
ALTER TABLE `onlineactivities`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `onlinequestions`
--
ALTER TABLE `onlinequestions`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
  MODIFY `ResourceId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subjectnotices`
--
ALTER TABLE `subjectnotices`
  MODIFY `NoticeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `SubjectId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `todolist`
--
ALTER TABLE `todolist`
  MODIFY `TodoId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

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
-- Constraints for table `directorsubjects`
--
ALTER TABLE `directorsubjects`
  ADD CONSTRAINT `directorsubjects_ibfk_1` FOREIGN KEY (`DirectorId`) REFERENCES `users` (`Id`),
  ADD CONSTRAINT `directorsubjects_ibfk_2` FOREIGN KEY (`SubjectId`) REFERENCES `subjects` (`SubjectId`);

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
  ADD CONSTRAINT `resources_ibfk_1` FOREIGN KEY (`UserId`) REFERENCES `users` (`Id`),
  ADD CONSTRAINT `resources_ibfk_2` FOREIGN KEY (`SubjectId`) REFERENCES `subjects` (`SubjectId`);

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
