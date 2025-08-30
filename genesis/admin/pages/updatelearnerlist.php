<!DOCTYPE html> 
<html>
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
  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>
  <?php
    // Handle disable
    /*

    if (isset($_GET['disable_id'])) {
      $disable_id = intval($_GET['disable_id']);

      // Update the learner as disabled
      $stmt = $connect->prepare("UPDATE learners SET IsDisabled = 1 WHERE LearnerId = ?");
      $stmt->bind_param("i", $disable_id);
      $stmt->execute();
      $stmt->close();

      // Success alert with the learner’s name (optional)
      $_SESSION['success_message'] = "Learner has been disabled successfully.";

      header('Location: updatelearnerlist.php');
      exit;
    }
 */
  ?>

  <div class="content-wrapper">

    <section class="content-header">
      <?php include(__DIR__ . "/../../partials/connect.php"); ?> 

      <h1>Update Learner Details <small>Manage learner profile information</small></h1>
      <ol class="breadcrumb">
        <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Update Learner</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <?php
                $stmt = $connect->prepare("
                    SELECT lt.*, u.Name, u.Surname
                    FROM learners lt
                    JOIN users u ON lt.LearnerId = u.Id
                ");
                $stmt->execute();
                $results = $stmt->get_result();

                echo "<h3 class='box-title'>Learners List</h3>";

              ?>
            </div>

            <div class="box-body">
              <div class="table-responsive">
                <table id="example1" class="table table-bordered table-hover">
                  <thead style="background-color: #d1d9ff;">
                    <tr>
                      <th>StNo.</th>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Grade</th>
                      <th class="text-center">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($results && $results->num_rows > 0): ?>
                      <?php while($final = $results->fetch_assoc()): ?>
                        <tr>
                          <td><?php echo htmlspecialchars($final['LearnerId']) ?></td>
                          <td><?php echo htmlspecialchars($final['Name']) ?></td>
                          <td><?php echo htmlspecialchars($final['Surname']) ?></td>
                          <td><?php echo htmlspecialchars($final['Grade']) ?></td>
                          <td class="text-center">
                            <a href="updatelearner.php?id=<?php echo $final['LearnerId'] ?>" class="btn btn-xs btn-warning">Update</a>
                            
                            <button class="btn btn-danger btn-xs swal-disable"
                              data-id="<?= $final['LearnerId'] ?>"
                              data-name="<?= htmlspecialchars($final['Name']) ?>"
                            >Disable</button>

                            <a href="learnerprofile.php?id=<?php echo $final['LearnerId'] ?>" class="btn btn-xs btn-primary">Open Profile</a>
                            
                          </td>
                        </tr>
                      <?php endwhile; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="5" class="text-center">No learners found for this class.</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                  <tfoot style="background-color: #f9f9f9;">
                    <tr>
                      <th>StNo.</th>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Grade</th>
                      <th class="text-center">Actions</th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <aside class="control-sidebar control-sidebar-dark">
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane" id="control-sidebar-home-tab"></div>
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
        </form>
      </div>
    </div>
  </aside>
  <div class="control-sidebar-bg"></div>
</div>

<!-- Scripts -->
<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

<!-- Enable DataTable features (search, sort, pagination) -->
<script>
  $(function () {
    $('#inviteTable').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,  // <-- This shows the search bar
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true
    });

    // disable with SweetAlert
    $('.swal-disable').on('click', function () {
      const id = $(this).data('id');
      const name = $(this).data('name');
      Swal.fire({
        title: 'Are you sure?',
        text: `Disable ${name} from the System?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#aaa',
       // cancelButtonText: 'give the cancel button a custom name',
        confirmButtonText: 'Yes, Disable!'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = `updatelearnerlist.php?disable_id=${id}`;
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
