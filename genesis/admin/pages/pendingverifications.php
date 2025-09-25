<!DOCTYPE html>
<html>
  
<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../../common/pages/login.php");
  exit();
}
include(__DIR__ . "/../../partials/connect.php");


$sql = "
    SELECT u.Id AS Id, u.Name AS Name, u.Email AS Email, u.Surname AS Surname, u.RegistrationDate AS RegistrationDate,
           u.VerificationToken, u.IsVerified,
           l.ParentTitle, l.ParentName, l.ParentSurname, l.ParentEmail
    FROM users u
    INNER JOIN learners l ON u.Id = l.LearnerId
    WHERE u.IsVerified = 0 
      AND u.UserType = '2'
";

$requests = $connect->query($sql);

?>




<?php include(__DIR__ . "/../../common/partials/head.php"); ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    
    <section class="content-header">
        <h1>Pending Verifications <small>manage verifications</small></h1>
        <ol class="breadcrumb">
            <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Verifications Manegement</li>
        </ol>
    </section>

    <section class="content">
      <div class="box box-info">
        <div class="box-body table-responsive">
        <div class="box-header with-border d-flex justify-content-between align-items-center" style="display: flex; justify-content: space-between; align-items: center;">
          <h3 class="box-title">Unverified Learners</h3>
        
          <form id="sendAllForm" action="emailsuperhandler.php" method="post" style="display: inline;">
            <input type="hidden" name="action" value="reminder_all">
            <input type="hidden" name="redirect" value="pendingverifications.php">
            <button type="submit" id="sendAllBtn" class="btn btn-warning btn-sm">Send Reminder to All</button>
          </form>
        </div>

          <div class="table-responsive">
            
            <table id="example1" class="table table-bordered table-hover">
              <thead style="background-color:#d1d9ff;">
                <tr>
                  <th>Name</th>
                  <th>Surname</th>
                  <th>Parent Email</th>
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
                    <td><?= htmlspecialchars($req['ParentEmail']) ?></td>
                    <td><?= htmlspecialchars($req['RegistrationDate']) ?></td>
                    <td><span class="label label-warning">Not Verified</span></td>
                    <td>
                      <form action="emailsuperhandler.php" method="post" style="display:inline;">
                        <input type="hidden" name="action" value="reminder">
                        <input type="hidden" name="redirect" value="pendingverifications.php">
                        <input type="hidden" name="id" value="<?= $req['Id'] ?>">
                        <button type="submit" class="btn btn-primary btn-xs btn-send-reminder">Send Reminder</button>
                      </form>
                      <button type="submit" class="btn btn-danger btn-xs btn-delete">Delete(Deregister)</button>
                    </td>
                    <td>

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

</div>

<!-- JS Libraries -->

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

<script>
  // Single reminder confirmation
  $('.send-reminder-form').on('submit', function(e){
      e.preventDefault();
      const form = this;
      Swal.fire({
          title: 'Send Reminder?',
          text: 'This will send a verification reminder to this learner.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, send it!',
          cancelButtonText: 'Cancel'
      }).then((result) => {
          if(result.isConfirmed){
              form.submit();
          }
      });
  });

  // Reminder to all confirmation
  $('#sendAllForm').on('submit', function(e){
      e.preventDefault();
      const form = this;
      Swal.fire({
          title: 'Send to all?',
          text: 'This will send a verification reminder to all unverified learners.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, send all!',
          cancelButtonText: 'Cancel'
      }).then((result) => {
          if(result.isConfirmed){
              form.submit();
          }
      });
  });

</script>



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

  // Alerts for success or error
  <?php if(isset($_SESSION['success'])): ?>
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: '<?= addslashes($_SESSION['success']) ?>',
      confirmButtonText: 'OK'
    });
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>

  <?php if(isset($_SESSION['error'])): ?>
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
