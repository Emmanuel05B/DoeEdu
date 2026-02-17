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


$tutorId = $_SESSION['user_id'];

// --- Fetch weekly availability ---
$weeklyStmt = $connect->prepare("
    SELECT DayOfWeek, StartTime, EndTime 
    FROM tutoravailability 
    WHERE TutorId = ? AND AvailabilityType = 'Recurring'
");
$weeklyStmt->bind_param("i", $tutorId);
$weeklyStmt->execute();
$weeklyRes = $weeklyStmt->get_result();
$weeklyAvailability = [];
while ($row = $weeklyRes->fetch_assoc()) {
    $weeklyAvailability[$row['DayOfWeek']] = [
        'start' => $row['StartTime'],
        'end'   => $row['EndTime']
    ];
}
$weeklyStmt->close();


// --- Fetch daily (once-off) availability ---
$dailyStmt = $connect->prepare("
    SELECT DayOfWeek, StartTime, EndTime 
    FROM tutoravailability 
    WHERE TutorId = ? AND AvailabilityType = 'OnceOff'
");
$dailyStmt->bind_param("i", $tutorId);
$dailyStmt->execute();
$dailyRes = $dailyStmt->get_result();

$dailyAvailability = [];
while ($row = $dailyRes->fetch_assoc()) {
    $dailyAvailability[$row['DayOfWeek']][] = [
        'start' => $row['StartTime'],
        'end'   => $row['EndTime']
    ];
}

$dailyStmt->close();





// --- Fetch pending sessions ---
$pendingSQL = "
    SELECT ts.*, u.Name, u.Contact, ts.AttachmentPath
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
    SELECT ts.*, u.Name, u.Contact, ts.AttachmentPath
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

<?php include_once(TUTOR_PATH . "/../partials/header.php"); ?> 
<?php include_once(TUTOR_PATH . "/../partials/mainsidebar.php"); ?>

<?php if (isset($_SESSION['alert'])): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        icon: '<?php echo $_SESSION['alert']['type']; ?>',
        title: '<?php echo addslashes($_SESSION['alert']['title']); ?>',
        text: '<?php echo addslashes($_SESSION['alert']['message']); ?>',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK'
    });
});
</script>
<?php unset($_SESSION['alert']); endif; ?>



<div class="content-wrapper" style="background-color: #f7f9fc;">
  <section class="content-header">
    <h1>Schedule & Bookings <small>Manage your weekly availability and booking requests here.</small></h1>
    <ol class="breadcrumb">
      <li><a href="tutorindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Bookings</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">

      <!-- Full-width Weekly Availability -->
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Manage Schedule</h3>
            <button class="btn btn-sm btn-info pull-right" data-toggle="modal" data-target="#availabilityModalOnceOff">Set Once-Off</button>
            <button class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target="#availabilityModal">Edit Recurring</button>

          </div>
          <div class="box-body table-responsive">
            <table class="table table-bordered text-center">
              <thead>
                <tr>
                  <?php foreach ($days as $day): ?>
                    <th><?= $day ?></th>
                  <?php endforeach; ?>
                </tr>
              </thead>
              <tbody style="background-color:#f0f8ff; color:#3c8dbc;">
                <tr>
                  <?php foreach ($days as $day):
                    $start = $weeklyAvailability[$day]['start'] ?? '-';
                    $end = $weeklyAvailability[$day]['end'] ?? '-';
                  ?>
                    <td><?= $start ?> - <?= $end ?></td>
                  <?php endforeach; ?>
                </tr>
              </tbody>


              <tbody>
                <?php 
                $maxSlots = max(array_map('count', $dailyAvailability ?: [[]]));
                for ($i = 0; $i < $maxSlots; $i++): ?>
                    <tr>
                        <?php foreach ($days as $day): ?>
                            <td>
                                <?php if (!empty($dailyAvailability[$day][$i])): 
                                    $slot = $dailyAvailability[$day][$i]; ?>
                                    <span>
                                        <?= htmlspecialchars($slot['start']) ?> - <?= htmlspecialchars($slot['end']) ?>
                                        <form method="POST" action="updateavailability.php" style="display:inline;" class="delete-slot-form">
                                            <input type="hidden" name="day" value="<?= htmlspecialchars($day) ?>">
                                            <input type="hidden" name="start" value="<?= htmlspecialchars($slot['start']) ?>">
                                            <input type="hidden" name="end" value="<?= htmlspecialchars($slot['end']) ?>">
                                            <input type="hidden" name="action" value="delete">
                                         
                                            <i 
                                                class="fa fa-trash delete-slot" 
                                                style="color:red; cursor:pointer; margin-left:5px;" 
                                                title="Delete this slot"
                                                data-day="<?= htmlspecialchars($day) ?>"
                                                data-start="<?= htmlspecialchars($slot['start']) ?>"
                                                data-end="<?= htmlspecialchars($slot['end']) ?>"
                                            ></i>

                                        </form>
                                    </span>
                                <?php else: ?>
                                    ---
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endfor; ?>
              </tbody>




            </table>
          </div>
        </div>
      </div>

      <!-- Bookings Column -->
      <div class="col-md-12">

        <!-- Pending Bookings -->
        <div class="box box-success" style="border-top:3px solid #00a65a;">
          <div class="box-header with-border" style="background-color:#e6ffed; color:#00a65a;">
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
                  <th>File</th>
                  <th>***</th>
                  <th>***</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($pendingResult->num_rows > 0): ?>
                  <?php while ($row = $pendingResult->fetch_assoc()): 
                    $dt = new DateTime($row['SlotDateTime']); ?>
                    <tr>
                      <td><?= htmlspecialchars($row['Name']) ?></td>
                      <td><?= htmlspecialchars($row['Subject']) ?></td>
                      <td><?= htmlspecialchars($row['Grade']) ?></td>
                      <td><?= $dt->format('Y-m-d') ?></td>
                      <td><?= $dt->format('H:i') ?></td>
                      <td><?= htmlspecialchars($row['Notes']) ?></td>
                      <td>
                        <?php if (!empty($row['AttachmentPath'])): ?>
                          <a href="<?= htmlspecialchars($row['AttachmentPath']) ?>" target="_blank" class="btn btn-xs btn-info">
                            <i class="fa fa-paperclip"></i> View
                          </a>
                        <?php else: ?>
                          ---
                        <?php endif; ?>
                      </td>

                      <td>
                        <form method="POST" action="update_session_status.php" style="display:inline;">
                          <input type="hidden" name="session_id" value="<?= $row['SessionId'] ?>">
                          <input type="hidden" name="action" value="accept">
                          <button type="submit" class="btn btn-xs btn-success"><i class="fa fa-check"></i> Accept</button>
                        </form>
                      </td>
                      <td>
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
                  <th>File</th>
                  <th>Meeting</th>
                </tr>
              </thead>
              

              <tbody>
                <?php if ($acceptedResult->num_rows > 0): ?>
                  <?php while ($row = $acceptedResult->fetch_assoc()): 
                    $dt = new DateTime($row['SlotDateTime']); ?>
                    <tr>
                      <td><?= htmlspecialchars($row['Name']) ?></td>
                      <td><?= htmlspecialchars($row['Subject']) ?></td>
                      <td><?= htmlspecialchars($row['Grade']) ?></td>
                      <td><?= $dt->format('Y-m-d') ?></td>
                      <td><?= $dt->format('H:i') ?></td>
                      <td><?= htmlspecialchars($row['Notes']) ?></td>
                      <td>
                        <?php if (!empty($row['AttachmentPath'])): ?>
                          <a href="<?= htmlspecialchars($row['AttachmentPath']) ?>" target="_blank" class="btn btn-xs btn-info">
                            <i class="fa fa-paperclip"></i> View
                          </a>
                        <?php else: ?>
                          ---
                        <?php endif; ?>
                      </td>
                      <td>
                        <?php if (!empty($row['MeetingLink'])): ?>
                          <a href="<?= htmlspecialchars($row['MeetingLink']) ?>" target="_blank" class="btn btn-xs btn-success">
                            <i class="fa fa-video-camera"></i> Join
                          </a>
                        <?php else: ?>
                          <button 
                            class="btn btn-xs btn-primary openMeetingModal" 
                            data-session="<?= $row['SessionId'] ?>" 
                            data-learner="<?= htmlspecialchars($row['Name']) ?>"
                          >
                            <i class="fa fa-plus"></i> Add Link
                          </button>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                <?php else: ?>
                  <tr><td colspan="8" class="text-center">No upcoming sessions.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
  </section>
