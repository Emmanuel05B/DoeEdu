<!DOCTYPE html>
<html>
<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../partials/connect.php");

if (!isset($_GET['activityId']) || !is_numeric($_GET['activityId'])) {
    die("Invalid activity ID.");
}

$activityId = intval($_GET['activityId']);
$userId = $_SESSION['user_id']; // logged-in learner

// Get subject name from URL if passed
$subjectName = isset($_GET['subject']) ? $_GET['subject'] : '';


// First, get the learner's class for this activity from the assignments
$stmtClass = $connect->prepare("
    SELECT aa.ClassID 
    FROM onlineactivitiesassignments aa
    JOIN learnerclasses lc ON aa.ClassID = lc.ClassID
    WHERE lc.LearnerId = ? AND aa.OnlineActivityId = ?
    LIMIT 1
");
$stmtClass->bind_param("ii", $userId, $activityId);
$stmtClass->execute();
$resClass = $stmtClass->get_result();
if ($resClass->num_rows === 0) {
    die("You are not enrolled in any class for this activity.");
}
$classRow = $resClass->fetch_assoc();
$classId = $classRow['ClassID'];
$stmtClass->close();





// Fetch activity details along with the correct due date from assignments
$stmt = $connect->prepare("
    SELECT a.TutorId, a.SubjectId, a.Grade, a.Topic, a.Title, a.Instructions, 
           a.TotalMarks, aa.DueDate, a.CreatedAt, a.ImagePath
    FROM onlineactivities a
    INNER JOIN onlineactivitiesassignments aa 
        ON a.Id = aa.OnlineActivityId
    WHERE a.Id = ? AND aa.ClassID = ?
    LIMIT 1
");

if (!$stmt) {
    die("Prepare failed: " . $connect->error);
}

$stmt->bind_param("ii", $activityId, $classId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo 'Class ID = ' . $classId . '<br>';
    echo 'Activity ID = ' . $activityId . '<br>';
    die("Activity not found for your class.");
}

$activity = $result->fetch_assoc();
$stmt->close();



if ($activity['TutorId'] == $userId) {   // Learner should NOT be tutor who created it
    die("You do not have permission to view this activity.");
}

// Fetch questions
$qstmt = $connect->prepare("SELECT id, QuestionText, OptionA, OptionB, OptionC, OptionD, CorrectAnswer FROM onlinequestions WHERE ActivityId = ?");
$qstmt->bind_param("i", $activityId);
$qstmt->execute();
$qresult = $qstmt->get_result();

$questions = [];
while ($row = $qresult->fetch_assoc()) {
    $questions[] = $row;
}
$qstmt->close();
?>

<?php include(__DIR__ . "/../../common/partials/head.php"); ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  <?php if (isset($_GET['alreadysubmitted']) && $_GET['alreadysubmitted'] == 1): ?>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php  
    echo "<script>
          Swal.fire({
              icon: 'info',
              title: 'Already Completed',
              text: 'You have already submitted this homework.',
              showCancelButton: true,
              confirmButtonText: 'View Memo',
              cancelButtonText: 'Okay'
          }).then((result) => {
              if (result.isConfirmed) {
                  window.location.href = 'viewmemo.php?activityid=$activityId&subject=" . urlencode($subjectName) . "';
              } else {
                  window.location.href = 'viewhomework.php?activityId={$activityId}&subject=" . urlencode($subjectName) . "';
              }
          });
     
      </script>"; 
      ?>
  <?php endif; ?>

    <?php if (isset($_GET['submitted']) && $_GET['submitted'] == 1): ?>
      
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php  
    $score = intval($_GET['score']);
          echo "<script>

          Swal.fire({
              icon: 'success',
              title: 'Homework Submitted',
              text: 'Score: {$score}%',
              showCancelButton: true,
              confirmButtonText: 'View Memo',
              cancelButtonText: 'Go to Dashboard'
          }).then((result) => {
              if (result.isConfirmed) {
                  window.location.href = 'viewmemo.php?activityid={$activityId}&subject=" . urlencode($subjectName) . "'; 
              } else {
                  window.location.href = 'homework.php';
              }
          });
      </script>"; 
      ?>
  <?php endif; ?>


  <div class="content-wrapper">

    <!-- Page Headerf -->
    <section class="content-header">
        <h1>
            Quiz Details
            <small><?php echo htmlspecialchars($activity['Title']); ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?php echo htmlspecialchars($activity['Topic']); ?></li>
        </ol>
    </section>

    <!-- Main Content -->
    <section class="content">
      <div class="row">

        <!-- Activity Info -->
        <div class="col-xs-12">
          <div class="box box-solid box-info">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo htmlspecialchars($activity['Title']); ?></h3>
            </div>
            <div class="box-body">
              <p>
                <strong>Subject:</strong> <?php echo htmlspecialchars($subjectName); ?> &nbsp;|&nbsp;
                <strong>Grade:</strong> <?php echo htmlspecialchars($activity['Grade']); ?> &nbsp;|&nbsp;
                <strong>Topic:</strong> <?php echo htmlspecialchars($activity['Topic']); ?> &nbsp;|&nbsp;
                <strong>Due Date:</strong> <?php echo htmlspecialchars($activity['DueDate']); ?>
              </p>
            </div>
          </div>
        </div>

        <!-- Homework Form -->
        <div class="col-xs-12">
          <form action="submithomework.php" method="POST">
            <input type="hidden" name="activityId" value="<?php echo $activityId; ?>">
            <input type="hidden" name="subjectName" value="<?php echo htmlspecialchars($subjectName); ?>">

            <!-- Instructions Box -->
            <div class="col-xs-12" style="margin-bottom:15px;">
              <div class="box box-primary">
                <div class="box-body">
                  <?php if (!empty($activity['ImagePath']) && file_exists($activity['ImagePath'])): ?>
                      <div class="text-center" style="margin-bottom:10px;">
                        <img src="<?php echo htmlspecialchars($activity['ImagePath']); ?>" alt="Activity Image" class="img-responsive" style="max-height:180px; margin:0 auto;">
                      </div>
                  <?php endif; ?>

                  <h4>Instructions</h4>
                  <p><?php echo nl2br(htmlspecialchars($activity['Instructions'])); ?></p>
                </div>
              </div>
            </div>

            <!-- Questions -->
            <?php foreach ($questions as $index => $question): ?>
              <div class="col-md-6 col-sm-12" style="margin-bottom:15px;">
                <div class="box box-primary">
                  <div class="box-body">
                    <p><strong>Q<?php echo $index + 1; ?>:</strong> <?php echo htmlspecialchars($question['QuestionText']); ?></p>
                    <ul class="list-unstyled">
                      <li><label><input type="radio" name="answers[<?php echo $question['id']; ?>]" value="A" required> A. <?php echo htmlspecialchars($question['OptionA']); ?></label></li>
                      <li><label><input type="radio" name="answers[<?php echo $question['id']; ?>]" value="B"> B. <?php echo htmlspecialchars($question['OptionB']); ?></label></li>
                      <li><label><input type="radio" name="answers[<?php echo $question['id']; ?>]" value="C"> C. <?php echo htmlspecialchars($question['OptionC']); ?></label></li>
                      <li><label><input type="radio" name="answers[<?php echo $question['id']; ?>]" value="D"> D. <?php echo htmlspecialchars($question['OptionD']); ?></label></li>
                    </ul>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>

            <div class="col-xs-12" style="margin-top:10px;">
              <button type="submit" class="btn btn-primary">Submit Homework</button>
            </div>

          </form>
        </div>

      </div>
    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>
</body>
</html>
