<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}

include("../partials/connect.php");
include("adminpartials/head.php");

// Get Tutor ID from URL
$tutorId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($tutorId === 0) {
  echo "<script>alert('Invalid tutor ID'); window.location.href='managetutors.php';</script>";
  exit();
}

// Fetch tutor personal and professional info
$tutor = [];
$stmt = $connect->prepare("
  SELECT u.Name, u.Surname, u.Email, u.Contact, u.Gender,
         t.Bio, t.Qualifications, t.ExperienceYears, t.Availability
  FROM users u
  LEFT JOIN tutors t ON u.Id = t.TutorId
  WHERE u.Id = ?
  LIMIT 1
");
$stmt->bind_param("i", $tutorId);
$stmt->execute();
$result = $stmt->get_result();
$tutor = $result->fetch_assoc();

// Fetch all subjects
$allSubjects = [];
$stmtAll = $connect->prepare("SELECT SubjectId, SubjectName, Grade FROM subjects ORDER BY SubjectName, Grade");
$stmtAll->execute();
$resAll = $stmtAll->get_result();
while ($row = $resAll->fetch_assoc()) {
    $allSubjects[] = $row;
}
$stmtAll->close();

// Fetch registered subject IDs
$registeredIds = [];
$stmtReg = $connect->prepare("SELECT SubjectId FROM tutorsubject WHERE TutorId = ?");
$stmtReg->bind_param("i", $tutorId);
$stmtReg->execute();
$resReg = $stmtReg->get_result();
while ($row = $resReg->fetch_assoc()) {
    $registeredIds[] = $row['SubjectId'];
}
$stmtReg->close();
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include("adminpartials/header.php"); ?>
  <?php include("adminpartials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Update Tutor Details <small>Manage tutor profile information</small></h1>
      <ol class="breadcrumb">
        <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Update Tutor</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Edit Tutor Information</h3>
            </div>

            <!-- SINGLE FORM -->
            <form role="form" action="updatetutorhandler.php" method="post" enctype="multipart/form-data">
              <input type="hidden" name="tutor_id" value="<?php echo $tutorId; ?>">

              <div class="box-body">

                <!-- Personal Info -->
                <fieldset style="border:1px solid #ddd; padding:15px; margin-bottom:20px;">
                  <legend><strong>Personal Information</strong></legend>
                  <div class="row">
                    <div class="form-group col-md-3">
                      <label for="firstname">First Name</label>
                      <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo htmlspecialchars($tutor['Name']); ?>" required>
                    </div>
                    <div class="form-group col-md-3">
                      <label for="surname">Surname</label>
                      <input type="text" class="form-control" id="surname" name="surname" value="<?php echo htmlspecialchars($tutor['Surname']); ?>" required>
                    </div>
                    <div class="form-group col-md-3">
                      <label for="email">Email</label>
                      <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($tutor['Email']); ?>" required>
                    </div>
                    <div class="form-group col-md-3">
                      <label for="contactnumber">Contact Number</label>
                      <input type="tel" class="form-control" id="contactnumber" name="contactnumber" value="<?php echo htmlspecialchars($tutor['Contact']); ?>" pattern="[0-9]{10}" maxlength="10" required>
                    </div>
                  </div>
                </fieldset>

                <!-- Professional Details - READONLY with form-control-plaintext -->
                <!-- Professional Details - READONLY with form-control-plaintext -->
<fieldset style="border:1px solid #ddd; padding:15px; margin-bottom:20px;">
  <legend><strong>Professional Details (Read-Only)</strong></legend>
  <div class="row">
    <!-- Bio -->
    <div class="form-group col-md-4">
      <label for="bio">Short Bio</label>
      <textarea class="form-control" id="bio" rows="5" readonly style="background:#f5f5f5; cursor:not-allowed;"><?php echo htmlspecialchars($tutor['Bio']); ?></textarea>
    </div>

    <!-- Qualifications -->
    <div class="form-group col-md-4">
      <label for="qualifications">Qualifications</label>
      <textarea class="form-control" id="qualifications" rows="5" readonly style="background:#f5f5f5; cursor:not-allowed;"><?php echo htmlspecialchars($tutor['Qualifications']); ?></textarea>
    </div>

    <!-- Experience and Availability (right side) -->
    <div class="form-group col-md-4">
      <div class="form-group">
        <label for="experience_years">Years of Experience</label>
        <input type="text" class="form-control form-control-plaintext" id="experience_years" value="<?php echo htmlspecialchars($tutor['ExperienceYears']); ?>" readonly style="background:#f5f5f5; cursor:not-allowed;">
      </div>
      <div class="form-group">
        <label for="availability">Availability</label>
        <input type="text" class="form-control form-control-plaintext" id="availability" value="<?php echo htmlspecialchars($tutor['Availability']); ?>" readonly style="background:#f5f5f5; cursor:not-allowed;">
      </div>
    </div>
  </div>
</fieldset>


                <!-- Manage Tutor Subjects -->
                <fieldset style="border:1px solid #ddd; padding:15px; margin-bottom:20px;">
                  <legend><strong>Manage Tutor Subjects</strong></legend>

                  <div class="row">
                    <?php foreach ($allSubjects as $subject): 
                      $checked = in_array($subject['SubjectId'], $registeredIds) ? 'checked' : '';
                      $subjectLabel = htmlspecialchars($subject['SubjectName']) . " - Grade " . htmlspecialchars($subject['Grade']);
                    ?>
                      <div class="form-group col-md-4">
                        <div class="checkbox">
                          <label>
                            <input type="checkbox" name="subject_ids[]" value="<?php echo $subject['SubjectId']; ?>" <?php echo $checked; ?>>
                            <?php echo $subjectLabel; ?>
                          </label>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  </div>

                </fieldset>

              </div>

              <div class="box-footer text-center" style="margin-top:15px;">
                <button type="submit" name="update_details" class="btn btn-lg btn-primary"><i class="fa fa-save"></i> Update Details & Subjects</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<!-- Scripts -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>

</body>
</html>
