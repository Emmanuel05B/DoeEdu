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
?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

<?php include_once(LEARNER_PATH . "/../partials/header.php"); ?>
<?php include_once(LEARNER_PATH . "/../partials/mainsidebar.php"); ?>

<div class="content-wrapper">
<section class="content-header">
    <h1>My Homework/s <small>List of assigned homework</small></h1>
    <ol class="breadcrumb">
        <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Homeworks</li>
    </ol>
</section>

<?php
$LearnerId = $_SESSION['user_id'];

/* PROVIDED SUBJECT */
$SubjectId = isset($_GET['val']) ? intval($_GET['val']) : 0;

if ($SubjectId === 0) {
    echo "<h3 class='text-center'>No subject selected.</h3>";
    exit();
}

/* Step 1: Get learner classes ONLY for this subject */
$stmtClasses = $connect->prepare("
    SELECT lc.ClassID
    FROM learnerclasses lc
    INNER JOIN classes c ON c.ClassID = lc.ClassID
    WHERE lc.LearnerID = ?
      AND c.SubjectId = ?
");
$stmtClasses->bind_param("ii", $LearnerId, $SubjectId);
$stmtClasses->execute();
$classResults = $stmtClasses->get_result()->fetch_all(MYSQLI_ASSOC);
$stmtClasses->close();

if (count($classResults) === 0) {
    echo "<h3 class='text-center'>No classes found for this subject.</h3>";
} else {

    foreach ($classResults as $classRow) {
        $classID = $classRow['ClassID'];

        /* Class info */
        $stmtClassInfo = $connect->prepare("
            SELECT Grade, GroupName
            FROM classes
            WHERE ClassID = ?
            LIMIT 1
        ");
        $stmtClassInfo->bind_param("i", $classID);
        $stmtClassInfo->execute();
        $classInfo = $stmtClassInfo->get_result()->fetch_assoc();
        $stmtClassInfo->close();

        $grade = $classInfo['Grade'];
        $group = $classInfo['GroupName'];

        /* Subject name */
        $stmtSubject = $connect->prepare("
            SELECT SubjectName
            FROM subjects
            WHERE SubjectId = ?
            LIMIT 1
        ");
        $stmtSubject->bind_param("i", $SubjectId);
        $stmtSubject->execute();
        $subjectRow = $stmtSubject->get_result()->fetch_assoc();
        $stmtSubject->close();

        $subjectName = $subjectRow['SubjectName'];
?>

<section class="content">
<div class="row">
<div class="col-xs-12">
<div class="box">
    <div class="box-header">
        <h3 class="box-title">
            <?php echo htmlspecialchars($subjectName); ?>
            - Homeworks (<?php echo $grade . ' ' . $group; ?>)
        </h3>
    </div>

    <div class="box-body table-responsive">
        <table class="table table-bordered table-striped">
            <thead style="background-color:#d1d9ff;">
            <tr>
                <th>Title</th>
                <th>Chapter</th>
                <th>Status</th>
                <th>Score</th>
            </tr>
            </thead>
            <tbody>
            <?php
            /* Step 2: Homework query – SUBJECT SAFE */
            $stmtActivities = $connect->prepare("
                SELECT 
                    a.Id,
                    a.Title,
                    a.Topic,
                    aa.DueDate,
                    a.TotalMarks
                FROM onlineactivities a
                INNER JOIN onlineactivitiesassignments aa
                    ON a.Id = aa.OnlineActivityId
                INNER JOIN learnersubject ls
                    ON ls.LearnerId = ?
                   AND ls.SubjectId = ?
                WHERE aa.ClassID = ?
                  AND aa.DueDate > ls.ContractStartDate
                ORDER BY aa.AssignedAt DESC
            ");

            $stmtActivities->bind_param(
                "iii",
                $LearnerId,
                $SubjectId,
                $classID
            );
            $stmtActivities->execute();
            $activities = $stmtActivities->get_result();

            if ($activities->num_rows === 0) {
                echo "<tr><td colspan='4'>No homework available.</td></tr>";
            } else {

                while ($activity = $activities->fetch_assoc()) {
                    $activityId = $activity['Id'];

                    /* Check submission */
                    $answerStmt = $connect->prepare("
                        SELECT oq.CorrectAnswer, la.SelectedAnswer
                        FROM learneranswers la
                        JOIN onlinequestions oq ON la.QuestionId = oq.Id
                        WHERE la.UserId = ? AND la.ActivityId = ?
                    ");
                    $answerStmt->bind_param("ii", $LearnerId, $activityId);
                    $answerStmt->execute();
                    $answers = $answerStmt->get_result();

                    $correct = 0;
                    $answered = 0;

                    while ($a = $answers->fetch_assoc()) {
                        $answered++;
                        if ($a['SelectedAnswer'] === $a['CorrectAnswer']) {
                            $correct++;
                        }
                    }
                    $answerStmt->close();

                    if ($answered > 0) {
                        $status = "<span class='label label-success'>Completed</span>";
                        $score  = $correct . "/" . $activity['TotalMarks'];
                    } else {
                        $status = "<span class='label label-warning'>Not Started</span>";
                        $score  = "-";
                    }

                    echo "<tr>
                            <td>{$activity['Title']}</td>
                            <td>{$activity['Topic']}</td>
                            <td>{$status}</td>
                            <td>{$score}</td>
                          </tr>";
                }
            }

            $stmtActivities->close();
            ?>
            </tbody>
        </table>
    </div>
</div>
</div>
</div>
</section>

<?php
    } // foreach class
}
?>
</div>

<div class="control-sidebar-bg"></div>
</div>

<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>
</body>
</html>
