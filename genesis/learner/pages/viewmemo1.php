<!DOCTYPE html>
<html>
<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../../common/pages/login.php");
  exit();
}
include(__DIR__ . "/../../partials/connect.php");
include(__DIR__ . "/../../common/partials/head.php");
?>

<style>
  .tile-card {
    background: #e6f0ff;
    border: 2px solid #a3c1f7;
    border-radius: 6px;
    padding: 10px 12px;
    height: 100%;
    box-shadow: 0 1px 4px rgba(163,193,247,0.25);
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    margin-bottom: 20px;
  }

  .tile-card.correct {
    border-left: 5px solid #4CAF50;
  }

  .tile-card.incorrect {
    border-left: 5px solid #F44336;
  }

  .tile-card h4 {
    font-size: 15px;
    margin-bottom: 8px;
    color: #3a3a72;
    font-weight: 600;
  }

  .tile-card p {
    font-size: 14px;
    margin: 4px 0;
    color: #3a3a72;
  }

  .correct-answer,
  .your-answer {
    font-size: 13px;
    font-weight: 600;
    padding: 6px 10px;
    border-radius: 5px;
    display: inline-block;
    margin-top: 8px;
  }

  .correct-answer {
    color: #2e7d32;
    background: #d0f0d0;
  }

  .your-answer {
    color: #b71c1c;
    background: #ffdddd;
  }
</style>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
        <h1>Homework Memo <small>xxxx x x x</small></h1>
        <ol class="breadcrumb">
          <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Homework Memo</li>
        </ol>
    </section>

    <section class="content">
      <div class="box box-primary">
        <div class="box-body">
          <?php
          if (!isset($_GET['activityid'])) {
              echo "<div class='alert alert-danger'>No activity selected.</div>";
              exit();
          }

          $activityId = intval($_GET['activityid']); 
          $userId = $_SESSION['user_id'];

          $stmt = $connect->prepare("SELECT oq.QuestionText, oq.OptionA, oq.OptionB, oq.OptionC, oq.OptionD, oq.CorrectAnswer, la.SelectedAnswer
                                     FROM onlinequestions oq
                                     LEFT JOIN learneranswers la ON oq.Id = la.QuestionId AND la.UserId = ? AND la.ActivityId = ?
                                     WHERE oq.ActivityId = ?");
          $stmt->bind_param("iii", $userId, $activityId, $activityId);
          $stmt->execute();
          $result = $stmt->get_result();

          if ($result->num_rows > 0) {
              $questionNumber = 1;
              echo "<div class='row'>"; // Bootstrap row
              while ($row = $result->fetch_assoc()) {
                  $isCorrect = ($row['SelectedAnswer'] === $row['CorrectAnswer']);
                  $cardClass = $isCorrect ? "correct" : "incorrect";

                  echo "<div class='col-lg-4 col-md-6 col-xs-12'>
                          <div class='tile-card {$cardClass}'>
                            <h4>Q{$questionNumber}: " . htmlspecialchars($row['QuestionText']) . "</h4>
                            <p><strong>A:</strong> " . htmlspecialchars($row['OptionA']) . "</p>
                            <p><strong>B:</strong> " . htmlspecialchars($row['OptionB']) . "</p>
                            <p><strong>C:</strong> " . htmlspecialchars($row['OptionC']) . "</p>
                            <p><strong>D:</strong> " . htmlspecialchars($row['OptionD']) . "</p>";

                  if (!$isCorrect && $row['SelectedAnswer']) {
                      echo "<div class='your-answer'>Your Answer: " . htmlspecialchars($row['SelectedAnswer']) . "</div>";
                  }

                  echo "<div class='correct-answer'>Correct Answer: " . htmlspecialchars($row['CorrectAnswer']) . "</div>";

                  echo "  </div>
                        </div>";

                  $questionNumber++;
              }
              echo "</div>"; // Close row
          } else {
              echo "<div class='alert alert-info'>No memo found for this activity.</div>";
          }

          $stmt->close();
          $connect->close();
          ?>
        </div>
      </div>
    </section>
  </div>
</div>
<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>
</body>
</html>
