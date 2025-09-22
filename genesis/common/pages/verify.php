<!DOCTYPE html>
<html>

<?php
session_start();
include('../../partials/connect.php');
?>

<?php include("adminpartials/head.php"); ?>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>


<body class="hold-transition skin-blue sidebar-mini">

<?php
  if (isset($_GET['token']) && !empty($_GET['token'])) {
    $reportId = $_GET['token'];

    $stmt = $connect->prepare("SELECT LearnerId, DateReported FROM reportlinks WHERE ReportId = ?");
    $stmt->bind_param("i", $reportId);
    $stmt->execute();
    $stmt->bind_result($LearnerId, $DateReported);
    $stmt->fetch();
    $stmt->close();
    
    if ($LearnerId) {

      $stmt = $connect->prepare("UPDATE reportlinks SET IsOpened = 1 WHERE ReportId = ?");
      $stmt->bind_param("i", $reportId);
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
      $errors[] = 'could not Update the IsOpened to 1.';
    }
  }

  ?>

<div class="wrapper">

</div>


    <?php include("adminpartials/queries.php") ;?>
    <script src="dist/js/demo.js"></script>


  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>

