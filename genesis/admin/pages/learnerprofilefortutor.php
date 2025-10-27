

<!DOCTYPE html>
<html>
<?php

require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");
include_once(COMMON_PATH . "/../partials/head.php");  
?>

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

  <?php include_once(ADMIN_PATH . "/../partials/header.php"); ?>
  <?php include_once(ADMIN_PATH . "/../partials/mainsidebar.php"); ?>


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

          <?php

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


                <div class="box-body text-center" style="background-color:#a3bffa; padding: 10px;">
                 
                  <div class="row justify-content-center" style="gap: 5px;">
                    
                    <div class="col-auto">
                      <a href="mcomposeparent.php?pid=<?php echo $final['LearnerId'] ?>" 
                        class="btn btn-primary btn-sm" style="min-width: 180px;"> Contact Parent
                      </a>
                    </div>
                
                    <div class="col-auto">
                      <div class="btn-group">
                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="min-width: 180px;">
                          Track Progress <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                          <li><a href="tracklearnerprogress.php?val=4&id=<?php echo $final['LearnerId'] ?>">Mathematics</a></li>
                          <li><a href="tracklearnerprogress.php?val=5&id=<?php echo $final['LearnerId'] ?>">Physical Science</a></li>
                        </ul>
                      </div>
                    </div>

                    <div class="col-auto">
                      <div class="btn-group">
                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="min-width: 180px;">
                          View Report <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                          <li><a href="file.php?val=4&lid=<?php echo $final['LearnerId'] ?>">Mathematics</a></li>
                          <li><a href="file.php?val=5&lid=<?php echo $final['LearnerId'] ?>">Physical Science</a></li>
                        </ul>
                      </div>
                    </div>

                    
                  </div>

                </div><br>

                <div class="box-body text-center" style="background-color:#ffb3b3; padding: 10px;">
                 
                  <div class="row justify-content-center" style="gap: 5px;">
                    
                    <div class="col-auto">
                      <a href="updatelearner.php?id=<?php echo $final['LearnerId'] ?>" 
                        class="btn btn-danger btn-sm" style="min-width: 180px;"> Update Details
                      </a>
                    </div>
                    <div class="col-auto">
                      <a href="updatelearner.php?id=<?php echo $final['LearnerId'] ?>" 
                        class="btn btn-danger btn-sm" style="min-width: 180px;"> Disable Learner
                      </a>
                    </div>
                    <div class="col-auto">
                      <a href="updatelearner.php?id=<?php echo $final['LearnerId'] ?>" 
                        class="btn btn-danger btn-sm" style="min-width: 180px;"> Deregister Learner
                      </a>
                    </div>


                    
                  </div>

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
                <li><a href="#record" data-toggle="tab">Record Marks</a></li>
                <li><a href="#more" data-toggle="tab">.....</a></li>
                <li><a href="#goals" data-toggle="tab">Goals</a></li>
                <li><a href="#zzz" data-toggle="tab">zzz</a></li>


              </ul>
              <div class="tab-content">
                <!-- about me tab-pane -->
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
                        <div class="col-9"><strong><p><?php echo $final['Contact'] ?></p></strong></div>
                      </div>
                  </div>

                  
                  <!-- /.here -->
                </div>

                <!-- support me tab-pane -->
                <div class="tab-pane" id="supportme">
                  <!-- here -->
                  <div class="profile-personal-info">

                              <h4 class="text-primary mb-4">Support Strategies</h4>
                              <!-- Content for support strategies -->
                              
                                <ul>
                                    <li>I need visuals to help me understant what is happening</li>
                                    <li>I need time to process information</li>
                                    <li>Language needs to be short and clear</li>
                                    
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

                  <div class="profile-personal-info">
                  
                    <div class="profile-skills border-bottom mb-4 pb-2">
                      <h4 class="text-primary mb-4">Things I Love</h4>
                      <div class="bubble-container">
                        <span class="bubble">Puzzles</span>
                        <span class="bubble">Being outside</span>
                      </div>
                    </div>
                  </div>
                  <div class="profile-personal-info">
                   <div class="profile-skills border-bottom mb-4 pb-2">
                              <h4 class="text-primary mb-4">My Hopes and Wishes</h4>
                              <div class="bubble-container">
                                <span class="bubble">To be happy</span>
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
                 
                </div>

                <!-- record tab-pane -->
                <div class="tab-pane" id="record">
                  <div class="profile-personal-info">
                      <div class="profile-skills border-bottom mb-3 pb-2">
                        <h4 class="text-primary mb-2">Capture Learner Marks</h4>

                        <form action="save_marks.php" method="POST">

                          <!-- Subject, Total Marks, Marks Obtained in One Row -->
                          <div class="row">
                            <div class="form-group col-md-6" style="padding-left: 5px;">
                              <label>Subject:</label>
                              <select name="subject" class="form-control input-sm" required>
                                <option value="">-- Select --</option>
                                <option value="Mathematics">Mathematics</option>
                                <option value="Natural Sciences">Natural Sciences</option>
                                <option value="English">English</option>
                              </select>
                            </div>

                            <div class="form-group col-md-6" style="padding-left: 5px;">
                              <label>Chapter / Topic:</label>
                              <input type="text" name="chapter" class="form-control input-sm" placeholder="e.g. Fractions" required>
                            </div>
                          </div>

                          <!-- Chapter / Topic and Activity Name -->
                          <div class="row">
                            <div class="form-group col-md-6" style="padding-left: 5px;">
                              <label>Activity Name:</label>
                              <input type="text" name="activity" class="form-control input-sm" placeholder="e.g. Quiz 1" required>
                            </div>

                            <div class="form-group col-md-3" style="padding: 0 5px;">
                              <label>Total Marks:</label>
                              <input type="number" name="total_marks" class="form-control input-sm" placeholder="e.g. 20" required>
                            </div>

                            <div class="form-group col-md-3" style="padding-left: 5px;">
                              <label>Marks Obtained:</label>
                              <input type="number" name="marks_obtained" class="form-control input-sm" placeholder="e.g. 15" required>
                            </div>
                          </div>

                          <!-- Remarks -->
                          <div class="form-group mb-2">
                            <label>Remarks / Notes (optional):</label>
                            <textarea name="remarks" class="form-control input-sm" rows="1" placeholder="e.g. Learner struggled with question 3."></textarea>
                          </div>

                          <!-- Submit -->
                          <button type="submit" class="btn btn-xs btn-primary">Save Record</button>

                        </form>
                      </div>

                  </div>
                </div>

                <!-- more tab-pane -->
                <div class="tab-pane" id="more">
                  <div class="profile-personal-info">
                    <div class="profile-skills border-bottom mb-4 pb-2">
                      <h4 class="text-primary mb-3">Mathematics</h4>
                      
                      <div class="bubble-container">
                        <div class="bubble">Algebra <span class="label label-success">Completed</span></div>
                        <div class="bubble">Fractions <span class="label label-warning">In Progress</span></div>
                      </div>

                      <div style="margin-top: 15px;">
                        <label>Enter Mark:</label>
                        <input type="text" value="Fractions" readonly style="border: none; background: transparent;">
                        <input type="number" class="form-control input-sm" style="width: 100px; display: inline;" placeholder="e.g. 65">
                        <button class="btn btn-xs btn-primary">Save</button>
                      </div>

                      <div style="margin-top: 15px;">
                        <label>Notes:</label>
                        <textarea class="form-control" rows="2" placeholder="e.g. Needs help with fractions."></textarea>
                        <button class="btn btn-xs btn-success" style="margin-top: 5px;">Save Note</button>
                      </div>

                      <div class="callout callout-info" style="margin-top: 20px;">
                        <h5>Progress Summary</h5>
                        <p>Completed 1 of 2 chapters. Avg Mark: <strong>70%</strong></p>
                      </div>
                    </div>
                  </div>
                </div>


                 <!-- Goals tab-pane -->
                  <div class="tab-pane" id="goals">

                    <!-- Mathematics Goal -->
                    <div class="profile-personal-info">
                      <div class="profile-skills border-bottom mb-4 pb-2">
                        <h4 class="text-primary mb-3">Mathematics Goal</h4>

                        <!-- Grid for Level Info -->

                        <div class="bubble-container row" style="margin-bottom: 15px;">
                          <div class="bubble col-md-2">Start Level: <span class="label label-primary">2</span></div>
                          <div class="bubble col-md-2">Current Level: <span class="label label-warning">3</span></div>
                          <div class="bubble col-md-2">Target Level: <span class="label label-success">6</span></div>
                          <div class="bubble col-md-2">Average Mark: <span class="label label-danger">58%</span></div>
                          <div class="bubble col-md-2">Attendance Rate: <span class="label label-default">78%</span></div>
                        </div>

                        <!-- Progress Bar -->
                        <label>Progress Toward Goal:</label>
                        <div class="progress" style="height: 20px;">
                          <div class="progress-bar progress-bar-info progress-bar-striped active"
                              role="progressbar" style="width: 50%;">
                            Level 3 of 6
                          </div>
                        </div>

                        <!-- Summary -->
                        <div class="callout callout-info" style="margin-top: 20px;">
                          <h5>Goal Tracker</h5>
                          <p>Based on current average, learner is expected to reach Level 4 by term end.</p>
                        </div>
                      </div>
                    </div>

                    <!-- Physical Sciences Goal -->
                    <div class="profile-personal-info">
                      <div class="profile-skills border-bottom mb-4 pb-2">
                        <h4 class="text-primary mb-3">Physical Sciences Goal</h4>

                        <!-- Grid for Level Info -->
                        <div class="row" style="margin-bottom: 15px;">
                          <div class="col-md-3"><strong>Start Level:</strong><p>1</p></div>
                          <div class="col-md-3"><strong>Current Level:</strong><p>2</p></div>
                          <div class="col-md-3"><strong>Target Level:</strong><p>5</p></div>
                          <div class="col-md-3"><strong>Average Mark:</strong><p>46%</p></div>
                        </div>

                        <!-- Progress Bar -->
                        <label>Progress Toward Goal:</label>
                        <div class="progress" style="height: 20px;">
                          <div class="progress-bar progress-bar-warning progress-bar-striped active"
                              role="progressbar" style="width: 40%;">
                            Level 2 of 5
                          </div>
                        </div>

                        <!-- Summary -->
                        <div class="callout callout-warning" style="margin-top: 20px;">
                          <h5>Goal Tracker</h5>
                          <p>Progress is slow. Recommend additional practice sessions.</p>
                        </div>
                      </div>
                    </div>

                  </div>











         

                <!-- Practice Progress Tab -->
