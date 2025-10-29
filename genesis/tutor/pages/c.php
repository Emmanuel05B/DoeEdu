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

<?php include_once(TUTOR_PATH . "/../partials/header.php"); ?> 
<?php include_once(TUTOR_PATH . "/../partials/mainsidebar.php"); ?>


  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-upload"></i> Submit Activity
        <small>Upload your work files for a specific activity</small>
      </h1>
    </section>

    <section class="content">

      <!-- Upload Form Box -->
      <div class="box box-primary" style="border-top: 3px solid #3c8dbc;">
        <div class="box-header with-border" style="background-color:#f0f8ff;">
          <h3 class="box-title" style="color:#3c8dbc;">
            <i class="fa fa-file-upload"></i> Upload Your Submission
          </h3>
        </div>

        <form method="post" action="submit_activity_process.php" enctype="multipart/form-data">
          <div class="box-body">

            <!-- Activity Selection -->
            <div class="form-group">
              <label for="activitySelect">Select Activity</label>
              <select class="form-control" id="activitySelect" name="activity_id" required>
                <option value="" disabled selected>-- Choose Activity --</option>
                <option value="1">Mathematics - Algebra Assignment</option>
                <option value="2">Science - Lab Report</option>
                <option value="3">English - Essay</option>
              </select>
            </div>

            <!-- Multiple File Upload -->
            <div class="form-group">
              <label for="fileUpload">Upload Files</label>
              <input type="file" id="fileUpload" name="submission_files[]" class="form-control" 
                     accept=".pdf, .jpg, .jpeg, .png" multiple required>
              <p class="help-block text-muted">You can upload multiple images or one PDF. Accepted formats: PDF, JPG, PNG.</p>

              <!-- File Preview -->
              <div id="previewArea" class="row" style="margin-top:10px;"></div>
            </div>

            <!-- Optional Notes -->
            <div class="form-group">
              <label for="notes">Add a Note (Optional)</label>
              <textarea id="notes" name="notes" class="form-control" rows="2" placeholder="Any comments for your tutor..."></textarea>
            </div>

          </div>

          <div class="box-footer">
            <button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Clear</button>
            <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-paper-plane"></i> Submit</button>
          </div>
        </form>
      </div>

      <!-- Recent Submissions -->
      <div class="box box-info" style="border-top: 3px solid #00c0ef;">
        <div class="box-header with-border" style="background-color:#f0ffff;">
          <h3 class="box-title" style="color:#00c0ef;">
            <i class="fa fa-history"></i> Recent Submissions
          </h3>
        </div>
        <div class="box-body">
          <table id="recentSubmissions" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Activity</th>
                <th>Files Uploaded</th>
                <th>Date Submitted</th>
                <th>Status</th>
                <th>Grade</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>Algebra Assignment</td>
                <td>3 Files</td>
                <td>15 Oct 2025 - 14:30</td>
                <td><span class="label label-warning">Pending</span></td>
                <td>-</td>
                <td><a href="#" class="btn btn-xs btn-info"><i class="fa fa-eye"></i> View</a></td>
              </tr>
              <tr>
                <td>2</td>
                <td>Science Lab Report</td>
                <td>1 PDF</td>
                <td>12 Oct 2025 - 11:10</td>
                <td><span class="label label-success">Marked</span></td>
                <td>88%</td>
                <td><a href="#" class="btn btn-xs btn-info"><i class="fa fa-eye"></i> View</a></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </section>
  </div>

  <footer class="main-footer text-center">
    <small>© 2025 Learner Portal</small>
  </footer>

</div>

<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>

<script>
  $(function () {
    $('#recentSubmissions').DataTable();
  });

  // Preview uploaded images
  $('#fileUpload').on('change', function() {
    const files = this.files;
    const previewArea = $('#previewArea');
    previewArea.html(''); // Clear previous previews

    Array.from(files).forEach(file => {
      if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = e => {
          const col = $('<div class="col-xs-6 col-sm-3">');
          const img = $('<img>').attr('src', e.target.result)
            .css({ width: '100%', borderRadius: '5px', marginBottom: '10px', border: '1px solid #ddd' });
          col.append(img);
          previewArea.append(col);
        };
        reader.readAsDataURL(file);
      } else if (file.type === 'application/pdf') {
        previewArea.append(`
          <div class="col-xs-12">
            <p><i class="fa fa-file-pdf-o text-red"></i> ${file.name}</p>
          </div>
        `);
      }
    });
  });
</script>

</body>
</html>
