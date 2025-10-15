<!DOCTYPE html>
<html>
<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
  exit();
}
include(__DIR__ . "/../../common/partials/head.php");  //
include(__DIR__ . "/../../partials/connect.php");
?>

<!-- Modal Styles -->
<style>
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
  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  <div class="content-wrapper" style="background-color:#f7f9fc;">
    <section class="content-header">
      <h1 style="color:#4a6fa5;">
        Dashboard
        <small>Your tutor overview</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="tutordashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <section class="content">

      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <div class="small-box" style="background:#a3bffa; color:#000;">
            <div class="inner">
              <h3>34</h3>
              <p>My Uploads</p>
            </div>
            <div class="icon">
              <i class="fa fa-tasks"></i>
            </div>
            <a href="manageactivities.php" class="small-box-footer" style="color:#d4dbff;">
              Manage Uploads <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <div class="small-box" style="background:#3498db; color:#ffffff;">
            <div class="inner">
              <h3>5</h3>
              <p>Pending Session Requests</p>
            </div>
            <div class="icon">
              <i class="fa fa-clock-o"></i>
            </div>
            <a href="sessionrequests.php" class="small-box-footer" style="color:#d7cafb;">
              View Requests <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <div class="small-box" style="background:#9f86d1; color:#fff;">
            <div class="inner">
              <h3>120</h3>
              <p>My Learners</p>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
            <a href="classes1.php" class="small-box-footer" style="color:#d7cafb;">
              Visit Classes <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <div class="small-box" style="background:#556cd6; color:#fff;">
            <div class="inner">
              <h3>3</h3>
              <p>Reminders</p>
            </div>
            <div class="icon">
              <i class="fa fa-calendar"></i>
            </div>
            <a href="manageactivities.php" class="small-box-footer" style="color:#d7cafb;">
              View reminders <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
      </div>

      <div class="row">
        
        <div class="col-md-7">
          <div class="box box-solid box-primary">
            <div class="box-header" style="background-color:#a3bffa; color:#2e3c82;">
              <h3 class="box-title">Learner Progress Overview</h3>
            </div>
            <div class="box-body" style="background-color:#d1d9ff;">

            </div>
          </div>
        </div>

        <div class="col-md-5">
          <div class="box box-solid box-purple">
            <div class="box-header" style="background-color:#9f86d1; color:#fff;">
              <i class="fa fa-bell"></i>
              <h3 class="box-title">Notifications</h3>
            </div>
            <div class="box-body" style="background-color:#dcd7f7; max-height:280px; overflow-y:auto;">
              <ul class="timeline timeline-inverse" style="margin-bottom:0;">
                <li>
                  <i class="fa fa-info-circle bg-blue"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> 2 hours ago</span>
                    <h3 class="timeline-header"><a href="#">Director</a> posted a new announcement</h3>
                    <div class="timeline-body">
                      New study materials uploaded for Grade 11 Science.
                    </div>
                  </div>
                </li>
                <li>
                  <i class="fa fa-calendar bg-purple"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> Yesterday</span>
                    <h3 class="timeline-header"><a href="#">System</a> Reminder</h3>
                    <div class="timeline-body">
                      Upcoming maintenance scheduled for 28 June.
                    </div>
                  </div>
                </li>
                <li>
                  <i class="fa fa-users bg-green"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> 3 days ago</span>
                    <h3 class="timeline-header"><a href="#">Session Requests</a> pending approval</h3>
                    <div class="timeline-body">
                      You have 5 session requests awaiting your response.
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

    </section>
 
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<!-- Scripts -->
<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

<script>
  $(document).ready(function () {
    $('#myModal').modal('show');
  });

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
        include(__DIR__ . "/../../partials/connect.php");
        //$sql = "SELECT NoticeNo, Title, Content, Date, IsOpened FROM notices WHERE CreatedFor = 2 OR  CreatedFor = 12 ORDER BY Date DESC";
        //$results = $connect->query($sql);  same as below
        $sql = "SELECT NoticeNo, Title, Content, Date, IsOpened FROM notices WHERE CreatedFor IN (2, 12) 
        ORDER BY Date DESC
        LIMIT 30";
        $results = $connect->query($sql);
        if ($results && $results->num_rows > 0):
          while ($notice = $results->fetch_assoc()): ?>
            <div class="notice <?= $notice['IsOpened'] ? 'read' : ''; ?>">
              <p>
                <strong style="color: blue;">Date:</strong> <?= date('Y-m-d', strtotime($notice['Date'])) ?>
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


</body>
</html>
