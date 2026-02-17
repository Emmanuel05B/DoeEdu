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

    <header class="main-header">
      <!-- Logo -->
      <a href="tutorindex.php" class="logo">
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
      
        <div class="navbar-custom-menu">
          <?php 

          // Pending verification users
          $usersQuery = $connect->query("SELECT COUNT(*) as count FROM users WHERE IsVerified = 1 AND UserType = '2'");
          $pendingUsers = $usersQuery ? $usersQuery->fetch_assoc()['count'] : 0;



          /*
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
          $pendingResult = $pendingQuery->get_result();  */

          // Invite requests
          $inviteQuery = $connect->query("SELECT COUNT(*) as count FROM tutorsessions WHERE TutorId = '2' AND Status = 'Pending'");
          $inviteRequests = $inviteQuery ? $inviteQuery->fetch_assoc()['count'] : 0;







          // Unread student voices
          $voicesQuery = $connect->query("SELECT COUNT(*) as count FROM studentvoices WHERE IsRead = 0");
          $unreadVoices = $voicesQuery ? $voicesQuery->fetch_assoc()['count'] : 0;

          // Expired contracts
          $expiredQuery = $connect->query("SELECT COUNT(*) AS count FROM learnersubject WHERE ContractExpiryDate < CURDATE() AND Status = 'Active'");
          $expiredContracts = $expiredQuery ? $expiredQuery->fetch_assoc()['count'] : 0;
          ?>


          <ul class="nav navbar-nav">

            <!-- Pending verification -->
            <li>
              <a href="pendingverifications.php">
                <i class="fa fa-user-times"></i>
                <span class="label label-warning"><?= $pendingUsers ?></span>
              </a>
            </li>

            <!-- Invite requests -->
            <li>
              <a href="schedule.php">
                <i class="fa fa-envelope-open"></i>
                <span class="label label-info"><?= $inviteRequests ?></span>
              </a>
            </li>

            <!-- Student voices -->
            <li>
              <a href="voices.php">
                <i class="fa fa-bullhorn"></i>
                <span class="label label-success"><?= $unreadVoices ?></span>
              </a>
            </li>

            <!-- Expired contracts -->
            <li>
              <a href="#" data-toggle="modal" data-target="#expiredModal">
                <i class="fa fa-file-text-o"></i>
                <span class="label label-danger"><?= $expiredContracts ?></span>
              </a>
            </li>

            <!-- Original notifications bell -->
            <li>
              <a href="#" data-toggle="modal" data-target="#adminNotificationsModal">
                <i class="fa fa-bell-o"></i>
              </a>
            </li>

            <!-- User account -->
            <li class="dropdown user user-menu">
              
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="../images/doe.jpg" class="user-image" alt="User Image">
                
              </a>
            </li>

          </ul>

        </div>
      </nav>
    </header>
<?php include_once(TUTOR_PATH . "/../partials/mainsidebar.php"); ?>

  <?php if (isset($_GET['added']) && $_GET['added'] == 1): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
    icon: 'success',
    title: 'Link Added!',
    text: 'The meeting link has been successfully added for this class.',
    confirmButtonText: 'OK'
});
</script>
<?php endif; ?>

