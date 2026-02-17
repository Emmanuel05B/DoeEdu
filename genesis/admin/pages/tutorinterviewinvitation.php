<?php
include_once(__DIR__ . "/../../partials/paths.php"); 
include_once(BASE_PATH . "/partials/session_init.php"); 
include_once(BASE_PATH . "/partials/connect.php"); 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../../vendor/autoload.php';

// Validate POST data
if (!isset($_POST['id'], $_POST['interview_date'], $_POST['interview_start_time'], $_POST['interview_end_time'])) {
    $_SESSION['error'] = "Invalid request.";
    header("Location: tutorapplications.php");
    exit();
}

$appId = intval($_POST['id']);
$date = $_POST['interview_date'];
$startTime = $_POST['interview_start_time'];
$endTime = $_POST['interview_end_time'];

// Validate that end time is after start time
if (strtotime($endTime) <= strtotime($startTime)) {
    $_SESSION['error'] = "End time must be after start time.";
    header("Location: tutorapplications.php");
    exit();
}

// Check for scheduling conflicts
/* 
$sqlCheck = "SELECT * FROM tutorinvitations 
             WHERE InterviewDate = ? 
             AND ((StartTime <= ? AND EndTime > ?) OR (StartTime < ? AND EndTime >= ?))";
$stmtCheck = $connect->prepare($sqlCheck);
$stmtCheck->bind_param('sssss', $date, $startTime, $startTime, $endTime, $endTime);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();  */

$sqlCheck = "
    SELECT * FROM (
        SELECT 'Marketer' AS Source, InterviewDate, StartTime, EndTime FROM marketerinvitations
        UNION ALL
        SELECT 'Tutor' AS Source, InterviewDate, StartTime, EndTime FROM tutorinvitations
    ) AS combined
    WHERE InterviewDate = ?
      AND ((StartTime <= ? AND EndTime > ?) OR (StartTime < ? AND EndTime >= ?))
";

$stmtCheck = $connect->prepare($sqlCheck);
$stmtCheck->bind_param('sssss', $date, $startTime, $startTime, $endTime, $endTime);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();

if ($resultCheck->num_rows > 0) {
    $conflict = $resultCheck->fetch_assoc();
    $_SESSION['error'] = "An applicant has already been scheduled during this time in " . $conflict['Source'] . " interviews.";
    header("Location: tutorapplications.php");
    exit();
}


// Fetch applicant info
$sqlApplicant = "SELECT Name, Surname, Email FROM tutorapplications WHERE Id = ?";
$stmtApplicant = $connect->prepare($sqlApplicant);
$stmtApplicant->bind_param('i', $appId);
$stmtApplicant->execute();
$resultApplicant = $stmtApplicant->get_result();
$applicant = $resultApplicant->fetch_assoc();

if (!$applicant) {
    $_SESSION['error'] = "Applicant not found.";
    header("Location: tutorapplications.php");
    exit();
}

$name = htmlspecialchars($applicant['Name']);
$surname = htmlspecialchars($applicant['Surname']);
$email = htmlspecialchars($applicant['Email']);

try {
    //  Insert invitation first
    $sqlInsert = "INSERT INTO tutorinvitations (TutorApplicationId, InterviewDate, StartTime, EndTime, SentAt) 
                  VALUES (?, ?, ?, ?, NOW())";
    $stmtInsert = $connect->prepare($sqlInsert);
    $stmtInsert->bind_param('isss', $appId, $date, $startTime, $endTime);
    $stmtInsert->execute();

    // Get the new invitation ID
    $invitationId = $connect->insert_id;

    // Generate a secure token and update the row
    $token = bin2hex(random_bytes(16));
    $sqlToken = "UPDATE tutorinvitations SET ConfirmationToken = ? WHERE Id = ?";
    $stmtToken = $connect->prepare($sqlToken);
    $stmtToken->bind_param('si', $token, $invitationId);
    $stmtToken->execute();

    // Build confirmation URL
    $confirmUrl = APPLICATIONS_URL . "/confirm_interview.php?token=$token";

            
    // Initialize PHPMailer
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = $_ENV['EMAIL_HOST'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $_ENV['EMAIL_ADDRESS'];  
    $mail->Password   = $_ENV['EMAIL_APP_PASSWORD']; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = $_ENV['EMAIL_PORT'];
    $mail->setFrom($_ENV['EMAIL_ADDRESS'], 'Distributors of Education');
    $mail->addAddress($email, $surname);
    $mail->addReplyTo($_ENV['EMAIL_ADDRESS'], 'Distributors of Education');
    $mail->isHTML(true);

    $mail->Subject = 'Interview Invitation - DoE Tutoring';
    $mail->Body = "
        <p>Dear $name $surname,</p>
        <p>We are pleased to invite you for an interview for the DoE Mathematics & Physical Sciences Tutor position.</p>
        <p><strong>Interview Date:</strong> $date<br>
        <strong>Start Time:</strong> $startTime<br>
        <strong>End Time:</strong> $endTime</p>
        
        <p>
            <strong>Please note that the interview meeting link will only be shared
            15 minutes before the interview, and only after you have confirmed your attendance.</strong>
        </p>
    
        <p>
            In preparation for the interview, we strongly encourage you to familiarise yourself with our organisation.
            Please visit our website: 
            <a href='https://doetutoring.com' target='_blank'>doetutoring.com</a>
        </p>
    
        <p>Please confirm your attendance by clicking the button below:</p>
        <p>
            <a href='$confirmUrl' 
               style='display:inline-block;
                      padding:10px 20px;
                      background-color:#007bff; 
                      color:#fff; 
                      text-decoration:none; 
                      border-radius:5px;
                      font-weight:bold;'>
                Confirm Attendance
            </a>
        </p>
        <br>
        <p>Best regards,<br>
        <strong>DoE Tutoring Team</strong><br>
        Email: info@doetutoring.com</p>
    ";

    if ($mail->send()) {
        // Update LastInviteSent
        $sqlUpdate = "UPDATE tutorapplications SET LastInviteSent = NOW() WHERE Id = ?";
        $stmtUpdate = $connect->prepare($sqlUpdate);
        $stmtUpdate->bind_param('i', $appId);
        $stmtUpdate->execute();

        $_SESSION['success'] = "Interview invite sent successfully to $name $surname.";
    } else {
        $_SESSION['error'] = "Failed to send email: " . $mail->ErrorInfo;
    }

} catch (Exception $e) {
    $_SESSION['error'] = "Mailer Error: " . $e->getMessage();
}

header("Location: tutorapplications.php");
exit();
