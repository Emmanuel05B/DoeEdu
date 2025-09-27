<!DOCTYPE html>
<html>
<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
  exit();
}
include(__DIR__ . "/../../common/partials/head.php");  //
include(__DIR__ . "/../../partials/connect.php");

?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    
    <section class="content-header">

          <h1>My Classe(s) <small>Below are the classes you've been assigned.</small></h1>
          <ol class="breadcrumb">
            <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Classes</li>
          </ol>

    </section>

    <section class="content">
      <div class="row">

      <?php
     
        $tutorId = $_SESSION['user_id'];
     
        // Fetch all classes assigned for this Tutor
        $sql = "SELECT c.*, s.SubjectName, s.MaxClassSize, s.SubjectId
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
        <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border text-center">
              <h3 class="box-title" style="margin:10px auto;"><?php echo $grade; ?></h3>
              <p><i class="fa fa-book"></i> <?php echo $subjectName; ?> - Group <?php echo $group; ?></p>
              <p><i class="fa fa-users"></i> <strong><?php echo $learnerCount; ?> learner<?php echo $learnerCount != 1 ? 's' : ''; ?></strong></p>
              <p>
                <i class="fa fa-circle text-<?php echo $row['Status'] == 'Full' ? 'red' : 'green'; ?>"></i> 
                <?php echo $row['Status']; ?>
              </p>            
            </div>
            <div class="box-body text-center" style="background-color:#a3bffa;">
              <!-- Button that triggers modal -->
                     
              <button 
                  class="btn btn-info btn-sm" 
                  style="width: 100px;" 
                  data-toggle="modal" 
                  data-target="#modal-recordMarks"
                  data-grade="<?php echo $grade; ?>"
                  data-subject="<?php echo $row['SubjectId']; ?>"
                  data-group="<?php echo $group; ?>">
                  Record Marks
              </button>
                          
              <a href="assignedquizzes.php?sub=<?php echo $row['SubjectID'] ?>&gra=<?php echo $grade ?>&group=<?php echo $group ?>" class="btn btn-info btn-sm" style="width: 100px;">+ Quizzes</a>
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



<!-- Record Marks Modal -->
<div class="modal fade" id="modal-recordMarks" tabindex="-1" role="dialog" aria-labelledby="recordMarksLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      
      <div class="modal-header bg-info">
        <h4 class="modal-title" id="recordMarksLabel">Record Marks</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="modalhandler.php" method="post">
        <div class="modal-body">

          <div class="row">
            <!-- Activity Name -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="activityName">Activity Name</label>
                <input type="text" class="form-control" id="activityName" name="activityname" required>
              </div>
            </div>

            <!-- Chapter Name -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="chapterName">Chapter Name</label>
                <input type="text" class="form-control" id="chapterName" name="chaptername" required>
              </div>
            </div>

            <!-- Activity Total -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="activityTotal">Activity Total</label>
                <input type="number" class="form-control" id="activityTotal" name="activitytotal" min="1" max="100" required>
              </div>
            </div>
          </div>

          <!-- Hidden inputs to send to handler -->
          <input type="hidden" id="graid" name="graid">
          <input type="hidden" id="subid" name="subid">
          <input type="hidden" id="groupid" name="group">
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
$('#modal-recordMarks').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal 
    var modal = $(this);

    // Get data attributes
    var grade = button.data('grade');
    var subject = button.data('subject');
    var group = button.data('group');

    // Fill hidden inputs
    modal.find('#graid').val(grade);
    modal.find('#subid').val(subject);
    modal.find('#groupid').val(group);
});
</script>





</body>
</html>
