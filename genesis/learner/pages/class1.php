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

    // Fetch past meetings that the learner hasn't given feedback for
    $stmt = $connect->prepare("
        SELECT 
            cm.MeetingId, cm.TutorId, cm.SubjectId, cm.Grade, cm.GroupName,
            cm.MeetingLink, cm.MeetingDate, cm.Status,
            u.Name AS TutorName, u.Surname AS TutorSurname,
            s.SubjectName
        FROM classmeetings cm
        JOIN users u ON cm.TutorId = u.Id
        JOIN subjects s ON cm.SubjectId = s.SubjectId
        WHERE cm.Status = 'Active'
          AND cm.MeetingDate < NOW()
          AND cm.MeetingId NOT IN (
              SELECT MeetingId FROM meetingfeedback WHERE LearnerId = ?
          )
        ORDER BY cm.MeetingDate DESC
    ");
    $stmt->bind_param("i", $LearnerId);
    $stmt->execute();
    $meetings = $stmt->get_result();
    $stmt->close();
    ?>
    

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Past Meetings Awaiting Feedback</h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead style="background-color: #3c8dbc; color: white;">
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Subject</th>
                                    <th>Grade / Group</th>
                                    <th>Tutor</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            if ($meetings->num_rows === 0) {
                                echo "<tr><td colspan='5' class='text-center'>No pending feedback meetings found.</td></tr>";
                            } else {
                                while ($row = $meetings->fetch_assoc()) {
                                    $dt = new DateTime($row['MeetingDate']);
                                    echo "<tr>
                                            <td>{$dt->format('Y-m-d H:i')}</td>
                                            <td>".htmlspecialchars($row['SubjectName'])."</td>
                                            <td>".htmlspecialchars($row['Grade'].' '.$row['GroupName'])."</td>
                                            <td>".htmlspecialchars($row['TutorName'].' '.$row['TutorSurname'])."</td>
                                            <td>
                                                <button class='btn btn-primary btn-xs openFeedbackModal'
                                                    data-meetingid='{$row['MeetingId']}'
                                                    data-tutorid='{$row['TutorId']}'
                                                    data-subjectid='{$row['SubjectId']}'
                                                    data-tutor='".htmlspecialchars($row['TutorName'].' '.$row['TutorSurname'])."'>
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
</div>

<div class="control-sidebar-bg"></div>
</div>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

<!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog" aria-labelledby="feedbackModalLabel">
  <div class="modal-dialog" role="document">
    <form id="feedbackForm" method="POST" action="submit_class_feedback.php">
      <div class="modal-content">
        <div class="modal-header bg-blue">
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
          <h4 class="modal-title">Feedback for <span id="feedbackTutorName"></span></h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="MeetingId" id="feedbackMeetingId">
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
    $('table').DataTable({
        responsive: true,
        autoWidth: false
    });

    // Open modal and populate fields
    $(document).on('click', '.openFeedbackModal', function() {
        const meetingId = $(this).data('meetingid');
        const tutorId = $(this).data('tutorid');
        const subjectId = $(this).data('subjectid');
        const tutorName = $(this).data('tutor');

        $('#feedbackMeetingId').val(meetingId);
        $('#feedbackTutorId').val(tutorId);
        $('#feedbackSubjectId').val(subjectId);
        $('#feedbackTutorName').text(tutorName);

        $('#feedbackForm')[0].reset();
        $('#feedbackModal').modal('show');
    });
});
</script>

<?php if (isset($_SESSION['alert_type']) && isset($_SESSION['alert_message'])): ?>
<script>
Swal.fire({
    icon: '<?= $_SESSION['alert_type'] ?>',
    title: '<?= ($_SESSION['alert_type'] === "success") ? "Success" : "Notice" ?>',
    text: '<?= $_SESSION['alert_message'] ?>',
    confirmButtonColor: '#3085d6',
    confirmButtonText: 'OK'
});
</script>
<?php 
// Clear the alert session so it doesn't repeat on refresh
unset($_SESSION['alert_type']);
unset($_SESSION['alert_message']);
endif;
?>

</body>
</html>
