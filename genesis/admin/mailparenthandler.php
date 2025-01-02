<!DOCTYPE html>
<html>

<?php
session_start();
include('../partials/connect.php');
?>

<?php include("adminpartials/head.php"); ?>

<link rel="stylesheet" type="text/css" href="./fontawesome-free-6.4.0-web\fontawesome-free-6.4.0-web\css\all.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

<body class="hold-transition skin-blue sidebar-mini">

<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require '../../vendor/autoload.php';  
include('../partials/connect.php');


//if (isset($_POST['btnsendP'])) {

    // SQL query for learners owing money
    $sql = "SELECT lt.*, ls.* 
    FROM learners AS lt
    JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
    WHERE lt.TotalOwe > 0
    AND ls.ContractExpiryDate = (
        SELECT MAX(ls2.ContractExpiryDate)
        FROM learnersubject AS ls2
        WHERE ls2.LearnerId = ls.LearnerId
    ) ";

    $results = $connect->query($sql);

    $LearnerIDs = [];
    while($final = $results->fetch_assoc()) { 
        //ids of all leRNERS WHO OWES
        $LearnerIDs[] = $final['LearnerId'];
    } 
    if (!empty($LearnerIDs)) {

        $learneridarray = implode(',', $LearnerIDs);
        // Get parent Ids from their table  for each learner that owess
        $query = "SELECT ParentId FROM parentlearner WHERE LearnerId IN ($learneridarray)";    //have a look at this line
        $sql = $connect->query($query);
    
        $parentIDs = [];
        while ($Idresults = $sql->fetch_assoc()) {
            $parentIDs[] = $Idresults['ParentId'];
        }
    
        if (!empty($parentIDs)) {
            $parentidarray = implode(',', $parentIDs);
    
            $query2 = "SELECT ParentTitle, ParentEmail, ParentName, ParentSurname FROM parents WHERE ParentId IN ($parentidarray)";  //gets emails for the each parentid
            $sql2 = $connect->query($query2);
    
            while ($results = $sql2->fetch_assoc()) {  //fetch the following for the each parentid
                $title = $results['ParentTitle'];
                $email = $results['ParentEmail'];
                $name = $results['ParentName'];
                $surname = $results['ParentSurname'];
    
    
                // send message here
                // Send the verification email to the user using PHPMailer
                $mail = new PHPMailer(true);
                try {
                  // Server settings
                  $mail->isSMTP(); // Set mailer to use SMTP
                  $mail->Host = 'smtp.gmail.com'; // Specify your SMTP server (in this case, Gmail)
                  $mail->SMTPAuth = true; // Enable SMTP authentication
                  $mail->Username = 'thedistributorsofedu@gmail.com'; // SMTP username (your Gmail email address)
                  $mail->Password = 'bxuxtebkzbibtvej'; // SMTP password (your Gmail password)
                  $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Enable TLS encryption, `PHPMailer::ENCRYPTION_STARTTLS` also accepted
                  $mail->Port = 465; // TCP port to connect to (Gmail's SMTP port for SSL)
    
                        // Recipients
                        $mail->setFrom('thedistributorsofedu@gmail.com', 'Genesis');
                        $mail->addAddress($email, $surname);                           // Add a recipient
                        $mail->addReplyTo('thedistributorsofedu@gmail.com', 'Genesis'); // Reply-to address
                                            // Set email format to HTML
                        $mail->Subject = 'EMAIL VERIFICATION';
                        $mail->Body = '
                        <p>Dear ' . $title . ' ' . $surname . ',</p>
                        <p>We hope this mail finds you well.</p>
                        <p>remind them that they need to pay.. they have outstading balance :</p>
                        <p>We have created an account for you as one of the Parent at Tamarisk Primary School.</p>
                        <p>What Happens Next?</p>
                        <p>Verify Your Email: Clicking the link above will verify your email address and activate your account.</p>
                        <p>Once verified, you will have full access to our system, where you can find valuable resources and stay updated with the latest news and events.</p>
                        <p>Ensure you stay connected with us for important updates and information relevant to you and your Child.</p>
                        <p>Warm regards,</p>
                        <p>Distributors of Education</p>
                        <p>Email: thedistributorsofedu@gmail.com</p>
                        <p>Phone: +27 81 461 8178</p>
                        ';

                        $mail->send();                 

    
                        echo '<script>
                        Swal.fire({
                            icon: "success",
                            title: "Announcement successfully sent to all the Parents!",
                            confirmButtonColor: "#3085d6",
                            confirmButtonText: "OK"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "mailparent.php";
                            }
                        });
                        </script>';
                        exit;

                } catch (Exception $e) {

                    echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Message could not be sent to ' . $title . ' ' . $name . ' ' . $surname . '!",
                        text: "Please check if you have entered the correct email.",
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "mailparent.php";
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
                                    window.location.href = "mailparent.php";
                                }
                            });
                        </script>';
        }

    } else {
        echo '<script>
                        Swal.fire({
                            icon: "error",
                            title: "Message could not be sent!",
                            text: "No leaner that owes exists.",
                            confirmButtonColor: "#3085d6",
                            confirmButtonText: "OK"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "mailparent.php";
                            }
                        });
                    </script>';
    }

//}
?>

<div class="wrapper"></div>




</body>
</html>