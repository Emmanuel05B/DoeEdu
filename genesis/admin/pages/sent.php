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

  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

              <?php
                include(__DIR__ . "/../../partials/connect.php");

                $userId = $_SESSION['user_id'];  //for looged in teacher
                $email = $_SESSION['email'];  //for logged-in teacher


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
        Messages sent
      </h1>
      <ol class="breadcrumb">
        <li><a href="teacherindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
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
              <!-- /.box-tools -->
            </div><br>
            <!-- /.box-header -->
            <div class="box-body no-padding">
             
              <div class="table-responsive mailbox-messages">
                <table class="table table-hover table-striped">


                        <?php

                        $sql = "SELECT * FROM messages WHERE TPEmail = ? ";  //comeback for condition
                        $stmt = $connect->prepare($sql);
                        $stmt->bind_param("s", $email); 

                        $stmt->execute();
                        $results = $stmt->get_result();

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
                    
                      $name = $final['ParentId'];

                        $sql = "SELECT * FROM users WHERE Id = $name ";  //comeback for condition
                        $pname = $connect->query($sql);
                        $pfinal = $pname->fetch_assoc();
                        ?>
                  

                  <tbody>
                  <tr>
                    <td><input type="checkbox"></td>
                    <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-yellow"></i></a></td>
                    <td class="mailbox-subject"><a href="readsentmessage.php?id=<?php echo $final['No'] ?>"> <?php echo 'To : ' ?></b> <?php echo $pfinal['Name'] ?></a></td>
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
                </table><br>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
            </div>
            <!-- /.box-body -->
            
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
<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

</body>
</html>
