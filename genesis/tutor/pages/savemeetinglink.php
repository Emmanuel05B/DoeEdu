<?php
session_start();
include(__DIR__ . "/../../partials/connect.php");

// Check if tutor is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

$tutorId = $_SESSION['user_id'];

// Get POST data safely
$sessionId = $_POST['session_id'] ?? '';
$meetingLink = trim($_POST['meeting_link'] ?? '');

// Basic validation
if (empty($sessionId) || empty($meetingLink)) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Missing Information',
        'text' => 'Session ID or meeting link is missing.'
    ];
    header("Location: schedule.php");
    exit();
}

// Validate URL format
if (!filter_var($meetingLink, FILTER_VALIDATE_URL)) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Invalid URL',
        'text' => 'Please provide a valid meeting link.'
    ];
    header("Location: schedule.php");
    exit();
}

// Update the session with the meeting link
$stmt = $connect->prepare("
    UPDATE tutorsessions 
    SET MeetingLink = ? 
    WHERE SessionId = ? AND TutorId = ?
");
$stmt->bind_param("sii", $meetingLink, $sessionId, $tutorId);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    $_SESSION['alert'] = [
        'type' => 'success',
        'title' => 'Saved!',
        'text' => 'Meeting link has been shared successfully.'
    ];
} else {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Failed!',
        'text' => 'Could not share the meeting link. Make sure the session exists.'
    ];
}

$stmt->close();
header("Location: schedule.php");
exit();
