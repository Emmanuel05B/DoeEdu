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
      <h1>Owing Learners  <small>...</small></h1>
        <ol class="breadcrumb">
          <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">debt</li>
        </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-body">
              <div class="table-responsive">
              <table id="example1" class="table table-bordered table-hover">
                <thead style="background-color:#d1d9ff;">
                  <tr>
                    <th>StNo</th>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Total Fees</th>
                    <th>Total Paid</th>
                    <th>Total Owe</th>
                    <th>Last Payment</th>
                    <th>Action</th>
                    <th>Last Reminder Sent</th>
                  </tr>
                </thead> 

                <tbody>
                <?php
                  // Query learners who owe money
                  
                  $sql = "
                      SELECT u.Id AS LearnerId, u.Name, u.Surname, f.TotalFees, f.TotalPaid, f.Balance, f.LastPaymentDate, f.LastReminderSent
                      FROM finances f
                      JOIN users u ON f.LearnerId = u.Id
                      WHERE f.Balance > 0
                        AND (f.LastPaymentDate IS NULL OR f.LastPaymentDate <= DATE_SUB(NOW(), INTERVAL 1 MONTH))
                      ORDER BY f.LastPaymentDate ASC, u.Surname, u.Name
                  ";


                  $results = $connect->query($sql);

                  $learnerIds = []; // collect IDs for "Mail all Parents"

                  if ($results && $results->num_rows > 0) {
                      while($learner = $results->fetch_assoc()) {
                          $learnerIds[] = $learner['LearnerId']; // add ID to array
                          ?>
                          <tr>
                              <td><?= htmlspecialchars($learner['LearnerId']) ?></td>
                              <td><?= htmlspecialchars($learner['Name']) ?></td>
                              <td><?= htmlspecialchars($learner['Surname']) ?></td>
                              <td>R<?= number_format($learner['TotalFees'], 2) ?></td>
                              <td>R<?= number_format($learner['TotalPaid'], 2) ?></td>
                              <td>R<?= number_format($learner['Balance'], 2) ?></td>
                              <td>
                                  <?= !empty($learner['LastPaymentDate']) ? date('d M Y, H:i', strtotime($learner['LastPaymentDate'])) : 'Never' ?>
                              </td>
                              <td>
                                  <form action="emailsuperhandler.php" method="post" style="display:inline;" class="sendIndividualEmail">
                                      <input type="hidden" name="action" value="owing_individual">
                                      <input type="hidden" name="redirect" value="mailparent.php">
                                      <input type="hidden" name="learnerId" value="<?= $learner['LearnerId'] ?>">
                                      <button type="submit" class="btn btn-xs btn-success"
                                          data-learner-name="<?= htmlspecialchars($learner['Name'] . ' ' . $learner['Surname']) ?>">
                                          Mail Parent
                                      </button>
                                  </form>
                              </td>
                              <td>
                                  <?= !empty($learner['LastReminderSent']) ? date('d M Y, H:i', strtotime($learner['LastReminderSent'])) : 'Never' ?>
                              </td>
                          </tr>
                      <?php }
                  } else { ?>
                      <tr>
                          <td colspan="8" class="text-center">No learners currently owe any fees.</td>
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
                    <th>Action</th>
                    <th>Last Reminder Sent</th>
                  </tr>
                </tfoot>
              </table>
              </div>
              <br>
              

              <form id="sendAllForm" action="emailsuperhandler.php" method="post">
                <input type="hidden" name="action" value="owing_lastmonth">
                <input type="hidden" name="redirect" value="mailparent.php">
                <input type="hidden" name="learnerIds" value="<?= implode(',', $learnerIds) ?>">
                <button type="submit" class="btn btn-block btn-primary">
                    Mail all Parents
                </button>
               </form>


            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

<script>
  $(function () {
    $('#example1').DataTable();
  });
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // Individual learner email
    document.querySelectorAll('.sendIndividualEmail button').forEach(function(btn){
        btn.addEventListener('click', function(e){
            e.preventDefault();
            const learnerName = this.dataset.learnerName;
            const form = this.closest('form');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure?',
                text: `Send reminder to parent of ${learnerName}?`,
                showCancelButton: true,
                confirmButtonText: 'Yes, send it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if(result.isConfirmed){
                    form.submit();
                }
            });
        });
    });

    // Mail all parents
    const allForm = document.getElementById('sendAllForm');
    allForm.querySelector('button').addEventListener('click', function(e){
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Are you sure?',
            text: "Send reminders to all parents of learners owing fees?",
            showCancelButton: true,
            confirmButtonText: 'Yes, send them!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if(result.isConfirmed){
                allForm.submit();
            }
        });
    });

});
</script>


<?php
if (isset($_SESSION['success'])) {
    $msg = $_SESSION['success'];
    unset($_SESSION['success']);
    echo "
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Done!',
            text: '". addslashes($msg) ."',
            confirmButtonText: 'OK'
        });
    </script>";
}

if (isset($_SESSION['error'])) {
    $msg = $_SESSION['error'];
    unset($_SESSION['error']);
    echo "
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Failed!',
            text: '". addslashes($msg) ."',
            confirmButtonText: 'OK'
        });
    </script>";
}
?>


</body>
</html>
