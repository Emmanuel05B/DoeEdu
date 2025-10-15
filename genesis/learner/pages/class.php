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
        <h1>My Old Classes/sessions <small>View all your passed scheduled class sessions</small></h1>
        <ol class="breadcrumb">
            <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Classes</li>
        </ol>
    </section>

    <?php
    include(__DIR__ . "/../../partials/connect.php");
    $LearnerId = $_SESSION['user_id'];

    // Step 1: Get learner's class IDs
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

            // Step 2: Get class info (Grade, GroupName, SubjectId)
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


            // Step 4: Fetch meetings for this class     .. WHERE cm.ClassId = ? AND cm.Status = 'Replaced'
            $stmtSessions = $connect->prepare("
                SELECT 
                    cm.MeetingID, cm.MeetingLink, cm.Status, cm.Notes, cm.MeetingDate,
                    u.Name AS TutorName, u.Surname AS TutorSurname
                FROM classmeetings cm
                JOIN users u ON cm.TutorId = u.Id
                WHERE cm.ClassId = ?
                ORDER BY cm.MeetingDate DESC
            ");

            if (!$stmtSessions) {
                die("Prepare failed: " . $connect->error);
            }

            $stmtSessions->bind_param("i", $classID);
            $stmtSessions->execute();
            $sessions = $stmtSessions->get_result();



    ?>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">
                            <?= htmlspecialchars($subjectName) ?> - <?= $grade . " " . $group ?>
                        </h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead style="background-color: #3c8dbc; color: white;">
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Tutor</th>
                                    <th>Status</th>
                                    <th>Meeting Link</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            if ($sessions->num_rows === 0) {
                                echo "<tr><td colspan='5'>No scheduled sessions for this class.</td></tr>";
                            } else {
                                while ($session = $sessions->fetch_assoc()) {
                                    $statusLabel = "";
                                    switch ($session['Status']) {
                                        case 'Active':
                                            $statusLabel = "<span class='label label-success'>Active</span>";
                                            break;
                                        case 'Pending':
                                            $statusLabel = "<span class='label label-warning'>Pending</span>";
                                            break;
                                        default:
                                            $statusLabel = "<span class='label label-default'>" . htmlspecialchars($session['Status']) . "</span>";
                                    }

                                    echo "<tr>
                                            <td>" . date("Y-m-d H:i", strtotime($session['MeetingDate'])) . "</td>
                                            <td>{$session['TutorName']} {$session['TutorSurname']}</td>
                                            <td>{$statusLabel}</td>
                                            <td><a href='{$session['MeetingLink']}' target='_blank'>Join</a></td>
                                            <td>
                                                <button class='btn btn-info btn-xs openFeedbackModal' 
                                                    data-session='{$session['MeetingID']}' 
                                                    data-tutor='{$session['TutorName']} {$session['TutorSurname']}'>
                                                    Give Feedback
                                                </button>
                                            </td>
                                        </tr>";
                                }
                            }

                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
            $stmtSessions->close();
        } // foreach class
    } // else
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
