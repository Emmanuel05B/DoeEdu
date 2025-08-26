<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../common/partials/head.php");
include('../../partials/connect.php');

$tutorId = $_SESSION['user_id'];

// Sanitize input parameters
$subjectId = isset($_GET['subject']) ? intval($_GET['subject']) : 0;
$grade     = isset($_GET['grade']) ? intval($_GET['grade']) : 0;
$group     = isset($_GET['group']) ? $_GET['group'] : '';

// Fetch subject details
$stmt = $connect->prepare("SELECT SubjectName FROM subjects WHERE SubjectId = ?");
$stmt->bind_param("i", $subjectId);
$stmt->execute();
$subjectResult = $stmt->get_result();

if ($subjectResult->num_rows === 0) {
    die("Invalid subject selected.");
}

$subject = $subjectResult->fetch_assoc();
$subjectName = $subject['SubjectName'];

$header = "Grade {$grade} {$subjectName}, Group-{$group} Learners";

// Build query dynamically without SubjectType
$sql = "
    SELECT DISTINCT lt.LearnerId, lt.Grade, u.Name, u.Surname, c.GroupName
    FROM learners lt
    INNER JOIN learnersubject ls ON lt.LearnerId = ls.LearnerId
    INNER JOIN users u ON lt.LearnerId = u.Id
    INNER JOIN learnerclasses lc ON lt.LearnerId = lc.LearnerID
    INNER JOIN classes c ON lc.ClassID = c.ClassID
    WHERE lt.Grade = ?
      AND ls.SubjectId = ?
      AND ls.ContractExpiryDate > CURDATE()
      AND c.GroupName = ?
";
$stmt2 = $connect->prepare($sql);
$stmt2->bind_param("iis", $grade, $subjectId, $group);
$stmt2->execute();
$results = $stmt2->get_result();
?>


<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <?php include(__DIR__ . "/../partials/header.php"); ?>
    <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>Class List <small>Learners</small></h1>
            <ol class="breadcrumb">
                <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Class List</li>
            </ol>
            <h4><?php echo htmlspecialchars($header); ?></h4>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>StNo.</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Grade</th>
                                            <th>Group/Class</th>
                                            <th>Progress</th>
                                            <th>More</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($learner = $results->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo $learner['LearnerId']; ?></td>
                                                <td><?php echo htmlspecialchars($learner['Name']); ?></td>
                                                <td><?php echo htmlspecialchars($learner['Surname']); ?></td>
                                                <td><?php echo $learner['Grade']; ?></td>
                                                <td><?php echo htmlspecialchars($learner['GroupName']); ?></td>
                                                <td>
                                                    <a href="tracklearnerprogress.php?id=<?php echo $learner['LearnerId']; ?>&val=<?php echo $subjectId; ?>" class="btn btn-xs btn-primary">
                                                        Track Progress
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="learnerprofile.php?id=<?php echo $learner['LearnerId']; ?>&val=<?php echo $subjectId; ?>" class="btn btn-xs btn-primary">
                                                        Open Profile
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>StNo.</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Grade</th>
                                            <th>Group/Class</th>
                                            <th>Progress</th>
                                            <th>More</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <p><a href="classform.php?val=<?php echo $subjectId; ?>" class="btn btn-block btn-primary">Create Class Form</a></p>
                            <p><a href="expiredclasslist.php?val=<?php echo $subjectId; ?>" class="btn btn-block btn-primary">Expired contract List</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>
<script>
    $(function () {
        $('#example1').DataTable();
    });
</script>
</body>
</html>
