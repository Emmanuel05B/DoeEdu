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

              if ($result->num_rows > 0) {
                  $questionNumber = 1;
                  echo "<div class='table-responsive'><table class='table table-borderless'><tr>";
                  $columnsPerRowDesktop = 3;
                  $columnsPerRowTablet = 2;
                  $columnsPerRow = $columnsPerRowDesktop;
                  $colCount = 0;

                  while ($row = $result->fetch_assoc()) {
                      $isCorrect = ($row['SelectedAnswer'] === $row['CorrectAnswer']);
                      $cardClass = $isCorrect ? "box-success" : "box-danger";

                      // Determine column width for desktop/tablet/mobile
                      $colWidth = 100 / $columnsPerRowDesktop;

                      echo "<td class='hidden-xs hidden-sm' style='width: {$colWidth}%; vertical-align: top;'>
                              <div class='box {$cardClass}'>
                                  <div class='box-body'>
                                      <p><strong>Q{$questionNumber}:</strong> " . htmlspecialchars($row['QuestionText']) . "</p>
                                      <p><strong>A:</strong> " . htmlspecialchars($row['OptionA']) . "</p>
                                      <p><strong>B:</strong> " . htmlspecialchars($row['OptionB']) . "</p>
                                      <p><strong>C:</strong> " . htmlspecialchars($row['OptionC']) . "</p>
                                      <p><strong>D:</strong> " . htmlspecialchars($row['OptionD']) . "</p>

                                      <div class='row' style='margin-top:10px;'>";

                      if (!$isCorrect && $row['SelectedAnswer']) {
                          echo "<div class='col-xs-12 col-sm-6'>
                                  <div class='alert alert-danger' style='margin:0; padding:5px;'>
                                      Your Answer: " . htmlspecialchars($row['SelectedAnswer']) . "
                                  </div>
                                </div>";
                      } else {
                          echo "<div class='col-xs-12 col-sm-6'></div>";
                      }

                      echo "<div class='col-xs-12 col-sm-6'>
                              <div class='alert alert-success' style='margin:0; padding:5px;'>
                                  Correct Answer: " . htmlspecialchars($row['CorrectAnswer']) . "
                              </div>
                            </div>";

                      echo "      </div>
                                  </div>
                              </div>
                            </td>";

                      $colCount++;
                      if ($colCount == $columnsPerRowDesktop) {
                          echo "</tr><tr>";
                          $colCount = 0;
                      }

                      $questionNumber++;
                  }

                  while ($colCount > 0 && $colCount < $columnsPerRowDesktop) {
                      echo "<td></td>";
                      $colCount++;
                  }
                  echo "</tr></table></div>"; // Close table & responsive div

                  // Additional rows for mobile (stacked)
                  $result->data_seek(0);
                  echo "<div class='visible-xs visible-sm'>";
                  $questionNumber = 1;
                  while ($row = $result->fetch_assoc()) {
                      $isCorrect = ($row['SelectedAnswer'] === $row['CorrectAnswer']);
                      $cardClass = $isCorrect ? "box-success" : "box-danger";

                      echo "<div class='box {$cardClass}' style='margin-bottom:15px;'>
                              <div class='box-body'>
                                  <p><strong>Q{$questionNumber}:</strong> " . htmlspecialchars($row['QuestionText']) . "</p>
                                  <p><strong>A:</strong> " . htmlspecialchars($row['OptionA']) . "</p>
                                  <p><strong>B:</strong> " . htmlspecialchars($row['OptionB']) . "</p>
                                  <p><strong>C:</strong> " . htmlspecialchars($row['OptionC']) . "</p>
                                  <p><strong>D:</strong> " . htmlspecialchars($row['OptionD']) . "</p>

                                  <div class='row' style='margin-top:10px;'>";

                      if (!$isCorrect && $row['SelectedAnswer']) {
                          echo "<div class='col-xs-6'>
                                  <div class='alert alert-danger' style='margin:0; padding:5px;'>
                                      Your Answer: " . htmlspecialchars($row['SelectedAnswer']) . "
                                  </div>
                                </div>";
                      } else {
                          echo "<div class='col-xs-6'></div>";
                      }

                      echo "<div class='col-xs-6'>
                              <div class='alert alert-success' style='margin:0; padding:5px;'>
                                  Correct Answer: " . htmlspecialchars($row['CorrectAnswer']) . "
                              </div>
                            </div>";

                      echo "      </div>
                              </div>
                            </div>";

                      $questionNumber++;
                  }
                  echo "</div>"; // Close mobile stacked view

              } else {
                  echo "<div class='alert alert-info'>No memo found for this activity.</div>";
              }

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
</body>
</html>
