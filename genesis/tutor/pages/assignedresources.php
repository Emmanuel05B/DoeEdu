<!DOCTYPE html>
<html>
<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../../common/pages/login.php");
  exit();
}

include(__DIR__ . "/../../partials/connect.php");

$tutorId = $_SESSION['user_id']; // Logged-in tutor id 

$grade     = $_GET['gra'];
$SubjectId = intval($_GET['sub']);
$group     = $_GET['group'];

// Fetch subject name
$stmt = $connect->prepare("SELECT SubjectName FROM subjects WHERE SubjectId = ?");
$stmt->bind_param("i", $SubjectId);
$stmt->execute();
$result = $stmt->get_result();
$SubjectName = "";
if ($row = $result->fetch_assoc()) {
    $SubjectName = $row['SubjectName'];
}
$stmt->close();


    // Fetch all resources matching tutor's assigned subjects & grades
    $uploadedResources = [];
    $stmt = $connect->prepare("
      SELECT r.ResourceID, r.Title, r.FilePath, r.ResourceType, r.Grade, s.SubjectName, r.UploadedAt
      FROM resources r
      JOIN subjects s ON r.SubjectID = s.SubjectID
      WHERE EXISTS (
        SELECT 1 FROM classes c
        WHERE c.TutorID = ? AND c.Grade = r.Grade AND c.SubjectID = r.SubjectID
      )
      ORDER BY r.UploadedAt DESC
    ");
    $stmt->bind_param("i", $tutorId);
    $stmt->execute();
    $uploadedResources = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Fetch tutor's assigned classes again for assigning resources dropdown (same as above, but separate variable)
    $assignedClasses = [];
    $stmt = $connect->prepare("
      SELECT c.ClassID, CONCAT('Grade ', c.Grade, ' - ', c.GroupName, ' (', s.SubjectName, ')') AS label
      FROM classes c
      JOIN subjects s ON c.SubjectID = s.SubjectId
      WHERE c.TutorID = ?
      ORDER BY c.Grade, c.GroupName
    ");
    $stmt->bind_param("i", $tutorId);
    $stmt->execute();
    $assignedClasses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
?>

<?php include(__DIR__ . "/../../common/partials/head.php"); ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  <?php if (isset($_GET['updated']) && $_GET['updated'] == 1): ?>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
  Swal.fire({
      icon: 'success',
      title: 'Updated!',
      text: 'The due date has been successfully updated.',
      confirmButtonText: 'OK'
  });
  </script>
  <?php endif; ?>

  <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
  Swal.fire({
      icon: 'success',
      title: 'Deleted!',
      text: 'The activity has been successfully deleted.',
      confirmButtonText: 'OK'
  });
  </script>
  <?php endif; ?>
  
  <div class="content-wrapper">
    <section class="content-header">
       <h1>Current Resources <small>Assigned to <?php echo htmlspecialchars($grade . " - " . $SubjectName . " - " . $group); ?></small></h1>
        <ol class="breadcrumb">
          <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Resources</li>
        </ol>
    </section>

    <section class="content">
      <div class="box box-default">
        <div class="box-body table-responsive">

            <div class="box-header with-border">
            <h3 class="box-title">
               Uploaded Resources
            </h3>
            <div class="box-tools pull-right">
                <!-- Assign Activity Button -->
                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-assignActivity">
                <i class="fa fa-minus"></i> Unassign Resources
                </button>

                <!-- Create Quiz Button -->
                
                <button 
                class="btn btn-info btn-sm <?php echo $disabled; ?>" 
                data-toggle="modal" 
                data-target="#modal-uploadResource"
                data-class="<?php echo $classId; ?>"
                data-grade="<?php echo $grade; ?>"
                data-subjectid="<?php echo $SubjectId; ?>"
                data-subjectname="<?php echo $subjectName; ?>"
                data-group="<?php echo $group; ?>">
                <i class="fa fa-plus"></i> Upload Resources
              </button>

            </div>
            </div>

          <div class="table-responsive">
            <table class="table table-bordered table-hover" id="resourceTable">
              <thead style="background-color:#e6e0fa; color:#333;">
                <tr>
                  <th>Title</th>
                  <th>Preview</th>
                  <th>Type</th>
                  <th>Subject</th>
                  <th>Grade</th>
                  <th style="width:130px;">Actions</th>
                  <th>Uploaded At</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($uploadedResources as $res): ?>
                  <tr>
                    <td><?= htmlspecialchars($res['Title']) ?></td>
                    <td>
                      <?php
                      // Base URL path to uploads folder (adjust if your project URL changes)
                      $baseUploadsUrl = '/DoeEdu/genesis/uploads/resources/';
                      // Inside your foreach loop for each resource -->

                      $fileName = $res['FilePath'] ?? '';  // filename stored in DB
                      $fileUrl = $baseUploadsUrl . urlencode($fileName); // Proper URL to file

                      $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                      if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                          echo '<a href="' . $fileUrl . '" target="_blank" title="View Image">';
                          echo '<img src="' . $fileUrl . '" style="max-width:80px; max-height:60px; border-radius:4px;" alt="Preview">';
                          echo '</a>';
                      } elseif ($ext === 'pdf') {
                          echo '<a href="' . $fileUrl . '" target="_blank" title="View PDF">';
                          echo '<i class="fa fa-file-pdf-o" style="font-size:24px; color:#d9534f;"></i></a>';
                      } elseif (in_array($ext, ['mp4', 'webm', 'ogg'])) {
                          echo '<a href="' . $fileUrl . '" target="_blank" title="View Video">';
                          echo '<i class="fa fa-file-video-o" style="font-size:24px; color:#5bc0de;"></i></a>';
                      } elseif (in_array($ext, ['mp3', 'wav', 'm4a'])) {
                          echo '<a href="' . $fileUrl . '" target="_blank" title="Listen Audio">';
                          echo '<i class="fa fa-file-audio-o" style="font-size:24px; color:#f0ad4e;"></i></a>';
                      } else {
                          echo '<a href="' . $fileUrl . '" target="_blank" title="Download File">';
                          echo '<i class="fa fa-file-o" style="font-size:24px; color:#777;"></i></a>';
                      }
                      ?>

                    </td>
                    <td><?= htmlspecialchars($res['ResourceType']) ?></td>
                    <td><?= htmlspecialchars($res['SubjectName']) ?></td>
                    <td><?= htmlspecialchars($res['Grade']) ?></td>
                    <td>
                      <a href="<?= $fileUrl ?>" class="btn btn-xs btn-primary" title="Download" download>
                        <i class="fa fa-download"></i>
                      </a>

                      <div class="btn-group">
                        <button type="button" class="btn btn-xs btn-info dropdown-toggle" data-toggle="dropdown" title="Assign Resource">
                          <i class="fa fa-link"></i> Assign <span class="caret"></span>
                        </button>
                        
                        <ul class="dropdown-menu">
                          <?php foreach ($assignedClasses as $class): ?>
                            <li><a href="assign_resource_single.php?resourceId=<?= htmlspecialchars($res['ResourceID']) ?>&classId=<?= htmlspecialchars($class['ClassID']) ?>">
                              <?= htmlspecialchars($class['label']) ?>
                            </a></li>
                          <?php endforeach; ?>
                        </ul>
                      </div>
                    </td>
                    <td><?= htmlspecialchars($res['UploadedAt'] ?? 'Unknown') ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div> 
        </div>
      </div>
    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>



<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

<!-- UnAssign Activity Modal -->
<div class="modal fade" id="modal-assignActivity" tabindex="-1" role="dialog" aria-labelledby="assignActivityLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header bg-warning">
        <h4 class="modal-title" id="assignActivityLabel">Assign Activity</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <script>
      <?php if (isset($_GET['assigned']) && $_GET['assigned'] == 1): ?>
          // Open the Assign Activity modal
          $('#modal-assignActivity').modal('show');

          // Show SweetAlert on top of modal
          Swal.fire({
              icon: 'success',
              title: 'Activity assigned successfully!',
              text: 'The selected activity has been assigned to this class/group.',
              backdrop: true,
              confirmButtonText: 'OK'
          });
      <?php endif; ?>
      </script>

      <div class="modal-body">
        <div class="table-responsive">
        <table id="assignActivityTable" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Title</th>
              <th>Chapter / Topic</th>
              <th>Orig. Class</th>
              <th>Status</th>
              <th>UnAssign</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (isset($grade) && isset($SubjectId) && isset($group)) {
                $stmt = $connect->prepare("
                  SELECT a.Id, a.Title, a.Topic, a.GroupName,
                        IF(b.OnlineActivityId IS NULL, 0, 1) AS assigned
                  FROM onlineactivities a
                  LEFT JOIN onlineactivitiesassignments b 
                    ON a.Id = b.OnlineActivityId 
                    AND b.ClassID = (SELECT ClassID FROM classes WHERE Grade = ? AND SubjectId = ? AND GroupName = ? LIMIT 1)
                  WHERE a.Grade = ? AND a.SubjectId = ?
                  ORDER BY a.CreatedAt DESC
                ");
                $stmt->bind_param("iisii", $grade, $SubjectId, $group, $grade, $SubjectId);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['Title']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['Topic']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['GroupName']) . '</td>';
                    echo '<td>' . ($assigned ? '<span class="text-success">Assigned</span>' : '<span class="text-warning">Not Assigned</span>') . '</td>';
                    echo '<td>';

                       // Form for Unassign button
    echo '<form action="unassign_resource.php" method="POST" style="display:inline;">';
    echo '<input type="hidden" name="resourceId" value="' . htmlspecialchars($row['ResourceId']) . '">';
    echo '<input type="hidden" name="classId" value="' . htmlspecialchars($row['ClassID']) . '">';
    echo '<button type="submit" class="btn btn-warning btn-sm">Unassign</button>';
    echo '</form>';
    
                    '</td>';
                    echo '</tr>';
                }

                $stmt->close();
            }
            ?>
          </tbody>
        </table>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>

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


<?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Deleted!',
    text: 'The activity has been successfully unassigned.',
    confirmButtonText: 'OK'
});
</script>
<?php endif; ?>


<script>
$(function () {
    $('#resourceTable').DataTable({
        responsive: true,
        autoWidth: false,
        order: [[3, "asc"]] // Sort by due date ascending
    });

    // Unassign Button 
    $(document).on('click', '.unassign-activity-btn', function(e) {
        e.preventDefault();
        var activityId = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "This will unassign the activity from this class/group.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f39c12',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, unassign it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'unassignactivity.php?activityId=' + activityId +
                                       '&gra=<?php echo urlencode($grade); ?>' +
                                       '&sub=<?php echo urlencode($SubjectId); ?>' +
                                       '&group=<?php echo urlencode($group); ?>';
            }
        });
    });

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
