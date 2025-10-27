<?php
require_once __DIR__ . '/../../common/config.php'; 
?> 

<?php 
include('../../partials/connect.php');

$userId = $_SESSION['user_id'];
// pending tutor sessions (invite requests)
$tutorId = $_SESSION['user_id']; // get TutorId from session

// Count pending tutor sessions
$countSQL = "SELECT COUNT(*) AS count FROM tutorsessions WHERE TutorId = ? AND Status = 'Pending'";
$countQuery = $connect->prepare($countSQL);
$countQuery->bind_param("i", $tutorId);
$countQuery->execute();
$countResult = $countQuery->get_result();
$inviteRequests = $countResult->fetch_assoc()['count'] ?? 0;

// Count upcoming confirmed (accepted) sessions
$countAcceptedSQL = "
    SELECT COUNT(*) AS count
    FROM tutorsessions
    WHERE TutorId = ? 
      AND Status = 'Confirmed' 
      AND SlotDateTime >= NOW()
";

$countAcceptedQuery = $connect->prepare($countAcceptedSQL);
$countAcceptedQuery->bind_param("i", $tutorId);
$countAcceptedQuery->execute();
$countAcceptedResult = $countAcceptedQuery->get_result();
$upcomingAcceptedCount = $countAcceptedResult->fetch_assoc()['count'] ?? 0;

// Fetch tutor details (for profile image and other tutor info)
$tutorSql = "SELECT ProfilePicture FROM tutors WHERE TutorId = ?";
$tutorStmt = $connect->prepare($tutorSql);
$tutorStmt->bind_param("i", $tutorId);
$tutorStmt->execute();
$tutorResult = $tutorStmt->get_result();
$tutorData = $tutorResult->fetch_assoc();

// Handle image fallback
$profileImage = !empty($tutorData['ProfilePicture'])
    ? htmlspecialchars($tutorData['ProfilePicture'])
    : "../../uploads/doe.jpg";

?>

<header class="main-header">
    <!-- Logo -->
    <a href="tutorindex.php" class="logo">
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

                <!-- Invite requests -->
                <li>
                    <a href="schedule.php">
                        <i class="fa fa-envelope-open"></i>
                        <span class="label label-warning"><?= $inviteRequests ?></span>
                    </a>
                </li>

                <li>
                    <a href="schedule.php">
                        <i class="fa fa-check-circle text-white"></i>
                        <span class="label label-success"><?= $upcomingAcceptedCount ?></span>
                    </a>
                </li>

                <!-- User account -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?php echo $profileImage; ?>" class="img-circle" alt="User Image" style="width:18px; height:18px; object-fit:cover;">
                    </a>
                </li>

            </ul>
        </div>
    </nav>
</header>
