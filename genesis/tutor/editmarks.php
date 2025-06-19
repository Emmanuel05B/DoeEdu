<!DOCTYPE html>
<html>

<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}
?>

<?php include("tutorpartials/head.php"); ?>

    <style>
     .content {  /* for the white thingy */
        background-color: white;
        margin-top: 20px;
        margin-left: 80px;
        margin-right: 80px;
     }
     .centr {
         text-align: center;     
     }

     table {
        width: 100%;
        border-collapse: collapse;
     }
     th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
     }
     th {
        background-color: #f2f2f2;
     }
     .button-container {
        margin-top: 20px;
        display: flex;
        gap: 10px;
        }
     .button-container button {
        padding: 10px 20px;
     }
        
     td {
        position: relative;
     }

    </style>


<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include("tutorpartials/header.php") ?>;
  <!-- Left side column. contains the logo and sidebar -->
 <?php include("tutorpartials/mainsidebar.php") ?>;
  <!-- Content Wrapper. Contains page content ##start -->
  <div class="content-wrapper">
  <!-- Content Header (Page header) -->



<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>


                    
        <!-- ./col 555555555555555555555-->
  <section class="content">
  <body>
    <h1 style="text-align: center;">Activity Name</h1><br>

    <?php
     include('../partials/connect.php');

     if(isset($_GET['id'])){
        $learnerId = $_GET['id'];
        $max = $_GET['max'];

        if (isset($_POST["update"])) {
            // get form data     Attendance	Marks	Submitted	Reason
            $learnerFakeid = $_POST['learnerFakeid'];
            $activityId = $_POST['activityid'];   //activity nname.. you can pass it via hidden input from class

            $Attendance = $_POST['Attendance'];  
            $Marks = $_POST['Marks'];  
            $Submitted = $_POST['Submitted'];  
            $Reason = $_POST['Reason'];  

            // we gonna need learn  LearnerId and  ActivityId
            // Prepare the SQL statements
            $sql = "UPDATE learneractivitymarks SET Attendance = ?, MarksObtained = ? , Submission = ?, Reason = ? WHERE LearnerId = ? AND ReportDate = CURDATE() AND ActivityId = ?";
            $UpdateStmt = $connect->prepare($sql);
            $UpdateStmt->bind_param("siissi", $Attendance, $Marks, $learnerFakeid, $Submitted, $Reason, $activityId);
            $UpdateStmt->execute();
            $UpdateStmt->close();

            $_SESSION['success'] = "Data updated successfully.";

            echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Successfully Updated",
                    text: "Data has been saved.",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.isConfirmed) {
                    window.location.href = "editmarks.php?id=' . $learnerId . '";
                    }
                });
                </script>';
                exit();

        }
        
        $sql = "SELECT * FROM learners where LearnerId = $learnerId";
        $result = $connect->query($sql);
        
        if ($result->num_rows > 0){
            $row = $result->fetch_assoc();

            $name = $row['Name'];
            $surname = $row['Surname'];
         
            echo'<form id="rollCallForm" action="editmarks.php?id=' . $learnerId . '" method="post"> ';
            echo'<fieldset> ';
            echo'<table> ';
            echo'<thead> ';
            echo'<tr> ';
            echo'<th>No</th> ';
            echo'<th>Name</th> ';
            echo'<th>Surname</th> ';
            echo'<th>Attendance</th> ';
            echo'<th>Reason </th> ';
            echo'<th>Enter Marks </th> ';
            echo'<th>Submitted </th> ';
            echo'<th>Reason </th> ';
            
            echo'</tr> ';
            echo'</thead> ';
            echo'<tbody> ';
            echo'<tr> ';

            echo'<td> ';
            echo'<label for="name_student1" style="font-weight: normal;">' . $learnerId . '</label> ';
            echo'<input type="hidden" id="urlParams" name="learnerFakeid" value="' . $learnerId . '">';
            echo'</td> ';

            echo'<td><label for="name_student1" style="font-weight: normal;">' . $name . '</label></td> ';

            echo'<td> ';
            echo'<label for="surname_student1" style="font-weight: normal;">' . $surname . '</label> ';
            echo'<input type="hidden" id="urlParams" name="activity" value="LifeSkills"> ';
            echo'</td> ';

            echo'<td> ';
            echo'<select name="attendance"> ';
            echo'<option value="present" selected>present</option>';
            echo'<option value="absent">Absent</option>';
            echo'<option value="late">Late</option>';
            echo'</select> ';
            echo'</td> ';

            echo'<td>';
            echo'<select name="attendancereason">';
            echo'<option value="None" selected>None Provided</option>';
            echo'<option value="Other">Other</option>';
            echo'<option value="Data Issues">Data Issues</option>';
            echo'</select>';
            echo'</td>';

            echo'<td>';
            echo'<input type="number" name="marks" value="" placeholder="Marks" min="0", max=' . $max . ' required>';
            echo'</td>';

            echo'<td>';
            echo'<select name="submitted"> ';
            echo'<option value="Yes" selected>Yes</option>';
            echo'<option value="No">No</option>';
            echo'</select> ';
            echo'</td>';

            echo'<td>';
            echo'<select name="submissionreason">';
            echo'<option value="None" selected>None Provided</option>';
            echo'<option value="Other">Other</option>';
            echo'<option value="Data Issues">Data Issues</option>';
            echo'<option value="Did Not Write">Did Not Write</option>';
            echo'</select>';
            echo'</td>';
            
            echo'</tr> ';
            echo'</tbody> ';
            echo'</table> ';
            echo'<div class="button-container"> ';
            echo'<button type="Submit" name="update">Update Data</button> ';
            echo'</div> ';
            echo'</fieldset> ';
            echo'</form>';
           
            
        }else {
            echo 'Learner not found';
        }
       
        $connect->close();

     }else{
        echo 'Invalid learner ID.'; 
     }
     
    ?>
     


  </section>


</div> <!-- /. ##start -->
      

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

