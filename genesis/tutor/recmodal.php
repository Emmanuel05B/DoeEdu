<!DOCTYPE html>
<html>
<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../common/login.php");
    exit();
}
?>

<?php include("tutorpartials/head.php"); ?>

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

    .example-modal .modal {
        background: transparent !important;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        border: 1px solid black;
        text-align: center;
        padding: 8px;
    }

    th {
        background-color: #f2f2f2;
    }

    td {
        height: 40px;
    }
</style>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <!-- Content Wrapper. Contains page content. -->
  <div class="content-wrapper">
    
    <!-- Main content -->
    <section class="content">

    <?php
    include('../partials/connect.php');
        $grade = $_GET['gra'];
        $subject = $_GET['sub'];
        $chaptername = $_GET['cha'];
    ?>
            


     <div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
              
                <h4 class="modal-title">Complete</h4><br>

                <p><strong> Set up the Activivty or Generate an Online Activity </strong></p>

              </div>
              <div class="modal-body">
                
                <form action="modalhandler.php" method="post">

                    <table>
                        <thead>
                            <tr>
                                <th rowspan="2">Nr</th>
                                <th rowspan="2">Labels</th>
                                <th colspan="5">Fill</th>
                            </tr>
                           
                        </thead>
                        <tbody>
                        
                        <tr>
                        <td>1</td>
                            <td>Provide the name of the activity</td>
                            <td><input type="text" name="activityname" ></td>
                            <input type="hidden" id="urlParams" name="graid" value="<?php echo $grade ?>">
                            <input type="hidden" id="urlParams" name="subid" value="<?php echo $subject ?>">
                            <input type="hidden" id="urlParams" name="chaid" value="<?php echo $chaptername ?>">

                        </tr>
                       
                        <tr>
                            <td>2</td>
                            <td>Provide the Total for the activity</td>
                            <td><input type="number" name="activitytotal" min="1" max="100"></td>
                        </tr>

                        </tbody>
                    </table>
                    <br>
                    <button type="Submit" name="submit">Submit</button>                    
         
                </form>

                   <hr>

                <!-- Generate Activity Button -->
                <div style="text-align: center; margin-top: 20px;">
                    <a href="generateactivity.php?gra=<?php echo $grade ?>&sub=<?php echo $subject ?>&cha=<?php echo $chaptername ?>" class="btn btn-primary">
                        Generate Quiz Instead
                    </a>
                </div>

                <hr>
                

                <div style="text-align: center; margin-top: 20px;">
                    <a href="classes1.php" class="btn btn-primary">
                       Back
                    </a>
                </div>

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
              window.location.href = 'classes1.php'; 
          }
      });
  });
</script>
</body>
</html>
