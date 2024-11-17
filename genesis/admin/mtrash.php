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


<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

<?php include("adminpartials/header.php") ?>;
  <!-- Left side column. contains the logo and sidebar -->
 
 <?php include("adminpartials/mainsidebar.php") ?>; 

              <?php
                include('../partials/connect.php');

                $userId = $_SESSION['user_id'];  //for looged in teacher

                $sql = "SELECT Surname FROM users WHERE Id =  $userId";

                $usql = "SELECT * FROM users WHERE Id = $userId" ;
                $Teacherresults = $connect->query($usql);
                $Teacherresultsfinal = $Teacherresults->fetch_assoc();  

                // Execute the query
                $result = $connect->query($sql);
                $reportrow = $result->fetch_assoc();

                $sql = "SELECT COUNT(*) as count FROM pmessages WHERE IsOpened = 0";
                // Execute the query
                $result = $connect->query($sql);
                $messagesrow = $result->fetch_assoc();
               
               ?>
 

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Deleted Messages
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
              <h3 class="box-title">Conversations</h3>

              <div class="box-tools pull-right">
                <div class="has-feedback">
                  <input type="text" class="form-control input-sm" placeholder="Search Mail">
                  <span class="glyphicon glyphicon-search form-control-feedback"></span>
                </div>
              </div>
              <!-- /.box-tools --->
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              
              <div class="table-responsive mailbox-messages">
                <table class="table table-hover table-striped">


                        <?php
                        include('../partials/connect.php');

                        $sql = "SELECT * FROM pmessages WHERE IsOpened = 2 ";  //comeback for condition
                        $results = $connect->query($sql);
                        while($final = $results->fetch_assoc()) { ?>

                    <?php 
                      $currentTime = time();
                      $recievedtime = strtotime($final['CreatedAt']);
                      $timepast = $currentTime - $recievedtime;
                      
                      $inMinutes = floor($timepast /60);  //floor.. cuts the milliseconds
                      $inHours = floor($timepast /3660);
                      $inDays = floor($timepast /86400);
                      $inMonths = floor($timepast /(30 * 86400));


                      if ($timepast < 60) {
                          $_SESSION['elapsed'] = $timepast . ' seconds';
                      } elseif ($timepast < 3600) {
                          $_SESSION['elapsed'] = $inMinutes . ' minute/s'; 
                      } elseif ($timepast < 86400) {
                          $_SESSION['elapsed'] = $inHours . ' hour/s';
                      } elseif ($timepast < 2592000) {
                          $_SESSION['elapsed'] = $inDays . ' day/s'; 
                      } else {
                          $_SESSION['elapsed'] = $inMonths . ' month/s'; 
                      }
                    
                    ?>

                  <tbody>
                  <tr>
                    <td><input type="checkbox"></td>
                    <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-yellow"></i></a></td>
                    <td class="mailbox-name"><a href="mread-mail.php?id=<?php echo $final['No'] ?>"> <?php echo $final['SenderName'] ?></a></td>
                    <td class="mailbox-subject"><b><?php echo $final['Subject'] ?></b> - <?php echo $final['Message'] ?></td>
                    <td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>
                    <?php
                        if (isset($_SESSION['elapsed'])) {
                            echo '<td class="mailbox-date">' . $_SESSION['elapsed'] . '</td>';
                            unset($_SESSION['elapsed']);
                        }
                        ?>
                  </tr>
                  </tbody>
                  <?php } ?>
                </table>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer no-padding">
              
            </div>
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Slimscroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js"></script>
<!-- Page Script -->

<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
