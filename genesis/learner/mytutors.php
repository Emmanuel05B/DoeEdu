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
      <h1>My Tutors</h1>
      <p>Meet the tutors assigned to assist you in Mathematics and Physical Sciences.</p>
    </section>

    <section class="content">
      <div class="row">

        <!-- Example Tutor Profile -->
        <div class="col-md-4">
          <div class="box box-primary">
            <div class="box-header with-border text-center">
              <img src="../assets/tutors/tutor1.jpg" alt="Tutor Picture" class="img-circle" width="80" height="80">
              <h3 class="box-title" style="margin-top:10px;">Mr. Thabo Mokoena</h3>
              <p>Subjects: Mathematics</p>
            </div>
            <div class="box-body">
              <p><strong>Email:</strong> thabo@doe.co.za</p>
              <p><strong>Availability:</strong> Mon - Fri, 16:00 - 19:00</p>
              <hr>
              <a href="feedback.php?tutor=thabo" class="btn btn-sm btn-info">Give Feedback</a>
              <a href="rate.php?tutor=thabo" class="btn btn-sm btn-warning">Rate Tutor</a>
              <a href="booking.php?tutor=thabo" class="btn btn-sm btn-primary">Book Session</a>
            </div>
          </div>
        </div>

        <!-- Example Tutor Profile -->
        <div class="col-md-4">
          <div class="box box-success">
            <div class="box-header with-border text-center">
              <img src="../assets/tutors/tutor2.jpg" alt="Tutor Picture" class="img-circle" width="80" height="80">
              <h3 class="box-title" style="margin-top:10px;">Ms. Lerato Dlamini</h3>
              <p>Subjects: Physical Sciences</p>
            </div>
            <div class="box-body">
              <p><strong>Email:</strong> lerato@doe.co.za</p>
              <p><strong>Availability:</strong> Tue, Wed, Sat (Flexible)</p>
              <hr>
              <a href="feedback.php?tutor=lerato" class="btn btn-sm btn-info">Give Feedback</a>
              <a href="rate.php?tutor=lerato" class="btn btn-sm btn-warning">Rate Tutor</a>
              <a href="booking.php?tutor=lerato&subject=Physical Sciences" class="btn btn-sm btn-primary">Book Session</a>
            </div>
          </div>
        </div>

        <!-- Add more tutor cards as needed -->

      </div>

    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>
</body>
</html>
