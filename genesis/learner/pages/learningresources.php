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
        <h1>xxxxxxx <small>xxxx x x x</small></h1>
        <ol class="breadcrumb">
          <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">xxxx</li>
        </ol>
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
<!-- jQuery 3 -->
  <?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

</body>
</html>
