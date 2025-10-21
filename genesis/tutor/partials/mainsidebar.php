<aside class="main-sidebar">
<?php
include('../../partials/connect.php');

$userId = $_SESSION['user_id']; // Logged-in tutor ID

// Fetch user details (for name, surname, gender)
$userSql = "SELECT Surname, Gender FROM users WHERE Id = ?";
$userStmt = $connect->prepare($userSql);
$userStmt->bind_param("i", $userId);
$userStmt->execute();
$userResult = $userStmt->get_result();
$userData = $userResult->fetch_assoc();

// Fetch tutor details (for profile image and other tutor info)
$tutorSql = "SELECT ProfilePicture FROM tutors WHERE TutorId = ?";
$tutorStmt = $connect->prepare($tutorSql);
$tutorStmt->bind_param("i", $userId);
$tutorStmt->execute();
$tutorResult = $tutorStmt->get_result();
$tutorData = $tutorResult->fetch_assoc();

// Handle image fallback
$profileImage = !empty($tutorData['ProfilePicture'])
    ? "../uploads/" . htmlspecialchars($tutorData['ProfilePicture'])
    : "../../uploads/doe.jpg";
?>
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo $profileImage; ?>" class="img-circle" alt="User Image" style="width:45px; height:45px; object-fit:cover;">
        </div>
        <div class="pull-left info">
          <p><?php echo htmlspecialchars($userData['Gender']); ?> <?php echo htmlspecialchars($userData['Surname']); ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
 
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>

        <li><a href="tutorindex.php"><i class="fa fa-dashboard"></i> <span>Home / Dashboard</span></a></li>
        <li><a href="schedule.php"><i class="fa fa-calendar"></i> <span>Bookings & Availability</span></a></li>
        <li><a href="myactivities.php"><i class="fa fa-tasks"></i> <span>Activity Management</span></a></li>
        <li><a href="setupquestion.php"><i class="fa fa-cubes"></i> <span>Question Builder</span></a></li>

        <li class="header">ACCOUNT</li>
        <li><a href="profilemanagement.php"><i class="fa fa-user"></i> <span>My Profile</span></a></li>
        <li><a href="logout.php"><i class="fa fa-sign-out"></i> <span>Log out</span></a></li>

        <!--  <li><a href="classes.php"><i class="fa fa-circle-o"></i> My Class</a></li> -->
        <!-- <li><a href="x.php"><i class="fa fa-circle-o"></i> Communications</a></li>  -->
      </ul>
    </section>
    <!-- /.sidebar -->
</aside>
