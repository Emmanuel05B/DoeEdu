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

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

    <section class="content-header">
      <h1>Grade 11 Mathematics <small>Study Resources</small></h1>
      <ol class="breadcrumb">
        <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Study Resources</li>
      </ol>
    </section>

    <!-- Main Content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Available Resources</h3>
            </div>

            <div class="box-body">
              <div class="row">

                <!-- Resource Card 1 -->
                <div class="col-md-4">
                  <div class="box box-widget widget-user" style="border-top: 3px solid #3c8dbc;">
                    <div class="box-body" style="background-color: #f9f9f9;">
                      <h4 class="text-primary">Notes</h4>
                      <p><strong>Type:</strong> PDF Documents</p>
                      <p><strong>Total:</strong> 20</p>
                      <a href="pdfs.php" class="btn btn-sm btn-primary pull-right">
                        <i class="fa fa-view"></i> View/Open
                      </a>
                    </div>
                  </div>
                </div>

                <!-- Resource Card 2 -->
                <div class="col-md-4">
                  <div class="box box-widget widget-user" style="border-top: 3px solid #3c8dbc;">
                    <div class="box-body" style="background-color: #f9f9f9;">
                      <h4 class="text-primary">Powerpoint Slides</h4>
                      <p><strong>Type:</strong> Slides</p>
                      <p><strong>Total:</strong> 23</p>
                      <a href="slides.php" class="btn btn-sm btn-primary pull-right">
                        <i class="fa fa-view"></i> View/Open
                      </a>
                    </div>
                  </div>
                </div>

                <!-- Resource Card 3 -->
                <div class="col-md-4">
                  <div class="box box-widget widget-user" style="border-top: 3px solid #3c8dbc;">
                    <div class="box-body" style="background-color: #f9f9f9;">
                      <h4 class="text-primary">Videos</h4>
                      <p><strong>Type:</strong> Video</p>
                      <p><strong>Total:</strong> 15</p>
                      <a href="vids.php" class="btn btn-sm btn-primary pull-right">
                        <i class="fa fa-open"></i> View/Open
                      </a>
                    </div>
                  </div>
                </div>

                <!-- Add more resource cards here -->

              </div>
              <div class="row">

                <!-- Resource Card 4 -->
                <div class="col-md-4">
                  <div class="box box-widget widget-user" style="border-top: 3px solid #3c8dbc;">
                    <div class="box-body" style="background-color: #f9f9f9;">
                      <h4 class="text-primary">Audio</h4>
                      <p><strong>Type:</strong> Audio</p>
                      <p><strong>Total:</strong> 5</p>
                      <a href="audio.php" class="btn btn-sm btn-primary pull-right">
                        <i class="fa fa-view"></i> View/Open
                      </a>
                    </div>
                  </div>
                </div>

                <!-- Resource Card 5 -->
                <div class="col-md-4">
                  <div class="box box-widget widget-user" style="border-top: 3px solid #3c8dbc;">
                    <div class="box-body" style="background-color: #f9f9f9;">
                      <h4 class="text-primary">Images</h4>
                      <p><strong>Type:</strong> Image</p>
                      <p><strong>Total:</strong> 10</p>
                      <a href="images.php" class="btn btn-sm btn-primary pull-right">
                        <i class="fa fa-open"></i> View/Open
                      </a>
                    </div>
                  </div>
                </div>

                <!-- Resource Card 3 -->
                <div class="col-md-4">
                  <div class="box box-widget widget-user" style="border-top: 3px solid #3c8dbc;">
                    <div class="box-body" style="background-color: #f9f9f9;">
                      <h4 class="text-primary">Documents</h4>
                      <p><strong>Type:</strong> Docs</p>
                      <p><strong>Total:</strong> 30</p>
                      <a href="#" class="btn btn-sm btn-primary pull-right">
                        <i class="fa fa-open"></i> View/Open
                      </a>
                    </div>
                  </div>
                </div>

                <!-- Add more resource cards here -->

              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

  </div>

  <div class="control-sidebar-bg"></div>
</div>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>


</body>
</html>
