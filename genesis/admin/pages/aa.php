<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../../common/pages/login.php");
  exit();
}
// Dummy static data for now — replace with real DB values later
$subject = "Mathematics";
$grade = "Grade 10";
$level = "Intermediate (Medium)";
$chapter = "Algebra Basics";

$questionsCompleted = 5;
$score = 4;
$failed = 1;
$totalTime = 750; // total time on level in seconds (e.g. 12m 30s)
$levelAttempt = 1;
$currentQuestionNumber = 6;

// Function to format seconds to mm:ss
function formatTime($seconds) {
  return sprintf('%02d:%02d', floor($seconds / 60), $seconds % 60);
}

// Calculate progress percentage
$totalQuestions = 25;
$progressPercent = ($questionsCompleted / $totalQuestions) * 100;
?>

<?php include(__DIR__ . "/../../common/partials/head.php"); ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Practice Questions <small>Answer and track your progress</small></h1>
      <ol class="breadcrumb">
        <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Practice Questions</li>
      </ol>
    </section>

    <section class="content">
      <!-- Info bar on top -->
      <div class="box box-solid" style="border-top: 3px solid #605ca8; margin-bottom: 10px;">
        <div class="box-header with-border" style="background-color:#f3edff;">
          <h3 class="box-title" style="color:#605ca8;">
            <i class="fa fa-folder-open"></i> Question Details
          </h3>
        </div>  
        <div class="box-body">
          <div class="row" style="margin: 0; font-weight: 600;">
            <div class="col-sm-3">Subject: <?= htmlspecialchars($subject) ?></div>
            <div class="col-sm-3">Chapter: <?= htmlspecialchars($chapter) ?></div>
            <div class="col-sm-3">Grade: <?= htmlspecialchars($grade) ?></div>
            <div class="col-sm-3">Level: <?= htmlspecialchars($level) ?></div>
          </div>

          <!-- Progress Bar -->
          <progress value="<?= $questionsCompleted ?>" max="<?= $totalQuestions ?>" style="width: 100%; height: 20px; margin-top: 15px;"></progress>
          <div style="text-align: right; font-weight: 600; margin-top: 5px;">
            Progress: <?= $questionsCompleted ?>/<?= $totalQuestions ?> (<?= round($progressPercent) ?>%)
          </div>
        </div>
      </div>

      <div class="row">
        <!-- Left side: Question and options -->
        <div class="col-md-6">
          <div class="box box-primary" style="border-top: 3px solid #3c8dbc;">
            <div class="box-header with-border" style="background-color:#f0f8ff;">
              <h3 class="box-title" style="color:#3c8dbc;">
                <i class="fa fa-question-circle"></i> Question
              </h3>
            </div>
            <div class="box-body" style="background-color:#ffffff;">
              
              <form id="learnerQuestionForm">
                <p><strong>Q<?= $currentQuestionNumber ?>. What is 2 + 2?</strong></p>

                <div class="radio">
                  <label><input type="radio" name="answer" value="A"> 3</label>
                </div>
                <div class="radio">
                  <label><input type="radio" name="answer" value="B"> 4</label>
                </div>
                <div class="radio">
                  <label><input type="radio" name="answer" value="C"> 5</label>
                </div>
                <div class="radio">
                  <label><input type="radio" name="answer" value="D"> 6</label>
                </div>

                <div class="text-right" style="margin-top:15px;">
                  <button type="button" class="btn btn-success" onclick="alert('Answer submitted!')">
                    <i class="fa fa-check"></i> Submit Answer
                  </button>
                  <button type="button" class="btn btn-primary" onclick="alert('Load next question!')">
                    <i class="fa fa-arrow-right"></i> Next Question
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Right side: Performance info -->
        <div class="col-md-6">
  <div class="box box-primary" style="border-top: 3px solid #3c8dbc;">
    <div class="box-header with-border" style="background-color:#f0f8ff;">
      <h3 class="box-title" style="color:#3c8dbc;">
        <i class="fa fa-bar-chart"></i> Practice Questions
      </h3>
    </div>
    <div class="box-body" style="background-color:#ffffff;">
      <div class="row text-center" style="font-size: 15px;">
        <div class="col-xs-6 col-sm-6" style="border-right: 1px solid #ddd; padding: 15px 5px;">
          <i class="fa fa-trophy" style="font-size: 24px; color: #f39c12;"></i>
          <div><strong>Correct</strong></div>
          <div><?= $score ?></div>
        </div>
        <div class="col-xs-6 col-sm-6" style="padding: 15px 5px;">
          <i class="fa fa-times-circle" style="font-size: 24px; color: red;"></i>
          <div><strong>Incorrect</strong></div>
          <div><?= $failed ?></div>
        </div>
      </div>

      <hr>

      <div class="row text-center" style="font-size: 15px;">
        <div class="col-xs-6 col-sm-4" style="border-right: 1px solid #ddd; padding: 15px 5px;">
          <i class="fa fa-clock-o" style="font-size: 24px;"></i>
          <div><strong>Timer</strong></div>
          <div id="timer">00:00</div>
        </div>
        <div class="col-xs-6 col-sm-4" style="border-right: 1px solid #ddd; padding: 15px 5px;">
          <i class="fa fa-clock" style="font-size: 24px;"></i>
          <div><strong>Total Time</strong></div>
          <div><?= formatTime($totalTime) ?></div>
        </div>
        <div class="col-xs-6 col-sm-4" style="padding: 15px 5px;">
          <i class="fa fa-repeat" style="font-size: 24px;"></i>
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
  const m = Math.floor(seconds / 60).toString().padStart(2, '0');
  const s = (seconds % 60).toString().padStart(2, '0');
  timerEl.textContent = `${m}:${s}`;
}, 1000);
</script>



<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>
</body>
</html>
