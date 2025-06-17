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

// Example name fetch (optional)
$learnerName = $_SESSION['full_name'] ?? 'Learner';
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
              <h2 style="font-weight:bold;">3</h2>
              <i class="fa fa-tasks fa-2x pull-right" style="color:#6a52a3;"></i>
              <a href="myhomework.php" class="btn btn-link">View All</a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="box" style="background:#f9f1fe; border-radius:15px;">
            <div class="box-body">
              <h4 style="color:#3a3a72;">Average Score</h4>
              <h2>89%</h2>
              <i class="fa fa-bar-chart fa-2x pull-right" style="color:#a06cd5;"></i>
              <a href="myresults.php" class="btn btn-link">View Results</a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="box" style="background:#f0f7ff; border-radius:15px;">
            <div class="box-body">
              <h4 style="color:#3a3a72;">Attendance</h4>
              <h2>100%</h2>
              <i class="fa fa-calendar-check-o fa-2x pull-right" style="color:#0073e6;"></i>
              <a href="attendance.php" class="btn btn-link">Track Attendance</a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="box" style="background:#d1ffe0; border-radius:15px;">
            <div class="box-body">
              <h4 style="color:#3a3a72;">Completed Tasks</h4>
              <h2>10</h2>
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
              <ul class="list-group">
                <li class="list-group-item">
                  <strong>Math:</strong> Functions (Due: 18 June)
                  <span class="label label-warning pull-right">Pending</span>
                </li>
                <li class="list-group-item">
                  <strong>Life Science:</strong> Cell Division (Due: 21 June)
                  <span class="label label-warning pull-right">Pending</span>
                </li>
              </ul>
              <a href="myhomework.php" class="btn btn-primary btn-sm" style="margin-top:10px;">See All Homework</a>
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
                  <tr>
                    <td>Math</td>
                    <td>Algebra</td>
                    <td><span class="label label-success">95%</span></td>
                    <td>10 June 2025</td>
                  </tr>
                  <tr>
                    <td>Physics</td>
                    <td>Electricity</td>
                    <td><span class="label label-success">88%</span></td>
                    <td>8 June 2025</td>
                  </tr>
                </tbody>
              </table>
              <a href="myresults.php" class="btn btn-link">See All Results</a>
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
  const ctx = document.getElementById('termChart').getContext('2d');
  const chart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['April', 'May', 'June'],
      datasets: [{
        label: 'Homework Scores',
        data: [78, 85, 90],
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
