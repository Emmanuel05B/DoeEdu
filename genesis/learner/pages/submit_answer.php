<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../partials/connect.php");

$learnerId  = $_POST['learnerId'] ?? 0;
$questionId = $_POST['questionId'] ?? 0;
$levelId    = intval($_POST['levelId'] ?? 0);
$timeTaken  = intval($_POST['timeTaken'] ?? 0);

if (!$learnerId || !$questionId || !$levelId) {
    die("Missing parameters.");
}

$connect->begin_transaction();

try {
    // 1. Get correct answer
    $stmt = $connect->prepare("SELECT Answer FROM practicequestions WHERE Id = ?");
    if (!$stmt) throw new Exception($connect->error);
    $stmt->bind_param("i", $questionId);
    $stmt->execute();
    $correctAnswer = $stmt->get_result()->fetch_assoc()['Answer'] ?? null;
    $stmt->close();

    $submittedAnswer = $_POST['answer'] ?? '';
    $isCorrect = ($submittedAnswer === $correctAnswer) ? 1 : 0;

    // 2. Mark question as complete
    $stmtLp = $connect->prepare("
        INSERT INTO learnerpracticequestions (LearnerId, QuestionId, Status)
        VALUES (?, ?, 'complete')
        ON DUPLICATE KEY UPDATE Status='complete'
    ");
    $stmtLp->bind_param("ii", $learnerId, $questionId);
    $stmtLp->execute();
    $stmtLp->close();

    // 3. Fetch learnerlevel
    $stmtLvl = $connect->prepare("SELECT * FROM learnerlevel WHERE LearnerId=? AND LevelId=? FOR UPDATE");
    $stmtLvl->bind_param("ii", $learnerId, $levelId);
    $stmtLvl->execute();
    $levelData = $stmtLvl->get_result()->fetch_assoc();
    $stmtLvl->close();

    // 4. Count total questions
    $stmtTotal = $connect->prepare("SELECT COUNT(*) AS total FROM practicequestions WHERE LevelId=?");
    $stmtTotal->bind_param("i", $levelId);
    $stmtTotal->execute();
    $totalQuestions = $stmtTotal->get_result()->fetch_assoc()['total'] ?? 1;
    $stmtTotal->close();

    // Initialize
    $currentScore = $isCorrect;
    $numCompleted = 1;
    $numLeft = $totalQuestions - 1;
    $numAttempts = 1;
    $totalTime = $timeTaken;

    if ($levelData) {
        $currentScore += $levelData['Mark'];
        $numCompleted += $levelData['NumberQuestionsComplete'];
        $numLeft = max($totalQuestions - $numCompleted, 0);
        $numAttempts = $levelData['NumberAttempts'];
        $totalTime += $levelData['TotalTimeTaken'];
    }

    // 5. Check completion
    $completeFlag = 0;
    if ($numCompleted >= $totalQuestions) {
        $passPercent = ($currentScore / $totalQuestions) * 100;
        if ($passPercent >= 75) {
            $completeFlag = 1;
        } else {
            $currentScore = 0;
            $numCompleted = 0;
            $numLeft = $totalQuestions;
            $numAttempts += 1;
        }
    }

    // 6. Update or Insert with exact column sequence
    if ($levelData) {
        $stmtUpdate = $connect->prepare("
            UPDATE learnerlevel
            SET Complete=?, Mark=?, NumberAttempts=?, TotalTimeTaken=?, NumberQuestionsComplete=?, NumberQuestionsLeft=?
            WHERE Id=?
        ");
        $stmtUpdate->bind_param(
            "iiiiiii",
            $completeFlag,
            $currentScore,
            $numAttempts,
            $totalTime,
            $numCompleted,
            $numLeft,
            $levelData['Id']
        );
        $stmtUpdate->execute();
        $stmtUpdate->close();
    } else {
        $stmtInsert = $connect->prepare("
            INSERT INTO learnerlevel
            (LearnerId, LevelId, Complete, Mark, NumberAttempts, TotalTimeTaken, NumberQuestionsComplete, NumberQuestionsLeft)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmtInsert->bind_param(
            "iiiiiiii",
            $learnerId,
            $levelId,
            $completeFlag,
            $currentScore,
            $numAttempts,
            $totalTime,
            $numCompleted,
            $numLeft
        );
        $stmtInsert->execute();
        $stmtInsert->close();
    }

    $connect->commit();

    // Redirect back for SweetAlert
    $answerStatus = $isCorrect ? "correct" : "wrong";
    $redirectUrl = "training.php?subject=" . urlencode($_POST['subject']) .
                   "&grade=" . urlencode($_POST['grade']) .
                   "&chapter=" . urlencode($_POST['chapter']) .
                   "&level=" . $levelId .
                   "&questionId=" . $questionId .
                   "&answer=" . $answerStatus;
    header("Location: $redirectUrl");
    exit();

} catch (Exception $e) {
    $connect->rollback();
    die("Error processing answer: " . $e->getMessage());
}
?>
