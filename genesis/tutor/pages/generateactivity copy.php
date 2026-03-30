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
?>
<!-- MathQuill CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/mathquill/build/mathquill.css">
<!-- MathQuill JS -->
<script src="https://cdn.jsdelivr.net/npm/mathquill/build/mathquill.min.js"></script>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
<?php include_once(TUTOR_PATH . "/../partials/header.php"); ?> 
<?php include_once(TUTOR_PATH . "/../partials/mainsidebar.php"); ?>


  <div class="content-wrapper">
    <section class="content-header">
        <h1>Generate Quiz</h1>
        <ol class="breadcrumb">
          <li><a href="tutorindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Quizzes</li>
        </ol>
    </section>

     <?php
        // might be unnecessary..
        include(__DIR__ . "/../../partials/connect.php");
        $grade = $_GET['gra'];
        $SubjectId = intval($_GET['sub']); // Get the subject value, ensure it's an integer
        $group = $_GET['group']; 


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
                <strong>Grade: <?php echo $_GET['gra']; ?></strong> 
                <input type="hidden" name="grade" value="<?php echo $_GET['gra']; ?>">
              </div>
              <div class="col-sm-4">
                <strong>Subject: <?php echo $SubjectName; ?></strong> 
                <input type="hidden" name="subject" value="<?php echo $_GET['sub']; ?>">
              </div>
              <div class="col-sm-4">
                <strong>Class: <?php echo $_GET['group']; ?></strong> 
                <input type="hidden" name="group" value="<?php echo $_GET['group']; ?>">
              </div>
            </div>

            <hr>

            <div class="form-group">
            

              <div class="row col-sm-6">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="chapter">Chapter Name</label>
                    <input type="text" class="form-control input-sm" id="chapter" name="chapter" placeholder="Enter chapter name" required>
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="activity_title">Quiz Title</label>
                    <input type="text" class="form-control input-sm" id="activity_title" name="activity_title" placeholder="Enter activity title" required>
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Attach Image (optional)</label>
                    <input type="file" name="activity_image" accept="image/*" class="form-control input-sm">
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="memo_file">Upload Memo (optional)</label>
                    <input type="file" class="form-control input-sm" id="memo_file" name="memo_file" accept=".pdf,.doc,.docx,.ppt,.pptx">
                    <small class="text-muted">Allowed: PDF</small>
                  </div>
                </div> 
              </div>

              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="instructions">Instructions</label>
                    <textarea class="form-control input-sm" id="instructions" name="instructions" rows="5" placeholder="Enter activity instructions..."></textarea>
                  </div>
                </div>

                
              </div>


            
            </div>

             
<div id="questions_container">
  <div class="question-block">
    <hr>
    <h4>Question 1</h4>

    <!-- Main question -->
    <div class="form-group">
      <label>Question</label>
      <div class="mathquill-container">
        <div class="mathquill-field"></div>
        <input type="hidden" name="questions[0][text]">
      </div>
    </div>

    <div class="row">
      <!-- Option A -->
      <div class="col-sm-3">
        <label>Option A</label>
        <div class="mathquill-container">
          <div class="mathquill-field"></div>
          <input type="hidden" name="questions[0][options][A]" required>
        </div>
      </div>
      <!-- Option B -->
      <div class="col-sm-3">
        <label>Option B</label>
        <div class="mathquill-container">
          <div class="mathquill-field"></div>
          <input type="hidden" name="questions[0][options][B]" required>
        </div>
      </div>
      <!-- Option C -->
      <div class="col-sm-3">
        <label>Option C</label>
        <div class="mathquill-container">
          <div class="mathquill-field"></div>
          <input type="hidden" name="questions[0][options][C]" required>
        </div>
      </div>
      <!-- Option D -->
      <div class="col-sm-3">
        <label>Option D</label>
        <div class="mathquill-container">
          <div class="mathquill-field"></div>
          <input type="hidden" name="questions[0][options][D]" required>
        </div>
      </div>
    </div>

    <div class="form-group" style="margin-top:10px;">
      <label>Set Correct Answer</label>
      <select name="questions[0][correct]" class="form-control input-sm" required>
        <option value="A">A</option>
        <option value="B">B</option>
        <option value="C">C</option>
        <option value="D">D</option>
      </select>
    </div>

    <button type="button" class="btn btn-danger btn-sm remove-question-btn">
      <i class="fa fa-trash"></i> Delete Question
    </button>
  </div>
