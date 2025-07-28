<?php
session_start();

include '../partials/Connect.php';
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Password Reset</title>
    <meta charset="utf-8">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="Images/Logo3.png">
  <link rel="icon" type="image/png" sizes="16x16" href="Images/Logo3.png">
    <link rel="manifest" href="/site.webmanifest">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="./fontawesome-free-6.4.0-web\fontawesome-free-6.4.0-web\css\all.css">
    <link rel="stylesheet" type="text/css" href="Partials\style.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    html,
    body {
        height: 100%;
    }

    body {
        display: -ms-flexbox;
        display: -webkit-box;
        display: flex;
        -ms-flex-align: center;
        -ms-flex-pack: center;
        -webkit-box-align: center;
        align-items: center;
        -webkit-box-pack: center;
        justify-content: center;
        background-color: #f5f5f5;
    }

    form {
        padding-top: 10px;
        font-size: 14px;
        margin-top: 30px;
    }

    .card-title {
        font-weight: 300;
    }

    .btn {
        font-size: 14px;
        margin-top: 20px;
    }

    .login-form {
        width: 320px;
        margin: 20px;
    }

    .sign-up {
        text-align: center;
        padding: 20px 0 0;
    }

    span {
        font-size: 14px;
    }
</style>

<body>
<?php
if (isset($_POST["Submit"])) {
    $resetCode = $_POST["reset_code"];
    $newPassword = $_POST["new_password"];
    $confirmPassword = $_POST["confirm_password"];

    if ($newPassword !== $confirmPassword) {
        // Passwords do not match, display an error message using SweetAlert
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Password Mismatch',
                text: 'The new passwords do not match.',
                confirmButtonText: 'OK'
            }).then(function() {
                window.location = 'reset.php';
            });
        </script>";
        exit;
    }
    // Hash the new password
      $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    // Retrieve the previous hashed password from  database
    $sql = "SELECT UserPassword FROM users WHERE ResetCode = '$resetCode'";
    $result = $connect->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $previousHashedPassword = $row["UserPassword"];

        // Compare the new password with the previous hashed password
        if (password_verify($hashedPassword, $previousHashedPassword)) {
            
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Password Reuse',
                    text: 'Please choose a different password from your previous one.',
                    confirmButtonText: 'OK'
                }).then(function() {
                    window.location = 'reset.php'; 
                });
            </script>";
            exit;
        }
    }
 

    $updateSql = "UPDATE users SET UserPassword = '$hashedPassword' WHERE ResetCode = '$resetCode'";
    if ($connect->query($updateSql) === TRUE) {
      
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Password Reset Successful',
                text: 'Your password has been reset.',
                confirmButtonText: 'OK'
            }).then(function() {
                window.location = 'login.php'; 
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Password reset failed. Please try again later.',
                confirmButtonText: 'OK'
            }).then(function() {
                window.location = 'reset.php'; 
            });
        </script>";
    }

    $connect->close();
}
?>

    <div class="container d-flex flex-column">
        <div class="row align-items-center justify-content-center
          min-vh-100">
            <div class="col-12 col-md-8 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="mb-4">
                            <h5>Reset Password</h5>
                    <?php
                    if (isset($_SESSION['reset_message'])) {
                        echo '<div class="alert alert-success rounded-pill text-center" style="border-color: #4CAF50; height: 90px;">';
                        echo '<p style="">' . $_SESSION['reset_message'] . '</p>';
                        echo '</div>';
                        unset($_SESSION['reset_message']);
                    }
                    ?>
                        </div>
                        <form method="POST" action="reset.php">
                            <div class="mb-3">
                                <label for="reset_code" class="form-label">Reset Code</label>
                                <input type="text" id="reset_code" class="form-control" name="reset_code" placeholder="Enter Reset Code" required="">
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" id="new_password" class="form-control" name="new_password" placeholder="Enter New Password" required="">
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" id="confirm_password" class="form-control" name="confirm_password" placeholder="Confirm New Password" required="">
                            </div>
                            <div class="d-grid gap-2 mt-3">
                                <input type="submit" name="Submit" value="Reset Password" class="btn" style="background-color: blue; color:white;">
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>