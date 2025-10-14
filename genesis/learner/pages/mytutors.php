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
          GROUP_CONCAT(DISTINCT s.SubjectName SEPARATOR ', ') AS Subjects,
          GROUP_CONCAT(DISTINCT c.Grade SEPARATOR ', ') AS Grades
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
        <div class="box box-primary" style="min-height: 280px;">
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
            <p><strong>Email...remove:</strong> <?= htmlspecialchars($tutor['Email']) ?></p>
            <p><strong>Availability...to remove:</strong> <?= htmlspecialchars($tutor['Availability']) ?: 'Not specified' ?></p>
            <hr>
            <div class="btn-group">
              <a href="feedback.php?tutor=<?= $tutor['TutorId'] ?>" class="btn btn-sm btn-info">Feedback</a>
              <a href="rate.php?tutor=<?= $tutor['TutorId'] ?>" class="btn btn-sm btn-warning">Rate</a>
              <button 
                class="btn btn-sm btn-primary openBookingModal"
                data-tutor="<?= $tutor['TutorId'] ?>"
                data-name="<?= htmlspecialchars($tutor['Name'] . ' ' . $tutor['Surname']) ?>"
                data-subjects="<?= htmlspecialchars($tutor['Subjects']) ?>"
                data-grade="<?= htmlspecialchars($tutor['Grades']) ?>" 
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
                <th>Day---Time</th>
                <th>Tutor</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $twoWeeksAgo = (new DateTime())->modify('-14 days')->format('Y-m-d H:i:s');
              /*
              $stmt3 = $connect->prepare("
                SELECT ts.SessionId, ts.SlotDateTime, ts.Subject, ts.Status, ts.MeetingLink, ts.Attendance, 
                      u.Gender, u.Surname
                FROM tutorsessions ts
                JOIN users u ON ts.TutorId = u.Id
                WHERE ts.LearnerId = ? AND ts.SlotDateTime >= ? AND ts.Hidden = 0
                ORDER BY ts.SlotDateTime DESC
              ");  */

              $stmt3 = $connect->prepare("
                SELECT ts.SessionId, ts.SlotDateTime, ts.Subject, ts.Grade, ts.Status, ts.MeetingLink, ts.Attendance, 
                      u.Gender, u.Surname, t.TutorId
                FROM tutorsessions ts
                JOIN users u ON ts.TutorId = u.Id
                JOIN tutors t ON ts.TutorId = t.TutorId
                WHERE ts.LearnerId = ? AND ts.SlotDateTime >= ? AND ts.Hidden = 0
                ORDER BY ts.SlotDateTime DESC
              ");
              if (!$stmt3) {
                  die("Prepare failed: " . $connect->error);
              }

              $stmt3->bind_param("is", $learnerId, $twoWeeksAgo);
              $stmt3->execute();
              $res3 = $stmt3->get_result();

              while ($booking = $res3->fetch_assoc()):
                $dt = new DateTime($booking['SlotDateTime']);
                $status = $booking['Status'];
                $color = match($status) {
                    'Confirmed' => '#28a745',
                    'Pending' => '#f04ec7ff',
                    'Completed' => '#007bff',
                    'Missed' => '#d9534f',
                    default => '#6c757d'
                };
              ?>
              <tr>
                <td><?= $dt->format('Y-m-d') ?></td>
                <td><?= $dt->format('l' . '---' . 'H:i') ?></td>
                <td><?= htmlspecialchars($booking['Gender'] . ' ' . $booking['Surname']) ?></td>
                <td><?= htmlspecialchars($booking['Subject']) ?></td>
                <td>
                  <span class="label" style="background-color:<?= $color ?>; color:white; border-radius:4px; padding:3px 8px;">
                    <?= $status ?>
                  </span>
                </td>
                <td>
                  <?php if($status === 'Confirmed' && !empty($booking['MeetingLink'])): ?>
                    <a href="join.php?sessionid=<?= $booking['SessionId'] ?>" target="_blank" class="btn btn-xs btn-success">
                      <i class="fa fa-video-camera"></i> Join Session
                    </a>

                    <?php elseif($status === 'Confirmed' && empty($booking['MeetingLink'])): ?>
                    <span class="text-warning">
                        ⏳ Link coming soon
                    </span>

                  
                    
                    <?php elseif($status === 'Completed'): ?>
                      <button 
                        class="btn btn-xs btn-primary openFeedbackModal"
                        data-session="<?= $booking['SessionId'] ?>"
                        data-tutor="<?= htmlspecialchars($booking['Gender'] . ' ' . $booking['Surname']) ?>"
                        data-tutorid="<?= $booking['TutorId'] ?>"
                        data-learnerid="<?= $learnerId ?>"
                        data-grade="<?= htmlspecialchars($booking['Grade']) ?>"
                        data-subject="<?= htmlspecialchars($booking['Subject']) ?>"
                      >
                        <i class="fa fa-star"></i> Rate & Feedback
                      </button>




                    <?php elseif($status === 'Missed'): ?>
                        
                        <form method="POST" action="cancelsession.php" class="decline-form" style="display:inline;">
                          <input type="hidden" name="sessionid" value="<?= $booking['SessionId'] ?>">
                          <input type="hidden" name="mode" value="remove_missed">
                          <button type="submit" class="btn btn-xs btn-warning">
                              <i class="fa fa-times"></i> Remove from list
                          </button>
                        </form>

                    <?php else: ?>
                        <form method="POST" action="cancelsession.php" class="decline-form" style="display:inline;">
                            <input type="hidden" name="sessionid" value="<?= $booking['SessionId'] ?>">
                            <input type="hidden" name="mode" value="delete"> 
                            <button type="submit" class="btn btn-xs btn-danger">
                                <i class="fa fa-times"></i> Delete
                            </button>
                        </form>
                    <?php endif; ?>

                  
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

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form id="bookingForm" action="booksession.php" method="POST" enctype="multipart/form-data">

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
            <label>Why request a Session?</label>
            <textarea name="notes" class="form-control" rows="3" placeholder="I need help with ..." required></textarea>
          </div>
          <div class="form-group">
            <label>Attach File (PDF/Image, optional)</label>
            <input type="file" name="attachment" accept=".pdf,image/*" class="form-control">
            <small class="text-muted">This can be a PDF or image to show your questions/topics for discussion.</small>
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

<!-- Feedback Modal -->  
<!-- Feedback Modal -->  
<div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog" aria-labelledby="feedbackModalLabel">
  <div class="modal-dialog" role="document">
    <form id="feedbackForm" method="POST" action="submit_feedback.php">
      <div class="modal-content">
        <div class="modal-header bg-blue">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="feedbackModalLabel">
            <i class="fa fa-comments"></i> Tutor Session Feedback for <span id="feedbackTutorName"></span>
          </h4>
        </div>

        <div class="modal-body">
          <!-- Hidden fields -->
          <input type="hidden" name="SessionId" id="feedbackSessionId">
          <input type="hidden" name="TutorId" id="feedbackTutorId">
          <input type="hidden" name="LearnerId" id="feedbackLearnerId">
          <input type="hidden" name="Grade" id="feedbackGrade">
          <input type="hidden" name="Subject" id="feedbackSubject">

          <p class="text-muted">Please rate your tutor and session experience below:</p>

          <!-- Clarity -->
          <div class="form-group">
            <label>1. How clear were the tutor’s explanations?</label><br>
            <?php for($i=1; $i<=5; $i++): ?>
              <label class="radio-inline">
                <input type="radio" name="Clarity" value="<?= $i ?>" required> <?= $i ?>
              </label>
            <?php endfor; ?>
          </div>

          <!-- Engagement -->
          <div class="form-group">
            <label>2. How engaging was the tutor?</label><br>
            <?php for($i=1; $i<=5; $i++): ?>
              <label class="radio-inline">
                <input type="radio" name="Engagement" value="<?= $i ?>" required> <?= $i ?>
              </label>
            <?php endfor; ?>
          </div>

          <!-- Understanding -->
          <div class="form-group">
            <label>3. Did the tutor answer your questions satisfactorily?</label>
            <select class="form-control" name="Understanding" required>
              <option value="">Select</option>
              <option value="Yes, all of them">Yes, all of them</option>
              <option value="Some of them">Some of them</option>
              <option value="No, not really">No, not really</option>
            </select>
          </div>

          <!-- Overall Rating -->
          <div class="form-group">
            <label>4. Overall satisfaction (1–10)</label><br>
            <?php for($i=1; $i<=10; $i++): ?>
              <label class="radio-inline">
                <input type="radio" name="OverallRating" value="<?= $i ?>" required> <?= $i ?>
              </label>
            <?php endfor; ?>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Submit Feedback</button>
        </div>
      </div>
    </form>
  </div>
</div>



<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

<script>
document.querySelectorAll('.decline-form button').forEach(btn => {
    btn.addEventListener('click', function(e){
        e.preventDefault();
        const form = this.closest('form');

        Swal.fire({
            title: 'Are you sure?',
            text: "This will delete the request!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Cancel it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>

<?php if(isset($_SESSION['alert'])): ?>

<script>
Swal.fire({
    icon: "<?= $_SESSION['alert']['type'] ?>",
    title: "<?= $_SESSION['alert']['title'] ?>",
    text: "<?= $_SESSION['alert']['message'] ?>",
    showConfirmButton: true,
    confirmButtonText: "OK",
    confirmButtonColor: "#3085d6"
});
</script>
<?php unset($_SESSION['alert']); endif; ?>

<script>

  $('.openBookingModal').on('click', function() {
    const tutorId = $(this).data('tutor');
    const tutorName = $(this).data('name');
    const subjects = $(this).data('subjects').split(',');
    const grade = $(this).data('grade'); 

    $('#modalTutorId').val(tutorId);
    $('#tutorName').text(tutorName);

    // Populate Subjects
    $('#modalSubject').empty();
    subjects.forEach(s => {
      const sub = s.trim();
      $('#modalSubject').append(`<option value="${sub}">${sub}</option>`);
    });

    // Add hidden input for grade
    if ($('#modalGrade').length === 0) {
      $('#modalSubject').after(`<input type="hidden" name="grade" id="modalGrade" value="${grade}">`); // <<< ADDED
    } else {
      $('#modalGrade').val(grade); // <<< ADDED
    }

    // Load available slots
    $('#modalSlot').html('<option>Loading available slots...</option>');
    $.get('fetchslots.php', { tutor: tutorId }, function(data) {
      $('#modalSlot').html(data);
    });

    $('#bookingModal').modal('show');
  });

</script>


<script>
// Feedback modal handler
$(document).on('click', '.openFeedbackModal', function() {
  const sessionId = $(this).data('session');
  const tutorName = $(this).data('tutor');
  const tutorId = $(this).data('tutorid');
  const learnerId = $(this).data('learnerid');
  const grade = $(this).data('grade');
  const subject = $(this).data('subject');

  $('#feedbackSessionId').val(sessionId);
  $('#feedbackTutorName').text(tutorName);
  $('#feedbackTutorId').val(tutorId);
  $('#feedbackLearnerId').val(learnerId);
  $('#feedbackGrade').val(grade);
  $('#feedbackSubject').val(subject);

  $('#feedbackForm input[type=radio]').prop('checked', false);
  $('#feedbackForm textarea').val('');

  $('#feedbackModal').modal('show');
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
