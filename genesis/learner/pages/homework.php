
<!DOCTYPE html>
<html>

<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../../common/pages/login.php");
  exit();
}
?>
<?php include(__DIR__ . "/../../common/partials/head.php"); ?>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">
  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

    <div class="content-wrapper">
      <section class="content-header">
        <h1>My Homework <small>xxxx x x x</small></h1>
        <ol class="breadcrumb">
          <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">xxxx</li>
        </ol>
      </section>

    <?php
    include(__DIR__ . "/../../partials/connect.php");

    $LearnerId = $_SESSION['user_id']; // Use logged-in learner ID

    function getSubjectName($id) {
      $map = [
        1 => "Mathematics",
        2 => "Physical Sciences",
        3 => "Mathematics",
        4 => "Physical Sciences",
        5 => "Mathematics",
        6 => "Physical Sciences",
      ];
      return $map[$id] ?? "Unknown Subject";
    }

    $stmt = $connect->prepare("SELECT SubjectId FROM learnersubject WHERE LearnerId = ? ORDER BY SubjectId ASC LIMIT 2");
    $stmt->bind_param("i", $LearnerId);
    $stmt->execute();
    $result = $stmt->get_result();
    $subjectRows = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    if (count($subjectRows) === 0) {
      echo "<h3 class='text-center'>No subjects found for this learner.</h3>";
    } else {
      foreach ($subjectRows as $subject) {
        $SubjectId = $subject['SubjectId'];
        $SubjectName = getSubjectName($SubjectId);
    ?>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><?php echo $SubjectName; ?> - Upcoming and Completed Homework</h3>
            </div>

            <div class="box-body table-responsive">
              <table class="table table-bordered table-striped" style="width:100%;">
                <thead style="background-color: #3c8dbc; color: white;">
                  <tr>
                    <th>Title</th>
                    <th>Chapter</th>
                    <th>Received On</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Score</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                $stmt2 = $connect->prepare("SELECT Id, Title, Topic, CreatedAt, DueDate, TotalMarks FROM onlineactivities WHERE SubjectName = ?");
                $stmt2->bind_param("i", $SubjectId);
                $stmt2->execute();
                $activities = $stmt2->get_result();

                if ($activities->num_rows === 0) {
                  echo "<tr><td colspan='8'>No homework available for this subject.</td></tr>";
                } else {
                  while ($activity = $activities->fetch_assoc()) {
                    $activityId = $activity['Id'];

                    // Check if learner submitted answers
                    $answerStmt = $connect->prepare("SELECT oq.CorrectAnswer, la.SelectedAnswer
                                                     FROM learneranswers la
                                                     JOIN onlinequestions oq ON la.QuestionId = oq.Id
                                                     WHERE la.UserId = ? AND la.ActivityId = ?");
                    $answerStmt->bind_param("ii", $LearnerId, $activityId);
                    $answerStmt->execute();
                    $answersResult = $answerStmt->get_result();

                    $correct = 0;
                    $totalAnswered = 0;

                    while ($ans = $answersResult->fetch_assoc()) {
                      $totalAnswered++;
                      if ($ans['SelectedAnswer'] === $ans['CorrectAnswer']) {
                        $correct++;
                      }
                    }

                    $answerStmt->close();

                    if ($totalAnswered > 0) {
                      $status = "<span class='label label-success'>Completed</span>";
                      $score = "{$correct}/{$activity['TotalMarks']}";
                      $memoBtn = "<a href='viewmemo.php?activityid={$activityId}' class='btn btn-info btn-sm'>View Memo</a>";
                    } else {
                      $status = "<span class='label label-warning'>Not Started</span>";
                      $score = "-";
                      $memoBtn = "";
                    }

                    echo "<tr>
                            <td>{$activity['Title']}</td>
                            <td>{$activity['Topic']}</td>
                            <td>{$activity['CreatedAt']}</td>
                            <td>{$activity['DueDate']}</td>
                            <td>{$status}</td>
                            <td>{$score}</td>
                            <td>
                              <a href='viewhomework.php?activityId={$activityId}' class='btn btn-primary btn-sm'>Open</a>
                              {$memoBtn}
                            </td>
                          </tr>";
                  }
                }

                $stmt2->close();
                ?>
                </tbody>
              </table>
            </div>

          </div>
        </div>
      </div>
    </section>

    <?php
      }
    }
    ?>

  </div>

  <div class="control-sidebar-bg"></div>
</div>

<!-- Scripts -->
<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

<script>
  $(function () {
    $('table').DataTable({
      responsive: true,
      autoWidth: false
    });
  });
</script>

</body>
</html>

