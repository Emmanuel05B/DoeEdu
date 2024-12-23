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
  <!-- Left side column. contains the logo and sidebar -->
  <?php include("adminpartials/header.php") ?>;
  <!-- Left side column. contains the logo and sidebar -->
  <?php include("adminpartials/mainsidebar.php") ?>;

  <!-- Content Wrapper. Contains page content --->
  <div class="content-wrapper">
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

  <?php
     include('../partials/connect.php'); 

     if (isset($_POST["submit"])) {

    // get form data
    $learnerFakeids = $_POST['learnerFakeids'];
    $activityIds = $_POST['activityIds'];  
    
    $attendances = $_POST['attendances'];  
    $attendancereasons = $_POST['attendancereasons']; 

    $marks = $_POST['marks']; 

    $submissions = $_POST['submitted'];  
    $submissionreasons = $_POST['submissionreasons'];  


    // Prepare the SQL statements
    $checkStmt = $connect->prepare("SELECT COUNT(*) FROM learneractivitymarks WHERE DateAssigned = CURDATE() AND ActivityId = ?");

    $insertStmt = $connect->prepare("INSERT INTO learneractivitymarks (LearnerId,	ActivityId,	MarkerId,	MarksObtained,	DateAssigned,	Attendance,	AttendanceReason,	Submission,	SubmissionReason) 
    VALUES (?, ?, ?, ?, Now(), ?, ?, ?, ?)");  	


    if ($checkStmt === false || $insertStmt === false) {
        die("Prepare failed: " . $connect->error); // Handle prepare statement failure
    }

    $index = 0;
    $numEntries = count($learnerFakeids);     //count the number of entries.
    $success = true;

    while ($index < $numEntries) {       //loop throght the Learners
        $markerId = $_SESSION['user_id'];  // for the marker

        $learnerFakeid = $learnerFakeids[$index];       //learnerId of that specific learner at index number
        $activityId = $activityIds[$index];  //on hold

        $attendance = $attendances[$index];  
        $attendanceReason = $attendancereasons[$index]; 
    
        $mark = $marks[$index]; 
    
        $submission = $submissions[$index]; 
        $submissionReason = $submissionreasons[$index]; 


        // Check if a task already exists for this learner today
        $checkStmt->bind_param("i", $activityId);    //..................................fix here
        
        if (!$checkStmt->execute()) {
            echo 'Failed to check existing reports. Please try again later.';
            $success = false;
            break;
        }

        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->free_result(); // Free the result set

        if ($count > 0) {
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Report Already Exists",
                    text: "Data has already been saved for today. Click the Edit Button if you wish to change data",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "class.php?gid=$graid&sid=$subject&cid=$chapter";
                    }
                });
            </script>';
            $success = false;
            break;
        }

        // Bind parameters and execute the insert statement
        $insertStmt->bind_param("iiiissss", $learnerFakeid, $activityId, $markerId, $mark, $attendance, $attendanceReason, $submission, $submissionReason);


        if (!$insertStmt->execute()) {
            if ($connect->errno === 1062) { // Duplicate entry error code
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Duplicate Report",
                        text: "Data has already been saved for today. Click the Edit Button if you wish to change data000",
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "class.php?gid=$graid&sid=$subject&cid=$chapter";
                        }
                    });
                </script>';
                $success = false;
                break;
            } else {
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Submission Error",
                        text: "Failed to submit the report. Please try again later.",
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "class.php?gid=$graid&sid=$subject&cid=$chapter";
                        }
                    });
                </script>';
                $success = false;
                break;
            }
        }

        $index++;
    }

    // Close the statements
    $checkStmt->close();
    $insertStmt->close();

    if ($success) {
        echo '<script>
            Swal.fire({
                icon: "success",
                title: "Successfully Reported",
                text: "Data has been saved for all Learners.",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "class.php?gid=$graid&sid=$subject&cid=$chapter";
                }
            });
        </script>';
    }

    // Close the database connection
    $connect->close();
    exit();
     }
