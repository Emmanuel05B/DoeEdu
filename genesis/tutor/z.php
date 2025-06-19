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
        Record Marks
        <small>Enter and manage learner scores</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="tutor_dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Record Marks</li>
      </ol>
    </section>

    <section class="content">

      <!-- Record Mark Form -->
      <div class="box box-primary">
        <div class="box-header with-border" style="background-color:#a3bffa; color:#fff;">
          <h3 class="box-title">Add New Mark</h3>
        </div>
        <div class="box-body" style="background-color:#e8eeff;">
          <form action="save_mark.php" method="POST">
            <div class="row">
              <div class="col-md-4 form-group">
                <label>Learner</label>
                <select name="learner" class="form-control" required>
                  <option value="" disabled selected>Select learner</option>
                  <option>Jane Dlamini</option>
                  <option>Thabo Mokoena</option>
                  <!-- Dynamic options -->
                </select>
              </div>
              <div class="col-md-4 form-group">
                <label>Subject</label>
                <select name="subject" class="form-control" required>
                  <option>Mathematics</option>
                  <option>English</option>
                  <option>Science</option>
                  <!-- Dynamic options -->
                </select>
              </div>
              <div class="col-md-4 form-group">
                <label>Assessment</label>
                <select name="assessment" class="form-control" required>
                  <option>Homework 1</option>
                  <option>Quiz 3</option>
                  <option>Test 2</option>
                  <!-- Dynamic options -->
                </select>
              </div>
              <div class="col-md-3 form-group">
                <label>Score</label>
                <input type="number" name="score" min="0" max="100" class="form-control" required>
              </div>
              <div class="col-md-9 form-group">
                <label>Comments (optional)</label>
                <input type="text" name="comments" class="form-control" placeholder="Remarks or feedback">
              </div>
              <div class="col-md-12">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Mark</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Recent Marks Table -->
      <div class="box box-success">
        <div class="box-header with-border" style="background-color:#b2d8b2; color:#355e35;">
          <h3 class="box-title">Recent Marks</h3>
        </div>
        <div class="box-body" style="background-color:#eaf4e4;">
          <table class="table table-bordered table-hover">
            <thead style="background-color:#d7f7c2;">
              <tr>
                <th>Learner</th>
                <th>Subject</th>
                <th>Assessment</th>
                <th>Score</th>
                <th>Comments</th>
                <th>Date Recorded</th>
                <th style="width:120px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Jane Dlamini</td>
                <td>Mathematics</td>
                <td>Homework 1</td>
                <td>85</td>
                <td>Good effort!</td>
                <td>2025-06-17</td>
                <td>
                  <button class="btn btn-xs btn-info" title="Edit"><i class="fa fa-edit"></i></button>
                  <button class="btn btn-xs btn-danger" title="Delete"><i class="fa fa-trash"></i></button>
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
