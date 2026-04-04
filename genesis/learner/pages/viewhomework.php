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

<!DOCTYPE html> 
<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/mathquill/build/mathquill.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/mathquill/build/mathquill.js"></script>
    <script>
        var MQ = MathQuill.getInterface(2);
    </script>

</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
<?php include_once(LEARNER_PATH . "/../partials/header.php"); ?>
<?php include_once(LEARNER_PATH . "/../partials/mainsidebar.php"); ?>
<?php

// Validate GET parameters
if (!isset($_GET['activityId']) || !is_numeric($_GET['activityId'])) {
    die("Invalid activity ID.");
}
if (!isset($_GET['classId']) || !is_numeric($_GET['classId'])) {
    die("Invalid class ID.");
}

$activityId = intval($_GET['activityId']);
$classId = intval($_GET['classId']);
$userId = $_SESSION['user_id']; 
$subjectName = isset($_GET['subject']) ? $_GET['subject'] : '';


// Fetch activity details
$stmt = $connect->prepare("
    SELECT a.SubjectId, a.Grade, a.Topic, a.Title, a.Instructions, 
           a.TotalMarks, aa.DueDate, a.CreatedAt, a.ImagePath
    FROM onlineactivities a
    INNER JOIN onlineactivitiesassignments aa 
        ON a.Id = aa.OnlineActivityId
    WHERE a.Id = ? AND aa.ClassID = ?
    LIMIT 1
");
$stmt->bind_param("ii", $activityId, $classId);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows === 0) {
    die("Activity not found for your class.");
}

$activity = $result->fetch_assoc();
$stmt->close();

//the below data was getting lost after pulling it from the db
$grade =  $activity['Grade'];
$title =  $activity['Title'];
$topic =  $activity['Topic'];
$instructions =  $activity['Instructions'];
$totalmarks =  $activity['TotalMarks'];
$duedate =  $activity['DueDate'];
$createdat =  $activity['CreatedAt'];
$imagepath =  $activity['ImagePath'];

