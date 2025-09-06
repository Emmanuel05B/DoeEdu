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

$learner_id = intval($_GET['id']);
if (!$learner_id) {
    echo "<script>Swal.fire('Invalid Parameters', 'Please ensure a learner is selected.', 'error');</script>";
    exit;
}

// Function to determine level from average mark
function getLevelFromMark($avgMark) {
    if ($avgMark < 30) return 1;
    if ($avgMark < 40) return 2;
    if ($avgMark < 50) return 3;
    if ($avgMark < 60) return 4;
    if ($avgMark < 70) return 5;
    if ($avgMark < 80) return 6;
    return 7; // 80–100%
}

// Fetch all subjects for this learner
$subjects_sql = "
    SELECT ls.LearnerSubjectId, s.SubjectName, s.SubjectId, ls.CurrentLevel, ls.TargetLevel
    FROM learnersubject ls
    JOIN subjects s ON ls.SubjectId = s.SubjectId
    WHERE ls.LearnerId = ?
";
$stmtSubjects = $connect->prepare($subjects_sql);
$stmtSubjects->bind_param('i', $learner_id);
$stmtSubjects->execute();
$subjectsResult = $stmtSubjects->get_result();
$subjects = $subjectsResult->fetch_all(MYSQLI_ASSOC);
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  <div class="content-wrapper">
      <section class="content-header">
          <h1>Learner Progress <small>Subject Score Analysis</small></h1>
          <ol class="breadcrumb">
              <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
              <li class="active">Progress</li>
          </ol>
      </section>

      <section class="content">
          <div class="container-fluid">

              <div class="row">
                  <?php foreach ($subjects as $subject): ?>
                      <?php
                      $subjectId = $subject['SubjectId'];
                      $learnerSubjectId = $subject['LearnerSubjectId'];
                      $startLevel = intval($subject['CurrentLevel']);
                      $targetLevel = intval($subject['TargetLevel']);

                      // Calculate average score for this subject
                      $MarksObtained = 0;
                      $Totals = 0;

                      $sqlActivities = "SELECT ActivityId, MaxMarks FROM activities WHERE SubjectId = $subjectId";
                      $activityResults = $connect->query($sqlActivities);

                      if ($activityResults && $activityResults->num_rows > 0) {
                          while ($activity = $activityResults->fetch_assoc()) {
                              $activityId = $activity['ActivityId'];
                              $maxMarks = $activity['MaxMarks'];

                              $sqlLearnerMarks = "SELECT MarksObtained FROM learneractivitymarks WHERE LearnerId = $learner_id AND ActivityId = $activityId";
                              $learnerResults = $connect->query($sqlLearnerMarks);

                              if ($learnerResults && $learnerResults->num_rows > 0) {
                                  while ($learner = $learnerResults->fetch_assoc()) {
                                      $MarksObtained += intval($learner['MarksObtained']);
                                      $Totals += intval($maxMarks);
                                  }
                              }
                          }

                          $averageMark = $Totals > 0 ? round(($MarksObtained / $Totals) * 100, 2) : 0;
                      } else {
                          $averageMark = 0;
                      }

                      $currentLevel = getLevelFromMark($averageMark);

                      // Calculate progress toward goal
                      $progress = ($currentLevel - $startLevel) / ($targetLevel - $startLevel) * 100;
                      if ($progress < 0) $progress = 0;

                      // Calculate attendance rate
                      $sqlAttendance = "
                          SELECT COUNT(*) AS total, 
                                 SUM(CASE WHEN Attendance='present' THEN 1 ELSE 0 END) AS present
                          FROM learneractivitymarks lam
                          JOIN activities a ON lam.ActivityId = a.ActivityId
                          WHERE lam.LearnerId = $learner_id AND a.SubjectId = $subjectId
                      ";
                      $attendanceResult = $connect->query($sqlAttendance)->fetch_assoc();
                      $attendanceRate = $attendanceResult['total'] > 0 ? round(($attendanceResult['present'] / $attendanceResult['total']) * 100, 2) : 0;

                      // Determine status message
                      if ($averageMark < 50) {
                          $statusText = 'Bad: Learner may fail if performance continues at this rate. Immediate intervention is recommended. Focus on remedial lessons and one-on-one support to improve understanding of key concepts.';
                      } else if ($averageMark < 80) {
                          $statusText = 'Average: Learner is improving, but can do better. Encourage consistent study habits, revision, and practice to boost performance. Monitoring progress regularly will help identify areas needing attention.';
                      } else {
                          $statusText = 'Excellent: Learner is performing very well! Keep motivating the learner to maintain this momentum. Consider challenging them with advanced material or enrichment activities to maximize potential.';
                      }
                      ?>

                      <div class="col-md-12 mb-3">
                          <div class="box box-primary">
                              <div class="box-header with-border">
                                  <h3 class="box-title"><?= htmlspecialchars($subject['SubjectName']) ?></h3>
                              </div>
                              <div class="box-body text-center">

                                  <div class="row text-center">
                                      <div class="col-md-2 col-md-offset-1"><strong>Start Level:</strong> <?= $startLevel ?></div>
                                      <div class="col-md-2"><strong>Current Level:</strong> <?= $currentLevel ?></div>
                                      <div class="col-md-2"><strong>Target Level:</strong> <?= $targetLevel ?></div>
                                      <div class="col-md-2"><strong>Average Mark:</strong> <?= $averageMark ?>%</div>
                                      <div class="col-md-2"><strong>Attendance Rate:</strong> <?= $attendanceRate ?>%</div>
                                  </div>
                                  <br>

                                  <div class="mt-3">
                                      <label>Progress Toward Goal:</label>
                                      <div class="progress">
                                          <div class="progress-bar progress-bar-info progress-bar-striped active" 
                                               role="progressbar" style="width: <?= $progress ?>%">
                                              Level <?= $currentLevel ?> of <?= $targetLevel ?>
                                          </div>
                                      </div>
                                  </div>

                                  <div class="callout callout-info mt-3" id="goalTracker_<?= $learnerSubjectId ?>">
                                      <h5>Goal Tracker</h5>
                                      <p><?= $statusText ?><br>Based on current average, learner is expected to reach Level <?= $targetLevel ?> by term end.</p>
                                  </div>

                                  <div class="mt-3">
                                      <button class="btn btn-primary btn-sm" 
                                              onclick="updateSkill(<?= $learnerSubjectId ?>, <?= $averageMark ?>)">
                                          Update Scores
                                      </button>
                                      <a href="subanalytics.php?id=<?= $learner_id ?>&val=<?= $subjectId ?>" 
                                         class="btn btn-success btn-sm">
                                          View Full Scores
                                      </a>
                                  </div>

                              </div>
                          </div>
                      </div>

                  <?php endforeach; ?>
              </div>
          </div>
      </section>
  </div>
</div>

<script>
function updateSkill(learnerSubjectId, score) {
    const tracker = document.getElementById('goalTracker_' + learnerSubjectId);
    if (!tracker) return;

    let statusText;
    if (score < 50) {
        statusText = 'Bad: Learner may fail if performance continues at this rate. Immediate intervention is recommended. Focus on remedial lessons and one-on-one support to improve understanding of key concepts.';
    } else if (score < 80) {
        statusText = 'Average: Learner is improving, but can do better. Encourage consistent study habits, revision, and practice to boost performance. Monitoring progress regularly will help identify areas needing attention.';
    } else {
        statusText = 'Excellent: Learner is performing very well! Keep motivating the learner to maintain this momentum. Consider challenging them with advanced material or enrichment activities to maximize potential.';
    }

    tracker.innerHTML = '<h5>Goal Tracker</h5><p>' + statusText + '<br>Based on current average, learner is expected to reach Level ' + 
                        tracker.dataset.target + ' by term end.</p>';
}
</script>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>
</body>
</html>
