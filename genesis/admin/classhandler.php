//  this is where ill insert the data in to the learnerActivity Marks.   actually this page willl handle parents reporting
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

  <?php include("adminpartials/header.php") ?>;
  <!-- Left side column. contains the logo and sidebar -->
 <?php include("adminpartials/mainsidebar.php") ?>;
  <!-- Content Wrapper. Contains page content ##start -->
  <div class="content-wrapper">
  <!-- Content Header (Page header) -->



<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>


<?php
     include('../partials/connect.php'); 

     if (isset($_POST["submit"])) {

    // get form data
    $learnerFakeids = $_POST['learnerFakeids'];
    $activitieIds = $_POST['activitieIds'];  //out
    
    $attendances = $_POST['attendances'];  
    $attendancereasons = $_POST['attendancereasons']; 

    $marks = $_POST['marks']; 

    $submissions = $_POST['submitted'];  
    $submissionreasons = $_POST['submissionreasons'];  


    // Prepare the SQL statements
    $checkStmt = $connect->prepare("SELECT COUNT(*) FROM scores WHERE LearnerId = ? AND ReportDate = CURDATE() AND ActivityName = ?");

    $insertStmt = $connect->prepare("INSERT INTO scores (ReporterId, LearnerId, ReportDate, EngagementLevel, IndependanceLevel, ActivityName) 
    VALUES (?, ?, Now(), ?, ?, ?)");

    if ($checkStmt === false || $insertStmt === false) {
        die("Prepare failed: " . $connect->error); // Handle prepare statement failure
    }

    $index = 0;
    $numEntries = count($learnerFakeids);     //count the number of entries.
    $success = true;

    while ($index < $numEntries) {       //loop throght the Learners
        $reporterFakeid = $_SESSION['user_id'];  // for reporter

        $learnerFakeid = $learnerFakeids[$index];       //learnerId of that specific learner at index number
        //$activity = $activities[$index];  //on hold

        $attendance = $attendances[$index];  
        $attendancereason = $attendancereasons[$index]; 
    
        $mark = $marks[$index]; 
    
        $submission = $submissions[$index]; 
        $submissionreason = $submissionreasons[$index]; 

        // Check if a report already exists for this learner today
        $checkStmt->bind_param("is", $learnerFakeid, $activity);
        
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
                        window.location.href = "classhandler.php";
                    }
                });
            </script>';
            $success = false;
            break;
        }

        // Bind parameters and execute the insert statement
        $insertStmt->bind_param("iisss", $reporterFakeid, $learnerFakeid, $engagementlevel, $independancelevel, $activity);

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
                            window.location.href = "classhandler.php";
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
                            window.location.href = "classhandler.php";
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
                    window.location.href = "classhandler.php";
                }
            });
        </script>';
    }

    // Close the database connection
    $connect->close();
    exit();
     }
?>



        <!-- ./col 555555555555555555555-->
  <section class="content">

                    <?php
                        include('../partials/connect.php');

                        $teacherid = $_SESSION['user_id'];  //for logged-in teacher
                       // $FunctionalLevel = $_SESSION['FunctionalLevel'];

                        $sql = "SELECT * FROM employee WHERE Id = $teacherid";
                        $results = $connect->query($sql);
                        $final = $results->fetch_assoc();

                        $Specialisation = $final['Specialisation'];

                    ?>
  <body>
    <form id="rollCallForm" action="classhandler.php" method="post">
        <fieldset>
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
                    //select all leaners who are doing this activity... now im selecting activities

                        $sql = "SELECT * FROM learners";
                        //$sql = "SELECT * FROM activity WHERE Grade = $grade AND Sub = $subid ";

                        $results = $connect->query($sql);

                        while($final = $results->fetch_assoc()) { ?>
                            <tr>

                              <td>
                                <?php echo $final['LearnerId'] ?>
                              </td>
                              <td>
                                <?php echo $final['Name'] ?>
                                <input type="hidden" id="urlParams" name="learnerFakeids[]" value="<?php echo $final['LearnerId'] ?>">
                                <!-- <input type="hidden" id="urlParams" name="activitieIds[]" value="<?php // echo $final['LearnerId'] ?>"> -->
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
            <div class="button-container">
            <button type="Submit" name="submit">Submit Data</button>
            </div>
        </fieldset>
    </form>
  </section>


</div> <!-- /. ##start -->
      

  <div class="control-sidebar-bg"></div>
</div>

<?php include("adminpartials/queries.php") ?>;
<script src="dist/js/demo.js"></script>

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

