<aside class="main-sidebar">
<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");

include_once(BASE_PATH . "/partials/connect.php");

$userId = $_SESSION['user_id'];

// Fetch user details
$userSql = "SELECT Name, Surname, Gender FROM users WHERE Id = ?";
$userStmt = $connect->prepare($userSql);
$userStmt->bind_param("i", $userId);
$userStmt->execute();
$userResult = $userStmt->get_result();
$userData = $userResult->fetch_assoc();


?>
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
            <img src="<?= PROFILE_PICS_URL . '/doe.jpg' ?>" class="img-circle" alt="User Image">

        </div>
        <div class="pull-left info">
          <p><?php echo htmlspecialchars($userData['Surname']); ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>

        <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> <span>Home</span></a></li>
        <li><a href="admin.php"><i class="fa fa-cogs"></i> <span>Administration</span></a></li>
        <li><a href="classes.php"><i class="fa fa-university"></i> <span>Classes</span></a></li>
        <li><a href="tutors.php"><i class="fa fa-users"></i> <span>Tutors</span></a></li> 
        <li><a href="learnerinfo.php"><i class="fa fa-users"></i> <span>Learner Info</span></a></li> 
        <li><a href="studyresources.php"><i class="fa fa-book"></i> <span>Study Resources</span></a></li>
        <li><a href="admincreatenotifications.php"><i class="fa fa-bell"></i> <span>Announcement Manager</span></a></li>
        <li><a href="finances.php"><i class="fa fa-money"></i> <span>Finances</span></a></li>
        <li><a href="myactivities.php"><i class="fa fa-tasks"></i> <span>Quiz Management</span></a></li>
        <li><a href="setupquestion.php"><i class="fa fa-cubes"></i> <span>Question Builder</span></a></li>
        <li><a href="ourdocs.php"><i class="fa fa-file"></i> <span>Documents</span></a></li>

        <li class="header">ACCOUNT</li>
        <li><a href="logout.php"><i class="fa fa-sign-out"></i> <span>Log out</span></a></li>
      </ul>
    </section>
    <!-- /.sidebar -->
</aside>
