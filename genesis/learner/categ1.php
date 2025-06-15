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
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>



    <!-- Main content table---------------------------------------------> 
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <!-- /.box -->

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Classes</h3>
            </div>
            
  
        
            <!-- /.box-header -->
            <div class="box-body">
            
                <!-- Main content -->
                <section class="content">
                <section class="content-header">
      <h1>
        Record Marks
        <small>classes</small>
      </h1>
      
    </section><br>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-4">
          <div class="box box-solid">
            <div style="background-color: yellow" class="box-header with-border">
              <i class="fa fa-text-width"></i>
              <h3 class="box-title">Grade 12</h3>
            </div><br>
            <!-- /.box-header -->
            <div class="box-body">
                <a href="maths12.php" class="btn btn-block btn-default btn-lg">Mathematics</a>
            </div><br>
            <div class="box-body">
                <a href="physics12.php" class="btn btn-block btn-default btn-lg">Physical Sciences</a>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- ./col -->

        <div class="col-md-4">
          <div class="box box-solid">
            <div style="background-color: #00c0ef" class="box-header with-border">
              <i class="fa fa-text-width"></i>
              <h3 class="box-title">Grade 11</h3>
            </div><br>
            <!-- /.box-header -->
            <div class="box-body">
                <a href="maths11.php" class="btn btn-block btn-default btn-lg">Mathematics</a>
            </div><br>
            <div class="box-body">
                <a href="physics11.php" class="btn btn-block btn-default btn-lg">Physical Sciences</a>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- ./col -->

        <div class="col-md-4">
          <div class="box box-solid">
            <div style="background-color: pink" class="box-header with-border">
              <i class="fa fa-text-width"></i>
              <h3 class="box-title">Grade 10</h3>
            </div><br>
            <!-- /.box-header -->
            <div class="box-body">
                <a href="maths10.php" class="btn btn-block btn-default btn-lg">Mathematics</a>
            </div><br>
            <div class="box-body">
                <a href="physics10.php" class="btn btn-block btn-default btn-lg">Physical Sciences</a>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
    </section>

                <!-- /.row -->
                </section>


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
