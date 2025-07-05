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
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

    <section class="content-header">
      <h1>Learning Resources</h1>
      <p>Access curated study materials for Mathematics and Physical Sciences</p>
    </section>

    <section class="content">

      <div class="row">
        <!-- MATHS -->
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Mathematics</h3>
            </div>
            <div class="box-body">
              <div class="row">

                <!-- Notes -->
                <div class="col-md-4">
                  <div class="box box-primary">
                    <div class="box-header with-border">
                      <h3 class="box-title">Notes</h3>
                    </div>
                    <div class="box-body">
                      <p>All Mathematics notes from Grade 10–12 in one place.</p>
                      <a href="notes.php" class="btn btn-sm btn-primary">View Notes</a>
                    </div>
                  </div>
                </div>

                <!-- Cheat Sheets -->
                <div class="col-md-4">
                  <div class="box box-info">
                    <div class="box-header with-border">
                      <h3 class="box-title">Cheat Sheets</h3>
                    </div>
                    <div class="box-body">
                      <p>Quick-reference formula sheets and summaries for Maths.</p>
                      <a href="cheatsheets.php" class="btn btn-sm btn-info">View Cheat Sheets</a>
                    </div>
                  </div>
                </div>

                <!-- Videos -->
                <div class="col-md-4">
                  <div class="box box-success">
                    <div class="box-header with-border">
                      <h3 class="box-title">Videos</h3>
                    </div>
                    <div class="box-body">
                      <p>Watch topic-focused tutorials and explanations for Maths.</p>
                      <a href="videos.php" class="btn btn-sm btn-success">Watch Videos</a>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>

        <!-- SCIENCES -->
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Physical Sciences</h3>
            </div>
            <div class="box-body">
              <div class="row">

                <!-- Notes -->
                <div class="col-md-4">
                  <div class="box box-warning">
                    <div class="box-header with-border">
                      <h3 class="box-title">Notes</h3>
                    </div>
                    <div class="box-body">
                      <p>All Physical Sciences notes organized per chapter.</p>
                      <a href="notes.php" class="btn btn-sm btn-warning">View Notes</a>
                    </div>
                  </div>
                </div>

                <!-- Cheat Sheets -->
                <div class="col-md-4">
                  <div class="box box-danger">
                    <div class="box-header with-border">
                      <h3 class="box-title">Cheat Sheets</h3>
                    </div>
                    <div class="box-body">
                      <p>Essential formulas and quick summaries for Physics & Chemistry.</p>
                      <a href="sciences_cheatsheets.php" class="btn btn-sm btn-danger">View Cheat Sheets</a>
                    </div>
                  </div>
                </div>

                <!-- Videos -->
                <div class="col-md-4">
                  <div class="box box-success">
                    <div class="box-header with-border">
                      <h3 class="box-title">Videos</h3>
                    </div>
                    <div class="box-body">
                      <p>Visual learning through topic-based science video tutorials.</p>
                      <a href="sciences_videos.php" class="btn btn-sm btn-success">Watch Videos</a>
                    </div>
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

<!-- Scripts -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="dist/js/demo.js"></script>

</body>
</html>
