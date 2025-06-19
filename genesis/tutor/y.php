<!DOCTYPE html>
<html>
<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}

include('../partials/connect.php');
include("tutorpartials/head.php");
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include("tutorpartials/header.php"); ?>
  <?php include("tutorpartials/mainsidebar.php"); ?>

  <div class="content-wrapper" style="background-color: #f7f9fc;">
    <section class="content-header">
      <h1 style="color:#4a6fa5;">
        Resources
        <small>Upload and share study materials</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="tutor_dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Resources</li>
      </ol>
    </section>

    <section class="content">

      <!-- Upload Resource Button -->
      <div class="row mb-3">
        <div class="col-xs-12">
          <button class="btn btn-primary" onclick="location.href='upload_resource.php'" style="background-color:#556cd6; border:none;">
            <i class="fa fa-upload"></i> Upload Resource
          </button>
          <button class="btn btn-success" onclick="location.href='add_link.php'" style="margin-left:10px;">
            <i class="fa fa-link"></i> Add External Link
          </button>
        </div>
      </div>

      <!-- Resource List -->
      <div class="box box-info">
        <div class="box-header with-border" style="background-color:#a3bffa; color:#fff;">
          <h3 class="box-title">Shared Resources</h3>
        </div>
        <div class="box-body table-responsive" style="background-color:#e8eeff;">
          <table class="table table-hover table-bordered">
            <thead style="background-color:#d1d9ff;">
              <tr>
                <th>Title</th>
                <th>Type</th>
                <th>Subject</th>
                <th>Grade</th>
                <th>Date Uploaded</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <!-- Sample row -->
              <tr>
                <td>Algebra Notes</td>
                <td>File (PDF)</td>
                <td>Mathematics</td>
                <td>Grade 10</td>
                <td>2025-06-15</td>
                <td>
                  <button class="btn btn-xs btn-info" title="Edit"><i class="fa fa-edit"></i></button>
                  <button class="btn btn-xs btn-danger" title="Delete"><i class="fa fa-trash"></i></button>
                  <a href="#" class="btn btn-xs btn-success" title="Download"><i class="fa fa-download"></i></a>
                </td>
              </tr>
              <tr>
                <td>Physics Video Tutorial</td>
                <td>External Link</td>
                <td>Science</td>
                <td>Grade 11</td>
                <td>2025-06-10</td>
                <td>
                  <button class="btn btn-xs btn-info" title="Edit"><i class="fa fa-edit"></i></button>
                  <button class="btn btn-xs btn-danger" title="Delete"><i class="fa fa-trash"></i></button>
                  <a href="#" target="_blank" class="btn btn-xs btn-success" title="Open Link"><i class="fa fa-external-link"></i></a>
                </td>
              </tr>
              <!-- More resources -->
            </tbody>
          </table>
        </div>
      </div>

    </section>
  </div>

</div>

<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../dist/js/app.min.js"></script>

</body>
</html>
