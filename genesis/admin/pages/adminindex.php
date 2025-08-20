<!DOCTYPE html>
<html>

<?php
  session_start();
  if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
  }
?>

<?php include(__DIR__ . "/../../common/partials/head.php"); ?>

<!-- Styles -->
<style>
  .dashline {
    border-top: 1px dashed #ccc;
    margin: 15px 0;
  }
  .modal-header {
    background-color: rgba(166, 220, 248, 1);
    color: white;
    padding: 20px;
    border-bottom: 1px solid #eee;
  }
  .modal-header h3 {
    margin: 0;
    color: blue;
  }
  .notice {
    padding: 15px;
    background-color: #f9f9f9;
    border-left: 5px solid #3c8dbc;
    border-radius: 4px;
    transition: background-color 0.3s ease;
  }
  .notice:hover {
    background-color: #eef5fb;
  }
  .modal-footer {
    padding: 15px 20px;
    background-color: #6cbedfff;
    text-align: right;
    border-top: 1px solid #ddd;
  }
  .modal-body p {
    margin-bottom: 5px;
  }
  .modal-content {
    box-shadow: 0 3px 9px rgba(0,0,0,0.3);
  }
  .modal-backdrop {
    background-color: rgba(0, 0, 0, 0.3);
  }
</style>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">
    <?php include(__DIR__ . "/../partials/header.php"); ?>
    <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

    <div class="content-wrapper">
      <!-- cover section (untouched)-->
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
          $sql = "SELECT COUNT(*) as count FROM users WHERE IsVerified = 1 AND UserType = '2'";
          $result = $connect->query($sql);
          $row = $result->fetch_assoc(); 


          $requests = $connect->query("SELECT COUNT(*) as countinvites FROM inviterequests");
          if ($requests) {
              $rowinvites = $requests->fetch_assoc();
              $inviteRequests = $rowinvites['countinvites'];
          } else {
              $inviteRequests = 0; // or handle error
          }

          $verify = $connect->query("SELECT COUNT(*) as countusers FROM users WHERE IsVerified = 0 AND UserType = '2' ");
          if ($verify) {
              $rowverify = $verify->fetch_assoc();
              $users = $rowverify['countusers'];
          } else {
              $users = 0; // or handle error
          }
          
        ?>

        <!-- Stat Boxes -->
        <div class="row">
          
          <div class="col-lg-3 col-xs-6">
            <div class="small-box" style="background-color: #7bd3f6ff;">
              <div class="inner">
                <h3><?= $users ?></h3>
                <p>Pending Verification</p>
              </div>
              <a href="pendingverifications.php">
                <div class="icon" style="font-size: 50px; top: 10px;">
                  <i class="ion ion-alert-circled"></i>
                </div>
              </a>
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
            </div>
          </div>

          <div class="col-lg-3 col-xs-6">
            <div class="small-box" style="background-color: #b2ebf2;"> <!-- light aqua  -->
              <div class="inner">
                <h3><?php echo $row['count']; ?></h3>
                <p>Learners Registered</p>
              </div>
              <a href="updatelearnerlist.php">
                <div class="icon" style="font-size: 55px; top: 10px;">
                  <i class="ion ion-person-add"></i>
                </div>
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-xs-6">
            <div class="small-box" style="background-color: #dd90d3ff;">
              <div class="inner">
                <h3><?= 55 ?></h3>
                <p>Student Voices</p>
              </div>
              <a href="voices.php">
                <div class="icon" style="font-size: 50px; top: 10px;">
                  <i class="fa fa-bullhorn"></i>
                </div>
              </a>

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
                        <!-- checkbox -->
                        <input type="checkbox" value="" <?php if ($task['Status'] == 1) echo 'checked'; ?>>
                        <!-- todo text -->
                        <span class="text"><?php echo htmlspecialchars($task['TaskText']); ?></span>
                        <!-- Emphasis label -->
                        <small class="label label-info">
                          <i class="fa fa-clock-o"></i> <?php echo $dueDate . ' ' . $dueTime; ?>
                        </small>
                        <!-- General tools such as edit or delete -->
                        <div class="tools">
                          <a href="updateTodo.php?todo_id=<?php echo $task['TodoId']; ?>" class="fa fa-edit"></a>
                          <a href="deleteTodo.php?todo_id=<?php echo $task['TodoId']; ?>" class="fa fa-trash-o" onclick="return confirm('Are you sure you want to delete this task?');"></a>
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
                <a href="todo.php" class="btn btn-block btn-primary"><i class="fa fa-plus"></i> Add item</a>
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
                <form action="quickmail.php" method="post">
                  <div class="form-group"><input type="email" class="form-control" name="emailto" placeholder="Email to:"></div>
                  <div class="form-group"><input type="text" class="form-control" name="subject" placeholder="Subject"></div>
                  <div>
                    <textarea class="textarea" name="message" placeholder="Message" style="width: 100%; height: 125px; border: 1px solid #ddd;"></textarea>
                  </div>
                  <input type="submit" class="btn btn-primary" value="Submit" name="btnsend">
                </form>
              </div>
            </div>

          </section>

        </div>

      </section>
    </div>

    <div class="control-sidebar-bg"></div>
  </div>

  <!-- Scripts -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

  <?php
    //include('../partials/connect.php');
    $userId = $_SESSION['user_id'];

    // Load user data
    $usql = "SELECT * FROM users WHERE Id = ?";
    $stmtUser = $connect->prepare($usql);
    $stmtUser->bind_param("i", $userId);
    $stmtUser->execute();
    $resultUser = $stmtUser->get_result();
    $userData = $resultUser->fetch_assoc();
    $stmtUser->close();

    // Fetch notices
    $sql = "SELECT NoticeNo, Title, Content, Date FROM notices WHERE ExpiryDate >= CURDATE() ORDER BY Date DESC";
    $results = $connect->query($sql);
  ?>

  <!-- Only show modal the first time -->
  <?php if (!isset($_SESSION['seen_notification'])): ?>
    <script>
      $(document).ready(function () {
        $('#myModal').modal('show');
      });
    </script>
    <?php $_SESSION['seen_notification'] = true; ?>
  <?php endif; ?>

  <!-- Notification Modal -->
  <div class="modal fade" id="myModal" role="dialog" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <a href="adminindex.php" class="close" data-dismiss="modal" onclick="closeModal()">&times;</a>
          <h3 class="modal-title" id="modalTitle">Notification Centre (for now)</h3>
        </div>

        <div class="modal-body">
          <?php if ($results && $results->num_rows > 0): ?>
            <?php while ($notice = $results->fetch_assoc()): ?>
              <div class="notice" data-id="<?php echo $notice['NoticeNo']; ?>">
                <p><strong style="color: blue;">Date:</strong> <?php echo date('Y-m-d', strtotime($notice['Date'])); ?></p>
                <p><strong style="color: blue;">Subject:</strong> <strong style="color: black;"><?php echo htmlspecialchars($notice['Title']); ?></strong></p>
                <p><?php echo nl2br(htmlspecialchars($notice['Content'])); ?></p>
              </div>
              <hr class="dashline" />
            <?php endwhile; ?>
          <?php else: ?>
            <p>No notices available.</p>
          <?php endif; ?>
        </div>

        <div class="modal-footer">
          <a href="adminindex.php" class="close" data-dismiss="modal" onclick="closeModal()" class="btn btn-default">Close</a>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
