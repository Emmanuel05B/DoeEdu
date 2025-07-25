<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}
include("adminpartials/head.php");
include('../partials/connect.php');

// Handle delete
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $connect->prepare("DELETE FROM inviterequests WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    $_SESSION['success_message'] = "Invite request deleted successfully.";
    header('Location: manage_inviterequests.php');
    exit;
}

$requests = $connect->query("SELECT * FROM inviterequests ORDER BY created_at DESC");
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include("adminpartials/header.php") ?>
  <?php include("adminpartials/mainsidebar.php") ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Manage Invite Requests</h1>
    </section>

    <section class="content">
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title">Invite Requests</h3>
        </div>
        <div class="box-body">
          <div class="table-responsive">
            <table id="inviteTable" class="table table-bordered table-striped">
              <thead style="background-color:#d1d9ff;">
                <tr>
                  <th>Name</th>
                  <th>Surname</th>
                  <th>Email</th>
                  <th>Message</th>
                  <th>Requested At</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($req = $requests->fetch_assoc()): ?>
                  <tr>
                    <td><?= htmlspecialchars($req['name']) ?></td>
                    <td><?= htmlspecialchars($req['surname']) ?></td>
                    <td><?= htmlspecialchars($req['email']) ?></td>
                    <td style="white-space: pre-wrap;"><?= htmlspecialchars($req['message']) ?></td>
                    <td><?= htmlspecialchars($req['created_at']) ?></td>
                    <td>
                      <?php if ($req['IsAccepted']): ?>
                        <span class="label label-success">Accepted</span>
                      <?php else: ?>
                        <span class="label label-default">Pending</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <?php if (!$req['IsAccepted']): ?>
                        <a href="send_invite.php?id=<?= $req['id'] ?>"
                           class="btn btn-success btn-xs swal-send">Send Invite</a>
                      <?php endif; ?>
                      <button class="btn btn-danger btn-xs swal-delete"
                              data-id="<?= $req['id'] ?>"
                              data-name="<?= htmlspecialchars($req['name']) ?>">Delete</button>
                    </td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>Name</th>
                  <th>Surname</th>
                  <th>Email</th>
                  <th>Message</th>
                  <th>Requested At</th>
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
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  $(function () {
    $('#inviteTable').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "autoWidth": false
    });

    // Delete with SweetAlert
    $('.swal-delete').on('click', function () {
      const id = $(this).data('id');
      const name = $(this).data('name');
      Swal.fire({
        title: 'Are you sure?',
        text: `Delete request from ${name}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = `manage_inviterequests.php?delete_id=${id}`;
        }
      });
    });

    // Success alert (after redirect)
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
</body>
</html>
