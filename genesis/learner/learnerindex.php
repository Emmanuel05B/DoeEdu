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

  <?php include("learnerpartials/header.php"); ?>

  <!-- Left side column. contains the logo and sidebar -->
  <?php include("learnerpartials/mainsidebar.php"); ?>

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

      $sql = "SELECT COUNT(*) as count FROM learners";
      $result = $connect->query($sql);
      $row = $result->fetch_assoc();

    ?>

      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo 20; ?></h3>
              <p>Announcements</p>
            </div>
            <a href="noticepage.php">
              <div class="icon">
                <i class="fa fa-bell-o"></i>
              </div>
            </a>
          </div>
        </div>
        <!-- ./col -->
        
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo 15; ?></h3>
              <p>My Classes</p>
            </div>
            <a href="mmailbox.php">
              <div class="icon">
                <i class="fa fa-envelope-o"></i>
              </div>
            </a>
          </div>
        </div>

        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo 30; ?></h3>
              <p>Upcoming Deadlines</p>
            </div>
            <a href="......php">
              <div class="icon">
                <i class="fa fa-files-o"></i>
              </div>
            </a>
          </div>
        </div>


        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $row['count']; ?></h3>
              <p>f ff fff</p>
            </div>
            <a href="hghj.php">
              <div class="icon">
                <i class="ion ion-person"></i>
              </div>
            </a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
<!-- -----------------------start here ----------------------  -  -->
<div class="row">
        <!-- Left col -->
        <section class="col-lg-7 connectedSortable">
          <!-- TO DO List -->
          <div class="box box-primary">
            <div class="box-header">
              <i class="ion ion-clipboard"></i>

              <h3 class="box-title">To Do List</h3>

              <div class="box-tools pull-right">
                <ul class="pagination pagination-sm inline">
                  <li><a href="#">&laquo;</a></li>
                  <li><a href="#">1</a></li>
                  <li><a href="#">2</a></li>
                  <li><a href="#">3</a></li>
                  <li><a href="#">&raquo;</a></li>
                </ul>
              </div>
            </div>
            <!-- /.box-header -->
            <?php

            // Get the logged-in user's ID
            $creatorId = $_SESSION['user_id']; 

            // Fetch the tasks for the logged-in user from the database
            $sql = "SELECT * FROM TodoList WHERE CreatorId = ? ORDER BY DueDate ASC";  // You can adjust the sorting as needed
            $stmt = $connect->prepare($sql);
            $stmt->bind_param("i", $creatorId);
            $stmt->execute();
            $result = $stmt->get_result();
            ?>

            <div class="box-body">
                <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                <ul class="todo-list">
                    <?php
                    // Check if there are tasks for the logged-in user
                    if ($result->num_rows > 0) {
                        // Loop through the tasks and display them
                        while ($task = $result->fetch_assoc()) {
                            // Format the date and time
                            $dueDate = date('Y-m-d', strtotime($task['DueDate']));
                            $dueTime = date('H:i', strtotime($task['DueDate']));
                            ?>
                            <li>
                                <!-- drag handle -->
                                <span class="handle">
                                    <i class="fa fa-ellipsis-v"></i>
                                    <i class="fa fa-ellipsis-v"></i>
                                </span>
                                <!-- checkbox -->
                                <input type="checkbox" value="" <?php if ($task['Status'] == 1) echo 'checked'; ?>>
                                <!-- todo text -->
                                <span class="text"><?php echo htmlspecialchars($task['TaskText']); ?></span>
                                <!-- Emphasis label -->
                                <small class="label label-info">
                                    <i class="fa fa-clock-o"></i> <?php echo $dueDate . ' ' . $dueTime; ?>
                                </small>
                                <!-- General tools such as edit or delete-->
                                <div class="tools">
                                    <a href="updateTodo.php?todo_id=<?php echo $task['TodoId']; ?>" class="fa fa-edit"></a>
                                    <a href="deleteTodo.php?todo_id=<?php echo $task['TodoId']; ?>" class="fa fa-trash-o" onclick="return confirm('Are you sure you want to delete this task?');"></a>
                                </div>
                            </li>
                            <?php
                        }
                    } else {
                        echo '<li>No tasks found.</li>';
                    }
                    ?>
                </ul>
            </div>

            <!-- /.box-body -->
            <div class="box-footer clearfix no-border">

              <a href="todo.php" class="btn btn-block btn-primary"><i class="fa fa-plus"></i> Add item</a>


            </div>

          </div>
          <!-- /.box -->
          <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs pull-right">
              <li class="active"><a href="#attendance-line-chart" data-toggle="tab">Attendance Line Chart</a></li>
              <li><a href="#sales-chart" data-toggle="tab"> xxxxxx xxxxxxx</a></li>
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
         


        </section>
        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-5 connectedSortable">
          <!-- quick email widget -->
          <div class="box box-info">
            <div class="box-header">
             
            </div>
            <div class="box-body">
            
            </div>
          </div>

          <!-- bar graph -->
          <div class="box box-solid">
            <div class="box-header">
             
            </div>
            <div class="box-body border-radius-none">
            
            </div>
            <!-- /.box-footer -->
          </div>


        </section>
        <!-- right col -->
      </div>

<!-- -----------------------end here ----------------------  -  -->


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



<div class="row">
    
  <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

  // Line Chart for Attendance
  const ctxLineAttendance = document.getElementById('lineChartAttendance').getContext('2d');
  const lineChartAttendance = new Chart(ctxLineAttendance, {
    type: 'line',
    data: {
      labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6', 'Week 7', 'Week 8', 'Week 9', 'Week 10', 'Week 11', 'Week 12'],
      datasets: [
        {
          label: 'Maths',
          data: [100, 100, 98, 100, 91, 100, 98, 95, 96, 100, 99, 100], 
          borderColor: 'rgba(14, 241, 14, 0.98)',
          backgroundColor: 'rgba(14, 241, 14, 0.98)',
          fill: false
        },
        {
          label: 'Physical Sciences',
          data: [45, 45, 56, 54, 41, 50, 47, 47, 48, 50, 46, 43], 
          borderColor: 'rgb(240, 17, 110)',
          backgroundColor: 'rgb(240, 17, 110)',
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


</script>

<?php include("learnerpartials/queries.php"); ?>
<script src="dist/js/demo.js"></script>
</body>
</html>
