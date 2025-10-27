<?php
require_once __DIR__ . '/../../common/config.php'; 
?> 

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

include_once(COMMON_PATH . "/../partials/head.php");  
include_once(BASE_PATH . "/partials/connect.php");

?>




<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">
    
    <header class="main-header">
      <!-- Logo -->
      <a href="adminindex.php" class="logo">
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

          // Invite requests
          $inviteQuery = $connect->query("SELECT COUNT(*) as count FROM inviterequests");
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
              <a href="manage_inviterequests.php">
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
                <img src="../../uploads/doe.jpg" class="user-image" alt="User Image">
                
              </a>
            </li>

          </ul>

        </div>
      </nav>
    </header>

    <?php include_once(ADMIN_PATH . "/../partials/mainsidebar.php"); ?>

    <div class="content-wrapper">
      <!-- cover section --> 

      <section class="content-header">
        <h1>Dashboard <small>Control panel</small></h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Dashboard</li>
        </ol>

      </section>

      <section class="content">
       
        <?php 
          include('../../partials/connect.php');
          
          // Pending verification users
          $usersQuery = $connect->query("SELECT COUNT(*) as count FROM users WHERE IsVerified = 0 AND UserType = '2'");
          $pendingusers = $usersQuery ? $usersQuery->fetch_assoc()['count'] : 0;

          // Invite requests
          $inviteQuery = $connect->query("SELECT COUNT(*) as countinvites FROM inviterequests");
          $inviteRequests = $inviteQuery ? $inviteQuery->fetch_assoc()['countinvites'] : 0;

          // Unread student voices
          $voicesQuery = $connect->query("SELECT COUNT(*) as unreadCount FROM studentvoices WHERE IsRead = 0");
          $unreadVoices = $voicesQuery ? $voicesQuery->fetch_assoc()['unreadCount'] : 0;

          // Expired contracts
          $expiredQuery = $connect->query("
              SELECT COUNT(*) AS count 
              FROM learnersubject 
              WHERE ContractExpiryDate < CURDATE() AND Status = 'Active'
          ");
          $expiredCount = $expiredQuery ? $expiredQuery->fetch_assoc()['count'] : 0;

          
        ?>

        <!-- Stat Boxes -->
        <div class="row">
          
          <div class="col-lg-3 col-xs-6">
            <div class="small-box" style="background-color: #7bd3f6ff;">
              <div class="inner">
                <h3><?= $pendingusers ?></h3>
                <p>Pending Verification</p>
              </div>
              <a href="pendingverifications.php">
                <div class="icon" style="font-size: 50px; top: 10px;">
                  <i class="ion ion-alert-circled"></i>
                </div>
              </a>
              <a href="pendingverifications.php" class="small-box-footer">open <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-xs-6">
            <div class="small-box" style="background-color: #c9b6f2;">
              <div class="inner">
                <h3><?= $inviteRequests ?></h3>
                <p>Invite Requests</p>
              </div>
              <a href="manage_inviterequests.php">
                <div class="icon" style="font-size: 50px; top: 10px;">
                  <i class="fa fa-envelope-open"></i>
                </div>
              </a>
              <a href="manage_inviterequests.php" class="small-box-footer">open <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-xs-6">
            <div class="small-box" style="background-color: #b2ebf2;">
              <div class="inner">
                <h3><?= $unreadVoices ?></h3>
                <p>Student Voices</p>
              </div>
              <a href="voices.php">
                <div class="icon" style="font-size: 50px; top: 10px;">
                  <i class="fa fa-bullhorn"></i>
                </div>
              </a>
              <a href="voices.php" class="small-box-footer">open <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-xs-6">
            <div class="small-box" style="background-color: #dd90d3ff;">
                <div class="inner">
                    <h3><?php echo $expiredCount; ?></h3>
                    <p>Expired Contracts</p>
                </div>

                <!-- Icon now triggers modal -->
                <a href="#" data-toggle="modal" data-target="#expiredModal">
                    <div class="icon" style="font-size: 55px; top: 10px;">
                        <i class="fa fa-file-text-o"></i> <!-- list/doc icon -->
                    </div>
                </a>
                <a href="#" data-toggle="modal" data-target="#expiredModal" class="small-box-footer">open <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>


        </div>

        <div class="row">
      
          <section class="col-lg-7 connectedSortable">
            <!-- TO DO List -->
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
      
          </section>

          <section class="col-lg-5 connectedSortable">
            <!-- Quick Email Widget -->
            <div class="box box-info">
              <div class="box-header">
                <i class="fa fa-envelope"></i>
                <h3 class="box-title">Quick Email</h3>
                <div class="pull-right box-tools">
                  <button type="button" class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
              </div>
              <div class="box-body">
                <form action="emailsuperhandler.php" method="post">
                  <div class="form-group">
                    <input type="email" class="form-control" name="emailto" placeholder="Email to:" required>
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control" name="subject" placeholder="Subject" required>
                  </div>
                  <div>
                    <textarea class="textarea" name="message" placeholder="Message" style="width: 100%; height: 125px; border: 1px solid #ddd;" ></textarea>
                  </div>
                  <!-- Optional hidden inputs for email type / redirect -->
                  <input type="hidden" name="action" value="general">
                  <input type="hidden" name="redirect" value="adminindex.php">
                  <input type="submit" class="btn btn-primary" value="Submit" name="btnsend">
                </form>

              </div>
            </div>

          </section>

        </div>

      </section>
    </div>

  </div>

  <!-- Scripts -->

  <?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>

  <?php
    if (isset($_SESSION['success'])) {
        $msg = $_SESSION['success'];
        unset($_SESSION['success']);
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Done!',
                text: '". addslashes($msg) ."',
                confirmButtonText: 'OK'
            });
        </script>";
    }

    if (isset($_SESSION['error'])) {
        $msg = $_SESSION['error'];
        unset($_SESSION['error']);
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Failed!',
                text: '". addslashes($msg) ."',
                confirmButtonText: 'OK'
            });
        </script>";
    }
  ?>

  <?php if(isset($_GET['status']) && isset($_GET['message'])): ?>
  <script>
      Swal.fire({
          icon: '<?php echo $_GET['status'] === "success" ? "success" : "error"; ?>',
          title: '<?php echo $_GET['status'] === "success" ? "Success" : "Oops!"; ?>',
          text: '<?php echo htmlspecialchars($_GET['message']); ?>'
      });
  </script>
  <?php endif; ?>

  <script>
  // Select all delete buttons
    document.querySelectorAll('.delete-task').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default navigation
            const url = this.href; // Get the link

            Swal.fire({
                title: 'Are you sure?',
                text: "This will permanently delete the task!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to the delete URL if confirmed
                    window.location.href = url;
                }
            });
        });
    });
  </script>



  <?php
    //include('../partials/connect.php');
    $userId = $_SESSION['user_id'];

    // Load user data
    /*
    $usql = "SELECT * FROM users WHERE Id = ?";
    $stmtUser = $connect->prepare($usql);
    $stmtUser->bind_param("i", $userId);
    $stmtUser->execute();
    $resultUser = $stmtUser->get_result();
    $userData = $resultUser->fetch_assoc();
    $stmtUser->close();

    */

    // Fetch notices
    $sql = "SELECT NoticeNo, Title, Content, Date FROM notices WHERE ExpiryDate >= CURDATE() ORDER BY Date DESC";
    $results = $connect->query($sql);
  ?>



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
              window.location.href = 'adminindex.php'; 
          }
      });
  });
