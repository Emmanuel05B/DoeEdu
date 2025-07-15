<!DOCTYPE html>
<html>
<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}
?>
<?php include("adminpartials/head.php"); ?>

<!-- Styles -->
<style>
  .dashline {
    border-top: 1px dashed #ccc;
    margin: 15px 0;
  }
  .modal-header {
    background-color: rgb(159, 176, 185);
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
  .notice.read {
    background-color: #d9edf7;
    text-decoration: line-through;
    opacity: 0.7;
  }
  .close-notice {
    float: right;
    padding: 6px 12px;
    color: white;
    background-color: #3c8dbc;
    border-radius: 3px;
    font-size: 12px;
    cursor: pointer;
  }
  .close-notice:hover {
    background-color: #367fa9;
  }
</style>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include("adminpartials/header.php"); ?>
  <?php include("adminpartials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Dashboard <small>Control panel</small></h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <section class="content">
      <?php include('../partials/connect.php');
      $sql = "SELECT COUNT(*) as count FROM learners";
      $result = $connect->query($sql);
      $row = $result->fetch_assoc(); ?>

      <!-- Stat Boxes -->
      <div class="row">
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
              <div class="inner"><h3>20</h3><p>Notifications</p></div>
              <a href="noticepage.php">
                <div class="icon"><i class="fa fa-bell-o"></i></div>
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
              <div class="inner"><h3>15</h3><p>New Message/s</p></div>
              <a href="mmailbox.php">
                <div class="icon"><i class="fa fa-envelope-o"></i></div>
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
              <div class="inner"><h3>30</h3><p>Reports</p></div>
              <a href="gradesreports.php">
                <div class="icon"><i class="fa fa-files-o"></i></div>
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
              <div class="inner"><h3><?php echo $row['count']; ?></h3><p>Learners Registered</p></div>
              <a href="classes.php">
                <div class="icon"><i class="ion ion-person"></i></div>
              </a>
            </div>
          </div>
      </div>

      <!-- Reserved Section (for deleted content) -->
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

<script>
  $(document).ready(function () {
    $('#myModal').modal('show');
  });

  function markAsRead(element) {
    const notice = element.closest('.notice');
    notice.classList.add('read');
  }
</script>

<!-- Notification Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <a class="close" data-dismiss="modal" aria-label="Close">&times;</a>
        <h3 class="modal-title" id="modalTitle">Notification Centre</h3>
        <?php if (isset($_SESSION['succes'])) {
          echo '<p>' . $_SESSION['succes'] . '</p>';
          unset($_SESSION['succes']);
        } ?>
      </div>
      <div class="modal-body">
        <?php
        $sql = "SELECT NoticeNo, Title, Content, Date, IsOpened FROM notices ORDER BY Date DESC";
        $results = $connect->query($sql);
        if ($results && $results->num_rows > 0):
          while ($notice = $results->fetch_assoc()): ?>
            <div class="notice <?= $notice['IsOpened'] ? 'read' : ''; ?>">
              <p>
                <strong style="color: blue;">Date:</strong> <?= date('Y-m-d', strtotime($notice['Date'])) ?>
                <a href="readnotice.php?id=<?= $notice['NoticeNo'] ?>" class="close-notice" onclick="markAsRead(this)">Mark Read</a>
              </p>
              <p><strong style="color: blue;">Subject:</strong> <strong style="color: black;"><?= htmlspecialchars($notice['Title']) ?></strong></p>
              <p><?= nl2br(htmlspecialchars($notice['Content'])) ?></p>
            </div>
            <hr class="dashline" />
          <?php endwhile;
        else: ?>
          <p>No notices available.</p>
        <?php endif; ?>
      </div>
      <div class="modal-footer">
        <button class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php include("adminpartials/queries.php"); ?>
<script src="dist/js/demo.js"></script>
</body>
</html>
