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

  <!-- Content Wrapper. Contains page content ---> 
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    </section>

    <!-- Main content table---------------------------------------------> 
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
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
                    <th>.....</th>

                  </tr>
                </thead> 

                <tbody>
                <?php
                include(__DIR__ . "/../../partials/connect.php");

    
                    echo '<h3>Owing Learners</h3><br>';

                    // SQL query for learners owing money
                    $sql = "SELECT lt.*, ls.* 
                            FROM learners AS lt
                            JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
                            WHERE lt.TotalOwe > 0
                            AND ls.ContractExpiryDate = (
                                SELECT MAX(ls2.ContractExpiryDate)
                                FROM learnersubject AS ls2
                                WHERE ls2.LearnerId = ls.LearnerId
                            )
                            ";
        
                    
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
                    <td>
                    <p><a href="mailindiv.php?id=<?php echo $final['LearnerId'] ?>" class="btn btn-block btn-primary">Mail Learner</a></p>
                    </td>

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
                    <th>.....</th>


                  </tr>
                </tfoot>

              </table><br>
              <a href="mailparenthandler.php" class="btn btn-block btn-primary">Mail all the Parents</a>

            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="../bower_components/fastclick/lib/fastclick.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
<script src="../../common/dist/js/demo.js"></script> 
<script>
  $(function () {
    $('#example1').DataTable();
  });
</script>

</body>
</html>  
