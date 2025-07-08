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

      $sql = "SELECT * FROM activities ORDER BY ActivityId DESC LIMIT 1";
      $results = $connect->query($sql);
  
      // Check if there is any result
      if ($results->num_rows > 0) {
      $finalres = $results->fetch_assoc();
      $activityno = $finalres['ActivityId'];
    
      } else {
      echo "No records found, after the sweet alert.";
      }

    // get form data
    /*
    $gid = $_POST['gid'];
    $sid = $_POST['sid'];  
    $cid = $_POST['cid'];  */

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
                        window.location.href = "class.php?gid=' . $_POST['gid'] . '&sid=' . $_POST['sid'] . '&cid=' . $_POST['cid'] . '&aid=' . $activityno . '";
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
                        window.location.href = "class.php?gid=' . $_POST['gid'] . '&sid=' . $_POST['sid'] . '&cid=' . $_POST['cid'] . '&aid=' . $activityno . '";
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
                title: "Successfully saved the marks",
                text: "Data has been saved for all Learners.",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK"
            }).then((result) => {
                if (result.isConfirmed) {
                   window.location.href = "classhandler.php?&aid=' . $activityno . '";
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
      $grade = $finalres['Grade'];   //directly from the database activities table
      $subjectId = $finalres['SubjectId'];
      

      ///////////////////
        //other I can get from the from the hidden input
  
     ?> 
    </section>

    <!-- Main content table---------------------------------------------> 
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <!-- /.box -->

          <div class="box">
            <div class="box-header">
            <h3 class="box-title">Activity Name = <?php echo $activityName ?> and 
            Total = <?php echo $maxmarks ?> <span style="color: red;">ddd</span>          
            </div>
  
            <!-- /.box-header -->
            <div class="box-body">
              <form id="learnerForm" action="class.php" method="post">
                <div class="table-responsive"> <!-- the magic!!!! -->
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
        
                      </tr>
                    </thead>

                    <tbody>
                      <?php
                      
                      if (!isset($_SESSION['learnerIds'])) {
                        $_SESSION['learnerIds'] = []; // Initialize session  array if it doesn't exist
                    }
          

                     // Check the status and render different HTML for each case
                        if ($subjectId == 1) {

                           echo '<h3>Grade 10 Mathematics Learners</h3><br>';

                            $sql = "SELECT lt.*, ls.*, u.Name, u.Surname
                            FROM learners AS lt
                            JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
                            JOIN users AS u ON lt.LearnerId = u.Id
                            WHERE lt.Grade = $grade 
                              AND lt.Math > 0 
                              AND ls.SubjectId = 1
                              AND ls.ContractExpiryDate > CURDATE()";  
                      

                        } else if ($subjectId == 2) {

                          echo '<h3>Grade 11 Mathematics Learners</h3><br>';
        
                            $sql = "SELECT lt.*, ls.*, u.Name, u.Surname
                            FROM learners AS lt
                            JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
                            JOIN users AS u ON lt.LearnerId = u.Id
                            WHERE lt.Grade = $grade 
                              AND lt.Math > 0 
                              AND ls.SubjectId = 2
                              AND ls.ContractExpiryDate > CURDATE()";    


                        } else if ($subjectId == 3) {
                             echo '<h3>Grade 12 Mathematics Learners</h3><br>';

                                    $sql = "SELECT lt.*, ls.*, u.Name, u.Surname
                                    FROM learners AS lt
                                    JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
                                    JOIN users AS u ON lt.LearnerId = u.Id
                                    WHERE lt.Grade = $grade 
                                      AND lt.Math > 0 
                                      AND ls.SubjectId = 3
                                      AND ls.ContractExpiryDate > CURDATE()";    

                        } else if ($subjectId == 4) {

                           echo '<h3>Grade 10 Physical Sciences Learners</h3><br>';
        
                            $sql = "SELECT lt.*, ls.*, u.Name, u.Surname
                            FROM learners AS lt
                            JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
                            JOIN users AS u ON lt.LearnerId = u.Id
                            WHERE lt.Grade = $grade 
                              AND lt.Physics > 0 
                              AND ls.SubjectId = 4
                              AND ls.ContractExpiryDate > CURDATE()";  


                        } else if ($subjectId == 5) {
                           echo '<h3>Grade 11 Physical Sciences Learners</h3><br>';

                            $sql = "SELECT lt.*, ls.*, u.Name, u.Surname
                            FROM learners AS lt
                            JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
                            JOIN users AS u ON lt.LearnerId = u.Id
                            WHERE lt.Grade = $grade 
                              AND lt.Physics > 0 
                              AND ls.SubjectId = 5
                              AND ls.ContractExpiryDate > CURDATE()"; 

         
                        } else if ($subjectId == 6) {
                             
                            echo '<h3>Grade 12 Physical Sciences Learners</h3><br>';
        
                            $sql = "SELECT lt.*, ls.*, u.Name, u.Surname
                            FROM learners AS lt
                            JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
                            JOIN users AS u ON lt.LearnerId = u.Id
                            WHERE lt.Grade = $grade 
                              AND lt.Physics > 0 
                              AND ls.SubjectId = 6
                              AND ls.ContractExpiryDate > CURDATE()";    

                        } else {
                            // Default case if none of the statuses match
                            echo '<h1>Learners - Unknown Status</h1>';
                        }



                        $results = $connect->query($sql);   //save the list of these learners somewhere so you can use it in the classhandler/viewer
                          while($final = $results->fetch_assoc()) { 
                            // Store learner ID in session
                            $_SESSION['learnerIds'][] = $final['LearnerId']; // Add the LearnerId to the session array
                            ?>     
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
                                  <select name="attendances[]" class="form-control input-sm">
                                  <option value="present" selected>Present</option>
                                  <option value="absent">Absent</option>
                                  <option value="late">Late</option>
                                  </select>
                                  
                              </td>
                              <td>
                                  <select name="attendancereasons[]">
                                    <option value="None" selected>None Provided</option>
                                    <option value="No Access to Device">No Access to Device</option>
                                    <option value="Data Issues">Data Issues</option>
                                    <option value="Network Issues">Network Issues</option>
                                    <option value="Device Malfunction">Device Malfunction</option>
                                    <option value="Family Emergency">Family Emergency</option>
                                    <option value="Illness">Illness</option>
                                    <option value="Forgot the Session">Forgot the Session</option>
                                    <option value="Personal Reasons">Personal Reasons</option>
                                    <option value="Had Other Commitments">Had Other Commitments</option>
                                    <option value="School Workload/Exam Preparation">School Workload/Exam Preparation</option>
                                    <option value="Miscommunication About Time">Miscommunication About Time</option>
                                    <option value="Shared Device Unavailable">Shared Device Unavailable</option>
                                    <option value="Other">Other </option> 
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
                                  <option value="Data Issues">Data Issues</option>
                                  <option value="No Access to Device">No Access to Device</option>
                                  <option value="Forgot to Submit">Forgot to Submit</option>
                                  <option value="Incomplete Work">Incomplete Work</option>
                                  <option value="Did Not Understand the Work">Did Not Understand the Work</option>
                                  <option value="Did Not Write">Did Not Write</option>
                                  <option value="Technical Issues">Technical Issues</option>
                                  <option value="Family Emergency">Family Emergency</option>
                                  <option value="Illness">Illness</option>
                                  <option value="Too Much School Work">Too Much School Work</option>
                                  <option value="Missed the Deadline">Missed the Deadline</option>
                                  <option value="Miscommunication About Instructions">Miscommunication About Instructions</option>
                                  <option value="Other">Other</option>
                                </select>
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
                        <th>Enter Marks</th>
                        <th>Submitted</th>
                        <th>Reason</th>
                      </tr>
                    </tfoot>
                  </table>
                </div>

                <!-- Submit button -->
                <div class="button-container">
                  <button type="submit" name="submit">Submit Learner Data</button>

                </div><br>
                <a href="feedback.php" class="btn btn-block btn-primary">Provide feedback to Parents</a>

               
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
