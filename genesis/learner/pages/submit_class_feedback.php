<?php
session_start();
include(__DIR__ . "/../../partials/connect.php");

if (!isset($_SESSION['email']) || !isset($_SESSION['user_id'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}


//this handler is for submitting feedback for a class meeting/session that happened... this meeting was for a whole class and not one on one.

$LearnerId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $MeetingId = $_POST['MeetingId'] ?? '';
    $TutorId = $_POST['TutorId'] ?? '';
    $SubjectId = $_POST['SubjectId'] ?? '';
    $Clarity = $_POST['ClarityRating'] ?? '';
    $Engagement = $_POST['EngagementRating'] ?? '';
    $Overall = $_POST['OverallSatisfaction'] ?? '';
    $Comments = trim($_POST['Comments'] ?? '');

    if (empty($MeetingId) || empty($TutorId) || empty($SubjectId) ||
        empty($Clarity) || empty($Engagement) || empty($Overall)) {
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_message'] = 'Please fill in all required fields.';
        header("Location: class.php");
        exit();
    }

    // Prevent duplicate feedback
    $check = $connect->prepare("SELECT FeedbackId FROM meetingfeedback WHERE LearnerId = ? AND MeetingId = ?");
    $check->bind_param("ii", $LearnerId, $MeetingId);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $check->close();
        $_SESSION['alert_type'] = 'info';
        $_SESSION['alert_message'] = 'You have already submitted feedback for this meeting.';
        header("Location: class.php");
        exit();
    }
    $check->close();

    // Insert feedback
    $stmt = $connect->prepare("
        INSERT INTO meetingfeedback 
        (MeetingId, LearnerId, TutorId, SubjectId, ClarityRating, EngagementRating, OverallSatisfaction, Comments, SubmittedAt)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    $stmt->bind_param("iiiiiiis", 
        $MeetingId, $LearnerId, $TutorId, $SubjectId, 
        $Clarity, $Engagement, $Overall, $Comments
    );

    if ($stmt->execute()) {
        $_SESSION['alert_type'] = 'success';
        $_SESSION['alert_message'] = 'Thank you! Your feedback has been submitted successfully.';
    } else {
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_message'] = 'Error submitting feedback. Please try again.';
    }

    $stmt->close();
    header("Location: class.php");
    exit();
} else {
    header("Location: class.php");
    exit();
}
?>
