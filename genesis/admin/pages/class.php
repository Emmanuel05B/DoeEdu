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
  <!-- Left side column. contains the logo and sidebar -->
  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  <!-- Content Wrapper. Contains page content --->
  <div class="content-wrapper">
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

  <?php

    include(__DIR__ . "/../../partials/connect.php");

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
      <h1>Class List <small>Learners</small></h1>
        <ol class="breadcrumb">
          <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Class List</li>
        </ol>

      <?php
      include('../../partials/connect.php');

      $activityid = intval($_GET['aid']);  // Ensure it's an integer

      $sql = "SELECT * FROM activities WHERE ActivityId = $activityid";
      $results = $connect->query($sql);
      $finalres = $results->fetch_assoc(); 

      $activityName = $finalres['ActivityName'];
      $maxmarks = $finalres['MaxMarks'];
      $grade = $finalres['Grade'];   //directly from the database activities table
      $subjectId = $finalres['SubjectId'];
      $group = $finalres['GroupName'];


     ?> 
    </section>

    <!-- Main content table---------------------------------------------> 
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <!-- /.box -->
          <div class="box">
            <div class="box-header text-center">
              <h4 class="box-title" style="font-weight: bold; font-size: 22px;">
                Activity Name = <?php echo htmlspecialchars($activityName); ?> &nbsp; | &nbsp; 
                Total = <?php echo htmlspecialchars($maxmarks); ?>
              </h4>
            </div>
  
            <!-- /.box-header -->
            <div class="box-body">
              <form id="learnerForm" action="class.php" method="post">
                <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped table-hover" style="width:100%; table-layout: fixed;">
                  <thead style="background-color:#d1d9ff;">
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

                          echo "<h4>Grade 10 Mathematics Group-{$group} Learners</h4><br>";

                                $sql = "
                                    SELECT DISTINCT lt.LearnerId, lt.Grade, u.Name, u.Surname, c.GroupName
                                    FROM learners lt
                                    JOIN learnersubject ls ON lt.LearnerId = ls.LearnerId
                                    JOIN users u ON lt.LearnerId = u.Id
                                    JOIN learnerclasses lc ON lt.LearnerId = lc.LearnerID
                                    JOIN classes c ON lc.ClassID = c.ClassID
                                    WHERE lt.Grade = $grade
                                      AND lt.Math > 0
                                      AND ls.SubjectId = 1
                                      AND ls.ContractExpiryDate > CURDATE()
                                      AND c.GroupName = '$group'
                                  ";
                      

                        } else if ($subjectId == 2) {

                          echo "<h4>Grade 11 Mathematics Group-{$group} Learners</h4><br>";

                                $sql = "
                                    SELECT DISTINCT lt.LearnerId, lt.Grade, u.Name, u.Surname, c.GroupName
                                    FROM learners lt
                                    JOIN learnersubject ls ON lt.LearnerId = ls.LearnerId
                                    JOIN users u ON lt.LearnerId = u.Id
                                    JOIN learnerclasses lc ON lt.LearnerId = lc.LearnerID
                                    JOIN classes c ON lc.ClassID = c.ClassID
                                    WHERE lt.Grade = $grade
                                      AND lt.Math > 0
                                      AND ls.SubjectId = 2
                                      AND ls.ContractExpiryDate > CURDATE()
                                      AND c.GroupName = '$group'
                                  ";  


                        } else if ($subjectId == 3) {

                          echo "<h4>Grade 12 Mathematics Group-{$group} Learners</h4><br>";

                                  $sql = "
                                    SELECT DISTINCT lt.LearnerId, lt.Grade, u.Name, u.Surname, c.GroupName
                                    FROM learners lt
                                    JOIN learnersubject ls ON lt.LearnerId = ls.LearnerId
                                    JOIN users u ON lt.LearnerId = u.Id
                                    JOIN learnerclasses lc ON lt.LearnerId = lc.LearnerID
                                    JOIN classes c ON lc.ClassID = c.ClassID
                                    WHERE lt.Grade = $grade
                                      AND lt.Math > 0
                                      AND ls.SubjectId = 3
                                      AND ls.ContractExpiryDate > CURDATE()
                                      AND c.GroupName = '$group'
                                  ";

                        } else if ($subjectId == 4) {

                           echo "<h4>Grade 10 Physical Sciences Group-{$group} Learners</h4><br>";
        
                            $sql = "
                            SELECT DISTINCT lt.LearnerId, lt.Grade, u.Name, u.Surname, c.GroupName
                            FROM learners lt
                            JOIN learnersubject ls ON lt.LearnerId = ls.LearnerId
                            JOIN users u ON lt.LearnerId = u.Id
                            JOIN learnerclasses lc ON lt.LearnerId = lc.LearnerID
                            JOIN classes c ON lc.ClassID = c.ClassID
                            WHERE lt.Grade = $grade
                              AND lt.Physics > 0
                              AND ls.SubjectId = 4
                              AND ls.ContractExpiryDate > CURDATE()
                              AND c.GroupName = '$group'
                        ";


                        } else if ($subjectId == 5) {
                            echo "<h4>Grade 11 Physical Sciences Group-{$group} Learners</h4><br>";

                            $sql = "
                            SELECT DISTINCT lt.LearnerId, lt.Grade, u.Name, u.Surname, c.GroupName
                            FROM learners lt
                            JOIN learnersubject ls ON lt.LearnerId = ls.LearnerId
                            JOIN users u ON lt.LearnerId = u.Id
                            JOIN learnerclasses lc ON lt.LearnerId = lc.LearnerID
                            JOIN classes c ON lc.ClassID = c.ClassID
                            WHERE lt.Grade = $grade
                              AND lt.Physics > 0
                              AND ls.SubjectId = 5
                              AND ls.ContractExpiryDate > CURDATE()
                              AND c.GroupName = '$group'
                        ";

         
                        } else if ($subjectId == 6) {
                             
                             echo "<h4>Grade 12 Physical Sciences Group-{$group} Learners</h4><br>";
        
                            $sql = "
                            SELECT DISTINCT lt.LearnerId, lt.Grade, u.Name, u.Surname, c.GroupName
                            FROM learners lt
                            JOIN learnersubject ls ON lt.LearnerId = ls.LearnerId
                            JOIN users u ON lt.LearnerId = u.Id
                            JOIN learnerclasses lc ON lt.LearnerId = lc.LearnerID
                            JOIN classes c ON lc.ClassID = c.ClassID
                            WHERE lt.Grade = $grade
                              AND lt.Physics > 0
                              AND ls.SubjectId = 6
                              AND ls.ContractExpiryDate > CURDATE()
                              AND c.GroupName = '$group'
                        ";

                        } else {
                            // Default case if none of the statuses match
                            echo '<h1>Learners - Unknown Status</h1>';
                        }

                        $results = $connect->query($sql);   //save the list of these learners somewhere so you can use it in the classhandler/viewer
                          
              
                      //save the list of these learners somewhere so you can use it in the classhandler/viewer
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
                              <td class="align-middle">
                                <select name="attendances[]" class="form-control input-sm">
                                  <option value="present" selected>Present</option>
                                  <option value="absent">Absent</option>
                                  <option value="late">Late</option>
                                </select>
                             </td>
                             <td class="align-middle">
                                <select name="attendancereasons[]" class="form-control input-sm">
                                  <option value="None" selected>None Provided</option>
                                  <option value="Other">Other</option>
                                  <option value="Data Issues">Data Issues</option>

                                </select>
                             </td>
                              <td class="align-middle"> 
                                <input type="number" name="marks[]" class="form-control input-sm" value="" placeholder="Marks" min="0", max="<?php echo $finalres['MaxMarks'] ?>" required>
                              </td>
                          
                              <td class="align-middle">
                                <select name="submitted[]" class="form-control">
                                  <option value="Yes" selected>Yes</option>
                                  <option value="No">No</option>
                                </select>
                              </td>
                         
                              <td class="align-middle">
                                <select name="submissionreasons[]" class="form-control input-sm">
                                <option value="None" selected>None Provided</option>
                                <option value="Other">Other</option>
                                <option value="Data Issues">Data Issues</option>
                                <option value="Did Not Write">Did Not Write</option>

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
                      <th>Reason</th>
                      <th>Enter Marks</th>
                      <th>Submitted</th>
                      <th>Reason</th>
                    </tr>
                  </tfoot>
                </table>
                </div>

                <!-- Submit button -->
                 <div class="form-group mt-3 d-flex justify-content-between">
                    <div class="button-container">
                       <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-save" style="background-color:#556cd6; border:none;"></i> Submit Learner Data</button>
                  
                        <button type="reset" class="btn btn-default">
                          <i class="fa fa-refresh"></i> Reset Form
                        </button>
                    </div><br>
                 </div>
                <a href="feedback.php" class="btn btn-block btn-primary"><i class="fa fa-commenting"></i> Provide feedback to Parents</a>
               
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

  <div class="control-sidebar-bg"></div>
</div>

<!-- jQuery 3 -->
<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>


<script>
  $(function () {
    $('#example1').DataTable()
  })
</script>

</body>
</html>