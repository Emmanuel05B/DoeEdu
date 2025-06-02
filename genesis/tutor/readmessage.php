<!DOCTYPE html>
<html>

<?php
session_start();
include('../partials/connect.php');
?>

<?php include("adminpartials/head.php"); ?>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>


<body class="hold-transition skin-blue sidebar-mini">

<?php
  if (isset($_GET['id']) && !empty($_GET['id'])) {
    $messageNo = $_GET['id'];
    $stmt = $connect->prepare("SELECT No FROM pmessages WHERE No = ?");
    $stmt->bind_param("i", $messageNo);
    $stmt->execute();
    $stmt->bind_result($Number);
    $stmt->fetch();
    $stmt->close();
   
     
      if ($Number) {
        $stmt = $connect->prepare("UPDATE pmessages SET IsOpened = 1 WHERE No = ?");
        $stmt->bind_param("i", $Number);
        $stmt->execute();
        $stmt->close();

        $_SESSION['succes'] = '<span style="color: Green; font-weight: bold;">Message Read.</span>';

        header('Location: modal.php');
        
      }else{
        echo 'error. line 28';
      }
   
  }
//
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

