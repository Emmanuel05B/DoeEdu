<aside class="main-sidebar">
<?php
include('../../partials/connect.php');

$userId = $_SESSION['user_id'];  // Logged-in learner

// Fetch learner's surname
$stmt = $connect->prepare("SELECT Surname FROM users WHERE Id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Fetch all subjects the learner is registered for/ will also have to take the group in to consderation
$subStmt = $connect->prepare("
    SELECT s.SubjectId, s.SubjectName
    FROM learnersubject ls
    JOIN subjects s ON ls.SubjectId = s.SubjectId
    WHERE ls.LearnerId = ?
    ORDER BY s.SubjectName ASC
");
$subStmt->bind_param("i", $userId);
$subStmt->execute();
$subjectsResult = $subStmt->get_result();
$subjects = [];
while ($row = $subjectsResult->fetch_assoc()) {
    $subjects[] = $row;
}
$subStmt->close();
?>
<section class="sidebar">
  <!-- Sidebar user panel -->
  <div class="user-panel">
    <div class="pull-left image">
      <img src="../images/emma.jpg" class="img-circle" alt="User Image">
    </div>
    <div class="pull-left info">
      <p>Mr <?php echo htmlspecialchars($user['Surname']); ?></p>
      <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
    </div>
  </div>

  <!-- search form -->
  <form action="#" method="get" class="sidebar-form">
    <div class="input-group">
      <input type="text" name="q" class="form-control" placeholder="Search...">
      <span class="input-group-btn">
        <button type="submit" name="search" id="search-btn" class="btn btn-flat">
          <i class="fa fa-search"></i>
        </button>
      </span>
    </div>
  </form>

  <!-- Sidebar menu -->
  <ul class="sidebar-menu" data-widget="tree">
    <li class="header">MAIN NAVIGATION</li>
    <li><a href="learnerindex.php"><i class="fa fa-circle-o"></i> Home / Dashboard</a></li>
    <li><a href="mytutors.php"><i class="fa fa-circle-o"></i> My Tutors</a></li>
    <li><a href="learningresources.php"><i class="fa fa-circle-o"></i> Learning Resources</a></li>

    <!-- Homeworks -->
    <li class="treeview">
      <a href="#">
        <i class="fa fa-circle-o"></i> <span>Homeworks</span>
        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
      </a>
      <ul class="treeview-menu">
        <?php
        if (!empty($subjects)) {
            foreach ($subjects as $sub) {
                echo '<li><a href="homework.php?subjectId=' . htmlspecialchars($sub['SubjectId']) . '">
                        <i class="fa fa-circle-o text-aqua"></i> ' . htmlspecialchars($sub['SubjectName']) . '</a></li>';
            }
        } else {
            echo '<li><a href="#"><i class="fa fa-circle-o text-red"></i> No subjects found</a></li>';
        }
        ?>
      </ul>
    </li>

    <!-- Training -->
    <li class="treeview">
      <a href="#">
        <i class="fa fa-circle-o"></i> <span>Training</span>
        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
      </a>
      <ul class="treeview-menu">
        <?php
        if (!empty($subjects)) {
            foreach ($subjects as $sub) {
                echo '<li><a href="setpicker.php?subjectId=' . htmlspecialchars($sub['SubjectId']) . '">
                        <i class="fa fa-circle-o text-aqua"></i> ' . htmlspecialchars($sub['SubjectName']) . '</a></li>';
            }
        } else {
            echo '<li><a href="#"><i class="fa fa-circle-o text-red"></i> No subjects found</a></li>';
        }
        ?>
      </ul>
    </li>

    <li><a href="perfomance.php"><i class="fa fa-circle-o"></i> My Performance</a></li>
    <li><a href="announcements.php"><i class="fa fa-circle-o"></i> Announcements / News</a></li>
    <li><a href="forums.php"><i class="fa fa-circle-o"></i> Discussion Forums</a></li>
    <li><a href="voices.php"><i class="fa fa-circle-o"></i> Student Voice</a></li>
    <li><a href="calendar.php"><i class="fa fa-circle-o"></i> Calendar / Schedule</a></li>
    <li><a href="help.php"><i class="fa fa-circle-o"></i> Help & Support</a></li>
    <li><a href="profilesettings.php"><i class="fa fa-circle-o"></i> Profile Settings</a></li>
    <li><a href="logout.php"><i class="fa fa-circle-o"></i> Log out</a></li>
  </ul>
</section>
</aside>
