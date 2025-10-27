<!DOCTYPE html>
<html>
<?php

require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");
include_once(COMMON_PATH . "/../partials/head.php");  


// Get activity ID
if (!isset($_GET['activityId'])) {
    echo "<h3 class='text-center text-danger'>No activity selected.</h3>";
    exit();
}
$activityId = intval($_GET['activityId']);

// Get activity details
$actStmt = $connect->prepare("
    SELECT Title, Grade, Topic, Instructions, CreatedAt, TotalMarks, SubjectId
    FROM onlineactivities
    WHERE Id = ?
");
$actStmt->bind_param("i", $activityId);
$actStmt->execute();
$activity = $actStmt->get_result()->fetch_assoc();
$actStmt->close();

// Get all classes assigned to this activity
$classStmt = $connect->prepare("
    SELECT oa.ClassID, c.Grade, c.GroupName, oa.AssignedAt, oa.DueDate
    FROM onlineactivitiesassignments oa
    JOIN classes c ON oa.ClassID = c.ClassID
    WHERE oa.OnlineActivityId = ?
");
$classStmt->bind_param("i", $activityId);
$classStmt->execute();
$classResult = $classStmt->get_result();
$classes = [];
while ($c = $classResult->fetch_assoc()) {
    // Count learners assigned to this class
    $lcStmt = $connect->prepare("
        SELECT COUNT(*) AS totalLearners
        FROM learnerclasses lc
        JOIN learnersubject ls ON lc.LearnerID = ls.LearnerId
        WHERE lc.ClassID = ? AND ls.Status = 'Active' AND ls.ContractExpiryDate > CURDATE()
    ");
    $lcStmt->bind_param("i", $c['ClassID']);
    $lcStmt->execute();
    $res = $lcStmt->get_result()->fetch_assoc();
    $c['totalLearners'] = $res['totalLearners'];
    $lcStmt->close();

    // Completion stats
    $completedStmt = $connect->prepare("
        SELECT COUNT(DISTINCT la.UserId) AS completed
        FROM learneranswers la
        JOIN learnersubject ls ON la.UserId = ls.LearnerId
        JOIN learnerclasses lc ON la.UserId = lc.LearnerID
        WHERE la.ActivityId = ? AND lc.ClassID = ? AND ls.Status = 'Active' AND ls.ContractExpiryDate > CURDATE()
    ");
    $completedStmt->bind_param("ii", $activityId, $c['ClassID']);
    $completedStmt->execute();
    $compRes = $completedStmt->get_result()->fetch_assoc();
    $c['completed'] = $compRes['completed'];
    $c['notSubmitted'] = $c['totalLearners'] - $c['completed'];
    $completedStmt->close();

    $classes[] = $c;
}
$classStmt->close();
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
<?php include_once(ADMIN_PATH . "/../partials/header.php"); ?>
<?php include_once(ADMIN_PATH . "/../partials/mainsidebar.php"); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Master Activity Overview
            <small class="text-muted"><?= htmlspecialchars($activity['Title']) ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Master Activity Overview</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <?php if (!empty($classes)): ?>
                <?php foreach ($classes as $cls): 
                    $isPastDue = new DateTime() > new DateTime($cls['DueDate']);
                ?>
                    <div class="col-md-4">
                        <div class="box <?= $isPastDue ? 'box-danger' : 'box-success' ?> box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title">
                                     <?= htmlspecialchars($cls['Grade']) ?> - <?= htmlspecialchars($cls['GroupName']) ?>
                                </h3>
                            </div>
                            <div class="box-body">
                                <p><strong>Assigned Learners:</strong> <?= htmlspecialchars($cls['totalLearners']) ?></p>
                                <p><strong>Completed:</strong> <?= htmlspecialchars($cls['completed']) ?></p>
                                <p><strong>Not Submitted:</strong> <?= htmlspecialchars($cls['notSubmitted']) ?></p>
                                <p><strong>Assigned At:</strong> <?= date("d M Y", strtotime($cls['AssignedAt'])) ?></p>
                                <p><strong>Due Date:</strong> <?= date("d M Y", strtotime($cls['DueDate'])) ?>
                                    <?php if ($isPastDue) echo '<span class="label label-danger">Past Due</span>'; ?>
                                </p>
                                <a href='activityoverview.php?activityId=<?= $activityId ?>&gra=<?= htmlspecialchars($cls['Grade']) ?>&sub=<?= htmlspecialchars($activity['SubjectId']) ?>&group=<?= urlencode($cls['GroupName']) ?>' 
                                   class='btn btn-xs btn-info' title='View Class Overview'>
                                   <i class='fa fa-info-circle'></i> Class Overview
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-md-12">
                    <div class="alert alert-warning text-center">
                        This activity is not assigned to any classes.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<div class="control-sidebar-bg"></div>
</div>

<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>
</body>
</html>
