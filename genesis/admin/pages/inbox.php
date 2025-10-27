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

  <?php include_once(ADMIN_PATH . "/../partials/header.php"); ?>
 <?php include_once(ADMIN_PATH . "/../partials/mainsidebar.php"); ?>

  <!-- Content Wrapper. Contains page content --->
  <div class="content-wrapper">

    <section class="content-header">
          <h1>Conversation <small>Chat between two users</small></h1>
          <ol class="breadcrumb">
            <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Conversation</li>
          </ol>
    </section>

    <!-- Main content ---------------------------------------------> 
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Direct Messages</h3>
            </div>
            
            <div class="box-body">
              <!-- Chat UI -->
              <div class="chat-box" style="border:1px solid #ddd; border-radius:5px; display:flex; flex-direction:column; height:70vh;">
                
                <!-- Header -->
                <div class="chat-header" style="padding:10px; background:#3c8dbc; color:#fff; font-weight:bold; border-top-left-radius:5px; border-top-right-radius:5px;">
                  Conversation with Emmanuel B
                </div>

                <!-- Body -->
                <div class="chat-body" style="flex:1; padding:15px; overflow-y:auto; background:#f9fafc;">
                  
                  <!-- Received -->
                  <div style="margin-bottom:15px; display:flex;">
                    <div style="background:#e4e6eb; padding:10px 15px; border-radius:20px; border-bottom-left-radius:0; max-width:60%;">
                      Hey, how are you doing today?
                    </div>
                  </div>

                  <!-- Sent -->
                  <div style="margin-bottom:15px; display:flex; justify-content:flex-end;">
                    <div style="background:#3c8dbc; color:#fff; padding:10px 15px; border-radius:20px; border-bottom-right-radius:0; max-width:60%;">
                      I’m good, thanks! Working on the project right now.
                    </div>
                  </div>

                  <!-- Received -->
                  <div style="margin-bottom:15px; display:flex;">
                    <div style="background:#e4e6eb; padding:10px 15px; border-radius:20px; border-bottom-left-radius:0; max-width:60%;">
                      That’s great. Need any help?
                    </div>
                  </div>

                  <!-- Sent -->
                  <div style="margin-bottom:15px; display:flex; justify-content:flex-end;">
                    <div style="background:#3c8dbc; color:#fff; padding:10px 15px; border-radius:20px; border-bottom-right-radius:0; max-width:60%;">
                      Yes, maybe with the quiz feature later today.
                    </div>
                  </div>

                </div>

                <!-- Footer -->
                <div class="chat-footer" style="padding:10px; border-top:1px solid #ddd; display:flex; gap:10px;">
                  <input type="text" class="form-control" placeholder="Type your message...">
                  <button class="btn btn-primary"><i class="fa fa-paper-plane"></i></button>
                </div>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>

  </div>

  <div class="control-sidebar-bg"></div>
</div>

<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>

</body>
</html>
