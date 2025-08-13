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
      <h1 style="color:#3a3a72; font-weight:600;">Student Voices</h1>
      <p style="color:#6a52a3;">Share your thoughts, feedback, or suggestions. You can choose to stay anonymous.</p>
    </section>

    <section class="content">
      <div class="row">
        <!-- Feedback Form -->
        <div class="col-md-8">
          <div class="box" style="border-top: 3px solid #6a52a3; border-radius:10px;">
            <div class="box-header with-border">
              <h3 class="box-title" style="color:#6a52a3; font-weight:600;">Submit Feedback</h3>
            </div>
            <div class="box-body">
              <form action="#" method="POST">
                <div class="form-group">
                  <label for="subject" style="color:#3a3a72;">Topic (Optional)</label>
                  <input type="text" name="subject" class="form-control input-sm" placeholder="e.g. Suggestion, Complaint, Appreciation">
                </div>

                <div class="form-group">
                  <label for="message" style="color:#3a3a72;">Message</label>
                  <textarea name="message" rows="5" class="form-control input-sm" placeholder="Write what's on your mind..." required></textarea>
                </div>

                <div class="form-group" style="color:#3a3a72;">
                  <label>
                    <input type="checkbox" name="anonymous" value="1"> Submit as Anonymous
                  </label>
                </div>

                <button type="submit" class="btn" style="background-color:#3a3a72; color:white; font-weight:600; border-radius:5px;">Submit Feedback</button>
              </form>
            </div>
          </div>
        </div>

        <!-- Why Your Voice Matters -->
        <div class="col-md-4">
          <div class="box" style="border-top: 3px solid #f0ad4e; border-radius:10px;">
            <div class="box-header with-border">
              <h3 class="box-title" style="color:#f0ad4e; font-weight:600;">Why Your Voice Matters</h3>
            </div>
            <div class="box-body" style="color:#3a3a72; font-weight:500;">
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
