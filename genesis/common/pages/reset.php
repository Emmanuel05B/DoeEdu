<?php
session_start();
include '../../partials/Connect.php';
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="login-container">
  <div class="login-box">
    <img src="../../admin/images/westtt.png" alt="Reset Password">
    <h2>Reset Password</h2>
    <p>Enter your reset code and new password below.</p>

    <?php
    if (isset($_SESSION['reset_message'])) {
        echo '<div class="success-message">' . $_SESSION['reset_message'] . '</div>';
        unset($_SESSION['reset_message']);
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
            Back to  
            <a href="login.php" class="back-link">Login</a>
          </label>
        </div>
      
    </form>
  </div>
</div>

<?php
if (isset($_POST["Submit"])) {
    $resetCode = $_POST["reset_code"];
    $newPassword = $_POST["new_password"];
    $confirmPassword = $_POST["confirm_password"];

    if ($newPassword !== $confirmPassword) {
        echo "<script>
            Swal.fire({icon: 'error', title: 'Password Mismatch', text: 'The new passwords do not match.', confirmButtonText: 'OK'})
            .then(()=>{window.location='reset.php';});
        </script>";
        exit;
    }

    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $sql = "SELECT UserPassword FROM users WHERE ResetCode = '$resetCode'";
    $result = $connect->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $previousHashedPassword = $row["UserPassword"];
        if (password_verify($hashedPassword, $previousHashedPassword)) {
            echo "<script>
                Swal.fire({icon: 'error', title: 'Password Reuse', text: 'Choose a different password.', confirmButtonText: 'OK'})
                .then(()=>{window.location='reset.php';});
            </script>";
            exit;
        }
    }

    $updateSql = "UPDATE users SET UserPassword='$hashedPassword' WHERE ResetCode='$resetCode'";
    if ($connect->query($updateSql) === TRUE) {
        echo "<script>
            Swal.fire({icon:'success', title:'Password Reset Successful', text:'Your password has been reset.', confirmButtonText:'OK'})
            .then(()=>{window.location='login.php';});
        </script>";
    } else {
        echo "<script>
            Swal.fire({icon:'error', title:'Error', text:'Password reset failed. Try again later.', confirmButtonText:'OK'})
            .then(()=>{window.location='reset.php';});
        </script>";
    }

    $connect->close();
}
?>
</body>
</html>
