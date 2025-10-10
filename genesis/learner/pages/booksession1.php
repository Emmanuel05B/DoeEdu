<?php
session_start();
include(__DIR__ . "/../../partials/connect.php");

$learnerId = $_SESSION['user_id'];
$tutorId = $_POST['tutor_id'] ?? '';
$subject = $_POST['subject'] ?? '';
$slot = $_POST['slot'] ?? '';
$notes = $_POST['notes'] ?? '';

$status = "";
$message = "";

if (empty($tutorId) || empty($subject) || empty($slot)) {
    $status = "error";
    $message = "Subject and slot selection are required.";
} else {
    $dt = DateTime::createFromFormat('Y-m-d H:i', $slot);
    if (!$dt) {
        $status = "error";
        $message = "Invalid date/time format.";
    } else {
        $slotDateTime = $dt->format('Y-m-d H:i:s');

        // Check slot availability
        $checkStmt = $connect->prepare("SELECT COUNT(*) FROM tutorsessions WHERE TutorId=? AND SlotDateTime=?");
        $checkStmt->bind_param("is", $tutorId, $slotDateTime);
        $checkStmt->execute();
        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->close();

        if ($count > 0) {
            $status = "error";
            $message = "This slot has just been taken. Please choose another.";
        } else {
            $insertStmt = $connect->prepare("INSERT INTO tutorsessions (TutorId, LearnerId, SlotDateTime, Subject, Notes, Status) VALUES (?, ?, ?, ?, ?, 'Pending')");
            $insertStmt->bind_param("iisss", $tutorId, $learnerId, $slotDateTime, $subject, $notes);
            if ($insertStmt->execute()) {
                $status = "success";
                $message = "Session booked! It’s pending confirmation.";
            } else {
                $status = "error";
                $message = "Something went wrong. Please try again.";
            }
            $insertStmt->close();
        }
    }
}

// Redirect back to mytutors.php with status and message
header("Location: mytutors.php?status={$status}&message=" . urlencode($message));
exit();
?>
