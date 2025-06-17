<!DOCTYPE html>
<html>
<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}
?>
<?php include("learnerpartials/head.php"); ?>

<!-- Chart Libraries -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include("learnerpartials/header.php"); ?>
  <?php include("learnerpartials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Progress Tracker</h1>
      <p>Track your learning progress in Mathematics and Physical Sciences</p>
    </section>

    <section class="content">

      <!-- Row: Charts -->
      <div class="row">

        <!-- Quiz Scores Over Time -->
        <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Quiz Scores Over Time</h3>
            </div>
            <div class="box-body">
              <canvas id="quizChart" height="150"></canvas>
            </div>
          </div>
        </div>

        <!-- Homework Completion -->
        <div class="col-md-6">
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Homework Completion Rate</h3>
            </div>
            <div class="box-body">
              <canvas id="homeworkChart" height="150"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Row: Motivational Badges -->
      <div class="row">
        <div class="col-md-12">
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Your Achievements</h3>
            </div>
            <div class="box-body">
              <div class="row text-center">
                <div class="col-sm-3">
                  <img src="../assets/badges/fast_learner.png" width="80">
                  <p><strong>Fast Learner</strong><br>Completed 5 tasks in 1 week</p>
                </div>
                <div class="col-sm-3">
                  <img src="../assets/badges/accuracy.png" width="80">
                  <p><strong>Sharp Shooter</strong><br>Scored 100% on a quiz</p>
                </div>
                <div class="col-sm-3">
                  <img src="../assets/badges/consistency.png" width="80">
                  <p><strong>Consistent Star</strong><br>Logged in 7 days in a row</p>
                </div>
                <div class="col-sm-3">
                  <img src="../assets/badges/improvement.png" width="80">
                  <p><strong>Improved Hero</strong><br>Improved score by 20%</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<!-- Dummy Chart Scripts -->
<script>
  const quizCtx = document.getElementById('quizChart').getContext('2d');
  const quizChart = new Chart(quizCtx, {
    type: 'line',
    data: {
      labels: ['May', 'June', 'July', 'August'],
      datasets: [{
        label: 'Quiz Score (%)',
        data: [65, 70, 85, 90],
        backgroundColor: 'rgba(60,141,188,0.2)',
        borderColor: '#3c8dbc',
        borderWidth: 2,
        fill: true,
        tension: 0.4
      }]
    }
  });

  const homeworkCtx = document.getElementById('homeworkChart').getContext('2d');
  const homeworkChart = new Chart(homeworkCtx, {
    type: 'bar',
    data: {
      labels: ['May', 'June', 'July', 'August'],
      datasets: [{
        label: 'Homework Completion (%)',
        data: [80, 90, 100, 95],
        backgroundColor: '#00a65a'
      }]
    }
  });
</script>
</body>
</html>
