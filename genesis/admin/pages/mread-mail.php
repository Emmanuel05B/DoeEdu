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
  <!-- Left side column. contains the logo and sidebar -->
 
 <?php include("../adminpartials/mainsidebar.php") ?>;  
 

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

  <?php
  include('../../partials/connect.php');
  $messageNo = $_GET['id'];  //for message number

  $sql = "SELECT * FROM pmessages WHERE No = $messageNo" ;
  $results = $connect->query($sql);
  $final = $results->fetch_assoc();  
  
   
      if ($messageNo) {  //setting the message clicked as read
        $stmt = $connect->prepare("UPDATE pmessages SET IsOpened = 1 WHERE No = ?");
        $stmt->bind_param("i", $messageNo);
        $stmt->execute();
        $stmt->close();
               
      }else{
        echo 'error. line 34';
      }

      
?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Read Mail
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Mailbox</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          <a href="mcompose2.php" class="btn btn-primary btn-block margin-bottom">Compose</a>

          <!-- ==============folder===================== -->
          <?php include("inb.php") ;?> 

          <!-- =================================== -->
         
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Read Mail</h3>

              <div class="box-tools pull-right">
                <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Previous"><i class="fa fa-chevron-left"></i></a>
                <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Next"><i class="fa fa-chevron-right"></i></a>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <div class="mailbox-read-info">
                <h3><?php echo $final['Subject'];?></h3>
                <h5>From: <?php echo $final['SenderName'];?>
                  <span class="mailbox-read-time pull-right"><?php echo date('Y-m-d H:i:s');?></span></h5>
              </div>
              <!-- /.mailbox-read-info -->
              <div class="mailbox-controls with-border text-center">
               
              </div>
              <!-- /.mailbox-controls -->
              <div class="mailbox-read-message">
                
                <p><?php echo $final['Message'];?></p>

              </div>
              <!-- /.mailbox-read-message -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              
            </div>
            <!-- /.box-footer -->
            <div class="box-footer">
              <div class="pull-right">
                <td><p><a class="btn btn-default" href="mcompose.php?id=<?php echo $final['No'] ?>"> <i class="fa fa-reply"></i>Reply</a></p></td>
              </div>


            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /. box --->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>

  <!-- Control Sidebar -->
 
  <!-- /.control-sidebar -->

  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
 
<?php include("../adminpartials/queries.php"); ?>
  <script src="../dist/js/demo.js"></script>

<!-- jQuery 3 -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Slimscroll -->
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
</body>
</html>
