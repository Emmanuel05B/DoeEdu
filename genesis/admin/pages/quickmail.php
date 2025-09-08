<?php
session_start();
include(__DIR__ . "/../../partials/connect.php");

// PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $emailto   = filter_var($_POST["emailto"], FILTER_SANITIZE_EMAIL);
    $subject   = filter_var($_POST["subject"], FILTER_SANITIZE_STRING);
    $message   = filter_var($_POST["message"], FILTER_SANITIZE_STRING);
    $emailType = $_POST['email_type'] ?? 'tutor'; // tutor or parent
    $redirect  = $_POST['redirect'] ?? 'adminindex.php'; // page to redirect after sending

    // Optional: Customize message for parent/tutor
    if ($emailType === 'parent') {
        $message = "<p>Dear Parent,</p>" . $message . "<p>Regards,<br>School Team</p>";
    } else {
        $message = "<p>Dear Tutor,</p>" . $message . "<p>Regards,<br>School Team</p>";
    }

    $mail = new PHPMailer(true);

    try {
        // SMTP config
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'thedistributorsofedu@gmail.com';
        $mail->Password   = 'xxx xxx xx xx xx'; // app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Recipients
        $mail->setFrom('thedistributorsofedu@gmail.com', 'DoE_Genesis');
        $mail->addAddress($emailto);
        $mail->addReplyTo('thedistributorsofedu@gmail.com', 'DoEGenesis');

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();

        $_SESSION['success'] = "Email successfully sent to " . htmlspecialchars($emailto) . "!";
        header("Location: " . $redirect);
        exit();

    } catch (Exception $e) {
        $_SESSION['error'] = "Email could not be sent to " . htmlspecialchars($emailto) . ". Mailer Error: {$mail->ErrorInfo}";
        header("Location: " . $redirect);
        exit();
    }

} else {
    die("Invalid request.");
}
