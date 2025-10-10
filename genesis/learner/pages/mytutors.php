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

$learnerId = $_SESSION['user_id'];
$tutors = [];

// Fetch learner's classes
$classQuery = $connect->prepare("SELECT ClassID FROM learnerclasses WHERE LearnerId = ?");
$classQuery->bind_param("i", $learnerId);
$classQuery->execute();
$classResult = $classQuery->get_result();
$classIds = [];
while ($row = $classResult->fetch_assoc()) {
  $classIds[] = $row['ClassID'];
}
$classQuery->close();

if (!empty($classIds)) {
  $inClause = implode(',', array_map('intval', $classIds));

  $sql = "
      SELECT 
          t.TutorId, 
          u.Name, u.Surname, u.Email, u.Contact, u.Gender, 
          t.Availability, t.ProfilePicture,
          GROUP_CONCAT(DISTINCT s.SubjectName SEPARATOR ', ') AS Subjects
      FROM classes c
      JOIN tutors t ON c.TutorID = t.TutorId
      JOIN users u ON t.TutorId = u.Id
      JOIN subjects s ON c.SubjectID = s.SubjectId
      WHERE c.ClassID IN ($inClause)
      GROUP BY t.TutorId
  ";

  $result = $connect->query($sql);
  while ($tutor = $result->fetch_assoc()) {
    $tutors[] = $tutor;
  }
}
?>
<?php
$alertStatus = $_GET['status'] ?? '';
$alertMessage = $_GET['message'] ?? '';
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>My Tutors <small>Book a session directly from here</small></h1>
    </section>

    <section class="content">
  <div class="row">
    <!-- Tutors -->
    <?php foreach ($tutors as $tutor): ?>
      <div class="col-md-6">
        <div class="box box-primary" style="min-height: 300px;">
          <div class="box-header with-border text-center">
            <img 
              src="<?= !empty($tutor['ProfilePicture']) ? '../' . htmlspecialchars($tutor['ProfilePicture']) : '../../uploads/doe.jpg' ?>" 
              alt="Tutor Picture" class="img-circle" width="90" height="90" style="object-fit: cover;">
            <h3 class="box-title" style="margin-top:10px;">
              <?= htmlspecialchars($tutor['Gender']) . ' ' . htmlspecialchars($tutor['Surname']) ?>
            </h3>
            <p>
              <span class="label label-info"><?= htmlspecialchars($tutor['Subjects']) ?></span>
            </p>
          </div>
          <div class="box-body text-center">
            <p><strong>Email:</strong> <?= htmlspecialchars($tutor['Email']) ?></p>
            <p><strong>Availability:</strong> <?= htmlspecialchars($tutor['Availability']) ?: 'Not specified' ?></p>
            <hr>
            <div class="btn-group">
              <a href="feedback.php?tutor=<?= $tutor['TutorId'] ?>" class="btn btn-sm btn-info">Feedback</a>
              <a href="rate.php?tutor=<?= $tutor['TutorId'] ?>" class="btn btn-sm btn-warning">Rate</a>
              <button 
                class="btn btn-sm btn-primary openBookingModal"
                data-tutor="<?= $tutor['TutorId'] ?>"
                data-name="<?= htmlspecialchars($tutor['Name'] . ' ' . $tutor['Surname']) ?>"
                data-subjects="<?= htmlspecialchars($tutor['Subjects']) ?>"
              >
                Book Session
              </button>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>

    <!-- Bookings Table -->
    <div class="col-md-12">
      <div class="box" style="border-top: 3px solid #3a3a72;">
        <div class="box-header with-border">
          <h3 class="box-title" style="color:#3a3a72; font-weight:600;">My Bookings</h3>
        </div>
        <div class="box-body table-responsive">
          <table class="table table-bordered table-striped">
            <thead>
              <tr style="background-color:#f0f4ff; color:#3a3a72;">
                <th>Date</th>
                <th>Day</th>
                <th>Time</th>
                <th>Tutor</th>
                <th>Subject</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $twoWeeksAgo = (new DateTime())->modify('-14 days')->format('Y-m-d H:i:s');

              $stmt3 = $connect->prepare("
                SELECT ts.SlotDateTime, ts.Subject, ts.Status, u.Name, u.Surname 
                FROM tutorsessions ts
                JOIN users u ON ts.TutorId = u.Id
                WHERE ts.LearnerId = ? AND ts.SlotDateTime >= ?
                ORDER BY ts.SlotDateTime DESC
              ");
              $stmt3->bind_param("is", $learnerId, $twoWeeksAgo);
              $stmt3->execute();
              $res3 = $stmt3->get_result();
              while ($booking = $res3->fetch_assoc()):
                $dt = new DateTime($booking['SlotDateTime']);
                $status = $booking['Status'];
                $color = $status == 'Confirmed' ? '#28a745' : ($status == 'Pending' ? '#f0ad4e' : '#d9534f');
              ?>
                <tr>
                  <td><?= $dt->format('Y-m-d') ?></td>
                  <td><?= $dt->format('l') // full day name ?></td>
                  <td><?= $dt->format('H:i') ?></td>
                  <td><?= htmlspecialchars($booking['Name'] . ' ' . $booking['Surname']) ?></td>
                  <td><?= htmlspecialchars($booking['Subject']) ?></td>
                  <td>
                    <span class="label" style="background-color:<?= $color ?>; color:white; border-radius:4px; padding:3px 8px;">
                      <?= $status ?>
                    </span>
                  </td>
                </tr>
              <?php endwhile; ?>
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

<!-- 📘 Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form id="bookingForm" action="booksession.php" method="POST">
      <div class="modal-content">
        <div class="modal-header bg-blue">
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
          <h4 class="modal-title">Book Session with <span id="tutorName"></span></h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="tutor_id" id="modalTutorId" value="">
          
          <div class="form-group">
            <label>Subject</label>
            <select name="subject" id="modalSubject" class="form-control" required></select>
          </div>

          <div class="form-group">
            <label>Available Slot</label>
            <select name="slot" id="modalSlot" class="form-control" required>
              <option value="">-- Loading available slots --</option>
            </select>
          </div>

          <div class="form-group">
            <label>Additional Notes</label>
            <textarea name="notes" class="form-control" rows="3" placeholder="Optional..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Confirm Booking</button>
        </div>
      </div>
    </form>
  </div>
</div>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

<script>
// Open modal
$('.openBookingModal').on('click', function() {
  const tutorId = $(this).data('tutor');
  const tutorName = $(this).data('name');
  const subjects = $(this).data('subjects').split(',');

  $('#modalTutorId').val(tutorId);
  $('#tutorName').text(tutorName);
  $('#modalSubject').empty();

  subjects.forEach(s => {
    const sub = s.trim();
    $('#modalSubject').append(`<option value="${sub}">${sub}</option>`);
  });

  $('#modalSlot').html('<option>Loading available slots...</option>');

  // Fetch available slots dynamically
  $.get('fetchslots.php', { tutor: tutorId }, function(data) {
    $('#modalSlot').html(data);
  });

  $('#bookingModal').modal('show');
});
</script>

<?php if (!empty($alertStatus) && !empty($alertMessage)): ?>

<script>
Swal.fire({
    icon: '<?= $alertStatus ?>',
    title: '<?= $alertStatus === "success" ? "Success!" : "Oops!" ?>',
    text: '<?= addslashes($alertMessage) ?>'
});
</script>
<?php endif; ?>


</body>
</html>
