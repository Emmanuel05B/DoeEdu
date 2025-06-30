<!DOCTYPE html>
<html>
<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}
?>
<?php include("tutorpartials/head.php"); ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include("tutorpartials/header.php") ?>
  <?php include("tutorpartials/mainsidebar.php") ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>My Classe(s)</h1>
      <p class="text-muted">Below are the subjects you're currently teaching.</p>
    </section>

    <section class="content">
      <div class="row">

        <!-- Subject Card Example -->
        <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border text-center" style="background-color:#a3bffa;">
              <h3 class="box-title" style="margin:10px auto;">Grade 12</h3>
              <p><i class="fa fa-book"></i> Mathematics</p>
              <p><i class="fa fa-users"></i> <strong>18 learners</strong></p> 
            </div>
            <div class="box-body text-center">
              <!-- <a href="alllearner.php?grade=12&subject=mathematics" class="btn btn-info btn-sm">Open Class</a>
               -->
              <a href="maths12.php" class="btn btn-info btn-sm">Record Marks</a>
              <a href="alllearner.php?val=1" class="btn btn-info btn-sm">Track Learner Progress</a>
              <a href="alllearner.php?val=1" class="btn btn-info btn-sm">Open Class</a>

            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border text-center" style="background-color:#a3bffa;">
              <h3 class="box-title" style="margin:10px auto;">Grade 10</h3>
              <p><i class="fa fa-book"></i> Mathematics</p>
              <p><i class="fa fa-users"></i> <strong>16 learners</strong></p> 
            </div>
            <div class="box-body text-center">
              <!-- <a href="alllearner.php?grade=12&subject=mathematics" class="btn btn-info btn-sm">Open Class</a>
               -->
              <a href="maths12.php" class="btn btn-info btn-sm">Record Marks</a>
              <a href="alllearner.php?val=1" class="btn btn-info btn-sm">Track Learner Progress</a>
              <a href="alllearner.php?val=1" class="btn btn-info btn-sm">Open Class</a>

            </div>
          </div>
        </div>

        <!-- Duplicate for other subjects -->
        <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border text-center" style="background-color:#d5a6ff;">
              <h3 class="box-title" style="margin:10px auto;">Grade 11</h3>
              <p><i class="fa fa-book"></i> Physical Sciences</p>
              <p><i class="fa fa-users"></i> <strong>28 learners</strong></p> 

            </div>
            <div class="box-body text-center">
              <a href="alllearner.php?grade=11&subject=physicalsciences" class="btn btn-info btn-sm">Open Class</a>
            </div>
          </div>
        </div>

  

        <!-- Continue duplicating blocks as needed -->

      </div>
    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>
</body>
</html>
