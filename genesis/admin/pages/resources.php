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


$userId   = $_SESSION['user_id'];;
$grade     = $_GET['gra'] ?? '';
$SubjectId = intval($_GET['sub'] ?? 0);
$group     = $_GET['group'] ?? '';

if (!$grade || !$SubjectId || !$group) {
    die("Missing parameters.");
}

$SubjectName = "";
$stmt = $connect->prepare("SELECT SubjectName FROM subjects WHERE SubjectId = ?");
$stmt->bind_param("i", $SubjectId);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $SubjectName = $row['SubjectName'];
}
$stmt->close();


$currentClassId = null;
$stmt = $connect->prepare("
    SELECT ClassID 
    FROM classes 
    WHERE SubjectID = ? AND Grade = ? AND GroupName = ?
    LIMIT 1
");
$stmt->bind_param("iss", $SubjectId, $grade, $group);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $currentClassId = $row['ClassID'];
}
$stmt->close();


// FETCH ASSIGNED RESOURCES for this class)
$assignedResources = [];
$stmt = $connect->prepare("
    SELECT 
        ra.AssignmentID,
        r.Title AS ResourceTitle,
        s.SubjectName,
        c.Grade,
        c.GroupName,
        ra.AssignedAt,
        ra.ClassID,
        r.FilePath,
        r.ResourceID
    FROM resourceassignments ra
    JOIN resources r ON ra.ResourceID = r.ResourceID
    JOIN classes c ON ra.ClassID = c.ClassID
    JOIN subjects s ON c.SubjectID = s.SubjectID
    WHERE c.SubjectID = ? 
      AND c.Grade = ? 
      AND c.GroupName = ?
    ORDER BY ra.AssignedAt DESC
");
$stmt->bind_param("iss", $SubjectId, $grade, $group);
$stmt->execute();
$assignedResources = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>


<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  
<?php include_once(ADMIN_PATH . "/../partials/header.php"); ?>
<?php include_once(ADMIN_PATH . "/../partials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
       <h1>Current Resources <small>Assigned to <?php echo htmlspecialchars($grade . " - " . $SubjectName . " - " . $group); ?></small></h1>
        <ol class="breadcrumb">
          <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Resources</li>
        </ol>
        </section>

    <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title" style="color:#605ca8;"><i class="fa fa-unlink"></i> Assigned Resources</h3>
        </div>
        <div class="box-body table-responsive">
          <table class="table table-bordered table-hover" id="assignedResourcesTable">
            <thead style="background-color:#d9edf7; color:#333;"  >
              <tr>
                <th>Title</th>
                <th>Preview</th>
                <th>Class</th>
                <th>Assigned At</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($assignedResources)): ?>
                <tr>
                  <td colspan="5" class="text-center text-muted">No resources have been assigned yet.</td>
                </tr>
              <?php else: ?>
                <?php foreach ($assignedResources as $row): ?>
                  <tr>
                    <td><?= htmlspecialchars($row['ResourceTitle']) ?></td>
                    <td>
                      <?php
                      // Base URL path to uploads folder (adjust if your project URL changes)
                      //$baseUploadsUrl2 = '/DoE_Genesis/DoeEdu/genesis/uploads/resources/';
                      $baseUploadsUrl2 = RESOURCES_URL . '/';
                      // Inside your foreach loop for each resource -->

                      $fileName = $row['FilePath'] ?? '';  // filename stored in DB
                      $fileUrl2 = $baseUploadsUrl2 . urlencode($fileName); // Proper URL to file

                      $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                      if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                          echo '<a href="' . $fileUrl2 . '" target="_blank" title="View Image">';
                          echo '<img src="' . $fileUrl2 . '" style="max-width:30px; max-height:20px;" alt="Preview">';
                          echo '</a>';
                      } elseif ($ext === 'pdf') {
                          echo '<a href="' . $fileUrl2 . '" target="_blank" title="View PDF">';
                          echo '<i class="fa fa-file-pdf-o" style="font-size:24px; color:#d9534f;"></i></a>';
                      } elseif (in_array($ext, ['mp4', 'webm', 'ogg'])) {
                          echo '<a href="' . $fileUrl2 . '" target="_blank" title="View Video">';
                          echo '<i class="fa fa-file-video-o" style="font-size:24px; color:#5bc0de;"></i></a>';
                      } elseif (in_array($ext, ['mp3', 'wav', 'm4a'])) {
                          echo '<a href="' . $fileUrl2 . '" target="_blank" title="Listen Audio">';
                          echo '<i class="fa fa-file-audio-o" style="font-size:24px; color:#f0ad4e;"></i></a>';
                      } else {
                          echo '<a href="' . $fileUrl2 . '" target="_blank" title="Download File">';
                          echo '<i class="fa fa-file-o" style="font-size:24px; color:#777;"></i></a>';
                      }

                      
                      ?>

                    </td>
                    <td><?= htmlspecialchars($row['SubjectName'] . ' - ' . $row['Grade'] . ' - ' . $row['GroupName']) ?></td>
                    <td><?= htmlspecialchars($row['AssignedAt']) ?></td>
                    <td>
                      
                      <button 
                        class="btn btn-warning btn-xs unassign-resource-btn" 
                        data-resource-id="<?= htmlspecialchars($row['ResourceID']) ?>" 
                        data-class-id="<?= htmlspecialchars($row['ClassID']) ?>"
                      >
                        <i class="fa fa-unlink"></i> Unassign
                      </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>



