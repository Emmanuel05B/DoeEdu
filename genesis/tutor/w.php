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
        Issue Reporting
        <small>Report problems or escalate learner issues</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="tutor_dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Issue Reporting</li>
      </ol>
    </section>

    <section class="content">

      <!-- New Issue Form -->
      <div class="box box-danger">
        <div class="box-header with-border" style="background-color:#d46a6a; color:#fff;">
          <h3 class="box-title">Submit New Issue</h3>
        </div>
        <div class="box-body" style="background-color:#f9d6d6;">
          <form action="submit_issue.php" method="POST">
            <div class="form-group">
              <label for="issueCategory">Category</label>
              <select id="issueCategory" name="category" class="form-control" required>
                <option value="" disabled selected>Select category</option>
                <option value="technical">Technical</option>
                <option value="learner_behavior">Learner Behavior</option>
                <option value="content_issue">Content Issue</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div class="form-group">
              <label for="issueDescription">Description</label>
              <textarea id="issueDescription" name="description" class="form-control" rows="5" placeholder="Describe the issue in detail..." required></textarea>
            </div>
            <button type="submit" class="btn btn-danger"><i class="fa fa-exclamation-triangle"></i> Submit Issue</button>
          </form>
        </div>
      </div>

      <!-- Previous Issues -->
      <div class="box box-warning">
        <div class="box-header with-border" style="background-color:#f0ad4e; color:#fff;">
          <h3 class="box-title">Previous Reports</h3>
        </div>
        <div class="box-body" style="background-color:#fff3cd;">
          <table class="table table-bordered table-hover">
            <thead style="background-color:#ffeeba;">
              <tr>
                <th>ID</th>
                <th>Category</th>
                <th>Description</th>
                <th>Status</th>
                <th>Date Submitted</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>#1234</td>
                <td>Technical</td>
                <td>Unable to upload homework attachments.</td>
                <td><span class="label label-warning">Pending</span></td>
                <td>2025-06-18</td>
                <td>
                  <button class="btn btn-xs btn-info" title="Edit"><i class="fa fa-edit"></i></button>
                  <button class="btn btn-xs btn-danger" title="Cancel"><i class="fa fa-times"></i></button>
                </td>
              </tr>
              <tr>
                <td>#1235</td>
                <td>Learner Behavior</td>
                <td>Repeated disruptive behavior in class.</td>
                <td><span class="label label-success">Resolved</span></td>
                <td>2025-06-10</td>
                <td>
                  <button class="btn btn-xs btn-info" title="View"><i class="fa fa-eye"></i></button>
                </td>
              </tr>
              <!-- More reports -->
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
