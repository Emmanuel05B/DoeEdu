<!DOCTYPE html> 
<html>
<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}
?>
<?php include("adminpartials/head.php"); ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include("adminpartials/header.php") ?>
  <?php include("adminpartials/mainsidebar.php") ?>

  <div class="content-wrapper">
    <section class="content-header">
      <?php include('../partials/connect.php'); ?> 
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <?php
                $subjectId = isset($_GET['id']) ? intval($_GET['id']) : 0;

              if ($subjectId) {
                $stmt = $connect->prepare("
                  SELECT lt.*, ls.*, u.Name, u.Surname, s.SubjectName
                  FROM learners lt
                  JOIN learnersubject ls ON lt.LearnerId = ls.LearnerId
                  JOIN users u ON lt.LearnerId = u.Id
                  JOIN subjects s ON ls.SubjectId = s.SubjectId
                  WHERE ls.SubjectId = ? AND ls.ContractExpiryDate > CURDATE()
                ");
                $stmt->bind_param("i", $subjectId);
                $stmt->execute();
                $results = $stmt->get_result();
                $subjectName = ($row = $results->fetch_assoc()) ? $row['SubjectName'] : null;
                $results->data_seek(0); // reset for looping
              } else {
                $results = null;
                $subjectName = null;
              }

               if ($subjectId) {
                  echo "<h2 class='box-title'>Learners for: {$subjectName}</h2>";
                } else {
                  echo "<h3 class='box-title'>Learners List</h3>";
                }

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
                            <a href="updatelearners.php?id=<?php echo $final['LearnerId'] ?>" class="btn btn-sm btn-warning">Update</a>
                            <a href="disablelearner.php?id=<?php echo $final['LearnerId'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to disable this learner?');">Disable</a>
                            <a href="learnerprofile.php?id=<?php echo $final['LearnerId'] ?>&val=<?php echo $subjectId ?>" class="btn btn-sm btn-primary">Open Profile</a>
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
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="dist/js/demo.js"></script>

<!-- Enable DataTable features (search, sort, pagination) -->
<script>
  $(function () {
    $('#example1').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,  // <-- This shows the search bar
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true
    });
  });
</script>

</body>
</html>
