
<?php
require_once __DIR__ . '/../../common/config.php';   
include_once(__DIR__ . "/../../partials/paths.php");

include_once(BASE_PATH . "/partials/connect.php");

 $userId = $_SESSION['user_id'];  //for looged in teacher


 $usql = "SELECT * FROM users WHERE Id = $userId" ;
 $Teacherresults = $connect->query($usql);
 $Teacherresultsfinal = $Teacherresults->fetch_assoc();  

        

        // Pending verification users
        $usersQuery = $connect->query("SELECT COUNT(*) as count FROM users WHERE IsVerified = 0 AND UserType = '2'");
        $pendingUsers = $usersQuery ? $usersQuery->fetch_assoc()['count'] : 0;

        // Invite requests
        $inviteQuery = $connect->query("SELECT COUNT(*) as count FROM inviterequests");
        $inviteRequests = $inviteQuery ? $inviteQuery->fetch_assoc()['count'] : 0;

        // Unread student voices
        $voicesQuery = $connect->query("SELECT COUNT(*) as count FROM studentvoices WHERE IsRead = 0");
        $unreadVoices = $voicesQuery ? $voicesQuery->fetch_assoc()['count'] : 0;

        // Expired contracts
        $expiredQuery = $connect->query("SELECT COUNT(*) AS count FROM learnersubject WHERE ContractExpiryDate < CURDATE() AND Status = 'Active'");
        $expiredContracts = $expiredQuery ? $expiredQuery->fetch_assoc()['count'] : 0;
?>

<header class="main-header">
    <!-- Logo -->
    <a href="adminindex.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>Click</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lgd"><b>DoE_Genesis </b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        
        <span class="logo-lg"><b>DoE </b></span>

      </a>

      <div class="navbar-custom-menu">

        <ul class="nav navbar-nav">
          <!-- Pending verification -->
          <li>
            <a href="pendingverifications.php">
              <i class="fa fa-user-times"></i>
              <span class="label label-warning"><?= $pendingUsers ?></span>
              
            </a>
          </li>

          <!-- Invite requests -->
          <li>
            <a href="manage_inviterequests.php">
              <i class="fa fa-envelope-open"></i>
              <span class="label label-info"><?= $inviteRequests ?></span>
              
            </a>
          </li>

          <!-- Student voices -->
          <li>
            <a href="voices.php">
              <i class="fa fa-bullhorn"></i>
              <span class="label label-success"><?= $unreadVoices ?></span>
              
            </a>
          </li>

          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <!--   <img src="../images/doe.jpg" class="user-image" alt="User Image"> -->
              <span class="hidden-xs"><?php echo $Teacherresultsfinal['Surname'] ?></span>
            </a>

          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>



  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark" style="display: none;">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">

      </div>
      
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">

        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>