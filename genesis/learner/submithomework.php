<!DOCTYPE html>
<html>

<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../common/login.php");
    exit();
}

include('../partials/connect.php');
?>

<?php include("learnerpartials/head.php"); ?>

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
 echo "<script>

    Swal.fire({
        icon: 'info',
        title: 'Already Submitted',
        text: 'You have already submitted this homework.',
        showCancelButton: true,
        confirmButtonText: 'View Memo',
        cancelButtonText: 'Go to Dashboard'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'viewmemo.php?activityid={$activityId}';
        } else {
            window.location.href = 'homework.php';
        }
    });
</script>";
exit();

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
echo "<script>

    Swal.fire({
        icon: 'success',
        title: 'Homework Submitted',
        text: 'Score: {$score}%',
        showCancelButton: true,
        confirmButtonText: 'View Memo',
        cancelButtonText: 'Go to Dashboard'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'viewmemo.php?activityid={$activityId}'; 
        } else {
            window.location.href = 'homework.php';
        }
    });
</script>";
?>

<div class="wrapper"></div>
</body>
</html>
