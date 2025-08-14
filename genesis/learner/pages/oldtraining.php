<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../common/partials/head.php");
include(__DIR__ . "/../../partials/connect.php");

$learnerId = $_SESSION['user_id'];
$subject = $_GET['subject'] ?? '';
$grade = $_GET['grade'] ?? '';
$chapter = $_GET['chapter'] ?? '';
$levelId = intval($_GET['level'] ?? 0);

if (!$subject || !$grade || !$chapter || !$levelId) {
    die("<div class='alert alert-danger' style='margin:20px;'>Missing parameters.</div>");
}

// --- Eligibility check ---
$isEligible = false;
if ($levelId == 1) {
    $isEligible = true; // Easy level always accessible
} else {
    // Previous level must be complete
    $prevLevelId = $levelId - 1;
    $prevStmt = $connect->prepare("
        SELECT Complete FROM learnerlevel
        WHERE LearnerId = ? AND LevelId = ?
    ");
    $prevStmt->bind_param("ii", $learnerId, $prevLevelId);
    $prevStmt->execute();
    $prevComplete = $prevStmt->get_result()->fetch_assoc()['Complete'] ?? 0;
    $isEligible = ($prevComplete == 1);
}

if (!$isEligible) {
    die("<div class='alert alert-danger' style='margin:20px;'>You are not eligible to access this level yet.</div>");
}

// Fetch Level Name
$levelStmt = $connect->prepare("SELECT LevelName FROM level WHERE Id = ?");
$levelStmt->bind_param("i", $levelId);
$levelStmt->execute();
$levelName = $levelStmt->get_result()->fetch_assoc()['LevelName'] ?? '';

// Fetch next unanswered question
$questionStmt = $connect->prepare("
    SELECT pq.* 
    FROM practicequestions pq
    LEFT JOIN learnerpracticequestions lpq 
      ON pq.Id = lpq.QuestionId AND lpq.LearnerId = ?
    WHERE pq.GradeName = ? AND pq.SubjectName = ? AND pq.Chapter = ? 
      AND pq.LevelId = ? AND (lpq.Status IS NULL OR lpq.Status != 'complete')
    ORDER BY pq.Id ASC
    LIMIT 1
");
$questionStmt->bind_param("isssi", $learnerId, $grade, $subject, $chapter, $levelId);
$questionStmt->execute();
$questionData = $questionStmt->get_result()->fetch_assoc();

// Fallback: first question if all answered
if (!$questionData) {
    $fallbackStmt = $connect->prepare("
        SELECT * FROM practicequestions 
        WHERE GradeName = ? AND SubjectName = ? AND Chapter = ? AND LevelId = ?
        ORDER BY Id ASC LIMIT 1
    ");
    $fallbackStmt->bind_param("sssi", $grade, $subject, $chapter, $levelId);
    $fallbackStmt->execute();
    $questionData = $fallbackStmt->get_result()->fetch_assoc();
}

// Extract details
$currentQuestionNumber = $questionData['Id'] ?? 1;
$questionText = $questionData['Text'] ?? 'No question available';
$options = [
    'A' => $questionData['OptionA'] ?? '',
    'B' => $questionData['OptionB'] ?? '',
    'C' => $questionData['OptionC'] ?? '',
    'D' => $questionData['OptionD'] ?? ''
];
$imagePath = $questionData['ImagePath'] ?? '';

// Progress
$totalQuestionsStmt = $connect->prepare("
    SELECT COUNT(*) as total 
    FROM practicequestions 
    WHERE GradeName = ? AND SubjectName = ? AND Chapter = ? AND LevelId = ?
");
$totalQuestionsStmt->bind_param("sssi", $grade, $subject, $chapter, $levelId);
$totalQuestionsStmt->execute();
$totalQuestions = $totalQuestionsStmt->get_result()->fetch_assoc()['total'] ?? 1;

$completedStmt = $connect->prepare("
    SELECT COUNT(*) as completed 
    FROM learnerpracticequestions 
    WHERE LearnerId = ? AND Status = 'complete' 
      AND QuestionId IN (SELECT Id FROM practicequestions WHERE GradeName = ? AND SubjectName = ? AND Chapter = ? AND LevelId = ?)
");
$completedStmt->bind_param("isssi", $learnerId, $grade, $subject, $chapter, $levelId);
$completedStmt->execute();
$questionsCompleted = $completedStmt->get_result()->fetch_assoc()['completed'] ?? 0;

$progressPercent = ($questionsCompleted / max($totalQuestions,1)) * 100;
$score = $questionsCompleted;
$failed = 0; // could be computed separately
$levelAttempt = 1;
$totalTime = 750;

function formatTime($seconds) {
    return sprintf('%02d:%02d', floor($seconds / 60), $seconds % 60);
}
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Practice Questions <small>Answer and track your progress</small></h1>
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
        <!-- Question & options -->
        <div class="col-md-6">
          <div class="box box-primary" style="border-top:3px solid #3c8dbc;">
            <div class="box-header with-border" style="background-color:#f0f8ff;">
              <h3 class="box-title" style="color:#3c8dbc;">
                <i class="fa fa-question-circle"></i> Question
              </h3>
            </div>
            <div class="box-body" style="background-color:#ffffff;">
              <form id="learnerQuestionForm" method="POST" action="submit_answer.php">
                <input type="hidden" name="questionId" value="<?= $currentQuestionNumber ?>">
                <input type="hidden" name="learnerId" value="<?= $learnerId ?>">
                <input type="hidden" name="grade" value="<?= htmlspecialchars($grade) ?>">
                <input type="hidden" name="subject" value="<?= htmlspecialchars($subject) ?>">
                <input type="hidden" name="chapter" value="<?= htmlspecialchars($chapter) ?>">
                <input type="hidden" name="levelId" value="<?= $levelId ?>">

                <p><strong>Q<?= $currentQuestionNumber ?>. <?= htmlspecialchars($questionText) ?></strong></p>


                <?php if ($imagePath): ?>
                <div style="margin-bottom:10px;">
                    <img src="../../uploads/practice_question_images/<?= htmlspecialchars(basename($imagePath)) ?>" 
                        alt="Question Image" style="max-width:100%;">
                </div>
                <?php endif; ?>


                <?php foreach ($options as $key => $val): ?>
                  <div class="radio">
                    <label>
                      <input type="radio" name="answer" value="<?= htmlspecialchars($key) ?>"> <?= htmlspecialchars($val) ?>
                    </label>
                  </div>
                <?php endforeach; ?>

                <div class="row" style="margin-top:15px;">
                    <div class="col-xs-6">
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fa fa-check"></i> Submit Answer
                        </button>
                    </div>
                    <div class="col-xs-6">
                        <a href="training.php?subject=<?= urlencode($subject) ?>&grade=<?= urlencode($grade) ?>&chapter=<?= urlencode($chapter) ?>&level=<?= $levelId ?>" 
                        class="btn btn-primary btn-block">
                            <i class="fa fa-arrow-right"></i> Next Question
                        </a>
                    </div>
                </div>

              </form>
            </div>
          </div>
        </div>

        <!-- Performance Info -->
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

<script>
let seconds = 0;
const timerEl = document.getElementById('timer');
setInterval(() => {
  seconds++;
  const m = Math.floor(seconds / 60).toString().padStart(2,'0');
  const s = (seconds % 60).toString().padStart(2,'0');
  timerEl.textContent = `${m}:${s}`;
}, 1000);
</script>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>
</body>
</html>
