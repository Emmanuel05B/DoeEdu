<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}


include(__DIR__ . "/../../partials/connect.php");


$learnerId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch learner personal info
$learnerData = $connect->query("
    SELECT u.Name, u.Surname, u.Email, u.Contact,
           l.ParentName, l.ParentSurname, l.ParentEmail, l.ParentContactNumber
    FROM users u
    LEFT JOIN learners l ON u.Id = l.LearnerId
    WHERE u.Id = $learnerId
")->fetch_assoc() ?? [];

// For now use schoolId=4
$learnerSchoolId = 4;

// Fetch learner grade
$learnerRow = $connect->query("SELECT Grade FROM learners WHERE LearnerId = $learnerId")->fetch_assoc();
$learnerGradeName = $learnerRow['Grade'] ?? null;

// Get GradeId
$learnerGradeId = null;
if ($learnerGradeName) {
    $stmt = $connect->prepare("SELECT GradeId FROM grades WHERE GradeName = ? AND SchoolId = ?");
    $stmt->bind_param("si", $learnerGradeName, $learnerSchoolId);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $learnerGradeId = $res['GradeId'] ?? null;
    $stmt->close();
}

// Fetch subjects for this grade
$allSubjects = [];
if ($learnerGradeId) {
    $stmt = $connect->prepare("SELECT SubjectId, SubjectName FROM subjects WHERE GradeId = ? ORDER BY SubjectName");
    $stmt->bind_param("i", $learnerGradeId);
    $stmt->execute();
    $allSubjects = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// Fetch current subjects
$subjects = $connect->query("
    SELECT ls.LearnerSubjectId, s.SubjectName, ls.ContractStartDate, ls.ContractExpiryDate, 
           ls.ContractFee, ls.Status
    FROM learnersubject ls
    JOIN subjects s ON ls.SubjectId = s.SubjectId
    WHERE ls.LearnerId = $learnerId
    ORDER BY s.SubjectName
")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<?php include(__DIR__ . "/../../common/partials/head.php"); ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
<?php include(__DIR__ . "/../partials/header.php"); ?>
<?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>



<div class="content-wrapper">
<section class="content-header">
    <h1>Update Learner Details <small>Manage Learner profile information</small></h1>
    <ol class="breadcrumb">
        <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Update Learner</li>
    </ol>
    <small>For now Learner ID: <?= $learnerId ?></small>
</section>

<section class="content">

<!-- SUBJECTS FORM -->
<form method="POST" action="savelearnerupdates.php">
    <input type="hidden" name="LearnerId" value="<?= $learnerId ?>">

    <!-- Current Subjects -->
    <div class="row">
    <?php foreach ($subjects as $sub): ?>
        <div class="col-md-6 mb-3">
          <div class="box box-primary" style="border-top:3px solid #3c8dbc;">
            <div class="box-header with-border" style="background-color:#f0f8ff;">
              <h3 class="box-title" style="color:#3c8dbc;"><?= htmlspecialchars($sub['SubjectName']) ?></h3>
            </div>
            <div class="box-body" style="background-color:#ffffff;">
              <div class="row">
                <div class="col-md-6 form-group">
                  <label>Contract Start</label>
                  <input type="date" name="Subjects[<?= $sub['LearnerSubjectId'] ?>][ContractStartDate]" value="<?= $sub['ContractStartDate'] ?>" class="form-control">
                </div>
                <div class="col-md-6 form-group">
                  <label>Contract End</label>
                  <input type="date" name="Subjects[<?= $sub['LearnerSubjectId'] ?>][ContractExpiryDate]" value="<?= $sub['ContractExpiryDate'] ?>" class="form-control">
                </div>
                <div class="col-md-4 form-group">
                  <label>Fee</label>
                  <input type="number" step="0.01" name="Subjects[<?= $sub['LearnerSubjectId'] ?>][ContractFee]" value="<?= $sub['ContractFee'] ?>" class="form-control">
                </div>
                <div class="col-md-4 form-group">
                  <label>Status</label>
                  <select name="Subjects[<?= $sub['LearnerSubjectId'] ?>][Status]" class="form-control">
                    <option value="Active" <?= $sub['Status']=='Active'?'selected':'' ?>>Active</option>
                    <option value="Suspended" <?= $sub['Status']=='Suspended'?'selected':'' ?>>Suspended</option>
                    <option value="Completed" <?= $sub['Status']=='Completed'?'selected':'' ?>>Completed</option>
                    <option value="Cancelled" <?= $sub['Status']=='Cancelled'?'selected':'' ?>>Cancelled</option>
                  </select>
                </div>
                <div class="col-md-4 form-group">
                  <label>Action</label>
                  <select name="Subjects[<?= $sub['LearnerSubjectId'] ?>][Action]" class="form-control">
                    <option value="">-- No Change --</option>
                    <option value="Update">Update</option>
                    <option value="Deregister">Drop</option>
                    <option value="Extend">Extend</option>
                    <option value="CutShort">Cut Short</option>
                  </select>
                </div>
                <div class="col-md-12 text-right">
                  <button type="submit" name="Action" value="UpdateSubject_<?= $sub['LearnerSubjectId'] ?>" class="btn btn-primary" style="width:120px;">Update</button>
                </div>
              </div>
            </div>
          </div>
        </div>
    <?php endforeach; ?>
    </div>

    <!-- Add New Subject -->
    <div class="box box-success" style="border-top:3px solid #00a65a;">
      <div class="box-header with-border" style="background-color:#e6ffed;">
        <h3 class="box-title" style="color:#00a65a;">Add New Subject</h3>
      </div>
      <div class="box-body" style="background-color:#ffffff;">
        <div class="row">
          <input type="hidden" name="NewSubject[GradeName]" value="<?= htmlspecialchars($learnerGradeName ?? '') ?>">

          <div class="col-md-2 mb-2">
            <label>Subject</label>
            <select name="NewSubject[SubjectId]" class="form-control" required>
              <option value="" >-- Select Subject --</option>
              <?php foreach($allSubjects as $s): ?>
                <option value="<?= $s['SubjectId'] ?>"><?= htmlspecialchars($s['SubjectName']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-2 mb-2">
            <label>Current Level</label>
            <input type="number" name="NewSubject[CurrentLevel]" class="form-control" placeholder="1-7" min="1" max="7" required>
          </div>
          <div class="col-md-2 mb-2">
            <label>Target Level</label>
            <input type="number" name="NewSubject[TargetLevel]" class="form-control" placeholder="1-7" min="1" max="7" required>
          </div>
          <div class="col-md-2 mb-2">
            <label>Start Date</label>
            <input type="date" name="NewSubject[ContractStartDate]" class="form-control" required>
          </div>
          <div class="col-md-2 mb-2">
            <label>End Date</label>
            <input type="date" name="NewSubject[ContractExpiryDate]" class="form-control" required>
          </div>
          <div class="col-md-2 mb-2">
            <label>Fee</label>
            <input type="number" step="0.01" name="NewSubject[ContractFee]" class="form-control" placeholder="R" required>
          </div>
          <div class="col-md-12 text-right">
            <button type="submit" name="Action" value="RegisterNewSubject" class="btn btn-success" style="width:120px;">Add</button>
          </div>
        </div>
      </div>
    </div>
</form>

<!-- PERSONAL INFO FORM -->
<form method="POST" action="savelearnerupdates.php" style="margin-top:30px;">
  <input type="hidden" name="LearnerId" value="<?= $learnerId ?>">
  <div class="box box-info" style="border-top:3px solid #00c0ef;">
    <div class="box-header with-border" style="background-color:#e0f7ff;">
      <h3 class="box-title" style="color:#00c0ef;">Update Personal Information</h3>
    </div>
    <div class="box-body">
      <div class="row">
        <div class="col-md-3 form-group">
          <label>First Name</label>
          <input type="text" name="firstname" class="form-control" value="<?= htmlspecialchars($learnerData['Name'] ?? '') ?>" required>
        </div>
        <div class="col-md-3 form-group">
          <label>Surname</label>
          <input type="text" name="surname" class="form-control" value="<?= htmlspecialchars($learnerData['Surname'] ?? '') ?>" required>
        </div>
        <div class="col-md-3 form-group">
          <label>Email</label>
          <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($learnerData['Email'] ?? '') ?>" required>
        </div>
        <div class="col-md-3 form-group">
          <label>Contact Number</label>
          <input type="tel" name="contactnumber" class="form-control" value="<?= htmlspecialchars($learnerData['Contact'] ?? '') ?>" required>
        </div>
        <div class="col-md-3 form-group">
          <label>Parent First Name</label>
          <input type="text" name="parentfirstname" class="form-control" value="<?= htmlspecialchars($learnerData['ParentName'] ?? '') ?>" required>
        </div>
        <div class="col-md-3 form-group">
          <label>Parent Surname</label>
          <input type="text" name="parentsurname" class="form-control" value="<?= htmlspecialchars($learnerData['ParentSurname'] ?? '') ?>" required>
        </div>
        <div class="col-md-3 form-group">
          <label>Parent Email</label>
          <input type="email" name="parentemail" class="form-control" value="<?= htmlspecialchars($learnerData['ParentEmail'] ?? '') ?>" required>
        </div>
        <div class="col-md-3 form-group">
          <label>Parent Contact Number</label>
          <input type="tel" name="parentcontactnumber" class="form-control" value="<?= htmlspecialchars($learnerData['ParentContactNumber'] ?? '') ?>" required>
        </div>
      </div>
    </div>
    <div class="box-footer text-right">
      <button type="submit" name="Action" value="UpdatePersonalInfo" class="btn btn-info" style="width:150px;">Update Personal Info</button>
    </div>
  </div>
</form>

</section>
</div>
</div>

<!-- SweetAlert placeholder for alerts -->
<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>
<?php if(isset($_GET['error'])): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Oops...',
    text: <?= json_encode($_GET['error']) ?>,
    confirmButtonText: 'Okay'
});
</script>
<?php endif; ?>

<?php if(isset($_GET['error']) && $_GET['error'] === 'already_registered'): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Oops...',
    text: 'This learner is already registered for the selected subject. Please update the existing subject instead.',
    confirmButtonText: 'Okay'
});
</script>
<?php endif; ?>

<?php if(isset($_GET['updated']) && $_GET['updated']==1): ?>
<script>
Swal.fire({
  icon: 'success',
  title: 'Update Successful',
  text: 'The learner information has been updated.',
  showConfirmButton: true
});
</script>
<?php endif; ?>

</body>
</html>
