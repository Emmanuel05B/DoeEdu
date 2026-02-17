<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");
include_once(BASE_PATH . "/partials/connect.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

// Fetch all marketer interview invitations with applicant info
$sqlInvited = "
    SELECT mi.*, ma.Name, ma.Surname, ma.Email, ma.CV_Matric
    FROM marketerinvitations mi
    INNER JOIN marketerapplications ma
        ON mi.MarketerApplicationId = ma.Id
    ORDER BY mi.SentAt DESC
";
$invited = $connect->query($sqlInvited);

// Fetch perfect applicants
$sqlPerfect = "
    SELECT *
    FROM marketerapplications
    WHERE Matric = 'Yes'
      AND EnglishMark >= 70
      AND DigitalMarketingSkill IN ('Intermediate','Advanced')
      AND MarketingTools != 'None'
      AND Communication = 'Excellent'
      AND ContentCreation = 'Yes'
      AND SocialMediaExp = 'Yes'
      AND ExpectedPay <= 50
    ORDER BY SubmissionDate DESC
";
$perfectApplications = $connect->query($sqlPerfect);

// Collect perfect IDs
$perfectIds = [];
while ($row = $perfectApplications->fetch_assoc()) {
    $perfectIds[] = $row['Id'];
}
$perfectApplications->data_seek(0);

// Fetch top applicants (excluding perfect)
$excludeIds = !empty($perfectIds) ? implode(',', $perfectIds) : '0';
$sqlTop = "
    SELECT *
    FROM marketerapplications
    WHERE Matric = 'Yes'
      AND EnglishMark >= 50 AND EnglishMark < 70
      AND DigitalMarketingSkill IN ('Beginner','Intermediate','Advanced')
      AND MarketingTools != 'None'
      AND Id NOT IN ($excludeIds)
    ORDER BY SubmissionDate DESC
";
$topApplications = $connect->query($sqlTop);

// Fetch non-qualifying applications
$sqlAll = "
    SELECT *
    FROM marketerapplications
    WHERE Id NOT IN (

        -- Perfect
        SELECT Id FROM marketerapplications
        WHERE Matric = 'Yes'
          AND EnglishMark >= 70
          AND DigitalMarketingSkill IN ('Intermediate','Advanced')
          AND MarketingTools != 'None'
          AND Communication = 'Excellent'
          AND ContentCreation = 'Yes'
          AND SocialMediaExp = 'Yes'
          AND ExpectedPay <= 50

        UNION

        -- Top
        SELECT Id FROM marketerapplications
        WHERE Matric = 'Yes'
          AND EnglishMark >= 50 AND EnglishMark < 70
          AND DigitalMarketingSkill IN ('Beginner','Intermediate','Advanced')
          AND MarketingTools != 'None'
    )
    ORDER BY SubmissionDate DESC
";
$nonQualifyingApplications = $connect->query($sqlAll);
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
        <h1>Marketer Applications <small>manage applications</small></h1>
        <ol class="breadcrumb">
            <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Applications</li>
        </ol>
    </section>

    <section class="content">
      <!-- Invited Applicants -->
      <div class="box box-warning">
           <div class="box-header with-border">
              <h3 class="box-title">Invited for Interviews</h3>
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
                                  <a href="<?= COMMON_URL ?>/uploads/<?= htmlspecialchars($row['CV_Matric']) ?>" 
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
                                  Contact
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
                <h3 class="box-title">Perfect Applicants</h3>
            </div>
            <div class="box-body table-responsive">
                <?php if($perfectApplications->num_rows > 0): ?>
                <table id="perfectTable" class="table table-bordered table-hover">
                    <thead style="background-color:#d1ffd6;">
                        <tr>
                            <th>Name</th>    
                            <th>Surname</th>    
                            <th>Eng %</th>   
                            <th>Dig_Skill</th>      
                            <th>Tools</th>      
                            <th>Comm</th>   
                            <th>Pay</th>   
                            <th>CV</th>       
                            <th>Inv</th>         
                            <th>Last Inv</th>  
                            <th>More</th>        
                            <th>Del</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $perfectApplications->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['Name']) ?></td>
                            <td><?= htmlspecialchars($row['Surname']) ?></td>
                            <td><?= htmlspecialchars($row['EnglishMark']) ?></td>
                            <td><?= htmlspecialchars($row['DigitalMarketingSkill']) ?></td>
                            <td><?= htmlspecialchars($row['MarketingTools']) ?></td>
                            <td><?= htmlspecialchars($row['Communication']) ?></td>
                            <td>R <?= htmlspecialchars($row['ExpectedPay']) ?></td>
                            <td>
                                <a href="<?= CVS_URL ?>/<?= htmlspecialchars($row['CV_Matric']) ?>" class="btn btn-info btn-xs" target="_blank">CV</a>
                            </td>
                            <td>
                                <button 
                                    type="button" 
                                    class="btn btn-success btn-xs btn-invite" 
                                    data-id="<?= $row['Id'] ?>">
                                    Invite
                                </button>
                            </td>
                            <td>
                                <?= $row['LastInviteSent'] ? htmlspecialchars($row['LastInviteSent']) : '<span class="text-muted">Never</span>' ?>
                            </td>
                            <td>
                                <a href="marketerapplicantinfo.php?id=<?= $row['Id'] ?>" 
                                    class="btn btn-warning btn-xs">
                                    View
                                </a>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-xs btn-delete" data-id="<?= $row['Id'] ?>">Delete</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <p>No perfect applicants yet.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Top Applicants -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Top Applicants</h3>
            </div>
            <div class="box-body table-responsive">
                <?php if($topApplications->num_rows > 0): ?>
                <table id="topTable" class="table table-bordered table-hover">
                    <thead style="background-color:#d1eaff;">
                        <tr>
                            <th>Name</th>    
                            <th>Surname</th>    
                            <th>Eng %</th>   
                            <th>Dig_Skill</th>      
                            <th>Tools</th>      
                            <th>Content</th>   
                            <th>Comm</th>   
                            <th>Pay</th>   
                            <th>CV</th>       
                            <th>Inv</th>         
                            <th>Last Inv</th>  
                            <th>More</th>        
                            <th>Del</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $topApplications->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['Name']) ?></td>
                            <td><?= htmlspecialchars($row['Surname']) ?></td>
                            <td><?= htmlspecialchars($row['EnglishMark']) ?></td>
                            <td><?= htmlspecialchars($row['DigitalMarketingSkill']) ?></td>
                            <td><?= htmlspecialchars($row['MarketingTools']) ?></td>
                            <td><?= htmlspecialchars($row['ContentCreation']) ?></td>
                            <td><?= htmlspecialchars($row['Communication']) ?></td>
                            <td>R <?= htmlspecialchars($row['ExpectedPay']) ?></td>
                            <td>
                                <a href="<?= CVS_URL ?>/<?= htmlspecialchars($row['CV_Matric']) ?>" class="btn btn-info btn-xs" target="_blank">CV</a>
                            </td>
                            <td>
                                <button 
                                    type="button" 
                                    class="btn btn-success btn-xs btn-invite" 
                                    data-id="<?= $row['Id'] ?>">
                                    Invite
                                </button>
                            </td>
                            <td>
                                <?= $row['LastInviteSent'] ? htmlspecialchars($row['LastInviteSent']) : '<span class="text-muted">Never</span>' ?>
                            </td>
                            <td>
                                <a href="marketerapplicantinfo.php?id=<?= $row['Id'] ?>" 
                                    class="btn btn-warning btn-xs">
                                    View
                                </a>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-xs btn-delete" data-id="<?= $row['Id'] ?>">Delete</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <p>No top applicants yet.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Non-qualifying Applicants -->
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">XXXXX-qualifying Applicants</h3>
            </div>
            <div class="box-body table-responsive">
                <?php if($nonQualifyingApplications->num_rows > 0): ?>
                <table id="nonQualTable" class="table table-bordered table-hover">
                    <thead style="background-color:#f5c6cb;">
                        <tr>
                            <th>Name</th>
                            <th>Surname</th>
                            <th>English %</th>
                            <th>CV</th>
                            <th>More</th>
                            <th>Inv</th>         
                            <th>Last Inv</th> 
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $nonQualifyingApplications->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['Name']) ?></td>
                            <td><?= htmlspecialchars($row['Surname']) ?></td>
                            <td><?= htmlspecialchars($row['EnglishMark']) ?></td>
                            <td>
                                <a href="<?= CVS_URL ?>/<?= htmlspecialchars($row['CV_Matric']) ?>" class="btn btn-info btn-xs" target="_blank">CV</a>
                            </td>
                            <td>
                                <a href="marketerapplicantinfo.php?id=<?= $row['Id'] ?>" 
                                    class="btn btn-warning btn-xs">
                                    View
                                </a>
                            </td>
                            <td>
                                <button 
                                    type="button" 
                                    class="btn btn-success btn-xs btn-invite" 
                                    data-id="<?= $row['Id'] ?>">
                                    Invite
                                </button>
                            </td>
                            <td>
                                <?= $row['LastInviteSent'] ? htmlspecialchars($row['LastInviteSent']) : '<span class="text-muted">Never</span>' ?>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-xs btn-delete" data-id="<?= $row['Id'] ?>">Delete</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <p>No non-qualifying applicants yet.</p>
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
    $('#perfectTable, #topTable, #nonQualTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true
    });

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
                window.location.href = 'delete_marketerapplication.php?id=' + appId;
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

    
    <?php if(isset($_SESSION['success'])): ?>
        Swal.fire({icon: 'success', title: 'Success', text: '<?= addslashes($_SESSION['success']) ?>', confirmButtonText: 'OK'});
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        Swal.fire({icon: 'error', title: 'Failed', text: '<?= addslashes($_SESSION['error']) ?>', confirmButtonText: 'OK'});
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
        <form id="inviteModalForm" method="post" action="marketerinterviewinvitation.php">
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
        <h4 class="modal-title" id="contactLabel">Contact Applicant</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form action="emailsuperhandler.php" method="post" id="contactForm">
          <div class="form-group">
            <label for="contactEmail">Email to:</label>
            <input type="email" id="contactEmail" class="form-control" name="emailto" required>
          </div>
          <div class="form-group">
            <input type="text" class="form-control" name="subject" placeholder="Subject" required>
          </div>
          <div>
            <textarea class="textarea" name="message" placeholder="Message" style="width: 100%; height: 125px; border: 1px solid #ddd;"></textarea>
          </div>
          <input type="hidden" name="action" value="general">
          <input type="hidden" name="redirect" value="marketerapplications.php">
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
