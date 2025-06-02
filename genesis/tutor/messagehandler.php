
<!DOCTYPE html>
<html>

<?php
session_start();
include('../partials/connect.php');
?>

<?php include("adminpartials/head.php"); ?>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>


<body class="hold-transition skin-blue sidebar-mini">




<?php

$userId = $_SESSION['user_id'];  //for logged in teacher
$senderemail = $_SESSION['email'] ;

$usql = "SELECT * FROM users WHERE Id = $userId" ;
$Teacherresults = $connect->query($usql);
$Teacherresultsfinal = $Teacherresults->fetch_assoc();  

$surname = $Teacherresultsfinal['Surname'];


if (isset($_POST['btnsend'])) {

    $reciverid = trim($_POST['reciverid']);   //reciver id...parent
   
    $subject = trim($_POST['subject']);
    $message = strip_tags($_POST['message']);   //to remove HTML and PHP tags from a string

//for parentid, senderName/email          CreatedAt	IsOpened	Message	Id	SenderName	Subject	TPEmail	No	

    $stmt = $connect->prepare("INSERT INTO messages (ParentId, SenderName, TPEmail, Subject, Message, CreatedAt, IsOpened) VALUES (?, ?, ?, ?, ?, now(), 0)");
    $stmt->bind_param("issss", $reciverid, $surname ,$senderemail, $subject, $message);

    //also handle the assignement of 0 to this messages....

    if ($stmt->execute()===TRUE) {
        echo '<script>
        Swal.fire({
            icon: "success",
            title: "Message has been sent Succesfuly!",
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
            title: "Message could not be sent!",
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
          
}


?>

<div class="wrapper">

</div>


    <?php include("adminpartials/queries.php") ;?>
    <script src="dist/js/demo.js"></script>


  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>
