<!DOCTYPE html>
<html>
<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}
?>
<?php include("learnerpartials/head.php"); ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include("learnerpartials/header.php"); ?>
  <?php include("learnerpartials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Homework & Assessments</h1>
      <p>Review your assigned tasks, due dates, and completion status.</p>
    </section>

    <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Your Tasks</h3>
        </div>
        <div class="box-body table-responsive">
          <table class="table table-bordered table-striped">
            <thead style="background-color: #3c8dbc; color: white;">
              <tr>
                <th>Title</th>
                <th>Subject</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <!-- Example 1 -->
              <tr>
                <td>Algebra Homework</td>
                <td>Mathematics</td>
                <td>2025-06-20</td>
                <td><span class="label label-warning">Pending</span></td>
                <td><a href="#" class="btn btn-xs btn-primary">Start</a></td>
              </tr>
              <!-- Example 2 -->
              <tr>
                <td>Forces & Motion Quiz</td>
                <td>Physical Sciences</td>
                <td>2025-06-22</td>
                <td><span class="label label-success">Completed</span></td>
                <td><a href="#" class="btn btn-xs btn-default disabled">View</a></td>
              </tr>
              <!-- Example 3 -->
              <tr>
                <td>Trigonometry Test</td>
                <td>Mathematics</td>
                <td>2025-06-15</td>
                <td><span class="label label-danger">Overdue</span></td>
                <td><a href="#" class="btn btn-xs btn-danger">Retry</a></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<script>
  $(function () {
    $('table').DataTable();
  });
</script>

</body>
</html>
