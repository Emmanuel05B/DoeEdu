<!DOCTYPE html>
<html>
<?php

require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");
include_once(COMMON_PATH . "/../partials/head.php");  

?>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

<?php include_once(LEARNER_PATH . "/../partials/header.php"); ?>
<?php include_once(LEARNER_PATH . "/../partials/mainsidebar.php"); ?>

    <div class="content-wrapper">
      <section class="content-header">
        <h1>Book a Tutoring Session <small>xxxx x x x</small></h1>
        <ol class="breadcrumb">
          <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">xxxx</li>
        </ol>
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
                <?php
                $tutorId = 2;
                $learnerId = $_SESSION['user_id'];
                $subject = "Mathematics";

                // Generate next 14 days' available time slots
                $availableSlots = [];

                $stmt = $connect->prepare("SELECT DayOfWeek, StartTime, EndTime FROM tutoravailability WHERE TutorId = ?");
                $stmt->bind_param("i", $tutorId);
                $stmt->execute();
                $result = $stmt->get_result();

                $availability = [];
                while ($row = $result->fetch_assoc()) {
                  $availability[$row['DayOfWeek']][] = [$row['StartTime'], $row['EndTime']];
                }

                // Fetch already booked slots
                $booked = [];
                $stmt2 = $connect->prepare("SELECT SlotDateTime FROM tutorsessions WHERE TutorId = ? AND SlotDateTime >= NOW()");
                $stmt2->bind_param("i", $tutorId);
                $stmt2->execute();
                $res2 = $stmt2->get_result();
                while ($r = $res2->fetch_assoc()) {
                  $booked[] = $r['SlotDateTime'];
                }

                $now = new DateTime();
                for ($i = 0; $i < 14; $i++) {
                  $day = clone $now;
                  $day->modify("+$i days");
                  $dayName = $day->format('l');

                  if (isset($availability[$dayName])) {
                    foreach ($availability[$dayName] as $timeRange) {
                      $slot = new DateTime($day->format('Y-m-d') . ' ' . $timeRange[0]);
                      $end = new DateTime($day->format('Y-m-d') . ' ' . $timeRange[1]);

                      while ($slot < $end) {
                        $slotCopy = clone $slot;
                        $slotString = $slotCopy->format('Y-m-d H:i:s');
                        if (!in_array($slotString, $booked)) {
                          $availableSlots[] = $slotCopy->format('Y-m-d H:i');
                        }
                        $slot->modify('+1 hour');
                      }
                    }
                  }
                }
                ?>

                <!-- Subject -->
                <div class="form-group">
                  <label style="color:#3a3a72;">Subject</label>
                  <input type="text" class="form-control input-sm" value="<?= $subject ?>" readonly>
                  <input type="hidden" name="subject" value="<?= $subject ?>">
                </div>

                <!-- Available Slots Dropdown -->
                <div class="form-group">
                  <label style="color:#3a3a72;">Select a Slot</label>
                  <select name="slot" class="form-control input-sm" required>
                    <option value="">-- Select an Available Slot --</option>
                    <?php foreach ($availableSlots as $slot): ?>
                      <option value="<?= $slot ?>"><?= date('l, d M Y H:i', strtotime($slot)) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <!-- Notes -->
                <div class="form-group">
                  <label for="notes" style="color:#3a3a72;">Additional Notes (Optional)</label>
                  <textarea name="notes" rows="3" class="form-control input-sm"></textarea>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn" style="background-color:#3a3a72; color:white; font-weight:600; border-radius:5px;">
                  Book Session
                </button>
              </form>
            </div>
          </div>
        </div>

        <!-- My Bookings Table -->
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
                      <td><?= $booking['Subject'] ?></td>
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
<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>

<script>
  $(function () {
    $('table').DataTable();
  });
</script>
</body>
</html>
