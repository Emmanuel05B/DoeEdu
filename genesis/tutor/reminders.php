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
        Reminders
        <small>Stay on top of sessions, deadlines & tasks</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="tutor_dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Reminders</li>
      </ol>
    </section>

    <section class="content">
      <!-- Add Reminder Button -->
      <div class="row mb-3">
        <div class="col-xs-12">
          <button class="btn btn-success" onclick="location.href='add_reminder.php'" style="background-color:#6ecf8f; border:none;">
            <i class="fa fa-plus"></i> Add New Reminder
          </button>
        </div>
      </div>

      <!-- Reminders List -->
      <div class="row">
        <div class="col-md-8">
          <div class="box box-solid box-info">
            <div class="box-header with-border" style="background-color:#a3bffa; color:#fff;">
              <h3 class="box-title">Upcoming Reminders</h3>
            </div>
            <div class="box-body" style="background-color:#e8eeff;">
              <ul class="todo-list">
                <!-- Sample reminder -->
                <li>
                  <span class="text">📌 Grade 10 Quiz Review – June 20, 14:00</span>
                  <small class="label label-warning">Due Soon</small>
                  <div class="tools">
                    <button class="btn btn-xs btn-success"><i class="fa fa-check"></i></button>
                    <button class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                  </div>
                </li>
                <li>
                  <span class="text">📝 Upload Physics Study Guide</span>
                  <small class="label label-default">No Deadline</small>
                  <div class="tools">
                    <button class="btn btn-xs btn-success"><i class="fa fa-check"></i></button>
                    <button class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                  </div>
                </li>
                <!-- More dynamic items -->
              </ul>
            </div>
          </div>
        </div>

        <!-- Quick Tips Panel -->
        <div class="col-md-4">
          <div class="box box-default">
            <div class="box-header with-border" style="background-color:#dcd7f7; color:#4a3b7d;">
              <h3 class="box-title">Tips</h3>
            </div>
            <div class="box-body" style="background-color:#f2f0fb;">
              <ul>
                <li>Use reminders for session follow-ups.</li>
                <li>Mark done items to keep things clean.</li>
                <li>Urgent tasks show up in yellow.</li>
              </ul>
            </div>
          </div>
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
