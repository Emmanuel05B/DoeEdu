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
      padding-left: 30px; 
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
      margin-left: 10px; 
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
</style>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  <div class="content-wrapper">

  <?php
    include(__DIR__ . "/../../partials/connect.php");

    if(isset($_GET['id'])){
        $learnerId = $_GET['id'];

        // Get learner details
        $sql = "
          SELECT learners.*, users.Name, users.Surname, users.Email, users.Contact, users.Gender
          FROM learners
          JOIN users ON learners.LearnerId = users.Id
          WHERE learners.LearnerId = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("i", $learnerId); 
        $stmt->execute();
        $results = $stmt->get_result();
        $final = $results->fetch_assoc();

        // Get subjects for this learner
        $subSql = "
          SELECT s.SubjectId, s.SubjectName 
          FROM learnersubject ls
          JOIN subjects s ON ls.SubjectId = s.SubjectId
          WHERE ls.LearnerId = ?";
        $subStmt = $connect->prepare($subSql);
        $subStmt->bind_param("i", $learnerId);
        $subStmt->execute();
        $subResult = $subStmt->get_result();
        $subjectOptions = [];
        while($row = $subResult->fetch_assoc()){
            $subjectOptions[] = $row;
        }
    } else {
        echo 'Invalid learner ID.';
        exit();
    }
  ?>

  <section class="content-header">
    <h1>Learner Profile  <small>...</small></h1>
    <ol class="breadcrumb">
      <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Learner Profile</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">

      <div class="col-md-3">
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
                      <?php foreach($subjectOptions as $sub): ?>
                        <li><a href="tracklearnerprogress.php?val=<?php echo $sub['SubjectId']; ?>&id=<?php echo $final['LearnerId'] ?>"><?php echo $sub['SubjectName']; ?></a></li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                </div>

                <div class="col-auto">
                  <div class="btn-group">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="min-width: 180px;">
                      View Report <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                      <?php foreach($subjectOptions as $sub): ?>
                        <li><a href="file.php?val=<?php echo $sub['SubjectId']; ?>&lid=<?php echo $final['LearnerId'] ?>"><?php echo $sub['SubjectName']; ?></a></li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                </div>
                
              </div>
            </div><br>

            <div class="box-body text-center" style="background-color:#ffb3b3; padding: 10px;">
              <div class="row justify-content-center" style="gap: 5px;">
                
                <div class="col-auto">
                  <a href="updatelearner.php?id=<?php echo $final['LearnerId'] ?>" class="btn btn-danger btn-sm" style="min-width: 180px;"> Update Details</a>
                </div>
                <div class="col-auto">
                  <a href="updatelearner.php?id=<?php echo $final['LearnerId'] ?>" class="btn btn-danger btn-sm" style="min-width: 180px;"> Disable Learner</a>
                </div>
                <div class="col-auto">
                  <a href="updatelearner.php?id=<?php echo $final['LearnerId'] ?>" class="btn btn-danger btn-sm" style="min-width: 180px;"> Deregister Learner</a>
                </div>

              </div>
            </div>

          </div>
        </div>
      </div>
      
      <div class="col-md-9">
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#aboutme" data-toggle="tab">About Me</a></li>
            <li><a href="#supportme" data-toggle="tab">Support Me</a></li>
            <li><a href="#record" data-toggle="tab">Record Marks</a></li>
            <li><a href="#goals" data-toggle="tab">Goals</a></li>
            <li><a href="#zzz" data-toggle="tab">Practice Q Progress</a></li>
          </ul>

          <div class="tab-content">

            <!-- About Me -->
            <div class="active tab-pane" id="aboutme">
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
            </div>

            <!-- Support Me -->
            <div class="tab-pane" id="supportme">
              <!-- Your existing support info HTML remains unchanged -->
              ... (keep as in your current code)
            </div>

            <!-- Record Marks -->
            <div class="tab-pane" id="record">
              <div class="profile-personal-info">
                <div class="profile-skills border-bottom mb-3 pb-2">
                  <h4 class="text-primary mb-2">Capture Learner Marks</h4>
                  <form action="save_marks.php" method="POST">
                    <div class="row">
                      <div class="form-group col-md-6" style="padding-left: 5px;">
                        <label>Subject:</label>
                        <select name="subject" class="form-control input-sm" required>
                          <option value="">-- Select --</option>
                          <?php foreach($subjectOptions as $sub): ?>
                            <option value="<?php echo $sub['SubjectId']; ?>"><?php echo $sub['SubjectName']; ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>

                      <div class="form-group col-md-6" style="padding-left: 5px;">
                        <label>Chapter / Topic:</label>
                        <input type="text" name="chapter" class="form-control input-sm" placeholder="e.g. Fractions" required>
                      </div>
                    </div>

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

                    <div class="form-group mb-2">
                      <label>Remarks / Notes (optional):</label>
                      <textarea name="remarks" class="form-control input-sm" rows="1" placeholder="e.g. Learner struggled with question 3."></textarea>
                    </div>

                    <button type="submit" class="btn btn-xs btn-primary">Save Record</button>
                  </form>
                </div>
              </div>
            </div>

            <!-- Goals Tab -->
            <div class="tab-pane" id="goals">
              <!-- Keep existing Goals HTML as-is -->
              ... (keep as in your current code)jnjk
            </div>

            


            <!-- Practice Q Progress -->
            <div class="tab-pane" id="zzz">
              <!-- Keep current HTML as-is -->
              ... (keep as in your current code)
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>
  </div>

  <div class="control-sidebar-bg"></div>
  </div>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

</body>
</html>