<?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
    icon: 'error',
    title: 'Oops!',
    text: 'Something went wrong while adding the link. Please try again.',
    confirmButtonText: 'OK'
});
</script>
<?php endif; ?>


  <div class="content-wrapper">
    
    <section class="content-header">

          <h1>Dashboard <small>Tutor overview</small></h1>
          <ol class="breadcrumb">
            <li><a href="tutorindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
          </ol>

    </section>

    <section class="content">

      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <div class="small-box" style="background:#a3bffa; color:#000;">
            <div class="inner">
              <h3>12</h3>
              <p>Unmarked Submissions</p>
            </div>
            <div class="icon" style="font-size: 50px; top: 10px;">
              <i class="fa fa-tasks"></i>
            </div>
            <a href="manageactivities.php" class="small-box-footer" style="color:#d4dbff;">
              Mark Scripts <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <div class="small-box" style="background:#a3bffa; color:#000;">
            <div class="inner">
              <h3>5</h3>
              <p>Pending Session Requests</p>
            </div>
            <div class="icon" style="font-size: 50px; top: 10px;">
              <i class="fa fa-clock-o"></i>
            </div>
            <a href="schedule.php" class="small-box-footer" style="color:#d7cafb;">
              View Requests <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <div class="small-box" style="background:#a3bffa; color:#000;">
            <div class="inner">
              <h3>7</h3>
              <p>Accepted Sessions</p>
            </div>
            <div class="icon" style="font-size: 50px; top: 10px;">
              <i class="fa fa-users"></i>
            </div>
            <a href="schedule.php" class="small-box-footer" style="color:#d7cafb;">
              Visit Sessions <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <div class="small-box" style="background:#a3bffa; color:#000;">
            <div class="inner">
              <h3>3</h3>
              <p>Reminders(Due date for quiz)</p>
            </div>
            <div class="icon" style="font-size: 50px; top: 10px;">
              <i class="fa fa-calendar"></i>
            </div>
            <a href="manageactivities.php" class="small-box-footer" style="color:#d7cafb;">
              View reminders <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
      </div>

      <div class="row">

        <?php
      
          $tutorId = $_SESSION['user_id'];
      
          // Fetch all classes assigned for this Tutor
          $sql = "SELECT c.*, s.SubjectName, s.MaxClassSize, s.SubjectId
                  FROM classes c
                  INNER JOIN subjects s ON c.SubjectID = s.SubjectID
                  WHERE c.TutorId = ?";

          $stmt = $connect->prepare($sql);
          $stmt->bind_param("i", $tutorId);   
          $stmt->execute();
          $result = $stmt->get_result();


          while ($row = $result->fetch_assoc()) {
            $classId = $row['ClassID'];
            $grade = $row['Grade'];
            $group = $row['GroupName'];
            $subjectName = $row['SubjectName'];
            $learnerCount = $row['CurrentLearnerCount'];
            $maxSize = $row['MaxClassSize'];
            $status = $learnerCount >= $maxSize ? 'Full' : 'Not Full';

            // Determine box color
            $boxColor = $learnerCount == 0 ? '#f8d7da' : '#ffffff'; // redish if 0 learners, default white otherwise
      
        ?>
        <div class="col-md-6">
          <div class="box box-primary" style="background-color: <?php echo $boxColor; ?>;">
            <div class="box-header with-border text-center">
              <h3 class="box-title" style="margin:10px auto;"><?php echo $grade; ?></h3>
              <p><i class="fa fa-book"></i> <?php echo $subjectName; ?> - Group <?php echo $group; ?></p>
              <p><i class="fa fa-users"></i> <strong><?php echo $learnerCount; ?> learner<?php echo $learnerCount != 1 ? 's' : ''; ?></strong></p>
              <p>
                <i class="fa fa-circle text-<?php echo $row['Status'] == 'Full' ? 'red' : 'green'; ?>"></i> 
                <?php echo $row['Status']; ?>
              </p>            
            </div>
            
            <div class="box-body text-center" style="background-color: <?php echo $learnerCount == 0 ? '#f7c6c7' : '#a3bffa'; ?>;">

              <?php $disabled = $learnerCount == 0 ? 'disabled" style="pointer-events:none;' : ''; ?>
              <?php $btnClass = $learnerCount == 0 ? 'btn btn-default btn-sm' : 'btn btn-info btn-sm'; ?>
              <!-- Button that triggers modal -->
                     
             
              <button 
                class="<?php echo $btnClass; ?> <?php echo $disabled; ?>" 
                style="width: 90px;" 
                data-toggle="modal" 
                data-target="#modal-recordMarks"
                data-grade="<?php echo $grade; ?>"
                data-subject="<?php echo $row['SubjectId']; ?>"
                data-subjectname="<?php echo $subjectName; ?>"
                data-group="<?php echo $group; ?>">
                Record Marks
              </button>

              <!-- Quizzes button -->
              <a href="assignedquizzes.php?sub=<?php echo $row['SubjectID'] ?>&gra=<?php echo $grade ?>&group=<?php echo $group ?>" 
                class="<?php echo $btnClass; ?> <?php echo $disabled; ?>" 
                style="width: 90px;">
                + Quizzes
              </a>

              <a href="resources.php?sub=<?php echo $row['SubjectID'] ?>&gra=<?php echo $grade ?>&group=<?php echo $group ?>" 
                class="<?php echo $btnClass; ?> <?php echo $disabled; ?>" 
                style="width: 90px;">
                + Resources
              </a>

              <a href="alllearner.php?subject=<?php echo $row['SubjectID'] ?>&grade=<?php echo $grade ?>&group=<?php echo $group ?>"
                class="btn btn-info btn-sm" 
                style="width: 90px;">
                Open Class
              </a>
              
              <button 
                
                class="<?php echo $btnClass; ?> <?php echo $disabled; ?>" 
                style="width: 90px;" 
                data-toggle="modal" 
                data-target="#modal-addMeetingLink"
                data-class="<?php echo $classId; ?>"
                data-grade="<?php echo $grade; ?>"
                data-subject="<?php echo $row['SubjectID']; ?>"
                data-subjectname="<?php echo $subjectName; ?>"
                data-group="<?php echo $group; ?>">
                Add Link
              </button>

              <button 
                class="<?php echo $btnClass; ?> <?php echo $disabled; ?>"  
                style="width: 90px;"
                data-toggle="modal" 
                data-target="#modal-notifyClass"
                data-grade="<?php echo $grade; ?>"
                data-subject="<?php echo $subjectName; ?>"
                data-group="<?php echo $group; ?>">
                Notify Class
              </button>


            </div>
          </div>
        </div>
        <?php } ?>

        <!-- TO DO List -->
        <div class="col-md-6">
            <div class="box box-primary">
              <div class="box-header">
                <i class="ion ion-clipboard"></i>
                <h3 class="box-title">To Do List</h3>

                <div class="box-tools pull-right">
                  <ul class="pagination pagination-sm inline">
                    <li><a href="#">&laquo;</a></li>
                    <li><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">&raquo;</a></li>
                  </ul>
                </div>
              </div>
              <!-- /.box-header -->

              <?php
                // Get the logged-in user's ID
                $creatorId = $_SESSION['user_id']; 

                // Fetch the tasks for the logged-in user from the database
                $sql = "SELECT * FROM TodoList WHERE CreatorId = ? ORDER BY DueDate ASC";  // You can adjust the sorting as needed
                $stmt = $connect->prepare($sql);
                $stmt->bind_param("i", $creatorId);
                $stmt->execute();
                $result = $stmt->get_result();
              ?>

              <div class="box-body">
                <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                <ul class="todo-list">
                  <?php
                  // Check if there are tasks for the logged-in user
                  if ($result->num_rows > 0) {
                    // Loop through the tasks and display them
                    while ($task = $result->fetch_assoc()) {
                      // Format the date and time
                      $dueDate = date('Y-m-d', strtotime($task['DueDate']));
                      $dueTime = date('H:i', strtotime($task['DueDate']));
                      ?>
                      <li>
                        <!-- drag handle -->
                        <span class="handle">
                          <i class="fa fa-ellipsis-v"></i>
                          <i class="fa fa-ellipsis-v"></i>
                        </span>
                        <span class="text"><?php echo htmlspecialchars($task['TaskText']); ?></span>
                        <small class="label label-info">
                          <i class="fa fa-clock-o"></i> <?php echo $dueDate . ' ' . $dueTime; ?>
                        </small>
                        <div class="tools">                        
                          <a href="deleteTodo.php?todo_id=<?php echo $task['TodoId']; ?>" 
                          class="fa fa-trash-o delete-task"></a>
                        </div>
                      </li>
                      <?php
                    }
                  } else {
                    echo '<li>No tasks found.</li>';
                  }
                  ?>
                </ul>
              </div>
              <!-- /.box-body -->
              <div class="box-footer clearfix no-border">
                <a href="#" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modal-default">
                  <i class="fa fa-plus"></i> Add item
                </a>
              </div>
              
            </div>
        </div>

      </div>

    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<!-- Scripts -->
