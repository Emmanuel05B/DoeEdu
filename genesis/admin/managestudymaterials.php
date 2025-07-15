<!DOCTYPE html>
<html>
<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../common/login.php");
    exit();
}

include('../partials/connect.php');
include("adminpartials/head.php");
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include("adminpartials/header.php"); ?>
  <?php include("adminpartials/mainsidebar.php"); ?>

  <div class="content-wrapper" style="background-color: #f7f9fc;">
    <section class="content-header">
      <h1 style="color:#4a6fa5;">
        Study Resources
        <small>Upload and manage learning materials</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="tutor_dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Study Resources</li>
      </ol>
    </section>

    <section class="content">
      <!-- Upload New Resource -->
      <div class="box box-success">
        <div class="box-header with-border" style="background-color:#b2d8b2; color:#355e35;">
          <h3 class="box-title">Upload New Resource</h3>
        </div>
        <div class="box-body" style="background-color:#eaf4e4;">
          <form action="upload_resource.php" method="POST" enctype="multipart/form-data">
            <div class="row">
              <div class="col-md-4 form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" required>
              </div>
              <div class="col-md-4 form-group">
                <label>Subject</label>
                <select name="subject" class="form-control">
                  <option>Mathematics</option>
                  <option>Science</option>
                  <option>English</option>
                  <option>History</option>
                  <!-- Populate dynamically if needed -->
                </select>
              </div>
              <div class="col-md-4 form-group">
                <label>Grade</label>
                <select name="grade" class="form-control">
                  <option>Grade 8</option>
                  <option>Grade 9</option>
                  <option>Grade 10</option>
                  <option>Grade 11</option>
                  <option>Grade 12</option>
                </select>
              </div>
              <div class="col-md-12 form-group">
                <label>Choose File</label>
                <input type="file" name="resource_file" class="form-control" required>
              </div>
              <div class="col-md-12">
                <button type="submit" class="btn btn-success"><i class="fa fa-upload"></i> Upload</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Existing Resources -->
      <div class="box box-info">
        <div class="box-header with-border" style="background-color:#9f86d1; color:#fff;">
          <h3 class="box-title">Your Uploaded Resources</h3>
        </div>
        <div class="box-body" style="background-color:#f3edff;">
          <table class="table table-hover table-bordered">
            <thead style="background-color:#e0d4fc;">
              <tr>
                <th>Title</th>
                <th>Subject</th>
                <th>Grade</th>
                <th>Uploaded At</th>
                <th style="width:120px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <!-- Sample Row -->
              <tr>
                <td>Algebra Basics PDF</td>
                <td>Mathematics</td>
                <td>Grade 10</td>
                <td>2025-06-01</td>
                <td>
                  <a href="#" class="btn btn-xs btn-primary" title="Download"><i class="fa fa-download"></i></a>
                  <a href="#" class="btn btn-xs btn-danger" title="Delete"><i class="fa fa-trash"></i></a>
                </td>
              </tr>
              <!-- More rows will load dynamically -->
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
