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

<?php include_once(ADMIN_PATH . "/../partials/header.php"); ?>
<?php include_once(ADMIN_PATH . "/../partials/mainsidebar.php"); ?>

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
              <div class="box box-primary" style="min-height: 300px;">
                <div class="box-header with-border text-center">
                    <?php
                    $profilePic = !empty($tutor['ProfilePicture']) 
                        ? PROFILE_PICS_URL . '/' . basename($tutor['ProfilePicture'])
                        : PROFILE_PICS_URL . '/doe.jpg';
                    ?>
                    <img src="<?= htmlspecialchars($profilePic) ?>" alt="Profile Picture" class="img-circle" style="width: 80px; height: 80px; margin-top:5px;">

                  
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
                  <p><strong>Availability:</strong> <?= htmlspecialchars($tutor['Availability']) ?: 'Not specified' ?></p>
                  <hr>
                  <div class="btn-group">
                    <a href="updatetutors.php?id=<?= $tutor['TutorId'] ?>" class="btn btn-sm btn-info">View / Update</a>                    
                    
                    
                    

                    <button class="btn btn-success btn-sm" data-toggle="modal" 
                        data-target="#modal-summary" 
                        data-tutorid="<?= $tutor['TutorId'] ?>" 
                        data-name="<?= htmlspecialchars($tutor['Name'].' '.$tutor['Surname']) ?>">
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
<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>

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


<!-- SUMMARY MODAL  -->
<div class="modal fade" id="modal-summary" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h4 class="modal-title" id="summaryTitle">Performance Summary</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p><strong>Tutor:</strong> <span id="summaryTutorName"></span></p>
        <ul id="summaryData"><li>Loading...</li></ul>
        <div class="text-center mt-2">
          <button id="btnOneOnOne" class="btn btn-success btn-sm">1-on-1 Feedback</button>
          <button id="btnClassMeeting" class="btn btn-info btn-sm">Class Feedback</button>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!--  ONE-ON-ONE FEEDBACK MODAL  -->
<div class="modal fade" id="modal-oneonone" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h4 class="modal-title">One-on-One Detailed Feedback</h4>
        <button class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="table-responsive" style="overflow-x:auto;">
        <table class="table table-bordered table-striped">
          <thead>
            <tr><th>Subject</th><th>Rating</th><th>Questions Answered satisfactorily?</th><th>How engaging was the tutor?</th><th>How clear were the tutor’s explanations?</th></tr>
          </thead>
          <tbody id="oneOnOneBody"></tbody>
        </table>
        </div>
      </div>
      <div class="modal-footer"><button class="btn btn-default" data-dismiss="modal">Close</button></div>
    </div>
  </div>
</div>

<!--  CLASS MEETING FEEDBACK MODAL  -->
<div class="modal fade" id="modal-class" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info">
        <h4 class="modal-title">Class Meeting Detailed Feedback</h4>
        <button class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr><th>Subject</th><th>Rating</th><th>Comment</th></tr>
          </thead>
          <tbody id="classBody"></tbody>
        </table>
      </div>
      <div class="modal-footer"><button class="btn btn-default" data-dismiss="modal">Close</button></div>
    </div>
  </div>
</div>


<script>


$('#modal-summary').on('show.bs.modal', function(event){
    var button = $(event.relatedTarget);
    var tutorid = button.data('tutorid');
    var name = button.data('name');
    var modal = $(this);
    modal.attr('data-tutorid', tutorid);
    modal.find('#summaryTutorName').text(name);
    modal.find('#summaryData').html('<li>Loading...</li>');

    $.ajax({
    url: 'fetch_tutor_feedback.php',
    method: 'POST',
    data: { tutor_id: tutorid, type: 'summary' }, 
    dataType: 'json',
    success: function(data) {
        var html = '';
        html += '<li>Average Rating: ' + (data.avg_rating ?? 'N/A') + ' ⭐</li>';
        html += '<li>Total Feedbacks: ' + (data.count ?? 0) + '</li>';

        if(data.comments && data.comments.length > 0){
            html += '<li>Recent Comments:</li>';
            html += '<ul style="padding-left: 20px;">';
            
            // First comment: One-on-One
            if(data.comments[0]) html += '<li>[1-on-1] Questions Answered satisfactorily?: ' + data.comments[0] + '</li>';

            // Second comment: Class Meeting
            if(data.comments[1]) html += '<li>[Class Meeting] Comment: ' + data.comments[1] + '</li>';
            
            html += '</ul>';
        }

        modal.find('#summaryData').html(html);
    },
    error: function(err){
        console.log(err);
        modal.find('#summaryData').html('<li>Error fetching feedback.</li>');
    }
});

});




// ONE-ON-ONE BUTTON
$('#btnOneOnOne').click(function(){
    var tutorid = $('#modal-summary').attr('data-tutorid');
    $.ajax({
        url: 'fetch_tutor_feedback.php',
        type: 'POST',
        data: { tutor_id: tutorid, type: 'oneonone' },
        dataType: 'json',
        success: function(data){
            var tbody = $('#oneOnOneBody');
            tbody.empty();
            if(!data.details || data.details.length===0) {
                tbody.append('<tr><td colspan="3" class="text-center">No feedback found</td></tr>');
                return;
            }
            data.details.forEach(f=>{
                tbody.append('<tr><td>'+ (f.subject||'N/A') +'</td><td>'+f.rating+' ⭐</td><td>'+ (f.comment||'') +'</td></tr>');
            });
            $('#modal-oneonone').modal('show');
        },
        error: function(){ alert('Error fetching one-on-one feedback'); }
    });
});

// CLASS MEETING BUTTON
$('#btnClassMeeting').click(function(){
    var tutorid = $('#modal-summary').attr('data-tutorid');
    $.ajax({
        url: 'fetch_tutor_feedback.php',
        type: 'POST',
        data: { tutor_id: tutorid, type: 'classmeeting' },
        dataType: 'json',
        success: function(data){
            var tbody = $('#classBody');
            tbody.empty();
            if(!data.details || data.details.length===0) {
                tbody.append('<tr><td colspan="3" class="text-center">No feedback found</td></tr>');
                return;
            }
            data.details.forEach(f=>{
                tbody.append('<tr><td>'+ (f.subject||'N/A') +'</td><td>'+f.rating+' ⭐</td><td>'+ (f.comment||'') +'</td></tr>');
            });
            $('#modal-class').modal('show');
        },
        error: function(){ alert('Error fetching class feedback'); }
    });
});
</script>

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
