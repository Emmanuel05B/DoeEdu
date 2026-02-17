<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");
include_once(BASE_PATH . "/partials/connect.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

$sqlInvited = "
    SELECT ti.*, ta.Name, ta.Surname, ta.Email, ta.CV_Matric
    FROM tutorinvitations ti
    INNER JOIN tutorapplications ta 
        ON ti.TutorApplicationId = ta.Id
    ORDER BY ti.SentAt DESC
";
$invited = $connect->query($sqlInvited);


// Fetch perfect applicants
$sqlPerfect = "
    SELECT *
    FROM tutorapplications
    WHERE Matric = 'Yes'
      AND Maths80 = 'Yes' AND MathsMark >= 80
      AND Science80 = 'Yes' AND ScienceMark >= 80
      AND Device != 'No device'
      AND Internet = 'Yes'
      AND OnlineOk = 'Yes'
      AND TeachGrades = 'Yes'
      AND ExpectedPay < 50
      AND ProbationRate = 'Yes'
      AND StartImmediately = 'Yes'
      AND Experience = 'Yes'
    ORDER BY SubmissionDate DESC
";
$perfectApplications = $connect->query($sqlPerfect);

// Collect perfect applicant IDs
$perfectIds = [];
while ($row = $perfectApplications->fetch_assoc()) {
    $perfectIds[] = $row['Id'];
}
// Reset result pointer for later use
$perfectApplications->data_seek(0);

// Fetch top qualifying applicants excluding perfect ones
$excludeIds = !empty($perfectIds) ? implode(',', $perfectIds) : '0'; // if empty, use 0 to avoid syntax error
$sqlTop = "
    SELECT *
    FROM tutorapplications
    WHERE Matric = 'Yes'
      AND Maths80 = 'Yes' AND MathsMark >= 80
      AND Device != 'No device'
      AND Internet = 'Yes'
      AND OnlineOk = 'Yes'
      AND TeachGrades = 'Yes'
      AND Id NOT IN ($excludeIds)
    ORDER BY SubmissionDate DESC
";
$topApplications = $connect->query($sqlTop);


// All NON-qualifying applications
$sqlAll = "
    SELECT *
    FROM tutorapplications
    WHERE Id NOT IN (

        -- Perfect
        SELECT Id FROM tutorapplications
        WHERE Matric = 'Yes'
          AND Maths80 = 'Yes' AND MathsMark >= 80
          AND Science80 = 'Yes' AND ScienceMark >= 80
          AND Device != 'No device'
          AND Internet = 'Yes'
          AND OnlineOk = 'Yes'
          AND TeachGrades = 'Yes'
          AND ExpectedPay < 50
          AND ProbationRate = 'Yes'
          AND StartImmediately = 'Yes'
          AND Experience = 'Yes'

        UNION

        -- Top
        SELECT Id FROM tutorapplications
        WHERE Matric = 'Yes'
          AND Maths80 = 'Yes' AND MathsMark >= 80
          AND Device != 'No device'
          AND Internet = 'Yes'
          AND OnlineOk = 'Yes'
          AND TeachGrades = 'Yes'
    )
    ORDER BY SubmissionDate DESC
";
$applications = $connect->query($sqlAll);




?>

<!DOCTYPE html>
<html>
<?php include_once(COMMON_PATH . "/../partials/head.php"); ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

<?php include_once(ADMIN_PATH . "/../partials/header.php"); ?>
<?php include_once(ADMIN_PATH . "/../partials/mainsidebar.php"); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Tutor Applications <small>manage applications</small></h1>
        <ol class="breadcrumb">
            <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Applications</li>
        </ol>
    </section>

    <section class="content">

      <!-- Invited Applicants -->
      <div class="box box-warning">
           <div class="box-header with-border">
              <h3 class="box-title">Invited for Interviews</h3><br><br>
              
                    <div class="col-md-12">
                        <div class="box box-default collapsed-box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Note:</h3>

                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="box-body">
                               Applicants listed here have been sent an interview invitation email. You can monitor confirmation status and avoid scheduling conflicts.
                            </div>
                        </div>
                    </div>
            </div>

          <div class="box-body table-responsive">
              <?php if($invited->num_rows > 0): ?>

              <table id="invitedTable" class="table table-bordered table-hover">
                  <thead style="background-color:#fff3cd;">
                      <tr>
                          <th>Name</th>
                          <th>Surname</th>
                          <th>Interview Date</th>
                          <th>Start Time</th>
                          <th>End Time</th>
                          <th>Confirmed?</th>
                          <th>CV</th>
                          <th>Update_App</th>
                          <th>Invited On</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php while ($row = $invited->fetch_assoc()): ?>
                          <tr>
                              <td><?= htmlspecialchars($row['Name']) ?></td>
                              <td><?= htmlspecialchars($row['Surname']) ?></td>
                              <td><?= htmlspecialchars($row['InterviewDate']) ?></td>
                              <td><?= htmlspecialchars($row['StartTime']) ?></td>
                              <td><?= htmlspecialchars($row['EndTime']) ?></td>
                              <td>
                                  <?php if ($row['Confirmed'] == 1): ?>
                                      <span class="label label-success">Confirmed</span>
                                  <?php else: ?>
                                      <span class="label label-warning">Pending</span>
                                  <?php endif; ?>
                              </td>
                              <td>
                                   <a href="<?= CVS_URL ?>/<?= htmlspecialchars($row['CV_Matric']) ?>" 
                                    class="btn btn-info btn-xs" target="_blank">Open CV</a>
                                </td>
                              <td>

                            

                                <button 
                                  type="button" 
                                  class="btn btn-primary btn-xs btn-contact" 
                                  data-toggle="modal" 
                                  data-target="#modal-contact" 
                                  data-email="<?= htmlspecialchars($row['Email']) ?>"
                                >
                                  Email
                                </button>
                                </td>

                                

                              <td><?= htmlspecialchars($row['SentAt']) ?></td>

                          </tr>
                      <?php endwhile; ?>
                  </tbody>
              </table>

              <?php else: ?>
                  <p>No interview invites have been sent yet.</p>
              <?php endif; ?>
          </div>
      </div>


        <!-- Perfect Applicants -->
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Perfect Qualifying Applicants</h3>
            </div>
            <div class="box-body table-responsive">
                <?php if($perfectApplications->num_rows > 0): ?>

                <table id="perfectTable" class="table table-bordered table-hover">
                    <thead style="background-color:#d1ffd6;">
                        <tr>

                            <th>Name</th>
                            <th>Surname</th>
                            <th>Math_%</th>
                            <th>Science_%</th>
                            <th>U_Stud?</th>
                            <th>Pay</th>
                            <th>CV</th>
                            <th>Invite</th>
                            <th>Last_Invite</th>
                            <th>More</th>

                            <th>Del</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($perfect = $perfectApplications->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($perfect['Name']) ?></td>
                                <td><?= htmlspecialchars($perfect['Surname']) ?></td>
                                <td><?= htmlspecialchars($perfect['MathsMark']) ?></td>
                                <td><?= htmlspecialchars($perfect['ScienceMark']) ?></td>
                                <td><?= htmlspecialchars($perfect['UniversityStudent']) ?></td>
                                <td>R <?= htmlspecialchars($perfect['ExpectedPay']) ?></td>
                                <td>
                                   
                                    <a href="<?= CVS_URL ?>/<?= htmlspecialchars($perfect['CV_Matric']) ?>" 
                                      class="btn btn-info btn-xs" target="_blank">CV</a>
                                </td>

                                <td>
                                    <button 
                                        type="button" 
                                        class="btn btn-success btn-xs btn-invite" 
                                        data-id="<?= $perfect['Id'] ?>">
                                        Invite
                                    </button>
                                </td>
                                <td>
                                    <?= $perfect['LastInviteSent'] ? htmlspecialchars($perfect['LastInviteSent']) : '<span class="text-muted">Never</span>' ?>
                                </td>
                                <td>
                                        <a href="tutorapplicantinfo.php?id=<?= $perfect['Id'] ?>" 
                                        class="btn btn-warning btn-xs">
                                        View
                                        </a>
                                    </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-xs btn-delete" data-id="<?= $perfect['Id'] ?>">Delete</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <?php else: ?>
                    <p>No perfect applicants meet the criteria yet.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Top Qualifying Applicants -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Top Qualifying Applicants</h3>
            </div>
            <div class="box-body table-responsive">
                <?php if($topApplications->num_rows > 0): ?>
                    <table id="topTable" class="table table-bordered table-hover">
                        <thead style="background-color:#d1eaff;">
                            <tr>
                                <th>Name</th>
                                <th>Surname</th>
                                <th>Math_%</th>
                                <th>Sci_%</th>
                                <th>U_Stud?</th>
                                <th>Exp?</th>
                                <th>S_Imme?</th>
                                <th>Pay</th>
                                <th>CV</th>
                                <th>Invite</th>
                                <th>Last_Invite</th>
                                 <th>More</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($top = $topApplications->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($top['Name']) ?></td>
                                    <td><?= htmlspecialchars($top['Surname']) ?></td>
                                    <td><?= htmlspecialchars($top['MathsMark']) ?></td>
                                    <td><?= htmlspecialchars($top['ScienceMark']) ?></td>
                                    <td><?= htmlspecialchars($top['UniversityStudent']) ?></td>
                                    <td><?= htmlspecialchars($top['Experience']) ?></td>
                                    <td><?= htmlspecialchars($top['StartImmediately']) ?></td>
                                    <td>R <?= htmlspecialchars($top['ExpectedPay']) ?></td>
                                    <td>
                                        <a href="<?= CVS_URL ?>/<?= htmlspecialchars($top['CV_Matric']) ?>" 
                                          class="btn btn-info btn-xs" target="_blank">CV</a>
                                    </td>
                                    <td>
                                      <button 
                                          type="button" 
                                          class="btn btn-success btn-xs btn-invite" 
                                          data-id="<?= $top['Id'] ?>">
                                          Invite
                                      </button>
                                    </td>
                                    <td>
                                        <?= $top['LastInviteSent'] ? htmlspecialchars($top['LastInviteSent']) : '<span class="text-muted">Never</span>' ?>
                                    </td>
                                    <td>
                                        <a href="tutorapplicantinfo.php?id=<?= $top['Id'] ?>" 
                                        class="btn btn-warning btn-xs">
                                        View
                                        </a>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-xs btn-delete" data-id="<?= $top['Id'] ?>">Delete</button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>

                <?php else: ?>
                    <p>No top qualifying applicants meet the criteria yet.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- All Applications -->
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">All NON-qualifying applications</h3>
            </div>
            <div class="box-body table-responsive">
                <?php if($applications->num_rows > 0): ?>
                    <table id="applicationsTable" class="table table-bordered table-hover">
                        <thead style="background-color:#f06f6fff;">
                            <tr>
                                <th>Name</th>
                                <th>Surname</th>
                                <th>Applied On</th>
                                <th>Delete</th>
                                <th>more</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($app = $applications->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($app['Name']) ?></td>
                                    <td><?= htmlspecialchars($app['Surname']) ?></td>
                                    <td><?= htmlspecialchars($app['SubmissionDate']) ?></td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-xs btn-delete" data-id="<?= $app['Id'] ?>">Delete</button>
                                    </td>

                                    <td>
                                        <a href="tutorapplicantinfo.php?id=<?= $app['Id'] ?>" 
                                        class="btn btn-warning btn-xs">
                                        View
                                        </a>
                                    </td>

                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No applications submitted yet.</p>
                <?php endif; ?>
            </div>
        </div>

    </section>
</div>

<div class="control-sidebar-bg"></div>
</div>

<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>

<script>
$(document).ready(function() {
    // Initialize all DataTables
    $('#perfectTable, #topTable, #applicationsTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true
    });

    // Delete confirmation
    $('.btn-delete').on('click', function() {
        const appId = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: 'This will permanently delete this application.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'delete_tutorapplication.php?id=' + appId;
            }
        });
    });


    // OPEN invite MODAL
    $('.btn-invite').on('click', function() {
        const appId = $(this).data('id');
        $('#invite_app_id').val(appId);
        $('#inviteModal').modal('show');
    });

    // SEND INVITE
    $('#sendInviteBtn').on('click', function() {

        Swal.fire({
            title: 'Send Interview Invite?',
            text: 'This will email the interview details to the applicant.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Send',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#inviteModalForm').submit();
            }
        });

    });

    


    // Success/Error alerts
    <?php if(isset($_SESSION['success'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '<?= addslashes($_SESSION['success']) ?>',
            confirmButtonText: 'OK'
        });
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        Swal.fire({
            icon: 'error',
            title: 'Failed',
            text: '<?= addslashes($_SESSION['error']) ?>',
            confirmButtonText: 'OK'
        });
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
});
</script>
<script>

