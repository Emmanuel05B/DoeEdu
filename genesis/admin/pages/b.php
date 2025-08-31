
<?php



if ($action === "RegisterNewSubject") {
    $newSub = $_POST['NewSubject'] ?? [];
    $subjectId = $newSub['SubjectId'] ?? 0;

    if ($subjectId) {
        // Check if learner is already registered for this subject
        $stmtCheck = $connect->prepare("
            SELECT COUNT(*) AS cnt 
            FROM learnersubject 
            WHERE LearnerId = ? AND SubjectId = ?
        ");
        $stmtCheck->bind_param("ii", $learnerId, $subjectId);
        $stmtCheck->execute();
        $res = $stmtCheck->get_result()->fetch_assoc();
        $stmtCheck->close();

        if ($res['cnt'] > 0) {
            header("Location: updatelearner.php?id=$learnerId&error=already_registered");
            exit();
        }

        try {
            // Start transaction
            $connect->begin_transaction();

            // ------------------------
            // INSERT NEW SUBJECT
            // ------------------------
            $Status = 'Active';
            $gradeName = $newSub['GradeName'];

            $stmt = $connect->prepare("
                INSERT INTO learnersubject 
                (LearnerId, SubjectId, TargetLevel, CurrentLevel, ContractStartDate, ContractExpiryDate, ContractFee, Status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param(
                "iiisssds",
                $learnerId,
                $subjectId,
                $newSub['TargetLevel'],
                $newSub['CurrentLevel'],
                $newSub['ContractStartDate'],
                $newSub['ContractExpiryDate'],
                $newSub['ContractFee'],
                $Status
            );
            $stmt->execute();
            $stmt->close();

            // ------------------------
            // CLASS ASSIGNMENT
            // ------------------------
            $stmtSub = $connect->prepare("
                SELECT DefaultTutorId, MaxClassSize 
                FROM subjects WHERE SubjectId = ?
            ");
            $stmtSub->bind_param("i", $subjectId);
            $stmtSub->execute();
            $subRes = $stmtSub->get_result()->fetch_assoc();
            $stmtSub->close();

            $maxLearnersPerClass = $subRes['MaxClassSize'] ?? 5;
            $tutorId             = $subRes['DefaultTutorId'] ?? 25;

            $stmtClass = $connect->prepare("
                SELECT ClassID, CurrentLearnerCount 
                FROM classes 
                WHERE SubjectID = ? AND Grade = ? AND Status != 'Full' 
                ORDER BY CreatedAt ASC 
                LIMIT 1
            ");
            $stmtClass->bind_param("ii", $subjectId, $gradeName);
            $stmtClass->execute();
            $resultClass = $stmtClass->get_result();

            if ($resultClass->num_rows > 0) {
                $class     = $resultClass->fetch_assoc();
                $classId   = (int)$class['ClassID'];
                $newCount  = ((int)$class['CurrentLearnerCount']) + 1;
                $classStat = ($newCount >= $maxLearnersPerClass) ? 'Full' : 'Not Full';

                $update = $connect->prepare("UPDATE classes SET CurrentLearnerCount = ?, Status = ? WHERE ClassID = ?");
                $update->bind_param("isi", $newCount, $classStat, $classId);
                $update->execute();
                $update->close();  
                    
            } else {
                $stmtGroup = $connect->prepare("
                    SELECT GroupName 
                    FROM classes 
                    WHERE SubjectID = ? AND Grade = ? 
                    ORDER BY GroupName DESC 
                    LIMIT 1
                ");
                $stmtGroup->bind_param("is", $subjectId, $gradeName);
                $stmtGroup->execute();
                $groupResult = $stmtGroup->get_result();

                if ($groupResult->num_rows > 0) {
                    $lastGroupName = $groupResult->fetch_assoc()['GroupName'];
                    $newGroupName = chr(ord($lastGroupName) + 1); // A → B → C, etc.
                } else {
                    $newGroupName = 'A';
                }
                $stmtGroup->close();

                $classStat = 'Not Full';
                $newCount  = 1;

                $insertClass = $connect->prepare("
                    INSERT INTO classes 
                        (SubjectID, Grade, GroupName, CurrentLearnerCount, TutorID, Status, CreatedAt) 
                    VALUES 
                        (?, ?, ?, ?, ?, ?, NOW())
                ");
                $insertClass->bind_param("ississ", $subjectId, $gradeName, $newGroupName, $newCount, $tutorId, $classStat);
                $insertClass->execute();
                $classId = $connect->insert_id;
                $insertClass->close();
            }

            $assign = $connect->prepare("INSERT INTO learnerclasses (LearnerID, ClassID, AssignedAt) VALUES (?, ?, NOW())");
            $assign->bind_param("ii", $learnerId, $classId);
            $assign->execute();
            $assign->close();

            // ------------------------
            // FINANCES
            // ------------------------
            $stmtTotal = $connect->prepare("
                SELECT SUM(ContractFee - IFNULL(DiscountAmount,0)) AS TotalFees
                FROM learnersubject
                WHERE LearnerId = ?
            ");
            $stmtTotal->bind_param("i", $learnerId);
            $stmtTotal->execute();
            $resultTotal = $stmtTotal->get_result()->fetch_assoc();
            $stmtTotal->close();

            $totalFees = (float)$resultTotal['TotalFees'];

            $insertFin = $connect->prepare("
                INSERT INTO finances (LearnerId, TotalFees, TotalPaid, PaymentStatus, UpdatedAt) 
                VALUES (?, ?, 0, 'Unpaid', NOW())
                ON DUPLICATE KEY UPDATE TotalFees = VALUES(TotalFees)
            ");
            $insertFin->bind_param("id", $learnerId, $totalFees);
            $insertFin->execute();
            $insertFin->close();

            // Commit transaction
            $connect->commit();

        } catch (Exception $e) {
            $connect->rollback(); // Undo all changes
            // Optional: log error or show message
            die("Error registering subject: " . $e->getMessage());
        }
    }

    header("Location: updatelearner.php?id=$learnerId&updated=1");
    exit();
}






























session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../partials/connect.php");

// Learner ID
$learnerId = intval($_GET['id']);

// Fetch learner personal info (from users + learners table)
$learnerData = $connect->query("
    SELECT u.Name, u.Surname, u.Email, u.Contact,
           l.ParentName, l.ParentSurname, l.ParentEmail, l.ParentContactNumber
    FROM users u
    LEFT JOIN learners l ON u.Id = l.LearnerId
    WHERE u.Id = $learnerId
")->fetch_assoc() ?? [];

// Use schoolId = 4 for now
$learnerSchoolId = 4;

// Fetch learner grade name
$learnerRow = $connect->query("SELECT Grade FROM learners WHERE LearnerId = $learnerId")->fetch_assoc();
$learnerGradeName = $learnerRow['Grade'] ?? null;

// Fetch GradeId from grades table
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

// Fetch current subjects of the learner
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
              <div class="col-md-2 mb-2">
                <label>Subject</label>
                <select name="NewSubject[SubjectId]" class="form-control">
                  <option value="">-- Select Subject --</option>
                  <?php foreach($allSubjects as $s): ?>
                    <option value="<?= $s['SubjectId'] ?>"><?= htmlspecialchars($s['SubjectName']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-2 mb-2">
                <label>Current Level</label>
                <input type="number" name="NewSubject[CurrentLevel]" class="form-control" placeholder="1-7">
              </div>
              <div class="col-md-2 mb-2">
                <label>Target Level</label>
                <input type="number" name="NewSubject[TargetLevel]" class="form-control" placeholder="1-7">
              </div>
              <div class="col-md-2 mb-2">
                <label>Start Date</label>
                <input type="date" name="NewSubject[ContractStartDate]" class="form-control">
              </div>
              <div class="col-md-2 mb-2">
                <label>End Date</label>
                <input type="date" name="NewSubject[ContractExpiryDate]" class="form-control">
              </div>
              <div class="col-md-2 mb-2">
                <label>Fee</label>
                <input type="number" step="0.01" name="NewSubject[ContractFee]" class="form-control" placeholder="R">
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

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>
</body>
</html>
