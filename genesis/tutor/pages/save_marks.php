<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../partials/connect.php");

    $TeacherId = $_SESSION['user_id'];  // Logged-in teacher

    // Fetch teacher surname
    $stmt = $connect->prepare("SELECT Surname FROM users WHERE Id = ?");
    $stmt->bind_param("i", $TeacherId);
    $stmt->execute();
    $stmt->bind_result($Surname);
    $stmt->fetch();
    $stmt->close();

    // Form data
    $learner       = (int) $_POST['learnerId'];
    $subject       = (int) $_POST['subjectId'];
    $activityname  = trim($_POST['activityname']);
    $chaptername   = trim($_POST['chaptername']);
    $activitytotal = (int) $_POST['activitytotal'];
    $marksobtained = (int) $_POST['marksobtained'];
    $group         = "Indiv"; // individual activity

    // Validation
    if (empty($activityname) || empty($chaptername) || $activitytotal <= 0) {
        die("All fields are required and total must be greater than 0.");
    }

    // Get grade
    $getGradeId = $connect->prepare("SELECT GradeId FROM subjects WHERE SubjectId = ?");
    $getGradeId->bind_param("i", $subject);
    $getGradeId->execute();
    $getGradeId->bind_result($GradeId);
    $getGradeId->fetch();
    $getGradeId->close();

    // Ensure subject exists
    $checkSub = $connect->prepare("SELECT SubjectId FROM subjects WHERE SubjectId = ?");
    $checkSub->bind_param("i", $subject);
    $checkSub->execute();
    $checkSub->store_result();
    if ($checkSub->num_rows === 0) {
        die("Invalid Subject ID: $subject. Cannot create activity.");
    }
    $checkSub->close();

    try {
        // Start transaction
        $connect->begin_transaction();

        // Insert activity
        $insertStmt = $connect->prepare("
            INSERT INTO activities
            (ActivityName, SubjectId, ActivityDate, MaxMarks, Creator, Grade, ChapterName, GroupName)
            VALUES (?, ?, NOW(), ?, ?, ?, ?, ?)
        ");
        $insertStmt->bind_param("siissss",
            $activityname,
            $subject,
            $activitytotal,
            $Surname,
            $GradeId,
            $chaptername,
            $group
        );
        $insertStmt->execute();
        $activityId = $insertStmt->insert_id;
        $insertStmt->close();

        // Insert learner marks
        $attendance        = "present";
        $attendanceReason  = "None";
        $submission        = "Yes";
        $submissionReason  = "None";

        $stmt2 = $connect->prepare("
          INSERT INTO learneractivitymarks 
          (LearnerId, ActivityId, MarkerId, MarksObtained, DateAssigned, Attendance, AttendanceReason, Submission, SubmissionReason) 
          VALUES (?, ?, ?, ?, CURDATE(), ?, ?, ?, ?)
        ");
        $stmt2->bind_param(
            "iiiissss",
            $learner, $activityId, $TeacherId, $marksobtained,
            $attendance, $attendanceReason, $submission, $submissionReason
        );
        $stmt2->execute();
        $stmt2->close();

        // Commit
        $connect->commit();

        // Set success message for learner profile
        $_SESSION['successMarks'] = "Activity and marks saved successfully!";
        header("Location: learnerprofile.php?id=$learner");
        exit();

    } catch (Exception $e) {
        $connect->rollback();
        
        die("Transaction failed: " . $e->getMessage());
    }

?>
