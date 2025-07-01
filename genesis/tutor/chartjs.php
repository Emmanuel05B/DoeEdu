<!DOCTYPE html>
<html>
<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}
?>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Analytics</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

<?php include("tutorpartials/header.php"); ?>

<?php include("tutorpartials/mainsidebar.php"); ?> 

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
  
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-6">
          <!-- PIE CHART -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Activity Averages</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <canvas id="newPieChart" style="height:250px"></canvas>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- DONUT CHART -->
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Activity Engagement Rates</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <canvas id="pieChart" style="height:250px"></canvas>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          <div class="text-center" style="margin-top: 10px;">
              <a href="adminindex.php" class="btn btn-primary">Attendace Analysis</a>
           </div>

        </div>
        <!-- /.col (LEFT) -->
        <div class="col-md-6">
          <!-- BAR CHART -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Class Overall Perfomances</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <canvas id="newBarChart" style="height:250px"></canvas>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- ORIGINAL BAR CHART -->
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Class Perfomances</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <canvas id="barChart" style="height:230px"></canvas>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        </div>
        <!-- /.col (RIGHT) -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>

  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar --> 
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- ChartJS -->
<script src="bower_components/chart.js/Chart.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- page script -->
<script>
  $(function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    //--------------
    //- NEW PIE CHART -
    //--------------
    var newPieChartCanvas = $('#newPieChart').get(0).getContext('2d');
    var newPieChart = new Chart(newPieChartCanvas);
    var newPieData = [
      {
        value: 70,
        color: 'blue',
        highlight: 'blue',
        label: 'Life Skills'
      },
      {           
        value: 50,
        color: 'green',
        highlight: 'green',
        label: 'Story Time'
      },
      {
        value: 100,
        color: 'yellow',
        highlight: 'yellow',
        label: 'Sensory Intergration'
      },
      {
        value: 40,
        color: '#00c0ef',
        highlight: '#00c0ef',
        label: 'Outdoor Play'
      }
    ];
    var newPieOptions = {
      segmentShowStroke: true,
      segmentStrokeColor: '#fff',
      segmentStrokeWidth: 2,
      percentageInnerCutout: 0, // Set to 0 for a Pie chart
      animationSteps: 100,
      animationEasing: 'easeOutBounce',
      animateRotate: true,
      animateScale: false,
      responsive: true,
      maintainAspectRatio: true
    };
    newPieChart.Pie(newPieData, newPieOptions);

    //-------------
    //- NEW BAR CHART -
    //-------------
    var newBarChartCanvas = $('#newBarChart').get(0).getContext('2d');
    var newBarChart = new Chart(newBarChartCanvas);
    var newBarChartData = {
      labels: ['ASD Level 1', 'ASD Level 2', 'ASD Level 3'],
      datasets: [
        {
          label: 'Attendance',
          fillColor: 'rgba(28, 230, 6, 1)',
          strokeColor: 'rgba(28, 230, 6, 1)',
          pointColor: '#00a65a',
          data: [50, 75, 60]
        }
      ]
    };
    var newBarChartOptions = {
      scaleBeginAtZero: true,
      scaleShowGridLines: true,
      scaleGridLineColor: 'rgba(0,0,0,.05)',
      scaleGridLineWidth: 1,
      scaleShowHorizontalLines: true,
      scaleShowVerticalLines: true,
      barShowStroke: true,
      barStrokeWidth: 2,
      barValueSpacing: 5,
      barDatasetSpacing: 1,
      responsive: true,
      maintainAspectRatio: true
    };
    newBarChart.Bar(newBarChartData, newBarChartOptions);

    //--------------
    //- ORIGINAL PIE CHART (DONUT) -
    //--------------
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d');
    var pieChart = new Chart(pieChartCanvas);
    var PieData = [
      
      {
        value: 70,
        color: 'blue',
        highlight: 'blue',
        label: 'Life Skills'
      },
      {           
        value: 50,
        color: 'green',
        highlight: 'green',
        label: 'Story Time'
      },
      {
        value: 100,
        color: 'yellow',
        highlight: 'yellow',
        label: 'Sensory Intergration'
      },
      {
        value: 40,
        color: '#00c0ef',
        highlight: '#00c0ef',
        label: 'Outdoor Play'
      }
    ];
    var pieOptions = {
      segmentShowStroke: true,
      segmentStrokeColor: '#fff',
      segmentStrokeWidth: 2,
      percentageInnerCutout: 50, // This is 0 for Pie charts
      animationSteps: 100,
      animationEasing: 'easeOutBounce',
      animateRotate: true,
      animateScale: false,
      responsive: true,
      maintainAspectRatio: true
    };
    pieChart.Doughnut(PieData, pieOptions);

    //--------------
    //- ORIGINAL BAR CHART -
    //--------------
    var barChartCanvas = $('#barChart').get(0).getContext('2d');
    var barChart = new Chart(barChartCanvas);
    var barChartData = {
      labels: ['Life Skills', 'Story Time', 'Sensory Intergration', 'Outdoor Play'],
      datasets: [
        {
          label: 'ASD Level 1',
          fillColor: '#00a65a',
          strokeColor: '#00a65a',
          pointColor: '#00a65a',
          data: [65, 59, 80, 81]
        },
        {
          label: 'ASD Level 2',
          fillColor: '#f39c12',
          strokeColor: '#f39c12',
          pointColor: '#f39c12',
          data: [28, 48, 40, 19]
        },
        {
          label: 'ASD Level 3',
          fillColor: '#00c0ef',
          strokeColor: '#00c0ef',
          pointColor: '#f39c12',
          data: [38, 28, 30, 29]
        }
      ]
    };
    var barChartOptions = {
      scaleBeginAtZero: true,
      scaleShowGridLines: true,
      scaleGridLineColor: 'rgba(0,0,0,.05)',
      scaleGridLineWidth: 1,
      scaleShowHorizontalLines: true,
      scaleShowVerticalLines: true,
      barShowStroke: true,
      barStrokeWidth: 2,
      barValueSpacing: 5,
      barDatasetSpacing: 1,
      responsive: true,
      maintainAspectRatio: true,
      datasetFill: false
    };
    barChart.Bar(barChartData, barChartOptions);

  });
</script>
</body>
</html>
