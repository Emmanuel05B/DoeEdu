<?php
session_start();
include('../partials/connect.php');

if (!isset($_GET['token'])) {
    die("Invalid verification link.");
}

$token = $_GET['token'];

// Prepare statement to fetch user by token
$stmt = $connect->prepare("SELECT Id, IsVerified FROM users WHERE VerificationToken = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Token not found or invalid
    ?>
    <!DOCTYPE html>
    <html>
    <head>
      <title>Verification Error</title>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
    <script>
    Swal.fire({
        icon: 'error',
        title: 'Invalid Link',
        text: 'This verification link is invalid or expired.',
        confirmButtonText: 'Go to Login'
    }).then(() => {
        window.location.href = '../common/login.php';
    });
    </script>
    </body>
    </html>
    <?php
    exit();
}

$user = $result->fetch_assoc();

if ($user['IsVerified']) {
    // Already verified
    ?>
    <!DOCTYPE html>
    <html>
    <head>
      <title>Already Verified</title>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
    <script>
    Swal.fire({
        icon: 'info',
        title: 'Already Verified',
        text: 'Your account was already verified. You will be redirected to the login page.',
        confirmButtonText: 'Go to Login'
    }).then(() => {
        window.location.href = '../common/login.php';
    });
    </script>
    </body>
    </html>
    <?php
    exit();
}

// If not verified, update verification status
$update = $connect->prepare("UPDATE users SET IsVerified = 1, VerificationToken = NULL WHERE Id = ?");
$update->bind_param("i", $user['Id']);
$update->execute();
$update->close();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Account Verified</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<script>
Swal.fire({
    icon: 'success',
    title: 'Account Verified',
    text: 'You are now officially part of the student community.',
    confirmButtonText: 'Go to Login'
}).then(() => {
    window.location.href = '../common/login.php';
});
</script>

</body>
</html>