<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>

<!-- Notification Modal -->
<div class="modal fade" id="adminNotificationsModal" tabindex="-1" role="dialog" aria-labelledby="adminNotifTitle" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header bg-primary text-white">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="adminNotifTitle">Notification Centre</h4>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">
        <?php if ($results && $results->num_rows > 0): ?>
          <?php while ($notice = $results->fetch_assoc()): ?>
            <div class="panel panel-default">
              <div class="panel-heading" style="background-color:#f5f5f5;">
                <strong>Date:</strong> <?php echo date('Y-m-d H:i', strtotime($notice['Date'])); ?>
              </div>
              <div class="panel-body">
                <strong><?php echo htmlspecialchars($notice['Title']); ?></strong> <a href="#">dynamic</a><br>
                <?php echo nl2br(htmlspecialchars($notice['Content'])); ?>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p>No notifications available.</p>
        <?php endif; ?>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<!-- To Do List Modal -->
<div class="modal fade" id="modal-default" tabindex="-1" role="dialog" aria-labelledby="toDoListLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      
      <!-- Header -->
      <div class="modal-header" style="background-color: #cce5ff; color: padding: 5px 15px;">
        <h4 class="modal-title" id="toDoListLabel" style="font-size: 1.2em;">Add New To Do Task</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #004085;">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <!-- Body -->
      <div class="modal-body">
        <form action="todohandler.php" method="post">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th rowspan="2">Labels</th>
                <th colspan="5">Fill</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Task Name</td>
                <td>
                  <textarea class="form-control" name="task_name" placeholder="Enter task description here" required></textarea>
                </td>
              </tr>
              <tr>
                <td>Date</td>
                <td><input type="date" class="form-control" name="due_date"></td>
              </tr>
              <tr>
                <td>Time</td>
                <td><input type="time" class="form-control" name="due_time"></td>
              </tr>
              <tr>
                <td>Priority</td>
                <td>
                  <select class="form-control" name="priority">
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                  </select>
                </td>
              </tr>
            </tbody>
          </table>
          <button type="submit" class="btn btn-primary" name="submit">Submit Task</button>
        </form>
      </div>
      
      <!-- Footer -->
      <div class="modal-footer">
        <!-- Optional footer buttons -->
      </div>
      
    </div>
  </div>
