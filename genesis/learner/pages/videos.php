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

<?php include(__DIR__ . "/../../common/partials/head.php"); ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  <div class="content-wrapper">

    <section class="content-header">
      <h1>Video Resources <small>for this class</small></h1>
      <ol class="breadcrumb">
        <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Video Lessons</li>
      </ol>
    </section>

    <!-- Available Video Lessons Table -->
    <section class="content">
      <div class="box box-solid" style="border-top:3px solid #605ca8;">
        <div class="box-header with-border" style="background-color:#f3edff;">
          <h3 class="box-title" style="color:#605ca8;">
            <i class="fa fa-video-camera"></i> Available Video Lessons
          </h3>
          <a href="videosgrid.php?classId=<?= $classId ?>" class="btn btn-sm btn-primary">
            <i class="fa fa-th"></i> View as Grid
          </a>
        </div>

        <div class="box-body" style="background-color:#ffffff;">
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
                // Fetch only video resources assigned to this class
                $sql = "
                  SELECT r.Title, r.FilePath, r.Description
                  FROM resources r
                  JOIN resourceassignments ra ON r.ResourceID = ra.ResourceID
                  WHERE r.ResourceType = 'video'
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
                    $filePath = htmlspecialchars($row['FilePath']);
                    $description = htmlspecialchars($row['Description'] ?: '---');
                    $fileUrl = "/DoE_Genesis/DoeEdu/genesis/uploads/resources/" . $filePath;
                    ?>
                    <tr>
                      <td><?= $title ?></td>
                      <td style="width: 200px;">
                        <video controls style="width:100%; height:100px;">
                          <source src="<?= $fileUrl ?>" type="video/mp4">
                          Your browser does not support the video element.
                        </video>
                      </td>
                      <td><?= $description ?></td>
                      <td>
                        <a href="<?= $fileUrl ?>" class="btn btn-xs btn-success" download title="Download">
                          <i class="fa fa-download"></i> Download
                        </a>
                      </td>
                    </tr>
                  <?php
                  }
                } else {
                  echo '<tr><td colspan="4" class="text-muted">No video lessons assigned to this class.</td></tr>';
                }
                $stmt->close();
                ?>
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
