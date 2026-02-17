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


// Grab alert from session if any, then clear it xxx
$alert = $_SESSION['alert'] ?? null;
unset($_SESSION['alert']);

// Get selected question ID from query (if any)
$editQuestionId = isset($_GET['question']) ? intval($_GET['question']) : 0;
$editQuestion = null;

if ($editQuestionId > 0) {
    $stmt = $connect->prepare("SELECT * FROM practicequestions WHERE Id = ?");
    $stmt->bind_param("i", $editQuestionId);
    $stmt->execute();
    $res = $stmt->get_result();
    $editQuestion = $res->fetch_assoc();
    $stmt->close();
}

// Fetch all questions grouped by Grade, Subject, Chapter, Level
$query = "
    SELECT 
        GradeName, SubjectName, Chapter, LevelName, Id, Text, ImagePath
    FROM 
        practicequestions 
    JOIN 
        level ON practicequestions.LevelId = level.Id
    ORDER BY 
        GradeName, SubjectName, Chapter, LevelName, Id
";

$result = $connect->query($query);

$questionsGrouped = [];
while ($row = $result->fetch_assoc()) {
    $questionsGrouped[$row['GradeName']][$row['SubjectName']][$row['Chapter']][$row['LevelName']][] = [
        'Id' => $row['Id'],
        'Text' => $row['Text'],
        'ImagePath' => $row['ImagePath']
    ];
}
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
<?php include_once(TUTOR_PATH . "/../partials/header.php"); ?> 
<?php include_once(TUTOR_PATH . "/../partials/mainsidebar.php"); ?>


  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        Edit Practice Questions
        <small>Pick up where you left off — browse and update your questions</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="tutorindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Edit Practice Questions</li>
      </ol>
    </section>

    <section class="content">
      <?php if ($alert): ?>
        <div class="alert alert-<?= $alert['type'] === 'success' ? 'success' : 'danger' ?>">
          <?= htmlspecialchars($alert['message']) ?>
        </div>
      <?php endif; ?>

      <div class="row">

        <!-- Sidebar: Questions grouped -->
        <div class="col-md-4" style="max-height: 700px; overflow-y: auto; border-right: 1px solid #ddd;">
          <h4>All Questions</h4>
          <?php if (empty($questionsGrouped)): ?>
            <p>No questions found.</p>
          <?php else: ?>
            <div class="panel-group" id="accordion">
            <?php foreach ($questionsGrouped as $grade => $subjects): ?>
              <div class="panel panel-default">
                <div class="panel-heading" style="background:#f7f7f7;">
                  <h4 class="panel-title">
                    <a data-toggle="collapse" href="#grade-<?= md5($grade) ?>" aria-expanded="false" class="collapsed">
                      Grade: <?= htmlspecialchars($grade) ?>
                    </a>
                  </h4>
                </div>
                <div id="grade-<?= md5($grade) ?>" class="panel-collapse collapse">
                  <div class="panel-body">
                    <?php foreach ($subjects as $subject => $chapters): ?>
                      <strong><?= htmlspecialchars($subject) ?></strong>
                      <ul>
                        <?php foreach ($chapters as $chapter => $levels): ?>
                          <li>
                            <?= htmlspecialchars($chapter) ?>
                            <ul>
                              <?php foreach ($levels as $level => $questions): ?>
                                <li>
                                  <em><?= htmlspecialchars($level) ?></em>
                                  <ul>
                                    <?php foreach ($questions as $q): ?>
                                      <li>
                                        <a href="?question=<?= $q['Id'] ?>" style="<?= ($q['Id'] == $editQuestionId) ? 'font-weight:bold; color:#3c8dbc;' : '' ?>">
                                          <?= htmlspecialchars(mb_strimwidth($q['Text'], 0, 40, '...')) ?>
                                          <?php if ($q['ImagePath']): ?>
                                            <i class="fa fa-image" title="Has Image" style="color:#888; margin-left:5px;"></i>
                                          <?php endif; ?>
                                        </a>
                                      </li>
                                    <?php endforeach; ?>
                                  </ul>
                                </li>
                              <?php endforeach; ?>
                            </ul>
                          </li>
                        <?php endforeach; ?>
                      </ul>
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- Main content: Edit form -->
        <div class="col-md-8">
          <?php if (!$editQuestion): ?>
            <div class="callout callout-info">
              <h4>No question selected</h4>
              <p>Please select a question from the list on the left to edit.</p>
            </div>
          <?php else: ?>
            <h3>Edit Question</h3>
            <form method="POST" action="editpracticequestions_submit.php" enctype="multipart/form-data" class="form-horizontal">
              <input type="hidden" name="editQuestionId" value="<?= $editQuestion['Id'] ?>">

              <div class="form-group">
                <label class="control-label col-sm-3">Question Text</label>
                <div class="col-sm-9">
                  <textarea name="editDescription" class="form-control" rows="3" required><?= htmlspecialchars($editQuestion['Text']) ?></textarea>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-3">Option A</label>
                <div class="col-sm-9">
                  <input type="text" name="editOptionA" value="<?= htmlspecialchars($editQuestion['OptionA']) ?>" class="form-control" required>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-3">Option B</label>
                <div class="col-sm-9">
                  <input type="text" name="editOptionB" value="<?= htmlspecialchars($editQuestion['OptionB']) ?>" class="form-control" required>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-3">Option C</label>
                <div class="col-sm-9">
                  <input type="text" name="editOptionC" value="<?= htmlspecialchars($editQuestion['OptionC']) ?>" class="form-control" required>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-3">Option D</label>
                <div class="col-sm-9">
                  <input type="text" name="editOptionD" value="<?= htmlspecialchars($editQuestion['OptionD']) ?>" class="form-control" required>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-3">Correct Answer</label>
                <div class="col-sm-9">
                  <select name="editAnswer" class="form-control" required>
                    <option value="">Select correct option</option>
                    <option value="A" <?= $editQuestion['Answer'] === 'A' ? 'selected' : '' ?>>A</option>
                    <option value="B" <?= $editQuestion['Answer'] === 'B' ? 'selected' : '' ?>>B</option>
                    <option value="C" <?= $editQuestion['Answer'] === 'C' ? 'selected' : '' ?>>C</option>
                    <option value="D" <?= $editQuestion['Answer'] === 'D' ? 'selected' : '' ?>>D</option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-3">Upload Image (optional)</label>
                <div class="col-sm-9">
                  <input type="file" name="question_image" accept="image/*" class="form-control">
                  <?php if (!empty($editQuestion['ImagePath'])): ?>
                    <p class="help-block">Current Image:</p>
                    <img src="<?= htmlspecialchars($editQuestion['ImagePath']) ?>" alt="Question Image" style="max-width: 150px; max-height: 150px; border: 1px solid #ddd; padding: 3px;">
                  <?php endif; ?>
                </div>
              </div>

              <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                  <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> Update Question
                  </button>
                </div>
              </div>
            </form>
          <?php endif; ?>
        </div>

      </div>
    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>

<!-- Bootstrap JS (for accordion collapse) -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</body>
</html>
