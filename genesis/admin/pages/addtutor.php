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
                    <div class="col-md-3">
                      <label for="name">First Name</label>
                      <input type="text" class="form-control" id="name" name="name" placeholder="Enter first name" required>
                    </div>
                    <div class="col-md-3">
                      <label for="surname">Surname</label>
                      <input type="text" class="form-control" id="surname" name="surname" placeholder="Enter surname" required>
                    </div>
                    <div class="col-md-3">
                      <label for="email">Email</label>
                      <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                    </div>
                    
                    <div class="col-md-3">
                      <label for="contactnumber">Contact Number</label>
                      <input type="tel" class="form-control" id="contactnumber" name="contactnumber" pattern="[0-9]{10}" maxlength="10" required>
                    </div> 
                    
                  </div>
                  <div class="form-group row">
                    <div class="col-md-3">
                      <label for="tutortitle">Title</label>
                      <select class="form-control" id="tutortitle" name="tutortitle" required>
                        <option value="">Select Title</option>
                        <option value="Mr">Mr.</option>
                        <option value="Mrs">Mrs.</option>
                        <option value="Ms">Ms.</option>
                        <option value="Dr">Dr.</option>
                      </select>
                    </div>
                    
                    <div class="col-md-3">
                      <label>Set Password</label>
                      <input type="password" class="form-control" name="password" placeholder="Password" required>
                    </div>
                  </div>
                </fieldset><br>


                <!-- Subject & Grade Selection -->
                <fieldset class="tab">
                  <legend>Subjects & Grades</legend>
                  <p>Select all subjects and grades the tutor teaches:</p>
                  
                                <div class="box-body">
                                    <div class="row">
                                    <?php
                                    $allSubjects = $connect->query("
                                        SELECT s.SubjectId, s.SubjectName, g.GradeName
                                        FROM subjects s
                                        JOIN grades g ON s.GradeId = g.GradeId
                                        ORDER BY g.GradeName, s.SubjectName
                                    ");
                                    while($sub = $allSubjects->fetch_assoc()){
                                       // $checked = in_array($sub['SubjectId'], $tutorSubjects) ? 'checked' : '';
                                        echo "
                                        <div class='col-md-4'>
                                            <div class='checkbox'>
                                                <label>
                                                    <input type='checkbox' name='subjects[]' value='{$sub['SubjectId']}'>
                                                    
                                                    {$sub['GradeName']} - {$sub['SubjectName']}
                                                </label>
                                            </div>
                                        </div>";
                                    }
                                    ?>
                                    </div>
                                </div>


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
<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>


<?php
    if (isset($_SESSION['success'])) {
        $msg = $_SESSION['success'];
        unset($_SESSION['success']);
        echo "
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Tutor Registered',
                text: '". addslashes($msg) ."',
                showDenyButton: true,
                confirmButtonText: 'Assign to Class Now!',
                denyButtonText: 'Ok, Back'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'assigntutorclass.php';
                } else if (result.isDenied) {
                    window.history.back();
                }
            });
        </script>";

    }

    if (isset($_SESSION['error'])) {
        $msg = $_SESSION['error'];
        unset($_SESSION['error']);
        echo "
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Failed to Add Tutor',
                text: '". addslashes($msg) ."',
                confirmButtonText: 'OK'
            });
        </script>";
    }
   
  ?>

<script>
  // Map subject IDs to friendly names for confirmation //must be dynamic
  const subjectMap = {
    1: "Mathematics (Grade 10)",
    2: "Mathematics (Grade 11)",
    3: "Mathematics (Grade 12)",
    4: "Physical Sciences (Grade 10)",
    5: "Physical Sciences (Grade 11)",
    6: "Physical Sciences (Grade 12)",
    
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

</body>
</html>
