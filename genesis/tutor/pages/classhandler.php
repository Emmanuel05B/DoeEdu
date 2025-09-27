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
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

    <section class="content-header">
      <h1>Class List <small>Learners</small></h1>
      <ol class="breadcrumb">
        <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Class List</li>
      </ol>

      <?php
      include('../../partials/connect.php');

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
            <div class="box-header with-border" style="background-color:#a3bffa;">
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

              <div class="button-container mt-3">
                <a href="feedback.php" class="btn btn-info"><i class="fa fa-commenting"></i> Provide Feedback to Parents</a>
                <a href="classlist.php" class="btn btn-warning"><i class="fa fa-users"></i> Create Class List</a>
              </div>

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
    $('#example1').DataTable({
      "pageLength": 25,
      "order": [[0, "asc"]]
    });
  });
</script>

</body>
</html>
