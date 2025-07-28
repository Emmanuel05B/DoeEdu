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





      <form action="messagehandler.php" method="post" >  <!--  form -->
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
            <!--<input type="email" class="form-control" name="emailto" placeholder="email"> e -->
            <label for="timeFrame" class="time-frame-label">Send to:</label>
                                
                                <select id="sendto" name="reciverid" class="form-control">

                                  <?php
                                  include('../../partials/connect.php');


                                
                                  $sql = "SELECT ParentId FROM parentlearner";  

                                  $result = $connect->query($sql);

                                  if ($result->num_rows > 0) {
                                      // Prepare an array to hold all Parent IDs
                                      $parentIds = [];
                                      
                                      while($row = $result->fetch_assoc()) {
                                          $parentIds[] = $row['ParentId'];
                                      }

                                      
                                      if (!empty($parentIds)) { // Check if we have any Parent IDs
                                         
                                          $parentIdsList = implode(',', $parentIds);
                                          
                                          // Query to get emails for the collected Parent IDs
                                          $sql = "SELECT Id, Surname, Gender, Email FROM users WHERE Id IN ($parentIdsList)";
                                          $result = $connect->query($sql);
                                          
                                          if ($result->num_rows > 0) {
                                              // Output each email as an option element
                                              while($row = $result->fetch_assoc()) {
                                                  echo "<option value='" . $row['Id'] . "'>" . $row['Gender'] . " " . $row['Surname'] . "  --------->  " . $row['Email'] . "</option>";

                                              }
                                          } else {
                                              echo "<option>No users found</option>";
                                          }
                                      } else {
                                          echo "<option>No parent IDs found</option>";
                                      }
                                  } else {
                                      echo "<option>No parent IDs found</option>";
                                  }

                                  $connect->close();

                                        /*  
                                          // Query to get emails for the collected Parent IDs
                                          $sql = "SELECT Id, Surname, Gender, Email FROM users";
                                          $result = $connect->query($sql);
                                          
                                          if ($result->num_rows > 0) {
                                              // Output each email as an option element
                                              while($row = $result->fetch_assoc()) {
                                                echo "<option value='" . $row['Id'] . "'>" . $row['Gender'] . " " . $row['Surname'] . "  --------->  " . $row['Email'] . "</option>";
                                              }
                                          } else {
                                              echo "<option>No users found</option>";
                                          }
                                              
                                  
                                  $connect->close();
                                  */

                                  ?>

                                </select>
          </div>
          <div class="form-group">
            <input type="text" class="form-control" name="subject" placeholder="Subject" required>
          </div>
          <div>
            <textarea class="textarea" id="message" name="message" placeholder="Message" 
              style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" required ></textarea>

              
          </div>

          
          <div class="box-footer">
              <div class="pull-right">
                <button type="submit" value="Submit" name="btnsend" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>
              </div>
              <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Discard</button>
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

<?php include("../adminpartials/queries.php"); ?>
  <script src="../dist/js/demo.js"></script>

 
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
