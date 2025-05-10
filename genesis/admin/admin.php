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
    .profile-personal-info {
      border-bottom: 2px solid #007bff;
      margin-bottom: 20px;
      padding-bottom: 15px;
      padding-left: 15px;
    }

    .profile-personal-info h4 {
      font-size: 24px;
      font-weight: bold;
      color: #007bff;
      margin-bottom: 15px;
    }

    .profile-personal-info .row {
      margin-bottom: 15px;
      display: flex;
      align-items: center;
    }

    .profile-personal-info .col-3 {
      flex: 0 0 25%;
      max-width: 25%;
      font-weight: bold;
      color: #333;
      font-size: 16px;
      padding-left: 0;
    }

    .profile-personal-info .col-9 {
      flex: 0 0 75%;
      max-width: 75%;
      font-size: 16px;
      color: #007bff;
      padding-right: 0;
    }

    .profile-personal-info .col-9 p,
    .profile-personal-info .col-3 p {
      margin: 0;
    }

    hr {
      border: none;
      border-top: 2px solid #007bff;
      margin: 20px 0;
    }

    .bubble-container {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
  }

  .bubble {
      border: 2px solid #add8e6; 
      padding: 10px 20px;
      border-radius: 50px; 
      text-align: center;
  }

  .bubble:hover {
      border-color: #007bff; 
      color: #007bff; 
  }


  .profile-personal-info {
      border-bottom: 2px solid #007bff;
      margin-bottom: 20px;
      padding-bottom: 15px;
      padding-left: 30px; /* Added padding to shift content right */
  }

  .profile-personal-info .row {
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      margin-left: 10px; /* Adjust if needed to further shift content */
  }

</style>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

    <!-- Left side column. contains the logo and sidebar -->
    <?php include("adminpartials/header.php"); ?>

    <!-- Left side column. contains the logo and sidebar -->
    <?php include("adminpartials/mainsidebar.php"); ?>
    <!-- /.sidebar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

      <!-- Main content -->
      <section class="content">
      <section class="content-header">
      <h1>
      Administration
        <small>Admin</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Administration</li>
      </ol><br>
     </section><br>


        <div class="row">
          <!-- /.col   just md number to change the screen size-->
          <div class="col-md-12"> 
            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#aboutme" data-toggle="tab">Add Users</a></li>
                <li><a href="#supportme" data-toggle="tab">Update Users</a></li>
                <li><a href="#more" data-toggle="tab">Disable Users</a></li>
              </ul>
              <div class="tab-content">
                <div class="active tab-pane" id="aboutme">
                  <!-- here -->
                  <div class="profile-personal-info">
                    <h4>Register</h4>
                    <div class="bubble-container">
                      <a href="addlearners.php" style="color: #1a73e8;" class="bubble">Register Learner</a>
                      <a href="addtutor.php" style="color: #1a73e8;" class="bubble">Register Tutor</a>

                    </div>

                  </div>
                    


                  
                  <!-- /.here -->
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="supportme">
                  <!-- here -->
                  <div class="profile-personal-info">

                              <h4 class="text-primary mb-4">Learner</h4>
                              <!-- Content for support strategies -->
                              <div class="bubble-container">
                              <a href="#" style="color:rgb(0, 0, 0);" class="bubble">Mathematics G12</a>
                              <a href="#" style="color:rgb(0, 0, 0);" class="bubble">Physical Sciences G12</a>
                              <a href="#" style="color:rgb(211, 31, 31);" class="bubble">Mathematics G11</a>
                              <a href="#" style="color:rgb(211, 31, 31);" class="bubble">Physical Sciences G11</a>
                              <a href="#" style="color: #1a73e8;" class="bubble">Mathematics G10</a>
                              <a href="#" style="color: #1a73e8;" class="bubble">Physical Sciences G10</a>

                              </div> 
                            
                  </div>
                  <div class="profile-personal-info">
                  <div class="profile-skills border-bottom mb-4 pb-2">
                              <h4 class="text-primary mb-4">Tutor</h4>
                              <div class="bubble-container">
                                <span class="bubble">Siphumelele</span>
                                <span class="bubble">Shirley</span>
                              </div> 
                            
                            </div>
          
                 </div>

                 <div class="profile-personal-info">
                 <div class="profile-skills border-bottom mb-4 pb-2">
                              <h4 class="text-primary mb-4">My Profile</h4>
                              <div class="bubble-container">
                                <span class="bubble">MyName</span>
                              </div> 
                              
                            </div>
                 </div>

                 


                  <!-- /.here -->
                </div>
                <!-- /.tab-pane -->

                <div class="tab-pane" id="more">
                  <!-- here -->
                  <div class="profile-personal-info">
                  <div class="profile-skills border-bottom mb-4 pb-2">
                    <h4 class="text-primary mb-4">Learners</h4>
                    <div class="bubble-container">
                      <span class="bubble">Mathematics G12</span>
                      <span class="bubble">Physical Sciences G12</span>
                      <span class="bubble">Mathematics G 11</span>
                      <span class="bubble">Physical Sciences G11</span>  
                      <span class="bubble">Mathematics G10</span>
                      <span class="bubble">Physical Sciences G10</span>
                    </div>
                  </div>
                  </div>
                  <div class="profile-personal-info">
                  <div class="profile-skills border-bottom mb-4 pb-2">
                              <h4 class="text-primary mb-4">Tutors</h4>
                              <div class="bubble-container">
                                <span class="bubble">Siphumelele</span>
                                <span class="bubble">Shirley</span>
                              </div>   
                   </div>
                  </div>
                  <div class="profile-personal-info">
                            <div class="profile-skills border-bottom mb-4 pb-2">
                              <h4 class="text-primary mb-4">Parents</h4>
                              <div class="bubble-container">
                                <span class="bubble">My laugh</span>
                                <span class="bubble">My smile</span>
                                <span class="bubble">I am interested in how things work</span>
                              </div>
                            </div>
                  </div>
                 
                  <!-- /.here -->
                </div>
                <!-- /.tab-pane -->
              </div>
              <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div>

  <?php include("adminpartials/queries.php"); ?>
  <script src="dist/js/demo.js"></script>

</body>
</html>
