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

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include("learnerpartials/header.php") ?>
  <?php include("learnerpartials/mainsidebar.php") ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>My Performance</h1>
    </section>

    <section class="content">
      <div class="row">
        <!-- Summary Cards -->
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>78%</h3>
              <p>Average Score</p>
            </div>
            <div class="icon">
              <i class="fa fa-percent"></i>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-green">
            <div class="inner">
              <h3>24</h3>
              <p>Total Activities</p>
            </div>
            <div class="icon">
              <i class="fa fa-tasks"></i>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>Maths</h3>
              <p>Best Subject</p>
            </div>
            <div class="icon">
              <i class="fa fa-star"></i>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-red">
            <div class="inner">
              <h3>Physics</h3>
              <p>Needs Improvement</p>
            </div>
            <div class="icon">
              <i class="fa fa-warning"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Charts Section -->
      <div class="row">
        <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Average per Subject</h3>
            </div>
            <div class="box-body">
              <canvas id="barChart"></canvas>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Score Over Time</h3>
            </div>
            <div class="box-body">
              <canvas id="lineChart"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Performance Table -->
      <div class="box box-success">
        <div class="box-header">
          <h3 class="box-title">Detailed Performance</h3>
        </div>
        <div class="box-body table-responsive">
          <table class="table table-bordered table-striped" id="performanceTable">
            <thead>
              <tr>
                <th>Subject</th>
                <th>Activity</th>
                <th>Total Marks</th>
                <th>Score</th>
                <th>%</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Maths</td>
                <td>Algebra Quiz</td>
                <td>20</td>
                <td>18</td>
                <td>90%</td>
                <td><span class="label label-success">Excellent</span></td>
                <td><a href="#" class="btn btn-xs btn-primary">View</a></td>
              </tr>
              <tr>
                <td>Physics</td>
                <td>Forces Homework</td>
                <td>25</td>
                <td>14</td>
                <td>56%</td>
                <td><span class="label label-danger">Needs Improvement</span></td>
                <td><a href="#" class="btn btn-xs btn-primary">View</a></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Example Bar Chart
  new Chart(document.getElementById("barChart"), {
    type: 'bar',
    data: {
      labels: ['Maths', 'Science', 'English', 'Geography'],
      datasets: [{
        label: 'Average %',
        data: [85, 70, 78, 65],
        backgroundColor: '#3c8dbc'
      }]
    }
  });

  // Example Line Chart
  new Chart(document.getElementById("lineChart"), {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
      datasets: [{
        label: 'Score Over Time',
        data: [65, 70, 75, 80, 78],
        fill: false,
        borderColor: '#00c0ef'
      }]
    }
  });
</script>

<script>
  $(function () {
    $('#performanceTable').DataTable();
  });
</script>
</body>
</html>
