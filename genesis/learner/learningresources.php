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
      <h1>Learning Resources</h1>
      <p>Access curated study materials for Mathematics and Physical Sciences</p>
    </section>

    <section class="content">

      <!-- Category Tabs -->
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#math" data-toggle="tab">Mathematics</a></li>
          <li><a href="#science" data-toggle="tab">Physical Sciences</a></li>
        </ul>

        <div class="tab-content">

          <!-- Mathematics Tab -->
          <div class="tab-pane active" id="math">
            <div class="row">
              <!-- Example Resource -->
              <div class="col-md-4">
                <div class="box box-primary">
                  <div class="box-header with-border">
                    <h3 class="box-title">Grade 11 Algebra Guide</h3>
                  </div>
                  <div class="box-body">
                    <p>Download a comprehensive guide on Algebra basics and tips.</p>
                    <a href="../resources/math-algebra-guide.pdf" target="_blank" class="btn btn-sm btn-primary">Download PDF</a>
                  </div>
                </div>
              </div>

              <!-- Example Resource -->
              <div class="col-md-4">
                <div class="box box-info">
                  <div class="box-header with-border">
                    <h3 class="box-title">Functions Cheat Sheet</h3>
                  </div>
                  <div class="box-body">
                    <p>Quick formulas and examples for understanding functions.</p>
                    <a href="../resources/functions-cheatsheet.pdf" target="_blank" class="btn btn-sm btn-info">View Now</a>
                  </div>
                </div>
              </div>

              <!-- Example Video Resource -->
              <div class="col-md-4">
                <div class="box box-success">
                  <div class="box-header with-border">
                    <h3 class="box-title">Video: Solving Quadratics</h3>
                  </div>
                  <div class="box-body">
                    <p>Watch this YouTube video tutorial on solving quadratic equations.</p>
                    <a href="https://www.youtube.com/watch?v=video_id" target="_blank" class="btn btn-sm btn-success">Watch Video</a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Physical Sciences Tab -->
          <div class="tab-pane" id="science">
            <div class="row">
              <!-- Example Resource -->
              <div class="col-md-4">
                <div class="box box-warning">
                  <div class="box-header with-border">
                    <h3 class="box-title">Grade 10 Chemistry Basics</h3>
                  </div>
                  <div class="box-body">
                    <p>Download notes on atoms, bonding, and chemical equations.</p>
                    <a href="../resources/chemistry-basics.pdf" target="_blank" class="btn btn-sm btn-warning">Download PDF</a>
                  </div>
                </div>
              </div>

              <!-- Example Resource -->
              <div class="col-md-4">
                <div class="box box-danger">
                  <div class="box-header with-border">
                    <h3 class="box-title">Physics Formulas</h3>
                  </div>
                  <div class="box-body">
                    <p>All important Physics formulas in one place!</p>
                    <a href="../resources/physics-formulas.pdf" target="_blank" class="btn btn-sm btn-danger">View PDF</a>
                  </div>
                </div>
              </div>

              <!-- Example Video -->
              <div class="col-md-4">
                <div class="box box-success">
                  <div class="box-header with-border">
                    <h3 class="box-title">Video: Newton's Laws</h3>
                  </div>
                  <div class="box-body">
                    <p>Watch a tutorial on Newton’s Laws of Motion.</p>
                    <a href="https://www.youtube.com/watch?v=another_video" target="_blank" class="btn btn-sm btn-success">Watch Video</a>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>

    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>
</body>
</html>
