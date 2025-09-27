<!DOCTYPE html>
<html>

<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../../common/pages/login.php");
  exit();
}

include(__DIR__ . "/../../common/partials/queries.php"); 
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
      <h1>Grade 11 Mathematics <small>PDF Notes</small></h1>
      <ol class="breadcrumb">
        <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">PDF Resources</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Available PDF Notes</h3>
            </div>

            <div class="box-body">
              <div class="row">

                <?php
                // Fetch all public PDFs from resources table
                $sql = "SELECT ResourceID, Title, FilePath, Description FROM resources WHERE ResourceType = 'PDF' AND Visibility = 'private' ORDER BY UploadedAt DESC";
                $result = $connect->query($sql);

                if ($result && $result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                    $title = htmlspecialchars($row['Title']);
                    $filePath = htmlspecialchars($row['FilePath']); // e.g., "algebra_basics.pdf"
                    $description = htmlspecialchars($row['Description']);
                    // Optionally parse number of pages from description or elsewhere, else 'N/A'
                    $pages = 'N/A'; // Or extract pages info if available
                ?>

                <div class="col-md-4">
                  <div class="box box-widget widget-user" style="border-top: 3px solid #3c8dbc;">
                    <div class="box-body" style="background-color: #f9f9f9;">
                      <h4 class="text-primary"><?php echo $title; ?></h4>
                      <p><strong>Pages:</strong> <?php echo $pages; ?></p>

                      <iframe src="<?php echo '../../uploads/resources/' . $filePath; ?>" style="width: 100%; height: 250px;" frameborder="0"></iframe>

                      <a href="<?php echo '../../uploads/resources/' . $filePath; ?>" download class="btn btn-sm btn-success pull-right" style="margin-top: 10px;">
                        <i class="fa fa-download"></i> Download
                      </a>
                    </div>
                  </div>
                </div>

                <?php
                  }
                } else {
                  echo '<p class="text-muted">No PDF notes found.</p>';
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
