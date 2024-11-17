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

  <!-- Left side column. contains the logo and sidebar -->
  <?php include("adminpartials/mainsidebar.php"); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

    <?php
      include('../partials/connect.php');

      $sql = "SELECT COUNT(*) as count FROM learner";
      $result = $connect->query($sql);
      $row = $result->fetch_assoc();

      $sql = "SELECT COUNT(*) as count FROM details";
      $result = $connect->query($sql);
      $reportrow = $result->fetch_assoc();

      $sql = "SELECT COUNT(*) as count FROM pmessages WHERE IsOpened = 0";
      $result = $connect->query($sql);
      $messagesrow = $result->fetch_assoc();

      $sql = "SELECT COUNT(*) as count FROM notices WHERE IsOpened = 0";
      $result = $connect->query($sql);
      $noticesrow = $result->fetch_assoc();
    ?>

      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $noticesrow['count']; ?></h3>
              <p>Notifications</p>
            </div>
            <a href="noticepage.php">
              <div class="icon">
                <i class="fa fa-bell-o"></i>
              </div>
            </a>
            <a href="noticepage.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $messagesrow['count']; ?></h3>
              <p>New Message/s</p>
            </div>
            <a href="mmailbox.php">
              <div class="icon">
                <i class="fa fa-envelope-o"></i>
              </div>
            </a>
            <a href="mmailbox.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $reportrow['count']; ?></h3>
              <p>Reports</p>
            </div>
            <a href="gradesreports.php">
              <div class="icon">
                <i class="fa fa-files-o"></i>
              </div>
            </a>
            <a href="gradesreports.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $row['count']; ?></h3>
              <p>Learners Registered</p>
            </div>
            <a href="alllearners.php">
              <div class="icon">
                <i class="ion ion-person"></i>
              </div>
            </a>
            <a href="alllearners.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->



<!-- -----------------------start here ----------------------  -  -->
<?php
include('../partials/connect.php');

// Step 1: Fetch all reporter_ids of type 1
$fetchIdsSql = "SELECT Id FROM employee WHERE employeeType = 1";
$fetchIdsResult = $connect->query($fetchIdsSql);

if ($fetchIdsResult === false) {
    echo "Error fetching reporter IDs: " . $connect->error;
    exit;
}

// Array to hold reporter IDs
$reporter_ids = [];

// Fetch the IDs and store them in the array
while ($row = $fetchIdsResult->fetch_assoc()) {
    $reporter_ids[] = $row['Id'];
}

// Check if there are any reporter IDs
if (empty($reporter_ids)) {
    echo "No reporter IDs of type 1 found.";
    exit;
}

// Initialize array to hold attendance data
$classAttendance = [];

// Step 2: Process each reporter_id to fetch class names and attendance data
foreach ($reporter_ids as $reporter_id) {
    // Get the class name for the current reporter
    $classSql = "SELECT Specialisation FROM employee WHERE Id = $reporter_id";
    $classResult = $connect->query($classSql);

    if ($classResult === false) {
        echo "Error fetching class for Reporter ID $reporter_id: " . $connect->error;
        continue;
    }

    $classRow = $classResult->fetch_assoc();
    $className = $classRow['Specialisation'];

    if ($className) {
        // Initialize attendance counts
        if (!isset($classAttendance[$className])) {
            $classAttendance[$className] = [
                'totalDays' => 0,
                'presentCount' => 0,
            ];
        }

        // Count attendance statuses for the class from the welcome table
        $attendanceSql = "SELECT Attendance FROM welcome WHERE ReporterId = '$reporter_id'";
        $attendanceResult = $connect->query($attendanceSql);

        if ($attendanceResult === false) {
            echo "Error fetching attendance for Class $className: " . $connect->error;
            continue;
        }

        // Count the statuses
        while ($row = $attendanceResult->fetch_assoc()) {
            $status = $row['Attendance'];
            $classAttendance[$className]['totalDays']++;

            // Count both "Present" and "Late" as present
            if ($status === 'Present' || $status === 'Late') {
                $classAttendance[$className]['presentCount']++;
            }
        }
    } else {
        echo "No class found for Reporter ID $reporter_id.";  
    }
}

$attendanceAverages = []; // Array to hold attendance averages
foreach ($classAttendance as $className => $attendance) {
    $totalDays = $attendance['totalDays'];
    $presentCount = $attendance['presentCount'];
    
    // Calculate attendance average as a percentage
    $averageAttendance = ($totalDays > 0) ? ($presentCount / $totalDays) * 100 : 0; 

    // Store the attendance average in a variable
    $attendanceAverages[$className] = round($averageAttendance, 2);
}

$ASD1 = $attendanceAverages['ASD Level 1'] ?? 0; 
$ASD2 = $attendanceAverages['ASD Level 2'] ?? 0; 
$ASD3 = $attendanceAverages['ASD Level 3'] ?? 0; 


?>

