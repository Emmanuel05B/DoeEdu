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
          <img src="../uploads/doe.jpg" class="img-circle" alt="User Image">
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
        <!-- Dashboard -->
        <li><a href="tutorindex.php"><i class="fa fa-circle-o"></i> Home / Dashboard</a></li>

        <!-- Learner Management -->
        <li><a href="classes1.php"><i class="fa fa-circle-o"></i> My Class</a></li>
        <li><a href="addsubjectnotice.php"><i class="fa fa-circle-o"></i> Add notices</a></li>
 
        <!-- Teaching & Content -->
        <li><a href="manageactivities.php"><i class="fa fa-circle-o"></i> Manage Activities</a></li>
        <li><a href="myactivities.php"><i class="fa fa-circle-o"></i> My Activities</a></li>
        <li><a href="managestudymaterials.php"><i class="fa fa-circle-o"></i> Manage Study Materials</a></li>
        <li><a href="Sessions.php"><i class="fa fa-circle-o"></i> Sessions</a></li>
        <li><a href="schedule.php"><i class="fa fa-circle-o"></i> Set Availability</a></li>


        <!-- Analysis & Reports -->
        <li><a href="chartjs.php"><i class="fa fa-circle-o"></i> Activity Analysis</a></li>

        <!-- Communication & Engagement -->
        <li><a href="announcements.php"><i class="fa fa-circle-o"></i> Announcements</a></li>
        <li><a href="reminders.php"><i class="fa fa-circle-o"></i> Reminders</a></li>
        <li><a href="x.php"><i class="fa fa-circle-o"></i> Communications</a></li>

        <!-- Profile & Logout -->
        <li><a href="profilemanagement.php"><i class="fa fa-circle-o"></i> My Profile</a></li>
        <li><a href="logout.php"><i class="fa fa-circle-o"></i> Log out</a></li>

        </li>
        
      </ul>

    </section>
    <!-- /.sidebar -->
  </aside>