<!DOCTYPE html>
<html>
<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../common/login.php");
    exit();
}
include('../partials/connect.php');
include("learnerpartials/head.php");
?>

<style>
  .tile-card {
    background: #e6f0ff;
    border: 2px solid #a3c1f7;
    border-radius: 8px;
    padding: 15px;
    height: 100%;
    box-shadow: 0 2px 6px rgba(163,193,247,0.3);
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    margin-bottom: 20px;
  }

  .tile-card.correct {
    border-left: 6px solid #4CAF50;
  }

  .tile-card.incorrect {
    border-left: 6px solid #F44336;
  }

  .tile-card h4,
  .tile-card p {
    color: #3a3a72;
    font-weight: 600;
  }

  .correct-answer {
    color: #2e7d32;
    font-weight: 600;
    background: #d0f0d0;
    padding: 8px 12px;
    border-radius: 6px;
    display: inline-block;
    margin-top: 10px;
  }

  .your-answer {
    color: #b71c1c;
    font-weight: 600;
    background: #ffdddd;
    padding: 8px 12px;
    border-radius: 6px;
    display: inline-block;
    margin-top: 10px;
  }

  .tiles-row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
  }

  .tile-wrapper {
    flex: 1 1 48%;
    box-sizing: border-box;
  }

  @media (max-width: 767px) {
    .tile-wrapper {
      flex: 1 1 100%;
    }
  }
</style>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include("learnerpartials/header.php"); ?>
  <?php include("learnerpartials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Homework Memo</h1>
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
              echo "<div class='tiles-row'>";
              while ($row = $result->fetch_assoc()) {
                  $isCorrect = ($row['SelectedAnswer'] === $row['CorrectAnswer']);
                  $cardClass = $isCorrect ? "correct" : "incorrect";

                  echo "<div class='tile-wrapper'>
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
              echo "</div>";
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
</body>
</html>
