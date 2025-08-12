<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../../common/pages/login.php");
  exit();
}
include(__DIR__ . "/../../common/partials/head.php");
include(__DIR__ . "/../../partials/connect.php");

$subject = isset($_POST['subject']) ? trim($_POST['subject']) : (isset($_GET['subject']) ? trim($_GET['subject']) : '');
$grade = isset($_POST['grade']) ? trim($_POST['grade']) : (isset($_GET['grade']) ? trim($_GET['grade']) : '');
$level = isset($_POST['level']) ? trim($_POST['level']) : (isset($_GET['level']) ? trim($_GET['level']) : '');
$chapter = isset($_POST['chapter']) ? trim($_POST['chapter']) : (isset($_GET['chapter']) ? trim($_GET['chapter']) : '');
$selectedQuestionId = isset($_GET['question']) ? intval($_GET['question']) : 0;

$alert = null; // Will hold sweetalert info ['type'=>'success'|'error', 'message'=>string]

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['addQuestion'])) {
        // Add question logic
        $description = trim($_POST['description'] ?? '');
        $optionA = trim($_POST['optionA'] ?? '');
        $optionB = trim($_POST['optionB'] ?? '');
        $optionC = trim($_POST['optionC'] ?? '');
        $optionD = trim($_POST['optionD'] ?? '');
        $answer = trim($_POST['answer'] ?? '');

        if ($subject && $grade && $level && $chapter && $description && $optionA && $optionB && $optionC && $optionD && $answer) {
            // Insert new question with prepared stmt
            $stmt = $connect->prepare("INSERT INTO practicequestions (Text, OptionA, OptionB, OptionC, OptionD, Answer, LevelId, Chapter, SubjectName, GradeName) VALUES (?, ?, ?, ?, ?, ?, (SELECT Id FROM level WHERE LevelName = ? LIMIT 1), ?, ?, ?)");
            $stmt->bind_param("ssssssssss", $description, $optionA, $optionB, $optionC, $optionD, $answer, $level, $chapter, $subject, $grade);

            if ($stmt->execute()) {
                $alert = ['type' => 'success', 'message' => 'Question added successfully!'];
                $selectedQuestionId = $stmt->insert_id;
            } else {
                $alert = ['type' => 'error', 'message' => 'Failed to add question: ' . $stmt->error];
            }
            $stmt->close();
        } else {
            $alert = ['type' => 'error', 'message' => 'Please fill in all fields to add a question.'];
        }
    }

    if (isset($_POST['editQuestion'])) {
        // Edit question logic
        $editDescription = trim($_POST['editDescription'] ?? '');
        $editOptionA = trim($_POST['editOptionA'] ?? '');
        $editOptionB = trim($_POST['editOptionB'] ?? '');
        $editOptionC = trim($_POST['editOptionC'] ?? '');
        $editOptionD = trim($_POST['editOptionD'] ?? '');
        $editAnswer = trim($_POST['editAnswer'] ?? '');
        $editQuestionId = intval($_POST['editQuestionId'] ?? 0);

        if ($editQuestionId > 0 && $editDescription && $editOptionA && $editOptionB && $editOptionC && $editOptionD && $editAnswer) {
            $stmt = $connect->prepare("UPDATE practicequestions SET Text=?, OptionA=?, OptionB=?, OptionC=?, OptionD=?, Answer=? WHERE Id=?");
            $stmt->bind_param("ssssssi", $editDescription, $editOptionA, $editOptionB, $editOptionC, $editOptionD, $editAnswer, $editQuestionId);

            if ($stmt->execute()) {
                $alert = ['type' => 'success', 'message' => 'Question updated successfully!'];
                $selectedQuestionId = $editQuestionId;
            } else {
                $alert = ['type' => 'error', 'message' => 'Failed to update question: ' . $stmt->error];
            }
            $stmt->close();
        } else {
            $alert = ['type' => 'error', 'message' => 'Please fill in all fields to update the question.'];
        }
    }
}

// Fetch question IDs for buttons
$questionIds = [];
if ($subject && $grade && $level && $chapter) {
    $stmt = $connect->prepare("SELECT Id FROM practicequestions WHERE SubjectName = ? AND GradeName = ? AND LevelId = (SELECT Id FROM level WHERE LevelName = ?) AND Chapter = ? ORDER BY Id ASC");
    $stmt->bind_param("ssss", $subject, $grade, $level, $chapter);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $questionIds[] = $row['Id'];
    }
    $stmt->close();
}

