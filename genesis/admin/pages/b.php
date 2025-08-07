<!DOCTYPE html>
<html>
    
<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../../common/pages/login.php");
  exit();
}

include(__DIR__ . "/../../common/partials/head.php");

// Dummy data example: replace with DB query fetching all questions for current level
$questions = [
  1 => ['id' => 101, 'text' => 'What is 5 + 7?', 'options' => ['10','11','12','13','14']],
  2 => ['id' => 102, 'text' => 'What is 8 - 3?', 'options' => ['3','4','5','6']],
  // ... up to 25 questions
];
$totalQuestions = count($questions);
$level = 'Easy';

// Initialize or retrieve learner progress from session or DB
if (!isset($_SESSION['practice_progress'])) {
  $_SESSION['practice_progress'] = []; // key=question_id, value=answer given
}

// Handle form submission for current question
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question_id'], $_POST['answer'])) {
  $qid = intval($_POST['question_id']);
  $answer = $_POST['answer'];
  $_SESSION['practice_progress'][$qid] = $answer;

  // Redirect to avoid form resubmission and show next question
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}

// Find next unanswered question
$nextQuestionNumber = 1;
foreach ($questions as $num => $q) {
  if (!array_key_exists($q['id'], $_SESSION['practice_progress'])) {
    $nextQuestionNumber = $num;
    break;
  }
  if ($num === $totalQuestions) {
    $nextQuestionNumber = $totalQuestions + 1; // all done
  }
}

?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
 
  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  <div class="content-wrapper">
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

    <section class="content-header">
      <h1>Practice Questions <small>Level: <?php echo htmlspecialchars($level); ?></small></h1>
      <ol class="breadcrumb">
        <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Practice Questions</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">
                <?php if ($nextQuestionNumber <= $totalQuestions): ?>
                  Question <?php echo $nextQuestionNumber; ?> of <?php echo $totalQuestions; ?>
                <?php else: ?>
                  Results Summary
                <?php endif; ?>
              </h3>
            </div>

            <div class="box-body">

            <?php if ($nextQuestionNumber <= $totalQuestions): 
              $q = $questions[$nextQuestionNumber];
            ?>

              <form method="POST" action="">
                <input type="hidden" name="question_id" value="<?php echo $q['id']; ?>">
                
                <div id="question-text" style="font-size:1.3em; margin-bottom: 20px;">
                  <?php echo htmlspecialchars($q['text']); ?>
                </div>

                <div class="form-group">
                  <?php 
                    $options = $q['options'];
                    shuffle($options);
                    foreach ($options as $opt) {
                      $checked = (isset($_SESSION['practice_progress'][$q['id']]) && $_SESSION['practice_progress'][$q['id']] === $opt) ? 'checked' : '';
                      echo '<div class="radio"><label>';
                      echo '<input type="radio" name="answer" value="' . htmlspecialchars($opt) . '" required ' . $checked . '> ' . htmlspecialchars($opt);
                      echo '</label></div>';
                    }
                  ?>
                </div>

                <button type="submit" class="btn btn-primary">Submit Answer</button>
              </form>

              <!-- Progress bar -->
              <div class="progress" style="height: 15px; margin-top: 15px;">
                <div class="progress-bar progress-bar-success" role="progressbar"
                  aria-valuenow="<?php echo $nextQuestionNumber - 1; ?>" aria-valuemin="0" aria-valuemax="<?php echo $totalQuestions; ?>"
                  style="width: <?php echo (($nextQuestionNumber - 1) / $totalQuestions) * 100; ?>%;">
                  <?php echo round((($nextQuestionNumber - 1) / $totalQuestions) * 100); ?>%
                </div>
              </div>

            <?php else: 
              // Show summary/results

              // Example scoring logic: assume correct answers are hardcoded here (replace with DB check)
              $correctAnswers = [
                101 => '12',
                102 => '5', // Actually '5' is not in options for q2, just an example, adjust accordingly
                // etc...
              ];

              $totalCorrect = 0;
              foreach ($_SESSION['practice_progress'] as $qid => $ans) {
                if (isset($correctAnswers[$qid]) && $correctAnswers[$qid] === $ans) {
                  $totalCorrect++;
                }
              }

              $percentage = ($totalCorrect / $totalQuestions) * 100;
            ?>

              <h4>You've completed all questions!</h4>
              <p>Correct answers: <strong><?php echo $totalCorrect; ?></strong> out of <strong><?php echo $totalQuestions; ?></strong></p>
              <p>Your score: <strong><?php echo round($percentage, 2); ?>%</strong></p>

              <?php if ($percentage >= 70): ?>
                <p class="text-success">Congratulations! You passed this level.</p>
              <?php else: ?>
                <p class="text-danger">Unfortunately, you did not reach the pass mark. You can retry the level.</p>
                <form method="POST" action="">
                  <input type="hidden" name="reset" value="1">
                  <button type="submit" class="btn btn-warning">Retry Level</button>
                </form>
              <?php endif; ?>

              <?php
              // Reset progress if retry clicked
              if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset']) && $_POST['reset'] == '1') {
                $_SESSION['practice_progress'] = [];
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
              }
              ?>

            <?php endif; ?>

            </div>

          </div>

        </div>
      </div>
    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

</body>
</html>
