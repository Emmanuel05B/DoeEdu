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
      <h1>Announcements & News</h1>
      <p>Stay up to date with important updates, tips, and events from your Tutors and Directors.</p>
    </section>

    <section class="content">
      <!-- Scrolling Banner -->
      <div class="callout callout-info" style="background-color: #3c8dbc; color: white;">
        <marquee behavior="scroll" direction="left" scrollamount="5">
          🗓️ Exam prep workshop this Saturday at 10AM • 📢 Mid-year exams start on July 1st • 📚 New study materials uploaded under Resources!
        </marquee>
      </div>

      <!-- Detailed Announcements -->
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Latest Announcements</h3>
        </div>
        <div class="box-body">
          <div class="post">
            <h4><strong>🔔 Mid-Year Exam Tips</strong></h4>
            <p>Posted by Tutor Lerato · 2025-06-17</p>
            <p>Focus on past papers and practice problems from the Trigonometry and Forces chapters. Don’t forget to take breaks and rest well!</p>
          </div>
          <hr>
          <div class="post">
            <h4><strong>🧪 Science Revision Workshop</strong></h4>
            <p>Posted by Director Mokoena · 2025-06-15</p>
            <p>Join us this Saturday at 10AM in the virtual classroom. We'll revise key concepts for the Physical Sciences exam and take questions.</p>
          </div>
          <hr>
          <div class="post">
            <h4><strong>🎉 School Holiday Notice</strong></h4>
            <p>Posted by Admin · 2025-06-12</p>
            <p>Classes will pause between June 24–28. Use this time to rest and review your progress. Weekly check-ins will resume July 1.</p>
          </div>
        </div>
      </div>
    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

</body>
</html>
