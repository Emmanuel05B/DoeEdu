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
        <h1>Notes <small>Grade 10 | Mathematics</small></h1>
        <ol class="breadcrumb">
          <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">xxxx</li>
        </ol>
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
<!-- Scripts -->
<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

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
