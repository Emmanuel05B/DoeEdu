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

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/mathquill/build/mathquill.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/mathquill/build/mathquill.js"></script>
    <script>
        var MQ = MathQuill.getInterface(2);
    </script>

</head>


<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
<?php include_once(LEARNER_PATH . "/../partials/header.php"); ?>
<?php include_once(LEARNER_PATH . "/../partials/mainsidebar.php"); ?>

<div class="content-wrapper">

  <!-- Page Header -->
  <section class="content-header">
      <h1>
          Homework Memo
          <small>Activity Details</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Homework Memo</li>
      </ol>
  </section>

  <!-- Main Content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box box-deault">

          <?php
            if (!isset($_GET['activityid'])) {
                echo "<div class='alert alert-danger'>No activity selected.</div>";
                exit();
            }

            $activityId = intval($_GET['activityid']); 
            $userId = $_SESSION['user_id'];

            $stmt = $connect->prepare("
                SELECT oq.QuestionText, oq.OptionA, oq.OptionB, oq.OptionC, oq.OptionD, oq.CorrectAnswer, la.SelectedAnswer
                FROM onlinequestions oq
                LEFT JOIN learneranswers la ON oq.Id = la.QuestionId AND la.UserId = ? AND la.ActivityId = ?
                WHERE oq.ActivityId = ?
            ");
            $stmt->bind_param("iii", $userId, $activityId, $activityId);
            $stmt->execute();
            $result = $stmt->get_result();

            // Fetch memo path for this activity
            $memoStmt = $connect->prepare("SELECT MemoPath FROM onlineactivities WHERE Id = ? LIMIT 1");
            $memoStmt->bind_param("i", $activityId);
            $memoStmt->execute();
            $memoResult = $memoStmt->get_result();
            $memoRow = $memoResult->fetch_assoc();
            $memoStmt->close();

            if (!empty($memoRow['MemoPath']) && file_exists(QUIZ_MEMOS_PATH . '/' . basename($memoRow['MemoPath']))) {
                $memoURL = QUIZ_MEMOS_URL . '/' . basename($memoRow['MemoPath']);
                echo "<div style='margin-bottom:15px;'>
                        <a href='" . htmlspecialchars($memoURL) . "' target='_blank' class='btn btn-primary'>
                            <i class='fa fa-file-pdf-o'></i> PDF Memo
                        </a>
                      </div>";
            }

            if ($result->num_rows > 0):
                $questions = $result->fetch_all(MYSQLI_ASSOC);
          ?>

          <div class="questions-grid">
          <?php foreach ($questions as $index => $q): 
              $isCorrect = ($q['SelectedAnswer'] === $q['CorrectAnswer']);
              $cardClass = $isCorrect ? "box-success" : "box-danger";
          ?>
          <div class="question-card <?= $cardClass ?>">
              <p><strong>Q<?= $index+1 ?>:</strong></p>
              <div class="math-box question-field"></div>
              <input type="hidden" class="question-latex" value="<?= htmlspecialchars($q['QuestionText']) ?>">

              <div class="options">
                  <?php foreach(['A','B','C','D'] as $opt): ?>
                  <div class="option">
                      <span class="option-label"><?= $opt ?>.</span>
                      <div class="math-box option-field"></div>
                      <input type="hidden" class="option-latex" value="<?= htmlspecialchars($q['Option'.$opt]) ?>">
                  </div>
                  <?php endforeach; ?>
              </div>

              <div class="answer-row" style="margin-top:10px;">
                  <?php if (!$isCorrect && $q['SelectedAnswer']): ?>
                  <div class="alert alert-danger" style="margin:0; padding:5px;">Your Answer: <?= htmlspecialchars($q['SelectedAnswer']) ?></div>
                  <?php endif; ?>
                  <div class="alert alert-success" style="margin:5px 0 0 0; padding:5px;">Correct Answer: <?= htmlspecialchars($q['CorrectAnswer']) ?></div>
              </div>
          </div>
          <?php endforeach; ?>
          </div>

          <?php else: ?>
              <div class='alert alert-info'>No memo found for this activity.</div>
          <?php endif; ?>

          <?php
            $stmt->close();
            $connect->close();
          ?>

        </div>
      </div>
    </div>
  </section>
</div>
 

  <div class="control-sidebar-bg"></div>
</div>


<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Questions
    document.querySelectorAll('.question-field').forEach(function(box){
        var hidden = box.nextElementSibling;
        var mq = MQ.StaticMath(box);
        mq.latex(hidden.value);
    });

    // Options
    document.querySelectorAll('.option-field').forEach(function(box){
        var hidden = box.nextElementSibling;
        var mq = MQ.StaticMath(box);
        mq.latex(hidden.value);
    });
});
</script>
<style>
/* Grid layout for questions */
.questions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

/* Individual question card */
.question-card {
    background: #fff;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    transition: transform 0.2s, box-shadow 0.2s;
}

.question-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
}

.question-card h4 {
    margin-bottom: 10px;
    font-weight: 600;
}

/* Options styling */
.options {
    margin-top: 10px;
}

.option {
    display: flex;
    align-items: flex-start;
    margin-bottom: 8px;
}

.option-label {
    font-weight: 600;
    margin-right: 8px;
    min-width: 20px;
}

/* MathQuill boxes */
.math-box {
    min-height: 50px;
    padding: 8px;
    font-size: 16px;
    line-height: 1.5;
    max-width: 100%;
    background: #f7f7f7;
    border-radius: 4px;
    white-space: normal;        /* allow wrapping */
    overflow-wrap: break-word;  /* break long words */
    word-break: break-word;
}

/* MathQuill internals for proper wrapping */
.math-box .mq-math-mode {
    display: inline-block !important;
    white-space: normal !important;
}

.math-box .mq-root-block {
    display: block !important;
    white-space: normal !important;
    word-break: break-word !important;
    overflow-wrap: break-word !important;
}
</style>
</body>
</html>
