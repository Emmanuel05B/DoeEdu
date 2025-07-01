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
        Session Requests
        <small>Manage session invites from learners</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="tutor_dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Session Requests</li>
      </ol>
    </section>

    <section class="content">

      <div class="box box-primary">
        <div class="box-header with-border" style="background-color:#a3bffa; color:#fff;">
          <h3 class="box-title">Pending Requests</h3>
        </div>
        <div class="box-body table-responsive" style="background-color:#e8eeff;">
          <table class="table table-hover table-bordered">
            <thead style="background-color:#d1d9ff;">
              <tr>
                <th>Learner</th>
                <th>Subject</th>
                <th>Grade</th>
                <th>Date</th>
                <th>Time</th>
                <th>Type</th>
                <th>Notes</th>
                <th>Status</th>
                <th style="width:130px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <!-- Sample row -->
              <tr>
                <td>Jane Dlamini</td>
                <td>Mathematics</td>
                <td>Grade 11</td>
                <td>2025-06-25</td>
                <td>15:00</td>
                <td>One-on-One</td>
                <td>Need help with algebra</td>
                <td><span class="label label-warning">Pending</span></td>
                <td>
                  <button class="btn btn-xs btn-success"><i class="fa fa-check"></i> Accept</button>
                  <button class="btn btn-xs btn-danger"><i class="fa fa-times"></i> Decline</button>
                </td>
              </tr>
              <!-- More dynamic rows go here -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- Accepted Sessions -->
      <div class="box box-success">
        <div class="box-header with-border" style="background-color:#b2d8b2; color:#355e35;">
          <h3 class="box-title">Upcoming Accepted Sessions</h3>
        </div>
        <div class="box-body" style="background-color:#eaf4e4;">
          <table class="table table-striped">
            <thead style="background-color:#dafbe4;">
              <tr>
                <th>Learner</th>
                <th>Subject</th>
                <th>Grade</th>
                <th>Date</th>
                <th>Time</th>
                <th>Type</th>
                <th>Notes</th>
              </tr>
            </thead>
            <tbody>
              <!-- Example row -->
              <tr>
                <td>John Nkosi</td>
                <td>Science</td>
                <td>Grade 9</td>
                <td>2025-06-24</td>
                <td>10:00</td>
                <td>Group</td>
                <td>Exam prep session</td>
              </tr>
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
