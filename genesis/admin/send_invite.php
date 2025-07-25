<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../common/login.php");
    exit();
}

include('../partials/connect.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader (adjust path as needed)
require '../../vendor/autoload.php';

if (!isset($_GET['id'])) {
    $_SESSION['error_message'] = "Invalid invite request ID.";
    header('Location: manage_inviterequests.php');
    exit;
}

$invite_id = intval($_GET['id']);

$stmt = $connect->prepare("SELECT * FROM inviterequests WHERE id = ?");
$stmt->bind_param("i", $invite_id);
$stmt->execute();
$result = $stmt->get_result();
$request = $result->fetch_assoc();
$stmt->close();

if (!$request) {
    $_SESSION['error_message'] = "Invite request not found.";
    header('Location: manage_inviterequests.php');
    exit;
}

if ($request['IsAccepted']) {
    $_SESSION['error_message'] = "Invite already accepted.";
    header('Location: manage_inviterequests.php');
    exit;
}

// Generate a unique token
$token = bin2hex(random_bytes(32));
$expiresAt = date('Y-m-d H:i:s', strtotime('+7 days'));

// Insert token
$insertStmt = $connect->prepare("INSERT INTO invitetokens (InviteRequestId, Token, Email, ExpiresAt) VALUES (?, ?, ?, ?)");
$insertStmt->bind_param("isss", $invite_id, $token, $request['email'], $expiresAt);

if (!$insertStmt->execute()) {
    echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Token Creation Failed',
                text: 'Could not create invite token.',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'manage_inviterequests.php';
            });
          </script>";
    exit;
}
$insertStmt->close();

function sendInviteEmail($learner_email, $learner_name, $token) {
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
        $mail->Subject = 'Your Invitation to Register at DoE Genesis';

        $invite_link = "http://localhost/DoeEdu/genesis/common/register.php?token=$token";

        $mail->Body = "
        <p>Dear $learner_name,</p>
        <p>You have been invited to register at DoE Genesis.</p>
        <p>Please click the button below to complete your registration:</p>
        <a href='$invite_link' style='background-color: #008CBA; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Register Now</a>
        <p>This link will expire in 7 days and can only be used once.</p>
        <br><p>Best regards,</p><p><strong>DoE Team</strong></p>";

        $mail->send();
        return true;

    } catch (Exception $e) {
        return false;
    }
}

if (sendInviteEmail($request['email'], $request['name'], $token)) {
    // Mark as accepted
    $updateStmt = $connect->prepare("UPDATE inviterequests SET IsAccepted = 1 WHERE id = ?");
    $updateStmt->bind_param("i", $invite_id);
    $updateStmt->execute();
    $updateStmt->close();

    echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Invite Sent',
                text: 'An invitation link has been sent to {$request['email']}.',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'manage_inviterequests.php';
            });
          </script>";
    exit;
} else {
    echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Email Sending Failed',
                text: 'There was an issue sending the invite email.',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'manage_inviterequests.php';
            });
          </script>";
    exit;
}
