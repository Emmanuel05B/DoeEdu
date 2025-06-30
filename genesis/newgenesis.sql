-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2025 at 12:27 PM
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

-- --------------------------------------------------------

--
-- Table structure for table `tutors`
--

CREATE TABLE `tutors` (
  `TutorId` int(11) NOT NULL,
  `Bio` text DEFAULT NULL,
  `Qualifications` text DEFAULT NULL,
  `ExperienceYears` int(11) DEFAULT NULL,
  `ProfilePicture` varchar(255) DEFAULT NULL
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
-- Indexes for table `tutors`
--
ALTER TABLE `tutors`
  ADD PRIMARY KEY (`TutorId`);

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
  MODIFY `ActivityId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `directorsubjects`
--
ALTER TABLE `directorsubjects`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `finances`
--
ALTER TABLE `finances`
  MODIFY `FinanceId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `learneractivitymarks`
--
ALTER TABLE `learneractivitymarks`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `learneranswers`
--
ALTER TABLE `learneranswers`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `learnerhomeworkresults`
--
ALTER TABLE `learnerhomeworkresults`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `learners`
--
ALTER TABLE `learners`
  MODIFY `LearnerId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `learnersubject`
--
ALTER TABLE `learnersubject`
  MODIFY `LearnerSubjectId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `onlineactivities`
--
ALTER TABLE `onlineactivities`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `onlinequestions`
--
ALTER TABLE `onlinequestions`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `SubjectId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `todolist`
--
ALTER TABLE `todolist`
  MODIFY `TodoId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

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
-- Constraints for table `tutors`
--
ALTER TABLE `tutors`
  ADD CONSTRAINT `tutors_ibfk_1` FOREIGN KEY (`TutorId`) REFERENCES `users` (`Id`);

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
