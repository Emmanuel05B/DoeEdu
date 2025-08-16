

<?php
    session_start();
    if (!isset($_SESSION['email'])) {
      header("Location: ../../common/pages/login.php");
      exit();
    }
    include(__DIR__ . "/../../common/partials/head.php");
    include(__DIR__ . "/../../partials/connect.php");
    

    // Assume tutor id for demo, replace with actual logged-in tutor ID in real
    $tutorId = 2;

    // Fetch tutor's assigned classes info for dropdowns
    $subjectGradeOptions = [];
    $stmt = $connect->prepare("
      SELECT c.ClassID, s.SubjectName, c.Grade, c.GroupName
      FROM classes c
      JOIN subjects s ON c.SubjectID = s.SubjectId
      WHERE c.TutorID = ?
      ORDER BY c.Grade, s.SubjectName, c.GroupName
    ");
    $stmt->bind_param("i", $tutorId);
    $stmt->execute();
    $subjectGradeOptions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
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

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  

  <div class="content-wrapper">

    <section class="content-header">
      <h1>Study Resources <small>Upload and manage learning materials.</small></h1>
      <ol class="breadcrumb">
        <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Study Resources</li>
      </ol>
    </section>

    <section class="content">

      <div class="row">
        <!-- Upload Resource - Left Side -->
        <div class="col-md-6">
          <div class="box box-primary" style="border-top: 3px solid #3c8dbc;">
            <div class="box-header with-border" style="background-color:#f0f8ff;">
              <h3 class="box-title" style="color:#3c8dbc;"><i class="fa fa-upload"></i> Upload New Resource</h3>
            </div>
            <div class="box-body" style="background-color:#ffffff;">
              <form action="upload_resource.php" method="POST" enctype="multipart/form-data">
                <div class="row">

                  <!-- Title -->
                  <div class="col-md-6 form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" class="form-control" placeholder="E.g. Newton’s Laws Summary" required>
                  </div>

                  <!-- Subject & Grade -->
                  <div class="col-md-6 form-group">
                    <label for="subject_grade">Subject & Grade</label>
                    <select name="classId" class="form-control" required>
                      <option value="">Select Subject & Grade</option>
                      <?php foreach ($subjectGradeOptions as $option): ?>
                        <option value="<?= htmlspecialchars($option['ClassID']) ?>">
                          Grade <?= htmlspecialchars($option['Grade']) ?> - Group <?= htmlspecialchars($option['GroupName']) ?> (<?= htmlspecialchars($option['SubjectName']) ?>)
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <!-- File Upload -->
                  <div class="col-md-6 form-group">
                    <label for="resource_file">Choose File</label>
                    <input type="file" name="resource_file" class="form-control" required>
                  </div>

                  <!-- Visibility -->
                  <div class="col-md-6 form-group">
                    <label for="visibility">Visibility</label>
                    <select name="visibility" class="form-control" required>
                      <option value="private">Private (Only assigned classes)</option>
                      <option value="public">Public (All learners can access)</option>
                    </select>
                  </div>

                  <!-- Description -->
                  <div class="col-md-6 form-group">
                    <label for="description">Description / Notes (Optional)</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Brief info about the resource"></textarea>
                  </div>

                </div>

                <div class="col-md-12 text-right" style="margin-top: 10px;">
                  <button type="submit" class="btn btn-primary"><i class="fa fa-cloud-upload"></i> Upload Resource</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Bulk Assign Resources to Class - Right Side -->
        <div class="col-md-6">
          <div class="box box-info" style="border-top: 3px solid #00c0ef;">
            <div class="box-header with-border" style="background-color:#d9f0fb;">
              <h3 class="box-title" style="color:#0073b7;">
                <i class="fa fa-tasks"></i> Bulk Assign Resources to Class/Group
              </h3>
            </div>
            <div class="box-body" style="background-color:#ffffff;">
              <form action="assign_resource.php" method="POST">
                <div class="form-group">
                  <label>Select Resources</label>
                  <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ccc; border-radius: 5px; background-color: #f9f9f9;">
                    <table class="table table-striped" style="margin-bottom: 0;">
                      <thead>
                        <tr>
                          <th style="width: 40px;"></th> <!-- checkbox column -->
                          <th>Title</th>
                          <th>Grade</th>
                          <th>Subject</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($uploadedResources as $res): ?>
                          <tr>
                            <td><input type="checkbox" name="resourceIds[]" value="<?= htmlspecialchars($res['ResourceID']) ?>"></td>
                            <td><?= htmlspecialchars($res['Title']) ?></td>
                            <td>Grade <?= htmlspecialchars($res['Grade']) ?></td>
                            <td><?= htmlspecialchars($res['SubjectName']) ?></td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>

                <div class="form-group row" style="margin-top: 15px;">
                  <div class="col-xs-8">
                    <select name="classId" id="classId" class="form-control" required>
                      <option value="">-- Select a Class/Group --</option>
                      <?php foreach ($assignedClasses as $class): ?>
                        <option value="<?= htmlspecialchars($class['ClassID']) ?>">
                          <?= htmlspecialchars($class['label']) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-xs-4">
                    <button type="submit" class="btn btn-info btn-block" style="margin-top: 0;">
                      <i class="fa fa-check"></i> Assign Selected
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Uploaded Resources Table -->
      <div class="box box-solid" style="border-top: 3px solid #605ca8;">
        <div class="box-header with-border" style="background-color:#f3edff;">
          <h3 class="box-title" style="color:#605ca8;"><i class="fa fa-folder-open"></i> Uploaded Resources</h3>
        </div>
        <div class="box-body" style="background-color:#ffffff;">
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
                    <td>Grade <?= htmlspecialchars($res['Grade']) ?></td>
                    <td>
                      <a href="<?= $fileUrl ?>" class="btn btn-xs btn-primary" title="Download" download>
                        <i class="fa fa-download"></i>
                      </a>
                      <button class="btn btn-xs btn-danger delete-resource-btn" data-id="<?= htmlspecialchars($res['ResourceID']) ?>" title="Delete">
                        <i class="fa fa-trash"></i>
                      </button>
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

<!-- Scripts -->
<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

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

<script>
  $(function () {
    $('#resourceTable').DataTable();
  });
</script>
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
      // Remove query param to avoid repeat alerts if page is refreshed
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
<script>
  $(function () {
    $('#resourceTable').DataTable();

    $('.delete-resource-btn').on('click', function () {
      const resourceId = $(this).data('id');
      const row = $(this).closest('tr');

      Swal.fire({
        title: 'Are you sure?',
        text: "This will permanently delete this resource.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: 'delete_resource.php',
            method: 'POST',
            data: { id: resourceId },
            success: function (response) {
              if (response.trim() === 'deleted') {
                row.fadeOut(300, function () {
                  $(this).remove();
                });
                Swal.fire('Deleted!', 'The resource has been deleted.', 'success');
              } else {
                Swal.fire('Error', 'Failed to delete the resource.', 'error');
              }
            },
            error: function () {
              Swal.fire('Error', 'An unexpected error occurred.', 'error');
            }
          });
        }
      });
    });
  });
</script>


</body>
</html>

<?php if (isset($_SESSION['bulk_assign_status'])): ?>
<script>
  Swal.fire({
    icon: '<?= $_SESSION['bulk_assign_status'] ?>',
    title: '<?= $_SESSION['bulk_assign_status'] === 'success' ? "Done!" : "Note" ?>',
    text: '<?= $_SESSION['bulk_assign_message'] ?>',
    confirmButtonColor: '#3085d6'
  });
</script>
<?php
unset($_SESSION['bulk_assign_status']);
unset($_SESSION['bulk_assign_message']);
endif;
?>

