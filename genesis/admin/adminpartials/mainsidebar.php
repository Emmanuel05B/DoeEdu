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
          <li><a href="manegeprofiles.php"><i class="fa fa-circle-o"></i> Manege Profile</a></li>
          <!--<li><a href="gradesreports.php"><i class="fa fa-circle-o"></i> Reports</a></li> -->
          <li><a href="trackingprogress.php"><i class="fa fa-circle-o"></i> Tracking and Analysis</a></li>
          <li><a href="schedulemeeting.php"><i class="fa fa-circle-o"></i> Schedule Meeting</a></li>
         <!--  <li><a href="manegedfgprograms.php"><i class="fa fa-circle-o"></i> Manege Programs</a></li> -->
          

          <li><a href="logout.php"><i class="fa fa-circle-o"></i> Log out</a></li>
        </li>
        
      </ul>

    </section>
    <!-- /.sidebar -->
  </aside>