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
      <h1>Student Voices</h1>
      <p>Share your thoughts, feedback, or suggestions. You can choose to stay anonymous.</p>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-md-8">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Submit Feedback</h3>
            </div>
            <div class="box-body">
              <form action="#" method="POST">
                <div class="form-group">
                  <label for="subject">Topic (Optional)</label>
                  <input type="text" name="subject" class="form-control input-sm" placeholder="e.g. Suggestion, Complaint, Appreciation">
                </div>

                <div class="form-group">
                  <label for="message">Message</label>
                  <textarea name="message" rows="5" class="form-control input-sm" placeholder="Write what's on your mind..." required></textarea>
                </div>

                <div class="form-group">
                  <label>
                    <input type="checkbox" name="anonymous" value="1"> Submit as Anonymous
                  </label>
                </div>

                <button type="submit" class="btn btn-success btn-sm">Submit Feedback</button>
              </form>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Why Your Voice Matters</h3>
            </div>
            <div class="box-body">
              <p>Your feedback helps us understand your experience, improve our services, and ensure that your voice is heard. Feel free to be open and honest.</p>
              <p>You can share anything—your thoughts about classes, tutors, ideas, or even concerns.</p>
              <p>Thank you for helping us grow together!</p>
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
