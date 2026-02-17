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


if (!isset($_GET['classId']) || intval($_GET['classId']) <= 0) {
  die("Invalid class selected.");
}
$classId = intval($_GET['classId']);
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

<?php include_once(LEARNER_PATH . "/../partials/header.php"); ?>
<?php include_once(LEARNER_PATH . "/../partials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Audio Resources <small>for this class</small></h1>
      <ol class="breadcrumb">
        <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Audio Lessons</li>
      </ol>
    </section>

    <!-- Audio Lessons -->
    <section class="content">
      <div class="box box-solid" style="border-top:3px solid #605ca8;">
        <div class="box-header with-border" style="background-color:#f3edff;">
          <h3 class="box-title" style="color:#605ca8;">
            <i class="fa fa-headphones"></i>Audio Lessons
          </h3>
          <div class="box-tools">
            <button id="gridBtn" class="btn btn-sm btn-primary">
              <i class="fa fa-th"></i> Grid View
            </button>
            <button id="listBtn" class="btn btn-sm btn-default">
              <i class="fa fa-th-list"></i> List View
            </button>
          </div>
        </div>

        <div class="box-body" style="background-color:#ffffff;">
          <?php
          $sql = "
            SELECT r.ResourceID, r.Title, r.FilePath, r.Description
            FROM resources r
            JOIN resourceassignments ra ON r.ResourceID = ra.ResourceID
            WHERE r.ResourceType = 'audio'
              AND ra.ClassID = ?
            ORDER BY r.UploadedAt DESC
          ";

          $stmt = $connect->prepare($sql);
          $stmt->bind_param("i", $classId);
          $stmt->execute();
          $result = $stmt->get_result();

          if ($result && $result->num_rows > 0):
          ?>
            <!-- Grid View -->
            <div id="gridView">
              <div class="row">
                <?php while ($row = $result->fetch_assoc()):
                  $title = htmlspecialchars($row['Title']);
                  $desc = htmlspecialchars($row['Description'] ?? 'No description available.');
                  $filePath = htmlspecialchars($row['FilePath']);
                  $fileUrl = RESOURCES_URL . '/' . $filePath;
                ?>
                  <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="box box-widget" style="border:1px solid #ddd; border-radius:10px; padding:10px; text-align:center;">
                      <div class="box-body">
                        <i class="fa fa-music" style="font-size:40px; color:#605ca8;"></i>
                        <h5 style="margin-top:10px; color:#333; min-height:30px;"><?= $title ?></h5>
                        <audio controls style="width:100%; margin-top:10px;">
                          <source src="<?= $fileUrl ?>" type="audio/mpeg">
                          Your browser does not support the audio element.
                        </audio>
                      </div>
                    </div>
                  </div>
                <?php endwhile; ?>
              </div>
            </div>

            <!-- List View -->
            <?php $result->data_seek(0); // Reset result pointer ?>
            <div id="listView" style="display:none;">
              <table class="table table-bordered table-hover">
                <thead style="background-color:#e6e0fa; color:#333;">
                  <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Play</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while ($row = $result->fetch_assoc()):
                    $title = htmlspecialchars($row['Title']);
                    $desc = htmlspecialchars($row['Description'] ?? 'No description available.');
                    $filePath = htmlspecialchars($row['FilePath']);
                    $fileUrl = RESOURCES_URL . '/' . $filePath;
                  ?>
                    <tr>
                      <td><?= $title ?></td>
                      <td><?= $desc ?></td>
                      <td style="text-align:center;">
                        <audio controls style="width:200px;">
                          <source src="<?= $fileUrl ?>" type="audio/mpeg">
                          Your browser does not support the audio element.
                        </audio>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>

          <?php
          else:
            echo '<div class="text-center text-muted">No audio lessons assigned to this class.</div>';
          endif;
          $stmt->close();
          ?>
        </div>
      </div>
    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>

<script>
  // Toggle grid/list
  document.getElementById('gridBtn').addEventListener('click', function() {
    document.getElementById('gridView').style.display = 'block';
    document.getElementById('listView').style.display = 'none';
    this.classList.add('btn-primary');
    this.classList.remove('btn-default');
    document.getElementById('listBtn').classList.remove('btn-primary');
    document.getElementById('listBtn').classList.add('btn-default');
  });

  document.getElementById('listBtn').addEventListener('click', function() {
    document.getElementById('gridView').style.display = 'none';
    document.getElementById('listView').style.display = 'block';
    this.classList.add('btn-primary');
    this.classList.remove('btn-default');
    document.getElementById('gridBtn').classList.remove('btn-primary');
    document.getElementById('gridBtn').classList.add('btn-default');
  });
</script>

</body>
</html>
