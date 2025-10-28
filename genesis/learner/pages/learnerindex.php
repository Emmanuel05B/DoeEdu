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


  $learnerId = $_SESSION['user_id'];
  $tutors = [];

  // Fetch learner full name (fallback to session var or 'Learner')
  $stmt = $connect->prepare("SELECT CONCAT(Name, ' ', Surname) AS fullname FROM users WHERE Id = ?");
  $stmt->bind_param("i", $learnerId);
  $stmt->execute();
  $stmt->bind_result($learnerName);
  $stmt->fetch();
  $stmt->close();

  if (!$learnerName) {
      $learnerName = $_SESSION['full_name'] ?? 'Learner';
  }

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


  $alertStatus = $_GET['status'] ?? '';
  $alertMessage = $_GET['message'] ?? '';


// Count Pending Homework (not submitted yet, due date in the future, for active classes and groups)
// Step 1: Get all current classes for this learner
$stmtClasses = $connect->prepare("SELECT ClassID FROM learnerclasses WHERE LearnerID = ?");
$stmtClasses->bind_param("i", $learnerId);
$stmtClasses->execute();
$classResults = $stmtClasses->get_result()->fetch_all(MYSQLI_ASSOC);
$stmtClasses->close();

$pendingHomeworkCount = 0;

if (count($classResults) > 0) {
    foreach ($classResults as $classRow) {
        $classID = $classRow['ClassID'];

        // Step 2: Get homework assigned to this class that is still due
        $stmtActivities = $connect->prepare("
            SELECT a.Id
            FROM onlineactivities a
            INNER JOIN onlineactivitiesassignments aa 
                ON a.Id = aa.OnlineActivityId
            WHERE aa.ClassID = ?
              AND aa.DueDate >= CURDATE()
        ");
        $stmtActivities->bind_param("i", $classID);
        $stmtActivities->execute();
        $activities = $stmtActivities->get_result();

        while ($activity = $activities->fetch_assoc()) {
            $activityId = $activity['Id'];

            // Step 3: Check if learner already submitted any answers for this activity
            $stmtCheck = $connect->prepare("
                SELECT Id FROM learneranswers 
                WHERE ActivityId = ? AND UserId = ? 
                LIMIT 1
            ");
            $stmtCheck->bind_param("ii", $activityId, $learnerId);
            $stmtCheck->execute();
            $submitted = $stmtCheck->get_result()->num_rows > 0;
            $stmtCheck->close();

            // Step 4: Count only if no submission yet
            if (!$submitted) {
                $pendingHomeworkCount++;
            }
        }

        $stmtActivities->close();
    }
}


// Count completed homework
$completedHomeworkCount = 0;
if(count($classResults) > 0){
    foreach ($classResults as $classRow) {
        $classID = $classRow['ClassID'];
        $stmtCompleted = $connect->prepare("
            SELECT a.Id
            FROM onlineactivities a
            INNER JOIN onlineactivitiesassignments aa 
                ON a.Id = aa.OnlineActivityId
            WHERE aa.ClassID = ?
        ");
        $stmtCompleted->bind_param("i", $classID);
        $stmtCompleted->execute();
        $activities = $stmtCompleted->get_result();
        while($activity = $activities->fetch_assoc()){
            $activityId = $activity['Id'];
            $stmtAns = $connect->prepare("SELECT Id FROM learneranswers WHERE ActivityId = ? AND UserId = ? LIMIT 1");
            $stmtAns->bind_param("ii", $activityId, $learnerId);
            $stmtAns->execute();
            if($stmtAns->get_result()->num_rows > 0){
                $completedHomeworkCount++;
            }
            $stmtAns->close();
        }
        $stmtCompleted->close();
    }
}


  $activeClassesCount = count($classIds);

  
  // Count the number of confirmed 1-on-1 sessions that haven't passed yet
  $twoWeeksAgo = (new DateTime())->modify('-14 days')->format('Y-m-d H:i:s');

  $stmt3 = $connect->prepare("
      SELECT COUNT(*) AS ConfirmedCount
      FROM tutorsessions ts
      JOIN users u ON ts.TutorId = u.Id
      JOIN tutors t ON ts.TutorId = t.TutorId
      WHERE ts.LearnerId = ? 
        AND ts.SlotDateTime >= ? 
        AND ts.Hidden = 0
        AND ts.Status = 'Confirmed'
  ");

  $stmt3->bind_param("is", $learnerId, $twoWeeksAgo);
  $stmt3->execute();
  $result3 = $stmt3->get_result();
  $row3 = $result3->fetch_assoc();
  $confirmedCount = $row3['ConfirmedCount'];


  
  $notifications = [];

  if (!empty($classIds)) {
      $inClause = implode(',', array_map('intval', $classIds));
      $notifSql = "
          SELECT Title, Content, Subject, CreatedAt 
          FROM classnotifications 
          WHERE Grade IN (SELECT Grade FROM classes WHERE ClassID IN ($inClause))
            AND Group_Class IN (SELECT Group_Class FROM classes WHERE ClassID IN ($inClause))
            AND CreatedAt >= NOW() - INTERVAL 14 DAY
          ORDER BY CreatedAt DESC
          LIMIT 20
      ";
      $notifResult = $connect->query($notifSql);
      while ($notif = $notifResult->fetch_assoc()) {
          $notifications[] = $notif;
      }
  }


    // Count general announcements
  $generalAnnouncementCount = 0;
  $countSql = "
      SELECT COUNT(*) as total
      FROM notifications
      WHERE CreatedFor IN (1, 12)
        AND (ExpiryDate IS NULL OR ExpiryDate >= NOW())
  ";
  $resultCount = $connect->query($countSql);
  if($rowCount = $resultCount->fetch_assoc()){
      $generalAnnouncementCount = $rowCount['total'];
  }

  
?>



              

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <header class="main-header">
      <!-- Logo -->
      <a href="learnerindex.php" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>Click</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lgd"><b>DoE_Genesis </b></span>
      </a>
      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <span class="sr-only">Toggle navigation</span>
          
          <span class="logo-lg"><b>Distributors Of Education </b></span>

        </a>
        
          <!-- Button to manually open the modal -->
        <a href="#" data-toggle="modal" data-target="#learnerNotificationsModal">
          <i class="fa fa-bell"></i> Notifications
        </a>

        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">

              <li>
                <a href="announcements.php"> 
                    <i class="fa fa-envelope"></i> 
                    <span class="label label-warning"><?= $generalAnnouncementCount ?></span>
                </a>
              </li>
              <!-- pending homeworks -->
              <li>
                <a href="homework.php">
                  <i class="fa fa-tasks"></i>
                  <span class="label label-warning"><?= $pendingHomeworkCount ?></span>
                </a>
              </li>

              <!-- confimed requests -->
              <li>
                <a href="mytutors.php">
                  <i class="fa fa-check-circle text-white"></i>
                  <span class="label label-success"><?= $confirmedCount ?></span>
                </a>
              </li>

              <!-- Original notifications bell -->
              <li>
                <a href="#" data-toggle="modal" data-target="#learnerNotificationsModal">
                  <i class="fa fa-bell-o"></i>
                </a>
              </li>

            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
              <a href="#">
                <img src="<?= PROFILE_PICS_URL . '/doe.jpg' ?>" class="user-image" alt="User Image">
                <span class="hidden-xs"><?php ?></span>
              </a>
            </li>
            
          </ul>
        </div>
      </nav>
    </header>
<?php include_once(LEARNER_PATH . "/../partials/mainsidebar.php"); ?>

  <div class="content-wrapper">

    <section class="content-header">
      <h1 style="color:#3a3a72; font-weight:600;">Welcome back, <?= htmlspecialchars($learnerName) ?> 👋 </h1>
      <p style="color:#888;">Here’s a quick overview of your learning journey.</p>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Dashboard</li>
        </ol>
    </section>

    <section class="content">

      <div class="row">
        <!-- Online Quizzes Average -->
        <div class="col-md-3">
          <div class="box box-primary" style="background:#f9f1fe;">
            <div class="box-body">
              <h4 style="color:#3a3a72;">Online Quizzes Avg</h4>
              <h2>...</h2>
              <i class="fa fa-laptop fa-2x pull-right" style="color:#a06cd5;"></i>
              <a href="#" class="btn btn-link">View Details</a>
            </div>
          </div>
        </div>

        <!-- Tutor Marks Average -->
        <div class="col-md-3">
          <div class="box box-primary" style="background:#f0f7ff;">
            <div class="box-body">
              <h4 style="color:#3a3a72;">Vid Activities Avg</h4>
              <h2>...</h2>
              <i class="fa fa-user-circle fa-2x pull-right" style="color:#0073e6;"></i>
              <a href="tracklearnerprogress.php?type=tutor" class="btn btn-link">View Details</a>
            </div>
          </div>
        </div>

        <!-- Pending Homework Count -->
        <div class="col-md-3">
          <div class="box box-primary" style="background:#e6f0ff;">
            <div class="box-body">
              <h4 style="color:#3a3a72;">Pending Homework</h4>
              <h2><?= $pendingHomeworkCount ?></h2>
              <i class="fa fa-tasks fa-2x pull-right" style="color:#6a52a3;"></i>
              <a href="homework.php" class="btn btn-link">View All</a>
            </div>
          </div>
        </div>

        <!-- Upcoming 1-1 Sessions Count -->
        <div class="col-md-3">
          <div class="box box-primary" style="background:#d1ffe0;">
            <div class="box-body">
              <h4 style="color:#3a3a72;">Upcoming 1-1 Sessions</h4>
              <h2><?= $confirmedCount ?></h2>
              <i class="fa fa-check-circle fa-2x pull-right" style="color:#28a745;"></i>
              <a href="mytutors.php" class="btn btn-link">View Sessions</a>
            </div>
          </div>
        </div>
      </div>


      <div class="row">
        <!-- Tutors -->
          <?php

            $activeMeetings = [];
              if (!empty($classIds)) {
                  $inClause = implode(',', array_map('intval', $classIds));
                  
                  $sql = "
                      SELECT c.TutorID, s.SubjectName, cm.MeetingLink
                      FROM classmeetings cm
                      JOIN classes c ON cm.ClassId = c.ClassID
                      JOIN subjects s ON c.SubjectID = s.SubjectId
                      WHERE cm.Status = 'Active' AND c.ClassID IN ($inClause)
                  ";
                  
                  $res = $connect->query($sql);
                  while ($row = $res->fetch_assoc()) {
                      $activeMeetings[$row['TutorID']][$row['SubjectName']] = $row['MeetingLink'];
                  }
              }

          ?>
          <script>
            const activeMeetings = <?= json_encode($activeMeetings ?? []) ?>;
          </script>

        <!-- My tutor card/s -->
        <?php foreach ($tutors as $tutor): ?>
          <div class="col-md-6">
            <div class="box box-primary" style="min-height: 280px;">
              <div class="box-header with-border text-center">
                <img 
                src="<?= !empty($tutor['ProfilePicture']) ? htmlspecialchars($tutor['ProfilePicture']) : PROFILE_PICS_URL . '/doe.jpg' ?>" 
                alt="Tutor Picture" class="img-circle" width="90" height="90" style="object-fit: cover;">


                <h3 class="box-title" style="margin-top:10px;">
                  <?= htmlspecialchars($tutor['Gender']) . ' ' . htmlspecialchars($tutor['Surname']) ?>
                </h3>
                <p>
                  <span class="label label-info"><?= htmlspecialchars($tutor['Subjects']) ?></span>
                </p>
              </div>
              <div class="box-body text-center">
                <p><strong>Email...to remove:</strong> <?= htmlspecialchars($tutor['Email']) ?></p>
                <p><strong>Availability:</strong> <?= htmlspecialchars($tutor['Availability']) ?: 'Not specified' ?></p>
                <hr>
                <div class="btn-group">
                  
                  
                  <?php if(!empty($tutor['Subjects'])): ?>
                      <button 
                          class="btn btn-sm btn-info openAttendModal"
                          data-tutor="<?= $tutor['TutorId'] ?>"
                          data-name="<?= htmlspecialchars($tutor['Name'] . ' ' . $tutor['Surname']) ?>"
                          data-subjects="<?= htmlspecialchars($tutor['Subjects']) ?>"
                      >
                          Attend Class
                      </button>
                  <?php else: ?>
                      <button class="btn btn-sm btn-info" disabled>
                          Link not available
                      </button>
                  <?php endif; ?>
                  

                  <a href="class.php?tutor=<?= $tutor['TutorId'] ?>" class="btn btn-sm btn-success">Open Past Sessions</a>
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

<!-- Attend Class Modal -->
<div class="modal fade" id="attendClassModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        <h4 class="modal-title">Attend Class with <span id="attendTutorName"></span></h4>
      </div>
      <div class="modal-body">
        <div id="attendSubjectsList">
          <!-- Subjects and buttons to be  be populated here -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Notifications Modal -->
<div class="modal fade" id="learnerNotificationsModal" tabindex="-1" role="dialog" aria-labelledby="learnerNotifTitle" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header bg-primary text-white">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="learnerNotifTitle">Notification Centre</h4>
      </div>  

      <div class="modal-body">
        <?php if (!empty($notifications)): ?>
            <?php foreach ($notifications as $notif): ?>
                <div class="panel panel-default">
                    <div class="panel-heading" style="display: flex; justify-content: space-between; align-items: center;">
                      <span><strong>Date:</strong> <?= htmlspecialchars($notif['CreatedAt']) ?></span>
                      <span><?= htmlspecialchars($notif['Subject']) ?></span>
                   </div>

                    <div class="panel-body">
                        <strong><?= htmlspecialchars($notif['Title']) ?>:</strong><br>
                        <?= nl2br(htmlspecialchars($notif['Content'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center text-muted">
                No notifications at the moment.
            </div>
        <?php endif; ?>
      </div>


      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>  

    </div>
  </div>
</div>







<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>

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

<script>
 //js for attend modal
$('.openAttendModal').on('click', function() {
    const tutorName = $(this).data('name');
    const subjectsStr = $(this).data('subjects'); // comma-separated subjects
    const tutorId = $(this).data('tutor');

    $('#attendTutorName').text(tutorName);
    const subjects = subjectsStr.split(',').map(s => s.trim());

    let html = '<table class="table table-bordered table-striped">';
    html += '<thead><tr><th>Subject</th><th>Link</th></tr></thead><tbody>';

    subjects.forEach(sub => {
        // Check if there's an active link for this tutor
        const link = activeMeetings[tutorId]?.[sub] ?? '';
        html += `<tr>
                    <td>${sub}</td>
                    <td class="text-center">
                      ${link ? 
                          `<a href="${link}" target="_blank" class="btn btn-sm btn-primary">Attend Class</a>` :
                          `<button class="btn btn-sm btn-secondary" disabled>Link Not Available</button>`
                      }
                    </td>
                 </tr>`;
    });

    html += '</tbody></table>';
    $('#attendSubjectsList').html(html);
    $('#attendClassModal').modal('show');
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


<!-- show modal the first time -->
<?php if (!isset($_SESSION['seen_notification'])): ?>
<script>
  $(document).ready(function () {
    $('#learnerNotificationsModal').modal('show');
  });
</script>
<?php $_SESSION['seen_notification'] = true; ?>
<?php endif; ?>


</body>
</html>
