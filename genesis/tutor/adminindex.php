<!DOCTYPE html>
<html>
<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}

include('../partials/connect.php');
include("tutorpartials/head.php");
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include("tutorpartials/header.php"); ?>
  <?php include("tutorpartials/mainsidebar.php"); ?>

  <div class="content-wrapper" style="background-color:#f7f9fc;">
    <section class="content-header">
      <h1 style="color:#4a6fa5;">
        Dashboard
        <small>Your tutor overview</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="tutordashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <section class="content">

      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <div class="small-box" style="background:#556cd6; color:#fff;">
            <div class="inner">
              <h3>34</h3>
              <p>Total Activities</p>
            </div>
            <div class="icon">
              <i class="fa fa-tasks"></i>
            </div>
            <a href="manageactivities.php" class="small-box-footer" style="color:#d4dbff;">
              Manage Activities <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <div class="small-box" style="background:#a3bffa; color:#000;">
            <div class="inner">
              <h3>5</h3>
              <p>Pending Session Requests</p>
            </div>
            <div class="icon">
              <i class="fa fa-clock-o"></i>
            </div>
            <a href="sessionrequests.php" class="small-box-footer" style="color:#3a479a;">
              View Requests <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <div class="small-box" style="background:#9f86d1; color:#fff;">
            <div class="inner">
              <h3>120</h3>
              <p>Active Learners</p>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
            <a href="learnergroups.php" class="small-box-footer" style="color:#d7cafb;">
              Manage Groups <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <div class="small-box" style="background:#b2d8b2; color:#355e35;">
            <div class="inner">
              <h3>3</h3>
              <p>Upcoming Deadlines</p>
            </div>
            <div class="icon">
              <i class="fa fa-calendar"></i>
            </div>
            <a href="manageactivities.php" class="small-box-footer" style="color:#5a7b57;">
              View Deadlines <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-7">
          <div class="box box-solid box-primary">
            <div class="box-header" style="background-color:#a3bffa; color:#2e3c82;">
              <i class="fa fa-line-chart"></i>
              <h3 class="box-title">Learner Progress Overview</h3>
            </div>
            <div class="box-body" style="background-color:#d1d9ff;">
              <canvas id="progressChart" style="width:100%; height:280px;"></canvas>
            </div>
          </div>
        </div>

        <div class="col-md-5">
          <div class="box box-solid box-purple">
            <div class="box-header" style="background-color:#9f86d1; color:#fff;">
              <i class="fa fa-bell"></i>
              <h3 class="box-title">Notifications</h3>
            </div>
            <div class="box-body" style="background-color:#dcd7f7; max-height:280px; overflow-y:auto;">
              <ul class="timeline timeline-inverse" style="margin-bottom:0;">
                <li>
                  <i class="fa fa-info-circle bg-blue"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> 2 hours ago</span>
                    <h3 class="timeline-header"><a href="#">Director</a> posted a new announcement</h3>
                    <div class="timeline-body">
                      New study materials uploaded for Grade 11 Science.
                    </div>
                  </div>
                </li>
                <li>
                  <i class="fa fa-calendar bg-purple"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> Yesterday</span>
                    <h3 class="timeline-header"><a href="#">System</a> Reminder</h3>
                    <div class="timeline-body">
                      Upcoming maintenance scheduled for 28 June.
                    </div>
                  </div>
                </li>
                <li>
                  <i class="fa fa-users bg-green"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> 3 days ago</span>
                    <h3 class="timeline-header"><a href="#">Session Requests</a> pending approval</h3>
                    <div class="timeline-body">
                      You have 5 session requests awaiting your response.
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

    </section>
  </div>

</div>

<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../dist/js/app.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  const ctx = document.getElementById('progressChart').getContext('2d');
  const progressChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5'],
      datasets: [{
        label: 'Average Score',
        data: [75, 80, 78, 85, 90],
        backgroundColor: 'rgba(163, 191, 250, 0.4)',
        borderColor: '#556cd6',
        borderWidth: 3,
        pointBackgroundColor: '#556cd6',
        fill: true,
        tension: 0.3
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          min: 0,
          max: 100,
          ticks: { stepSize: 10 }
        }
      }
    }
  });
</script>

</body>
</html>
