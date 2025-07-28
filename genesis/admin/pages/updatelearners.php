<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../../common/login.php");
  exit();
}

include("../../partials/connect.php");
include("../adminpartials/head.php");

// Get Learner ID from URL
$LearnerId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($LearnerId === 0) {
  echo "<script>alert('Invalid tutor ID'); window.location.href='managetutors.php';</script>";
  exit();
}

// Fetch tutor personal and professional info
$Learner = [];
$stmt = $connect->prepare("
  SELECT 
    u.Name, u.Surname, u.Email, u.Contact, u.Gender,
    t.Bio, t.Qualifications, t.ExperienceYears, t.Availability,
    l.ParentName, l.ParentSurname, l.ParentEmail, l.ParentContactNumber
  FROM users u
  LEFT JOIN tutors t ON u.Id = t.TutorId
  LEFT JOIN learners l ON u.Id = l.LearnerId
  WHERE u.Id = ?
  LIMIT 1
");

$stmt->bind_param("i", $LearnerId);
$stmt->execute();
$result = $stmt->get_result();
$Learner = $result->fetch_assoc();

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
$stmtReg = $connect->prepare("SELECT SubjectId FROM learnersubject WHERE LearnerId = ?");
$stmtReg->bind_param("i", $LearnerId);
$stmtReg->execute();
$resReg = $stmtReg->get_result();
while ($row = $resReg->fetch_assoc()) {
    $registeredIds[] = $row['SubjectId'];
}
$stmtReg->close();
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include("../adminpartials/header.php"); ?>
  <?php include("../adminpartials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Update Learner Details <small>Manage Learner profile information</small></h1>
      <ol class="breadcrumb">
        <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Update Learner</li>
      </ol>
    </section>
  <?php $LearnerId = isset($_GET['id']) ? intval($_GET['id']) : 0; ?>

    
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Edit Learner Information</h3>
            </div>

            <!-- SINGLE FORM -->
            <form role="form" action="updatellearnerhandler.php" method="post" enctype="multipart/form-data">
              <input type="hidden" name="learner_id" value="<?php echo $LearnerId; ?>">

              <div class="box-body">

                <!-- Personal Info -->
                <fieldset style="border:1px solid #ddd; padding:15px; margin-bottom:20px;">
                  <legend><strong>Personal Information</strong></legend>
                  <div class="row">
                    <div class="form-group col-md-3">
                      <label for="firstname">First Name</label>
                      <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo htmlspecialchars($Learner['Name']); ?>" required>
                    </div>
                    <div class="form-group col-md-3">
                      <label for="surname">Surname</label>
                      <input type="text" class="form-control" id="surname" name="surname" value="<?php echo htmlspecialchars($Learner['Surname']); ?>" required>
                    </div>
                    <div class="form-group col-md-3">
                      <label for="email">Email</label>
                      <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($Learner['Email']); ?>" required>
                    </div>
                    <div class="form-group col-md-3">
                      <label for="contactnumber">Contact Number</label>
                      <input type="tel" class="form-control" id="contactnumber" name="contactnumber" value="<?php echo htmlspecialchars($Learner['Contact']); ?>" pattern="[0-9]{10}" maxlength="10" required>
                    </div>

                    <div class="form-group col-md-3">
                      <label for="parentfirstname">Parent First Name</label>
                      <input type="text" class="form-control" id="parentfirstname" name="parentfirstname" value="<?php echo htmlspecialchars($Learner['ParentName']); ?>" required>
                    </div>
                    <div class="form-group col-md-3">
                      <label for="parentsurname">Parent Surname</label>
                      <input type="text" class="form-control" id="parentsurname" name="parentsurname" value="<?php echo htmlspecialchars($Learner['ParentSurname']); ?>" required>
                    </div>
                    <div class="form-group col-md-3">
                      <label for="parentemail">Parent Email</label>
                      <input type="email" class="form-control" id="parentemail" name="parentemail" value="<?php echo htmlspecialchars($Learner['ParentEmail']); ?>" required>
                    </div>
                    <div class="form-group col-md-3">
                      <label for="parentcontactnumber">Parent Contact Number</label>
                      <input type="tel" class="form-control" id="parentcontactnumber" name="parentcontactnumber" value="<?php echo htmlspecialchars($Learner['ParentContactNumber']); ?>" pattern="[0-9]{10}" maxlength="10" required>
                    </div>
                  </div>
                </fieldset>

                


                <!-- Manage Tutor Subjects -->
                <fieldset style="border:1px solid #ddd; padding:15px; margin-bottom:20px;">
                  <legend><strong>Manage Learner Subjects</strong></legend>

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
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>

 <?php include("../adminpartials/queries.php") ;?>
    <script src="../dist/js/demo.js"></script>

</body>
</html>
