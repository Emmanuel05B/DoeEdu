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

$learnerId = $_SESSION['user_id'];
// Fetch learner full name (fallback to session var or 'Learner')
$stmt = $connect->prepare("SELECT CONCAT(Name, ' ', Surname) AS fullname FROM users WHERE Id = ?");
$stmt->bind_param("i", $learnerId);
$stmt->execute();
$stmt->bind_result($learnerName);
$stmt->fetch();
$stmt->close();

if (!$learnerName) {
    $learnerName = $_SESSION['full_name'] ?? 'Learner';
}

// Fetch Pending Homework count (not submitted & due date in future)
$stmt = $connect->prepare("
    SELECT COUNT(DISTINCT oa.Id) 
    FROM onlineactivities oa
    LEFT JOIN learneranswers la ON la.ActivityId = oa.Id AND la.UserId = ?
    WHERE la.Id IS NULL AND oa.DueDate >= CURDATE()
");
$stmt->bind_param("i", $learnerId);
$stmt->execute();
$stmt->bind_result($pendingHomeworkCount);
$stmt->fetch();
$stmt->close();

// Fetch Completed Homework count (learner answered)
$stmt = $connect->prepare("
    SELECT COUNT(DISTINCT la.ActivityId) 
    FROM learneranswers la
    WHERE la.UserId = ?
");
$stmt->bind_param("i", $learnerId);
$stmt->execute();
$stmt->bind_result($completedTasksCount);
$stmt->fetch();
$stmt->close();

// Fetch Average Score from learneranswers and onlinequestions
$stmt = $connect->prepare("
    SELECT AVG(score) FROM (
        SELECT la.ActivityId, SUM(oq.CorrectAnswer = la.SelectedAnswer) / COUNT(*) * 100 AS score
        FROM learneranswers la
        JOIN onlinequestions oq ON la.QuestionId = oq.Id
        WHERE la.UserId = ?
        GROUP BY la.ActivityId
    ) AS scores
");
$stmt->bind_param("i", $learnerId);
$stmt->execute();
$stmt->bind_result($averageScore);
$stmt->fetch();
$stmt->close();

$averageScore = $averageScore ? round($averageScore) : 0;



// Fetch Upcoming Homework (due in future, not submitted yet) limit 5
$stmt = $connect->prepare("
    SELECT oa.Id, oa.SubjectName, oa.Title, oa.DueDate
    FROM onlineactivities oa
    LEFT JOIN learneranswers la ON la.ActivityId = oa.Id AND la.UserId = ?
    WHERE oa.DueDate >= CURDATE() AND la.Id IS NULL
    ORDER BY oa.DueDate ASC
    LIMIT 5
");
$stmt->bind_param("i", $learnerId);
$stmt->execute();
$upcomingHomeworkResult = $stmt->get_result();
$upcomingHomework = $upcomingHomeworkResult->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Map SubjectId to name (or fetch from subjects table if exists)
function getSubjectName($id, $connect) {
    // Try DB lookup for subject name
    $subStmt = $connect->prepare("SELECT Name FROM subjects WHERE Id = ?");
    $subStmt->bind_param("i", $id);
    $subStmt->execute();
    $subStmt->bind_result($name);
    if ($subStmt->fetch()) {
        $subStmt->close();
        return $name;
    }
    $subStmt->close();
    return "Unknown Subject";
}

// Fetch Recent Results (limit 5 latest)
$stmt = $connect->prepare("
    SELECT oa.SubjectName, oa.Topic, oa.Title, oa.DueDate, la.UserId, la.CreatedAt,
    ROUND(SUM(oq.CorrectAnswer = la.SelectedAnswer) / COUNT(*) * 100) AS ScorePercent
    FROM learneranswers la
    JOIN onlinequestions oq ON la.QuestionId = oq.Id
    JOIN onlineactivities oa ON la.ActivityId = oa.Id
    WHERE la.UserId = ?
    GROUP BY la.ActivityId
    ORDER BY la.CreatedAt DESC
    LIMIT 5
");
$stmt->bind_param("i", $learnerId);
$stmt->execute();
$recentResultsResult = $stmt->get_result();
$recentResults = $recentResultsResult->fetch_all(MYSQLI_ASSOC);
$stmt->close();

?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include("learnerpartials/header.php"); ?>
  <?php include("learnerpartials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1 style="color:#3a3a72; font-weight:600;">Welcome back, <?= htmlspecialchars($learnerName) ?> 👋</h1>
      <p style="color:#888;">Here’s a quick overview of your learning journey.</p>
    </section>

    <section class="content">
      <div class="row">
        <!-- Metric Cards -->
        <div class="col-md-3">
          <div class="box" style="background:#e6f0ff; border-radius:15px; box-shadow:0 0 10px rgba(0,0,0,0.05);">
            <div class="box-body">
              <h4 style="color:#3a3a72;">Pending Homework</h4>
              <h2 style="font-weight:bold;"><?= $pendingHomeworkCount ?></h2>
              <i class="fa fa-tasks fa-2x pull-right" style="color:#6a52a3;"></i>
              <a href="homework.php" class="btn btn-link">View All</a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="box" style="background:#f9f1fe; border-radius:15px;">
            <div class="box-body">
              <h4 style="color:#3a3a72;">Average Score</h4>
              <h2><?= $averageScore ?>%</h2>
              <i class="fa fa-bar-chart fa-2x pull-right" style="color:#a06cd5;"></i>
              <a href="myresults.php" class="btn btn-link">View Results</a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="box" style="background:#f0f7ff; border-radius:15px;">
            <div class="box-body">
              <h4 style="color:#3a3a72;">Attendance</h4>
              <h2>%</h2>
              <i class="fa fa-calendar-check-o fa-2x pull-right" style="color:#0073e6;"></i>
              <a href="attendance.php" class="btn btn-link">Track Attendance</a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="box" style="background:#d1ffe0; border-radius:15px;">
            <div class="box-body">
              <h4 style="color:#3a3a72;">Completed Tasks</h4>
              <h2><?= $completedTasksCount ?></h2>
              <i class="fa fa-check-circle fa-2x pull-right" style="color:#28a745;"></i>
              <a href="completed.php" class="btn btn-link">View Completed</a>
            </div>
          </div>
        </div>
      </div>

      <!-- Upcoming Homework + Chart -->
      <div class="row">
        <div class="col-md-6">
          <div class="box" style="border-top: 3px solid #3a3a72;">
            <div class="box-header with-border">
              <h3 class="box-title">Upcoming Homework</h3>
            </div>
            <div class="box-body">
              <?php if (count($upcomingHomework) === 0): ?>
                <p>No upcoming homework.</p>
              <?php else: ?>
                <ul class="list-group">
                  <?php foreach ($upcomingHomework as $hw): 
                    $subjectName = getSubjectName(intval($hw['SubjectName']), $connect);
                    $dueDate = date("j M Y", strtotime($hw['DueDate']));
                  ?>
                    <li class="list-group-item">
                      <strong><?= htmlspecialchars($subjectName) ?>:</strong> <?= htmlspecialchars($hw['Title']) ?> (Due: <?= $dueDate ?>)
                      <span class="label label-warning pull-right">Pending</span>
                    </li>
                  <?php endforeach; ?>
                </ul>
                <a href="myhomework.php" class="btn btn-primary btn-sm" style="margin-top:10px;">See All Homework</a>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="box" style="border-top: 3px solid #6a52a3;">
            <div class="box-header with-border">
              <h3 class="box-title">Performance This Term</h3>
            </div>
            <div class="box-body">
              <canvas id="termChart" width="100%" height="70"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Results -->
      <div class="row">
        <div class="col-md-12">
          <div class="box" style="border-top: 3px solid #a3c1f7;">
            <div class="box-header with-border">
              <h3 class="box-title">Recent Results</h3>
            </div>
            <div class="box-body">
              <?php if (count($recentResults) === 0): ?>
                <p>No recent results available.</p>
              <?php else: ?>
              <table class="table table-bordered">
                <thead>
                  <tr style="background-color:#f0f7ff;">
                    <th>Subject</th>
                    <th>Topic</th>
                    <th>Score</th>
                    <th>Date</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($recentResults as $result):
                    $subjectName = getSubjectName(intval($result['SubjectName']), $connect);
                    $date = date("j M Y", strtotime($result['CreatedAt']));
                  ?>
                  <tr>
                    <td><?= htmlspecialchars($subjectName) ?></td>
                    <td><?= htmlspecialchars($result['Topic']) ?></td>
                    <td>
                      <?php if ($result['ScorePercent'] >= 75): ?>
                        <span class="label label-success"><?= $result['ScorePercent'] ?>%</span>
                      <?php elseif ($result['ScorePercent'] >= 50): ?>
                        <span class="label label-warning"><?= $result['ScorePercent'] ?>%</span>
                      <?php else: ?>
                        <span class="label label-danger"><?= $result['ScorePercent'] ?>%</span>
                      <?php endif; ?>
                    </td>
                    <td><?= $date ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
              <a href="myresults.php" class="btn btn-link">See All Results</a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

    </section>
  </div>

</div>

<!-- JS Scripts -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/chart.js/Chart.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>

<script>
  // Chart data - you can make this dynamic by fetching monthly averages or homework scores from DB
  const ctx = document.getElementById('termChart').getContext('2d');
  const chart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['April', 'May', 'June'],
      datasets: [{
        label: 'Homework Scores',
        data: [78, 85, 90],  // TODO: replace with real dynamic data
        backgroundColor: '#6a52a3'
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true, max: 100 }
      }
    }
  });
</script>

</body>
</html>
