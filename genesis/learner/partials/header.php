<?php
  if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
  }

  include(__DIR__ . "/../../common/partials/head.php");
  include(__DIR__ . "/../../partials/connect.php");

  $learnerId = $_SESSION['user_id'];

  // Fetch learner full name (fallback to session var or 'Learner')
  $stmt = $connect->prepare("SELECT CONCAT(Name, ' ', Surname) AS fullname FROM users WHERE Id = ?");
  $stmt->bind_param("i", $learnerId);
  $stmt->execute();
  $stmt->bind_result($learnerName);
  $stmt->fetch();
  $stmt->close();

  if (!$learnerName) {
      $learnerName = $_SESSION['full_name'] ?? 'Learner';
  }

// Count Pending Homework (not submitted yet, due date in the future, for active classes and groups)
// Step 1: Get all current classes for this learner
$stmtClasses = $connect->prepare("SELECT ClassID FROM learnerclasses WHERE LearnerID = ?");
$stmtClasses->bind_param("i", $learnerId);
$stmtClasses->execute();
$classResults = $stmtClasses->get_result()->fetch_all(MYSQLI_ASSOC);
$stmtClasses->close();

$pendingHomeworkCount = 0;

if (count($classResults) > 0) {
    foreach ($classResults as $classRow) {
        $classID = $classRow['ClassID'];

        // Step 2: Get homework assigned to this class that is still due
        $stmtActivities = $connect->prepare("
            SELECT a.Id
            FROM onlineactivities a
            INNER JOIN onlineactivitiesassignments aa 
                ON a.Id = aa.OnlineActivityId
            WHERE aa.ClassID = ?
              AND aa.DueDate >= CURDATE()
        ");
        $stmtActivities->bind_param("i", $classID);
        $stmtActivities->execute();
        $activities = $stmtActivities->get_result();

        while ($activity = $activities->fetch_assoc()) {
            $activityId = $activity['Id'];

            // Step 3: Check if learner already submitted any answers for this activity
            $stmtCheck = $connect->prepare("
                SELECT Id FROM learneranswers 
                WHERE ActivityId = ? AND UserId = ? 
                LIMIT 1
            ");
            $stmtCheck->bind_param("ii", $activityId, $learnerId);
            $stmtCheck->execute();
            $submitted = $stmtCheck->get_result()->num_rows > 0;
            $stmtCheck->close();

            // Step 4: Count only if no submission yet
            if (!$submitted) {
                $pendingHomeworkCount++;
            }
        }

        $stmtActivities->close();
    }
}

  // Count the number of confirmed 1-on-1 sessions that haven't passed yet
  $twoWeeksAgo = (new DateTime())->modify('-14 days')->format('Y-m-d H:i:s');

  $stmt3 = $connect->prepare("
      SELECT COUNT(*) AS ConfirmedCount
      FROM tutorsessions ts
      JOIN users u ON ts.TutorId = u.Id
      JOIN tutors t ON ts.TutorId = t.TutorId
      WHERE ts.LearnerId = ? 
        AND ts.SlotDateTime >= ? 
        AND ts.Hidden = 0
        AND ts.Status = 'Confirmed'
  ");

  $stmt3->bind_param("is", $learnerId, $twoWeeksAgo);
  $stmt3->execute();
  $result3 = $stmt3->get_result();
  $row3 = $result3->fetch_assoc();
  $confirmedCount = $row3['ConfirmedCount'];

  
  // Count general announcements
  $generalAnnouncementCount = 0;
  $countSql = "
      SELECT COUNT(*) as total
      FROM notifications
      WHERE CreatedFor IN (1, 12)
        AND (ExpiryDate IS NULL OR ExpiryDate >= NOW())
  ";
  $resultCount = $connect->query($countSql);
  if($rowCount = $resultCount->fetch_assoc()){
      $generalAnnouncementCount = $rowCount['total'];
  }


  
?>

  <header class="main-header">
    <!-- Logo -->
    <a href="learnerindex.php" class="logo">
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
        
        <span class="logo-lg"><b>Distributors Of Education </b></span>

      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->

          <li>
            <a href="announcements.php"> 
                <i class="fa fa-envelope"></i> 
                <span class="label label-warning"><?= $generalAnnouncementCount ?></span>
            </a>
          </li>
          <!-- pending homeworks -->
          <li>
            <a href="homework.php">
              <i class="fa fa-tasks"></i>
              <span class="label label-warning"><?= $pendingHomeworkCount ?></span>
            </a>
          </li>
          <!-- confimed requests -->
          <li>
            <a href="mytutors.php">
              <i class="fa fa-check-circle text-white"></i>
              <span class="label label-success"><?= $confirmedCount ?></span>
            </a>
          </li>

          

          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="../images/emma.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $learnerName ?></span>
            </a>

          </li>
          
        </ul>
      </div>
    </nav>
  </header>
