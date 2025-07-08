<!DOCTYPE html>
<html>
<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}
include("tutorpartials/head.php");
include('../partials/connect.php');
?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include("tutorpartials/header.php") ?>
  <?php include("tutorpartials/mainsidebar.php") ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>My Availability</h1>
      <p class="text-muted">Set up your weekly and custom availability below so learners can book you easily.</p>
    </section>

    <section class="content">
      <div class="row">
        <!-- Weekly Availability Left -->
        <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border" style="background-color:#a3bffa;">
              <h3 class="box-title">Weekly Availability</h3>
            </div>
            <form method="POST" action="saveavailability.php">
              <div class="box-body">
                <!-- Header row with labels -->
                <div class="form-group row align-items-center font-weight-bold" style="padding-left:15px;">
                  <div class="col-sm-3">Day</div>
                  <div class="col-sm-4">Start Time</div>
                  <div class="col-sm-4">End Time</div>
                </div>

                <?php
                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                foreach ($days as $day): ?>
                  <div class="form-group row align-items-center">
                    <div class="col-sm-3">
                      <label>
                        <input type="checkbox" name="days[]" value="<?php echo $day ?>"> <?php echo $day ?>
                      </label>
                    </div>
                    <div class="col-sm-4">
                      <input type="time" name="start[<?php echo $day ?>]" id="start_<?php echo strtolower($day) ?>" class="form-control input-sm">
                    </div>
                    <div class="col-sm-4">
                      <input type="time" name="end[<?php echo $day ?>]" id="end_<?php echo strtolower($day) ?>" class="form-control input-sm">
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Save Weekly Availability</button>
              </div>
            </form>
          </div>
        </div>

        <!-- Custom Exceptions Right -->
        <div class="col-md-6">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Custom Date Exceptions</h3>
            </div>
            <form method="POST" action="saveavailability.php">
              <div class="box-body">
                <div class="form-group">
                  <label>Date</label>
                  <input type="date" name="date" class="form-control input-sm" required>
                </div>
                <div class="form-group">
                  <label>Availability Status</label>
                  <select name="is_available" class="form-control input-sm">
                    <option value="0">Unavailable</option>
                    <option value="1">Available with custom time</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="custom_start">Start Time</label>
                  <input type="time" name="custom_start" id="custom_start" class="form-control input-sm">
                </div>
                <div class="form-group">
                  <label for="custom_end">End Time</label>
                  <input type="time" name="custom_end" id="custom_end" class="form-control input-sm">
                </div>
              </div>
              <div class="box-footer">
                <button type="submit" class="btn btn-warning">Add Exception</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>
</body>
</html>
