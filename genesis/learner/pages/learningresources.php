<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../../common/pages/login.php");
  exit();
}
include(__DIR__ . "/../../partials/connect.php");

// Get learner ID
$learnerId = $_SESSION['user_id'];

// Fetch learner's subjects & classes
$stmt = $connect->prepare("
    SELECT 
        c.ClassID,
        s.SubjectName,
        c.Grade,
        c.GroupName
    FROM learnerclasses lc
    JOIN classes c ON lc.ClassID = c.ClassID
    JOIN subjects s ON c.SubjectID = s.SubjectId
    WHERE lc.LearnerID = ?
    ORDER BY c.Grade, s.SubjectName, c.GroupName
");
$stmt->bind_param("i", $learnerId);
$stmt->execute();
$result = $stmt->get_result();
$learnerSubjects = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
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
      <h1>My Resources <small>list of your learning resources categorized.</small></h1>
      <ol class="breadcrumb">
        <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Resources</li>
      </ol>
    </section>

    <section class="content">

      <?php foreach ($learnerSubjects as $subject): ?>
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header">
                <h3 class="box-title">
                  <?php echo htmlspecialchars($subject['SubjectName']) . " - " . htmlspecialchars($subject['Grade']) . " (" . htmlspecialchars($subject['GroupName']) . ")"; ?>
                </h3>
              </div>
              <div class="box-body">
                <div class="row">

                  <!-- Notes -->
                  <div class="col-md-3">
                    <div class="box box-primary">
                      <div class="box-header with-border">
                        <h3 class="box-title">Notes & Documents</h3>
                      </div>
                      <div class="box-body">
                        <p>All in one place.</p>
                        <a href="notes.php?classId=<?php echo $subject['ClassID']; ?>" class="btn btn-sm btn-primary">View Notes</a>
                      </div>
                    </div>
                  </div>

                  <!-- Powerpoint Slides -->
                  <div class="col-md-3">
                    <div class="box box-info">
                      <div class="box-header with-border">
                        <h3 class="box-title">Powerpoint Slides</h3>
                      </div>
                      <div class="box-body">
                        <p>Quick-reference form.</p>
                        <a href="slides.php?classId=<?php echo $subject['ClassID']; ?>" class="btn btn-sm btn-info">View Powerpoint Slides</a>
                      </div>
                    </div>
                  </div>

                  <!-- Videos -->
                  <div class="col-md-3">
                    <div class="box box-success">
                      <div class="box-header with-border">
                        <h3 class="box-title">Videos</h3>
                      </div>
                      <div class="box-body">
                        <p>Watch topic videos.</p>
                        <a href="videosgrid.php?classId=<?php echo $subject['ClassID']; ?>" class="btn btn-sm btn-success">Watch Videos</a>
                      </div>
                    </div>
                  </div>

                  <!-- Audio -->
                  <div class="col-md-3">
                    <div class="box box-warning">
                      <div class="box-header with-border">
                        <h3 class="box-title">Audio</h3>
                      </div>
                      <div class="box-body">
                        <p>Listen to audio lessons.</p>
                        <a href="audiogrid.php?classId=<?php echo $subject['ClassID']; ?>" class="btn btn-sm btn-warning">Open Audios</a>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>

    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>
</body>
</html>
