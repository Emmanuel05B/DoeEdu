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
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

<?php include_once(TUTOR_PATH . "/../partials/header.php"); ?> 
<?php include_once(TUTOR_PATH . "/../partials/mainsidebar.php"); ?>


  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

    <section class="content-header">
      <h1>Grade 11 Mathematics <small>Audio Resources</small></h1>
      <ol class="breadcrumb">
        <li><a href="tutorindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Audio Lessons</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Available Audio Lessons</h3>
            </div>

            <div class="box-body">
              <div class="row">

                <?php
                // Fetch all public Audio resources
                $sql = "SELECT Title, FilePath, Description FROM resources WHERE ResourceType = 'audio' AND Visibility = 'private' ORDER BY UploadedAt DESC";
                $result = mysqli_query($connect, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                  while ($row = mysqli_fetch_assoc($result)) {
                    $title = htmlspecialchars($row['Title']);
                    $filePath = htmlspecialchars($row['FilePath']); // e.g., "functions_recap.mp3"
                    $description = htmlspecialchars($row['Description']);

                    // Extract length info from description if available, else default
                    $length = 'N/A';
                    if (preg_match('/Length:\s*([\d:]+(\s*min)?)/i', $description, $matches)) {
                      $length = $matches[1];
                    }
                ?>
                <div class="col-md-4">
                  <div class="box box-widget widget-user" style="border-top: 3px solid #3c8dbc;">
                    <div class="box-body" style="background-color: #f9f9f9;">
                      <h4 class="text-primary"><?php echo $title; ?></h4>
                      <p><strong>Length:</strong> <?php echo $length; ?></p>

                      <audio controls style="width: 100%; margin-bottom: 10px;">
                        <source src="<?php echo '/DoeEdu/genesis/uploads/resources/' . $filePath; ?>" type="audio/mpeg">
                        Your browser does not support the audio element.
                      </audio>
                      
                    </div>
                  </div>
                </div>
                <?php
                  }
                } else {
                  echo '<p class="text-muted">No audio lessons found.</p>';
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

<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>

</body>
</html>
