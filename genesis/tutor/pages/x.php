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

<?php include_once(TUTOR_PATH . "/../partials/header.php"); ?> 
<?php include_once(TUTOR_PATH . "/../partials/mainsidebar.php"); ?>


  <div class="content-wrapper" style="background-color: #f7f9fc;">
    <section class="content-header">
      <h1 style="color:#4a6fa5;">
        Communications
        <small>Message learners & manage forums</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="tutor_dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Communications</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">

        <!-- Left panel: Message List -->
        <div class="col-md-4">
          <div class="box box-primary" style="background-color:#a3bffa; color:#fff;">
            <div class="box-header with-border">
              <h3 class="box-title">Messages</h3>
              <div class="box-tools">
                <button class="btn btn-xs btn-success" onclick="location.href='new_message.php'">
                  <i class="fa fa-plus"></i> New Message
                </button>
              </div>
            </div>
            <div class="box-body" style="background-color:#e8eeff; color:#333;">
              <ul class="nav nav-pills nav-stacked">
                <li><a href="#" class="active">Jane Dlamini <span class="label label-primary pull-right">3</span></a></li>
                <li><a href="#">Thabo Mokoena <span class="label label-default pull-right">0</span></a></li>
                <li><a href="#">Group: Grade 10 Maths <span class="label label-primary pull-right">5</span></a></li>
                <!-- Dynamic list -->
              </ul>
            </div>
          </div>
        </div>

        <!-- Right panel: Message Content -->
        <div class="col-md-8">
          <div class="box box-info">
            <div class="box-header with-border" style="background-color:#9f86d1; color:#fff;">
              <h3 class="box-title">Conversation with Jane Dlamini</h3>
            </div>
            <div class="box-body" style="background-color:#dcd7f7; height:400px; overflow-y:auto;">
              <!-- Sample messages -->
              <div><strong>Jane Dlamini</strong> <small class="text-muted">10:05 AM</small></div>
              <div>Hello, I need help with last week's homework.</div>
              <hr>
              <div><strong>You</strong> <small class="text-muted">10:07 AM</small></div>
              <div>Sure! Let's schedule a session.</div>
              <!-- More chat messages -->
            </div>
            <div class="box-footer">
              <form>
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="Type your message...">
                  <span class="input-group-btn">
                    <button type="submit" class="btn btn-primary" style="background-color:#556cd6; border:none;">Send</button>
                  </span>
                </div>
              </form>
            </div>
          </div>

          <!-- Discussion Forums -->
          <div class="box box-warning">
            <div class="box-header with-border" style="background-color:#f7e6ff; color:#6a42b6;">
              <h3 class="box-title">Discussion Forums</h3>
            </div>
            <div class="box-body" style="background-color:#f9f1ff;">
              <ul class="list-group">
                <li class="list-group-item">
                  <a href="#">Grade 10 Maths - Quadratic Equations</a>
                  <span class="badge bg-purple">12 posts</span>
                </li>
                <li class="list-group-item">
                  <a href="#">Exam Preparation Tips</a>
                  <span class="badge bg-purple">7 posts</span>
                </li>
                <!-- More forums -->
              </ul>
            </div>
          </div>

        </div>

      </div>
    </section>
  </div>

</div>

<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>

</body>
</html>
