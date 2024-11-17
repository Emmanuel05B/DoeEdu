<!DOCTYPE html>
<html>

<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}
?>

<?php
include('../../partials/connect.php');
?>

<link rel="stylesheet" type="text/css" href="./fontawesome-free-6.4.0-web\fontawesome-free-6.4.0-web\css\all.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>


<body class="hold-transition skin-blue sidebar-mini">

<?php

    //Leaner and Reporter IDs  1
    $learnerFakeid = $_POST['learnerFakeid'];
    $reporterFakeid = $_POST['teacherFakeid'];
    $userType = $_POST['usertype'];

    //Arrival and Attendance 2
    $arrivaltime = $_POST['arrival-time'];
    $attendance = $_POST['attendance'];
    $attendancereason = $_POST['attendance-reason'];
    //Initial Transition 3
    $separationparent = $_POST['separation-parent'];
    $transitionclassroom = $_POST['transition-classroom'];

     // Morning Circle Activity  4
    $MCAengagementlevel = $_POST['engagement-level'];
    $MCAindependancelevel = $_POST['independance-level'];
    

    // Social Skills Activity  5
    if (isset($_POST['ssEngagement-level'])){
        $SKAengagementlevel = $_POST['ssEngagement-level'];  ////////////////////
    }else{
        $SKAengagementlevel = 'ssEngagement-level';
    }

    if (isset($_POST['ssIndependance-level'])){
        $SKAindependancelevel = $_POST['ssIndependance-level'];  ////////////////////
    }else{
        $SKAindependancelevel = 'ssIndependance-level';
    }
   

    // Sensory and Emotional Activity  6
    if (isset($_POST['seEngagement-level'])){
        $SEAengagementlevel = $_POST['seEngagement-level'];  ////////////////////
    }else{
        $SEAengagementlevel = 'seEngagement-level';
    }
    
    //$SEAindependancelevel = $_POST['seIndependance-level'];
    if (isset($_POST['seIndependance-level'])){
        $SEAindependancelevel = $_POST['seIndependance-level'];  ////////////////////
    }else{
        $SEAindependancelevel = 'seIndependance-level';
    }

    // Story Time Activity  7
    
    if (isset($_POST['stEngagement-level'])){
        $STAengagementlevel = $_POST['stEngagement-level'];  ////////////////////
    }else{
        $STAengagementlevel = 'stEngagement-level';
    }

   // $STAindependancelevel = $_POST['stIndependance-level'];
    if (isset($_POST['stIndependance-level'])){
        $STAindependancelevel = $_POST['stIndependance-level']; 
    }else{
        $STAindependancelevel = 'stIndependance-level';
    }




     // Start transaction
     $connect->begin_transaction();

     try{

        $stmt = $connect->prepare("INSERT INTO finalreport (LearnerId, ReporterId, ArrivalTime, Attandance, Reason, SeparationParent, ClassroomTransition,
        MCAengagementlevel, MCAindependancelevel, SKAengagementlevel, SKAindependancelevel, SEAengagementlevel, SEAindependancelevel, STAengagementlevel, STAindependancelevel) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisssssiiiiiiii", $learnerFakeid, $reporterFakeid, $arrivaltime, $attendance, $attendancereason, $separationparent, $transitionclassroom,
         $MCAengagementlevel,  $MCAindependancelevel, $SKAengagementlevel, $SKAindependancelevel, $SEAengagementlevel, $SEAindependancelevel, $STAengagementlevel, $STAindependancelevel);

       if ($stmt->execute()===TRUE) {
        // Get the last inserted reportID
          $reportId = $connect->insert_id;
          $link = '<a href="http://localhost/code-masters/team02-main/auticare/common/verify.php?token=' . $reportId . '" style="background-color: #008CBA; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">Open Report</a>';

          // Insert data into reportlinks table
          $stmt2 = $connect->prepare("INSERT INTO reportlinks (ReportId, Link, LearnerId, DateReported, IsOpened )
          VALUES(?,?,?,Now(),0)");
          $stmt2->bind_param("isi",$reportId, $link, $learnerFakeid);  

          if ($stmt2->execute()===TRUE){
            // Commit transaction
            $connect->commit();

            //can even send them an email that a report has been made


                if ($userType == '1') {
                                    
                    echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "Succesfully Reported",
                        text: "A report has been made.",
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect or perform any other action as needed
                            window.location.href = "../../teacher/gradelearners.php";
                        }
                    });
                  </script>';
                exit();

                }
                if ($userType == '0') {

                    echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "Succesfully Reported",
                        text: "A report has been made.",
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect or perform any other action as needed
                            window.location.href = "../../admin/gradesreports.php";
                        }
                    });
                  </script>'; 

                    exit();
                }

          }else{
            // Rollback transaction if second insert fails
            $connect->rollback();
           
                if ($userType == '1') {
            
                    echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "Unsuccesfully Reporting",
                        text: "No report has been made.",
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect or perform any other action as needed
                            window.location.href = "../../teacher/gradelearners.php";
                        }
                    });
                  </script>';
                exit();
            
                } 
                if ($userType == '0') {
                    
                    echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "Unsuccesfully Reporting",
                        text: "No report has been made.",
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect or perform any other action as needed
                            window.location.href = "../../admin/gradesreports.php";
                        }
                    });
                  </script>'; 
                exit();
                    
                }

          }



        }else{
            // Rollback transaction if first insert fails
            $connect->rollback();

        if ($userType == '1') {
            
            echo '<script>
            Swal.fire({
                icon: "error",
                title: "Ensure that You entered correct details",
                text: "No report has been made.",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect or perform any other action as needed
                    window.location.href = "../../teacher/gradelearners.php";
                }
            });
          </script>';
        exit();
        } 
        if ($userType == '0') {
            
            echo '<script>
            Swal.fire({
                icon: "error",
                title: "Unsuccesfully Reporting",
                title: "Ensure that You entered correct details",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect or perform any other action as needed
                    window.location.href = "../../admin/gradesreports.php";
                }
            });
          </script>'; 
        exit();
            
        }
        }


    }catch(Exception $e){
        // Rollback transaction if an exception occurs
        $connect->rollback();
    // $_SESSION['Error'] = true;
    }
    $connect->close();
        


?>


</body>
</html>

