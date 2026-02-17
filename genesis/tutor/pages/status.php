<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");
include_once(COMMON_PATH . "/../partials/head.php");  
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
<?php include_once(TUTOR_PATH . "/../partials/header.php"); ?> 
<?php include_once(TUTOR_PATH . "/../partials/mainsidebar.php"); ?>


  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <section class="content-header"></section>

    <!-- Main content -->
    <section class="content">
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
                      <th>StNo</th>
                      <th>Name</th>
                      <th>Surname</th>
                      <th>Total Fees</th>
                      <th>Total Paid</th>
                      <th>Total Owe</th>
                      <th>Last Payment</th>
                      <th>Update By</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                  $statusValue = isset($_GET['val']) ? intval($_GET['val']) : 0;

                  $sqlBase = "
                    SELECT f.LearnerId, f.TotalFees, f.TotalPaid, f.Balance, f.LastPaymentDate,
                           u.Name, u.Surname,
                           ls.ContractExpiryDate
                    FROM finances AS f
                    JOIN users AS u ON f.LearnerId = u.Id
                    LEFT JOIN learnersubject AS ls ON f.LearnerId = ls.LearnerId
                    AND ls.ContractExpiryDate = (
                        SELECT MAX(ls2.ContractExpiryDate)
                        FROM learnersubject AS ls2
                        WHERE ls2.LearnerId = f.LearnerId
                    )
                  ";

                  switch($statusValue) {
                      case 1: // On Contract & Owing
                          echo '<h3>On Contract and Owing Learners</h3><br>';
                          $sql = $sqlBase . " WHERE f.Balance > 0 AND ls.ContractExpiryDate > CURDATE()";
                          break;
                      case 2: // On Contract & Not Owing
                          echo '<h3>On Contract and Not Owing Learners</h3><br>';
                          $sql = $sqlBase . " WHERE f.Balance <= 0 AND ls.ContractExpiryDate > CURDATE()";
                          break;
                      case 3: // Expired Contract & Not Owing
                          echo '<h3>Expired Contract and Not Owing Learners</h3><br>';
                          $sql = $sqlBase . " WHERE f.Balance <= 0 AND ls.ContractExpiryDate <= CURDATE()";
                          break;
                      case 4: // Expired Contract & Owing
                          echo '<h3>Expired Contract and Owing Learners</h3><br>';
                          $sql = $sqlBase . " WHERE f.Balance > 0 AND ls.ContractExpiryDate <= CURDATE()";
                          break;
                      default:
                          echo '<h3>All Learners</h3><br>';
                          $sql = $sqlBase;
                          break;
                  }

                  $results = $connect->query($sql);
                  while($final = $results->fetch_assoc()) { ?>
                    <tr>
                      <td><?php echo htmlspecialchars($final['LearnerId']); ?></td>
                      <td><?php echo htmlspecialchars($final['Name']); ?></td>
                      <td><?php echo htmlspecialchars($final['Surname']); ?></td>
                      <td>R<?php echo number_format($final['TotalFees'], 2); ?></td>
                      <td>R<?php echo number_format($final['TotalPaid'], 2); ?></td>
                      <td>R<?php echo number_format($final['Balance'], 2); ?></td>
                      <td>
                        <?php 
                          if (!empty($final['LastPaymentDate'])) {
                              echo date('d M Y, H:i', strtotime($final['LastPaymentDate']));
                          } else {
                              echo "Never";
                          }
                        ?>
                      </td>
                      <td>
                        <form action="payhandler.php" method="POST" class="horizontal-container">
                          <input type="number" class="form-control2" id="newamount" name="newamount" min="-5000" max="5000" required>
                          <input type="hidden" name="learnerid" value="<?php echo htmlspecialchars($final['LearnerId']); ?>">
                          <button type="submit" name="updateby" class="btn btn-sm btn-primary py-3 px-4">Pay</button>
                        </form>
                      </td>
                    </tr>
                  <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>StNo</th>
                      <th>Name</th>
                      <th>Surname</th>
                      <th>Total Fees</th>
                      <th>Total Paid</th>
                      <th>Total Owe</th>
                      <th>Last Payment</th>
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
</div>

<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>

<script>
  $(function () {
    $('#example1').DataTable({
      
    });
  });
</script>

</body>
</html>
