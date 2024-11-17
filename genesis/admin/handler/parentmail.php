<!DOCTYPE html>
<html>

<?php
session_start();
include('../../partials/connect.php');
?>

<?php include("../adminpartials/head.php"); ?>

<link rel="stylesheet" type="text/css" href="./fontawesome-free-6.4.0-web\fontawesome-free-6.4.0-web\css\all.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

<body class="hold-transition skin-blue sidebar-mini">

<?php

/*
if (isset($_POST['btnsendP'])) {
    // Get parent Ids from their table
    $query = "SELECT ParentId FROM parentlearner";
    $sql = $connect->query($query);

    $IDs = [];
    while ($Idresults = $sql->fetch_assoc()) {
        $IDs[] = $Idresults['ParentId'];
    }

    if (!empty($IDs)) {
        $in = implode(',', $IDs);

        $query2 = "SELECT Email, Name FROM users WHERE Id IN ($in)";  //gets emails for the each parentid
        $sql2 = $connect->query($query2);

        $subject = filter_var($_POST["subject"], FILTER_SANITIZE_STRING);
        $message = filter_var($_POST["message"], FILTER_SANITIZE_STRING);

        while ($results = $sql2->fetch_assoc()) {  //fetch emails for the each parentid
            $email = $results['Email'];
            $name = $results['Name'];

            // send message here
                If(vvvvvv){

                    // Recipients
                    $mail->setFrom('vilakazinurse128@gmail.com', 'Auti_Care Connect');
                    $mail->addAddress($email, $name);                           // Add a recipient
                    $mail->addReplyTo('vilakazinurse128@gmail.com', 'Auti_Care Connect'); // Reply-to address
                                        // Set email format to HTML
                    $mail->Subject = $subject;
                    $mail->Body = $message;

                    echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "Announcement successfully sent to all the Parents!",
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "../schedulemeeting.php";
                        }
                    });
                    </script>';
                    exit;

                }else{
                    echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Message could not be sent to ' . $name . '!",
                        text: "Please check if you have entered the correct email.",
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "../schedulemeeting.php";
                        }
                    });
                </script>';
                exit;
        }
              
    }   
    } else {
        echo '<script>
                        Swal.fire({
                            icon: "error",
                            title: "Message could not be sent!",
                            text: "No parent exists.",
                            confirmButtonColor: "#3085d6",
                            confirmButtonText: "OK"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "../schedulemeeting.php";
                            }
                        });
                    </script>';
    }
}
    */

                    echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "Announcement successfully sent to all the Parents!",
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "../schedulemeeting.php";
                        }
                    });
                    </script>';
                    exit;
?>

<div class="wrapper"></div>




</body>
</html>