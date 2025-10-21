<aside class="main-sidebar">
<?php
include('../../partials/connect.php');

$userId = $_SESSION['user_id'];

// Fetch user details
$userSql = "SELECT Name, Surname, Gender FROM users WHERE Id = ?";
$userStmt = $connect->prepare($userSql);
$userStmt->bind_param("i", $userId);
$userStmt->execute();
$userResult = $userStmt->get_result();
$userData = $userResult->fetch_assoc();


// Fetch admin profile picture (if stored in a separate table, otherwise use fallback)
$adminImagePath = "../uploads/admin_" . $userId . ".jpg";
$profileImage = file_exists($adminImagePath) ? $adminImagePath : "../images/doe.jpg";
?>
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo $profileImage; ?>" class="img-circle" alt="User Image" style="width:45px; height:45px; object-fit:cover;">
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
        <li><a href="studyresources.php"><i class="fa fa-book"></i> <span>Resources</span></a></li>
        <li><a href="admincreatenotifications.php"><i class="fa fa-bell"></i> <span>Create Notifications</span></a></li>
        <li><a href="finances.php"><i class="fa fa-money"></i> <span>Finances</span></a></li>
        <li><a href="myactivities.php"><i class="fa fa-tasks"></i> <span>Activity Management</span></a></li>
        <li><a href="setupquestion.php"><i class="fa fa-cubes"></i> <span>Question Builder</span></a></li>

        <li class="header">ACCOUNT</li>
        <li><a href="logout.php"><i class="fa fa-sign-out"></i> <span>Log out</span></a></li>
      </ul>
    </section>
    <!-- /.sidebar -->
</aside>
