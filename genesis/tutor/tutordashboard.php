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

  <div class="content-wrapper">
    <section class="content-header">
      <h1 style="color:#3a3a72; font-weight:700;">
        Tutor Dashboard
      </h1>
      <hr style="border-color:#a3c1f7;">
    </section>

    <section class="content">
      <div class="row">
        <!-- Stat Boxes -->
        <div class="col-lg-3 col-xs-6">
          <div class="small-box" style="background:#3498db; color:#ffffff;">
            <div class="inner">
              <h3>5</h3>
              <p>Pending Homework</p>
            </div>
            <div class="icon">
              <i class="fa fa-book"></i>
            </div>
            <a href="myhomework.php" class="small-box-footer">View Homework <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <div class="small-box" style="background:#6c5ce7; color:#ffffff;">
            <div class="inner">
              <h3>82%</h3>
              <p>Average Score</p>
            </div>
            <div class="icon">
              <i class="fa fa-line-chart"></i>
            </div>
            <a href="myresults.php" class="small-box-footer">View Results <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <div class="small-box" style="background:#1abc9c; color:#ffffff;">
            <div class="inner">
              <h3>12</h3>
              <p>Completed Tasks</p>
            </div>
            <div class="icon">
              <i class="fa fa-check-circle"></i>
            </div>
            <a href="completed.php" class="small-box-footer">See All <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <div class="small-box" style="background:#fff3cd; color:#3a3a72;">
            <div class="inner">
              <h3>100%</h3>
              <p>Attendance</p>
            </div>
            <div class="icon">
              <i class="fa fa-calendar-check-o"></i>
            </div>
            <a href="attendance.php" class="small-box-footer">Track Attendance <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>

      <!-- Chart, Homework Summary, and Quick Stats -->
      <div class="row">
        <div class="col-md-6">
          <div class="box" style="border-top: 3px solid #a3c1f7;">
            <div class="box-header with-border">
              <h3 class="box-title" style="color:#3a3a72;">Performance Chart</h3>
            </div>
            <div class="box-body">
              <canvas id="scoreChart" width="100%" height="80"></canvas>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="box" style="border-top: 3px solid #a3c1f7;">
            <div class="box-header with-border">
              <h3 class="box-title" style="color:#3a3a72;">Upcoming Homework</h3>
            </div>
            <div class="box-body">
              <ul class="list-group">
                <li class="list-group-item">Maths - Algebra (Due: 2025-06-20)</li>
                <li class="list-group-item">Physics - Motion (Due: 2025-06-22)</li>
                <li class="list-group-item">Life Science - Cells (Due: 2025-06-25)</li>
              </ul>
              <a href="myhomework.php" class="btn btn-sm btn-primary" style="margin-top:10px;">Go to My Homework</a>
            </div>
          </div>
        </div>
      </div>

      <!-- Additional Tutor Info -->
      <div class="row">
        <div class="col-md-6">
          <div class="box" style="border-top: 3px solid #a3c1f7;">
            <div class="box-header with-border">
              <h3 class="box-title" style="color:#3a3a72;">Upcoming Tutoring Sessions</h3>
            </div>
            <div class="box-body">
              <p><strong>Next Session:</strong> 2025-06-21 at 10:00 AM – Grade 10 Math</p>
              <p><strong>Requests Pending:</strong> 2 new session requests</p>
              <a href="schedule.php" class="btn btn-sm btn-info">Manage Schedule</a>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="box" style="border-top: 3px solid #a3c1f7;">
            <div class="box-header with-border">
              <h3 class="box-title" style="color:#3a3a72;">Recent Feedback</h3>
            </div>
            <div class="box-body">
              <blockquote>
                "Thank you for explaining electricity so clearly!"
              </blockquote>
              <p><strong>Rating:</strong> ★★★★☆</p>
              <a href="feedback.php" class="btn btn-sm btn-success">See All Feedback</a>
            </div>
          </div>
        </div>
      </div>

    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<!-- Scripts -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/chart.js/Chart.min.js"></script>
<script src="dist/js/adminlte.min.js"></scrip