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
        Announcements
        <small>Create and view important updates</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="tutor_dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Announcements</li>
      </ol>
    </section>

    <section class="content">
      <!-- Create Button -->
      <div class="row mb-3">
        <div class="col-xs-12">
          <button class="btn btn-primary" onclick="location.href='create_announcement.php'" style="background-color:#556cd6; border:none;">
            <i class="fa fa-bullhorn"></i> New Announcement
          </button>
        </div>
      </div>

      <!-- Announcements Table -->
      <div class="box box-info">
        <div class="box-header with-border" style="background-color:#a3bffa; color:#fff;">
          <h3 class="box-title">Recent Announcements</h3>
        </div>
        <div class="box-body table-responsive" style="background-color:#e8eeff;">
          <table class="table table-hover table-bordered">
            <thead style="background-color:#d1d9ff;">
              <tr>
                <th>Title</th>
                <th>Posted By</th>
                <th>Audience</th>
                <th>Subject</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <!-- Sample row -->
              <tr>
                <td>Upcoming Algebra Test</td>
                <td>Mr. Khumalo (You)</td>
                <td>Grade 10 Learners</td>
                <td>Mathematics</td>
                <td>2025-06-20</td>
                <td>
                  <button class="btn btn-xs btn-info" title="Edit"><i class="fa fa-edit"></i></button>
                  <button class="btn btn-xs btn-danger" title="Delete"><i class="fa fa-trash"></i></button>
                </td>
              </tr>
              <!-- More announcements go here -->
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
