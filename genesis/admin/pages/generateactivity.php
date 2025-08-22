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
            

              <div class="row">
                <div class="col-sm-3">
                  <div class="form-group">
                    <label for="chapter">Chapter Name</label>
                    <input type="text" class="form-control input-sm" id="chapter" name="chapter" placeholder="Enter chapter name" required>
                  </div>
                </div>

                <div class="col-sm-3">
                  <div class="form-group">
                    <label for="activity_title">Quiz Title</label>
                    <input type="text" class="form-control input-sm" id="activity_title" name="activity_title" placeholder="Enter activity title" required>
                  </div>
                </div>

                <div class="col-sm-2">
                  <div class="form-group">
                    <label for="due_date">Due Date</label>
                    <input type="date" class="form-control input-sm" id="due_date" name="due_date" required>
                  </div>
                </div>

                <div class="col-sm-2">
                  <div class="form-group">
                    <label for="due_time">Due Time</label>
                    <input type="time" class="form-control input-sm" id="due_time" name="due_time" required>
                  </div>
                </div>

                <div class="col-sm-2">
                  <div class="form-group">
                    <label>Attach Image (optional)</label>
                    <input type="file" name="activity_image" accept="image/*" class="form-control input-sm">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="instructions">Instructions</label>
                    <textarea class="form-control input-sm" id="instructions" name="instructions" rows="3" placeholder="Enter activity instructions..."></textarea>
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



<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>


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
        <table id="assignActivityTable" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Title</th>
              <th>Chapter / Topic</th>
              <th>Originally Created For Class</th>
              <th>Status</th>
              <th>Assign For My Class (<?php echo htmlspecialchars($group); ?>)</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (isset($grade) && isset($SubjectId) && isset($group)) {
                // Fetch all activities for this grade and subject
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
                          <form method="POST" action="assignactivityhandler.php" style="display:inline;">
                            <input type="hidden" name="activityId" value="' . $row['Id'] . '">
                            <input type="hidden" name="grade" value="' . $grade . '">
                            <input type="hidden" name="subject" value="' . $SubjectId . '">
                            <input type="hidden" name="group" value="' . $group . '">
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