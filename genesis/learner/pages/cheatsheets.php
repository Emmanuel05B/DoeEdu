<!DOCTYPE html>
<html>
<?php

require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");
include_once(COMMON_PATH . "/../partials/head.php");  

?>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

<?php include_once(LEARNER_PATH . "/../partials/header.php"); ?>
<?php include_once(LEARNER_PATH . "/../partials/mainsidebar.php"); ?>


    <div class="content-wrapper">
      <section class="content-header">
        <h1>Cheat Sheets <small>Grade 10 | Mathematics</small></h1>
        <ol class="breadcrumb">
          <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">xxxx</li>
        </ol>
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
<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>


</body>
</html>