</div>

<!-- Record Marks Modal -->
<div class="modal fade" id="modal-recordMarks" tabindex="-1" role="dialog" aria-labelledby="recordMarksLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      
      <div class="modal-header bg-info">
        <h4 class="modal-title" id="recordMarksLabel">Record Marks</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="modalhandler.php" method="post">
        <div class="modal-body">
          <p id="modalClassInfoRecord" style="margin-bottom:15px;"></p>


          <div class="row">
            <!-- Activity Name -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="activityName">Activity Name</label>
                <input type="text" class="form-control" id="activityName" name="activityname" required>
              </div>
            </div>

            <!-- Chapter Name -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="chapterName">Chapter Name</label>
                <input type="text" class="form-control" id="chapterName" name="chaptername" required>
              </div>
            </div>

            <!-- Activity Total -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="activityTotal">Activity Total</label>
                <input type="number" class="form-control" id="activityTotal" name="activitytotal" min="1" max="100" required>
              </div>
            </div>
          </div>

          <!-- Hidden inputs to send to handler -->
          <input type="hidden" id="graid" name="graid">
          <input type="hidden" id="subid" name="subid">
          <input type="hidden" id="groupid" name="group">
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Add Meeting Link Modal -->
<div class="modal fade" id="modal-addMeetingLink" tabindex="-1" role="dialog" aria-labelledby="addMeetingLinkLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      
      <div class="modal-header bg-info">
        <h4 class="modal-title" id="addMeetingLinkLabel">Add Meeting Link</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="addmeetinghandler.php" method="post">
        <div class="modal-body">
          
          <p id="modalClassInfo" style=" margin-bottom:15px;">
            <!-- JS will fill this -->
          </p>
          <div class="form-group">
            <label for="meetingLink">Meeting Link</label>
            <input type="url" class="form-control" id="meetingLink" name="meetinglink" required placeholder="https://meet.google.com/...">
          </div>

          <div class="form-group">
            <label for="meetingNotes">Notes (optional)</label>
            <textarea class="form-control" id="meetingNotes" name="notes" rows="3" placeholder="Any details or comments"></textarea>
          </div>

          <!-- Hidden inputs -->
          <input type="hidden" id="classid" name="classid">
          <input type="hidden" id="grade" name="grade">
          <input type="hidden" id="subjectid" name="subjectid">
          <input type="hidden" id="groupname" name="groupname">
          <input type="hidden" id="subjectname" name="subjectname">
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" name="submit" class="btn btn-primary">Save Link</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Notify Class Modal -->
<div class="modal fade" id="modal-notifyClass" tabindex="-1" role="dialog" aria-labelledby="notifyClassLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      
      <div class="modal-header bg-info">
        <h4 class="modal-title" id="notifyClassLabel">Notify Class</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="addclassnoticeh.php" method="post">
        <div class="modal-body">
          <p id="modalClassInfoNotice" style="margin-bottom:15px;"></p>

          <div class="form-group">
            <label>Title <span style="color:red">*</span></label>
            <input type="text" name="title" class="form-control" required>
          </div>

          <div class="form-group">
            <label>Content <span style="color:red">*</span></label>
            <textarea name="content" class="form-control" rows="5" placeholder="Write your notice here..." required></textarea>
          </div>

          <!-- Hidden inputs -->
          <input type="hidden" name="subject" id="noticeSubject">
          <input type="hidden" name="grade" id="noticeGrade">
          <input type="hidden" name="group" id="noticeGroup">
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Post Notice</button>
        </div>
      </form>
    </div>
  </div>
