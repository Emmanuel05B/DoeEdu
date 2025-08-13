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
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

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
            echo "<div class='row'>"; // Main row for cards
            while ($row = $result->fetch_assoc()) {
                $isCorrect = ($row['SelectedAnswer'] === $row['CorrectAnswer']);
                $cardClass = $isCorrect ? "box-success" : "box-danger"; // AdminLTE colors

                echo "<div class='col-lg-4 col-md-6 col-sm-12' style='margin-bottom:15px;'>
                        <div class='box {$cardClass}'>
                            <div class='box-body'>
                                <p>Q{$questionNumber}: " . htmlspecialchars($row['QuestionText']) . "</p>
                                <p><strong>A:</strong> " . htmlspecialchars($row['OptionA']) . "</p>
                                <p><strong>B:</strong> " . htmlspecialchars($row['OptionB']) . "</p>
                                <p><strong>C:</strong> " . htmlspecialchars($row['OptionC']) . "</p>
                                <p><strong>D:</strong> " . htmlspecialchars($row['OptionD']) . "</p>

                                <div class='row' style='margin-top:10px;'>";

                // Your Answer (left)
                if (!$isCorrect && $row['SelectedAnswer']) {
                    echo "<div class='col-xs-12 col-sm-6'>
                            <div class='alert alert-danger' style='margin:0; padding:5px;'>
                                Your Answer: " . htmlspecialchars($row['SelectedAnswer']) . "
                            </div>
                          </div>";
                } else {
                    echo "<div class='col-xs-12 col-sm-6'></div>"; // empty for alignment
                }

                // Correct Answer (right)
                echo "<div class='col-xs-12 col-sm-6'>
                        <div class='alert alert-success' style='margin:0; padding:5px;'>
                            Correct Answer: " . htmlspecialchars($row['CorrectAnswer']) . "
                        </div>
                      </div>";

                echo "      </div> <!-- /.row for answers -->
                        </div> <!-- /.box-body -->
                      </div> <!-- /.box -->
                    </div> <!-- /.col -->";

                $questionNumber++;
            }
            echo "</div>"; // Close main row
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

  <div class="control-sidebar-bg"></div>
</div>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>
</body>
</html>
