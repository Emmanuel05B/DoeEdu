<?php
include_once(__DIR__ . "/../../partials/paths.php"); 

include_once(BASE_PATH . "/partials/session_init.php"); 

include_once(BASE_PATH . "/partials/connect.php"); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Forgot Password</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #e8eff1;
      margin: 0;
      padding: 0;
    }

    .login-container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-box {
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
      width: 70%;
      max-width: 450px;
      overflow: hidden;
      padding: 30px;
      text-align: center;
    }

    .login-box img {
      width: 170px;
      height: auto;
      margin-bottom: 5px;
      max-height: 200px;
    }

    .login-box h2 {
      font-size: 24px;
      color: #333;
      margin-bottom: 8px;
    }

    .login-box p {
      font-size: 14px;
      color: #555;
      margin-bottom: 20px;
    }

    .container {
      margin-bottom: 15px;
      text-align: left;
    }

    input[type="email"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 14px;
      background-color: #f9f9f9;
      box-sizing: border-box;
    }

    input[type="email"]:focus {
      border-color: #007bff;
      outline: none;
    }

    .loginbtn {
      width: 100%;
      padding: 12px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 14px;
      cursor: pointer;
    }

    .loginbtn:hover {
      background-color: #0056b3;
    }

    .error-message {
      color: red;
      font-weight: bold;
      text-align: center;
      margin-bottom: 15px;
    }

    .success-message {
      color: green;
      font-weight: bold;
      text-align: center;
      margin-bottom: 15px;
    }
    .log {
      float: right;
      font-size: 13px;
    }
    
  </style>
</head>
<body>

<div class="login-container">
  <div class="login-box">
    <!-- Image above form -->
    <img src="../../uploads/ProfilePictures/doep.png" alt="Forgot Password">

    <h2>Forgot Password?</h2>
    <p>Enter your email address and we will send you a code to reset your password.</p>

    <?php
    if (isset($_SESSION['error_message'])) {
        echo '<div class="error-message">' . $_SESSION['error_message'] . '</div>';
        unset($_SESSION['error_message']);
    }
    if (isset($_SESSION['reset_message'])) {
        echo '<div class="success-message">' . $_SESSION['reset_message'] . '</div>';
        unset($_SESSION['reset_message']);
    }
    ?>

    <form method="POST" action="">
      <div class="container">
        <input type="email" id="email" name="email" maxlength="100" placeholder="Enter Your Email" required>
      </div>
      <div class="container">
        <input type="submit" name="Submit" value="Reset Password" class="loginbtn">
      </div>

        <div style="text-align: center; margin-top: 10px;">
          <label style="font-size: 13px;">
            Back to  
            <a href="login.php" class="back-link">Login</a>
          </label>
        </div>
      
    </form>
  </div>
</div>

<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../../../vendor/autoload.php';

// --- LOAD .env VARIABLES ---
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();

if (isset($_POST['Submit'])) {
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

    $stmt = $connect->prepare("SELECT Id, Email, Surname, Gender FROM users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($id, $email, $surname, $title);
    $stmt->fetch();
    $stmt->close();

    if ($id) {
        // Generate 6-character reset code
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomCode = '';
        for ($i = 0; $i < 6; $i++) {
            $randomCode .= $characters[rand(0, strlen($characters) - 1)];
        }

        // Begin transaction
        $connect->begin_transaction();

        try {
            // 1. Update reset code in DB
            $hashedResetCode = password_hash($randomCode, PASSWORD_BCRYPT);
            $timestamp = date("Y-m-d H:i:s");
            $updateQuery = "UPDATE users SET ResetCode = ?, ResetTimestamp = ? WHERE Email = ?";
            $stmt = $connect->prepare($updateQuery);
            $stmt->bind_param("sss", $hashedResetCode, $timestamp, $email);

            if (!$stmt->execute()) {
                throw new Exception("Error updating reset code in database.");
            }

            // 2. Send email
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['EMAIL_ADDRESS'];
            $mail->Password = $_ENV['EMAIL_APP_PASSWORD']; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom($_ENV['EMAIL_ADDRESS'], 'Distributors of Education');
            $mail->addAddress($email, $surname);
            $mail->addReplyTo($_ENV['EMAIL_ADDRESS'], 'Distributors of Education');

            $mail->isHTML(true);
            $mail->Subject = 'Your Password Reset Code - Distributors of Education';
            $mail->Body = "
                <p>Dear $title $surname,</p>
                <p>We received a request to reset your password on the Distributors of Education system.</p>
                <p><strong>Your password reset code is:</strong></p>
                <h2 style='color: #007BFF;'>$randomCode</h2>
                <p>Please use this code on the password reset page. If you did not request this, feel free to ignore this message.</p>
                <p>_</p>
                <p>Best regards,</p>
                <p><strong>DoE Team</strong></p>
                <p>Email: thedistributorsofedu@gmail.com</p>
            ";

            $mail->send();

            // 3. Commit transaction
            $connect->commit();

            $_SESSION['reset_message'] = "If an account exists, a Reset Code has been sent to it.";
            header("Location: reset.php");
            exit;

        } catch (Exception $e) {
            // Rollback if anything fails
            $connect->rollback();
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: forgotpassword.php');
            exit;
        }

    } else {
      

        // After sending the reset code email (or even if email not found)
        $_SESSION['reset_message'] = "If an account exists, a Reset Code has been sent to it.";
        header("Location: reset.php");
        exit;

    }
}

$connect->close();
?>


</body>
</html>
