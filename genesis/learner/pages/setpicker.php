<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../common/partials/head.php");
include(__DIR__ . "/../../partials/connect.php");

// Ensure subjectId exists
if (!isset($_GET['subjectId']) || !is_numeric($_GET['subjectId'])) {
    die("Invalid subject.");
}

$learnerId = $_SESSION['user_id'];
$subjectId = intval($_GET['subjectId']);

// 1) Get SubjectName + GradeName
$subjectInfoSql = "
    SELECT s.SubjectName, g.GradeName
    FROM subjects s
    JOIN grades g ON s.GradeId = g.GradeId
    WHERE s.SubjectId = ?
    LIMIT 1
";
$subjectStmt = $connect->prepare($subjectInfoSql);
if (!$subjectStmt) {
    die("Prepare failed (subject info): " . $connect->error);
}
$subjectStmt->bind_param("i", $subjectId);
$subjectStmt->execute();
$subjectRes = $subjectStmt->get_result();
if ($subjectRes->num_rows === 0) {
    die("Subject not found.");
}
$subjectInfo = $subjectRes->fetch_assoc();
$subjectStmt->close();

$subjectName = $subjectInfo['SubjectName'];
$gradeName   = $subjectInfo['GradeName'];

// 2) Fetch chapters + levels for this subject/grade
$chaptersSql = "
    SELECT pq.Chapter, pq.LevelId
    FROM practicequestions pq
    WHERE pq.SubjectName = ?
      AND pq.GradeName = ?
    ORDER BY pq.Chapter, pq.LevelId
";
$chaptersStmt = $connect->prepare($chaptersSql);
if (!$chaptersStmt) {
    die("Prepare failed (chapters): " . $connect->error);
}
$chaptersStmt->bind_param("ss", $subjectName, $gradeName);
$chaptersStmt->execute();
$chaptersRes = $chaptersStmt->get_result();

$chapters = []; 
while ($row = $chaptersRes->fetch_assoc()) {
    $chapter = $row['Chapter'];
    $levelId = $row['LevelId'];

    if (!isset($chapters[$chapter])) {
        $chapters[$chapter] = ['Easy' => null, 'Medium' => null, 'Hard' => null];
    }

    if ($levelId == 1) $chapters[$chapter]['Easy'] = $levelId;
    if ($levelId == 2) $chapters[$chapter]['Medium'] = $levelId;
    if ($levelId == 3) $chapters[$chapter]['Hard'] = $levelId;
}
$chaptersStmt->close();

// 3) Fetch learner eligibility per chapter
$learnerLevelsStmt = $connect->prepare("
    SELECT LevelId, ChapterName, Complete
    FROM learnerlevel
    WHERE LearnerId = ?
");
$learnerLevelsStmt->bind_param("i", $learnerId);
$learnerLevelsStmt->execute();
$res = $learnerLevelsStmt->get_result();
$learnerLevels = [];
while($row = $res->fetch_assoc()){
    $learnerLevels[$row['ChapterName']][$row['LevelId']] = $row['Complete'];
}
$learnerLevelsStmt->close();


// Helper: check if learner is eligible for a level
function isEligible($levelId, $chapterName, $learnerLevels){
    if($levelId == 1) return true; // Easy always eligible
    $prevLevel = $levelId - 1;
    return !empty($learnerLevels[$chapterName][$prevLevel]) && $learnerLevels[$chapterName][$prevLevel] == 1;
}

?>
<!DOCTYPE html>
<html>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <?php include(__DIR__ . "/../partials/header.php"); ?>
    <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Practice Questions
                <small>Select a chapter to practice</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Practice Picker</li>
            </ol>
        </section>

        <section class="content">
            <div class="box box-primary" style="border-top: 3px solid #3c8dbc;">
                <div class="box-header with-border">
                    <h3 class="box-title" style="color:#3c8dbc;">
                        <i class="fa fa-cogs"></i>
                        <?php echo htmlspecialchars($subjectName) . " — " . htmlspecialchars($gradeName); ?>
                    </h3>
                </div>

                <div class="box-body">
                    <div class="row">
                        <!-- Left: Chapters table -->
                        <div class="col-md-8">
                            <div class="box box-solid">
                                <div class="box-body">
                                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                        <table id="example1" class="table table-bordered table-hover table-condensed">
                                            <thead>
                                                <tr>
                                                    <th>Chapter</th>
                                                    <th class="text-center">Easy</th>
                                                    <th class="text-center">Medium</th>
                                                    <th class="text-center">Hard</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php if (empty($chapters)): ?>
                                                <tr>
                                                    <td colspan="4" class="text-center">No practice sets found for this subject/grade.</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($chapters as $chapterName => $levels): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($chapterName); ?></td>

                                                        <?php foreach(['Easy'=>1,'Medium'=>2,'Hard'=>3] as $levelName=>$lvlId): ?>
                                                        <td class="text-center">
                                                            <?php if(!empty($levels[$levelName])): ?>
                                                                <?php if(isEligible($lvlId,$chapterName,$learnerLevels)): ?>
                                                                    <a href="training.php?grade=<?= urlencode($gradeName); ?>&subject=<?= urlencode($subjectName); ?>&sid=<?= $subjectId; ?>&chapter=<?= urlencode($chapterName); ?>&level=<?= $levels[$levelName]; ?>"

                                                                    class="btn btn-xs btn-<?= $levelName=='Easy'?'success':($levelName=='Medium'?'warning':'danger'); ?>">
                                                                        <i class="fa fa-play"></i> Practice
                                                                    </a>
                                                                    <?php if(!empty($learnerLevels[$chapterName][$lvlId]) && $learnerLevels[$chapterName][$lvlId] == 1): ?>
                                                                        <a href="memo.php?grade=<?= urlencode($gradeName); ?>&subject=<?= urlencode($subjectName); ?>&chapter=<?= urlencode($chapterName); ?>&level=<?= $levels[$levelName]; ?>"
                                                                        class="btn btn-xs btn-info" title="View Memo">
                                                                            <i class="fa fa-file-text"></i> Memo
                                                                        </a>
                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    <button class="btn btn-xs btn-default" disabled>
                                                                        <i class="fa fa-lock"></i> Locked
                                                                    </button>
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                <span class="text-muted">N/A</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <?php endforeach; ?>



                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Tips panel -->
                        <div class="col-md-4">
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Tips for Answering the Questions</h3>
                                </div>
                                <div class="box-body">
                                    <ul>
                                        <li>Read each question carefully before selecting an option.</li>
                                        <li>Start with the Easy level to warm up, then move to Medium and Hard.</li>
                                        <li>Review explanations (if available) to learn from mistakes.</li>
                                        <li>Track your weak chapters and revisit them regularly.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /.row -->
                </div> <!-- /.box-body -->
            </div> <!-- /.box -->
        </section>
    </div>

    <div class="control-sidebar-bg"></div>
</div>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

<!-- DataTables -->
<script>
  $(function () {
    $('#example1').DataTable({
      paging: true,
      lengthChange: true,
      searching: true,
      ordering: true,
      info: true,
      autoWidth: false,
      responsive: true
    });
  });
</script>
</body>
</html>
