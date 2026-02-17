<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<?php include_once(COMMON_PATH . "/../partials/head.php");  ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include_once(TUTOR_PATH . "/../partials/header.php"); ?> 
  <?php include_once(TUTOR_PATH . "/../partials/mainsidebar.php"); ?>

  <div class="content-wrapper">
     <section class="content-header">
       <h1>Edit <small>Learner</small></h1>
        <ol class="breadcrumb">
          <li><a href="tutorindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Learner</li>
        </ol>
      </section>
    <section class="content">
      <?php
        include_once(BASE_PATH . "/partials/connect.php"); 

      if (isset($_GET['id'])) {
        $learnerId = intval($_GET['id']);
        $max = intval($_GET['max']);
        $activityId = intval($_GET['aid']);

        if (isset($_POST["update"])) {
          $learnerFakeid = $_POST['learnerFakeid'];
          $activityId = $_POST['activityid'];
          $Attendance = $_POST['attendance'];
          $Marks = $_POST['marks'];
          $Submitted = $_POST['submitted'];
          $AttendanceReason = $_POST['attendancereason'];
          $SubmissionReason = $_POST['submissionreason'];

          $sql = "UPDATE learneractivitymarks
                  SET Attendance = ?, MarksObtained = ?, Submission = ?, AttendanceReason = ?, SubmissionReason = ? 
                  WHERE LearnerId = ? AND ActivityId = ?";
          $stmt = $connect->prepare($sql);

          if ($stmt) {
            $stmt->bind_param("sisssii", $Attendance, $Marks, $Submitted, $AttendanceReason, $SubmissionReason, $learnerFakeid, $activityId);
            $stmt->execute();
            $stmt->close();
            
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    Swal.fire({
                        icon: "success",
                        title: "Updated",
                        text: "Learner data updated successfully.",
                        confirmButtonText: "OK"
                    }).then(() => {
                        window.location.href = "classhandler.php?aid=' . $activityId . '";
                    });
                </script>';

            exit();
          } else {
            echo '<script>
                    alert("Prepare failed: ' . $connect->error . '");
                  </script>';
          }
          
          
          
        }

        // Fetch learner details
        $sql = "SELECT lt.*, u.Name, u.Surname 
                FROM learners AS lt 
                JOIN users AS u ON lt.LearnerId = u.Id 
                WHERE lt.LearnerId = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("i", $learnerId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();
          $name = $row['Name'];
          $surname = $row['Surname'];
          ?>

          <form method="post" action="editmarks.php?id=<?= $learnerId ?>&max=<?= $max ?>&aid=<?= $activityId ?>">

            <input type="hidden" name="learnerFakeid" value="<?= $learnerId ?>">
            <input type="hidden" name="activityid" value="<?= $activityId ?>">

            <div class="box box-primary">
               <h3 class="text-center">Edit Learner Marks</h3>

              <div class="box-body table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>No</th>
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
                    <tr>
                      <td><?= $learnerId ?></td>
                      <td><?= $name ?></td>
                      <td><?= $surname ?></td>
                      <td>
                        <select name="attendance" class="form-control">
                          <option value="Present">Present</option>
                          <option value="Absent">Absent</option>
                          <option value="Late">Late</option>
                        </select>
                        
                      </td>
                      <td>
                        <select name="attendancereason" class="form-control">
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
                        <input type="number" name="marks" min="0" max="<?= $max ?>" class="form-control" required>
                      </td>
                      <td>
                        <select name="submitted" class="form-control">
                          <option value="Yes">Yes</option>
                          <option value="No">No</option>
                        </select>
                      </td>
                      <td>
                        <select name="submissionreason" class="form-control">
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
                  </tbody>
                </table>
              </div>
              <div class="box-footer text-center">
                <button type="submit" name="update" class="btn btn-primary">Update</button>
              </div>
            </div>
          </form>

          <?php
        } else {
          echo "<div class='alert alert-danger'>Learner not found.</div>";
        }
        $stmt->close();
        $connect->close();
      } else {
        echo "<div class='alert alert-warning'>Invalid learner ID.</div>";
      }
      ?>
    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<!-- Scripts -->

<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>

</body>
</html>
