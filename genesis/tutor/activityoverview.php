<!DOCTYPE html>
<html>
<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}
include("tutorpartials/head.php");
include('../partials/connect.php');

// Get activity ID from URL
if (!isset($_GET['activityId'])) {
  echo "<h3 class='text-center text-danger'>No activity selected.</h3>";
  exit();
}
$activityId = intval($_GET['activityId']);

// Get activity details
$actStmt = $connect->prepare("SELECT Title, Grade, Topic, Instructions, DueDate, CreatedAt, TotalMarks, SubjectName FROM onlineactivities WHERE Id = ?");
$actStmt->bind_param("i", $activityId);
$actStmt->execute();
$activity = $actStmt->get_result()->fetch_assoc();
$actStmt->close();

$subjectId = $activity['SubjectName'];
$grade = $activity['Grade'];

// Get learner IDs assigned to this subject
$learnerIds = [];
$learnerStmt = $connect->prepare("SELECT LearnerId FROM learnersubject WHERE SubjectId = ?");
$learnerStmt->bind_param("i", $subjectId);
$learnerStmt->execute();
$result = $learnerStmt->get_result();
while ($row = $result->fetch_assoc()) {
  $learnerIds[] = $row['LearnerId'];
}
$learnerStmt->close();

$totalAssigned = count($learnerIds);

// Get learner details
if (!empty($learnerIds)) {
  $placeholders = implode(',', array_fill(0, count($learnerIds), '?'));
  $types = str_repeat('i', count($learnerIds));
  $learnerQuery = $connect->prepare("SELECT Id, Name, Surname FROM users WHERE Id IN ($placeholders)");
  $learnerQuery->bind_param($types, ...$learnerIds);
  $learnerQuery->execute();
  $learners = $learnerQuery->get_result();
} else {
  $learners = false;
}

// Score calculations
$completed = 0;
$totalScores = [];

foreach ($learnerIds as $userId) {
  $answerStmt = $connect->prepare("SELECT oq.CorrectAnswer, la.SelectedAnswer FROM learneranswers la JOIN onlinequestions oq ON la.QuestionId = oq.Id WHERE la.UserId = ? AND la.ActivityId = ?");
  $answerStmt->bind_param("ii", $userId, $activityId);
  $answerStmt->execute();
  $answers = $answerStmt->get_result();

  $correct = 0;
  $total = 0;
  while ($row = $answers->fetch_assoc()) {
    $total++;
    if ($row['SelectedAnswer'] === $row['CorrectAnswer']) $correct++;
  }

  if ($total > 0) {
    $completed++;
    $totalScores[] = round(($correct / $total) * 100);
  }

  $answerStmt->close();
}

$notSubmitted = $totalAssigned - $completed;
$completionRate = $totalAssigned > 0 ? round(($completed / $totalAssigned) * 100) : 0;
$averageScore = count($totalScores) > 0 ? round(array_sum($totalScores) / count($totalScores)) : 0;
$highestScore = count($totalScores) > 0 ? max($totalScores) : 0;
$lowestScore = count($totalScores) > 0 ? min($totalScores) : 0;

