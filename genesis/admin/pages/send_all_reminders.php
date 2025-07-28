<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../partials/connect.php");

require __DIR__ . '/../../../vendor/autoload.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Fetch all unverified learners
$query = "SELECT Name, Surname, Email, VerificationToken FROM users WHERE IsVerified = 0 AND UserType = '2'";
$result = $connect->query($query);

$successCount = 0;
$failCount = 0;
$failures = [];

function sendEmailToLearner($learner_email, $learner_name, $verificationToken) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'thedistributorsofedu@gmail.com';
        $mail->Password = 'bxuxtebkzbibtvej';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('thedistributorsofedu@gmail.com', 'DoE_Genesis');
        $mail->addAddress($learner_email, $learner_name);
        $mail->addReplyTo('thedistributorsofedu@gmail.com', 'DoEGenesis');

        $mail->isHTML(true);
        $mail->Subject = 'Reminder: Please Verify Your DoE Account';

        $verify_link = "http://localhost/DoeEdu/genesis/common/verification.php?token=$verificationToken";

        $mail->Body = "
        <p>Dear $learner_name,</p>
        <p>This is a friendly reminder to verify your email address to activate your DoE Genesis learner account.</p>
        <p>Please click the button below to verify:</p>
        <a href='$verify_link' style='background-color: #008CBA; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Verify Email</a>
        <p>If you’ve already verified, you can ignore this message.</p>
        <br><p>Best regards,</p><p><strong>DoE Team</strong></p>";

        return $mail->send();
    } catch (Exception $e) {
        return false;
    }
}

// Loop through learners and send emails
while ($learner = $result->fetch_assoc()) {
    $email = $learner['Email'];
    $name = $learner['Name'];
    $token = $learner['VerificationToken'];

    if (sendEmailToLearner($email, $name, $token)) {
        $successCount++;
    } else {
        $failCount++;
        $failures[] = $email;
    }
}

$_SESSION['success_message'] = "$successCount reminder(s) sent successfully.";
if ($failCount > 0) {
    $_SESSION['error_message'] = "$failCount email(s) failed: " . implode(', ', $failures);
}

header("Location: pendingverifications.php");
exit();
?>
