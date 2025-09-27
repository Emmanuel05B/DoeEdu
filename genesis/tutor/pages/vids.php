<!DOCTYPE html>
<html>

<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../../common/pages/login.php");
  exit();
}

include(__DIR__ . "/../../common/partials/queries.php"); // assume this contains $conn (mysqli connection)
?>

<?php include(__DIR__ . "/../../common/partials/head.php"); ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

    <section class="content-header">
      <h1>Grade 11 Mathematics <small>Video Library</small></h1>
      <ol class="breadcrumb">
        <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Study Videos</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Available Videos</h3>
            </div>

            <div class="box-body">
              <div class="row">

                <?php
                // Prepare and execute query to get all videos
                $sql = "SELECT ResourceID, Title, FilePath, Description FROM resources WHERE ResourceType = 'video' AND Visibility = 'private' ORDER BY UploadedAt DESC";
                $result = $connect->query($sql);

                if ($result && $result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                    // You can customize how duration is stored or calculated - 
                    // for now just omit or use description for duration or show "N/A"
                    $videoTitle = htmlspecialchars($row['Title']);
                    $videoPath = htmlspecialchars($row['FilePath']);
                    $videoDesc = htmlspecialchars($row['Description']);
                ?>

                <div class="col-md-3">
                  <div class="box box-widget widget-user" style="border-top: 3px solid #3c8dbc;">
                    <div class="box-body" style="background-color: #f9f9f9;">
                      <h4 class="text-primary"><?php echo $videoTitle; ?></h4>
                      <p><strong>Description:</strong> <?php echo $videoDesc ?: 'N/A'; ?></p>

                      <video controls style="width: 100%; height: 150px; border-radius: 5px; margin-bottom: 10px;">
                        <source src="<?php echo '/DoeEdu/genesis/uploads/resources/' . $videoPath; ?>" type="video/mp4">
                        Your browser does not support the video tag.
                      </video>

                      <a href="<?php echo '/DoeEdu/genesis/uploads/resources/' . $videoPath; ?>" 
                        download="<?php echo 'DOE_' . $videoPath; ?>" 
                        class="btn btn-sm btn-success pull-right" style="margin-top: 10px;">
                        <i class="fa fa-download"></i> Download
                      </a>
                    </div>
                  </div>
                </div>

                <?php
                  }
                } else {
                  echo '<p class="text-muted">No videos found.</p>';
                }
                ?>

              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

  </div>

  <div class="control-sidebar-bg"></div>
</div>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

</body>
</html>
