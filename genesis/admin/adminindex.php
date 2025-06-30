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

      $sql = "SELECT COUNT(*) as count FROM learners";
      $result = $connect->query($sql);
      $row = $result->fetch_assoc();
/*

      $sql = "SELECT COUNT(*) as count FROM pmessages WHERE IsOpened = 0";
      $result = $connect->query($sql);
      $messagesrow = $result->fetch_assoc();

      $sql = "SELECT COUNT(*) as count FROM notices WHERE IsOpened = 0";
      $result = $connect->query($sql);
      $noticesrow = $result->fetch_assoc();
      */
    ?>

      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo 20; ?></h3>
              <p>Notifications</p>
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
              <p>New Message/s</p>
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
              <p>Reports</p>
            </div>
            <a href="gradesreports.php">
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
              <p>Learners Registered</p>
            </div>
            <a href="classes.php">
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
         


        </section>
        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-5 connectedSortable">
          <!-- quick email widget -->
          <div class="box box-info">
            <div class="box-header">
              <i class="fa fa-envelope"></i>

              <h3 class="box-title">Quick Email</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
                <button type="button" class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip"
                        title="Remove">
                  <i class="fa fa-times"></i></button>
              </div>
              <!-- /. tools -->
            </div>
            <div class="box-body">
              <form action="quickmail.php" method="post">
                <div class="form-group">
                <input type="email" class="form-control" name="emailto" placeholder="Email to:">
                </div>
                <div class="form-group">
                  <input type="text" class="form-control" name="subject" placeholder="Subject">
                  </div>
                <div>
                  <textarea class="textarea" name="message" placeholder="Message" style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                </div>
                <input type="submit" value="Submit" name="btnsend">
              </form>
            </div>
          </div>

          <!-- bar graph -->
          <div class="box box-solid">
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
        
            </div>
            <!-- /.box-footer -->
          </div>


        </section>
        <!-- right col -->
      </div>


<!-- -----------------------end here ----------------------  -  -->



<div class="row">
    
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
      labels: ['G-12 Maths', 'G-12 Physics', 'G-11 Maths', 'G-10 Maths'],
      datasets: [{
        data: [<?php echo 75 ?>, <?php echo 63 ?>, <?php echo 74 ?>, <?php echo 70 ?>],
        backgroundColor: ['rgba(14, 241, 14, 0.98)', 'rgb(245, 123, 9)', 'rgba(15, 209, 235, 0.97)', 'rgb(240, 17, 110) ']
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
          label: 'G-12 Maths',
          data: [100, 100, 98, 100, 91, 100, 98, 95, 96, 100, 99, 100], 
          borderColor: 'rgba(14, 241, 14, 0.98)',
          backgroundColor: 'rgba(14, 241, 14, 0.98)',
          fill: false
        },
        {
          label: 'G-12 Physics',
          data: [92, 87, 85, 75, 86, 92, 93, 85, 74, 85, 90, 86], 
          borderColor: 'rgb(245, 123, 9)',
          backgroundColor: 'rgb(245, 123, 9)',
          fill: false
        },
        {
          label: 'G-11 Maths',
          data: [98, 87, 80, 82, 80, 76, 89, 90, 92, 93, 85, 87], 
          borderColor: 'rgba(15, 209, 235, 0.97)',
          backgroundColor: 'rgba(15, 209, 235, 0.97)',
          fill: false
        },
        {
          label: 'G-10 Maths',
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

  // Bar Chart for Attendance
  const ctxBarAttendance = document.getElementById('barChartAttendance').getContext('2d');
  const barChartAttendance = new Chart(ctxBarAttendance, {
    type: 'bar',
    data: {
      labels: ['G-12 Maths', 'G-12 Physics', 'G-11 Maths', 'G-10 Maths'],
      //labels: ['Grade 12', 'Grade 11', 'Grade 10'],

      datasets: [{
        label: 'Attendance',
        data: [<?php echo 65 ?>, <?php echo 57 ?>, <?php echo 72 ?>, <?php echo 92 ?>],
        backgroundColor: 'rgba(0,166,90,0.2)',
        borderColor: 'rgba(0,166,90,1)',
        borderWidth: 1
      }]
      /*
      datasets: [
        {
          label: 'Mathematics',
          fillColor: '#00a65a',
          strokeColor: '#00a65a',
          pointColor: '#00a65a',
          data: [65, 59, 80]
        },
        {
          label: 'Physics',
          fillColor: '#f39c12',
          strokeColor: '#f39c12',
          pointColor: '#f39c12',
          data: [28, 48, 40]
        }
      ] */
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
