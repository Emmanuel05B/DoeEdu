<!DOCTYPE html>
<html>

<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../../common/pages/login.php");
  exit();
}

include(__DIR__ . "/../../partials/connect.php");

// Example: Assume we get learnerId and activityId from GET
$activityId = isset($_GET['activity_id']) ? intval($_GET['activity_id']) : 0;
$learnerId = isset($_GET['learner_id']) ? intval($_GET['learner_id']) : 0;

if (!$activityId || !$learnerId) {
  die("Missing required parameters.");
}

// Fetch learner details
$learnerStmt = $connect->prepare("SELECT name FROM users WHERE id = ?");
$learnerStmt->bind_param("i", $learnerId);
$learnerStmt->execute();
$learnerResult = $learnerStmt->get_result();
$learner = $learnerResult->fetch_assoc();
$learnerStmt->close();

// Fetch activity title
$activityStmt = $connect->prepare("SELECT Title FROM onlineactivities WHERE id = ?");
$activityStmt->bind_param("i", $activityId);
$activityStmt->execute();
$activityResult = $activityStmt->get_result();
$activity = $activityResult->fetch_assoc();
$activityStmt->close();

// Fetch learner answers and questions
$qaStmt = $connect->prepare("SELECT oq.QuestionText, oq.OptionA, oq.OptionB, oq.OptionC, oq.OptionD, oq.CorrectAnswer, oa.SelectedAnswer FROM onlinequestions oq JOIN onlineanswers oa ON oq.id = oa.QuestionId WHERE oa.ActivityId = ? AND oa.LearnerId = ?");
$qaStmt->bind_param("ii", $activityId, $learnerId);
$qaStmt->execute();
$qaResult = $qaStmt->get_result();

$questions = [];
while ($row = $qaResult->fetch_assoc()) {
  $questions[] = $row;
}
$qaStmt->close();
?>

<?php include(__DIR__ . "/../../common/partials/head.php"); ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include(__DIR__ . "/../../common/partials/header.php"); ?>
  <?php include(__DIR__ . "/../../common/partials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Scores for <?php echo htmlspecialchars($learner['name']); ?> on "<?php echo htmlspecialchars($activity['Title']); ?>"</h3>
            </div>

            <div class="box-body">
              <?php foreach ($questions as $index => $q): ?>
                <div class="question-block" style="margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">
                  <h4>Question <?php echo $index + 1; ?></h4>
                  <p><?php echo htmlspecialchars($q['QuestionText']); ?></p>
                  <ul style="list-style-type:none; padding-left: 0;">
                    <li><strong>A:</strong> <?php echo htmlspecialchars($q['OptionA']); ?></li>
                    <li><strong>B:</strong> <?php echo htmlspecialchars($q['OptionB']); ?></li>
                    <li><strong>C:</strong> <?php echo htmlspecialchars($q['OptionC']); ?></li>
                    <li><strong>D:</strong> <?php echo htmlspecialchars($q['OptionD']); ?></li>
                  </ul>
                  <p><strong>Correct Answer:</strong> <?php echo $q['CorrectAnswer']; ?></p>
                  <p><strong>Learner's Answer:</strong> 
                    <span style="color: <?php echo ($q['CorrectAnswer'] == $q['SelectedAnswer']) ? 'green' : 'red'; ?>">
                      <?php echo $q['SelectedAnswer']; ?>
                    </span>
                  </p>
                </div>
              <?php endforeach; ?>
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
