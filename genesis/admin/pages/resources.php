<!DOCTYPE html>
<html>
    
<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../../common/pages/login.php");
  exit();
}
?>

<?php include(__DIR__ . "/../../common/partials/head.php"); ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <!-- Left side column. contains the logo and sidebar -->
  <?php include(__DIR__ . "/../../common/partials/header.php"); ?>
  <?php include(__DIR__ . "/../../common/partials/mainsidebar.php"); ?>

  <!-- Content Wrapper. Contains page content --->
  <div class="content-wrapper">
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>


    <section class="content-header">
          <h1>XXXXXXX <small>xxxx xxxxxxx xx.</small></h1>
          <ol class="breadcrumb">
            <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">xxxxxx</li>
          </ol>
    </section>

    <!-- Main content table---------------------------------------------> 
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">xxxxx</h3>
            </div>
     
            <div class="box-body">
            

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
    
    </section>

  </div>

  <div class="control-sidebar-bg"></div>
</div>

<!-- jQuery 3 -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../common/dist/js/demo.js"></script> 

<script>
  $(function () {
    $('#example1').DataTable()
  })
</script>

</body>
</html>
