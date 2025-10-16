<aside class="main-sidebar">
<?php
 include('../../partials/connect.php');
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
        <li><a href="classes.php"><i class="fa fa-circle-o"></i> My Class</a></li>
        <li><a href="schedule.php"><i class="fa fa-circle-o"></i>Bookings & Availability</a></li>
        <li><a href="myactivities.php"><i class="fa fa-circle-o"></i> Activity Manegement</a></li>
        <li><a href="setupquestion.php"><i class="fa fa-circle-o"></i> Question Builder</a></li>

           
        <!-- Teaching & Content -->        
        <li><a href="studyresources.php"><i class="fa fa-circle-o"></i>Manage Resources</a></li>
        
        <li><a href="myactivities.php"><i class="fa fa-circle-o"></i> Manage Activities</a></li>


        <!-- Communication & Engagement -->
        <li><a href="x.php"><i class="fa fa-circle-o"></i> Communications</a></li>

        <!-- Profile & Logout -->
        <li><a href="profilemanagement.php"><i class="fa fa-circle-o"></i> My Profile</a></li>
        <li><a href="logout.php"><i class="fa fa-circle-o"></i> Log out</a></li>

        </li>
        
      </ul>

    </section>
    <!-- /.sidebar -->
  </aside>