</script>


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


<!-- Expired Learners Modal -->
<div class="modal fade" id="expiredModal" tabindex="-1" role="dialog" aria-labelledby="expiredModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#f44336; color:#fff;">
        <h5 class="modal-title" id="expiredModalLabel">Expired Contracts</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead style="background-color: #fdd;">
              <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Subject</th>
                <th>Registered On</th>
                <th>Expired On</th>
                <th>Drop</th>
                <th>Notify</th>
                <th>Last Reminded</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sqlExpired = "
                SELECT lt.LearnerId, u.Name, u.Surname, u.Email, 
                       ls.SubjectId, ls.ContractStartDate, ls.ContractExpiryDate, ls.LastReminded
                FROM learners lt
                INNER JOIN users u ON lt.LearnerId = u.Id
                LEFT JOIN learnersubject ls ON lt.LearnerId = ls.LearnerId
                WHERE ls.ContractExpiryDate < CURDATE()
                  AND ls.Status = 'Active'
                ORDER BY lt.LearnerId, ls.ContractExpiryDate ASC
              ";
              $stmtExp = $connect->prepare($sqlExpired);
              $stmtExp->execute();
              $expiredResults = $stmtExp->get_result();

              if ($expiredResults && $expiredResults->num_rows > 0):
                  while ($row = $expiredResults->fetch_assoc()):
                      $subjectName = '';
                      if (!empty($row['SubjectId'])) {
                          $subjStmt = $connect->prepare("SELECT SubjectName FROM subjects WHERE SubjectId = ?");
                          $subjStmt->bind_param("i", $row['SubjectId']);
                          $subjStmt->execute();
                          $subjResult = $subjStmt->get_result();
                          $subjRow = $subjResult->fetch_assoc();
                          $subjectName = $subjRow['SubjectName'] ?? '';
                          $subjStmt->close();
                      }
              ?>
              <tr>
                <td><?php echo htmlspecialchars($row['Name']); ?></td>
                <td><?php echo htmlspecialchars($row['Surname']); ?></td>
                <td><?php echo htmlspecialchars($subjectName); ?></td>
                <td><?php echo $row['ContractStartDate'] ? htmlspecialchars(date('Y-m-d', strtotime($row['ContractStartDate']))) : '-'; ?></td>
                <td><?php echo $row['ContractExpiryDate'] ? htmlspecialchars(date('Y-m-d', strtotime($row['ContractExpiryDate']))) : '-'; ?></td>
                <td>
                  <button class="btn btn-danger btn-xs drop-btn" 
                          data-learnerid="<?php echo $row['LearnerId']; ?>" 
                          data-subjectid="<?php echo $row['SubjectId']; ?>">
                    Drop
                  </button>
                </td>
                <td>
                  <button class="btn btn-warning btn-xs notify-btn" 
                          data-learnerid="<?= $row['LearnerId']; ?>" 
                          data-subjectid="<?= $row['SubjectId']; ?>"
                          data-email="<?= htmlspecialchars($row['Email']); ?>"
                          data-subject="<?= htmlspecialchars($subjectName); ?>">
                    Notify
                  </button>
                  
                </td>
                <td><?php echo $row['LastReminded'] ? htmlspecialchars(date('Y-m-d', strtotime($row['LastReminded']))) : '-'; ?></td>
                
              </tr>
              <?php
                  endwhile;
              else:
              ?>
              <tr>
                <td colspan="7" class="text-center">No learners found.</td>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    // Drop subject confirmation
    $('.drop-btn').click(function() {
        let learnerId = $(this).data('learnerid');
        let subjectId = $(this).data('subjectid');

        Swal.fire({
            title: 'Are you sure?',
            text: "This will drop the subject for the learner!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, drop it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form via POST
                $('<form method="post" action="drop_subject.php">' +
                    '<input type="hidden" name="learnerId" value="'+learnerId+'">' +
                    '<input type="hidden" name="subjectId" value="'+subjectId+'">' +
                  '</form>').appendTo('body').submit();
            }
        });
    });
    
    // Notify learner confirmation using emailsuperhandler.php
    $('.notify-btn').click(function() {
      let firstName = $(this).closest('tr').find('td:first').text(); // First Name
      let lastName  = $(this).closest('tr').find('td:nth-child(2)').text(); // Last Name
      let email     = $(this).data('email'); // Learner Email
      let subject   = $(this).data('subject'); // Subject Name
      let learnerName = firstName + ' ' + lastName;
      let learnerId = $(this).data('learnerid');
      let subjectId = $(this).data('subjectid');

      Swal.fire({
          title: 'Send Notification?',
          text: "This will notify the learner about the expired contract.",
          icon: 'info',
          showCancelButton: true,
          confirmButtonColor: '#f0ad4e',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Yes, notify!'
      }).then((result) => {
          if (result.isConfirmed) {
              $('<form method="post" action="emailsuperhandler.php">' +
                  '<input type="hidden" name="emailto" value="' + email + '">' +
                  '<input type="hidden" name="learnerName" value="' + learnerName + '">' +
                  '<input type="hidden" name="subjectName" value="' + subject + '">' +
                  '<input type="hidden" name="learnerId" value="' + learnerId + '">' +
                  '<input type="hidden" name="subjectId" value="' + subjectId + '">' +
                  '<input type="hidden" name="action" value="contract_expiry">' +
                  '<input type="hidden" name="redirect" value="adminindex.php">' +
                '</form>').appendTo('body').submit();
          }
      });
    });

    //end
});
</script>









</body>
</html>
