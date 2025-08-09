<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../../common/pages/login.php");
  exit();
}

include(__DIR__ . "/../../common/partials/head.php");
include(__DIR__ . "/../../partials/connect.php");

$schoolId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($schoolId <= 0) {
    echo "<script>alert('Invalid or missing school ID in URL'); window.location.href='updateschoollist.php';</script>";
    exit();
}

$stmt = $connect->prepare("SELECT * FROM schools WHERE SchoolId = ?");
if (!$stmt) {
    die("Prepare failed: " . $connect->error);
}
$stmt->bind_param("i", $schoolId);
$stmt->execute();
$result = $stmt->get_result();
if (!$result) {
    die("Get result failed: " . $stmt->error);
}
$school = $result->fetch_assoc();
$stmt->close();

if (!$school) {
    echo "<script>alert('No school found with ID $schoolId'); window.location.href='updateschoollist.php';</script>";
    exit();
}

// Store school details in variables for later use
$schoolName = $school['SchoolName'];
$schoolEmail = $school['Email'];
$schoolContact = $school['ContactNumber'];
$schoolAddress = $school['Address'];

// Fetch all subjects grouped by grade
$allSubjects = [];
$stmt = $connect->prepare("
  SELECT g.GradeId, g.GradeName, s.SubjectName
  FROM grades g
  JOIN subjects s ON g.GradeId = s.GradeId
  WHERE g.SchoolId = ?
  ORDER BY g.GradeName ASC, s.SubjectName ASC
");
$stmt->bind_param("i", $schoolId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
  $allSubjects[] = $row;
}
$stmt->close();

$grouped = [];
foreach ($allSubjects as $row) {
  $gradeId = $row['GradeId'];
  if (!isset($grouped[$gradeId])) {
    $grouped[$gradeId] = [
      'GradeName' => $row['GradeName'],
      'Subjects' => []
    ];
  }
  $grouped[$gradeId]['Subjects'][] = $row['SubjectName'];
}
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

<?php include(__DIR__ . "/../partials/header.php"); ?>
<?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

<div class="content-wrapper">
  <section class="content-header">
    <h1>Update School Details <small>Manage school profile and subjects</small></h1>
    <ol class="breadcrumb">
      <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Update School</li>
    </ol>
  </section><br>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-danger">
          <div class="box-header with-border">
            <h3 class="box-title">Edit School Information</h3>
          </div>

          <form role="form" action="updateschoolhandler.php" method="post">
            <input type="hidden" name="school_id" value="<?php echo $schoolId; ?>">

            <div class="box-body">

              <fieldset style="border:1px solid #ddd; padding:15px; margin-bottom:20px;">
                <legend><strong>School Information</strong></legend>
                <div class="row">
                  <div class="form-group col-md-4">
                    <label for="schoolname">School Name</label>
                    <input type="text" class="form-control" id="schoolname" name="schoolname" value="<?php echo htmlspecialchars($schoolName); ?>" required>
                  </div>
                  <div class="form-group col-md-4">
                    <label for="schoolemail">Email</label>
                    <input type="email" class="form-control" id="schoolemail" name="schoolemail" value="<?php echo htmlspecialchars($schoolEmail); ?>" required>
                  </div>
                  <div class="form-group col-md-4">
                    <label for="schoolcontact">Contact Number</label>
                    <input type="tel" class="form-control" id="schoolcontact" name="schoolcontact" value="<?php echo htmlspecialchars($schoolContact); ?>" pattern="[0-9]{10}" maxlength="10" required>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-md-12">
                    <label for="schooladdress">Address</label>
                    <textarea class="form-control" id="schooladdress" name="schooladdress" rows="3" required><?php echo htmlspecialchars($schoolAddress); ?></textarea>
                  </div>
                </div>
              </fieldset>

              <fieldset style="border:1px solid #ddd; padding:15px; margin-bottom:20px;">
                <legend><strong>Subjects by Grade</strong></legend>

                <?php foreach ($grouped as $gradeId => $grade): ?>
                  <h4><?php echo htmlspecialchars($grade['GradeName']); ?></h4>
                  <ul>
                    <?php foreach ($grade['Subjects'] as $subjectName): ?>
                      <li><?php echo htmlspecialchars($subjectName); ?></li>
                    <?php endforeach; ?>
                  </ul>
                <?php endforeach; ?>

              </fieldset>

            </div>

            <div class="box-footer text-center" style="margin-top:15px;">
              <button type="submit" name="update_details" class="btn btn-lg btn-primary">
                <i class="fa fa-save"></i> Update School Details
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>

<div class="control-sidebar-bg"></div>
</div>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>
</body>
</html>
