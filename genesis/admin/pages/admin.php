<!DOCTYPE html>
<html>
<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
  exit();
}

include(__DIR__ . "/../../common/partials/head.php"); 
include(__DIR__ . "/../../partials/connect.php");

// Fetch all tutors 
$tutors = [];
$sqlTutors = "
    SELECT 
        t.TutorId, u.Name, u.Surname
    FROM tutors t
    JOIN users u ON t.TutorId = u.Id
    ORDER BY u.Surname ASC, u.Name ASC
";
$resultTutors = $connect->query($sqlTutors);
if ($resultTutors) {
    while ($row = $resultTutors->fetch_assoc()) {
        $tutors[] = $row;
    }
} else {
    die("Failed to fetch tutors: " . $connect->error);
}

// Fetch all subjects
$subjects = [];
$sqlSubjects = "
    SELECT SubjectId, SubjectName 
    FROM subjects
    ORDER BY SubjectName ASC
";
$resultSubjects = $connect->query($sqlSubjects);
if ($resultSubjects) {
    while ($row = $resultSubjects->fetch_assoc()) {
        $subjects[] = $row;
    }
} else {
    die("Failed to fetch subjects: " . $connect->error);
}
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
    text-decoration: none;
    color: #000;
    display: inline-block;
    white-space: nowrap;
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
   
        <section class="content-header">
          <h1>
            Administration
            <small>Admin</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Administration</li>
          </ol><br>
        </section>
        <section class="content">
  <div class="row">
    <div class="col-md-12"> 
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#add" data-toggle="tab">Add Users</a></li>
          <li><a href="#update" data-toggle="tab">Update Users</a></li>
          <li><a href="#settings" data-toggle="tab">System Settings</a></li>
        </ul>
        <div class="tab-content">

          <!-- Add Users -->
          <div class="active tab-pane" id="add">
            <div class="profile-personal-info">
              <h4 class="text-primary mb-4">Register</h4>
              <div class="bubble-container">
                <a href="addlearners.php" class="bubble">Add Learners</a>
                <a href="addtutor.php" class="bubble">Add Tutors</a>
                <a href="addschool.php" class="bubble">Add School</a>
                <a href="manage_inviterequests.php" class="bubble">Manage Requests</a>
                <a href="addlearnersv2.php" class="bubble">V2</a>
              </div>
            </div>
          </div>

          <!-- Update Users -->
          <div class="tab-pane" id="update">
            <div class="profile-personal-info">
              <h4 class="text-primary mb-4">Update</h4>
              <div class="bubble-container">
                <a href="updatelearnerlist.php" class="bubble">Update Learner</a>
                <a href="updatetutorlist.php" class="bubble">Update Tutor</a>
                <a href="updateschoollist.php" class="bubble">Update School</a>
              </div>
            </div>
          </div>

          <!-- System Settings -->
          <div class="tab-pane" id="settings">
            <div class="profile-personal-info">
              <h4 class="text-primary mb-4">System Settings</h4>
              <div class="bubble-container">
                <a href="general_settings.php" class="bubble">General Settings</a>
                <a href="roles_permissions.php" class="bubble">Roles & Permissions</a>
                <a href="audit_logs.php" class="bubble">Audit Logs</a>
              </div>
            </div>
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

<!-- Deregister Learner Modal -->
<div class="modal fade" id="deregisterLearnerModal" tabindex="-1" role="dialog" aria-labelledby="deregisterLearnerLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      
      <div class="modal-header bg-red">
        <h4 class="modal-title" id="deregisterLearnerLabel">Deregister Learner from Program</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="process_deregistration.php" method="POST">
        <div class="modal-body">
          <p>Are you sure you want to deregister this learner from the program completely?</p>
          
          <!-- Learner selection -->
          <div class="form-group">
            <label for="learnerSelect">Select Learner</label>
            <select name="LearnerId" id="learnerSelect" class="form-control" required>
              <option value="">-- Choose Learner --</option>
              <?php
              $learnersQuery = "
                SELECT l.LearnerId, u.Name, u.Surname
                FROM learners l
                JOIN users u ON l.LearnerId = u.Id
                ORDER BY u.Surname, u.Name
              ";
              $res = $connect->query($learnersQuery);
              while ($row = $res->fetch_assoc()) {
                  echo "<option value='{$row['LearnerId']}'>{$row['Surname']}, {$row['Name']}</option>";
              }
              ?>
            </select>
          </div>

          <!-- Reason -->
          <div class="form-group">
            <label for="reason">Reason for Deregistration</label>
            <textarea name="Reason" id="reason" class="form-control" rows="3" placeholder="Enter reason..." required></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Deregister</button>
        </div>
      </form>
    </div>
  </div>
</div>

</body>
</html>
