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

  <?php include("adminpartials/header.php"); ?>

  <!-- Left side column. contains the logo and sidebar -->
  <?php include("adminpartials/mainsidebar.php"); ?>

  <style>
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
      text-align: center;
    }

    /* cards styling */
    * {
      box-sizing: border-box;
    }

    /* Center the row */
    .row {
      display: flex;
      justify-content: center;
      margin: 0 -5px;
      flex-wrap: wrap;
    }

    /* Adjust the column width */
    .column {
      flex: 0 0 auto; /* Adjust width to fit content */
      padding: 0 10px;
    }

    /* Responsive columns */
    @media screen and (max-width: 600px) {
      .column {
        flex: 0 0 100%;
        max-width: 100%;
        margin-bottom: 20px;
      }
    }

    /* Style the counter cards */
    .card {
      box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
      padding: 16px;
      text-align: center;
      background-color: #f1f1f1;
      margin: 10px;
    }

    /* White background for text */
    .card-text {
      background-color: white;
      padding: 10px;
      border-radius: 5px;
      display: inline-block;
      min-width: 150px; /* Ensure the white box fits the text */
      text-align: center; /* Center the text */
    }
  </style>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="oder">
          <div class="card">
            <div class="card-body">
              <div class="tab-content">
                <!-- /.tab-pane -->
                <div class="active tab-pane" id="addprofile">
                  <h3>Categories</h3>   
                  <div class="pos">
                    <div class="row">
                      <a href="trackgradelearners.php?id=1" class="card-link">
                        <div class="column">
                          <div class="card">
                            <div class="card-text">
                              <h3>Grade 12</h3>
                              <p>Learners</p>
                              <!-- Links Inside the Card -->
                              <div>
                                <a href="recmodal.php?gra=12&sub=1" class="btn btn-primary btn-block">Mathematics</a>
                                <a href="recmodal.php?gra=12&sub=2" class="btn btn-primary btn-block">Physical Sciences</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </a> 

                      <a href="trackgradelearners.php?id=2" class="card-link">
                        <div class="column">
                          <div class="card">
                            <div class="card-text">
                              <h3>Grade 11</h3>
                              <p>Learners</p>
                              
                              <!-- Links Inside the Card -->
                              <div>
                                <a href="recmodal.php?gra=11&sub=1" class="btn btn-primary btn-block">Mathematics</a>
                                <a href="recmodal.php?gra=11&sub=2" class="btn btn-primary btn-block">Physical Sciences</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </a>


                      <a href="trackgradelearners.php?id=3" class="card-link">
                        <div class="column">
                          <div class="card">
                            <div class="card-text">
                              <h3>Grade 10</h3>
                              <p>Learners</p>
                              <!-- Links Inside the Card -->
                              <div>
                                <a href="recmodal.php?gra=10&sub=1" class="btn btn-primary btn-block">Mathematics</a>
                                <a href="recmodal.php?gra=10&sub=2" class="btn btn-primary btn-block">Physical Sciences</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </a> 
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.tab-pane -->
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<?php include("adminpartials/queries.php"); ?>
<script src="dist/js/demo.js"></script>
</body>
</html>