// Fetch questions
$qstmt = $connect->prepare("
    SELECT id, QuestionText, OptionA, OptionB, OptionC, OptionD, CorrectAnswer
    FROM onlinequestions
    WHERE ActivityId = ?
");
$qstmt->bind_param("i", $activityId);
$qstmt->execute();
$qresult = $qstmt->get_result();

$questions = [];
while ($row = $qresult->fetch_assoc()) {
    $questions[] = $row;
}
$qstmt->close();


?>
<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>

<!-- SweetAlert popups -->
<?php if (isset($_GET['alreadysubmitted']) && $_GET['alreadysubmitted'] == 1): ?>
<script>
Swal.fire({
    icon: 'info',
    title: 'Already Completed',
    text: 'You have already submitted this homework.',
    showCancelButton: true,
    confirmButtonText: 'View Memo',
    cancelButtonText: 'Okay'
}).then((result) => {
    if (result.isConfirmed) {
        window.location.href = 'viewmemo.php?activityid=<?=$activityId?>&classId=<?=$classId?>&subject=<?=urlencode($subjectName)?>';
    } else {
        window.location.href = 'homework.php';
    }
});
</script>
<?php endif; ?>

<?php if (isset($_GET['submitted']) && $_GET['submitted'] == 1): ?>
<?php  
$score = intval($_GET['score']);
?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Homework Submitted',
    text: 'Score: <?=$score?>%',
    showCancelButton: true,
    confirmButtonText: 'View Memo',
    cancelButtonText: 'Go to Dashboard'
}).then((result) => {
    if (result.isConfirmed) {
        window.location.href = 'viewmemo.php?activityid=<?=$activityId?>&classId=<?=$classId?>&subject=<?=urlencode($subjectName)?>';
    } else {
        window.location.href = 'homework.php';
    }
});
</script>
<?php endif; ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Quiz Details
            <small><?=htmlspecialchars($title)?></small>
            
        </h1>
        <ol class="breadcrumb">
            <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?=htmlspecialchars($topic)?></li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <!-- Activity Info -->
            <div class="col-xs-12">
                <div class="box box-solid box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?=htmlspecialchars($title)?></h3>
                    </div>
                    <div class="box-body">
                        <p>
                            <strong>Subject:</strong> <?=htmlspecialchars($subjectName)?> &nbsp;|&nbsp;
                            <strong>Grade:</strong> <?=htmlspecialchars($grade)?> &nbsp;|&nbsp;
                            <strong>Topic:</strong> <?=htmlspecialchars($topic)?> &nbsp;|&nbsp;
                            <strong>Due Date:</strong> <?=htmlspecialchars($duedate)?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="col-md-12 col-sm-12 col-xs-12" style="margin-bottom:15px;">
                <div class="box box-primary">
                    <div class="box-body">
                        <h4>Instructions</h4>
                        <p><?=nl2br(htmlspecialchars($instructions))?></p>
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
            </div>

            <!-- Homework Form -->
           
            <div class="questions-table-wrapper">

            <form action="submithomework.php" method="POST">
                    <input type="hidden" name="activityId" value="<?=$activityId?>">
                    <input type="hidden" name="classId" value="<?=$classId?>">
                    <input type="hidden" name="subjectName" value="<?=htmlspecialchars($subjectName)?>">
                
                <table class="table table-borderless questions-table">
                    <tr>
                        <?php 
                            $colsPerRow = 2; 
                            $colCount = 0;

                            foreach ($questions as $index => $question): 
                                $colWidth = 100 / $colsPerRow;
                        ?>
                        <td class="question-td" style="width: <?= $colWidth ?>%; vertical-align: top; padding: 10px;">
                            <div class="question-card box box-primary">
                                <div class="box-body">
                                    <p><strong>Q<?= $index + 1 ?>:</strong></p>
                                    <div class="math-box multi-line question-field"></div>
                                    <input type="hidden" class="question-latex" value="<?=htmlspecialchars($question['QuestionText'])?>">

                                    <div class="options">
                                        <?php foreach(['A','B','C','D'] as $opt): ?>
                                    
                                        <div class="option">
                                            <label style="display: flex; align-items: flex-start; cursor: pointer;">
                                                <input type="radio" name="answers[<?=$question['id']?>]" value="<?= $opt ?>" required style="margin-right:8px;">
                                                <span class="option-label"><?= $opt ?>.</span>
                                                <div class="math-box multi-line option-field" style="flex:1;"></div>
                                                <input type="hidden" class="option-latex" value="<?=htmlspecialchars($question['Option'.$opt])?>">
                                            </label>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <?php 
                            $colCount++;
                            if ($colCount == $colsPerRow) {
                                echo "</tr><tr>";
                                $colCount = 0;
                            }
                        endforeach;

                        while ($colCount > 0 && $colCount < $colsPerRow) {
                            echo "<td></td>";
                            $colCount++;
                        }
                        ?>
                    </tr>
                </table>
            </div>

            <!-- Submit Button -->
 
                    <div class="col-xs-12" style="margin-top:10px;">
                        <button type="submit" class="btn btn-primary">Submit Homework</button>
                    </div>
            </form>

        </div>
    </section>
</div>

<div class="control-sidebar-bg"></div>
</div>

<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Questions
    document.querySelectorAll('.question-field').forEach(function(box){
        var hidden = box.nextElementSibling;
        var mq = MQ.StaticMath(box);
        mq.latex(hidden.value);
    });

    // Options
    document.querySelectorAll('.option-field').forEach(function(box){
        var hidden = box.nextElementSibling;
        var mq = MQ.StaticMath(box);
        mq.latex(hidden.value);
    });
});
</script>
<style>
/* Grid layout for questions */
.questions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

/* Individual question card */
.question-card {
    background: #fff;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    transition: transform 0.2s, box-shadow 0.2s;
}

.question-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
}

.question-card h4 {
    margin-bottom: 10px;
    font-weight: 600;
}

/* Options styling */
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

/* MathQuill boxes */
.math-box {
    min-height: 50px;
    padding: 8px;
    font-size: 16px;
    line-height: 1.5;
    max-width: 100%;
    background: #f7f7f7;
    border-radius: 4px;
    white-space: normal;        /* allow wrapping */
    overflow-wrap: break-word;  /* break long words */
    word-break: break-word;
}

/* MathQuill internals for proper wrapping */
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

@media (max-width: 767px) {
    .questions-table-wrapper table,
    .questions-table-wrapper tr,
    .questions-table-wrapper td {
        display: block !important;
        width: 100% !important;
        padding: 0 !important;
    }

    .question-td {
        margin-bottom: 15px;
    }
}
</style>

</body>
</html>
