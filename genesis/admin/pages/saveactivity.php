<!DOCTYPE html>
<html>

<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../../common/login.php");
    exit();
}

include('../../partials/connect.php');  // Make sure this sets $connect as your DB connection

?>

<?php include("../adminpartials/head.php"); ?>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

<body class="hold-transition skin-blue sidebar-mini">

<?php

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

    echo '<script>
        Swal.fire({
            icon: "success",
            title: "Activity saved successfully.",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "OK"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "classes.php";
            }
        });
    </script>';

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

<div class="wrapper"></div>

</body>
</html>
