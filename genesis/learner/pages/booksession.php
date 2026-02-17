

<?php

require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");


$learnerId = $_SESSION['user_id'];
$tutorId = $_POST['tutor_id'] ?? '';
$subject = $_POST['subject'] ?? '';
$slot = $_POST['slot'] ?? '';
$notes = $_POST['notes'] ?? '';
$grade = $_POST['grade'] ?? '';

$status = "";
$message = "";
$attachmentPath = null; 

//  Handle file upload ---
$attachmentPath = null; 

if (!empty($_FILES['attachment']['name'])) {
    $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
    $fileType = $_FILES['attachment']['type'];
    $fileSize = $_FILES['attachment']['size'];

    if (!in_array($fileType, $allowedTypes)) {
        $status = "error";
        $message = "Invalid file type. Only PDF or image files are allowed.";
    } elseif ($fileSize > 5 * 1024 * 1024) {
        $status = "error";
        $message = "File too large. Max 5MB.";
    } else {
        $uploadDir = ATTACHMENTS_PATH . '/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $fileName = time() . '_' . basename($_FILES['attachment']['name']);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $targetFile)) {
            $attachmentPath = ATTACHMENTS_URL . '/' . $fileName; // Path saved in DB
        } else {
            $status = "error";
            $message = "Failed to upload the file.";
        }
    }
}


// Only proceed if no file errors
if ($status !== "error") {
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
                // Check if this learner had a previously declined session
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
                    $message = "You cannot rebook a session that was previously declined.";
                } else {
                    // Insert new session
                    $insertStmt = $connect->prepare("
                        INSERT INTO tutorsessions 
                        (TutorId, LearnerId, SlotDateTime, Subject, Grade, Notes, AttachmentPath, Status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending')
                    ");
                    $insertStmt->bind_param("iisssss", 
                        $tutorId, $learnerId, $slotDateTime, $subject, $grade, $notes, $attachmentPath
                    );

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
}

// Redirect back to mytutors.php with status and message
header("Location: learnerindex.php?status={$status}&message=" . urlencode($message));
exit();
?>

