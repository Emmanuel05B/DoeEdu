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

// Fetch activity details
$stmt = $connect->prepare("SELECT TutorId, SubjectName, Grade, Topic, Title, Instructions, TotalMarks, DueDate, CreatedAt, ImagePath FROM onlineactivities WHERE id = ?");
$stmt->bind_param("i", $activityId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Activity not found.");
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

  <div class="content-wrapper">

    <!-- Page Header -->
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
                <strong>Subject:</strong> <?php echo htmlspecialchars($activity['SubjectName']); ?> &nbsp;|&nbsp;
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
