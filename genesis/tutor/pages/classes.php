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
                style="width: 100px;" 
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
                style="width: 100px;">
                + Quizzes
              </a>

              <a href="assignedresources.php?sub=<?php echo $row['SubjectID'] ?>&gra=<?php echo $grade ?>&group=<?php echo $group ?>" 
                class="<?php echo $btnClass; ?> <?php echo $disabled; ?>" 
                style="width: 100px;">
                + Reso
              </a>
                          
              <button 
                class="btn btn-info btn-sm <?php echo $disabled; ?>" 
                style="width: 100px;"
                data-toggle="modal" 
                data-target="#modal-uploadResource"
                data-class="<?php echo $classId; ?>"
                data-grade="<?php echo $grade; ?>"
                data-subjectid="<?php echo $row['SubjectID']; ?>"
                data-subjectname="<?php echo $subjectName; ?>"
                data-group="<?php echo $group; ?>">
                + Resources
              </button>

              <a href="alllearner.php?subject=<?php echo $row['SubjectID'] ?>&grade=<?php echo $grade ?>&group=<?php echo $group ?>"
                class="btn btn-info btn-sm" 
                style="width: 100px;">
                Open Class
              </a>
              
              <button 
                class="btn btn-info btn-sm" 
                style="width: 100px;" 
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
                class="btn btn-info btn-sm" 
                style="width: 100px;"
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

<!-- Upload Resource Modal -->
<div class="modal fade" id="modal-uploadResource" tabindex="-1" role="dialog" aria-labelledby="uploadResourceLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      
      <div class="modal-header bg-info">
        <h4 class="modal-title" id="uploadResourceLabel">Upload Resource</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="upload_resource.php" method="post" enctype="multipart/form-data">
        <div class="modal-body">
          
          <p id="modalClassInfoResource" style="margin-bottom:15px;"></p>

          <div class="row">
            <!-- Left Column: Inputs -->
            <div class="col-md-6">
              <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" placeholder="E.g. Newton’s Laws Summary" required>
              </div>

              <div class="form-group">
                <label>Choose File</label>
                <input type="file" name="resource_file" class="form-control" required>
              </div>

              <div class="form-group">
                <label>Description / Notes (Optional)</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Brief info about the resource"></textarea>
              </div>
            </div>

            <!-- Right Column: File Type Info -->
            <div class="col-md-6">
              <div class="form-group">
                <label>Supported File Types</label>
                <div class="alert alert-info" style="margin-bottom:0;">
                  <strong>Allowed formats:</strong>
                  <ul style="margin: 0; padding-left: 18px;">
                    <li>PDF (.pdf)</li>
                    <li>Images (.jpg, .jpeg, .png, .gif, .webp)</li>
                    <li>Documents (.doc, .docx, .xls, .xlsx, .ppt, .pptx)</li>
                    <li>Videos (.mp4, .avi, .mov, .mkv, .webm)</li>
                    <li>Audio (.mp3, .wav, .m4a, .ogg)</li>
                    <li>Compressed (.zip, .rar, .7z)</li>
                    <li>Text files (.txt, .csv)</li>
                  </ul>
                  <p style="margin-top:5px;"><strong>Maximum size:</strong> 50 MB</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Hidden inputs -->
          <input type="hidden" id="resourceClassId" name="classId">
          <input type="hidden" id="resourceGrade" name="grade">
          <input type="hidden" id="resourceSubjectId" name="subjectid">
          <input type="hidden" id="resourceGroup" name="group">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-cloud-upload"></i> Upload Resource</button>
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

<script>
$('#modal-uploadResource').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var modal = $(this);

    var classId = button.data('class');
    var grade = button.data('grade');
    var subjectId = button.data('subjectid');
    var group = button.data('group');
    var subjectName = button.data('subjectname');

    modal.find('#resourceClassId').val(classId);
    modal.find('#resourceGrade').val(grade);
    modal.find('#resourceSubjectId').val(subjectId);
    modal.find('#resourceGroup').val(group);

    modal.find('#modalClassInfoResource').text(`${subjectName} | ${grade} | Group: ${group}`);
});
</script>




</body>
</html>
