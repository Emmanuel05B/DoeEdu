<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}
include('../partials/connect.php');

// Get subject and grade from URL
$subjectId = isset($_GET['s']) ? (int)$_GET['s'] : 0;
$grade = isset($_GET['g']) ? $_GET['g'] : '';

// Basic validation
if ($subjectId <= 0 || $grade <= 0) {
    die('Invalid subject or grade.');
}


$stmt = $connect->prepare("SELECT SubjectName, Grade FROM subjects WHERE SubjectId = ? AND Grade = ?");
if (!$stmt) {
    die("Prepare failed: (" . $connect->errno . ") " . $connect->error);
}

$stmt->bind_param("is", $subjectId, $grade);
$stmt->execute();
$stmt->bind_result($subjectNameFromDb, $gradeFromDb);
if (!$stmt->fetch()) {
    die('Subject not found or grade mismatch.');
}
$stmt->close();

// Prepare display variables
$subjectDisplay = htmlspecialchars($subjectNameFromDb);
$gradeDisplay = "Grade " . htmlspecialchars($gradeFromDb);
?>

<!DOCTYPE html>
<html>
<?php include("tutorpartials/head.php"); ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include("tutorpartials/header.php"); ?>
  <?php include("tutorpartials/mainsidebar.php"); ?>

  <div class="content-wrapper" style="padding: 20px;">
    <section class="content-header">
      <h1>Create Notice for <?php echo $subjectDisplay . " - " . $gradeDisplay; ?></h1>
      <p>This notice will be visible only to <?php echo $gradeDisplay . " " . $subjectDisplay; ?> learners.</p>
    </section>

    <section class="content">
      <div class="row">

        <!-- Left column: Form -->
        <div class="col-md-6">
          <div class="box box-primary" style="max-width: 100%;">
            <div class="box-header with-border">
              <h3 class="box-title">Post Announcement</h3>
            </div>

            <form action="addsubjectnoticeh.php" method="POST">
              <div class="box-body">
                <input type="hidden" name="subjectName" value="<?php echo $subjectDisplay; ?>">
                <input type="hidden" name="grade" value="<?php echo $gradeFromDb; ?>">

                <div class="row">
                  <div class="form-group col-md-6">
                    <label>Subject</label>
                    <input type="text" class="form-control" value="<?php echo $subjectDisplay; ?>" disabled>
                  </div>

                  <div class="form-group col-md-6">
                    <label>Grade</label>
                    <input type="text" class="form-control" value="<?php echo $gradeDisplay; ?>" disabled>
                  </div>
                </div>

                <div class="form-group">
                  <label>Title <span style="color:red">*</span></label>
                  <input type="text" name="title" class="form-control" required>
                </div>

                <div class="form-group">
                  <label>Content <span style="color:red">*</span></label>
                  <textarea name="content" class="form-control" rows="5" placeholder="Write your notice here..." required></textarea>
                </div>
              </div>

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Post Notice</button>
              </div>
            </form>
          </div>
        </div>

        <!-- Right column: Notices -->
        <div class="col-md-6">
          <div class="box box-primary" style="max-height: 600px; overflow-y: auto; background-color: white; padding: 15px; border-radius: 4px;">
            <div class="box-header with-border">
              <h3 class="box-title">Tutor Notices from Admins</h3>
            </div>
            <div class="box-body">
              <?php
              $sql = "SELECT NoticeNo, Title, Content, Date, IsOpened 
                      FROM notices 
                      WHERE CreatedFor IN (2, 12) 
                      ORDER BY Date DESC
                      LIMIT 30";
              $stmt = $connect->prepare($sql);
              $stmt->execute();
              $results = $stmt->get_result();

              if ($results && $results->num_rows > 0):
                while ($notice = $results->fetch_assoc()):
                  $readClass = $notice['IsOpened'] ? 'read' : '';
              ?>
                  <div class="notice <?php echo $readClass; ?>" data-id="<?php echo $notice['NoticeNo']; ?>" style="margin-bottom: 15px; padding: 15px; background-color: #f9f9f9; border-left: 5px solid #3c8dbc; border-radius: 4px;">
                    <p><strong>Date:</strong> <?php echo date('Y-m-d', strtotime($notice['Date'])); ?></p>
                    <p><strong><?php echo htmlspecialchars($notice['Title']); ?></strong></p>
                    <p><?php echo nl2br(htmlspecialchars($notice['Content'])); ?></p>
                  </div>
                  <hr style="border-top: 1px dashed #ccc; margin: 10px 0;" />
              <?php
                endwhile;
              else:
                echo "<p>No notices available.</p>";
              endif;
              $stmt->close();
              ?>
            </div> <!-- /.box-body -->
          </div> <!-- /.box -->
        </div> <!-- /.col-md-6 -->

      </div> <!-- /.row -->
    </section>
  </div> <!-- /.content-wrapper -->

  <div class="control-sidebar-bg"></div>
</div>

</body>
</html>
