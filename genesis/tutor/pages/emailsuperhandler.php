
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
$redirect = $_POST['redirect'] ?? $_GET['redirect'] ?? 'tutorindex.php';

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
    $mail->Username   = $_ENV['EMAIL_ADDRESS'];  
    $mail->Password   = $_ENV['EMAIL_APP_PASSWORD']; 
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


        // 5. Feedback emails to parents for non-submissions.    Done!
        case 'feedback':
            $activityId = intval($_POST['activityId'] ?? 0);
            if (!$activityId) throw new Exception("Invalid activity ID.");

            //  Get activity info
            $stmt = $connect->prepare("SELECT Title, LastFeedbackSent FROM onlineactivities WHERE Id = ?");
            $stmt->bind_param("i", $activityId);
            $stmt->execute();
            $activity = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            if (!$activity) throw new Exception("Activity not found.");

            $activityTitle = $activity['Title'];
 
            // Get learners who did NOT submit (from form)
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
             
            //Send emails
            $successCount = 0;
            $failures = [];

            while ($row = $learnersToEmail->fetch_assoc()) {
                try {
                    $mail = initMailer();
                    $mail->addAddress($row['ParentEmail'], $row['ParentName']);
                    $mail->Subject = "Feedback: {$row['LearnerName']} did not submit activity";
                    $mail->Body = "
                        <p>Dear {$row['ParentName']},</p>
                        <p>Your child <strong>{$row['LearnerName']} {$row['LearnerSurname']}</strong> did not complete the quiz titled <strong>{$activityTitle}</strong> before the due date.</p>
                        <p>Please encourage them to participate in future activities to support their academic progress.</p>
                        <br><p>Warm regards,</p><p><strong>DoE Team</strong></p>
                    ";
                    $mail->send();
                    $successCount++;
                } catch (Exception $e) {
                    $failures[] = $row['ParentEmail'];
                }
            }

            // Update LastFeedbackSent
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



                // 6. Feedback emails to parents for offline (class-based) non-submissions or absences.
        case 'offline_feedback':
            $activityId = intval($_POST['activityId'] ?? 0);
            if (!$activityId) throw new Exception("Invalid activity ID.");

            //  Get offline activity info
            $stmt = $connect->prepare("SELECT ActivityName, MaxMarks FROM activities WHERE ActivityId = ?");
            $stmt->bind_param("i", $activityId);
            $stmt->execute();
            $activity = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            if (!$activity) throw new Exception("Offline activity not found.");

            $activityTitle = $activity['ActivityName'];
            $maxMarks = $activity['MaxMarks'];

            // Get learner IDs from form
            $notSubmittedIds = $_POST['notSubmittedIds'] ?? '';
            $notSubmittedIds = array_filter(array_map('intval', explode(',', $notSubmittedIds)));

            if (empty($notSubmittedIds)) {
                $_SESSION['error'] = "No learners found for offline feedback.";
                header("Location: " . ($_POST['redirect'] ?? 'classlist.php'));
                exit;
            }

            // Prepare query to get parent info + learner status
            $placeholders = implode(',', array_fill(0, count($notSubmittedIds), '?'));
            $types = str_repeat('i', count($notSubmittedIds));

            $query = "
                SELECT u.Id AS LearnerId, u.Name AS LearnerName, u.Surname AS LearnerSurname,
                       l.ParentName, l.ParentEmail,
                       lam.Attendance, lam.AttendanceReason,
                       lam.Submission, lam.SubmissionReason, lam.MarksObtained
                FROM users u
                JOIN learners l ON u.Id = l.LearnerId
                LEFT JOIN learneractivitymarks lam ON lam.LearnerId = u.Id AND lam.ActivityId = ?
                WHERE u.Id IN ($placeholders)
            ";

            // Merge types: one for activityId + all learnerIds
            $stmt = $connect->prepare($query);
            $stmt->bind_param('i' . $types, $activityId, ...$notSubmittedIds);
            $stmt->execute();
            $learnersToEmail = $stmt->get_result();
            $stmt->close();

            $successCount = 0;
            $failures = [];

            while ($row = $learnersToEmail->fetch_assoc()) {
                $learnerFullName = "{$row['LearnerName']} {$row['LearnerSurname']}";
                $attendance = $row['Attendance'] ?? 'N/A';
                $attendanceReason = $row['AttendanceReason'] ?? '-';
                $submission = $row['Submission'] ?? 'N/A';
                $submissionReason = $row['SubmissionReason'] ?? '-';
                $marks = $row['MarksObtained'] !== null ? "{$row['MarksObtained']} / {$maxMarks}" : 'Not graded';

                try {
                    $mail = initMailer();
                    $mail->addAddress($row['ParentEmail'], $row['ParentName']);
                    $mail->Subject = "Feedback: {$learnerFullName} - Offline Activity Update";

                    $mail->Body = "
                        <p>Dear {$row['ParentName']},</p>
                        <p>This is an update regarding your child <strong>{$learnerFullName}</strong> for the offline activity <strong>{$activityTitle}</strong>.</p>
                        <table border='1' cellpadding='6' cellspacing='0' style='border-collapse:collapse;'>
                            <tr><td><strong>Attendance:</strong></td><td>{$attendance}</td></tr>
                            <tr><td><strong>Reason (if absent):</strong></td><td>{$attendanceReason}</td></tr>
                            <tr><td><strong>Submission:</strong></td><td>{$submission}</td></tr>
                            <tr><td><strong>Reason (if not submitted):</strong></td><td>{$submissionReason}</td></tr>
                            <tr><td><strong>Marks:</strong></td><td>{$marks}</td></tr>
                        </table>
                        <p>Please discuss this with your child to ensure they keep up with future class activities.</p>
                        <br><p>Kind regards,</p><p><strong>DoE Team</strong></p>
                    ";

                    $mail->send();
                    $successCount++;
                } catch (Exception $e) {
                    $failures[] = $row['ParentEmail'];
                }
            }

            $_SESSION['success'] = "$successCount offline feedback email(s) sent.";
            if (!empty($failures)) {
                $_SESSION['error'] = count($failures) . " failed: " . implode(', ', $failures);
            }

            // --- Handle redirect logic for offline feedback ---
            $redirect = $_POST['redirect'] 
                ?? $_GET['redirect'] 
                ?? "classhandler.php?aid={$activityId}";

            header("Location: $redirect");
            exit();
        break;





        default:
            throw new Exception("Unknown email action.");
    }

} catch(Exception $e) {
    $_SESSION['error'] = $e->getMessage();
}

header("Location: $redirect");
exit();
