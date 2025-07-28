<!DOCTYPE html>
<html>

<?php
session_start();
include('../../partials/connect.php');
?>

<?php include("../adminpartials/head.php"); ?>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>


<body class="hold-transition skin-blue sidebar-mini">

<?php
  
  $messageNo = $_GET['id'];  //for message number.

  $sql = "SELECT * FROM pmessages WHERE No = $messageNo" ;
  $results = $connect->query($sql);
  $final = $results->fetch_assoc();  
  
   
      if ($messageNo) {  //setting the message clicked as deleted
        $stmt = $connect->prepare("UPDATE pmessages SET IsOpened = 2 WHERE No = ?");
        $stmt->bind_param("i", $messageNo);
        $stmt->execute();
/////////////////////////////////////
        
        if ($stmt->execute()===TRUE) {
            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Message has been moved to Trash Succesfuly!",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "mmailbox.php";
                }
            });
        </script>';
        exit;

        }else{
            echo '<script>
            Swal.fire({
                icon: "error",
                title: "Message could not be moved to trsh!",
                text: "Please try again later .",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "mmailbox.php";
                }
            });
        </script>';
        }
/////////////////////////////////////

        $stmt->close();
               
      }else{
        echo 'error. line 86';
      }

    


?>

<div class="wrapper">

</div>


    <?php include("../adminpartials/queries.php") ;?>
    <script src="../dist/js/demo.js"></script>


  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>
