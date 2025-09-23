<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
        width: 90%;
        max-width: 500px;
        display: flex;
        overflow: hidden;
        flex-direction: row;
      }
    

    .login-box img {
      width: 170px;
        height: auto;
        max-height: 200px;
        object-fit: contain;
    }

      .image-column {
        display: flex;
        align-items: center;
        justify-content: center;
      }

    

      .form-column {
        flex: 1;
        padding: 30px;
      }

      .form-column h2 {
        text-align: center;
        font-size: 28px;
        color: #333;
        margin-bottom: 10px;
      }

      .container {
        padding: 8px 0;
      }

      input[type="text"], input[type="password"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
        background-color: #f9f9f9;
        box-sizing: border-box;
      }

      

      input[type="text"]:focus, input[type="password"]:focus {
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

      .cancelbtn:hover {
        background-color: #c4c4c4;
      }

      .error-message {
        color: red;
        font-weight: bold;
        text-align: center;
        margin-bottom: 10px;
      }

  </style>
</head>
<body>

<?php
session_start();
include('../../partials/connect.php');

if (isset($_POST['login'])) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    $genericError = '<span class="error-message">Email or password is incorrect.</span>';

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($password)) {
        $_SESSION['error_message'] = $genericError;
        header('Location: login.php');
        exit;
    }

    // Fetch user by email (include PermanentlyBlocked now)
    $stmt = $connect->prepare("SELECT Id, UserPassword, UserType, FailedAttempts, LastFailedAttempt, PermanentlyBlocked 
                               FROM users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!$user) {
        // Email doesn't exist: generic error
        $_SESSION['error_message'] = $genericError;
        header('Location: login.php');
        exit;
    }

    // Check if permanently blocked
    if ($user['PermanentlyBlocked']) {
    $_SESSION['error_message'] = '<span class="error-message">Your account has been blocked due to multiple failed attempts. Please reset your password to regain access.</span>';
    header('Location: reset.php');
    exit;
}


    // Check if temporarily locked
    $currentTime = time();
    $lockTime = 15 * 60; // 15 minutes
    $failedAttempts = (int)$user['FailedAttempts'];
    $lastFailed = $user['LastFailedAttempt'] ? strtotime($user['LastFailedAttempt']) : 0;

    if ($failedAttempts >= 5 && ($currentTime - $lastFailed) < $lockTime) {
        $_SESSION['error_message'] = $genericError;
        header('Location: login.php');
        exit;
    }

    // Reset failed attempts if lock expired
    if ($failedAttempts >= 5 && ($currentTime - $lastFailed) >= $lockTime) {
        $failedAttempts = 0;
        $updateStmt = $connect->prepare("UPDATE users SET FailedAttempts = 0 WHERE Id = ?");
        $updateStmt->bind_param("i", $user['Id']);
        $updateStmt->execute();
        $updateStmt->close();
    }

    // Verify password
    if (password_verify($password, $user['UserPassword'])) {
        // Success: reset failed attempts
        $updateStmt = $connect->prepare("UPDATE users SET FailedAttempts = 0, LastFailedAttempt = NULL WHERE Id = ?");
        $updateStmt->bind_param("i", $user['Id']);
        $updateStmt->execute();
        $updateStmt->close();

        // Set session and redirect
        $_SESSION['user_id'] = $user['Id'];
        $_SESSION['UserType'] = $user['UserType'];
        $_SESSION['email'] = $email;

        switch ($user['UserType']) {
            case 0: header('Location: ../../admin/pages/adminindex.php'); break;
            case 1: header('Location: ../../tutor/tutorindex.php'); break;
            case 2: header('Location: ../../learner/pages/learnerindex.php'); break;
            default: 
                $_SESSION['error_message'] = $genericError;
                header('Location: login.php'); 
                break;
        }
        exit;
    } else {
        // Wrong password: increment failed attempts
        $failedAttempts++;
        
        // If already locked before and they hit 5+ again within 15 mins → permanently block
        if ($failedAttempts >= 5 && ($currentTime - $lastFailed) < $lockTime) {
            $updateStmt = $connect->prepare("UPDATE users SET PermanentlyBlocked = 1 WHERE Id = ?");
            $updateStmt->bind_param("i", $user['Id']);
            $updateStmt->execute();
            $updateStmt->close();
        } else {
            $updateStmt = $connect->prepare("UPDATE users SET FailedAttempts = ?, LastFailedAttempt = NOW() WHERE Id = ?");
            $updateStmt->bind_param("ii", $failedAttempts, $user['Id']);
            $updateStmt->execute();
            $updateStmt->close();
        }

        $_SESSION['error_message'] = $genericError;
        header('Location: login.php');
        exit;
        
    }
}
?>





<!-- Login Container -->
<div class="login-container">
  <div class="login-box">
  <div class="form-column">

    <div class="image-column">
      <img src="../../admin/images/westtt.png" alt="Login Image">
    </div>
    
      <h2>Login</h2>

      <?php
      if (isset($_SESSION['error_message'])) {
          echo $_SESSION['error_message'];
          unset($_SESSION['error_message']);
      }
      ?>

      <!-- Login Form -->
      <form action="login.php" method="post">
        <div class="container">
          <input type="text" placeholder="Enter Email" id="email" name="email" maxlength="100" required>
        </div>

        <div class="container">
          <input type="password" placeholder="Enter Password" id="password" name="password" maxlength="250" required>
        </div>

        <div class="container">
          <button type="submit" class="loginbtn" name="login">Login</button>
        </div>

        <div style="text-align: center; margin-top: 10px;">
          <label style="font-size: 13px;">
            Reset 
            <a href="forgotpassword.php" style="color:#007bff; text-decoration:none; font-size: 13px;">
              password?
            </a>
          </label>
        </div>
        <div style="text-align: center; margin-top: 10px;">
          <label style="font-size: 13px;">
            Don't have an account? 
            <a href="request_invite.php" style="color:#007bff; text-decoration:none; font-size: 13px;">
              Request an Invite
            </a>
          </label>
        </div>

      </form>
    </div>
  </div>
</div>

</body>
</html>
