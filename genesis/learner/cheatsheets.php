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
      <h1>Cheat Sheets</h1>
      <p>Grade 10 | Mathematics</p>
    </section>

    <section class="content">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Quick Reference Materials</h3>
        </div>

        <div class="box-body">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th style="width: 30%">Topic</th>
                <th>Description</th>
                <th style="width: 15%">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Algebra Formulas</td>
                <td>Common algebraic formulas and shortcuts at a glance.</td>
                <td><a href="../resources/cheatsheet-algebra.pdf" target="_blank" class="btn btn-sm btn-primary">Download</a></td>
              </tr>
              <tr>
                <td>Trig Ratios</td>
                <td>Cheat sheet on sine, cosine, and tangent rules for right-angle triangles.</td>
                <td><a href="../resources/cheatsheet-trig.pdf" target="_blank" class="btn btn-sm btn-info">Download</a></td>
              </tr>
              <tr>
                <td>Functions & Graphs</td>
                <td>Quick overview of function types and how their graphs look.</td>
                <td><a href="../resources/cheatsheet-functions.pdf" target="_blank" class="btn btn-sm btn-warning">Download</a></td>
              </tr>
              <tr>
                <td>Geometry Facts</td>
                <td>Angle rules, shape properties, and basic theorems summarized.</td>
                <td><a href="../resources/cheatsheet-geometry.pdf" target="_blank" class="btn btn-sm btn-danger">Download</a></td>
              </tr>
              <tr>
                <td>Measurement Units</td>
                <td>Important formulas and unit conversions used in Maths.</td>
                <td><a href="../resources/cheatsheet-measurements.pdf" target="_blank" class="btn btn-sm btn-success">Download</a></td>
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
<script src="dist/js/adminlte.min.js"></script>

</body>
</html>
