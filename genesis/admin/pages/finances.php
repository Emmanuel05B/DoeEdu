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
      <?php
// Fetch totals from finances table
$sqlTotals = "
    SELECT 
        SUM(TotalFees) AS TotalFees,
        SUM(TotalPaid) AS TotalPaid,
        SUM(Balance) AS TotalOwe
    FROM finances
";
$resultTotals = $connect->query($sqlTotals);
$totals = $resultTotals->fetch_assoc();

$TotalFees = (float)$totals['TotalFees'];
$TotalPaid = (float)$totals['TotalPaid'];
$TotalOwe  = (float)$totals['TotalOwe'];
$Owe       = 0; // still static if needed
?>

<div class="row">
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
        <h3 class="box-title">Learners Finances</h3>
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <table id="example1" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Name</th>
                <th>Surname</th>
                <th>Total Fees</th>
                <th>Total Paid</th>
                <th>Balance</th>
                <th>Last Payment Date</th> <!-- Added column -->
                <th>Update Payment</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $sql = "
                  SELECT 
                    f.LearnerId,
                    u.Name,
                    u.Surname,
                    f.TotalFees,
                    f.TotalPaid,
                    f.Balance,
                    f.LastPaymentDate
                  FROM finances f
                  JOIN users u ON f.LearnerId = u.Id
                  ORDER BY u.Surname, u.Name
                ";
                
                $results = $connect->query($sql);
                if ($results && $results->num_rows > 0) {
                  while($final = $results->fetch_assoc()) { ?>
                    <tr>
                      <td><?= htmlspecialchars($final['Name']) ?></td>
                      <td><?= htmlspecialchars($final['Surname']) ?></td>
                      <td>R<?= number_format($final['TotalFees'], 2) ?></td>
                      <td>R<?= number_format($final['TotalPaid'], 2) ?></td>
                      <td>R<?= number_format($final['Balance'], 2) ?></td>
                      <td>
                        <?= !empty($final['LastPaymentDate']) ? date('d M Y, H:i', strtotime($final['LastPaymentDate'])) : 'Never' ?>
                      </td>
                      <td>
                        <form action="payhandler.php" method="POST" class="horizontal-container">
                          <input type="number" class="form-control2" id="newamount" name="newamount" min="-5000" max="5000" required>
                          <input type="hidden" name="learnerid" value="<?= htmlspecialchars($final['LearnerId']) ?>">                                                                        
                          <button type="submit" name="updateby" class="btn btn-sm btn-primary py-3 px-4">Pay</button>
                        </form>
                      </td>
                    </tr>
                  <?php }
                } else { ?>
                  <tr>
                    <td colspan="7" class="text-center">No learners found in finances table.</td>
                  </tr>
              <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <th>Name</th>
                <th>Surname</th>
                <th>Total Fees</th>
                <th>Total Paid</th>
                <th>Balance</th>
                <th>Last Payment Date</th> <!-- Added column -->
                <th>Update Payment</th>
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

</div>

<!-- Scripts -->
  <?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

  <?php if (isset($_GET['paid']) && $_GET['paid'] == 1): ?>
    <?php  
    echo '<script>
        Swal.fire({
            icon: "success",
            title: "Payment Updated Successfully",
            text: "The learner\'s payment information has been saved.",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "OK"
        }).then(() => {
            window.location.href = "#";
        });
        </script>'; 
      ?>
  <?php endif; ?>

  <?php if (isset($_GET['notpaid']) && $_GET['notpaid'] == 1): ?>
    <?php  
    echo '<script>
        Swal.fire({
            icon: "error",
            title: "Update Failed",
            text: "Unable to update the payment record. Please try again.",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "OK"
        }).then(() => {
            window.location.href = "#";
        });
        </script>';
      ?>
  <?php endif; ?>

  <?php if (isset($_GET['notfound']) && $_GET['notfound'] == 1): ?>
    <?php  
    echo '<script>
        Swal.fire({
            icon: "error",
            title: "Learner Not Found",
            text: "The learner ID does not exist.",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "OK"
        }).then(() => {
            window.location.href = "#";
        });
        </script>';
      ?>
  <?php endif; ?>

<script>
  $(function () {
    $('#example1').DataTable();
  });
</script>

</body>
</html>
