<?php
session_start();
include(__DIR__ . "/../../partials/connect.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../../vendor/autoload.php';

// --- Determine action ---
$action = $_POST['action'] ?? $_GET['action'] ?? null;
$redirect = $_POST['redirect'] ?? $_GET['redirect'] ?? 'adminindex.php';

if (!$action) {
    $_SESSION['error'] = "No email action specified.";
    header("Location: $redirect");
    exit();
}

// --- PHPMailer Setup Function ---
function initMailer() {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'thedistributorsofedu@gmail.com';
    $mail->Password   = 'YOUR_APP_PASSWORD'; // secure storage recommended
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;
    $mail->setFrom('thedistributorsofedu@gmail.com', 'DoE_Genesis');
    $mail->addReplyTo('thedistributorsofedu@gmail.com', 'DoEGenesis');
    $mail->isHTML(true);
    return $mail;
}

// --- Main Actions ---
try {

    switch($action) {

        // 0. General email to anyone
        case 'general':
            $emailto = $_POST['emailto'] ?? '';
            $subject = $_POST['subject'] ?? 'No Subject';
            $message = $_POST['message'] ?? '';

            if (empty($emailto) || empty($subject) || empty($message)) {
                throw new Exception("Please provide email, subject, and message.");
            }

            $mail = initMailer();
            $mail->addAddress($emailto);
            $mail->Subject = $subject;
            $mail->Body = $message;
            $mail->send();

            $_SESSION['success'] = "Email successfully sent to " . htmlspecialchars($emailto) . "!";
            break;


        // 1. Custom email to one or multiple people
        case 'custom':
            $recipients = $_POST['recipients'] ?? [];
            if (!is_array($recipients)) $recipients = [$recipients];

            $subject = $_POST['subject'] ?? 'No Subject';
            $message = $_POST['message'] ?? '';
            $emailType = $_POST['email_type'] ?? 'tutor';

            $prefix = ($emailType === 'parent') ? 'Dear Parent,' : 'Dear Tutor,';
            $message = "<p>$prefix</p>$message<p>Regards,<br>School Team</p>";

            $mail = initMailer();
            $mail->Subject = $subject;
            $mail->Body = $message;

            foreach ($recipients as $email) $mail->addAddress($email);

            $mail->send();
            $_SESSION['success'] = "Email sent to " . count($recipients) . " recipient(s).";
            break;

            

        // 2. Invite email
        case 'invite':
            
            $invite_id = intval($_POST['id'] ?? $_GET['id'] ?? 0);
            if (!$invite_id) throw new Exception("Invalid invite ID.");

            $stmt = $connect->prepare("SELECT * FROM inviterequests WHERE id=?");
            $stmt->bind_param("i", $invite_id);
            $stmt->execute();
            $request = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            if (!$request) throw new Exception("Invite request not found.");
            if ($request['IsAccepted']) throw new Exception("Invite already accepted.");

            $token = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+7 days'));
            $insertStmt = $connect->prepare("INSERT INTO invitetokens (InviteRequestId, Token, Email, ExpiresAt) VALUES (?, ?, ?, ?)");
            $insertStmt->bind_param("isss", $invite_id, $token, $request['email'], $expiresAt);
            if (!$insertStmt->execute()) throw new Exception("Could not create invite token.");
            $insertStmt->close();

            $mail = initMailer();
            $mail->addAddress($request['email'], $request['name']);
            $mail->Subject = "Your Invitation to Register at DoE Genesis";
            $invite_link = "http://localhost/DoeEdu/genesis/common/register.php?token=$token";
            $mail->Body = "<p>Dear {$request['name']},</p>
                           <p>You have been invited to register at DoE Genesis.</p>
                           <p><a href='$invite_link' style='background-color: #008CBA; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Register Now</a></p>
                           <p>This link expires in 7 days.</p>
                           <p>Best regards,<br><strong>DoE Team</strong></p>";
            $mail->send();

            $updateStmt = $connect->prepare("UPDATE inviterequests SET IsAccepted=1 WHERE id=?");
            $updateStmt->bind_param("i", $invite_id);
            $updateStmt->execute();
            $updateStmt->close();

            $_SESSION['success'] = "Invite sent to {$request['email']}.";
            break;

        // 3. Learner verification / reminder
        case 'reminder':
            $learner_id = intval($_POST['id'] ?? $_GET['id'] ?? 0);

            if (!$learner_id) throw new Exception("Invalid learner ID.");

            $stmt = $connect->prepare("SELECT Name, Email, VerificationToken, IsVerified FROM users WHERE Id=? AND UserType='2'");
            $stmt->bind_param("i", $learner_id);
            $stmt->execute();
            $learner = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            if (!$learner) throw new Exception("Learner not found.");
            if ($learner['IsVerified']) throw new Exception("Learner already verified.");

            $mail = initMailer();
            $mail->addAddress($learner['Email'], $learner['Name']);
            $mail->Subject = 'Reminder: Please Verify Your DoE Account';
            $verify_link = "http://localhost/DoeEdu/genesis/common/verification.php?token={$learner['VerificationToken']}";
            $mail->Body = "<p>Dear {$learner['Name']},</p>
                           <p>Please verify your account:</p>
                           <a href='$verify_link' style='background-color: #008CBA; color: white; padding: 10px 20px;'>Verify Email</a>
                           <p>Best regards,<br><strong>DoE Team</strong></p>";
            $mail->send();

            $_SESSION['success'] = "Reminder sent to {$learner['Email']}.";
            break;

        // 4. reminder all
        case 'reminder_all':
        $stmt = $connect->query("SELECT Id, Name, Email, VerificationToken FROM users WHERE IsVerified=0 AND UserType='2'");
        $successCount = 0;
        $failures = [];

        while ($learner = $stmt->fetch_assoc()) {
            try {
                $mail = initMailer();
                $mail->addAddress($learner['Email'], $learner['Name']);
                $mail->Subject = 'Reminder: Please Verify Your DoE Account';
                $verify_link = "http://localhost/DoeEdu/genesis/common/verification.php?token={$learner['VerificationToken']}";
                $mail->Body = "<p>Dear {$learner['Name']},</p>
                            <p>Please verify your account:</p>
                            <a href='$verify_link' style='background-color: #008CBA; color: white; padding: 10px 20px;'>Verify Email</a>
                            <p>Best regards,<br><strong>DoE Team</strong></p>";
                
                $mail->send();
                $successCount++;
            } catch (Exception $e) {
                $failures[] = $learner['Email'];
            }
        }

        $_SESSION['success'] = "$successCount reminder(s) sent successfully.";
        if (!empty($failures)) {
            $_SESSION['error'] = count($failures) . " reminder(s) failed to send: " . implode(', ', $failures);
        }
        break;


        // 5. Feedback emails to parents for non-submissions
        case 'feedback':
            $activityId = intval($_POST['activityId'] ?? 0);
            if (!$activityId) throw new Exception("Invalid activity ID.");

            $stmt = $connect->prepare("
                SELECT u.Name as LearnerName, u.Surname, p.Email as ParentEmail, p.Name as ParentName
                FROM learners l
                JOIN users u ON l.LearnerId=u.Id
                JOIN users p ON u.ParentId=p.Id
                LEFT JOIN learneronlineactivity loa ON loa.LearnerId=u.Id AND loa.ActivityId=?
                WHERE loa.Id IS NULL
            ");
            $stmt->bind_param("i", $activityId);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            $successCount = 0;
            $failCount = 0;
            $failures = [];

            while ($row = $result->fetch_assoc()) {
                $mail = initMailer();
                $mail->addAddress($row['ParentEmail'], $row['ParentName']);
                $mail->Subject = "Feedback: {$row['LearnerName']} did not submit activity";
                $mail->Body = "<p>Dear {$row['ParentName']},</p>
                               <p>Your child <strong>{$row['LearnerName']}</strong> did not submit the activity.</p>
                               <p>Please follow up accordingly.</p>
                               <p>Best regards,<br><strong>DoE Team</strong></p>";
                if ($mail->send()) $successCount++;
                else {
                    $failCount++;
                    $failures[] = $row['ParentEmail'];
                }
            }

            $_SESSION['success'] = "$successCount feedback email(s) sent.";
            if ($failCount) $_SESSION['error'] = "$failCount failed: " . implode(', ', $failures);
            break;

        default:
            throw new Exception("Unknown email action.");
    }

} catch(Exception $e) {
    $_SESSION['error'] = $e->getMessage();
}

header("Location: $redirect");
exit();
