<aside class="main-sidebar">
<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");

include_once(BASE_PATH . "/partials/connect.php");

$userId = $_SESSION['user_id'];  // Logged-in learner

// Fetch learner info
$stmt = $connect->prepare("SELECT Surname, Gender FROM users WHERE Id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// --- Fetch subjects based on active learner classes ---
$subjectSql = "
    SELECT DISTINCT s.SubjectId, s.SubjectName
    FROM learnerclasses lc
    JOIN classes c ON lc.ClassID = c.ClassID
    JOIN subjects s ON c.SubjectID = s.SubjectId
    WHERE lc.LearnerId = ?
    ORDER BY s.SubjectName ASC
";
$subStmt = $connect->prepare($subjectSql);
$subStmt->bind_param("i", $learnerId);
$subStmt->execute();
$subjects = $subStmt->get_result()->fetch_all(MYSQLI_ASSOC);
$subStmt->close();
?>

<section class="sidebar">
  <!-- User Panel -->
  <div class="user-panel">
    <div class="pull-left image">
      <img src="<?= PROFILE_PICS_URL . '/doe.jpg' ?>" class="img-circle" alt="User Image">

    </div>
    <div class="pull-left info">
      <p><?php echo htmlspecialchars($user['Gender']) . ' ' . htmlspecialchars($user['Surname']); ?></p>
      <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
    </div>
  </div>

  <!-- Search Form -->
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

  <!-- Sidebar Menu -->
  <ul class="sidebar-menu" data-widget="tree">
    <li class="header">MAIN NAVIGATION</li>

    <li><a href="learnerindex.php"><i class="fa fa-home"></i> <span>Home / Dashboard</span></a></li>
    <li><a href="mytutors.php"><i class="fa fa-calendar-check-o"></i> <span>Sessions</span></a></li>
    <li><a href="learningresources.php"><i class="fa fa-book"></i> <span>Learning Resources</span></a></li>
    <li><a href="homework.php"><i class="fa fa-pencil-square-o"></i> <span>Homeworks</span></a></li>

    <!-- Training -->
    <li class="treeview">
      <a href="#">
        <i class="fa fa-graduation-cap"></i> <span>Training</span>
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

    <li><a href="announcements.php"><i class="fa fa-bullhorn"></i> <span>Announcements / News</span></a></li>
    <li><a href="studentvoices.php"><i class="fa fa-comments-o"></i> <span>Student Voice</span></a></li>
    <li><a href="logout.php"><i class="fa fa-sign-out"></i> <span>Log Out</span></a></li>
  </ul>
</section>
</aside>
