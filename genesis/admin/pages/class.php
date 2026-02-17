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
<?php include_once(ADMIN_PATH . "/../partials/header.php"); ?> 
<?php include_once(ADMIN_PATH . "/../partials/mainsidebar.php"); ?>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

  <?php
  // ================== HANDLER FOR FORM SUBMISSION ==================
  if (isset($_POST["submit"])) {
      $learnerFakeids = $_POST['learnerFakeids'];
      $activityIds = $_POST['activityIds'];  
      $attendances = $_POST['attendances'];  
      $attendancereasons = $_POST['attendancereasons']; 
      $marks = $_POST['marks']; 
      $submissions = $_POST['submitted'];  
      $submissionreasons = $_POST['submissionreasons'];  

      $insertStmt = $connect->prepare("
          INSERT INTO learneractivitymarks 
          (LearnerId, ActivityId, MarkerId, MarksObtained, DateAssigned, Attendance, AttendanceReason, Submission, SubmissionReason) 
          VALUES (?, ?, ?, ?, CURDATE(), ?, ?, ?, ?)
      ");

      $checkStmt = $connect->prepare("
          SELECT COUNT(*) 
          FROM learneractivitymarks 
          WHERE LearnerId = ? AND ActivityId = ? AND DateAssigned = CURDATE()
      ");

      if ($checkStmt === false || $insertStmt === false) {
          die("Prepare failed: " . $connect->error);
      }

      $success = true;
      $markerId = $_SESSION['user_id'];  
      $numEntries = count($learnerFakeids);

      for ($i = 0; $i < $numEntries; $i++) {
          $learnerId = $learnerFakeids[$i];
          $activityId = $activityIds[$i];
          $attendance = $attendances[$i];  
          $attendanceReason = $attendancereasons[$i]; 
          $mark = $marks[$i]; 
          $submission = $submissions[$i]; 
          $submissionReason = $submissionreasons[$i]; 

          // Check duplicate for this learner + activity + today
          $checkStmt->bind_param("ii", $learnerId, $activityId);
          $checkStmt->execute();
          $checkStmt->bind_result($count);
          $checkStmt->fetch();
          $checkStmt->free_result();

          if ($count > 0) {
              echo '<script>
                  Swal.fire({
                      icon: "error",
                      title: "Duplicate Entry",
                      text: "Marks already recorded for Learner ID ' . $learnerId . ' today.",
                      confirmButtonText: "OK"
                  }).then(() => {
                      window.location.href = "class.php?aid=' . $activityId . '";
                  });
              </script>';
              $success = false;
              break;
          }

          // Insert record
          $insertStmt->bind_param(
              "iiiissss", 
              $learnerId, $activityId, $markerId, $mark, 
              $attendance, $attendanceReason, $submission, $submissionReason
          );

          if (!$insertStmt->execute()) {
              $success = false;
              echo '<script>
                  Swal.fire({
                      icon: "error",
                      title: "Database Error",
                      text: "Failed to save data for Learner ID ' . $learnerId . '",
                      confirmButtonText: "OK"
                  });
              </script>';
              break;
          }
      }

      $checkStmt->close();
      $insertStmt->close();

      if ($success) {
        
          echo '<script>
              Swal.fire({
                  icon: "success",
                  title: "Saved Successfully",
                  text: "Marks have been recorded for all learners.",
                  confirmButtonText: "OK"
              }).then(() => {
                  window.location.href = "classhandler.php?aid=' . $activityIds[0] . '";
              });
          </script>';
      }

      $connect->close();
      exit();
  }
  ?>

  <!-- ================== FRONTEND MARKS ENTRY ================== -->

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Class List <small>Learners</small></h1>
      <ol class="breadcrumb">
        <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Class List</li>
      </ol>

    <?php
    $activityid = intval($_GET['aid']);  // Ensure it's an integer

    $sql = "SELECT * FROM activities WHERE ActivityId = $activityid";
    $results = $connect->query($sql);
    $finalres = $results->fetch_assoc(); 

    $activityName = $finalres['ActivityName'];
    $maxmarks = $finalres['MaxMarks'];
    $grade = $finalres['Grade'];   
    $subjectId = $finalres['SubjectId'];
    $group = $finalres['GroupName'];
    ?> 
  </section>

  <!-- Main content table -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header text-center">
            <h4 class="box-title" style="font-weight: bold; font-size: 22px;">
              Activity Name = <?php echo htmlspecialchars($activityName); ?> &nbsp; | &nbsp; 
              Total = <?php echo htmlspecialchars($maxmarks); ?>
            </h4>
          </div>
  
          <div class="box-body">
            <form id="learnerForm" action="class.php" method="post">

                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped table-hover">
                        <thead style="background-color:#d1d9ff;">
                            <tr>
                                <th>StNo.</th>
                                <th>Name</th>
                                <th>Surname</th>
                                <th>Attendance</th>
                                <th>Attendance Reason</th>
                                <th>Marks</th>
                                <th>Submitted</th>
                                <th>Submission Reason</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php
                        // Get subject details
                        $subjectQuery = "SELECT SubjectName FROM subjects WHERE SubjectId = ?";
                        $stmtSub = $connect->prepare($subjectQuery);
                        $stmtSub->bind_param("i", $subjectId);
                        $stmtSub->execute();
                        $resultSub = $stmtSub->get_result();
                        if ($resultSub && $rowSub = $resultSub->fetch_assoc()) {
                            $subjectName = $rowSub['SubjectName'];
                        } else {
                            die("Invalid subject.");
                        }

                        // Heading
                        echo "<h4>{$grade} {$subjectName} Group-{$group} Learners</h4><br>";

                        // Query learners
                        $sql = "
                            SELECT DISTINCT 
                                lt.LearnerId,
                                lt.Grade,
                                u.Name,
                                u.Surname,
                                c.GroupName
                            FROM learners lt
                            JOIN learnersubject ls 
                                ON lt.LearnerId = ls.LearnerId
                                AND ls.ContractExpiryDate > CURDATE()
                                AND ls.Status = 'Active'
                                AND ls.SubjectId = ?
                            JOIN users u 
                                ON lt.LearnerId = u.Id
                            JOIN learnerclasses lc 
                                ON lt.LearnerId = lc.LearnerID
                            JOIN classes c 
                                ON lc.ClassID = c.ClassID
                                AND c.SubjectID = ls.SubjectID
                            WHERE lt.Grade = ? 
                            AND c.GroupName = ?
                        ";
                        $stmt = $connect->prepare($sql);
                        $stmt->bind_param("iss", $subjectId, $grade, $group);
                        $stmt->execute();
                        $results = $stmt->get_result();

                        while ($final = $results->fetch_assoc()) { 
                        ?>
                            <tr>
                                <td><?php echo $final['LearnerId'] ?></td>
                                <td>
                                    <?php echo $final['Name'] ?>
                                    <input type="hidden" name="learnerFakeids[]" value="<?php echo $final['LearnerId'] ?>">
                                    <input type="hidden" name="activityIds[]" value="<?php echo $finalres['ActivityId'] ?>">
                                </td>
                                <td><?php echo $final['Surname'] ?></td>
                                <td>
                                    <select name="attendances[]" class="form-control input-sm">
                                        <option value="present" selected>Present</option>
                                        <option value="absent">Absent</option>
                                        <option value="late">Late</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="attendancereasons[]" class="form-control input-sm">
                                        <option value="None" selected>None Provided</option>
                                        <option value="Data Issues">Data Issues</option>
                                        <option value="Power Outage">Power Outage</option>
                                        <option value="No Internet Access">No Internet Access</option>
                                        <option value="Device Issues">Device Issues</option>
                                        <option value="Technical Difficulties">Technical Difficulties</option>
                                        <option value="Was Not Aware of Class">Was Not Aware of Class</option>
                                        <option value="Joined Late">Joined Late</option>
                                        <option value="Illness">Illness</option>
                                        <option value="Family Emergency">Family Emergency</option>
                                        <option value="Personal Reasons">Personal Reasons</option>
                                        <option value="Attended but Connection Dropped">Attended but Connection Dropped</option>
                                        <option value="No Supervision or Support at Home">No Supervision or Support at Home</option>
                                        <option value="Forgot Class Schedule">Forgot Class Schedule</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="marks[]" class="form-control input-sm" placeholder="Marks" min="0" max="<?php echo $finalres['MaxMarks'] ?>" required>
                                </td>
                                <td>
                                    <select name="submitted[]" class="form-control input-sm">
                                        <option value="Yes" selected>Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="submissionreasons[]" class="form-control input-sm">
                                        <option value="None" selected>None Provided</option>
                                        <option value="Data Issues">Data Issues</option>
                                        <option value="Did Not Write">Did Not Write</option>
                                        <option value="Forgot to Submit">Forgot to Submit</option>
                                        <option value="Was Absent">Was Absent</option>
                                        <option value="Technical Issues">Technical Issues</option>
                                        <option value="No Access to Materials">No Access to Materials</option>
                                        <option value="Incomplete Work">Incomplete Work</option>
                                        <option value="Personal Reasons">Personal Reasons</option>
                                        <option value="Illness">Illness</option>
                                        <option value="Family Emergency">Family Emergency</option>
                                        <option value="Lack of Understanding">Lack of Understanding</option>
                                        <option value="Lost or Damaged Work">Lost or Damaged Work</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>

                        <tfoot style="background-color:#d1d9ff;">
                            <tr>
                                <th>StNo.</th>
                                <th>Name</th>
                                <th>Surname</th>
                                <th>Attendance</th>
                                <th>Attendance Reason</th>
                                <th>Marks</th>
                                <th>Submitted</th>
                                <th>Submission Reason</th>
                            </tr>
                        </tfoot>
                    </table>
                </div> <!-- /.table-responsive -->

                <div class="form-group mt-3 d-flex justify-content-between">
                    <button type="submit" name="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Submit Learner Data
                    </button>
                    <button type="reset" class="btn btn-default">
                        <i class="fa fa-refresh"></i> Reset Form
                    </button>
                </div>
            </form>
        </div>



        </div>
      </div>
    </div>
  </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<!-- jQuery 3 -->
<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>

<script>
  $(function () {
    $('#example1').DataTable()
  })
</script>

</body>
</html>
