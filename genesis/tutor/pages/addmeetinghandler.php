<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");


if (isset($_POST['submit'])) {
    $tutorId = $_SESSION['user_id'];
    $classId = $_POST['classid'];
    $grade = $_POST['grade'];
    $subjectId = $_POST['subjectid'];
    $groupName = $_POST['groupname'];
    $subjectName = $_POST['subjectname'];
    $meetingLink = trim($_POST['meetinglink']);
    $notes = trim($_POST['notes']);

    if (empty($meetingLink)) {
        die("Meeting link cannot be empty.");
    }

    // Start transaction for safety
    $connect->begin_transaction();

    try {
        // Step 1: Mark any existing active meeting for this class as 'Replaced'
        $update = $connect->prepare("
            UPDATE classmeetings 
            SET Status = 'Replaced' 
            WHERE ClassId = ? AND Status = 'Active'
        ");
        $update->bind_param("i", $classId);
        $update->execute();
        $update->close();

        // Step 2: Insert the new meeting link
        $insert = $connect->prepare("
            INSERT INTO classmeetings (ClassId, TutorId, Grade, SubjectId, GroupName, MeetingLink, Status, Notes)
            VALUES (?, ?, ?, ?, ?, ?, 'Active', ?)
        ");
        $insert->bind_param("iisssss", $classId, $tutorId, $grade, $subjectId, $groupName, $meetingLink, $notes);
        $insert->execute();
        $insert->close();

        $connect->commit();

        // Redirect with success
        header("Location: tutorindex.php?added=1");
        exit();

    } catch (Exception $e) {
        $connect->rollback();
        die("Error: " . $e->getMessage());
        
    }
} else {
    header("Location: tutorindex.php?error=1");
    exit();
}
?>
