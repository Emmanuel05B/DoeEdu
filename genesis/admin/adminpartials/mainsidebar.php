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
          <li><a href="manegeprofiles.php"><i class="fa fa-circle-o"></i> Registrations</a></li>
          <li><a href="finances.php"><i class="fa fa-circle-o"></i> Finances</a></li>

          <!-- Dropdown styled like other links, aligned to the right -->
          <li class="dropdown" style="list-style-type: none; padding-left: 0; margin-bottom: 0;">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="display: block; padding: 10px 15px;">
             <i class="fa fa-circle-o"></i> Tracking and Analysis <b class="caret"></b>
            </a>
            <ul class="dropdown-menu dropdown-menu-right" role="menu" style="min-width: 200px;">
              <li><a href="trackalllearners.php" style="padding: 10px 15px;">Track learner Progress</a></li>
              <li><a href="chartjs.php" style="padding: 10px 15px;">Activity Analysis</a></li>
              <li><a href="categ.php" style="padding: 10px 15px;">Record Marks</a></li>
              <li class="divider"></li>
              <li><a href="#" style="padding: 10px 15px;">Separated link</a></li>
            </ul>
          </li>

          <li><a href="categ.php"><i class="fa fa-circle-o"></i> My Class</a></li>
          <li><a href="schedulemeeting.php"><i class="fa fa-circle-o"></i> Communications</a></li>
          <li><a href="logout.php"><i class="fa fa-circle-o"></i> Log out</a></li>
        </li>
        
      </ul>

    </section>
    <!-- /.sidebar -->
  </aside>