</div>

<!-- Modals -->
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

<div class="modal fade" id="availabilityModalOnceOff" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <form method="POST" action="savedailyavailability.php">
      <div class="modal-content">
        <div class="modal-header bg-default">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Set daily Availability</h4>
        </div>
        <div class="modal-body">
          
            <div class="form-group row align-items-center">
              <div class="col-sm-4">
                
                <label>Day</label>
                  <select class="form-control" name="parenttitle" required>
                    <option value="">Select Day</option>
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                    <option value="Saturday">Saturday</option>
                    <option value="Sunday">Sunday</option>
                  </select>
              </div>
              <div class="col-sm-4">
                <label>Start</label>
                <input type="time" name="start" class="form-control input-sm time-input" required>
              </div>
              <div class="col-sm-4">
                <label>End</label>
                <input type="time" name="end" class="form-control input-sm time-input" required>
              </div>
            </div>
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-info">Save Availability</button>
        </div>
      </div>
    </form>
  </div>
</div>


<div class="modal fade" id="meetingModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <form id="meetingForm" method="POST" action="savemeetinglink.php">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Meeting Link for <span id="modalLearnerName"></span></h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="session_id" id="modalSessionId" value="">
          <div class="form-group">
            <label>MS Teams / Zoom Link</label>
            <input type="url" name="meeting_link" class="form-control" placeholder="Paste meeting link here" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Link</button>
        </div>
      </div>
    </form>
  </div>
</div>


<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>

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

$('.openMeetingModal').on('click', function() {
  const sessionId = $(this).data('session');
  const learnerName = $(this).data('learner');

  $('#modalSessionId').val(sessionId);
  $('#modalLearnerName').text(learnerName);

  $('#meetingModal').modal('show');
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


document.querySelectorAll('.delete-slot').forEach(el => {
    el.addEventListener('click', function() {
        const day = this.dataset.day;
        const start = this.dataset.start;
        const end = this.dataset.end;

        Swal.fire({
            title: 'Are you sure?',
            text: `Delete the slot for ${day} (${start} - ${end})?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then(result => {
            if (result.isConfirmed) {
                // Create a temporary form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'updateavailability.php';

                form.innerHTML = `
                    <input type="hidden" name="day" value="${day}">
                    <input type="hidden" name="start" value="${start}">
                    <input type="hidden" name="end" value="${end}">
                    <input type="hidden" name="action" value="delete">
                `;
                document.body.appendChild(form);
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
