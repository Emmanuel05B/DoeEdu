<!DOCTYPE html>
<html>

<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../../common/pages/login.php");
  exit();
}

include(__DIR__ . "/../../common/partials/head.php");
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

    <section class="content-header">
      <h1>Create Questions <small>For Subjects & Levels</small></h1>
      <ol class="breadcrumb">
        <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Create Questions</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title"><i class="fa fa-question-circle"></i> Question Details</h3>
            </div>

            <div class="box-body">
              <form method="post" action="submit_question.php">
                <div class="row">
                  <!-- Subject Selection -->
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="Subject">Subject</label>
                      <select class="form-control" name="Subject" id="Subject" required>
                        <option value="">-- Select Subject --</option>
                        <option value="Mathematics">Mathematics</option>
                        <option value="English">English</option>
                        <option value="Science">Science</option>
                      </select>
                    </div>
                  </div>

                  <!-- Difficulty -->
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="Difficulty">Difficulty</label>
                      <select class="form-control" name="Difficulty" id="Difficulty" required>
                        <option value="">-- Select Difficulty --</option>
                        <option value="Easy">Easy</option>
                        <option value="Medium">Medium</option>
                        <option value="Hard">Hard</option>
                      </select>
                    </div>
                  </div>

                  <!-- Question Type -->
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="QuestionType">Question Type</label>
                      <select class="form-control" name="QuestionType" id="QuestionType" required>
                        <option value="">-- Select Type --</option>
                        <option value="mcq">Multiple Choice</option>
                        <option value="text">One-word Answer</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- Question Text -->
                <div class="form-group">
                  <label for="QuestionText">Question</label>
                  <textarea class="form-control" name="QuestionText" id="QuestionText" rows="3" placeholder="Enter the question..." required></textarea>
                </div>

                <!-- Multiple Choice Options (Shown only if MCQ selected) -->
                <div id="mcqOptions" style="display: none;">
                  <label>Options</label>
                  <div class="form-group">
                    <input type="text" class="form-control" name="OptionA" placeholder="Option A">
                    <input type="text" class="form-control" name="OptionB" placeholder="Option B">
                    <input type="text" class="form-control" name="OptionC" placeholder="Option C">
                    <input type="text" class="form-control" name="OptionD" placeholder="Option D">
                    <input type="text" class="form-control" name="OptionE" placeholder="Option E (Optional)">
                    <label for="CorrectOption">Correct Option</label>
                    <select class="form-control" name="CorrectOption">
                      <option value="">-- Select Correct Option --</option>
                      <option value="A">A</option>
                      <option value="B">B</option>
                      <option value="C">C</option>
                      <option value="D">D</option>
                      <option value="E">E</option>
                    </select>
                  </div>
                </div>

                <!-- Text Answer Input (Shown only if text selected) -->
                <div id="textAnswer" style="display: none;">
                  <div class="form-group">
                    <label for="AnswerText">Correct Answer</label>
                    <input type="text" class="form-control" name="AnswerText" id="AnswerText" placeholder="Enter the correct answer...">
                  </div>
                </div>

                <!-- Pass Mark per Level (Optional Control) -->
                <div class="form-group">
                  <label for="PassMark">Pass Mark for Level (%)</label>
                  <input type="number" class="form-control" name="PassMark" id="PassMark" placeholder="e.g. 70" min="0" max="100">
                </div>

                <!-- Submit Buttons -->
                <div class="text-right">
                  <button type="reset" class="btn btn-default">Clear</button>
                  <button type="submit" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Save Question</button>
                </div>
              </form>
            </div> <!-- /.box-body -->
          </div> <!-- /.box -->
        </div> <!-- /.col -->
      </div> <!-- /.row -->
    </section> <!-- /.content -->

  </div> <!-- /.content-wrapper -->

  <div class="control-sidebar-bg"></div>
</div> <!-- ./wrapper -->

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

<!-- jQuery logic to switch question type views -->
<script>
  $(document).ready(function () {
    $('#QuestionType').change(function () {
      const selected = $(this).val();
      if (selected === "mcq") {
        $('#mcqOptions').show();
        $('#textAnswer').hide();
      } else if (selected === "text") {
        $('#textAnswer').show();
        $('#mcqOptions').hide();
      } else {
        $('#mcqOptions, #textAnswer').hide();
      }
    });
  });
</script>

</body>
</html>
