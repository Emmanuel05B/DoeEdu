<!DOCTYPE html>
<html>

<?php
session_start();
include(__DIR__ . "/../../partials/connect.php");
?>

<?php include(__DIR__ . "/../../common/partials/head.php"); ?>

<link rel="stylesheet" type="text/css" href="./fontawesome-free-6.4.0-web\fontawesome-free-6.4.0-web\css\all.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

<body class="hold-transition skin-blue sidebar-mini">

<?php
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require __DIR__ . '/../../../vendor/autoload.php';


if (isset($_POST['btnsend'])) {


        $emailto = filter_var($_POST["emailto"], FILTER_SANITIZE_STRING);
        $subject = filter_var($_POST["subject"], FILTER_SANITIZE_STRING);
        $message = filter_var($_POST["message"], FILTER_SANITIZE_STRING);

 
            // Create an instance; passing `true` enables exceptions
            $mail = new PHPMailer(true);

        try {
            // Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host = 'smtp.gmail.com';                             // Set the SMTP server to send through
            $mail->SMTPAuth = true;                                     // Enable SMTP authentication
            $mail->Username = 'thedistributorsofedu@gmail.com';             // SMTP username
            $mail->Password = 'bxuxtebkzbibtvej';                       // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            // Enable implicit TLS encryption
            $mail->Port = 465;                                          // TCP port to connect to

            // Recipients
            $mail->setFrom('vilakazinurse128@gmail.com', 'DoE Genesis');
            $mail->addAddress($emailto);                           // Add a recipient
            $mail->addReplyTo('vilakazinurse128@gmail.com', 'DoE Genesis'); // Reply-to address

            // Content
            $mail->isHTML(true);                                        // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->send();
            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Email successfully sent!",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "adminindex.php";
                }
            });
            </script>';
        } catch (Exception $e) {
            echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Email could not be sent to ' . $emailto . '!",
                        text: "Please check if you have entered the correct email.",
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "adminindex.php";
                        }
                    });
                </script>';
            exit;
        }
    
}
?>

<div class="wrapper"></div>

</body>
</html>