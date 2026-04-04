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

// Fetch activity details
$stmt = $connect->prepare("
    SELECT TutorId, SubjectId, Grade, Topic, Title, Instructions, MemoPath, TotalMarks, CreatedAt, ImagePath
    FROM onlineactivities 
    WHERE Id = ?
");
$stmt->bind_param("i", $activityId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) die("Activity not found.");
$activity = $result->fetch_assoc();

$imagepath =  $activity['ImagePath'];

// Subject name
$stmt2 = $connect->prepare("SELECT SubjectName FROM subjects WHERE SubjectId = ?");
$stmt2->bind_param("i", $activity['SubjectId']);
$stmt2->execute();
$result2 = $stmt2->get_result();
if ($result2->num_rows === 0) die("Subject not found.");
$subjectRow = $result2->fetch_assoc();
$subjectName = $subjectRow['SubjectName'];

// Fetch questions
$qstmt = $connect->prepare("SELECT Id, QuestionText, OptionA, OptionB, OptionC, OptionD, CorrectAnswer FROM onlinequestions WHERE ActivityId = ?");
$qstmt->bind_param("i", $activityId);
$qstmt->execute();
$qresult = $qstmt->get_result();

$questions = [];
while ($row = $qresult->fetch_assoc()) $questions[] = $row;
$qstmt->close();
?>

<head>
    <title><?php echo htmlspecialchars($activity['Title']); ?> - Questions</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/mathquill/build/mathquill.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/mathquill/build/mathquill.js"></script>
    <script>
        var MQ = MathQuill.getInterface(2);
    </script>
</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
<?php include_once(TUTOR_PATH . "/../partials/header.php"); ?> 
<?php include_once(TUTOR_PATH . "/../partials/mainsidebar.php"); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?php echo htmlspecialchars($activity['Title']); ?> - Questions.  <small class="text-muted">View Only – You cannot edit from this page</small></h1>
        

    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!-- Activity Details -->
                <div class="box box-primary">
                  <div class="box-header">
                    <h3 class="box-title">Activity Details</h3>
                  </div>
                  <div class="box-body">

                      <div class="row">
                        <!-- Title -->
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($activity['Title']); ?>" readonly>
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
                            <textarea class="form-control" rows="3" readonly><?php echo htmlspecialchars($activity['Instructions']); ?></textarea>
                          </div>
                        </div>

                        
                        <!-- Image -->
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Uploaded Image</label><br>
                              <?php 
                                  if (!empty($imagepath)) {
                                      $imageURL = (strpos($imagepath, 'http') === 0) ? $imagepath : QUIZ_IMAGES_URL . '/' . basename($imagepath);
                                      $imageFile = QUIZ_IMAGES_PATH . '/' . basename($imagepath);
                                      if (file_exists($imageFile)): ?>

                                          <div class="text-center" style="margin-bottom:10px;">
                                              <img src="<?=htmlspecialchars($imageURL)?>" 
                                                  alt="Activity Image" 
                                                  class="img-responsive" 
                                                  style="max-width:100%; max-height:150px; height:auto; margin:0 auto;">
                                          </div>
                              <?php 
                                      endif;
                                  } 
                              ?>
                          </div>
                        </div>

                        <!-- Memo -->
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Memo</label><br>
                            <?php if(!empty($activity['MemoPath']) && file_exists($activity['MemoPath'])): ?>
                              <a href="<?php echo htmlspecialchars($activity['MemoPath']); ?>" target="_blank">View Memo</a>
                            <?php else: ?>
                              <p>No memo uploaded</p>
                            <?php endif; ?>
                          </div>
                        </div>
                      </div>

                  </div>
                </div>

                <!-- Questions -->
                <div class="box box-default">


                  <!-- Inside the question loop -->
                  <div class="questions-grid">
                  <?php foreach ($questions as $index => $q): ?>
                      <div class="question-card">
                          <h4>Question <?php echo $index+1; ?></h4>
                          
                          <div class="math-box multi-line">
                            <div class="question-field"></div>
                            <input type="hidden" class="question-latex" value="<?php echo htmlspecialchars($q['QuestionText']); ?>">
                          </div>

                          <div class="options">
                              <?php foreach(['A','B','C','D'] as $opt): ?>
                              <div class="option">
                                  <span class="option-label"><?php echo $opt; ?>.</span>
                                  <div class="math-box multi-line option-field"></div>
                                  <input type="hidden" class="option-latex" value="<?php echo htmlspecialchars($q['Option'.$opt]); ?>">
                              </div>
                              <?php endforeach; ?>
                          </div>

                          <!-- Correct Answer -->
                          <div style="margin-top:10px; font-weight:600; color:green;">
                              Correct Answer: <?php echo htmlspecialchars($q['CorrectAnswer']); ?>
                          </div>
                      </div>
                  <?php endforeach; ?>
                  </div>

                </div>
            </div>
        </div>
    </section>
</div>

<div class="control-sidebar-bg"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Questions
    document.querySelectorAll('.question-field').forEach(function(box){
        var hidden = box.nextElementSibling;
        var mq = MQ.StaticMath(box);  // read-only
        mq.latex(hidden.value);
    });

    // Options
    document.querySelectorAll('.option-field').forEach(function(box){
        var hidden = box.nextElementSibling;
        var mq = MQ.StaticMath(box);  // read-only
        mq.latex(hidden.value);
    });
});
</script>

<style>
.questions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.question-card {
    background: #fff;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.question-card h4 {
    margin-bottom: 10px;
    font-weight: 600;
}

.options {
    margin-top: 10px;
}

.option {
    display: flex;
    align-items: flex-start;
    margin-bottom: 8px;
}

.option-label {
    font-weight: 600;
    margin-right: 8px;
    min-width: 20px;
}

.math-box {
    min-height: 50px;
    padding: 8px;
    font-size: 16px;
    line-height: 1.5;
    max-width: 100%;
    background: #f7f7f7;
    border-radius: 4px;
    white-space: normal;
    overflow-wrap: break-word;
    word-break: break-word;
}

.math-box .mq-math-mode {
    display: inline-block !important;
    white-space: normal !important;
}

.math-box .mq-root-block {
    display: block !important;
    white-space: normal !important;
    word-break: break-word !important;
    overflow-wrap: break-word !important;
}
</style>
</body>
</html>