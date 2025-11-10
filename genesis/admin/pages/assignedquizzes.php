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

$tutorId = $_SESSION['user_id']; // Logged-in tutor id 

$grade     = $_GET['gra'];
$SubjectId = intval($_GET['sub']);
$group     = $_GET['group'];

// Fetch subject name
$stmt = $connect->prepare("SELECT SubjectName FROM subjects WHERE SubjectId = ?");
$stmt->bind_param("i", $SubjectId);
$stmt->execute();
$result = $stmt->get_result();
$SubjectName = "";
if ($row = $result->fetch_assoc()) {
    $SubjectName = $row['SubjectName'];
}
$stmt->close();
?>

<?php include_once(COMMON_PATH . "/../partials/head.php"); ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include_once(ADMIN_PATH . "/../partials/header.php"); ?>
  <?php include_once(ADMIN_PATH . "/../partials/mainsidebar.php"); ?>


  <?php if (isset($_GET['updated']) && $_GET['updated'] == 1): ?>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
  Swal.fire({
      icon: 'success',
      title: 'Updated!',
      text: 'The due date has been successfully updated.',
      confirmButtonText: 'OK'
  });
  </script>
  <?php endif; ?>

  <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
  Swal.fire({
      icon: 'success',
      title: 'Deleted!',
      text: 'The activity has been successfully deleted.',
      confirmButtonText: 'OK'
  });
  </script>
  <?php endif; ?>
  
  <div class="content-wrapper">
    <section class="content-header">
       <h1>Current Activities <small>Assigned to <?php echo htmlspecialchars($grade . " - " . $SubjectName . " - " . $group); ?></small></h1>
        <ol class="breadcrumb">
          <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Activities</li>
        </ol>
    </section>

    <section class="content">
      <div class="box box-default">
        <div class="box-body table-responsive">

            <div class="box-header with-border">
            <h3 class="box-title">
                Activities List
            </h3>
            <div class="box-tools pull-right">
                <!-- Assign Activity Button -->
                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-assignActivity">
                <i class="fa fa-plus"></i> Assign New Activity
                </button>

                <!-- Create Quiz Button -->
                <a href="generateactivity.php?sub=<?php echo urlencode($SubjectId); ?>&gra=<?php echo urlencode($grade); ?>&group=<?php echo urlencode($group); ?>" 
                class="btn btn-primary btn-sm" style="width: 100px; margin-left:5px;">
                Create Quiz
                </a>
            </div>
            </div>

          <div class="table-responsive">
            <table class="table table-bordered table-hover" id="activitiesTable" style="width:100%;">
                <thead style="background-color: #d1d9ff;">
                <tr>
                    <th>Title</th>
                    <th>Topic</th>
                    <th>Assigned_On</th>
                    <th>Due_Date</th>
                    <th>**</th>
                    <th>++</th>
                </tr>
                </thead>
                <tbody>
                <?php
                // Query to only get activities assigned to this class/subject/group
                $stmt = $connect->prepare("
                    SELECT 
                    oa.Id, 
                    oa.Title, 
                    oa.Topic, 
                    s.SubjectName,
                    oaa.AssignedAt, 
                    oaa.DueDate, 
                    oaa.LastFeedBackSent
                    FROM onlineactivities oa
                    INNER JOIN subjects s 
                        ON oa.SubjectId = s.SubjectId
                    INNER JOIN onlineactivitiesassignments oaa 
                        ON oa.Id = oaa.OnlineActivityId
                    INNER JOIN classes c
                        ON oaa.ClassID = c.ClassID
                    WHERE 
                    c.Grade = ?
                    AND c.SubjectId = ?
                    AND c.GroupName = ?
                    ORDER BY oaa.DueDate ASC
                ");
                    if (!$stmt) {
                        die("SQL error: " . $connect->error);
                    }

                    $stmt->bind_param("sis", $grade, $SubjectId, $group); // or "iis" if Grade is numeric
                    $stmt->execute();
                    $result = $stmt->get_result();

                if ($result->num_rows === 0) {
                    echo "<tr><td colspan='5' class='text-center'>No activities assigned to this class/group.</td></tr>";
                } else {
                    while ($row = $result->fetch_assoc()) {
                    $activityId = intval($row['Id']);
                    echo "<tr>
                        <td>" . htmlspecialchars($row['Title']) . "</td>
                        <td>" . htmlspecialchars($row['Topic']) . "</td>
                        <td>" . htmlspecialchars($row['AssignedAt']) . "</td>
                        <td>
                            <span class='due-date' id='due-date-$activityId'>" . htmlspecialchars($row['DueDate']) . "</span>
                            <a href='#' class='btn btn-xs btn-success edit-due-btn' 
                            data-id='$activityId' 
                            data-duedate='" . htmlspecialchars($row['DueDate']) . "' 
                            title='Edit Due Date'>
                                <i class='fa fa-calendar'></i>
                            </a>
                        </td>
                        <td>
                            
                            <a href='#' class='btn btn-xs btn-warning unassign-activity-btn' data-id='$activityId' title='Unassign from Class'>
                                <i class='fa fa-unlink'></i>
                            </a>

                        </td>
                        <td>
                        
                            <a href='activityoverview.php?activityId=$activityId&gra=" . urlencode($grade) . "&group=" . urlencode($group) . "' 
                              class='btn btn-xs btn-info' 
                              title='Overview'>
                                <i class='fa fa-info-circle'></i>
                            </a>

                        </td>
                    </tr>";
                    }
                }
                $stmt->close();
                ?>
                </tbody>
            </table>
          </div> 
        </div>
      </div>
    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<!-- Modal for Editing Due Date -->
<div class="modal fade" id="editDueDateModal" tabindex="-1" role="dialog" aria-labelledby="editDueDateLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="editDueDateForm" method="POST" action="updateduedate.php">
        <div class="modal-header">
          <h4 class="modal-title" id="editDueDateLabel">Edit Due Date</h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="activityId" id="modalActivityId">
          <input type="hidden" name="gra" value="<?php echo htmlspecialchars($grade); ?>">
          <input type="hidden" name="sub" value="<?php echo htmlspecialchars($SubjectId); ?>">
          <input type="hidden" name="group" value="<?php echo htmlspecialchars($group); ?>">
          <div class="form-group">
            <label for="newDueDate">New Due Date</label>
            <input type="date" class="form-control" name="newDueDate" id="newDueDate" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Include SweetAlert2 & jQuery -->
<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>

<?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Deleted!',
    text: 'The activity has been successfully unassigned.',
    confirmButtonText: 'OK'
});
</script>
<?php endif; ?>


