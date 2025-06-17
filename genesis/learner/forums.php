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
      <h1>Discussion Forum</h1>
      <p>Ask questions and help each other understand Maths and Science better.</p>
    </section>

    <section class="content">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab_maths" data-toggle="tab">🧮 Mathematics</a></li>
          <li><a href="#tab_science" data-toggle="tab">🔬 Physical Sciences</a></li>
        </ul>
        <div class="tab-content">
          <!-- Mathematics Tab -->
          <div class="tab-pane active" id="tab_maths">
            <div class="box box-info">
              <div class="box-header with-border">
                <h3 class="box-title">Maths Questions</h3>
              </div>
              <div class="box-body">
                <div class="form-group">
                  <label>Ask a Question</label>
                  <textarea class="form-control" rows="3" placeholder="Type your Maths question here..."></textarea>
                  <button class="btn btn-primary btn-sm" style="margin-top: 10px;">Post</button>
                </div>

                <hr>
                <!-- Sample Thread -->
                <div class="post">
                  <p><strong>Learner Thando:</strong> How do you solve a quadratic inequality like x² - 5x + 6 < 0?</p>
                  <p><strong>Tutor Lunga:</strong> Factor it into (x - 2)(x - 3) < 0 and analyze the sign chart!</p>
                </div>
                <hr>
                <div class="post">
                  <p><strong>Learner Ayanda:</strong> What’s the easiest way to remember trig identities?</p>
                  <p><em>No replies yet.</em></p>
                </div>
              </div>
            </div>
          </div>

          <!-- Science Tab -->
          <div class="tab-pane" id="tab_science">
            <div class="box box-warning">
              <div class="box-header with-border">
                <h3 class="box-title">Physical Sciences Questions</h3>
              </div>
              <div class="box-body">
                <div class="form-group">
                  <label>Ask a Question</label>
                  <textarea class="form-control" rows="3" placeholder="Type your Physical Science question here..."></textarea>
                  <button class="btn btn-warning btn-sm" style="margin-top: 10px;">Post</button>
                </div>

                <hr>
                <!-- Sample Thread -->
                <div class="post">
                  <p><strong>Learner Kamo:</strong> Can someone explain Newton's 3rd law with an example?</p>
                  <p><strong>Tutor Musa:</strong> Sure! When you push a wall, it pushes back with equal force. Action = Reaction!</p>
                </div>
                <hr>
                <div class="post">
                  <p><strong>Learner Zinhle:</strong> What’s the difference between fission and fusion?</p>
                  <p><em>No replies yet.</em></p>
                </div>
              </div>
            </div>
          </div>
        </div> <!-- /.tab-content -->
      </div> <!-- /.nav-tabs-custom -->
    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>
</body>
</html>
