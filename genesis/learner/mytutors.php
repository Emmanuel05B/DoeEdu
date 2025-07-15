<!DOCTYPE html>
<html>
<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../common/login.php");
    exit();
}

include('../partials/connect.php');
include("learnerpartials/head.php");

// Get learner ID from session
$learnerId = $_SESSION['user_id'];

// Prepare tutor data array
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
        // Step 2: Get tutors linked to those subject IDs
        $cleanedIds = array_map('intval', $subjectIds); // Ensure all IDs are integers
        $inClause = implode(',', $cleanedIds);

        $sql = "
            SELECT 
                t.TutorId, u.Name, u.Surname, u.Email, u.Contact, u.Gender, t.Availability, t.ProfilePicture, 
                GROUP_CONCAT(s.SubjectName SEPARATOR ', ') AS Subjects
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
    <?php include("learnerpartials/header.php"); ?>
    <?php include("learnerpartials/mainsidebar.php"); ?>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>My Tutors</h1>
            <p>Meet the tutors assigned to assist you in your registered subjects.</p>
        </section>

        <section class="content">
            <div class="row">
                <?php if (empty($tutors)): ?>
                    <div class="col-md-12">
                        <div class="alert alert-warning">No tutors found for your current subjects.</div>
                    </div>
                <?php else: ?>
                    <?php foreach ($tutors as $tutor): ?>
                        <div class="col-md-4">
                            <div class="box box-primary">
                                <div class="box-header with-border text-center">
                                    <img src="<?= $tutor['ProfilePicture'] ? '../' . htmlspecialchars($tutor['ProfilePicture']) : '../assets/defaults/user.png' ?>" 
                                         alt="Tutor Picture" class="img-circle" width="80" height="80">
                                    <h3 class="box-title" style="margin-top:10px;">
                                        <?= htmlspecialchars($tutor['Gender']) . ' ' . htmlspecialchars($tutor['Surname']) ?>
                                    </h3>
                                    <p>Subjects: <?= htmlspecialchars($tutor['Subjects']) ?></p>
                                </div>
                                <div class="box-body">
                                    <p><strong>Email:</strong> <?= htmlspecialchars($tutor['Email']) ?></p>
                                    <p><strong>Availability:</strong> <?= htmlspecialchars($tutor['Availability']) ?: 'Not specified' ?></p>
                                    <hr>
                                    <a href="feedback.php?tutor=<?= $tutor['TutorId'] ?>" class="btn btn-sm btn-info">Give Feedback</a>
                                    <a href="rate.php?tutor=<?= $tutor['TutorId'] ?>" class="btn btn-sm btn-warning">Rate Tutor</a>
                                    <a href="booking.php?tutor=<?= $tutor['TutorId'] ?>" class="btn btn-sm btn-primary">Book Session</a>
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
</body>
</html>
