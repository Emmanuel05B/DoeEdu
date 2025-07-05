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
      <h1>Notes</h1>
      <p>Grade 10 | Mathematics</p>
    </section>

    <section class="content">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Available Notes</h3>
        </div>

        <div class="box-body">
          <table id="notesTable" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th style="width: 30%">Topic</th>
                <th>Description</th>
                <th style="width: 15%">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Algebra Basics</td>
                <td>Understand variables, expressions, and solving simple equations.</td>
                <td><a href="../resources/grade10-maths-algebra.pdf" target="_blank" class="btn btn-sm btn-primary">Download</a></td>
              </tr>
              <tr>
                <td>Trigonometry</td>
                <td>Explore sine, cosine, tangent, and solving triangles.</td>
                <td><a href="../resources/grade10-maths-trigonometry.pdf" target="_blank" class="btn btn-sm btn-info">Download</a></td>
              </tr>
              <tr>
                <td>Number Patterns</td>
                <td>Learn to identify and describe arithmetic and geometric patterns.</td>
                <td><a href="../resources/grade10-maths-patterns.pdf" target="_blank" class="btn btn-sm btn-warning">Download</a></td>
              </tr>
              <tr>
                <td>Geometry</td>
                <td>Basic geometry rules: angles, lines, shapes and reasoning.</td>
                <td><a href="../resources/grade10-maths-geometry.pdf" target="_blank" class="btn btn-sm btn-danger">Download</a></td>
              </tr>
              <tr>
                <td>Functions & Graphs</td>
                <td>Intro to linear and quadratic functions and their graphs.</td>
                <td><a href="../resources/grade10-maths-functions.pdf" target="_blank" class="btn btn-sm btn-success">Download</a></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<!-- Scripts -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- DataTables -->
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script src="dist/js/adminlte.min.js"></script>

<script>
  $(function () {
    $('#notesTable').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "pageLength": 5,
      "lengthMenu": [5, 10, 25, 50]
    });
  });
</script>

</body>
</html>
