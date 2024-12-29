<!DOCTYPE html>
<html>
<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../common/login.php");
    exit();
}
?>
    <?php
    include('../partials/connect.php');
      //  $grade = $_GET['gra'];
      //  $subject = $_GET['sub'];
      //  $chapter = $_GET['cha'];


      
    ?>

<?php include("adminpartials/head.php"); ?>

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<style>
    .example-modal .modal {
        position: relative;
        top: auto;
        bottom: auto;
        right: auto;
        left: auto;
        display: block;
        z-index: 1;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        border: 1px solid gray;
        text-align: center;
        padding: 8px;
    }

    th {
        background-color:rgb(242, 242, 242);
    }

</style>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <!-- Content Wrapper. Contains page content. -->
  <div class="content-wrapper">
    
    <!-- Main content -->
    <section class="content">
        
     <div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>

                <h4 class="modal-title">Complete</h4><br>

                <p><strong> Set up To Do List </strong></p>

              </div>
              <div class="modal-body">
                
                <form action="modalhandlerhere.php" method="post">

                    <table>
                        <thead>
                            <tr>
                                <th rowspan="2">Labels</th>
                                <th colspan="5">Fill</th>
                            </tr>
                           
                        </thead>
                        <tbody>
                        
                        <tr>
                            <td>Task Name</td>
                            <td>
                            <textarea class="textarea" name="message" placeholder="Type here" style="width: 100%; height: 60px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                            </td>
                            <input type="hidden" id="urlParams" name="graid" value="<?php echo 10 ?>">
                            <input type="hidden" id="urlParams" name="subid" value="<?php echo 2 ?>">
                            <input type="hidden" id="urlParams" name="chaid" value="<?php echo 5 ?>">

                        </tr>
                       
                        <tr>
                            <td>Date</td>
                            <td><input type="date" name="date"></td>
                        </tr>
                        <tr>
                            <td>Time</td>
                            <td><input type="time" name="time"></td>
                        </tr>

                        </tbody>
                    </table>
                    <br>
                    <button type="Submit" name="submit">Submit Data</button>                    
         
                </form>
              </div>
              <div class="modal-footer">
                
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>

    </section>
    <!-- /.content -->
  </div>
  
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>

<script>
  $(document).ready(function() {
      $('#modal-default').modal('show'); // Show the modal immediately on page load

      // Close the modal and redirect when clicking on the backdrop
      $('.modal').on('click', function (e) {
          // Check if the click target is the modal backdrop
          if ($(e.target).is('.modal')) {
              window.location.href = 'adminindex.php'; 
          }
      });
  });
</script>
</body>
</html>
