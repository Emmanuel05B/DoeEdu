<!DOCTYPE html>
<html>
<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}
?>
<?php include("learnerpartials/head.php"); ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include("learnerpartials/header.php"); ?>
  <?php include("learnerpartials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Profile Settings</h1>
      <small>Manage your personal information and preferences</small>
    </section>

    <section class="content">
      <div class="row">

        <!-- Personal Information -->
        <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Personal Information</h3>
            </div>
            <div class="box-body">
              <form action="#" method="POST" autocomplete="off">
                <div class="form-group">
                  <label for="fullName">Full Name</label>
                  <input type="text" class="form-control input-sm" id="fullName" name="fullName" placeholder="Your full name" required>
                </div>
                <div class="form-group">
                  <label for="email">Email Address (readonly)</label>
                  <input type="email" class="form-control input-sm" id="email" name="email" readonly value="learner@example.com">
                </div>
                <div class="form-group">
                  <label for="phone">Phone Number</label>
                  <input type="tel" class="form-control input-sm" id="phone" name="phone" placeholder="e.g. +27 123 456 7890">
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Update Info</button>
              </form>
            </div>
          </div>
        </div>

        <!-- Password Change -->
        <div class="col-md-6">
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Change Password</h3>
            </div>
            <div class="box-body">
              <form action="#" method="POST" autocomplete="off">
                <div class="form-group">
                  <label for="currentPassword">Current Password</label>
                  <input type="password" class="form-control input-sm" id="currentPassword" name="currentPassword" placeholder="Current password" required>
                </div>
                <div class="form-group">
                  <label for="newPassword">New Password</label>
                  <input type="password" class="form-control input-sm" id="newPassword" name="newPassword" placeholder="New password" required>
                </div>
                <div class="form-group">
                  <label for="confirmPassword">Confirm New Password</label>
                  <input type="password" class="form-control input-sm" id="confirmPassword" name="confirmPassword" placeholder="Confirm new password" required>
                </div>
                <button type="submit" class="btn btn-danger btn-sm">Change Password</button>
              </form>
            </div>
          </div>
        </div>

      </div>

      <!-- Notification Preferences -->
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Notification Preferences</h3>
            </div>
            <div class="box-body">
              <form action="#" method="POST">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="emailNotifications" checked>
                    Receive notifications by Email
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="smsNotifications">
                    Receive SMS notifications
                  </label>
                </div>
                <button type="submit" class="btn btn-info btn-sm">Save Preferences</button>
              </form>
            </div>
          </div>
        </div>
      </div>

    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>
</body>
</html>
