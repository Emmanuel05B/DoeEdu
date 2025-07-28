<!DOCTYPE html>
<html>
<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
  exit();
}
?>

<?php include(__DIR__ . "/../../common/partials/head.php"); ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include(__DIR__ . "/../../common/partials/header.php"); ?>
  <?php include(__DIR__ . "/../../common/partials/mainsidebar.php"); ?>

 <?php
                include(__DIR__ . "/../../partials/connect.php");

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
                      echo 'error. line 39';
                    }

                // Execute the query
                $result = $connect->query($sql);
                $reportrow = $result->fetch_assoc();

                $sql = "SELECT COUNT(*) as count FROM pmessages WHERE IsOpened = 0";
                // Execute the query
                $result = $connect->query($sql);
                $messagesrow = $result->fetch_assoc();
               
               ?>



  <!-- Content Wrapper. Contains page content e-->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Messages
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
          <a href="mmailbox.php" class="btn btn-primary btn-block margin-bottom">Back to Inbox</a>

          <!-- ==============folder===================== -->
          <?php include("inb.php") ;?> 

          <!-- =================================== -->
    
          <!-- /.box -->
        </div>



      <form action="messagehandler.php" method="post">  <!-- fake form -->
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Compose New Message</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <form action="messagehandler.php" method="post">
          
          <div class="form-group">
            <input type="email" class="form-control" name="emailto" value="<?php echo $final['TPEmail'];?>" required>
          </div>
          <input type="hidden" id="urlParams" name="reciverid" value="<?php echo $final['Id']; ?>">

          <div class="form-group">
            <input type="text" class="form-control" name="subject" placeholder="Subject" required>
          </div>
          <div>
            <textarea class="textarea" name="message" placeholder="Message"
              style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" required></textarea>
          </div>

          
          <div class="box-footer">
              <div class="pull-right">
                <button type="submit" value="Submit" name="btnsend" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>
              </div>
            </div>
          
        </form>
            </div>
          
            <!-- /.box-footer -->
          </div>
          <!-- /. box -->
        </div>
        </div><!-- fake form -->
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 

  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>
<script src="../../common/dist/js/demo.js"></script> 

<!-- jQuery 3 -->
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
<!-- iCheck -->
<script src="../plugins/iCheck/icheck.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Page Script -->
<script>
  $(function () {
    //Add text editor
    $("#compose-textarea").wysihtml5();
  });
</script>
</body>
</html>
