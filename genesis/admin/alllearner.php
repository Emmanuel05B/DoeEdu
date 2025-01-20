<!DOCTYPE html>
<html>
    
<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}
?>

<?php include("adminpartials/head.php"); ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <!-- Left side column. contains the logo and sidebar -->
  <?php include("adminpartials/header.php") ?>;
  <!-- Left side column. contains the logo and sidebar -->
  <?php include("adminpartials/mainsidebar.php") ?>;

  <!-- Content Wrapper. Contains page content --->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <?php
      include('../partials/connect.php');

    ?> 
    </section>

    <!-- Main content table---------------------------------------------> 
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <!-- /.box -->

          <div class="box">
 
            <div class="box-header">
              
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>StNo.</th>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Grade</th>
                      <th>Progress</th>
                      <th>More</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php
                       // $sql = "SELECT * FROM learners WHERE Grade = 10 AND Math > 0";


                        $statusValue = intval($_GET['val']);  // Ensure it's an integer
          
                        // Check the status and render different HTML for each case
                        if ($statusValue == 1) {
                            echo '<h3>Grade 12 Mathematics Learners</h3><br>';

                            /*/ SQL query for learners owing money and with unexpired contracts
                            $sql = "SELECT lt.*, ls.* 
                                    FROM learners AS lt
                                    JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
                                    WHERE lt.Grade = 12 AND lt.Math > 0
                                    AND ls.ContractExpiryDate = (
                                        SELECT MAX(ls2.ContractExpiryDate)
                                        FROM learnersubject AS ls2
                                        WHERE ls2.LearnerId = ls.LearnerId
                                    )
                                    AND ls.ContractExpiryDate > CURDATE()";  */

                                    $sql = "SELECT lt.*, ls.* 
                                    FROM learners AS lt
                                    JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
                                    WHERE lt.Grade = 12 AND lt.Math > 0 AND ls.SubjectId = 1
                                    AND ls.Status = 'Active' ";   // but this also includes physics.

                        } else if ($statusValue == 2) {

                            echo '<h3>Grade 12 Physical Sciences Learners</h3><br>';
        
                            // SQL query for learners not owing money and with unexpired contracts
                            $sql = "SELECT lt.*, ls.* 
                            FROM learners AS lt
                            JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
                            WHERE lt.Grade = 12 AND lt.Physics > 0 AND ls.SubjectId = 2
                            AND ls.Status = 'Active' ";

                        } else if ($statusValue == 3) {
                            echo '<h3>Grade 11 Mathematics Learners</h3><br>';
        
                            // SQL query for learners not owing money and with expired contracts
                            $sql = "SELECT lt.*, ls.* 
                                    FROM learners AS lt
                                    JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
                                    WHERE lt.Grade = 11 AND lt.Math > 0 AND ls.SubjectId = 3
                                    AND ls.Status = 'Active' ";

                        } else if ($statusValue == 4) {
                            echo '<h3>Grade 11 Physical Sciences Learners</h3><br>';

                            // SQL query for learners not owing money and with expired contracts
                            $sql = "SELECT lt.*, ls.* 
                            FROM learners AS lt
                            JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
                            WHERE lt.Grade = 11 AND lt.Physics > 0 AND ls.SubjectId = 4
                            AND ls.Status = 'Active' ";


                        } else if ($statusValue == 5) {
                            echo '<h3>Grade 10 Mathematics Learners</h3><br>';

                            $sql = "SELECT lt.*, ls.* 
                            FROM learners AS lt
                            JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
                            WHERE lt.Grade = 10 AND lt.Math > 0 AND ls.SubjectId = 5
                            AND ls.Status = 'Active' ";

         
                        } else if ($statusValue == 6) {
                            echo '<h3>Grade 10 Physical Sciences Learners</h3><br>';
        
                            // SQL query for learners owing money and with expired contracts
                            $sql = "SELECT lt.*, ls.* 
                            FROM learners AS lt
                            JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
                            WHERE lt.Grade = 10 AND lt.Physics > 0 AND ls.SubjectId = 6
                            AND ls.Status = 'Active' ";

                        } else {
                            // Default case if none of the statuses match
                            echo '<h1>Learners - Unknown Status</h1>';
                        }

                        $results = $connect->query($sql);
                        while($final = $results->fetch_assoc()) { ?>
                            <tr>
                                
                              <td> 
                              <?php echo $final['LearnerId'] ?>
                              </td>
                              <td>
                                <?php echo $final['Name'] ?>
                              </td>
                              <td>
                                <?php echo $final['Surname'] ?>
                              </td>

                              <td>
                                <?php echo $final['Grade'] ?>
                              </td>
                              <td>
                                <p><a href="tracklearnerprogress.php?id=<?php echo $final['LearnerId'] ?>&val=<?php echo $_GET['val'] ?>" class="btn btn-block btn-primary">Track Progress</a></p>
                              </td>
                              <td>
                                <p><a href="learnerprofile.php?id=<?php echo $final['LearnerId'] ?>" class="btn btn-block btn-primary">Open Profile</a></p>
                              </td>

                          </tr>

                    <?php } ?>
                  </tbody>

                  <tfoot>
                    <tr>
                      <th>StNo.</th>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Grade</th>
                      <th>Progress</th>
                      <th>More</th>
                    </tr>
                  </tfoot>
                </table>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
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
<!-- DataTables -->
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>

<script>
  $(function () {
    $('#example1').DataTable()
  })
</script>

</body>
</html>
