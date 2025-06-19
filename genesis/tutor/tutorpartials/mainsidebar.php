<aside class="main-sidebar">
<?php
 include('../partials/connect.php');
$userId = $_SESSION['user_id'];  //for looged in teacher

$sql = "SELECT Surname FROM users WHERE Id =  $userId";

$usql = "SELECT * FROM users WHERE Id = $userId" ;
$Principalresults = $connect->query($usql);
$Principalresultsfinal = $Principalresults->fetch_assoc();  

?>
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="dist/img/avatar5.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Mr <?php echo $Principalresultsfinal['Surname'] ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>

          <li><a href="adminindex.php"><i class="fa fa-circle-o"></i> Home</a></li>
          <li><a href="tutordashboard.php"><i class="fa fa-circle-o"></i> Dashboard</a></li>

          <li><a href="chartjs.php"><i class="fa fa-circle-o"></i>Activity Analysis</a></li>
          <li><a href="categ.php"><i class="fa fa-circle-o"></i> Record Marks</a></li>
          <li><a href="classes.php"><i class="fa fa-circle-o"></i> Track learner Progress</a></li>
          <li><a href="schedulemeeting.php"><i class="fa fa-circle-o"></i> Communications</a></li>
          <li><a href="logout.php"><i class="fa fa-circle-o"></i> Log out</a></li>
           <li><a href="manageactivities.php"><i class="fa fa-circle-o"></i> Manage Activities </a></li>
           <li><a href="managestudymaterials.php"><i class="fa fa-circle-o"></i> Manage Study materials </a></li>
           <li><a href="Sessions.php"><i class="fa fa-circle-o"></i> Sessions </a></li>
           <li><a href="announcements.php"><i class="fa fa-circle-o"></i> Announcements </a></li>
           <li><a href="reminders.php"><i class="fa fa-circle-o"></i> Reminders </a></li>
           <li><a href="profilemanagement.php"><i class="fa fa-circle-o"></i> My Profile </a></li>
           <li><a href="attendancetracking.php"><i class="fa fa-circle-o"></i> Attendance </a></li>
           <li><a href="#.php"><i class="fa fa-circle-o"></i> xxxxxx </a></li>

        </li>
        
      </ul>

    </section>
    <!-- /.sidebar -->
  </aside>