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

  <div class="content-wrapper">

    <section class="content-header">
      <?php include(__DIR__ . "/../../partials/connect.php"); ?> 

      <h1>Update School Details <small>Manage School profile information</small></h1>
      <ol class="breadcrumb">
        <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Update Schools</li>
      </ol>
    </section><br>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <?php
                $stmt = $connect->prepare("SELECT * FROM schools");
                $stmt->execute();
                $results = $stmt->get_result();

                echo "<h3 class='box-title'>Schools List</h3>";

              ?>
            </div>

            <div class="box-body">
              <div class="table-responsive">
                <table id="example1" class="table table-bordered table-hover">
                  <thead style="background-color: #d1d9ff;">
                    <tr>
                      <th>SNo.</th>
                      <th>School Name</th>
                      <th>School Address</th>
                      <th>School Email</th>
                      <th class="text-center">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($results && $results->num_rows > 0): ?>
                      <?php while($final = $results->fetch_assoc()): ?>
                        <tr>
                          <td><?php echo htmlspecialchars($final['SchoolId']) ?></td>
                          <td><?php echo htmlspecialchars($final['SchoolName']) ?></td>
                          <td><?php echo htmlspecialchars($final['Address']) ?></td>
                          <td><?php echo htmlspecialchars($final['Email']) ?></td>
                          <td class="text-center">
                            <a href="updateschool.php?id=<?php echo $final['SchoolId'] ?>" class="btn btn-sm btn-warning">Update</a>
                            <a href="disableschool.php?id=<?php echo $final['SchoolId'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to disable this school?');">Disable</a>
                            <a href="deleteschool.php?id=<?php echo $final['SchoolId'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this school?');">Delete</a>
                        </td>
                        </tr>
                      <?php endwhile; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="5" class="text-center">No tutors found</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                  <tfoot style="background-color: #f9f9f9;">
                    <tr>
                      <th>SNo.</th>
                      <th>School Name</th>
                      <th>School Address</th>
                      <th>School Email</th>
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