?>



  <!-- --------------------------------------front end code below ------------------------------------------------>





    <!-- Content Header (Page header) -->
    <section class="content-header">
    <?php
      include('../partials/connect.php');

      $activityid = intval($_GET['aid']);  // Ensure it's an integer

      $sql = "SELECT * FROM activities WHERE ActivityId = $activityid";
      $results = $connect->query($sql);
      $finalres = $results->fetch_assoc(); 

      $activityName = $finalres['ActivityName'];
      $maxmarks = $finalres['MaxMarks'];
      $grade = $finalres['Grade'];
      $subject = $finalres['SubjectId'];

      

    ?> 
    </section>

    <!-- Main content table---------------------------------------------> 
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <!-- /.box -->

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Learners</h3>
            </div>
  
            <div class="box-header">
              <h3 class="box-title">Activity Name = <?php echo $activityName ?> and 
              Total = <?php echo $maxmarks ?> 
              
              and submit all this data into the learnerActivity Marks in the classhandler.php..   
              keep in mind that these have to be learners f this particular grade and subject. 
              the logic might be.. go to the learner subject table and get all learners/IDs who are doing 
               that subjectId... then find their names in the learners table as well as their grade.</h3>
              
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <form id="learnerForm" action="class.php" method="post">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>StNo.</th>
                      <th>Name</th>
                      <th>Surname</th>
                      <th>Attendance</th>
                      <th>Reason</th>
                      <th>Enter Marks</th>
                      <th>Submitted</th>
                      <th>Reason</th>
                      <th>Edit</th>
                      <th>Profile</th>

                    </tr>
                  </thead>

                  <tbody>
                    <?php

                   $sql = "SELECT lt.LearnerId, 
                   lt.Name, 
                   lt.Surname, 
                   lt.Email, 
                   lt.ContactNumber, 
                   lt.Grade, 
                   lt.RegistrationDate, 
                   lt.LearnerKnockoffTime, 
                   lt.Math, 
                   lt.Physics, 
                   lt.TotalFees, 
                   lt.TotalPaid, 
                   lt.TotalOwe, 
                   lt.Creator, 
                   lt.ChapterId,
                   ls.LearnerSubjectId,
                   ls.SubjectId, 
                   ls.TargetLevel, 
                   ls.CurrentLevel, 
                   ls.NumberOfTerms, 
                   ls.ContractExpiryDate, 
                   ls.Status
                   FROM learners AS lt
                   JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
                   WHERE lt.Grade = ? 
                   AND ls.SubjectId = ? 
                   AND ls.Status = 'Active'
                   AND ls.ContractExpiryDate > CURDATE()";  // Ensure contract expiry date is greater than today


                   $stmt = $connect->prepare($sql);
                   $stmt->bind_param("si", $grade, $subject); 
                   $stmt->execute();
                   $result = $stmt->get_result();

                   
                        while($final = $results->fetch_assoc()) { ?>
                            <tr>

                              <td>
                                <?php echo $final['LearnerId'] ?>
                              </td>
                              <td>
                                <?php echo $final['Name'] ?>
                                <input type="hidden" id="urlParams" name="learnerFakeids[]" value="<?php echo $final['LearnerId'] ?>">
                                <input type="hidden" id="urlParams" name="activityIds[]" value="<?php echo $finalres['ActivityId'] ?>">
                              </td>
                              <td>
                                <?php echo $final['Surname'] ?>
                              </td>
                              <td>
                                <select name="attendances[]">
                                  <option value="present" selected>Present</option>
                                  <option value="absent">Absent</option>
                                  <option value="late">Late</option>
                                </select>
                             </td>
                             <td>
                                <select name="attendancereasons[]">
                                  <option value="None" selected>None Provided</option>
                                  <option value="Other">Other</option>
                                  <option value="Data Issues">Data Issues</option>

                                </select>
                             </td>
                              <td> 
                                <input type="number" name="marks[]" value="" placeholder="Marks" min="0", max="<?php echo $finalres['MaxMarks'] ?>" required>
                              </td>
                              <td>
                                <select name="submitted[]">
                                  <option value="Yes" selected>Yes</option>
                                  <option value="No">No</option>
                                </select>
                              </td>
                              <td>
                                <select name="submissionreasons[]">
                                <option value="None" selected>None Provided</option>
                                <option value="Other">Other</option>
                                <option value="Data Issues">Data Issues</option>
                                <option value="Did Not Write">Did Not Write</option>

                                </select>
                              </td>
                              <td>
                                <p><a href="editmarks.php?id=<?php echo $final['LearnerId'] ?>&max=<?php echo $finalres['MaxMarks'] ?>" class="btn btn-block btn-primary">Edit</a></p>
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
                      <th>Reason</th>
                      <th>Marks</th>
                      <th>Submitted</th>
                      <th>Reason</th>
                      <th>Edit</th>
                      <th>Profile</th>

                    </tr>
                  </tfoot>
                </table>

                <!-- Submit button -->
                <div class="button-container">
                  <button type="submit" name="submit">Submit Learner Data</button><br>
                  <button type="submit" name="submitreport">Report to Parents</button>

                </div><br>
               
              </form>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>

  </div>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-user bg-yellow"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                <p>New phone +1(800)555-1234</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                <p>nora@example.com</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-file-code-o bg-green"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                <p>Execution time 5 seconds</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="label label-danger pull-right">70%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Update Resume
                <span class="label label-success pull-right">95%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Laravel Integration
                <span class="label label-warning pull-right">50%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Back End Framework
                <span class="label label-primary pull-right">68%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Allow mail redirect
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Other sets of options are available
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Expose author name in posts
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Allow the user to show his name in blog posts
            </p>
          </div>
          <!-- /.form-group -->

          <h3 class="control-sidebar-heading">Chat Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Show me as online
              <input type="checkbox" class="pull-right" checked>
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Turn off notifications
              <input type="checkbox" class="pull-right">
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Delete chat history
              <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
            </label>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <div class="control-sidebar-bg"></div>
</div>

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>

<script>
  $(function () {
    $('#example1').DataTable()
  })
</script>

</body>
</html>
