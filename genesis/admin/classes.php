<!DOCTYPE html>
<html>
<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}
include("adminpartials/head.php");
include('../partials/connect.php');
?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include("adminpartials/header.php") ?>
  <?php include("adminpartials/mainsidebar.php") ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>My Classe(s)</h1>
      <p class="text-muted">Below are the subjects you're currently teaching.</p>
    </section>

    <section class="content">
      <div class="row">

      <?php
        $tutorId = $_SESSION['user_id'];

        $sql = "SELECT * FROM directorsubjects  WHERE DirectorId = $tutorId";
        $results = $connect->query($sql);

        function getSubjectDetails($subjectId) {
              $grade = null;
              $subjectName = null;

              // Determine grade
              if ($subjectId == 1 || $subjectId == 4) $grade = 10;
              elseif ($subjectId == 2 || $subjectId == 5) $grade = 11;
              elseif ($subjectId == 3 || $subjectId == 6) $grade = 12;

              // Determine subject name
              if (in_array($subjectId, [1, 2, 3])) {
                  $subjectName = "Mathematics";
              } elseif (in_array($subjectId, [4, 5, 6])) {
                  $subjectName = "Physical Sciences";
              }

              return ['grade' => $grade, 'subjectName' => $subjectName];
          }


        while ($row = $results->fetch_assoc()) {
          $subjectId = $row['SubjectId'];
          
          $details = getSubjectDetails($subjectId);
          $grade = $details['grade'];
          $subjectName = $details['subjectName'];

          // Count learners
          $countSQL = "SELECT COUNT(*) AS learnerCount 
                       FROM learnersubject ls 
                       JOIN learners l ON ls.LearnerId = l.LearnerId 
                       WHERE ls.SubjectId = $subjectId 
                         AND l.Grade = $grade 
                         AND ls.ContractExpiryDate > CURDATE()";

          $countResult = $connect->query($countSQL)->fetch_assoc();
          $learnerCount = $countResult['learnerCount'];
      ?>
        <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border text-center" style="background-color:#a3bffa;">
              <h3 class="box-title" style="margin:10px auto;">Grade <?php echo $grade; ?></h3>
              <p><i class="fa fa-book"></i> <?php echo $subjectName; ?></p>
              <p><i class="fa fa-users"></i> <strong><?php echo $learnerCount; ?> learner<?php echo $learnerCount != 1 ? 's' : ''; ?></strong></p> 
            </div>
            <div class="box-body text-center">
              <a href="chapters.php?subject=<?php echo $subjectId ?>&grade=<?php echo $grade ?>" class="btn btn-info btn-sm">Record Marks</a>
              <a href="actychapters.php?subject=<?php echo $subjectId ?>&grade=<?php echo $grade ?>" class="btn btn-info btn-sm">Create Activity</a>
              <a href="managestudymaterials.php?subject=<?php echo $subjectId ?>&grade=<?php echo $grade ?>" class="btn btn-info btn-sm">Resources</a>
              <a href="alllearner.php?subject=<?php echo $subjectId ?>&grade=<?php echo $grade ?>" class="btn btn-info btn-sm">Open Class</a>
            </div>  
          </div>
        </div>
      <?php } ?>

      </div>
    </section>
  </div>



    <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>

      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-user bg-yellow"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                <p>New phone +1(800)555-1234</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                <p>nora@example.com</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-file-code-o bg-green"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                <p>Execution time 5 seconds</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="label label-danger pull-right">70%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Update Resume
                <span class="label label-success pull-right">95%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Laravel Integration
                <span class="label label-warning pull-right">50%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Back End Framework
                <span class="label label-primary pull-right">68%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Allow mail redirect
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Other sets of options are available
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Expose author name in posts
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Allow the user to show his name in blog posts
            </p>
          </div>
          <!-- /.form-group -->

          <h3 class="control-sidebar-heading">Chat Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Show me as online
              <input type="checkbox" class="pull-right" checked>
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Turn off notifications
              <input type="checkbox" class="pull-right">
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Delete chat history
              <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
            </label>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  
  <div class="control-sidebar-bg"></div>
</div>
  <!-- jQuery 3 -->
  <script src="bower_components/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- FastClick -->
  <script src="bower_components/fastclick/lib/fastclick.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>
</body>
</html>
