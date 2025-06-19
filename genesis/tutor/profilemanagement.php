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
        Profile Management
        <small>Update your information and availability</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="tutor_dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Profile Management</li>
      </ol>
    </section>

    <section class="content">

      <div class="box box-primary">
        <div class="box-header with-border" style="background-color:#a3bffa; color:#fff;">
          <h3 class="box-title">Edit Profile</h3>
        </div>
        <div class="box-body" style="background-color:#e8eeff;">
          <form action="save_profile.php" method="POST" enctype="multipart/form-data">
            <div class="row">

              <div class="col-md-4 text-center">
                <img src="profile_pics/default_avatar.png" alt="Profile Picture" class="img-thumbnail" style="width:150px; height:150px;">
                <br><br>
                <input type="file" name="profile_pic" accept="image/*" class="form-control">
              </div>

              <div class="col-md-8">
                <div class="form-group">
                  <label>Full Name</label>
                  <input type="text" name="fullname" class="form-control" value="John Doe" required>
                </div>

                <div class="form-group">
                  <label>Email</label>
                  <input type="email" name="email" class="form-control" value="john.doe@example.com" required>
                </div>

                <div class="form-group">
                  <label>Phone Number</label>
                  <input type="tel" name="phone" class="form-control" value="+27 123 456 7890">
                </div>

                <div class="form-group">
                  <label>Availability</label>
                  <select name="availability" class="form-control" required>
                    <option value="fulltime" selected>Full Time</option>
                    <option value="parttime">Part Time</option>
                    <option value="weekends">Weekends Only</option>
                    <option value="evenings">Evenings Only</option>
                  </select>
                </div>

                <div class="form-group">
                  <label>Change Password</label>
                  <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
                </div>

                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Changes</button>
              </div>

            </div>
          </form>
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
