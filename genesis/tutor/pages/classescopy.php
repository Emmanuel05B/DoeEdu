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
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

<?php include_once(TUTOR_PATH . "/../partials/header.php"); ?> 
<?php include_once(TUTOR_PATH . "/../partials/mainsidebar.php"); ?>


  <?php if (isset($_GET['added']) && $_GET['added'] == 1): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
    icon: 'success',
    title: 'Link Added!',
    text: 'The meeting link has been successfully added for this class.',
    confirmButtonText: 'OK'
});
</script>
<?php endif; ?>

<?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
    icon: 'error',
    title: 'Oops!',
    text: 'Something went wrong while adding the link. Please try again.',
    confirmButtonText: 'OK'
});
</script>
<?php endif; ?>


  <div class="content-wrapper">
    
    <section class="content-header">

          <h1>My Classe(s) <small>Below are the classes you've been assigned.</small></h1>
          <ol class="breadcrumb">
            <li><a href="tutorindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Classes</li>
          </ol>

    </section>

    <section class="content">

      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <div class="small-box" style="background:#a3bffa; color:#000;">
            <div class="inner">
              <h3>34</h3>
              <p>My Uploads</p>
            </div>
            <div class="icon">
              <i class="fa fa-tasks"></i>
            </div>
            <a href="manageactivities.php" class="small-box-footer" style="color:#d4dbff;">
              Manage Uploads <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <div class="small-box" style="background:#3498db; color:#ffffff;">
            <div class="inner">
              <h3>5</h3>
              <p>Pending Session Requests</p>
            </div>
            <div class="icon">
              <i class="fa fa-clock-o"></i>
            </div>
            <a href="sessionrequests.php" class="small-box-footer" style="color:#d7cafb;">
              View Requests <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <div class="small-box" style="background:#9f86d1; color:#fff;">
            <div class="inner">
              <h3>120</h3>
              <p>My Learners</p>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
            <a href="classes1.php" class="small-box-footer" style="color:#d7cafb;">
              Visit Classes <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <div class="small-box" style="background:#556cd6; color:#fff;">
            <div class="inner">
              <h3>3</h3>
              <p>Reminders</p>
            </div>
            <div class="icon">
              <i class="fa fa-calendar"></i>
            </div>
            <a href="manageactivities.php" class="small-box-footer" style="color:#d7cafb;">
              View reminders <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
      </div>

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

            // Determine box color
            $boxColor = $learnerCount == 0 ? '#f8d7da' : '#ffffff'; // redish if 0 learners, default white otherwise
      
        ?>
        <div class="col-md-6">
          <div class="box box-primary" style="background-color: <?php echo $boxColor; ?>;">
            <div class="box-header with-border text-center">
              <h3 class="box-title" style="margin:10px auto;"><?php echo $grade; ?></h3>
              <p><i class="fa fa-book"></i> <?php echo $subjectName; ?> - Group <?php echo $group; ?></p>
              <p><i class="fa fa-users"></i> <strong><?php echo $learnerCount; ?> learner<?php echo $learnerCount != 1 ? 's' : ''; ?></strong></p>
              <p>
                <i class="fa fa-circle text-<?php echo $row['Status'] == 'Full' ? 'red' : 'green'; ?>"></i> 
                <?php echo $row['Status']; ?>
              </p>            
            </div>
            
            <div class="box-body text-center" style="background-color: <?php echo $learnerCount == 0 ? '#f7c6c7' : '#a3bffa'; ?>;">

              <?php $disabled = $learnerCount == 0 ? 'disabled" style="pointer-events:none;' : ''; ?>
              <?php $btnClass = $learnerCount == 0 ? 'btn btn-default btn-sm' : 'btn btn-info btn-sm'; ?>
              <!-- Button that triggers modal -->
                     
             
              <button 
                class="<?php echo $btnClass; ?> <?php echo $disabled; ?>" 
                style="width: 90px;" 
                data-toggle="modal" 
                data-target="#modal-recordMarks"
                data-grade="<?php echo $grade; ?>"
                data-subject="<?php echo $row['SubjectId']; ?>"
                data-subjectname="<?php echo $subjectName; ?>"
                data-group="<?php echo $group; ?>">
                Record Marks
              </button>

              <!-- Quizzes button -->
              <a href="assignedquizzes.php?sub=<?php echo $row['SubjectID'] ?>&gra=<?php echo $grade ?>&group=<?php echo $group ?>" 
                class="<?php echo $btnClass; ?> <?php echo $disabled; ?>" 
                style="width: 90px;">
                + Quizzes
              </a>

              <a href="resources.php?sub=<?php echo $row['SubjectID'] ?>&gra=<?php echo $grade ?>&group=<?php echo $group ?>" 
                class="<?php echo $btnClass; ?> <?php echo $disabled; ?>" 
                style="width: 90px;">
                + Resources
              </a>

              <a href="alllearner.php?subject=<?php echo $row['SubjectID'] ?>&grade=<?php echo $grade ?>&group=<?php echo $group ?>"
                class="btn btn-info btn-sm" 
                style="width: 90px;">
                Open Class
              </a>
              
              <button 
                
                class="<?php echo $btnClass; ?> <?php echo $disabled; ?>" 
                style="width: 90px;" 
                data-toggle="modal" 
                data-target="#modal-addMeetingLink"
                data-class="<?php echo $classId; ?>"
                data-grade="<?php echo $grade; ?>"
                data-subject="<?php echo $row['SubjectID']; ?>"
                data-subjectname="<?php echo $subjectName; ?>"
                data-group="<?php echo $group; ?>">
                Add Link
              </button>

              <button 
                class="<?php echo $btnClass; ?> <?php echo $disabled; ?>"  
                style="width: 90px;"
                data-toggle="modal" 
                data-target="#modal-notifyClass"
                data-grade="<?php echo $grade; ?>"
                data-subject="<?php echo $subjectName; ?>"
                data-group="<?php echo $group; ?>">
                Notify Class
              </button>


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
<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>


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
          <p id="modalClassInfoRecord" style="margin-bottom:15px;"></p>


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

