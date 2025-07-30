<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
  exit();
}
?>
<?php include(__DIR__ . "/../../common/partials/head.php"); 
 ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <?php include(__DIR__ . "/../partials/header.php"); ?>
    <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  <div class="content-wrapper">
      <section class="content-header">
        <h1>Tutor Registration  <small>Form</small></h1>
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
              <form id="tutorForm" action="addtutorh.php" method="post">

                <!-- Tutor Info -->
                <fieldset class="tab">
                  <legend>Tutor Info</legend>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <label for="name">First Name</label>
                      <input type="text" class="form-control" id="name" name="name" placeholder="Enter first name" required>
                    </div>
                    <div class="col-md-6">
                      <label for="surname">Surname</label>
                      <input type="text" class="form-control" id="surname" name="surname" placeholder="Enter surname" required>
                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="col-md-4">
                      <label for="email">Email</label>
                      <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                    </div>
                    <div class="col-md-4">
                      <label for="contactnumber">Contact Number</label>
                      <input type="tel" class="form-control" id="contactnumber" name="contactnumber" pattern="[0-9]{10}" maxlength="10" required>
                    </div>
                    <div class="col-md-4">
                      <label for="tutortitle">Title</label>
                      <select class="form-control" id="tutortitle" name="tutortitle" required>
                        <option value="">Select Title</option>
                        <option value="Mr">Mr.</option>
                        <option value="Mrs">Mrs.</option>
                        <option value="Ms">Ms.</option>
                        <option value="Dr">Dr.</option>
                      </select>
                    </div>
                  </div>
                </fieldset><br>

                <!-- Subject & Grade Selection -->
                <fieldset class="tab">
                  <legend>Subjects & Grades</legend>
                  <p>Select all subjects and grades the tutor teaches:</p>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Subject</th>
                        <th>Grade 10</th>
                        <th>Grade 11</th>
                        <th>Grade 12</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Mathematics</td>
                        <td><input type="checkbox" name="subjects[]" value="1"></td>
                        <td><input type="checkbox" name="subjects[]" value="2"></td>
                        <td><input type="checkbox" name="subjects[]" value="3"></td>
                      </tr>
                      <tr>
                        <td>Physical Sciences</td>
                        <td><input type="checkbox" name="subjects[]" value="4"></td>
                        <td><input type="checkbox" name="subjects[]" value="5"></td>
                        <td><input type="checkbox" name="subjects[]" value="6"></td>
                      </tr>
                      <!-- Add more subjects with unique IDs as needed -->
                    </tbody>
                  </table>
                </fieldset><br>

                <!-- Submit Button -->
                <div class="text-center" style="margin-top: 20px;">
                  <button type="button" id="submitBtn" class="btn btn-primary">Register Tutor</button>
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

<!-- Scripts -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  // Map subject IDs to friendly names for confirmation
  const subjectMap = {
    1: "Mathematics (Grade 10)",
    2: "Mathematics (Grade 11)",
    3: "Mathematics (Grade 12)",
    4: "Physical Sciences (Grade 10)",
    5: "Physical Sciences (Grade 11)",
    6: "Physical Sciences (Grade 12)"
  };

  document.getElementById('submitBtn').addEventListener('click', () => {
    const form = document.getElementById('tutorForm');
    const checkedBoxes = [...form.querySelectorAll('input[name="subjects[]"]:checked')];
    
    if (checkedBoxes.length === 0) {
      Swal.fire({
        icon: 'warning',
        title: 'No Subjects Selected',
        text: 'Please select at least one subject.',
      });
      return;
    }

    let summaryHtml = '';
    checkedBoxes.forEach(cb => {
      summaryHtml += subjectMap[cb.value] + '<br>';
    });

    Swal.fire({
      title: 'Confirm Tutor Registration',
      html: `For the following Subjects:<br>${summaryHtml}`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Yes, Register',
      cancelButtonText: 'Cancel'
    }).then(result => {
      if (result.isConfirmed) {
        form.submit();
      }
    });
  });
</script>

<script src="../../common/dist/js/demo.js"></script> 
</body>
</html>
