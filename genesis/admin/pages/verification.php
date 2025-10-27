<!DOCTYPE html>
<html>

<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");
include_once(COMMON_PATH . "/../partials/head.php"); 
?>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>


<body class="hold-transition skin-blue sidebar-mini">

<?php
  if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = $_GET['token'];
    $stmt = $connect->prepare("SELECT Id, RegistrationDate FROM users WHERE VerificationToken = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->bind_result($userId, $registrationDate);
    $stmt->fetch();
    $stmt->close();
    
    if ($userId) {
      $currentTime = time();
      $registrationTime = strtotime($registrationDate);
      $timeElapsed = $currentTime - $registrationTime;
      if ($timeElapsed <= 180) {
        $stmt = $connect->prepare("UPDATE users SET IsVerified = 1, VerificationToken = NULL WHERE Id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();
        echo '<script>
                  Swal.fire({
                      icon: "success",
                      title: "Email Successfully Verified",
                      text: "Your email has been verified. Proceed to Login and Reset Your Password.",
                      confirmButtonColor: "#3085d6",
                      confirmButtonText: "OK"
                  }).then((result) => {
                      if (result.isConfirmed) {
                          // Redirect or perform any other action as needed
                          window.location.href = "../common/login.php";
                      }
                  });
              </script>';
      
      } else {
        $stmt = $connect->prepare("DELETE FROM users WHERE Id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();

        $errors[] = 'The verification link has expired. Please register again.';
      }
    } else {
      $errors[] = 'Invalid verification token.';
    }
  }

  ?>

<div class="wrapper">

</div>


<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>


</body>
</html>

