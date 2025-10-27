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
  <?php include_once(ADMIN_PATH . "/../partials/header.php"); ?>
  <?php include_once(ADMIN_PATH . "/../partials/mainsidebar.php"); ?>

  <div class="content-wrapper">

  <?php

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
                  
                  <button 
                      type="button" 
                      class="btn btn-primary btn-sm" 
                      data-toggle="modal" 
                      data-target="#modal-contact-parent"
                      data-email="<?php echo htmlspecialchars($final['ParentEmail'] ?? ''); ?>"
                      data-name="<?php echo htmlspecialchars($final['ParentName'] ?? ''); ?>"
                      style="min-width: 180px;"
                  >
                      Contact Parent
                  </button>

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
            <li><a href="#practicequestionsprogress" data-toggle="tab">Practice Q Progress</a></li>
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
              
              
            </div>
            

            <!-- Record Marks -->
            <div class="tab-pane" id="record">
              <div class="profile-personal-info">
                <div class="profile-skills border-bottom mb-3 pb-2">
                  <h4 class="text-primary mb-2">Capture Learner Marks</h4>
                  <form action="save_marks.php" method="POST">
                    <div class="row">
                      <div class="form-group col-md-3" style="padding-left: 5px;">
                        <label>Subject:</label>
                        <select name="subjectId" class="form-control input-sm" required>
                          <option value="">-- Select --</option>
                          <?php foreach($subjectOptions as $sub): ?>
                            <option value="<?php echo $sub['SubjectId']; ?>"><?php echo $sub['SubjectName']; ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>

                      <div class="form-group col-md-4" style="padding-left: 5px;">
                        <label>Chapter / Topic:</label>
                        <input type="text" name="chaptername" class="form-control input-sm" placeholder="e.g. Fractions" required>
                        <input type="hidden" name="learnerId" value="<?php echo $learnerId; ?>" class="form-control input-sm" >
                      </div>
                      <div class="form-group col-md-4" style="padding-left: 5px;">
                        <label>Activity Name:</label>
                        <input type="text" name="activityname" class="form-control input-sm" placeholder="e.g. Quiz 1" required>
                      </div>
                    </div>

                    <div class="row">
                      
                      <div class="form-group col-md-3" style="padding: 0 5px;">
                        <label>Total Marks:</label>
                        <input type="number" name="activitytotal" class="form-control input-sm" placeholder="e.g. 20" required>
                      </div>
                      <div class="form-group col-md-3" style="padding-left: 5px;">
                        <label>Marks Obtained:</label>
                        <input type="number" name="marksobtained" class="form-control input-sm" placeholder="e.g. 15" required>
                      </div>
                    </div>

                    <button type="submit" class="btn btn-xs btn-primary">Save Record</button>
                  </form>
                </div>
              </div>
            </div>

            <!-- Goals Tab -->
            <div class="tab-pane" id="goals">

              <?php
                  // Function to map average mark to level 1–7
                  function getLevelFromMark($avgMark) {
                      if ($avgMark < 30) return 1;       // <30%
                      if ($avgMark < 40) return 2;       // 30–39%
                      if ($avgMark < 50) return 3;       // 40–49%
                      if ($avgMark < 60) return 4;       // 50–59%
                      if ($avgMark < 70) return 5;       // 60–69%
                      if ($avgMark < 80) return 6;       // 70–79%
                      return 7;                           // 80–100%
                  }

                  // Fetch subjects and levels
                  $query = $connect->prepare("
                      SELECT s.SubjectName, ls.CurrentLevel, ls.TargetLevel, ls.LearnerSubjectId
                      FROM learnersubject ls
                      JOIN subjects s ON ls.SubjectId = s.SubjectId
                      WHERE ls.LearnerId = ?
                  ");
                  $query->bind_param("i", $learnerId);
                  $query->execute();
                  $result = $query->get_result();

                while ($goal = $result->fetch_assoc()):
                    $learnerSubjectId = $goal['LearnerSubjectId'];

                    // --- Average Mark ---
                    $activity_sql = "
                        SELECT lam.MarksObtained, a.MaxMarks
                        FROM learneractivitymarks lam
                        JOIN activities a ON lam.ActivityId = a.ActivityId
                        WHERE lam.LearnerId = ? AND a.SubjectId = (
                            SELECT SubjectId FROM learnersubject WHERE LearnerSubjectId = ?
                        )
                    ";
                    $stmtAct = $connect->prepare($activity_sql);
                    $stmtAct->bind_param("ii", $learnerId, $learnerSubjectId);
                    $stmtAct->execute();
                    $resAct = $stmtAct->get_result();

                    $totalMarks = 0;
                    $totalMax = 0;
                    while ($rowAct = $resAct->fetch_assoc()) {
                        $totalMarks += (float)$rowAct['MarksObtained'];
                        $totalMax += (float)$rowAct['MaxMarks'];
                    }
                    $averageMark = ($totalMax > 0) ? ($totalMarks / $totalMax) * 100 : 0;

                    // --- Attendance Rate ---
                    $attendance_sql = "
                        SELECT lam.Attendance
                        FROM learneractivitymarks lam
                        JOIN activities a ON lam.ActivityId = a.ActivityId
                        WHERE lam.LearnerId = ? AND a.SubjectId = (
                            SELECT SubjectId FROM learnersubject WHERE LearnerSubjectId = ?
                        )
                    ";
                    $stmtAtt = $connect->prepare($attendance_sql);
                    $stmtAtt->bind_param("ii", $learnerId, $learnerSubjectId);
                    $stmtAtt->execute();
                    $resAtt = $stmtAtt->get_result();

                    $totalActivities = $resAtt->num_rows;
                    $absentCount = 0;
                    while ($rowAtt = $resAtt->fetch_assoc()) {
                        if ($rowAtt['Attendance'] === 'absent') $absentCount++;
                    }
                    $attendanceRate = ($totalActivities > 0) ? (($totalActivities - $absentCount) / $totalActivities) * 100 : 0;

                    // --- Levels ---
                    $startLevel = $goal['CurrentLevel']; // DB CurrentLevel
                    $targetLevel = $goal['TargetLevel']; // DB TargetLevel
                    $nowLevel = getLevelFromMark($averageMark); // calculated from average marks

                    // Progress %
                    $progressPercent = ($targetLevel > $startLevel) ? (($nowLevel - $startLevel) / ($targetLevel - $startLevel)) * 100 : 0;
                    if ($progressPercent < 0) $progressPercent = 0;
                    if ($progressPercent > 100) $progressPercent = 100;

                    $expectedLevel = $targetLevel; // For display
                ?>

                    <div class="profile-personal-info">
                        <div class="profile-skills border-bottom mb-4 pb-2">
                            <h4 class="text-primary mb-3"><?= htmlspecialchars($goal['SubjectName']) ?></h4>

                            <div class="bubble-container row" style="margin-bottom: 15px;">
                                <div class="bubble col-md-2">Start Level: <span class="label label-primary"><?= $startLevel ?></span></div>
                                <div class="bubble col-md-2">Current Level: <span class="label label-warning"><?= $nowLevel ?></span></div>
                                <div class="bubble col-md-2">Target Level: <span class="label label-success"><?= $targetLevel ?></span></div>
                                <div class="bubble col-md-2">Average Mark: <span class="label label-danger"><?= round($averageMark, 2) ?>%</span></div>
                                <div class="bubble col-md-2">Attendance Rate: <span class="label label-default"><?= round($attendanceRate, 2) ?>%</span></div>
                            </div>

                            <label>Progress Toward Goal:</label>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar progress-bar-info progress-bar-striped active"
                                    role="progressbar" style="width: <?= $progressPercent ?>%;">
                                    Level <?= $nowLevel ?> of <?= $targetLevel ?>
                                </div>
                            </div>

                            <div class="callout callout-info" style="margin-top: 20px;">
                                <h5>Goal Tracker</h5>
                                <p>Based on current average, learner is expected to reach Level <?= $expectedLevel ?> by term end.</p>
                            </div>
                        </div>
                    </div>

                <?php endwhile; ?>

            </div>

            <!-- //////////////////////////////////////////////////////////////////////////////////////////////  -->
            <!-- Practice Q Progress -->
            <div class="tab-pane" id="practicequestionsprogress">

                  <?php
                  foreach($subjectOptions as $sub):
                      $subjectId = $sub['SubjectId'];
                      $subjectName = $sub['SubjectName'];
                      // Fetch GradeName for this subject
                      $subjectInfoSql = "
                          SELECT g.GradeName
                          FROM subjects s
                          JOIN grades g ON s.GradeId = g.GradeId
                          WHERE s.SubjectId = ?
                          LIMIT 1
                      ";
                      $subjectStmt = $connect->prepare($subjectInfoSql);
                      if (!$subjectStmt) {
                          die("Prepare failed: " . $connect->error);
                      }
                      $subjectStmt->bind_param("i", $subjectId);
                      $subjectStmt->execute();
                      $subjectRes = $subjectStmt->get_result();
                      $subjectInfo = $subjectRes->fetch_assoc();
                      $subjectStmt->close();

                      $gradeName = $subjectInfo['GradeName'] ?? 'N/A';

                      // Fetch chapters and levels for this subject
                      $chaptersSql = "
                          SELECT Chapter, LevelId
                          FROM practicequestions
                          WHERE SubjectName = ? AND GradeName = ?
                          ORDER BY Chapter, LevelId
                      ";
                      $stmt = $connect->prepare($chaptersSql);
                      $stmt->bind_param("ss", $subjectName, $gradeName);
                      $stmt->execute();
                      $res = $stmt->get_result();

                      $chapters = [];
                      while($row = $res->fetch_assoc()) {
                          $chapter = $row['Chapter'];
                          $levelId = $row['LevelId'];

                          if(!isset($chapters[$chapter])) {
                              $chapters[$chapter] = ['Easy'=>null,'Medium'=>null,'Hard'=>null];
                          }

                          if($levelId==1) $chapters[$chapter]['Easy'] = $levelId;
                          if($levelId==2) $chapters[$chapter]['Medium'] = $levelId;
                          if($levelId==3) $chapters[$chapter]['Hard'] = $levelId;
                      }
                      $stmt->close();

                      // Fetch learner's completion for this subject
                      $learnerLevelsStmt = $connect->prepare("
                          SELECT LevelId, ChapterName, Complete
                          FROM learnerlevel
                          WHERE LearnerId=? 
                      ");
                      $learnerLevelsStmt->bind_param("i", $learnerId);
                      $learnerLevelsStmt->execute();
                      $resLevels = $learnerLevelsStmt->get_result();
                      $learnerLevels = [];
                      while($row = $resLevels->fetch_assoc()){
                          $learnerLevels[$row['ChapterName']][$row['LevelId']] = $row['Complete'];
                      }
                      $learnerLevelsStmt->close();
                  ?>
                  <div class="profile-personal-info">
                      <h4 class="text-primary mb-2"><?= htmlspecialchars($subjectName) ?>, <?= htmlspecialchars($gradeName) ?></h4>

                      <div class="box box-default">
                          
                          
                              <div class="table-responsive">
                                  <table class="table table-bordered table-hover table-condensed">
                                      <thead>
                                          <tr>
                                              <th>Chapter</th>
                                              <th class="text-center">Easy</th>
                                              <th class="text-center">Medium</th>
                                              <th class="text-center">Hard</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                        <?php if(empty($chapters)): ?>
                                            <tr><td colspan="4" class="text-center">No chapters found.</td></tr>
                                        <?php else: ?>
                                            <?php foreach($chapters as $chapterName => $levels): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($chapterName) ?></td>
                                                    <?php foreach(['Easy'=>1,'Medium'=>2,'Hard'=>3] as $levelName=>$lvlId): ?>
                                                        <td class="text-center">
                                                            <?php 
                                                                if($levels[$levelName]){
                                                                    $isCompleted = !empty($learnerLevels[$chapterName][$lvlId]) && $learnerLevels[$chapterName][$lvlId]==1;
                                                                    $labelClass = $isCompleted ? 'success' : 'default';
                                                                    $symbol = $isCompleted ? '✔' : '○'; // ✔ = Completed, ○ = Not completed
                                                                    ?>
                                                                    <button type="button" class="btn btn-xs btn-<?= $labelClass ?>" 
                                                                        data-toggle="modal" data-target="#levelModal"
                                                                        data-subject="<?= htmlspecialchars($subjectName) ?>"
                                                                        data-chapter="<?= htmlspecialchars($chapterName) ?>"
                                                                        data-level="<?= $levelName ?>"
                                                                        title="<?= $isCompleted ? 'Completed' : 'Not Completed' ?>"
                                                                    >
                                                                        <?= $symbol ?>
                                                                    </button>
                                                                <?php 
                                                                } else {
                                                                    echo '<span class="text-muted">N/A</span>';
                                                                }
                                                            ?>
                                                        </td>
                                                    <?php endforeach; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                      </tbody>

                                  </table>
                              </div>
                          
                      </div>
                    
                  </div>
                  <?php endforeach; ?>
                
            </div>

            <!-- Static Modal -->
            <div class="modal fade" id="levelModal" tabindex="-1" role="dialog" aria-labelledby="levelModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="levelModalLabel">Level Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <!-- Static content for now -->
                    <p>Level details will be displayed here.</p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Optional JS to update modal title dynamically -->
            <script>
            $('#levelModal').on('show.bs.modal', function (event) {
              var button = $(event.relatedTarget); 
              var subject = button.data('subject');
              var chapter = button.data('chapter');
              var level = button.data('level');
              var modal = $(this);
              modal.find('.modal-title').text(subject + " — " + chapter + " (" + level + ")");
            });
            </script>
            <!-- /////////////////////////////////////////////////////////////////////////////////////////////// -->
          
          </div>
        </div>
      </div>
    </div>
  </section>
  </div>

  <div class="control-sidebar-bg"></div>
  </div>

<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>

<!-- Contact Parent Modal -->
<div class="modal fade" id="modal-contact-parent" tabindex="-1" role="dialog" aria-labelledby="contactParentLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">

      <div class="modal-header bg-info">
        <h4 class="modal-title" id="contactParentLabel">Contact Parent: <span id="contactParentNameHeader"></span></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <form action="emailsuperhandler.php" method="post">
          <div class="form-group">
            <input type="email" id="contactParentEmail" class="form-control" name="emailto" placeholder="Email to:" required>
          </div>
          <div class="form-group">
            <input type="text" class="form-control" name="subject" placeholder="Subject" required>
          </div>
          <div class="form-group">
            <textarea class="textarea" name="message" placeholder="Message" style="width: 100%; height: 125px; border: 1px solid #ddd;" required></textarea>
          </div>

          <!-- Hidden inputs for the handler -->
          <input type="hidden" name="action" value="general">
          <input type="hidden" name="redirect" value="learnerprofile.php?id=<?php echo $learnerId; ?>">

          <input type="submit" class="btn btn-primary" value="Submit" name="btnsend">
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
      </div>

    </div>
  </div>
</div>

<?php
    if (isset($_SESSION['success'])) {
        $msg = $_SESSION['success'];
        unset($_SESSION['success']);
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Email Sent',
                text: '". addslashes($msg) ."',
                confirmButtonText: 'OK'
            });
        </script>";
    }

    if (isset($_SESSION['error'])) {
        $msg = $_SESSION['error'];
        unset($_SESSION['error']);
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Failed to Send',
                text: '". addslashes($msg) ."',
                confirmButtonText: 'OK'
            });
        </script>";
    }
    if (isset($_SESSION['successMarks'])) {
        $msg = $_SESSION['successMarks'];
        unset($_SESSION['successMarks']);
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Marks Recorded!',
                text: '". addslashes($msg) ."',
                confirmButtonText: 'OK'
            });
        </script>";
    }
  ?>


<script>
$('#modal-contact-parent').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); 
    var email = button.data('email');  
    var name = button.data('name'); 
    var modal = $(this);
    modal.find('#contactParentEmail').val(email); // populate email input
    modal.find('#contactParentNameHeader').text(name); // populate header with parent name
});
</script>

</body>
</html>
