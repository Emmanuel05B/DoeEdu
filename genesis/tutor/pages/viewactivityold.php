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



$activityId = isset($_GET['activityId']) ? intval($_GET['activityId']) : 0;
$tutorId = $_SESSION['user_id']; // Logged-in tutor

// Fetch activity details including SubjectId
$stmt = $connect->prepare("
    SELECT TutorId, SubjectId, Grade, Topic, Title, Instructions, MemoPath, TotalMarks, CreatedAt, ImagePath
    FROM onlineactivities 
    WHERE Id = ?
");
$stmt->bind_param("i", $activityId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Activity not found.");
}

$activity = $result->fetch_assoc();
$subjectId = $activity['SubjectId'];

// Fetch the subject name from subjects table
$stmt2 = $connect->prepare("SELECT SubjectName FROM subjects WHERE SubjectId = ?");
$stmt2->bind_param("i", $subjectId);
$stmt2->execute();
$result2 = $stmt2->get_result();

if ($result2->num_rows === 0) {
    die("Subject not found.");
}

$subjectRow = $result2->fetch_assoc();
$subjectName = $subjectRow['SubjectName'];



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


<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
<?php include_once(TUTOR_PATH . "/../partials/header.php"); ?> 
<?php include_once(TUTOR_PATH . "/../partials/mainsidebar.php"); ?>


  <div class="content-wrapper">
    <section class="content-header">
       <h1>Edit Activity <small>Update activity details and questions</small></h1>
        <ol class="breadcrumb">
          <li><a href="tutorindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Edit Activity</li>
        </ol>
    </section>
    
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <!-- Update Activity Form -->
          <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Update Activity Details</h3>
            </div>
            <div class="box-body">
              <form action="updateactivity.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="activityId" value="<?php echo $activityId; ?>">

                <div class="row">
                  <!-- Title -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Title</label>
                      <input type="text" name="Title" class="form-control" value="<?php echo htmlspecialchars($activity['Title']); ?>" required>
                    </div>
                  </div>

                  <!-- Grade & Subject -->
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Grade</label>
                      <input type="text" class="form-control" value="<?php echo htmlspecialchars($activity['Grade']); ?>" readonly>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Subject</label>
                      <input type="text" class="form-control" value="<?php echo $subjectName; ?>" readonly>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <!-- Instructions -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Instructions</label>
                      <textarea name="Instructions" class="form-control" rows="4"><?php echo htmlspecialchars($activity['Instructions']); ?></textarea>
                    </div>
                  </div>

                  <!-- Image -->
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Current Image</label><br>
                      <?php if(!empty($activity['ImagePath']) && file_exists($activity['ImagePath'])): ?>
                        <img src="<?php echo htmlspecialchars($activity['ImagePath']); ?>" style="max-width:100%; margin-bottom:10px;">
                      <?php else: ?>
                        <p>No image uploaded</p>
                      <?php endif; ?>
                      <input type="file" name="ImagePath" class="form-control">
                    </div>
                  </div>

                  <!-- Memo -->
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Current Memo</label><br>
                      <?php if(!empty($activity['MemoPath']) && file_exists($activity['MemoPath'])): ?>
                        <a href="<?php echo htmlspecialchars($activity['MemoPath']); ?>" target="_blank">View Memo</a>
                      <?php else: ?>
                        <p>No memo uploaded</p>
                      <?php endif; ?>
                      <input type="file" name="MemoPath" class="form-control">
                    </div>
                  </div>
                </div>

                <button type="submit" class="btn btn-primary">Update Activity</button>
              </form>
            </div>
          </div>


          <!-- Questions Display -->
          <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title"><?php echo htmlspecialchars($activity['Title']); ?> - Questions</h3>
            </div>

            <div class="box-body">

                <!-- Desktop/Table layout -->
                <div class="hidden-xs hidden-sm">
                  <table class="table table-borderless">
                    <tr>
                    <?php
                    $columnsPerRow = 3;
                    $colCount = 0;

                    foreach ($questions as $index => $question):
                        if ($colCount == $columnsPerRow) {
                            echo "</tr><tr>";
                            $colCount = 0;
                        }
                    ?>
                        <td class="align-top" style="width: <?php echo 100/$columnsPerRow; ?>%;">
                          <div class="box box-solid">
                            <div class="box-body">
                              <h4>Question <?php echo $index + 1; ?></h4>
                              <p><?php echo htmlspecialchars($question['QuestionText']); ?></p>
                              <ul class="list-unstyled">
                                <li>A. <?php echo htmlspecialchars($question['OptionA']); ?></li>
                                <li>B. <?php echo htmlspecialchars($question['OptionB']); ?></li>
                                <li>C. <?php echo htmlspecialchars($question['OptionC']); ?></li>
                                <li>D. <?php echo htmlspecialchars($question['OptionD']); ?></li>
                              </ul>
                              <a href="editquestion.php?questionId=<?php echo $question['Id']; ?>" class="btn btn-sm btn-primary">Edit Question</a>
                            </div>
                          </div>
                        </td>
                    <?php
                        $colCount++;
                    endforeach;

                    while ($colCount > 0 && $colCount < $columnsPerRow) {
                        echo "<td></td>";
                        $colCount++;
                    }
                    ?>
                    </tr>
                  </table>
                </div>

                <!-- Mobile layout -->
                <div class="visible-xs visible-sm">
                  <?php foreach ($questions as $index => $question): ?>
                    <div class="box box-solid" style="margin-bottom:15px;">
                      <div class="box-body">
                        <h4>Question <?php echo $index + 1; ?></h4>
                        <p><?php echo htmlspecialchars($question['QuestionText']); ?></p>
                        <ul class="list-unstyled">
                          <li>A. <?php echo htmlspecialchars($question['OptionA']); ?></li>
                          <li>B. <?php echo htmlspecialchars($question['OptionB']); ?></li>
                          <li>C. <?php echo htmlspecialchars($question['OptionC']); ?></li>
                          <li>D. <?php echo htmlspecialchars($question['OptionD']); ?></li>
                        </ul>
                        <a href="editquestion.php?questionId=<?php echo $question['Id']; ?>" class="btn btn-sm btn-primary">Edit Question</a>
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

<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>
<?php if (isset($_SESSION['alert'])): ?>
<script>
    Swal.fire({
        icon: '<?php echo $_SESSION['alert']['icon']; ?>',
        title: '<?php echo $_SESSION['alert']['title']; ?>',
        text: '<?php echo $_SESSION['alert']['text']; ?>'
    });
</script>
<?php unset($_SESSION['alert']); ?>
<?php endif; ?>

</body>
</html>
