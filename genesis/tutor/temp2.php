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
        Learner Progress Reports
        <small>Track individual and class performance</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="tutor_dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Progress Reports</li>
      </ol>
    </section>

    <section class="content">

      <!-- Filter Panel -->
      <div class="box box-info">
        <div class="box-header with-border" style="background-color:#9f86d1; color:#fff;">
          <h3 class="box-title">Filter by Grade, Subject, or Learner</h3>
        </div>
        <div class="box-body" style="background-color:#ede7f6;">
          <form class="form-inline">
            <div class="form-group">
              <label>Grade: </label>
              <select class="form-control" name="grade">
                <option>All</option>
                <option>Grade 8</option>
                <option>Grade 9</option>
                <option>Grade 10</option>
                <option>Grade 11</option>
                <option>Grade 12</option>
              </select>
            </div>
            <div class="form-group">
              <label>Subject: </label>
              <select class="form-control" name="subject">
                <option>All</option>
                <option>Mathematics</option>
                <option>English</option>
                <option>Science</option>
                <option>History</option>
              </select>
            </div>
            <div class="form-group">
              <label>Learner: </label>
              <input type="text" class="form-control" placeholder="Search learner name">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Apply</button>
          </form>
        </div>
      </div>

      <!-- Results Table -->
      <div class="box box-success">
        <div class="box-header with-border" style="background-color:#b2d8b2; color:#355e35;">
          <h3 class="box-title">Learner Performance</h3>
        </div>
        <div class="box-body" style="background-color:#eaf4e4;">
          <table class="table table-bordered table-striped">
            <thead style="background-color:#d7f7c2;">
              <tr>
                <th>Learner Name</th>
                <th>Subject</th>
                <th>Grade</th>
                <th>Average Score</th>
                <th>Last Activity</th>
                <th style="width:100px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <!-- Sample row -->
              <tr>
                <td>Samantha Mokoena</td>
                <td>Mathematics</td>
                <td>Grade 10</td>
                <td>84%</td>
                <td>2025-06-17</td>
                <td>
                  <a href="view_learner_detail.php?id=123" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>
                  <a href="#" class="btn btn-xs btn-warning"><i class="fa fa-download"></i></a>
                </td>
              </tr>
              <!-- More rows load dynamically -->
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
