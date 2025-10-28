
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

// Validate GET parameters
if (!isset($_GET['activityId']) || !is_numeric($_GET['activityId'])) {
    die("Invalid activity ID.");
}
if (!isset($_GET['classId']) || !is_numeric($_GET['classId'])) {
    die("Invalid class ID.");
}

$activityId = intval($_GET['activityId']);
$classId = intval($_GET['classId']);
$userId = $_SESSION['user_id']; // logged-in learner
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

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
<?php include_once(LEARNER_PATH . "/../partials/header.php"); ?>
<?php include_once(LEARNER_PATH . "/../partials/mainsidebar.php"); ?>

<!-- SweetAlert popups -->
<?php if (isset($_GET['alreadysubmitted']) && $_GET['alreadysubmitted'] == 1): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            <small><?=htmlspecialchars($activity['Title'])?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?=htmlspecialchars($activity['Topic'])?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <!-- Activity Info -->
            <div class="col-xs-12">
                <div class="box box-solid box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?=htmlspecialchars($activity['Title'])?></h3>
                    </div>
                    <div class="box-body">
                        <p>
                            <strong>Subject:</strong> <?=htmlspecialchars($subjectName)?> &nbsp;|&nbsp;
                            <strong>Grade:</strong> <?=htmlspecialchars($activity['Grade'])?> &nbsp;|&nbsp;
                            <strong>Topic:</strong> <?=htmlspecialchars($activity['Topic'])?> &nbsp;|&nbsp;
                            <strong>Due Date:</strong> <?=htmlspecialchars($activity['DueDate'])?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Homework Form -->
            <div class="col-xs-12">
                <form action="submithomework.php" method="POST">
                    <input type="hidden" name="activityId" value="<?=$activityId?>">
                    <input type="hidden" name="classId" value="<?=$classId?>">
                    <input type="hidden" name="subjectName" value="<?=htmlspecialchars($subjectName)?>">

                    <!-- Instructions -->
                    <div class="col-xs-12" style="margin-bottom:15px;">
                        <div class="box box-primary">
                            <div class="box-body">
                                <?php if (!empty($activity['ImagePath']) && file_exists($activity['ImagePath'])): ?>
                                    <div class="text-center" style="margin-bottom:10px;">
                                        <img src="<?=htmlspecialchars($activity['ImagePath'])?>" alt="Activity Image" class="img-responsive" style="max-height:180px; margin:0 auto;">
                                    </div>
                                <?php endif; ?>
                                <h4>Instructions</h4>
                                <p><?=nl2br(htmlspecialchars($activity['Instructions']))?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Questions -->
                    <?php foreach ($questions as $index => $question): ?>
                    <div class="col-md-6 col-sm-12" style="margin-bottom:15px;">
                        <div class="box box-primary">
                            <div class="box-body">
                                <p><strong>Q<?=$index + 1?>:</strong> <?=htmlspecialchars($question['QuestionText'])?></p>
                                <ul class="list-unstyled">
                                    <li><label><input type="radio" name="answers[<?=$question['id']?>]" value="A" required> A. <?=htmlspecialchars($question['OptionA'])?></label></li>
                                    <li><label><input type="radio" name="answers[<?=$question['id']?>]" value="B"> B. <?=htmlspecialchars($question['OptionB'])?></label></li>
                                    <li><label><input type="radio" name="answers[<?=$question['id']?>]" value="C"> C. <?=htmlspecialchars($question['OptionC'])?></label></li>
                                    <li><label><input type="radio" name="answers[<?=$question['id']?>]" value="D"> D. <?=htmlspecialchars($question['OptionD'])?></label></li>
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

<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>
</body>
</html>
