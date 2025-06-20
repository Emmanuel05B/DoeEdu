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

<?php include("tutorpartials/header.php"); ?>

<!-- Left side column. contains the logo and sidebar -->
<?php include("tutorpartials/mainsidebar.php"); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Grade 11 Physical Sciences
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Chapters</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header">
              <i class="fa fa-edit"></i>

              <h3 class="box-title">Chapters</h3>
            </div>
            <div class="box-body pad table-responsive">
              <p>Select chapter</p>
              <table class="table table-bordered text-center">
                <tr>
                  <th>Term 1</th>
                  <th>Term 2</th>
                  <th>Term 3</th>
                  <th>Term 4/Revision</th>
                </tr>
                <tr>
                  <td>
                    <a href="recmodal.php?gra=11&sub=4&cha=Vectors In 2 dimensions" class="btn btn-block btn-info btn-lg">Vectors in two dimensions</a>
                  </td>
                  <td>
                    <a href="recmodal.php?gra=11&sub=4&cha=Intermolecular Forces" class="btn btn-block btn-warning btn-lg"> Intermolecular forces</a>
                  </td>
                  <td>
                    <a href="recmodal.php?gra=11&sub=4&cha=Ideal Gases" class="btn btn-block btn-success btn-lg">Ideal gases</a>
                  </td>
                  <td>
                    <a href="recmodal.php?gra=11&sub=4&cha=Electromagnetism" class="btn btn-block btn-primary btn-lg">Electromagnetism</a>
                  </td>
                </tr>
                <tr>
                  <td>
                    <a href="recmodal.php?gra=11&sub=4&cha=Newtons Laws" class="btn btn-block btn-info btn-lg">Newtons laws</a>
                  </td>
                  <td>
                    <a href="recmodal.php?gra=11&sub=4&cha=Geometrical Optics" class="btn btn-block btn-warning btn-lg">Geometrical optics</a>
                  </td>
                  <td>
                    <a href="recmodal.php?gra=11&sub=4&cha=Quantitative Aspects Of Chemical Change" class="btn btn-block btn-success btn-lg">Quantitative aspects of chemical change</a>
                  </td>
                  <td>
                    <a href="recmodal.php?gra=11&sub=4&cha=Electric Circuits" class="btn btn-block btn-primary btn-lg">Electric circuits</a>
                  </td>
                </tr>
                <tr>
                  <td>
                    <a href="recmodal.php?gra=11&sub=4&cha=Atomic Combinations" class="btn btn-block btn-info btn-lg">Atomic combinations</a>
                  </td>
                  <td>
                    <a href="recmodal.php?gra=11&sub=4&cha=2d and 3d wavefronts" class="btn btn-block btn-warning btn-lg">2d and 3d wavefronts</a>
                  </td>
                  <td>
                    <a href="recmodal.php?gra=11&sub=4&cha=Electrostatics" class="btn btn-block btn-success btn-lg">Electrostatics</a>
                  </td>
                  <td>
                    <a href="recmodal.php?gra=11&sub=4&cha=Energy and Chemical Change" class="btn btn-block btn-primary btn-lg">Energy and chemical change</a>
                  </td>
                </tr>
                <tr>
                  <td>
                    <a href="#recmodal.php?gra=11&sub=4&cha=14" class="btn btn-block btn-info btn-lg">a</a>
                  </td>
                  <td>
                    <a href="#recmodal.php?gra=11&sub=4&cha=24" class="btn btn-block btn-warning btn-lg">b</a>
                  </td>
                  <td>
                    <a href="recmodal.php?gra=11&sub=4&cha=Types Of Reactions" class="btn btn-block btn-success btn-lg">Types of reactions</a>
                  </td>
                  <td>
                    <a href="recmodal.php?gra=11&sub=4&cha=The Lithosphere" class="btn btn-block btn-primary btn-lg">The lithosphere</a>
                  </td>
                </tr>
              </table>
            </div>
            <!-- /.box -->
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /. row -->
    </section>
    <!-- /.content -->
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
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

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
