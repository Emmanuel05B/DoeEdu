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
        Attendance Tracking
        <small>Mark and review learner attendance</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="tutor_dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Attendance Tracking</li>
      </ol>
    </section>

    <section class="content">

      <!-- Attendance Form -->
      <div class="box box-primary">
        <div class="box-header with-border" style="background-color:#a3bffa; color:#fff;">
          <h3 class="box-title">Mark Attendance</h3>
        </div>
        <div class="box-body" style="background-color:#e8eeff;">
          <form action="save_attendance.php" method="POST">
            <div class="row">
              <div class="col-md-4 form-group">
                <label>Select Class/Group</label>
                <select name="class_group" class="form-control" required>
                  <option value="" disabled selected>Select group</option>
                  <option>Grade 10 - Mathematics</option>
                  <option>Grade 11 - Science</option>
                  <!-- Dynamic groups -->
                </select>
              </div>
              <div class="col-md-4 form-group">
                <label>Date</label>
                <input type="date" name="attendance_date" class="form-control" required>
              </div>
            </div>

            <hr>

            <!-- Learners List -->
            <div class="form-group">
              <label>Mark Learners Present</label>
              <div style="max-height: 300px; overflow-y: auto; background: #fff; padding: 10px; border: 1px solid #ccc;">
                <div class="checkbox">
                  <label><input type="checkbox" name="attendance[]" value="learner1"> Jane Dlamini</label>
                </div>
                <div class="checkbox">
                  <label><input type="checkbox" name="attendance[]" value="learner2"> Thabo Mokoena</label>
                </div>
                <div class="checkbox">
                  <label><input type="checkbox" name="attendance[]" value="learner3"> Sipho Nkosi</label>
                </div>
                <!-- More learners -->
              </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Attendance</button>
          </form>
        </div>
      </div>

      <!-- Recent Attendance Records -->
      <div class="box box-success">
        <div class="box-header with-border" style="background-color:#b2d8b2; color:#355e35;">
          <h3 class="box-title">Recent Attendance Records</h3>
        </div>
        <div class="box-body" style="background-color:#eaf4e4;">
          <table class="table table-bordered table-hover">
            <thead style="background-color:#d7f7c2;">
              <tr>
                <th>Date</th>
                <th>Class/Group</th>
                <th>Present</th>
                <th>Absent</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>2025-06-18</td>
                <td>Grade 10 - Mathematics</td>
                <td>25</td>
                <td>3</td>
                <td>
                  <button class="btn btn-xs btn-info" title="View"><i class="fa fa-eye"></i></button>
                  <button class="btn btn-xs btn-danger" title="Delete"><i class="fa fa-trash"></i></button>
                </td>
              </tr>
              <!-- More records -->
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
