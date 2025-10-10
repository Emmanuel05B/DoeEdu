<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../common/partials/head.php");
include(__DIR__ . "/../../partials/connect.php");

$tutorId = $_SESSION['user_id'];

// --- Fetch weekly availability ---
$weeklyStmt = $connect->prepare("SELECT DayOfWeek, StartTime, EndTime FROM tutoravailability WHERE TutorId = ?");
$weeklyStmt->bind_param("i", $tutorId);
$weeklyStmt->execute();
$weeklyRes = $weeklyStmt->get_result();
$weeklyAvailability = [];
while ($row = $weeklyRes->fetch_assoc()) {
    $weeklyAvailability[$row['DayOfWeek']] = [
        'start' => $row['StartTime'],
        'end' => $row['EndTime']
    ];
}
$weeklyStmt->close();

// --- Fetch pending sessions ---
$pendingSQL = "
    SELECT ts.*, u.Name, u.Contact 
    FROM tutorsessions ts
    JOIN users u ON ts.LearnerId = u.Id
    WHERE ts.TutorId = ? AND ts.Status = 'Pending'
    ORDER BY ts.SlotDateTime ASC
";
$pendingQuery = $connect->prepare($pendingSQL);
$pendingQuery->bind_param("i", $tutorId);
$pendingQuery->execute();
$pendingResult = $pendingQuery->get_result();

// --- Fetch accepted sessions ---
$acceptedSQL = "
    SELECT ts.*, u.Name, u.Contact
    FROM tutorsessions ts
    JOIN users u ON ts.LearnerId = u.Id
    WHERE ts.TutorId = ? AND ts.Status = 'Confirmed' AND ts.SlotDateTime >= NOW()
    ORDER BY ts.SlotDateTime ASC
";
$acceptedQuery = $connect->prepare($acceptedSQL);
$acceptedQuery->bind_param("i", $tutorId);
$acceptedQuery->execute();
$acceptedResult = $acceptedQuery->get_result();

$days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

<?php include(__DIR__ . "/../partials/header.php"); ?>
<?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

