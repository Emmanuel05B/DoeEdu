<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../../common/pages/login.php");
  exit();
}

include(__DIR__ . "/../../partials/connect.php");

if (!isset($_GET['classId']) || intval($_GET['classId']) <= 0) {
  die("Invalid class selected.");
}
$classId = intval($_GET['classId']);
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
      <h1>Slide Resources <small>for this class</small></h1>
      <ol class="breadcrumb">
        <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Slides</li>
      </ol>
    </section>

    <section class="content">
      <div class="box box-solid" style="border-top:3px solid #605ca8;">
        <div class="box-header with-border" style="background-color:#f3edff;">
          <h3 class="box-title" style="color:#605ca8;">
            <i class="fa fa-file-powerpoint-o"></i> Available Slides
          </h3>
          <div class="box-tools pull-right">
            <button id="toggleViewBtn" class="btn btn-default btn-sm">
              <i class="fa fa-th-large"></i> Grid View
            </button>
          </div>
        </div>

        <div class="box-body" style="background-color:#ffffff;">
          <!-- List View -->
          <div id="listView">
            <div class="table-responsive">
              <table class="table table-bordered table-hover" id="resourceTable">
                <thead style="background-color:#e6e0fa; color:#333;">
                  <tr>
                    <th>Title</th>
                    <th>Preview</th>
                    <th>Description</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $sql = "
                    SELECT r.Title, r.FilePath, r.Description
                    FROM resources r
                    JOIN resourceassignments ra ON r.ResourceID = ra.ResourceID
                    WHERE r.ResourceType = 'Slides'
                      AND ra.ClassID = ?
                    ORDER BY r.UploadedAt DESC
                  ";
                  $stmt = $connect->prepare($sql);
                  $stmt->bind_param("i", $classId);
                  $stmt->execute();
                  $result = $stmt->get_result();

                  if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                      $title = htmlspecialchars($row['Title']);
                      $description = htmlspecialchars($row['Description'] ?: 'N/A');
                      $filePath = htmlspecialchars($row['FilePath']);
                      $fileUrl = "/DoE_Genesis/DoeEdu/genesis/uploads/resources/" . $filePath;
                      ?>
                      <tr>
                        <td><?= $title ?></td>
                        <td class="text-center" style="font-size:40px; color:#d9534f;">
                          <i class="fa fa-file-powerpoint-o"></i>
                        </td>
                        <td><?= $description ?></td>
                        <td>
                          <a href="<?= $fileUrl ?>" target="_blank" class="btn btn-xs btn-primary">
                            <i class="fa fa-eye"></i> View
                          </a>
                          <a href="<?= $fileUrl ?>" class="btn btn-xs btn-success" download>
                            <i class="fa fa-download"></i> Download
                          </a>
                        </td>
                      </tr>
                    <?php
                    }
                  } else {
                    echo '<tr><td colspan="4" class="text-muted text-center">No slides assigned to this class.</td></tr>';
                  }
                  $stmt->close();
                  ?>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Grid View -->
          <div id="gridView" class="row" style="display:none;">
            <?php
            $stmt = $connect->prepare($sql);
            $stmt->bind_param("i", $classId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                $title = htmlspecialchars($row['Title']);
                $description = htmlspecialchars($row['Description'] ?: 'No description');
                $filePath = htmlspecialchars($row['FilePath']);
                $fileUrl = "/DoE_Genesis/DoeEdu/genesis/uploads/resources/" . $filePath;
                ?>
                <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="box box-widget" style="border:1px solid #ddd; border-radius:10px; padding:15px; text-align:center;">
                    <div class="box-body">
                      <i class="fa fa-file-powerpoint-o" style="font-size:40px; color:#d9534f;"></i>
                      <h4 style="margin-top:10px; color:#333;"><?= $title ?></h4>
                      <p style="color:#777; min-height:40px;"><?= $description ?></p>
                      <div>
                        <a href="<?= $fileUrl ?>" target="_blank" class="btn btn-xs btn-primary">
                          <i class="fa fa-eye"></i> View
                        </a>
                        <a href="<?= $fileUrl ?>" download class="btn btn-xs btn-success">
                          <i class="fa fa-download"></i> Download
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              <?php
              }
            } else {
              echo '<div class="col-md-12 text-center text-muted">No slides assigned to this class.</div>';
            }
            $stmt->close();
            ?>
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

    $('#toggleViewBtn').on('click', function() {
      $('#listView').toggle();
      $('#gridView').toggle();

      if ($('#gridView').is(':visible')) {
        $(this).html('<i class="fa fa-list"></i> List View');
      } else {
        $(this).html('<i class="fa fa-th-large"></i> Grid View');
      }
    });
  });
</script>
</body>
</html>
