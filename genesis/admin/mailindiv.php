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

$learnerid = intval($_GET['id']);

// Get email for this learner
$query = "SELECT * FROM learners WHERE LearnerId = $learnerid";
$sql = $connect->query($query);
$results = $sql->fetch_assoc();

$email = $results['Email'];
$name = $results['Name'];
$surname = $results['Surname'];

$failedEmails = []; // To store emails that failed to send

try {
    // Create an instance of PHPMailer
    $mail = new PHPMailer(true);

    // Enable SMTP debugging to view detailed output
    $mail->SMTPDebug = 0;  // Set to 0 for no debug output, 2 for detailed output

    // Server settings
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host = 'smtp.gmail.com';                              // Set the SMTP server to send through
    $mail->SMTPAuth = true;                                      // Enable SMTP authentication
    $mail->Username = 'thedistributorsofedu@gmail.com';          // Your Gmail email address
    $mail->Password = 'bxuxtebkzbibtvej';                       // Your Gmail App Password (if 2FA enabled)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;             // Enable implicit TLS encryption
    $mail->Port = 465;                                           // TCP port to connect to

    // Recipients
    $mail->setFrom('thedistributorsofedu@gmail.com', 'The Distributors of Education'); // Sender's email
    $mail->addAddress($email, $surname);                         // Recipient email and name
    $mail->addReplyTo('thedistributorsofedu@gmail.com', 'The Distributors of Education'); // Reply-to email

    // Set email format to HTML
    $mail->isHTML(true);                                         // Set email format to HTML
    $mail->Subject = 'EMAIL VERIFICATION';    ////this is the header of the email
    $mail->Body    = '
    <p>Dear ' . $name . ' ' . $surname . ',</p>
    <p>We hope this mail finds you well.</p>
    <p>We have created an account for you as one of the parents at Tamarisk Primary School.</p>
    <p>What Happens Next?</p>
    <p>Verify Your Email: Clicking the link above will verify your email address and activate your account.</p>
    <p>Once verified, you will have full access to our system, where you can find valuable resources and stay updated with the latest news and events.</p>
    <p>Ensure you stay connected with us for important updates and information relevant to you and your child.</p>
    <p>Warm regards,</p>
    <p>Distributors of Education</p>
    <p>Email: thedistributorsofedu@gmail.com</p>
    <p>Phone: +27 81 461 8178</p>
    ';

    // Send the email
    if($mail->send()) {
        // Success message for all emails sent successfully
        echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Mail successfully sent to ' . $name . ' ' . $surname . '!",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "mailparent.php";
                    }
                });
              </script>';
        exit;
    } else {
        // If email fails to send, record it in the failed emails array
        $failedEmails[] = $email;
    }

} catch (Exception $e) {
    // Add error to the failed emails list
    $failedEmails[] = $email;
}

// If there are any failed emails
if (count($failedEmails) > 0) {
    $failedList = implode('<br>', $failedEmails); // Convert array to a list of emails
    // Error message with detailed failed emails
    echo '<script>
            Swal.fire({
                icon: "error",
                title: "Mail could not be sent to some parents!",
                html: "The following emails could not be sent:<br>' . $failedList . '",
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

?>

<div class="wrapper"></div>

</body>
</html>
