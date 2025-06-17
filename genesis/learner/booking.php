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
  <?php include("learnerpartials/header.php") ?>
  <?php include("learnerpartials/mainsidebar.php") ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1 style="color:#3a3a72; font-weight:600;">Book a Tutoring Session</h1>
    </section>

    <section class="content">
      <div class="row">
        <!-- Booking Form -->
        <div class="col-md-6">
          <div class="box" style="border-top: 3px solid #6a52a3; border-radius:10px;">
            <div class="box-header with-border">
              <h3 class="box-title" style="color:#6a52a3; font-weight:600;">Booking Form</h3>
            </div>
            <div class="box-body">
              <form action="#" method="POST">
                <div class="form-group">
                  <label for="subject" style="color:#3a3a72;">Subject</label>
                  <select name="subject" class="form-control input-sm" required>
                    <option value="">Select Subject</option>
                    <option value="Mathematics">Mathematics</option>
                    <option value="Physical Sciences">Physical Sciences</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="date" style="color:#3a3a72;">Date</label>
                  <input type="date" name="date" class="form-control input-sm" required>
                </div>

                <div class="form-group">
                  <label for="time" style="color:#3a3a72;">Time</label>
                  <input type="time" name="time" class="form-control input-sm" required>
                </div>

                <div class="form-group">
                  <label for="notes" style="color:#3a3a72;">Additional Notes (Optional)</label>
                  <textarea name="notes" rows="3" class="form-control input-sm"></textarea>
                </div>

                <button type="submit" class="btn" style="background-color:#3a3a72; color:white; font-weight:600; border-radius:5px;">Book Session</button>
              </form>
            </div>
          </div>
        </div>

        <!-- My Bookings -->
        <div class="col-md-6">
          <div class="box" style="border-top: 3px solid #3a3a72; border-radius:10px;">
            <div class="box-header with-border">
              <h3 class="box-title" style="color:#3a3a72; font-weight:600;">My Bookings</h3>
            </div>
            <div class="box-body table-responsive">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr style="background-color:#f0f4ff; color:#3a3a72;">
                    <th>Date</th>
                    <th>Time</th>
                    <th>Subject</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>2025-06-20</td>
                    <td>15:00</td>
                    <td>Mathematics</td>
                    <td><span class="label" style="background-color:#28a745; color:white; border-radius:4px; padding:3px 8px;">Confirmed</span></td>
                  </tr>
                  <tr>
                    <td>2025-06-25</td>
                    <td>14:00</td>
                    <td>Physical Sciences</td>
                    <td><span class="label" style="background-color:#f0ad4e; color:white; border-radius:4px; padding:3px 8px;">Pending</span></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<script>
  $(function () {
    $('table').DataTable();
  });
</script>
</body>
</html>
