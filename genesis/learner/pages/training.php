<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../common/partials/head.php");
include(__DIR__ . "/../../partials/connect.php");

$learnerId       = $_SESSION['user_id'];

$subject         = $_GET['subject'] ?? '';
$subjectId         = $_GET['sid'] ?? '';

$grade           = $_GET['grade'] ?? '';
$chapter         = $_GET['chapter'] ?? '';
$levelId         = intval($_GET['level'] ?? 0);

$questionIdParam = intval($_GET['questionId'] ?? 0);  //******* Note
$answerStatus    = $_GET['answer'] ?? ''; //******* Note

if (!$subject || !$grade || !$chapter || !$levelId) {
    die("<div class='alert alert-danger' style='margin:20px;'>Missing parameters.</div>");
}

// --- Eligibility check ---
$isEligible = false;
if ($levelId == 1) {
    $isEligible = true;
} else {
    $prevLevelId = $levelId - 1;
    $prevStmt = $connect->prepare("
        SELECT Complete 
        FROM learnerlevel 
        WHERE LearnerId=? AND LevelId=?
    ");
    $prevStmt->bind_param("ii", $learnerId, $prevLevelId);
    $prevStmt->execute();
    $prevComplete = $prevStmt->get_result()->fetch_assoc()['Complete'] ?? 0;
    $isEligible   = ($prevComplete == 1);
}

if (!$isEligible) {
    die("<div class='alert alert-danger' style='margin:20px;'>You are not eligible to access this level yet.</div>");
}



// --- Check if level for this chapter is already completed ---
$stmtCheck = $connect->prepare("
    SELECT Complete 
    FROM learnerlevel 
    WHERE LearnerId=? AND LevelId=? AND ChapterName=?
");
$stmtCheck->bind_param("iis", $learnerId, $levelId, $chapter);
$stmtCheck->execute();
$completedRow = $stmtCheck->get_result()->fetch_assoc();
$stmtCheck->close();

if (!empty($completedRow) && intval($completedRow['Complete']) === 1) {
    // Redirect to setpicker.php with a query parameter
    header("Location: setpicker.php?subjectId=" . urlencode($subjectId) . "&levelCompleted=1");
    exit;
}




// Fetch Level Name
$levelStmt = $connect->prepare("SELECT LevelName FROM level WHERE Id=?");
$levelStmt->bind_param("i", $levelId);
$levelStmt->execute();
$levelName = $levelStmt->get_result()->fetch_assoc()['LevelName'] ?? '';
$levelStmt->close();

// Fetch question
if ($questionIdParam > 0) {
    // Load this exact question (after submission)
    $stmtQ = $connect->prepare("SELECT * FROM practicequestions WHERE Id=?");
    $stmtQ->bind_param("i", $questionIdParam);
    $stmtQ->execute();
    $questionData = $stmtQ->get_result()->fetch_assoc();
    $stmtQ->close();
} else {
    // Load next unanswered question
    $stmtQ = $connect->prepare("
        SELECT pq.* 
        FROM practicequestions pq
        LEFT JOIN learnerpracticequestions lpq 
            ON pq.Id=lpq.QuestionId AND lpq.LearnerId=?
        WHERE pq.GradeName=? 
          AND pq.SubjectName=? 
          AND pq.Chapter=? 
          AND pq.LevelId=? 
          AND (lpq.Status IS NULL OR lpq.Status != 'complete')
        ORDER BY pq.Id ASC
        LIMIT 1
    ");
    $stmtQ->bind_param("isssi", $learnerId, $grade, $subject, $chapter, $levelId);
    $stmtQ->execute();
    $questionData = $stmtQ->get_result()->fetch_assoc();
    $stmtQ->close();

    // Fallback: first question if all answered
    if (!$questionData) {
        $stmtFallback = $connect->prepare("
            SELECT * 
            FROM practicequestions 
            WHERE GradeName=? 
              AND SubjectName=? 
              AND Chapter=? 
              AND LevelId=?
            ORDER BY Id ASC 
            LIMIT 1
        ");
        $stmtFallback->bind_param("sssi", $grade, $subject, $chapter, $levelId);
        $stmtFallback->execute();
        $questionData = $stmtFallback->get_result()->fetch_assoc();
        $stmtFallback->close();
    }
}

// Extract question details
$currentQuestionId = $questionData['Id'] ?? 0;
$questionText      = $questionData['Text'] ?? 'No question available';
$options           = [
    'A' => $questionData['OptionA'] ?? '',
    'B' => $questionData['OptionB'] ?? '',
    'C' => $questionData['OptionC'] ?? '',
    'D' => $questionData['OptionD'] ?? ''
];
$imagePath = $questionData['ImagePath'] ?? '';

// Progress
$stmtTotal = $connect->prepare("
    SELECT COUNT(*) as total 
    FROM practicequestions 
    WHERE GradeName=? 
      AND SubjectName=? 
      AND Chapter=? 
      AND LevelId=?
");
$stmtTotal->bind_param("sssi", $grade, $subject, $chapter, $levelId);
$stmtTotal->execute();
$totalQuestions = $stmtTotal->get_result()->fetch_assoc()['total'] ?? 1;
$stmtTotal->close();

$stmtCompleted = $connect->prepare("
    SELECT COUNT(*) as completed 
    FROM learnerpracticequestions 
    WHERE LearnerId=? 
      AND Status='complete' 
      AND QuestionId IN (
        SELECT Id 
        FROM practicequestions 
        WHERE GradeName=? 
          AND SubjectName=? 
          AND Chapter=? 
          AND LevelId=?
      )
");
$stmtCompleted->bind_param("isssi", $learnerId, $grade, $subject, $chapter, $levelId);
$stmtCompleted->execute();
$questionsCompleted = $stmtCompleted->get_result()->fetch_assoc()['completed'] ?? 0;
$stmtCompleted->close();

$stmtLevel = $connect->prepare("
    SELECT * 
    FROM learnerlevel 
    WHERE LearnerId=? AND LevelId=? AND ChapterName=?
");
$stmtLevel->bind_param("iis", $learnerId, $levelId, $chapter);
$stmtLevel->execute();
$levelData = $stmtLevel->get_result()->fetch_assoc();
$stmtLevel->close();


$totalTime       = $levelData['TotalTimeTaken'] ?? 0;
$levelAttempt    = $levelData['NumberAttempts'] ?? 1;
$score           = $levelData['Mark'] ?? 0;
$failed          = $questionsCompleted - $score;
$progressPercent = ($questionsCompleted / max($totalQuestions, 1)) * 100;

function formatTime($seconds)
{
    return sprintf('%02d:%02d', floor($seconds / 60), $seconds % 60);
}
?>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php include(__DIR__ . "/../partials/header.php"); ?>
        <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>
                    Practice Questions
                    <small>Answer and track your progress</small>
                </h1>
            </section>

            <section class="content">
                <div class="box box-solid" style="border-top:3px solid #605ca8; margin-bottom:10px;">
                    <div class="box-header with-border" style="background-color:#f3edff;">
                        <h3 class="box-title" style="color:#605ca8;">
                            <i class="fa fa-folder-open"></i> Question Details
                        </h3>
                    </div>
                    <div class="box-body">
                        <div class="row" style="font-weight:600;">
                            <div class="col-sm-3">Subject: <?= htmlspecialchars($subject) ?></div>
                            <div class="col-sm-3">Chapter: <?= htmlspecialchars($chapter) ?></div>
                            <div class="col-sm-3">Grade: <?= htmlspecialchars($grade) ?></div>
                            <div class="col-sm-3">Level: <?= htmlspecialchars($levelName) ?></div>
                        </div>

                        <progress value="<?= $questionsCompleted ?>" max="<?= $totalQuestions ?>" style="width:100%; height:20px; margin-top:15px;"></progress>
                        <div style="text-align:right; font-weight:600; margin-top:5px;">
                            Progress: <?= $questionsCompleted ?>/<?= $totalQuestions ?> (<?= round($progressPercent) ?>%)
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Question Section -->
                    <div class="col-md-6">
                        <div class="box box-primary" style="border-top:3px solid #3c8dbc;">
                            <div class="box-header with-border" style="background-color:#f0f8ff;">
                                <h3 class="box-title" style="color:#3c8dbc;">
                                    <i class="fa fa-question-circle"></i> Question
                                </h3>
                            </div>
                            <div class="box-body" style="background-color:#ffffff;">
                                <form id="learnerQuestionForm" method="POST" action="submit_answer.php">
                                    <input type="hidden" name="questionId" value="<?= $currentQuestionId ?>">
                                    <input type="hidden" name="learnerId" value="<?= $learnerId ?>">
                                    <input type="hidden" name="sid" value="<?= $subjectId ?>">

                                    <input type="hidden" name="grade" value="<?= htmlspecialchars($grade) ?>">
                                    <input type="hidden" name="subject" value="<?= htmlspecialchars($subject) ?>">
                                    <input type="hidden" name="chapter" value="<?= htmlspecialchars($chapter) ?>">
                                    <input type="hidden" name="levelId" value="<?= $levelId ?>">

                                    <input type="hidden" name="timeTaken" id="timeTakenInput" value="0">

                                    <p><strong>Q<?= $currentQuestionId ?>. <?= htmlspecialchars($questionText) ?></strong></p>

                                    <?php if ($imagePath): ?>
                                        <div style="margin-bottom:10px;">
                                            <img src="../../uploads/practice_question_images/<?= htmlspecialchars(basename($imagePath)) ?>" alt="Question Image" style="max-width:100%;">
                                        </div>
                                    <?php endif; ?>

                                    <?php foreach ($options as $key => $val): ?>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="answer" value="<?= htmlspecialchars($key) ?>" required>
                                                <?= htmlspecialchars($val) ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>

                                    <div class="row" style="margin-top:15px;">
                                        <div class="col-xs-6">
                                            <button type="submit" class="btn btn-success btn-block">
                                                <i class="fa fa-check"></i> Submit Answer
                                            </button>
                                        </div>
                                       
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Practice Info Section -->
                    <div class="col-md-6">
                        <div class="box box-primary" style="border-top:3px solid #3c8dbc;">
                            <div class="box-header with-border" style="background-color:#f0f8ff;">
                                <h3 class="box-title" style="color:#3c8dbc;">
                                    <i class="fa fa-bar-chart"></i> Practice Info
                                </h3>
                            </div>
                            <div class="box-body" style="background-color:#ffffff;">
                                <div class="row text-center" style="font-size:15px;">
                                    <div class="col-xs-6" style="border-right:1px solid #ddd; padding:15px 5px;">
                                        <i class="fa fa-trophy" style="font-size:24px; color:#f39c12;"></i>
                                        <div><strong>Correct</strong></div>
                                        <div><?= $score ?></div>
                                    </div>
                                    <div class="col-xs-6" style="padding:15px 5px;">
                                        <i class="fa fa-times-circle" style="font-size:24px; color:red;"></i>
                                        <div><strong>Incorrect</strong></div>
                                        <div><?= $failed ?></div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row text-center" style="font-size:15px;">
                                    <div class="col-xs-4" style="border-right:1px solid #ddd; padding:15px 5px;">
                                        <i class="fa fa-clock-o" style="font-size:24px;"></i>
                                        <div><strong>Timer</strong></div>
                                        <div id="timer">00:00</div>
                                    </div>
                                    <div class="col-xs-4" style="border-right:1px solid #ddd; padding:15px 5px;">
                                        <i class="fa fa-clock" style="font-size:24px;"></i>
                                        <div><strong>Total Time</strong></div>
                                        <div><?= formatTime($totalTime) ?></div>
                                    </div>
                                    <div class="col-xs-4" style="padding:15px 5px;">
                                        <i class="fa fa-repeat" style="font-size:24px;"></i>
                                        <div><strong>Level Attempt</strong></div>
                                        <div><?= $levelAttempt ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="control-sidebar-bg"></div>
    </div>
    <?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

    <script>
        let seconds = 0;
        const timerEl = document.getElementById('timer');
        const timeTakenInput = document.getElementById('timeTakenInput');

        setInterval(() => {
            seconds++;
            const m = Math.floor(seconds / 60).toString().padStart(2, '0');
            const s = (seconds % 60).toString().padStart(2, '0');
            timerEl.textContent = `${m}:${s}`;
            timeTakenInput.value = seconds;
        }, 1000);

    </script>
    

<script>
    document.getElementById('learnerQuestionForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const subjectId = <?= json_encode($subjectId); ?>;
    const form = e.target;
    const formData = new FormData(form);

    try {
        const res = await fetch('submit_answer.php', { method: 'POST', body: formData });
        const data = await res.text();
        let json;

        try { json = JSON.parse(data); }
        catch (err) { throw new Error("Invalid JSON response"); }

        if (json.status !== 'success') {
            throw new Error(json.message || "Something went wrong");
        }

        // Determine alert
        let alertOptions = {};
        if (json.passMessage) {
            alertOptions = {
                icon: 'success',
                title: 'Level Complete!',
                text: json.passMessage,
                confirmButtonText: 'OK'
            };
        } else if (json.failMessage) {
            alertOptions = {
                icon: 'error',
                title: 'Level Incomplete',
                text: json.failMessage,
                confirmButtonText: 'Retry Level'
            };
        } else {
            // Answer feedback (correct/incorrect)
            alertOptions = {
                icon: json.isCorrect ? 'success' : 'error',
                title: json.isCorrect ? 'Correct answer!' : 'Incorrect answer!',
                html: `
                    Score: ${json.score}<br>
                    Completed: ${json.numCompleted}/${json.totalQuestions}<br>
                    Time: ${json.totalTimeFormatted}
                `,
                showCancelButton: true,
                confirmButtonText: 'Next Question',
                cancelButtonText: 'Rest/Continue Later',
                reverseButtons: true
            };
        }

        const result = await Swal.fire(alertOptions);

        // Redirect logic
        if (json.passMessage) {
            window.location.href = `setpicker.php?subjectId=${encodeURIComponent(subjectId)}`;
        } else if (json.failMessage) {
            window.location.href = `training.php?subject=${encodeURIComponent(formData.get('subject'))}&sid=${encodeURIComponent(subjectId)}&grade=${encodeURIComponent(formData.get('grade'))}&chapter=${encodeURIComponent(formData.get('chapter'))}&level=${formData.get('levelId')}`;
        } else if (result.isConfirmed) {
            // Next question
            window.location.href = `training.php?subject=${encodeURIComponent(formData.get('subject'))}&sid=${encodeURIComponent(subjectId)}&grade=${encodeURIComponent(formData.get('grade'))}&chapter=${encodeURIComponent(formData.get('chapter'))}&level=${formData.get('levelId')}`;
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            // Rest / back
            window.location.href = `setpicker.php?subjectId=${encodeURIComponent(subjectId)}`;
        }

    } catch (err) {
        console.error(err);
        Swal.fire('Error', err.message, 'error');
    }
});
/*
document.getElementById('learnerQuestionForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const subjectId = <?= json_encode($subjectId); ?>;

    const form = e.target;
    const formData = new FormData(form);

    fetch('submit_answer.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text()) // get raw response first
    .then(data => {
        let json;
        try {
            json = JSON.parse(data); // parse JSON safely
        } catch (err) {
            console.error("JSON parse error:", err, "Raw data:", data);
            Swal.fire('Error', 'Something went wrong (invalid response).', 'error');
            return;
        }

        if (json.status === 'success') {
            const message = json.isCorrect ? 'Correct answer!' : 'Incorrect answer!';

            Swal.fire({
                icon: json.isCorrect ? 'success' : 'error',
                title: message,
                html: `
                    Score: ${json.score}<br>
                    Completed: ${json.numCompleted}/${json.totalQuestions}<br>
                    Time: ${json.totalTimeFormatted}
                `,
                showCancelButton: true,        // enables second button
                confirmButtonText: 'Next Question',     // main button
                cancelButtonText: 'Rest',      // second button
                reverseButtons: true           // optional: shows cancel on left
            }).then((result) => {
                if (result.isConfirmed) {
                    // User clicked "Next"
                    window.location.href = `training.php?subject=${encodeURIComponent(formData.get('subject'))}&sid=${encodeURIComponent(subjectId)}&grade=${encodeURIComponent(formData.get('grade'))}&chapter=${encodeURIComponent(formData.get('chapter'))}&level=${formData.get('levelId')}`;

                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // User clicked "Back/Rest"
                     window.location.href = `setpicker.php?subjectId=${encodeURIComponent(subjectId)}`;  //subjectId
                     

                }
            });





        } else {
            Swal.fire('Error', json.message || 'Something went wrong', 'error');
        }

   
        if (json.passMessage) {
            Swal.fire({
                icon: 'success',
                title: 'Level Complete!',
                text: json.passMessage,
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = `setpicker.php?subjectId=${encodeURIComponent(subjectId)}`;
            });
        } else if (json.failMessage) {
            Swal.fire({
                icon: 'error',
                title: 'Level Incomplete',
                text: json.failMessage,
                confirmButtonText: 'Retry Level'
            }).then(() => {
                // Reload the same level to repeat
                window.location.href = `training.php?subject=${encodeURIComponent(formData.get('subject'))}&sid=${encodeURIComponent(subjectId)}&grade=${encodeURIComponent(formData.get('grade'))}&chapter=${encodeURIComponent(formData.get('chapter'))}&level=${formData.get('levelId')}`;
            });
        } else {
            // Existing Next/Rest SweetAlert logic
        }



    })
    .catch(err => {
        console.error("Fetch error:", err);
        Swal.fire('Error', 'Something went wrong (network error).', 'error');
    }); 
});   

*/
</script>

    




    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
