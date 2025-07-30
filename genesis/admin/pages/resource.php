<!DOCTYPE html>
<html> 
<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../../common/pages/login.php");
  exit();
}
include(__DIR__ . "/../../common/partials/head.php");
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include(__DIR__ . "/../../common/partials/header.php"); ?>
  <?php include(__DIR__ . "/../../common/partials/mainsidebar.php"); ?>

  <div class="content-wrapper">
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

    <section class="content-header">
      <h1>Study Resources <small>Upload and manage learning materials.</small></h1>
      <ol class="breadcrumb">
        <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Study Resources</li>
      </ol>
    </section>

    <section class="content">
      
      <div class="row">
        <!-- Upload Resource - Left Side -->
        <div class="col-md-6">
          <div class="box box-primary" style="border-top: 3px solid #3c8dbc;">
            <div class="box-header with-border" style="background-color:#f0f8ff;">
              <h3 class="box-title" style="color:#3c8dbc;"><i class="fa fa-upload"></i> Upload New Resource</h3>
            </div>
            <div class="box-body" style="background-color:#ffffff;">
              <form action="upload_resource.php" method="POST" enctype="multipart/form-data">
                <div class="row">

                  <!-- Title and Subject & Grade side by side -->
                  <div class="col-md-6 form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" class="form-control" placeholder="E.g. Newton’s Laws Summary" required>
                  </div>

                  <div class="col-md-6 form-group">
                    <label for="subject_grade">Subject & Grade</label>
                    <select name="subject_grade" class="form-control" required>
                      <option value="">Select Subject & Grade</option>
                      <!-- Options from PHP will populate here -->
                    </select>
                  </div>

                  <!-- Type of Resource and Choose File side by side -->
                  <div class="col-md-6 form-group">
                    <label for="resource_type">Type of Resource</label>
                    <select name="resource_type" class="form-control" required>
                      <option value="">Select Type</option>
                      <option value="PDF">PDF Document</option>
                      <option value="Image">Image</option>
                      <option value="Slides">Slides (e.g. PPT)</option>
                      <option value="Video">Video</option>
                    </select>
                  </div>

                  <div class="col-md-6 form-group">
                    <label for="resource_file">Choose File</label>
                    <input type="file" name="resource_file" class="form-control" required>
                  </div>

                  <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-cloud-upload"></i> Upload Resource</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Assign Resource to Class - Right Side -->
        <div class="col-md-6">
          <div class="box box-info" style="border-top: 3px solid #00c0ef;">
            <div class="box-header with-border" style="background-color:#d9f0fb;">
              <h3 class="box-title" style="color:#0073b7;"><i class="fa fa-link"></i> Assign Existing Resource to Class/Group</h3>
            </div>
            <div class="box-body" style="background-color:#ffffff;">
              <form action="assign_resource.php" method="POST">
                <div class="row">

                  <div class="col-md-12 form-group">
                    <label for="resourceId">Select Resource</label>
                    <select name="resourceId" id="resourceId" class="form-control" required>
                      <option value="">-- Select a Resource --</option>
                      <!-- Populate with uploaded resources (id + title) -->
                    </select>
                  </div>

                  <div class="col-md-12 form-group">
                    <label for="classId">Select Class/Group</label>
                    <select name="classId" id="classId" class="form-control" required>
                      <option value="">-- Select a Class/Group --</option>
                      <!-- Populate with tutor's classes/groups -->
                    </select>
                  </div>

                  <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-info"><i class="fa fa-check"></i> Assign Resource</button>
                  </div>

                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Uploaded Resources -->
      <div class="box box-solid" style="border-top: 3px solid #605ca8;">
        <div class="box-header with-border" style="background-color:#f3edff;">
          <h3 class="box-title" style="color:#605ca8;"><i class="fa fa-folder-open"></i> Your Uploaded Resources</h3>
        </div>
        <div class="box-body" style="background-color:#ffffff;">
          <table class="table table-bordered table-hover" id="resourceTable">
            <thead style="background-color:#e6e0fa; color:#333;">
              <tr>
                <th>Title</th>
                <th>Type</th>
                <th>Subject</th>
                <th>Grade</th>
                <th>Uploaded At</th>
                <th style="width:120px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <!-- Resources will populate from PHP -->
            </tbody>
          </table>
        </div>
      </div>

    </section>

  </div>

  <div class="control-sidebar-bg"></div>
</div>

<!-- Scripts -->
<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>


<script>
  $(function () {
    $('#resourceTable').DataTable();
  });
</script>

</body>
</html>
