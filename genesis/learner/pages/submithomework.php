<!DOCTYPE html>
<html>

<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");
include_once(COMMON_PATH . "/../partials/head.php"); 

?>

<!-- SweetAlert and jQuery scripts exactly like yours -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

<body class="hold-transition skin-blue sidebar-mini">

<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['activityId']) || !isset($_POST['answers'])) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Invalid submission.',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'learnerdashboard.php';
        });
    </script>";
    exit();
}

$userId = $_SESSION['user_id'];
$activityId = intval($_POST['activityId']);
$answers = $_POST['answers'];

// Prevent duplicate submissions
$checkStmt = $connect->prepare("SELECT Id FROM learnerhomeworkresults WHERE UserId = ? AND ActivityId = ?");
$checkStmt->bind_param("ii", $userId, $activityId);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    $checkStmt->close();

    header("Location: viewhomework.php?activityId=" . urlencode($activityId) . "&alreadysubmitted=1");
    exit;
}
$checkStmt->close();

$totalQuestions = count($answers);
$correctAnswers = 0;

// Save each answer
$insertStmt = $connect->prepare("INSERT INTO learneranswers (UserId, ActivityId, QuestionId, SelectedAnswer) VALUES (?, ?, ?, ?)");
$insertStmt->bind_param("iiis", $userId, $activityId, $questionId, $selectedAnswer);

foreach ($answers as $questionId => $selectedAnswer) {
    $questionId = intval($questionId);
    $selectedAnswer = strtoupper($selectedAnswer);

    $insertStmt->execute();

    // Check correctness
    $correctStmt = $connect->prepare("SELECT CorrectAnswer FROM onlinequestions WHERE id = ? AND ActivityId = ?");
    $correctStmt->bind_param("ii", $questionId, $activityId);
    $correctStmt->execute();
    $correctStmt->bind_result($correctAnswer);
    if ($correctStmt->fetch() && $correctAnswer === $selectedAnswer) {
        $correctAnswers++;
    }
    $correctStmt->close();
}
$insertStmt->close();

// Final score
$score = ($totalQuestions > 0) ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0.00;

// Insert result
$resultStmt = $connect->prepare("INSERT INTO learnerhomeworkresults (UserId, ActivityId, Score) VALUES (?, ?, ?)");
$resultStmt->bind_param("iid", $userId, $activityId, $score);
$resultStmt->execute();
$resultStmt->close();

$connect->close();

// Final SweetAlert....//note that the id at the memo is causing that flag
    header("Location: viewhomework.php?score=" . urlencode($score) . "&activityId=" . urlencode($activityId) . "&submitted=1");
    exit;

?>

<div class="wrapper"></div>
</body>
</html>
