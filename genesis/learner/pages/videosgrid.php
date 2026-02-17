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
      <h1>Video Resources <small>for this class</small></h1>
      <ol class="breadcrumb">
        <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Video Lessons</li>
      </ol>
    </section>

    <section class="content">
        
      <div class="box box-solid" style="border-top:3px solid #605ca8;">
        <div class="box-header with-border" style="background-color:#f3edff;">
          <h3 class="box-title" style="color:#605ca8;">
            <i class="fa fa-video-camera"></i> Video Lessons
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
            WHERE r.ResourceType = 'video'
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
                  $filePath = htmlspecialchars($row['FilePath']);
                 // $fileUrl = "/DoE_Genesis/DoeEdu/genesis/uploads/resources/" . $filePath;
                  $fileUrl = RESOURCES_URL . '/' . $filePath;
                ?>
                  <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="box box-widget" style="border:1px solid #ddd; border-radius:10px; padding:5px; text-align:center;">
                      <div class="box-body">
                        <h5 style="margin-top:1px; color:#333; min-height:30px;"><?= $title ?></h5>
                        <video controls style="width:100%; border-radius:8px; margin-bottom:8px;">
                          <source src="<?= $fileUrl ?>" type="video/mp4">
                          Your browser does not support the video element.
                        </video>
                      </div>
                    </div>
                  </div>
                <?php endwhile; ?>
              </div>
            </div>

            <!-- List View -->
            <?php $result->data_seek(0); // Reset pointer ?>
            <div id="listView" style="display:none;">
              <table class="table table-bordered table-hover">
                <thead style="background-color:#e6e0fa; color:#333;">
                  <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while ($row = $result->fetch_assoc()):
                    $title = htmlspecialchars($row['Title']);
                    $desc = htmlspecialchars($row['Description'] ?? 'No description available.');
                    $filePath = htmlspecialchars($row['FilePath']);
                    //$fileUrl = "/DoE_Genesis/DoeEdu/genesis/uploads/resources/" . $filePath;
                    $fileUrl = RESOURCES_URL . '/' . $filePath;
                  ?>
                    <tr>
                      <td><?= $title ?></td>
                      <td><?= $desc ?></td>
                      <td style="text-align:center;">
                        <button class="btn btn-default btn-sm playVideo" data-url="<?= $fileUrl ?>" data-title="<?= $title ?>" data-desc="<?= $desc ?>" title="Play Video">
                          <i class="fa fa-play-circle" style="font-size:18px; color:#605ca8;"></i>
                        </button>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>

          <?php
          else:
            echo '<div class="text-center text-muted">No video lessons assigned to this class.</div>';
          endif;
          $stmt->close();
          ?>
        </div>
      </div>
      
      
      <!-- ZIP Resources -->
        <!-- Compressed Resources -->
        <div class="box box-solid" style="border-top:3px solid #00a65a; margin-top:20px;">
          <div class="box-header with-border" style="background-color:#e8f5e9;">
            <h3 class="box-title" style="color:#00a65a;">
              <i class="fa fa-file-archive-o"></i> Zipped Videos
            </h3>
            <div class="box-tools">
              <button id="zipGridBtn" class="btn btn-sm btn-primary">
                <i class="fa fa-th"></i> Grid View
              </button>
              <button id="zipListBtn" class="btn btn-sm btn-default">
                <i class="fa fa-th-list"></i> List View
              </button>
            </div>
          </div>
        
          <div class="box-body" style="background-color:#ffffff;">
            <?php
            $zipSql = "
              SELECT r.ResourceID, r.Title, r.FilePath, r.Description
              FROM resources r
              JOIN resourceassignments ra ON r.ResourceID = ra.ResourceID
              WHERE r.ResourceType = 'compressed'
                AND ra.ClassID = ?
              ORDER BY r.UploadedAt DESC
            ";
        
            $zipStmt = $connect->prepare($zipSql);
            $zipStmt->bind_param("i", $classId);
            $zipStmt->execute();
            $zipResult = $zipStmt->get_result();
            ?>
        
            <!-- Grid View -->
            <div id="zipGridView" class="row">
              <?php if ($zipResult && $zipResult->num_rows > 0):
                $zipResult->data_seek(0); // reset pointer
                while ($row = $zipResult->fetch_assoc()):
                  $title = htmlspecialchars($row['Title']);
                  $filePath = htmlspecialchars($row['FilePath']);
                  $fileUrl = RESOURCES_URL . '/' . $filePath;
              ?>
                <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="box box-widget" style="border:1px solid #ddd; border-radius:10px; padding:10px; text-align:center;">
                    <div class="box-body">
                      <h5 style="margin-top:1px; color:#333; min-height:30px;"><?= $title ?></h5>
                      <a href="<?= $fileUrl ?>" class="btn btn-success btn-sm" download>
                        <i class="fa fa-download"></i> Download
                      </a>
                    </div>
                  </div>
                </div>
              <?php endwhile; else: ?>
                <div class="text-center text-muted" style="width:100%;">No compressed resources assigned to this class.</div>
              <?php endif; ?>
            </div>
        
            <!-- List View -->
            <div id="zipListView" style="display:none;">
              <?php if ($zipResult && $zipResult->num_rows > 0):
                $zipResult->data_seek(0);
              ?>
              <table class="table table-bordered table-hover">
                <thead style="background-color:#c8e6c9; color:#333;">
                  <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while ($row = $zipResult->fetch_assoc()):
                    $title = htmlspecialchars($row['Title']);
                    $desc = htmlspecialchars($row['Description'] ?? 'No description available.');
                    $filePath = htmlspecialchars($row['FilePath']);
                    $fileUrl = RESOURCES_URL . '/' . $filePath;
                  ?>
                    <tr>
                      <td><?= $title ?></td>
                      <td><?= $desc ?></td>
                      <td style="text-align:center;">
                        <a href="<?= $fileUrl ?>" class="btn btn-success btn-sm" download>
                          <i class="fa fa-download"></i>
                        </a>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
              <?php endif; ?>
            </div>
        
            <?php $zipStmt->close(); ?>
          </div>
        </div>



    </section>
  </div>

  <!-- Video Modal -->
  <div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="videoModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content" style="border-radius:10px;">
        <div class="modal-header" style="background-color:#605ca8; color:#fff;">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;"><span>&times;</span></button>
          <h4 class="modal-title" id="videoModalLabel"></h4>
        </div>
        <div class="modal-body" style="text-align:center;">
          <p id="videoDescription" style="margin-bottom:10px; color:#555;"></p>
          <video id="videoPlayer" controls style="width:100%; border-radius:10px;">
            <source src="" type="video/mp4">
          </video>
        </div>
      </div>
    </div>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>

