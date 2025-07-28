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

if (!isset($_GET['id'])) {
    $_SESSION['error_message'] = "Invalid learner ID.";
    header('Location: pendingverifications.php');
    exit();
}

$learner_id = intval($_GET['id']);
$stmt = $connect->prepare("SELECT Name, Surname, Email, VerificationToken, IsVerified FROM users WHERE Id = ? AND UserType = '2'");
$stmt->bind_param("i", $learner_id);
$stmt->execute();
$result = $stmt->get_result();
$learner = $result->fetch_assoc();
$stmt->close();

if (!$learner) {
    $_SESSION['error_message'] = "Learner not found.";
    header('Location: pendingverifications.php');
    exit();
}

if ($learner['IsVerified']) {
    $_SESSION['error_message'] = "Learner is already verified.";
    header('Location: pendingverifications.php');
    exit();
}

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

if (sendEmailToLearner($learner['Email'], $learner['Name'], $learner['VerificationToken'])) {
    $_SESSION['success_message'] = "Reminder sent to {$learner['Email']}.";
} else {
    $_SESSION['error_message'] = "Failed to send reminder to {$learner['Email']}.";
}

header("Location: pendingverifications.php");
exit();
?>
