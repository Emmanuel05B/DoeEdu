<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
  exit();
}
include(__DIR__ . "/../../common/partials/head.php"); 
include(__DIR__ . "/../../partials/connect.php");

// Handle delete
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $connect->prepare("DELETE FROM inviterequests WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    $_SESSION['success_message'] = "Invite request deleted successfully.";  //settiing the session for the success alert
    header('Location: manage_inviterequests.php');
    exit;
}

$requests = $connect->query("SELECT * FROM inviterequests ORDER BY created_at DESC");
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Manage Invite Requests  <small>...</small></h1>
        <ol class="breadcrumb">
          <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Administration</li>
        </ol>
    </section>
 
    <section class="content">
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title">Invite Requests</h3>
        </div>
        <div class="box-body">
          <div class="table-responsive">
            <table id="example1" class="table table-bordered table-hover">
              <thead style="background-color:#d1d9ff;">
                <tr>
                  <th>Name</th>
                  <th>Surname</th>
                  <th>Email</th>
                  <th>Message</th>
                  <th>Requested On</th>
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
                        <span class="label label-success">Recieved</span>
                      <?php else: ?>
                        <span class="label label-default">Pending</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      

                      <?php if (!$req['IsAccepted']): ?>
                        <form action="emailsuperhandler.php" method="post" style="display:inline;">
                            <input type="hidden" name="action" value="invite">
                            <input type="hidden" name="id" value="<?= $req['id'] ?>">
                            <input type="hidden" name="redirect" value="manage_inviterequests.php">
                            <button type="submit" class="btn btn-primary btn-xs swal-send">Send Invite</button>
                        </form>
                      <?php endif; ?>
                      <button class="btn btn-danger btn-xs swal-delete"
                              data-id="<?= $req['id'] ?>"
                              data-name="<?= htmlspecialchars($req['name']) ?>"
                            >Delete</button>
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



<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

<script>
  $(function () {

    $('#example1').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true
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
       // cancelButtonText: 'give the cancel button a custom name',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = `manage_inviterequests.php?delete_id=${id}`;
        }
      });
    });

    // Success alert (after redirect)
    // Alerts for emailsuperhandler
    <?php if (isset($_SESSION['success'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Email Sent',
            text: '<?= addslashes($_SESSION['success']) ?>',
            confirmButtonText: 'OK'
        });
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        Swal.fire({
            icon: 'error',
            title: 'Failed to Send',
            text: '<?= addslashes($_SESSION['error']) ?>',
            confirmButtonText: 'OK'
        });
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>


  });
</script>


</body>
</html>