// Fetch selected question details
$editQuestion = null;
if ($selectedQuestionId > 0) {
    $stmt = $connect->prepare("SELECT * FROM practicequestions WHERE Id = ?");
    $stmt->bind_param("i", $selectedQuestionId);
    $stmt->execute();
    $res = $stmt->get_result();
    $editQuestion = $res->fetch_assoc() ?: null;
    $stmt->close();
}

?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if ($alert): ?>
      <script>
        $(function() {
          Swal.fire({
            icon: '<?= $alert['type'] ?>',
            title: '<?= $alert['type'] === 'success' ? 'Success!' : 'Error!' ?>',
            text: '<?= addslashes($alert['message']) ?>',
            timer: 3500,
            timerProgressBar: true,
            showConfirmButton: false
          });
        });
      </script>
    <?php endif; ?>

    <section class="content-header">
      <h1>Create Questions <small>Create and edit practice questions.</small></h1>
      <ol class="breadcrumb">
        <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Practice Questions</li>
      </ol>
    </section>

    <section class="content">

      <!-- Questions Created -->
      <div class="box box-solid" style="border-top: 3px solid #605ca8;">
          <div class="box-header with-border" style="background-color:#f3edff;">
              <h3 class="box-title" style="color:#605ca8;">
                  <i class="fa fa-folder-open"></i> Questions Created
              </h3>
          </div>
          <div class="box-body">
              <div class="row" style="margin: 0;">
                  <div class="col-sm-3"><strong>Subject:</strong> <?= htmlspecialchars($subject) ?></div>
                  <div class="col-sm-3"><strong>Chapter:</strong> <?= htmlspecialchars($chapter) ?></div>
                  <div class="col-sm-3"><strong>Grade:</strong> <?= htmlspecialchars($grade) ?></div>
                  <div class="col-sm-3"><strong>Level:</strong> <?= htmlspecialchars($level) ?></div>
              </div>
          </div>
          <div class="box-body" style="background-color:#ffffff;">
              <div class="btn-group" role="group" style="overflow-x:auto; white-space:nowrap; width:100%;">
                  <?php if (count($questionIds) > 0): ?>
                    <?php foreach ($questionIds as $qid): ?>
                      <a href="?subject=<?= urlencode($subject) ?>&grade=<?= urlencode($grade) ?>&chapter=<?= urlencode($chapter) ?>&level=<?= urlencode($level) ?>&question=<?= $qid ?>" class="btn btn-default <?= ($qid == $selectedQuestionId) ? 'active' : '' ?>">
                        <?= $qid ?>
                      </a>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <span>No questions created yet.</span>
                  <?php endif; ?>
              </div>
              
              <!-- Upload Memo PDF Form -->
              <hr>
              <form action="upload_memo.php" method="POST" enctype="multipart/form-data" style="margin-top: 10px;">
                <div class="form-group">
                    <label>Upload Memo (PDF)</label>
                    <div class="row">
                        <div class="col-sm-9">
                            <input type="file" name="memo_pdf" class="form-control" accept="application/pdf" required>
                            <input type="hidden" name="subject" value="<?= htmlspecialchars($subject) ?>">
                            <input type="hidden" name="grade" value="<?= htmlspecialchars($grade) ?>">
                            <input type="hidden" name="level" value="<?= htmlspecialchars($level) ?>">
                            <input type="hidden" name="chapter" value="<?= htmlspecialchars($chapter) ?>">
                        </div>
                        <div class="col-sm-3 text-right">
                            <button type="submit" class="btn btn-default btn-block">
                                <i class="fa fa-upload"></i> Upload Memo
                            </button>
                        </div>
                    </div>
                </div>
              </form>

          </div>
      </div>

      <div class="row">
        <!-- Add New Question -->
        <div class="col-md-6">
          <div class="box box-primary" style="border-top: 3px solid #3c8dbc;">
            <div class="box-header with-border" style="background-color:#f0f8ff;">
              <h3 class="box-title" style="color:#3c8dbc;">
                <i class="fa fa-plus-circle"></i> Add New Question
              </h3>
            </div>
            <div class="box-body" style="background-color:#ffffff;">
              <form id="addQuestionForm" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="subject" value="<?= htmlspecialchars($subject) ?>">
                <input type="hidden" name="grade" value="<?= htmlspecialchars($grade) ?>">
                <input type="hidden" name="level" value="<?= htmlspecialchars($level) ?>">
                <input type="hidden" name="chapter" value="<?= htmlspecialchars($chapter) ?>">
                <input type="hidden" name="addQuestion" value="1">

                <div class="form-group">
                  <label for="questionText">Question</label>
                  <textarea name="description" id="questionText" class="form-control" rows="2" placeholder="Type your question here..." required></textarea>
                </div>

                <div class="row">
                  <div class="col-xs-6 form-group">
                    <label>Option A</label>
                    <input type="text" name="optionA" class="form-control" required>
                  </div>
                  <div class="col-xs-6 form-group">
                    <label>Option B</label>
                    <input type="text" name="optionB" class="form-control" required>
                  </div>
                  <div class="col-xs-6 form-group">
                    <label>Option C</label>
                    <input type="text" name="optionC" class="form-control" required>
                  </div>
                  <div class="col-xs-6 form-group">
                    <label>Option D</label>
                    <input type="text" name="optionD" class="form-control" required>
                  </div>

                  <div class="col-xs-6 form-group">
                    <label>Set Answer</label>
                    <select name="answer" class="form-control" required>
                        <option value="">Select correct option</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                    </select>
                  </div>

                  <div class="col-xs-6 form-group">
                    <label>Upload Image (optional)</label>
                    <input type="file" name="question_image" class="form-control" accept="image/*">
                  </div>
                </div>

                <div class="text-right">
                  <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> Save Question
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Edit Question -->
        <div class="col-md-6">
          <div class="box box-info" style="border-top: 3px solid #00c0ef;">
            <div class="box-header with-border" style="background-color:#d9f0fb;">
              <h3 class="box-title" style="color:#0073b7;">
                <i class="fa fa-pencil"></i> Edit Question
              </h3>
            </div>
            <div class="box-body" style="background-color:#ffffff;">
              <?php if (!$editQuestion): ?>
                <p>No records found.</p>
              <?php else: ?>
                <form id="editQuestionForm" method="POST" enctype="multipart/form-data">
                  <input type="hidden" name="editQuestionId" value="<?= $editQuestion['Id'] ?>">
                  <input type="hidden" name="subject" value="<?= htmlspecialchars($subject) ?>">
                  <input type="hidden" name="grade" value="<?= htmlspecialchars($grade) ?>">
                  <input type="hidden" name="level" value="<?= htmlspecialchars($level) ?>">
                  <input type="hidden" name="chapter" value="<?= htmlspecialchars($chapter) ?>">
                  <input type="hidden" name="editQuestion" value="1">

                  <div class="form-group">
                    <label>Selected Question</label>
                    <textarea class="form-control" style="background-color:#f3edff;" id="editQuestionText" name="editDescription" rows="2" required><?= htmlspecialchars($editQuestion['Text']) ?></textarea>
                  </div>

                  <div class="row">
                    <div class="col-xs-6 form-group">
                      <label>Option A</label>
                      <input type="text" name="editOptionA" class="form-control" style="background-color:#f3edff;" required value="<?= htmlspecialchars($editQuestion['OptionA']) ?>">
                    </div>
                    <div class="col-xs-6 form-group">
                      <label>Option B</label>
                      <input type="text" name="editOptionB" class="form-control" style="background-color:#f3edff;" required value="<?= htmlspecialchars($editQuestion['OptionB']) ?>">
                    </div>
                    <div class="col-xs-6 form-group">
                      <label>Option C</label>
                      <input type="text" name="editOptionC" class="form-control" style="background-color:#f3edff;" required value="<?= htmlspecialchars($editQuestion['OptionC']) ?>">
                    </div>
                    <div class="col-xs-6 form-group">
                      <label>Option D</label>
                      <input type="text" name="editOptionD" class="form-control" style="background-color:#f3edff;" required value="<?= htmlspecialchars($editQuestion['OptionD']) ?>">
                    </div>
                    <div class="col-xs-6 form-group">
                      <label>Correct Answer</label>
                      <select name="editAnswer" class="form-control" style="background-color:#f3edff;" required>
                        <option value="">Select correct option</option>
                        <option value="A" <?= $editQuestion['Answer'] === 'A' ? 'selected' : '' ?>>A</option>
                        <option value="B" <?= $editQuestion['Answer'] === 'B' ? 'selected' : '' ?>>B</option>
                        <option value="C" <?= $editQuestion['Answer'] === 'C' ? 'selected' : '' ?>>C</option>
                        <option value="D" <?= $editQuestion['Answer'] === 'D' ? 'selected' : '' ?>>D</option>
                      </select>
                    </div>

                    <div class="col-xs-6 form-group">
                      <label>Upload Image (optional)</label>
                      <input type="file" name="question_image" class="form-control" accept="image/*">
                    </div>
                  </div>

                  <div class="text-right">
                    <button type="submit" class="btn btn-info">
                      <i class="fa fa-check"></i> Update
                    </button>
                  </div>
                </form>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

</body>
</html>
