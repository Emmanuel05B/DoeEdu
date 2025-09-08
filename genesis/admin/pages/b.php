for handling custom emails to single person.
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









handler for sending an invite

<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../partials/connect.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require __DIR__ . '/../../../vendor/autoload.php';

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
    echo '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: "error",
                title: "Token Creation Failed",
                text: "Could not create invite token.",
                confirmButtonText: "OK"
            }).then(() => {
                window.location.href = "manage_inviterequests.php";
            });
        </script>
    </body>
    </html>';
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
        $mail->Password = 'xx x x x x x x x x x'; // Consider moving this to a secure config
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
    // Mark as accepted/recieved
    $updateStmt = $connect->prepare("UPDATE inviterequests SET IsAccepted = 1 WHERE id = ?");
    $updateStmt->bind_param("i", $invite_id);
    $updateStmt->execute();
    $updateStmt->close();

    echo '

                icon: "success",
                title: "Invite Sent",
                text: "An invitation link has been sent to ' . htmlspecialchars($request["email"]) . '",
       
    </html>';
    exit;
} else {
  
            }).then(() => {
                window.location.href = "manage_inviterequests.php";
            });
   
    exit;
}




for sending reminders

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
        $mail->Password = 'xxx x x x xx x x ';
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
