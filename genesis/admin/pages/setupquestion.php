<?php


// Load path constants
include_once(__DIR__ . "/../../partials/paths.php");

// Start session securely
include_once(BASE_PATH . "/partials/session_init.php");


if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

// Includes
include_once(COMMON_PATH . "/../partials/head.php");  //correct

include_once(BASE_PATH . "/partials/connect.php");



// Fetch distinct existing question sets grouped by Grade, Subject, Chapter, Level
$query = "
    SELECT DISTINCT GradeName, SubjectName, Chapter, LevelName
    FROM practicequestions 
    JOIN level ON practicequestions.LevelId = level.Id
    ORDER BY GradeName, SubjectName, Chapter, LevelName
";
$result = $connect->query($query);

$existingSets = [];
while ($row = $result->fetch_assoc()) {
    $existingSets[] = $row;
}

// Fetch all grades dynamically
$gradesRes = $connect->query("SELECT GradeId, GradeName FROM grades ORDER BY GradeName");
$grades = [];
while($row = $gradesRes->fetch_assoc()){
    $grades[] = $row;
}

// Fetch all subjects dynamically
$subjectsRes = $connect->query("SELECT SubjectId, SubjectName FROM subjects ORDER BY SubjectName");
$subjects = [];
while($row = $subjectsRes->fetch_assoc()){
    $subjects[] = $row;
}
?>


<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">


    <?php include_once(ADMIN_PATH . "/../partials/header.php"); ?>
    <?php include_once(ADMIN_PATH . "/../partials/mainsidebar.php"); ?>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>Practice Questions Setup <small>Select details before creating questions</small></h1>
            <ol class="breadcrumb">
                <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Practice Questions Setup</li>
            </ol>
        </section>

        <section class="content">
            <div class="box box-primary" style="border-top: 3px solid #3c8dbc;">
                <div class="box-header with-border" style="background-color:#f0f8ff;">
                    <h3 class="box-title" style="color:#3c8dbc;">
                        <i class="fa fa-cogs"></i> Select Details
                    </h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <!-- Left side: Smaller form -->
                        <div class="col-md-6">
                            <form action="create_questions.php" method="POST">
                                <div class="form-group">
                                    <label>Grade</label>
                                    <select name="grade" id="gradeSelect" class="form-control" required>
                                      <option value="">Select Grade</option>
                                      <?php foreach($grades as $grade): ?>
                                          <option value="<?= htmlspecialchars($grade['GradeName']) ?>"><?= htmlspecialchars($grade['GradeName']) ?></option>
                                      <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Subject</label>
                                    <select name="subject" id="subjectSelect" class="form-control" required>
                                      <option value="">Select Subject</option>
                                      <!-- dynamically filled -->
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Chapter</label>
                                    <input type="text" name="chapter" class="form-control" placeholder="Enter chapter name" required>
                                </div>

                                <div class="form-group">
                                    <label>Level</label>
                                    <select name="level" class="form-control" required>
                                        <option value="">Select Level</option>
                                        <option value="Easy">Easy</option>
                                        <option value="Medium">Medium</option>
                                        <option value="Hard">Hard</option>
                                    </select>
                                </div>

                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-arrow-right"></i> Continue
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Right side: Info panel -->
                        <div class="col-md-6">
                            <div class="callout callout-info">
                                <h4><i class="fa fa-info-circle"></i> About This Page</h4>
                                <p>This setup page allows you to define the key details for your practice questions before creating them. Once you select a grade, subject, chapter, and difficulty level, you can proceed to add the questions.</p>
                                <p>You can also edit existing sets of questions by clicking the <strong>Edit</strong> button in the table below.</p>
                            </div>

                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Tips for Creating Good Questions</h3>
                                </div>
                                <div class="box-body">
                                    <ul>
                                        <li>Keep the question clear and concise.</li>
                                        <li>Match difficulty level to learners’ capabilities.</li>
                                        <li>Use relevant and up-to-date examples.</li>
                                        <li>Attach diagrams or supporting files when needed.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Existing question sets list -->
            <div class="box box-warning" style="border-top: 3px solid #f39c12; margin-top: 20px;">
              <div class="box-header with-border" style="background-color:#fff8e1;">
                <h3 class="box-title" style="color:#f39c12;">
                  <i class="fa fa-folder-open"></i> Existing Practice Question Sets
                </h3>
              </div>
              <div class="box-body">
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                  <?php if (empty($existingSets)): ?>
                    <p>No existing question sets found.</p>
                  <?php else: ?>
                    <table id="example1" class="table table-bordered table-hover table-condensed">
                      <thead style="background-color: #f9f9f9;">
                        <tr>
                          <th>Grade</th>
                          <th>Subject</th>
                          <th>Chapter</th>
                          <th>Level</th>
                          <th class="text-center">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($existingSets as $set): ?>
                          <tr>
                            <td><?= htmlspecialchars($set['GradeName']) ?></td>
                            <td><?= htmlspecialchars($set['SubjectName']) ?></td>
                            <td><?= htmlspecialchars($set['Chapter']) ?></td>
                            <td><?= htmlspecialchars($set['LevelName']) ?></td>
                            <td class="text-center">
                              <a href="create_questions.php?grade=<?= urlencode($set['GradeName']) ?>&subject=<?= urlencode($set['SubjectName']) ?>&chapter=<?= urlencode($set['Chapter']) ?>&level=<?= urlencode($set['LevelName']) ?>" 
                                class="btn btn-xs btn-warning">
                                <i class="fa fa-edit"></i> Edit
                              </a>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                      <tfoot style="background-color: #f9f9f9;">
                        <tr>
                          <th>Grade</th>
                          <th>Subject</th>
                          <th>Chapter</th>
                          <th>Level</th>
                          <th class="text-center">Action</th>
                        </tr>
                      </tfoot>
                    </table>
                  <?php endif; ?>
                </div>
              </div>
            </div>




        </section>
    </div>

    <div class="control-sidebar-bg"></div>
</div>

<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>


<!-- DataTables initialization script -->
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

<script>
$(document).ready(function(){
    $('#gradeSelect').on('change', function(){
        var grade = $(this).val();
        var subjectSelect = $('#subjectSelect');

        // Clear previous options
        subjectSelect.html('<option value="">Select Subject</option>');

        if(grade){
            $.ajax({
                url: 'get_subjects.php',
                type: 'GET',
                data: { grade: grade },
                dataType: 'json',
                success: function(data){
                    if(data.length > 0){
                        $.each(data, function(i, subject){
                            subjectSelect.append('<option value="' + subject.SubjectName + '">' + subject.SubjectName + '</option>');
                        });
                    }
                }
            });
        }
    });
});
</script>


</body>
</html>
