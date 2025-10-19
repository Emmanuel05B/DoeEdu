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
        <h1>My Past Meetings <small>Give feedback on your previous sessions</small></h1>
        <ol class="breadcrumb">
            <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Past Meetings</li>
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

        // Get class info (Grade, GroupName, SubjectId, TutorId)
        $stmtClassInfo = $connect->prepare("SELECT Grade, GroupName, SubjectId, TutorId FROM classes WHERE ClassID = ? LIMIT 1");
        $stmtClassInfo->bind_param("i", $classID);
        $stmtClassInfo->execute();
        $classInfo = $stmtClassInfo->get_result()->fetch_assoc();
        $stmtClassInfo->close();

        $grade = $classInfo['Grade'];
        $group = $classInfo['GroupName'];
        $subjectId = $classInfo['SubjectId'];
        $tutorId = $classInfo['TutorId'];

        // Get subject name
        $stmtSubject = $connect->prepare("SELECT SubjectName FROM subjects WHERE SubjectId = ? LIMIT 1");
        $stmtSubject->bind_param("i", $subjectId);
        $stmtSubject->execute();
        $subjectRow = $stmtSubject->get_result()->fetch_assoc();
        $stmtSubject->close();
        $subjectName = $subjectRow ? $subjectRow['SubjectName'] : '';

        // Fetch past meetings that haven't received feedback yet
        $stmtMeetings = $connect->prepare("
            SELECT 
                cm.MeetingId, cm.MeetingDate,
                u.Name AS TutorName, u.Surname AS TutorSurname
            FROM classmeetings cm
            JOIN users u ON cm.TutorId = u.Id
            WHERE cm.ClassId = ?
              AND cm.MeetingDate < NOW()
              AND cm.MeetingId NOT IN (
                  SELECT MeetingId FROM meetingfeedback WHERE LearnerId = ?
              )
            ORDER BY cm.MeetingDate DESC
        ");
        $stmtMeetings->bind_param("ii", $classID, $LearnerId);
        $stmtMeetings->execute();
        $meetings = $stmtMeetings->get_result();
?>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"><?= htmlspecialchars($subjectName) ?> - <?= htmlspecialchars($grade . " " . $group) ?></h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead style="background-color: #d1d9ff;">
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Subject</th>
                                    <th>Grade</th>
                                    <th>Tutor</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            if ($meetings->num_rows === 0) {
                                echo "<tr><td colspan='5'>No past sessions awaiting feedback for this class.</td></tr>";
                            } else {
                                while ($row = $meetings->fetch_assoc()) {
                                    $meetingId = (int)$row['MeetingId'];
                                    $meetingDate = date('Y-m-d H:i', strtotime($row['MeetingDate']));
                                    $tutorName = htmlspecialchars($row['TutorName'] . ' ' . $row['TutorSurname']);

                                    echo "<tr>
                                            <td>{$meetingDate}</td>
                                            <td>{$subjectName}</td>
                                            <td>{$grade} {$group}</td>
                                            <td>{$tutorName}</td>
                                            <td>
                                                <button class='btn btn-primary btn-xs openFeedbackModal'
                                                    data-meetingid='{$meetingId}'
                                                    data-classid='".htmlspecialchars($classID)."'
                                                    data-tutorid='".htmlspecialchars($tutorId)."'
                                                    data-subjectid='".htmlspecialchars($subjectId)."'
                                                    data-tutor='{$tutorName}'>
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
        $stmtMeetings->close();
    } // foreach class
} // else
?>
</div>

<div class="control-sidebar-bg"></div>
</div>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

<!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog" aria-labelledby="feedbackModalLabel">
  <div class="modal-dialog" role="document">
    <form id="feedbackForm" method="POST" action="submit_class_feedback.php">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
          <h4 class="modal-title">Feedback for <span id="feedbackTutorName"></span></h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="MeetingId" id="feedbackMeetingId">
          <input type="hidden" name="ClassId" id="feedbackClassId">
          <input type="hidden" name="TutorId" id="feedbackTutorId">
          <input type="hidden" name="SubjectId" id="feedbackSubjectId">

          <div class="form-group">
            <label>1. How clear were the tutor’s explanations?</label><br>
            <?php for($i=1;$i<=5;$i++): ?>
              <label class="radio-inline">
                <input type="radio" name="ClarityRating" value="<?= $i ?>" required> <?= $i ?>
              </label>
            <?php endfor; ?>
          </div>

          <div class="form-group">
            <label>2. How engaging was the tutor?</label><br>
            <?php for($i=1;$i<=5;$i++): ?>
              <label class="radio-inline">
                <input type="radio" name="EngagementRating" value="<?= $i ?>" required> <?= $i ?>
              </label>
            <?php endfor; ?>
          </div>

          <div class="form-group">
            <label>3. Overall satisfaction (1–10)</label><br>
            <?php for($i=1;$i<=10;$i++): ?>
              <label class="radio-inline">
                <input type="radio" name="OverallSatisfaction" value="<?= $i ?>" required> <?= $i ?>
              </label>
            <?php endfor; ?>
          </div>

          <div class="form-group">
            <label>Additional Comments</label>
            <textarea name="Comments" class="form-control" rows="3" placeholder="Your feedback (optional)"></textarea>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Submit Feedback</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
$(function () {


    // Open modal and populate fields
    $(document).on('click', '.openFeedbackModal', function() {
        const meetingId = $(this).data('meetingid');
        const classId = $(this).data('classid');
        const tutorId = $(this).data('tutorid');
        const subjectId = $(this).data('subjectid');
        const tutorName = $(this).data('tutor');

        $('#feedbackMeetingId').val(meetingId);
        $('#feedbackClassId').val(classId);
        $('#feedbackTutorId').val(tutorId);
        $('#feedbackSubjectId').val(subjectId);
        $('#feedbackTutorName').text(tutorName);

        $('#feedbackForm')[0].reset();
        $('#feedbackModal').modal('show');
    });
});

// Display SweetAlert feedback if session set
<?php if (isset($_SESSION['alert_type']) && isset($_SESSION['alert_message'])): ?>
Swal.fire({
    icon: '<?= $_SESSION['alert_type'] ?>',
    title: '<?= ($_SESSION['alert_type'] === "success") ? "Success" : "Notice" ?>',
    text: '<?= $_SESSION['alert_message'] ?>',
    confirmButtonColor: '#3085d6',
    confirmButtonText: 'OK'
});
<?php 
unset($_SESSION['alert_type']);
unset($_SESSION['alert_message']);
endif; ?>
</script>

</body>
</html>
