<?php
session_start();
include(__DIR__ . "/../../partials/connect.php");

$learnerId = $_SESSION['user_id'];
$tutorId = $_POST['tutor_id'] ?? '';
$subject = $_POST['subject'] ?? '';
$slot = $_POST['slot'] ?? '';
$notes = $_POST['notes'] ?? '';
$grade = $_POST['grade'] ?? '';

$status = "";
$message = "";

if (empty($tutorId) || empty($subject) || empty($slot) || empty($grade)) {
    $status = "error";
    $message = "Subject and slot selection are required.";
} else {
    $dt = DateTime::createFromFormat('Y-m-d H:i', $slot);
    if (!$dt) {
        $status = "error";
        $message = "Invalid date/time format.";
    } else {
        $slotDateTime = $dt->format('Y-m-d H:i:s');

        // Check if the slot is taken (Pending or Confirmed) 

        $checkStmt = $connect->prepare("
            SELECT COUNT(*) 
            FROM tutorsessions 
            WHERE TutorId = ? 
              AND SlotDateTime = ? 
              AND Status IN ('Pending','Confirmed')
        ");
        $checkStmt->bind_param("is", $tutorId, $slotDateTime);
        $checkStmt->execute();
        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->close();

        if ($count > 0) {
            $status = "error";
            $message = "This slot has just been taken. Please choose another.";
        } else {
            // Check if this learner already had a declined session at this slot

            $declinedStmt = $connect->prepare("
                SELECT COUNT(*) 
                FROM tutorsessions 
                WHERE TutorId = ? 
                  AND LearnerId = ? 
                  AND SlotDateTime = ? 
                  AND Status = 'Declined'
            ");
            $declinedStmt->bind_param("iis", $tutorId, $learnerId, $slotDateTime);
            $declinedStmt->execute();
            $declinedStmt->bind_result($declinedCount);
            $declinedStmt->fetch();
            $declinedStmt->close();

            if ($declinedCount > 0) {
                $status = "error";
                $message = "You cannot rebook a session that you previously booked and was declined.";
            } else {
                
                // always insert a new record
                // even if this slot was previously declined for another learner
                $insertStmt = $connect->prepare("
                    INSERT INTO tutorsessions (TutorId, LearnerId, SlotDateTime, Subject, Grade, Notes, Status) 
                    VALUES (?, ?, ?, ?, ?, ?, 'Pending')
                ");
                $insertStmt->bind_param("iissss", $tutorId, $learnerId, $slotDateTime, $subject, $grade, $notes); 


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
}

// Redirect back to mytutors.php with status and message
header("Location: mytutors.php?status={$status}&message=" . urlencode($message));
exit();
?>
