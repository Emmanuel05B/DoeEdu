<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../../common/pages/login.php");
    exit();
}
include(__DIR__ . "/../../partials/connect.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}

$tutorId = $_SESSION['user_id']; // your session should store user id here 

$grade    = $_POST['grade'] ?? '';
$subject  = $_POST['subject'] ?? '';
$chapter  = $_POST['chapter'] ?? '';
$group  = $_POST['group'] ?? '';
$title    = $_POST['activity_title'] ?? '';
$dueDate  = $_POST['due_date'] ?? '';
$dueTime  = $_POST['due_time'] ?? '';
$questions = $_POST['questions'] ?? [];

$instructions = "Default instructions here";

// Handle the optional image upload for the activity (simplified)
$imagePath = null;
if (isset($_FILES['activity_image']) && $_FILES['activity_image']['error'] === 0) {
    $uploadsDir = "../uploads/";
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0755, true);
    }

    $filename = time() . '_' . basename($_FILES['activity_image']['name']);
    $filepath = $uploadsDir . $filename;

    if (move_uploaded_file($_FILES['activity_image']['tmp_name'], $filepath)) {
        $imagePath = $filepath;
    } else {
        die("Failed to upload image.");
    }
}

// Calculate total marks (e.g., 1 mark per question)
$totalMarks = count($questions);
$createdAt = date('Y-m-d H:i:s');

// Start transaction
$connect->begin_transaction();

try {
    // Prepare insert into onlineactivities
    $stmt = $connect->prepare("INSERT INTO onlineactivities (TutorId, SubjectName, Grade, Topic, Title, Instructions, TotalMarks, DueDate, CreatedAt, ImagePath, GroupName) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception("Prepare failed: (" . $connect->errno . ") " . $connect->error);
    }

    // Bind parameters
    $dueDateTime = $dueDate . ' ' . ($dueTime ?? '00:00:00'); // combine date and time if you want datetime, adjust if DueDate column is DATE only
    $stmt->bind_param("isisssissss", $tutorId, $subject, $grade, $chapter, $title, $instructions, $totalMarks, $dueDateTime, $createdAt, $imagePath, $group);

    if (!$stmt->execute()) {
        throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }

    $activityId = $stmt->insert_id;
    $stmt->close();

    // Prepare insert questions
    $qstmt = $connect->prepare("INSERT INTO onlinequestions (ActivityId, QuestionText, OptionA, OptionB, OptionC, OptionD, CorrectAnswer) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$qstmt) {
        throw new Exception("Prepare failed (questions): (" . $connect->errno . ") " . $connect->error);
    }

    foreach ($questions as $q) {
        $qText   = $q['text'] ?? '';
        $optionA = $q['options']['A'] ?? '';
        $optionB = $q['options']['B'] ?? '';
        $optionC = $q['options']['C'] ?? '';
        $optionD = $q['options']['D'] ?? '';
        $correct = $q['correct'] ?? '';

        $qstmt->bind_param("issssss", $activityId, $qText, $optionA, $optionB, $optionC, $optionD, $correct);
        if (!$qstmt->execute()) {
            throw new Exception("Execute failed (questions): (" . $qstmt->errno . ") " . $qstmt->error);
        }
    }

    $qstmt->close();

    // Commit transaction
    $connect->commit();
    
    header("Location: generateactivity.php?gra=" . urlencode($grade) . "&cha=" . urlencode($chapter) . "&group=" . urlencode($group) . "&sub=" . urlencode($subject) . "&save=1");
    exit; 

    
} catch (Exception $e) {
    $connect->rollback();
    // Log error for debugging (optional)
    error_log("Error saving activity: " . $e->getMessage());

    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Failed to save activity',
            text: 'Please try again later.',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location = 'classes.php';
        });
    </script>";
}

$connect->close();

?>

