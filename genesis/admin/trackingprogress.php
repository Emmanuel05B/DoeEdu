<!DOCTYPE html>
<html>

<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}
?>

<?php include("adminpartials/head.php"); ?>

<style>

.content {
  background-color: white;
  
  margin-top: 20px;
  margin-left: 100px;
  margin-right: 100px;
}
.pos {
  margin-top: 50px;
  margin-left: 10px;
  margin-right: 10px;
}

</style>


<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include("adminpartials/header.php") ?>;

  <!-- Left side column. contains the logo and sidebar -->
 
 <?php include("adminpartials/mainsidebar.php") ?>;

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
        <!-- ./col -->
            <!-- ./col 555555555555555555555-->
  <section class="content">
    <div class="container-fluid">
          
         <div class="oder">
            <div class="card">
              
              <div class="card-body">
                <div class="tab-content">
                  <!-- /.tab-pane -->
                  <div class="active tab-pane" id="addprofile">
            
                    <div class="pos">

                       <div class="pos">    
          
                            <!-- ./col -->
                            <div class="pos">
                              <!-- small box -->
                              <a href="trackalllearners.php" >
                              <div class="small-box bg-aqua">
                                <div class="inner">
                                  <h4>Track learner Progress</h4>
                              </div>
                            </div>
                            <div class="pos">
                              <!-- small box -->
                              <a href="chartjs.php" >
                              <div class="small-box bg-aqua">
                                <div class="inner">
                                  <h4>Activity Analysis</h4>
                              </div>
                            </div>
                       

                    </div>
                    
                  </div>

    
            <!-- /.nav-tabs-custom -->
          </div>
 </div>
</section>

        <!-- ./col -->
      </div>
      
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->



<?php include("adminpartials/queries.php") ?>;
<script src="dist/js/demo.js"></script>
</body>
</html>


