<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../../common/pages/login.php");
  exit();
}
include(__DIR__ . "/../../common/partials/head.php");
include(__DIR__ . "/../../partials/connect.php");

/* ss
$subject = "Mathematics";
$grade = "Grade 10";
$level = "Intermediate(Medium)";
$chapter = "Algebra Basics";
*/ 

$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$grade = isset($_POST['grade']) ? trim($_POST['grade']) : '';
$level = isset($_POST['level']) ? trim($_POST['level']) : '';
$chapter = isset($_POST['chapter']) ? trim($_POST['chapter']) : '';

?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                  <?php for ($i = 1; $i <= 23; $i++): ?>
                      <a href="?question=<?= $i ?>" class="btn btn-default"><?= $i ?></a>
                  <?php endfor; ?>
                  <a href="?question=24" class="btn btn-default">24</a>
                  <a href="?question=25" class="btn btn-default">255</a>
              </div>
              
              <!-- Upload Memo PDF Form -->
              <hr>
              <form action="upload_memo.php" method="POST" enctype="multipart/form-data" style="margin-top: 10px;">
                <div class="form-group">
                    <label>Upload Memo (PDF)</label>
                    <div class="row">
                        <div class="col-sm-9">
                            <input type="file" name="memo_pdf" class="form-control" accept="application/pdf" required>
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
              <form id="editQuestionForm" method="POST">

                <div class="form-group">
                  <label>Selected Question</label>
                  <textarea class="form-control" style="background-color:#f3edff;" id="editQuestionText" name="editDescription" rows="2"></textarea>
                </div>

                <div class="row">
                  <div class="col-xs-6 form-group">
                    <label>Option A</label>
                    <input type="text" name="editOptionA" class="form-control" style="background-color:#f3edff;">
                  </div>
                  <div class="col-xs-6 form-group">
                    <label>Option B</label>
                    <input type="text" name="editOptionB" class="form-control" style="background-color:#f3edff;">
                  </div>
                  <div class="col-xs-6 form-group">
                    <label>Option C</label>
                    <input type="text" name="editOptionC" class="form-control" style="background-color:#f3edff;">
                  </div>
                  <div class="col-xs-6 form-group">
                    <label>Option D</label>
                    <input type="text" name="editOptionD" class="form-control" style="background-color:#f3edff;">
                  </div>
                  <div class="col-xs-6 form-group">
                    <label>Correct Answer</label>
                    <select name="editAnswer" class="form-control" style="background-color:#f3edff;">
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
                  <button type="submit" class="btn btn-info">
                    <i class="fa fa-check"></i> Update
                  </button>
                </div>

              </form>
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
