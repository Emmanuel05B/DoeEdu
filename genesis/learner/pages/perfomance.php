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

$LearnerId = $_SESSION['user_id'];

// Step 1: Get all classes for this learner
$stmtClasses = $connect->prepare("SELECT ClassID FROM learnerclasses WHERE LearnerID = ?");
$stmtClasses->bind_param("i", $LearnerId);
$stmtClasses->execute();
$classResults = $stmtClasses->get_result()->fetch_all(MYSQLI_ASSOC);
$stmtClasses->close();
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
<?php include_once(LEARNER_PATH . "/../partials/header.php"); ?>
<?php include_once(LEARNER_PATH . "/../partials/mainsidebar.php"); ?>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>My Performance <small>Online Quizzes & Manual Marks</small></h1>
            <ol class="breadcrumb">
                <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Performance</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <?php 
                if (count($classResults) === 0) {
                    echo "<div class='col-xs-12'><h3 class='text-center'>You are not assigned to any classes yet.</h3></div>";
                } else {
                    foreach ($classResults as $classRow) {
                        $classID = $classRow['ClassID'];

                        // Step 2: Get class info: Grade, GroupName, SubjectId
                        $stmtClassInfo = $connect->prepare("SELECT Grade, GroupName, SubjectId FROM classes WHERE ClassID = ? LIMIT 1");
                        $stmtClassInfo->bind_param("i", $classID);
                        $stmtClassInfo->execute();
                        $classInfo = $stmtClassInfo->get_result()->fetch_assoc();
                        $stmtClassInfo->close();

                        $grade = $classInfo['Grade'];
                        $group = $classInfo['GroupName'];
                        $subjectId = $classInfo['SubjectId'];

                        // Step 3: Get subject name
                        $stmtSubject = $connect->prepare("SELECT SubjectName FROM subjects WHERE SubjectId = ? LIMIT 1");
                        $stmtSubject->bind_param("i", $subjectId);
                        $stmtSubject->execute();
                        $subjectRow = $stmtSubject->get_result()->fetch_assoc();
                        $stmtSubject->close();

                        $subjectName = $subjectRow['SubjectName'];

                        // Step 4: Calculate Online Quizzes Avg for this subject
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

                        $onlineAvg = $totalMarks > 0 ? round(($totalScore / $totalMarks) * 100, 2) : 0;
                ?>
                
                <!-- Subject Box -->
                <div class="col-xs-12" style="margin-bottom:20px;">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title"><?= htmlspecialchars($subjectName) ?> (<?= $grade . ' ' . $group ?>)</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <!-- Left Block: Online Quizzes -->
                                <div class="col-md-6 col-sm-12">
                                    <div class="box box-solid" style="background:#f0f7ff; padding:15px; text-align:center;">
                                        <h4 style="color:#3a3a72;">Online Quizzes</h4>
                                        <h2><?= $onlineAvg ?>%</h2>
                                        <i class="fa fa-line-chart fa-2x" style="color:#0073e6;"></i>

                                        <!-- Extra Info -->
                                        <p style="margin-top:10px;">
                                            Assignments:  = 5 <br>
                                            Submitted: = 7 <br>
                                            Submission Rate: 9%
                                        </p>
                                    </div>
                                </div>

                                <!-- Right Block: Manual Marks -->
                                <div class="col-md-6 col-sm-12">
                                    <div class="box box-solid" style="background:#fff3e6; padding:15px; text-align:center;">
                                        <h4 style="color:#3a3a72;">Manual Marks</h4>
                                        <h2><?= $manualAvg ?? '-' ?>%</h2>
                                        <i class="fa fa-pencil fa-2x" style="color:#ff6600;"></i>

                                        <!-- Extra Info -->
                                        <p style="margin-top:10px;">
                                            Assignments: <?= $manualAssignments ?? 0 ?> <br>
                                            Submitted: <?= $manualSubmitted ?? 0 ?> <br>
                                            Submission Rate: <?= $manualSubmissionRate ?? '-' ?>%
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <?php 
                    } // end foreach classes
                } // end if classes exist
                ?>
            </div>
        </section>
    </div>

    <div class="control-sidebar-bg"></div>
</div>

<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>
</body>
</html>
