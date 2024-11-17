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

.button {
  display: inline-block;
  padding: 4px 8px;
  background-color: blue;
  color: #fff; 
}

.content {  /* for the white thingy */
  background-color: white;
  margin-top: 20px;
  margin-left: 80px;
  margin-right: 80px;
}

.centr {
    text-align: center;     
}


</style>


<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include("adminpartials/header.php") ?>;
  <!-- Left side column. contains the logo and sidebar -->
 <?php include("adminpartials/mainsidebar.php") ?>;
  <!-- Content Wrapper. Contains page content ##start -->
  <div class="content-wrapper">
  
  <section class="content">
    <div class="container-fluid">
          
    <section class="ftco-section ftco-cart">
            <div class="centr">
                <h2>Select the Learner</h2><br>
               
            </div>
                <h2></h2>
                <div style="overflow-x:auto;">
                   
                    <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Gender</th>
                  <th>Functional level</th>
                  <th>More</th>
                </tr>
                </thead>

                <?php
                        include('../partials/connect.php');
               
                        $sql = "SELECT * FROM learner";
                        $results = $connect->query($sql);
                        while($final = $results->fetch_assoc()) { ?>
                <tbody>
                <tr>
                  <td><?php echo $final['Name'] ?></td>
                  <td><?php echo $final['Surname'] ?></td>
                  <td><?php echo $final['Gender'] ?></td>
                  <td><?php echo $final['FunctionalLevel'] ?></td>

                  <td><p><a class="button" href=" " >Update details</a></p></td>
                </tr>
                </tbody>
                <?php } ?>

                <tfoot>
                <tr>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Gender</th>
                  <th>Functional level</th>
                  <th>More</th>
                </tr>
                </tfoot>
              </table>
                  
                </div>
    </div>
</section>
</div> <!-- /. ##start -->
      

  <div class="control-sidebar-bg"></div>
</div>



<script src="dist/js/demo.js"></script>
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
<!-- page script -->
<script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>
</body>
</html>

