<!DOCTYPE html>
<html>

<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}
?>

<?php include("adminpartials/head.php"); ?>
<style>
  .button-container {
    margin-top: 20px;
    display: flex;
    gap: 10px;
  }

  .button-container button {
    padding: 10px 20px;
  }
</style>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">
    <?php include("adminpartials/header.php"); ?>
    <?php include("adminpartials/mainsidebar.php"); ?>

    <div class="content-wrapper">
      <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

      <section class="content-header">
        <?php
        include('../partials/connect.php');

        $activityid = intval($_GET['aid']); // Ensure it's an integer

        $sql = "SELECT * FROM activities WHERE ActivityId = $activityid";    
        $results = $connect->query($sql);
        $finalres = $results->fetch_assoc(); 

        $activityName = $finalres['ActivityName'];
        $maxmarks = $finalres['MaxMarks'];
        $subject = $finalres['SubjectId'];

        // Query to select marks for learners based on the activity ID
        $learnerMarksQuery = "
            SELECT lt.LearnerId, lt.Name, lt.Surname, lam.MarksObtained, lam.Attendance, lam.AttendanceReason, lam.Submission, lam.SubmissionReason
            FROM learners AS lt
            JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
            LEFT JOIN learneractivitymarks AS lam ON lt.LearnerId = lam.LearnerId AND lam.ActivityId = $activityid
            WHERE ls.SubjectId = $subject AND ls.Status = 'Active' AND lt.Grade IN (10, 11, 12)
        ";

        $results = $connect->query($learnerMarksQuery);
        ?>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header">
                <h3 class="box-title">Activity Name = <?php echo $activityName ?> and Total = <?php echo $maxmarks ?>.</h3>
              </div>

              <div class="box-body">
                <form id="learnerForm" action="class.php" method="post">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
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
                      <?php
                      while($final = $results->fetch_assoc()) { ?>
                        <tr>
                          <td><?php echo $final['LearnerId'] ?></td>
                          <td>
                            <?php echo $final['Name'] ?>
                            <input type="hidden" name="learnerFakeids[]" value="<?php echo $final['LearnerId'] ?>">
                            <input type="hidden" name="activityIds[]" value="<?php echo $activityid ?>">
                          </td>
                          <td><?php echo $final['Surname'] ?></td>
                          <td><?php echo $final['Attendance']?></td>
                          <td><?php echo $final['AttendanceReason'] ?></td>
                          <td><?php echo $final['MarksObtained'] ? $final['MarksObtained'] . ' / ' . $maxmarks : 'Not Graded'; ?></td>
                          <td><?php echo $final['Submission'] ?></td>
                          <td><?php echo $final['SubmissionReason'] ?></td>
                          <td>
                            <p><a href="editmarks.php?id=<?php echo $final['LearnerId'] ?>&max=<?php echo $maxmarks ?>" class="btn btn-block btn-primary">Edit</a></p>
                          </td>
                          <td>
                            <p><a href="learnerprofile.php?id=<?php echo $final['LearnerId'] ?>" class="btn btn-block btn-primary">Open</a></p>
                          </td>
                        </tr>
                      <?php } ?>
                    </tbody>
                    <tfoot>
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

                  <div class="button-container">
                    <button type="submit" name="submit">Submit Learner Data</button>
                  </div><br>
                  <a href="feedback.php" class="btn btn-block btn-primary">Provide feedback to Parents</a>
                  <a href="classlist.php" class="btn btn-block btn-primary">Create Class List</a>
                </form>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>

  <script src="bower_components/jquery/dist/jquery.min.js"></script>
  <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
  <script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
  <script src="bower_components/fastclick/lib/fastclick.js"></script>
  <script src="dist/js/adminlte.min.js"></script>
  <script src="dist/js/demo.js"></script>

  <script>
    $(function () {
      $('#example1').DataTable();
    })
  </script>

</body>
</html>
