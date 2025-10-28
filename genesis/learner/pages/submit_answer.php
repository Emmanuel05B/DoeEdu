<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");

header('Content-Type: application/json');



$learnerId  = $_POST['learnerId'] ?? 0;
$questionId = $_POST['questionId'] ?? 0;
$subjectId = $_POST['sid'] ?? 0;  ///newly added id...
$levelId    = intval($_POST['levelId'] ?? 0);
$subject    = $_POST['subject'] ?? '';
$grade      = $_POST['grade'] ?? '';
$chapter    = $_POST['chapter'] ?? '';
$timeTaken  = intval($_POST['timeTaken'] ?? 0);
$answer     = $_POST['answer'] ?? '';

if (!$learnerId || !$questionId || !$levelId || !$answer) {
    echo json_encode(['status'=>'error','message'=>'Missing parameters']);
    exit;
}

$connect->begin_transaction();

try {
    // 1. Get correct answer
    $stmt = $connect->prepare("SELECT Answer FROM practicequestions WHERE Id=?");
    $stmt->bind_param("i", $questionId);
    $stmt->execute();
    $correctAnswer = $stmt->get_result()->fetch_assoc()['Answer'] ?? '';
    $stmt->close();

    $isCorrect = ($answer === $correctAnswer) ? 1 : 0;

    // 2. Mark question complete
    $stmtLp = $connect->prepare("
        INSERT INTO learnerpracticequestions (LearnerId, QuestionId, Status)
        VALUES (?, ?, 'complete')
        ON DUPLICATE KEY UPDATE Status='complete'
    ");
    $stmtLp->bind_param("ii", $learnerId, $questionId);
    $stmtLp->execute();
    $stmtLp->close();

    // 3. Fetch learnerlevel for this specific chapter
    $stmtLvl = $connect->prepare("
        SELECT * FROM learnerlevel 
        WHERE LearnerId=? AND LevelId=? AND ChapterName=? 
        FOR UPDATE
    ");
    $stmtLvl->bind_param("iis", $learnerId, $levelId, $chapter);
    $stmtLvl->execute();
    $levelData = $stmtLvl->get_result()->fetch_assoc();
    $stmtLvl->close();

    // 4. Count total questions for this level and chapter
    $stmtTotal = $connect->prepare("
        SELECT COUNT(*) AS total 
        FROM practicequestions 
        WHERE GradeName=? AND SubjectName=? AND Chapter=? AND LevelId=?
    ");
    $stmtTotal->bind_param("sssi", $grade, $subject, $chapter, $levelId);
    $stmtTotal->execute();
    $totalQuestions = $stmtTotal->get_result()->fetch_assoc()['total'] ?? 25;
    $stmtTotal->close();

    // Initialize / update totals
    $currentScore = $isCorrect;
    $numCompleted = 1;
    $numAttempts  = 1;
    $totalTimeAccum = $timeTaken;

    if ($levelData) {
        $currentScore += $levelData['Mark'];
        $numCompleted += $levelData['NumberQuestionsComplete'];
        $numAttempts   = $levelData['NumberAttempts'];
        $totalTimeAccum += $levelData['TotalTimeTaken'];
    }

    $numLeft = max($totalQuestions - $numCompleted, 0);

    // Check completion
    $passMessage = '';  
    $failMessage = '';
    $completeFlag = 0;

    if ($numCompleted >= $totalQuestions) {
        $passPercent = ($currentScore / $totalQuestions) * 100;
        if ($passPercent >= 70) {
            $completeFlag = 1;

            //congratulate them on passing the level and let them know that the memo is now available
            // direct back to setpicker.php?subjectId=subjectid here
            // then ready to start a new level

            $passMessage = '🎉 Congratulations! You have passed this level. The memo is now available.';
        } else {
            // Repeat level
            $currentScore = 0;
            $numCompleted = 0;
            $numLeft = $totalQuestions;
            $numAttempts += 1;

            //Motivate them to repeat, the level and let them know that the memo will only be available once hey pass

            // delete the records already recorded for this level but only in the learnerpracticequestions table becouse the learner is reapeating
            // and we are interested in the new scores.
            //delete from learnerpracticequestions where LearnerId = hhh AND QuestionId = 

            // Optional: Delete previous learnerpracticequestions for this level and chapter
            $stmtDel = $connect->prepare("
                DELETE lpq FROM learnerpracticequestions lpq
                JOIN practicequestions pq ON lpq.QuestionId = pq.Id
                WHERE lpq.LearnerId=? AND pq.LevelId=? AND pq.Chapter=?
            ");
            $stmtDel->bind_param("iis", $learnerId, $levelId, $chapter);
            $stmtDel->execute();
            $stmtDel->close();

            $failMessage = '❌ You did not pass this level. You are required to repeat it.';
        }
    }

    // Update or insert learnerlevel with ChapterName
    if ($levelData) {
        // Update existing record for this learner, level, and chapter
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
            $totalTimeAccum,
            $numCompleted,
            $numLeft,
            $levelData['Id']
        );
        $stmtUpdate->execute();
        $stmtUpdate->close();
    } else {
        // Insert new record because no row exists for this chapter yet
        $stmtInsert = $connect->prepare("
            INSERT INTO learnerlevel
            (LearnerId, LevelId, ChapterName, Complete, Mark, NumberAttempts, TotalTimeTaken, NumberQuestionsComplete, NumberQuestionsLeft)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmtInsert->bind_param(
            "iisiiiiii",
            $learnerId,
            $levelId,
            $chapter,
            $completeFlag,
            $currentScore,
            $numAttempts,
            $totalTimeAccum,
            $numCompleted,
            $numLeft
        );
        $stmtInsert->execute();
        $stmtInsert->close();
    }

    $connect->commit();

    // Return JSON for SweetAlert
    echo json_encode([
        'status' => 'success',
        'isCorrect' => (bool)$isCorrect,
        'score' => $currentScore,
        'numCompleted' => $numCompleted,
        'numLeft' => $numLeft,
        'totalQuestions' => $totalQuestions,
        'totalTimeFormatted' => sprintf('%02d:%02d', floor($totalTimeAccum/60), $totalTimeAccum%60),
        'progressPercent' => round(($numCompleted / max($totalQuestions,1))*100),
        'subjectId' => $subjectId,
        'passMessage' => $passMessage ?? '',
        'failMessage' => $failMessage ?? ''
    ]);

} catch (Exception $e) {
    $connect->rollback();
    echo json_encode(['status'=>'error','message'=>'Error processing answer: '.$e->getMessage()]);
}
