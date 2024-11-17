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

.postiontag {
  margin-top: 20px;
  margin-bottom: 100px;
  margin-right: 100px;
  margin-left: 100px;
  background-color: white;
}
.row {
  
  background-color: white;
}
</style>

<body class="hold-transition skin-blue sidebar-mini">


<div class="wrapper">

  <?php include("adminpartials/header.php") ?>;

  <!-- Left side column. contains the logo and sidebar -->
 
 <?php include("adminpartials/mainsidebar.php") ?>;

 <div class="postiontag"> <!-- control css from here -->
 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    
   <!-- Small boxes (Stat box) -->
     <div class="row">
      
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
            <p> add </p>
              <h3>Learner</h3>
            
            </div>
            <a href="addlearner.php" >
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            </a>
          </div>
        </div>

        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
            <p> add </p>
              <h3>Teacher</h3>
              
            </div>
            <a href="addteacher.php" >
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            </a>
          </div>
        </div>

         <!-- ./col -->
         <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
            <p> add </p>
              <h3>Parent</h3>
             
            </div>
            <a href="addmaneger.php" >
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            </a>
          </div>
        </div>

        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
            <p> add </p>
              <h3>SGB</h3>
             
            </div>
            <a href="addmaneger.php" >
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            </a>
          </div>
        </div>

       

      </div>
      <!-- Small boxes (Stat box)22222222222222 -->
     <div class="row">
      
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="inner">
          <p> Update </p>
            <h3>Learner</h3>
          
          </div>
          <a href="addlearner.php" >
          <div class="icon">
            <i class="ion ion-person-add"></i>
          </div>
          </a>
        </div>
      </div>

      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="inner">
          <p> Update </p>
            <h3>Teacher</h3>
            
          </div>
          <a href="addteacher.php" >
          <div class="icon">
            <i class="ion ion-person-add"></i>
          </div>
          </a>
        </div>
      </div>

       <!-- ./col -->
       <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="inner">
          <p> Update </p>
            <h3>Parent</h3>
           
          </div>
          <a href="addmaneger.php" >
          <div class="icon">
            <i class="ion ion-person-add"></i>
          </div>
          </a>
        </div>
      </div>

      <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
            <p> Update </p>
              <h3>SGB</h3>
             
            </div>
            <a href="addmaneger.php" >
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            </a>
          </div>
        </div>

    </div>

    <!-- Small boxes (Stat box)3333333333333 -->
    <div class="row">
      
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="inner">
          <p> Remove </p>
            <h3>Learner</h3>
          
          </div>
          <a href="addlearner.php" >
          <div class="icon">
            <i class="ion ion-person-add"></i>
          </div>
          </a>
        </div>
      </div>

      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="inner">
          <p> Remove </p>
            <h3>Teacher</h3>
            
          </div>
          <a href="addteacher.php" >
          <div class="icon">
            <i class="ion ion-person-add"></i>
          </div>
          </a>
        </div>
      </div>

       <!-- ./col -->
       <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="inner">
          <p> Remove </p>
            <h3>Parent</h3>
           
          </div>
          <a href="addmaneger.php" >
          <div class="icon">
            <i class="ion ion-person-add"></i>
          </div>
          </a>
        </div>
      </div>


      <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
            <p> Remove </p>
              <h3>SGB</h3>
             
            </div>
            <a href="addmaneger.php" >
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            </a>
          </div>
        </div>

    </div> <!-- 3333333333333333 -->
  
</div>
<!-- ./wrapper -->
</div><!-- postiontag -->


<?php include("adminpartials/queries.php") ?>;
<script src="dist/js/demo.js"></script>
</body>
</html>