</div>


            </div>

            <button type="button" class="btn btn-default btn-sm" id="add_question_btn"><i class="fa fa-plus"></i> Add Another Question</button>
          </div>

          <div class="box-footer text-center">
            <button type="submit" class="btn btn-primary btn-sm">Generate Activity</button>
            
            <button 
                type="button"
                class="btn btn-success btn-sm" 
                style="width: 150px;" 
                data-toggle="modal" 
                data-target="#modal-assignActivity"
                data-grade="<?php echo $grade; ?>"
                data-subject="<?php echo $SubjectId; ?>"
                data-group="<?php echo $group; ?>">
                Assign Available Activities
            </button>

          </div>
          
        </form>
      </div>
    </section>
  </div>
 
  <div class="control-sidebar-bg"></div>
</div>



<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>


  <?php if (isset($_GET['save']) && $_GET['save'] == 1): ?>
    <script>
      Swal.fire({
          icon: 'success',
          title: 'Activity saved successfully!',
          text: 'Do you want to assign this activity now?',
          showDenyButton: true,
          confirmButtonText: 'Yes, assign now',
          denyButtonText: 'No, assign later',
          confirmButtonColor: '#28a745',
          denyButtonColor: '#3085d6'
      }).then((result) => {
          if (result.isConfirmed) {
              // Open the assign modal directly
              $('#modal-assignActivity').modal('show');
          } else if (result.isDenied) {
              
              window.location.href = 'classes.php';
          }
      });
    </script>
  <?php endif; ?>

  <?php if (isset($_GET['memo']) && $_GET['memo'] == 1): ?>
    <script>
      Swal.fire({
          icon: 'error',
          title: 'Quiz Generation Failed!',
          text: 'Only Pdf allowed for Memo',
          
          confirmButtonText: 'OK'
      });
    </script>
  <?php endif; ?>
  

  <?php if (isset($_GET['saveerror']) && $_GET['saveerror'] == 1): ?>
    <script>
      Swal.fire({
          icon: 'error',
          title: 'Failed to save activity!',
          text: 'Please try again later.',
          
          confirmButtonText: 'OK'
      });
    </script>
  <?php endif; ?>






<script>
var MQ = MathQuill.getInterface(2);

function initMathFields() {
  document.querySelectorAll('.mathquill-container').forEach(function(container) {
    var div = container.querySelector('.mathquill-field');
    var hiddenInput = container.querySelector('input[type="hidden"]');
    if (!div.dataset.initialized) {
      var field = MQ.MathField(div, {
        spaceBehavesLikeTab: true,
        handlers: {
          edit: function() {
            hiddenInput.value = field.latex();
          }
        }
      });
      div.dataset.initialized = true;
    }
  });
}

// Initialize existing fields
initMathFields();

let questionIndex = 1;

document.getElementById('add_question_btn').addEventListener('click', function() {
  const container = document.getElementById('questions_container');
  
  const newBlock = document.createElement('div');
  newBlock.classList.add('question-block');
  newBlock.innerHTML = `
    <hr>
    <h4>Question ${questionIndex + 1}</h4>

    <div class="form-group">
      <label>Question</label>
      <div class="mathquill-container">
        <div class="mathquill-field"></div>
        <input type="hidden" name="questions[${questionIndex}][text]">
      </div>
    </div>

    <div class="row">
      <div class="col-sm-3">
        <label>Option A</label>
        <div class="mathquill-container">
          <div class="mathquill-field"></div>
          <input type="hidden" name="questions[${questionIndex}][options][A]" required>
        </div>
      </div>
      <div class="col-sm-3">
        <label>Option B</label>
        <div class="mathquill-container">
          <div class="mathquill-field"></div>
          <input type="hidden" name="questions[${questionIndex}][options][B]" required>
        </div>
      </div>
      <div class="col-sm-3">
        <label>Option C</label>
        <div class="mathquill-container">
          <div class="mathquill-field"></div>
          <input type="hidden" name="questions[${questionIndex}][options][C]" required>
        </div>
      </div>
      <div class="col-sm-3">
        <label>Option D</label>
        <div class="mathquill-container">
          <div class="mathquill-field"></div>
          <input type="hidden" name="questions[${questionIndex}][options][D]" required>
        </div>
      </div>
    </div>

    <div class="form-group" style="margin-top:10px;">
      <label>Set Correct Answer</label>
      <select name="questions[${questionIndex}][correct]" class="form-control input-sm" required>
        <option value="A">A</option>
        <option value="B">B</option>
        <option value="C">C</option>
        <option value="D">D</option>
      </select>
    </div>

    <button type="button" class="btn btn-danger btn-sm remove-question-btn">
      <i class="fa fa-trash"></i> Delete Question
    </button>
  `;

  container.appendChild(newBlock);
  initMathFields();
  questionIndex++;
});