$now = new DateTime();
$dueDate = new DateTime($activity['DueDate']);
$isClosed = $now > $dueDate;
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include("tutorpartials/header.php"); ?>
  <?php include("tutorpartials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Activity Overview</h1>
      <small class="text-muted">Summary for: <strong><?= htmlspecialchars($activity['Title']) ?> - Grade <?= $grade ?></strong></small>
    </section>

    <section class="content">

      <!-- Activity Details -->
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-info-circle"></i> Activity Details</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-4">
              <p><strong>Title:</strong> <?= htmlspecialchars($activity['Title']) ?></p>
              <p><strong>Grade:</strong> <?= htmlspecialchars($activity['Grade']) ?></p>
              <p><strong>Topic:</strong> <?= htmlspecialchars($activity['Topic']) ?></p>
              <p><strong>Instructions:</strong> <?= nl2br(htmlspecialchars($activity['Instructions'])) ?></p>
              <br>
            </div>
            <div class="col-md-4">
              <p><strong>Due Date:</strong> <?= date("F j, Y", strtotime($activity['DueDate'])) ?></p>
              <p><strong>Created At:</strong> <?= date("F j, Y", strtotime($activity['CreatedAt'])) ?></p>
              <p><strong>Total Marks:</strong> <?= $activity['TotalMarks'] ?></p>
              <p><strong>Status:</strong>
                <?php
                  if ($isClosed) {
                    echo '<span class="label label-danger">Closed/Past Due</span>';
                  } else {
                    echo '<span class="label label-success">Open/Available</span>';
                  }
                ?>
              </p>
              <br>
            </div>
            <div class="col-md-4">
              <p><strong>Assigned Learners:</strong> <?= $totalAssigned ?></p>
              <p><strong>Completed:</strong> <?= $completed ?></p>
              <p><strong>Not Submitted:</strong> <?= $notSubmitted ?></p>
              <p><strong>Completion Rate:</strong> <?= $completionRate ?>%</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Score Boxes -->
      <div class="row" style="margin-bottom: 30px;">
        <div class="col-md-4">
          <div class="box box-solid box-primary text-center">
            <div class="box-header"><h3 class="box-title"><i class="fa fa-bar-chart"></i> Average Score</h3></div>
            <div class="box-body"><h2><?= $averageScore ?>%</h2><p class="text-muted">From completed learners</p></div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="box box-solid box-success text-center">
            <div class="box-header"><h3 class="box-title"><i class="fa fa-trophy"></i> Highest Score</h3></div>
            <div class="box-body"><h2><?= $highestScore ?>%</h2><p class="text-muted">Top performer</p></div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="box box-solid box-danger text-center">
            <div class="box-header"><h3 class="box-title"><i class="fa fa-exclamation-triangle"></i> Lowest Score</h3></div>
            <div class="box-body"><h2><?= $lowestScore ?>%</h2><p class="text-muted">Lowest performer</p></div>
          </div>
        </div>
      </div>

      <!-- Learner Table -->
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-users"></i> Learner Performance Summary</h3>
        </div>
        <div class="box-body table-responsive">
          
          <!-- Feedback Button -->
          <?php if ($isClosed): ?>
            <form id="feedbackForm" action="send_feedback.php" method="post">
              <input type="hidden" name="activityId" value="<?= htmlspecialchars($activityId) ?>">
              <button type="button" class="btn btn-danger" id="sendFeedbackBtn" style="margin-bottom: 15px;">
                <i class="fa fa-envelope"></i> Send Feedback to Parents (Not Submitted)
              </button>
            </form>
          <?php else: ?>
            <div class="alert alert-warning" style="margin-bottom: 15px;">
              <i class="fa fa-info-circle"></i> Feedback is only available after the due date has passed.
            </div>
          <?php endif; ?>

          <table class="table table-bordered table-striped table-hover">
            <thead style="background-color: #3c8dbc; color: white;">
              <tr>
                <th>Name</th>
                <th>Surname</th>
                <th class="text-center">Status</th>
                <th class="text-center">Score</th>
                <th class="text-center">Percentage</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($learners && $learners->num_rows > 0) {
                while ($learner = $learners->fetch_assoc()) {
                  $uid = $learner['Id'];
                  $scoreStmt = $connect->prepare("
                    SELECT oq.CorrectAnswer, la.SelectedAnswer 
                    FROM learneranswers la
                    JOIN onlinequestions oq ON la.QuestionId = oq.Id
                    WHERE la.UserId = ? AND la.ActivityId = ?
                  ");
                  $scoreStmt->bind_param("ii", $uid, $activityId);
                  $scoreStmt->execute();
                  $answers = $scoreStmt->get_result();

                  $correct = 0;
                  $answered = 0;
                  while ($a = $answers->fetch_assoc()) {
                    $answered++;
                    if ($a['CorrectAnswer'] === $a['SelectedAnswer']) $correct++;
                  }
                  $scoreStmt->close();

                  if ($answered > 0) {
                    $status = "<span class='badge bg-green'>Completed</span>";
                    $scorePercent = round(($correct / $answered) * 100);
                    $scoreDisplay = "<div class='progress' style='height: 18px; margin-bottom: 0;'>
                                      <div class='progress-bar progress-bar-success' role='progressbar' style='width: $scorePercent%;'>
                                        $scorePercent%
                                      </div>
                                     </div>";
                  } else {
                    $status = "<span class='badge bg-warning'>Not Submitted</span>";
                    $scoreDisplay = "-";
                  }

                  echo "<tr>
                          <td>" . htmlspecialchars($learner['Name']) . "</td>
                          <td>" . htmlspecialchars($learner['Surname']) . "</td>
                          <td class='text-center'>$status</td>
                          <td class='text-center'><div style='font-weight: bold; color: #333;'>$correct / $answered</div></td>
                          <td class='text-center'>$scoreDisplay</td>
                        </tr>";
                }
              } else {
                echo "<tr><td colspan='5' class='text-center'>No learners assigned.</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>

    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<!-- Scripts -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.getElementById('sendFeedbackBtn')?.addEventListener('click', function () {
    Swal.fire({
      title: 'Are you sure?',
      text: 'This will send feedback to the parents of all learners who did not submit the activity.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Yes, send it!'
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById('feedbackForm').submit();
      }
    });
  });
</script>
</body>
</html>
