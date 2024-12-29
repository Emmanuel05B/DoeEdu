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


<style>

</style>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <!-- Left side column. contains the logo and sidebar -->
  <?php include("adminpartials/header.php") ?>;
  <!-- Left side column. contains the logo and sidebar -->
 <?php include("adminpartials/mainsidebar.php") ?>;

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Finances 
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Finances</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Info boxes ----------------------------------------->
        <div class="row">
        <!-- Financial Balance -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-balance-scale"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Financial Balance</span>
                <span class="info-box-number">R2500</span>
            </div>
            </div>
        </div>

        <!-- Debts -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-credit-card"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Debts</span>
                <span class="info-box-number">R41,410</span>
            </div>
            </div>
        </div>

        <!-- Amount Due to Us -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-dollar"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Amount Due to Us</span>
                <span class="info-box-number">R760</span>
            </div>
            </div>
        </div>

        <!-- Amount Used -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-money"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Amount Used</span>
                <span class="info-box-number">R2,000</span>
            </div>
            </div>
        </div>
        </div>

      <!-- /.row -->

      <!-- monthly recap ----------------------------------------->
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Balance Over Time</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-wrench"></i></button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-8">
                  <p class="text-center">
                    <strong>Sales: 1 Jan, 2014 - 30 Jul, 2014</strong>
                  </p>

                  <div class="chart">
                    <!-- Sales Chart Canvas -->
                    <canvas id="salesChart" style="height: 180px;"></canvas>
                  </div>
                  <!-- /.chart-responsive -->
                </div>
                <!-- /.col -->
                <div class="col-md-4">
                  <p class="text-center">
                    <strong>Goal Completion</strong>
                  </p>

                  <div class="progress-group">
                    <span class="progress-text">Add Products to Cart</span>
                    <span class="progress-number"><b>160</b>/200</span>

                    <div class="progress sm">
                      <div class="progress-bar progress-bar-aqua" style="width: 80%"></div>
                    </div>
                  </div>
                  <!-- /.progress-group -->
                  <div class="progress-group">
                    <span class="progress-text">Complete Purchase</span>
                    <span class="progress-number"><b>310</b>/400</span>

                    <div class="progress sm">
                      <div class="progress-bar progress-bar-red" style="width: 80%"></div>
                    </div>
                  </div>
                  <!-- /.progress-group -->
                  <div class="progress-group">
                    <span class="progress-text">Visit Premium Page</span>
                    <span class="progress-number"><b>480</b>/800</span>

                    <div class="progress sm">
                      <div class="progress-bar progress-bar-green" style="width: 80%"></div>
                    </div>
                  </div>
                  <!-- /.progress-group -->
                  <div class="progress-group">
                    <span class="progress-text">Send Inquiries</span>
                    <span class="progress-number"><b>250</b>/500</span>

                    <div class="progress sm">
                      <div class="progress-bar progress-bar-yellow" style="width: 80%"></div>
                    </div>
                  </div>
                  <!-- /.progress-group -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- ./box-body -->
            <div class="box-footer">
              <div class="row">
                <div class="col-sm-3 col-xs-6">
                  <div class="description-block border-right">
                    <a href="status.php?val=<?php echo 1 ?>" style="display: block; text-decoration: none; padding: 5px; color: #fff; text-align: center; border-radius: 5px; background-color: #17a2b8;">
                    <div>
                    <h5 class="description-header">Learners</h5>
                    <span class="description-text">Active--Owing</span>
                     </div>
                    </a>
                  </div>
                  <!-- /.description-block -->
                </div>
                 <!-- /.col -->
                 <div class="col-sm-3 col-xs-6">
                  <div class="description-block">
                  <a href="status.php?val=<?php echo 4 ?>" style="display: block; text-decoration: none; padding: 5px; color: #fff; text-align: center; border-radius: 5px; background-color: #dc3545;">
                  <div>
                    <h5 class="description-header">Learners</h5>
                    <span class="description-text">Not Active--Owing</span>
                     </div>
                    </a>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-xs-6">
                  <div class="description-block border-right">
                  <a href="status.php?val=<?php echo 2  ?>" style="display: block; text-decoration: none; padding: 5px; color: #fff; text-align: center; border-radius: 5px; background-color: #28a745;">
                  <div>
                    <h5 class="description-header">Learners</h5>
                    <span class="description-text">Active--Not Owing</span>
                     </div>
                    </a>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-xs-6">
                  <div class="description-block border-right">
                  <a href="status.php?val=<?php echo 3 ?>" style="display: block; text-decoration: none; padding: 5px; color: #fff; text-align: center; border-radius: 5px; background-color: #ffc107;">
                  <div>
                    <h5 class="description-header">Learners</h5>
                    <span class="description-text">Not Active--Not Owing</span>
                     </div>
                    </a>
                  </div>
                  <!-- /.description-block -->
                </div>
               
              </div>
              <!-- /.row -->
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>

    <!-- Main content table--------------------h------------------------->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <!-- /.box -->

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Learners</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>StNo</th>
                      <th>Name</th>
                      <th>Surname</th>
                      <th>Grade</th>
                      <th>Math</th>
                      <th>Physics</th>
                      <th>Total Fees</th>
                      <th>Total Paid</th>
                      <th>Total Owe</th>
                      <th>Pay</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                                 /*   SELECT SUM(column_name) AS total
                                    FROM learners;
                                    
                                    
    SELECT                 
    SUM(column_name) AS total_amount,
    SUM(column_name) AS total_fees,
    SUM(column_name) AS total_taxes
    FROM finances
    WHERE status = 'active';


    SELECT 
    SUM(CASE WHEN column1 > 0 THEN column1 ELSE 0 END) AS total_column1,
    SUM(CASE WHEN column2 > 0 THEN column2 ELSE 0 END) AS total_column2,
    SUM(CASE WHEN column3 > 0 THEN column3 ELSE 0 END) AS total_column3
FROM finances;     avoids negatives
*/


                        //select all leaners who are doing this activity... now im selecting activities
                        $sql = "SELECT * FROM learners";
                        $results = $connect->query($sql);
                        while($final = $results->fetch_assoc()) { ?>
                          <tr>
                            <td><?php echo $final['LearnerId'] ?></td>
                            <td><?php echo $final['Name'] ?></td>
                            <td><?php echo $final['Surname'] ?></td>
                            <td><?php echo $final['Grade'] ?></td>
                            <td><?php echo $final['Math'] ?></td>
                            <td><?php echo $final['Physics'] ?></td>
                            <td><?php echo $final['TotalFees'] ?></td>
                            <td><?php echo $final['TotalPaid'] ?></td>
                            <td> <?php echo $final['TotalOwe'] ?></td>
                            <td> 
                              <form action="payhandler.php" method="POST" class="horizontal-container">
                                <input type="number" class="form-control2" id="newamount" name="newamount" min="-5000" max="5000" required>
                                <input type="hidden" name="learnerid" value="<?php echo $final['LearnerId']; ?>">                                                                        
                                <button type="submit" name="updateby" class="button btn btn-primary py-3 px-4">Update By</button>
                              </form>
                            </td>
                          </tr>

                    <?php } ?>
                  </tbody>

                  <tfoot>
                    <tr>
                    <th>StNo</th>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Grade</th>
                    <th>Math</th>
                    <th>Physics</th>
                    <th>Total Fees</th>
                    <th>Total Paid</th>
                    <th>Total Owe</th>
                    <th>Pay</th>

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
    <!-- /.content -->
    <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-aqua">
                <span class="info-box-icon"><i class="ion-ios-chatbubble-outline"></i></span>

                <?php 
                 $sql = "SELECT                
                 SUM(TotalFees) AS TotalFees,
                 SUM(TotalPaid) AS TotalPaid,
                 SUM(CASE WHEN TotalOwe > 0 THEN TotalOwe ELSE 0 END) AS TotalOwe,
                 SUM(CASE WHEN TotalOwe < 0 THEN TotalOwe ELSE 0 END) AS Owe

                 FROM learners";
                 $results = $connect->query($sql);
                 $final = $results->fetch_assoc();

                 $TotalFees = $final['TotalFees'];
                 $TotalPaid = $final['TotalPaid'];
                 $TotalOwe = $final['TotalOwe'];
                 $Owe = (-1 * $final['Owe']);

                
                ?>



                <div class="info-box-content">
                    <span class="info-box-text">Total Paid by Learners</span>
                    <span class="info-box-number">R<?php echo $TotalPaid ?></span>

                    <div class="progress">
                    <div class="progress-bar" style="width: 40%"></div>
                    </div>
                    <span class="progress-description">
                    40% Increase in 30 Days
                    </span>
                </div>
                <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <!-- Info Boxes Style 2 -->
                <div class="info-box bg-yellow">
                <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Tot Amound Expexted</span>
                    <span class="info-box-number">R<?php echo $TotalFees ?></span>

                    <div class="progress">
                    <div class="progress-bar" style="width: 50%"></div>
                    </div>
                    <span class="progress-description">
                    50% Increase in 30 Days
                    </span>
                </div>
                <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-green">
                <span class="info-box-icon"><i class="ion ion-ios-heart-outline"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Amound Due to Us</span>
                    <span class="info-box-number">R<?php echo $TotalOwe ?></span>

                    <div class="progress">
                    <div class="progress-bar" style="width: 20%"></div>
                    </div>
                    <span class="progress-description">
                    20% Increase in 30 Days
                    </span>
                </div>
                <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-red">
                <span class="info-box-icon"><i class="ion ion-ios-cloud-download-outline"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Amound we Owe</span>
                    <span class="info-box-number">R<?php echo $Owe ?></span>

                    <div class="progress">
                    <div class="progress-bar" style="width: 70%"></div>
                    </div>
                    <span class="progress-description">
                    70% Increase in 30 Days
                    </span>
                </div>
                <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>

            
        </div>
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
<!-- ./wrapper -->

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
<!-- Sparkline -->
<script src="bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap  -->
<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- ChartJS -->
<script src="bower_components/chart.js/Chart.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard2.js"></script>


<!-- page script -->

<script>
  $(function () {
    $('#example1').DataTable()
  })
</script>


</body>
</html>
