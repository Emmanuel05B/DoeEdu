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
?>

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
      <div class="row">
        <div class="col-xs-12">

          <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Available Slides</h3>
            </div>

            <div class="box-body">
              <div class="row">

<?php
// Fetch slides assigned to this class
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
    while ($row = $result->fetch_assoc()):
        $title = htmlspecialchars($row['Title']);
        $filePath = htmlspecialchars($row['FilePath']);
        $description = htmlspecialchars($row['Description']);
        $fileUrl = '/DoeEdu/genesis/uploads/resources/' . $filePath;
?>
                <div class="col-md-4">
                  <div class="box box-widget widget-user" style="border-top: 3px solid #605ca8;">
                    <div class="box-body" style="background-color: #f9f9f9;">
                      <h5 class="text-primary"><?php echo $title; ?></h5>
                      
                      <!-- Slide icon preview -->
                      <div style="font-size:50px; text-align:center; margin-bottom:10px;">
                        <i class="fa fa-file-powerpoint-o" style="color:#d9534f;"></i>
                      </div>

                      <p class="text-muted"><?php echo $description ?: 'N/A'; ?></p>
                      <a href="<?php echo $fileUrl; ?>" download class="btn btn-sm btn-success pull-right" style="margin-top:10px;">
                        <i class="fa fa-download"></i> Download
                      </a>
                    </div>
                  </div>
                </div>
<?php
    endwhile;
} else {
    echo '<p class="text-muted">No slides assigned to this class.</p>';
}
$stmt->close();
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
