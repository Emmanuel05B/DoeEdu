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

/* Tabs */
.nav-pills .nav-link {
  border-radius: 0;
  color: #333;
}

.nav-pills .nav-link.addprofile {
  background-color: #95a5a6;
  color: #fff;
}
.nav-pills .nav-link.deleteprofile:hover {
  background-color: red;
}



/* Timeline */
.time-label {
  margin-bottom: 10px;
  padding: 10px 0;
}

.bg-danger {
  background-color: #dc3545;
  color: #fff;
  padding: 5px 10px;
  border-radius: 5px;
}


.content {
  background-color: white;
  
  margin-top: 20px;
  margin-left: 100px;
  margin-right: 100px;
}
.pos {
  margin-top: 50px;
  margin-left: 10px;
  margin-right: 10px;
}

.small-box .icon {
  float: right;
  font-size: 64px;
  line-height: 100px;
  text-align: center;
  
}/* Icon color */
.small-box .icon d {
  color: #ff0000; /* Red color */
}
</style>


<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include("adminpartials/header.php") ?>;
  <!-- Left side column. contains the logo and sidebar -->
 <?php include("adminpartials/mainsidebar.php") ?>;
  <!-- Content Wrapper. Contains page content ##start -->
  <div class="content-wrapper">
  <!-- Content Header (Page header) -->
    
        
        <!-- ./col 555555555555555555555-->
  <section class="content">
    <div class="container-fluid">
          
         <div class="oder">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link addprofile" href="#addprofile" data-toggle="tab">Add Profile</a></li>
                  <li class="nav-item"><a class="nav-link updateprofile" href="#updateprofile" data-toggle="tab">Update Profile</a></li>
                  <li class="nav-item"><a class="nav-link deleteprofile" href="#deleteprofile" data-toggle="tab">Delete Profile</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <!-- /.tab-pane -->
                  <div class="active tab-pane" id="addprofile">
            
                    <div class="pos">

                       <div class="row">    <!-- start -->
                            <!-- ./col -->
                            <div class="col-lg-3 col-xs-6">
                              <!-- small box -->
                              <div class="small-box bg-aqua">
                                <div class="inner">
                                <p> Add </p>
                                  <h2>Learner</h2>
                                
                                </div>
                                <a href="add.php" >
                                <div class="icon">
                                  <i class="ion ion-person-add"></i>
                                </div>
                                </a>
                              </div>
                            </div>

                            <!-- ./col -->
                            <div class="col-lg-3 col-xs-6">
                              <!-- small box -->
                              <div class="small-box bg-aqua">
                                <div class="inner">
                                <p> Add </p>
                                  <h2>Teacher</h2>
                                  
                                </div>
                                <a href="addteacher.php" >
                                <div class="icon">
                                  <i class="ion ion-person-add"></i>
                                </div>
                                </a>
                              </div>
                            </div>

                            <!-- ./col -->
                            <div class="col-lg-3 col-xs-6">
                              <!-- small box -->
                              <div class="small-box bg-aqua">
                                <div class="inner">
                                <p> Add </p>
                                  <h2>Parent</h2>
                                
                                </div>
                                <a href="learners.php" >
                                <div class="icon">
                                  <i class="ion ion-person-add"></i>
                                </div>
                                </a>
                              </div>
                            </div>

                            <!-- ./col -->
                            <div class="col-lg-3 col-xs-6">
                              <!-- small box -->
                              <div class="small-box bg-aqua">
                                <div class="inner">
                                <p> Add </p>
                                  <h2>Admin</h2>
                                
                                </div>
                                <a href="addadmin.php" >
                                <div class="icon">
                                  <i class="ion ion-person-add"></i>
                                </div>
                                </a>
                              </div>
                            </div>
                            

                       </div>    <!-- start -->

                    </div>
                    
                  </div>

            <!-- /.tab-pane -->
            <div class="tab-pane" id="updateprofile">
                   <div class="pos">
                      <div class="row">
                            <!-- ./col -->
                            <div class="col-lg-3 col-xs-6">
                              <!-- small box -->
                              <div class="small-box bg-aqua">
                                <div class="inner">
                                <p> Update </p>
                                  <h2>Learner</h2>
                                
                                </div>
                                <a href="updatelearner.php" >
                                <div class="icon">
                                <i class="ion ion-edit"></i>
                                </div>
                                </a>
                              </div>
                            </div>

                             <!-- ./col -->
                             <div class="col-lg-3 col-xs-6">
                              <!-- small box -->
                              <div class="small-box bg-aqua">
                                <div class="inner">
                                <p> Update </p>
                                  <h2>Parent</h2>
                                
                                </div> 
                                <a href="learners.php" >
                                <div class="icon">
                                <i class="ion ion-edit"></i>
                                </div>
                                </a>
                              </div>
                            </div>

                            <!-- ./col -->
                            <div class="col-lg-3 col-xs-6">
                              <!-- small box -->
                              <div class="small-box bg-aqua">
                                <div class="inner">
                                <p> Update </p>
                                  <h2>Employee</h2>
                                  
                                </div>
                                <a href="updateemployee.php" >
                                <div class="icon">
                                <i class="ion ion-edit"></i>
                                </div>
                                </a>
                              </div>
                            </div>
                           
                       </div>

                      </div>
                    
                  </div>
                  <!-- /.tab-pane -->

                  
                  <div class="tab-pane" id="deleteprofile">
                  <div class="pos">
                  <div class="row">    <!-- start -->
                            <!-- ./col -->
                            <div class="col-lg-3 col-xs-6">
                              <!-- small box -->
                              <div class="small-box bg-aqua">
                                <div class="inner">
                                <p> Delete </p>
                                  <h2>Learner</h2>
                                
                                </div>
                                <a href="deletelearner.php" >
                                <div class="icon">
                                <d class="ion ion-trash-b"></d>
                                </div>
                                </a>
                              </div>
                            </div>


                            <!-- ./col -->
                            <div class="col-lg-3 col-xs-6">
                              <!-- small box -->
                              <div class="small-box bg-aqua">
                                <div class="inner">
                                <p> Delete </p>
                                  <h2>Parent</h2>
                                
                                </div>
                                <a href="deleteparent.php" >
                                <div class="icon">
                                <d class="ion ion-trash-b"></d>
                                   </div>
                                </a>
                              </div>
                            </div>

                            <!-- ./col -->
                            <div class="col-lg-3 col-xs-6">
                              <!-- small box -->
                              <div class="small-box bg-aqua">
                                <div class="inner">
                                <p style="red"> Delete</p>
                                  <h2>Employee</h2>
                                
                                </div>
                                <a href="deleteemployee.php" >
                                <div class="icon">
                                <d class="ion ion-trash-b"></d>
                                </div>
                                </a>
                              </div>
                            </div>

                       </div>    <!-- start -->
                  </div>
                </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>
 </div>
</section>
        <!-- ./col 555555555555555555555-->
</div> <!-- /. ##start -->
      
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->



<?php include("adminpartials/queries.php") ?>;
<script src="dist/js/demo.js"></script>
</body>
</html>

