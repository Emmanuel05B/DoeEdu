<?php
session_start();
include '../../partials/Connect.php';

if (isset($_POST["Submit"])) {

    $resetCode = trim($_POST["reset_code"]);
    $newPassword = trim($_POST["new_password"]);
    $confirmPassword = trim($_POST["confirm_password"]);

    // Basic validation
    if ($newPassword !== $confirmPassword) {
        $_SESSION['error_message'] = "The new passwords do not match.";
        header('Location: reset.php');
        exit;
    }

    // Additional password rules
    $uppercase = preg_match('@[A-Z]@', $newPassword);
    $lowercase = preg_match('@[a-z]@', $newPassword);
    $number    = preg_match('@[0-9]@', $newPassword);
    $specialChars = preg_match('@[^\w]@', $newPassword);

    if(strlen($newPassword) < 8 || !$uppercase || !$lowercase || !$number || !$specialChars) {
        $_SESSION['error_message'] = "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.";
        header('Location: reset.php');
        exit;
    }

    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Start transaction
    $connect->begin_transaction();

    try {
        // 1. Find the user by verifying hashed ResetCode
        $stmt = $connect->prepare("SELECT Id, UserPassword, ResetCode FROM users WHERE ResetCode IS NOT NULL");
        $stmt->execute();
        $result = $stmt->get_result();

        $userFound = false;
        while ($row = $result->fetch_assoc()) {
            if (password_verify($resetCode, $row['ResetCode'])) {
                $userId = $row['Id'];
                $previousHashedPassword = $row['UserPassword'];
                $userFound = true;
                break;
            }
        }

        if (!$userFound) {
            throw new Exception("InvalidCode");
        }

        // 2. Prevent reuse of previous password
        if (password_verify($newPassword, $previousHashedPassword)) {
            throw new Exception("PasswordReuse");
        }

        // 3. Update password and clear reset code & timestamp
        $updateStmt = $connect->prepare(
            "UPDATE users SET UserPassword = ?, ResetCode = NULL, ResetTimestamp = NULL WHERE Id = ?"
        );
        $updateStmt->bind_param("si", $hashedPassword, $userId);

        if (!$updateStmt->execute()) {
            throw new Exception("UpdateFailed");
        }

        // 4. Commit transaction
        $connect->commit();

        // Set success message and stay on the same page
        $_SESSION['success_message'] = "Your password has been reset successfully. You can now to login page.";

    } catch (Exception $e) {
        $connect->rollback();

        if ($e->getMessage() === "PasswordReuse") {
            $_SESSION['error_message'] = "Please choose a different password from your previous one.";
        } elseif ($e->getMessage() === "InvalidCode") {
            $_SESSION['error_message'] = "Invalid reset code.";
        } else {
            $_SESSION['error_message'] = "Password reset failed. Please try again later.";
        }
    }

    $connect->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Reset Password</title>
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
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    width: 70%;
    max-width: 450px;
    overflow: hidden;
    padding: 30px;
    text-align: center;
  }

  .login-box img {
    width: 170px;
    height: auto;
    margin-bottom: 15px;
    max-height: 200px;
  }

  .login-box h2 {
    font-size: 24px;
    color: #333;
    margin-bottom: 10px;
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

  input[type="text"], input[type="password"], input[type="email"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
    background-color: #f9f9f9;
    box-sizing: border-box;
  }

  input:focus {
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

  .error-message, .success-message {
    font-weight: bold;
    text-align: center;
    margin-bottom: 15px;
  }

  .error-message { color: red; }
  .success-message { color: green; }

  .back-link:hover { text-decoration: underline; }
</style>
</head>
<body>
<div class="login-container">
  <div class="login-box">
    <img src="../../admin/images/westtt.png" alt="Reset Password">
    <h2>Reset Password</h2>
    <p>Enter your reset code and new password below.</p>

    <?php
    if (isset($_SESSION['error_message'])) {
        echo '<div class="error-message">' . $_SESSION['error_message'] . '</div>';
        unset($_SESSION['error_message']);
    }

    if (isset($_SESSION['success_message'])) {
        echo '<div class="success-message">' . $_SESSION['success_message'] . '</div>';
        unset($_SESSION['success_message']);
    }

    if (isset($_SESSION['reset_message'])) {
    echo '<div class="success-message">' . $_SESSION['reset_message'] . '</div>';
    unset($_SESSION['reset_message']); // remove so it only shows once
    }
    ?>


    <form method="POST" action="">
      <div class="container">
        <input type="text" name="reset_code" placeholder="Reset Code" required>
      </div>
      <div class="container">
        <input type="password" name="new_password" placeholder="New Password" required>
      </div>
      <div class="container">
        <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
      </div>
      <div class="container">
        <input type="submit" name="Submit" value="Reset Password" class="loginbtn">
      </div>
      <div style="text-align: center; margin-top: 10px;">
        <label style="font-size: 13px;">
          Back to <a href="login.php" class="back-link">Login</a>
        </label>
      </div>
    </form>
  </div>
</div>
</body>
</html>







