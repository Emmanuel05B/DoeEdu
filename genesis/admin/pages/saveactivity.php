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
$subject  = $_POST['subject'] ?? ''; // now assuming this is SubjectId
$chapter  = $_POST['chapter'] ?? '';
$group    = $_POST['group'] ?? '';
$title    = $_POST['activity_title'] ?? '';
$dueDate  = $_POST['due_date'] ?? '';
$dueTime  = $_POST['due_time'] ?? '';
$questions = $_POST['questions'] ?? [];
$instructions = $_POST['instructions'] ?? "This quiz must be completed in one sitting. Answer all questions before submitting. Once completed, you can access the memo. Ensure you read each question carefully. No external help allowed.";


// Handle the optional image upload for the activity
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


// Handle the optional memo upload (PDF only)
$memoPath = null;
if (isset($_FILES['memo_file']) && $_FILES['memo_file']['error'] === 0) {
    $uploadsDir = "../uploads/memos/";
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0755, true);
    }

    $fileExt = strtolower(pathinfo($_FILES['memo_file']['name'], PATHINFO_EXTENSION));
    if ($fileExt !== 'pdf') {
        die("Only PDF files are allowed for the memo.");
    }

    $memoFilename = time() . '_' . basename($_FILES['memo_file']['name']);
    $memoFilepath = $uploadsDir . $memoFilename;

    if (move_uploaded_file($_FILES['memo_file']['tmp_name'], $memoFilepath)) {
        $memoPath = $memoFilepath;
    } else {
        die("Failed to upload memo file.");
    }
}

// Calculate total marks (e.g., 1 mark per question)
$totalMarks = count($questions);
$createdAt = date('Y-m-d H:i:s');

// Start transaction
$connect->begin_transaction();

try {
    // Insert into onlineactivities
    $stmt = $connect->prepare("
        INSERT INTO onlineactivities 
        (TutorId, SubjectId, Grade, Topic, Title, Instructions, TotalMarks, DueDate, CreatedAt, ImagePath, MemoPath, GroupName) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    if (!$stmt) {
        throw new Exception("Prepare failed: (" . $connect->errno . ") " . $connect->error);
    }

    $dueDateTime = $dueDate . ' ' . ($dueTime ?? '00:00:00'); // combine date and time
    $stmt->bind_param(
        "iissssssssss",
        $tutorId,
        $subject,     // SubjectId
        $grade,
        $chapter,
        $title,
        $instructions,
        $totalMarks,
        $dueDateTime,
        $createdAt,
        $imagePath,
        $memoPath,
        $group        // <-- store the group here
    );

    if (!$stmt->execute()) {
        throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }

    $activityId = $stmt->insert_id;
    $stmt->close();

    // Insert questions
    $qstmt = $connect->prepare("
        INSERT INTO onlinequestions 
        (ActivityId, QuestionText, OptionA, OptionB, OptionC, OptionD, CorrectAnswer) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
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
