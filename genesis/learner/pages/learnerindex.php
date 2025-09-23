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

    $stmt1 = $connect->prepare("
      SELECT COUNT(DISTINCT oa.Id) 
      FROM onlineactivities oa
      LEFT JOIN learneranswers la 
          ON la.ActivityId = oa.Id AND la.UserId = ?
      LEFT JOIN onlineactivitiesassignments oaa
          ON oaa.AssignmentId = oa.Id
      WHERE la.Id IS NULL 
        AND oaa.DueDate >= CURDATE()
  ");
  
  if (!$stmt1) {
      die("Prepare failed: " . $connect->error);
  }

  $stmt1->bind_param("i", $learnerId);
  $stmt1->execute();
  $stmt1->bind_result($pendingHomeworkCount);
  $stmt1->fetch();
  $stmt1->close();     

  

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
      SELECT oa.Id, oa.SubjectId, oa.Title, oaa.DueDate
      FROM onlineactivities oa
      LEFT JOIN learneranswers la 
          ON la.ActivityId = oa.Id AND la.UserId = ?
      LEFT JOIN onlineactivitiesassignments oaa
          ON oaa.OnlineActivityId = oa.Id
      WHERE oaa.DueDate >= CURDATE() 
        AND la.Id IS NULL
      ORDER BY oaa.DueDate ASC
      LIMIT 5
  ");

  if (!$stmt) {
    die("Prepare failed: " . $connect->error);
}

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
      SELECT oa.SubjectId, oa.Topic, oa.Title, oaa.DueDate, la.UserId, la.CreatedAt,
            ROUND(SUM(oq.CorrectAnswer = la.SelectedAnswer) / COUNT(*) * 100) AS ScorePercent
      FROM learneranswers la
      JOIN onlinequestions oq 
          ON la.QuestionId = oq.Id
      JOIN onlineactivities oa 
          ON la.ActivityId = oa.Id
      LEFT JOIN onlineactivitiesassignments oaa 
          ON oaa.OnlineActivityId = oa.Id
      WHERE la.UserId = ?
      GROUP BY la.ActivityId
      ORDER BY la.CreatedAt DESC
      LIMIT 5
  ");


  if (!$stmt) {
      die("Prepare failed: " . $connect->error);
  }
  $stmt->bind_param("i", $learnerId);
  $stmt->execute();
  $recentResultsResult = $stmt->get_result();
  $recentResults = $recentResultsResult->fetch_all(MYSQLI_ASSOC);
  $stmt->close();


  
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="adminindex.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>Click</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lgd"><b>DoE_Genesis </b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        
        <span class="logo-lg"><b>Distributors Of Education </b></span>

      </a>
      
        <!-- Button to manually open the modal -->
      <a href="#" data-toggle="modal" data-target="#learnerNotificationsModal">
        <i class="fa fa-bell"></i> Notifications
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Notifications:-->
          
          <li href="#">
            <a href="#" data-toggle="modal" data-target="#learnerNotificationsModal">
            <i class="fa fa-bell-o"></i> Notifications
            </a>
          </li>

          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#">
              <img src="../images/emma.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php ?></span>
            </a>
          </li>
          
        </ul>
      </div>
    </nav>
  </header>
  
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
              <h2>95%</h2>
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
          <div class="box box-default" style="border-top:3px solid #3a3a72;">
            <div class="box-header with-border">
              <h3 class="box-title">Quick Links</h3>
            </div>
            <div class="box-body">

              <!-- ================= Profile & Settings ================= -->
              <h4 style="color:#3a3a72; margin-bottom:10px;">Profile & Settings</h4>
              <div class="row">
                <div class="col-md-3 col-sm-6">
                  <a href="profile.php">
                    <div class="small-box" style="background:#f0f8ff; color:#333;">
                      <div class="inner">
                        <h4>👤</h4>
                        <p>My Profile</p>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="col-md-3 col-sm-6">
                  <a href="mysubjects.php">
                    <div class="small-box" style="background:#e0f7fa; color:#333;">
                      <div class="inner">
                        <h4>📖</h4>
                        <p>My Subjects</p>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="col-md-3 col-sm-6">
                  <a href="booksession.php">
                    <div class="small-box" style="background:#fffde7; color:#333;">
                      <div class="inner">
                        <h4>📅</h4>
                        <p>Book a Session</p>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="col-md-3 col-sm-6">
                  <a href="tutorrating.php">
                    <div class="small-box" style="background:#fff3e0; color:#333;">
                      <div class="inner">
                        <h4>⭐</h4>
                        <p>Rate Your Tutor</p>
                      </div>
                    </div>
                  </a>
                </div>
              </div>

              <!-- ================= Study & Resources ================= -->
              <h4 style="color:#3a3a72; margin:20px 0 10px 0;">Study & Resources</h4>
              <div class="row">
                <div class="col-md-3 col-sm-6">
                  <a href="studymaterials.php">
                    <div class="small-box" style="background:#e8f5e9; color:#333;">
                      <div class="inner">
                        <h4>📚</h4>
                        <p>Study Materials</p>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="col-md-3 col-sm-6">
                  <a href="pastpapers.php">
                    <div class="small-box" style="background:#fff3f3; color:#333;">
                      <div class="inner">
                        <h4>📝</h4>
                        <p>Past Papers</p>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="col-md-3 col-sm-6">
                  <a href="practicequizzes.php">
                    <div class="small-box" style="background:#e8eaf6; color:#333;">
                      <div class="inner">
                        <h4>📝</h4>
                        <p>Sharpen My Skills</p>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="col-md-3 col-sm-6">
                  <a href="assignments.php">
                    <div class="small-box" style="background:#fff8e1; color:#333;">
                      <div class="inner">
                        <h4>🗂️</h4>
                        <p>Homeworks/Quizzes</p>
                      </div>
                    </div>
                  </a>
                </div>
              </div>

              <!-- ================= Progress & Performance ================= -->
              <h4 style="color:#3a3a72; margin:20px 0 10px 0;">Progress & Performance</h4>
              <div class="row">
                <div class="col-md-3 col-sm-6">
                  <a href="myresults.php">
                    <div class="small-box" style="background:#fff8e1; color:#333;">
                      <div class="inner">
                        <h4>📊</h4>
                        <p>My Results</p>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="col-md-3 col-sm-6">
                  <a href="progress.php">
                    <div class="small-box" style="background:#f0f7ff; color:#333;">
                      <div class="inner">
                        <h4>🚀</h4>
                        <p>Track Progress</p>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="col-md-3 col-sm-6">
                  <a href="schedule.php">
                    <div class="small-box" style="background:#fefcf0; color:#333;">
                      <div class="inner">
                        <h4>⏰</h4>
                        <p>My Schedule</p>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="col-md-3 col-sm-6">
                  <a href="attendance.php">
                    <div class="small-box" style="background:#f0fff0; color:#333;">
                      <div class="inner">
                        <h4>📅</h4>
                        <p>My Attendance</p>
                      </div>
                    </div>
                  </a>
                </div>
              </div>

              <!-- ================= Communication & Support ================= -->
              <h4 style="color:#3a3a72; margin:20px 0 10px 0;">Communication & Support</h4>
              <div class="row">
                <div class="col-md-3 col-sm-6">
                  <a href="support.php">
                    <div class="small-box" style="background:#fff0f5; color:#333;">
                      <div class="inner">
                        <h4>🆘</h4>
                        <p>Help & Support</p>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="col-md-3 col-sm-6">
                  <a href="forums.php">
                    <div class="small-box" style="background:#f1f8e9; color:#333;">
                      <div class="inner">
                        <h4>💬</h4>
                        <p>Discussion Forums</p>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="col-md-3 col-sm-6">
                  <a href="studentvoice.php">
                    <div class="small-box" style="background:#fff3e0; color:#333;">
                      <div class="inner">
                        <h4>🗣️</h4>
                        <p>Student Voice</p>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="col-md-3 col-sm-6">
                  <a href="announcements.php">
                    <div class="small-box" style="background:#fce4ec; color:#333;">
                      <div class="inner">
                        <h4>📢</h4>
                        <p>Announcements</p>
                      </div>
                    </div>
                  </a>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>






    </section>
  </div>

  

</div>

<!-- JS Scripts -->
<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

<!-- show modal the first time -->
<?php if (!isset($_SESSION['seen_notification'])): ?>
<script>
  $(document).ready(function () {
    $('#learnerNotificationsModal').modal('show');
  });
</script>
<?php $_SESSION['seen_notification'] = true; ?>
<?php endif; ?>

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
