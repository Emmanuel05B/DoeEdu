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

// Get learner ID from session
$learnerId = $_SESSION['user_id'];
$tutors = [];

if ($learnerId) {
    // Step 1: Get subject IDs linked to learner
    $subjectQuery = $connect->prepare("SELECT SubjectId FROM learnersubject WHERE LearnerId = ?");
    $subjectQuery->bind_param("i", $learnerId);
    $subjectQuery->execute();
    $subjectResult = $subjectQuery->get_result();

    $subjectIds = [];
    while ($row = $subjectResult->fetch_assoc()) {
        $subjectIds[] = $row['SubjectId'];
    }
    $subjectQuery->close();

    if (!empty($subjectIds)) {
        $cleanedIds = array_map('intval', $subjectIds);
        $inClause = implode(',', $cleanedIds);

        $sql = "
            SELECT 
                t.TutorId, u.Name, u.Surname, u.Email, u.Contact, u.Gender, t.Availability, t.ProfilePicture, 
                GROUP_CONCAT(DISTINCT s.SubjectName SEPARATOR ', ') AS Subjects
            FROM tutorsubject ts
            JOIN tutors t ON ts.TutorId = t.TutorId
            JOIN users u ON t.TutorId = u.Id
            JOIN subjects s ON ts.SubjectId = s.SubjectId
            WHERE ts.SubjectId IN ($inClause)
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
    }
}
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <?php include(__DIR__ . "/../partials/header.php"); ?>
    <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

    <div class="content-wrapper">
      <section class="content-header">
        <h1>My Tutors <small>Meet the tutors assigned to assist you in your registered subjects.</small></h1>
        <ol class="breadcrumb">
          <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">MyTutors</li>
        </ol>
      </section>


        <section class="content">
            <div class="row">
                <?php if (empty($tutors)): ?>
                    <div class="col-md-12">
                        <div class="alert alert-warning text-center">No tutors found for your current subjects.</div>
                    </div>
                <?php else: ?>
                    <?php foreach ($tutors as $tutor): ?>
                        <div class="col-md-4">
                            <div class="box box-primary" style="min-height: 330px;">
                                <div class="box-header with-border text-center">
                                    <img 
                                        src="<?= !empty($tutor['ProfilePicture']) ? '../' . htmlspecialchars($tutor['ProfilePicture']) : '../../uploads/doe.jpg' ?>" 
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
                                    <p><strong>Email:</strong> <?= htmlspecialchars($tutor['Email']) ?></p>
                                    <p><strong>Availability:</strong> <?= htmlspecialchars($tutor['Availability']) ?: 'Not specified' ?></p>
                                    <hr>
                                    <div class="btn-group">
                                        <a href="feedback.php?tutor=<?= $tutor['TutorId'] ?>" class="btn btn-sm btn-info">Give Feedback</a>
                                        <a href="rate.php?tutor=<?= $tutor['TutorId'] ?>" class="btn btn-sm btn-warning">Rate Tutor</a>
                                        <a href="booking.php?tutor=<?= $tutor['TutorId'] ?>" class="btn btn-sm btn-primary">Book Session</a>
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
