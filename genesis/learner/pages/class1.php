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
        <h1>My Past Sessions <small>Give feedback on sessions you've attended</small></h1>
        <ol class="breadcrumb">
            <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Past Sessions</li>
        </ol>
    </section>

    <?php
    include(__DIR__ . "/../../partials/connect.php");
    $LearnerId = $_SESSION['user_id'];

    // Step 1: Get past sessions
    $stmtSessions = $connect->prepare("
        SELECT 
            ts.SessionId, ts.SlotDateTime, ts.Subject, ts.Grade, 
            u.Name AS TutorName, u.Surname AS TutorSurname
        FROM tutorsessions ts
        JOIN users u ON ts.TutorId = u.Id
        WHERE ts.LearnerId = ? AND ts.Status = 'Completed'
        ORDER BY ts.SlotDateTime DESC
    ");
    $stmtSessions->bind_param("i", $LearnerId);
    $stmtSessions->execute();
    $sessions = $stmtSessions->get_result();
    $stmtSessions->close();
    ?>
    

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Completed Sessions</h3>
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
                            if ($sessions->num_rows === 0) {
                                echo "<tr><td colspan='5' class='text-center'>No past sessions found.</td></tr>";
                            } else {
                                while ($session = $sessions->fetch_assoc()) {
                                    $dt = new DateTime($session['SlotDateTime']);
                                    echo "<tr>
                                            <td>{$dt->format('Y-m-d H:i')}</td>
                                            <td>".htmlspecialchars($session['Subject'])."</td>
                                            <td>".htmlspecialchars($session['Grade'])."</td>
                                            <td>".htmlspecialchars($session['TutorName'].' '.$session['TutorSurname'])."</td>
                                            <td>
                                                <button class='btn btn-primary btn-xs openFeedbackModal'
                                                    data-sessionid='{$session['SessionId']}'
                                                    data-tutor='".htmlspecialchars($session['TutorName'].' '.$session['TutorSurname'])."'>
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
    <form id="feedbackForm" method="POST" action="submit_feedback.php">
      <div class="modal-content">
        <div class="modal-header bg-blue">
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
          <h4 class="modal-title">Feedback for <span id="feedbackTutorName"></span></h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="SessionId" id="feedbackSessionId">

          <div class="form-group">
            <label>1. How clear were the tutor’s explanations?</label><br>
            <?php for($i=1;$i<=5;$i++): ?>
              <label class="radio-inline">
                <input type="radio" name="Clarity" value="<?= $i ?>" required> <?= $i ?>
              </label>
            <?php endfor; ?>
          </div>

          <div class="form-group">
            <label>2. How engaging was the tutor?</label><br>
            <?php for($i=1;$i<=5;$i++): ?>
              <label class="radio-inline">
                <input type="radio" name="Engagement" value="<?= $i ?>" required> <?= $i ?>
              </label>
            <?php endfor; ?>
          </div>

          <div class="form-group">
            <label>3. Did the tutor answer your questions satisfactorily?</label>
            <select class="form-control" name="Understanding" required>
              <option value="">Select</option>
              <option value="Yes, all of them">Yes, all of them</option>
              <option value="Some of them">Some of them</option>
              <option value="No, not really">No, not really</option>
            </select>
          </div>

          <div class="form-group">
            <label>4. Overall satisfaction (1–10)</label><br>
            <?php for($i=1;$i<=10;$i++): ?>
              <label class="radio-inline">
                <input type="radio" name="OverallRating" value="<?= $i ?>" required> <?= $i ?>
              </label>
            <?php endfor; ?>
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

    // Feedback modal handler
    $(document).on('click', '.openFeedbackModal', function() {
        const sessionId = $(this).data('sessionid');
        const tutorName = $(this).data('tutor');

        $('#feedbackSessionId').val(sessionId);
        $('#feedbackTutorName').text(tutorName);

        // Reset form
        $('#feedbackForm input[type=radio]').prop('checked', false);
        $('#feedbackForm select').val('');

        $('#feedbackModal').modal('show');
    });
});
</script>

</body>
</html>