$(document).ready(function() {
    // Populate email when Contact modal is shown
    $('#modal-contact').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var email = button.data('email');    // Extract email
        var modal = $(this);

        // Populate the email input
        modal.find('#contactEmail').val(email);

        // Optional: clear subject & message
        modal.find('#contactSubject').val('');
        modal.find('#contactMessage').val('');
    });
});


</script>

<!-- Invite Modal -->
<div class="modal fade" id="inviteModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title">Send Interview Invite</h4>
      </div>

      <div class="modal-body">
        <form id="inviteModalForm" method="post" action="tutorinterviewinvitation.php">
            <input type="hidden" name="id" id="invite_app_id">

            <div class="form-group">
                <label>Interview Date</label>
                <input type="date" name="interview_date" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Start Time</label>
                <input type="time" name="interview_start_time" class="form-control" required>
            </div>

            <div class="form-group">
                <label>End Time</label>
                <input type="time" name="interview_end_time" class="form-control" required>
            </div>
        </form>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
        <button type="button" id="sendInviteBtn" class="btn btn-success btn-sm">Send Invite</button>
      </div>

    </div>
  </div>
</div>

<!-- Contact Modal -->
<div class="modal fade" id="modal-contact" tabindex="-1" role="dialog" aria-labelledby="contactLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">

      <div class="modal-header bg-info">
        <h4 class="modal-title" id="contactLabel">Update Applicant</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        
        <form action="emailsuperhandler.php" method="post" id="contactForm">
              
          <div class="form-group">
            <label for="contactEmail">Email to:</label>
              <input type="email" id="contactEmail" class="form-control" name="emailto" placeholder="Email" required>
            </div>

            <div class="form-group">
              <input type="text" class="form-control" name="subject" placeholder="Subject" required>
            </div>
            <div>
              <textarea class="textarea" name="message" placeholder="Message" style="width: 100%; height: 125px; border: 1px solid #ddd;" ></textarea>
            </div>
                  <!-- Optional hidden inputs for email type / redirect -->
                  <input type="hidden" name="action" value="general">
                  <input type="hidden" name="redirect" value="tutorapplications.php">
                  <input type="submit" class="btn btn-primary" value="Submit" name="btnsend">
        </form>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
      </div>

    </div>
  </div>
</div>



</body>
</html>
