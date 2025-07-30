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

?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include(__DIR__ . "/../../common/partials/header.php"); ?>
  <?php include(__DIR__ . "/../../common/partials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    
    <section class="content-header">

          <h1>My Classe(s) <small>Below are the classes you've been assigned.</small></h1>
          <ol class="breadcrumb">
            <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Classes</li>
          </ol>

       <div style="display: flex; justify-content: space-between; flex-wrap: wrap;">

        <div style="display: flex; gap: 10px; margin-top: 15px;">
          
          <a href="tutors.php" 
            class="btn btn-primary" 
            style="height: fit-content;">
            Open Tutors
          </a>

          <a href="assigntutorclass.php" 
            class="btn btn-primary" 
            style="height: fit-content;">
            Assign Tutors to Classes
          </a>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="row">

      <?php
     
     /*
        // Fetch all classes created/assigned by this Tutor
        $sql = "SELECT c.*, s.SubjectName, s.MaxClassSize
                FROM classes c
                INNER JOIN subjects s ON c.SubjectID = s.SubjectID
                WHERE c.TutorId = ?";

        $stmt = $connect->prepare($sql);
        $stmt->bind_param("i", $directorId);
        $stmt->execute();
        $result = $stmt->get_result();
        */

        $sql = "SELECT c.*, s.SubjectName, s.MaxClassSize
        FROM classes c
        INNER JOIN subjects s ON c.SubjectID = s.SubjectID";

        $stmt = $connect->prepare($sql);
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
        <div class="col-md-3">
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
<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

</body>
</html>