document.getElementById('questions_container').addEventListener('click', function(e) {
  if (e.target.closest('.remove-question-btn')) {
    e.target.closest('.question-block').remove();
  }
});


</script>







<style>
 .mathquill-field {
    display: block;
    width: 100%;
    min-height: 35px;
    padding: 5px 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    cursor: text;
    background-color: #fff;
}

.mathquill-field:focus {
    border-color: #66afe9;
    outline: none;
}


</style>

<script>
$('#modal-assignActivity').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); 
    var grade = button.data('grade');
    var subject = button.data('subject');
    var group = button.data('group');

    console.log("Grade:", grade, "Subject:", subject, "Group:", group);

    // Later: populate table rows via AJAX here
});
</script>


<!-- Assign Activity Modal -->

<div class="modal fade" id="modal-assignActivity" tabindex="-1" role="dialog" aria-labelledby="assignActivityLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header bg-success">
        <h4 class="modal-title" id="assignActivityLabel">Assign Activity</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script>
      <?php if (isset($_GET['assigned']) && $_GET['assigned'] == 1): ?>
          // Open the Assign Activity modal
          $('#modal-assignActivity').modal('show');

          // Show SweetAlert on top of modal
          Swal.fire({
              icon: 'success',
              title: 'Activity assigned successfully!',
              text: 'The selected activity has been assigned to this class/group.',
              backdrop: true,
              confirmButtonText: 'OK'
          });
      <?php endif; ?>
      </script>

      <div class="modal-body">
        <div class="table-responsive">
        <table id="assignActivityTable" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Title</th>
              <th>Chapter / Topic</th>
              <th>Orig. Class</th>
              <th>Status</th>
              <th>Assign For My Class (<?php echo htmlspecialchars($group); ?>) / (Due Date?)</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (isset($grade) && isset($SubjectId) && isset($group)) {
                $stmt = $connect->prepare("
                  SELECT a.Id, a.Title, a.Topic, a.GroupName,
                        IF(b.OnlineActivityId IS NULL, 0, 1) AS assigned
                  FROM onlineactivities a
                  LEFT JOIN onlineactivitiesassignments b 
                    ON a.Id = b.OnlineActivityId 
                    AND b.ClassID = (SELECT ClassID FROM classes WHERE Grade = ? AND SubjectId = ? AND GroupName = ? LIMIT 1)
                  WHERE a.Grade = ? AND a.SubjectId = ?
                  ORDER BY a.CreatedAt DESC
                ");
                $stmt->bind_param("iisii", $grade, $SubjectId, $group, $grade, $SubjectId);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $assigned = $row['assigned'] ? true : false;
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['Title']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['Topic']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['GroupName']) . '</td>';
                    echo '<td>' . ($assigned ? '<span class="text-success">Assigned</span>' : '<span class="text-warning">Not Assigned</span>') . '</td>';
                    echo '<td>';
                    if ($assigned) {
                        echo '<button class="btn btn-default btn-sm" disabled>Assign</button>';
                    } else {
                        echo '
                            <form method="POST" action="assignactivityhandler.php" style="display:flex; align-items:center; gap:5px;">
                                <input type="hidden" name="activityId" value="' . $row['Id'] . '">
                                <input type="hidden" name="grade" value="' . $grade . '">
                                <input type="hidden" name="subject" value="' . $SubjectId . '">
                                <input type="hidden" name="group" value="' . $group . '">
                                <input type="date" name="dueDate" class="form-control input-sm" required style="width:140px;">
                                <button type="submit" class="btn btn-success btn-sm">Assign</button>
                            </form>
                            ';

                    }
                    echo '</td>';
                    echo '</tr>';
                }

                $stmt->close();
            }
            ?>
          </tbody>
        </table>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>


<script>
  $(function () {
    $('#assignActivityTable').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
</script>




</body>
</html>