<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>


<!-- Upload Resource Modal -->
<div class="modal fade" id="modal-uploadResource" tabindex="-1" role="dialog" aria-labelledby="uploadResourceLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" style="max-width: 600px;">
    <div class="modal-content">
      
      <div class="modal-header bg-info">
        <h4 class="modal-title" id="uploadResourceLabel">Upload Resource</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="upload_resource.php" method="post" enctype="multipart/form-data">
        <div class="modal-body">
          
          <p id="modalClassInfoResource" style="margin-bottom:15px;"></p>

          <div class="row">
            <!-- Left Column: Title + File -->
            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" placeholder="E.g. Newton’s Laws Summary" required>
              </div>

              <div class="form-group">
                <label>Choose File</label>
                <input type="file" name="resource_file" class="form-control" required>
              </div>
            </div>

            <!-- Right Column: Description -->
            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                <label>Description / Notes (Optional)</label>
                <textarea name="description" class="form-control" rows="5" placeholder="Brief info about the resource"></textarea>
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
          <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-cloud-upload"></i> Upload</button>
        </div>
      </form>
    </div>
  </div>
</div>



<!-- Upload successul -->
  <?php if (isset($_GET['uploaded']) && $_GET['uploaded'] == 1): ?>
    <?php  
      echo "<script>
          Swal.fire({
              icon: 'success',
              title: 'Resource uploaded!',
          }).then(() => {
              window.location = '#';
          });
      </script>"; 
    ?>
  <?php endif; ?>

  <!-- Unasign successul    works  -->
<?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Unassigned!',
    text: 'The resource has been successfully unassigned.',
    confirmButtonText: 'OK'
});
</script>
<?php endif; ?>


<!-- Assign successul    works  -->
<script>
  $(document).ready(function() {
  const urlParams = new URLSearchParams(window.location.search);
  const message = urlParams.get('message');

  if (message === 'assigned_success') {
    Swal.fire({
      icon: 'success',
      title: 'Resource Assigned',
      text: 'The resource has been successfully assigned to the selected class.',
    }).then(() => {
      history.replaceState(null, '', window.location.pathname);
    });
  } else if (message === 'already_assigned') {
    Swal.fire({
      icon: 'info',
      title: 'Already Assigned',
      text: 'This resource is already assigned to the selected class.',
    }).then(() => {
      history.replaceState(null, '', window.location.pathname);
    });
  }
});
</script>



<!-- warning button and table stuff -->
<script>
$(function () {
    $('#resourceTable').DataTable({
        responsive: true,
        autoWidth: false,
        order: [[3, "asc"]] // Sort by due date ascending
    });

    $('#assignedResourcesTable').DataTable({
        responsive: true,
        autoWidth: false,
        order: [[3, "asc"]] // Sort by due date ascending
    });

  
    // Unassign Button 
    $(document).on('click', '.unassign-resource-btn', function(e) {
        e.preventDefault();
        var resourceId = $(this).data('resource-id');
        var classId = $(this).data('class-id');

        Swal.fire({
            title: 'Are you sure?',
            text: "This will unassign the resource from this class.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f39c12',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, unassign it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'unassign_resource.php?resourceId=' + resourceId +
                                      '&classId=' + classId +
                                      '&gra=<?= urlencode($grade) ?>' +
                                      '&sub=<?= urlencode($SubjectId) ?>' +
                                      '&group=<?= urlencode($group) ?>';
            }
        });
    });


});

</script>

<!-- upload modal js-->
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
