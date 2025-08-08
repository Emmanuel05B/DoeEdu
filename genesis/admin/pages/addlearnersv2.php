<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}
include(__DIR__ . "/../../partials/connect.php");
?>
<?php include(__DIR__ . "/../../common/partials/head.php"); ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
   <?php include(__DIR__ . "/../partials/header.php"); ?>
   <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>
  
  <div class="content-wrapper">

        <section class="content-header">
          <h1>
            Learner Registration
            <small>Form</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Administration</li>
          </ol>
        </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <h3 class="box-title" style="text-align: center;"></h3>
            <div class="box-body">
              <form action="addlearnerhv2.php" method="post">

                <!-- Learner Info Block -->
                <fieldset class="tab">
                  <legend>Learner Info</legend>
                  <div class="form-group row">
                    <div class="col-md-3">
                      <label for="name">First Name</label>
                      <input type="text" class="form-control" id="name" name="name" placeholder="Enter your first name" required>
                    </div>
                    <div class="col-md-3">
                      <label for="surname">Surname</label>
                      <input type="text" class="form-control" id="surname" name="surname" placeholder="Enter your surname" required>
                    </div>
                    <div class="col-md-3">
                      <label for="email">Email</label>
                      <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="col-md-3">
                      <label for="contactnumber">Contact Number (10 digits)</label>
                      <input type="tel" class="form-control" id="contactnumber" name="contactnumber" pattern="[0-9]{10}" maxlength="10" required>
                      <input type="hidden" id="password" name="password" value="12345">
                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="col-md-2">
                      <label for="learnertitle">Title</label>
                      <select class="form-control" id="learnertitle" name="learnertitle" required>
                        <option value="">Select Title</option>
                        <option value="Mr">Mr.</option>
                        <option value="Mrs">Mrs.</option>
                        <option value="Ms">Ms.</option>
                      </select>
                    </div>
                    <div class="col-md-3">
                      <label for="schoolname">School Name</label>
                      <select class="form-control" id="schoolname" name="schoolname" required>
                        <option value="">Select School Name</option>
                        <?php

                        $result = $connect->query("SELECT SchoolId, SchoolName FROM schools ORDER BY SchoolName ASC");
                        
                        if ($result && $result->num_rows > 0) {
                            while ($school = $result->fetch_assoc()) {
                                echo '<option value="' . htmlspecialchars($school['SchoolId']) . '">' . htmlspecialchars($school['SchoolName']) . '</option>';
                            }
                        } else {
                            echo '<option value="">No schools found</option>';
                        }
                        ?>
                      </select>
                    </div>
                    
                    <div class="col-md-2">
                      <label for="grade">Grade</label>
                      <select id="grade" name="grade" class="form-control" required>
                        <option value="" disabled selected>Select Grade</option>
                        <!-- Grades dynamically loaded here -->
                      </select>
                    </div>
                    
                    <div class="col-md-2">
                      <label for="knockout_time">Knockout Time</label>
                      <input type="time" class="form-control" id="knockout_time" name="knockout_time" required>
                    </div>
                  </div>
                </fieldset><br>

                <!-- Subject Selection Block -->
                <fieldset class="tab">
                  <legend>Register Subjects and Levels (Goals)</legend>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Subject</th>
                        <th>Mark (<i class="fa fa-check-square"></i>)</th>
                        <th>Current Level (1 - 7)</th>
                        <th>Target Level (3 - 7)</th>
                      </tr>
                    </thead>
                    <tbody id="subjectsTableBody">
                      <!-- Subjects dynamically loaded here based on school + grade -->
                      <tr><td colspan="4" style="text-align:center;">Please select a school and grade to load subjects.</td></tr>
                    </tbody>
                  </table>
                </fieldset><br>

                <!-- Parent Info Block -->
                <fieldset class="tab">
                  <legend>Parent Info</legend>
                  <div class="form-group row">
                    <div class="col-md-2">
                      <label for="parentname">First Name</label>
                      <input type="text" class="form-control" id="parentname" name="parentname" placeholder="Parent first name" required>
                    </div>
                    <div class="col-md-3">
                      <label for="parentsurname">Surname</label>
                      <input type="text" class="form-control" id="parentsurname" name="parentsurname" placeholder="Enter parent surname" required>
                    </div>
                  
                    <div class="col-md-3">
                      <label for="parentemail">Email</label>
                      <input type="email" class="form-control" id="parentemail" name="parentemail" placeholder="Enter parent email" required>
                    </div>
                    <div class="col-md-2">
                      <label for="parenttitle">Title</label>
                      <select class="form-control" id="parenttitle" name="parenttitle" required>
                        <option value="">Select Title</option>
                        <option value="Mr">Mr.</option>
                        <option value="Mrs">Mrs.</option>
                        <option value="Ms">Ms.</option>
                        <option value="Dr">Dr.</option>
                        <option value="Prof">Prof.</option>
                      </select>
                    </div>
                    <div class="col-md-2">
                      <label for="parentcontact">Contact Number</label>
                      <input type="tel" class="form-control" id="parentcontact" name="parentcontact" pattern="[0-9]{10}" maxlength="10" required>
                    </div>
                  </div>
                </fieldset>

                <!-- Submit Button -->
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

<!-- jQuery (ensure this is loaded before your script) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {

  // When School changes, fetch grades for that school
  $('#schoolname').change(function() {
    const schoolId = $(this).val();
    if (!schoolId) {
      $('#grade').html('<option value="" disabled selected>Select Grade</option>');
      clearSubjectsTable();
      return;
    }
    $.post('fetch_grades.php', { schoolId: schoolId }, function(data) {
      let options = '<option value="" disabled selected>Select Grade</option>';
      if (data.length > 0) {
        data.forEach(function(grade) {
          options += `<option value="${grade}">${grade}</option>`;
        });
      } else {
        options = '<option value="" disabled selected>No grades found</option>';
      }
      $('#grade').html(options);
      clearSubjectsTable();
    }, 'json').fail(function() {
      alert('Failed to load grades');
    });
  });

  // When Grade changes, fetch subjects for school + grade
  $('#grade').change(function() {
    const schoolId = $('#schoolname').val();
    const grade = $(this).val();

    if (!schoolId || !grade) {
      clearSubjectsTable();
      return;
    }

    $.post('fetch_subjects.php', { schoolId: schoolId, grade: grade }, function(subjects) {
      const tbody = $('#subjectsTableBody');
      tbody.empty();

      if (subjects.length === 0) {
        tbody.append('<tr><td colspan="4" style="text-align:center;">No subjects found for selected grade.</td></tr>');
        return;
      }

      subjects.forEach(function(subject) {
        const cleanName = subject.toLowerCase().replace(/\s+/g, '-'); // safe for input names
        const row = `
          <tr>
            <td>${subject}</td>
            <td><input type="checkbox" name="subjects[]" value="${subject}"></td>
            <td>
              <select name="${cleanName}-current" class="form-control">
                <option value="1" selected>1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
              </select>
            </td>
            <td>
              <select name="${cleanName}-target" class="form-control">
                <option value="1">1</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7" selected>7</option>
              </select>
            </td>

          </tr>
        `;
        tbody.append(row);
      });
    }, 'json').fail(function() {
      alert('Failed to load subjects');
    });
  });

  function clearSubjectsTable() {
    $('#subjectsTableBody').html('<tr><td colspan="4" style="text-align:center;">Please select a school and grade to load subjects.</td></tr>');
  }
});
</script>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>
</body>
</html>
