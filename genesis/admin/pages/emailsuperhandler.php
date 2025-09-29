
<?php
session_start();
include(__DIR__ . "/../../partials/connect.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// --- FIXED PATH TO COMPOSER AUTOLOAD ---
require __DIR__ . '/../../../vendor/autoload.php'; // <-- fixed

// --- LOAD .env VARIABLES ---
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../'); // project root
$dotenv->load();

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
    $mail->Username   = $_ENV['EMAIL_ADDRESS'];  // read from .env
    $mail->Password   = $_ENV['EMAIL_APP_PASSWORD']; // read from .env
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;
    $mail->setFrom($_ENV['EMAIL_ADDRESS'], 'DoE_Genesis');
    $mail->addReplyTo($_ENV['EMAIL_ADDRESS'], 'DoE_Genesis');
    $mail->isHTML(true);
    return $mail;
}

// --- Main Actions ---
try {

    switch($action) {

        // 0. General email to anyone           Done!
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


        // 1. Custom email to one or multiple people            Done!
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

        // 2. Invite email....to register         Done!
        case 'invite':
            
            $invite_id = intval($_POST['id'] ?? $_GET['id'] ?? 0);
            if (!$invite_id) throw new Exception("Invalid invite ID.");

            $stmt = $connect->prepare("SELECT * FROM inviterequests WHERE id=?");
            $stmt->bind_param("i", $invite_id);
            $stmt->execute();
            $request = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            if (!$request) throw new Exception("Invite request not found.");
            //will change coz we wanna send another one as reminder/ or if expired
            // if ($request['IsAccepted']) throw new Exception("Invite already accepted.");  

            $token = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+7 days'));
            $insertStmt = $connect->prepare("INSERT INTO invitetokens (InviteRequestId, Token, Email, ExpiresAt) VALUES (?, ?, ?, ?)");
            $insertStmt->bind_param("isss", $invite_id, $token, $request['email'], $expiresAt);
            if (!$insertStmt->execute()) throw new Exception("Could not create invite token.");
            $insertStmt->close();

            $mail = initMailer();
            $mail->addAddress($request['email'], $request['name']);
            $mail->Subject = "Your Invitation to Register at DoE Genesis";
            $invite_link = "http://localhost/DoE_Genesis/DoeEdu/genesis/common/pages/registration.php?token=$token";
            $mail->Body = "<p>Dear {$request['name']},</p>
                           <p>You have been invited to register at DoE Genesis.</p>
                           <p>Please click the button below to complete your registration:</p>
                           <p><a href='$invite_link' style='background-color: #008CBA; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Register Now</a></p>
                           <p>This link expires in 7 days and can only be used once.</p>
                           <p>Best regards,<br><strong>DoE Team</strong></p>";

            $mail->send();

            $updateStmt = $connect->prepare("UPDATE inviterequests SET IsAccepted=1 WHERE id=?");
            $updateStmt->bind_param("i", $invite_id);
            $updateStmt->execute();
            $updateStmt->close();

            $_SESSION['success'] = "Invite sent to {$request['email']}.";
        break;

        // 3. parent verification/approval / reminder..already registered        Done!
        case 'reminder':
                $learner_id = intval($_POST['id'] ?? $_GET['id'] ?? 0);

                if (!$learner_id) throw new Exception("Invalid learner ID.");

                // --- Fetch learner subjects and fees ---
                $stmt = $connect->prepare("
                    SELECT s.SubjectName, ls.NumberOfTerms, ls.ContractFee
                    FROM learnersubject ls
                    JOIN subjects s ON ls.SubjectId = s.SubjectId
                    WHERE ls.LearnerId = ?
                ");
                $stmt->bind_param("i", $learner_id);
                $stmt->execute();
                $result = $stmt->get_result();

                $subjectRows = "";
                $totalFees   = 0;

                while ($row = $result->fetch_assoc()) {
                    $subjectRows .= "<tr>
                                        <td>{$row['SubjectName']}</td>
                                        <td>{$row['NumberOfTerms']} months</td>
                                        <td>R {$row['ContractFee']}</td>
                                    </tr>";
                    $totalFees += (float)$row['ContractFee'];
                }
                $stmt->close();

                // --- Fetch learner + parent details ---
                $sql = "
                    SELECT u.Name AS LearnerName, u.VerificationToken, u.IsVerified,
                        l.ParentTitle, l.ParentName, l.ParentSurname, l.ParentEmail, l.ParentContactNumber
                    FROM users u
                    INNER JOIN learners l ON u.Id = l.LearnerId
                    WHERE u.Id = ? AND u.UserType = '2'
                ";
                $stmt = $connect->prepare($sql);
                $stmt->bind_param("i", $learner_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $learner = $result->fetch_assoc();
                $stmt->close();

                if (!$learner) throw new Exception("Learner not found.");
                if ($learner['IsVerified']) throw new Exception("Learner already verified.");

                // --- Prepare variables ---
                $pname            = $learner['ParentTitle'] . ' ' . $learner['ParentName'] . ' ' . $learner['ParentSurname'];
                $pemail           = $learner['ParentEmail'];
                $learnerName      = $learner['LearnerName'];
                $verificationToken = $learner['VerificationToken'];

                // --- Send email ---
                $mail = initMailer();
                $mail->addAddress($pemail, $pname);
                $mail->Subject = 'Your Child Registration & Fees Approval - DoE';

                $verify_link = "http://localhost/DoE_Genesis/DoeEdu/genesis/common/pages/verification.php?token={$verificationToken}";

                $mail->Body = "
                    <p>Dear $pname,</p>
                    <p>Your child <strong>$learnerName</strong> has been successfully registered with the Distributors of Education.</p>

                    <p>Please review the subjects and fees below. By verifying, you acknowledge awareness of the costs and approve your child's registration:</p>

                    <table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse;'>
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Duration</th>
                                <th>Fee</th>
                            </tr>
                        </thead>
                        <tbody>
                            $subjectRows
                            <tr>
                                <td colspan='2'><strong>Total</strong></td>
                                <td><strong>R $totalFees</strong></td>
                            </tr>
                        </tbody>
                    </table>

                <p style='text-align:center; margin:20px 0;'>
                    <a href='$verify_link' 
                    style='background-color: #008CBA; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;'>
                    Verify & Approve Registration
                    </a>
                </p>

                <p>Once verified, you will receive updates on your child's progress, upcoming sessions, and announcements.</p>
                <p>If you did not expect this email or the link does not work, please contact us so we can assist you.</p>

                <br>
                <p>Warm regards,</p>
                <p><strong>DoE Team</strong></p>
            ";

            $mail->send();

            $parentFullName = $learner['ParentTitle'] . ' ' . $learner['ParentName'] . ' ' . $learner['ParentSurname'];
            $_SESSION['success'] = "Reminder sent to {$parentFullName} ({$pemail}).";

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
                                <p>This is a friendly reminder to verify your email address to activate your DoE Genesis learner account:</p>
                                <a href='$verify_link' style='background-color: #008CBA; color: white; padding: 10px 20px;'>Verify Email</a>
                                <p>If you’ve already verified, you can ignore this message.</p>
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


        // 5. Feedback emails to parents for non-submissions.    Done!
        case 'feedback':
            $activityId = intval($_POST['activityId'] ?? 0);
            if (!$activityId) throw new Exception("Invalid activity ID.");

            // 1️⃣ Get activity info
            $stmt = $connect->prepare("SELECT Title, LastFeedbackSent FROM onlineactivities WHERE Id = ?");
            $stmt->bind_param("i", $activityId);
            $stmt->execute();
            $activity = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            if (!$activity) throw new Exception("Activity not found.");

            $activityTitle = $activity['Title'];

            // >>> NEW CODE <<<  
            // 2️⃣ Get learners who did NOT submit (from form)
            $notSubmittedIds = $_POST['notSubmittedIds'] ?? '';
            $notSubmittedIds = array_filter(array_map('intval', explode(',', $notSubmittedIds)));

            if (empty($notSubmittedIds)) {
                $_SESSION['error'] = "No learners to send feedback to.";
                header("Location: " . ($_POST['redirect'] ?? 'activityoverview.php'));
                exit;
            }

            $placeholders = implode(',', array_fill(0, count($notSubmittedIds), '?'));
            $types = str_repeat('i', count($notSubmittedIds));
            $stmt = $connect->prepare("
                SELECT u.Id AS LearnerId, u.Name AS LearnerName, u.Surname AS LearnerSurname, 
                    l.ParentName, l.ParentEmail
                FROM users u
                JOIN learners l ON u.Id = l.LearnerId
                WHERE u.Id IN ($placeholders)
            ");
            $stmt->bind_param($types, ...$notSubmittedIds);
            $stmt->execute();
            $learnersToEmail = $stmt->get_result();
            $stmt->close();
            // <<< NEW CODE <<<

            // 3️⃣ Send emails
            $successCount = 0;
            $failures = [];

            while ($row = $learnersToEmail->fetch_assoc()) {
                try {
                    $mail = initMailer();
                    $mail->addAddress($row['ParentEmail'], $row['ParentName']);
                    $mail->Subject = "Feedback: {$row['LearnerName']} did not submit activity";
                    $mail->Body = "
                        <p>Dear {$row['ParentName']},</p>
                        <p>Your child <strong>{$row['LearnerName']} {$row['LearnerSurname']}</strong> did not submit the activity titled <strong>{$activityTitle}</strong> before the due date.</p>
                        <p>Please encourage them to participate in future activities to support their academic progress.</p>
                        <br><p>Warm regards,</p><p><strong>DoE Team</strong></p>
                    ";
                    $mail->send();
                    $successCount++;
                } catch (Exception $e) {
                    $failures[] = $row['ParentEmail'];
                }
            }

            // 4️⃣ Update LastFeedbackSent
            if ($successCount > 0) {
                $updateStmt = $connect->prepare("UPDATE onlineactivities SET LastFeedbackSent = NOW() WHERE Id = ?");
                $updateStmt->bind_param("i", $activityId);
                $updateStmt->execute();
                $updateStmt->close();
            }

            $_SESSION['success'] = "$successCount feedback email(s) sent.";
            if (!empty($failures)) {
                $_SESSION['error'] = count($failures) . " failed: " . implode(', ', $failures);
            }
        break;


        // 6. Notify learner of contract expiry             Done!
        case 'contract_expiry':
            $learnerEmail = trim($_POST['emailto'] ?? '');
            $learnerName  = trim($_POST['learnerName'] ?? '');
            $subjectName  = trim($_POST['subjectName'] ?? '');
            $learnerId    = intval($_POST['learnerId'] ?? 0);
            $subjectId    = intval($_POST['subjectId'] ?? 0);

            if (!$learnerEmail || !$learnerName || !$subjectName || !$learnerId || !$subjectId) {
                throw new Exception("Missing learner or subject information.");
            }

            // Update LastReminded before sending email
            $stmtUpdate = $connect->prepare("
                UPDATE learnersubject 
                SET LastReminded = NOW() 
                WHERE LearnerId = ? AND SubjectId = ?
            ");
            $stmtUpdate->bind_param("ii", $learnerId, $subjectId);
            $stmtUpdate->execute();
            $stmtUpdate->close();

            //send the email

            $mail = initMailer();
            $mail->addAddress($learnerEmail, $learnerName);
            $mail->Subject = "Important: Contract Expiry for {$subjectName}";

            $mail->Body = "
                <p>Dear {$learnerName},</p>
                
                <p>This is to inform you that your contract for the subject <strong>{$subjectName}</strong> has expired.</p>
                <p>Please renew your contract within <strong>7 days</strong> to avoid termination. Failure to renew will result in loss of access to classes for this subject.</p>
                <p>To renew, please log in to your account and complete the renewal process.</p>
                <br>
                <p>Best regards,</p>
                <p><strong>DoE Team</strong></p>
            ";

            $mail->send();

            $_SESSION['success'] = "Contract expiry notification sent to {$learnerName} - ({$learnerEmail}).";
        break;

        // 7. Mail all parents of owing learners who haven't paid in the last month       Done!
        case 'owing_lastmonth':
            $learnerIds = $_POST['learnerIds'] ?? '';
            $learnerIds = array_filter(array_map('intval', explode(',', $learnerIds)));

            if(empty($learnerIds)) {
                $_SESSION['error'] = "No learners to send reminders to.";
                break;
            }

            $placeholders = implode(',', array_fill(0, count($learnerIds), '?'));
            $types = str_repeat('i', count($learnerIds));

            // Get learner + parent details
            $stmt = $connect->prepare("
                SELECT u.Id AS LearnerId, u.Name AS LearnerName, u.Surname AS LearnerSurname,
                    l.ParentName, l.ParentSurname, l.ParentEmail
                FROM users u
                JOIN learners l ON u.Id = l.LearnerId
                WHERE u.Id IN ($placeholders)
            ");
            $stmt->bind_param($types, ...$learnerIds);
            $stmt->execute();
            $learners = $stmt->get_result();
            $stmt->close();

            $successCount = 0;
            $failures = [];

            while($row = $learners->fetch_assoc()) {
                try {
                    $mail = initMailer();
                    $mail->addAddress($row['ParentEmail'], $row['ParentName'] . ' ' . $row['ParentSurname']);
                    $mail->Subject = "Reminder: Outstanding Fees for {$row['LearnerName']}";
                    $mail->Body = "
                        <p>Dear {$row['ParentName']} {$row['ParentSurname']},</p>
                        <p>Your child <strong>{$row['LearnerName']} {$row['LearnerSurname']}</strong> has not made a payment in the last month.</p>
                        <p>Please clear the outstanding fees at your earliest convenience.</p>
                        <br>
                        <p>Best regards,</p>
                        <p><strong>DoE Team</strong></p>
                    ";
                    $mail->send();
                    $successCount++;

                    $stmtUpdate = $connect->prepare("UPDATE finances SET LastReminderSent = NOW() WHERE LearnerId = ?");
                    $stmtUpdate->bind_param("i", $row['LearnerId']);
                    $stmtUpdate->execute();
                    $stmtUpdate->close();

                } catch(Exception $e) {
                    $failures[] = $row['ParentEmail'];
                }
            }

            $_SESSION['success'] = "$successCount reminder(s) sent successfully.";
            if(!empty($failures)) {
                $_SESSION['error'] = count($failures) . " reminder(s) failed to send: " . implode(', ', $failures);
            }
        break;


        // 8. Reminding/Mailing individually for payments          Done!
        case 'owing_individual':
            $learnerId = intval($_POST['learnerId'] ?? 0);

            if ($learnerId > 0) {
                $stmt = $connect->prepare("
                    SELECT u.Name AS LearnerName, u.Surname AS LearnerSurname,
                        l.ParentName, l.ParentSurname, l.ParentEmail,
                        f.Balance
                    FROM finances f
                    JOIN users u ON f.LearnerId = u.Id
                    JOIN learners l ON u.Id = l.LearnerId
                    WHERE u.Id = ?
                ");
                $stmt->bind_param("i", $learnerId);
                $stmt->execute();
                $row = $stmt->get_result()->fetch_assoc();

                if ($row) {
                    try {
                        $mail = initMailer();
                        $pemail  = $row['ParentEmail'];
                        $pname   = trim($row['ParentName'] . ' ' . $row['ParentSurname']);
                        $learner = $row['LearnerName'] . ' ' . $row['LearnerSurname'];
                        $balance = number_format($row['Balance'], 2);

                        $mail->addAddress($pemail, $pname);
                        $mail->Subject = "Outstanding Fees Notice - $learner";
                        $mail->Body    = "
                            <p>Dear $pname,</p>
                            <p>Our records indicate outstanding school fees for your child <strong>$learner</strong>.</p>
                            <p><strong>Balance: R $balance</strong></p>
                            <p>Please settle as soon as possible.</p>
                            <br><p>Best regards,<br><strong>DoE Team</strong></p>
                        ";
                        $mail->send();

                        $stmtUpdate = $connect->prepare("UPDATE finances SET LastReminderSent = NOW() WHERE LearnerId = ?");
                        $stmtUpdate->bind_param("i", $learnerId);
                        $stmtUpdate->execute();
                        $stmtUpdate->close();

                        $_SESSION['success'] = "Reminder sent to {$pemail}.";
                        header("Location: mailparent.php");
                        exit();

                    } catch (Exception $e) {
                        $_SESSION['error'] = "Failed to send reminder to {$pemail}: " . $e->getMessage();
                        header("Location: mailparent.php");
                        exit();
                    }
                }
            }
        break;



        default:
            throw new Exception("Unknown email action.");
    }

} catch(Exception $e) {
    $_SESSION['error'] = $e->getMessage();
}

header("Location: $redirect");
exit();
