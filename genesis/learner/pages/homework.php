<!DOCTYPE html>
<html>

<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}
?>
<?php include(__DIR__ . "/../../common/partials/head.php"); ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
<?php include(__DIR__ . "/../partials/header.php"); ?>
<?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>My Homework/s <small>List of all assigned homework</small></h1>
        <ol class="breadcrumb">
            <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Homeworks</li>
        </ol>
    </section>

    <?php
    include(__DIR__ . "/../../partials/connect.php");
    $LearnerId = $_SESSION['user_id'];

    // Step 1: Get all classes for this learner
    $stmtClasses = $connect->prepare("SELECT ClassID FROM learnerclasses WHERE LearnerID = ?");
    $stmtClasses->bind_param("i", $LearnerId);
    $stmtClasses->execute();
    $classResults = $stmtClasses->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmtClasses->close();

    if (count($classResults) === 0) {
        echo "<h3 class='text-center'>You are not assigned to any classes yet.</h3>";
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
    ?>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"><?php echo htmlspecialchars($subjectName); ?> - Assigned Homework (<?php echo $grade . ' ' . $group; ?>)</h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-striped" style="width:100%;">
                            <thead style="background-color: #3c8dbc; color: white;">
                            <tr>
                                <th>Title</th>
                                <th>Chapter</th>
                                <th>Received On</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Score</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            // Step 4: Get assigned activities for this class
                            $stmtActivities = $connect->prepare("
                                SELECT a.Id, a.Title, a.Topic, a.CreatedAt, aa.DueDate, a.TotalMarks
                                FROM onlineactivities a
                                INNER JOIN onlineactivitiesassignments aa 
                                    ON a.Id = aa.OnlineActivityId
                                WHERE aa.ClassID = ?
                                ORDER BY aa.AssignedAt DESC
                            ");

                            if (!$stmtActivities) {
                                    die("Prepare failed: " . $connect->error);
                                }

                            $stmtActivities->bind_param("i", $classID);
                            $stmtActivities->execute();
                            $activities = $stmtActivities->get_result();

                            if ($activities->num_rows === 0) {
                                echo "<tr><td colspan='7'>No assigned homework available for this class.</td></tr>";
                            } else {
                                while ($activity = $activities->fetch_assoc()) {
                                    $activityId = $activity['Id'];

                                    // Check if learner submitted answers
                                    $answerStmt = $connect->prepare("
                                        SELECT oq.CorrectAnswer, la.SelectedAnswer
                                        FROM learneranswers la
                                        JOIN onlinequestions oq ON la.QuestionId = oq.Id
                                        WHERE la.UserId = ? AND la.ActivityId = ?
                                    ");
                                    $answerStmt->bind_param("ii", $LearnerId, $activityId);
                                    $answerStmt->execute();
                                    $answersResult = $answerStmt->get_result();

                                    $correct = 0;
                                    $totalAnswered = 0;

                                    while ($ans = $answersResult->fetch_assoc()) {
                                        $totalAnswered++;
                                        if ($ans['SelectedAnswer'] === $ans['CorrectAnswer']) {
                                            $correct++;
                                        }
                                    }
                                    $answerStmt->close();

                                    if ($totalAnswered > 0) {
                                        $status = "<span class='label label-success'>Completed</span>";
                                        $score = "{$correct}/{$activity['TotalMarks']}";
                                        $memoBtn = "<a href='viewmemo.php?activityid={$activityId}' class='btn btn-info btn-sm'>View Memo</a>";
                                    } else {
                                        $status = "<span class='label label-warning'>Not Started</span>";
                                        $score = "-";
                                        $memoBtn = "";
                                    }

                                    // Updated Open button with subject parameter
                                    echo "<tr>
                                            <td>{$activity['Title']}</td>
                                            <td>{$activity['Topic']}</td>
                                            <td>{$activity['CreatedAt']}</td>
                                            <td>{$activity['DueDate']}</td>
                                            <td>{$status}</td>
                                            <td>{$score}</td>
                                            <td>
                                                <a href='viewhomework.php?activityId={$activityId}&subject=" . urlencode($subjectName) . "' class='btn btn-primary btn-xs'>Open</a>
                                                {$memoBtn}
                                            </td>
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
        } // end foreach learner's classes
    }
    ?>

</div>
<div class="control-sidebar-bg"></div>
</div>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

<script>
$(function () {
    $('table').DataTable({
        responsive: true,
        autoWidth: false
    });
});
</script>

</body>
</html>