<!-- Tutor View of Learner's Practice Progress -->
<div class="tab-pane" id="zzz">
  <div class="profile-personal-info">
    <div class="profile-skills border-bottom mb-4 pb-2">
      <h4 class="text-primary mb-3">Practice Question Progress</h4>

      <!-- Level Breakdown Table -->
      <div class="table-responsive">
        <table class="table table-condensed table-bordered">
          <thead>
            <tr class="bg-gray">
              <th>Level</th>
              <th>Attempts</th>
              <th>Avg Score</th>
              <th>Best Score</th>
              <th>Status</th>
              <th>Time Spent</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Easy</td>
              <td>3</td>
              <td>68%</td>
              <td>76%</td>
              <td><span class="label label-success">Passed</span></td>
              <td>13 min</td>
            </tr>
            <tr>
              <td>Medium</td>
              <td>1</td>
              <td>54%</td>
              <td>54%</td>
              <td><span class="label label-danger">Failed</span></td>
              <td>8 min</td>
            </tr>
            <tr>
              <td>Hard</td>
              <td>0</td>
              <td>-</td>
              <td>-</td>
              <td><span class="label label-default">Not Attempted</span></td>
              <td>-</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pattern Analysis (Optional but Insightful) -->
      <div class="box box-default box-solid">
        <div class="box-header with-border">
          <h5 class="box-title">Tutor Observations</h5>
        </div>
        <div class="box-body">
          <ul style="margin-left: 20px;">
            <li>Struggles with Medium-level logic questions (avg score below pass mark).</li>
            <li>Completes levels faster than peers (avg time per level: ~10 min).</li>
            <li>Performs better on conceptual questions vs. memory-based ones.</li>
            <li>Shows improvement with each Easy-level attempt.</li>
          </ul>
        </div>
      </div>

      <!-- Additional Tutor Note -->
      <div class="form-group mt-2">
        <label>Tutor Note (Private)</label>
        <textarea name="note" class="form-control input-sm" rows="2" placeholder="e.g. Consider giving extra support on algebra-based problems."></textarea>
      </div>
    </div>
  </div>
</div>



















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

<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>

</body>
</html>
