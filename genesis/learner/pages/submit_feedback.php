<?php
session_start();
include(__DIR__ . "/../../partials/connect.php");

// Get POST data from the simplified feedback modal
$sessionId     = (int) ($_POST['SessionId'] ?? 0);
$tutorId       = (int) ($_POST['TutorId'] ?? 0);
$learnerId     = (int) ($_POST['LearnerId'] ?? 0);
$grade         = $_POST['Grade'] ?? '';           // now a string
$subjectName       = $_POST['Subject'] ?? '';         // optional if you want to store subject name
$clarity       = (int) ($_POST['Clarity'] ?? 0);
$engagement    = (int) ($_POST['Engagement'] ?? 0);
$understanding = $_POST['Understanding'] ?? '';
$overall       = (int) ($_POST['OverallRating'] ?? 0);

// Validate required fields
if (empty($sessionId) || empty($clarity) || empty($engagement) || empty($understanding) || empty($overall) || empty($grade)) {
    $_SESSION['alert'] = [
        'type' => 'error', 
        'title' => 'Error', 
        'message' => 'Please complete all required fields before submitting.'
    ];
    header("Location: mytutors.php");
    exit();
}

// Insert feedback
$stmt = $connect->prepare("

    INSERT INTO tutorfeedback 
    (SessionId, TutorId, LearnerId, Grade, SubjectName, Clarity, Engagement, Understanding, OverallRating, FeedbackDate)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())

");
if (!$stmt) {
    die("Prepare failed: (" . $connect->errno . ") " . $connect->error);
}

$stmt->bind_param(
    "iiisssssi", 
    $sessionId, 
    $tutorId, 
    $learnerId, 
    $grade, 
    $subjectName, 
    $clarity, 
    $engagement, 
    $understanding, 
    $overall
);

if ($stmt->execute()) {    //come..this will affect other learners
    // Mark session as hidden so it no longer appears in the main list
    $update = $connect->prepare("UPDATE tutorsessions SET Hidden = 1 WHERE SessionId = ?");
    $update->bind_param("i", $sessionId);
    $update->execute();
    $update->close();

    $_SESSION['alert'] = [
        'type' => 'success', 
        'title' => 'Thank you!', 
        'message' => 'Your feedback has been submitted successfully.'
    ];
} else {
    $_SESSION['alert'] = [
        'type' => 'error', 
        'title' => 'Error', 
        'message' => 'Could not save feedback. Please try again.'
    ];
}

header("Location: mytutors.php");
exit();
?>
