<!DOCTYPE html>
<html>

<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
  exit();
}

include(__DIR__ . "/../../common/partials/head.php"); 
include(__DIR__ . "/../../partials/connect.php");
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  
  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Finances</h1>
      <ol class="breadcrumb">
        <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Finances</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <?php 
          $sql = "SELECT                
            SUM(TotalFees) AS TotalFees,
            SUM(TotalPaid) AS TotalPaid,
            SUM(CASE WHEN TotalOwe > 0 THEN TotalOwe ELSE 0 END) AS TotalOwe,
            SUM(CASE WHEN TotalOwe < 0 THEN TotalOwe ELSE 0 END) AS Owe
            FROM learners";
          $results = $connect->query($sql);
          $final = $results->fetch_assoc();

          $TotalFees = $final['TotalFees'];
          $TotalPaid = $final['TotalPaid'];
          $TotalOwe = $final['TotalOwe'];
          $Owe = (-1 * $final['Owe']);
        ?>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-balance-scale"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Paid by Learners</span><br>
              <span class="info-box-number">R<?php echo number_format($TotalPaid, 2); ?></span>
            </div>
          </div>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-money"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Amount Expected</span><br>
              <span class="info-box-number">R<?php echo number_format($TotalFees, 2); ?></span>
            </div>
          </div>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-dollar"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Amount Due to Us</span><br>
              <span class="info-box-number">R<?php echo number_format($TotalOwe, 2); ?></span>
            </div>
          </div>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-credit-card"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Amount We Owe</span><br>
              <span class="info-box-number">R<?php echo number_format($Owe, 2); ?></span>
            </div>
          </div>
        </div>
      </div>

      <!-- Status Links  -->
      <div class="row">
        <div class="col-md-12">
          <div class="box-footer">
            <div class="row">
              <div class="col-sm-3 col-xs-6">
                <div class="description-block border-right">
                  <a href="status.php?val=1" style="display: block; text-decoration: none; padding: 5px; color: #fff; text-align: center; border-radius: 5px; background-color: #17a2b8;">
                    <div>
                      <h5 class="description-header">Learners</h5>
                      <span class="description-text">Active - Owing</span>
                    </div>
                  </a>
                </div>
              </div>
              <div class="col-sm-3 col-xs-6">
                <div class="description-block border-right">
                  <a href="status.php?val=3" style="display: block; text-decoration: none; padding: 5px; color: #fff; text-align: center; border-radius: 5px; background-color: #ffc107;">
                    <div>
                      <h5 class="description-header">Learners</h5>
                      <span class="description-text">Not Active - Not Owing</span>
                    </div>
                  </a>
                </div>
              </div>
              <div class="col-sm-3 col-xs-6">
                <div class="description-block border-right">
                  <a href="status.php?val=2" style="display: block; text-decoration: none; padding: 5px; color: #fff; text-align: center; border-radius: 5px; background-color: #28a745;">
                    <div>
                      <h5 class="description-header">Learners</h5>
                      <span class="description-text">Active - Not Owing</span>
                    </div>
                  </a>
                </div>
              </div>
              <div class="col-sm-3 col-xs-6">
                <div class="description-block">
                  <a href="status.php?val=4" style="display: block; text-decoration: none; padding: 5px; color: #fff; text-align: center; border-radius: 5px; background-color: #dc3545;">
                    <div>
                      <h5 class="description-header">Learners</h5>
                      <span class="description-text">Not Active - Owing</span>
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Learners Table -->
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header">
                <h3 class="box-title">Learners</h3>
              </div>
              <div class="box-body">
                <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Surname</th>
                        <th>Grade</th>
                        <th>Math</th>
                        <th>Physics</th>
                        <th>Total Fees</th>
                        <th>Total Paid</th>
                        <th>Total Owe</th>
                        <th>Last Paid</th>
                        <th>Update By</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $sql = "
                          SELECT 
                            learners.LearnerId, 
                            users.Name, 
                            users.Surname, 
                            learners.Grade, 
                            learners.Math, 
                            learners.Physics,
                            learners.TotalFees, 
                            learners.TotalPaid, 
                            learners.TotalOwe,
                            learners.LastUpdated
                          FROM learners
                          JOIN users ON learners.LearnerId = users.Id
                        ";
                        $results = $connect->query($sql);
                        while($final = $results->fetch_assoc()) { ?>
                          <tr>
                            <td><?php echo htmlspecialchars($final['Name']); ?></td>
                            <td><?php echo htmlspecialchars($final['Surname']); ?></td>
                            <td><?php echo htmlspecialchars($final['Grade']); ?></td>
                            <td><?php echo htmlspecialchars($final['Math']); ?></td>
                            <td><?php echo htmlspecialchars($final['Physics']); ?></td>
                            <td>R<?php echo number_format($final['TotalFees'], 2); ?></td>
                            <td>R<?php echo number_format($final['TotalPaid'], 2); ?></td>
                            <td>R<?php echo number_format($final['TotalOwe'], 2); ?></td>
                            <td>
                              <?php 
                                if (!empty($final['LastUpdated'])) {
                                  echo date('d M Y, H:i', strtotime($final['LastUpdated']));
                                } else {
                                  echo "Never";
                                }
                              ?>
                            </td>
                            <td>
                              <form action="payhandler.php" method="POST" class="horizontal-container">
                                <input type="number" class="form-control2" id="newamount" name="newamount" min="-5000" max="5000" required>
                                <input type="hidden" name="learnerid" value="<?php echo htmlspecialchars($final['LearnerId']); ?>">                                                                        
                                <button type="submit" name="updateby" class="btn btn-primary py-3 px-4">Pay</button>
                              </form>
                            </td>
                          </tr>
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Name</th>
                        <th>Surname</th>
                        <th>Grade</th>
                        <th>Math</th>
                        <th>Physics</th>
                        <th>Total Fees</th>
                        <th>Total Paid</th>
                        <th>Total Owe</th>
                        <th>Last Paid</th>
                        <th>Update By</th>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                <a href="mailparent.php" class="btn btn-block btn-primary">Mail Parents <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
          </div>
        </div>
    </section>

  </div>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Tabs omitted for brevity - keep your existing code here -->
    <!-- ... -->
  </aside>
  <div class="control-sidebar-bg"></div>
</div>

<!-- Scripts -->
<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>


<script>
  $(function () {
    $('#example1').DataTable();
  });
</script>

</body>
</html>
