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
        <h1>Tutor_Class Pairing <small>Select class and assign a tutor</small></h1>
        <ol class="breadcrumb">
            <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Tutor Assignment</li>
        </ol>
    </section>

    <section class="content">
      <div class="row">
        <!-- Form Column -->
        <div class="col-md-5">
          <div class="box box-primary">
            <div class="box-body">
              <form action="assigntutorclass.php" method="POST">

                <!-- Class Dropdown -->
                <div class="form-group">
                  <label for="class">Select Class:</label>
                  <select name="classId" id="classSelect" class="form-control" required>
                    <option value="">-- Select Class --</option>
                    <?php
                    $classes = $connect->query("
                      SELECT c.ClassID, c.Grade, c.GroupName, c.TutorID, s.SubjectId, s.SubjectName, s.DefaultTutorId
                      FROM classes c
                      JOIN subjects s ON c.SubjectID = s.SubjectId
                      ORDER BY c.Grade, c.GroupName
                    ");

                    while ($class = $classes->fetch_assoc()) {
                        $assigned = ($class['TutorID'] != $class['DefaultTutorId']) ? "✅" : "❌";
                        $label = "{$assigned} {$class['Grade']} - Group {$class['GroupName']} ({$class['SubjectName']})";
                        echo "<option value='{$class['ClassID']}' 
                                data-subject='{$class['SubjectId']}' 
                                data-grade='{$class['Grade']}'>
                                {$label}
                              </option>";
                    }
                    ?>
                  </select>
                </div>

                <!-- Tutor Dropdown -->
                
                <div class="form-group">
                  <label for="tutor">Select Tutor:</label>
                  <select name="tutorId" id="tutorSelect" class="form-control" required disabled>
                    <option value="">-- Select Tutor --</option>
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
                    <th>Subject</th>
                    <th>Tutor</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $assignments = $connect->query("
                    SELECT c.Grade, c.GroupName, c.TutorID, s.SubjectName, s.DefaultTutorId, u.Name, u.Surname
                    FROM classes c
                    JOIN subjects s ON c.SubjectID = s.SubjectId
                    LEFT JOIN users u ON c.TutorID = u.Id
                    ORDER BY c.Grade, c.GroupName
                  ");
                  while ($row = $assignments->fetch_assoc()) {
                      $tutorName = $row['Name'] ? "{$row['Name']} {$row['Surname']}" : "<i>Unassigned</i>";
                      $status = ($row['TutorID'] != $row['DefaultTutorId']) ? "✅ Assigned" : "❌ Default";
                      echo "<tr>
                              <td>{$row['Grade']} - Group {$row['GroupName']}</td>
                              <td>{$row['SubjectName']}</td>
                              <td>{$tutorName}</td>
                              <td>{$status}</td>
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

<script>
$(document).ready(function() {
    $('#classSelect').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const subjectId = selectedOption.data('subject');
        const gradeName = selectedOption.data('grade');
        const tutorSelect = $('#tutorSelect');

        // Reset tutor dropdown
        tutorSelect.empty().append('<option value="">-- Select Tutor --</option>');

        if(subjectId && gradeName){
            $.ajax({
                url: 'fetch_tutors.php',
                method: 'GET',
                data: { subjectId: subjectId, gradeName: gradeName },
                dataType: 'json',
                success: function(response) {
                    if(response.length){
                        response.forEach(tutor => {
                            tutorSelect.append(`<option value="${tutor.Id}">${tutor.Name} ${tutor.Surname}</option>`);
                        });
                        tutorSelect.prop('disabled', false); // enable dropdown
                    } else {
                        tutorSelect.append('<option value="">No tutors available</option>');
                        tutorSelect.prop('disabled', true); // keep disabled if none
                    }
                }
            });
        } else {
            tutorSelect.prop('disabled', true); // keep disabled if no class
        }
    });
});

</script>


</body>
</html>
