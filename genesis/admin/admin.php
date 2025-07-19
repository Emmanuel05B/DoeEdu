<!DOCTYPE html>
<html>
<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}

include('../partials/connect.php');
include("adminpartials/head.php");

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

    <?php include("adminpartials/header.php"); ?>
    <?php include("adminpartials/mainsidebar.php"); ?>

    <div class="content-wrapper">
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
          <div class="col-md-12"> 
            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#add" data-toggle="tab">Add Users</a></li>
                <li><a href="#update" data-toggle="tab">Update Users</a></li>
                <li><a href="#disable" data-toggle="tab">Disable Users</a></li>
              </ul>
              <div class="tab-content">
                <div class="active tab-pane" id="add">
                  <div class="profile-personal-info">
                    <h4>Register</h4>
                    <div class="bubble-container">
                      <a href="addlearners.php" style="color: #1a73e8;" class="bubble">Register Learner</a>
                      <a href="addtutor.php" style="color: #1a73e8;" class="bubble">Register Tutor</a>
                    </div>
                  </div>
                </div>

                <div class="tab-pane" id="update">
                  <div class="profile-personal-info">
                    <h4 class="text-primary mb-4">Tutors</h4>
                    <div class="bubble-container">
                      <?php if (empty($tutors)): ?>
                        <span>No tutors found</span>
                      <?php else: ?>
                        <?php foreach ($tutors as $tutor): ?>
                          <a href="updatetutors.php?id=<?= urlencode($tutor['TutorId']) ?>" class="bubble">
                            <?= htmlspecialchars($tutor['Name'] . ' ' . $tutor['Surname']) ?>
                          </a>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </div>
                  </div>

                  <div class="profile-personal-info">
                    <h4 class="text-primary mb-4">Learners</h4>
                    <div class="bubble-container">
                      <?php if (empty($subjects)): ?>
                        <span>No subjects found</span>
                      <?php else: ?>
                        <?php foreach ($subjects as $subject): ?>
                          <a href="updatelearner.php?id=<?= urlencode($subject['SubjectId']) ?>" class="bubble">
                            <?= htmlspecialchars($subject['SubjectName']) ?>
                          </a>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>

                <div class="tab-pane" id="disable">
                  <!-- Your disable tab content here -->
                </div>
              </div>
            </div>
          </div>
        </div>

      </section>
    </div>

    <div class="control-sidebar-bg"></div>
  </div>

  <?php include("adminpartials/queries.php"); ?>
  <script src="dist/js/demo.js"></script>
</body>
</html>
