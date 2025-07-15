<!DOCTYPE html>
<html>
<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}
?>
<?php include("adminpartials/head.php"); ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include("adminpartials/header.php"); ?>
  <?php include("adminpartials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Activity Overview</h1>
      <small class="text-muted">Summary for: <strong>Algebra Quiz - Grade 10</strong></small>
    </section>

    <section class="content">
      <!-- Activity Details First -->
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title">Activity Details</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <p><strong>Title:</strong> Algebra Quiz</p>
              <p><strong>Grade:</strong> Grade 10</p>
              <p><strong>Created By:</strong> Mr. Smith (Math Tutor)</p>
              <p><strong>Instructions:</strong> Answer all 10 questions. Each question is worth 5 marks.</p>
            </div>
            <div class="col-md-6">
              <p><strong>Due Date:</strong> July 15, 2025</p>
              <p><strong>Submission Window:</strong> July 10 – July 15</p>
              <p><strong>Total Marks:</strong> 50</p>
              <p><strong>Status:</strong> <span class="label label-success">Active</span></p>
            </div>
          </div>
        </div>
      </div>

      <!-- Top Row Summary Cards -->
      <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box bg-aqua">
            <span class="info-box-icon"><i class="fa fa-users"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Assigned</span>
              <span class="info-box-number">50 learners</span>
            </div>
          </div>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-check-circle"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Completed</span>
              <span class="info-box-number">42 learners</span>
            </div>
          </div>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Not Submitted</span>
              <span class="info-box-number">8 learners</span>
            </div>
          </div>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box bg-red">
            <span class="info-box-icon"><i class="fa fa-percent"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Completion Rate</span>
              <span class="info-box-number">84%</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Scores Row -->
      <div class="row">
        <div class="col-md-4">
          <div class="box box-solid box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Average Score</h3>
            </div>
            <div class="box-body text-center">
              <h2 style="font-size: 38px; margin-top: 10px">64%</h2>
              <p class="text-muted">Calculated from all completed submissions</p>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="box box-solid box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Highest Score</h3>
            </div>
            <div class="box-body text-center">
              <h2 style="font-size: 38px; margin-top: 10px">98%</h2>
              <p class="text-muted">Top-performing learner</p>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="box box-solid box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Lowest Score</h3>
            </div>
            <div class="box-body text-center">
              <h2 style="font-size: 38px; margin-top: 10px">22%</h2>
              <p class="text-muted">Lowest-performing learner</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Button to view full results -->
      <div class="text-center" style="margin-bottom: 30px;">
        <a href="viewsubmissions.php?activity_id=123" class="btn btn-primary btn-lg">
          <i class="fa fa-eye"></i> View Individual Submissions
        </a>
      </div>
    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<!-- Scripts -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
