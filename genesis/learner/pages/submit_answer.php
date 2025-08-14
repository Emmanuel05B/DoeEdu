<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../partials/connect.php");

$learnerId   = $_POST['learnerId'] ?? 0;
$questionId  = $_POST['questionId'] ?? 0;
$answer      = $_POST['answer'] ?? '';
$levelId     = intval($_POST['levelId'] ?? 0);

if (!$learnerId || !$questionId || !$answer || !$levelId) {
    die("Invalid data submitted.");
}

// 1. Check if the answer is correct
$stmt = $connect->prepare("SELECT Answer FROM practicequestions WHERE Id = ?");
$stmt->bind_param("i", $questionId);
$stmt->execute();
$correctAnswer = $stmt->get_result()->fetch_assoc()['Answer'] ?? null;
$stmt->close();

$isCorrect = ($answer === $correctAnswer) ? 1 : 0;

// 2. Fetch learnerlevel record or create it if it doesn’t exist
$stmtLvl = $connect->prepare("SELECT * FROM learnerlevel WHERE LearnerId = ? AND LevelId = ?");
$stmtLvl->bind_param("ii", $learnerId, $levelId);
$stmtLvl->execute();
$levelData = $stmtLvl->get_result()->fetch_assoc();
$stmtLvl->close();

$totalQuestionsStmt = $connect->prepare("SELECT COUNT(*) AS total FROM practicequestions WHERE LevelId = ?");
$totalQuestionsStmt->bind_param("i", $levelId);
$totalQuestionsStmt->execute();
$totalQuestions = $totalQuestionsStmt->get_result()->fetch_assoc()['total'] ?? 1;
$totalQuestionsStmt->close();

if ($levelData) {
    // Update stats
    $currentScore = $levelData['Mark'] + $isCorrect;
    $numCompleted = $levelData['NumberQuestionsComplete'] + 1;
    $numLeft      = max($levelData['NumberQuestionsLeft'] - 1, 0);
    $numAttempts  = $levelData['NumberAttempts'];

    $completeFlag = 0;

    // Check if level attempt finished
    if ($numCompleted >= $totalQuestions) {
        $passPercent = ($currentScore / $totalQuestions) * 100;
        if ($passPercent >= 75) {
            $completeFlag = 1; // passed level, unlock next
        } else {
            // Failed level, repeat
            $currentScore = 0;
            $numCompleted = 0;
            $numLeft      = $totalQuestions;
            $numAttempts += 1;
        }
    }

    $stmtUpdate = $connect->prepare("
        UPDATE learnerlevel 
        SET Mark = ?, NumberQuestionsComplete = ?, NumberQuestionsLeft = ?, NumberAttempts = ?, Complete = ?
        WHERE Id = ?
    ");
    $stmtUpdate->bind_param("iiiiii", $currentScore, $numCompleted, $numLeft, $numAttempts, $completeFlag, $levelData['Id']);
    $stmtUpdate->execute();
    $stmtUpdate->close();

} else {
    // First time attempt
    $stmtInsert = $connect->prepare("
        INSERT INTO learnerlevel (LearnerId, LevelId, Mark, NumberAttempts, NumberQuestionsComplete, NumberQuestionsLeft, Complete)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $completeFlag = 0;
    $stmtInsert->bind_param("iiiiiii", $learnerId, $levelId, $isCorrect, 1, 1, $totalQuestions - 1, $completeFlag);
    $stmtInsert->execute();
    $stmtInsert->close();
}

// Redirect back to training page
header("Location: training.php?level=$levelId");
exit();
?>
