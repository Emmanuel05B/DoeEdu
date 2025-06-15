<!DOCTYPE html>
<html>

<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}
include("learnerpartials/head.php");
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include("learnerpartials/header.php") ?>
  <?php include("learnerpartials/mainsidebar.php") ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>My Homework</h1>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Upcoming and Completed Homework</h3>
            </div>

            <div class="box-body table-responsive">
              <table class="table table-bordered table-striped" id="homeworkTable" style="width:100%;">
                <thead>
                  <tr>
                    <th>Title</th>
                    <th>Subject</th>
                    <th>Chapter</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Total Marks</th>
                    <th>Score</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Sample static rows (Replace with dynamic PHP later) -->
                  <tr>
                    <td>Linear Equations</td>
                    <td>Mathematics</td>
                    <td>Algebra</td>
                    <td>2025-06-20 17:00</td>
                    <td><span class="label label-warning">Not Started</span></td>
                    <td>20</td>
                    <td>-</td>
                    <td><a href="viewhomework.php?id=1" class="btn btn-primary btn-sm">Open</a></td>
                  </tr>
                  <tr>
                    <td>Periodic Table</td>
                    <td>Physical Sciences</td>
                    <td>Chemistry</td>
                    <td>2025-06-18 23:59</td>
                    <td><span class="label label-success">Completed</span></td>
                    <td>15</td>
                    <td>12</td>
                    <td><a href="viewresults.php?id=2" class="btn btn-success btn-sm">View Results</a></td>
                  </tr>
                </tbody>
              </table>
            </div>

          </div>
        </div>
      </div>
    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<!-- Scripts -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>

<script>
  $(function () {
    $('#homeworkTable').DataTable({
      responsive: true,
      autoWidth: false
    });
  });
</script>

</body>
</html>
