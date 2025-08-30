<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../partials/connect.php");

// Fetch all subjects (for now, assuming schoolId = 4)
$schoolId = 4;
$stmt = $connect->prepare("SELECT SubjectId, SubjectName, Price3Months, Price6Months, Price12Months 
                           FROM subjects WHERE SchoolId = ? ORDER BY SubjectName");
$stmt->bind_param("i", $schoolId);
$stmt->execute();
$subjects = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch system defaults
$defaultsRes = $connect->query("SELECT SettingKey, SettingValue FROM systemsettings");
$defaults = [];
while ($row = $defaultsRes->fetch_assoc()) {
    $defaults[$row['SettingKey']] = $row['SettingValue'];
}
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
      <h1>System Settings <small>Manage Pricing & Default Values</small></h1>
      <ol class="breadcrumb">
        <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Settings</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <!-- SUBJECT PRICING -->
        <div class="col-md-8">
          <h3 style="margin-bottom:15px;">Subject Pricing</h3>
          <div class="row">
          <?php foreach ($subjects as $sub): ?>
            <div class="col-md-6 mb-3">
              <form method="POST" action="updatesubjectprice.php">
                <input type="hidden" name="SubjectId" value="<?= $sub['SubjectId'] ?>">
                <div class="box box-primary" style="border-top:3px solid #3c8dbc;">
                  <div class="box-header with-border" style="background-color:#f0f8ff;">
                    <h3 class="box-title" style="color:#3c8dbc;"><?= htmlspecialchars($sub['SubjectName']) ?></h3>
                  </div>
                  <div class="box-body" style="background-color:#ffffff;">
                    <div class="row">
                      <div class="col-md-4 form-group">
                        <label>3 Months</label>
                        <input type="number" step="0.01" name="Price3Months" 
                               value="<?= $sub['Price3Months'] ?>" class="form-control">
                      </div>
                      <div class="col-md-4 form-group">
                        <label>6 Months</label>
                        <input type="number" step="0.01" name="Price6Months" 
                               value="<?= $sub['Price6Months'] ?>" class="form-control">
                      </div>
                      <div class="col-md-4 form-group">
                        <label>12 Months</label>
                        <input type="number" step="0.01" name="Price12Months" 
                               value="<?= $sub['Price12Months'] ?>" class="form-control">
                      </div>
                      <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary" style="width:120px;">Save</button>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          <?php endforeach; ?>
          </div>
        </div>

        <!-- DEFAULT VALUES -->
        <div class="col-md-4">
          <h3 style="margin-bottom:15px;">Default Values</h3>
          <form method="POST" action="updatedefaults.php">
            <div class="box box-warning" style="border-top:3px solid #f39c12;">
              <div class="box-header with-border" style="background-color:#fff8e1;">
                <h3 class="box-title" style="color:#f39c12;">System Defaults</h3>
              </div>
              <div class="box-body" style="background-color:#ffffff;">
                <div class="form-group">
                  <label>Default Tutor ID</label>
                  <input type="number" class="form-control" 
                         name="DefaultTutorId" value="<?= $defaults['DefaultTutorId'] ?? '' ?>">
                </div>
                <div class="form-group">
                  <label>Default Class Duration (minutes)</label>
                  <input type="number" class="form-control" 
                         name="DefaultClassDuration" value="<?= $defaults['DefaultClassDuration'] ?? '' ?>">
                </div>
                <div class="form-group">
                  <label>Default Pass Mark (%)</label>
                  <input type="number" class="form-control" 
                         name="DefaultPassMark" value="<?= $defaults['DefaultPassMark'] ?? '' ?>">
                </div>
                <div class="form-group">
                  <label>Default Grade Level</label>
                  <input type="number" class="form-control" 
                         name="DefaultGradeLevel" value="<?= $defaults['DefaultGradeLevel'] ?? '' ?>">
                </div>
                <div class="form-group">
                  <label>Default Resource Limit</label>
                  <input type="number" class="form-control" 
                         name="DefaultResourceLimit" value="<?= $defaults['DefaultResourceLimit'] ?? '' ?>">
                </div>
                <div class="form-group">
                  <label>Default Currency</label>
                  <input type="text" class="form-control" 
                         name="DefaultCurrency" value="<?= $defaults['DefaultCurrency'] ?? '' ?>">
                </div>
                <div class="form-group">
                  <label>Default Payment Cycle</label>
                  <select class="form-control" name="DefaultPaymentCycle">
                    <option value="Monthly" <?= ($defaults['DefaultPaymentCycle']??'')==='Monthly'?'selected':'' ?>>Monthly</option>
                    <option value="Quarterly" <?= ($defaults['DefaultPaymentCycle']??'')==='Quarterly'?'selected':'' ?>>Quarterly</option>
                    <option value="Annually" <?= ($defaults['DefaultPaymentCycle']??'')==='Annually'?'selected':'' ?>>Annually</option>
                  </select>
                </div>
              </div>
              <div class="box-footer text-right">
                <button type="submit" class="btn btn-warning">Save Defaults</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </section>
  </div>
</div>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>
</body>
</html>
