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
      width: 70%;
      max-width: 500px;
      display: flex;
      overflow: hidden;
      flex-direction: row;
    }

    .image-column {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .image-column img {
      width: 200px;
      height: auto;
      max-height: 200px;
      object-fit: contain;
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
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 12px;
      background-color: #f9f9f9;
      box-sizing: border-box;
    }

    input[type="text"]:focus, input[type="password"]:focus {
      border-color: #007bff;
      outline: none;
    }

    .loginbtn {
      width: 100%;
      padding: 10px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 12px;
      cursor: pointer;
    }

    .loginbtn:hover {
      background-color: #0056b3;
    }

    .cancelbtn:hover {
      background-color: #c4c4c4;
    }

    .psw {
      float: right;
      font-size: 13px;
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

if(isset($_POST['login'])){
    include('../../partials/connect.php');

    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    $errors = [];

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = '<span class="error-message">Invalid Email Address.</span>';
    }
    if (empty($password)) {
        $errors[] = '<span class="error-message">Password is required.</span>';
    }

    if (empty($errors)) {
        $sql = "SELECT Id, Email, UserPassword, UserType FROM users WHERE Email = ?";

        if ($stmt = $connect->prepare($sql)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();

            if (!empty($user)) {
                if (password_verify($password, $user['UserPassword'])) {
                    $_SESSION['user_id'] = $user['Id'];
                    $_SESSION['UserType'] = $user['UserType'];
                    $_SESSION['email'] = $user['Email'];

                    switch ($user['UserType']) {
                        case 0: // Admin
                            header('Location: ../../admin/pages/adminindex.php');
                            break;
                        case 1: // Tutor
                            header('Location: ../../tutor/tutorindex.php');
                            break;
                        case 2: // Learner
                            header('Location: ../../learner/learnerindex.php');
                            break;
                        default:
                            $_SESSION['error_message'] = '<span class="error-message">Invalid user role.</span>';
                            header('Location: login.php');
                            break;
                    }
                    exit;
                } else {
                    $_SESSION['error_message'] = '<span class="error-message">Invalid password.</span>';
                    header('Location: login.php');
                    exit;
                }
            } else {
                $_SESSION['error_message'] = '<span class="error-message">Email does not exist.</span>';
                header('Location: login.php');
                exit;
            }
        } else {
            $_SESSION['error_message'] = '<span class="error-message">System error. Please try again later.</span>';
            header('Location: login.php');
            exit;
        }
    } else {
        $_SESSION['error_message'] = implode('<br>', $errors);
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
          <label for="email"><b>Email</b></label>
          <input type="text" placeholder="Enter Email" id="email" name="email" required>
        </div>

        <div class="container">
          <label for="password"><b>Password</b></label>
          <input type="password" placeholder="Enter Password" id="password" name="password" required>
        </div>

        <div class="container">
          <button type="submit" class="loginbtn" name="login">Login</button>
          <label><input type="checkbox" checked="checked" name="remember"> Remember me</label>
        </div>
        <div style="text-align:center; margin: 15px 0; font-size: 13px;">
          Don't have an account? <a href="request_invite.php" style="color:#007bff; text-decoration:none;">Request an Invite</a>
        </div>

        <div class="container" style="background-color:#f1f1f1">
          <button type="button" class="cancelbtn">Cancel</button>
          <span class="psw">Reset <a href="forgotpassword.php">password?</a></span>
        </div>
      </form>
    </div>
  </div>
</div>

</body>
</html>
