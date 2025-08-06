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

  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

          <?php
              include(__DIR__ . "/../../partials/connect.php");

              $statusValue = intval($_GET['val']); // Get the subject value, ensure it's an integer

              if(isset($_GET['id'])){
                $learnerId = $_GET['id'];
   
                $sql = "
                  SELECT 
                    learners.*,  users.Name, users.Surname,  users.Email, users.Contact, users.Gender
                  FROM 
                    learners
                  JOIN 
                    users ON learners.LearnerId = users.Id
                  WHERE 
                    learners.LearnerId = ?";

                    $stmt = $connect->prepare($sql);
                    $stmt->bind_param("i", $learnerId); 
                          
                    $stmt->execute();
                    $results = $stmt->get_result();
                    $final = $results->fetch_assoc();
                         
              }else{
                echo 'Invalid learner ID.'; 
              }
                        
          ?>

      <section class="content-header">
        <h1>Learner Profile  <small>...</small></h1>
        <ol class="breadcrumb">
          <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Learner Profile</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">

        <div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="box box-primary">
              <div class="box-body box-profile">
                <div class="profile-photo-square">
                  <img class="profile-user-img img-responsive img-circle" src="../../uploads/doe.jpg" alt="User profile picture">
                </div>

                <h3 class="profile-username text-center"><?php echo $final['Name'] ?> <?php echo $final['Surname'] ?></h3>

                <p class="text-muted text-center">DoE Learner</p>

                <div class="box-body text-center" style="background-color:#a3bffa;">
                  <a href="individualactivity.php?id=<?php echo $final['LearnerId'] ?>" class="btn btn-primary btn-sm" style="width: 100px;">Record Marks</a>
                  <a href="goals.php?id=<?php echo $final['LearnerId'] ?>" class="btn btn-primary btn-sm" style="width: 100px;">View Goals</a>
                  <a href="updatelearners.php?id=<?php echo $final['LearnerId'] ?>" class="btn btn-primary btn-sm" style="width: 100px;">Update Details</a>
                  <a href="tracklearnerprogress.php?id=<?php echo $final['LearnerId'] ?>&val=<?php echo $_GET['val'] ?>" class="btn btn-primary btn-sm" style="width: 100px;">Track Progress</a>
                  <a href="mcomposeparent.php?pid=<?php echo $final['LearnerId'] ?>" class="btn btn-primary btn-sm" style="width: 100px;">Contact Parent</a>
                  <a href="file.php?lid=<?php echo $final['LearnerId'] ?>&val=<?php echo $_GET['val'] ?>" class="btn btn-primary btn-sm" style="width: 100px;">View Report</a>

                </div>

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
                  <div>
                    <div class="profile-personal-info">
                      <h4 style="font-size: 18px; font-weight: bold; margin-bottom: 15px;">About Me</h4>
                      <p>I'm a creative and curious kid who loves exploring new ideas and finding joy in my favorite activities.
                        I do well in a calm, structured environment where I can learn and grow at my own pace.</p>
                    </div>

                    <div class="profile-personal-info" style="margin-top: 20px;">
                        <h4 style="font-size: 18px; font-weight: bold; margin-bottom: 15px;">Personal Information</h4>

                        <table style="width: 100%; border-collapse: collapse; font-size: 14px; color: #333;">
                          <tr>
                            <td style="width: 30%; padding: 8px 10px; font-weight: bold;">Name:</td>
                            <td style="padding: 8px 10px;"><strong><?php echo $final['Name'] ?></strong></td>
                          </tr>
                          <tr>
                            <td style="width: 30%; padding: 8px 10px; font-weight: bold;">Surname:</td>
                            <td style="padding: 8px 10px;"><?php echo $final['Surname'] ?></td>
                          </tr>
                          <tr>
                            <td style="padding: 8px 10px; font-weight: bold;">Grade:</td>
                            <td style="padding: 8px 10px;"><?php echo $final['Grade'] ?></td>
                          </tr>
                          <tr>
                            <td style="padding: 8px 10px; font-weight: bold;">Email:</td>
                            <td style="padding: 8px 10px;"><?php echo $final['Email'] ?></td>
                          </tr>
                          <tr>
                            <td style="padding: 8px 10px; font-weight: bold;">Contact Number:</td>
                            <td style="padding: 8px 10px;"><?php echo $final['Contact'] ?></td>
                          </tr>
                        </table>
                      </div>


                      
                      <!-- /.here -->
                    </div>
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

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

</body>
</html>
