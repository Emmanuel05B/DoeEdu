<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../partials/connect.php");

// Get classId from URL
if (!isset($_GET['classId']) || intval($_GET['classId']) <= 0) {
    die("Invalid class selected.");
}
$classId = intval($_GET['classId']);

// Allowed note/document types
$noteTypes = ['PDF', 'document', 'image'];

// Build placeholders for IN clause
$placeholders = implode(',', array_fill(0, count($noteTypes), '?'));

// Query to fetch notes/documents for the class
$sql = "
    SELECT r.ResourceID, r.Title, r.FilePath, r.Description, r.ResourceType
    FROM resources r
    JOIN resourceassignments ra ON r.ResourceID = ra.ResourceID
    WHERE ra.ClassID = ? AND r.ResourceType IN ($placeholders)
    ORDER BY r.UploadedAt DESC
";

$stmt = $connect->prepare($sql);

// Bind parameters
$types = 'i' . str_repeat('s', count($noteTypes));
$params = array_merge([$classId], $noteTypes);
$refs = [];
foreach ($params as $key => $value) {
    $refs[$key] = &$params[$key];
}
call_user_func_array([$stmt, 'bind_param'], array_merge([$types], $refs));

$stmt->execute();
$result = $stmt->get_result();
$resources = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<?php include(__DIR__ . "/../../common/partials/head.php"); ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

<?php include(__DIR__ . "/../partials/header.php"); ?>
<?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Notes & Documents <small>for this class</small></h1>
    </section>

    <section class="content">
        <div class="box box-solid" style="border-top:3px solid #605ca8;">
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
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if ($resources): ?>
                            <?php foreach ($resources as $res): 
                                $fileUrl = '/DoeEdu/genesis/uploads/resources/' . urlencode($res['FilePath']);
                                $ext = strtolower(pathinfo($res['FilePath'], PATHINFO_EXTENSION));
                                // Choose icon based on file type
                                if ($ext === 'pdf') {
                                    $icon = '<i class="fa fa-file-pdf-o" style="font-size:24px; color:#d9534f;"></i>';
                                } elseif (in_array($ext, ['jpg','jpeg','png','gif','webp'])) {
                                    $icon = '<i class="fa fa-file-image-o" style="font-size:24px; color:#5bc0de;"></i>';
                                } elseif (in_array($ext, ['doc','docx'])) {
                                    $icon = '<i class="fa fa-file-word-o" style="font-size:24px; color:#337ab7;"></i>';
                                } else {
                                    $icon = '<i class="fa fa-file-o" style="font-size:24px; color:#777;"></i>';
                                }
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($res['Title']) ?></td>
                                    <td class="text-center">
                                        <a href="<?= $fileUrl ?>" target="_blank" title="Open Resource"><?= $icon ?></a>
                                    </td>
                                    <td><?= htmlspecialchars($res['ResourceType']) ?></td>
                                    <td><?= htmlspecialchars($res['Description'] ?: 'N/A') ?></td>
                                    <td>
                                        <a href="<?= $fileUrl ?>" class="btn btn-xs btn-success" download title="Download">
                                            <i class="fa fa-download"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-muted">No notes or documents assigned to this class.</td></tr>
                        <?php endif; ?>
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
<script>
  $(function () {
    $('#resourceTable').DataTable();
  });
</script>
</body>
</html>
