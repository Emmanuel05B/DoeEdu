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

                      <?php
                        include('../partials/connect.php');

                        $statusValue = intval($_GET['val']); // Get the subject value, ensure it's an integer

                        if(isset($_GET['id'])){
                          $learnerId = $_GET['id'];

                          $sql = "
                          SELECT 
                              learners.*,
                              parentlearner.ParentId
                          FROM 
                              learners
                          JOIN 
                              parentlearner ON learners.LearnerId = parentlearner.LearnerId
                          WHERE 
                              learners.LearnerId = ?";
                              
                      $stmt = $connect->prepare($sql);
                      $stmt->bind_param("i", $learnerId); 
                      
                      $stmt->execute();
                      $results = $stmt->get_result();
                      $final = $results->fetch_assoc();

                          /*
                          $sql = "SELECT * FROM learner WHERE LearnerId = ?";
                          $stmt = $connect->prepare($sql);
                          $stmt->bind_param("i", $learnerId); 

                          $stmt->execute();
                          $results = $stmt->get_result();
                          $final = $results->fetch_assoc();
                          */
                        }else{
                          echo 'Invalid learner ID.'; 
                         }

                        
                        ?>

      <!-- Main content -->
      <section class="content">

        <div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="box box-primary">
              <div class="box-body box-profile">
                <div class="profile-photo-square">
                  <img class="profile-user-img img-responsive img-circle" src="images/1.jpg" alt="User profile picture">
                </div>

                <h3 class="profile-username text-center"><?php echo $final['Name'] ?> <?php echo $final['Surname'] ?></h3>

                <p class="text-muted text-center">DoE Learner</p>

                <ul class="list-group list-group-unbordered">
                  <li class="list-group-item">
                    <a href="goals.php?id=<?php echo $final['LearnerId'] ?>" class="btn btn-primary btn-block">View Goals</a>
                  </li>  
                  <li class="list-group-item">
                    <a href="tracklearnerprogress.php?id=<?php echo $final['LearnerId'] ?>&val=<?php echo $_GET['val'] ?>" class="btn btn-primary btn-block">Track Progress</a>
                  </li>
                  <li class="list-group-item">
                  <a href="mcomposeparent.php?pid=<?php echo $final['ParentId'] ?>" class="btn btn-primary btn-block">Contact Parent</a>
                  </li>
                  <li class="list-group-item">
                    <a href="file.php?pid=<?php echo $final['ParentId'] ?>&lid=<?php echo $final['LearnerId'] ?>&val=<?php echo $_GET['val'] ?>" class="btn btn-primary btn-block">View Report</a>

                  </li>


                 
                 
                </ul>

              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->

          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#aboutme" data-toggle="tab">About Me</a></li>
                <li><a href="#supportme" data-toggle="tab">Support Me</a></li>
                <li><a href="#more" data-toggle="tab">More</a></li>
              </ul>
              <div class="tab-content">
                <div class="active tab-pane" id="aboutme">
                  <!-- here -->
                  <div class="profile-personal-info">
                    <h4>About Me</h4>
                    <p>I'm a creative and curious kid who loves exploring new ideas and finding joy in my favorite activities.
                      I do well in a calm, structured environment where I can learn and grow at my own pace.</p>
                  </div>

                  <div class="profile-personal-info">
                      <h4>Personal Information</h4>

                      <div class="row mb-3">
                        <div class="col-3"><strong><p>Name: </p></strong></div>
                        <div class="col-9"><strong><p><?php echo $final['Name'] ?></p></strong></div>
                      </div>
                      <div class="row mb-3">
                        <div class="col-3"><p>Surname: </p></div>
                        <div class="col-9"><strong><p><?php echo $final['Surname'] ?></p></strong></div>
                      </div>
                      <div class="row mb-3">
                        <div class="col-3"><strong><p>Grade: </p></strong></div>
                        <div class="col-9"><strong><p><?php echo $final['Grade'] ?></p></strong></div>
                      </div>
                      <div class="row mb-3">
                        <div class="col-3"><strong><p>Email: </p></strong></div>
                        <div class="col-9"><strong><p><?php echo $final['Email'] ?></p></strong></div>
                      </div>
                      <div class="row mb-3">
                        <div class="col-3"><strong><p>Contact Number: </p></strong></div>
                        <div class="col-9"><strong><p><?php echo $final['ContactNumber'] ?></p></strong></div>
                      </div>
                  </div>

                  
                  <!-- /.here -->
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="supportme">
                  <!-- here -->
                  <div class="profile-personal-info">

                              <h4 class="text-primary mb-4">Support Strategies</h4>
                              <!-- Content for support strategies -->
                              
                                <ul>
                                    <li>I need visuals to help me understant what is happening</li>
                                    <li>I need time to process information</li>
                                    <li>Language needs to be short and clear</li>
                                    <li>I am still in nappies and need help with dressing</li>
                                    <li>Eating is still hard for meI may need extra time and food put directly into my hand.</li>
                                    <li>I am still in nappies and need help with dressing</li>
                                </ul>
                             
                            
                  </div>
                  <div class="profile-personal-info">
                  <div class="profile-skills border-bottom mb-4 pb-2">
                              <h4 class="text-primary mb-4">How to help me learn</h4>
                              
                                <ul>
                                    <li>Please Use clear, straightforward language.</li>
                                    <li>I prefer visual aids like pictures, diagrams, and written instructions to understant</li>
                                    
                                </ul>
                            
                            </div>
          
                 </div>

                 <div class="profile-personal-info">
                 <div class="profile-skills border-bottom mb-4 pb-2">
                              <h4 class="text-primary mb-4">How to help me calm down</h4>
                              
                                <ul>
                                    <li>Reduce Sensory Input.</li>
                                    <li>Provide Fidget Toys</li>
                                    <li>Give me Space</li>
                                </ul>
                              
                            </div>
                 </div>

                 


                  <!-- /.here -->
                </div>
                <!-- /.tab-pane -->

                <div class="tab-pane" id="more">
                  <!-- here -->
                  <div class="profile-personal-info">
                  <div class="profile-skills border-bottom mb-4 pb-2">
                    <h4 class="text-primary mb-4">Things I Love</h4>
                    <div class="bubble-container">
                      <span class="bubble">Puzzles</span>
                      <span class="bubble">Being outside</span>
                      <span class="bubble">Animals</span>
                      <span class="bubble">Bubbles</span>
                      <span class="bubble">Chocolate cake</span>
                      <span class="bubble">Messy play</span>
                    </div>
                  </div>
                  </div>
                  <div class="profile-personal-info">
                  <div class="profile-skills border-bottom mb-4 pb-2">
                              <h4 class="text-primary mb-4">My Hopes and Wishes</h4>
                              <div class="bubble-container">
                                <span class="bubble">To be happy</span>
                                <span class="bubble">To be Independent</span>
                                <span class="bubble">To reach my potential</span>
                                <span class="bubble">To make friends</span>
                                <span class="bubble">To be healthy</span>
                                <span class="bubble">Messy play</span>
                              </div>   
                   </div>
                  </div>
                  <div class="profile-personal-info">
                            <div class="profile-skills border-bottom mb-4 pb-2">
                              <h4 class="text-primary mb-4">What People Love About Me</h4>
                              <div class="bubble-container">
                                <span class="bubble">My laugh</span>
                                <span class="bubble">My smile</span>
                                <span class="bubble">I am very good at numbers</span>
                                <span class="bubble">I am great at giving hugs</span>
                                <span class="bubble">I am very good at puzzels</span>
                                <span class="bubble">I am interested in how things work</span>
                              </div>
                            </div>
                  </div>
                  <div class="profile-personal-info">
                           <div class="profile-skills border-bottom mb-4 pb-2">
                              <h4 class="text-primary mb-4">Things that I find Stressful</h4>

                              <div class="bubble-container">
                              <span class="bubble">Noise</span>
                              <span class="bubble">Pressure to Be Independent</span>
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
