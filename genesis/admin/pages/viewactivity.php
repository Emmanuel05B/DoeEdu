<!DOCTYPE html>
<html>

<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../../common/login.php");
  exit();
}

include('../../partials/connect.php');

// Validate activityId from GET
if (!isset($_GET['activityId']) || !is_numeric($_GET['activityId'])) {
    die("Invalid activity ID.");
}

$activityId = intval($_GET['activityId']);
$tutorId = $_SESSION['user_id']; // logged-in tutor

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

if ($activity['TutorId'] != $tutorId) {
    die("You do not have permission to view this activity.");
}

// Fetch questions
$qstmt = $connect->prepare("SELECT Id, QuestionText, OptionA, OptionB, OptionC, OptionD, CorrectAnswer FROM onlinequestions WHERE ActivityId = ?");
$qstmt->bind_param("i", $activityId);
$qstmt->execute();
$qresult = $qstmt->get_result();

$questions = [];
while ($row = $qresult->fetch_assoc()) {
    $questions[] = $row;
}
$qstmt->close();
?>

<?php include("../adminpartials/head.php"); ?>

<style>
  .question-tile {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 15px;
    margin-bottom: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }
  .question-tile h4 {
    margin-top: 0;
    font-weight: 600;
    color: #333;
  }
  .question-tile ul {
    list-style: none;
    padding-left: 0;
    margin-bottom: 10px;
  }
  .question-tile ul li {
    margin-bottom: 6px;
    color: #555;
  }
  .edit-btn {
    align-self: flex-start;
  }
</style>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include("../adminpartials/header.php") ?>
  <?php include("../adminpartials/mainsidebar.php") ?>

  <div class="content-wrapper">
    <section class="content-header">
       <h1>Edit Activity <small>List of all questions for this activities</small></h1>
        <ol class="breadcrumb">
          <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Questions</li>
        </ol>
    </section>
    
    <section class="content">
      <div class="row" >
        <div class="col-xs-12">

          <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title"><?php echo htmlspecialchars($activity['Title']); ?></h3>
              <p><small>
                Subject: <?php echo htmlspecialchars($activity['SubjectName']); ?> | 
                Grade: <?php echo htmlspecialchars($activity['Grade']); ?> | 
                Topic: <?php echo htmlspecialchars($activity['Topic']); ?> | 
                Due Date: <?php echo htmlspecialchars($activity['DueDate']); ?>
              </small></p>
            </div>

            <div class="box-body">
                <p><strong>Instructions:</strong></p>
                <p><?php echo nl2br(htmlspecialchars($activity['Instructions'])); ?></p>

                <?php if (!empty($activity['ImagePath']) && file_exists($activity['ImagePath'])): ?>
                <div style="margin-bottom:20px;">
                  <img src="<?php echo htmlspecialchars($activity['ImagePath']); ?>" alt="Activity Image" style="max-width: 30%; height: auto;">
                </div>
                <?php endif; ?>

                <h4>Questions</h4>

                <div class="row">
                  <?php foreach ($questions as $index => $question): ?>
                    <div class="col-lg-4 col-xs-12">
                      <div class="question-tile">
                        <h4>Question <?php echo $index + 1; ?></h4>
                        <p><?php echo htmlspecialchars($question['QuestionText']); ?></p>
                        <ul>
                          <li>A. <?php echo htmlspecialchars($question['OptionA']); ?></li>
                          <li>B. <?php echo htmlspecialchars($question['OptionB']); ?></li>
                          <li>C. <?php echo htmlspecialchars($question['OptionC']); ?></li>
                          <li>D. <?php echo htmlspecialchars($question['OptionD']); ?></li>
                        </ul>
                        <a href="editquestion.php?questionId=<?php echo $question['Id']; ?>" class="btn btn-sm btn-primary edit-btn">Edit Question</a>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>

            </div>
          </div>

        </div>
      </div>
    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<!-- jQuery 3 -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>

<script>
  $(function () {
    $('#example1').DataTable()
  })
</script>

</body>
</html>
