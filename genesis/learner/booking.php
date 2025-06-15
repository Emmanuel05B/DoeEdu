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
      <h1>Book a Tutoring Session</h1>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Booking Form</h3>
            </div>
            <div class="box-body">
              <form action="#" method="POST">
                <div class="form-group">
                  <label for="subject">Subject</label>
                  <select name="subject" class="form-control input-sm" required>
                    <option value="">Select Subject</option>
                    <option value="Maths">Maths</option>
                    <option value="Science">Science</option>
                    <option value="English">English</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="date">Date</label>
                  <input type="date" name="date" class="form-control input-sm" required>
                </div>

                <div class="form-group">
                  <label for="time">Time</label>
                  <input type="time" name="time" class="form-control input-sm" required>
                </div>

                <div class="form-group">
                  <label for="notes">Additional Notes (Optional)</label>
                  <textarea name="notes" rows="3" class="form-control input-sm"></textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-sm">Book Session</button>
              </form>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">My Bookings</h3>
            </div>
            <div class="box-body table-responsive">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
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
                    <td>Maths</td>
                    <td><span class="label label-success">Confirmed</span></td>
                  </tr>
                  <tr>
                    <td>2025-06-25</td>
                    <td>14:00</td>
                    <td>Science</td>
                    <td><span class="label label-warning">Pending</span></td>
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
