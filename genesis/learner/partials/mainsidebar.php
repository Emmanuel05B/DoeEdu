<aside class="main-sidebar">
<?php
  include('../../partials/connect.php');

$userId = $_SESSION['user_id'];  //for looged in learner

$sql = "SELECT Surname FROM users WHERE Id =  $userId";

$usql = "SELECT * FROM users WHERE Id = $userId" ;
$Principalresults = $connect->query($usql);
$Principalresultsfinal = $Principalresults->fetch_assoc();  

?>
    <!-- sidebar: style can be found in sidebar.less  -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="../images/emma.jpg" class="img-circle" alt="User Image">
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
        <li><a href="homework.php"><i class="fa fa-circle-o"></i> Homeworks</a></li>
        <li><a href="training.php"><i class="fa fa-circle-o"></i> Training</a></li>
        <li class=" treeview">
            <a href="#">
              <i class="fa fa-circle-o"></i> <span>xxxxxxx</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <?php
              // Query all schools
              $result = $connect->query("SELECT SchoolId, SchoolName FROM schools ORDER BY SchoolName ASC");
              if ($result && $result->num_rows > 0) {
                  while ($school = $result->fetch_assoc()) {
                      // Customize the URL as needed, e.g. passing school id as parameter
                      echo '<li><a href="classes.php?schoolId=' . htmlspecialchars($school['SchoolId']) . '"><i class="fa fa-circle-o text-aqua"></i> ' . htmlspecialchars($school['SchoolName']) . '</a></li>';
                  }
              } else {
                  echo '<li><a href="#"><i class="fa fa-circle-o text-red"></i> No schools found</a></li>';
              }
              ?>
            </ul>

          </li>
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