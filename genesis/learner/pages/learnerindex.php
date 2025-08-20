<!DOCTYPE html>
<html>
<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../common/login.php");
    exit();
}

include('../../partials/connect.php');
include(__DIR__ . "/../../common/partials/head.php"); 

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

  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1 style="color:#3a3a72; font-weight:600;">Welcome back, <?= htmlspecialchars($learnerName) ?> 👋 </h1>
      <p style="color:#888;">Here’s a quick overview of your learning journey.</p>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Dashboard</li>
        </ol>
    </section>

<button class="btn btn-primary" data-toggle="modal" data-target="#learnerNotificationsModal">
    View Notifications
  </button>

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

      <div class="row">
        <div class="col-md-12">
          <div class="box" style="border-top:3px solid #3a3a72;">
            <div class="box-header with-border">
              <h3 class="box-title">Quick Actions</h3>
            </div>
            <div class="box-body text-center">
              <a href="startquiz.php" class="btn btn-primary" style="margin:5px;">📘 Start New Quiz</a>
              <a href="studymaterials.php" class="btn btn-info" style="margin:5px;">📂 View Study Materials</a>
              <a href="myresults.php" class="btn btn-success" style="margin:5px;">📊 Check Results</a>
              <a href="homework.php" class="btn btn-warning" style="margin:5px;">📝 Pending Homework</a>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="box" style="border-top:3px solid #ff9800;">
            <div class="box-header with-border">
              <h3 class="box-title">Class Leaderboard</h3>
            </div>
            <div class="box-body">
              <table class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>Rank</th>
                    <th>Learner</th>
                    <th>Score (%)</th>
                    <th>Badges</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>1</td>
                    <td>Jane Doe</td>
                    <td>95%</td>
                    <td>🏆🎯</td>
                  </tr>
                  <tr>
                    <td>2</td>
                    <td>John Smith</td>
                    <td>92%</td>
                    <td>🎯</td>
                  </tr>
                  <tr>
                    <td>3</td>
                    <td>Mary Johnson</td>
                    <td>90%</td>
                    <td>🎯</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>





      <div class="row">

        <!-- Upcoming Homework -->
        <div class="col-md-6">
          <div class="box" style="border-top:3px solid #0073e6;">
            <div class="box-header with-border">
              <h3 class="box-title">Upcoming Homework</h3>
            </div>
            <div class="box-body">
              <ul>
                <li>Math – Algebra Quiz <span style="color:red;">Due Tomorrow</span></li>
                <li>Science – Lab Report <span style="color:orange;">Due in 3 days</span></li>
                <li>History – Essay <span style="color:green;">Due in 1 week</span></li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Recent Results -->
        <div class="col-md-6">
          <div class="box" style="border-top:3px solid #28a745;">
            <div class="box-header with-border">
              <h3 class="box-title">Recent Results</h3>
            </div>
            <div class="box-body">
              <table class="table table-bordered">
                <tr><th>Subject</th><th>Score</th><th>Date</th></tr>
                <tr><td>Math</td><td>85%</td><td>Aug 10</td></tr>
                <tr><td>Science</td><td>92%</td><td>Aug 7</td></tr>
              </table>
            </div>
          </div>
        </div>

      </div>

      <div class="row">

          <!-- Announcements -->
          <div class="col-md-6">
            <div class="box" style="border-top:3px solid #ff9800;">
              <div class="box-header with-border">
                <h3 class="box-title">Announcements</h3>
              </div>
              <div class="box-body">
                <p>📢 School closed on Friday for maintenance.</p>
                <p>📢 New worksheets uploaded for Grade 10 Science.</p>
              </div>
            </div>
          </div>

          <!-- Achievements -->
          <div class="col-md-6">
            <div class="box" style="border-top:3px solid #9c27b0;">
              <div class="box-header with-border">
                <h3 class="box-title">Your Achievements</h3>
              </div>
              <div class="box-body">
                <span class="badge" style="background:#28a745;">Perfect Score</span>
                <span class="badge" style="background:#0073e6;">On-Time Submissions</span>
                <span class="badge" style="background:#ff9800;">Consistency Star</span>
              </div>
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
<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

<!-- Notifications Modal -->
  <div class="modal fade" id="learnerNotificationsModal" tabindex="-1" role="dialog" aria-labelledby="learnerNotifTitle" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header bg-primary text-white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="learnerNotifTitle">Notification Centre</h4>
        </div>

        <div class="modal-body">
          <!-- Sample notifications -->
          <div class="panel panel-default">
            <div class="panel-heading">
              <strong>Date:</strong> 2025-08-20 10:30
            </div>
            <div class="panel-body">
              <strong>New Resource Uploaded:</strong> <a href="#">Algebra Notes</a><br>
              Check out the latest material uploaded for your Math class.
            </div>
          </div>

          <div class="panel panel-default">
            <div class="panel-heading">
              <strong>Date:</strong> 2025-08-19 16:00
            </div>
            <div class="panel-body">
              <strong>Upcoming Quiz Reminder:</strong> <a href="#">Science Quiz</a><br>
              Don't forget your quiz scheduled for tomorrow at 10:00 AM.
            </div>
          </div>

          <div class="panel panel-default">
            <div class="panel-heading">
              <strong>Date:</strong> 2025-08-18 09:00
            </div>
            <div class="panel-body">
              <strong>Feedback Received:</strong> <a href="#">Math Assignment</a><br>
              Your tutor has provided feedback on your recent submission.
            </div>
          </div>

          <div class="panel panel-default">
            <div class="panel-heading">
              <strong>Date:</strong> 2025-08-17 12:45
            </div>
            <div class="panel-body">
              <strong>Appreciation Received:</strong> <a href="#">Tutor John</a><br>
              Your tutor appreciated your active participation in the class.
            </div>
          </div>

          <div class="panel panel-default">
            <div class="panel-heading">
              <strong>Date:</strong> 2025-08-16 14:30
            </div>
            <div class="panel-body">
              <strong>Missed Homework Alert:</strong> <a href="#">History Essay</a><br>
              You missed the submission deadline. Please contact your tutor.
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>

      </div>
    </div>
  </div>

</body>
</html>