<!-- -----------------------end here ----------------------  -  -->





      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-7 connectedSortable">
          <!-- Custom tabs (Charts with tabs)-->
          <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs pull-right">
              <li class="active"><a href="#attendance-line-chart" data-toggle="tab">Attendance Line Chart</a></li>
              <li><a href="#sales-chart" data-toggle="tab"> Class Attendence Averages</a></li>
              <li class="pull-left header"><i class="fa fa-inbox"></i> Activities</li>
            </ul>
            <div class="tab-content no-padding">
              <!-- Attendance Line Chart -->
              <div class="chart tab-pane active" id="attendance-line-chart" style="position: relative; height: 300px;">
                <canvas id="lineChartAttendance" style="height: 300px;"></canvas>
              </div>
              <!-- Doughnut Chart -->
              <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                <canvas id="doughnutChart" style="height: 300px;"></canvas>
              </div>
            </div>
          </div>
          <!-- /.nav-tabs-custom -->
          
        </section>
        <!-- /.Left col -->

        <!-- right col -->
        <section class="col-lg-5 connectedSortable">
          <!-- Solid sales graph -->
          <div class="box box-solid bg-teal-gradient">
            <div class="box-header">
              <i class="fa fa-th"></i>
              <h3 class="box-title">Attendance Graph</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body border-radius-none">
              <div class="chart" id="attendance-chart" style="height: 250px;">
                <canvas id="barChartAttendance" style="height: 250px;"></canvas>
              </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer no-border">
              <div class="row">
                <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
                  <input type="text" class="knob" data-readonly="true" value="<?php echo $reportrow['count']; ?>" data-width="60" data-height="60"
                         data-fgColor="#39CCCC">
                  <div class="knob-label">Reports</div>
                </div>
                <!-- ./col -->
                <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
                  <input type="text" class="knob" data-readonly="true" value="4" data-width="60" data-height="60"
                         data-fgColor="#39CCCC">
                  <div class="knob-label">Activities</div>
                </div>
                <!-- ./col -->
                <div class="col-xs-4 text-center">
                  <input type="text" class="knob" data-readonly="true" value="<?php echo $noticesrow['count']; ?>" data-width="60" data-height="60"
                         data-fgColor="#39CCCC">
                  <div class="knob-label">Notifications</div>
                </div>
                <!-- ./col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- /.box-footer -->
          </div>
        </section>
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
  </div>
 
  <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Doughnut Chart Example
  const ctxDoughnut = document.getElementById('doughnutChart').getContext('2d');
  const doughnutChart = new Chart(ctxDoughnut, {
    type: 'doughnut',
    data: {
      labels: ['ASD Level 1', 'ASD Level 2', 'ASD Level 3'],
      datasets: [{
        data: [<?php echo $ASD1 ?>, <?php echo $ASD2 ?>, <?php echo $ASD3 ?>],
        backgroundColor: ['yellow', 'blue', 'skyblue']
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
    }
  });

  // Line Chart for Attendance
  const ctxLineAttendance = document.getElementById('lineChartAttendance').getContext('2d');
  const lineChartAttendance = new Chart(ctxLineAttendance, {
    type: 'line',
    data: {
      labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6', 'Week 7', 'Week 8', 'Week 9', 'Week 10', 'Week 11', 'Week 12'],
      datasets: [
        {
          label: 'ASD Level 1 Class',
          data: [100, 100, 100, 100, 100, 100, 100, 95, 100, 100, 100, 100], 
          borderColor: '#00a65a',
          backgroundColor: 'rgba(0,166,90,0.2)',
          fill: false
        },
        {
          label: 'ASD Level 2 Class',
          data: [92, 87, 85, 90, 86, 92, 93, 85, 87, 85, 90, 86], 
          borderColor: '#f39c12',
          backgroundColor: 'rgba(243,156,18,0.2)',
          fill: false
        },
        {
          label: 'ASD Level 3 Class',
          data: [98, 87, 80, 82, 80, 76, 89, 90, 92, 93, 85, 87], 
          borderColor: '#f56954',
          backgroundColor: 'rgba(245,105,84,0.2)',
          fill: false
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        x: {
          beginAtZero: true
        },
        y: {
          beginAtZero: true
        }
      },
      elements: {
        line: {
          tension: 0.1
        }
      }
    }
  });

  // Bar Chart for Attendance
  const ctxBarAttendance = document.getElementById('barChartAttendance').getContext('2d');
  const barChartAttendance = new Chart(ctxBarAttendance, {
    type: 'bar',
    data: {
      labels: ['ASD Level 1 class', 'ASD Level 2 class ', 'ASD Level 3 class'],
      datasets: [{
        label: 'Attendance',
        data: [<?php echo $ASD1 ?>, <?php echo $ASD2 ?>, <?php echo $ASD3 ?>],
        backgroundColor: 'rgba(0,166,90,0.2)',
        borderColor: 'rgba(0,166,90,1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>

<?php include("adminpartials/queries.php"); ?>
<script src="dist/js/demo.js"></script>
</body>
</html>
