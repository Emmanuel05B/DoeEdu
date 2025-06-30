CREATE DATABASE IF NOT EXISTS genesis;
USE genesis;

-- Subjects
CREATE TABLE subjects (
  SubjectId int(11) NOT NULL AUTO_INCREMENT,
  SubjectName varchar(255) NOT NULL,
  SubjectCode varchar(50) DEFAULT NULL,
  ThreeMonthsPrice decimal(10,2) DEFAULT 0.00,
  SixMonthsPrice decimal(10,2) DEFAULT 0.00,
  TwelveMonthsPrice decimal(10,2) DEFAULT 0.00,
  PRIMARY KEY (SubjectId),
  UNIQUE KEY SubjectCode (SubjectCode)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Users
CREATE TABLE users (
  Id int(11) NOT NULL AUTO_INCREMENT,
  Surname varchar(100) NOT NULL,
  Name varchar(100) NOT NULL,
  UserPassword varchar(255) NOT NULL,
  Gender varchar(6) DEFAULT NULL,
  Contact int(10) NOT NULL,
  AlternativeContact int(10) DEFAULT NULL,
  Email varchar(100) NOT NULL,
  IsVerified int(2) NOT NULL,
  ResetCode varchar(64) NOT NULL,
  ResetTimestamp timestamp NULL DEFAULT current_timestamp(),
  VerificationToken varchar(64) NOT NULL,
  RegistrationDate timestamp NOT NULL DEFAULT current_timestamp(),
  UserType int(5) NOT NULL,
  PRIMARY KEY (Id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tutors
CREATE TABLE tutors (
  TutorId int(11) NOT NULL,
  Bio text DEFAULT NULL,
  Qualifications text DEFAULT NULL,
  ExperienceYears int(11) DEFAULT NULL,
  ProfilePicture varchar(255) DEFAULT NULL,
  PRIMARY KEY (TutorId),
  CONSTRAINT tutors_ibfk_1 FOREIGN KEY (TutorId) REFERENCES users(Id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- DirectorsSubjects
CREATE TABLE directorsubjects (
  Id int(11) NOT NULL AUTO_INCREMENT,
  DirectorId int(11) NOT NULL,
  SubjectId int(11) DEFAULT NULL,
  SubjectName varchar(100) DEFAULT NULL,
  PRIMARY KEY (Id),
  KEY DirectorId (DirectorId),
  KEY SubjectId (SubjectId),
  CONSTRAINT directorsubjects_ibfk_1 FOREIGN KEY (DirectorId) REFERENCES users(Id),
  CONSTRAINT directorsubjects_ibfk_2 FOREIGN KEY (SubjectId) REFERENCES subjects(SubjectId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- TutorsSubject
CREATE TABLE tutorsubject (
  TutorId int(11) NOT NULL,
  SubjectId int(11) NOT NULL,
  Active tinyint(1) DEFAULT 1,
  AvgRating decimal(3,2) DEFAULT 0.00,
  PRIMARY KEY (TutorId, SubjectId),
  KEY SubjectId (SubjectId),
  CONSTRAINT tutorsubject_ibfk_1 FOREIGN KEY (TutorId) REFERENCES users(Id),
  CONSTRAINT tutorsubject_ibfk_2 FOREIGN KEY (SubjectId) REFERENCES subjects(SubjectId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Learners
CREATE TABLE learners (
  LearnerId int(11) NOT NULL AUTO_INCREMENT,
  Grade varchar(20) DEFAULT NULL,
  RegistrationDate date DEFAULT NULL,
  LearnerKnockoffTime time DEFAULT NULL,
  Math decimal(10,2) DEFAULT 0.00,
  Physics decimal(10,2) DEFAULT 0.00,
  TotalFees decimal(10,2) DEFAULT 0.00,
  TotalPaid decimal(10,2) DEFAULT 0.00,
  TotalOwe decimal(10,2) GENERATED ALWAYS AS (TotalFees - TotalPaid) STORED,
  ParentTitle varchar(10) DEFAULT NULL,
  ParentName varchar(100) DEFAULT NULL,
  ParentSurname varchar(100) DEFAULT NULL,
  ParentEmail varchar(100) DEFAULT NULL,
  ParentContactNumber varchar(20) DEFAULT NULL,
  PRIMARY KEY (LearnerId),
  CONSTRAINT learner_ibfk_1 FOREIGN KEY (LearnerId) REFERENCES users(Id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- LearnersSubject
CREATE TABLE learnersubject (
  LearnerSubjectId int(11) NOT NULL AUTO_INCREMENT,
  LearnerId int(11) NOT NULL,
  SubjectId int(11) NOT NULL,
  TargetLevel int(11) DEFAULT NULL,
  CurrentLevel int(11) DEFAULT NULL,
  NumberOfTerms int(11) DEFAULT NULL,
  ContractExpiryDate datetime DEFAULT NULL,
  Status varchar(50) DEFAULT NULL,
  PRIMARY KEY (LearnerSubjectId),
  KEY LearnerId (LearnerId),
  KEY SubjectId (SubjectId),
  CONSTRAINT learnersubject_ibfk_1 FOREIGN KEY (LearnerId) REFERENCES learners(LearnerId),
  CONSTRAINT learnersubject_ibfk_2 FOREIGN KEY (SubjectId) REFERENCES subjects(SubjectId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Finances
CREATE TABLE finances (
  FinanceId int(11) NOT NULL AUTO_INCREMENT,
  LearnerId int(11) DEFAULT NULL,
  Grade int(3) DEFAULT NULL,
  TotalFees decimal(10,2) DEFAULT NULL,
  TotalPaid decimal(10,2) DEFAULT NULL,
  Math decimal(15,2) DEFAULT NULL,
  Physics decimal(15,2) DEFAULT NULL,
  PRIMARY KEY (FinanceId),
  KEY LearnerId (LearnerId),
  CONSTRAINT finances_ibfk_1 FOREIGN KEY (LearnerId) REFERENCES learners(LearnerId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Activities
CREATE TABLE activities (
  ActivityId int(11) NOT NULL AUTO_INCREMENT,
  ActivityName varchar(255) NOT NULL,
  SubjectId int(11) DEFAULT NULL,
  ActivityDate timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  MaxMarks decimal(10,2) DEFAULT NULL,
  Creator varchar(255) DEFAULT NULL,
  Grade int(3) NOT NULL,
  ChapterName varchar(50) NOT NULL,
  PRIMARY KEY (ActivityId),
  KEY SubjectId (SubjectId),
  CONSTRAINT activities_ibfk_1 FOREIGN KEY (SubjectId) REFERENCES subjects(SubjectId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- OnlineActivities
CREATE TABLE onlineactivities (
  Id int(11) NOT NULL AUTO_INCREMENT,
  TutorId int(11) NOT NULL,
  SubjectName varchar(100) NOT NULL,
  Grade varchar(20) NOT NULL,
  Topic varchar(100) NOT NULL,
  Title varchar(255) NOT NULL,
  Instructions text DEFAULT NULL,
  TotalMarks int(11) NOT NULL,
  DueDate date DEFAULT NULL,
  CreatedAt datetime DEFAULT current_timestamp(),
  ImagePath varchar(255) DEFAULT NULL,
  PRIMARY KEY (Id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- OnlineQuestions
CREATE TABLE onlinequestions (
  Id int(11) NOT NULL AUTO_INCREMENT,
  ActivityId int(11) NOT NULL,
  QuestionText text NOT NULL,
  OptionA text NOT NULL,
  OptionB text NOT NULL,
  OptionC text NOT NULL,
  OptionD text NOT NULL,
  CorrectAnswer enum('A','B','C','D') NOT NULL,
  PRIMARY KEY (Id),
  KEY ActivityId (ActivityId),
  CONSTRAINT onlinequestions_ibfk_1 FOREIGN KEY (ActivityId) REFERENCES onlineactivities(Id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- LearnerAnswers
CREATE TABLE learneranswers (
  Id int(11) NOT NULL AUTO_INCREMENT,
  UserId int(11) NOT NULL,
  ActivityId int(11) NOT NULL,
  QuestionId int(11) NOT NULL,
  SelectedAnswer char(1) NOT NULL,
  CreatedAt timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (Id),
  KEY idx_user_activity (UserId, ActivityId),
  KEY ActivityId (ActivityId),
  KEY QuestionId (QuestionId),
  CONSTRAINT learneranswers_ibfk_1 FOREIGN KEY (UserId) REFERENCES users(Id) ON DELETE CASCADE,
  CONSTRAINT learneranswers_ibfk_2 FOREIGN KEY (ActivityId) REFERENCES onlineactivities(Id) ON DELETE CASCADE,
  CONSTRAINT learneranswers_ibfk_3 FOREIGN KEY (QuestionId) REFERENCES onlinequestions(Id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- LearnerHomeworkResults
CREATE TABLE learnerhomeworkresults (
  Id int(11) NOT NULL AUTO_INCREMENT,
  UserId int(11) NOT NULL,
  ActivityId int(11) NOT NULL,
  Score decimal(5,2) NOT NULL,
  SubmittedAt timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (Id),
  KEY idx_user_activity (UserId, ActivityId),
  KEY ActivityId (ActivityId),
  CONSTRAINT learnerhomeworkresults_ibfk_1 FOREIGN KEY (UserId) REFERENCES users(Id) ON DELETE CASCADE,
  CONSTRAINT learnerhomeworkresults_ibfk_2 FOREIGN KEY (ActivityId) REFERENCES onlineactivities(Id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- LearnerActivityMarks
CREATE TABLE learneractivitymarks (
  Id int(11) NOT NULL AUTO_INCREMENT,
  LearnerId int(11) NOT NULL,
  ActivityId int(11) NOT NULL,
  MarkerId int(11) NOT NULL,
  MarksObtained double NOT NULL,
  DateAssigned timestamp NOT NULL DEFAULT current_timestamp(),
  Attendance varchar(255) NOT NULL,
  AttendanceReason varchar(255) NOT NULL,
  Submission varchar(255) NOT NULL,
  SubmissionReason varchar(255) NOT NULL,
  PRIMARY KEY (Id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Ratings
CREATE TABLE ratings (
  RatingId int(11) NOT NULL AUTO_INCREMENT,
  TutorId int(11) NOT NULL,
  LearnerId int(11) NOT NULL,
  Rating int(11) DEFAULT NULL CHECK (Rating BETWEEN 1 AND 10),
  RatingDate datetime DEFAULT current_timestamp(),
  Comments text DEFAULT NULL,
  PRIMARY KEY (RatingId),
  KEY TutorId (TutorId),
  KEY LearnerId (LearnerId),
  CONSTRAINT ratings_ibfk_1 FOREIGN KEY (TutorId) REFERENCES users(Id),
  CONSTRAINT ratings_ibfk_2 FOREIGN KEY (LearnerId) REFERENCES users(Id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- RegistrationQuestions
CREATE TABLE registrationquestions (
  QuestionId int(11) NOT NULL AUTO_INCREMENT,
  QuestionText varchar(255) NOT NULL,
  QuestionType varchar(50) NOT NULL,
  PRIMARY KEY (QuestionId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- LearnerRegistrationAnswers
CREATE TABLE learnerregistrationanswers (
  LearnerId int(11) NOT NULL,
  QuestionId int(11) NOT NULL,
  AnswerText varchar(255) DEFAULT NULL,
  PRIMARY KEY (LearnerId, QuestionId),
  KEY QuestionId (QuestionId),
  CONSTRAINT learnerregistrationanswers_ibfk_1 FOREIGN KEY (LearnerId) REFERENCES learners(LearnerId),
  CONSTRAINT learnerregistrationanswers_ibfk_2 FOREIGN KEY (QuestionId) REFERENCES registrationquestions(QuestionId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Resources
CREATE TABLE resources (
  ResourceId int(11) NOT NULL AUTO_INCREMENT,
  UserId int(11) NOT NULL,
  SubjectId int(11) NOT NULL,
  ResourceType varchar(50) DEFAULT NULL,
  ResourceName varchar(150) DEFAULT NULL,
  ResourceURL text DEFAULT NULL,
  UploadDate datetime DEFAULT current_timestamp(),
  PRIMARY KEY (ResourceId),
  KEY UserId (UserId),
  KEY SubjectId (SubjectId),
  CONSTRAINT resources_ibfk_1 FOREIGN KEY (UserId) REFERENCES users(Id),
  CONSTRAINT resources_ibfk_2 FOREIGN KEY (SubjectId) REFERENCES subjects(SubjectId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Todolist
CREATE TABLE todolist (
  TodoId int(11) NOT NULL AUTO_INCREMENT,
  CreatorId int(11) NOT NULL,
  TaskText text NOT NULL,
  CreationDate datetime DEFAULT current_timestamp(),
  DueDate datetime DEFAULT NULL,
  Priority varchar(10) DEFAULT NULL,
  Status int(11) DEFAULT 0,
  TimeSpent time DEFAULT NULL,
  CompletionDate datetime DEFAULT NULL,
  Category varchar(50) DEFAULT NULL,
  PRIMARY KEY (TodoId),
  KEY CreatorId (CreatorId),
  CONSTRAINT todolist_ibfk_1 FOREIGN KEY (CreatorId) REFERENCES users(Id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- UsersSubject
CREATE TABLE usersubject (
  Id int(11) NOT NULL AUTO_INCREMENT,
  SubjectId int(11) DEFAULT NULL,
  UserId int(11) NOT NULL,
  SubjectName varchar(100) DEFAULT NULL,
  SubjectCode varchar(20) DEFAULT NULL,
  ThreeMonthsPrice decimal(10,2) DEFAULT NULL,
  SixMonthsPrice decimal(10,2) DEFAULT NULL,
  TwelveMonthsPrice decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (Id),
  KEY UserId (UserId),
  KEY SubjectId (SubjectId),
  CONSTRAINT usersubject_ibfk_1 FOREIGN KEY (UserId) REFERENCES users(Id),
  CONSTRAINT usersubject_ibfk_2 FOREIGN KEY (SubjectId) REFERENCES subjects(SubjectId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
