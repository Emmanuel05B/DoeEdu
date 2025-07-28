<!DOCTYPE html>
<html>

<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../../common/login.php");
  exit();
}
?>

<?php include("../adminpartials/head.php"); ?>


<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include("../adminpartials/header.php") ?>;
 
 <?php include("../adminpartials/mainsidebar.php") ?>;

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    
        
  
      </div>
      
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->



<?php include("../adminpartials/queries.php") ?>;
<script src="../dist/js/demo.js"></script>
</body>
</html>
