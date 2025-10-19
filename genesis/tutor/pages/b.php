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
          <h3 class="box-title" style="color:#3c8dbc;">
            <i class="fa fa-info-circle"></i> Learner Information
          </h3>
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
              <p><strong>Total Activities:</strong> 5</p>
              <p><strong>Marked:</strong> 3 | <strong>Pending:</strong> 2</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Unmarked Activities -->
      <div class="box box-warning" style="border-top: 3px solid #f39c12;">
        <div class="box-header with-border" style="background-color:#fffaf0;">
          <h3 class="box-title" style="color:#f39c12;">
            <i class="fa fa-hourglass-half"></i> Unmarked Activities
          </h3>
        </div>
        <div class="box-body">
          <table id="unmarkedTable" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Activity Title</th>
                <th>File / Answer</th>
                <th>Date Submitted</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>Algebra Assignment 1</td>
                <td>
                  <a href="#" class="btn btn-xs btn-primary">
                    <i class="fa fa-file"></i> View File
                  </a><br>
                  <small class="text-muted">Answer: “x = 2, y = 3”</small>
                </td>
                <td>15 Oct 2025 - 13:25</td>
                <td>
                  <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#gradeModal">
                    <i class="fa fa-pencil"></i> Grade
                  </button>
                </td>
              </tr>
              <tr>
                <td>2</td>
                <td>Trigonometry Quiz</td>
                <td>
                  <a href="#" class="btn btn-xs btn-primary">
                    <i class="fa fa-file"></i> View File
                  </a><br>
                  <small class="text-muted">Answer: “sin(30) = 0.5”</small>
                </td>
                <td>16 Oct 2025 - 10:45</td>
                <td>
                  <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#gradeModal">
                    <i class="fa fa-pencil"></i> Grade
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Marked Activities -->
      <div class="box box-success" style="border-top: 3px solid #00a65a;">
        <div class="box-header with-border" style="background-color:#f0fff0;">
          <h3 class="box-title" style="color:#00a65a;">
            <i class="fa fa-check-circle"></i> Marked Activities
          </h3>
        </div>
        <div class="box-body">
          <table id="markedTable" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Activity Title</th>
                <th>File / Answer</th>
                <th>Grade (%)</th>
                <th>Feedback</th>
                <th>Date Submitted</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>Linear Equations Test</td>
                <td>
                  <a href="#" class="btn btn-xs btn-primary">
                    <i class="fa fa-file"></i> View File
                  </a><br>
                  <small class="text-muted">Answer: “Solved all correctly”</small>
                </td>
                <td>88</td>
                <td>Excellent understanding!</td>
                <td>12 Oct 2025 - 09:50</td>
                <td>
                  <button class="btn btn-info btn-sm">
                    <i class="fa fa-eye"></i> Review
                  </button>
                </td>
              </tr>
              <tr>
                <td>2</td>
                <td>Quadratic Worksheet</td>
                <td>
                  <a href="#" class="btn btn-xs btn-primary">
                    <i class="fa fa-file"></i> View File
                  </a><br>
                  <small class="text-muted">Answer: “x = -2 or x = 3”</small>
                </td>
                <td>75</td>
                <td>Good effort, minor calculation errors.</td>
                <td>10 Oct 2025 - 14:10</td>
                <td>
                  <button class="btn btn-info btn-sm">
                    <i class="fa fa-eye"></i> Review
                  </button>
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

<!-- Grade Modal -->
<div class="modal fade" id="gradeModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-pencil"></i> Grade Submission</h4>
      </div>
      <div class="modal-body">
        <p><strong>Activity:</strong> Algebra Assignment 1</p>
        <div class="form-group">
          <label>Grade (%)</label>
          <input type="number" class="form-control" placeholder="Enter grade (0-100)">
        </div>
        <div class="form-group">
          <label>Feedback</label>
          <textarea class="form-control" rows="3" placeholder="Enter feedback"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button class="btn btn-success"><i class="fa fa-check"></i> Save Grade</button>
      </div>
    </div>
  </div>
</div>

<!-- JS scripts -->
<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script>
  $(function () {
    $('#unmarkedTable').DataTable();
    $('#markedTable').DataTable();
  });
</script>

</body>
</html>
