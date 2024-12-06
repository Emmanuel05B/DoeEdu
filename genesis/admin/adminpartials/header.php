<?php
                include('../partials/connect.php');

                $userId = $_SESSION['user_id'];  //for looged in teacher


                $usql = "SELECT * FROM users WHERE Id = $userId" ;
                $Teacherresults = $connect->query($usql);
                $Teacherresultsfinal = $Teacherresults->fetch_assoc();  


                $sql = "SELECT COUNT(*) as count FROM pmessages WHERE IsOpened = 0";
                // Execute the query
                $result = $connect->query($sql);
                $messagesrow = $result->fetch_assoc();
               
    ?>

<header class="main-header">
    <!-- Logo -->
    <a href="adminindex.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>Click</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Genesis </b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success"><?php echo $messagesrow['count'];?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have <?php echo $messagesrow['count'];?> messages</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">

                <?php
                  
                  $sql = "SELECT * FROM pmessages WHERE IsOpened = 0";  //comeback for condition
                  $results = $connect->query($sql);
                  while($final = $results->fetch_assoc()) { ?>

                    <?php 
                    $currentTime = time();
                    $recievedtime = strtotime($final['CreatedAt']);
                    $timepast = $currentTime - $recievedtime;
                    
                    $inMinutes = floor($timepast /60);  //floor.. cuts the milliseconds
                    $inHours = floor($timepast /3660);
                    $inDays = floor($timepast /86400);
                    $inMonths = floor($timepast /(30 * 86400));


                    if ($timepast < 60) {
                        $_SESSION['elapsed'] = $timepast . ' seconds';
                    } elseif ($timepast < 3600) {
                        $_SESSION['elapsed'] = $inMinutes . ' minute/s'; 
                    } elseif ($timepast < 86400) {
                        $_SESSION['elapsed'] = $inHours . ' hour/s';
                    } elseif ($timepast < 2592000) {
                        $_SESSION['elapsed'] = $inDays . ' day/s'; 
                    } else {
                        $_SESSION['elapsed'] = $inMonths . ' month/s'; 
                    }
                    
                    ?>

                  <li><!-- start message -->
                    <a href="mread-mail.php?id=<?php echo $final['No'];?>">
                      <div class="pull-left">
                        <!-- <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">-->
                        <img class="img-circle" >
                      </div>
                      <h4>
                        <?php echo $final['SenderName'];?> <!--sender Name -->
                   
                        <?php
                        if (isset($_SESSION['elapsed'])) {
                            echo '<small><i class="fa fa-clock-o"></i>' . $_SESSION['elapsed'] . '</small>';
                            unset($_SESSION['elapsed']);
                        }
                        ?>
            

                      </h4>
                      <p><?php echo $final['Subject'];?></p>  <!--message/title -->
                    </a>
                  </li>
                  <!-- end message -->
                  <?php } ?>
                  
                </ul>
              </li>
              <li class="footer"><a href="#">See All Messages</a></li>
            </ul>
          </li>
          <!-- Notifications: style can be found in dropdown.less -->


          <?php

                $sql = "SELECT COUNT(*) as countnotices FROM notices";
                // Execute the query
                $result = $connect->query($sql);
                $noticesrow = $result->fetch_assoc();
               
          ?>



          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning"><?php echo $noticesrow['countnotices'];?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have <?php echo $noticesrow['countnotices'];?> notifications</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">

                <?php
                  
                  $sql = "SELECT * FROM notices WHERE IsOpened = 0";  //comeback for condition
                  $results = $connect->query($sql);
                  while($final = $results->fetch_assoc()) { ?>
         
                  <li>
                    <a href="#">
                      <i class="fa fa-user text-red"></i> <?php echo $final['Notice'];?>
                    </a>
                  </li>
                  <?php } ?>
                </ul>
              </li>
              <li class="footer"><a href="#">View all</a></li>
            </ul>
          </li>
          <!-- Tasks: style can be found in dropdown.less -->


          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="dist/img/avatar5.png" class="user-image" alt="User Image">
            
              <span class="hidden-xs"><?php echo $Teacherresultsfinal['Surname'] ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="dist/img/avatar5.png" class="img-circle" alt="User Image">

                <p>
                <?php echo $Teacherresultsfinal['Name'] ?> <?php echo $Teacherresultsfinal['Surname'] ?>
                  
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  
                  <div class="col-xs-4 text-center">
                    <a href="#">Collegues</a>
                  </div>
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="../common/logout.php" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark" style="display: none;">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-user bg-yellow"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                <p>New phone +1(800)555-1234</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                <p>nora@example.com</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-file-code-o bg-green"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                <p>Execution time 5 seconds</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="label label-danger pull-right">70%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Update Resume
                <span class="label label-success pull-right">95%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Laravel Integration
                <span class="label label-warning pull-right">50%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Back End Framework
                <span class="label label-primary pull-right">68%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Allow mail redirect
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Other sets of options are available
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Expose author name in posts
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Allow the user to show his name in blog posts
            </p>
          </div>
          <!-- /.form-group -->

          <h3 class="control-sidebar-heading">Chat Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Show me as online
              <input type="checkbox" class="pull-right" checked>
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Turn off notifications
              <input type="checkbox" class="pull-right">
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Delete chat history
              <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
            </label>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>