</div>



<script>
$('#modal-recordMarks').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal 
    var modal = $(this);

    // Get data attributes
    var grade = button.data('grade');
    var subject = button.data('subject');
    var group = button.data('group');
    var subjectName = button.data('subjectname');

    // Fill hidden inputs
    modal.find('#graid').val(grade);
    modal.find('#subid').val(subject);
    modal.find('#groupid').val(group);

     modal.find('#modalClassInfoRecord').text(`${subjectName} | ${grade} | Group: ${group}`);
});
</script>

<script>
$('#modal-addMeetingLink').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget); 
  var modal = $(this);

  // Get data attributes
  var classId = button.data('class');
  var grade = button.data('grade');
  var subjectId = button.data('subject');
  var groupName = button.data('group');
  var subjectName = button.data('subjectname');

  // Fill hidden inputs
  modal.find('#classid').val(classId);
  modal.find('#grade').val(grade);
  modal.find('#subjectid').val(subjectId);
  modal.find('#groupname').val(groupName);
  modal.find('#subjectname').val(subjectName);

  // Display class info line
  modal.find('#modalClassInfo').text(`${grade} | ${subjectName} | Group: ${groupName}`);
});
</script>

<script>
$('#modal-notifyClass').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget); // Button that triggered the modal
  var modal = $(this);

  var grade = button.data('grade');
  var subject = button.data('subject');
  var group = button.data('group');

  // Set hidden inputs
  modal.find('#noticeGrade').val(grade);
  modal.find('#noticeSubject').val(subject);
  modal.find('#noticeGroup').val(group);

  // Display class info at top of modal
  modal.find('#modalClassInfoNotice').text(`${subject} | ${grade} | Group: ${group}`);
});
</script>


<!-- show modal the first time -->
<?php if (!isset($_SESSION['seen_notification'])): ?>
<script>
  $(document).ready(function () {
    $('#adminNotificationsModal').modal('show');
  });
</script>
<?php $_SESSION['seen_notification'] = true; ?>
<?php endif; ?>

<script>
  $(document).ready(function() {

      // Close the modal and redirect when clicking on the backdrop
      $('.modal').on('click', function (e) {
          if ($(e.target).is('.modal')) {
              window.location.href = 'classes.php'; 
          }
      });
  });
</script>




</body>
</html>