<div class="content-wrapper" style="background-color: #f7f9fc;">
  <section class="content-header">
    <h1>My Availability & Bookings</h1>
    <p class="text-muted">Manage your weekly availability and see learner booking requests here.</p>
  </section>

  <section class="content">
    <div class="row">

      <!-- Full-width Weekly Availability -->
      <div class="col-md-12">
        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title">Weekly Availability</h3>
            <button class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target="#availabilityModal">Edit Availability</button>
          </div>
          <div class="box-body">
            <table class="table table-bordered text-center">
              <thead>
                <tr>
                  <?php foreach ($days as $day): ?>
                    <th><?= $day ?></th>
                  <?php endforeach; ?>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <?php foreach ($days as $day):
                    $start = $weeklyAvailability[$day]['start'] ?? '-';
                    $end = $weeklyAvailability[$day]['end'] ?? '-';
                  ?>
                    <td><?= $start ?> - <?= $end ?></td>
                  <?php endforeach; ?>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Bookings Column -->
      <div class="col-md-12">

        <!-- Pending Bookings -->
        <div class="box box-primary">
          <div class="box-header with-border" style="background-color:#f0f8ff; color:#3c8dbc;">
            <h3 class="box-title"><i class="fa fa-clock-o"></i> Pending Sessions</h3>
          </div>
          <div class="box-body table-responsive">
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th>Learner</th>
                  <th>Subject</th>
                  <th>Grade</th>
                  <th>Date</th>
                  <th>Time</th>
                  <th>Notes</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($pendingResult->num_rows > 0): ?>
                  <?php while ($row = $pendingResult->fetch_assoc()): 
                    $dt = new DateTime($row['SlotDateTime']); ?>
                    <tr>
                      <td><?= htmlspecialchars($row['Name']) ?></td>
                      <td><?= htmlspecialchars($row['Subject']) ?></td>
                      <td>Default</td>
                      <td><?= $dt->format('Y-m-d') ?></td>
                      <td><?= $dt->format('H:i') ?></td>
                      <td><?= htmlspecialchars($row['Notes']) ?></td>
                      <td><span class="label label-warning">Pending</span></td>
                      <td>
                        <form method="POST" action="update_session_status.php" style="display:inline;">
                          <input type="hidden" name="session_id" value="<?= $row['SessionId'] ?>">
                          <input type="hidden" name="action" value="accept">
                          <button type="submit" class="btn btn-xs btn-success"><i class="fa fa-check"></i> Accept</button>
                        </form>
                        
                        <form method="POST" action="update_session_status.php" class="decline-form" style="display:inline;">
                          <input type="hidden" name="session_id" value="<?= $row['SessionId'] ?>">
                          <input type="hidden" name="action" value="decline">
                          <button type="submit" class="btn btn-xs btn-danger">
                              <i class="fa fa-times"></i> Decline
                          </button>
                         </form>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                <?php else: ?>
                  <tr><td colspan="8" class="text-center">No pending sessions.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Accepted Sessions -->
        <div class="box" style="border-top: 3px solid #9e8cceff;">
          <div class="box-header with-border" style="background-color:#f3e8ff; color:#7b5fc0;">
            <h3 class="box-title">Upcoming Accepted Sessions</h3>
          </div>
          <div class="box-body table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Learner</th>
                  <th>Subject</th>
                  <th>Grade</th>
                  <th>Date</th>
                  <th>Time</th>
                  <th>Notes</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($acceptedResult->num_rows > 0): ?>
                  <?php while ($row = $acceptedResult->fetch_assoc()): 
                    $dt = new DateTime($row['SlotDateTime']); ?>
                    <tr>
                      <td><?= htmlspecialchars($row['Name']) ?></td>
                      <td><?= htmlspecialchars($row['Subject']) ?></td>
                      <td>Default</td>
                      <td><?= $dt->format('Y-m-d') ?></td>
                      <td><?= $dt->format('H:i') ?></td>
                      <td><?= htmlspecialchars($row['Notes']) ?></td>
                    </tr>
                  <?php endwhile; ?>
                <?php else: ?>
                  <tr><td colspan="6" class="text-center">No upcoming sessions.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
  </section>
</div>

<!-- Modal stays unchanged -->
 <div class="modal fade" id="availabilityModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <form method="POST" action="saveavailability.php">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Set Weekly Availability</h4>
        </div>
        <div class="modal-body">
          <?php foreach ($days as $day):
            $start = $weeklyAvailability[$day]['start'] ?? '';
            $end = $weeklyAvailability[$day]['end'] ?? '';
            $checked = $start && $end ? 'checked' : '';
          ?>
            <div class="form-group row align-items-center">
              <div class="col-sm-3">
                <label>
                  <input type="checkbox" class="day-checkbox" name="days[]" value="<?= $day ?>" <?= $checked ?>> <?= $day ?>
                </label>
              </div>
              <div class="col-sm-4">
                <input type="time" name="start[<?= $day ?>]" class="form-control input-sm time-input" value="<?= $start ?>" <?= $checked ? '' : 'disabled' ?>>
              </div>
              <div class="col-sm-4">
                <input type="time" name="end[<?= $day ?>]" class="form-control input-sm time-input" value="<?= $end ?>" <?= $checked ? '' : 'disabled' ?>>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Availability</button>
        </div>
      </div>
    </form>
  </div>
</div>
<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

<script>
// Enable/disable time inputs in modal
document.querySelectorAll('.day-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const row = this.closest('.form-group');
        row.querySelectorAll('.time-input').forEach(input => {
            input.disabled = !this.checked;
            if (!this.checked) input.value = '';
        });
    });
});
</script>



<script>
document.querySelectorAll('.decline-form button').forEach(btn => {
    btn.addEventListener('click', function(e){
        e.preventDefault();
        const form = this.closest('form');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to undo this action!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, decline it!'
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
    timer: 3000,
    showConfirmButton: false
});
</script>
<?php unset($_SESSION['alert']); endif; ?>


</body>
</html>