<!-- Add Meeting Link Modal -->
<div class="modal fade" id="modal-addMeetingLink" tabindex="-1" role="dialog" aria-labelledby="addMeetingLinkLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      
      <div class="modal-header bg-info">
        <h4 class="modal-title" id="addMeetingLinkLabel">Add Meeting Link</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="addmeetinghandler.php" method="post">
        <div class="modal-body">
          
          <p id="modalClassInfo" style=" margin-bottom:15px;">
            <!-- JS will fill this -->
          </p>
          <div class="form-group">
            <label for="meetingLink">Meeting Link</label>
            <input type="url" class="form-control" id="meetingLink" name="meetinglink" required placeholder="https://meet.google.com/...">
          </div>

          <div class="form-group">
            <label for="meetingNotes">Notes (optional)</label>
            <textarea class="form-control" id="meetingNotes" name="notes" rows="3" placeholder="Any details or comments"></textarea>
          </div>

          <!-- Hidden inputs -->
          <input type="hidden" id="classid" name="classid">
          <input type="hidden" id="grade" name="grade">
          <input type="hidden" id="subjectid" name="subjectid">
          <input type="hidden" id="groupname" name="groupname">
          <input type="hidden" id="subjectname" name="subjectname">
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" name="submit" class="btn btn-primary">Save Link</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Notify Class Modal -->
<div class="modal fade" id="modal-notifyClass" tabindex="-1" role="dialog" aria-labelledby="notifyClassLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      
      <div class="modal-header bg-info">
        <h4 class="modal-title" id="notifyClassLabel">Notify Class</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="addclassnoticeh.php" method="post">
        <div class="modal-body">
          <p id="modalClassInfoNotice" style="margin-bottom:15px;"></p>

          <div class="form-group">
            <label>Title <span style="color:red">*</span></label>
            <input type="text" name="title" class="form-control" required>
          </div>

          <div class="form-group">
            <label>Content <span style="color:red">*</span></label>
            <textarea name="content" class="form-control" rows="5" placeholder="Write your notice here..." required></textarea>
          </div>

          <!-- Hidden inputs -->
          <input type="hidden" name="subject" id="noticeSubject">
          <input type="hidden" name="grade" id="noticeGrade">
          <input type="hidden" name="group" id="noticeGroup">
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Post Notice</button>
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
    var subjectName = button.data('subjectname');

    // Fill hidden inputs
    modal.find('#graid').val(grade);
    modal.find('#subid').val(subject);
    modal.find('#groupid').val(group);

     modal.find('#modalClassInfoRecord').text(`${subjectName} | ${grade} | Group: ${group}`);
});
</script>

<script>
$('#modal-addMeetingLink').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget); 
  var modal = $(this);

  // Get data attributes
  var classId = button.data('class');
  var grade = button.data('grade');
  var subjectId = button.data('subject');
  var groupName = button.data('group');
  var subjectName = button.data('subjectname');

  // Fill hidden inputs
  modal.find('#classid').val(classId);
  modal.find('#grade').val(grade);
  modal.find('#subjectid').val(subjectId);
  modal.find('#groupname').val(groupName);
  modal.find('#subjectname').val(subjectName);

  // Display class info line
  modal.find('#modalClassInfo').text(`${grade} | ${subjectName} | Group: ${groupName}`);
});
</script>

<script>
$('#modal-notifyClass').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget); // Button that triggered the modal
  var modal = $(this);

  var grade = button.data('grade');
  var subject = button.data('subject');
  var group = button.data('group');

  // Set hidden inputs
  modal.find('#noticeGrade').val(grade);
  modal.find('#noticeSubject').val(subject);
  modal.find('#noticeGroup').val(group);

  // Display class info at top of modal
  modal.find('#modalClassInfoNotice').text(`${subject} | ${grade} | Group: ${group}`);
});
</script>




</body>
</html>
