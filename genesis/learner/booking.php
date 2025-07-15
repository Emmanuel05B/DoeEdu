<!DOCTYPE html>
<html>
<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}

include("learnerpartials/head.php");
include('../partials/connect.php');

$tutorId = isset($_GET['tutor']) ? intval($_GET['tutor']) : 0;
$learnerId = $_SESSION['user_id'];

// Get shared subjects between learner and tutor
$sharedSubjects = [];
if ($tutorId > 0) {
  $stmt = $connect->prepare("
    SELECT s.SubjectName 
    FROM learnersubject ls 
    JOIN subjects s ON ls.SubjectId = s.SubjectId 
    WHERE ls.LearnerId = ? AND ls.SubjectId IN (
      SELECT SubjectId FROM tutorsubject WHERE TutorId = ?
    )
  ");
  $stmt->bind_param("ii", $learnerId, $tutorId);
  $stmt->execute();
  $res = $stmt->get_result();
  while ($row = $res->fetch_assoc()) {
    $sharedSubjects[] = $row['SubjectName'];
  }
  $stmt->close();
}

// Get weekly availability for tutor
$availability = [];
if ($tutorId > 0) {
  $stmt = $connect->prepare("SELECT DayOfWeek, StartTime, EndTime FROM tutoravailability WHERE TutorId = ?");
  $stmt->bind_param("i", $tutorId);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $availability[$row['DayOfWeek']][] = [$row['StartTime'], $row['EndTime']];
  }
}

// Get already booked slots for tutor in next 14 days
$booked = [];
if ($tutorId > 0) {
  $stmt2 = $connect->prepare("SELECT SlotDateTime FROM tutorsessions WHERE TutorId = ? AND SlotDateTime BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 14 DAY)");
  $stmt2->bind_param("i", $tutorId);
  $stmt2->execute();
  $res2 = $stmt2->get_result();
  while ($r = $res2->fetch_assoc()) {
    $booked[] = $r['SlotDateTime'];
  }
}

// Generate available slots
$availableSlots = [];
$now = new DateTime();
$endLimit = (clone $now)->modify('+14 days');
for ($i = 0; $i <= 14; $i++) {
  $day = (clone $now)->modify("+$i days");
  if ($day > $endLimit) break;
  $dayName = $day->format('l');
  if (isset($availability[$dayName])) {
    foreach ($availability[$dayName] as $timeRange) {
      $slot = new DateTime($day->format('Y-m-d') . ' ' . $timeRange[0]);
      $end = new DateTime($day->format('Y-m-d') . ' ' . $timeRange[1]);
      while ($slot < $end) {
        $slotString = $slot->format('Y-m-d H:i:s');
        if (!in_array($slotString, $booked)) {
          $availableSlots[] = $slot->format('Y-m-d H:i');
        }
        $slot->modify('+1 hour');
      }
    }
  }
}
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include("learnerpartials/header.php"); ?>
  <?php include("learnerpartials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1 style="color:#3a3a72; font-weight:600;">Book a Tutoring Session</h1>
    </section>

    <section class="content">
      <div class="row">
        <!-- Booking Form -->
        <div class="col-md-6">
          <div class="box" style="border-top: 3px solid #6a52a3; border-radius:10px;">
            <div class="box-header with-border">
              <h3 class="box-title" style="color:#6a52a3; font-weight:600;">Booking Form</h3>
            </div>
            <div class="box-body">
              <form action="booksession.php" method="POST">
                <input type="hidden" name="tutor_id" value="<?= $tutorId ?>">

                <!-- Subject Dropdown -->
                <div class="form-group">
                  <label style="color:#3a3a72;">Subject</label>
                  <?php if (empty($sharedSubjects)): ?>
                    <div class="alert alert-danger">You and this tutor do not share any subjects. Booking not possible.</div>
                  <?php else: ?>
                    <select name="subject" class="form-control input-sm" required>
                      <option value="">-- Select Subject --</option>
                      <?php foreach ($sharedSubjects as $sub): ?>
                        <option value="<?= htmlspecialchars($sub) ?>"><?= htmlspecialchars($sub) ?></option>
                      <?php endforeach; ?>
                    </select>
                  <?php endif; ?>
                </div>

                <!-- Available Slots -->
                <div class="form-group">
                  <label style="color:#3a3a72;">Select a Slot</label>
                  <select name="slot" class="form-control input-sm" required>
                    <option value="">-- Select an Available Slot --</option>
                    <?php foreach ($availableSlots as $slot): ?>
                      <option value="<?= htmlspecialchars($slot) ?>"><?= date('l, d M Y H:i', strtotime($slot)) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <!-- Notes -->
                <div class="form-group">
                  <label for="notes" style="color:#3a3a72;">Additional Notes (Optional)</label>
                  <textarea name="notes" rows="3" class="form-control input-sm"></textarea>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn" style="background-color:#3a3a72; color:white; font-weight:600; border-radius:5px;" <?= empty($sharedSubjects) ? 'disabled' : '' ?>>
                  Book Session
                </button>
              </form>
            </div>
          </div>
        </div>

        <!-- Bookings Table -->
        <div class="col-md-6">
          <div class="box" style="border-top: 3px solid #3a3a72; border-radius:10px;">
            <div class="box-header with-border">
              <h3 class="box-title" style="color:#3a3a72; font-weight:600;">My Bookings</h3>
            </div>
            <div class="box-body table-responsive">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr style="background-color:#f0f4ff; color:#3a3a72;">
                    <th>Date</th>
                    <th>Time</th>
                    <th>Subject</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $stmt3 = $connect->prepare("SELECT SlotDateTime, Subject, Status FROM tutorsessions WHERE LearnerId = ? ORDER BY SlotDateTime DESC");
                  $stmt3->bind_param("i", $learnerId);
                  $stmt3->execute();
                  $res3 = $stmt3->get_result();
                  while ($booking = $res3->fetch_assoc()):
                    $dt = new DateTime($booking['SlotDateTime']);
                  ?>
                    <tr>
                      <td><?= $dt->format('Y-m-d') ?></td>
                      <td><?= $dt->format('H:i') ?></td>
                      <td><?= htmlspecialchars($booking['Subject']) ?></td>
                      <td>
                        <?php
                        $status = $booking['Status'];
                        $color = $status == 'Confirmed' ? '#28a745' : ($status == 'Pending' ? '#f0ad4e' : '#d9534f');
                        ?>
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

<script>
  $(function () {
    $('table').DataTable();
  });
</script>
</body>
</html>
