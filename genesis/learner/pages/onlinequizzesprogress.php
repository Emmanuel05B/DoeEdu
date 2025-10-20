<!DOCTYPE html>
<html>
<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../common/partials/head.php");
include(__DIR__ . "/../../partials/connect.php");

$LearnerId = $_SESSION['user_id'];

// Fetch learner's subjects   ..not correct.
$sqlSubjects = "
    SELECT DISTINCT s.SubjectId, s.SubjectName
    FROM subjects s
    JOIN classes c ON s.SubjectId = c.SubjectId
    JOIN learnerclasses lc ON c.ClassID = lc.ClassID
    WHERE lc.LearnerID = ?
";
$stmtSubjects = $connect->prepare($sqlSubjects);
$stmtSubjects->bind_param("i", $LearnerId);
$stmtSubjects->execute();
$subjects = $stmtSubjects->get_result()->fetch_all(MYSQLI_ASSOC);
$stmtSubjects->close();
?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <?php include(__DIR__ . "/../partials/header.php"); ?>
    <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>Online Quiz Progress <small>Track your performance</small></h1>
            <ol class="breadcrumb">
                <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Online Quiz Progress</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <?php foreach ($subjects as $subject):
                    $subjectId = $subject['SubjectId'];

                    // Get all online activities for this subject and GroupName also should count
                    $sqlActivities = "
                        SELECT a.Id, a.TotalMarks
                        FROM onlineactivities a
                        JOIN onlineactivitiesassignments aa ON a.Id = aa.OnlineActivityId
                        JOIN classes c ON aa.ClassID = c.ClassID
                        WHERE c.SubjectId = ?
                    ";
                    $stmtActivities = $connect->prepare($sqlActivities);
                    $stmtActivities->bind_param("i", $subjectId);
                    $stmtActivities->execute();
                    $activitiesResult = $stmtActivities->get_result();

                    $totalScore = 0;
                    $totalMarks = 0;

                    while ($activity = $activitiesResult->fetch_assoc()) {
                        $activityId = $activity['Id'];
                        $maxMarks = $activity['TotalMarks'];

                        // Get learner answers
                        $sqlAnswers = "
                            SELECT COUNT(*) AS correctCount
                            FROM learneranswers la
                            JOIN onlinequestions oq ON la.QuestionId = oq.Id
                            WHERE la.UserId = ? AND la.ActivityId = ? AND la.SelectedAnswer = oq.CorrectAnswer
                        ";
                        $stmtAnswers = $connect->prepare($sqlAnswers);
                        $stmtAnswers->bind_param("ii", $LearnerId, $activityId);
                        $stmtAnswers->execute();
                        $correctCount = $stmtAnswers->get_result()->fetch_assoc()['correctCount'];
                        $stmtAnswers->close();

                        $totalScore += $correctCount;
                        $totalMarks += $maxMarks;
                    }

                    $stmtActivities->close();

                    $averagePercent = $totalMarks > 0 ? round(($totalScore / $totalMarks) * 100, 2) : 0;
                ?>

                <div class="col-md-4">
                    <div class="box box-primary" style="background:#f0f7ff;">
                        <div class="box-body text-center">
                            <h4 style="color:#3a3a72;"><?= htmlspecialchars($subject['SubjectName']) ?></h4>
                            <h2><?= $averagePercent ?>%</h2>
                            <i class="fa fa-line-chart fa-2x pull-right" style="color:#0073e6;"></i>
                            <a href="onlinehomework.php?subjectId=<?= $subjectId ?>" class="btn btn-link">View Activities</a>
                        </div>
                    </div>
                </div>

                <?php endforeach; ?>
            </div>
        </section>
    </div>
</div>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>
</body>
</html>
