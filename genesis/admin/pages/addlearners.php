<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../common/partials/head.php");
include(__DIR__ . "/../../partials/connect.php");

// Hardcoded SchoolId
$schoolId = 4;

// Fetch grades for the school
$gradesStmt = $connect->prepare("SELECT GradeId, GradeName FROM grades WHERE SchoolId = ? ORDER BY GradeName ASC");
$gradesStmt->bind_param("i", $schoolId);
$gradesStmt->execute();
$gradesResult = $gradesStmt->get_result();
$grades = [];
while($row = $gradesResult->fetch_assoc()){
    $grades[] = $row;
}
?>
<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">
    <?php include(__DIR__ . "/../partials/header.php"); ?>
    <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

    <div class="content-wrapper">
      <section class="content-header">
        <h1>Learner Registration <small>Form</small></h1>
        <ol class="breadcrumb">
          <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Administration</li>
        </ol>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-body">
                <form action="addlearnerh.php" method="post" id="learnerForm">

                  <!-- Learner Info -->
                  <fieldset class="tab">
                    <legend>Learner Info</legend>
                    <div class="form-group row">
                      <div class="col-md-6">
                        <label>First Name</label>
                        <input type="text" class="form-control" name="name" required>
                      </div>
                      <div class="col-md-3">
                        <label>Surname</label>
                        <input type="text" class="form-control" name="surname" required>
                      </div>
                      <div class="col-md-3">
                        <label>School Name</label>
                        <input type="text" class="form-control" name="schoolname" value="DOE" readonly>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-md-3">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" required>
                      </div>
                      <div class="col-md-3">
                        <label>Contact Number</label>
                        <input type="tel" class="form-control" name="contactnumber" pattern="[0-9]{10}" maxlength="10" required>
                        <input type="hidden" name="password" value="12345">
                      </div>
                      <div class="col-md-2">
                        <label>Title</label>
                        <select class="form-control" name="learnertitle" required>
                          <option value="">Select Title</option>
                          <option value="Mr">Mr.</option>
                          <option value="Mrs">Mrs.</option>
                          <option value="Ms">Ms.</option>
                        </select>
                      </div>
                      <div class="col-md-2">
                        <label>Grade</label>
                        <select class="form-control" name="grade" id="gradeSelect" required>
                          <option value="" disabled selected>Select Grade</option>
                          <?php foreach ($grades as $grade): ?>
                            <option value="<?= $grade['GradeId'] ?>"><?= $grade['GradeName'] ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="col-md-2">
                        <label>Knockout Time</label>
                        <input type="time" class="form-control" name="knockout_time" required>
                      </div>
                    </div>
                  </fieldset><br>

                  <!-- Subjects, Duration & Levels -->
                  <fieldset class="tab">
                    <legend>Select Subjects, Duration & Levels</legend>
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>Subject</th>
                          <th>Duration</th>
                          <th>Current Level</th>
                          <th>Target Level</th>
                        </tr>
                      </thead>
                      <tbody id="subjectsTable">
                        <!-- Subjects will be loaded dynamically -->
                      </tbody>
                    </table>
                  </fieldset>

                  <!-- Parent Info -->
                  <fieldset class="tab">
                    <legend>Parent Info</legend>
                    <div class="form-group row">
                      <div class="col-md-3">
                        <label>First Name</label>
                        <input type="text" class="form-control" name="parentname" required>
                      </div>
                      <div class="col-md-3">
                        <label>Surname</label>
                        <input type="text" class="form-control" name="parentsurname" required>
                      </div>
                      <div class="col-md-3">
                        <label>Email</label>
                        <input type="email" class="form-control" name="parentemail" required>
                      </div>
                      <div class="col-md-2">
                        <label>Cell No:</label>
                        <input type="tel" class="form-control" name="parentcontact" pattern="[0-9]{10}" maxlength="10" required>
                      </div>
                      <div class="col-md-2">
                        <label>Title</label>
                        <select class="form-control" name="parenttitle" required>
                          <option value="">Select Title</option>
                          <option value="Mr">Mr.</option>
                          <option value="Mrs">Mrs.</option>
                          <option value="Ms">Ms.</option>
                          <option value="Dr">Dr.</option>
                          <option value="Prof">Prof.</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      
                      
                    </div>
                  </fieldset>

                  <div class="text-center" style="margin-top: 20px;">
                    <button type="submit" class="btn btn-primary">Submit All Info</button>
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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
  $(document).ready(function(){
      <?php if(isset($_SESSION['success'])): ?>
          Swal.fire({
              icon: 'success',
              title: 'Success!',
              html: `<?= $_SESSION['success'] ?>`,
              confirmButtonText: 'OK'
          });
          <?php unset($_SESSION['success']); ?>
      <?php endif; ?>

      <?php if(isset($_SESSION['error'])): ?>
          Swal.fire({
              icon: 'error',
              title: 'Error!',
              html: `<?= $_SESSION['error'] ?>`,
              confirmButtonText: 'OK'
          });
          <?php unset($_SESSION['error']); ?>
      <?php endif; ?>
  });
  </script>

  <!-- SUMMARY MODAL -->
  <div class="modal fade" id="summaryModal" tabindex="-1" role="dialog" aria-labelledby="summaryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="summaryModalLabel">Registration Summary</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <table class="table table-bordered" id="summaryTable">
            <thead>
              <tr>
                <th>Subject</th>
                <th>Duration</th>
                <th>Cost</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
          <h4 class="text-right">Total: <span id="totalCost">0</span></h4>
          <p class="text-muted"><small>Note: Prices shown are standard. Discounts may be applied if available.</small></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Edit</button>
          <button type="button" class="btn btn-primary" id="confirmSubmit">Confirm Registration</button>
        </div>
      </div>
    </div>
  </div>

 <script>
  $(document).ready(function(){

      // -----------------------
      // DYNAMIC SUBJECTS TABLE
      // -----------------------
      $('#gradeSelect').change(function(){
          const gradeId = $(this).val();
          $.ajax({
              url: 'fetch_subjects.php',
              type: 'POST',
              data: { gradeId: gradeId },
              dataType: 'json',
              success: function(subjects){
                  const tbody = $('#subjectsTable');
                  tbody.empty();
                  subjects.forEach(function(sub){
                      const row = `
                          <tr>
                              <td>
                                  ${sub.SubjectName}
                                  <!-- Hidden input for SubjectID[] -->
                                  <input type="hidden" name="SubjectID[]" value="${sub.SubjectId}">
                              </td>
                              <td>
                                  <!-- Duration[] array -->
                                  <select name="Duration[]" class="form-control subject-duration" required>
                                      <option value="0" selected>Not Registered</option>
                                      <option value="${sub.ThreeMonthsPrice}">3 Months</option>
                                      <option value="${sub.SixMonthsPrice}">6 Months</option>
                                      <option value="${sub.TwelveMonthsPrice}">12 Months</option>
                                  </select>
                              </td>
                              <td>
                                  <!-- CurrentLevel[] array -->
                                  <select name="CurrentLevel[]" class="form-control level-select" disabled>
                                      ${[1,2,3,4,5,6,7].map(i => `<option value="${i}" ${i==1?'selected':''}>${i}</option>`).join('')}
                                  </select>
                              </td>
                              <td>
                                  <!-- TargetLevel[] array -->
                                  <select name="TargetLevel[]" class="form-control level-select" disabled>
                                      ${[3,4,5,6,7].map(i => `<option value="${i}" ${i==7?'selected':''}>${i}</option>`).join('')}
                                  </select>
                              </td>
                          </tr>
                      `;
                      tbody.append(row);
                  });

                  // Enable/disable level selects based on duration
                  $('.subject-duration').off('change').on('change', function(){
                      const row = $(this).closest('tr');
                      const duration = $(this).val();
                      if(duration != '0'){
                          row.find('.level-select').prop('disabled', false);
                      } else {
                          row.find('.level-select').prop('disabled', true);
                          row.find('select[name$="CurrentLevel[]"]').val('1');
                          row.find('select[name$="TargetLevel[]"]').val('7');
                      }
                  });
              }
          });
      });

      // -----------------------
      // SUMMARY MODAL
      // -----------------------
      $('#learnerForm').on('submit', function(e){
          e.preventDefault();
          const tbody = $('#summaryTable tbody');
          tbody.empty();
          let total = 0;

          // Loop through all registered subjects using arrays
          const subjects = $('input[name="SubjectID[]"]').map((i, el) => $(el).val()).get();
          const durations = $('select[name="Duration[]"]').map((i, el) => parseFloat($(el).val())).get();
          const currentLevels = $('select[name="CurrentLevel[]"]').map((i, el) => $(el).val()).get();
          const targetLevels = $('select[name="TargetLevel[]"]').map((i, el) => $(el).val()).get();

          for(let i=0; i<subjects.length; i++){
              if(durations[i] > 0){  // check if subject has been registered or not. 
                  const subjectName = $('#subjectsTable tr').eq(i).find('td:first').text();
                  const durationText = $('#subjectsTable tr').eq(i).find('select[name="Duration[]"] option:selected').text();
                  total += durations[i];
                  tbody.append(`<tr><td>${subjectName}</td><td>${durationText}</td><td>${durations[i]}</td></tr>`);
              }
          }

          $('#totalCost').text(total.toFixed(2));
          $('#summaryModal').modal('show');
      });

      // -----------------------
      // CONFIRM SUBMISSION
      // -----------------------
      $('#confirmSubmit').click(function(){
          $('#learnerForm')[0].submit();
      });

  });
</script>




