<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Password Reset</title>
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="Images/Favi.png">
    <link rel="icon" type="image/png" sizes="16x16" href="Images/Favi.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="./fontawesome-free-6.4.0-web/fontawesome-free-6.4.0-web/css/all.css">
    <link rel="stylesheet" type="text/css" href="Partials/style.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container d-flex flex-column">
    <div class="row align-items-center justify-content-center min-vh-100">
        <div class="col-12 col-md-8 col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="mb-4 text-center">
                        <h5>Forgot Password?</h5>
                    </div>
                    <?php
                    if (isset($_SESSION['error_message'])) {
                        echo '<div class="alert alert-danger rounded-pill text-center" style="border-color: #FF5733; height: 70px;">';
                        echo '<p>' . $_SESSION['error_message'] . '</p>';
                        echo '</div>';
                        unset($_SESSION['error_message']);
                    }
                    ?>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="email">Enter your email address and we will send you a code to reset your password.</label>
                        </div>
                        <div class="mb-3 pt-3">
                            <input type="email" id="email" class="form-control" name="email"
                                   placeholder="Enter Your Email" required="">
                        </div>
                        <div class="d-grid gap-2 mt-3">
                            <input type="submit" name="Submit" value="Reset Password" class="btn"
                                   style="background-color: blue; color:white;">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

<?php
include '../../partials/Connect.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1); // set to 0 in production
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../../../vendor/autoload.php';

if (isset($_POST['Submit'])) {
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

    $stmt = $connect->prepare("SELECT Id, Email, Surname FROM users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($id, $email, $surname);
    $stmt->fetch();
    $stmt->close();

    if ($id) {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomCode = '';
        for ($i = 0; $i < 6; $i++) {
            $randomCode .= $characters[rand(0, strlen($characters) - 1)];
        }
        $hashedResetCode = password_hash($randomCode, PASSWORD_BCRYPT);
        $timestamp = date("Y-m-d H:i:s");
        $updateQuery = "UPDATE users SET ResetCode = '$hashedResetCode', ResetTimestamp = '$timestamp' WHERE Email = '$email'";

        if (mysqli_query($connect, $updateQuery)) {
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'thedistributorsofedu@gmail.com'; // DoE sender email
                $mail->Password = 'bxuxtdgfhjkfghjnej'; // App-specific password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;

                $mail->setFrom('thedistributorsofedu@gmail.com', 'Distributors of Education');
                $mail->addAddress($email, $surname);
                $mail->addReplyTo('thedistributorsofedu@gmail.com', 'Distributors of Education');

                $mail->isHTML(true);
                $mail->Subject = 'Your Password Reset Code - Distributors of Education';
                $mail->Body = "
                    <p>Dear Mr/Ms $surname,</p>

                    <p>We received a request to reset your password on the Distributors of Education system.</p>

                    <p><strong>Your password reset code is:</strong></p>
                    <h2 style='color: #007BFF;'>$randomCode</h2>

                    <p>Please use this code on the password reset page. If you did not request this, feel free to ignore this message.</p>

                    <br>
                    <p>Best regards,</p>
                    <p><strong>DoE Team</strong></p>
                    <p>Email: info@doeconnect.org.za</p>
                ";

                $mail->send();
                $_SESSION['reset_message'] = "A reset code has been sent to your email address. Please check your inbox.";
                header('Location: reset.php');
                exit;
            } catch (Exception $e) {
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            }
        } else {
            echo "Error updating reset code: " . mysqli_error($connect);
        }
    } else {
        $_SESSION['error_message'] = "User not found or not verified.";
        header('Location: forgotpassword.php');
        exit;
    }
}

mysqli_close($connect);
?>

</body>
</html>
