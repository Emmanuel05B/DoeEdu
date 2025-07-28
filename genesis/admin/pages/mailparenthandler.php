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

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require __DIR__ . '/../../../vendor/autoload.php';
include('../../partials/connect.php');

// SQL query for learners owing money
$sql = "SELECT lt.*, ls.* 
FROM learners AS lt
JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
WHERE lt.TotalOwe > 0
AND ls.ContractExpiryDate = (
    SELECT MAX(ls2.ContractExpiryDate)
    FROM learnersubject AS ls2
    WHERE ls2.LearnerId = ls.LearnerId
)";

$results = $connect->query($sql);

$LearnerIDs = [];
$Balances = []; // Store balances separately
while($final = $results->fetch_assoc()) { 
    // Store the ids of all learners who owe
    $LearnerIDs[] = $final['LearnerId'];
    $Balances[$final['LearnerId']] = $final['TotalOwe']; // Store the balance with LearnerId as key
} 

if (!empty($LearnerIDs)) {
    $learneridarray = implode(',', $LearnerIDs);

    // Get parent Ids for each learner who owes
    $query = "SELECT ParentId FROM parentlearner WHERE LearnerId IN ($learneridarray)";
    $sql = $connect->query($query);

    $parentIDs = [];
    while ($Idresults = $sql->fetch_assoc()) {
        $parentIDs[] = $Idresults['ParentId'];
    }

    if (!empty($parentIDs)) {
        $parentidarray = implode(',', $parentIDs);

        // Get emails for each parent id
        $query2 = "SELECT ParentTitle, ParentEmail, ParentName, ParentSurname, ParentId FROM parents WHERE ParentId IN ($parentidarray)";
        $sql2 = $connect->query($query2);

        $failedEmails = []; // To track failed email addresses

        while ($results = $sql2->fetch_assoc()) {  
            $title = $results['ParentTitle'];
            $email = $results['ParentEmail'];
            $name = $results['ParentName'];
            $surname = $results['ParentSurname'];
            $parentId = $results['ParentId'];

            // Sanitize and validate email
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue; // Skip invalid emails
            }

            // Get the learner's balance for this parent (if available)
            $learnerBalance = 0;
            $queryForBalance = "SELECT TotalOwe FROM learners WHERE LearnerId IN (SELECT LearnerId FROM parentlearner WHERE ParentId = $parentId)";
            $balanceResult = $connect->query($queryForBalance);
            if ($balanceResult) {
                $balanceRow = $balanceResult->fetch_assoc();
                $learnerBalance = $balanceRow['TotalOwe'];
            }

            // send message here using PHPMailer
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP(); 
                $mail->Host = 'smtp.gmail.com'; 
                $mail->SMTPAuth = true; 
                $mail->Username = 'thedistributorsofedu@gmail.com'; // SMTP username
                $mail->Password = 'bxuxtebkzbibtvej'; // SMTP app password (for Gmail 2FA)
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
                $mail->Port = 465; 

                // Recipients
                $mail->setFrom('thedistributorsofedu@gmail.com', 'DoE Genesis');
                $mail->addAddress($email, $surname); 
                $mail->addReplyTo('thedistributorsofedu@gmail.com', 'DoE Genesis'); 

                // Set email format to HTML
                $mail->isHTML(true);  // Ensure the email is in HTML format
                
                // Updated Subject in Capital Letters
                $mail->Subject = 'OUTSTANDING BALANCE FOR YOUR CHILD';

                // Updated Body content
                $mail->Body = '
                    <html>
                    <body>
                        <p>Dear ' . $title . ' ' . $surname . ',</p>
                        <p>We hope this email finds you well. We are writing to inform you that your child currently has an outstanding balance of <strong>' . number_format($learnerBalance, 2) . '</strong>.</p>
                        <p>We kindly request that you make the necessary arrangements to settle the balance at your earliest convenience. If you have any questions, please do not hesitate to reach out.</p>
                        <p>Thank you for your attention to this matter.</p>
                        <p>Warm regards,</p>
                        <p>Distributors of Education</p>
                        <p>Email: thedistributorsofedu@gmail.com</p>
                        <p>Phone: +27 81 461 8178</p>
                    </body>
                    </html>
                ';

                // Send the email
                $mail->send(); 

            } catch (Exception $e) {
                // Track the email failure
                $failedEmails[] = $title . ' ' . $surname . ' (' . $email . ')';

                // Error handling
            }
        }

        // If there are failed emails, display them
        if (!empty($failedEmails)) {
            $failedList = implode('<br>', $failedEmails); // Convert the array to a list of names
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Some emails could not be sent!",
                    html: "The following learners did not receive their emails:<br>' . $failedList . '",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "mailparent.php";
                    }
                });
            </script>';
        } else {
            echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Emails successfully sent to all parents!",
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
            text: "No learner that owes exists.",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "OK"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "mailparent.php";
            }
        });
    </script>';
}

?>

<div class="wrapper"></div>

</body>
</html>
