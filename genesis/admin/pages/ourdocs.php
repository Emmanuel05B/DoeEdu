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


$tutorId   = $_SESSION['user_id'];

$uploadedResources = [];

$stmt = $connect->prepare("
    SELECT d.Id, d.Title, d.FilePath, d.UploadedAt,u.Surname AS UploadedBySurname
    FROM documents d
    JOIN users u ON d.UploadedBy = u.Id
    WHERE d.IsDeleted = 0
    ORDER BY d.UploadedAt DESC
");
$stmt->execute();
$result = $stmt->get_result();

$uploadedResources = [];
while ($row = $result->fetch_assoc()) {
    $uploadedResources[] = $row;
}

$powerpoints = [];

foreach ($uploadedResources as $res) {
    $ext = strtolower(pathinfo($res['FilePath'], PATHINFO_EXTENSION));
    if (in_array($ext, ['ppt', 'pptx'])) {
        $powerpoints[] = $res;
    }
}


?>


<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

<?php include_once(ADMIN_PATH . "/../partials/header.php"); ?> 
<?php include_once(ADMIN_PATH . "/../partials/mainsidebar.php"); ?>

  
  <div class="content-wrapper">
    <section class="content-header">
       <h1>DoE Documents <small>Manage all uploaded documents</small></h1>
        <ol class="breadcrumb">
          <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">DoE Docs</li>
        </ol>
    </section>

    <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title" style="color:#3c8dbc;"><i class="fa fa-folder-open"></i>Uploaded Documents</h3>
          <div class="box-tools pull-right">
            <button 
              class="btn btn-info btn-sm" 
              data-toggle="modal" 
              data-target="#modal-uploadResource"
             >
              <i class="fa fa-plus"></i> Document
            </button>

            </div>
        </div>
        
        <div class="box-body table-responsive">
          <div class="table-responsive">
            <table class="table table-bordered table-hover" id="resourceTable">
              <thead style="background-color:#d9edf7; color:#333;">
                <tr>
                  <th>Title</th>
                  <th>Download</th>
                  <th>Uploader</th>
                  <th style="width:130px;">Delete</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($uploadedResources)): ?>
                <?php foreach ($uploadedResources as $res): ?>
                <?php
                    $ext = strtolower(pathinfo($res['FilePath'], PATHINFO_EXTENSION));
                    if (in_array($ext, ['ppt', 'pptx'])) {
                        continue; // ❌ skip PowerPoints here
                    }
                ?>

                  <tr>
                    <td><?= htmlspecialchars($res['Title']) ?></td>

                    <td>
                        <?php
                        
                        $fileName = $res['FilePath'] ?? '';
                        //$fileUrl  = DOCUMENTS_URL . '/' . urlencode($fileName);
                        $fileUrl = 'view_document.php?file=' . urlencode($fileName);
                        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));


                        if ($ext === 'pdf') {
                            echo '<a href="'.$fileUrl.'" target="_blank">
                                    <i class="fa fa-file-pdf-o text-danger fa-lg"></i>
                                    </a>';
                                    
                        } elseif (in_array($ext, ['doc','docx'])) {
                            echo '<a href="'.$fileUrl.'" target="_blank">
                                    <i class="fa fa-file-word-o text-primary fa-lg"></i>
                                  </a>';
                        
                        } elseif (in_array($ext, ['xls','xlsx','csv'])) {
                            echo '<a href="'.$fileUrl.'" target="_blank">
                                    <i class="fa fa-file-excel-o text-success fa-lg"></i>
                                  </a>';
                        
                        } elseif (in_array($ext, ['ppt','pptx'])) {
                            echo '<a href="'.$fileUrl.'" target="_blank">
                                    <i class="fa fa-file-powerpoint-o text-warning fa-lg"></i>
                                  </a>';
          
                        } else {
                            echo '<a href="'.$fileUrl.'" target="_blank">
                                    <i class="fa fa-file-o fa-lg"></i>
                                    </a>';
                        }
                        ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($res['UploadedBySurname']) ?>
                    </td>
                    <td>
                        <button 
                            class="btn btn-xs btn-danger"
                            onclick="confirmDelete(<?= $res['Id'] ?>)"
                        >
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                  </tr>

                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                <td colspan="4" class="text-center text-muted">
                    No documents uploaded yet.
                </td>
                </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div> 
        </div>
      </div>
      
      <div class="box box-danger">
          <div class="box-header with-border">
            <h3 class="box-title" style="color:#dd4b39;">
              <i class="fa fa-file-powerpoint-o"></i> PowerPoint Presentations
            </h3>
            <div class="box-tools pull-right">
            <button 
              class="btn btn-danger btn-sm" 
              data-toggle="modal" 
              data-target="#modal-uploadResource"
             >
              <i class="fa fa-plus"></i> Document
            </button>

            </div>
          </div>
    
          <div class="box-body table-responsive">
            <table class="table table-bordered table-hover" id="pptTable">
              <thead style="background-color:#f9d6d3;">
                <tr>
                  <th>Title</th>
                  <th>Download</th>
                  <th>Uploader</th>
                  <th>Delete</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($powerpoints)): ?>
                  <?php foreach ($powerpoints as $ppt): ?>
                    <?php
                      $fileUrl = 'view_document.php?file=' . urlencode($ppt['FilePath']);
                    ?>
                    <tr>
                      <td><?= htmlspecialchars($ppt['Title']) ?></td>
                      <td>
                        <a href="<?= $fileUrl ?>" target="_blank">
                          <i class="fa fa-file-powerpoint-o text-warning fa-lg"></i>
                        </a>
                      </td>
                      <td><?= htmlspecialchars($ppt['UploadedBySurname']) ?></td>
                      <td>
                            <button 
                                class="btn btn-xs btn-danger"
                                onclick="confirmDelete(<?= $res['Id'] ?>)"
                            >
                                <i class="fa fa-trash"></i>
                            </button>
                       </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="3" class="text-center text-muted">
                      No PowerPoint presentations uploaded.
                    </td>
                  </tr>
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

      <form action="upload_document.php" method="post" enctype="multipart/form-data">
        <div class="modal-body">
          
          <p id="modalClassInfoResource" style="margin-bottom:15px;"></p>

          <div class="row">
            <!-- Left Column: Title + File -->
            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" placeholder="E.g. Tutor Contract" required>
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
              title: 'Document uploaded!',
          }).then(() => {
              window.location = '#';
          });
      </script>"; 
    ?>
  <?php endif; ?>

  

    <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Document deleted',
    });
    </script>
    <?php endif; ?>

    <script>
    function confirmDelete(docId) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This document will be deleted.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'delete_document.php?id=' + docId;
            }
        });
    }
    </script>


    <?php if (isset($_GET['upload_failed'])): ?>
    <script>
    <?php
        $type = $_GET['type'] ?? 'general';

        switch ($type) {
            case 'invalid':
                $title = 'Unsupported file type';
                $text  = 'Please upload a valid document (PDF, Word, Excel, ZIP, etc).';
                break;

            case 'size':
                $title = 'File too large';
                $text  = 'The selected file exceeds the allowed size limit.';
                break;

            default:
                $title = 'Upload failed';
                $text  = 'Something went wrong while uploading the document.';
                break;
        }
    ?>
    Swal.fire({
        icon: 'error',
        title: '<?= $title ?>',
        text: '<?= $text ?>'
    });
    </script>
    <?php endif; ?>




    <!-- warning button and table stuff -->
    <script>
    $(function () {
        $('#resourceTable').DataTable({
            responsive: true,
            autoWidth: false,
            order: [[0, "asc"]] // Sort by due date ascending
        });

    });
   

    </script>
    <script>
    
    $(function () {
    $('#pptTable').DataTable({
        responsive: true,
        autoWidth: false,
        order: [[0, 'asc']]
    });
    
    });

    </script>

    <!-- upload modal js-->
    <script>
    $('#modal-uploadResource').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var modal = $(this);
    });
    </script>





</body>
</html>
