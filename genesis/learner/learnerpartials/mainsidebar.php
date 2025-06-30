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

        <!-- Dashboard -->
        <li><a href="learnerindex.php"><i class="fa fa-circle-o"></i> Home / Dashboard</a></li>

        <!-- Learning & Activities -->
        <li><a href="mytutors.php"><i class="fa fa-circle-o"></i> My Tutors</a></li>
        <li><a href="learningresources.php"><i class="fa fa-circle-o"></i> Learning Resources</a></li>
        <li><a href="homework.php"><i class="fa fa-circle-o"></i> Homeworks & Assessments</a></li>
        <li><a href="perfomance.php"><i class="fa fa-circle-o"></i> My Performance</a></li>

        <!-- Support & Community -->
        <li><a href="announcements.php"><i class="fa fa-circle-o"></i> Announcements / News</a></li>
        <li><a href="forums.php"><i class="fa fa-circle-o"></i> Discussion Forums</a></li>
        <li><a href="voices.php"><i class="fa fa-circle-o"></i> Student Voice</a></li>
        <li><a href="calendar.php"><i class="fa fa-circle-o"></i> Calendar / Schedule</a></li>
        <li><a href="help.php"><i class="fa fa-circle-o"></i> Help & Support</a></li>

        <!-- Settings & Logout -->
        <li><a href="profilesettings.php"><i class="fa fa-circle-o"></i> Profile Settings</a></li>
        <li><a href="logout.php"><i class="fa fa-circle-o"></i> Log out</a></li>

        </li>
        
      </ul>

    </section>
    <!-- /.sidebar -->
  </aside>