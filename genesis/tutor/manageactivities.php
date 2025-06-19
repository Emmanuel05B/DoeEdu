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

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="background-color: #f7f9fc;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 style="color:#4a6fa5;">
        Manage Activities
        <small>View, create, and edit homework & assessments</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="tutor_dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Activities</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Add Activity Button -->
      <div class="row mb-3">
        <div class="col-xs-12">
          <button id="btnAddActivity" class="btn btn-primary" style="background-color:#556cd6; border:none;">
            <i class="fa fa-plus"></i> Add New Activity
          </button>
        </div>
      </div>

      <!-- Activities Table -->
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-solid box-primary">
            <div class="box-header with-border" style="background-color:#a3bffa; color:#fff;">
              <h3 class="box-title">Your Activities</h3>
            </div>
            <div class="box-body table-responsive no-padding" style="background-color:#e8eeff;">
              <table class="table table-hover table-bordered">
                <thead style="background-color:#d1d9ff;">
                  <tr>
                    <th>Title</th>
                    <th>Subject</th>
                    <th>Grade</th>
                    <th>Due Date</th>
                    <th>Total Marks</th>
                    <th>Status</th>
                    <th style="width:120px;">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Sample rows - replace with dynamic rows -->
                  <tr>
                    <td>Chapter 1 Homework</td>
                    <td>Mathematics</td>
                    <td>Grade 10</td>
                    <td>2025-06-30</td>
                    <td>50</td>
                    <td><span class="label label-success">Active</span></td>
                    <td>
                      <button class="btn btn-xs btn-info" title="Edit"><i class="fa fa-edit"></i></button>
                      <button class="btn btn-xs btn-danger" title="Delete"><i class="fa fa-trash"></i></button>
                    </td>
                  </tr>
                  <tr>
                    <td>Science Quiz</td>
                    <td>Science</td>
                    <td>Grade 11</td>
                    <td>2025-07-05</td>
                    <td>40</td>
                    <td><span class="label label-success">Active</span></td>
                    <td>
                      <button class="btn btn-xs btn-info" title="Edit"><i class="fa fa-edit"></i></button>
                      <button class="btn btn-xs btn-danger" title="Delete"><i class="fa fa-trash"></i></button>
                    </td>
                  </tr>
                  <tr>
                    <td>History Assignment</td>
                    <td>History</td>
                    <td>Grade 9</td>
                    <td>2025-06-01</td>
                    <td>30</td>
                    <td><span class="label label-danger">Expired</span></td>
                    <td>
                      <button class="btn btn-xs btn-info" title="Edit"><i class="fa fa-edit"></i></button>
                      <button class="btn btn-xs btn-danger" title="Delete"><i class="fa fa-trash"></i></button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- To-Do List and Quick Links -->
      <div class="row">

        <div class="col-md-4">
          <div class="box box-warning">
            <div class="box-header with-border" style="background-color:#b2d8b2; color:#355e35;">
              <h3 class="box-title">To-Do List</h3>
            </div>
            <div class="box-body" style="background-color:#dff0d8;">
              <ul class="todo-list">
                <li><i class="fa fa-circle-o text-green"></i> Prepare next week's quiz</li>
                <li><i class="fa fa-circle-o text-green"></i> Review learner submissions</li>
                <li><i class="fa fa-circle-o text-green"></i> Update study materials</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="col-md-8">
          <div class="box box-info">
            <div class="box-header with-border" style="background-color:#9f86d1; color:#fff;">
              <h3 class="box-title">Quick Links</h3>
            </div>
            <div class="box-body" style="background-color:#dcd7f7;">
              <a href="tutor_create_activity.php" class="btn btn-success" style="margin-right:10px;">Create Activity</a>
              <a href="tutor_activities_report.php" class="btn btn-primary">View Reports</a>
              <a href="tutor_messages.php" class="btn btn-warning" style="color:#fff;">Messages</a>
            </div>
          </div>
        </div>

      </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


</div>
<!-- ./wrapper -->

<!-- Include necessary scripts -->
<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../dist/js/app.min.js"></script>

<script>
  document.getElementById('btnAddActivity').addEventListener('click', function() {
    window.location.href = 'tutor_create_activity.php';
  });
</script>

</body>
</html>
