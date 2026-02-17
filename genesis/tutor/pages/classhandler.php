<!DOCTYPE html>
<html>
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
 ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

<?php include_once(TUTOR_PATH . "/../partials/header.php"); ?> 
<?php include_once(TUTOR_PATH . "/../partials/mainsidebar.php"); ?>


  <div class="content-wrapper">

    <section class="content-header">
      <h1>Class List <small>Learners</small></h1>
      <ol class="breadcrumb">
        <li><a href="tutorindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Class List</li>
      </ol>

      <?php

      $activityid = intval($_GET['aid']);
      $stmt = $connect->prepare("SELECT * FROM activities WHERE ActivityId = ?");
      $stmt->bind_param("i", $activityid);
      $stmt->execute();
      $resultActivity = $stmt->get_result();
      $activity = $resultActivity->fetch_assoc();

      $activityName = $activity['ActivityName'];
      $maxmarks = $activity['MaxMarks'];
      $subjectId = $activity['SubjectId'];
      $grade = $activity['Grade'];
      $group = $activity['GroupName'];

      // Fetch learners + marks for this activity
      $learnerMarksQuery = "
        SELECT DISTINCT lt.LearnerId, lt.Grade, u.Name, u.Surname, c.GroupName,
                        lam.MarksObtained, lam.Attendance, lam.AttendanceReason,
                        lam.Submission, lam.SubmissionReason
        FROM learners lt
        JOIN learnersubject ls ON lt.LearnerId = ls.LearnerId
        JOIN users u ON lt.LearnerId = u.Id
        JOIN learnerclasses lc ON lt.LearnerId = lc.LearnerID
        JOIN classes c ON lc.ClassID = c.ClassID
        LEFT JOIN learneractivitymarks lam ON lt.LearnerId = lam.LearnerId AND lam.ActivityId = ?
        WHERE lt.Grade = ? AND ls.SubjectId = ? AND ls.Status = 'Active'
          AND ls.ContractExpiryDate > CURDATE() AND c.GroupName = ?
      ";
      $stmt2 = $connect->prepare($learnerMarksQuery);
      $stmt2->bind_param("isis", $activityid, $grade, $subjectId, $group);
      $stmt2->execute();
      $results = $stmt2->get_result();
      ?>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">
                📋 Activity Overview:  
                <span style="font-weight:normal;">"<?php echo htmlspecialchars($activityName) ?>" | Max Marks: <?php echo $maxmarks ?></span>
              </h3>
            </div>

            <div class="box-body">
              <div class="box-body table-responsive">
              <table id="example1" class="table table-bordered table-hover">
                <thead style="background-color:#d6e0ff;">
                  <tr>
                    <th>StNo.</th>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Attendance</th>
                    <th>Attendance Reason</th>
                    <th>Marks</th>
                    <th>Submitted</th>
                    <th>Submission Reason</th>
                    <th>Edit</th>
                    <th>Profile</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while ($learner = $results->fetch_assoc()) { ?>
                    <tr>
                      <td><?php echo $learner['LearnerId'] ?></td>
                      <td><?php echo htmlspecialchars($learner['Name']) ?></td>
                      <td><?php echo htmlspecialchars($learner['Surname']) ?></td>
                      <td><?php echo $learner['Attendance'] ?? '-' ?></td>
                      <td><?php echo $learner['AttendanceReason'] ?? '-' ?></td>
                      <td>
                        <?php echo $learner['MarksObtained'] !== null ? $learner['MarksObtained'] . ' / ' . $maxmarks : '<span style="color:red;">Not Graded</span>'; ?>
                      </td>
                      <td><?php echo $learner['Submission'] ?? '-' ?></td>
                      <td><?php echo $learner['SubmissionReason'] ?? '-' ?></td>
                      <td>
                        <a href="editmarks.php?id=<?php echo $learner['LearnerId'] ?>&aid=<?php echo $activityid ?>&max=<?php echo $maxmarks ?>" class="btn btn-sm btn-primary">Edit</a>
                      </td>
                      <td>
                        <a href="learnerprofile.php?id=<?php echo $learner['LearnerId'] ?>" class="btn btn-sm btn-info">Open</a>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
                <tfoot style="background-color:#d6e0ff;">
                  <tr>
                    <th>StNo.</th>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Attendance</th>
                    <th>Attendance Reason</th>
                    <th>Marks</th>
                    <th>Submitted</th>
                    <th>Submission Reason</th>
                    <th>Edit</th>
                    <th>Profile</th>
                  </tr>
                </tfoot>
              </table>
              </div>

              <!-- Feedback Form -->
              <form id="feedbackForm" action="emailsuperhandler.php" method="post">
                <input type="hidden" name="action" value="offline_feedback">
                <input type="hidden" name="activityId" value="<?= $activityid ?>">
                <input type="hidden" id="notSubmittedIds" name="notSubmittedIds" value="">

                
                <div class="button-container mt-3">
                  <button type="button" class="btn btn-danger" id="sendFeedbackBtn">
                    <i class="fa fa-envelope"></i> Send Feedback to Parents (Not Submitted or Attended)
                  </button>
                </div>
              </form>


            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>

<script>
  $(function () {
    $('#example1').DataTable({
      "pageLength": 25,
      "order": [[0, "asc"]]
    });
  });

  document.getElementById('sendFeedbackBtn').addEventListener('click', function () {
    // Collect learners who did not submit or attend
    const rows = document.querySelectorAll('#example1 tbody tr');
    const notSubmittedIds = [];

    rows.forEach(row => {
      const learnerId = row.children[0].textContent.trim();
      const attendance = row.children[3].textContent.trim();
      const submission = row.children[6].textContent.trim();

      if (attendance.toLowerCase() === 'absent' || submission.toLowerCase() === 'no') {
        notSubmittedIds.push(learnerId);
      }
    });

    if (notSubmittedIds.length === 0) {
      Swal.fire({
        icon: 'info',
        title: 'No Learners Found',
        text: 'All learners attended and submitted their work.',
      });
      return;
    }

    // Set the hidden input with IDs
    document.getElementById('notSubmittedIds').value = notSubmittedIds.join(',');

    // Confirm sending
    Swal.fire({
      title: 'Are you sure?',
      text: `This will send feedback to ${notSubmittedIds.length} parent(s).`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Yes, send it!'
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById('feedbackForm').submit();
      }
    });
  });
</script>


<?php if (isset($_SESSION['success']) || isset($_SESSION['error'])): ?>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    <?php if (isset($_SESSION['success'])): ?>
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '<?= addslashes($_SESSION['success']); ?>',
        confirmButtonColor: '#3085d6'
      });
    <?php unset($_SESSION['success']); endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '<?= addslashes($_SESSION['error']); ?>',
        confirmButtonColor: '#d33'
      });
    <?php unset($_SESSION['error']); endif; ?>
  });
</script>
<?php endif; ?>


</body>
</html>