<script>
  // Toggle between grid and list views
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

  // Play video in modal
  document.querySelectorAll('.playVideo').forEach(btn => {
    btn.addEventListener('click', function() {
      const url = this.getAttribute('data-url');
      const title = this.getAttribute('data-title');
      const desc = this.getAttribute('data-desc');

      document.getElementById('videoModalLabel').textContent = title;
      document.getElementById('videoDescription').textContent = desc;
      document.getElementById('videoPlayer').src = url;

      $('#videoModal').modal('show');
    });
  });

  // Stop video on modal close
  $('#videoModal').on('hidden.bs.modal', function () {
    const player = document.getElementById('videoPlayer');
    player.pause();
    player.src = '';
  });
</script>

<script>
  // Toggle Grid/List for Compressed Resources
  document.getElementById('zipGridBtn').addEventListener('click', function() {
    document.getElementById('zipGridView').style.display = 'flex';
    document.getElementById('zipListView').style.display = 'none';
    this.classList.add('btn-primary');
    this.classList.remove('btn-default');
    document.getElementById('zipListBtn').classList.remove('btn-primary');
    document.getElementById('zipListBtn').classList.add('btn-default');
  });

  document.getElementById('zipListBtn').addEventListener('click', function() {
    document.getElementById('zipGridView').style.display = 'none';
    document.getElementById('zipListView').style.display = 'block';
    this.classList.add('btn-primary');
    this.classList.remove('btn-default');
    document.getElementById('zipGridBtn').classList.remove('btn-primary');
    document.getElementById('zipGridBtn').classList.add('btn-default');
  });
</script>

</body>
</html>
