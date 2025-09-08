<!DOCTYPE html>
<html>
<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}
include(__DIR__ . "/../../common/partials/head.php"); 
include(__DIR__ . "/../../partials/connect.php");


// ------------------ HANDLER ------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tutorId'], $_POST['classId'])) {
    $tutorId = intval($_POST['tutorId']);
    $classId = intval($_POST['classId']);

    $stmt = $connect->prepare("UPDATE classes SET TutorID = ? WHERE ClassID = ?");
    $stmt->bind_param("ii", $tutorId, $classId);

    if ($stmt->execute()) {
        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => 'Tutor Assigned!',
            'text'  => 'Tutor was successfully assigned to the class.'
        ];
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Error',
            'text'  => 'Failed to assign tutor. Please try again.'
        ];
    }
    $stmt->close();

    header("Location: assigntutorclass.php");
    exit();
}

// ---------------- END HANDLER ----------------
?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    
    <section class="content-header">
        <h1>Tutor_Class Pairing <small>Select tutor and a class to pair</small></h1>
        <ol class="breadcrumb">
            <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Tutor Assignment</li>
        </ol>
    </section>

    <section class="content">
      <div class="row">
        <!-- Form Column  -->
        <div class="col-md-5">
          <div class="box box-primary">
            <div class="box-body">
              <form action="assigntutorclass.php" method="POST">
                <div class="form-group">
                  <label for="tutor">Select Tutor:</label>
                  <select name="tutorId" class="form-control" required>
                    <option value="">-- Select Tutor --</option>
                    <?php
                    $tutors = $connect->query("SELECT u.Id, u.Name, u.Surname FROM users u JOIN tutors t ON u.Id = t.TutorId");
                    while ($tutor = $tutors->fetch_assoc()) {
                        echo "<option value='{$tutor['Id']}'>{$tutor['Name']} {$tutor['Surname']}</option>";
                    }
                    ?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="class">Select Class:</label>
                  <select name="classId" class="form-control" required>
                    <option value="">-- Select Class --</option>
                    <?php
                    $classes = $connect->query("
                      SELECT c.ClassID, c.Grade, c.GroupName, s.SubjectName 
                      FROM classes c 
                      JOIN subjects s ON c.SubjectID = s.SubjectId
                      ORDER BY c.Grade, c.GroupName
                    ");
                    while ($class = $classes->fetch_assoc()) {
                        $label = "Grade {$class['Grade']} - Group {$class['GroupName']} ({$class['SubjectName']})";
                        echo "<option value='{$class['ClassID']}'>{$label}</option>";
                    }
                    ?>
                  </select>
                </div>

                <button type="submit" class="btn btn-primary">Assign Tutor</button>
              </form>
            </div>
          </div>
        </div>

        <!-- Table Column -->
        <div class="col-md-7">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Current Tutor Assignments</h3>
            </div>
            <div class="box-body table-responsive">
              <table class="table table-bordered table-hover">
                <thead style="background-color:#d1d9ff;">
                  <tr>
                    <th>Class</th>
                    <th>Grade</th>
                    <th>Group</th>
                    <th>Subject</th>
                    <th>Tutor</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $assignments = $connect->query("
                    SELECT c.Grade, c.GroupName, s.SubjectName, u.Name, u.Surname
                    FROM classes c
                    JOIN subjects s ON c.SubjectID = s.SubjectId
                    LEFT JOIN users u ON c.TutorID = u.Id
                    ORDER BY c.Grade, c.GroupName
                  ");
                  while ($row = $assignments->fetch_assoc()) {
                      $tutorName = $row['Name'] ? "{$row['Name']} {$row['Surname']}" : "<i>Unassigned</i>";
                      echo "<tr>
                              <td>{$row['Grade']} - Group {$row['GroupName']}</td>
                              <td>{$row['Grade']}</td>
                              <td>{$row['GroupName']}</td>
                              <td>{$row['SubjectName']}</td>
                              <td>{$tutorName}</td>
                            </tr>";
                  }
                  ?>
                </tbody>
              </table>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (isset($_SESSION['alert'])): ?>
<script>
Swal.fire({
  icon: '<?php echo $_SESSION['alert']['type']; ?>',
  title: '<?php echo $_SESSION['alert']['title']; ?>',
  text: '<?php echo $_SESSION['alert']['text']; ?>',
  showConfirmButton: true
});
</script>
<?php unset($_SESSION['alert']); endif; ?>


</body>
</html>
