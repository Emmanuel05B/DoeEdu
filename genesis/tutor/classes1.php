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
      <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;">
        <div>
          <h3>My Classe(s)</h3>
          <p class="text-muted">Below are the classes you've created or assigned.</p>
        </div>
 
      </div>
    </section>

    <section class="content">
      <div class="row">

      <?php

        $tutorId = $_SESSION['user_id'];
     
        // Fetch all classes assigned for this Tutor
        $sql = "SELECT c.*, s.SubjectName, s.MaxClassSize
                FROM classes c
                INNER JOIN subjects s ON c.SubjectID = s.SubjectID
                WHERE c.TutorId = ?";

        $stmt = $connect->prepare($sql);
        $stmt->bind_param("i", $tutorId);
        $stmt->execute();
        $result = $stmt->get_result();


        while ($row = $result->fetch_assoc()) {
          $classId = $row['ClassID'];
          $grade = $row['Grade'];
          $group = $row['GroupName'];
          $subjectName = $row['SubjectName'];
          $learnerCount = $row['CurrentLearnerCount'];
          $maxSize = $row['MaxClassSize'];
          $status = $learnerCount >= $maxSize ? 'Full' : 'Not Full';
      ?>
        <div class="col-md-4">
          <div class="box box-primary">
            <div class="box-header with-border text-center">
              <h3 class="box-title" style="margin:10px auto;">Grade <?php echo $grade; ?></h3>
              <p><i class="fa fa-book"></i> <?php echo $subjectName; ?> - Group <?php echo $group; ?></p>
              <p><i class="fa fa-users"></i> <strong><?php echo $learnerCount; ?> learner<?php echo $learnerCount != 1 ? 's' : ''; ?></strong></p>
              <p><i class="fa fa-circle text-<?php echo $status == 'Full' ? 'red' : 'green'; ?>"></i> <?php echo $status; ?></p>
            </div>
            <div class="box-body text-center" style="background-color:#a3bffa;">
              <a href="chapters.php?subject=<?php echo $row['SubjectID'] ?>&grade=<?php echo $grade ?>&group=<?php echo $group ?>" class="btn btn-info btn-sm" style="width: 100px;">Record Marks</a>
              <a href="actychapters.php?subject=<?php echo $row['SubjectID'] ?>&grade=<?php echo $grade ?>&group=<?php echo $group ?>" class="btn btn-info btn-sm" style="width: 100px;">Create Quiz</a>
              <a href="managestudymaterials.php?subject=<?php echo $row['SubjectID'] ?>&grade=<?php echo $grade ?>&group=<?php echo $group ?>" class="btn btn-info btn-sm" style="width: 100px;">Resources</a>
              <a href="alllearner.php?subject=<?php echo $row['SubjectID'] ?>&grade=<?php echo $grade ?>&group=<?php echo $group ?>" class="btn btn-info btn-sm" style="width: 100px;">Open Class</a>
            </div>
          </div>
        </div>
      <?php } ?>

      </div>
    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<!-- Scripts -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="dist/js/demo.js"></script>
</body>
</html>
