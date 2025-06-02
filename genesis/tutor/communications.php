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
  margin-left: 50px;
  margin-right: 50px;
}
.pos {
  margin-top: 50px;
  margin-left: 10px;
  margin-right: 10px;
}

* {
  box-sizing: border-box;
}

/* Create two equal columns that floats next to each other */
.column {
  float: left;
  width: 50%;
  padding: 10px;
 
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}

/* Responsive layout - makes the two columns stack on top of each other instead of next to each other */
@media screen and (max-width: 600px) {
  .column {
    width: 100%;
  }
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
            <!-- ./col 555555555555555555555-->
  <section class="content">
    <div class="container-fluid">
          
    <?php
  include('../partials/connect.php');
  $message = $_GET['id'];  //for message number

  $sql = "SELECT * FROM parentreply WHERE No = $message" ;
  $results = $connect->query($sql);
  $final = $results->fetch_assoc();           
      
?>
        
<div class="row">
      <div class="column" style="background-color:white;">
    
          <div class="box box-info">
            
            <div class="box-header">
              <i class="fa fa-envelope"></i>
              <h3 class="box-title">Message Recieved</h3>
            </div>

            <div class="box-body">
   
              <form ><br><br>
                <div class="form-group">
                  <input type="email" class="form-control" name="emailto" readonly value="Sender Name: <?php echo $final['Name'];?>">
                </div>
                <div class="form-group">
                  <input type="text" class="form-control" name="subject" readonly value="Sender Email: <?php echo $final['Email'];?>">
                </div>
                <div class="form-group">
                  <input type="text" class="form-control" name="subject" readonly value="Subject: <?php echo $final['Subject'];?>">
                </div>
                <div>
                  <textarea name="message" readonly  style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?php echo $final['Message'];?></textarea>
                </div>

              </form>
            </div>
    
          </div>
      </div>


    <div class="column" style="background-color:white;">
    
      <!-- quick email widget -->
          <div class="box box-info">
            <div class="box-header">
              <i class="fa fa-envelope"></i>
              <h3 class="box-title">Quick Reply</h3>
            </div>

            <div class="box-body">
           
              <form action="mailhandler.php" method="post">
                <div class="form-group">
                  <input type="email" class="form-control" name="emailto" placeholder="Email to:">
                </div>
                <div class="form-group">
                  <input type="text" class="form-control" name="subject" placeholder="Subject">
                </div>
                <div>
                  <textarea class="textarea" name="message" placeholder="Message"
                            style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                </div>

                <div style="text-align: center;">
                <input style="width: 100px;" type="submit" value="Submit" name="btnsend">
                </div> 
              </form>
            </div>
    
          </div>
   </div>
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


