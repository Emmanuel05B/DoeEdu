<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../../common/login.php");
  exit();
}
include(__DIR__ . "/../adminpartials/head.php");
include(__DIR__ . "/../../partials/connect.php");

$requests = $connect->query("SELECT * FROM users WHERE IsVerified = 0 AND UserType = '2'");
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include(__DIR__ . "/../adminpartials/header.php") ?>
  <?php include(__DIR__ . "/../adminpartials/mainsidebar.php") ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Manage Pending Verifications</h1>
    </section>

    <section class="content">
      <div class="box box-info">
        <div class="box-header with-border d-flex justify-content-between align-items-center" style="display: flex; justify-content: space-between; align-items: center;">
        <h3 class="box-title">Unverified Learners</h3>
        <a href="#" id="sendAllBtn" class="btn btn-warning btn-sm">
            Send Reminder to All
        </a>
        </div>

        <div class="box-body">
          <div class="table-responsive">
            
            <table id="inviteTable" class="table table-bordered table-striped">
              <thead style="background-color:#d1d9ff;">
                <tr>
                  <th>Name</th>
                  <th>Surname</th>
                  <th>Email</th>
                  <th>Registered At</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($req = $requests->fetch_assoc()): ?>
                  <tr>
                    <td><?= htmlspecialchars($req['Name']) ?></td>
                    <td><?= htmlspecialchars($req['Surname']) ?></td>
                    <td><?= htmlspecialchars($req['Email']) ?></td>
                    <td><?= htmlspecialchars($req['RegistrationDate']) ?></td>
                    <td><span class="label label-warning">Not Verified</span></td>
                    <td>
                      <a href="send_reminder.php?id=<?= $req['Id'] ?>" class="btn btn-primary btn-xs">Send Reminder</a>
                    </td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>Name</th>
                  <th>Surname</th>
                  <th>Email</th>
                  <th>Registered At</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<!-- JS Libraries -->

<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  document.getElementById("sendAllBtn").addEventListener("click", function(e) {
    e.preventDefault(); // Prevent link from following

    Swal.fire({
      title: 'Are you sure?',
      text: 'This will send a reminder email to ALL unverified learners!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Yes, send them!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = "send_all_reminders.php";
      }
    });
  });
</script>


<script>
  $(function () {
    $('#inviteTable').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "autoWidth": false
    });

    <?php if (isset($_SESSION['success_message'])): ?>
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: '<?= $_SESSION['success_message'] ?>',
        confirmButtonText: 'OK'
      });
      <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
  });
</script>


<?php include(__DIR__ . "/../adminpartials/queries.php"); ?>
<script src="../dist/js/demo.js"></script>
</body>
</html>
