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

// Get all tutors
$tutors = [];
$sql = "
    SELECT 
        t.TutorId, u.Name, u.Surname, u.Email, u.Contact, u.Gender, t.Availability, t.ProfilePicture, 
        GROUP_CONCAT(DISTINCT s.SubjectName SEPARATOR ', ') AS Subjects
    FROM tutors t
    JOIN users u ON t.TutorId = u.Id
    LEFT JOIN tutorsubject ts ON t.TutorId = ts.TutorId
    LEFT JOIN subjects s ON ts.SubjectId = s.SubjectId
    GROUP BY t.TutorId
";

$result = $connect->query($sql);

if ($result) {
    while ($tutor = $result->fetch_assoc()) {
        $tutors[] = $tutor;
    }
} else {
    die("Query failed: (" . $connect->errno . ") " . $connect->error);
}
?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>All Tutors <small>View, update, or manage all registered tutors in the system.</small></h1>
      <ol class="breadcrumb">
        <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Classes</li>
      </ol>
      <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;">
        <div style="display: flex; gap: 10px; margin-top: 30px;">
          <a href="classes.php" class="btn btn-primary" style="height: fit-content;">Open Classes</a>
          <a href="assigntutorclass.php" class="btn btn-primary" style="height: fit-content;">Assign Tutors to Classes</a>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="row">
        <?php if (empty($tutors)): ?>
          <div class="col-md-12">
            <div class="alert alert-warning text-center">No tutors found in the system.</div>
          </div>
        <?php else: ?>
          <?php foreach ($tutors as $tutor): ?>
            <div class="col-md-4">
              <div class="box box-primary" style="min-height: 350px;">
                <div class="box-header with-border text-center">
                  <img 
                    src="<?= !empty($tutor['ProfilePicture']) ? '../' . htmlspecialchars($tutor['ProfilePicture']) : '../../uploads/doe.jpg' ?>" 
                    alt="Tutor Picture" class="img-circle" width="90" height="90" style="object-fit: cover;">
                  <h3 class="box-title" style="margin-top:10px;">
                    <?= htmlspecialchars($tutor['Gender']) . ' ' . htmlspecialchars($tutor['Surname']) ?>
                  </h3>
                  <p style="word-wrap: break-word; white-space: normal;">
                    <span class="label label-info" style="display: inline-block; max-width: 100%; white-space: normal;">
                      <?= htmlspecialchars($tutor['Subjects'] ?: 'No subjects assigned') ?>
                    </span>
                  </p>                                
                </div>
                <div class="box-body text-center">
                  <p><strong>Name:</strong> <?= htmlspecialchars($tutor['Name']) . ' ' . htmlspecialchars($tutor['Surname']) ?></p>
                  <p><strong>Email:</strong> <?= htmlspecialchars($tutor['Email']) ?></p>
                  <p><strong>Availability:</strong> <?= htmlspecialchars($tutor['Availability']) ?: 'Not specified' ?></p>
                  <hr>
                  <div class="btn-group">
                    <a href="updatetutors.php?id=<?= $tutor['TutorId'] ?>" class="btn btn-sm btn-info">View / Update</a>                    
                    <button 
                      class="btn btn-success btn-sm" 
                      data-toggle="modal" 
                      data-target="#modal-performance"
                      data-tutorid="<?= $tutor['TutorId'] ?>"
                      data-name="<?= htmlspecialchars($tutor['Name'] . ' ' . $tutor['Surname']) ?>"
                    >
                      Performance
                    </button>
                    <button 
                      class="btn btn-primary btn-sm" 
                      data-toggle="modal" 
                      data-target="#modal-contact"
                      data-email="<?= htmlspecialchars($tutor['Email']) ?>"
                    >
                      Contact
                    </button>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<!-- Scripts -->
<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

<!-- Contact Modal (exactly as before) -->
<!-- Contact Modal -->
<div class="modal fade" id="modal-contact" tabindex="-1" role="dialog" aria-labelledby="contactLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">

      <div class="modal-header bg-info">
        <h4 class="modal-title" id="contactLabel">Contact Tutor</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <form action="emailsuperhandler.php" method="post">
          <!-- Hidden inputs for super handler -->
          <input type="hidden" name="action" value="custom">
          <input type="hidden" name="email_type" value="tutor">
          <input type="hidden" name="redirect" value="tutors.php">

          <div class="form-group">
            <input type="email" id="contactEmail" class="form-control" name="recipients[]" placeholder="Email to:" required>
          </div>
          <div class="form-group">
            <input type="text" class="form-control" name="subject" placeholder="Subject" required>
          </div>
          <div class="form-group">
            <textarea class="textarea" name="message" placeholder="Message" style="width: 100%; height: 125px; border: 1px solid #ddd;" required></textarea>
          </div>
          <input type="submit" class="btn btn-primary" value="Send Email">
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
      </div>

    </div>
  </div>
</div>


<!-- Performance Modal (separate, does not affect Contact modal) -->
<div class="modal fade" id="modal-performance" tabindex="-1" role="dialog" aria-labelledby="performanceLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      
      <div class="modal-header bg-success">
        <h4 class="modal-title" id="performanceLabel">Performance Ratings</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <div class="box box-success">
          <div class="box-header">
            <i class="fa fa-bar-chart"></i>
            <h3 class="box-title">Tutor Performance</h3>
          </div>
          <div class="box-body">
            <p><strong>Tutor:</strong> <span id="perfTutorName"></span></p>
            <ul id="perfData">
              <li>Average Rating: ⭐⭐⭐⭐☆ (4.2/5)</li>
              <li>Classes Taught: 15</li>
              <li>Attendance: 98%</li>
              <li>Student Feedback: "Very engaging and clear explanations."</li>
            </ul>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
      </div>
    </div>
  </div>
</div>

<script>
$('#modal-contact').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); 
    var email = button.data('email');  
    var modal = $(this);
    modal.find('#contactEmail').val(email); // populate input
});

$('#modal-performance').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); 
    var tutorid = button.data('tutorid');
    var tutorname = button.data('name');
    var modal = $(this);
    modal.find('#perfTutorName').text(tutorname);
    console.log("Performance modal opened for Tutor ID:", tutorid);
});
</script>

<?php
if (isset($_SESSION['success'])) {
    $successMsg = $_SESSION['success'];
    unset($_SESSION['success']);
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '". addslashes($successMsg) ."',
            confirmButtonText: 'OK'
        });
    </script>";
}

if (isset($_SESSION['error'])) {
    $errorMsg = $_SESSION['error'];
    unset($_SESSION['error']);
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '". addslashes($errorMsg) ."',
            confirmButtonText: 'OK'
        });
    </script>";
}
?>

</body>
</html>
