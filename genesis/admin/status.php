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
  <!-- Left side column. contains the logo and sidebar -->
  <?php include("adminpartials/header.php"); ?>
  <!-- Left side column. contains the logo and sidebar -->
  <?php include("adminpartials/mainsidebar.php"); ?>

  <!-- Content Wrapper. Contains page content --->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
     
    </section>

    <!-- Main content table---------------------------------------------> 
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <!-- /.box -->

          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>StNo</th>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Grade</th>
                    <th>Math</th>
                    <th>Physics</th>
                    <th>Total Fees</th>
                    <th>Total Paid</th>
                    <th>Total Owe</th>
                  </tr>
                </thead> 

                <tbody>
                <?php
                include('../partials/connect.php');
      
                $statusValue = intval($_GET['val']);  // Ensure it's an integer
          
                // Check the status and render different HTML for each case
                if ($statusValue == 1) {
                    // Status 1: On Contract and Owing Learners
                    echo '<h3>On Contract and Owing Learners</h3><br>';

                    // Correct SQL query for learners owing money and with unexpired contracts
                   /* $sql = "SELECT lt.LearnerId, lt.Name, lt.Surname, lt.Grade, lt.TotalFees, lt.TotalPaid, lt.TotalOwe,
                            ls.LearnerSubjectId, ls.SubjectId, ls.Math, ls.Physics, ls.ContractExpiryDate, ls.Status
                            FROM learners AS lt
                            JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
                            WHERE lt.TotalOwe > 0
                            AND ls.ContractExpiryDate > CURDATE()";  */

                             $sql = "SELECT lt.*, ls.*
                            FROM learners AS lt
                            JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
                            WHERE lt.TotalOwe > 0
                            AND ls.ContractExpiryDate = (
                                SELECT MAX(ls2.ContractExpiryDate)
                                FROM learnersubject AS ls2
                                WHERE ls2.LearnerId = ls.LearnerId
                            )
                            AND ls.ContractExpiryDate > CURDATE()";

                } else if ($statusValue == 2) {
                    // Status 2: On Contract and Not Owing Learners
                    echo '<h3>On Contract and Not Owing Learners</h3><br>';
                    $sql = "SELECT * FROM learners WHERE TotalOwe <= 0";
                } else if ($statusValue == 3) {
                    // Status 3: Expired Contract and Not Owing Learners
                    echo '<h3>Expired Contract and Not Owing Learners</h3><br>';
                    $sql = "SELECT * FROM learners WHERE TotalOwe <= 0";
                } else if ($statusValue == 4) {
                    // Status 4: Expired Contract and Owing Learners
                    echo '<h3>Expired Contract and Owing Learners</h3><br>';
                    $sql = "SELECT * FROM learners WHERE TotalOwe > 0";
                } else {
                    // Default case if none of the statuses match
                    echo '<h1>Learners - Unknown Status</h1>';
                }
                    
                $results = $connect->query($sql);
                while($final = $results->fetch_assoc()) { ?> 
                <tr>
                    <td><?php echo $final['LearnerId'] ?></td>
                    <td><?php echo $final['Name'] ?></td>
                    <td><?php echo $final['Surname'] ?></td>
                    <td><?php echo $final['Grade'] ?></td>
                    <td><?php echo $final['Math'] ?></td>
                    <td><?php echo $final['Physics'] ?></td>
                    <td><?php echo $final['TotalFees'] ?></td>
                    <td><?php echo $final['TotalPaid'] ?></td>
                    <td> <?php echo $final['TotalOwe'] ?></td>
                </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th>StNo</th>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Grade</th>
                    <th>Math</th>
                    <th>Physics</th>
                    <th>Total Fees</th>
                    <th>Total Paid</th>
                    <th>Total Owe</th>
                  </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
  </div>
</div>
<!-- End of wrapper -->

<!-- Scripts -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="dist/js/demo.js"></script>
<script>
  $(function () {
    $('#example1').DataTable();
  });
</script>

</body>
</html>
