<?php
session_start();
include(__DIR__ . "/../../partials/connect.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

$learnerId = $_SESSION['user_id'];
$sessionId = $_GET['sessionid'] ?? null;

if (!$sessionId) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Error!',
        'message' => 'No session specified.'
    ];
    header("Location: mytutors.php");
    exit();
}

// Fetch session details
$stmt = $connect->prepare("
    SELECT MeetingLink, Status, SlotDateTime
    FROM tutorsessions
    WHERE SessionId = ? AND LearnerId = ?
");
$stmt->bind_param("ii", $sessionId, $learnerId);
$stmt->execute();
$session = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$session) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Error!',
        'message' => 'Session not found.'
    ];
    header("Location: mytutors.php");
    exit();
}

// Check session status
$now = new DateTime();
$slotTime = new DateTime($session['SlotDateTime']);

if ($session['Status'] !== 'Confirmed') {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Cannot Join!',
        'message' => 'This session is not confirmed yet.'
    ];
    header("Location: mytutors.php");
    exit();
}

// Optional: mark session as "Completed" if time has passed
if ($now >= $slotTime) {
    $updateStmt = $connect->prepare("
        UPDATE tutorsessions 
        SET Status = 'Completed', Attendance = 'Joined'
        WHERE SessionId = ?
    ");

    if (!$updateStmt) {
    die("Prepare failed: " . $connect->error);
}
    $updateStmt->bind_param("i", $sessionId);
    $updateStmt->execute();
    $updateStmt->close();
}

// Redirect learner to meeting link
$meetingLink = $session['MeetingLink'];

if (!$meetingLink) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'No Meeting Link',
        'message' => 'The meeting link is not available.'
    ];
    header("Location: mytutors.php");
    exit();
}

// Optionally, mark that learner attended if time is now
$updateAttendance = $connect->prepare("
    UPDATE tutorsessions
    SET Attendance = 'Joined'
    WHERE SessionId = ? AND LearnerId = ?
");
$updateAttendance->bind_param("ii", $sessionId, $learnerId);
$updateAttendance->execute();
$updateAttendance->close();

// Redirect to the actual meeting
header("Location: " . $meetingLink);
exit();
