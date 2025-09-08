
<!DOCTYPE html>
<html>
<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
  exit();
}
include(__DIR__ . "/../../common/partials/head.php"); 
include(__DIR__ . "/../../partials/connect.php");

// Get activity ID
if (!isset($_GET['activityId'])) {
  echo "<h3 class='text-center text-danger'>No activity selected.</h3>";
  exit();
}
$activityId = intval($_GET['activityId']);


// Get activity details including last feedback date
$actStmt = $connect->prepare("SELECT Title, Grade, Topic, Instructions, DueDate, CreatedAt, TotalMarks, SubjectId, LastFeedbackSent, GroupName
FROM onlineactivities WHERE Id = ?");
$actStmt->bind_param("i", $activityId);
$actStmt->execute();
$activity = $actStmt->get_result()->fetch_assoc();
$actStmt->close();

$subjectId = $activity['SubjectId'];   
$grade = $activity['Grade'];
$last_feedback_date = $activity['LastFeedbackSent'];
$group = $activity['GroupName'];

// Get learner IDs..... we have to update this part to get them by group also and not Suspended/cancelledd.. only active
$learnerIds = [];


$learnerStmt = $connect->prepare("
  SELECT DISTINCT lt.LearnerId
  FROM learners lt
  JOIN learnersubject ls ON lt.LearnerId = ls.LearnerId
  JOIN learnerclasses lc ON lt.LearnerId = lc.LearnerID
  JOIN classes c ON lc.ClassID = c.ClassID
  WHERE lt.Grade = ? 
    AND ls.SubjectId = ? 
    AND c.GroupName = ? 
    AND ls.ContractExpiryDate > CURDATE()
    AND ls.Status = 'Active'
");

$learnerStmt->bind_param("iis", $grade, $subjectId, $group);
$learnerStmt->execute();

$result = $learnerStmt->get_result();
$learnerIds = [];

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

// Score calculation
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
 
  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>
  
  <div class="content-wrapper">
    <section class="content-header">
      <h1>Activity Overview<small class="text-muted">Summary for: <strong><?= htmlspecialchars($activity['Title']) ?> - Grade <?= $grade ?></strong></small></h1>
      <ol class="breadcrumb">
        <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Study Resources</li>
      </ol>
      
    </section>

    <section class="content">
      <!-- Activity Info Box -->
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-info-circle"></i> Activity Details</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-4">
              
              <p><strong>Topic:</strong> <?= htmlspecialchars($activity['Topic']) ?></p>
              <p><strong>Instructions:</strong> <?= nl2br(htmlspecialchars($activity['Instructions'])) ?></p>
            </div>
            <div class="col-md-4">
              <p><strong>Due Date:</strong> <?= date("F j, Y", strtotime($activity['DueDate'])) ?></p>
              <p><strong>Created At:</strong> <?= date("F j, Y", strtotime($activity['CreatedAt'])) ?></p>
              <p><strong>Total Marks:</strong> <?= $activity['TotalMarks'] ?></p>
              <p><strong>Status:</strong>
                <?= $isClosed
                  ? '<span class="label label-danger">Closed/Past Due</span>'
                  : '<span class="label label-success">Open/Available</span>';
                ?>
              </p>
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

      <!-- Score Cards -->
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

      <!-- Learner Table + Feedback -->
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-users"></i> Learner Performance Summary</h3>
        </div>
        <div class="box-body table-responsive">

          <!-- Feedback section -->


          <?php if ($isClosed): ?>
            <form id="feedbackForm" action="emailsuperhandler.php" method="post">
              
              <input type="hidden" name="action" value="feedback">
              <input type="hidden" name="redirect" value="activityoverview.php?activityId=<?= $activityId ?>">
              <input type="hidden" name="activityId" value="<?= $activityId ?>">
            
              <div class="row">
                <div class="col-md-6">
                  <button type="button" class="btn btn-danger" id="sendFeedbackBtn" style="margin-bottom: 15px;">
                    <i class="fa fa-envelope"></i> Send Feedback to Parents (Not Submitted)
                  </button>
                </div>
                <div class="col-md-6 text-right" style="padding-top: 10px;">
                  <?php if (!empty($last_feedback_date)): ?>
                    <div style="color: #555;">
                      <i class="fa fa-calendar"></i> Last feedback sent on:
                      <strong><?= date('d M Y, H:i', strtotime($last_feedback_date)) ?></strong>
                    </div>
                  <?php else: ?>
                    <div style="color: #777;">
                      <i class="fa fa-info-circle"></i> No feedback has been sent yet.
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </form>


          <?php else: ?>
            <div class="alert alert-warning">
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

                //this code checks to see if the leaner has completed an online activity or not.
                //im a creating a learneronlineactivity to store the status of the learner for that onlineactivity. 
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

                  $status = $answered > 0 ? "<span class='badge bg-green'>Completed</span>" : "<span class='badge bg-warning'>Not Submitted</span>";
                  $scoreDisplay = $answered > 0
                    ? "<div class='progress' style='height: 18px; margin-bottom: 0;'><div class='progress-bar progress-bar-success' role='progressbar' style='width: " . round(($correct / $answered) * 100) . "%;'>" . round(($correct / $answered) * 100) . "%</div></div>"
                    : "-";

                  echo "<tr>
                          <td>" . htmlspecialchars($learner['Name']) . "</td>
                          <td>" . htmlspecialchars($learner['Surname']) . "</td>
                          <td class='text-center'>$status</td>
                          <td class='text-center'><div style='font-weight: bold;'>$correct / $answered</div></td>
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
        this.disabled = true; // Disable button after click to avoid duplicates
        document.getElementById('feedbackForm').submit();
      }
    });
  });
</script>




<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

<?php
    if (isset($_SESSION['success'])) {
        $msg = $_SESSION['success'];
        unset($_SESSION['success']);
        echo "
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Email Sent',
                text: '". addslashes($msg) ."',
                confirmButtonText: 'OK'
            });
        </script>";
    }

    if (isset($_SESSION['error'])) {
        $msg = $_SESSION['error'];
        unset($_SESSION['error']);
        echo "
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Failed to Send',
                text: '". addslashes($msg) ."',
                confirmButtonText: 'OK'
            });
        </script>";
    }
  ?>

</body>
</html>