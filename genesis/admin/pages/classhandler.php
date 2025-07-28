<!DOCTYPE html>
<html>
<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../../common/login.php");
  exit();
}
?>
<?php include("../adminpartials/head.php"); ?>


<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include("../adminpartials/header.php"); ?>
  <?php include("../adminpartials/mainsidebar.php"); ?>

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
      $sql = "SELECT * FROM activities WHERE ActivityId = $activityid";
      $results = $connect->query($sql);
      $finalres = $results->fetch_assoc();

      $activityName = $finalres['ActivityName'];
      $maxmarks = $finalres['MaxMarks'];
      $subject = $finalres['SubjectId'];
      $grade = $finalres['Grade'];
      $group = $finalres['GroupName'];

      //tocome back to below code.... i wonder if you  for grades
            
      $learnerMarksQuery = "
        SELECT DISTINCT lt.LearnerId, lt.Grade, u.Name, u.Surname, c.GroupName,
              lam.MarksObtained, lam.Attendance, lam.AttendanceReason, 
              lam.Submission, lam.SubmissionReason
        FROM learners lt
        JOIN learnersubject ls ON lt.LearnerId = ls.LearnerId
        JOIN users u ON lt.LearnerId = u.Id
        JOIN learnerclasses lc ON lt.LearnerId = lc.LearnerID
        JOIN classes c ON lc.ClassID = c.ClassID
        LEFT JOIN learneractivitymarks lam 
          ON lt.LearnerId = lam.LearnerId AND lam.ActivityId = $activityid
        WHERE lt.Grade = $grade
          AND lt.Math > 0
          AND ls.SubjectId = $subject
          AND ls.Status = 'Active'
          AND ls.ContractExpiryDate > CURDATE()
          AND c.GroupName = '$group'
      ";

      $results = $connect->query($learnerMarksQuery);
      ?>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-header with-border" style="background-color:#a3bffa;">
              <h3 class="box-title">
                📋 Activity Overview:  
                <span style="font-weight:normal;">"<?php echo $activityName ?>" | Max Marks: <?php echo $maxmarks ?></span>
              </h3>
            </div>

            <div class="box-body">
              <form id="learnerForm" action="class.php" method="post">
                <div class="table-responsive">
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
                      <?php while ($final = $results->fetch_assoc()) { ?>
                        <tr>
                          <td><?php echo $final['LearnerId'] ?></td>
                          <td>
                            <?php echo $final['Name'] ?>
                            <input type="hidden" name="learnerFakeids[]" value="<?php echo $final['LearnerId'] ?>">
                            <input type="hidden" name="activityIds[]" value="<?php echo $activityid ?>">
                          </td>
                          <td><?php echo $final['Surname'] ?></td>
                          <td><?php echo $final['Attendance'] ?></td>
                          <td><?php echo $final['AttendanceReason'] ?></td>
                          <td>
                            <?php echo $final['MarksObtained'] !== null ? $final['MarksObtained'] . ' / ' . $maxmarks : '<span style="color:red;">Not Graded</span>'; ?>
                          </td>
                          <td><?php echo $final['Submission'] ?></td>
                          <td><?php echo $final['SubmissionReason'] ?></td>
                          <td>
                            <a href="editmarks.php?id=<?php echo $final['LearnerId'] ?>&max=<?php echo $maxmarks ?>" class="btn btn-sm btn-primary">Edit</a>
                          </td>
                          <td>
                            <a href="learnerprofile.php?id=<?php echo $final['LearnerId'] ?>" class="btn btn-sm btn-info">Open</a>
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

                <div class="button-container">
                  <a href="feedback.php" class="btn btn-info"><i class="fa fa-commenting"></i> Provide Feedback to Parents</a>
                  <a href="classlist.php" class="btn btn-warning"><i class="fa fa-users"></i> Create Class List</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<!-- Scripts  -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="../bower_components/fastclick/lib/fastclick.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
<script src="../dist/js/demo.js"></script>
<script>
  $(function () {
    $('#example1').DataTable();
  });
</script>

</body>
</html>
