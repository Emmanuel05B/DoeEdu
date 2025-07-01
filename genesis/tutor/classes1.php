<!DOCTYPE html>
<html>
<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}
include("tutorpartials/head.php");
include('../partials/connect.php');
?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include("tutorpartials/header.php") ?>
  <?php include("tutorpartials/mainsidebar.php") ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>My Classe(s)</h1>
      <p class="text-muted">Below are the subjects you're currently teaching.</p>
    </section>

    <section class="content">
      <div class="row">

      <?php
        $tutorId = $_SESSION['user_id'];

        $sql = "SELECT * FROM tutorsubject  WHERE TutorId = $tutorId";
        $results = $connect->query($sql);

        function getSubjectDetails($subjectId) {
              $grade = null;
              $subjectName = null;

              // Determine grade
              if ($subjectId == 1 || $subjectId == 4) $grade = 10;
              elseif ($subjectId == 2 || $subjectId == 5) $grade = 11;
              elseif ($subjectId == 3 || $subjectId == 6) $grade = 12;

              // Determine subject name
              if (in_array($subjectId, [1, 2, 3])) {
                  $subjectName = "Mathematics";
              } elseif (in_array($subjectId, [4, 5, 6])) {
                  $subjectName = "Physical Sciences";
              }

              return ['grade' => $grade, 'subjectName' => $subjectName];
          }


        while ($row = $results->fetch_assoc()) {
          $subjectId = $row['SubjectId'];
          
          $details = getSubjectDetails($subjectId);
          $grade = $details['grade'];
          $subjectName = $details['subjectName'];

          // Count learners
          $countSQL = "SELECT COUNT(*) AS learnerCount 
                       FROM learnersubject ls 
                       JOIN learners l ON ls.LearnerId = l.LearnerId 
                       WHERE ls.SubjectId = $subjectId 
                         AND l.Grade = $grade 
                         AND ls.ContractExpiryDate > CURDATE()";

          $countResult = $connect->query($countSQL)->fetch_assoc();
          $learnerCount = $countResult['learnerCount'];
      ?>
        <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border text-center" style="background-color:#a3bffa;">
              <h3 class="box-title" style="margin:10px auto;">Grade <?php echo $grade; ?></h3>
              <p><i class="fa fa-book"></i> <?php echo $subjectName; ?></p>
              <p><i class="fa fa-users"></i> <strong><?php echo $learnerCount; ?> learner<?php echo $learnerCount != 1 ? 's' : ''; ?></strong></p> 
            </div>
            <div class="box-body text-center">
              <a href="recordmarks.php?subject=<?php echo $subjectId ?>&grade=<?php echo $grade ?>" class="btn btn-info btn-sm">Record Marks</a>
              <a href="managestudymaterials.php?subject=<?php echo $subjectId ?>&grade=<?php echo $grade ?>" class="btn btn-info btn-sm">Resources</a>
              <a href="alllearner.php?subject=<?php echo $subjectId ?>&grade=<?php echo $grade ?>" class="btn btn-info btn-sm">Open Class</a>
            </div>
          </div>
        </div>
      <?php } ?>

      </div>
    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>
</body>
</html>
