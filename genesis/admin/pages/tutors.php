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

// Get all tutors in the system...to come back to pull based on groups
$tutors = [];

$sql = "
    SELECT 
        t.TutorId, u.Name, u.Surname, u.Email, u.Contact, u.Gender, t.Availability, t.ProfilePicture, 
        GROUP_CONCAT(DISTINCT s.SubjectName SEPARATOR ', ') AS Subjects
    FROM tutors t
    JOIN users u ON t.TutorId = u.Id
    LEFT JOIN tutorsubject ts ON t.TutorId = ts.TutorId
    LEFT JOIN subjects s ON ts.SubjectId = s.SubjectId
    GROUP BY t.TutorId
";

$result = $connect->query($sql);

if ($result) {
    while ($tutor = $result->fetch_assoc()) {
        $tutors[] = $tutor;
    }
} else {
    die("Query failed: (" . $connect->errno . ") " . $connect->error);
}
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

    <div class="content-wrapper">
 
          <section class="content-header">

          <h1>All Tutors <small>View, update, or manage all registered tutors in the system.</small></h1>
          <ol class="breadcrumb">
            <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Classes</li>
          </ol>
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;">

                <div style="display: flex; gap: 10px; margin-top: 30px;">
                
                <a href="classes.php" 
                    class="btn btn-primary" 
                    style="height: fit-content;">
                    Open Classes
                </a>

                <a href="assigntutorclass.php" 
                    class="btn btn-primary" 
                    style="height: fit-content;">
                    Assign Tutors to Classes
                </a>
                </div>
            </div>
            </section>

        <section class="content">
            <div class="row">
                <?php if (empty($tutors)): ?>
                    <div class="col-md-12">
                        <div class="alert alert-warning text-center">No tutors found in the system.</div>
                    </div>
                <?php else: ?>
                    <?php foreach ($tutors as $tutor): ?>
                        <div class="col-md-4">
                            <div class="box box-primary" style="min-height: 380px;">
                                <div class="box-header with-border text-center">
                                    <img 
                                        src="<?= !empty($tutor['ProfilePicture']) ? '' . htmlspecialchars($tutor['ProfilePicture']) : '../uploads/doe.jpg' ?>" 
                                        alt="Tutor Picture" class="img-circle" width="90" height="90" style="object-fit: cover;">
                                    <h3 class="box-title" style="margin-top:10px;">
                                        <?= htmlspecialchars($tutor['Gender']) . ' ' . htmlspecialchars($tutor['Surname']) ?>
                                    </h3>
                                      <p style="word-wrap: break-word; white-space: normal;">
                                          <span class="label label-info" style="display: inline-block; max-width: 100%; white-space: normal;">
                                              <?= htmlspecialchars($tutor['Subjects'] ?: 'No subjects assigned') ?>
                                          </span>
                                      </p>                                
                                </div>
                                <div class="box-body text-center">
                                    <p><strong>Name:</strong> <?= htmlspecialchars($tutor['Name']) . ' ' . htmlspecialchars($tutor['Surname']) ?></p>
                                    <p><strong>Email:</strong> <?= htmlspecialchars($tutor['Email']) ?></p>
                                    <p><strong>Contact:</strong> <?= htmlspecialchars($tutor['Contact']) ?></p>
                                    <p><strong>Availability:</strong> <?= htmlspecialchars($tutor['Availability']) ?: 'Not specified' ?></p>
                                    <hr>
                                    <div class="btn-group">
                                        <a href="updatetutors.php?id=<?= $tutor['TutorId'] ?>" class="btn btn-sm btn-info">View / Update</a>
                                        <a href="overview.php?id=<?= $tutor['TutorId'] ?>" class="btn btn-sm btn-success">View Performance</a>
                                        <a href="mailto:<?= $tutor['Email'] ?>" class="btn btn-sm btn-primary">Contact</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <div class="control-sidebar-bg"></div>
</div>
<!-- Scripts copy them, responsible for my white mainsidebar-->   
<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>


</body>
</html>
