<!DOCTYPE html>
<html>


<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../../common/pages/login.php");
  exit();
}

include(__DIR__ . "/../../common/partials/queries.php");
?>
<?php include(__DIR__ . "/../../common/partials/head.php"); ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  <!-- Main Content -->
  <div class="content-wrapper"> 
    <section class="content-header">
      <h1>
        <i class="fa fa-user"></i> Learner Submissions
        <small>View and Grade Work</small>
      </h1>
    </section>

    <section class="content">

      <!-- Learner Info Card -->
      <div class="box box-primary" style="border-top: 3px solid #3c8dbc;">
        <div class="box-header with-border" style="background-color:#f0f8ff;">
          <h3 class="box-title" style="color:#3c8dbc;"><i class="fa fa-info-circle"></i> Learner Information</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-4">
              <p><strong>Name:</strong> John Doe</p>
              <p><strong>Email:</strong> johndoe@example.com</p>
            </div>
            <div class="col-md-4">
              <p><strong>Grade:</strong> 10A</p>
              <p><strong>Subject:</strong> Mathematics</p>
            </div>
            <div class="col-md-4">
              <p><strong>Activity:</strong> Algebra Assignment 1</p>
              <p><strong>Due Date:</strong> 15 Oct 2025</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Submission List -->
      <div class="box box-info" style="border-top: 3px solid #00c0ef;">
        <div class="box-header with-border" style="background-color:#f0f8ff;">
          <h3 class="box-title" style="color:#00c0ef;"><i class="fa fa-file-text-o"></i> Submitted Work</h3>
        </div>
        <div class="box-body">
          <table id="submissionTable" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>File / Answer</th>
                <th>Date Submitted</th>
                <th>Grade (%)</th>
                <th>Feedback</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>
                  <a href="#" class="btn btn-xs btn-primary"><i class="fa fa-file"></i> View File</a><br>
                  <small class="text-muted">Answer: "x = 2, y = 3"</small>
                </td>
                <td>15 Oct 2025 - 13:25</td>
                <td>
                  <input type="number" class="form-control input-sm" style="width: 80px;" placeholder="0-100">
                </td>
                <td>
                  <input type="text" class="form-control input-sm" placeholder="Enter feedback">
                </td>
                <td>
                  <button class="btn btn-success btn-sm"><i class="fa fa-check"></i> Save</button>
                </td>
              </tr>
              <tr>
                <td>2</td>
                <td>
                  <a href="#" class="btn btn-xs btn-primary"><i class="fa fa-file"></i> View File</a><br>
                  <small class="text-muted">Answer: "Simplified the equation..."</small>
                </td>
                <td>16 Oct 2025 - 10:45</td>
                <td>
                  <input type="number" class="form-control input-sm" style="width: 80px;" value="85">
                </td>
                <td>
                  <input type="text" class="form-control input-sm" value="Good effort!">
                </td>
                <td>
                  <button class="btn btn-success btn-sm"><i class="fa fa-check"></i> Save</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </section>
  </div>

  <footer class="main-footer text-center">
    <small>© 2025 Tutor Panel</small>
  </footer>

</div>

<!-- JS scripts -->
<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script>
  $(function () {
    $('#submissionTable').DataTable();
  });
</script>

</body>
</html>