<script>
$(function () {
    $('#activitiesTable').DataTable({
        responsive: true,
        autoWidth: false,
        order: [[3, "asc"]] // Sort by due date ascending
    });

    
    // Edit Due Date Button
    $(document).on('click', '.edit-due-btn', function(e) {
        e.preventDefault();
        var activityId = $(this).data('id');
        var currentDue = $(this).data('duedate');

        $('#modalActivityId').val(activityId);
        $('#newDueDate').val(currentDue);
        $('#editDueDateModal').modal('show');
    });

    // Unassign Button 
    $(document).on('click', '.unassign-activity-btn', function(e) {
        e.preventDefault();
        var activityId = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "This will unassign the activity from this class/group.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f39c12',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, unassign it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'unassignactivity.php?activityId=' + activityId +
                                       '&gra=<?php echo urlencode($grade); ?>' +
                                       '&sub=<?php echo urlencode($SubjectId); ?>' +
                                       '&group=<?php echo urlencode($group); ?>';
            }
        });
    });

});

$(function () {
    $('#assignActivityTable').DataTable({
      
    });
  });

</script>

<!-- Assign Activity Modal -->
<div class="modal fade" id="modal-assignActivity" tabindex="-1" role="dialog" aria-labelledby="assignActivityLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header bg-success">
        <h4 class="modal-title" id="assignActivityLabel">Assign Activity</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script>
      <?php if (isset($_GET['assigned']) && $_GET['assigned'] == 1): ?>
          // Open the Assign Activity modal
          $('#modal-assignActivity').modal('show');

          // Show SweetAlert on top of modal
          Swal.fire({
              icon: 'success',
              title: 'Activity assigned successfully!',
              text: 'The selected activity has been assigned to this class/group.',
              backdrop: true,
              confirmButtonText: 'OK'
          });
      <?php endif; ?>
      </script>

      <div class="modal-body">
        <div class="table-responsive">
        <table id="assignActivityTable" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Title</th>
              <th>Chapter / Topic</th>
              <th>Orig. Class</th>
              <th>Status</th>
              <th>Assign For My Class (<?php echo htmlspecialchars($group); ?>) / (Due Date?)</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (isset($grade) && isset($SubjectId) && isset($group)) {
                $stmt = $connect->prepare("
                  SELECT a.Id, a.Title, a.Topic, a.GroupName,
                        IF(b.OnlineActivityId IS NULL, 0, 1) AS assigned
                  FROM onlineactivities a
                  LEFT JOIN onlineactivitiesassignments b 
                    ON a.Id = b.OnlineActivityId 
                    AND b.ClassID = (SELECT ClassID FROM classes WHERE Grade = ? AND SubjectId = ? AND GroupName = ? LIMIT 1)
                  WHERE a.Grade = ? AND a.SubjectId = ?
                  ORDER BY a.CreatedAt DESC
                ");
                $stmt->bind_param("iisii", $grade, $SubjectId, $group, $grade, $SubjectId);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $assigned = $row['assigned'] ? true : false;
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['Title']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['Topic']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['GroupName']) . '</td>';
                    echo '<td>' . ($assigned ? '<span class="text-success">Assigned</span>' : '<span class="text-warning">Not Assigned</span>') . '</td>';
                    echo '<td>';
                    if ($assigned) {
                        echo '<button class="btn btn-default btn-sm" disabled>Assign</button>';
                    } else {
                        echo '
                            <form method="POST" action="assignactivityhandler.php" style="display:flex; align-items:center; gap:5px;">
                                <input type="hidden" name="activityId" value="' . $row['Id'] . '">
                                <input type="hidden" name="grade" value="' . $grade . '">
                                <input type="hidden" name="subject" value="' . $SubjectId . '">
                                <input type="hidden" name="group" value="' . $group . '">
                                <input type="date" name="dueDate" class="form-control input-sm" required style="width:140px;">
                                <button type="submit" class="btn btn-success btn-sm">Assign</button>
                            </form>
                            ';

                    }
                    echo '</td>';
                    echo '</tr>';
                }

                $stmt->close();
            }
            ?>
          </tbody>
        </table>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>



</body>
</html>
