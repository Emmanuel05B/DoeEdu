<!DOCTYPE html>
<html>
<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
  exit();
}
?>

<?php include(__DIR__ . "/../../common/partials/head.php"); ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
        <h1>Generate Quiz </h1>
        <ol class="breadcrumb">
          <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Quizzes</li>
        </ol>
    </section>

     <?php
        // might be unnecessary.
        include(__DIR__ . "/../../partials/connect.php");
        $grade = $_GET['gra'];
        $chaptername = $_GET['cha'];
        $SubjectId = intval($_GET['sub']); // Get the subject value, ensure it's an integer

        $stmt = $connect->prepare("SELECT SubjectName FROM subjects WHERE SubjectId = ?");
        $stmt->bind_param("i", $SubjectId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $SubjectName = $row['SubjectName'];
        }

     ?>

    <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border text-center">
          <h3 class="box-title">Quiz Details</h3>
        </div>

        <form action="saveactivity.php" method="POST" enctype="multipart/form-data">

          <div class="box-body">
            <div class="row text-center activity-info">
              <div class="col-sm-4">
                <strong>Grade:</strong> <?php echo $_GET['gra']; ?>
                <input type="hidden" name="grade" value="<?php echo $_GET['gra']; ?>">
              </div>
              <div class="col-sm-4">
                <strong>Subject:</strong> <?php echo $SubjectName; ?>
                <input type="hidden" name="subject" value="<?php echo $_GET['sub']; ?>">
              </div>
              <div class="col-sm-4">
                <strong>Chapter:</strong> <?php echo $_GET['cha']; ?>
                <input type="hidden" name="chapter" value="<?php echo $_GET['cha']; ?>">
                <input type="hidden" name="group" value="<?php echo $_GET['group']; ?>">
              </div>
            </div>

            <hr>

            <div class="form-group">
              
              <div class="row">
                <div class="col-sm-3">
                  <div class="form-group">
                    <label for="activity_title">Quiz Title</label>
                    <input type="text" class="form-control input-sm" id="activity_title" name="activity_title" placeholder="Enter activity title" required>
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label for="due_date">Due Date</label>
                    <input type="date" class="form-control input-sm" id="due_date" name="due_date" required>
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label for="due_time">Due Time</label>
                    <input type="time" class="form-control input-sm" id="due_time" name="due_time" required>
                  </div>
                </div>
                <div class="col-sm-3">
                 <div class="form-group">
                    <label>Attach Image (optional)</label>
                    <input type="file" name="activity_image" accept="image/*" class="form-control input-sm">
                  </div>
                </div>

              </div>

            
            </div>

            <div id="questions_container">
             
              <div class="question-block">
                <hr>
                <h4>Question 1</h4>
                <div class="form-group">
                  <label>Question</label>
                  <textarea name="questions[0][text]" class="form-control input-sm" required></textarea>
                </div>
                <div class="row">
                  <div class="col-sm-2">
                    <div class="form-group">
                      <label>Option A</label>
                      <input type="text" name="questions[0][options][A]" class="form-control input-sm" required>
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <div class="form-group">
                      <label>Option B</label>
                      <input type="text" name="questions[0][options][B]" class="form-control input-sm" required>
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <div class="form-group">
                      <label>Option C</label>
                      <input type="text" name="questions[0][options][C]" class="form-control input-sm" required>
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <div class="form-group">
                      <label>Option D</label>
                      <input type="text" name="questions[0][options][D]" class="form-control input-sm" required>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label>Set Correct Answer</label>
                      <select name="questions[0][correct]" class="form-control input-sm" required>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                      </select>
                    </div>
                  </div>


                </div>
               
               
              </div>
            </div>

            <button type="button" class="btn btn-default btn-sm" id="add_question_btn"><i class="fa fa-plus"></i> Add Another Question</button>
          </div>

          <div class="box-footer text-center">
            <button type="submit" class="btn btn-primary btn-sm">Generate Activity</button>
          </div>
        </form>
      </div>
    </section>
  </div>
 
  <div class="control-sidebar-bg"></div>
</div>



<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>




<script>
  let questionIndex = 1;

  document.getElementById('add_question_btn').addEventListener('click', function () {
    const container = document.getElementById('questions_container');
    const newBlock = document.createElement('div');
    newBlock.classList.add('question-block');

    newBlock.innerHTML = `
      <hr>
      <h4>Question ${questionIndex + 1}</h4>
      <div class="form-group">
        <label>Question</label>
        <textarea name="questions[${questionIndex}][text]" class="form-control input-sm" required></textarea>
      </div>

      <div class="row">
        <div class="col-sm-2">
          <div class="form-group">
            <label>Option A</label>
            <input type="text" name="questions[${questionIndex}][options][A]" class="form-control input-sm" required>
          </div>
        </div>
        <div class="col-sm-2">
          <div class="form-group">
            <label>Option B</label>
            <input type="text" name="questions[${questionIndex}][options][B]" class="form-control input-sm" required>
          </div>
        </div>
        <div class="col-sm-2">
          <div class="form-group">
            <label>Option C</label>
            <input type="text" name="questions[${questionIndex}][options][C]" class="form-control input-sm" required>
          </div>
        </div>
        <div class="col-sm-2">
          <div class="form-group">
            <label>Option D</label>
            <input type="text" name="questions[${questionIndex}][options][D]" class="form-control input-sm" required>
          </div>
        </div>
        <div class="col-sm-2">
          <div class="form-group">
            <label>Set Correct Answer</label>
            <select name="questions[${questionIndex}][correct]" class="form-control input-sm" required>
              <option value="A">A</option>
              <option value="B">B</option>
              <option value="C">C</option>
              <option value="D">D</option>
            </select>
          </div>
        </div>
        <div class="col-sm-2">
          <div class="form-group">

            <label>Delete</label>
            <button type="button" class="btn btn-danger btn-sm remove-question-btn"><i class="fa fa-trash"></i> Delete Question</button>

          </div>
        </div>
      </div>

    `;

    // Append the question block
    container.appendChild(newBlock);
    questionIndex++;
  });

  // Delegate delete button click to container
  document.getElementById('questions_container').addEventListener('click', function (e) {
    if (e.target.closest('.remove-question-btn')) {
      const block = e.target.closest('.question-block');
      block.remove();
    }
  });
</script>


<style>
  .form-control {
    max-width: 100%;
  }
  .question-block {
    margin-bottom: 20px;
  }
  .box-header.text-center h3 {
    text-align: center;
    margin: 0 auto;
    font-weight: 600;
  }
  .activity-info {
    margin-top: 15px;
    margin-bottom: 15px;
    font-size: 16px;
  }
  .activity-info strong {
    display: block;
    margin-bottom: 5px;
  }
  @media (max-width: 768px) {
    .form-control {
      font-size: 14px;
    }
    h4 {
      font-size: 16px;
    }
    .btn {
      font-size: 14px;
      padding: 6px 10px;
    }
    .activity-info {
      font-size: 14px;
    }
  }
</style>
</body>
</html>